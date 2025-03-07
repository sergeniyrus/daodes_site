<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory;


    protected $fillable = [
        'name',
        //'email',  Добавлено поле email
        'keyword',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
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
     * Define the relationship with the tasks created by the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Define the relationship with the bids made by the user.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Define the relationship with seeds.
     */
    public function seeds()
    {
        return $this->hasMany(Seed::class);
    }

    /**
     * Define the relationship with the user's profile.
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Define the relationship with chats.
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'chat_user', 'user_id', 'chat_id');
    }

    // В модели User
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
