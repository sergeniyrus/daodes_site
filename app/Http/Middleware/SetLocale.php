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
    // Страны, для которых устанавливается русский язык (все страны бывшего СССР)
    const RUSSIAN_SPEAKING_COUNTRIES = [
        'RU', // Россия
        'BY', // Беларусь
        'UA', // Украина
        'KZ', // Казахстан
        'AZ', // Азербайджан
        'AM', // Армения
        'GE', // Грузия
        'MD', // Молдова
        'UZ', // Узбекистан
        'TM', // Туркменистан
        'TJ', // Таджикистан
        'KG', // Кыргызстан
        'LV', // Латвия
        'LT', // Литва
        'EE', // Эстония
    ];

    const DEFAULT_LOCALE = 'en';
    const TRUSTED_IPS = ['127.0.0.1', '::1', '95.188.118.100'];

    public function handle($request, Closure $next)
    {
        try {
            $ip = $request->ip();
            $isTrustedIp = in_array($ip, self::TRUSTED_IPS);

            // Проверка CAPTCHA (пропускаем для доверенных IP и страниц CAPTCHA)
            if (!$request->routeIs('captcha.show', 'captcha.verify') && 
                !$isTrustedIp && 
                !Session::has('captcha_passed')) {
                return Redirect::route('captcha.show');
            }

            // Установка локали
            if (Session::has('locale')) {
                $locale = Session::get('locale');
            } else {
                // Для доверенных IP по умолчанию русский, если не выбран другой
                $locale = $isTrustedIp ? 'ru' : $this->getLocaleFromIP($ip);
                Session::put('locale', $locale);
            }

            App::setLocale($locale);
            return $next($request);

        } catch (\Exception $e) {
            // Логируем ошибку и используем локаль по умолчанию
            Log::error('Locale middleware error: ' . $e->getMessage());
            App::setLocale(self::DEFAULT_LOCALE);
            return $next($request);
        }
    }

    protected function getLocaleFromIP(string $ip): string
    {
        $cacheKey = 'ipstack_locale_' . $ip;
        
        return Cache::remember($cacheKey, now()->addMonth(), function () use ($ip) {
            try {
                $location = app('ipstack')->getLocation($ip);
                $countryCode = $location['country_code'] ?? null;
                
                // Для стран бывшего СССР - русский, для остальных - английский
                return $this->getLocaleByCountryCode($countryCode);
            } catch (\Exception $e) {
                Log::error("IPStack failed for IP {$ip}: " . $e->getMessage());
                return self::DEFAULT_LOCALE;
            }
        });
    }

    protected function getLocaleByCountryCode(?string $countryCode): string
    {
        if ($countryCode === null) {
            return self::DEFAULT_LOCALE;
        }

        return in_array($countryCode, self::RUSSIAN_SPEAKING_COUNTRIES) ? 'ru' : 'en';
    }
}