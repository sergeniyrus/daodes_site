<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_section_id',
        'name',
        'weight',
        'unit',
        'price',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'menu_section_id' => 'integer',
        'weight' => 'decimal:3',
        'price' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Раздел меню.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(
            MenuSection::class,
            'menu_section_id'
        );
    }

    /**
     * Организация через раздел меню.
     */
    public function organization(): BelongsTo
    {
        return $this->hasOneThrough(
            Organization::class,
            MenuSection::class,
            'id',
            'id',
            'menu_section_id',
            'organization_id'
        );
    }
}
