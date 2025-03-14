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
    /**
     * Отображает список чатов.
     * Групповые и личные чаты отображаются с пагинацией.
     */
    public function index()
    {
        // Получаем групповые чаты с пагинацией
        $groupChats = Chat::where('type', 'group')->paginate(10);

        // Получаем личные чаты с пагинацией
        $privateChats = Chat::where('type', 'personal')->paginate(10);

        // Возвращаем представление со списком чатов
        return view('chats.index', compact('groupChats', 'privateChats'));
    }

    /**
     * Отображает страницу создания чата.
     * Получает список пользователей, исключая текущего.
     */
    public function create()
    {
        // Получаем всех пользователей, кроме текущего
        $users = User::where('id', '!=', auth()->id())->get();

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
    Log::info('Request data:', $request->all());
    Log::info('Users array:', $request->users);

    // Определяем тип чата: personal (на двоих) или group
    $type = count($request->users) >= 3 ? 'group' : 'personal';
    Log::info('Chat type determined:', ['type' => $type]);

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
    Log::info('Validation rules:', $rules);

    // Валидация запроса с кастомными сообщениями
    $request->validate($rules, [
        'name.unique' => __('chats.chat_name_exists'),
        'name.required' => __('chats.chat_name_required'),
        'users.required' => __('chats.users_required'),
    ]);

    // Если это чат на двоих, проверяем, существует ли уже такой чат
    if ($type === 'personal') {
        $otherUserId = $request->users[0]; // ID второго пользователя
        Log::info('Checking for existing personal chat with user:', ['otherUserId' => $otherUserId]);

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
            Log::info('Existing chat found:', ['chatId' => $existingChat->id]);
            return redirect()->route('chats.show', $existingChat->id)
                ->with('info', __('chats.chat_exists'));
        }
    }

    // Создаем новый чат
    $chat = Chat::create([
        'name' => $type === 'personal' ? 'personal' : $request->name, // Для чатов на двоих название не нужно
        'type' => $type, // Указываем тип чата
    ]);
    Log::info('New chat created:', ['chatId' => $chat->id, 'name' => $chat->name, 'type' => $chat->type]);

    // Добавляем участников
    $usersToAttach = array_merge($request->users, [auth()->id()]);
    //Log::info('Attaching users to chat:', ['users' => $usersToAttach]);
    $chat->users()->attach($usersToAttach);

    // Перенаправляем на страницу чата
    return redirect()->route('chats.index')
        ->with('success', __('chats.chat_created'));
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
        $userId = auth()->id();

        // Помечаем все уведомления из этого чата как прочитанные
        Notification::whereHas('message', function ($query) use ($chatId) {
            $query->where('chat_id', $chatId); // Фильтруем уведомления по ID чата
        })
        ->where('user_id', $userId) // Фильтруем уведомления для текущего пользователя
        ->update(['is_read' => true]); // Помечаем как прочитанные

        // Возвращаем представление с сообщением
        return view('chats.show', compact('chat'))->with('success', __('chats.notification_read'));
    }

    /**
     * Отправляет сообщение в чат.
     * Сохраняет сообщение в IPFS и создает уведомления для участников чата.
     */
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
               // 'message' => null,  Основное сообщение не сохраняем в БД
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
            return redirect()->back()->with('success', __('chats.message_sent'));
        } catch (\Exception $e) {
            // Логируем ошибку
            Log::error('Ошибка при отправке сообщения: ' . $e->getMessage());

            // Возвращаем пользователя обратно с сообщением об ошибке
            return redirect()->back()->with('error', __('chats.message_send_error'));
        }
    }

    /**
     * Вспомогательный метод для загрузки сообщения в IPFS.
     */
    private function uploadMessage($message)
    {
        Log::info('Uploading message to IPFS', ['messageLength' => strlen($message)]);

        try {
            // Шифруем сообщение
            $encryptionService = new EncryptionService();
            $encryptedMessage = $encryptionService->encrypt($message);

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

        // Помечаем уведомление как прочитанное
        $notification->update(['is_read' => true]);

        // Перенаправляем пользователя обратно
        return redirect()->back()->with('success', __('chats.notification_read'));
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
            $chat->users()->attach([auth()->id(), $userId]);
        }

        // Перенаправляем на страницу чата
        return redirect()->route('chats.show', $chat->id);
    }
}
