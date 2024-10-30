<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryNews extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Определите связь с моделью News (если необходимо)
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
