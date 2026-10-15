<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DeviceType;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\MonitoringLog;
use App\Services\CheckHistoryPurger;
use App\Services\NetworkTools\AssistantChat;
use App\Services\NetworkTools\DiagnosisAnalyzer;
use App\Services\NetworkTools\NetworkAssistant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Facades\Process;

class ToolsController extends Controller
{
    /**
     * Ikon + label untuk tiap tipe perangkat.
     *
     * Kolom TYPE di devices.csv isinya seragam ("ICMP" untuk semua baris),
     * jadi tipe yang informatif justru datang dari kolom `type` pada tabel
     * device: Router, Switch, EPON, OLT, atau Other. Peta di bawah memakai
     * nilai-nilai itu, dan `Other`/nilai tak dikenal tetap dapat ikonnya
     * supaya tidak pernah ada baris tanpa gambar.
     *
     * Semua ikon di sini harus benar-benar ada di font ikon tema
     * (public/admin-assets/assets/css/style.css). Ikon yang tidak ada akan
     * tampil kosong tanpa error apa pun.
     *
     * @var array<string, array{icon:string, label:string}>
     */
    private const DEVICE_TYPES = [
        'router' => ['icon' => 'ni-share-alt', 'label' => 'Router'],
        'switch' => ['icon' => 'ni-network',    'label' => 'Switch'],
        'epon' => ['icon' => 'ni-signal',     'label' => 'EPON'],
        'olt' => ['icon' => 'ni-server',     'label' => 'OLT'],
        'other' => ['icon' => 'ni-wifi',       'label' => 'Perangkat'],
    ];

    /** Token yang dikirim browser untuk "Server Radnet (Server Lokal)". */
    private const LOCAL_TARGET_TOKEN = '@local';

    /** Alamat yang dipakai token di atas. Loopback, jadi selalu milik server ini. */
    private const LOCAL_TARGET_IP = '127.0.0.1';

    /**
     * Nama kolom `device` yang berisi ringkasan hasil pemeriksaan, untuk
     * pesan di layar. Daftar ini harus sama dengan
     * CheckHistoryPurger::DEVICE_METRIC_COLUMNS.
     *
     * @var array<string, string>
     */
    private const DEVICE_METRIC_LABELS = [
        'avg_delay' => 'rata-rata delay',
        'missed_percentage' => 'persentase tidak merespons',
    ];

    public function index()
    {
        // Daftar perangkat dibaca LANGSUNG dari tabel `devices` di database
        // monitoring Server Radnet — bukan dari berkas CSV maupun data statis.
        // Setiap penambahan, perubahan, atau perubahan status (UP/DOWN) di
        // halaman Kelola Router langsung tercermin di halaman ini.
        $devices = Device::query()
            ->orderBy('id')
            ->get()
            ->map(fn (Device $device) => $this->presentDevice($device))
            ->sortBy(fn ($device) => match ($device->status) {
                'down' => 0,
                'warning' => 1,
                'up' => 2,
                default => 3,
            }, SORT_REGULAR)
            ->values();

        return view('admin.tools', [
            'devices' => $devices,
            'analysisLimits' => $this->analysisLimits(),
            // Tombol "Hapus Semua Log" hanya dirender untuk Super Admin,
            // supaya staff tidak melihat tombol yang pasti ditolak 403.
            'canPurgeHistory' => auth()->user()?->isSuperAdmin() ?? false,
        ]);
    }

