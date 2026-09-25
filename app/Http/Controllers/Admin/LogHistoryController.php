<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogArchive;
use App\Models\MonitoringLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        // Tab Arsip membaca monitoring_logs DAN log_archives. Tabel arsip
        // baru terisi setelah `logs:archive` berjalan, jadi tanpa join ini
        // data terbaru (masih di monitoring_logs) tidak akan pernah muncul.
        $columns = ['id', 'device_id', 'device_name', 'ip_address', 'status', 'response_time', 'checked_at'];

        $current  = MonitoringLog::query()->select($columns);
        $archived = LogArchive::query()->select($columns);

        $this->applyArchiveDateFilter($current, $request);
        $this->applyArchiveDateFilter($archived, $request);

        $logs = $current->unionAll($archived)
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        $html = view('admin.partials.log-archive-table', ['logs' => $logs])->render();

        return response()->json([
            'html'  => $html,
            'total' => $logs->total(),
        ]);
    }

    /**
     * Filter rentang tanggal yang aman & inklusif.
     *
     * Input date picker bisa datang sebagai YYYY-MM-DD (input type=date),
     * MM/DD/YYYY atau DD/MM/YYYY. Nilai diparsing sebagai tanggal lokal di
     * timezone aplikasi (WIB), lalu:
     *   - dari  -> startOfDay()  (00:00:00)
     *   - sampai-> endOfDay()    (23:59:59)
     * sehingga seluruh hari terakhir ikut tercakup (bukan hanya midnight).
     * Kolom disimpan sebagai datetime (Y-m-d H:i:s), bukan string tanggal.
     */
    protected function applyArchiveDateFilter($query, Request $request): void
    {
        $from = $this->parseDateBound($request->input('from'));
        $to   = $this->parseDateBound($request->input('to'));

        if ($from !== null) {
            $query->where('checked_at', '>=', $from->copy()->startOfDay());
        }

        if ($to !== null) {
            $query->where('checked_at', '<=', $to->copy()->endOfDay());
        }
    }

    /**
     * Parse input tanggal menjadi Carbon di timezone aplikasi.
     * Mendukung beberapa format umum (Y-m-d, m/d/Y, d/m/Y, dst).
     */
    protected function parseDateBound(mixed $value): ?Carbon
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);
        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d', 'd-m-Y', 'm-d-Y', 'Y-m-d H:i:s', 'm/d/Y H:i:s'];

        foreach ($formats as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $value);
            } catch (\Throwable) {
                continue;
            }

            // Pastikan seluruh token ter-parse (hindari 09/24/2026 dibaca keliru).
            if ($parsed && $parsed->format($format) === $value) {
                return $parsed->setTimezone(config('app.timezone'));
            }
        }

        try {
            return Carbon::parse($value)->setTimezone(config('app.timezone'));
        } catch (\Throwable) {
            return null;
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
                'type'                 => $device->display_type,
                'protocol'             => $device->display_protocol,
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