<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements Rule
{
    public function passes($attribute, $value)
    {


        // Получаем IP-адрес пользователя
        $userIp = request()->ip();

        // Проверяем, находится ли IP в списке исключений
        if (in_array($userIp, config('recaptcha.skip_ip', []))) {
            return true; // Пропускаем проверку для локальных IP
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        return $response->json()['success'];
    }

    public function message()
    {
        return 'Please confirm that you are not a robot.';
    }
}
