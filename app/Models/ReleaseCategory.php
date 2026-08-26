<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReleaseCategory extends Model
{
    protected $table = 'release_categories';

    protected $fillable = [
        'organization_id',
        'slug',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    public function releases(): HasMany
    {
        return $this->hasMany(
            AppRelease::class,
            'category_id'
        );
    }
}