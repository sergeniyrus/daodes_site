<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // Уведомление будет сохранено в базе данных
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Новое сообщение в чате: ' . $this->message->chat->name,
            'chat_id' => $this->message->chat_id,
            'sender_name' => $this->message->sender->name,
        ];
    }
}