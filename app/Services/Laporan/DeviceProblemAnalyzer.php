<?php

namespace App\Services\Laporan;

use App\Helpers\LaporanFormat;
use App\Models\Device;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Analyzer masalah perangkat — SELURUHNYA berbasis histori monitoring nyata.
 *
 * Prinsip kelas ini:
 *
 *  1. Tidak ada penyebab fisik yang ditebak. Data monitoring project ini
 *     hanya merekam hasil ICMP ping (status up/down + response_time),
 *     sehingga penyebab seperti "kabel putus", "power bermasalah", atau
 *     "rusak" TIDAK PERNAH dibangkitkan oleh kelas ini.
 *  2. Setiap klaim harus bisa ditunjuk ke angka nyata dari histori
 *     (jumlah pemeriksaan, transisi, durasi episode, latency).
 *  3. Pemisahan wajib antara tiga tingkat keyakinan:
 *       - facts        : fakta, selalu diiringi angka yang tercatat.
 *       - findings     : pola yang terdeteksi dari faktanya.
 *       - possibilities: kemungkinAN yang belum terbukti, wajib diperiksa.
 *  4. Kalau datanya tidak cukup, jawabannya "Bukti belum memadai" —
 *     analyzer tidak dipaksa menebak pola.
 *
 * Sumber data (sudah dipakai aplikasi ini, tidak ada tabel/field baru):
 *   - monitoring_logs : device_id, device_name, ip_address, status,
 *                      response_time (ms, NULL saat down), checked_at
 *   - log_archives    : skema identik, log lama yang sudah diarsipkan
 *   - device          : name, ip_address, status, penyebab (catatan operator)
 *
 * Catatan penting mengenai response_time = 0:
 * MonitorDevices::parseLatency() menyimpan 0 bila parsing rtt gagal walaupun status
 * up. Angka 0 berarti "latency tidak terbaca", BUKAN 0 ms, jadi tidak boleh
 * dipakai sebagai basis kesimpulan latensi.
 */
class DeviceProblemAnalyzer
{
    /** Durasi (detik) yang dipakai sebagai ambang "outage panjang". */
    public const LONG_OUTAGE_SECONDS = 3600;

    /** Jumlah pemeriksaan DOWN beruntun yang juga dianggap outage panjang. */
    public const LONG_OUTAGE_POLLS = 20;

    /** Minimal sample latency (dengan rt > 0) sebelum boleh disimpulkan. */
    public const MIN_LATENCY_SAMPLES = 5;

    /** Rasio & selisih absolut untuk menyimpulkan latency meningkat. */
    public const LATENCY_RATIO = 1.5;
    public const LATENCY_DELTA_MS = 20;

    /** Rasio max/avg yang menandai lonjakan latency. */
    public const LATENCY_SPIKE_RATIO = 3.0;

    /** Rasio DOWN terhadap total pemeriksaan yang menandai tidak stabil. */
    public const HIGH_DOWN_RATIO = 0.20;

    /** Minimal jumlah episode DOWN terpisah untuk menyebut intermiten. */
    public const MIN_OUTAGE_EVENTS = 2;

    /** Panjang jendela "terbaru" untuk membandingkan latency (fraksi periode). */
    public const RECENT_WINDOW = 0.25;

    /** Batas jumlah episode yang ditampilkan di detail. */
    public const MAX_OUTAGES_SHOWN = 20;

    /**
     * Analisa banyak perangkat dalam SATU query (menghindari N+1).
     *
     * @param  Collection<int,Device>  $devices
     * @return Collection<int,array>  key = device id
     */
    public function analyzeMany(Collection $devices, array $period): Collection
    {
        $devices = $devices->values();

        if ($devices->isEmpty()) {
            return collect();
        }

        $series = $this->loadSeriesFor($devices, $period);

        return $devices->mapWithKeys(function (Device $device) use ($series, $period): array {
            $rows = $series->get((int) $device->id, collect());

            return [(int) $device->id => $this->analyzeWithRows($device, $period, $rows)];
        });
    }

    /**
     * Analisa satu perangkat (memuat serinya sendiri bila belum diberikan).
     */
    public function analyze(Device $device, array $period, ?Collection $rows = null): array
    {
        $rows ??= $this->loadSeriesFor(collect([$device]), $period)->get((int) $device->id, collect());

        return $this->analyzeWithRows($device, $period, $rows);
    }

    /*
    |--------------------------------------------------------------------------
    | Pengambilan data (read-only)
    |--------------------------------------------------------------------------
    */

