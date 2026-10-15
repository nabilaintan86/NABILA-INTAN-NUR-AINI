<?php

namespace App\Services;

use App\Models\Device;
use App\Services\SpeedTest\CommandTransport;
use App\Services\SpeedTest\LocalTransport;
use App\Services\SpeedTest\SshTransport;
use App\Services\Ssh\SshUnavailableException;
use RuntimeException;

/**
 * Menjalankan pengujian kecepatan internet, baik dari server NOC maupun dari
 * perangkat yang dipilih Super Admin.
 *
 * Urutan alat ukur (berlaku di kedua tempat):
 *   1. speedtest-cli — akurat, mengukur ke server uji terdekat.
 *   2. Cadangan — unduh/unggah lewat curl dan latensi lewat ping.
 *
 * Yang membedakan hanya tempat perintahnya dijalankan, dan itu semuanya
 * ditangani CommandTransport (Lokal atau SSH). Algoritma pengukurannya satu,
 * jadi angka dari server dan dari router selalu sebanding.
 *
 * Pengukuran dipecah menjadi TAHAP (lihat self::STAGES): ping, download,
 * upload. Pemecahan ini yang membuat halaman bisa menampilkan angka sedikit
 * demi sedikit lewat runStage(), alih-alih menunggu semuanya muncul di akhir.
 * run() tetap melewati tahap-tahap itu secara berurutan, jadi jalur sinkron dan
 * jalur bertahap tidak punya dua implementasi yang bisa berbeda hasilnya.
 *
 * Catatan keamanan: semua perintah disusun sebagai array dan dikirim lewat
 * Process::run() tanpa shell, sehingga tidak ada input pengguna yang bisa ikut
 * masuk ke command line. Tidak ada parameter "server" dari request yang
 * diteruskan ke CLI; client hanya mengirim device_id, dan device dibaca ulang
 * dari database.
 */
class SpeedTestService
{
    /**
     * Tahap pengujian, dari yang paling cepat ke paling lambat.
     *
     * Urutan ini juga urutan yang ditampilkan di layar, sehingga frontend
     * bisa membuat indikator progres tanpa daftar tahapnya sendiri.
     */
    public const STAGES = ['ping', 'download', 'upload'];

    /** Kalimat yang dilihat pengguna untuk tiap tahap. */
    public const STAGE_LABELS = [
        'ping' => 'Mengukur respons jaringan',
        'download' => 'Mengukur laju unduh',
        'upload' => 'Mengukur laju unggah',
    ];

    /**
     * Status satu metrik di respons API.
     *
     * Penting untuk dibedakan oleh frontend:
     *
     *  - 'pending'  = tahapnya belum dijalankan, jadi tidak ada yang bisa
     *                 ditampilkan. Ini BUKAN kegagalan.
     *  - 'gagal'    = tahapnya sudah dijalankan tapi angkanya tidak terbaca.
     *  - 'ok'       = angkanya tersedia.
     *
     * Kalau keduanya dicampur jadi null, kartu di layar menampilkan
     * "Gagal terbaca" untuk tahap yang belumstarted — dan tiap polling
     * menimpa cuplikan langsung dengan tanda hubung.
     */
    public const METRIC_PENDING = 'pending';

    public const METRIC_OK = 'ok';

    public const METRIC_GAGAL = 'gagal';

    /** Tahap yang sudah dieksekusi pada pengujian berjalan. */
    private const EXECUTED_KEY = 'executed_stages';

    /** Status keberadaan speedtest-cli di tempat pengujian berjalan. */
    private const CLI_UNKNOWN = 'unknown';

    private const CLI_ABSENT = 'absent';

    private const CLI_OK = 'ok';

    private const CLI_FAILED = 'failed';

    /** Batas waktu pengujian lokal. speedtest-cli sendiri butuh ~30 detik. */
    private const TIMEOUT_SECONDS = 75;

    /** Batas waktu tiap perintah saat dijalankan di perangkat via SSH. */
    private const REMOTE_TIMEOUT_SECONDS = 90;

    /** Ukuran payload uji (byte) untuk pengukuran cadangan. */
    private const DOWNLOAD_BYTES = 25_000_000;

    private const UPLOAD_BYTES = 8_000_000;

