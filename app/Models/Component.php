<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'installation_id',
        'component_type_id',
        'name',
        'manufacturer',
        'model',
        'serial_number',
        'installation_date',
        'rated_capacity',
        'rated_voltage',
        'expected_lifespan',
        'current_condition',
        'status',
        'description',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'rated_capacity' => 'decimal:2',
        'rated_voltage' => 'decimal:2',
    ];

    /**
     * The installation this component belongs to.
     */
    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class);
    }

    /**
     * The component type.
     */
    public function componentType(): BelongsTo
    {
        return $this->belongsTo(ComponentType::class);
    }

    /**
     * Measurements recorded for this component.
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    /**
     * Maintenance schedules for this component.
     */
    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }

    /**
     * Maintenance records for this component.
     */
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    /**
     * Cost records associated with this component.
     */
    public function costRecords(): HasMany
    {
        return $this->hasMany(CostRecord::class);
    }

    /**
     * Replacement forecasts for this component.
     */
    public function replacementForecasts(): HasMany
    {
        return $this->hasMany(ReplacementForecast::class);
    }

    /**
     * Inspection checklist items for this component.
     */
    public function inspectionItems(): HasMany
    {
        return $this->hasMany(InspectionItem::class);
    }
}