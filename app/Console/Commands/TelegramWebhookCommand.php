<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;

class TelegramWebhookCommand extends Command
{
    protected $signature = 'telegram:webhook {action=set}'; // set или delete
    protected $description = 'Управление Telegram webhook с secret token';

    public function handle()
{
    $action = $this->argument('action');
    $botToken = config('services.telegram.bot_token');
    $secretToken = config('services.telegram.secret_token');
    $webhookUrl = config('app.url') . '/telegram/webhook';

    $telegram = new Api($botToken);

    if ($action === 'delete') {
        $telegram->deleteWebhook();
        $this->info('✅ Webhook удалён.');
        return;
    }

    // Устанавливаем webhook с secret token (позиционные аргументы!)
    $result = $telegram->setWebhook([
    'url' => $webhookUrl,
    'secret_token' => $secretToken,
]);

    if ($result) {
        $this->info("✅ Webhook установлен: {$webhookUrl}");
        $this->info("🔑 Secret token: " . substr($secretToken, 0, 8) . '...');
    } else {
        $this->error('❌ Не удалось установить webhook.');
    }
}
}