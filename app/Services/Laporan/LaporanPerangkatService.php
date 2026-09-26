<?php

namespace App\Services\Laporan;

use App\Models\ActivityLog;
use App\Models\Device;
use App\Models\DeviceNotification;
use App\Services\DeviceCsvService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Laporan per perangkat untuk halaman "Laporan & Grafik".
 *
 * Seluruh angka dihitung dari data nyata (monitoring_logs + log_archives +
 * activity_logs + device_notifications). Tidak ada nilai dummy: metrik yang
 * tidak punya sumber data bernilai null dan ditampilkan sebagai "-" di PDF.
 *
 * Telemetry digabung dari monitoring_logs (data berjalan) dan log_archives
 * (arsip), sama seperti laporan keseluruhan, agar angka per perangkat dan
 * angka total tidak berbeda sumber.
 */
class LaporanPerangkatService
{
    public function __construct(protected DeviceCsvService $deviceCsv) {}

    /**
     * Daftar perangkat resmi untuk dropdown pilih perangkat.
     * Sumber: devices.csv (source of truth aplikasi), bukan tabel device mentah.
     *
     * @return array<int, array{id:int,name:string,ip:?string,status:?string}>
     */
    public function options(): array
    {
        return $this->deviceCsv->officialDevices()
            ->map(fn (Device $d) => [
                'id'     => (int) $d->id,
                'name'   => (string) $d->name,
                'ip'     => $d->ip_address ?: null,
                'status' => $d->status ?: null,
            ])
            ->sortBy(fn ($row) => mb_strtolower((string) $row['name']))
            ->values()
            ->all();
    }

