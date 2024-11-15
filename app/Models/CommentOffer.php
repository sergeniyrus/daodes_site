<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentOffer extends Model
{
    protected $table = 'comments_offers'; // Указываем таблицу
    protected $fillable = ['offer_id', 'user_id', 'text', 'created_at']; // Заполняемые поля

    public $timestamps = false; // Если у вас нет автоматических временных меток
}
