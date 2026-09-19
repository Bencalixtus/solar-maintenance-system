<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'component_id',
        'check_item',
        'result',
        'measurement',
        'unit',
        'condition',
        'remarks',
    ];

    protected $casts = [
        'measurement' => 'decimal:2',
    ];

    /**
     * Inspection this checklist item belongs to.
     */
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Component inspected.
     */
    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}