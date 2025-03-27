<?php

namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        // Получаем обновление от Telegram
        $update = Telegram::commandsHandler(true);

        // Проверка, что сообщение действительно существует
        if ($update->hasMessage()) {
            $message = $update->getMessage()->getText();
            $chatId = $update->getMessage()->getChat()->getId();

            // Обработка команды /start
            if ($message === '/start') {
                $keyboard = [
                    'inline_keyboard' => [[
                        [
                            'text' => 'Открыть приложение',
                            'web_app' => ['url' => 'https://daodes.space'] // Ваш URL
                        ]
                    ]]
                ];

                // Отправляем сообщение с кнопкой
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Добро пожаловать! Нажмите кнопку ниже, чтобы открыть приложение.',
                    'reply_markup' => json_encode($keyboard)
                ]);
            }
            // Проверка других сообщений (если нужно)
            else {
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Вы написали: $message",
                ]);
            }
        }

        // Ответ Telegram, чтобы подтвердить успешную обработку вебхука
        return response()->json(['status' => 'success']);
    }
}
