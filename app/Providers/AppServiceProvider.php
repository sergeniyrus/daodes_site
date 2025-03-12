<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use App\Rules\Recaptcha; // Убедитесь, что это правило существует
use Illuminate\Support\Facades\Validator;
use App\Services\IpStackService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов.
     *
     * @return void
     */
    public function boot()
    {
        // Передача переменной $unreadCount во все шаблоны
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $unreadCount = Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                $view->with('unreadCount', $unreadCount);
            } else {
                $view->with('unreadCount', 0);
            }
        });

        // Регистрация кастомного правила валидации reCAPTCHA
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            return (new Recaptcha)->passes($attribute, $value);
        });
    }

    /**
     * Регистрация сервисов приложения.
     *
     * @return void
     */
    public function register()
    {
        // Регистрация сервиса IpStackService
        $this->app->singleton('ipstack', function () {
            return new IpStackService();
        });

        // Здесь можно зарегистрировать другие привязки контейнера, если необходимо
    }
}