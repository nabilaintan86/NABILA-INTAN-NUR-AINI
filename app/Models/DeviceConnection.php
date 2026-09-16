<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceConnection extends Model
{
    use HasFactory;

    protected $table = 'device_connections';

    protected $fillable = [
        'from_device_id',
        'to_device_id',
        'connection_type',
    ];

    public function fromDevice()
    {
        return $this->belongsTo(Device::class, 'from_device_id');
    }

    public function toDevice()
    {
        return $this->belongsTo(Device::class, 'to_device_id');
    }
}
