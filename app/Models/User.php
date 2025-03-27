<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable; // Подключаем трейт для уведомлений

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Добавляем трейт Notifiable

    /**
     * Поля, которые можно массово назначать.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        //'email',  Добавлено поле email
        'keyword',
        'password',
    ];

    /**
     * Поля, которые должны быть скрыты при сериализации.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Преобразование типов атрибутов.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // Убрано email_verified_at
        ];
    }

    /**
     * Определяем отношение с задачами, созданными пользователем.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Определяем отношение с заявками, сделанными пользователем.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Определяем отношение с семенами.
     */
    public function seeds()
    {
        return $this->hasMany(Seed::class);
    }

    /**
     * Определяем отношение с профилем пользователя.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Определяем отношение с чатами.
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'chat_user', 'user_id', 'chat_id');
    }

    /**
     * Определяем отношение с уведомлениями пользователя.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}