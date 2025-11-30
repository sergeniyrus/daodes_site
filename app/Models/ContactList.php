<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    protected $fillable = ['name'];

    public function recipients() { return $this->belongsToMany(Recipient::class); }
}
