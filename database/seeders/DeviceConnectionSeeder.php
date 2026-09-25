<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceConnection;
use Illuminate\Database\Seeder;

class DeviceConnectionSeeder extends Seeder
{
    /**
     * Logical reference -> canonical database device.
     *
     * Setiap ref menunjuk ke record device ACTUAL yang sudah ada di database.
     * Tidak membuat device baru sama sekali.
     *
     * 'name'  = nama perangkat pada map (untuk validasi nama).
     * 'ip'    = IP address record canonical di database.
     * 'pop'   = (opsional) preferensi pop_name bila nama ambigu.
     */
    protected array $refs = [
        // ---- CORE ----
        'Intiland Core'       => ['name' => 'Intiland 1', 'ip' => '202.154.4.4'],
        'Border'              => ['name' => 'Border', 'ip' => '202.154.4.3'],
        'Hotel Core'          => ['name' => 'Core', 'ip' => '192.168.36.2'],

        // ---- Plaza BRI ----
        'TPS Plaza BRI'       => ['name' => 'TPS', 'ip' => '202.154.4.42'],
        'TPS 1'               => ['name' => 'TPS 1', 'ip' => '192.168.90.13'],

        // ---- Bumi Mandiri ----
        'Buchi'               => ['name' => 'Buchi', 'ip' => '10.100.0.0'],
        'Sw Mandiri'          => ['name' => 'Sw Mandiri', 'ip' => '192.168.35.5'],
        'YKK'                 => ['name' => 'YKK', 'ip' => '203.153.114.121'],
        'FPG'                 => ['name' => 'FPG', 'ip' => '202.146.232.246'],

        // ---- Intiland ----
        'H Kampi'             => ['name' => 'H Kampi', 'ip' => '192.168.87.3'],
        'DINKES'              => ['name' => 'DINKES', 'ip' => '202.154.4.250'],
        'DP3AK'               => ['name' => 'DP3AK', 'ip' => '202.154.4.210'],
        'PJB'                 => ['name' => 'PJB', 'ip' => '202.154.4.134'],
        'SMKN-1'              => ['name' => 'SMKN-1', 'ip' => '202.154.4.241'],
        'POP SMK WP'          => ['name' => 'POP SMK WP', 'ip' => '202.154.4.30'],
        'SMK WP'              => ['name' => 'SMK WP', 'ip' => '202.154.63.202'],
        'BTN Pemuda'          => ['name' => 'BTN Pemuda', 'ip' => '192.168.205.2'],
        'BTN Kenongo'         => ['name' => 'BTN Kenongo', 'ip' => '192.168.206.2'],
        'SBY-JKT'             => ['name' => 'SBY-JKT', 'ip' => '202.154.0.29'],
        'IIX-JI'              => ['name' => 'IIX-JI', 'ip' => '218.100.32.1'],
        // PANI pada map -> satu-satunya kandidat di DB adalah PANDI (202.154.59.126)
        'PANDI (PANI)'        => ['name' => 'PANDI', 'ip' => '202.154.59.126'],
        'TPS Intiland'        => ['name' => 'TPS', 'ip' => '202.154.4.90'],
        // Kominfo yang mengarah ke INTILAND = 202.154.4.226 (sebelumnya salah
        // disambungkan ke SMK Rajasa). Bukan KOMINFO 202.154.4.94 (Sham FM).
        'Kominfo'             => ['name' => 'Kominfo', 'ip' => '202.154.4.226'],
        'DPRD'                => ['name' => 'DPRD', 'ip' => '202.154.62.58'],
        // SM-FM actual 202.154.4.222 (bukan dummy 202.154.4.60). Di map mengarah ke Intiland.
        'SM-FM (Intiland)'    => ['name' => 'SM-FM', 'ip' => '202.154.4.222'],

        // ---- POP Yapalis ----
        'POP Mjkt (POP Mkt)'  => ['name' => 'POP Mjkt', 'ip' => '192.168.93.195'],
        'To SMK Nasional'     => ['name' => 'To SMK Nasional', 'ip' => '192.168.93.194'],
        // "SMK Nasional" pada koreksi = perangkat "To SMK Nasional" (192.168.93.194).
        'SMK Nasional'        => ['name' => 'To SMK Nasional', 'ip' => '192.168.93.194'],
        // "POP Krian" actual = 202.154.56.126 (bukan dummy 202.154.4.70).
        'POP Krian'           => ['name' => 'Pop Krian', 'ip' => '202.154.56.126'],

        // ---- POP SMK2 ----
        'SMKN2'               => ['name' => 'SMKN2', 'ip' => '202.154.4.83'],
        'to SMKN8'            => ['name' => 'to SMKN8', 'ip' => '192.168.94.3'],

        // ---- POP Vuri ----
        'RMIX Core'           => ['name' => 'RMIX Core', 'ip' => '192.168.103.2'],
        'RMIX hAP'            => ['name' => 'RMIX hAP', 'ip' => '192.168.80.12'],
        'To Furi'             => ['name' => 'To Furi', 'ip' => '192.168.80.11'],
        'AP Furi'             => ['name' => 'AP Furi', 'ip' => '192.168.80.10'],

        // ---- POP SMKN 1 ----
        'Disnaker'            => ['name' => 'Disnaker', 'ip' => '202.154.4.238'],
        '93.60'               => ['name' => '93.60', 'ip' => '192.168.93.60'],
        '93.59'               => ['name' => '93.59', 'ip' => '192.168.93.59'],
        // Device di IP 202.154.4.50 dgn nama "POP YPM" adalah perangkat "4.50" pada map
        '4.50'                => ['name' => 'POP YPM', 'ip' => '202.154.4.50'],

        // ---- POP SMK8 ----
        'to SMK2'             => ['name' => 'to SMK2', 'ip' => '192.168.94.2'],
        'R.SMK8'              => ['name' => 'R.SMK8', 'ip' => '202.154.4.82'],
        'AP SMK8'             => ['name' => 'AP SMK8', 'ip' => '192.168.94.67'],

        // ---- POP Rajasa ----
        'SMK Rajasa'          => ['name' => 'SMK Rajasa', 'ip' => '202.154.4.42'],
        'to SMK8'             => ['name' => 'to SMK8', 'ip' => '192.168.94.66'],
        'Dispenduk Siola'     => ['name' => 'Dispenduk Siola', 'ip' => '202.154.4.246'],
        'RMIX-BU'             => ['name' => 'RMIX-BU', 'ip' => '192.168.94.12'],
        'EPON-BU'             => ['name' => 'EPON-BU', 'ip' => '192.168.94.18'],

        // ---- POP Sham FM ----
        'To-DISNAKER'         => ['name' => 'To-DISNAKER', 'ip' => '192.168.93.59'],
        'PB-DISNAKER'         => ['name' => 'PB-DISNAKER', 'ip' => '192.168.93.60'],
        'RMIX-DISNAKER'       => ['name' => 'RMIX-DISNAKER', 'ip' => '192.168.93.61'],
        'AP Disnaker'         => ['name' => 'AP Disnaker', 'ip' => '10.93.61.2'],
        // Hub POP Sham FM yang TERVERIFIKASI = "Router" (202.154.4.34).
        // Bukan SM-FM (202.154.4.222); SM-FM bukan hub pada daftar terverifikasi.
        'Router (Sham FM)'    => ['name' => 'Router', 'ip' => '202.154.4.34'],
        'to YPM'              => ['name' => 'to YPM', 'ip' => '192.168.93.10'],
        'To BU'               => ['name' => 'To BU', 'ip' => '192.168.93.34'],
        'To SHAMFM'           => ['name' => 'To SHAMFM', 'ip' => '192.168.93.35'],
        'PDI'                 => ['name' => 'PDI', 'ip' => '192.168.93.19'],
        'PDI-Router'          => ['name' => 'PDI-Router', 'ip' => '202.154.4.94'],
        'To PDI'              => ['name' => 'To PDI', 'ip' => '192.168.93.18'],
        'Kost Jojoran'        => ['name' => 'Kost Jojoran', 'ip' => '127.0.0.1'],

        // ---- POP SMK-YPM ----
        '93.11'               => ['name' => '93.11', 'ip' => '192.168.93.11'],
        'POP YPM'             => ['name' => 'POP YPM', 'ip' => '202.154.4.38'],
        'To Yapalis'          => ['name' => 'To Yapalis', 'ip' => '192.168.92.26'],
        '92.10'               => ['name' => '92.10', 'ip' => '192.168.92.10'],
        'ToKarantina'         => ['name' => 'ToKarantina', 'ip' => '192.168.92.34'],
        'To Yakult'           => ['name' => 'To Yakult', 'ip' => '192.168.80.19'],
        'AP Yakult'           => ['name' => 'AP Yakult', 'ip' => '192.168.80.20'],
        '92.39'               => ['name' => '92.39', 'ip' => '192.168.92.39'],
        '92.37'               => ['name' => '92.37', 'ip' => '192.168.92.37'],
        '92.44'               => ['name' => '92.44', 'ip' => '192.168.92.44'],
        'BTN Winyung'         => ['name' => 'BTN Wiyung', 'ip' => '202.154.56.122'],
        'Kedaung'             => ['name' => 'Kedaung', 'ip' => '192.168.92.40'],
        '56.78'               => ['name' => '56.78', 'ip' => '202.154.56.78'],

        // ---- Hotel Bumi ----
        'Function'            => ['name' => 'Function', 'ip' => '192.168.36.3'],
        'LT-7'                => ['name' => 'LT-7', 'ip' => '192.168.36.6'],
        'LT-7-2'              => ['name' => 'LT-7-2', 'ip' => '192.168.36.5'],
        'LT 11'               => ['name' => 'LT 11', 'ip' => '192.168.36.7'],
        'LT 15'               => ['name' => 'LT 15', 'ip' => '192.168.36.8'],
        'LT 19'               => ['name' => 'LT 19', 'ip' => '192.168.36.9'],
        'LT 22'               => ['name' => 'LT 22', 'ip' => '192.168.36.10'],
    ];

