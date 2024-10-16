<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Model
{
    use HasFactory;

    // Указываем имя таблицы, если оно не соответствует стандарту
    protected $table = 'task_category';

    protected $fillable = [
        'name', // полное название категории
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
