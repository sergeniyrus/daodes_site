<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOffers extends Model
{
    use HasFactory;
    
    protected $fillable = ['name_ru', 'name_en'];

    public $timestamps = false; // Отключение автоматического управления метками времени
// Определите связь с моделью News (если необходимо)
public function offers()
{
    return $this->hasMany(Offers::class);
}

// Добавьте методы для получения названия категории в зависимости от локали
public function getNameAttribute()
{
    // Возвращаем название в зависимости от текущей локали
    return app()->getLocale() === 'en' ? $this->name_en : $this->name_ru;
}

}