    /**
     * Master connection mapping berdasarkan koreksi topology terbaru.
     * Setiap baris adalah sepasang ref key (undirected/garis hijau pada map).
     *
     * ARAH panah (source -> target) DIVERIFIKASI pada $this->directions
     * (di NetworkTopologyService::$edgeDirections) — daftar ini hanya
     * menentukan SET pasangan (undirected) yang ada.
     */
    protected array $connectionsByArea = [
        'PLAZA BRI' => [
            // Koreksi: TPS -> TPS 1. 8 device lain TANPA edge (56.17, RMIX SOHO,
            // SwBRI 2, Dist BRI, BHAKTI, Switch SFP blm ditentukan).
            ['TPS Plaza BRI', 'TPS 1'],
        ],

        'BUMI MANDIRI' => [
            ['Buchi', 'Sw Mandiri'],
            ['Sw Mandiri', 'YKK'],
            ['Sw Mandiri', 'FPG'],
        ],

        'INTILAND' => [
            // Semua device mengarah KE INTILAND (hub) — TPS Intiland = 202.154.4.90.
            ['PANDI (PANI)', 'Intiland Core'],
            ['DPRD', 'Intiland Core'],
            ['SM-FM (Intiland)', 'Intiland Core'],
            ['Kominfo', 'Intiland Core'],
            ['H Kampi', 'Intiland Core'],
            ['DINKES', 'Intiland Core'],
            ['TPS Intiland', 'Intiland Core'],
            ['DP3AK', 'Intiland Core'],
            ['PJB', 'Intiland Core'],
            ['SMKN-1', 'Intiland Core'],
            ['POP SMK WP', 'Intiland Core'],
            ['BTN Pemuda', 'Intiland Core'],
            ['POP SMK WP', 'SMK WP'],
            ['BTN Kenongo', 'BTN Pemuda'],
            ['SBY-JKT', 'Border'],
            ['IIX-JI', 'Border'],
        ],

        'POP YAPALIS' => [
            ['POP Mjkt (POP Mkt)', 'To SMK Nasional'],
            // Koreksi: SMK Nasional -> POP Krian
            ['SMK Nasional', 'POP Krian'],
        ],

        'POP SMK2' => [
            ['SMKN2', 'to SMKN8'],
        ],

        'POP VURI' => [
            // Terverifikasi: HANYA RMIX CORE -> RMIX HAP. Tidak ada koneksi tambahan.
            ['RMIX Core', 'RMIX hAP'],
        ],

        'POP SMKN 1' => [
            // POP SMKN 1 yang benar = 4.50 (device "POP YPM" 202.154.4.50).
            ['Disnaker', '93.60'],
            ['93.60', '93.59'],
            ['93.59', '4.50'],
        ],

        'POP SMK8' => [
            ['to SMK2', 'R.SMK8'],
            ['R.SMK8', 'AP SMK8'],
        ],

        'POP RAJASA' => [
            // Kominfo TIDAK lagi terhubung ke SMK Rajasa (koreksi: -> Intiland).
            ['to SMK8', 'SMK Rajasa'],
            ['SMK Rajasa', 'Dispenduk Siola'],
        ],

        'POP SHAM FM' => [
            // Hub = ROUTER (202.154.4.34). Semua hub line terverifikasi.
            ['To-DISNAKER', 'Router (Sham FM)'],
            ['to YPM', 'Router (Sham FM)'],
            ['To BU', 'Router (Sham FM)'],
            ['To Furi', 'Router (Sham FM)'],
            ['PB-DISNAKER', 'To-DISNAKER'],
            ['RMIX-DISNAKER', 'PB-DISNAKER'],
            ['AP Disnaker', 'RMIX-DISNAKER'],
            // PDI: PDI-Router -> PDI -> To PDI -> Router (PB-PDI tidak ada di DB,
            // sehingga hop terakhir dibuat langsung TO PDI -> ROUTER).
            ['PDI-Router', 'PDI'],
            ['PDI', 'To PDI'],
            ['To PDI', 'Router (Sham FM)'],
            // Kos Jojoran -> AP Furi -> To Furi
            ['Kost Jojoran', 'AP Furi'],
            ['AP Furi', 'To Furi'],
            // Perbanas: sambungan SALAH telah dihapus; tidak dibuat koneksi.
        ],

        'POP SMK-YPM' => [
            ['POP YPM', '93.11'],
            ['To Yapalis', 'POP YPM'],
            ['92.10', 'POP YPM'],
            ['ToKarantina', 'POP YPM'],
            ['92.39', 'POP YPM'],
            ['To Yakult', 'POP YPM'],
            ['92.37', 'POP YPM'],
            ['AP Yakult', 'To Yakult'],
            // BTN Winyung -> 92.44 -> 92.37
            ['BTN Winyung', '92.44'],
            ['92.44', '92.37'],
            // 56.78 -> Kedaung
            ['56.78', 'Kedaung'],
            // AP PLN berdiri sendiri -> TIDAK ada koneksi.
        ],

        'EPON-BU' => [
            // EPON-BU -> RMIX-BU -> To SHAMFM
            ['RMIX-BU', 'To SHAMFM'],
            ['EPON-BU', 'RMIX-BU'],
        ],

        'HOTEL BUMI' => [
            ['Function', 'Hotel Core'],
            ['LT-7', 'Hotel Core'],
            ['LT-7-2', 'Hotel Core'],
            ['LT 11', 'Hotel Core'],
            ['LT 15', 'Hotel Core'],
            ['LT 19', 'Hotel Core'],
            ['LT 22', 'Hotel Core'],
        ],
    ];

