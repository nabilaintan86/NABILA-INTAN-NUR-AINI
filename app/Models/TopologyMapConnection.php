<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopologyMapConnection extends Model
{
    protected $table = 'topology_map_connections';

    protected $fillable = [
        'topology_map_id',
        'from_map_device_id', 'from_map_area_id',
        'to_map_device_id', 'to_map_area_id',
        'connection_type', 'direction', 'label',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(TopologyMap::class, 'topology_map_id');
    }

    public function fromDevice(): BelongsTo
    {
        return $this->belongsTo(TopologyMapDevice::class, 'from_map_device_id');
    }

    public function toDevice(): BelongsTo
    {
        return $this->belongsTo(TopologyMapDevice::class, 'to_map_device_id');
    }
}