    /* ══ Cuplikan langsung (probe) ══════════════════════════
     *
     * Nilai-nilai ini hanya dipakai probe() untuk mengambil satu sampel nyata
     * per detik supaya angka di layar bergerak. Nilainya SENGAJA jauh lebih
     * kecil dari payload di atas: cuplikan dibatasi satu detik, jadi payload
     * besar hanya jadi sia-sia tanpa pernah terkirim.
     */
    private const PROBE_SECONDS = 1;

    private const PROBE_UPLOAD_BYTES = 4_000_000;

    /** Endpoint uji cadangan milik Cloudflare — publik dan tanpa API key. */
    private const DOWNLOAD_URL = 'https://speed.cloudflare.com/__down?bytes='.self::DOWNLOAD_BYTES;

    private const UPLOAD_URL = 'https://speed.cloudflare.com/__up';

    /** Nama yang ditampilkan untuk server uji cadangan. */
    private const FALLBACK_SERVER = 'Cloudflare';

    /** DNS publik untuk mengukur latensi. */
    private const PING_TARGET = '1.1.1.1';

    /**
     * Tahap yang dibaca frontend untuk menggambar progres.
     *
     * @return list<array{key: string, label: string, position: int}>
     */
    public function stagePlan(): array
    {
        $plan = [];

        foreach (array_values(self::STAGES) as $position => $stage) {
            $plan[] = [
                'key' => $stage,
                'label' => self::STAGE_LABELS[$stage],
                'position' => $position + 1,
            ];
        }

        return $plan;
    }

    /**
     * Jalankan pengujian dari server NOC.
     *
     * @return array<string, mixed>
     */
    public function run(): array
    {
        return $this->measure(new LocalTransport);
    }

    /**
     * Jalankan pengujian dari perangkat yang dipilih Super Admin.
     *
     * Perintahnya sama seperti di server, hanya dijalankan lewat ssh(1) di
     * perangkat tujuan sehingga angka yang keluar benar-benar milik uplink
     * perangkat itu.
     *
     * @return array<string, mixed>
     */
    public function runOnDevice(Device $device): array
    {
        try {
            $transport = SshTransport::forDevice($device);
        } catch (SshUnavailableException $e) {
            return $this->failure($e->getMessage());
        }

        return $this->measure($transport);
    }

    /**
     * Jalankan pengujian ke alamat yang diketik Super Admin sendiri, untuk
     * perangkat yang belum terdaftar di tabel `device`.
     *
     * @return array<string, mixed>
     */
    public function runOnManualHost(string $host): array
    {
        try {
            $transport = SshTransport::forManualHost($host);
        } catch (SshUnavailableException $e) {
            return $this->failure($e->getMessage());
        }

        return $this->measure($transport);
    }

    /**
     * Transport untuk satu target, dipakai jalur pengujian bertahap.
     *
     * Device selalu menang atas manual host, dan kosong berarti server NOC.
     * Jalur ini sama dengan run()/runOnDevice()/runOnManualHost(), jadi aturan
     * "target mana yang boleh dieksekusi" hanya ada di sini.
     *
     * @throws SshUnavailableException
     */
    public function transportFor(?Device $device = null, ?string $manualHost = null): CommandTransport
    {
        if ($device !== null) {
            return SshTransport::forDevice($device);
        }

        if ($manualHost !== null && trim($manualHost) !== '') {
            return SshTransport::forManualHost($manualHost);
        }

        return new LocalTransport;
    }

    /**
     * Jalankan pengujian penuh dan kembalikan hasil ternormalisasi.
     *
     * Bentuk kembalian:
     *   ok            bool
     *   download_mbps float|null   upload_mbps float|null   ping_ms float|null
     *   source        'cli'|'curl'|'tidak-ada'
     *   server        string|null   nama server uji
     *   message       string        ringkasan status dalam bahasa manusia
     *   duration_ms   int
     *
     * @return array<string, mixed>
     */
    public function measure(CommandTransport $transport): array
    {
        $startedAt = microtime(true);
        $state = $this->freshState();

        foreach (self::STAGES as $stage) {
            $state = $this->runStage($stage, $transport, $state);
        }

        return $this->composeResult(
            $state,
            $transport->source(),
            $this->elapsedMs($startedAt),
        );
    }

