<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_id',
        'maintenance_id',
        'cost_type',
        'description',
        'amount',
        'cost_date',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cost_date' => 'date',
    ];

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function maintenance()
    {
        return $this->belongsTo(MaintenanceRecord::class);
    }
}