    /**
     * Muat histori monitoring_logs + log_archives untuk sekumpulan device.
     *
     * device_id bisa NULL/0 pada baris lama, jadi baris tersebut dicocokkan
     * lewat ip_address — logika yang sama dengan LaporanPerangkatService.
     *
     * @param  Collection<int,Device>  $devices
     * @return Collection<int,Collection>
     */
    protected function loadSeriesFor(Collection $devices, array $period): Collection
    {
        $byId = [];

        foreach ($devices as $device) {
            $byId[(int) $device->id] = (string) ($device->ip_address ?: '');
        }

        if ($byId === []) {
            return collect();
        }

        $ids    = array_keys($byId);
        $ips    = array_values(array_filter($byId));
        $start  = $period['start'];
        $end    = $period['end'];
        $columns = ['device_id', 'device_name', 'ip_address', 'status', 'response_time', 'checked_at'];

        $match = function ($query) use ($ids, $ips) {
            $query->where(function ($q) use ($ids, $ips) {
                $q->whereIn('device_id', $ids);

                if ($ips !== []) {
                    $q->orWhere(function ($sub) use ($ips) {
                        $sub->where(function ($inner) {
                            $inner->whereNull('device_id')->orWhere('device_id', 0);
                        })->whereIn('ip_address', $ips);
                    });
                }
            });
        };

        $current = DB::table('monitoring_logs')
            ->select($columns)
            ->whereBetween('checked_at', [$start, $end]);

        $archive = DB::table('log_archives')
            ->select($columns)
            ->whereBetween('checked_at', [$start, $end]);

        $match($current);
        $match($archive);

        $rows = DB::query()->fromSub($current->unionAll($archive), 'logs')
            ->orderBy('checked_at')
            ->orderBy('device_id')
            ->get();

        $grouped = collect();

        foreach ($rows as $row) {
            $id = (int) ($row->device_id ?? 0);

            if ($id === 0) {
                // Baris tanpa device_id: coba petakan lewat IP.
                foreach ($byId as $candidateId => $candidateIp) {
                    if ($candidateIp !== '' && $candidateIp === (string) $row->ip_address) {
                        $id = $candidateId;
                        break;
                    }
                }
            }

            if (! isset($byId[$id])) {
                continue;
            }

            if (! $grouped->has($id)) {
                $grouped->put($id, collect());
            }

            $grouped->get($id)->push((object) [
                'device_id'     => $id,
                'device_name'   => $row->device_name,
                'ip_address'    => $row->ip_address,
                'status'        => strtolower((string) $row->status),
                'response_time' => $row->response_time,
                'checked_at'    => Carbon::parse($row->checked_at),
            ]);
        }

        return $grouped;
    }

    /*
    |--------------------------------------------------------------------------
    | Analisis
    |--------------------------------------------------------------------------
    */

