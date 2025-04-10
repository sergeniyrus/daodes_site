<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'title_ru',
        'content_ru',
        'title_en',
        'content_en',
        'category_id',
        'user_id',
        'img',
        'views',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