    /**
     * Ringkasan status semua perangkat untuk auto-refresh badge di halaman.
     *
     * Halaman Network Tools merender daftar perangkat sekali dari database,
     * sehingga badge UP/WARNING/DOWN bisa basi sampai `monitor:ping` menulis
     * status baru (tiap 5 menit) DAN halaman di-reload. Endpoint ini membaca
     * ulang kolom `status` (plus metrik ringkas) dari tabel `devices` supaya
     * JavaScript di halaman dapat menyegarkan badge secara berkala tanpa
     * memuat ulang seluruh halaman. Endpoint ini hanya MEMBACA: tidak ada
     * perubahan data sama sekali.
     */
    public function status(): JsonResponse
    {
        $devices = Device::query()
            ->orderBy('id')
            ->get()
            ->map(function (Device $device): array {
                $status = strtolower(trim((string) $device->status));

                return [
                    'id' => (int) $device->id,
                    'status' => in_array($status, ['up', 'down', 'warning'], true) ? $status : 'up',
                    'avg_delay' => (float) ($device->avg_delay ?? 0),
                    'missed_percentage' => (float) ($device->missed_percentage ?? 0),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'checked_at' => now()->format('H:i:s'),
            'counts' => [
                'all' => $devices->count(),
                'down' => $devices->where('status', 'down')->count(),
                'warning' => $devices->where('status', 'warning')->count(),
                'up' => $devices->where('status', 'up')->count(),
            ],
            'devices' => $devices,
        ]);
    }

    /**
     * Bentuk satu baris perangkat untuk ditampilkan.
     *
     * Status dibaca apa adanya dari kolom `status` tabel `devices`, jadi
     * badge UP/WARNING/DOWN selalu sama dengan kondisi monitoring terakhir.
     * Nilai yang tidak dikenal diperlakukan sebagai `up` hanya kalau memang
     * kosong; nilai asing seperti "offline" atau angka tidak ikut diteruskan
     * ke badge, karena kelas statusnya tidak ada sehingga warnanya jadi tidak
     * terbaca.
     */
    protected function presentDevice(Device $device): object
    {
        $status = strtolower(trim((string) $device->status));
        $status = in_array($status, ['up', 'down', 'warning'], true) ? $status : 'up';

        $rawType = trim((string) $device->type);
        $type = self::DEVICE_TYPES[strtolower($rawType)] ?? self::DEVICE_TYPES['other'];

        // Gambar perangkat (PNG tajam di public/images/devices) memakai
        // aturan yang sama dengan model Device::getIconUrlAttribute, supaya
        // ikon di halaman ini identik dengan halaman Kelola Router.
        $iconType = trim((string) $device->icon_type);

        return (object) [
            'id' => $device->id,
            'name' => (string) $device->name,
            'ip_address' => (string) $device->ip_address,
            'status' => $status,
            'type' => $type['label'],
            'type_icon' => $type['icon'],
            'icon_url' => DeviceType::iconUrl($rawType !== '' ? $rawType : null, $iconType !== '' ? $iconType : null),
            'raw_type' => $rawType,
            'avg_delay' => (float) ($device->avg_delay ?? 0),
            'missed_percentage' => (float) ($device->missed_percentage ?? 0),
            'pop_name' => (string) ($device->pop_name ?? ''),
        ];
    }

    /**
     * Ambang penilaian yang dibaca JavaScript di halaman.
     *
     * Nilainya diambil dari DiagnosisAnalyzer supaya ambang di browser dan
     * ambang di backend tidak pernah berbeda. View tetap punya nilai cadangan,
     * tapi sumber tunggalnya ada di sini.
     *
     * @return array{goodLatencyMs:float, poorLatencyMs:float, jitterMs:float, minorLossPct:float, majorLossPct:float}
     */
    protected function analysisLimits(): array
    {
        return [
            'goodLatencyMs' => DiagnosisAnalyzer::GOOD_LATENCY_MS,
            'poorLatencyMs' => DiagnosisAnalyzer::POOR_LATENCY_MS,
            'jitterMs' => DiagnosisAnalyzer::JITTER_MS,
            'minorLossPct' => DiagnosisAnalyzer::MINOR_LOSS_PCT,
            'majorLossPct' => DiagnosisAnalyzer::MAJOR_LOSS_PCT,
        ];
    }

    /**
     * Ping tujuan dengan pemeriksaan ICMP sungguhan ke IP/domain target.
     */
    public function ping(Request $request)
    {
        try {
            $target = $this->resolveTarget($request->input('target'));
            if ($target === null) {
                return $this->errorResponse('Target IP / domain tidak valid.');
            }

            // Dua paket sudah cukup untuk menyimpulkan UP/WARNING/DOWN dan
            // membuat tombol kembali responsif jauh lebih cepat.
            $count = $request->integer('count', 2);
            if ($count < 1 || $count > 100) {
                return $this->errorResponse('Ping count harus antara 1 hingga 100.');
            }

            $response = $this->pingTarget($target, $count);

            // Lampirkan status asli perangkat dari database (bila target adalah
            // perangkat terdaftar), agar hasil pemeriksaan sinkron dengan status
            // sebenarnya alih-alih hanya menebak dari packet loss uji manual.
            $payload = $response->getData(true);
            $payload['device'] = $this->deviceInfo($request->input('device_id'), $target);

            // Saran penanganan (AI Network Assistant) + peta jalur paket
            // (Hop Route Mapper). Keduanya membaca hasil ping yang sama, jadi
            // angkanya tidak mungkin berbeda dari ringkasan yang tampil di layar.
            $payload['assistant'] = (new NetworkAssistant)->suggest(
                is_string($payload['analysis']['status'] ?? null) ? $payload['analysis']['status'] : null,
                (string) ($payload['output'] ?? ''),
                is_array($payload['analysis'] ?? null) ? $payload['analysis'] : [],
            );

            // Teruskan detail status (mis. "time=12.4ms", "High Latency",
            // "Destination Host Unreachable") dari analisis ke kartu asisten,
            // supaya kartu hasil menampilkan angka/keterangan yang sama persis.
            if (is_array($payload['assistant'])) {
                $payload['assistant']['status_detail'] = is_string($payload['analysis']['status_detail'] ?? null)
                    ? $payload['analysis']['status_detail']
                    : null;
            }
            $payload['path'] = $this->pathInfo(
                $target,
                (string) ($payload['output'] ?? ''),
                is_array($payload['analysis'] ?? null) ? $payload['analysis'] : [],
            );

            return response()->json($payload);
        } catch (\Throwable $e) {
            return $this->failedJson(
                'Pemeriksaan ping gagal dijalankan di server. Coba lagi sebentar.',
                $target ?? null,
                $e,
            );
        }
    }

    /**
     * Eksekusi ping ICMP yang NYATA ke IP/domain target memakai biner
     * `ping` Linux:
     *
     *   ping -4 -c <count> -W 3 <target>
     *
     *   -4   paksa keluarga alamat IPv4 (target adalah IP perangkat Radnet)
     *   -c   jumlah permintaan gema (echo request) yang dikirim
     *   -W   batas detik menunggu balasan tiap paket
     *
     * Hasilnya (exit code + keluaran mentah) diteruskan ke runCommand apa
     * adanya; status UP/WARNING/DOWN TIDAK dibaca dari exit code, melainkan
     * disimpulkan DiagnosisAnalyzer dari packet loss & latency. Exit code
     * ping yang bukan 0 hanya berarti ada paket yang tidak membalas, hal ini
     * normal pada kondisi WARNING maupun DOWN.
     */
    protected function pingTarget(string $target, int $count): JsonResponse
    {
        // -c : jumlah paket echos (2 sudah cukup untuk diagnosis cepat).
        // -W : batas tunggu tiap balasan. Karena target DOWN tidak membalas,
        //      -W 2 membuat ping berhenti dalam hitungan detik, bukan
        //      menggantung lama sehingga tombol terlihat "stuck".
        $count = max(1, min($count, 10));
        $command = 'ping -4 -c '.$count.' -W 2 '.escapeshellarg($target);

        // Batas proses sedikit di atas perkiraan terburuk (-W 2 x jumlah
        // paket) supaya eksekusi selalu selesai dan respons tetap terkirim.
        $timeout = max(10, $count * 3 + 5);

        return $this->runCommand('Ping', $target, $command, $timeout);
    }

    /**
     * Ping BERKELANJUTAN: keluaran dikirim baris demi baris (NDJSON) selama
     * proses ping masih berjalan, layaknya terminal ping sungguhan, sampai
     * pengguna menekan "Hentikan Ping" atau batas waktu tercapai.
     *
     * Tiap baris respons adalah satu objek JSON:
     *
     *   {"type":"start", ...}     ping baru dimulai
     *   {"type":"line", ...}      satu baris keluaran ping mentah
     *   {"type":"progress", ...}  ringkasan live (paket + latency, TANPA %)
     *   {"type":"summary", ...}   ringkasan akhir, bentuk sama dgn tools.ping
     *
     * Penghentian dikirim lewat pingStop() berupa file penanda, BUKAN dengan
     * memutus koneksi — supaya server masih sempat menutup ping dengan
     * ringkasan akhir (UP / WARNING / DOWN) yang ikut terkirim ke layar.
     */
    public function pingStream(Request $request)
    {
        $target = $this->resolveTarget($request->input('target'));

        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $id = (string) $request->input('id');

        if (preg_match('/^[A-Za-z0-9_-]{4,64}$/', $id) !== 1) {
            $id = bin2hex(random_bytes(8));
        }

        $deviceId = $request->input('device_id');
        $stopFile = $this->pingStopFile($id);

        if (is_file($stopFile)) {
            @unlink($stopFile);
        }

        return response()->stream(function () use ($target, $deviceId, $stopFile): void {
            try {
                $this->streamPing($target, $deviceId, $stopFile);
            } catch (\Throwable $e) {
                // Stream sudah terlanjur terbuka: kirim satu event penutup
                // supaya browser tidak menggantung menunggu data berikutnya.
                @error_log('ping stream gagal: '.$e->getMessage());

                echo json_encode([
                    'type' => 'error',
                    'message' => 'Pemeriksaan terputus di server. Coba lagi sebentar.',
                ], JSON_UNESCAPED_UNICODE)."\n";

                @flush();
            }
        }, 200, [
            'Content-Type' => 'application/x-ndjson; charset=UTF-8',
            'Content-Encoding' => 'identity',
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, no-transform',
        ]);
    }

    /**
     * Tombol "Hentikan Ping": menulis file penanda kecil di storage.
     *
     * Dipakai alih-alih memutus koneksi HTTP karena streamPing() harus
     * masih punya kesempatan mengirim ringkasan akhir (status + rekomendasi)
     * sebelum menutup stream-nya.
     */
    public function pingStop(Request $request): JsonResponse
    {
        $id = (string) $request->input('id');

        if (preg_match('/^[A-Za-z0-9_-]{4,64}$/', $id) !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Id ping tidak valid.',
            ], 422);
        }

        $file = $this->pingStopFile($id);
        $dir = dirname($file);

        if (! is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        @file_put_contents($file, (string) time());

        return response()->json(['success' => true]);
    }

    protected function pingStopFile(string $id): string
    {
        return storage_path('framework/ping/'.$id.'.stop');
    }