    /**
     * Duplikat/non-canonical yang muncul saat audit (dari data actual DB).
     */
    protected array $nonCanonical = [
        'TPS 1'                 => ['name' => 'TPS 1', 'ip' => '202.154.4.21', 'reason' => 'Device dummy lama. Canonical TPS 1 = 192.168.90.13'],
        'Switch SFP'            => ['name' => 'Switch SFP', 'ip' => '202.154.4.24', 'reason' => 'Device dummy lama.'],
        'Dst BRI'               => ['name' => 'Dst BRI', 'ip' => '202.154.4.20', 'reason' => 'Device dummy lama.'],
        'TPS 2'                 => ['name' => 'TPS 2', 'ip' => '202.154.4.22', 'reason' => 'Device dummy lama, tidak ada di topology.'],
        'EBAY'                  => ['name' => 'EBAY', 'ip' => '202.154.4.23', 'reason' => 'Device dummy lama, tidak ada di topology.'],
        'Dist BRI'              => ['name' => 'Dist BRI', 'ip' => '202.154.4.4', 'reason' => 'IP bertabrakan dgn Intiland 1 (core). Tidak terhubung di topology.'],
        '93.11 (202.154.93.11)' => ['name' => '93.11', 'ip' => '202.154.93.11', 'reason' => 'Bukan yg di topology (pakai 192.168.93.11).'],
        'SM-FM (202.154.4.60)'  => ['name' => 'SM-FM', 'ip' => '202.154.4.60', 'reason' => 'Bukan core Sham FM (pakai 202.154.4.222).'],
        'Pop Krian (202.154.4.70)' => ['name' => 'Pop Krian', 'ip' => '202.154.4.70', 'reason' => 'Bukan device POP Yapalis di topology.'],
        'DNS 1 (202.154.4.10)'  => ['name' => 'DNS 1', 'ip' => '202.154.4.10', 'reason' => 'Di luar topology.'],
        'KOMINFO (202.154.4.94)' => ['name' => 'KOMINFO', 'ip' => '202.154.4.94', 'reason' => 'Berkaitan dgn PDI-Router (Sham FM). Kominfo Rajasa = 202.154.4.226.'],
    ];

