<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\EncryptionService;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    // Список чатов
    public function index()
    {
        // Получаем групповые чаты с пагинацией
        $groupChats = Chat::where('type', 'group')->paginate(10);

        // Получаем личные чаты с пагинацией
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
        // Определяем тип чата: personal (на двоих) или group
        $type = count($request->users) === 1 ? 'personal' : 'group';

        // Правила валидации
        $rules = [
            'users' => 'required|array',
        ];

        // Для групповых чатов добавляем проверку на уникальность названия (игнорируя регистр)
        if ($type === 'group') {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Проверяем уникальность названия, игнорируя регистр
                    $exists = Chat::whereRaw('LOWER(name) = LOWER(?)', [$value])->exists();
                    if ($exists) {
                        $fail(__('chats.chat_name_exists'));
                    }
                },
            ];
        } else {
            $rules['name'] = 'nullable'; // Для чатов на двоих название не требуется
        }

        // Валидация запроса с кастомными сообщениями
        $request->validate($rules, [
            'name.unique' => __('chats.chat_name_exists'),
            'name.required' => __('chats.chat_name_required'),
            'users.required' => __('chats.users_required'),
        ]);

        // Если это чат на двоих, проверяем, существует ли уже такой чат
        if ($type === 'personal') {
            $otherUserId = $request->users[0]; // ID второго пользователя

            // Проверяем, существует ли уже чат между текущим пользователем и выбранным пользователем
            $existingChat = Chat::whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', auth()->id());
            })->whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->withCount('users') // Добавляем подсчет количества пользователей в чате
            ->having('users_count', '=', 2) // Фильтруем только чаты с двумя пользователями
            ->first();

            // Если чат уже существует, перенаправляем на него
            if ($existingChat) {
                return redirect()->route('chats.show', $existingChat->id)
                    ->with('info', __('chats.chat_exists'));
            }
        }

        // Создаем новый чат
        $chat = Chat::create([
            'name' => $type === 'personal' ? 'personal' : $request->name, // Для чатов на двоих название не нужно
            'type' => $type, // Указываем тип чата
        ]);

        // Добавляем участников
        $chat->users()->attach(array_merge($request->users, [auth()->id()]));

        // Перенаправляем на страницу чата
        return redirect()->route('chats.show', $chat->id)
            ->with('success', __('chats.chat_created'));
    }

    // Страница чата
    public function show($chatId)
{
    // Находим чат
    $chat = Chat::with(['users', 'messages.sender'])->findOrFail($chatId);

    // Получаем ID текущего пользователя
    $userId = auth()->id();

    // Помечаем все уведомления из этого чата как прочитанные
    Notification::whereHas('message', function ($query) use ($chatId) {
        $query->where('chat_id', $chatId); // Фильтруем уведомления по ID чата
    })
    ->where('user_id', $userId) // Фильтруем уведомления для текущего пользователя
    ->where('is_read', false) // Фильтруем только непрочитанные уведомления
    ->update(['is_read' => true]); // Помечаем как прочитанные

    // Возвращаем представление с сообщением
    return view('chats.show', compact('chat'))->with('message', __('chats.message_sent'));
}



    // Отправка сообщения
    public function sendMessage(Request $request, $chatId)
{
    // Валидация входящего сообщения
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

        // Возвращаем пользователя обратно с сообщением об успехе
        return redirect()->back()->with('message', __('chats.message_sent'));
    } catch (\Exception $e) {
        // Логируем ошибку
        Log::error('Ошибка при отправке сообщения: ' . $e->getMessage());

        // Возвращаем пользователя обратно с сообщением об ошибке
        return redirect()->back()->with('error', __('chats.message_send_error'));
    }
}
    
        // Вспомогательный метод для загрузки сообщения в IPFS
        private function uploadMessage($message)
    {
        Log::info('Uploading message to IPFS', ['messageLength' => strlen($message)]);
    
        try {
            // Шифруем сообщение
            $encryptionService = new EncryptionService();
            $encryptedMessage = $encryptionService->encrypt($message);
    
            // Логируем зашифрованные данные
            // Log::info('Зашифрованные данные', [
            //     'encryptedMessageLength' => strlen($encryptedMessage),
            //     'encryptedMessageSample' => substr($encryptedMessage, 0, 50), // Логируем первые 50 символов
            // ]);
    
            // Инициализация клиента Guzzle для работы с IPFS
            $client = new Client([
                'base_uri' => 'https://daodes.space'
            ]);
    
            // Отправка сообщения на IPFS
            $response = $client->request('POST', '/api/v0/add', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => $encryptedMessage,
                        'filename' => 'message.txt', // Имя файла для IPFS
                    ]
                ]
            ]);
    
            // Парсим ответ и извлекаем CID
            $data = json_decode($response->getBody(), true);
    
            if (isset($data['Hash'])) {
                $cid = $data['Hash'];
               // Log::info('Message uploaded to IPFS successfully', ['cid' => $cid]);
                return $cid;
            } else {
              //  Log::error('IPFS error: No Hash in response', ['response' => $data]);
                throw new \Exception('No valid response from IPFS');
            }
        } catch (\Exception $e) {
           // Log::error('IPFS upload error: ' . $e->getMessage());
            throw $e; // Пробрасываем исключение, чтобы обработать его в sendMessage
        }
    }
    // Уведомления
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

        return view('chats.notifications', compact('uniqueChats'));
    }

    // Пометка уведомления как прочитанного
    public function markAsRead($notificationId)
    {
        // Находим уведомление текущего пользователя
        $notification = Auth::user()->notifications()->findOrFail($notificationId);

        // Помечаем уведомление как прочитанное
        $notification->update(['is_read' => true]);

        // Перенаправляем пользователя обратно
        return redirect()->back()->with('success', __('chats.notification_read'));
    }

    // Создание чата с пользователем
    public function createWithUser($userId)
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
        $chat->users()->attach([auth()->id(), $userId]);

        // Перенаправляем на страницу чата
        return redirect()->route('chats.show', $chat->id);
    }

    // Создание или открытие чата с пользователем
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
            $chat->users()->attach([auth()->id(), $userId]);
        }

        // Перенаправляем на страницу чата
        return redirect()->route('chats.show', $chat->id);
    }
}
