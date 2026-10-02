<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Map extends Model
{
    use HasFactory;

    protected $table = 'maps';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Ambil map default (fallback ke map pertama).
     */
    public static function default(): ?self
    {
        return static::where('is_default', true)->first()
            ?? static::orderBy('sort_order')->orderBy('id')->first();
    }

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'device_map')
            ->withPivot(['pos_x', 'pos_y', 'network_area_id', 'map_positioned'])
            ->withTimestamps();
    }

    public function networkAreas()
    {
        return $this->hasMany(NetworkArea::class);
    }
}
