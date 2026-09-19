<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_id',
        'recorded_by',
        'measurement_date',
        'parameter',
        'value',
        'unit',
        'reference_value',
        'remarks',
    ];

    protected $casts = [
        'measurement_date' => 'date',
        'value' => 'decimal:2',
        'reference_value' => 'decimal:2',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}