<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryNews extends Model
{
    use HasFactory;

    protected $fillable = ['category_name'];
    public $timestamps = false; // Отключение автоматического управления метками времени


    // Определите связь с моделью News (если необходимо)
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
