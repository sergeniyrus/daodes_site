<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $chatId;
    public $sender;
    public $timestamp;

    public function __construct($message, $chatId, $sender, $timestamp)
    {
        $this->message = $message;
        $this->chatId = $chatId;
        $this->sender = $sender;
        $this->timestamp = $timestamp;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chatId);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
            ],
            'timestamp' => $this->timestamp,
        ];
    }
}