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
use App\Events\NewMessage;

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
     * Получает список пользователей, исключая текущего.
     */
    public function create()
    {
        // Получаем всех пользователей, кроме текущего
        $users = User::where('id', '!=', Auth::user()->id)->get();

        // Возвращаем представление для создания чата
        return view('chats.create', compact('users'));
    }

    /**
     * Сохраняет новый чат в базе данных.
     * Определяет тип чата (личный или групповой) и создает его.
     */
    public function store(Request $request)
    {
        // Логируем входные данные
        // Log::info('Request data:', $request->all());
        // Log::info('Users array:', $request->users);

        // Определяем тип чата: personal (на двоих) или group
        $type = count($request->users) >= 2 ? 'group' : 'personal';
        // Log::info('Chat type determined:', ['type' => $type]);

        // Правила валидации
        $rules = [
            'users' => 'required|array',
        ];

        // Для групповых чатов добавляем проверку на уникальность названия
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

        // Логируем правила валидации
        // Log::info('Validation rules:', $rules);

        // Валидация запроса с кастомными сообщениями
        $request->validate($rules, [
            'name.unique' => __('chats.chat_name_exists'),
            'name.required' => __('chats.chat_name_required'),
            'users.required' => __('chats.users_required'),
        ]);

        // Если это чат на двоих, проверяем, существует ли уже такой чат
        if ($type === 'personal') {
            $otherUserId = $request->users[0]; // ID второго пользователя
            // Log::info('Checking for existing personal chat with user:', ['otherUserId' => $otherUserId]);

            // Проверяем, существует ли уже чат между текущим пользователем и выбранным пользователем
            $existingChat = Chat::whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', Auth::user()->id);
            })->whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->withCount('users') // Добавляем подсчет количества пользователей в чате
            ->having('users_count', '=', 2) // Фильтруем только чаты с двумя пользователями
            ->first();

            // Если чат уже существует, перенаправляем на него
            if ($existingChat) {
                // Log::info('Existing chat found:', ['chatId' => $existingChat->id]);
                return redirect()->route('chats.show', $existingChat->id)
                    ->with('info', __('chats.chat_exists'));
            }
        }

        // Создаем новый чат
        $chat = Chat::create([
            'name' => $type === 'personal' ? 'personal' : $request->name, // Для чатов на двоих название не нужно
            'type' => $type, // Указываем тип чата
        ]);
        // Log::info('New chat created:', ['chatId' => $chat->id, 'name' => $chat->name, 'type' => $chat->type]);

        // Добавляем участников
        $usersToAttach = array_merge($request->users, [Auth::user()->id]);
        //Log::info('Attaching users to chat:', ['users' => $usersToAttach]);
        $chat->users()->attach($usersToAttach);

        // Перенаправляем на страницу чата
        return redirect()->route('chats.index')
            ->with('message', __('chats.chat_created'));
    }

    /**
     * Отображает страницу чата.
     * Помечает все уведомления из этого чата как прочитанные.
     */
    public function show($chatId)
    {
        // Находим чат
        $chat = Chat::with(['users', 'messages.sender'])->findOrFail($chatId);

        // Получаем ID текущего пользователя
        $userId = Auth::user()->id;

       // Удаляем все уведомления из этого чата для текущего пользователя
    Notification::whereHas('message', function ($query) use ($chatId) {
        $query->where('chat_id', $chatId); // Фильтруем уведомления по ID чата
    })
    ->where('user_id', $userId) // Фильтруем уведомления для текущего пользователя
    ->delete(); // Удаляем уведомления

        // Возвращаем представление с сообщением
        return view('chats.show', compact('chat'))->with('message', __('chats.notification_read'));
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
   public function sendMessage(Request $request, $chatId)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    try {
        $messageText = $request->input('message');
        
        // Шифруем и загружаем в IPFS
        $messageModel = new Message();
        $cid = $messageModel->uploadMessageToIPFS($messageText);

        // Сохраняем сообщение
        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'ipfs_cid' => $cid,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $message->id,
                'text' => $messageText,
                'sender' => Auth::user()->name,
                'created_at' => now()->toDateTimeString()
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Ошибка отправки'
        ], 500);
    }
}
    
    /**
     * Вспомогательный метод для загрузки сообщения в IPFS.
     */
    private function uploadMessage($message) {
        // Log::info('Uploading message to IPFS', ['messageLength' => strlen($message)]);

        try {
            // Шифруем сообщение
            $encryptionService = new EncryptionService();
            $encryptedMessage = $encryptionService->encrypt($message);

            // Проверяем длину зашифрованного сообщения
            if (strlen($encryptedMessage) < 16) {
                throw new \Exception('Зашифрованные данные слишком короткие.');
            }

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
                return $cid;
            } else {
                throw new \Exception('No valid response from IPFS');
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке сообщения в IPFS: ' . $e->getMessage());
            throw $e; // Пробрасываем исключение, чтобы обработать его в sendMessage
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
}