    protected array $resolved = [];
    protected array $failed = [];

    public function run(): void
    {
        $out = function (string $msg): void {
            if ($this->command) {
                $this->command->line($msg);
            } else {
                echo $msg . PHP_EOL;
            }
        };
        $info = function (string $msg): void {
            if ($this->command) {
                $this->command->info($msg);
            } else {
                echo $msg . PHP_EOL;
            }
        };
        $warn = function (string $msg): void {
            if ($this->command) {
                $this->command->warn($msg);
            } else {
                echo $msg . PHP_EOL;
            }
        };

        $out('');
        $info('============ MASTER TOPOLOGY AUDIT — RADNET WIRELESS NETWORK 2025 ============');
        $out('');

        $this->resolveRefs();

        // ---------- A. CANONICAL DEVICES ----------
        $info('A. CANONICAL DEVICES');
        foreach ($this->refs as $key => $ref) {
            $dev = $this->resolved[$key] ?? null;
            $row = $dev
                ? sprintf('  %s | %s | %s | %s | %s', $dev->id, $dev->name, $dev->ip_address, $dev->pop_name ?? '-', $key)
                : sprintf('  UNRESOLVED ---- %s (ip: %s)', $key, $ref['ip']);
            $out($row);
        }
        $out('');

        // ---------- B. DUPLICATE / NON-CANONICAL ----------
        $info('B. DUPLICATE / NON-CANONICAL DEVICES');
        foreach ($this->nonCanonical as $dupLabel => $dup) {
            $dev = Device::where('ip_address', $dup['ip'])
                ->where('name', 'like', '%' . $dup['name'] . '%')
                ->first();
            if ($dev) {
                $out(sprintf('  id=%s | name=%s | ip=%s | pop=%s | %s',
                    $dev->id, $dev->name, $dev->ip_address, $dev->pop_name ?? '-', $dup['reason']));
            }
        }
        $out('');

        // ---------- Existing connections audit ----------
        [$toKeep, $toRemove] = $this->auditExistingConnections();

        // ---------- C. EXISTING CONNECTIONS TO KEEP ----------
        $info('C. EXISTING CONNECTIONS TO KEEP (' . count($toKeep) . ')');
        foreach ($toKeep as $c) {
            $out(sprintf('  %s <-> %s | %s', $c['fromName'], $c['toName'], $c['reason']));
        }
        $out('');

        // ---------- D. EXISTING CONNECTIONS TO REMOVE / RECONCILE ----------
        $info('D. EXISTING CONNECTIONS TO REMOVE / RECONCILE (' . count($toRemove) . ')');
        foreach ($toRemove as $c) {
            $out(sprintf('  %s <-> %s | %s', $c['fromName'], $c['toName'], $c['reason']));
        }
        $out('');

        // ---------- E. NEW / VALIDATED CONNECTIONS ----------
        $info('E. MASTER CONNECTIONS (akan diinsert / dipertahankan)');
        $total = 0;
        foreach ($this->connectionsByArea as $area => $pairs) {
            foreach ($pairs as $pair) {
                $a = $this->resolved[$pair[0]] ?? null;
                $b = $this->resolved[$pair[1]] ?? null;
                if (!$a || !$b) {
                    $warn(sprintf('  SKIP %s | %s <-> %s (unresolved)', $area, $pair[0], $pair[1]));
                    continue;
                }
                $total++;
                $out(sprintf('  %s | %s(id %s) <-> %s(id %s)', $area, $a->name, $a->id, $b->name, $b->id));
            }
        }
        $out('');

        // ---------- F. NEEDS_REVIEW ----------
        $info('F. NEEDS_REVIEW (tidak dibuat koneksinya)');
        $review = $this->needsReview();
        foreach ($review as $r) {
            $out(sprintf('  %s | %s <-> %s | %s', $r[0], $r[1], $r[2], $r[3]));
        }
        $out('');

        $info('==================================== END AUDIT ====================================');
        $out('');

        // ---------- APPLY ----------
        $warn('>>> MENERAPKAN PERUBAHAN KE database (`device_connections`) ...');

        $now = now();

        // 1) Reconcile: hapus koneksi existing yg TIDAK termasuk master mapping.
        foreach ($toRemove as $c) {
            DeviceConnection::where('id', $c['id'])->delete();
            $info('  REMOVED  : ' . $c['fromName'] . ' <-> ' . $c['toName']);
        }

        // 2) Insert / pertahankan master connections.
        $inserted = 0;
        foreach ($this->connectionsByArea as $area => $pairs) {
            foreach ($pairs as $pair) {
                $a = $this->resolved[$pair[0]] ?? null;
                $b = $this->resolved[$pair[1]] ?? null;
                if (!$a || !$b) {
                    continue;
                }
                [$fromId, $toId] = $this->normalizePair($a->id, $b->id);

                $exists = DeviceConnection::where('from_device_id', $fromId)
                    ->where('to_device_id', $toId)
                    ->exists();

                DeviceConnection::updateOrCreate(
                    ['from_device_id' => $fromId, 'to_device_id' => $toId],
                    ['connection_type' => 'ethernet', 'updated_at' => $now]
                );

                if (!$exists) {
                    $inserted++;
                    $info(sprintf('  INSERT  : %s(id %s) <-> %s(id %s)  [%s]', $a->name, $a->id, $b->name, $b->id, $area));
                }
            }
        }

        // Catatan: perangkat (table `devices`) TIDAK diubah di sini.
        // Nama device, IP, status, maupun pop_name dibiarkan sesuai data asli
        // (implementasi connection TIDAK menyentuh database device/status).

        $out('');
        $info(sprintf('>>> SELESAI. Koneksi dihapus: %d | Koneksi ditambahkan: %d | Total koneksi master: %d',
            count($toRemove), $inserted, $total));
    }

