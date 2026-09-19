<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_id',
        'maintenance_task',
        'frequency',
        'last_maintenance_date',
        'next_due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_due_date' => 'date',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}