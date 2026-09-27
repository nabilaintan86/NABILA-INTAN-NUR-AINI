<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceNotification extends Model
{
    protected $table = 'device_notifications';

    protected $guarded = [];

    protected $casts = [
        'device_id'  => 'integer',
        'latency'    => 'integer',
        'is_read'    => 'boolean',
        'checked_at' => 'datetime',
    ];

    /**
     * Jumlah notifikasi yang belum dibaca (isi badge lonceng di header).
     */
    public function unreadCount(): int
    {
        return (int) $this->newQuery()->unread()->count();
    }

    /**
     * Notifikasi terbaru yang ditampilkan pada panel Recent Notification.
     */
    public function recentForPanel(int $limit = 6)
    {
        return $this->newQuery()
            ->orderByDesc('checked_at')
            ->limit($limit)
            ->get();
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}