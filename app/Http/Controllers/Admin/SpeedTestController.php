<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\SpeedTestLog;
use App\Services\SpeedTestService;
use App\Services\Ssh\SshConnection;
use App\Services\Ssh\SshUnavailableException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Halaman Speed Test — KHUSUS Super Admin.
 *
 * Akses diatur di routes/web.php lewat middleware 'role:super_admin', yang
 * applies ke halaman, endpoint jalankan, dan endpoint riwayat sekaligus.
 * Controller ini tetap memverifikasi ulang perannya supaya halaman tidak
 * pernah bisa dibuka walau route-nya nanti salah ubah.
 *
 * Super Admin juga boleh mengarahkan pengujian ke salah satu perangkat di
 * tabel `device` (dropdown "Target uji"). Client hanya mengirim device_id;
 * nama dan IP diambil ulang dari database, dan hanya device yang punya
 * alamat IP valid yang bisa dipilih.
 */
class SpeedTestController extends Controller
{
    /** Berapa baris riwayat yang ditampilkan. */
    public const HISTORY_LIMIT = 15;

    /** Opsi nilai device_id saat pengujian dijalankan di server NOC. */
    public const LOCAL_TARGET = '0';

    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        // Riwayat TIDAK dikirim ke view: tabelnya diisi lewat AJAX dari
        // endpoint history, jadi halaman render pertama tetap ringan dan
        // tidak perlu query log sia-sia.
        return view('admin.speedtest', [
            'devices' => $this->targets(),
            'remoteReady' => $this->remoteTestReady(),
            'allowedCidrs' => (array) config('ssh.allowed_cidrs', []),
            'historyLimit' => self::HISTORY_LIMIT,

            // Alamat IP perangkat yang sedang membuka halaman. Dikirim dari
            // server supaya panel "Informasi Koneksi" tetap terisi walau
            // CDN luar tidak dapat dijangkau browser. Nilai yang tampil di
            // halaman tetap alamat publik dari CDN kalau bisa dibaca, karena
            // di balik proxy nilai $request->ip() bisa jadi IP proxy.
            'clientIp' => $request->ip(),
        ]);
    }

    /**
     * Endpoint tong unggah milik RADNET NOC sendiri.
     *
     * Browser TIDAK bisa mengukur laju unggah ke CDN luar: permintaan POST
     * lintas asal akan ditolak CORS sebelum datanya sampai. Karena itu laju
     * unggah diukur ke endpoint first-party ini: bodinya langsung dibaca
     * lalu dibuang, dan Browser membandingkan ukuran payload dengan waktu
     * tempuhnya sendiri.
     *
     * Yang dikembalikan hanya jumlah byte yang diterima supaya frontend bisa
     * memastikan pengirimannya utuh. Isi payload tidak pernah disimpan,
     * tidak masuk log, dan tidak menyentuh database.
     */
    public function uploadSink(Request $request): JsonResponse
    {
        $this->authorizeSuperAdmin();

        $bytes = $this->drainRequestBody($request);

        return response()->json([
            'success' => true,
            'received' => $bytes,
        ]);
    }

    /**
     * Baca lalu buang isi request tanpa memegangnya di memori.
     *
     * Payload unggah bisa berukuran belasan megabyte, jadi dibaca per
     * potongan 64 KB. Isinya langsung dibuang: tidak disimpan ke variabel
     * yang menumpuk, tidak ditulis ke disk, tidak masuk log.
     *
     * Kalau dibaca sekaligus, satu unggahan besar bisa menghabiskan memori
     * worker PHP hanya untuk lalu dibuang — tidak ada gunanya.
     */
    private function drainRequestBody(Request $request): int
    {
        $stream = $request->getContent(true);

        // Tidak semua server menyediakan resource; kalau tidak, jatuhkan
        // ke pembacaan biasa supaya angka tetap benar.
        if (! is_resource($stream)) {
            return strlen((string) $stream);
        }

        $bytes = 0;

        while (! feof($stream)) {
            $chunk = fread($stream, self::UPLOAD_CHUNK_BYTES);

            if ($chunk === false) {
                break;
            }

            $bytes += strlen($chunk);
        }

        fclose($stream);

        return $bytes;
    }

    /** Ukuran satu potongan saat membaca payload unggah. */
    private const UPLOAD_CHUNK_BYTES = 65536;

    /**
     * Jalankan pengujian dan kembalikan hasil sebagai JSON.
     * Hasil yang berhasil disimpan ke speed_test_logs sebagai riwayat.
     */
    public function run(Request $request, SpeedTestService $service): JsonResponse
    {
        $this->authorizeSuperAdmin();

        $target = $this->resolveRequestTarget($request);

        // Transport dibangun lebih dulu supaya kesalahan konfigurasi SSH
        // (perangkat tanpa IP, CIDR tidak diizinkan) menjadi pesan yang jelas,
        // bukan kegagalan diam-diam di tengah pengukuran.
        $result = $this->runForTarget($target, $service);

        $log = $this->storeLog($request, $target, $result);

        return response()->json([
            'success' => $result['ok'],
            'status' => $result['ok'] ? 'ok' : 'gagal',
            'download_mbps' => $result['download_mbps'],
            'upload_mbps' => $result['upload_mbps'],
            'ping_ms' => $result['ping_ms'],
            'source' => $result['source'],
            'server' => $result['server'],
            'message' => $result['message'],
            'duration_ms' => $result['duration_ms'],
            'target_label' => $log->targetLabel(),
            'timestamp' => now()->format('d/m/Y H:i:s'),
            'log' => $this->logPayload($log),
        ], $result['ok'] ? 200 : 422);
    }

    /**
     * Jalankan pengujian penuh untuk satu target yang sudah tervalidasi.
     *
     * Semua kegagalan dikembalikan sebagai hasil, tidak pernah dilempar keluar:
     * riwayat harus tetap mencatat percobaan yang gagal supaya ada jejak.
     *
     * @param  array{device: ?Device, manual_host: ?string, label: string}  $target
     * @return array<string, mixed>
     */
    protected function runForTarget(array $target, SpeedTestService $service): array
    {
        try {
            return $service->measure(
                $service->transportFor($target['device'], $target['manual_host']),
            );
        } catch (\Throwable $e) {
            report($e);

            return $service->unavailable(
                'Pengujian tidak bisa dijalankan. Periksa koneksi dan konfigurasi SSH server ini.'
            );
        }
    }

    /**
     * Simpan satu baris riwayat.
     *
     * Penulisan log tidak boleh menggagalkan pengujian yang sudah selesai
     * mengukur, jadi error database dilaporkan dan diabaikan.
     *
     * @param  array{device: ?Device, manual_host: ?string, label: string}  $target
     * @param  array<string, mixed>  $result
     */
    protected function storeLog(Request $request, array $target, array $result): SpeedTestLog
    {
        $device = $target['device'];
        $isManual = $target['manual_host'] !== null;

        try {
            return SpeedTestLog::create([
                'user_id' => $request->user()->id,
                'device_id' => $device?->id,
                'device_name' => $device?->name,
                'device_ip_address' => $device?->ip_address,
                'target_type' => $isManual ? 'manual' : ($device !== null ? 'device' : 'local'),
                'target_host' => $isManual ? $target['manual_host'] : null,
                'download_mbps' => $result['download_mbps'],
                'upload_mbps' => $result['upload_mbps'],
                'ping_ms' => $result['ping_ms'],
                'source' => $result['source'],
                'server' => $result['server'],
                'ip_address' => $request->ip(),
                'duration_ms' => $result['duration_ms'],
                'status' => $result['ok'] ? 'ok' : 'gagal',
                'message' => Str::limit((string) $result['message'], 250, ''),
                'tested_at' => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);

            // Kembalikan model yang belum tersimpan supaya pemanggil tetap
            // bisa membangun respons tanpa membaca database.
            return new SpeedTestLog([
                'user_id' => $request->user()->id,
                'device_id' => $device?->id,
                'device_name' => $device?->name,
                'target_type' => $isManual ? 'manual' : ($device !== null ? 'device' : 'local'),
                'target_host' => $isManual ? $target['manual_host'] : null,
                'download_mbps' => $result['download_mbps'],
                'upload_mbps' => $result['upload_mbps'],
                'ping_ms' => $result['ping_ms'],
                'source' => $result['source'],
                'server' => $result['server'],
                'duration_ms' => $result['duration_ms'],
                'status' => $result['ok'] ? 'ok' : 'gagal',
                'message' => $result['message'],
                'tested_at' => now(),
            ]);
        }
    }

    /* ═══════════════════════════════════════════════════════════════
     *  Pengujian bertahap (polling per detik)
     *
     *  Endpoint 'run' di atas menjalankan semuanya sekaligus, jadi angkanya
     *  baru terlihat setelah puluhan detik. Endpoint di bawah memecahnya:
     *  'start' membuka satu sesi dan 'step' mengerjakan satu tahap per
     *  panggilan, sehingga frontend bisa menampilkan angka serta progres
     *  setiap detik.
     *
     *  Sesi disimpan di cache dengan kunci yang memuat id user, jadi satu
     *  akun tidak bisa membaca atau melanjutkan sesi milik akun lain.
     * ═══════════════════════════════════════════════════════════════ */

    /** Lama satu sesi bertahap hidup di cache. */
    private const SESSION_TTL_SECONDS = 600;

    /**
     * Batas pemanggilan endpoint cuplikan per satu sesi pengujian.
     *
     * Value-nya sengaja longgar: frontend meminta sekitar satu kali per
     * detik, jadi batas ini hanya menahan penyalahgunaan (mis. skrip yang
     * memanggilnya nonstop), bukan pemakaian normal. Ditegakkan lewat cache
     * dengan kunci terpisah dari sesi, supaya tidak ada dua request yang
     * saling menimpa saat menulis state pengujian.
     */
    private const PROBE_LIMIT = 20;

    private const PROBE_LIMIT_DECAY_SECONDS = 10;

    /**
     * Buka sesi pengujian: validasi target dulu, lalu kembalikan target yang
     * akan diuji. Belum ada perintah yang dijalankan di sini — supaya
     * kesalahan konfigurasi SSH (perangkat tanpa IP, CIDR tidak diizinkan)
     * muncul seketika, bukan setelah pengujian berjalan setengah jalan.
     */
    public function start(Request $request, SpeedTestService $service): JsonResponse
    {
        $this->authorizeSuperAdmin();

        // Validasi input di luar try: penolakan validasi harus tetap memakai
        // format 422 + daftar field seperti endpoint 'run', bukan diubah jadi
        // pesan umum.
        $target = $this->resolveRequestTarget($request);

        try {
            // Bangun transport sekali di awal: inilah titik paling awal
            // kegagalan yang bisa dicek sebelum pengukuran benar berjalan.
            $service->transportFor($target['device'], $target['manual_host']);
        } catch (SshUnavailableException $e) {
            return $this->targetErrorResponse($e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return $this->targetErrorResponse('Pengujian tidak bisa dimulai. Silakan coba lagi.');
        }

        $runId = (string) Str::uuid();

        $this->writeSession($request->user()->id, $runId, [
            'run_id' => $runId,
            'target' => $this->sessionTarget($target),
            'state' => $service->freshState(),
            'stage_index' => 0,
            'started_at' => microtime(true),
        ]);

        return response()->json([
            'success' => true,
            'run_id' => $runId,
            'target_label' => $target['label'],
            'stages' => $service->stagePlan(),
        ]);
    }

    /**
     * Kerjakan satu tahap pengujian dan kembalikan progres terbaru.
     *
     * Dipanggil frontend setiap detik. Panggilan terakhir sekaligus menyimpan
     * baris riwayat, sehingga satu pengujian tetap menghasilkan tepat satu
     * baris log walaupun ada puluhan request polling.
     */
    public function step(Request $request, SpeedTestService $service): JsonResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'run_id' => ['required', 'string', 'uuid'],
        ]);

        $session = $this->readSession($request->user()->id, $validated['run_id']);

        if ($session === null) {
            return response()->json([
                'success' => false,
                'status' => 'gagal',
                'message' => 'Sesi pengujian sudah berakhir atau tidak ditemukan. Silakan mulai lagi.',
            ], 422);
        }

        $target = $this->reviveTarget((array) $session['target']);

        try {
            $transport = $service->transportFor($target['device'], $target['manual_host']);
        } catch (SshUnavailableException $e) {
            // Konfigurasi berubah di tengah sesi, mis. CIDR dicabut atau
            // private key dihapus.
            $this->forgetSession($request->user()->id, $session['run_id']);

            return $this->targetErrorResponse($e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            $this->forgetSession($request->user()->id, $session['run_id']);

            return $this->targetErrorResponse('Koneksi ke target tidak tersedia. Silakan mulai ulang pengujian.');
        }

        $index = max(0, (int) ($session['stage_index'] ?? 0));
        $stages = $service::STAGES;
        $stage = $stages[$index] ?? null;

        if ($stage === null) {
            // Semua tahap sudah habis — misalnya request sebelumnya gagal
            // setelah tahap terakhir sempat dieksekusi.
            return $this->finishSession($request, $session, $target, $service);
        }

        // Pesan tahap yang sempat gagal sengaja ikut dibawa ke state, supaya
        // hasil akhirnya bisa langsung ditampilkan tanpa fetch ulang.
        $session['state'] = $service->runStage($stage, $transport, (array) $session['state']);
        $session['stage_index'] = $index + 1;

        $this->writeSession($request->user()->id, $session['run_id'], $session);

        $total = count($stages);

        // Tahap terakhir: pengujian selesai di panggilan yang sama ini, jadi
        // frontend tidak perlu satu request tambahan untuk menutupnya.
        if ($session['stage_index'] >= $total) {
            return $this->finishSession($request, $session, $target, $service);
        }

        return response()->json([
            'success' => true,
            'run_id' => $session['run_id'],
            'done' => false,
            'stage' => $stage,
            'stage_label' => $service::STAGE_LABELS[$stage],
            // Tahap yang baru selesai, bukan yang sedang berjalan.
            'completed_stage' => $stage,
            'next_stage' => $stages[$session['stage_index']] ?? null,
            'progress' => (int) round(($session['stage_index'] / $total) * 100),
            'elapsed_ms' => $this->elapsedMs($session),
            'target_label' => $target['label'],
            'metrics' => $this->metricsPayload((array) $session['state'], $service),
        ]);
    }

    /**
     * Ambil satu cuplikan angka untuk tahap yang sedang berjalan.
     *
     * Endpoint ini yang membuat angka Download/Upload/Ping bergerak SETIAP
     * DETIK, bukan hanya melompat begitu satu tahap selesai. Frontend
     * memanggilnya paralel dengan polling 'step'.
     *
     * Dua aturan yang dijaga di sini:
     *
     *  1. Endpoint ini TIDAK PERNAH menulis ke sesi pengujian. Sesi ditulis
     *     oleh 'step'; kalau probe ikut menulis, dua request yang jalan
     *     bersamaan bisa saling menimpa dan membuat satu tahap terulang
     *     atau terlewat. Ini sebabnya throttle di sini memakai kunci cache
     *     terpisah, bukan counter di dalam sesi.
     *  2. Cuplikan tidak pernah menyentuh state pengujian, jadi angka yang
     *     tersimpan di riwayat tetap hasil pengukuran resmi, bukan hasil
     *     cuplikan satu detik.
     *
     * Kalau samplenya tidak tersedia (alat ukur tidak ada, atau jaringan
     * sedang tidak terbaca), metriknya null dan angka di layar ditahan.
     * Tidak pernah diisi angka placeholder.
     */
    public function probe(Request $request, SpeedTestService $service): JsonResponse
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'run_id' => ['required', 'string', 'uuid'],
        ]);

        $userId = $request->user()->id;
        $runId = (string) $validated['run_id'];

        $session = $this->readSession($userId, $runId);

        if ($session === null) {
            // Sesi berakhir = pengujian selesai. Ini bukan error: frontend
            // hanya perlu berhenti mengirim probe.
            return response()->json([
                'success' => false,
                'live' => false,
                'done' => true,
                'message' => 'Sesi pengujian sudah berakhir atau tidak ditemukan.',
            ], 422);
        }

        // Backstop kalau ada yang memanggil endpoint ini tanpa ritme yang
        // bisa dipertanggungjawabkan. Frontend sudah membatasi diri sendiri
        // jadi sekitar 1 permintaan per detik.
        if (RateLimiter::tooManyAttempts($this->probeLimitKey($userId, $runId), self::PROBE_LIMIT)) {
            return response()->json([
                'success' => true,
                'live' => false,
                'throttled' => true,
                'stage' => $this->activeStage($session),
                // Sengaja null semua: frontend menahan angka sebelumnya.
                'metrics' => ['ping_ms' => null, 'download_mbps' => null, 'upload_mbps' => null],
            ]);
        }

        RateLimiter::hit($this->probeLimitKey($userId, $runId), self::PROBE_LIMIT_DECAY_SECONDS);

        $target = $this->reviveTarget((array) $session['target']);

        try {
            $transport = $service->transportFor($target['device'], $target['manual_host']);
        } catch (\Throwable $e) {
            // Transport tidak tersedia (konfigurasi SSH berubah, atau target
            // dihapus). Cuplikan dilewati diam-diam: tahap 'step' yang akan
            // melaporkan masalahnya, dengan pesan yang tepat.
            report($e);

            return response()->json([
                'success' => true,
                'live' => false,
                'stage' => $this->activeStage($session),
                'metrics' => ['ping_ms' => null, 'download_mbps' => null, 'upload_mbps' => null],
            ]);
        }

        $stage = $this->activeStage($session);

        if ($stage === null) {
            // Tahap habis tapi 'step' belum sempat menutup sesi. Tidak ada yang
            // boleh diukur lagi.
            return response()->json([
                'success' => true,
                'live' => false,
                'done' => true,
                'metrics' => ['ping_ms' => null, 'download_mbps' => null, 'upload_mbps' => null],
            ]);
        }

        $sample = $service->probe($stage, $transport, (array) $session['state']);

        return response()->json([
            'success' => true,
            'live' => true,
            'run_id' => $runId,
            'done' => false,
            'stage' => $stage,
            'stage_label' => SpeedTestService::STAGE_LABELS[$stage] ?? null,
            // Penanda bahwa angka ini cuplikan satu detik, bukan hasil final.
            'provisional' => true,
            'metrics' => [
                'ping_ms' => $sample['ping_ms'],
                'download_mbps' => $sample['download_mbps'],
                'upload_mbps' => $sample['upload_mbps'],
            ],
            'elapsed_ms' => $this->elapsedMs($session),
        ] + (isset($sample['message']) ? ['message' => $sample['message']] : []));
    }

    /**
     * Tahap yang sedang berjalan menurut sesi, atau null kalau sudah habis.
     *
     * Membaca stage_index tanpa mengubahnya: probe tidak boleh menggeser
     * posisi tahap, itu urusan 'step'.
     *
     * @param  array<string, mixed>  $session
     */
    protected function activeStage(array $session): ?string
    {
        $index = max(0, (int) ($session['stage_index'] ?? 0));

        return SpeedTestService::STAGES[$index] ?? null;
    }

    protected function probeLimitKey(int $userId, string $runId): string
    {
        return 'speedtest:probe:'.$userId.':'.$runId;
    }

    /**
     * Riwayat terbaru, dipakai AJAX supaya tabel bisa disegarkan tanpa
     * reload seluruh halaman.
     */
    public function history(): JsonResponse
    {
        $this->authorizeSuperAdmin();

        return response()->json([
            'success' => true,
            'logs' => $this->recentLogs()
                ->map(fn (SpeedTestLog $log) => $this->logPayload($log))
                ->all(),
        ]);
    }

    /* ═══════════════════════════════════════════════════════════════
     *  Helper untuk pengujian bertahap
     * ═══════════════════════════════════════════════════════════════ */

    /**
     * Baca dan validasi target dari request, lalu putuskan device/host yang
     * harus diuji.
     *
     * Dipisah dari run() supaya aturan validasi yang sama dipakai oleh jalur
     * sinkron dan jalur bertahap — tidak ada jalur yang lolos validasi di satu
     * jalan tapi tidak di jalur lain.
     *
     * @return array{device: ?Device, manual_host: ?string, label: string}
     *
     * @throws ValidationException
     */
    protected function resolveRequestTarget(Request $request): array
    {
        $validated = $request->validate([
            'device_id' => ['nullable', 'integer', 'min:0'],
            'target_type' => ['nullable', Rule::in(['device', 'manual', 'local'])],
            'custom_host' => [
                'nullable',
                'string',
                'max:253',
                Rule::requiredIf(fn (): bool => $request->input('target_type') === 'manual'),
            ],
        ]);

        $targetType = $validated['target_type'] ?? 'local';

        // Prioritas: manual > device_id > server lokal.
        if ($targetType === 'manual') {
            $host = trim((string) ($validated['custom_host'] ?? ''));

            return [
                'device' => null,
                'manual_host' => $host,
                'label' => $host,
            ];
        }

        $device = $this->resolveTarget($validated['device_id'] ?? null);

        if ($device === null) {
            return [
                'device' => null,
                'manual_host' => null,
                'label' => 'Server NOC',
            ];
        }

        return [
            'device' => $device,
            'manual_host' => null,
            'label' => $device->name.' ('.$device->ip_address.')',
        ];
    }

    /**
     * Sesi di cache selalu menyimpan objek Device sebagai array serializable,
     * dan di sini diubah kembali menjadi model supaya bisa dipakai transport.
     *
     * @param  array<string, mixed>  $target
     * @return array{device: ?Device, manual_host: ?string, label: string}
     */
    protected function reviveTarget(array $target): array
    {
        $device = null;

        if (! empty($target['device'])) {
            $restored = Device::query()->find((int) $target['device']['id']);

            // Perangkat yang dihapus di tengah pengujian tidak bisa dieksekusi;
            // perlakukan sebagai tidak ada supaya pesannya jelas.
            $device = $restored?->ip_address ? $restored : null;
        }

        return [
            'device' => $device,
            'manual_host' => $target['manual_host'] ?? null,
            'label' => $target['label'] ?? 'Server NOC',
        ];
    }

    /**
     * Bentuk target yang disimpan di cache. Device disimpan sebagai array
     * supaya tidak ikut ter-serialize sebagai Eloquent model.
     *
     * @param  array{device: ?Device, manual_host: ?string, label: string}  $target
     * @return array<string, mixed>
     */
    protected function sessionTarget(array $target): array
    {
        return [
            'device' => $target['device'] === null ? null : [
                'id' => $target['device']->id,
                'name' => $target['device']->name,
                'ip_address' => $target['device']->ip_address,
            ],
            'manual_host' => $target['manual_host'],
            'label' => $target['label'],
        ];
    }

    protected function sessionKey(int $userId, string $runId): string
    {
        // Id user ikut masuk ke kunci: sesi milik satu akun tidak bisa dibaca
        // atau dilanjutkan oleh akun lain, walau run_id-nya diketahui.
        return 'speedtest:run:'.$userId.':'.$runId;
    }

    /**
     * @param  array<string, mixed>  $session
     */
    protected function writeSession(int $userId, string $runId, array $session): void
    {
        cache()->put(
            $this->sessionKey($userId, $runId),
            $session,
            self::SESSION_TTL_SECONDS,
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function readSession(int $userId, string $runId): ?array
    {
        $session = cache()->get($this->sessionKey($userId, $runId));

        return is_array($session) ? $session : null;
    }

    protected function forgetSession(int $userId, string $runId): void
    {
        cache()->forget($this->sessionKey($userId, $runId));
    }

    /**
     * Bangun hasil akhir dari sesi, catat ke riwayat, lalu hapus sesi.
     *
     * Sesi dihapus setelah dipakai supaya request ganda atau reload halaman
     * tidak bisa membuat log ganda untuk pengujian yang sama.
     *
     * @param  array<string, mixed>  $session
     * @param  array{device: ?Device, manual_host: ?string, label: string}  $target
     */
    protected function finishSession(Request $request, array $session, array $target, SpeedTestService $service): JsonResponse
    {
        $userId = $request->user()->id;
        $runId = (string) $session['run_id'];

        $result = $service->finalize(
            (array) $session['state'],
            microtime(true) - (float) $session['started_at'],
        );

        $log = $this->storeLog($request, $target, $result);

        $this->forgetSession($userId, $runId);

        return response()->json([
            'success' => (bool) $result['ok'],
            'status' => $result['ok'] ? 'ok' : 'gagal',
            'done' => true,
            'run_id' => $runId,
            'progress' => 100,
            'elapsed_ms' => $this->elapsedMs($session),
            'metrics' => $this->metricsPayload((array) $session['state'], $service),
            'download_mbps' => $result['download_mbps'],
            'upload_mbps' => $result['upload_mbps'],
            'ping_ms' => $result['ping_ms'],
            'source' => $result['source'],
            'server' => $result['server'],
            'message' => $result['message'],
            'duration_ms' => $result['duration_ms'],
            'target_label' => $log->targetLabel(),
            'timestamp' => now()->format('d/m/Y H:i:s'),
            'log' => $this->logPayload($log),
        ], $result['ok'] ? 200 : 422);
    }

    /**
     * Respons gagal yang seragam untuk semua kegagalan target, supaya
     * frontend hanya perlu membaca satu bentuk pesan.
     */
    protected function targetErrorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => 'gagal',
            'source' => 'tidak-ada',
            'message' => $message,
        ], 422);
    }

    /**
     * Lama pengujian berjalan sejauh ini, untuk timer di layar.
     *
     * @param  array<string, mixed>  $session
     */
    protected function elapsedMs(array $session): int
    {
        return (int) round((microtime(true) - (float) ($session['started_at'] ?? microtime(true))) * 1000);
    }

    /**
     * Angka sementara per tahap, supaya frontend bisa mengisi kartu metrik
     * satu per satu tanpa menunggu pengujian selesai.
     *
     * 'status' wajib ikut dikirim. Tanpa itu frontend hanya melihat null untuk
     * tahap yang belum dijalankan, dan akan menyimpulkannya "Gagal terbaca" —
     * lalu menimpa cuplikan langsung yang sedang tampil dengan tanda hubung.
     *
     * @param  array<string, mixed>  $state
     * @return array<string, mixed>
     */
    protected function metricsPayload(array $state, ?SpeedTestService $service = null): array
    {
        return [
            'ping_ms' => $state['ping_ms'] ?? null,
            'download_mbps' => $state['download_mbps'] ?? null,
            'upload_mbps' => $state['upload_mbps'] ?? null,
            'status' => ($service ?? app(SpeedTestService::class))->metricStatuses($state),
        ];
    }

    /**
     * Penjaga lapis kedua di dalam controller. Middleware sudah menolak, jadi
     * ini hanya pengaman tambahan (defense in depth) bila route di Salah ubah.
     */
    protected function authorizeSuperAdmin(): void
    {
        $user = request()->user();

        abort_unless($user !== null, 403, 'Hanya Super Admin yang bisa memakai halaman ini.');
        abort_unless($user->isSuperAdmin(), 403, 'Hanya Super Admin yang bisa memakai halaman ini.');
    }

    /**
     * Device yang boleh jadi target uji: punya alamat IP yang benar-benar bisa
     * dipakai. Diurutkan dengan yang bermasalah di atas supaya mudah dicari.
     *
     * @return Collection<int, Device>
     */
    protected function targets()
    {
        return Device::query()
            ->whereNotNull('ip_address')
            ->where('ip_address', '!=', '')
            ->orderByRaw("CASE status WHEN 'down' THEN 0 WHEN 'warning' THEN 1 WHEN 'up' THEN 2 ELSE 3 END")
            ->orderBy('name')
            ->orderBy('ip_address')
            ->get(['id', 'name', 'ip_address', 'pop_name', 'status', 'avg_delay', 'missed_percentage']);
    }

    /**
     * Apakah server ini siap menjalankan pengujian via SSH.
     *
     * Kalau belum siap, halaman tetap bisa menampilkan form, tapi endpoint run
     * akan menolak pengujian jarak jauh dengan pesan yang aman.
     */
    protected function remoteTestReady(): bool
    {
        return SshConnection::isReady();
    }

    /**
     * Ubah device_id dari request menjadi objek Device.
     *
     * Nilai kosong / "0" berarti "server lokal", jadi null. device_id yang ada
     * tapi tidak lagi terdaftar di tabel device diperlakukan sebagai server
     * lokal juga — bukan error, karena tidak ada target yang bisa dieksekusi.
     */
    protected function resolveTarget(mixed $deviceId): ?Device
    {
        if (! is_numeric($deviceId) || (int) $deviceId <= 0) {
            return null;
        }

        return Device::query()->find((int) $deviceId);
    }

    /**
     * @return Collection<int, SpeedTestLog>
     */
    protected function recentLogs()
    {
        return SpeedTestLog::query()
            ->with('user:id,name')
            ->latest('tested_at')
            ->latest('id')
            ->limit(self::HISTORY_LIMIT)
            ->get();
    }

    /**
     * Terjemahan nilai internal `source` menjadi kalimat yang dipahami
     * pengguna non-teknis.
     *
     * Nilai aslinya ('cli' / 'curl') tetap tersimpan di database untuk
     * keperluan diagnosa, tapi tidak pernah ditampilkan ke layar.
     */
    protected function sourceLabel(?string $source): string
    {
        return match ($source) {
            'cli' => 'Alat ukur standar',
            'curl' => 'Alat ukur cadangan',
            'tidak-ada' => 'Alat ukur bermasalah',
            default => 'Alat ukur server',
        };
    }

    /**
     * Terjemahan status internal menjadi kata yang biasa dipakai manajemen.
     */
    protected function statusLabel(?string $status): string
    {
        return $status === 'ok' ? 'Berhasil' : 'Gagal';
    }

    /**
     * Bentuk baris riwayat siap tampil (dipakai blade dan endpoint JSON).
     *
     * @return array<string, mixed>
     */
    protected function logPayload(SpeedTestLog $log): array
    {
        return [
            'id' => $log->id,
            'tested_at' => $log->tested_at?->format('d/m/Y H:i:s'),
            'user_name' => $log->user?->name ?? '—',
            'target_label' => $log->targetLabel(),
            'download_mbps' => $log->download_mbps,
            'upload_mbps' => $log->upload_mbps,
            'ping_ms' => $log->ping_ms,
            'server' => $log->server,
            'source' => $log->source,
            'status' => $log->status,
            'source_label' => $this->sourceLabel($log->source),
            'status_label' => $this->statusLabel($log->status),
        ];
    }
}
