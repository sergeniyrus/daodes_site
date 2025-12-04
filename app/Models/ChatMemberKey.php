<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMemberKey extends Model
{
    protected $table = 'chat_member_keys';

    protected $fillable = [
        'chat_id',
        'user_id',
        'encrypted_key',
        'nonce',
        
    ];

    public $timestamps = true;

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