    /**
     * @param  Collection<int,object>  $rows
     */
    protected function analyzeWithRows(Device $device, array $period, Collection $rows): array
    {
        $rows = $rows->sortBy(fn ($r) => $r->checked_at->getTimestamp())->values();

        $statuses = $rows->pluck('status');

        $total = $rows->count();
        $up    = $statuses->filter(fn ($s) => $s === 'up')->count();
        $down  = $statuses->filter(fn ($s) => $s === 'down')->count();
        $other = $total - $up - $down;

        $outages  = $this->buildOutages($rows);
        $interval = $this->pollInterval($rows);
        $latency  = $this->latencyProfile($rows, $period);

        $upToDown  = 0;
        $downToUp  = 0;
        $previous  = null;

        foreach ($statuses as $status) {
            if ($previous === 'up' && $status === 'down') {
                $upToDown++;
            } elseif ($previous === 'down' && $status === 'up') {
                $downToUp++;
            }
            $previous = $status;
        }

        $lastStatus = $statuses->last();
        $ongoing    = collect($outages)->firstWhere('ongoing', true);

        $downtimeSeconds = 0;
        foreach ($outages as $outage) {
            $downtimeSeconds += (int) ($outage['duration_seconds'] ?? 0);
        }

        $longest = collect($outages)->sortByDesc(fn ($o) => (int) ($o['duration_seconds'] ?? 0))->first();
        $lastRecovery = collect($outages)->where('ongoing', false)->sortByDesc('end')->first();

        $downRatio     = $total > 0 ? $down / $total : 0.0;
        $uptimePercent = $total > 0 ? round(($up / $total) * 100, 2) : null;

        $metrics = [
            'checks'              => $total,
            'up'                  => $up,
            'down'                => $down,
            'other'               => $other,
            'uptime_percent'      => $uptimePercent,
            'down_ratio_percent'  => round($downRatio * 100, 2),
            'incident_count'      => count($outages),
            'downtime_seconds'    => $downtimeSeconds,
            'downtime_text'       => $downtimeSeconds > 0 ? LaporanFormat::duration($downtimeSeconds) : null,
            'longest_seconds'     => $longest ? (int) ($longest['duration_seconds'] ?? 0) : 0,
            'longest_text'        => $longest ? ($longest['duration_text'] ?? null) : null,
            'ongoing'             => $ongoing !== null,
            'ongoing_since'       => $ongoing['start'] ?? null,
            'ongoing_polls'       => $ongoing['polls'] ?? 0,
            'ongoing_text'        => $ongoing ? ($ongoing['duration_text'] ?? null) : null,
            'poll_interval'       => $interval,
            'poll_interval_text'  => $interval > 0 ? LaporanFormat::duration($interval) : null,
            'first_check'         => $rows->first()->checked_at ?? null,
            'last_check'          => $rows->last()->checked_at ?? null,
        ];

        $signals = $this->detectSignals($metrics, $upToDown, $downToUp, $outages, $latency, $total);

        $pattern = $this->classify($metrics, $signals, $total);
        $facts   = $this->buildFacts($metrics, $lastStatus, $upToDown, $downToUp, $latency);
        $findings = $this->buildFindings($signals, $pattern['key']);
        [$possibilities, $unverifiable] = $this->buildCaveats($metrics, $latency, $outages, $total);

        return [
            'device' => [
                'id'            => (int) $device->id,
                'name'          => (string) $device->name,
                'ip'            => $device->ip_address ?: null,
                'type'          => $device->type_label,
                'protocol'      => $device->protocol_label,
                'status_current'=> strtoupper((string) ($device->status ?: '')) ?: null,
                'status_label'  => $this->statusLabel($device->status),
            ],
            'period' => [
                'start' => $period['start']->format('d M Y H:i'),
                'end'   => $period['end']->format('d M Y H:i'),
                'label' => $period['label'] ?? null,
            ],
            'stats' => array_merge($metrics, [
                'first_check_text'  => $metrics['first_check']?->format('d M Y H:i:s'),
                'last_check_text'   => $metrics['last_check']?->format('d M Y H:i:s'),
                'ongoing_since_text'=> $metrics['ongoing_since']?->format('d M Y H:i:s'),
            ]),
            'transitions' => [
                'up_to_down'    => $upToDown,
                'down_to_up'    => $downToUp,
                'last_status'   => $lastStatus !== null ? strtoupper($lastStatus) : null,
                'last_check'    => $metrics['last_check']?->format('d M Y H:i:s'),
                'last_recovery' => $lastRecovery?->get('end'),
                'last_recovery_text' => $lastRecovery?->get('end')?->format('d M Y H:i:s'),
            ],
            'latency' => $latency,
            'pattern' => $pattern,
            'facts'   => $facts,
            'findings'=> $findings,
            'possibilities'     => $possibilities,
            'unverifiable'     => $unverifiable,
            'conclusion'        => $this->buildConclusion($pattern, $metrics, $facts, $latency, $total),
            'outages'           => $this->presentOutages($outages, $interval),
            'outages_total'     => count($outages),
            'operator_note'     => trim((string) ($device->penyebab ?: '')) ?: null,
        ];
    }

    /**
     * Kelompokkan baris DOWN beruntun menjadi episode (incident).
     *
     * Durasi hanya dihitung bila episode punya >= 2 baris, karena selisih
     * waktu hanya terukur bila ada dua titik waktu. Episode satu baris
     * diberi durasi null (bukan angka tebakan) dan ditandai explicit.
     *
     * @param  Collection<int,object>  $rows
     * @return array<int,array>
     */
    protected function buildOutages(Collection $rows): array
    {
        $outages = [];
        $current = null;

        foreach ($rows as $row) {
            if ($row->status === 'down') {
                if ($current === null) {
                    $current = [
                        'start'  => $row->checked_at,
                        'last'   => $row->checked_at,
                        'polls'  => 1,
                    ];
                } else {
                    $current['last'] = $row->checked_at;
                    $current['polls']++;
                }
            } elseif ($current !== null) {
                $outages[] = $this->closeOutage($current, $row->checked_at, false);
                $current   = null;
            }
        }

        if ($current !== null) {
            // Outage yang masih berjalan diukur sampai pemeriksaan DOWN
            // terakhir (bukan sampai "sekarang"), supaya angkanya benar-benar
            // berasal dari data dan tidak mengira waktu yang belum terekam.
            $outages[] = $this->closeOutage($current, $current['last'], true);
        }

        return $outages;
    }

    protected function closeOutage(array $run, ?Carbon $end, bool $ongoing): array
    {
        $seconds = $end !== null
            ? abs($end->diffInSeconds($run['start']))
            : null;

        $text = $seconds === null
            ? 'tidak terukur'
            : ($seconds > 0
                ? LaporanFormat::duration($seconds)
                : 'di bawah 1 detik antar-pemeriksaan');

        return [
            'start'            => $run['start'],
            'end'              => $ongoing ? null : $end,
            'polls'            => $run['polls'],
            'ongoing'          => $ongoing,
            'duration_seconds' => $seconds,
            'duration_text'    => $text,
        ];
    }

