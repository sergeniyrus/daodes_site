<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use App\Telegram\Commands\StartCommand;

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
    }

    /**
     * Регистрация сервисов приложения.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