    /**
     * Susun laporan lengkap satu perangkat untuk periode aktif.
     *
     * @param  array{start:Carbon,end:Carbon,granularity:string}  $period
     * @return array<string,mixed>
     */
    public function report(Device $device, array $period): array
    {
        $totals    = $this->totals($this->telemetry($device, $period));
        $response  = $this->responseTotals($this->telemetry($device, $period));

        $rows    = $this->seriesRows($this->telemetry($device, $period));
        $down    = $this->downEpisodes($rows, $period);
        $warning = $this->warningEpisodes($device, $period);

        $episodes = collect($down['list'])
            ->merge($warning['list'])
            ->sortByDesc(fn ($ep) => $ep['start'])
            ->values();

        return [
            'device'     => $this->identity($device),
            'period'     => $period,
            'metrics'    => $this->metrics($totals, $response, $down, $warning, $episodes, $device, $period),
            'availability' => $this->availabilitySeries($this->telemetry($device, $period), $period),
            'response'     => $this->responseSeries($this->telemetry($device, $period), $period),
            'history'    => $episodes->map(fn ($ep) => [
                'status'           => $ep['status'],
                'start'            => $this->asTime($ep['start'])->format('d M Y H:i:s'),
                'end'              => $ep['end'] ? $this->asTime($ep['end'])->format('d M Y H:i:s') : null,
                'duration_seconds' => (int) $ep['duration_seconds'],
                'response_time'    => $ep['response_time'],
                'ongoing'          => (bool) $ep['ongoing'],
            ])->all(),
            'hasData'    => $totals['checks'] > 0 || $episodes->isNotEmpty(),
            'generatedAt'=> now()->format('d M Y H:i:s'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Identitas perangkat
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<string,mixed>
     */
    protected function identity(Device $device): array
    {
        return [
            'id'        => (int) $device->id,
            'name'      => (string) $device->name,
            'ip'        => $device->ip_address ?: null,
            'type'      => $this->typeLabel($device),
            'protocol'  => $this->protocolLabel($device),
            'location'  => $this->locationLabel($device),
            'deskripsi' => $this->text($device->deskripsi),
            'status'    => strtoupper((string) ($device->status ?: '')) ?: null,
            'polls'     => $device->polls !== null ? (int) $device->polls : null,
        ];
    }

    /**
     * Tipe perangkat. Nilai DB dipakai apa adanya; kalau kolom kosong baru
     * ditebak dari nama/icon (helper yang sama dengan halaman detail).
     */
    protected function typeLabel(Device $device): ?string
    {
        $raw = trim((string) ($device->type ?: ''));

        if ($raw !== '') {
            return $raw;
        }

        try {
            $inferred = \App\Helpers\DeviceType::inferType($device->name, $device->icon_type);

            return $inferred !== '' ? $inferred : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Metode monitoring = protocol ping yang dipakai perangkat.
     */
    protected function protocolLabel(Device $device): ?string
    {
        $raw = strtoupper(trim((string) ($device->protocol ?: '')));

        if ($raw === '') {
            return null;
        }

        return match ($raw) {
            'ICMP'  => 'ICMP Ping',
            'TCP'   => 'TCP Ping',
            'HTTP'  => 'HTTP',
            default => $raw,
        };
    }

    /**
     * Router / lokasi perangkat.
     */
    protected function locationLabel(Device $device): ?string
    {
        foreach (['pop_name', 'deskripsi'] as $field) {
            $value = trim((string) ($device->{$field} ?: ''));

            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    protected function text(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Metrik periode
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<string,mixed>
     */
    protected function metrics(
        array $totals,
        array $response,
        array $down,
        array $warning,
        Collection $episodes,
        Device $device,
        array $period
    ): array {
        $checks = $totals['checks'];
        $downtime = (int) $down['downtime'] + (int) $warning['downtime'];

        return [
            'checks'        => $checks,
            'up'            => $totals['up'],
            'down'          => $totals['down'],
            'other'         => $totals['other'],
            'availability'  => $checks > 0
                ? round(($totals['up'] / $checks) * 100, 2)
                : null,
            'avg_response'  => $response['avg'],
            'min_response'  => $response['min'],
            'max_response'  => $response['max'],
            'downtime'      => $downtime,
            'downtime_text' => $downtime > 0 ? \App\Helpers\LaporanFormat::duration($downtime) : null,
            'incidents'     => $episodes->count(),
            'incident_down' => $down['count'],
            'incident_unreach' => $warning['count'],
            'recovered'     => $down['recoveries'],
            'ongoing'       => $episodes->filter(fn ($ep) => ! empty($ep['ongoing']))->count(),
            'alerts'        => $this->alertCount($device, $period),
            'current_status'=> strtoupper((string) ($device->status ?: '')) ?: null,
            'last_seen'     => $this->lastSeen($device, $period),
        ];
    }

    /**
     * Jumlah alert perangkat pada periode (device_notifications).
     */
    protected function alertCount(Device $device, array $period): ?int
    {
        $count = DeviceNotification::query()
            ->where('device_id', $device->id)
            ->whereBetween('checked_at', [$period['start'], $period['end']])
            ->count();

        return (int) $count;
    }

    /**
     * Waktu pengecekan terakhir pada periode.
     */
    protected function lastSeen(Device $device, array $period): ?string
    {
        $row = DB::table('monitoring_logs')
            ->where('device_id', $device->id)
            ->whereBetween('checked_at', [$period['start'], $period['end']])
            ->max('checked_at');

        return $row ? Carbon::parse($row)->format('d M Y H:i:s') : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Query telemetry
    |--------------------------------------------------------------------------
    */

    /**
     * Builder telemetry baru untuk satu perangkat pada periode.
     *
     * Sengaja mengembalikan instance baru setiap panggilan: tiap agregasi
     * menambah select/groupBy sendiri, dan builder yang dipakai ulang akan
     * menumpukan klausa dari query sebelumnya.
     */
    protected function telemetry(Device $device, array $period)
    {
        $columns = ['device_id', 'device_name', 'ip_address', 'status', 'response_time', 'checked_at'];

        $ip = (string) ($device->ip_address ?: '');

        $match = function ($query) use ($device, $ip) {
            $query->where(function ($q) use ($device, $ip) {
                $q->where('device_id', $device->id);

                if ($ip !== '') {
                    $q->orWhere(function ($sub) use ($ip) {
                        $sub->where(function ($inner) {
                            $inner->whereNull('device_id')->orWhere('device_id', 0);
                        })->where('ip_address', $ip);
                    });
                }
            });
        };

        $current = DB::table('monitoring_logs')->select($columns)->whereBetween('checked_at', [$period['start'], $period['end']]);
        $archive = DB::table('log_archives')->select($columns)->whereBetween('checked_at', [$period['start'], $period['end']]);

        $match($current);
        $match($archive);

        return DB::query()->fromSub($current->unionAll($archive), 'logs');
    }

    protected function totals($query): array
    {
        $row = $query->selectRaw(
            "COUNT(*) as `checks`, "
            ."SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as `up`, "
            ."SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as `down`, "
            ."SUM(CASE WHEN status NOT IN ('up','down') THEN 1 ELSE 0 END) as `other`"
        )->first();

        return [
            'checks' => (int) ($row->checks ?? 0),
            'up'     => (int) ($row->up ?? 0),
            'down'   => (int) ($row->down ?? 0),
            'other'  => (int) ($row->other ?? 0),
        ];
    }

    protected function responseTotals($query): array
    {
        $row = (clone $query)
            ->whereNotNull('response_time')
            ->selectRaw(
                "ROUND(AVG(response_time), 0) as `avg`, "
                ."MIN(response_time) as `min`, "
                ."MAX(response_time) as `max`, "
                ."COUNT(*) as `n`"
            )
            ->first();

        $has = (int) ($row->n ?? 0) > 0;

        return [
            'avg' => $has ? (int) $row->avg : null,
            'min' => $has ? (int) $row->min : null,
            'max' => $has ? (int) $row->max : null,
        ];
    }

    protected function seriesRows($query): Collection
    {
        return $query
            ->select('status', 'response_time', 'checked_at')
            ->orderBy('checked_at')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Episode (riwayat status)
    |--------------------------------------------------------------------------
    */

    /**
     * Episode DOWN dari transisi status telemetry.
     */
    protected function downEpisodes(Collection $rows, array $period): array
    {
        $start = null;
        $list  = [];
        $recoveries = 0;

        foreach ($rows as $row) {
            if ($row->status === 'down') {
                $start ??= $this->asTime($row->checked_at);

                continue;
            }

            if ($start === null) {
                continue;
            }

            $end = $this->asTime($row->checked_at);

            $list[] = [
                'status'           => 'down',
                'start'            => $start,
                'end'              => $end,
                'duration_seconds' => abs($end->diffInSeconds($start)),
                'response_time'    => $row->response_time,
                'ongoing'          => false,
            ];

            $recoveries++;
            $start = null;
        }

        if ($start !== null) {
            $list[] = [
                'status'           => 'down',
                'start'            => $start,
                'end'              => null,
                'duration_seconds' => abs($period['end']->diffInSeconds($start)),
                'response_time'    => null,
                'ongoing'          => true,
            ];
        }

        return [
            'count'      => count($list),
            'recoveries' => $recoveries,
            'ongoing'    => count(array_filter($list, fn ($ep) => $ep['ongoing'])),
            'downtime'   => array_sum(array_column($list, 'duration_seconds')),
            'list'       => $list,
        ];
    }

    /**
     * Episode UNREACHABLE (status warning / timed out) dari activity_logs.
     */
    protected function warningEpisodes(Device $device, array $period): array
    {
        $rows = ActivityLog::query()
            ->where('device_id', $device->id)
            ->whereBetween('log_time', [$period['start'], $period['end']])
            ->select('status', 'log_time')
            ->orderBy('log_time')
            ->get();

        $start = null;
        $list  = [];

        foreach ($rows as $row) {
            if ($row->status === 'warning') {
                $start ??= $this->asTime($row->log_time);

                continue;
            }

            if ($start === null) {
                continue;
            }

            $end = $this->asTime($row->log_time);

            $list[] = [
                'status'           => 'unreachable',
                'start'            => $start,
                'end'              => $end,
                'duration_seconds' => abs($end->diffInSeconds($start)),
                'response_time'    => null,
                'ongoing'          => false,
            ];

            $start = null;
        }

        if ($start !== null) {
            $list[] = [
                'status'           => 'unreachable',
                'start'            => $start,
                'end'              => null,
                'duration_seconds' => abs($period['end']->diffInSeconds($start)),
                'response_time'    => null,
                'ongoing'          => true,
            ];
        }

        return [
            'count'    => count($list),
            'ongoing'  => count(array_filter($list, fn ($ep) => $ep['ongoing'])),
            'downtime' => array_sum(array_column($list, 'duration_seconds')),
            'list'     => $list,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Deret (grafik)
    |--------------------------------------------------------------------------
    */

    protected function bucketExpression(string $granularity): string
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return $granularity === 'hour'
                ? "strftime('%Y-%m-%d %H:00:00', checked_at)"
                : 'date(checked_at)';
        }

        return $granularity === 'hour'
            ? "DATE_FORMAT(checked_at, '%Y-%m-%d %H:00:00')"
            : 'DATE(checked_at)';
    }

    protected function availabilitySeries($query, array $period): array
    {
        $expression = $this->bucketExpression($period['granularity']);

        $buckets = (clone $query)
            ->selectRaw(
                $expression.' as `bucket`, '
                .'COUNT(*) as `total`, '
                .'SUM(CASE WHEN status = \'up\' THEN 1 ELSE 0 END) as `up`'
            )
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->pluck(null, 'bucket');

        list($labels, $keys) = $this->axis($period);

        $availability = [];

        foreach ($keys as $key) {
            $bucket = $buckets[$key] ?? null;
            $total  = (int) ($bucket->total ?? 0);

            $availability[] = $total > 0
                ? round(((int) ($bucket->up ?? 0) / $total) * 100, 2)
                : null;
        }

        return compact('labels', 'availability');
    }

    protected function responseSeries($query, array $period): array
    {
        $expression = $this->bucketExpression($period['granularity']);

        $buckets = (clone $query)
            ->whereNotNull('response_time')
            ->selectRaw(
                $expression.' as `bucket`, '
                .'ROUND(AVG(response_time), 0) as `avg`, '
                .'MIN(response_time) as `min`, '
                .'MAX(response_time) as `max`'
            )
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get()
            ->pluck(null, 'bucket');

        list($labels, $keys) = $this->axis($period);

        $avg = $min = $max = [];

        foreach ($keys as $key) {
            $bucket = $buckets[$key] ?? null;

            $avg[] = $bucket ? (int) $bucket->avg : null;
            $min[] = $bucket ? (int) $bucket->min : null;
            $max[] = $bucket ? (int) $bucket->max : null;
        }

        return compact('labels', 'avg', 'min', 'max');
    }

    /**
     * Sumbu X mengikuti periode: per jam (harian) atau per hari.
     *
     * @return array{0:array<int,string>,1:array<int,string>}
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

    protected function asTime($value): Carbon
    {
        return $value instanceof Carbon ? $value : Carbon::parse($value);
    }
}