    /**
     * Inti ping streaming: buka proses `ping`, teruskan output baris demi
     * baris ke browser, lalu tutup dengan ringkasan akhir.
     *
     * @param  mixed  $deviceId  id perangkat terpilih (boleh null)
     */
    protected function streamPing(string $target, mixed $deviceId, string $stopFile): void
    {
        set_time_limit(0);

        // Buang semua lapisan output buffering (Laravel / PHP-FPM) supaya tiap
        // baris benar-benar terkirim saat itu juga, bukan sekaligus di akhir.
        while (ob_get_level() > 0) {
            if (! @ob_end_flush()) {
                break;
            }
        }

        @ini_set('zlib.output_compression', '0');

        /** @var callable(array<string, mixed>): bool $emit */
        $emit = static function (array $event): bool {
            echo json_encode($event, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n";
            @flush();

            return connection_aborted() === 0;
        };

        $command = 'ping -4 -n -i 1 -W 2 -w 118 '.escapeshellarg($target);

        // Bentuk perintah memakai ARRAY, bukan string, supaya PHP TIDAK
        // membungkusnya dengan `sh -c`. Sinyal "Hentikan Ping" (SIGINT) harus
        // mendarat di proses ping-nya langsung, persis seperti Ctrl+C di
        // terminal — kalau lewat shell, sinyalnya tidak sampai.
        $process = @proc_open(
            ['ping', '-4', '-n', '-i', '1', '-W', '2', '-w', '118', $target],
            [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );

        if (! is_resource($process)) {
            $emit(['type' => 'error', 'message' => 'Perintah ping tidak bisa dijalankan di server.']);

            return;
        }

        @fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $closed = false;

        // Kalau browser tertutup / pindah halaman di tengah jalan, proses
        // ping tetap wajib ikut mati — tidak boleh jadi proses yatim yang
        // berjalan sampai batas waktu 118 detik.
        register_shutdown_function(static function () use (&$process, &$pipes, &$closed): void {
            if ($closed || ! is_resource($process)) {
                return;
            }

            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                    @fclose($pipe);
                }
            }

            @proc_terminate($process, 9);
            @proc_close($process);
            $closed = true;
        });

        $startedAt = microtime(true);
        $deadline = $startedAt + 125;
        $exitCode = null;
        $buffer = '';
        $collected = '';
        $seq = 0;
        $lastSeq = 0;
        $lastProgress = 0.0;
        $stoppedByUser = false;
        $aborted = false;

        /* Ping Linux tidak mencetak apa pun untuk paket yang hilang, sehingga
           nomor icmp_seq terlihat melompat. Supaya log terbaca baris-demi-baris
           seperti di terminal, celah seq ditutup dengan baris timeout buatan
           ("Request timed out for icmp_seq=X") tanpa nilai time ms. */
        $fillGap = static function (int $upto) use (&$seq, &$lastSeq, &$aborted, $emit): bool {
            while ($lastSeq < $upto) {
                $lastSeq++;
                $seq++;

                if (! $emit([
                    'type' => 'line',
                    'seq' => $seq,
                    'line' => 'Request timed out for icmp_seq='.$lastSeq,
                    'lost' => true,
                ])) {
                    $aborted = true;

                    return false;
                }
            }

            return true;
        };

        // Kirim semua baris yang sudah lengkap; berhenti bila koneksi putus.
        $flushLines = static function () use (&$buffer, &$collected, &$seq, &$aborted, &$lastSeq, $emit, $fillGap): bool {
            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = rtrim(substr($buffer, 0, $pos), "\r");
                $buffer = substr($buffer, $pos + 1);

                if ($line === '') {
                    continue;
                }

                /* Buang artefak iputils "%spipe %d" yang kadang tercetak saat
                   stdout ping putus di tengah jalan (mis. klien menutup koneksi). */
                if (preg_match('/^pipe \d+$/', $line) === 1) {
                    continue;
                }

                /* Baris balasan maupun pesan error membawa "icmp_seq=N".
                   Sebelum baris ini ditampilkan, isi dulu nomor seq yang
                   terlewat (paket hilang) supaya nomor tidak melompat. */
                if (preg_match('/icmp_seq[= ](\d+)/', $line, $m) === 1) {
                    $n = (int) $m[1];

                    if ($n > $lastSeq + 1 && ! $fillGap($n - 1)) {
                        return false;
                    }

                    if ($n > $lastSeq) {
                        $lastSeq = $n;
                    }
                }

                /* Blok statistik ping memuat total paket terkirim. Lengkapi
                   sisa paket yang hilang SEBELUM baris statistik ditampilkan,
                   supaya baris timeout muncul di atas ringkasan (bukan di
                   bawahnya) dan tetap tidak ada nomor seq yang melompat. */
                if (preg_match('/(\d+)\s+packets transmitted/i', $line, $pm) === 1) {
                    if (! $fillGap((int) $pm[1])) {
                        return false;
                    }
                }

                $collected .= $line."\n";
                $seq++;

                if (! $emit(['type' => 'line', 'seq' => $seq, 'line' => $line])) {
                    $aborted = true;

                    return false;
                }
            }

            return true;
        };

        $emit(['type' => 'start', 'target' => $target, 'at' => now()->format('H:i:s')]);

        while (true) {
            if (microtime(true) > $deadline) {
                break;
            }

            if (is_file($stopFile)) {
                $stoppedByUser = true;
                break;
            }

            $buffer .= $this->pingSelectRead([$pipes[1], $pipes[2]], 200000);

            if (! $flushLines()) {
                break;
            }

            /* Ringkasan live tiap 2 detik dipakai badge di layar, dan juga
               menjaga stream tetap "sibuk": saat target senyap ping tidak
               mengeluarkan baris apa pun sama sekali, sehingga tanpa event
               ini reverse proxy bisa memutus koneksi karena dianggap diam. */
            if (microtime(true) - $lastProgress >= 2.0) {
                $lastProgress = microtime(true);

                if (! $emit(array_merge(['type' => 'progress'], $this->pingTally($collected, microtime(true) - $startedAt)))) {
                    $aborted = true;
                    break;
                }
            }

            if (! $this->pingRunning($process, $exitCode)) {
                // Ping selesai sendiri (batas -w): baca sisa output sampai
                // EOF, termasuk blok statistik penutupnya.
                for ($i = 0; $i < 50; $i++) {
                    $chunk = $this->pingSelectRead([$pipes[1], $pipes[2]], 20000);

                    if ($chunk !== '') {
                        $buffer .= $chunk;
                    }

                    if (! $flushLines()) {
                        break 2;
                    }

                    if ($chunk === '' && feof($pipes[1]) && feof($pipes[2])) {
                        break;
                    }
                }

                break;
            }
        }

        /* "Hentikan Ping" diterjemahkan jadi SIGINT: sama persis dengan
           menekan Ctrl+C di terminal, sehingga ping menutup sendiri dan
           mencetak blok statistik (packets transmitted / rtt min/avg/max). */
        if (! $aborted && is_resource($process) && $this->pingRunning($process, $exitCode)) {
            @proc_terminate($process, 2);

            $graceUntil = microtime(true) + 1.5;

            while (microtime(true) < $graceUntil) {
                $buffer .= $this->pingSelectRead([$pipes[1], $pipes[2]], 150000);

                if (! $flushLines()) {
                    break;
                }

                if (! $this->pingRunning($process, $exitCode)) {
                    break;
                }
            }
        }

