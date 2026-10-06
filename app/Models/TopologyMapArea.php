<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TopologyMapArea extends Model
{
    protected $table = 'topology_map_areas';

    protected $fillable = [
        'topology_map_id', 'name', 'code', 'description',
        'pos_x', 'pos_y', 'width', 'height',
        'border_color', 'bg_color', 'title_color', 'sort_order',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(TopologyMap::class, 'topology_map_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(TopologyMapDevice::class, 'topology_map_area_id');
    }
}