    /**
     * Resolve semua ref -> Device. Simpan ke $this->resolved / $this->failed.
     */
    protected function resolveRefs(): void
    {
        foreach ($this->refs as $key => $ref) {
            $device = $this->resolveDevice($ref);
            if ($device) {
                $this->resolved[$key] = $device;
            } else {
                $this->failed[$key] = $ref;
            }
        }
    }

    /**
     * Cari device ACTUAL (tidak membuat baru) berdasarkan ip + validasi nama.
     */
    protected function resolveDevice(array $ref): ?Device
    {
        $byIp = Device::where('ip_address', $ref['ip'])->get();

        // IP unik -> langsung dipakai.
        if ($byIp->count() === 1) {
            return $byIp->first();
        }

        // IP duplikat -> disaring ulang berdasarkan nama ternormalisasi.
        if ($byIp->count() > 1) {
            $byName = $byIp->filter(fn (Device $d) => $this->normalizeName($d->name) === $this->normalizeName($ref['name']));
            if ($byName->count() === 1) {
                return $byName->first();
            }

            return null; // ambigu pada IP yang sama
        }

        // IP tidak ditemukan -> fallback ke pencarian nama.
        $likes = Device::where('name', 'like', '%' . $ref['name'] . '%')->get();
        $exact = $likes->filter(fn (Device $d) => $this->normalizeName($d->name) === $this->normalizeName($ref['name']));

        if ($exact->count() === 1) {
            return $exact->first();
        }

        if ($exact->count() > 1) {
            if (! empty($ref['pop'])) {
                $inPop = $exact->firstWhere('pop_name', $ref['pop']);
                if ($inPop) {
                    return $inPop;
                }
            }

            return null; // nama ambigu
        }

        // Tidak ada kecocokan persis; ambil bila hanya ada satu kemiripan.
        return $likes->count() === 1 ? $likes->first() : null;
    }

