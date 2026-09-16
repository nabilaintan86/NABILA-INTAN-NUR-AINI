<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogArchive;
use App\Models\MonitoringLog;
use Illuminate\Http\Request;

class LogHistoryController extends Controller
{
    protected const BATCH_SIZE = 50;

    public function index()
    {
        $statusCounts = $this->statusCounts();

        return view('admin.log', [
            'statusCounts' => $statusCounts,
            'logs'         => $this->recentLogs(0),
            'archiveTotal' => LogArchive::count(),
            'hasAnyLog'    => MonitoringLog::exists(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Tab Real-time
    |--------------------------------------------------------------------------
    */

    protected function recentLogs(int $offset)
    {
        return MonitoringLog::with('device')
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->offset($offset)
            ->limit(self::BATCH_SIZE)
            ->get();
    }

    public function loadMore(Request $request)
    {
        $offset = max(0, (int) $request->input('offset', 0));
        $logs   = $this->recentLogs($offset);

        $html = view('admin.partials.log-items', ['logs' => $logs])->render();

        return response()->json([
            'html'        => $html,
            'next_offset' => $offset + $logs->count(),
            'has_more'    => $logs->count() === self::BATCH_SIZE,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Tab Arsip
    |--------------------------------------------------------------------------
    */

    public function archive(Request $request)
    {
        $query = LogArchive::query();

        $this->applyArchiveDateFilter($query, $request);

        $logs = (clone $query)
            ->orderByDesc('checked_at')
            ->paginate(25)
            ->withQueryString();

        $html = view('admin.partials.log-archive-table', ['logs' => $logs])->render();

        return response()->json([
            'html'  => $html,
            'total' => (clone $query)->count(),
        ]);
    }

    protected function applyArchiveDateFilter($query, Request $request): void
    {
        if ($request->filled('from')) {
            $query->whereDate('checked_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('checked_at', '<=', $request->input('to'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Device (modal popup)
    |--------------------------------------------------------------------------
    */

    public function detail(string $id)
    {
        $log = MonitoringLog::with('device')->find($id)
            ?? LogArchive::with('device')->find($id);

        abort_unless($log, 404, 'Log tidak ditemukan.');

        $device = $log->device;

        return response()->json([
            'log' => [
                'device_name'   => $log->device_name,
                'ip_address'    => $log->ip_address,
                'status'        => $log->status,
                'response_time' => $log->response_time,
                'checked_at'    => optional($log->checked_at)->format('Y-m-d H:i:s'),
            ],
            'device' => $device ? [
                'name'                 => $device->name,
                'type'                 => $device->type,
                'ip_address'           => $device->ip_address,
                'status'               => $device->status,
                'polls'                => (int) $device->polls,
                'responded_percentage' => $device->responded_percentage,
                'missed_percentage'    => $device->missed_percentage,
                'down_time'            => $device->down_time,
                'period'               => $device->period,
                'alerts_count'         => (int) $device->alerts_count,
                'avg_delay'            => (int) $device->avg_delay,
                'min_delay'            => (int) $device->min_delay,
                'max_delay'            => (int) $device->max_delay,
            ] : null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Statistik ringkas untuk kartu KPI
    |--------------------------------------------------------------------------
    */

    protected function statusCounts(): array
    {
        $byStatus = MonitoringLog::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'total'   => MonitoringLog::count(),
            'up'      => (int) ($byStatus['up'] ?? 0),
            'down'    => (int) ($byStatus['down'] ?? 0),
            'warning' => (int) ($byStatus['warning'] ?? 0),
        ];
    }
}