    /**
     * Ambil SATU sampel nyata untuk satu tahap yang sedang berjalan.
     *
     * Ini yang membuat angka di layar bergerak setiap detik, bukan melompat
     * begitu satu tahap selesai. Bedanya dengan runStage() penting dan
     * disengaja:
     *
     *   - runStage() adalah pengukuran OTORITATIF. Nilainya yang disimpan ke
     *     riwayat dan yang ditampilkan sebagai hasil akhir.
     *   - probe() adalah cuplikan SEKUNDER, dibatasi satu detik, dan TIDAK
     *     PERNAH ditulis ke state pengujian. Kalau sampai ikut tersimpan,
     *     angka cuplikan bisa menimpa hasil pengukuran yang benar.
     *
     * Karena probe() memakai bandwidth yang sama dengan tahap yang sedang
     * berjalan, dampaknya ke hasil akhir sengaja dibuat sekecil mungkin:
     * hanya satu detik dari pengukuran yang berdurasi puluhan detik, jadi
     * pengaruhnya di bawah pembulatan angka yang ditampilkan.
     *
     * Tidak ada angka yang dibuat-buat di sini. Kalau tahapnya tidak bisa
     * diukur (alat ukur tidak ada, atau jaringan sedang tidak bisa dibaca),
     * metriknya null dan frontend menahan angka sebelumnya — bukan
     * menampilkan nol atau angka placeholder.
     *
     * @param  array<string, mixed>  $state  state pengujian yang sedang berjalan
     * @return array<string, mixed>
     */
    public function probe(string $stage, CommandTransport $transport, array $state = []): array
    {
        $startedAt = microtime(true);
        $sample = [
            'stage' => $stage,
            'ping_ms' => null,
            'download_mbps' => null,
            'upload_mbps' => null,
            'source' => 'live',
        ];

        if (! in_array($stage, self::STAGES, true)) {
            return $sample;
        }

        // Dua kemungkinan tahap 'ping' yang belum bisa dipastikan: yang
        // baru saja selesai, atau yang justru sedang berjalan. Selama
        // cli_state masih 'unknown', tahap berikutnya bisa jadi eksekusi
        // speedtest-cli itu sendiri, dan mengukur unduh/unggah saat itu akan
        // bersaing langsung dengan pengukuran resmi sehingga keduanya tidak
        // berarti. Jadi tahap ini hanya boleh menampilkan latensi murah.
        $cliPending = $this->cliPending($state);

        try {
            $value = match (true) {
                $stage === 'ping' => $this->probePing($transport),
                $stage === 'download' && ! $cliPending => $this->probeThroughput($transport, 'download'),
                $stage === 'upload' && ! $cliPending => $this->probeThroughput($transport, 'upload'),
                default => null,
            };
        } catch (RuntimeException $e) {
            // Detail exception bisa memuat nama perintah atau path server, jadi
            // dicatat di log saja dan tidak pernah sampai ke layar.
            report($e);

            $sample['message'] = 'Alat ukur sedang bermasalah. Silakan hubungi administrator jaringan.';

            return $sample + ['duration_ms' => $this->elapsedMs($startedAt)];
        }

        if ($value !== null) {
            $sample[$this->metricKey($stage)] = $value;
        }

        return $sample + ['duration_ms' => $this->elapsedMs($startedAt)];
    }

    /**
     * Apakah speedtest-cli masih belum pernah dicoba pada pengujian ini.
     *
     * Selama jawabannya masih "ya", tahap yang sedang berjalan bisa jadi
     * eksekusi CLI, jadi cuplikan berat tidak boleh dijalankan bersamaan.
     *
     * @param  array<string, mixed>  $state
     */
    public function cliPending(array $state): bool
    {
        return ($state['cli_state'] ?? self::CLI_UNKNOWN) === self::CLI_UNKNOWN;
    }

    /**
     * Sampel latensi satu detik: satu balasan ping, bukan rata-rata empat.
     *
     * @return float|null milidetik
     */
    protected function probePing(CommandTransport $transport): ?float
    {
        if (! $transport->isAvailable('ping')) {
            return null;
        }

        $result = $transport->run(
            ['ping', '-c', '1', '-W', '2', self::PING_TARGET],
            null,
            min(15, $this->timeoutFor($transport)),
        );

        return $this->parsePingLatency($result->output);
    }