    /**
     * Normalisasi nama record DB: buang '*', spasi ganda, dan suffix " (ip)".
     */
    protected function normalizeName(string $name): string
    {
        $name = str_replace(["\xc2\xa0", "\xa0"], ' ', $name);
        $name = preg_replace('/\*+/u', '', $name);
        $name = preg_replace('/\(\s*[\d.:]+\s*\)\s*$/u', '', $name);
        $name = preg_replace('/\s+/u', ' ', trim($name));

        return mb_strtolower(trim($name), 'UTF-8');
    }

    /**
     * Pasangan undirected dinormalisasi -> [minId, maxId].
     */
    protected function normalizePair(int $a, int $b): array
    {
        return $a <= $b ? [$a, $b] : [$b, $a];
    }

    /**
     * Device id pada POP/area yang koneksinya SUDAH terverifikasi (source of truth).
     *
     * Koneksi lama yang menyentuh device di area ini DAN tidak cocok dengan
     * master mapping (connectionsByArea) akan direkonsiliasi/hapus.
     *
     * - id 12 (SM-FM) diintilandkan sesuai koreksi SMK-FM -> INTILAND
     *   (berbeda dgn pop_name legacy "POP Sham FM").
     * - GRUP HIJAU (SW 35.x, INTILAND 192.168.168.2, MG/MX/MV, FTP, DNS, dst.)
     *   TIDAK di-audit: tidak ada hubungan yang divertifikasi di sana.
     */
    protected array $auditedAreas = [
        'PLAZA BRI'    => [8, 24, 25, 26, 46, 51, 52, 82],
        'BUMI MANDIRI' => [78, 79, 80, 81],
        'INTILAND'     => [2, 3, 4, 5, 6, 7, 12, 37, 42, 44, 53, 55, 56, 58, 59, 65, 66, 83],
        'POP SMK-YPM'  => [10, 11, 19, 20, 21, 38, 39, 40, 43, 62, 63, 71, 88, 89, 90, 91],
        'POP SMK8'     => [72, 73, 74],
        'POP SHAM FM'  => [45, 47, 48, 49, 57, 68, 69, 70, 84, 92, 112, 113, 114, 115],
        'EPON-BU'      => [85, 86, 87],
        'POP YAPALIS'  => [13, 93, 94, 96],
        'POP VURI'     => [75, 76],
        'POP SMKN 1'   => [108, 109, 110, 111],
        'POP SMK2'     => [18, 50],
        'POP GRESIK'   => [1],
        'POP RAJASA'   => [61, 67, 77, 83],
        'HOTEL BUMI'   => [27, 28, 29, 30, 31, 32, 33, 34, 35, 54],
    ];

