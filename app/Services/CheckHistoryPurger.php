<?php

namespace App\Services;

use Illuminate\Cache\DatabaseStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Pembersih TOTAL riwayat hasil pemeriksaan target.
 *
 * Tombol "Bersihkan Semua" di halaman Network Tools memanggil ini. Satu
 * tombol, satu tujuan: setelah selesai tidak boleh ada sisa hasil
 * pemeriksaan lama di mana pun — bukan hanya di tabel log.
 *
 * Yang sebenarnya menyimpan hasil pemeriksaan di aplikasi ini:
 *
 *  1. TABEL HISTORI
 *     - monitoring_logs : hasil ping per device, ditulis MonitorDevices
 *     - log_archives    : log lama yang diarsipkan ArchiveOldLogs
 *     - ping_history    : riwayat ping (tabel ini sudah tidak diisi lagi,
 *                         tapi tetap ikut dikosongkan supaya tidak ada sisa)
 *     Ketiganya di-TRUNCATE: tidak ada arsip, tidak ada soft delete, tidak
 *     ada tombol undo. Satu-satunya cara recovering-nya adalah backup.
 *
 *  2. ANGKA HASIL YANG DISIMPAN DI TABEL `device`
 *     Kolom avg_delay dan missed_percentage adalah RINGKASAN hasil
 *     pemeriksaan, dihitung MonitorDevices lalu disimpan permanen di baris
 *     device. Kalau log-nya dihapus tapi kolom ini dibiarkan, halaman
 *     Network Tools, Dashboard, dan Laporan tetap menampilkan angka
 *     latency lama yang tidak lagi punya data pendukung — itu sisa data,
 *     jadi ikut dinolkan. Kolom `status` SENGAJA tidak disentuh: itu
 *     keadaan perangkat saat ini, bukan riwayat.
 *
 *  3. CACHE HASIL PEMERIKSAAN PER TARGET
 *     SnmpInspectionService menyimpan hasil probe tiap perangkat dengan
 *     kunci "snmp:device:{id}:{hash ip}". Ini cache yang menempel pada
 *     target, jadi ikut dihapus agar pemeriksaan berikutnya benar-benar
 *     mengetuk perangkatnya secara langsung, bukan memakai hasil lama.
 *
 * Yang SENGAJA TIDAK disentuh:
 *   - device                : daftar perangkat, bukan hasil pemeriksaan
 *   - device_connections    : topologi, disusun manual di halaman Monitoring
 *   - device_notes          : catatan operator
 *   - activity_logs         : audit siapa melakukan apa
 *   - device_notifications  : riwayat kejadian status, punya halamannya sendiri
 *   - sessions / users      : bukan data pemeriksaan
 *
 * Catatan penting soal "benar-benar kosong": proses MonitorDevices berjalan
 * terjadwal dan akan menulis monitoring_logs lagi beberapa menit setelah
 * pembersihan. Itu hasil pemeriksaan BARU, bukan sisa lama. Kalau yang
 * diinginkan log tetap kosong sampai ada perintah eksplisit, hentikan
 * scheduler lebih dulu (php artisan schedule:stop / schedule pause).
 */
class CheckHistoryPurger
{
    /**
     * Tabel histori hasil pemeriksaan.
     *
     * @var array<string, string>
     */
    public const LOG_TABLES = [
        'monitoring_logs' => 'Log Monitoring',
        'log_archives' => 'Arsip Log',
        'ping_history' => 'Riwayat Ping',
    ];

    /**
     * Prefix kunci cache yang menyimpan hasil pemeriksaan per target.
     *
     * @var array<int, string>
     */
    public const CACHE_PREFIXES = ['snmp:device:'];

    /**
     * Kolom `device` yang berisi ringkasan hasil pemeriksaan.
     *
     * @var array<int, string>
     */
    public const DEVICE_METRIC_COLUMNS = ['avg_delay', 'missed_percentage'];

    public function __construct(protected TableTruncator $truncator) {}

