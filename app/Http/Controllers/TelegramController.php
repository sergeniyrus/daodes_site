<?php

namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function sendMessage()
    {
        $response = Telegram::sendMessage([
            'chat_id' => '350029587', // Укажите ID чата или имя пользователя
            'text' => 'Привет! Это сообщение из Laravel.',
        ]);

        return $response;
    }

    public function webhook(Request $request)
    {
        // Получаем обновление от Telegram
        $update = Telegram::commandsHandler(true);

        // Проверка, что сообщение действительно существует
        if ($update->hasMessage()) {
            $message = $update->getMessage()->getText();
            $chatId = $update->getMessage()->getChat()->getId();

            // Отправляем сообщение обратно в тот же чат
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => "Вы написали: $message",
            ]);
        }

        // Ответ Telegram, чтобы подтвердить успешную обработку вебхука
        return response()->json(['status' => 'success']);
    }
}
