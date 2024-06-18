<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $table = 'seed';

    protected $fillable = [
        'user_id',
        'word0',
        'word1',
        'word2',
        'word3',
        'word4',
        'word5',
        'word6',
        'word7',
        'word8',
        'word9',
        'word10',
        'word11',
        'word12',
        'word13',
        'word14',
        'word15',
        'word16',
        'word17',
        'word18',
        'word19',
        'word20',
        'word21',
        'word22',
        'word23',
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    // Если название таблицы не совпадает с названием модели, раскомментируйте и укажите имя таблицы
    // protected $table = 'seeds';
}
