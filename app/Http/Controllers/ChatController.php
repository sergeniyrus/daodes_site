<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // Список чатов
    public function index()
    {
        $groupChats = Chat::where('type', 'group')->paginate(10);
        $privateChats = Chat::where('type', 'personal')->paginate(10);
        return view('chats.index', compact('groupChats', 'privateChats'));
    }

    // Страница создания чата
    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('chats.create', compact('users'));
    }

    // Сохранение нового чата
    public function store(Request $request)
    {
        $type = count($request->users) === 1 ? 'personal' : 'group';

        $rules = [
            'users' => 'required|array',
        ];

        if ($type === 'group') {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Chat::whereRaw('LOWER(name) = LOWER(?)', [$value])->exists();
                    if ($exists) {
                        $fail('Чат с таким названием уже существует.');
                    }
                },
            ];
        } else {
            $rules['name'] = 'nullable';
        }

        $request->validate($rules, [
            'name.unique' => 'Чат с таким названием уже существует.',
            'name.required' => 'Название чата обязательно для групповых чатов.',
            'users.required' => 'Выберите хотя бы одного участника.',
        ]);

        if ($type === 'personal') {
            $otherUserId = $request->users[0];
            $existingChat = Chat::whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', auth()->id());
            })->whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
                ->withCount('users')
                ->having('users_count', '=', 2)
                ->first();

            if ($existingChat) {
                return redirect()->route('chats.show', $existingChat->id)
                    ->with('info', 'Чат с этим пользователем уже существует.');
            }
        }

        $chat = Chat::create([
            'name' => $type === 'personal' ? 'personal' : $request->name,
            'type' => $type,
        ]);

        $chat->users()->attach(array_merge($request->users, [auth()->id()]));

        return redirect()->route('chats.show', $chat->id)
            ->with('success', 'Чат успешно создан!');
    }

    // Страница чата
    public function show($chatId)
    {
        $chat = Chat::with(['users', 'messages.sender'])->findOrFail($chatId);
        $userId = auth()->id();

        Notification::whereHas('message', function ($query) use ($chatId) {
            $query->where('chat_id', $chatId);
        })
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chats.show', compact('chat'));
    }

    // Отправка сообщения
    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string|max:1000|regex:/^[\p{L}\p{N}\s.,!?-]+$/u',
        ]);

        try {
            // Загружаем сообщение в IPFS
            $messageModel = new Message();
            $cid = $messageModel->uploadMessageToIPFS($request->input('message'));

            // Сохраняем сообщение в БД
            $message = Message::create([
                'chat_id' => $chatId,
                'sender_id' => auth()->id(),
                'message' => null, // Основное сообщение не сохраняем в БД
                'ipfs_cid' => $cid, // Сохраняем CID из IPFS
            ]);

            // Создаем уведомления для всех участников чата (кроме отправителя)
            $chat = Chat::findOrFail($chatId);
            foreach ($chat->users as $user) {
                if ($user->id !== auth()->id()) {
                    Notification::create([
                        'user_id' => $user->id,
                        'message_id' => $message->id,
                        'is_read' => false,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Сообщение отправлено!');
        } catch (\Exception $e) {
            Log::error('Ошибка при отправке сообщения: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка при отправке сообщения.');
        }
    }
}