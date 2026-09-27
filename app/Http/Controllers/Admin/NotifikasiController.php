<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    /**
     * Jumlah notifikasi yang belum dibaca, untuk menyegarkan badge lonceng
     * tanpa reload halaman.
     */
    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'unread' => (new DeviceNotification)->unreadCount(),
        ]);
    }

    public function markAsRead(DeviceNotification $notification): JsonResponse
    {
        $notification->update(['is_read' => true]);

        return $this->readStateResponse();
    }

    /**
     * Tandai notifikasi yang sudah ditampilkan di panel Recent Notification
     * sebagai sudah dibaca, sehingga badge lonceng langsung menyesuaikan.
     */
    public function markDisplayedAsRead(Request $request): JsonResponse
    {
        $ids = collect($request->input('ids', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isNotEmpty()) {
            DeviceNotification::query()
                ->whereIn('id', $ids)
                ->unread()
                ->update(['is_read' => true]);
        }

        return $this->readStateResponse();
    }

    /**
     * Tandai SEMUA notifikasi yang belum dibaca (bukan hanya yang tampil di
     * panel Recent Notification) sebagai sudah dibaca.
     */
    public function markAllAsRead(): JsonResponse
    {
        DeviceNotification::query()
            ->unread()
            ->update(['is_read' => true]);

        return $this->readStateResponse();
    }

    private function readStateResponse(): JsonResponse
    {
        return response()->json([
            'ok'     => true,
            'unread' => (new DeviceNotification)->unreadCount(),
        ]);
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