    /**
     * Median jarak antar-pemeriksaan (detik) — dipakai sebagai konteks, bukan
     * sebagai durasi outages.
     */
    protected function pollInterval(Collection $rows): int
    {
        if ($rows->count() < 2) {
            return 0;
        }

        $diffs = [];
        $previous = null;

        foreach ($rows as $row) {
            if ($previous !== null) {
                $diff = abs($row->checked_at->diffInSeconds($previous));
                if ($diff > 0) {
                    $diffs[] = $diff;
                }
            }
            $previous = $row->checked_at;
        }

        if ($diffs === []) {
            return 0;
        }

        sort($diffs);
        $count = count($diffs);

        return (int) ($count % 2 === 1
            ? $diffs[intdiv($count, 2)]
            : (int) round(($diffs[($count / 2) - 1] + $diffs[$count / 2]) / 2));
    }

    /**
     * Profil latency HANYA dari baris UP dengan response_time > 0.
     *
     * response_time = 0 berarti parser rtt gagal (lihat MonitorDevices),
     * jadi nilai itu dihitung sebagai "tidak terbaca", bukan 0 ms.
     */
    protected function latencyProfile(Collection $rows, array $period): array
    {
        $samples    = [];
        $unreadable = 0;
        $upRows     = 0;

        foreach ($rows as $row) {
            if ($row->status !== 'up') {
                continue;
            }

            $upRows++;
            $value = $row->response_time;

            if ($value === null || (int) $value <= 0) {
                $unreadable++;
                continue;
            }

            $samples[] = [
                'ms'   => (int) $value,
                'time' => $row->checked_at,
            ];
        }

        $empty = [
            'available'       => false,
            'samples'         => 0,
            'up_rows'         => $upRows,
            'unreadable'      => $unreadable,
            'avg'             => null,
            'min'             => null,
            'max'             => null,
            'baseline_avg'    => null,
            'recent_avg'      => null,
            'elevated'        => false,
            'spike'           => false,
            'comparable'      => false,
        ];

        if (count($samples) < self::MIN_LATENCY_SAMPLES) {
            return $empty;
        }

        $values = array_column($samples, 'ms');
        $avg    = (int) round(array_sum($values) / count($values));
        $min    = min($values);
        $max    = max($values);

        // Jendela "terbaru": 25% terakhir dari rentang waktu yang dianalisis.
        $spanStart = $rows->first()->checked_at->getTimestamp();
        $spanEnd   = $rows->last()->checked_at->getTimestamp();
        $cutoff    = $spanEnd - (int) (($spanEnd - $spanStart) * self::RECENT_WINDOW);

        $baseline = [];
        $recent   = [];

        foreach ($samples as $sample) {
            if ($sample['time']->getTimestamp() > $cutoff) {
                $recent[] = $sample['ms'];
            } else {
                $baseline[] = $sample['ms'];
            }
        }

        $comparable = count($baseline) >= self::MIN_LATENCY_SAMPLES
            && count($recent) >= self::MIN_LATENCY_SAMPLES;

        $baselineAvg = $baseline !== [] ? (int) round(array_sum($baseline) / count($baseline)) : null;
        $recentAvg   = $recent !== [] ? (int) round(array_sum($recent) / count($recent)) : null;

        $elevated = false;
        if ($comparable && $baselineAvg > 0) {
            $elevated = $recentAvg >= $baselineAvg * self::LATENCY_RATIO
                && ($recentAvg - $baselineAvg) >= self::LATENCY_DELTA_MS;
        }

        return [
            'available'    => true,
            'samples'      => count($samples),
            'up_rows'      => $upRows,
            'unreadable'   => $unreadable,
            'avg'          => $avg,
            'min'          => $min,
            'max'          => $max,
            'baseline_avg' => $baselineAvg,
            'recent_avg'   => $recentAvg,
            'comparable'   => $comparable,
            'elevated'     => $elevated,
            'spike'        => $avg > 0 && $max >= $avg * self::LATENCY_SPIKE_RATIO,
        ];
    }