    /**
     * Sampel laju unduh/unggah lasting satu detik.
     *
     * curl yang dihentikan oleh --max-time keluar dengan kode 28, dan itu
     * HAL YANG NORMAL untuk cuplikan ini — bukan kegagalan. Karena itu
     * keberhasilannya dibaca dari angka yang dicetak curl, bukan dari exit
     * code-nya; kalau tidak, tidak akan pernah ada sampel yang masuk.
     *
     * @return float|null Mbps
     */
    protected function probeThroughput(CommandTransport $transport, string $direction): ?float
    {
        // Batas "--max-time" harus dilebihkan sedikit: itu yang dipatokan
        // curl, sedangkan timeout di atas untuk transport adalah batas keras
        // tempat proses dibunuh. Kalau keduanya sama, proses bisa dibunuh
        // sebelum sempat mencetak angkanya.
        $timeouts = [
            '--max-time', (string) self::PROBE_SECONDS,
            '--connect-timeout', (string) self::PROBE_SECONDS,
        ];

        if ($direction === 'download') {
            $result = $transport->run([
                'curl', '--silent', '--location',
                ...$timeouts,
                '--output', '/dev/null',
                // speed_download = size_download / time_total, byte/detik.
                '--write-out', '%{speed_download}',
                '--range', '0-'.self::DOWNLOAD_BYTES,
                self::DOWNLOAD_URL,
            ], null, $this->probeTimeoutFor($transport));
        } else {
            $result = $transport->run([
                'curl', '--silent', '--location',
                ...$timeouts,
                '--output', '/dev/null',
                '--write-out', '%{speed_upload}',
                '--request', 'POST',
                '--header', 'Content-Type: application/octet-stream',
                '--data-binary', '@-',
                self::UPLOAD_URL,
            ], $this->probeUploadPayload(), $this->probeTimeoutFor($transport));
        }

        return $this->toMbps(trim($result->output));
    }

    /**
     * Body acak untuk cuplikan unggah.
     *
     * Lebih kecil daripada payload pengujian: cuplikan dibatasi satu detik,
     * jadi 8 MB hanya jadi sia-sia tanpa pernah terkirim. Empat MB sudah
     * cukup mengisi sampai sekitar 40 Mbps; di atas itu angka lajunya sudah
     * benar tanpa perlu mengirim payload yang lebih besar.
     */
    protected function probeUploadPayload(): string
    {
        return random_bytes(self::PROBE_UPLOAD_BYTES);
    }

    /**
     * Batas waktu perintah cuplikan: sedikit di atas batas curl sendiri,
     * ditambah kelonggaran untuk biaya sambungan SSH.
     */
    protected function probeTimeoutFor(CommandTransport $transport): int
    {
        $base = self::PROBE_SECONDS + 10;

        return $transport->source() === 'perangkat' ? $base + 10 : $base;
    }

    /**
     * Jalankan SATU tahap pengukuran.
     *
     * Dipakai halaman Speed Test supaya angka muncul satu per satu: setiap
     * panggilan menambah satu angka ke hasil sementara dan frontend
     * menampilkannya begitu ada. Tidak ada proses yang diulang — tahap yang
     * angkanya sudah lengkap (mis. speedtest-cli yang mengukur ketiganya
     * sekaligus) langsung dilewati.
     *
     * @param  array<string, mixed>  $state  hasil tahap-tahap sebelumnya
     * @return array<string, mixed> state yang sudah diperbarui
     */
    public function runStage(string $stage, CommandTransport $transport, array $state): array
    {
        $state = $this->normalizeState($state);

        // Asal tempat pengukuran disimpan di state supaya pesan akhirnya tetap
        // tepat walau frontend yang menyusunnya (jalur bertahap).
        $state['transport_source'] = $transport->source();

        // Tahap di luar daftar berarti permintaan rusak, jangan dieksekusi.
        // CLI yang sudah sukses juga berarti semua angka sudah ada.
        if (! in_array($stage, self::STAGES, true) || $state['cli_state'] === self::CLI_OK) {
            return $state;
        }

        // Dicatat SEBELUM pengukuran: kalau prosesnya nanti meledak atau
        // angkanya tidak terbaca, tahap ini tetap harus dihitung sebagai
        // "sudah dicoba" — bukan "belum jalan". Tanpa ini frontend tidak
        // bisa membedakan gagal baca dari tahap yang masih/ant belum diukur.
        $state[self::EXECUTED_KEY][$stage] = true;

        // speedtest-cli cukup dipanggil sekali per pengujian: lewat SSH itu
        // satu perintah mahal yang tidak perlu diulang di tiap tahap.
        if ($state['cli_state'] === self::CLI_UNKNOWN) {
            $binary = $this->detectCliBinary($transport);

            if ($binary === null) {
                $state['cli_state'] = self::CLI_ABSENT;
            } else {
                $metrics = $this->runCli($transport, $binary);

                if ($metrics !== null) {
                    return array_merge($state, $metrics, [
                        'cli_state' => self::CLI_OK,
                        'cli_binary' => $binary,
                    ]);
                }

                // CLI ada tapi gagal: tandai supaya tidak dicoba lagi, lalu
                // turun ke pengukuran cadangan pada tahap ini juga.
                $state['cli_state'] = self::CLI_FAILED;
                $state['cli_binary'] = $binary;
            }
        }

        try {
            $value = match ($stage) {
                'ping' => $this->measurePing($transport),
                'download' => $this->measureCurl($transport, 'download'),
                'upload' => $this->measureCurl($transport, 'upload'),
                default => null,
            };
        } catch (RuntimeException $e) {
            // Detail exception bisa memuat nama perintah atau path server, jadi
            // tidak pernah dikirim ke layar; dicatat di log saja.
            report($e);

            $state['message'] = 'Alat ukur sedang bermasalah. Silakan hubungi administrator jaringan.';

            return $state;
        }

        if ($value !== null) {
            $state[$this->metricKey($stage)] = $value;
            $state['source'] = 'curl';
            $state['server'] = self::FALLBACK_SERVER;
        }

        return $state;
    }

