<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Events\NewMessage;
use App\Models\ChatMemberKey;

class ChatController extends Controller
{
    /**
     * Отображает список чатов.
     * Групповые и личные чаты отображаются с пагинацией.
     */
    public function index()
    {
        // Получаем текущего аутентифицированного пользователя
        $user = Auth::user();

        // Получаем групповые чаты с пагинацией и фильтруем по участию пользователя
        $groupChats = Chat::where('type', 'group')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->paginate(10);

        // Получаем личные чаты с пагинацией и фильтруем по участию пользователя
        $privateChats = Chat::where('type', 'personal')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->paginate(10);

        // Получаем количество непрочитанных сообщений для каждого чата
        $uniqueChats = $this->getUnreadMessagesCount($groupChats->merge($privateChats));

        // Возвращаем представление со списком чатов
        return view('chats.index', compact('groupChats', 'privateChats', 'uniqueChats'));
    }

    /**
     * Получает количество непрочитанных сообщений для каждого чата.
     */
    private function getUnreadMessagesCount($chats)
    {
        $userId = Auth::user()->id;
        $uniqueChats = [];

        foreach ($chats as $chat) {
            // Проверяем, участвует ли текущий пользователь в чате
            if ($chat->users->contains($userId)) {
                $unreadCount = $chat->messages()
                    ->whereHas('notifications', function ($query) use ($userId) {
                        $query->where('user_id', $userId)->where('is_read', false);
                    })
                    ->count();

                $uniqueChats[$chat->id] = $unreadCount;
            }
        }

        return $uniqueChats;
    }

