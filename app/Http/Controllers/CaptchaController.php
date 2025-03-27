<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Rules\Recaptcha;

class CaptchaController extends Controller
{
    // Отображение страницы с CAPTCHA
    public function show()
    {
        return view('captcha');
    }

    // Проверка CAPTCHA
    public function verify(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => ['required', new Recaptcha],
        ]);

        // Сохраняем флаг, что CAPTCHA пройдена
        Session::put('captcha_passed', true);

        // Перенаправляем пользователя на целевую страницу
        return redirect()->intended('/');
    }
}