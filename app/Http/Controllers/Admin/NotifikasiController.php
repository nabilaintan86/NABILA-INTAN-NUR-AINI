<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceNotification;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = DeviceNotification::query()
            ->orderByDesc('checked_at')
            ->limit(200)
            ->get()
            ->map(fn ($n) => $this->toView($n))
            ->values();

        return view('admin.notifikasi', [
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(DeviceNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    private function toView(DeviceNotification $n): array
    {
        return [
            'id'          => $n->id,
            'device'      => $n->device_name,
            'ip'          => $n->ip_address,
            'status'      => $n->status,
            'event'       => $n->event,
            'message'     => $n->message,
            'time'        => $n->checked_at ? $n->checked_at->toIso8601String() : null,
            'timeLabel'   => $n->checked_at ? $n->checked_at->diffForHumans() : '-',
            'latency'     => $n->latency,
            'monitorType' => 'ICMP',
            'read'        => (bool) $n->is_read,
        ];
    }
}