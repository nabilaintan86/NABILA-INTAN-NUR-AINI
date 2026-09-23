<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonitoringLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan', [
            'chartData' => $this->buildChartData(),
        ]);
    }

    /**
     * Bangun data grafik availability & response time dari riwayat monitoring_logs.
     * Perioda: 24 jam (per jam), 7 hari & 30 hari (per hari).
     */
    protected function buildChartData(): array
    {
        $hourly  = $this->aggregate(now()->startOfHour()->subHours(23), 'hour');
        $weekly  = $this->aggregate(now()->startOfDay()->subDays(6), 'day');
        $monthly = $this->aggregate(now()->startOfDay()->subDays(29), 'day');

        return [
            '24h' => $this->series($hourly, now()->startOfHour()->subHours(23), 'hour'),
            '7d'  => $this->series($weekly, now()->startOfDay()->subDays(6), 'weekday'),
            '30d' => $this->series($monthly, now()->startOfDay()->subDays(29), 'day'),
        ];
    }

    /**
     * Agregasi monitoring_logs per bucket (jam/hari).
     * Memakai GROUP BY agar efisien walau riwayat banyak.
     */
    protected function aggregate(Carbon $start, string $granularity): array
    {
        $bucketExpr = $granularity === 'hour'
            ? $this->dateExpression('%Y-%m-%d %H:00:00')
            : $this->dateExpression('%Y-%m-%d');

        $rows = MonitoringLog::query()
            ->where('checked_at', '>=', $start)
            ->selectRaw($bucketExpr.' as bucket, status, COUNT(*) as total_per_status, AVG(CASE WHEN response_time IS NOT NULL THEN response_time END) as avg_latency')
            ->groupBy('bucket', 'status')
            ->get();

        $result = [];

        foreach ($rows as $row) {
            $bucket = $row->bucket;

            $result[$bucket]['total'] = ($result[$bucket]['total'] ?? 0) + (int) $row->total_per_status;

            if ($row->status === 'up') {
                $result[$bucket]['up'] = ($result[$bucket]['up'] ?? 0) + (int) $row->total_per_status;
            } elseif ($row->status === 'down') {
                $result[$bucket]['down'] = ($result[$bucket]['down'] ?? 0) + (int) $row->total_per_status;
            }

            if ($row->avg_latency !== null) {
                $result[$bucket]['avg_latency'] = round((float) $row->avg_latency, 1);
            }
        }

        return $result;
    }

    /**
     * Ekspresi SQL yang menghasilkan bucket waktu. Driver-aware agar
     * kompatibel dengan MySQL (produksi) dan SQLite (testing).
     */
    protected function dateExpression(string $format): string
    {
        $hourly = str_contains($format, ':00:00');

        return DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('{$format}', checked_at)"
            : ($hourly
                ? 'DATE_FORMAT(checked_at, \'%Y-%m-%d %H:00:00\')'
                : 'DATE(checked_at)');
    }

    /**
     * Rangkaikan agregat ke label + nilai per titik untuk Chart.js.
     * $labelStyle: hour (H:i), weekday (Sen..Min), day (1..30).
     */
    protected function series(array $buckets, Carbon $start, string $labelStyle): array
    {
        $labels       = [];
        $availability = [];
        $response     = [];

        $cursor = $start->copy();

        while ($cursor->lte(now())) {
            $key = $labelStyle === 'hour'
                ? $cursor->format('Y-m-d H:00:00')
                : $cursor->format('Y-m-d');

            $labels[] = match ($labelStyle) {
                'hour'    => $cursor->format('H:i'),
                'weekday' => $cursor->locale('id')->isoFormat('ddd'),
                default   => $cursor->format('j'),
            };

            $bucket = $buckets[$key] ?? null;
            $total  = $bucket['total'] ?? 0;
            $up     = $bucket['up'] ?? 0;

            $availability[] = $total > 0 ? round(($up / $total) * 100, 2) : null;
            $response[]     = $bucket['avg_latency'] ?? null;

            $labelStyle === 'hour' ? $cursor->addHour() : $cursor->addDay();
        }

        return compact('labels', 'availability', 'response');
    }
}