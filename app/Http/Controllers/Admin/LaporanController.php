<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Device;
use App\Services\DeviceCsvService;
use App\Services\Laporan\LaporanPerangkatService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public const PER_PAGE = 15;

    public function __construct(
        protected LaporanPerangkatService $perangkatService,
        protected DeviceCsvService $deviceCsv,
    ) {}

    /**
     * Halaman dashboard reporting /laporan.
     */
    public function index(Request $request)
    {
        $data = $this->report($request);

        // Dipakai halaman untuk dropdown "Export PDF per perangkat".
        $data['perangkatOptions'] = $this->perangkatService->options();

        return view('admin.laporan', $data);
    }

    /**
     * Export PDF mengikuti periode aktif (membawa detail incident penuh).
     */
    public function pdf(Request $request)
    {
        $data = $this->report($request, true);

        // Ringkasan jumlah perangkat resmi (devices.csv) untuk PDF keseluruhan.
        $data['deviceSummary'] = $this->deviceCsv->officialSummary();

        $pdf = Pdf::loadView('admin.laporan-pdf', $data);

        $filename = 'laporan-monitoring-'
            .$data['period']['type'].'-'
            .now()->format('Y-m-d_His').'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export PDF laporan satu perangkat (identitas, availability, downtime,
     * response time, alert, dan riwayat status) pada periode aktif.
     */
    public function perangkatPdf(Request $request, int $device)
    {
        $row = Device::query()->find($device);

        if (! $row) {
            abort(404, 'Perangkat tidak ditemukan.');
        }

        $data = $this->perangkatService->report($row, $this->resolvePeriod($request));

        $pdf = Pdf::loadView('admin.laporan-pdf-perangkat', $data);

        $slug = Str::slug((string) $row->name) ?: 'perangkat';
        $slug = substr($slug, 0, 40);

        $filename = 'laporan-perangkat-'.$slug.'-'
            .$data['period']['type'].'-'
            .now()->format('Y-m-d_His').'.pdf';

        return $pdf->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | Periode
    |--------------------------------------------------------------------------
    */

    /**
     * Tentukan rentang waktu laporan dari input.
     * daily  : satu tanggal  -> 00:00:00 s/d 23:59:59
     * weekly : rentang tanggal (start_date .. end_date)
     * monthly: satu bulan (month = YYYY-MM)
     */
    protected function resolvePeriod(Request $request): array
    {
        $type = $request->query('period', 'daily');
        $type = in_array($type, ['daily', 'weekly', 'monthly'], true) ? $type : 'daily';

        $now = now();

        switch ($type) {
            case 'weekly':
                $start = $this->parseDate(
                    $request->query('start_date'),
                    $now->copy()->startOfDay()->subDays(6)
                )->startOfDay();
                $end = $this->parseDate(
                    $request->query('end_date'),
                    $now->copy()->startOfDay()
                )->endOfDay();

                if ($end->lt($start)) {
                    [$start, $end] = [$end, $start];
                }

                $label = $start->format('d M Y').' — '.$end->format('d M Y');
                $granularity = 'day';
                break;

            case 'monthly':
                $start = $this->parseMonth(
                    $request->query('month'),
                    $now
                )->startOfMonth();
                $end = (clone $start)->endOfMonth();

                $label = $start->format('F Y');
                $granularity = 'day';
                break;

            default:
                $start = $this->parseDate(
                    $request->query('date'),
                    $now
                )->startOfDay();
                $end = (clone $start)->endOfDay();

                $label = $start->format('d M Y');
                $granularity = 'hour';
        }

        if ($end->gt($now)) {
            $end = $now;
        }

        return [
            'type'        => $type,
            'start'       => $start,
            'end'         => $end,
            'label'       => $label,
            'granularity' => $granularity,
        ];
    }

    protected function parseDate($raw, Carbon $fallback): Carbon
    {
        if (! is_string($raw) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return $fallback->copy();
        }

        try {
            $date = Carbon::createFromFormat('Y-m-d', $raw);
            if ($date && $date->format('Y-m-d') === $raw) {
                return $date;
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return $fallback;
    }

    protected function parseMonth($raw, Carbon $fallback): Carbon
    {
        if (! is_string($raw) || ! preg_match('/^\d{4}-\d{2}$/', $raw)) {
            return $fallback->copy();
        }

        try {
            $month = Carbon::createFromFormat('Y-m', $raw);
            if ($month) {
                return $month;
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return $fallback;
    }

    /*
    |--------------------------------------------------------------------------
    | Build data laporan
    |--------------------------------------------------------------------------
    */

    protected function report(Request $request, bool $fullDetail = false): array
    {
        $period = $this->resolvePeriod($request);

        $totals   = $this->totals($period);
        $response = $this->responseTotals($period);

        $primarySeries   = $this->primarySeries($period);
        $downEpisodes    = $this->downEpisodes($primarySeries, $period);
        $warningEpisodes = $this->warningEpisodes($period);

        $episodes = $downEpisodes['list']
            ->merge($warningEpisodes['list'])
            ->sortByDesc(fn ($ep) => $ep['start'])
            ->values();

        // Lengkapi nama perangkat untuk episode berlangsung (device_name asalnya
        // 'NaN' karena baris penutup belum ada pada periode berjalan).
        $episodes = $this->resolveDeviceNames($episodes);

        $perDevice = $this->attachIncidents($this->perDevice($period), $episodes);

        $availability = $this->availabilitySeries($period);
        $responseSeries = $this->responseSeries($period);

        $devicesWithIncident = $perDevice
            ->filter(fn ($d) => (($d['incidents'] ?? 0) > 0) || (($d['downtime'] ?? 0) > 0))
            ->sortByDesc(fn ($d) => ($d['incidents'] * 1_000_000_000) + (int) ($d['downtime'] ?? 0))
            ->values();

        return [
            'period'          => $period,
            'kpi'             => $this->kpi($totals, $response, $downEpisodes, $warningEpisodes, $episodes),
            'summary'         => [
                'down'        => $downEpisodes['count'],
                'unreachable' => $warningEpisodes['count'],
                'recovery'    => $downEpisodes['recoveries'],
                'ongoing'     => $downEpisodes['ongoing'] + $warningEpisodes['ongoing'],
            ],
            'availability'    => $availability,
            'response'        => $responseSeries,
            'topDevices'      => $this->deviceCauses($devicesWithIncident, $episodes)->take(15),
            'history'         => $fullDetail
                ? $this->fullHistory($episodes, $request)
                : $this->paginateHistory($episodes, $request),
            'historyFilter'   => [
                'device'  => $request->query('device', ''),
                'options' => $episodes->groupBy('device_name')
                    ->mapWithKeys(fn ($rows, $name) => [(string) $name => $rows->first()['device_id']])
                    ->sort()
                    ->take(200),
            ],
            'existingDevices' => DB::table('device')->whereNotNull('id')->pluck('id')->map(fn ($id) => (int) $id)->flip(),
            'hasData'         => $totals['checks'] > 0 || $episodes->isNotEmpty(),
            'generatedAt'     => now()->format('d M Y H:i:s'),
            'modalData'       => $this->modalData($period, $perDevice, $episodes),
        ];
    }

    /**
     * Rangkum "penyebab bermasalah" per perangkat: kenapa bermasalah selama
     * seminggu terakhir dan satu bulan berjalan (dari episode incident).
     */
    protected function deviceCauses(Collection $topDevices, Collection $episodes): Collection
    {
        $now = now();
        $weekStart  = $now->copy()->subDays(7);
        $monthStart = $now->copy()->startOfMonth();

        $groups = $episodes->groupBy('device_id');

        $manualCauses = \App\Models\Device::query()
            ->whereNotNull('penyebab')
            ->where('penyebab', '!=', '')
            ->pluck('penyebab', 'id');

        return $topDevices->map(function ($device) use (
            $groups, $now, $weekStart, $monthStart, $manualCauses
        ) {
            $rows = $groups->get($device['device_id'] ?? null, collect());

            $sumDowntime = fn (Collection $r) => $r->sum(fn ($ep) => (int) ($ep['duration_seconds'] ?? 0));

            $weekRows  = $rows->filter(fn ($ep) => ($ep['start'] ?? null) instanceof Carbon && $ep['start'] >= $weekStart);
            $monthRows = $rows->filter(fn ($ep) => ($ep['start'] ?? null) instanceof Carbon && $ep['start'] >= $monthStart);

            $periodCount = $rows->count();
            $weekCount   = $weekRows->count();
            $monthCount  = $monthRows->count();

            $worst = $rows->sortByDesc(fn ($ep) => (int) ($ep['duration_seconds'] ?? 0))->first();
            $worstSeconds = $worst ? (int) ($worst['duration_seconds'] ?? 0) : 0;

            $last = $rows->sortByDesc(fn ($ep) => $ep['start'])->first();
            $ongoing = $rows->contains(fn ($ep) => ! empty($ep['ongoing']));
            $lastLabel = $ongoing ? 'berlangsung' : $this->relativeTime($last['start'] ?? null, $now);

            if ($periodCount === 0) {
                $problem = [
                    'cause' => 'Belum tercatat',
                    'tone'  => 'info',
                ];
            } elseif ($weekCount >= 2 && $sumDowntime($weekRows) <= 1800) {
                $problem = ['cause' => 'Down berulang (flapping)', 'tone' => 'warn'];
            } elseif ($worstSeconds >= 3600) {
                $problem = ['cause' => 'Outage berkepanjangan', 'tone' => 'danger'];
            } elseif ($monthCount >= 5) {
                $problem = ['cause' => 'Sering bermasalah', 'tone' => 'warn'];
            } else {
                $problem = ['cause' => 'Episode singkat berulang', 'tone' => 'info'];
            }

            return array_merge($device, [
                'problem' => $problem + [
                    'period_count' => $periodCount,
                    'period_down'  => $sumDowntime($rows),
                    'week_count'   => $weekCount,
                    'week_down'    => $sumDowntime($weekRows),
                    'month_count'  => $monthCount,
                    'month_down'   => $sumDowntime($monthRows),
                    'worst_label'  => \App\Helpers\LaporanFormat::duration($worstSeconds),
                    'last_label'   => $lastLabel,
                    'cause_manual' => $manualCauses->get((int) ($device['device_id'] ?? 0)) ?: null,
                ],
            ]);
        });
    }

    /**
     * Waktu relatif dalam Bahasa Indonesia ("5 jam lalu").
     */
    protected function relativeTime(?\Carbon\CarbonInterface $time, Carbon $now): ?string
    {
        if (! $time instanceof Carbon) {
            return null;
        }

        $seconds = (int) $time->diffInSeconds($now, true);

        if ($seconds < 60) {
            return 'baru saja';
        }
        if ($seconds < 3600) {
            return floor($seconds / 60).' menit lalu';
        }
        if ($seconds < 86400) {
            return floor($seconds / 3600).' jam lalu';
        }

        return floor($seconds / 86400).' hari lalu';
    }

    /**
     * Payload per-perangkat untuk modal stat card. Data dibawa sekali bersama
     * periode aktif (JS yang sama dengan chart), sehingga otomatis ikut update
     * setiap user berganti periode / reload.
     */
    protected function modalData(array $period, Collection $perDevice, Collection $episodes): array
    {
        $episodePayload = fn (array $ep) => [
            'device_id' => $ep['device_id'],
            'name'      => ($ep['device_name'] ?? null) ?: 'NaN',
            'ip'        => $ep['ip_address'] ?? null,
            'status'    => $ep['status'] ?? '',
            'start'     => ($ep['start'] ?? null) instanceof Carbon
                ? $ep['start']->format('Y-m-d H:i:s')
                : ($ep['start'] ?? null),
            'duration'  => (int) ($ep['duration_seconds'] ?? 0),
            'response'  => $ep['response_time'] ?? null,
            'ongoing'   => (bool) ($ep['ongoing'] ?? false),
        ];

        return [
            'uptime'    => $perDevice->map(fn ($d) => [
                'device_id' => $d['device_id'],
                'name'      => $d['device_name'] ?: 'NaN',
                'ip'        => $d['ip_address'] ?? null,
                'checks'    => (int) $d['checks'],
                'up'        => (int) $d['up'],
                'down'      => (int) $d['down'],
                'uptime'    => $d['uptime'] !== null ? (float) $d['uptime'] : null,
            ])->values()->all(),
            'response'  => $this->perDeviceResponse($period)->all(),
            'down'      => $episodes->filter(fn ($ep) => ($ep['status'] ?? '') === 'down')->map($episodePayload)->values()->all(),
            'incidents' => $episodes->map($episodePayload)->values()->all(),
        ];
    }

    /**
     * Respon time agregat per perangkat (untuk modal Avg Response).
     */
    protected function perDeviceResponse(array $period): Collection
    {
        return $this->primaryQuery($period)
            ->whereNotNull('response_time')
            ->selectRaw(
                "COALESCE(device_id, 0) as `device_id`, "
                ."MAX(device_name) as `name`, "
                ."MAX(ip_address) as `ip`, "
                ."ROUND(AVG(response_time), 0) as `avg`, "
                ."MIN(response_time) as `min`, "
                ."MAX(response_time) as `max`, "
                ."COUNT(*) as `n`"
            )
            ->groupBy('device_id')
            ->orderByDesc('avg')
            ->get()
            ->map(function ($row) {
                $avg = $row->avg !== null ? (int) $row->avg : null;

                return [
                    'device_id' => (int) ($row->device_id ?? 0),
                    'name'      => ($row->name ?? '') ?: 'NaN',
                    'ip'        => $row->ip ?? null,
                    'avg'       => $avg,
                    'min'       => $row->min !== null ? (int) $row->min : null,
                    'max'       => $row->max !== null ? (int) $row->max : null,
                ];
            });
    }

    protected function kpi(
        array $totals,
        array $response,
        array $down,
        array $unreachable,
        Collection $episodes
    ): array {
        return [
            'uptime'        => $totals['checks'] > 0
                ? round(($totals['up'] / $totals['checks']) * 100, 2)
                : null,
            'checks'        => (int) $totals['checks'],
            'up'            => (int) $totals['up'],
            'down_checks'   => (int) $totals['down'],
            'down'          => $down['count'],
            'incidents'     => $down['count'] + $unreachable['count'],
            'affected'      => $episodes->pluck('device_id')->unique()->count(),
            'avg_response'  => $response['avg'],
            'min_response'  => $response['min'],
            'max_response'  => $response['max'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Query data kasar dari tabel monitoring (read-only)
    |--------------------------------------------------------------------------
    */

    /**
     * Base query telemetry utama: monitoring_logs + log_archives (skema sama),
     * dibungkus derived table agar agregasi bekerja di atas seluruh baris.
     */
    protected function primaryQuery(array $period)
    {
        $columns = ['device_id', 'device_name', 'ip_address', 'status', 'response_time', 'checked_at'];

        $current = DB::table('monitoring_logs')
            ->select($columns)
            ->whereBetween('checked_at', [$period['start'], $period['end']]);

        $archive = DB::table('log_archives')
            ->select($columns)
            ->whereBetween('checked_at', [$period['start'], $period['end']]);

        return DB::query()->fromSub($current->unionAll($archive), 'logs');
    }

    protected function totals(array $period): array
    {
        $row = $this->primaryQuery($period)
            ->selectRaw(
                "COUNT(*) as `checks`, "
                ."SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as `up`, "
                ."SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as `down`, "
                ."SUM(CASE WHEN status NOT IN ('up','down') THEN 1 ELSE 0 END) as `other`"
            )
            ->first();

        return [
            'checks' => (int) ($row->checks ?? 0),
            'up'     => (int) ($row->up ?? 0),
            'down'   => (int) ($row->down ?? 0),
            'other'  => (int) ($row->other ?? 0),
        ];
    }

    protected function responseTotals(array $period): array
    {
        $row = $this->primaryQuery($period)
            ->whereNotNull('response_time')
            ->selectRaw(
                "ROUND(AVG(response_time), 0) as `avg`, "
                ."MIN(response_time) as `min`, "
                ."MAX(response_time) as `max`, "
                ."COUNT(*) as `n`"
            )
            ->first();

        return [
            'avg' => $row->n > 0 ? (int) $row->avg : null,
            'min' => $row->n > 0 ? (int) $row->min : null,
            'max' => $row->n > 0 ? (int) $row->max : null,
        ];
    }

    /**
     * Isi device_name episode yang kosong ('NaN') dari tabel device.
     */
    protected function resolveDeviceNames(Collection $episodes): Collection
    {
        $ids = $episodes->pluck('device_id')
            ->filter(fn ($id) => (int) $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return $episodes;
        }

        $names = DB::table('device')
            ->whereIn('id', $ids)
            ->pluck('name', 'id');

        return $episodes->map(function (array $ep) use ($names) {
            $id = (int) ($ep['device_id'] ?? 0);

            if ($id > 0 && ($ep['device_name'] === null || $ep['device_name'] === 'NaN') && isset($names[$id])) {
                $ep['device_name'] = $names[$id];
            }

            return $ep;
        });
    }

    /**
     * Statistik agregat per perangkat.
     */
    protected function perDevice(array $period): Collection
    {
        return $this->primaryQuery($period)
            ->selectRaw(
                "COALESCE(device_id, 0) as `device_id`, "
                ."MAX(device_name) as `device_name`, "
                ."MAX(ip_address) as `ip_address`, "
                ."COUNT(*) as `checks`, "
                ."SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as `up`, "
                ."SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as `down`"
            )
            ->groupBy('device_id')
            ->get()
            ->map(function ($row) {
                $checks = (int) $row->checks;

                return [
                    'device_id'   => (int) ($row->device_id ?? 0),
                    'device_name' => $row->device_name ?: 'NaN',
                    'ip_address'  => $row->ip_address,
                    'checks'      => $checks,
                    'up'          => (int) $row->up,
                    'down'        => (int) $row->down,
                    'uptime'      => $checks > 0 ? round(((int) $row->up / $checks) * 100, 2) : null,
                    'incidents'   => 0,
                    'downtime'    => 0,
                ];
            });
    }

    /**
     * Ambil seluruh baris telemetry uraian waktu (untuk deteksi transisi).
     */
    protected function primarySeries(array $period): Collection
    {
        return $this->primaryQuery($period)
            ->select('device_id', 'device_name', 'ip_address', 'status', 'response_time', 'checked_at')
            ->orderBy('checked_at')
            ->get();
    }

    /**
     * Sambungkan statistik incident & total downtime ke agregat per perangkat.
     * Incident dihitung dari jumlah episode (down + unreachable), downtime dari
     * total durasi seluruh episode pada periode berjalan.
     */
    protected function attachIncidents(Collection $devices, Collection $episodes): Collection
    {
        $byDevice = $episodes->groupBy('device_id');

        return $devices->map(function (array $device) use ($byDevice) {
            $rows = $byDevice->get($device['device_id'] ?? 0, collect());

            $device['incidents'] = $rows->count();
            $device['downtime']  = (int) $rows->sum('duration_seconds');

            return $device;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Deret (chart)
    |--------------------------------------------------------------------------
    */

    protected function bucketExpression(string $granularity): string
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return $granularity === 'hour'
                ? "strftime('%Y-%m-%d %H:00:00', checked_at)"
                : "date(checked_at)";
        }

        return $granularity === 'hour'
            ? "DATE_FORMAT(checked_at, '%Y-%m-%d %H:00:00')"
            : 'DATE(checked_at)';
    }

    protected function availabilitySeries(array $period): array
    {
        $expression = $this->bucketExpression($period['granularity']);

        $buckets = $this->primaryQuery($period)
            ->selectRaw(
                $expression.' as `bucket`, '
                ."COUNT(*) as `total`, "
                ."SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as `up`"
            )
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->pluck(null, 'bucket');

        list($labels, $keys) = $this->axis($period);

        $availability = [];

        foreach ($keys as $index => $key) {
            $bucket = $buckets[$key] ?? null;
            $total  = (int) ($bucket->total ?? 0);

            $availability[] = $total > 0
                ? round(((int) ($bucket->up ?? 0) / $total) * 100, 2)
                : null;
        }

        return compact('labels', 'availability');
    }

    protected function responseSeries(array $period): array
    {
        $expression = $this->bucketExpression($period['granularity']);

        $buckets = $this->primaryQuery($period)
            ->whereNotNull('response_time')
            ->selectRaw(
                $expression.' as `bucket`, '
                ."ROUND(AVG(response_time), 0) as `avg`, "
                ."MIN(response_time) as `min`, "
                ."MAX(response_time) as `max`"
            )
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->pluck(null, 'bucket');

        list($labels, $keys) = $this->axis($period);

        $avg = $min = $max = [];

        foreach ($keys as $index => $key) {
            $bucket = $buckets[$key] ?? null;

            $avg[] = $bucket ? (int) $bucket->avg : null;
            $min[] = $bucket ? (int) $bucket->min : null;
            $max[] = $bucket ? (int) $bucket->max : null;
        }

        return compact('labels', 'avg', 'min', 'max');
    }

    /**
     * Sumbu X yang mengikuti periode: per jam (harian) atau per hari.
     * Mengembalikan [labels, bucketKeys].
     */
    protected function axis(array $period): array
    {
        $labels = [];
        $keys   = [];

        $cursor = $period['start']->copy();

        while ($cursor->lte($period['end'])) {
            if ($period['granularity'] === 'hour') {
                $labels[] = $cursor->format('H:i');
                $keys[]   = $cursor->format('Y-m-d H:00:00');
                $cursor->addHour();
            } else {
                $labels[] = $cursor->format('d M');
                $keys[]   = $cursor->format('Y-m-d');
                $cursor->addDay();
            }
        }

        return [$labels, $keys];
    }

    /*
    |--------------------------------------------------------------------------
    | Incident (episode) & recovery
    |--------------------------------------------------------------------------
    */

    /**
     * Deteksi episode DOWN dari transisi status telemetry utama.
     * UP -> DOWN = incident baru; DOWN -> DOWN = episode sama;
     * DOWN -> UP = recovery.
     */
    protected function downEpisodes(Collection $rows, array $period): array
    {
        $inDown = [];
        $list   = [];

        $recoveries = 0;
        $ongoing    = 0;

        foreach ($rows as $row) {
            $id = (int) ($row->device_id ?? 0);

            if ($row->status === 'down') {
                if (! isset($inDown[$id])) {
                    $inDown[$id] = $this->asTime($row->checked_at);
                }
            } else {
                if (isset($inDown[$id])) {
                    $list[] = [
                        'device_id'        => $id,
                        'device_name'      => $row->device_name ?: 'NaN',
                        'ip_address'       => $row->ip_address,
                        'status'           => 'down',
                        'start'            => $inDown[$id],
                        'end'              => $this->asTime($row->checked_at),
                        'duration_seconds' => abs($this->asTime($row->checked_at)->diffInSeconds($inDown[$id])),
                        'response_time'    => $row->response_time,
                        'ongoing'          => false,
                    ];

                    $recoveries++;
                    unset($inDown[$id]);
                }
            }
        }

        $endBound = $period['end']->copy();

        foreach ($inDown as $id => $start) {
            $ongoing++;

            $list[] = [
                'device_id'        => $id,
                'device_name'      => 'NaN',
                'ip_address'       => null,
                'status'           => 'down',
                'start'            => $start,
                'end'              => null,
                'duration_seconds' => abs($endBound->diffInSeconds($start)),
                'response_time'    => null,
                'ongoing'          => true,
            ];
        }

        return [
            'count'      => count($list),
            'recoveries' => $recoveries,
            'ongoing'    => $ongoing,
            'list'       => collect($list),
        ];
    }

    /**
     * Deteksi episode UNREACHABLE (status 'warning' / Timed Out)
     * dari activity_logs (network:poll).
     */
    protected function warningEpisodes(array $period): array
    {
        $rows = ActivityLog::query()
            ->whereBetween('log_time', [$period['start'], $period['end']])
            ->select('device_id', 'device_name', 'ip_address', 'status', 'latency', 'log_time')
            ->orderBy('log_time')
            ->get();

        $inWarning = [];
        $list      = [];

        $ongoing = 0;

        foreach ($rows as $row) {
            $id = (int) ($row->device_id ?? 0);

            if ($row->status === 'warning') {
                if (! isset($inWarning[$id])) {
                    $inWarning[$id] = $this->asTime($row->log_time);
                }
            } else {
                if (isset($inWarning[$id])) {
                    $end = $this->asTime($row->log_time);

                    $list[] = [
                        'device_id'        => $id,
                        'device_name'      => $row->device_name ?: 'NaN',
                        'ip_address'       => $row->ip_address,
                        'status'           => 'unreachable',
                        'start'            => $inWarning[$id],
                        'end'              => $end,
                        'duration_seconds' => abs($end->diffInSeconds($inWarning[$id])),
                        'response_time'    => null,
                        'ongoing'          => false,
                    ];

                    unset($inWarning[$id]);
                }
            }
        }

        $endBound = $period['end']->copy();

        foreach ($inWarning as $id => $start) {
            $ongoing++;

            $list[] = [
                'device_id'        => $id,
                'device_name'      => 'NaN',
                'ip_address'       => null,
                'status'           => 'unreachable',
                'start'            => $start,
                'end'              => null,
                'duration_seconds' => abs($endBound->diffInSeconds($start)),
                'response_time'    => null,
                'ongoing'          => true,
            ];
        }

        return [
            'count'   => count($list),
            'ongoing' => $ongoing,
            'list'    => collect($list),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Incident history (tabel + pagination)
    |--------------------------------------------------------------------------
    */

    /**
     * Seluruh detail incident untuk PDF (dibatasi 500 baris terakhir agar
     * ukuran file tetap wajar, tetap mematuhi filter perangkat bila ada).
     */
    protected function fullHistory(Collection $episodes, Request $request): array
    {
        $deviceFilter = $request->query('device', '');

        if ($deviceFilter !== '') {
            $episodes = $episodes->filter(fn ($ep) => (string) $ep['device_id'] === (string) $deviceFilter);
        }

        $total = $episodes->count();

        $items = $episodes
            ->take(500)
            ->values()
            ->map(function ($ep) {
                $ep['start'] = $ep['start'] instanceof Carbon ? $ep['start']->format('Y-m-d H:i:s') : $ep['start'];
                $ep['end']   = $ep['end'] instanceof Carbon ? $ep['end']->format('Y-m-d H:i:s') : null;

                return $ep;
            });

        return [
            'items'    => $items,
            'total'    => $total,
            'page'     => 1,
            'perPage'  => max(1, $items->count()),
            'lastPage' => 1,
        ];
    }

    protected function paginateHistory(Collection $episodes, Request $request): array
    {
        $deviceFilter = $request->query('device', '');

        if ($deviceFilter !== '') {
            $episodes = $episodes->filter(fn ($ep) => (string) $ep['device_id'] === (string) $deviceFilter);
        }

        $total = $episodes->count();
        $page  = max(1, (int) $request->query('page', 1));
        $per   = self::PER_PAGE;

        $items = $episodes
            ->forPage($page, $per)
            ->values()
            ->map(function ($ep) {
                $ep['start'] = $ep['start'] instanceof Carbon ? $ep['start']->format('Y-m-d H:i:s') : $ep['start'];
                $ep['end']   = $ep['end'] instanceof Carbon ? $ep['end']->format('Y-m-d H:i:s') : null;

                return $ep;
            });

        $lastPage = max(1, (int) ceil($total / $per));

        return [
            'items'     => $items,
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $per,
            'lastPage'  => $lastPage,
        ];
    }

    protected function asTime($value): Carbon
    {
        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }
}