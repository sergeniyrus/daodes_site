<?php

namespace App\Providers;

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
        // Регистрируем кастомные команды
        $telegram = app('telegram.bot');
        $telegram->addCommand(StartCommand::class);
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
