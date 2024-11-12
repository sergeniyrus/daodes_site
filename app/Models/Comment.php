<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments_offers';

    protected $fillable = [
        'offer_id',
        'user_id',
        'text',
    ];
}

