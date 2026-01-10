<?php // app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatMemberKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $groupChats = Chat::where('type', 'group')
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->paginate(10);

        $privateChats = Chat::where('type', 'personal')
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->paginate(10);

        $uniqueChats = $this->getUnreadMessagesCount($groupChats->merge($privateChats));

        return view('chats.index', compact('groupChats', 'privateChats', 'uniqueChats'));
    }

    private function getUnreadMessagesCount($chats)
    {
        $userId = Auth::id();
        $counts = [];

        foreach ($chats as $chat) {
            if ($chat->users->contains($userId)) {
                $unread = $chat->messages()
                    ->whereHas('notifications', fn($q) => $q->where('user_id', $userId)->where('is_read', false))
                    ->count();
                $counts[$chat->id] = $unread;
            }
        }

        return $counts;
    }

    public function create()
    {
        $currentUser = Auth::user();

        $users = User::with('profile')
            ->where('id', '!=', $currentUser->id)
            ->whereNotIn('id', [1, 2])
            ->get()
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->profile?->avatar_url ?? '/img/main/default-avatar.png',
            ]);

        return view('chats.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'chat_type' => 'required|string|in:group,personal',
            'name'      => 'nullable|string|max:255',
            'users'     => 'nullable|string',
            'encrypted_keys' => 'required|array',
            'encrypted_keys.*.encrypted_key' => 'required|string',
            'encrypted_keys.*.nonce' => 'required|string',
        ]);

        $userIds = array_map('intval', json_decode($validated['users'] ?? '[]', true) ?: []);
        $initiatorId = Auth::id();
        if (!in_array($initiatorId, $userIds)) {
            $userIds[] = $initiatorId;
        }

        $chat = Chat::create([
            'name' => $request->chat_type === 'personal' ? null : $request->name,
            'type' => $request->chat_type,
            'initiator_id' => $initiatorId,
        ]);

        $chat->users()->attach(array_unique($userIds));

        foreach ($validated['encrypted_keys'] as $uid => $data) {
            ChatMemberKey::create([
                'chat_id' => $chat->id,
                'user_id' => $uid,
                'encrypted_key' => $data['encrypted_key'],
                'nonce' => $data['nonce'],
            ]);
        }

        return redirect()->route('chats.show', $chat->id);
    }

    public function show($chatId)
    {
        $chat = Chat::with(['users', 'messages.sender'])->findOrFail($chatId);
        $userId = Auth::id();
        $otherUser = $chat->type === 'personal'
            ? $chat->users->first(fn($u) => $u->id !== $userId)
            : null;

            // Подгружаем payload для всех сообщений
    foreach ($chat->messages as $message) {
        $message->setAttribute('full_payload', $message->getMessageFromIPFS($message->ipfs_cid));
    }

        // Удаляем уведомления
        \App\Models\Notification::whereHas('message', fn($q) => $q->where('chat_id', $chatId))
            ->where('user_id', $userId)
            ->delete();

        return view('chats.show', compact('chat', 'otherUser'));
    }

    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->where('is_read', false)
            ->with('message.chat')
            ->get();

        $grouped = $notifications->groupBy(fn($n) => $n->message?->chat_id);
        $uniqueChats = collect();

        foreach ($grouped as $chatId => $group) {
            if ($chatId && $chat = $group->first()?->message?->chat) {
                $unreadCount = $chat->messages()
                    ->whereHas('notifications', fn($q) => $q->where('user_id', $user->id)->where('is_read', false))
                    ->count();
                $uniqueChats->push(['chat' => $chat, 'unread_count' => $unreadCount]);
            }
        }

        return view('chats.notifications', compact('uniqueChats'));
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->delete();
        return redirect()->back();
    }

    public function createWithUser($userId)
    {
        $otherUser = User::findOrFail($userId);

        $chat = Chat::whereHas('users', fn($q) => $q->where('user_id', Auth::id()))
            ->whereHas('users', fn($q) => $q->where('user_id', $userId))
            ->withCount('users')
            ->having('users_count', 2)
            ->first();

        if ($chat) {
            return redirect()->route('chats.show', $chat->id);
        }

        $chat = Chat::create(['type' => 'personal']);
        $chat->users()->attach([Auth::id(), $userId]);
        return redirect()->route('chats.show', $chat->id);
    }

    public function myKey($chatId)
{
    $userId = auth()->id();

    // Находим запись ключа участника
    $record = \DB::table('chat_member_keys')
        ->where('chat_id', $chatId)
        ->where('user_id', $userId)
        ->first();

    if (!$record) {
        return response()->json(['error' => 'No key found'], 404);
    }

    // Получаем публичный ключ ИНИЦИАТОРА чата
    $chat = Chat::with('initiator.profile')->findOrFail($chatId);
    $initiatorPublicKey = $chat->initiator?->profile?->public_key;

    if (!$initiatorPublicKey) {
        Log::error("❌ Публичный ключ инициатора отсутствует", [
            'chat_id' => $chatId,
            'initiator_id' => $chat->initiator_id
        ]);
        return response()->json(['error' => 'Initiator public key missing'], 500);
    }

    return response()->json([
        'encrypted_key' => $record->encrypted_key,
        'nonce' => $record->nonce,
        'initiator_public_key' => $initiatorPublicKey, // ← берём из профиля
    ]);
}
}