<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOffers extends Model
{
    use HasFactory;
    protected $fillable = ['category_name'];
    public $timestamps = false; // Отключение автоматического управления метками времени

}
