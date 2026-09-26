<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonitoringLog;
use App\Services\DeviceCsvService;

class DashboardController extends Controller
{
    public function index(DeviceCsvService $devices)
    {
        return view('dashboard', [
            'devices'        => $devices->officialDevices(),
            'deviceSource'   => $devices->officialSummary(),
            'latencyHistory' => $this->getLatencyHistory(),
            'lastCheckedAt'  => MonitoringLog::max('checked_at'),
        ]);
    }

    /**
     * Endpoint JSON untuk auto-refresh dashboard tanpa reload halaman.
     */
    public function data(DeviceCsvService $source)
    {
        $devices = $source->officialDevices();

        $total         = $devices->count();
        $upCount       = $devices->where('status', 'up')->count();
        $downCount     = $devices->where('status', 'down')->count();
        $warningCount  = $devices->where('status', 'warning')->count();
        $availability  = $total > 0
            ? round($devices->avg('responded_percentage') ?? 0, 1)
            : 0;
        $averageLatency = $total > 0
            ? round((float) $devices->avg('avg_delay'), 1)
            : 0;

        return response()->json([
            'latencyHistory' => $this->getLatencyHistory(),
            'lastCheckedAt'  => MonitoringLog::max('checked_at'),
            'deviceSource'   => $source->officialSummary(),
            'summary'        => [
                'total'          => $total,
                'up'             => $upCount,
                'down'           => $downCount,
                'warning'        => $warningCount,
                'availability'   => $availability,
                'averageLatency' => $averageLatency,
                'totalAlerts'    => (int) $devices->sum('alerts_count'),
            ],
        ]);
    }

    protected function getLatencyHistory(): array
    {
        $latencyHistory = [];
        foreach (['6', '12', '24', '168'] as $period) {
            $latencyHistory[$period] = $this->buildLatencyHistory((int) $period);
        }

        return $latencyHistory;
    }

    /**
     * Agregasi rata-rata latency per bucket waktu.
     * Periode <= 24 jam memakai bucket per jam; periode 168 jam memakai bucket harian.
     */
    private function buildLatencyHistory(int $periodHours): array
    {
        $bucketCount = $periodHours <= 24 ? $periodHours : 7;
        $stepHours   = $periodHours <= 24 ? 1 : 24;

        $rows = MonitoringLog::query()
            ->whereNotNull('response_time')
            ->where('checked_at', '>=', now()->subHours($periodHours))
            ->get(['checked_at', 'response_time']);

        // Kelompokkan nilai latency per bucket agar di rata-rata pada langkah berikutnya
        $buckets = [];
        foreach ($rows as $row) {
            $key = $periodHours <= 24
                ? $row->checked_at->format('Y-m-d H:00:00')
                : $row->checked_at->format('Y-m-d');
            $buckets[$key][] = $row->response_time;
        }

        $labels = [];
        $values = [];

        for ($i = $bucketCount - 1; $i >= 0; $i--) {
            $time  = now()->subHours($i * $stepHours);
            $key   = $periodHours <= 24 ? $time->format('Y-m-d H:00:00') : $time->format('Y-m-d');
            $label = $periodHours <= 24 ? $time->format('H:i') : $time->format('D d/m');

            $latencies = $buckets[$key] ?? [];

            $labels[] = $label;
            $values[] = $latencies
                ? round(array_sum($latencies) / count($latencies), 1)
                : null;
        }

        return compact('labels', 'values');
    }
}