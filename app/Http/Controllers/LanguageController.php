<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function change($locale)
    {
        // Проверяем, поддерживается ли выбранный язык
        if (!in_array($locale, ['en', 'ru'])) {
            abort(400, 'Unsupported language');
        }

        // Устанавливаем локаль в сессии
        Session::put('locale', $locale);

        // Устанавливаем локаль для текущего приложения
        App::setLocale($locale);

        // Перенаправляем пользователя обратно
        return redirect()->back();
    }
}