    /**
     * Audit seluruh device_connections existing.
     *
     * - Koneksi yang pair-nya ada di master mapping (connectionsByArea) -> KEEP.
     * - Koneksi yang TIDAK ada di master mapping HANYA dihapus bila menyentuh
     *   device pada POP yang sudah terverifikasi (auditedAreas). Koneksi di area
     *   lain (Intiland, SMK-YPM, Yapalis, SMK2, EPON-BU, Hotel Bumi, dst.) yang
     *   belum diberikan data terverifikasinya TIDAK dihapus.
     */
    protected function auditExistingConnections(): array
    {
        $masterPairs = [];
        foreach ($this->connectionsByArea as $pairs) {
            foreach ($pairs as $pair) {
                $a = $this->resolved[$pair[0]] ?? null;
                $b = $this->resolved[$pair[1]] ?? null;
                if ($a && $b) {
                    [$min, $max] = $this->normalizePair($a->id, $b->id);
                    $masterPairs[$min . ':' . $max] = true;
                }
            }
        }

        $auditedIds = [];
        foreach ($this->auditedAreas as $ids) {
            foreach ($ids as $id) {
                $auditedIds[$id] = true;
            }
        }

        $toKeep = [];
        $toRemove = [];

        foreach (DeviceConnection::all() as $c) {
            [$min, $max] = $this->normalizePair($c->from_device_id, $c->to_device_id);
            $from = Device::find($c->from_device_id);
            $to = Device::find($c->to_device_id);
            $nameFrom = $from ? $from->name : ('#' . $c->from_device_id);
            $nameTo = $to ? $to->name : ('#' . $c->to_device_id);

            $touchesAudited = isset($auditedIds[$c->from_device_id]) || isset($auditedIds[$c->to_device_id]);

            if (isset($masterPairs[$min . ':' . $max])) {
                $toKeep[] = [
                    'id' => $c->id,
                    'fromName' => $nameFrom,
                    'toName' => $nameTo,
                    'reason' => 'Cocok dengan master mapping (validated).',
                ];
            } elseif ($touchesAudited) {
                $toRemove[] = [
                    'id' => $c->id,
                    'fromName' => $nameFrom,
                    'toName' => $nameTo,
                    'reason' => 'OLD/UNVERIFIED: tidak ada pada daftar connection terverifikasi (POP ybs).',
                ];
            } else {
                $toKeep[] = [
                    'id' => $c->id,
                    'fromName' => $nameFrom,
                    'toName' => $nameTo,
                    'reason' => 'Area belum diverifikasi: koneksi existing dipertahankan (tidak dihapus).',
                ];
            }
        }

        return [$toKeep, $toRemove];
    }

