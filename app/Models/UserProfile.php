<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'wallet_address', 'role', 'avatar_url', 'nickname', 'timezone', 
        'languages', 'birth_date', 'education', 'resume', 'portfolio', 'specialization', 
        'rating', 'trust_level', 'sbt_tokens', 'tasks_completed', 'tasks_failed', 
        'recommendations', 'activity_log', 'achievements', 'gender' 
    ];

    // Отключение автоматического управления метками времени
    public $timestamps = false; 

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
    
public function profile()
{
    return $this->hasOne(UserProfile::class, 'user_id');
}

}