    /**
     * Deteksi sinyal dari angka yang sudah dihitung. Setiap sinyal membawa
     * bukti angkanya sendiri supaya bisa langsung ditampilkan.
     */
    protected function detectSignals(
        array $metrics,
        int $upToDown,
        int $downToUp,
        array $outages,
        array $latency,
        int $total
    ): array {
        $down       = $metrics['down'];
        $downRatio  = $metrics['down_ratio_percent'] / 100;
        $ongoing    = $metrics['ongoing'];
        $longest    = (int) $metrics['longest_seconds'];
        $longestPolls = 0;

        foreach ($outages as $outage) {
            $longestPolls = max($longestPolls, (int) $outage['polls']);
        }

        $allSinglePoll = $down > 0;
        foreach ($outages as $outage) {
            if ((int) $outage['polls'] > 1) {
                $allSinglePoll = false;
                break;
            }
        }

        $noRecovery = $down > 0 && $downToUp === 0;

        return [
            'no_history' => [
                'met'    => $total === 0,
                'label'  => 'Bukti belum memadai',
                'detail' => 'Tidak ada satu pun baris monitoring untuk perangkat ini pada periode terpilih.',
            ],
            'never_responsive' => [
                'met'    => $total > 0 && $metrics['up'] === 0 && $down > 0,
                'label'  => 'Tidak pernah merespons',
                'detail' => sprintf(
                    '0 dari %s pemeriksaan menghasilkan respons UP%s.',
                    number_format($total),
                    $noRecovery ? ' dan tidak ada satu pun recovery yang tercatat' : ''
                ),
            ],
            'prolonged_outage' => [
                'met'    => $ongoing
                    && ($longest >= self::LONG_OUTAGE_SECONDS
                        || $metrics['ongoing_polls'] >= self::LONG_OUTAGE_POLLS),
                'label'  => 'Outage berkepanjangan',
                'detail' => sprintf(
                    'Status terakhir masih DOWN sejak %s: %s pemeriksaan berturut-turut (%s sejak pemeriksaan pertama).',
                    $metrics['ongoing_since']?->format('d M Y H:i') ?? '—',
                    number_format($metrics['ongoing_polls']),
                    $metrics['ongoing_text'] ?? 'durasi tidak terukur'
                ),
            ],
            'intermittent' => [
                'met'    => count($outages) >= self::MIN_OUTAGE_EVENTS
                    && $downRatio >= 0.10,
                'label'  => 'Koneksi intermiten (naik-turun berulang)',
                'detail' => sprintf(
                    '%s episode terpisah dengan %s transisi UP→DOWN dan %s transisi DOWN→UP; %.1f%% dari %s pemeriksaan berstatus DOWN.',
                    number_format(count($outages)),
                    number_format($upToDown),
                    number_format($downToUp),
                    $metrics['down_ratio_percent'],
                    number_format($total)
                ),
            ],
            'isolated_response_loss' => [
                'met'    => $allSinglePoll && $down > 0,
                'label'  => 'Kehilangan respons sesaat',
                'detail' => sprintf(
                    'Setiap episode DOWN hanya terdiri dari 1 pemeriksaan (%s episode, %s pemeriksaan DOWN).',
                    number_format(count($outages)),
                    number_format($down)
                ),
            ],
            'no_recovery_observed' => [
                'met'    => $noRecovery,
                'label'  => 'Tidak ada recovery tercatat',
                'detail' => sprintf(
                    'Terdapat %s pemeriksaan DOWN tanpa satu pun transisi DOWN→UP pada periode ini.',
                    number_format($down)
                ),
            ],
            'high_down_ratio' => [
                'met'    => $downRatio >= self::HIGH_DOWN_RATIO,
                'label'  => 'Ketidakstabilan kronis',
                'detail' => sprintf(
                    '%.1f%% dari %s pemeriksaan berstatus DOWN, di atas ambang %.0f%%.',
                    $metrics['down_ratio_percent'],
                    number_format($total),
                    self::HIGH_DOWN_RATIO * 100
                ),
            ],
            'recurring_brief_outage' => [
                'met'    => $down > 0
                    && ! $metrics['ongoing']
                    && $longest < self::LONG_OUTAGE_SECONDS
                    && $longestPolls < self::LONG_OUTAGE_POLLS,
                'label'  => 'Gangguan berulang berdurasi pendek',
                'detail' => sprintf(
                    '%s episode, semuanya berakhir (tidak ada outage berjalan); episode terpanjang %s dengan %s pemeriksaan DOWN.',
                    number_format(count($outages)),
                    $metrics['longest_text'] ?? '—',
                    number_format($down)
                ),
            ],
            'elevated_latency' => [
                'met'    => (bool) $latency['elevated'],
                'label'  => 'Latensi meningkat',
                'detail' => sprintf(
                    'Rata-rata latency %.0f ms pada jendela terakhir vs %.0f ms pada periode sebelumnya (%.1f×, selisih %+.0f ms).',
                    $latency['recent_avg'] ?? 0,
                    $latency['baseline_avg'] ?? 0,
                    ($latency['baseline_avg'] ?? 0) > 0 ? ($latency['recent_avg'] / $latency['baseline_avg']) : 0,
                    ($latency['recent_avg'] ?? 0) - ($latency['baseline_avg'] ?? 0)
                ),
            ],
            'latency_spike' => [
                'met'    => (bool) $latency['spike'],
                'label'  => 'Lonjakan latency',
                'detail' => sprintf(
                    'Latency tertinggi %d ms compared to rata-rata %d ms pada UP yang tercatat.',
                    $latency['max'] ?? 0,
                    $latency['avg'] ?? 0
                ),
            ],
            'no_downtime' => [
                'met'    => $total > 0 && $down === 0,
                'label'  => 'Tidak ada gangguan connectivity',
                'detail' => sprintf(
                    'Seluruh %s pemeriksaan pada periode ini berstatus UP.',
                    number_format($total)
                ),
            ],
        ];
    }

