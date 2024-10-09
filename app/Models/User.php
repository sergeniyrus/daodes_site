<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
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
     * Define the relationship with seeds (example from your original code).
     */
    public function seeds()
    {
        return $this->hasMany(Seed::class);
    }
}
