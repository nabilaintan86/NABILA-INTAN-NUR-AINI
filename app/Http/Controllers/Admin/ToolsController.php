<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\MonitoringLog;
use App\Services\DeviceCsvService;
use App\Services\NetworkTools\DiagnosisAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        'epon'   => ['icon' => 'ni-signal',     'label' => 'EPON'],
        'olt'    => ['icon' => 'ni-server',     'label' => 'OLT'],
        'other'  => ['icon' => 'ni-wifi',       'label' => 'Perangkat'],
    ];

    /** Token yang dikirim browser untuk "Server Radnet (Server Lokal)". */
    private const LOCAL_TARGET_TOKEN = '@local';

    /** Alamat yang dipakai token di atas. Loopback, jadi selalu milik server ini. */
    private const LOCAL_TARGET_IP = '127.0.0.1';

    public function index(DeviceCsvService $csv)
    {
        $devices = $csv->devices()
            ->map(fn (array $device) => $this->presentDevice($device))
            ->sortBy(fn ($device) => match ($device->status) {
                'down'    => 0,
                'warning' => 1,
                'up'      => 2,
                default   => 3,
            }, SORT_REGULAR)
            ->values();

        // Halaman /admin/tools sengaja TIDAK lagi mengirim ringkasan
        // ($summary / $areaSummary). Bar dan badge statistik kecil di bagian
        // atas halaman sudah dihapus, dan angkanya memang hanya mengulang
        // apa yang sudah terlihat di daftar perangkat: jumlah baris dan badge
        // status tiap baris.
        //
        // Kalau suatu saat butuh total global yang jujur (mis. untuk laporan
        // atau audit sinkronisasi devices.csv), bacalah dari
        // DeviceCsvService::officialSummary() — satu sumber yang sama dengan
        // Dashboard, Monitoring, DHCP Lease, dan Log. Jangan menghitung
        // ulang di sini, supaya angka antar halaman tidak bisa berbeda.
        return view('admin.tools', [
            'devices'        => $devices,
            'analysisLimits' => $this->analysisLimits(),
        ]);
    }

    /**
     * Bentuk satu baris perangkat untuk ditampilkan.
     *
     * Status dinormalkan di sini, sekali saja, supaya seluruh halaman memakai
     * definisi "UP / WARNING / DOWN" yang sama. Nilai yang tidak dikenal
     * diperlakukan sebagai `up` hanya kalau memang kosong; nilai asing
     * seperti "offline" atau angka tidak ikut diteruskan ke badge, karena
     * kelas statusnya tidak ada sehingga warnanya jadi tidak terbaca.
     *
     * @param  array<string, mixed>  $device
     * @return object
     */
    protected function presentDevice(array $device): object
    {
        $status = strtolower(trim((string) ($device['status'] ?? '')));
        $status = in_array($status, ['up', 'down', 'warning'], true) ? $status : 'up';

        $type = strtolower(trim((string) ($device['type'] ?? '')));
        $type = self::DEVICE_TYPES[$type] ?? self::DEVICE_TYPES['other'];

        return (object) [
            'id'                => $device['id'] ?? null,
            'name'              => (string) ($device['name'] ?? ''),
            'ip_address'        => (string) ($device['ip_address'] ?? ''),
            'status'            => $status,
            'type'              => $type['label'],
            'type_icon'         => $type['icon'],
            'raw_type'          => (string) ($device['type'] ?? ''),
            'avg_delay'         => (float) ($device['avg_delay'] ?? 0),
            'missed_percentage' => (float) ($device['missed_percentage'] ?? 0),
            'pop_name'          => (string) ($device['pop_name'] ?? ''),
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
            'goodLatencyMs'  => DiagnosisAnalyzer::GOOD_LATENCY_MS,
            'poorLatencyMs'  => DiagnosisAnalyzer::POOR_LATENCY_MS,
            'jitterMs'       => DiagnosisAnalyzer::JITTER_MS,
            'minorLossPct'   => DiagnosisAnalyzer::MINOR_LOSS_PCT,
            'majorLossPct'   => DiagnosisAnalyzer::MAJOR_LOSS_PCT,
        ];
    }

    /**
     * Ping tujuan dengan flag standar Linux:
     *   ping -c <count> -W <timeout> <target>
     */
    public function ping(Request $request)
    {
        $target = $this->resolveTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $count = $request->integer('count', 5);
        if ($count < 1 || $count > 100) {
            return $this->errorResponse('Ping count harus antara 1 hingga 100.');
        }

        $command = 'ping -4 -c '.$count.' -W 3 '.escapeshellarg($target);
        $timeout = max(30, $count * 4 + 5);

        $response = $this->runCommand('Ping', $target, $command, $timeout);

        // Lampirkan status asli perangkat dari database (bila target adalah
        // perangkat terdaftar), agar Terminal Output sinkron dengan status
        // sebenarnya alih-alih hanya menebak dari packet loss uji manual.
        $payload = $response->getData(true);
        $payload['device'] = $this->deviceInfo($request->input('device_id'), $target);

        return response()->json($payload);
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

        return $this->runCommand('Trace', $target, $command, 90, 'trace', [
            'target' => $target,
        ]);
    }

    public function lookup(Request $request)
    {
        $target = $this->resolveTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $types  = ['A', 'AAAA', 'CNAME', 'MX', 'NS'];
        $lookup = strtoupper($request->input('lookup_type', 'A'));
        if (!in_array($lookup, $types, true)) {
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

        return $this->runCommand('Lookup ('.$lookup.')', $target, $command, 30, 'lookup', [
            'type' => $lookup,
        ]);
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

        return $this->runCommand('Scan :'.$first.'-'.$last, $target.':'.$first.'-'.$last, $command, 90, 'scan', [
            'first' => $first,
            'last'  => $last,
        ]);
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
        if (!is_string($value) || $value === '' || mb_strlen($value) > 255) {
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
            'id'           => (int) $device->id,
            'name'         => (string) $device->name,
            'ip_address'   => (string) $device->ip_address,
            'status'       => in_array($device->status, ['up', 'down', 'warning'], true)
                ? (string) $device->status
                : 'unknown',
            'avg_delay'    => $lastLog?->response_time !== null
                ? (float) $lastLog->response_time
                : null,
            'last_checked' => $lastLog?->checked_at?->format('d/m/y H:i:s'),
        ];
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
        $output    = null;
        $exitCode  = 0;
        $timedOut  = false;
        $errorMsg  = null;

        try {
            $result = Process::timeout($timeout)->run($command);

            $output   = trim($result->output());
            $exitCode = $result->exitCode();
        } catch (ProcessTimedOutException $e) {
            $timedOut  = true;
            $exitCode  = 127;
            $errorMsg  = $e->getMessage();
        } catch (\Throwable $e) {
            $exitCode  = 126;
            $errorMsg  = $e->getMessage();
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
            'output'     => (string) $output,
            'exit'       => $exitCode,
            'timedout'   => $timedOut,
            'duration_ms' => $durationMs,
        ]);

        $payload = [
            'success'      => $exitCode === 0,
            'label'        => $label,
            'summary'      => $summary,
            'command'      => $command,
            'exit'         => $exitCode,
            'duration'     => round($durationMs / 1000, 2),
            'duration_ms'  => $durationMs,
            'checked_at'   => $now->format('H:i:s'),
            'checked_date' => $now->format('d/m/Y'),
            'output'       => $output,
            'timedout'     => $timedOut,
            'network_fault' => $networkFault,
            'error'        => $errorMsg,
            'analysis'     => $this->analyze($tool, $raw),
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
        $analyzer = new DiagnosisAnalyzer();

        try {
            return match ($tool) {
                'trace'  => $analyzer->trace($this->traceFacts($raw)),
                'lookup' => $analyzer->lookup($this->lookupFacts($raw)),
                'scan'   => $analyzer->scan($this->scanFacts($raw)),
                default  => $analyzer->ping($raw),
            };
        } catch (\Throwable $e) {
            return [
                'status' => 'info',
                'label'  => 'INFO',
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
            'hops'        => $hops,
            'silent'      => $silent,
            'arrived'     => $arrived,
            'slowest_ms'  => $slowestMs,
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
            'found'     => $records,
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
            'success'  => false,
            'message'  => $message,
            // Isian salah tidak boleh membuat kotak "Alasan" kosong: dari sisi
            // pengguna, hasil pemeriksaan memang gagal, jadi statusnya DOWN
            // dengan alasan yang menyebut apa yang salah.
            'analysis' => [
                'status'  => 'down',
                'label'   => 'DOWN',
                'headline' => 'Pemeriksaan tidak bisa dijalankan',
                'reason'  => $message,
                'facts'   => [],
                'recommendation' => 'Perbaiki isian di atas lalu tekan tombolnya lagi.',
            ],
        ], 422);
    }
}