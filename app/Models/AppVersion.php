<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'version_code',
        'version_name',
        'apk_file',
        'force_update',
        'changelog',
        'is_active',
    ];

    protected $casts = [
        'force_update' => 'boolean',
        'is_active' => 'boolean',
        'changelog' => 'array',
    ];

    public function getApkUrlAttribute(): string
    {
        return asset('storage/apk/' . $this->apk_file);
    }
}