<?php
// app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramNotifier;

class MessageController extends Controller
{
    /**
     * TTL кэша для списка сообщений (в секундах)
     * 60 секунд — оптимально для активных чатов
     */
    private const MESSAGES_CACHE_TTL = 3;

    /**
     * TTL кэша для превью ответа
     */
    private const REPLY_INFO_CACHE_TTL = 60;

    /**
     * Получение списка сообщений чата
     * Кэшируем метаданные в Redis, контент тянем из IPFS
     */
    public function index(Chat $chat)
    {
        if (!$chat->users()->where('user_id', auth()->id())->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        $lastId = (int) request('last_id', 0);

        // Ключ кэша зависит от last_id, чтобы не смешивать новые и старые сообщения
        $cacheKey = "chat:{$chat->id}:messages:{$lastId}";

        // Получаем метаданные из кэша или из БД
        $messagesMeta = Cache::remember($cacheKey, self::MESSAGES_CACHE_TTL, function () use ($chat, $lastId) {
            $newMessages = $chat->messages()
                ->where('id', '>', $lastId)
                ->with('sender:id,name')
                ->orderBy('id', 'asc')
                ->get();

            $recentlyEdited = $chat->messages()
                ->where('edited_at', 1)
                ->where('updated_at', '>', now()->subMinutes(5))
                ->with('sender:id,name')
                ->get();

            return $newMessages->merge($recentlyEdited)->unique('id')->values();
        });

        // Контент тянем из IPFS (не кэшируем в Redis — он уже в IPFS!)
        $messages = $messagesMeta->map(function ($msg) {
            try {
                $payload = $msg->getMessageFromIPFS($msg->ipfs_cid);
            } catch (\Exception $e) {
                Log::error("Failed to load message {$msg->id} from IPFS: " . $e->getMessage());
                $payload = $msg->ipfs_cid; // fallback — возвращаем сам CID
            }

            return [
                'id' => $msg->id,
                'sender' => [
                    'id' => $msg->sender->id,
                    'name' => $msg->sender->name,
                ],
                'message' => $payload,
                'ipfs_cid' => $msg->ipfs_cid,
                'reply_to_message_id' => $msg->reply_to_message_id,
                'created_at' => $msg->created_at->toIso8601String(),
                'is_edited' => $msg->edited_at == 1,
            ];
        })->sortBy('id')->values();

        return response()->json($messages);
    }

    /**
     * Отправка нового сообщения
     */
    public function store(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string',
            'nonce'   => 'required|string',
            'reply_to_message_id' => 'nullable|integer|exists:messages,id',
        ]);

