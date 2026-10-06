<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopologyMapDevice extends Model
{
    protected $table = 'topology_map_devices';

    protected $fillable = [
        'topology_map_id', 'topology_map_area_id', 'device_id',
        'pos_x', 'pos_y', 'icon_type', 'note',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(TopologyMap::class, 'topology_map_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(TopologyMapArea::class, 'topology_map_area_id');
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}