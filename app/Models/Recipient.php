<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $fillable = ['name', 'email', 'imported_from'];

    public function lists() { return $this->belongsToMany(ContactList::class); }
}
