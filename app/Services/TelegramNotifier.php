<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Telegram\Bot\Api;

class TelegramNotifier
{
    private const USSR_LANG_CODES = [
        'ru', 'uk', 'be', 'kk', 'uz', 'az', 'hy', 'ka', 'tg', 'tk', 'ky',
        'mo', 'tt', 'ba', 'cv', 'os', 'ce', 'kv'
    ];

    private static function detectLocaleFromTelegramCode(?string $langCode): string
    {
        if (!$langCode) {
            return 'ru'; // fallback
        }

        $lang = strtolower(substr($langCode, 0, 2));
        return in_array($lang, self::USSR_LANG_CODES) ? 'ru' : 'en';
    }

    public static function notifyNewMessage(int $recipientId, array $payload): void
    {
        $user = User::find($recipientId);
        if (!$user || $user->isOnline()) {
            return;
        }

        $profile = UserProfile::where('user_id', $recipientId)->first();
        if (!$profile?->telegram_chat_id) {
            return;
        }

        // Определяем локаль
        $locale = self::detectLocaleFromTelegramCode($profile->telegram_language_code);
        $originalLocale = App::getLocale();
        App::setLocale($locale);

        // Подготавливаем параметры для перевода
        $translationKey = $payload['is_personal'] ? 'new_message.personal' : 'new_message.group';
        $text = __(
            "telegram.{$translationKey}",
            [
                'sender_login' => $payload['sender_login'] ?? '',
                'chat_name' => $payload['chat_name'] ?? '',
                'chat_url' => $payload['chat_url'],
            ]
        );

        App::setLocale($originalLocale);

        // Отправляем сообщение
        try {
            $telegram = new Api(config('services.telegram.bot_token'));
            $telegram->sendMessage([
                'chat_id' => $profile->telegram_chat_id,
                'text' => $text,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => true,
            ]);
        } catch (\Exception $e) {
            Log::warning('Не удалось отправить Telegram-уведомление', [
                'user_id' => $recipientId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}