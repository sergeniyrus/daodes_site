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
    // Определяем тип чата
    $chatType = $request->input('chat_type', 'group');

    // Валидация базовых полей
    $rules = [
        'chat_type' => 'required|in:group,direct',
        'users' => 'required|string',
    ];

    $messages = [
        'chat_type.in' => __('chats.invalid_chat_type'),
        'users.required' => __('chats.select_at_least_one_user'), // ← Используем твой текст ошибки
    ];

    // Название обязательно только для группового чата
    if ($chatType === 'group') {
        $rules['name'] = [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) {
                if (Chat::whereRaw('LOWER(name) = LOWER(?)', [$value])->exists()) {
                    $fail(__('chats.chat_name_exists'));
                }
            },
        ];
        $messages['name.required'] = __('chats.chat_name_required');
    }

    // Ручная валидация → ошибка через session('error')
    $validator = \Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        return redirect()->back()
            ->with('error', $validator->errors()->first())
            ->withInput();
    }

    // Декодируем пользователей
    $userIds = json_decode($request->input('users'), true);
    if (!is_array($userIds) || empty($userIds)) {
        return redirect()->back()
            ->with('error', __('chats.select_at_least_one_user'))
            ->withInput();
    }

    // Фильтрация ID
    $userIds = array_map('intval', $userIds);
    $userIds = array_filter($userIds, fn($id) => $id > 0 && $id !== Auth::id() && !in_array($id, [1, 2]));

    if (empty($userIds)) {
        return redirect()->back()
            ->with('error', __('chats.select_at_least_one_user')) // или более точный текст
            ->withInput();
    }

    // Определяем тип чата
    $type = ($chatType === 'direct' || count($userIds) === 1) ? 'personal' : 'group';

    // Проверка существования личного чата
    if ($type === 'personal') {
        $otherUserId = $userIds[0];

        $existingChat = Chat::whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', Auth::user()->id);
            })
            ->whereHas('users', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->withCount('users')
            ->having('users_count', '=', 2)
            ->first();

        if ($existingChat) {
            return redirect()->route('chats.show', $existingChat->id)
                ->with('info', __('chats.chat_exists'));
        }
    }

    // Создаём чат
    $chat = Chat::create([
        'name' => $type === 'personal' ? 'personal' : $request->name,
        'type' => $type,
    ]);

    // Добавляем участников
    $allUserIds = array_unique(array_merge($userIds, [Auth::id()]));
    $chat->users()->attach($allUserIds);

    return redirect()->route('chats.index')
        ->with('message', __('chats.chat_created'));
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

        // Получаем ID всех участников чата, кроме отправителя
        $recipientIds = Chat::findOrFail($chatId)
            ->users()
            ->where('user_id', '!=', Auth::id())
            ->pluck('user_id');

        // Создаем уведомления для каждого получателя
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

        // Массовое создание уведомлений
        if (!empty($notifications)) {
            Notification::insert($notifications);
        }

        
// === Отправка Telegram-уведомлений ===
$chat = Chat::with('users')->findOrFail($chatId);
$sender = Auth::user();

$baseUrl = config('app.url'); // https://daodes.space
$chatUrl = "{$baseUrl}/chats/{$chat->id}";

foreach ($recipientIds as $recipientId) {
    $isPersonal = $chat->type === 'personal';
    $payload = [
        'is_personal' => $isPersonal,
        'sender_login' => $sender->name, // или $sender->username, если есть
        'chat_name' => $isPersonal ? 'личный чат' : $chat->name,
        'chat_url' => $chatUrl,
    ];

    \App\Services\TelegramNotifier::notifyNewMessage($recipientId, $payload);
}


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
        Log::error('Ошибка отправки сообщения: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Ошибка отправки сообщения'
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