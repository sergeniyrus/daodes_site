<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table = 'organization_users';

    protected $fillable = [
        'organization_id',
        'user_id',
        'role',
        'status',
    ];

    /**
     * Организация, к которой относится пользователь.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Пользователь DAODES.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}