        if (is_resource($process) && $this->pingRunning($process, $exitCode)) {
            @proc_terminate($process, 9);
        }

        if (! $aborted) {
            $drainUntil = microtime(true) + 1.0;

            while (microtime(true) < $drainUntil) {
                $chunk = $this->pingSelectRead([$pipes[1], $pipes[2]], 150000);

                if ($chunk !== '') {
                    $buffer .= $chunk;
                }

                if (! $flushLines()) {
                    break;
                }

                if ($chunk === '' && feof($pipes[1]) && feof($pipes[2])) {
                    break;
                }
            }

            if ($buffer !== '') {
                $line = rtrim($buffer, "\r");
                $buffer = '';

                if ($line !== '') {
                    $collected .= $line."\n";
                    $seq++;
                    $emit(['type' => 'line', 'seq' => $seq, 'line' => $line]);
                }
            }
        }

        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

        $this->pingRunning($process, $exitCode);

        foreach ($pipes as $pipe) {
            if (is_resource($pipe)) {
                @fclose($pipe);
            }
        }

        if (is_resource($process)) {
            $closeCode = @proc_close($process);

            if ($exitCode === null && $closeCode >= 0) {
                $exitCode = $closeCode;
            }
        }

        $closed = true;
        @unlink($stopFile);

        if ($aborted) {
            return;
        }

        $collected = $this->pingSynthStats($collected, $durationMs);

        /* Lengkapi paket di ujung sesi yang tidak pernah dijawab. Jumlah
           "transmitted" dari blok statistik dipakai sebagai batas, supaya
           nomor icmp_seq terakhir juga tidak melompat di log. */
        if (preg_match('/(\d+)\s+packets transmitted/i', $collected, $tm) === 1) {
            $fillGap((int) $tm[1]);
        }

        $payload = $this->buildPingSummary(
            $target,
            $collected,
            $exitCode ?? 0,
            $durationMs,
            $deviceId,
            false,
            $command,
        );

        $payload['type'] = 'summary';
        $payload['stopped_by_user'] = $stoppedByUser;

