<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'title', 'content', 'user_id', // обновите этот список согласно вашему таблицу
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
