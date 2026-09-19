<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'installation_date',
        'system_capacity',
        'description',
        'status',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'system_capacity' => 'decimal:2',
    ];

    /**
     * An installation can have many components.
     */
    public function components(): HasMany
    {
        return $this->hasMany(Component::class);
    }

    /**
     * An installation can have many inspections.
     */
    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }
}