        $emit($payload);
    }

    /**
     * Baca keluaran proses ping tanpa memblokir event loop di atas.
     *
     * @param  array<int, resource>  $streams
     */
    protected function pingSelectRead(array $streams, int $timeoutUs): string
    {
        $read = $streams;
        $write = null;
        $except = null;

        if (@stream_select($read, $write, $except, 0, $timeoutUs) < 1) {
            return '';
        }

        $out = '';

        foreach ($read as $stream) {
            $chunk = @fread($stream, 8192);

            if (is_string($chunk) && $chunk !== '') {
                $out .= $chunk;
            }
        }

        return $out;
    }

    /**
     * Status proses ping. Exit code yang valid (>= 0) disimpan ke $exitCode
     * begitu terbaca, karena PHP hanya melaporkannya satu kali setelah proses
     * selesai — panggilan berikutnya bisa saja sudah mengembalikan -1.
     */
    protected function pingRunning(mixed $process, ?int &$exitCode): bool
    {
        if (! is_resource($process)) {
            return false;
        }

        $status = @proc_get_status($process);

        if (! is_array($status)) {
            return false;
        }

        if (! $status['running'] && $status['exitcode'] >= 0 && $exitCode === null) {
            $exitCode = (int) $status['exitcode'];
        }

        return (bool) $status['running'];
    }

    /**
     * Balasan ping (latency ms) yang sudah terkumpul dari keluaran mentah.
     *
     * @return list<float>
     */
    protected function pingLatencies(string $output): array
    {
        preg_match_all('/\btime[=:]\s*([\d.]+)\s*ms/i', $output, $matches);

        return array_map('floatval', $matches[1] ?? []);
    }

    /**
     * Ringkasan live dari keluaran ping yang sudah terkumpul.
     *
     * Sengaja TIDAK mengembalikan persentase packet loss: angka persen tidak
     * boleh muncul di badge / indikator UI. Yang dikirim hanya status hasil
     * DiagnosisAnalyzer::statusFromProbe() — aturan yang sama persis dengan
     * badge daftar perangkat — plus jumlah paket dan latency rata-rata.
     *
     * @return array{sent: int, received: int, avg: float|null, status: string|null}
     */
    protected function pingTally(string $output, float $elapsedSeconds = 0.0): array
    {
        preg_match_all('/icmp_seq=(\d+)/', $output, $matches);
        $seqs = array_map('intval', $matches[1] ?? []);

        $seqSent = $seqs === [] ? 0 : max($seqs);
        $latencies = $this->pingLatencies($output);
        $received = count($latencies);
        $avg = $received > 0 ? round(array_sum($latencies) / $received, 1) : null;

        /* Selama ada baris icmp_seq, jumlah terkirim diambil dari seq tertinggi
           (paling akurat). Perkiraan dari lamanya ping (interval 1 detik) hanya
           dipakai untuk target senyap total (DROP) yang tidak mengeluarkan baris
           apa pun — tanpa itu statusnya menggantung di "belum ada data" walau
           targetnya jelas tidak merespons. Memakai perkiraan saat seq sudah ada
           justru membuat paket yang masih dalam perjalanan dihitung sebagai
           hilang, sehingga status sempat salah jadi "warning". */
        $sent = $seqSent > 0 ? $seqSent : (int) floor(max(0.0, $elapsedSeconds));

        if ($sent === 0 && $received === 0) {
            // Belum ada paket yang sama sekali: status belum boleh ditebak.
            return ['sent' => 0, 'received' => 0, 'avg' => null, 'status' => null];
        }

        $loss = $sent > 0 ? round((($sent - $received) / $sent) * 100, 1) : 100.0;

        return [
            'sent' => $sent,
            'received' => $received,
            'avg' => $avg,
            'status' => DiagnosisAnalyzer::statusFromProbe($loss, $avg),
        ];
    }

    /**
     * Lengkapi blok statistik ping bila proses berhenti sebelum ping sempat
     * mencetaknya — target yang senyap total (DROP, bukan host unreachable)
     * tidak mengeluarkan baris apa pun selama berjalan.
     *
     * Tanpa blok ini DiagnosisAnalyzer tidak punya angka packet loss /
     * latency, dan statusnya justru bisa terbaca UP padahal target mati.
     */
    protected function pingSynthStats(string $output, int $durationMs): string
    {
        if (preg_match('/packets transmitted/i', $output) === 1) {
            return $output;
        }

        $latencies = $this->pingLatencies($output);

        preg_match_all('/icmp_seq=(\d+)/', $output, $matches);
        $seqs = array_map('intval', $matches[1] ?? []);

        $received = count($latencies);
        $estimated = (int) floor(max(0.0, $durationMs / 1000));
        $sent = max(1, $seqs === [] ? $received : max($seqs), $received, $estimated);
        $loss = round((($sent - $received) / $sent) * 100, 1);

        $line = $sent.' packets transmitted, '.$received.' received, '
            .$loss.'% packet loss, time '.max(1, $durationMs).'ms';

        if ($received > 0) {
            $min = min($latencies);
            $max = max($latencies);
            $avg = array_sum($latencies) / $received;
            $variance = 0.0;

            foreach ($latencies as $latency) {
                $variance += ($latency - $avg) ** 2;
            }

            $line .= "\nrtt min/avg/max/mdev = "
                .round($min, 3).'/'.round($avg, 3).'/'.round($max, 3).'/'
                .round(sqrt($variance / $received), 3).' ms';
        }

        return $output."\n".$line."\n";
    }

    /**
     * Ringkasan akhir ping berkelanjutan — bentuknya sama persis dengan
     * respons POST /admin/tools/ping (runCommand) plus lapisan device,
     * asisten, dan peta jalur, supaya kartu hasil tidak perlu membedakan
     * dua jenis ping sama sekali.
     */
    protected function buildPingSummary(
        string $target,
        string $output,
        int $exitCode,
        int $durationMs,
        mixed $deviceId,
        bool $timedOut = false,
        string $command = 'ping',
    ): array {
        $now = now();

        $networkFault = false;

        if ($exitCode !== 0) {
            foreach (['Host Unreachable', 'Destination Host Unreachable', 'No route to host', 'Request timeout', 'Name or service not known', 'Timed Out', 'packet loss', '0 received'] as $fault) {
                if (str_contains($output, $fault)) {
                    $networkFault = true;
                    break;
                }
            }
        }

        $raw = [
            'output' => $output,
            'exit' => $exitCode,
            'timedout' => $timedOut,
            'duration_ms' => $durationMs,
        ];

        $payload = [
            'success' => $exitCode === 0,
            'label' => 'Ping',
            'summary' => $target,
            'command' => $command,
            'exit' => $exitCode,
            'duration' => round($durationMs / 1000, 2),
            'duration_ms' => $durationMs,
            'checked_at' => $now->format('H:i:s'),
            'checked_date' => $now->format('d/m/Y'),
            'output' => $output,
            'timedout' => $timedOut,
            'network_fault' => $networkFault,
            'error' => null,
            'analysis' => $this->analyze('ping', $raw),
        ];

        $payload['device'] = $this->deviceInfo($deviceId, $target);

        $payload['assistant'] = (new NetworkAssistant)->suggest(
            is_string($payload['analysis']['status'] ?? null) ? $payload['analysis']['status'] : null,
            $output,
            $payload['analysis'],
        );

        if (is_array($payload['assistant'])) {
            $payload['assistant']['status_detail'] = is_string($payload['analysis']['status_detail'] ?? null)
                ? $payload['analysis']['status_detail']
                : null;
        }

        $payload['path'] = $this->pathInfo($target, $output, $payload['analysis']);

        return $payload;
    }

    /**
     * Jawaban chat box AI Network Assistant (tanya-jawab engineer).
     *
     * Halaman mengirim pertanyaan bebas plus ringkasan hasil ping terakhir
     * yang barusan tampil di layar, supaya jawabannya menyinggung kondisi
     * jaringan yang sedang dibahas — misalnya "kenapa down?" langsung
     * dijawab dengan data packet loss / gateway dari pemeriksaan itu, bukan
     * jawaban umum.
     *
     * Kuerinya berbasis aturan (AssistantChat), bukan layanan AI luar:
     * tanpa API key, tanpa biaya per pesan, dan tanpa jeda jaringan saat
     * ada gangguan.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'context' => ['nullable', 'array'],
        ], [
            'question.required' => 'Tulis pertanyaan dulu sebelum mengirim ke asisten.',
            'question.max' => 'Pertanyaan terlalu panjang, maksimal 500 karakter.',
        ]);

        $result = (new AssistantChat)->answer(
            (string) $validated['question'],
            is_array($validated['context'] ?? null) ? $validated['context'] : [],
        );

        return response()->json([
            'success' => true,
            'answer' => $result['answer'],
            'topic' => $result['topic'],
        ]);
    }

    /**
     * Peta lompatan (hop) untuk visualisasi jalur paket di halaman.
     *
     * Data diambil dari dua perintah lokal yang murah, bukan dari tebakan:
     *
     *   1. `ip -4 route get <target>` -> alamat asal server dan gateway yang
     *      benar-benar dipakai kernel untuk merutekan paket ke target.
     *   2. `ping -c 1 <gateway>`       -> latency lompatan pertama, sekaligus
     *      penanda apakah gateway masih hidup.
     *
     * Keduanya gagal-aman: bila `ip` tidak tersedia, target tidak bisa
     * dirutekan, atau gateway tidak merespons, field-nya dikirim null dan
     * halaman tetap merender diagram dari data yang ada.
     *
     * @param  array<string, mixed>  $analysis
     * @return array<string, mixed>
     */
    protected function pathInfo(string $target, string $output, array $analysis): array
    {
        $stats = (new DiagnosisAnalyzer)->parsePing($output);

        $source = null;
        $gateway = null;

        try {
            $route = Process::timeout(5)->run('ip -4 route get '.escapeshellarg($target));

            if ($route->successful()) {
                if (preg_match('/\bsrc\s+(\S+)/', $route->output(), $m) === 1) {
                    $source = $m[1];
                }
                if (preg_match('/\bvia\s+(\S+)/', $route->output(), $m) === 1) {
                    $gateway = $m[1];
                }
            }
        } catch (\Throwable $e) {
            // Perintah `ip` tidak ada atau target tidak dirutekan: lanjut
            // tanpa sumber/gateway, diagram tetap bisa digambar.
        }

        $gatewayLatency = null;
        $gatewayReachable = null;

        if ($gateway !== null) {
            try {
                $probe = Process::timeout(5)->run('ping -4 -c 1 -W 2 '.escapeshellarg($gateway));
                $probeStats = (new DiagnosisAnalyzer)->parsePing($probe->output());

                $gatewayReachable = $probe->successful() && $probeStats['received'] !== 0;
                $gatewayLatency = $probeStats['avg'];
            } catch (\Throwable $e) {
                $gatewayReachable = false;
            }
        }

        return [
            'source' => $source,
            'gateway' => $gateway,
            'gateway_reachable' => $gatewayReachable,
            'gateway_latency_ms' => $gatewayLatency,
            'target' => $target,
            'target_latency_ms' => $stats['avg'],
            'target_loss_pct' => $stats['loss'],
            'status' => is_string($analysis['status'] ?? null) ? $analysis['status'] : 'info',
        ];
    }

    public function trace(Request $request)
    {
        $target = $this->resolveTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        // Dua nama field dipakai bergantian: `max_hops` oleh kontrak API dan
        // `hops` oleh form di halaman. Keduanya diterima supaya hasil
        // pemeriksaan tidak bergantung pada pemanggil mana pun.
        $hops = $request->has('max_hops')
            ? $request->integer('max_hops')
            : $request->integer('hops', 32);

        if ($hops < 1 || $hops > 64) {
            return $this->errorResponse('Trace max hops harus antara 1 hingga 64.');
        }

        if ($this->commandExists('traceroute')) {
            $command = 'traceroute -m '.$hops.' -w 2 -q 1 '.escapeshellarg($target);
        } elseif ($this->commandExists('tracepath')) {
            $command = 'tracepath -m '.$hops.' '.escapeshellarg($target);
        } else {
            return $this->errorResponse('Perintah traceroute / tracepath tidak tersedia di server ini.');
        }

        $response = $this->runCommand('Trace', $target, $command, 90, 'trace', [
            'target' => $target,
        ]);

        // Lampirkan status perangkat dari database (bila target cocok dengan
        // perangkat terdaftar) supaya ringkasan traceroute sinkron dengan
        // data monitoring Server Radnet, sama seperti pada Ping.
        $payload = $response->getData(true);
        $payload['device'] = $this->deviceInfo($request->input('device_id'), $target);

        return response()->json($payload);
    }

    public function lookup(Request $request)
    {
        $target = $this->resolveTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $types = ['A', 'AAAA', 'CNAME', 'MX', 'NS'];
        $lookup = strtoupper($request->input('lookup_type', 'A'));
        if (! in_array($lookup, $types, true)) {
            return $this->errorResponse('Lookup type harus salah satu dari: '.implode(', ', $types).'.');
        }

        $server = trim($request->input('lookup_server', ''));
        if ($server !== '' && $this->validateTarget($server) === null) {
            return $this->errorResponse('Lookup server tidak valid.');
        }

        // Urutan alat: dig paling lengkap (bisa memilih server DNS sendiri),
        // lalu nslookup, lalu getent sebagai cadangan terakhir karena
        // getent hanya bisa membaca A/AAAA.
        if ($this->commandExists('dig')) {
            $command = 'dig +time=5 +tries=1 -t '.strtolower($lookup).' '.escapeshellarg($target);
            if ($server !== '') {
                $command .= ' @'.escapeshellarg($server);
            }
        } elseif ($this->commandExists('nslookup')) {
            $command = 'nslookup -type='.$lookup.' -timeout=5 '.escapeshellarg($target);
            if ($server !== '') {
                $command .= ' '.escapeshellarg($server);
            }
        } else {
            $command = 'getent ahosts '.escapeshellarg($target);
        }

        $response = $this->runCommand('Lookup ('.$lookup.')', $target, $command, 30, 'lookup', [
            'type' => $lookup,
        ]);

        $payload = $response->getData(true);
        $payload['device'] = $this->deviceInfo($request->input('device_id'), $target);

        return response()->json($payload);
    }

    public function scan(Request $request)
    {
        $target = $this->resolveTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        // `port_from` / `port_to` = nama kontrak API, `first_port` /
        // `last_port` = nama field pada form di halaman.
        $first = $request->has('port_from')
            ? $request->integer('port_from')
            : $request->integer('first_port', 1);

        $last = $request->has('port_to')
            ? $request->integer('port_to')
            : $request->integer('last_port', 512);

        if ($first < 1 || $last < 1 || $first > 65535 || $last > 65535) {
            return $this->errorResponse('Port harus berada pada rentang 1 hingga 65535.');
        }

        if ($first > $last) {
            return $this->errorResponse('Scan first port harus lebih kecil atau sama dengan scan last port.');
        }

        $range = $last - $first + 1;
        if ($range > 1024) {
            return $this->errorResponse('Rentang port maksimal 1024 port per scan.');
        }

        $timeout = $request->integer('scan_timeout', 300);
        if ($timeout < 100 || $timeout > 2000) {
            return $this->errorResponse('Scan timeout harus antara 100 hingga 2000 ms.');
        }

        $phpScript = <<<'PHP'
$target  = $argv[1];
$first   = (int) $argv[2];
$last    = (int) $argv[3];
$timeout = max(0.1, min(2.0, (int) $argv[4] / 1000));
$deadline = microtime(true) + $timeout;
$socks  = [];
foreach (range($first, $last) as $p) {
    $fp = @stream_socket_client("tcp://{$target}:{$p}", $errno, $errstr, 0.0);
    if (is_resource($fp)) {
        stream_set_blocking($fp, false);
        $socks[$p] = $fp;
    }
}
$left = $socks;
while ($left && microtime(true) < $deadline) {
    $r = array_values($left);
    $w = $r;
    $e = null;
    if (@stream_select($r, $w, $e, 0, 50000) === false) {
        break;
    }
    foreach ($w as $fp) {
        $p = array_search($fp, $left, true);
        if ($p === false) {
            continue;
        }
        $peer = '';
        $sock = @socket_import_stream($fp);
        if ($sock !== false && @socket_getpeername($sock, $peer) === true) {
            printf("%-6d  open\n", $p);
            fclose($fp);
            unset($left[$p]);
        }
    }
}
foreach ($left as $fp) {
    if (is_resource($fp)) {
        fclose($fp);
    }
}
PHP;

        $command = 'php -r '.escapeshellarg($phpScript).' '.escapeshellarg($target).' '.$first.' '.$last.' '.$timeout;

        $response = $this->runCommand('Scan :'.$first.'-'.$last, $target.':'.$first.'-'.$last, $command, 90, 'scan', [
            'first' => $first,
            'last' => $last,
        ]);

        $payload = $response->getData(true);
        $payload['device'] = $this->deviceInfo($request->input('device_id'), $target);

        return response()->json($payload);
    }

    /**
     * Bersihkan TOTAL seluruh riwayat hasil pemeriksaan target.
     *
     * Satu endpoint untuk semua sisa hasil pemeriksaan, bukan hanya tabel
     * log: log histori (monitoring_logs, log_archives, ping_history),
     * angka latency yang tersimpan di baris device, dan cache hasil
     * pemeriksaan per target. Rinciannya ada di CheckHistoryPurger —
     * termasuk daftar hal yang sengaja TIDAK disentuh (daftar perangkat,
     * topologi, catatan, audit).
     *
     * Penghapusan permanen, tanpa arsip dan tanpa soft delete, jadi
     * endpoint-nya dibatasi Super Admin di routes/web.php.
     *
     * Proses MonitorDevices tetap berjalan terjadwal dan akan mencatat
     * hasil pemeriksaan baru beberapa menit setelah pembersihan. Itu data
     * baru, bukan sisa lama.
     */
    public function purgeAll(CheckHistoryPurger $purger): JsonResponse
    {
        $report = $purger->purge();

        if ($report['failed'] !== []) {
            return response()->json([
                'success' => false,
                'message' => 'Sebagian data belum bisa dibersihkan: '.implode(', ', array_keys($report['failed']))
                    .'. Bagian lain yang berhasil sudah tetap dikosongkan.',
                'report' => $report,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'report' => $report,
            'message' => $this->purgeMessage($report),
        ]);
    }

    /**
     * Pesan hasil pembersihan, tanpa menyebut bagian yang benar-benar
     * berisi data supaya "tidak ada yang tersisa" bisa dibuktikan dari layar,
     * bukan sekadar diasumsikan.
     *
     * @param  array{tables: array<string, int>, rows: int, device_metrics_reset: int, cache_entries_cleared: int, cache_supported: bool, failed: array<string, string>}  $report
     */
    protected function purgeMessage(array $report): string
    {
        $lines = [];

        $lines[] = $report['rows'] === 0
            ? 'Tidak ada riwayat pemeriksaan yang tersimpan — tabel log sudah kosong.'
            : sprintf(
                '%s baris riwayat hasil pemeriksaan dihapus permanen (%s).',
                number_format($report['rows'], 0, ',', '.'),
                $this->countSummary($report['tables'])
            );

        $lines[] = $report['device_metrics_reset'] === 0
            ? 'Ringkasan latency tidak ada yang perlu dinolkan.'
            : sprintf(
                'Ringkasan latency (%s) dinolkan pada %s device.',
                implode(' + ', self::DEVICE_METRIC_LABELS),
                number_format($report['device_metrics_reset'], 0, ',', '.')
            );

        if (! $report['cache_supported']) {
            $lines[] = 'Cache target tidak bisa dibersihkan dari sini karena cache store bukan database — periksa config CACHE_STORE.';
        } elseif ($report['cache_entries_cleared'] > 0) {
            $lines[] = sprintf(
                '%s entri cache target dihapus, pemeriksaan berikutnya mengetuk perangkat langsung.',
                number_format($report['cache_entries_cleared'], 0, ',', '.')
            );
        } else {
            $lines[] = 'Cache target sudah kosong.';
        }

        $lines[] = 'Daftar perangkat, topologi, catatan, dan audit tidak ikut terhapus.';

        return implode(' ', $lines);
    }

    /**
     * Ringkasan "220 Log Monitoring, 0 Arsip Log" untuk pesan di layar.
     *
     * @param  array<string, int>  $counts
     */
    protected function countSummary(array $counts): string
    {
        $parts = [];

        foreach ($counts as $label => $count) {
            $parts[] = number_format($count, 0, ',', '.').' '.$label;
        }

        return implode(', ', $parts);
    }

    /**
     * Terjemahkan token target lokal jadi alamat loopback, lalu validasi.
     *
     * Pemisahan ini disengaja: `validateTarget()` tetap murni validator dan
     * menolak "@local" apa adanya, karena "@local" bukan alamat yang sah.
     * Tokennya baru di sini diterjemahkan ke 127.0.0.1 supaya tidak pernah
     * lolos mentah-mentah ke baris perintah.
     */
    protected function resolveTarget(mixed $value): ?string
    {
        if (is_string($value) && strtolower(trim($value)) === self::LOCAL_TARGET_TOKEN) {
            return self::LOCAL_TARGET_IP;
        }

        return $this->validateTarget(is_string($value) ? $value : null);
    }

    protected function validateTarget(?string $value): ?string
    {
        if (! is_string($value) || $value === '' || mb_strlen($value) > 255) {
            return null;
        }

        $value = trim($value);

        if ((bool) filter_var($value, FILTER_VALIDATE_IP) ||
            (bool) filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return $value;
        }

        return null;
    }

    protected function commandExists(string $name): bool
    {
        try {
            $result = Process::timeout(5)->run('command -v '.escapeshellarg($name).' 2>/dev/null');

            return $result->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Status perangkat dari database (dipakai Terminal Output agar ringkasan
     * status sinkron dengan data monitoring, bukan sekadar tebakan dari hasil
     * uji ping manual).
     *
     * - device_id dipakai bila dikirim (target berasal dari daftar perangkat).
     * - Fallback: cari berdasarkan IP/hostname target (cek tunjuk ke perangkat
     *   terdaftar walau dites lewat Target Kustom).
     * - device_id yang tidak konsisten dengan target diabaikan.
     *
     * @return array<string, mixed>|null
     */
    protected function deviceInfo(mixed $deviceId, string $target): ?array
    {
        try {
            $device = null;

            if (is_numeric($deviceId)) {
                $device = Device::find((int) $deviceId);
                if ($device && (string) $device->ip_address !== $target) {
                    $device = null; // target berbeda dari perangkat terpilih
                }
            }

            if (! $device) {
                $device = Device::where('ip_address', $target)->first();
            }

            if (! $device) {
                return null;
            }

            $lastLog = MonitoringLog::query()
                ->where('device_id', $device->id)
                ->orderByDesc('checked_at')
                ->orderByDesc('id')
                ->first();

            return [
                'id' => (int) $device->id,
                'name' => (string) $device->name,
                'ip_address' => (string) $device->ip_address,
                'status' => in_array($device->status, ['up', 'down', 'warning'], true)
                    ? (string) $device->status
                    : 'unknown',
                'avg_delay' => $lastLog?->response_time !== null
                    ? (float) $lastLog->response_time
                    : null,
                'last_checked' => $lastLog?->checked_at?->format('d/m/y H:i:s'),
            ];
        } catch (\Throwable $e) {
            // Database monitoring belum siap (mis. tabel devices belum ada):
            // diagnostik tetap boleh jalan, hanya info perangkat yang absen.
            return null;
        }
    }

    /**
     * Jalankan perintah melalui Laravel Process facade (pembungkus Symfony Process).
     * Tangkap exception asli agar pesan error yang muncul informatif.
     *
     * Setiap respons selalu membawa tiga lapis informasi yang terpisah:
     *
     *   1. `output`      log mentah — bukti, disimpan apa adanya.
     *   2. `checked_at` / `checked_date` / `duration_ms` — kapan dan berapa lama.
     *   3. `analysis`    kesimpulan bahasa manusia dari DiagnosisAnalyzer.
     *
     * Pemisahan itu penting: log mentah tidak boleh disimpulkan sendiri oleh
     * browser (hasilnya bisa berbeda antar alat), dan analisis tidak boleh
     * menghapus log mentah (bukti hilang). Frontend cukup merender `analysis`
     * dan tetap bisa membuka `output` sebagai bukti.
     *
     * @param  array<string, mixed>  $context  petunjuk tambahan per alat
     */
    protected function runCommand(
        string $label,
        string $summary,
        string $command,
        int $timeout,
        string $tool = 'ping',
        array $context = [],
    ): JsonResponse {
        $startedAt = microtime(true);
        $output = null;
        $exitCode = 0;
        $timedOut = false;
        $errorMsg = null;

        try {
            $result = Process::timeout($timeout)->run($command);

            $output = trim($result->output());
            $exitCode = $result->exitCode();
        } catch (ProcessTimedOutException $e) {
            $timedOut = true;
            $exitCode = 127;
            $errorMsg = $e->getMessage();
        } catch (\Throwable $e) {
            $exitCode = 126;
            $errorMsg = $e->getMessage();
        }

        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);
        $now = now();

        $networkFault = false;

        if ($exitCode !== 0) {
            foreach (['Host Unreachable', 'Destination Host Unreachable', 'No route to host', 'Request timeout', 'Name or service not known', 'Timed Out', 'packet loss', '0 received'] as $fault) {
                if (($output !== null && str_contains($output, $fault)) || ($errorMsg !== null && str_contains($errorMsg, $fault))) {
                    $networkFault = true;
                    break;
                }
            }
        }

        $raw = array_merge($context, [
            'output' => (string) $output,
            'exit' => $exitCode,
            'timedout' => $timedOut,
            'duration_ms' => $durationMs,
        ]);

        $payload = [
            'success' => $exitCode === 0,
            'label' => $label,
            'summary' => $summary,
            'command' => $command,
            'exit' => $exitCode,
            'duration' => round($durationMs / 1000, 2),
            'duration_ms' => $durationMs,
            'checked_at' => $now->format('H:i:s'),
            'checked_date' => $now->format('d/m/Y'),
            'output' => $output,
            'timedout' => $timedOut,
            'network_fault' => $networkFault,
            'error' => $errorMsg,
            'analysis' => $this->analyze($tool, $raw),
        ];

        return response()->json($payload);
    }

    /**
     * Terjemahkan hasil mentah menjadi status + alasan bahasa manusia.
     *
     * DiagnosisAnalyzer dijamin tidak melempar exception dan selalu
     * mengembalikan bentuk yang sama, jadi就算 alatnya belum dikenali pun
     * halaman tetap punya satu kotak "Alasan" yang tidak kosong.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function analyze(string $tool, array $raw): array
    {
        $analyzer = new DiagnosisAnalyzer;

        try {
            return match ($tool) {
                'trace' => $analyzer->trace($this->traceFacts($raw)),
                'lookup' => $analyzer->lookup($this->lookupFacts($raw)),
                'scan' => $analyzer->scan($this->scanFacts($raw)),
                default => $analyzer->ping($raw),
            };
        } catch (\Throwable $e) {
            return [
                'status' => 'info',
                'label' => 'INFO',
                'headline' => 'Hasil belum bisa dianalisis',
                'reason' => 'Pemeriksaan selesai, tetapi rinciannya belum bisa dibaca: '
                    .$e->getMessage(),
                'facts' => [],
                'recommendation' => 'Buka Log Terminal (mentah) di bawah untuk melihat keluaran aslinya.',
            ];
        }
    }

    /**
     * Ringkas hasil traceroute: berapa lompatan, apakah sampai, dan mana yang
     * paling lambat. Tanpa ini, traceroute hanya jadi log panjang tanpa
     * kesimpulan.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function traceFacts(array $raw): array
    {
        $output = (string) ($raw['output'] ?? '');
        $target = trim((string) ($raw['target'] ?? ''));

        $hops = 0;
        $silent = 0;
        $arrived = null;
        $slowestMs = null;
        $slowestHop = 0;
        $lastHopNumber = null;
        $lastHopReplied = false;
        $targetSeenAt = null;

        foreach (preg_split('/\R/', $output) ?: [] as $line) {
            $line = trim($line);

            if ($line === '' || preg_match('/^(traceroute to|tracepath to|resume:|too many hops|ping:)/i', $line)) {
                continue;
            }

            // Baris lompatan selalu diawali nomor urut.
            if (preg_match('/^(\d+)\s+(.*)$/', $line, $m) !== 1) {
                continue;
            }

            $number = (int) $m[1];
            $rest = $m[2];

            if ($number > $hops) {
                $hops = $number;
            }

            $hasReply = preg_match('/\d+(?:\.\d+)?\s*ms/i', $rest) === 1;

            if (! $hasReply) {
                $silent++;
            } else {
                preg_match_all('/(\d+(?:\.\d+)?)\s*ms/i', $rest, $times);

                foreach ($times[1] as $time) {
                    $value = (float) $time;

                    if ($slowestMs === null || $value > $slowestMs) {
                        $slowestMs = $value;
                        $slowestHop = $number;
                    }
                }
            }

            if ($lastHopNumber === null || $number > $lastHopNumber) {
                $lastHopNumber = $number;
                $lastHopReplied = $hasReply;
            } elseif ($number === $lastHopNumber) {
                $lastHopReplied = $lastHopReplied || $hasReply;
            }

            if ($targetSeenAt === null && $target !== '' && str_contains($rest, $target)) {
                $targetSeenAt = $number;
            }
        }

        /* Dua bukti "sampai", dan yang menang adalah yang paling lemah:
           hop terakhir membalas, ATAU alamat tujuan muncul di salah satu hop.
           Kalau keduanya tidak ada, jalur benar-benar berhenti di tengah dan
           perangkat tujuan tidak boleh dianggap hidup. */
        if ($lastHopReplied) {
            $arrived = $lastHopNumber;
        } elseif ($targetSeenAt !== null) {
            $arrived = $targetSeenAt;
        }

        return array_merge($raw, [
            'hops' => $hops,
            'silent' => $silent,
            'arrived' => $arrived,
            'slowest_ms' => $slowestMs,
            'slowest_hop' => $slowestHop,
        ]);
    }

    /**
     * Hitung data DNS yang benar-benar ditemukan, dan bedakan "nama tidak ada"
     * dari "nama ada tapi record yang dicari tidak ada" — dua masalah yang
     * punya tindakan saling berlawanan.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function lookupFacts(array $raw): array
    {
        $output = (string) ($raw['output'] ?? '');

        $notFound = (bool) preg_match('/NXDOMAIN|nxdomain/i', $output);
        $noServer = (bool) preg_match('/no servers could be reached|connection timed out|SERVFAIL/i', $output);

        $records = 0;

        foreach (preg_split('/\R/', $output) ?: [] as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, ';')) {
                continue;
            }

            $records++;
        }

        // `dig` mencetak satu baris per record, sedangkan nslookup/getent
        // menulis blok jawaban yang lebih panjang. Kalau tidak ada satu pun
        // baris jawaban, perlakukan sebagai belum ada record.
        return array_merge($raw, [
            'found' => $records,
            'not_found' => $notFound,
            'no_record' => ! $notFound && ! $noServer && $records === 0,
        ]);
    }

    /**
     * Kumpulkan port yang benar-benar terbuka dari log scan.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function scanFacts(array $raw): array
    {
        $output = (string) ($raw['output'] ?? '');
        $open = [];

        if (preg_match_all('/^\s*(\d{1,5})\s+open\b/im', $output, $m) > 0) {
            $open = array_values(array_unique(array_map('intval', $m[1])));
        }

        return array_merge($raw, ['open' => $open]);
    }

    protected function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            // Isian salah tidak boleh membuat kotak "Alasan" kosong: dari sisi
            // pengguna, hasil pemeriksaan memang gagal, jadi statusnya DOWN
            // dengan alasan yang menyebut apa yang salah.
            'analysis' => [
                'status' => 'down',
                'label' => 'DOWN',
                'headline' => 'Pemeriksaan tidak bisa dijalankan',
                'reason' => $message,
                'facts' => [],
                'recommendation' => 'Perbaiki isian di atas lalu tekan tombolnya lagi.',
                'status_detail' => 'Invalid Target',
            ],
        ], 422);
    }

    /**
     * Respons gagal-aman untuk exception tak terduga di dalam pengendali.
     *
     * Kontraknya sama dengan respons normal: selalu JSON, selalu punya
     * `analysis` dan `assistant`. Dengan begitu browser tetap bisa menampilkan
     * kartu hasil (status DOWN) alih-alih menggantung di status "Menganalisis…"
     * atau menampilkan error mentah.
     */
    protected function failedJson(string $message, ?string $target = null, ?\Throwable $e = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'label' => 'Ping',
            'summary' => (string) ($target ?? ''),
            'output' => '',
            'analysis' => [
                'status' => 'down',
                'label' => 'DOWN',
                'headline' => 'Pemeriksaan tidak bisa diselesaikan',
                'reason' => $message,
                'facts' => [],
                'recommendation' => 'Jalankan ulang pemeriksaan. Bila tetap gagal, periksa koneksi server ke perangkat.',
                'status_detail' => 'Request Timed Out',
            ],
            'assistant' => [
                'level' => 'danger',
                'title' => 'GANGGUAN — Pemeriksaan gagal dijalankan',
                'message' => $message,
                'status_detail' => 'Request Timed Out',
                'actions' => [
                    'Jalankan ulang pengecekan; target bisa saja tidak merespons sama sekali (100% RTO).',
                    'Periksa jalur fisik kabel/konektor dan power supply perangkat bila masalah berlanjut.',
                    'Pastikan server monitoring dapat menjangkau target sebelum menaikkan ke tim lapangan.',
                ],
                'metrics' => ['packet_loss_pct' => null, 'latency_ms' => null, 'jitter_ms' => null],
            ],
            'error' => $e?->getMessage(),
        ], 500);
    }
}