    /**
     * Keadaan awal pengujian bertahap.
     *
     * @return array<string, mixed>
     */
    public function freshState(): array
    {
        return [
            'ping_ms' => null,
            'download_mbps' => null,
            'upload_mbps' => null,
            'source' => null,
            'server' => null,
            'message' => null,
            'cli_state' => self::CLI_UNKNOWN,
            'cli_binary' => null,
            // Tahap yang sudah dieksekusi, dipakai untuk membedakan metrik
            // yang "gagal terbaca" dari metrik yang belum diukur sama sekali.
            self::EXECUTED_KEY => [],
            // 'server' atau 'perangkat', dipakai untuk menyusun kalimat status.
            'transport_source' => 'server',
        ];
    }

    /**
     * Status tiap metrik: sudah ada angkanya, sudah dicoba tapi gagal, atau
     * belum dijalankan.
     *
     * Frontend memakai ini untuk tidak salah menulis "Gagal terbaca" pada
     * tahap yang belum mulai, dan tidak menimpa cuplikan langsung dengan
     * tanda hubung.
     *
     * @param  array<string, mixed>  $state
     * @return array<string, string> kunci metrik → status
     */
    public function metricStatuses(array $state): array
    {
        $executed = (array) ($state[self::EXECUTED_KEY] ?? []);
        $statuses = [];

        foreach (self::STAGES as $stage) {
            $key = $this->metricKey($stage);

            if (($state[$key] ?? null) !== null) {
                $statuses[$key] = self::METRIC_OK;
            } elseif (! empty($executed[$stage])) {
                $statuses[$key] = self::METRIC_GAGAL;
            } else {
                $statuses[$key] = self::METRIC_PENDING;
            }
        }

        return $statuses;
    }

    /**
     * Apakah metrik ini sudah selesai dicoba, sehingga angkanya won't berubah
     * lagi: punya angka, atau sudah dipastikan gagal.
     *
     * @param  array<string, mixed>  $state
     */
    public function metricSettled(array $state, string $metricKey): bool
    {
        $status = $this->metricStatuses($state)[$metricKey] ?? self::METRIC_PENDING;

        return $status !== self::METRIC_PENDING;
    }

    /**
     * @param  array<string, mixed>  $state
     * @return array<string, mixed>
     */
    public function normalizeState(array $state): array
    {
        return array_merge($this->freshState(), $state);
    }