    /**
     * Daftar relasi pada map yg TIDAK bisa dipetakan ke database.
     */
    protected function needsReview(): array
    {
        return [
            ['INTILAND', 'PANI/DP3D', 'Intiland Core', 'DP3D tidak ada di DB (PANI -> PANDI sudah dipetakan)'],
            ['INTILAND', 'S1-FM', 'Intiland Core', 'Tidak ada di DB (SM-FM 202.154.4.222 sudah mengarah ke Intiland)'],
            ['INTILAND', 'OMINFO', 'Intiland Core', 'Hanya KOMINFO/Kominfo yg ada; Kominfo area tidak jelas (202.154.4.226 sudah dipetakan ke Intiland)'],
            ['INTILAND', 'BTN Pinang', 'BTN Pemuda', 'Tidak ada di DB'],
            ['POP YAPALIS', 'Pop Tian', 'To SMK Nasional', 'Tidak ada di DB'],
            ['POP YAPALIS', 'POP Krian via ypm', 'Pop Tian', 'Tidak ada di DB (POP Krian 202.154.56.126 sudah dipetakan ke SMK Nasional)'],
            ['POP SMK-YPM', '92.7', 'POP YPM', 'Tidak ada di DB'],
            ['POP SMK-YPM', 'BTN Wiyung', '92.14', 'BTN Wiyung sudah ada sbg node; link ke 92.14 tidak bisa dibuat (92.14 tidak ada di DB)'],
            ['POP SMK-YPM', '92.14', '92.7', 'Tidak ada di DB'],
            ['POP SMK-YPM', 'To Karantina', '92.5', 'Tidak ada di DB'],
            ['POP SMK-YPM', '92.5', 'Karantina', 'Tidak ada di DB'],
            ['POP SMK-YPM', 'POP YPM', '92.13', 'Tidak ada di DB'],
            ['POP SMK-YPM', '92.13', 'Kedung...', 'Tidak ada di DB'],
            ['POP SMK-YPM', '92.35 / Karantina / AP PLN', '-', 'Tidak terhubung (AP PLN berdiri sendiri sesuai koreksi)'],
            ['HOTEL BUMI', 'N6', 'Hotel Core', 'Tidak ada di DB'],
            // Perbanas: koneksi lama dihapus sesuai koreksi (node tetap tampil tanpa edge).
            ['POP SHAM FM', 'Perbanas', '-', 'Koneksi dihapus; tidak dibuat lagi'],
            ['POP SHAM FM', 'TO PDI -> ROUTER', '(PB-PDI)', 'PB-PDI TIDAK ada di DB. Hop dibuat LANGSUNG TO PDI -> ROUTER.'],
            ['POP RAJASA', 'Kominfo', 'Intiland Core', 'Kominfo (202.154.4.226) kini mengarah ke INTILAND (tidak lagi ke SMK Rajasa)'],
        ];
    }
}