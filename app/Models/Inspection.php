<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'installation_id',
        'inspector_id',
        'inspection_date',
        'overall_condition',
        'general_observation',
        'recommendation',
        'remarks',
        'next_inspection_date',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'next_inspection_date' => 'date',
    ];

    /**
     * Installation being inspected.
     */
    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class);
    }

    /**
     * User who carried out the inspection.
     */
    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    /**
     * Checklist items belonging to this inspection.
     */
    public function inspectionItems(): HasMany
    {
        return $this->hasMany(InspectionItem::class);
    }
}