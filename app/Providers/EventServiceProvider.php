<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use App\Listeners\UpdateMailLogStatus;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Карта событий и слушателей.
     */
    protected $listen = [
        MessageSent::class => [
            UpdateMailLogStatus::class,
        ],
    ];

    /**
     * Регистрация событий приложения.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