    /**
     * Отображает страницу создания чата.
     * Получает список пользователей, исключая текущего и системных (ID=1,2),
     * и подгружает аватарки из user_profiles.
     */
    public function create()
    {
        $currentUser = Auth::user();

        $users = User::with('profile')
            ->where('id', '!=', $currentUser->id)
            ->whereNotIn('id', [1, 2])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->profile?->avatar_url ?? '/img/main/default-avatar.png',
                ];
            });

        return view('chats.create', compact('users'));
    }

    /**
     * Сохраняет новый чат в базе данных.
     * Определяет тип чата (личный или групповой) и создает его.
     */


    public function store(Request $request)
    {
        Log::info("STORE REQUEST RAW:", $request->all());

        $validated = $request->validate([
            'chat_type' => 'required|string|in:group,personal',
            'name'      => 'nullable|string|max:255',
            'users'     => 'nullable|string',
            'encrypted_keys' => 'required|array',
            'encrypted_keys.*.encrypted_key' => 'required|string',
            'encrypted_keys.*.nonce' => 'required|string',
        ]);

        $userIds = json_decode($validated['users'] ?? '[]', true);
        if (!is_array($userIds)) $userIds = [];
        $userIds = array_map('intval', $userIds);

        $initiatorId = auth()->id();
        if (!in_array($initiatorId, $userIds)) {
            $userIds[] = $initiatorId;
        }

        Log::info("STORE PARSED users:", $userIds);

        // ===== СОЗДАЁМ ЧАТ =====
        $chat = Chat::create([
            'name'         => $request->chat_type === 'direct' ? null : $request->name,
            'type'         => $request->chat_type,
            'initiator_id' => $initiatorId,
        ]);

        Log::info("STORE Chat created:", ['chat_id' => $chat->id]);

        // ===== СОХРАНЯЕМ УЧАСТНИКОВ ЧЕРЕЗ attach() =====
        $chat->users()->attach(array_unique($userIds));

        Log::info("STORE Chat members added");

        // ===== СОХРАНЯЕМ encrypted_keys =====
        $ekeys = $validated['encrypted_keys'];

        foreach ($ekeys as $uid => $data) {
            Log::info("STORE Key for UID=$uid", $data);

            ChatMemberKey::create([
                'chat_id'       => $chat->id,
                'user_id'       => $uid,
                'encrypted_key' => $data['encrypted_key'],
                'nonce'         => $data['nonce'],
            ]);
        }

        Log::info("STORE OK: CHAT CREATED");

        return redirect()->route('chats.show', $chat->id);
    }


    /**
     * Отображает страницу чата.
     * Удаляет все уведомления из этого чата для текущего пользователя.
     */
    public function show($chatId)
    {
        try {
            // Находим чат с участниками и сообщениями
            $chat = Chat::with(['users', 'messages.sender'])->findOrFail($chatId);

            $userId = Auth::user()->id;
            $otherUser = null; // ← Инициализируем явно!

            // Определяем собеседника — только для личного чата
            if ($chat->type === 'personal') {
                $otherUser = $chat->users->first(fn($user) => $user->id !== $userId);
            }

            // Удаляем уведомления текущего пользователя в этом чате
            Notification::whereHas('message', function ($query) use ($chatId) {
                $query->where('chat_id', $chatId);
            })
                ->where('user_id', $userId)
                ->delete();

            // Передаём обе переменные — $chat и $otherUser (может быть null)
            return view('chats.show', compact('chat', 'otherUser'));
        } catch (\Exception $e) {
            Log::error("Error in ChatController@show: " . $e->getMessage());
            return redirect()->back()->with('error', __('chats.error_loading_chat'));
        }
    }

    public function getMessages(Chat $chat)
    {
        $lastId = request('last_id', 0);

        return $chat->messages()
            ->where('id', '>', $lastId)
            ->with('sender')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Отправляет сообщение в чат.
     * Сохраняет сообщение в IPFS и создает уведомления для участников чата.
     */
    /**
     * Отправляет сообщение в чат.
     * Сохраняет сообщение в IPFS и создает уведомления для участников чата.
     */
    public function sendMessage(Request $request, $chatId)
{
    $request->validate([
        'message' => 'required|string', // base64 зашифрованного текста
        'nonce'   => 'required|string', // base64 nonce
    ]);

    try {
        // Формат: nonce|ciphertext
        $encryptedPayload = $request->nonce . '|' . $request->message;

        $messageModel = new Message();
        $cid = $messageModel->uploadMessageToIPFS($encryptedPayload);

        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'ipfs_cid' => $cid,
        ]);

        $recipientIds = Chat::findOrFail($chatId)
            ->users()
            ->where('user_id', '!=', Auth::id())
            ->pluck('user_id');

        if ($recipientIds->isNotEmpty()) {
            $notifications = [];
            foreach ($recipientIds as $recipientId) {
                $notifications[] = [
                    'user_id' => $recipientId,
                    'message_id' => $message->id,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            Notification::insert($notifications);
        }

        // Telegram-уведомления (без текста!)
        $chat = Chat::with('users')->findOrFail($chatId);
        $sender = Auth::user();
        $baseUrl = config('app.url');
        $chatUrl = "{$baseUrl}/chats/{$chat->id}";

        foreach ($recipientIds as $recipientId) {
            $isPersonal = $chat->type === 'personal';
            $payload = [
                'is_personal' => $isPersonal,
                'sender_login' => $sender->name,
                'chat_name' => $isPersonal ? 'личный чат' : $chat->name,
                'chat_url' => $chatUrl,
            ];
            \App\Services\TelegramNotifier::notifyNewMessage($recipientId, $payload);
        }

        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $message->id,
                'sender' => Auth::user()->name,
                'created_at' => now()->toDateTimeString()
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Ошибка отправки сообщения: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Ошибка'], 500);
    }
}

    /**
     * Возвращает список непрочитанных уведомлений для текущего пользователя.
     */
    public function notifications()
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Загружаем только непрочитанные уведомления
        $notifications = $user->notifications()
            ->where('is_read', false)
            ->with('message.chat') // Загружаем связанные сообщения и чаты
            ->get();

        // Группируем уведомления по чатам
        $groupedNotifications = $notifications->groupBy(function ($notification) {
            return $notification->message ? $notification->message->chat_id : null;
        });

        // Создаем коллекцию для уникальных чатов
        $uniqueChats = collect();

        foreach ($groupedNotifications as $chatId => $notificationsGroup) {
            if ($chatId) {
                $chat = $notificationsGroup->first()->message->chat;

                // Подсчитываем общее количество непрочитанных сообщений в чате
                $unreadCount = $chat->unreadMessagesCount($user->id);

                // Добавляем чат в коллекцию с общим количеством непрочитанных сообщений
                $uniqueChats->push([
                    'chat' => $chat,
                    'unread_count' => $unreadCount,
                ]);
            }
        }

        // Возвращаем представление с уникальными чатами
        return view('chats.notifications', compact('uniqueChats'));
    }

    /**
     * Помечает уведомление как прочитанное.
     */
    public function markAsRead($notificationId)
    {
        // Находим уведомление текущего пользователя
        $notification = Auth::user()->notifications()->findOrFail($notificationId);

        // Удаляем уведомление из базы данных
        $notification->deleteNotification();

        // Перенаправляем пользователя обратно
        return redirect()->back();
    }

    /**
     * Создает или открывает чат с указанным пользователем.
     */
    public function createWithUser($userId)
    {
        // Находим пользователя, с которым создаем/открываем чат
        $otherUser = User::findOrFail($userId);

        // Проверяем, существует ли уже чат между текущим пользователем и выбранным пользователем
        $chat = Chat::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', Auth::user()->id);
        })->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->withCount('users') // Добавляем подсчет количества пользователей в чате
            ->having('users_count', '=', 2) // Фильтруем только чаты с двумя пользователями
            ->first();

        // Если чат уже существует, перенаправляем на него
        if ($chat) {
            return redirect()->route('chats.show', $chat->id);
        }

        // Если чат не существует, создаем его
        $chat = Chat::create([
            'name' => 'personal', // Для чатов на двоих название не нужно
            'type' => 'personal', // Указываем тип чата
        ]);

        // Добавляем текущего пользователя и выбранного пользователя в чат
        $chat->users()->attach([Auth::id(), $userId]);
        // Перенаправляем на страницу чата
        return redirect()->route('chats.show', $chat->id);
    }

    /**
     * Создает или открывает чат с указанным пользователем.
     */
    public function createOrOpen($userId)
    {
        // Находим пользователя, с которым создаем/открываем чат
        $otherUser = User::findOrFail($userId);

        // Проверяем, существует ли уже чат между текущим пользователем и выбранным пользователем
        $chat = Chat::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', auth()->id());
        })->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->withCount('users') // Добавляем подсчет количества пользователей в чате
            ->having('users_count', '=', 2) // Фильтруем только чаты с двумя пользователями
            ->first();

        // Если чат не существует, создаем его
        if (!$chat) {
            $chat = Chat::create([
                'name' => 'personal', // Для чатов на двоих название не нужно
                'type' => 'personal', // Указываем тип чата
            ]);
            $chat->users()->attach([Auth::id(), $userId]);
        }

        // Перенаправляем на страницу чата
        return redirect()->route('chats.show', $chat->id);
    }

    public function myKey($chatId)
    {
        $userId = auth()->id();

        $record = \DB::table('chat_member_keys')
            ->where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->first();

        if (!$record) {
            \Log::error("❌ my-key: нет ключа chat_id=$chatId user_id=$userId");
            return response()->json(['error' => 'No key found'], 400);
        }

        return response()->json([
            'encrypted_key'        => $record->encrypted_key,
            'nonce'                => $record->nonce,
            'initiator_public_key' => $record->initiator_public_key,
        ]);
    }
}
