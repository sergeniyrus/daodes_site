<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class SetLocale
{
    public function handle($request, Closure $next)
{
    // Если это маршрут страницы с CAPTCHA, пропускаем проверку
    if ($request->routeIs('captcha.show') || $request->routeIs('captcha.verify')) {
        return $next($request);
    }

    // Проверка, прошел ли пользователь reCAPTCHA
    if (!Session::has('captcha_passed')) {
        // Если reCAPTCHA не пройдена, перенаправляем на страницу с CAPTCHA
        return Redirect::route('captcha.show');
    }

    // Остальная логика middleware
    if ($request->ip() === '127.0.0.1' || $request->ip() === '::1') {
        $locale = 'en';
    } else {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            $locale = $this->getLocaleFromIP($request->ip());
            Session::put('locale', $locale);
        }
    }

    App::setLocale($locale);

    return $next($request);
}

    /**
     * Определяет локаль на основе IP-адреса пользователя.
     *
     * @param string $ip
     * @return string
     */
    protected function getLocaleFromIP(string $ip): string
    {
        Log::info('getLocaleFromIP called for IP: ' . $ip);

        // Ключ для кеширования
        $cacheKey = 'ipstack_locale_' . $ip;

        // Пытаемся получить локаль из кеша
        return Cache::remember($cacheKey, now()->addMonth(), function () use ($ip) {
            // Используем сервис ipstack для определения страны
            $ipstack = app('ipstack');
            $location = $ipstack->getLocation($ip);

            // Если страну определить не удалось, используем локаль по умолчанию
            $countryCode = $location['country_code'] ?? null;

            // Логируем запись в кеш
            Log::info("Locale cached for IP: {$ip}", [
                'country_code' => $countryCode,
                'locale' => $this->getLocaleByCountryCode($countryCode),
            ]);

            // Возвращаем локаль на основе страны
            return $this->getLocaleByCountryCode($countryCode);
        });
    }

    /**
     * Возвращает локаль на основе кода страны.
     *
     * @param string|null $countryCode
     * @return string
     */
    protected function getLocaleByCountryCode(?string $countryCode): string
    {
        // Если код страны не передан, используем локаль по умолчанию
        if ($countryCode === null) {
            return 'en'; // или 'ru', в зависимости от ваших предпочтений
        }

        // Пример: для России устанавливаем русский язык, для остальных — английский
        return $countryCode === 'RU' ? 'ru' : 'en';
    }
}