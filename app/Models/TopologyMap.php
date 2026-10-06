<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopologyMap extends Model
{
    protected $table = 'topology_maps';

    protected $fillable = [
        'name', 'slug', 'description', 'created_by', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function areas(): HasMany
    {
        return $this->hasMany(TopologyMapArea::class, 'topology_map_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(TopologyMapDevice::class, 'topology_map_id');
    }

    public function connections(): HasMany
    {
        return $this->hasMany(TopologyMapConnection::class, 'topology_map_id');
    }
}