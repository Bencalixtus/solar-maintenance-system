<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplacementForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_id',
        'forecast_date',
        'current_age',
        'current_condition',
        'degradation_rate',
        'estimated_remaining_life',
        'estimated_replacement_cost',
        'risk_level',
        'recommended_action',
        'forecast_notes',
    ];

    protected $casts = [
        'forecast_date' => 'date',
        'current_age' => 'decimal:2',
        'degradation_rate' => 'decimal:2',
        'estimated_remaining_life' => 'decimal:2',
        'estimated_replacement_cost' => 'decimal:2',
    ];

    /**
     * Component associated with this forecast.
     */
    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}