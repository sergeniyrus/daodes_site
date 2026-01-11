<?php // app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramNotifier;

class MessageController extends Controller
{
    public function index(Chat $chat)
    {
        $lastId = request('last_id', 0);
        $messages = $chat->messages()
            ->where('id', '>', $lastId)
            ->with('sender')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($msg) {
                $payload = $msg->getMessageFromIPFS($msg->ipfs_cid);
                return [
                    'id' => $msg->id,
                    'sender' => [
                        'id' => $msg->sender->id,
                        'name' => $msg->sender->name,
                    ],
                    'message' => $payload, // ← "nonce|ciphertext"
                    'created_at' => $msg->created_at->toIso8601String(),
                    'is_edited' => !is_null($msg->edited_at),
                ];
            });

        return response()->json($messages);
    }

    public function store(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string', // base64(ciphertext)
            'nonce'   => 'required|string', // base64(nonce)
        ]);

        try {
            $fullPayload = $request->nonce . '|' . $request->message;

            $cid = (new Message())->uploadMessageToIPFS($fullPayload);

            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => Auth::id(),
                'ipfs_cid' => $cid,
            ]);

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

            // Telegram
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
                'mini_app_url' => $miniAppUrl, // Передаем URL Mini App
                ];
                TelegramNotifier::notifyNewMessage($recipientId, $payload); // Вызов остается прежним
            }

            return response()->json([
                'status' => 'success',
                'message' => [
                    'id' => $message->id,
                    'sender' => $sender->name,
                    'created_at' => now()->toDateTimeString(),
                    'is_edited' => false,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка отправки: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function update(Request $request, Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['status' => 'error'], 403);
        }

        $request->validate([
            'message' => 'required|string', // ciphertext
            'nonce'   => 'required|string', // nonce
        ]);

        $fullPayload = $request->nonce . '|' . $request->message;
        $newCid = $message->updateMessageContent($fullPayload);

        // Обновляем поле edited_at при редактировании
        $message->update(['edited_at' => 1]);

        return response()->json([
            'status' => 'success',
            'message' => [
                'id' => $message->id,
                'encrypted' => $fullPayload,
                'ipfs_cid' => $newCid,
                'is_edited' => true,
            ]
        ]);
    }

    public function destroy(Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['status' => 'error'], 403);
        }

        $message->delete();
        return response()->json(['status' => 'success']);
    }
}