    /**
     * Klasifikasi utama: ambil sinyal dengan prioritas tertinggi.
     *
     * PENTING: label di sini menggambarkan POLA KONEKTIVITAS yang terukur,
     * bukan penyebab fisik. "Outage berkepanjangan" berarti "tidak merespons
     * dalam waktu lama according to log" — bukan "perangkatnya rusak".
     */
    protected function classify(array $metrics, array $signals, int $total): array
    {
        $priority = [
            'no_history'             => ['Bukti belum memadai', 'info'],
            'never_responsive'       => ['Tidak pernah merespons', 'danger'],
            'prolonged_outage'       => ['Outage berkepanjangan', 'danger'],
            'intermittent'           => ['Koneksi intermiten', 'warn'],
            'isolated_response_loss' => ['Kehilangan respons sesaat', 'warn'],
            'high_down_ratio'        => ['Ketidakstabilan kronis', 'warn'],
            'recurring_brief_outage' => ['Gangguan berulang berdurasi pendek', 'info'],
            'elevated_latency'       => ['Latensi meningkat', 'warn'],
            'latency_spike'          => ['Lonjakan latency', 'warn'],
            'no_downtime'            => ['Tidak ada gangguan connectivity', 'ok'],
        ];

        foreach ($priority as $key => [$label, $tone]) {
            if (! empty($signals[$key]['met'])) {
                return [
                    'key'   => $key,
                    'label' => $label,
                    'tone'  => $tone,
                    'basis' => $signals[$key]['detail'],
                ];
            }
        }

        return [
            'key'   => 'unclassified',
            'label' => 'Bukti belum memadai',
            'tone'  => 'info',
            'basis' => $total === 0
                ? 'Tidak ada histori monitoring pada periode ini.'
                : 'Pola tidak dapat ditentukan dari data yang tersedia.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Penyusunan teks (fakta / temuan / kemungkinan)
    |--------------------------------------------------------------------------
    */

    /**
     * FAKTA — hanya angka yang benar-benar tercatat.
     *
     * @return array<int,string>
     */
    protected function buildFacts(array $metrics, ?string $lastStatus, int $upToDown, int $downToUp, array $latency): array
    {
        if ($metrics['checks'] === 0) {
            return ['Tidak ada baris monitoring untuk perangkat ini pada periode terpilih.'];
        }

        $facts = [];

        $facts[] = sprintf(
            'Device tercatat DOWN pada %s dari %s pemeriksaan (UP %s, DOWN %s).',
            number_format($metrics['down']),
            number_format($metrics['checks']),
            number_format($metrics['up']),
            number_format($metrics['down'])
        );

        $facts[] = sprintf(
            'Uptime berbasis pemeriksaan: %s%%. Rasio DOWN: %s%%.',
            $metrics['uptime_percent'] !== null ? number_format($metrics['uptime_percent'], 2) : '—',
            number_format($metrics['down_ratio_percent'], 2)
        );

        $facts[] = sprintf(
            'Status terakhir pada %s: %s.',
            $metrics['last_check']?->format('d M Y H:i:s') ?? '—',
            $lastStatus !== null ? strtoupper($lastStatus) : '—'
        );

        $facts[] = sprintf(
            'Terjadi %s transisi UP→DOWN dan %s transisi DOWN→UP dalam periode ini.',
            number_format($upToDown),
            number_format($downToUp)
        );

        $facts[] = sprintf(
            'Jumlah incident (episode DOWN terpisah): %s. Total downtime terukur: %s.',
            number_format($metrics['incident_count']),
            $metrics['downtime_text'] ?? '0 dtk'
        );

        if ($metrics['ongoing']) {
            $facts[] = sprintf(
                'Outage masih BERLANGSUNG sejak %s (%s pemeriksaan berturut-turut).',
                $metrics['ongoing_since']?->format('d M Y H:i:s') ?? '—',
                number_format($metrics['ongoing_polls'])
            );
        }

        if ($metrics['poll_interval'] > 0) {
            $facts[] = sprintf(
                'Jarak antar-pemeriksaan (median): %s. Durasi outage dihitung dari selisih waktu pemeriksaan pertama dan terakhir tiap episode.',
                $metrics['poll_interval_text']
            );
        }

        if ($latency['available']) {
            $facts[] = sprintf(
                'Latency pada %s pemeriksaan UP yang terbaca: rata-rata %s ms, min %s ms, max %s ms.',
                number_format($latency['samples']),
                number_format($latency['avg']),
                number_format($latency['min']),
                number_format($latency['max'])
            );
        } else {
            $facts[] = sprintf(
                'Latency tidak dapat dianalisis: hanya %s pemeriksaan UP dengan latency terbaca (minimal %s diperlukan).',
                number_format($latency['samples']),
                number_format(self::MIN_LATENCY_SAMPLES)
            );
        }

        if ($latency['unreadable'] > 0) {
            $facts[] = sprintf(
                '%s pemeriksaan UP tidak memiliki latency yang terbaca (response_time = 0), sehingga tidak dihitung sebagai 0 ms.',
                number_format($latency['unreadable'])
            );
        }

        return $facts;
    }

    /**
     * TEMUAN / POLA — hasil deteksi sinyal, dengan angka pendukungnya.
     *
     * @return array<int,array{key:string,label:string,detail:string,primary:bool}>
     */
    protected function buildFindings(array $signals, string $primaryKey): array
    {
        $findings = [];

        foreach ($signals as $key => $signal) {
            if (empty($signal['met']) || $key === 'no_history') {
                continue;
            }

            $findings[] = [
                'key'     => $key,
                'label'   => $signal['label'],
                'detail'  => $signal['detail'],
                'primary' => $key === $primaryKey,
            ];
        }

        return $findings;
    }

    /**
     * KEMUNGKINAN YANG PERLU DIPERIKSA + DATA YANG BELUM DAPAT DIPASTIKAN.
     *
     * Bagian ini SENGAJA berbentuk "perlu diperiksa", tidak pernah "<X> karena
     * <Y>". Penyebab fisik tidak dapat dibuktikan dari data ICMP.
     *
     * @return array{0:array<int,string>,1:array<int,string>}
     */
    protected function buildCaveats(array $metrics, array $latency, array $outages, int $total): array
    {
        $possibilities = [];
        $unverifiable = [];

        if ($metrics['down'] > 0) {
            $possibilities[] = 'Kehilangan respons ICMP dapat berasal dari beberapa lapisan sekaligus '
                .'(konektivitas fisik, routing, konfigurasi, atau filtering ICMP). '
                .'Data ini hanya merekam hasil ping, sehingga penyebab lapisan mana yang bermasalah '
                .'belum dapat dipastikan dan perlu diperiksa langsung.';
        }

        if ($metrics['ongoing'] && $metrics['ongoing_polls'] <= 1) {
            $possibilities[] = 'Outage yang hanya tercatat satu kali pemeriksaan belum bisa dibedakan antara '
                .'gangguan sesaat dan polling yang terlewat; perlu diamati pada pemeriksaan berikutnya.';
        }

        $singlePoll = collect($outages)->every(fn ($o) => (int) $o['polls'] <= 1);

        if ($singlePoll && $metrics['down'] > 0) {
            $possibilities[] = 'Episode satu-periksaan dapat mencerminkan timeout sesaat maupun perangkat yang '
                .'membalas terlambat; keduanya menghasilkan status DOWN yang sama pada log.';
        }

        if (! $latency['available']) {
            $unverifiable[] = sprintf(
                'Pola latensi tidak dapat dinilai: jumlah pemeriksaan UP dengan latency terbaca (%s) '
                .'di bawah ambang minimal %s.',
                number_format($latency['samples']),
                number_format(self::MIN_LATENCY_SAMPLES)
            );
        } elseif (! $latency['comparable']) {
            $unverifiable[] = 'Perbandingan latensi awal vs akhir tidak dapat dilakukan karena salah satu '
                .'jendela memiliki sample kurang dari ambang minimal.';
        }

        $unverifiable[] = 'Packet loss tidak dapat dihitung: tabel monitoring tidak punya kolom packet loss '
            .'(hanya status dan response_time).';
        $unverifiable[] = 'Penyebab fisik (kabel, power, suhu, kerusakan perangkat) tidak dapat dibuktikan '
            .'dari data ICMP dan perlu pemeriksaan di lapangan.';
        $unverifiable[] = 'Status ini hanya merekam hasil ping ke IP, sehingga tidak membedakan perangkat mati, '
            .'IP salah, atau perangkat hidup yang memblokir ICMP.';

        if ($total === 0) {
            $unverifiable[] = 'Periode terpilih tidak memuat data monitoring untuk perangkat ini, sehingga '
                .'belum ada bukti yang dapat dianalisis.';
        }

        return [$possibilities, $unverifiable];
    }

    /**
     * KESIMPULAN — mengikuti pola utama, hanya mengulang bukti yang ada.
     */
    protected function buildConclusion(array $pattern, array $metrics, array $facts, array $latency, int $total): string
    {
        if ($total === 0) {
            return 'Belum dapat disimpulkan: tidak ada histori monitoring untuk perangkat ini pada periode terpilih. '
                .'Perlu menunggu hasil pemeriksaan berikutnya sebelum analisis bisa dilakukan.';
        }

        return match ($pattern['key']) {
            'never_responsive' => sprintf(
                'Tidak ada satu pun respons UP pada %s pemeriksaan di periode ini, sehingga perangkat tidak pernah '
                .'tercatat merespons ICMP. Karena tidak pernah ada respons, penyebabnya tidak dapat dibedakan dari histori ini '
                .'dan perlu diperiksa langsung di lokasi.',
                number_format($total)
            ),
            'prolonged_outage' => sprintf(
                'Perangkat berada dalam outage yang masih berjalan: %s pemeriksaan DOWN berturut-turut sejak %s. '
                .'Pola ini menunjukkan ketidaktersambungan yang berkelanjutan pada periode ini, '
                .'bukan gangguan sesaat. Penyebab lapisan (konektivitas, routing, atau filtering) belum dapat dipastikan dari data ICMP.',
                number_format($metrics['ongoing_polls']),
                $metrics['ongoing_since']?->format('d M Y H:i:s') ?? '—'
            ),
            'intermittent' => sprintf(
                'Koneksi bersifat intermiten: %s episode terpisah dengan %s pemeriksaan DOWN (%.1f%% dari total) '
                .'diselingi recovery. Karena naik dan turun berulang dalam satu periode, gangguan ini lebih mencerminkan '
                .'kondisi yang tidak stabil daripada outage tunggal yang berlanjut.',
                number_format($metrics['incident_count']),
                number_format($metrics['down']),
                $metrics['down_ratio_percent']
            ),
            'isolated_response_loss' => sprintf(
                'Semua episode DOWN hanya berlangsung satu pemeriksaan dan langsung kembali UP. '
                .'Ini konsisten dengan kehilangan respons sesaat, namun tidak dapat dibedakan dari filtering ICMP '
                .'atau polling yang terlewat hanya dengan data ini.',
            ),
            'high_down_ratio' => sprintf(
                'Ketidakstabilan kronis: %.1f%% pemeriksaan berstatus DOWN sepanjang periode. '
                .'Tingkat DOWN yang tinggi ini berulang, bukan kejadian tunggal.',
                $metrics['down_ratio_percent']
            ),
            'recurring_brief_outage' => sprintf(
                'Terdapat %s episode gangguan, semuanya berakhir dan tidak ada outage yang masih berjalan. '
                .'Episode terpanjang berlangsung %s. Ini menunjukkan gangguan berulang yang singkat, '
                .'bukan ketidaktersambungan berkepanjangan.',
                number_format($metrics['incident_count']),
                $metrics['longest_text'] ?? '—'
            ),
            'elevated_latency' => sprintf(
                'Tidak ada gangguan connectivity (tidak ada episode DOWN), tetapi latensi meningkat: '
                .'rata-rata %.0f ms pada jendela terakhir dibanding %.0f ms pada periode sebelumnya. '
                .'Kenaikan ini terukur dari response_time yang tersimpan, namun penyebabnya (beban, jarak, '
                .'antrean, atau kongesti) belum dapat dipastikan dari data ini.',
                $latency['recent_avg'] ?? 0,
                $latency['baseline_avg'] ?? 0
            ),
            'latency_spike' => sprintf(
                'Status connectivity stabil, namun terdapat lonjakan latency (tertinggi %d ms vs rata-rata %d ms). '
                .'Penyebab lonjakan tidak dapat dipastikan dari data ini.',
                $latency['max'] ?? 0,
                $latency['avg'] ?? 0
            ),
            'no_downtime' => sprintf(
                'Tidak ada gangguan connectivity yang tercatat: seluruh %s pemeriksaan berstatus UP pada periode ini.',
                number_format($total)
            ),
            default => 'Pola belum dapat ditentukan dari data monitoring yang tersedia pada periode ini.',
        };
    }

    /**
     * @return array<int,array>
     */
    protected function presentOutages(array $outages, int $interval): array
    {
        $mapped = array_map(fn (array $o) => [
            'start'            => $o['start']->format('d M Y H:i:s'),
            'end'              => $o['end']?->format('d M Y H:i:s'),
            'polls'            => (int) $o['polls'],
            'ongoing'          => (bool) $o['ongoing'],
            'duration_seconds' => $o['duration_seconds'],
            'duration_text'    => $o['duration_text'],
        ], $outages);

        // Episode terbaru lebih relevan ditampilkan di atas.
        return array_reverse($mapped);
    }

    protected function statusLabel(?string $status): ?string
    {
        return match (strtolower((string) $status)) {
            'up'   => 'UP',
            'down' => 'DOWN',
            default => null,
        };
    }
}