    /**
     * Nama biner speedtest-cli di tempat pengujian, atau null kalau tidak ada.
     */
    protected function detectCliBinary(CommandTransport $transport): ?string
    {
        foreach (['speedtest', 'speedtest-cli'] as $candidate) {
            if ($transport->isAvailable($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * speedtest-cli dengan keluaran JSON.
     *
     * Balikin null kalau CLI gagal, supaya caller turun ke pengukuran cadangan.
     *
     * @return array<string, mixed>|null
     */
    protected function runCli(CommandTransport $transport, string $binary): ?array
    {
        $result = $transport->run([
            $binary,
            '--accept-license',
            '--accept-gdpr',
            '--format=json',
        ], null, $this->timeoutFor($transport));

        if (! $result->ok) {
            return null;
        }

        $data = json_decode(trim($result->output), true);

        if (! is_array($data)) {
            return null;
        }

        // "bandwidth" di JSON Ookla adalah byte/detik, jadi dikonversi ke Mbps.
        $download = $this->toMbps($data['download']['bandwidth'] ?? null);
        $upload = $this->toMbps($data['upload']['bandwidth'] ?? null);
        $ping = $this->round($data['ping']['latency'] ?? null, 2);

        if ($download === null && $upload === null && $ping === null) {
            return null;
        }

        return [
            'ping_ms' => $ping,
            'download_mbps' => $download,
            'upload_mbps' => $upload,
            'source' => 'cli',
            'server' => $this->cliServerName($data),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function cliServerName(array $data): ?string
    {
        $name = $data['server']['name'] ?? null;

        if (! is_string($name) || trim($name) === '') {
            return null;
        }

        $sponsor = $data['server']['sponsor'] ?? null;

        return trim($name.(is_string($sponsor) && trim($sponsor) !== '' ? ' '.$sponsor : ''));
    }

    /**
     * @param  array<string, mixed>  $state
     * @return array<string, mixed>
     */
    protected function composeResult(array $state, string $transportSource, int $durationMs): array
    {
        $download = $state['download_mbps'];
        $upload = $state['upload_mbps'];
        $source = $state['source'] ?? 'tidak-ada';

        $ok = $source === 'cli' || $download !== null || $upload !== null;

        return [
            'ok' => $ok,
            'download_mbps' => $download,
            'upload_mbps' => $upload,
            'ping_ms' => $state['ping_ms'],
            'source' => $source,
            'server' => $state['server'],
            // Catatan dari tahap yang gagal lebih berguna daripada ringkasan
            // umum di bawah, jadi dipakai kalau ada.
            'message' => $state['message'] ?? $this->summary($ok, $source, $transportSource),
            'duration_ms' => $durationMs,
        ];
    }

    /**
     * Kalimat hasil dalam bahasa manusia, mengikuti asal pengukurannya.
     */
    protected function summary(bool $ok, string $source, string $transportSource): string
    {
        $remote = $transportSource === 'perangkat';

        if ($source === 'cli') {
            return $remote
                ? 'Pengujian selesai memakai alat ukur standar di perangkat tujuan.'
                : 'Pengujian selesai memakai alat ukur standar server.';
        }

        if ($ok) {
            return $remote
                ? 'Pengujian selesai memakai alat ukur cadangan di perangkat tujuan.'
                : 'Pengujian selesai memakai alat ukur cadangan server.';
        }

        return $remote
            ? 'Perangkat tujuan tidak merespons pengukuran. Periksa koneksi dan hak akses SSH perangkat.'
            : 'Gagal mengukur kecepatan internet. Periksa koneksi internet server.';
    }

    /**
     * Kecepatan lewat unduh/unggah file uji dari Cloudflare.
     *
     * @return float|null Mbps
     */
    protected function measureCurl(CommandTransport $transport, string $direction): ?float
    {
        $common = [
            '--silent',
            '--show-error',
            '--location',
            // Batas waktu supaya satu klik tidak memakai bandwidth tanpa kendali.
            '--max-time', '45',
            '--connect-timeout', '10',
        ];

        if ($direction === 'download') {
            $result = $transport->run([
                'curl', ...$common,
                '--output', '/dev/null',
                // speed_download = byte/detik.
                '--write-out', '%{speed_download}',
                '--range', '0-'.self::DOWNLOAD_BYTES,
                self::DOWNLOAD_URL,
            ], null, $this->timeoutFor($transport));
        } else {
            // Body unggah dikirim lewat stdin (@-) supaya payload besar tidak
            // jadi argumen command line, dan supaya tidak ikut di-escape ulang
            // ketika perintahnya diteruskan ke shell perangkat.
            $result = $transport->run([
                'curl', ...$common,
                '--output', '/dev/null',
                '--write-out', '%{speed_upload}',
                '--request', 'POST',
                '--header', 'Content-Type: application/octet-stream',
                '--data-binary', '@-',
                self::UPLOAD_URL,
            ], $this->uploadPayload(), $this->timeoutFor($transport));
        }

        if (! $result->ok) {
            return null;
        }

        // Output curl = byte/detik, sama seperti "bandwidth" Ookla.
        return $this->toMbps(trim($result->output));
    }

    /**
     * Body acak untuk uji unggah.
     *
     * random_bytes() jauh lebih cepat daripada Str::random() pada ukuran ini:
     * yang terakhir memecah string per karakter, jadi 8 MB akan memakan waktu
     * belasan detik.
     */
    protected function uploadPayload(): string
    {
        return random_bytes(self::UPLOAD_BYTES);
    }

    /**
     * Latensi ke DNS publik, diambil dari rata-rata balasan rtt ping.
     *
     * @return float|null milidetik
     */
    protected function measurePing(CommandTransport $transport): ?float
    {
        if (! $transport->isAvailable('ping')) {
            return null;
        }

        $result = $transport->run(
            ['ping', '-c', '4', '-W', '2', self::PING_TARGET],
            null,
            min(30, $this->timeoutFor($transport)),
        );

        return $this->parsePingLatency($result->output);
    }

    /**
     * Ambil latensi rata-rata dari keluaran ping.
     *
     * Dipakai oleh pengujian resmi (rata-rata dari empat balasan) dan oleh
     * cuplikan langsung (satu balasan) sekaligus, supaya keduanya membaca
     * format yang sama.
     *
     * @return float|null milidetik
     */
    protected function parsePingLatency(string $output): ?float
    {
        // "rtt min/avg/max/mdev = 12.1/15.4/18.0/2.1 ms"
        if (preg_match('#rtt\s+min/avg/max\S*\s*=\s*[\d.]+/([\d.]+)/#i', $output, $m)) {
            return $this->round($m[1], 2);
        }

        return null;
    }

    /**
     * Kolom hasil yang diisi sebuah tahap.
     */
    protected function metricKey(string $stage): string
    {
        return $stage === 'ping' ? 'ping_ms' : $stage.'_mbps';
    }

    /**
     * Batas waktu per perintah, dibedakan untuk lokal dan perangkat via SSH.
     */
    protected function timeoutFor(CommandTransport $transport): int
    {
        return $transport->source() === 'perangkat'
            ? self::REMOTE_TIMEOUT_SECONDS
            : self::TIMEOUT_SECONDS;
    }

    protected function elapsedMs(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }

    /**
     * Rampungkan hasil pengujian bertahap menjadi bentuk akhir yang sama
     * dengan hasil measure(), memakai durasi kumulatif dari frontend.
     *
     * @param  array<string, mixed>  $state
     * @return array<string, mixed>
     */
    public function finalize(array $state, float $elapsedSeconds): array
    {
        $state = $this->normalizeState($state);

        return $this->composeResult(
            $state,
            (string) ($state['transport_source'] ?? 'server'),
            $this->elapsedMs($elapsedSeconds),
        );
    }

    /**
     * Hasil gagal yang pesan errornya sudah aman untuk ditampilkan.
     *
     * Dipakai controller saat membangun transport gagal, sehingga jalur
     * bertahap dan jalur sinkron memberi pesan yang sama persis.
     *
     * @return array<string, mixed>
     */
    public function unavailable(string $message): array
    {
        return $this->failure($message);
    }

    /**
     * Hasil gagal tanpa satu pun angka terukur.
     *
     * Tidak ada target di sini: penulisan log tetap diurus controller yang
     * sudah tahu target mana yang diminta.
     *
     * @return array<string, mixed>
     */
    protected function failure(string $message): array
    {
        return [
            'ok' => false,
            'download_mbps' => null,
            'upload_mbps' => null,
            'ping_ms' => null,
            'source' => 'tidak-ada',
            'server' => null,
            'message' => $message,
            // Tidak ada perintah yang sempat jalan, jadi durasinya nol.
            'duration_ms' => 0,
        ];
    }

    /**
     * Byte/detik -> Mbps (1 Mbps = 1.000.000 byte/detik).
     */
    protected function toMbps(mixed $bytesPerSecond): ?float
    {
        $value = is_numeric($bytesPerSecond) ? (float) $bytesPerSecond : null;

        if ($value === null || $value <= 0) {
            return null;
        }

        return $this->round($value * 8 / 1_000_000, 2);
    }

    protected function round(mixed $value, int $precision): ?float
    {
        if (! is_numeric($value)) {
            return null;
        }

        return round((float) $value, $precision);
    }
}