    /**
     * Bersihkan semuanya dan kembalikan laporan per bagian.
     *
     * Setiap bagian dilaporkan terpisah, dan kegagalan satu bagian tidak
     * menghentikan bagian lain: lebih baik 3 dari 4 target benar-benar
     * bersih daripada membatalkan semuanya karena satu tabel terkunci.
     * Bagian yang gagal dikembalikan pada key 'failed' lengkap dengan
     * alasannya supaya bisa dilaporkan apa adanya ke pengguna.
     *
     * @return array{
     *     tables: array<string, int>,
     *     rows: int,
     *     device_metrics_reset: int,
     *     cache_entries_cleared: int,
     *     cache_supported: bool,
     *     failed: array<string, string>
     * }
     */
    public function purge(): array
    {
        $tables = [];
        $failed = [];

        foreach (self::LOG_TABLES as $table => $label) {
            try {
                $tables[$label] = $this->truncator->truncate($table);
            } catch (\Throwable $e) {
                $failed[$label] = $e->getMessage();
                Log::warning('Gagal mengosongkan tabel histori', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $metricsReset = $this->resetDeviceMetrics($failed);
        [$cacheCleared, $cacheSupported] = $this->clearTargetCache($failed);

        return [
            'tables' => $tables,
            'rows' => array_sum($tables),
            'device_metrics_reset' => $metricsReset,
            'cache_entries_cleared' => $cacheCleared,
            'cache_supported' => $cacheSupported,
            'failed' => $failed,
        ];
    }

    /**
     * Nolkan ringkasan hasil pemeriksaan yang tersimpan di tabel device.
     *
     * Hanya baris yang memang punya angka (bukan 0) yang disentuh, jadi
     * jumlah yang dikembalikan berarti "berapa device yang masih menyimpan
     * angka lama", bukan "berapa device ada".
     *
     * @param  array<string, string>  $failed
     */
    protected function resetDeviceMetrics(array &$failed): int
    {
        if (! Schema::hasTable('devices')) {
            return 0;
        }

        try {
            return DB::table('devices')
                ->where(function ($query): void {
                    foreach (self::DEVICE_METRIC_COLUMNS as $column) {
                        $query->orWhere($column, '!=', 0);
                    }
                })
                ->update(array_fill_keys(self::DEVICE_METRIC_COLUMNS, 0));
        } catch (\Throwable $e) {
            $failed['Ringkasan Latency Device'] = $e->getMessage();

            return 0;
        }
    }

    /**
     * Escape karakter wildcard LIKE pada satu potongan pola kunci cache.
     */
    protected function likePattern(string $value): string
    {
        return addcslashes($value, '\\%_');
    }

    /**
     * Hapus cache hasil pemeriksaan per target (kunci snmp:device:*).
     *
     * Hanya store yang isi kuncinya bisa dilacak satu per satu yang bisa
     * dibersihkan di sini: pada store database kunci disimpan sebagai baris
     * biasa, jadi bisa dihapus berdasarkan awalan kuncinya. Untuk store lain
     * (file, redis, array) hasilnya sengaja dikembalikan sebagai 0 dengan
     * cache_supported=false — lebih jujur melaporkan "tidak bisa dibersihkan
     * dari sini" daripada mengosongkan seluruh cache aplikasi dan ikut
     * menghapus hal yang tidak berhubungan dengan target.
     *
     * @param  array<string, string>  $failed
     * @return array{0: int, 1: bool}
     */
    protected function clearTargetCache(array &$failed): array
    {
        // Cache::getStore() sudah mengembalikan store asalnya (bukan
        // repository), jadi jangan dipanggil getStore() lagi.
        $store = Cache::getStore();

        if (! $store instanceof DatabaseStore) {
            return [0, false];
        }

        try {
            $storeName = (string) config('cache.default');
            $table = (string) config("cache.stores.{$storeName}.table", 'cache');
            $connection = $store->getConnection();
            $prefix = (string) $store->getPrefix();
            $cleared = 0;

            foreach (self::CACHE_PREFIXES as $cachePrefix) {
                $keys = $connection->table($table)
                    ->where('key', 'like', $this->likePattern($prefix.$cachePrefix).'%')
                    ->pluck('key')
                    // Prefix cache bisa mengandung _ atau % yang di LIKE berarti
                    // wildcard, jadi hasil LIKE tetap dicek ulang persis.
                    ->filter(fn (mixed $key): bool => str_starts_with((string) $key, $prefix.$cachePrefix))
                    ->values();

                if ($keys->isNotEmpty()) {
                    $cleared += (int) $connection->table($table)->whereIn('key', $keys)->delete();
                }
            }

            return [$cleared, true];
        } catch (\Throwable $e) {
            $failed['Cache Target'] = $e->getMessage();

            return [0, true];
        }
    }
}
