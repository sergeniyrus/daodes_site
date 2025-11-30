<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $fillable = [
        'template_id',
        'recipient_id',
        'status',
        'sent_at',
        'read_at',
    ];

    // ✅ Автоматическое преобразование дат в Carbon
    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function template()
    {
        return $this->belongsTo(MailTemplate::class, 'template_id');
    }
}
