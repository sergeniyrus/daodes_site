<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Получаем локаль из сессии или используем локаль по умолчанию
        $locale = Session::get('locale', config('app.locale'));

        // Устанавливаем локаль для приложения
        App::setLocale($locale);

        return $next($request);
    }
}