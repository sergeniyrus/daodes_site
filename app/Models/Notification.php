<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'message_id', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }


    /**
     * Удаляет уведомление из базы данных.
     */
    public function deleteNotification()
    {
        $this->delete();
    }
}
