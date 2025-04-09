<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ru', 'title_en', 'content_ru', 'content_en', 'user_id', 'category_id', 'img', // обновите этот список согласно вашему таблицу
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Определите связь с моделью Category
    public function category()
    {
        return $this->belongsTo(CategoryNews::class, 'category_id'); // Замените CategoryNews на фактическое имя вашей модели категории
    }
    
}
