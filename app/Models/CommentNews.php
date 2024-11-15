<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentNews extends Model
{
    protected $table = 'comments_news'; // Указываем таблицу
    protected $fillable = ['news_id', 'user_id', 'text', 'created_at']; // Заполняемые поля

    public $timestamps = false; // Если у вас нет автоматических временных меток
}
