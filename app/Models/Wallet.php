<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function historyPaysFrom()
    {
        return $this->hasMany(HistoryPay::class, 'from_wallet_id');
    }

    public function historyPaysTo()
    {
        return $this->hasMany(HistoryPay::class, 'to_wallet_id');
    }
}
