<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $chatId;
    protected $senderId;

    public function __construct($message, $chatId, $senderId)
    {
        $this->message = $message;
        $this->chatId = $chatId;
        $this->senderId = $senderId;
    }

    public function via($notifiable)
    {
        return ['database']; // или другие каналы (mail, broadcast и т.д.)
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'chatId' => $this->chatId,
            'senderId' => $this->senderId,
        ];
    }
}