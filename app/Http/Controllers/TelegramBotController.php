<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\RateLimiter;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use App\Models\UserProfile;

class TelegramBotController extends Controller
{
    /**
     * Языковые коды стран бывшего СССР → русская локаль
     */
    private const USSR_LANG_CODES = [
        'ru',
        'uk',
        'be',
        'kk',
        'uz',
        'az',
        'hy',
        'ka',
        'tg',
        'tk',
        'ky',
        'mo',
        'tt',
        'ba',
        'cv',
        'os',
        'ce',
        'kv'
    ];

    /**
     * Определяем локаль по языку Telegram
     */
    private function detectLocaleFromTelegram(string $telegramLangCode): string
    {
        $lang = strtolower(substr($telegramLangCode, 0, 2));
        return in_array($lang, self::USSR_LANG_CODES) ? 'ru' : 'en';
    }

    public function handle(Request $request)
    {
        // 🔐 Проверка секретного токена
        $secret = $request->header('X-Telegram-Bot-Api-Secret-Token');
        Log::info('TG SECRET:', ['header' => $secret]);

        if ($secret !== config('services.telegram.secret_token')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $telegram = new Api(config('services.telegram.bot_token'));

            // Получение webhook update
            $update = $telegram->getWebhookUpdates();

            // Пропускаем всё, кроме текстовых сообщений
            if (!isset($update['message']) || !isset($update['message']['text'])) {
                return response()->json(['ok' => true]);
            }

            $message  = $update['message'];
            $chatId   = $message['chat']['id'];
            $text     = $message['text'];
            $from     = $message['from'] ?? [];
            $username = $from['username'] ?? null;

            // Поддерживаем только команду /start
            if ($text !== '/start') {
                return response()->json(['ok' => true]);
            }

            // 🛡 Ограничение частоты (1 раз в минуту)
            $throttleKey = "telegram_start:{$chatId}";
            if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
                return response()->json(['ok' => true]);
            }
            RateLimiter::hit($throttleKey, 60);

            // 🌍 Определяем язык
            $locale = isset($from['language_code'])
                ? $this->detectLocaleFromTelegram($from['language_code'])
                : 'ru';

            $originalLocale = App::getLocale();
            App::setLocale($locale);

            // ❌ Нет username — перевод: telegram.no_username
            if (!$username) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text'    => __('telegram.no_username'),
                ]);
                App::setLocale($originalLocale);
                return response()->json(['ok' => true]);
            }

            // 🔍 Ищем профиль
            $profile = UserProfile::where('telegram_chat_id', $chatId)->first();

            if ($profile) {
                // Если username обновился
                if ($profile->nickname !== $username) {
                    $profile->nickname = $username;
                    $profile->save();
                }
            } else {
                // Пытаемся найти по никнейму
                $profile = UserProfile::where('nickname', $username)->first();
            }

            // ❌ Профиль не найден — telegram.profile_not_found
            if (!$profile) {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text'    => __('telegram.profile_not_found', ['username' => $username]),
                ]);
                App::setLocale($originalLocale);
                return response()->json(['ok' => true]);
            }

            // 🟩 Сохраняем только chat_id
            $profile->telegram_chat_id = $chatId;
            $profile->save();

            // 🎉 Ответ об успешной привязке — полностью локализован
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => __('telegram.bound_success', ['username' => $username]),
                'parse_mode' => 'Markdown',
            ]);

            App::setLocale($originalLocale);
        } catch (TelegramSDKException $e) {
            Log::error('Telegram Bot Error (webhook): ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('General Error in TelegramBotController: ' . $e->getMessage());
        }

        return response()->json(['ok' => true]);
    }
}
