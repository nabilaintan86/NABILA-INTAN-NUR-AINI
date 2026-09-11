<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'ip_address',
        'polls',
        'responded_percentage',
        'missed_percentage',
        'down_time',
        'period',
        'alerts_count',
        'avg_delay',
        'min_delay',
        'max_delay',
        'status',
    ];

    protected $casts = [
        'polls'                 => 'integer',
        'responded_percentage'  => 'float',
        'missed_percentage'     => 'float',
        'alerts_count'          => 'integer',
        'avg_delay'             => 'integer',
        'min_delay'             => 'integer',
        'max_delay'             => 'integer',
    ];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function troubleshootingLogs()
    {
        return $this->hasMany(TroubleshootingLog::class);
    }
}
