<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_id',
        'technician_id',
        'maintenance_type',
        'maintenance_date',
        'description',
        'condition_before',
        'action_taken',
        'condition_after',
        'next_due_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_due_date' => 'date',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}