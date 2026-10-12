<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EzyHomeArea extends Model
{
    protected $table = 'ezyhome_areas';

    protected $fillable = [
        'name', 'code', 'description',
        'pos_x', 'pos_y', 'width', 'height',
        'border_color', 'bg_color', 'title_color',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(EzyHomePosition::class, 'ezyhome_area_id');
    }
}