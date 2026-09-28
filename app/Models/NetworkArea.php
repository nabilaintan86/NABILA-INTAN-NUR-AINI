<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkArea extends Model
{
    use HasFactory;

    protected $table = 'network_areas';

    protected $fillable = [
        'name',
        'code',
        'description',
        'pos_x',
        'pos_y',
        'width',
        'height',
        'border_color',
        'bg_color',
        'title_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'pos_x'      => 'integer',
        'pos_y'      => 'integer',
        'width'      => 'integer',
        'height'     => 'integer',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    /**
     * Relasi ke seluruh perangkat (router / switch) di dalam area ini.
     */
    public function devices()
    {
        return $this->hasMany(Device::class, 'network_area_id');
    }
}