        try {
            $fullPayload = $request->nonce . '|' . $request->message;
            $cid = (new Message())->uploadMessageToIPFS($fullPayload);

            // Проверяем, что reply_to_message_id из того же чата
            $replyToId = null;
            if ($request->reply_to_message_id) {
                $replyMessage = Message::where('id', $request->reply_to_message_id)
                    ->where('chat_id', $chat->id)
                    ->first();
                if ($replyMessage) {
                    $replyToId = $replyMessage->id;
                }
            }

            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => Auth::id(),
                'ipfs_cid' => $cid,
                'reply_to_message_id' => $replyToId,
            ]);

            // Инвалидируем кэш чата — список сообщений изменился
            $this->invalidateChatCache($chat->id);

            // Уведомления
            $recipientIds = $chat->users()->where('user_id', '!=', Auth::id())->pluck('user_id');
            if ($recipientIds->isNotEmpty()) {
                $notifications = $recipientIds->map(fn($id) => [
                    'user_id' => $id,
                    'message_id' => $message->id,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();
                Notification::insert($notifications);
            }

            // Telegram-уведомления
            $sender = Auth::user();
            $baseUrl = config('app.url');
            $chatUrl = "{$baseUrl}/chats/{$chat->id}";
            $miniAppUrl = "https://t.me/DAODES_Robot/DAODES_Dapp?chat_id={$chat->id}";
            $isPersonal = $chat->type === 'personal';

            foreach ($recipientIds as $recipientId) {
                $payload = [
                    'is_personal' => $isPersonal,
                    'sender_login' => $sender->name,
                    'chat_name' => $isPersonal ? 'личный чат' : $chat->name,
                    'chat_url' => $chatUrl,
                    'mini_app_url' => $miniAppUrl,
                ];
                TelegramNotifier::notifyNewMessage($recipientId, $payload);
            }

            return response()->json([
                'status' => 'success',
                'message' => [
                    'id' => $message->id,
                    'sender' => $sender->name,
                    'ipfs_cid' => $message->ipfs_cid,
                    'reply_to_message_id' => $message->reply_to_message_id,
                    'created_at' => now()->toDateTimeString(),
                    'is_edited' => false,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка отправки: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Редактирование сообщения
     */
    public function update(Request $request, Message $message)
    {
        $request->validate([
            'message' => 'required|string',
            'nonce'   => 'required|string',
        ]);

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        try {
            $newPayload = $request->nonce . '|' . $request->message;
            $newCid = (new Message())->uploadMessageToIPFS($newPayload);

            $message->update([
                'ipfs_cid' => $newCid,
                'edited_at' => 1,
            ]);

            // Инвалидируем кэш чата
            $this->invalidateChatCache($message->chat_id);

            return response()->json([
                'status' => 'success',
                'message' => [
                    'id' => $message->id,
                    'sender' => Auth::user()->name,
                    'ipfs_cid' => $message->ipfs_cid,
                    'reply_to_message_id' => $message->reply_to_message_id,
                    'created_at' => $message->created_at->toDateTimeString(),
                    'is_edited' => true,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка редактирования: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Удаление сообщения
     */
    public function destroy(Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['status' => 'error'], 403);
        }

        $chatId = $message->chat_id;
        $message->delete();

        // Инвалидируем кэш чата
        $this->invalidateChatCache($chatId);

        return response()->json(['status' => 'success']);
    }

    /**
     * Получение информации о сообщении для превью ответа
     * Кэшируем в Redis, т.к. превью запрашивается часто
     */
    public function getReplyInfo(Message $message)
    {
        $isParticipant = $message->chat->users()
            ->where('user_id', Auth::id())
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }

        // Кэшируем результат (метаданные + контент из IPFS)
        $cacheKey = "message:{$message->id}:reply_info";

        $data = Cache::remember($cacheKey, self::REPLY_INFO_CACHE_TTL, function () use ($message) {
            try {
                $payload = $message->getMessageFromIPFS($message->ipfs_cid);
            } catch (\Exception $e) {
                Log::error("Failed to load reply message {$message->id} from IPFS: " . $e->getMessage());
                return null;
            }

            return [
                'id' => $message->id,
                'sender' => [
                    'id' => $message->sender->id,
                    'name' => $message->sender->name,
                ],
                'content' => $payload,
            ];
        });

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'IPFS unavailable'
            ], 503);
        }

        return response()->json([
            'status' => 'success',
            'message' => $data,
        ]);
    }

    /**
     * Получение ID всех сообщений чата (для проверки удалений)
     * Лёгкий запрос — только ID, без IPFS
     */
    public function getMessageIds(Chat $chat)
    {
        if (!$chat->users()->where('user_id', auth()->id())->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Forbidden'], 403);
        }

        $cacheKey = "chat:{$chat->id}:message_ids";

        $ids = Cache::remember($cacheKey, self::MESSAGES_CACHE_TTL, function () use ($chat) {
            return $chat->messages()->pluck('id')->toArray();
        });

        return response()->json($ids);
    }

    /**
     * Инвалидация всех кэшей, связанных с чатом
     * Вызывается при создании/редактировании/удалении сообщений
     */
    private function invalidateChatCache(int $chatId): void
    {
        // Удаляем кэш списка сообщений для всех last_id
        // Используем tags, если Redis настроен с поддержкой tags
        // Иначе — удаляем по известным ключам
        
        // Основной кэш сообщений
        Cache::forget("chat:{$chatId}:messages:0");
        
        // Кэш ID сообщений
        Cache::forget("chat:{$chatId}:message_ids");
        
        // Можно также использовать паттерн, если Redis настроен:
        // Cache::tags(["chat:{$chatId}"])->flush();
    }
}