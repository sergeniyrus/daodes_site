<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chat extends Model
{
    protected $fillable = ['name']; // Убедитесь, что 'name' указан здесь

    // Отношение "многие ко многим" с пользователями
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_user');
    }
    
    // Отношение "многие ко многим" с моделью User (альтернативное название)
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user', 'chat_id', 'user_id');
    }

    // Отношение "один ко многим" с сообщениями
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Количество непрочитанных сообщений
    public function getUnreadMessagesCountAttribute()
    {
        return $this->messages()->where('is_read', false)->count();
    }

    // В модели Chat
public function getChatNameForUser($userId)
{
    // Если это групповой чат, возвращаем общее название
    if ($this->type === 'group') {
        return $this->name;
    }

    // Если это личный чат, возвращаем имя собеседника
    $otherUser = $this->users()->where('user_id', '!=', $userId)->first();
    return $otherUser ? $otherUser->name : 'Чат';
}

public function unreadMessagesCount($userId)
{
    return $this->messages()
        ->whereHas('notifications', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('is_read', false);
        })
        ->count();
}

}