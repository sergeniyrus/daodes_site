<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * Имя команды.
     */
    protected string $name = 'start';

    /**
     * Описание команды.
     */
    protected string $description = 'Стартовая команда для приветствия пользователей';

    /**
     * Логика выполнения команды.
     */
    public function handle(): void
    {
        $this->replyWithMessage([
            'text' => 'Добро пожаловать! Я ваш Telegram-бот.'
        ]);
    }
}
