<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceConnection;
use App\Models\MonitoringLog;

/**
 * Sumber tunggal topology jaringan "RADNET Wireless Network 2025".
 *
 * Menghasilkan payload topology yang SAMA untuk semua halaman
 * (admin.monitoring) sehingga konsisten.
 *
 * - Posisi area & device MANUAL mengikuti map asli (bukan auto-layout).
 * - Data device dari tabel `devices` dan koneksi dari `device_connections`.
 *
 * KOMPOSISI MAP (urutan grid sesuai gambar referensi):
 *   KIRI (col 0) : Plaza BRI, Bumi Mandiri, POP Gresik, POP SMK8, POP Rajasa
 *   TENGAH (col 1): Intiland, POP SMK-YRM, POP Sham FM, EPON-BU
 *   KANAN (col 2): POP Krian, POP SMK2, POP Vuri, POP SMKN 1, Server & DNS
 *   KANAN PENUH (col 3): Hotel Bumi
 */
class NetworkTopologyService
{
    /**
     * KEANGGOTAAN area per device id — source of truth pembagian group.
     * Node di-assign ke area lewat mapping eksplisit ini (bukan geometri
     * kotak), sehingga tidak bergantung urutan rect untuk node di perbatasan.
     *
     * Urutan array menentukan urutan render kotak area.
     */
    private array $areaDevices = [
        'Plaza BRI'    => [8, 51, 52, 24, 46, 82, 25, 26],
        'Bumi Mandiri' => [78, 79, 80, 81],
        'POP Gresik'   => [1],
        'POP SMK8'     => [72, 73, 74],
        'POP Rajasa'   => [61, 67, 77],

        'Intiland'     => [2, 12, 37, 42, 83, 58, 3, 44, 59, 53, 5, 65, 66, 55, 56, 4, 6, 7],
        'POP SMK-YRM'  => [10, 11, 19, 20, 21, 38, 39, 40, 43, 62, 63, 71, 88, 89, 90, 91],
        'POP Sham FM'  => [92, 48, 57, 113, 47, 68, 114, 69, 70, 115, 49, 84, 45, 112],
        'EPON-BU'      => [85, 86, 87],

        'POP Krian'    => [93, 94, 13, 96],
        'POP SMK2'     => [18, 50],
        'POP Vuri'     => [75, 76],
        'POP SMKN 1'   => [108, 109, 110, 111],
        'Server & DNS' => [64, 16, 36, 15, 14, 17, 106, 105, 22, 23, 95, 104, 107, 101, 102, 103, 98, 99, 100, 9, 60, 97],

        'Hotel Bumi'   => [27, 28, 29, 30, 31, 32, 33, 34, 35, 54],
    ];

    /**
     * POSISI MANUAL per device (key = id record `device` di database).
     * Nilai = [x, y] relatif di dalam grupnya (akan ditranslasikan ke kotak).
     *
     * Pola per area mengikuti topologi referensi:
     *  - Star: hub di tengah, spoke kiri/kanan/bawah lurus.
     *  - Chain: antrean vertikal/horizontal satu arah.
     */
    private array $topologyPositions = [
        // ---- Plaza BRI : 2 baris (atas: 56.17, RMIX SOHO, SwBRI 2, Switch SFP; bawah: Dist BRI, TPS, TPS 1) ----
        8   => [80, 100],   // 56.17
        52  => [200, 100],  // RMIX SOHO
        24  => [320, 100],  // SwBRI 2
        46  => [430, 100],  // Switch SFP
        82  => [430, 210],  // BHAKTI
        51  => [80, 210],   // Dist BRI
        25  => [200, 210],  // TPS
        26  => [320, 210],  // TPS 1

        // ---- Bumi Mandiri : star, hub Sw Mandiri di tengah ----
        78  => [220, 400],  // Sw Mandiri (hub)
        81  => [100, 320],  // Buchi
        79  => [340, 320],  // YKK
        80  => [220, 540],  // FPG

        // ---- Intiland : star besar, hub Intiland 1 di tengah-atas, spine BTN di bawah ----
        2   => [250, 780],  // Intiland 1 (hub)
        12  => [80, 780],   // SM-FM
        37  => [80, 930],   // DPRD
        42  => [80, 1080],  // DINKES
        83  => [80, 1230],  // Kominfo
        58  => [420, 780],  // PANDI
        3   => [420, 930],  // PJB
        44  => [420, 1080], // H Kampi
        59  => [420, 1230], // DP3AK
        53  => [175, 950],  // TPS
        5   => [325, 950],  // SMKN-1
        65  => [120, 1120], // BTN Pemuda
        66  => [250, 1120], // BTN Kenongo
        55  => [330, 1320], // POP SMK WP
        56  => [180, 1490], // SMK WP
        4   => [430, 1320], // Border
        6   => [430, 1490], // SBY-JKT
        7   => [300, 1490], // IIX-JI

        // ---- POP SMK-YRM : star dari POP YPM (hub tengah), chain BTN/92.37 & AP Yakult di kiri-bawah ----
        11  => [600, 300],  // POP YPM (hub)
        10  => [450, 150],  // 93.11
        71  => [780, 150],  // 92.10
        43  => [450, 300],  // To Yapalis
        19  => [780, 300],  // 92.39
        62  => [450, 450],  // To Yakult
        88  => [780, 450],  // ToKarantina
        63  => [450, 600],  // AP Yakult
        21  => [780, 600],  // 56.78
        40  => [450, 750],  // BTN Wiyung
        39  => [600, 750],  // 92.44
        38  => [750, 750],  // 92.37
        20  => [780, 750],  // Kedaung
        89  => [450, 900],  // 92.35
        90  => [610, 900],  // Karantina
        91  => [770, 900],  // AP PLN

        // ---- POP Sham FM : hub Router tengah, chain Disnaker kiri & PDI kanan, Furi bawah-kiri ----
        48  => [660, 520],  // Router (hub)
        92  => [480, 420],  // To-DISNAKER
        113 => [480, 540],  // PB-DISNAKER
        114 => [480, 660],  // RMIX-DISNAKER
        115 => [480, 780],  // AP Disnaker
        57  => [850, 600],  // to YPM
        84  => [550, 760],  // To BU
        68  => [820, 460],  // To PDI
        69  => [820, 580],  // PDI
        70  => [820, 700],  // PDI-Router
        47  => [770, 900],  // To Furi
        49  => [620, 900],  // AP Furi
        45  => [480, 900],  // Kos Jojoran
        112 => [740, 700],  // Perbanas

        // ---- EPON-BU : chain horizontal EPON-BU -> RMIX-BU -> To SHAMFM ----
        87  => [560, 1560], // EPON-BU
        85  => [680, 1560], // RMIX-BU
        86  => [800, 1560], // To SHAMFM

        // ---- POP Krian : chain POP Mjkt -> To SMK Nasional -> Pop Krian ----
        93  => [980, 200],  // POP Mjkt
        94  => [980, 360],  // To SMK Nasional
        13  => [980, 520],  // Pop Krian
        96  => [1150, 520], // pop Krian via ypm

        // ---- POP SMK2 : chain SMKN2 -> to SMKN8 ----
        18  => [980, 640],  // SMKN2
        50  => [980, 780],  // to SMKN8

        // ---- POP Vuri : chain RMIX Core -> RMIX hAP ----
        75  => [980, 820],  // RMIX Core
        76  => [980, 960],  // RMIX hAP

        // ---- POP SMKN 1 : chain Disnaker -> 93.60 -> 93.59 -> 4.50 ----
        111 => [980, 1050], // Disnaker
        110 => [980, 1200], // 93.60
        109 => [980, 1350], // 93.59
        108 => [980, 1500], // 4.50 (POP YPM)

        // ---- POP Gresik ----
        1   => [640, 1200], // POP Gresik

        // ---- POP SMK8 : chain to SMK2 -> R.SMK8 -> AP SMK8 ----
        73  => [640, 680],  // to SMK2
        72  => [640, 800],  // R.SMK8
        74  => [640, 920],  // AP SMK8

        // ---- POP Rajasa : to SMK8 -> SMK Rajasa -> Dispenduk Siola ----
        67  => [520, 1300], // to SMK8
        61  => [660, 1300], // SMK Rajasa (hub)
        77  => [660, 1450], // Dispenduk Siola

        // ---- Server & DNS (GRUP HIJAU) : jajaran switch SW 35.x berbaris rapi ----
        64  => [1150, 100], // SW 35.1
        16  => [1340, 100], // SW 35.3
        36  => [1530, 100], // SW 35.7
        15  => [1720, 100], // SW 35.10
        14  => [1150, 230], // SW 35.6
        17  => [1340, 230], // SW 35.14
        106 => [1530, 230], // SW 35.27
        105 => [1720, 230], // SW 35.8
        22  => [1150, 360], // INTILAND
        23  => [1340, 360], // GRANDCITY
        95  => [1530, 360], // SMK Unitomo
        104 => [1720, 360], // MV 2
        107 => [1150, 490], // FTP 1
        101 => [1340, 490], // MG 4
        102 => [1530, 490], // MX
        103 => [1720, 490], // MV 1
        98  => [1150, 620], // MG 1
        99  => [1340, 620], // MG 2
        100 => [1530, 620], // MG 3
        9   => [1720, 620], // DNS 1
        60  => [1150, 750], // DNS 2
        97  => [1340, 750], // DNS 3

        // ---- Hotel Bumi : trunk vertikal lantai (OLT HBS -> Function -> LT-7 .. LT-22) + core kanan ----
        54  => [1860, 130], // OLT HBS
        29  => [1860, 290], // Function
        30  => [1860, 450], // LT-7
        31  => [1860, 610], // LT-7-2
        32  => [1860, 770], // LT 11
        33  => [1860, 930], // LT 15
        34  => [1860, 1120],// LT 22
        27  => [2080, 130], // NOC
        28  => [2080, 290], // Core (hub)
        35  => [2080, 450], // LT 19
    ];

    /**
     * Kolom (grid) untuk setiap area — komposisi map asli.
     * Dipakai untuk penempatan visual kotak group.
     */
    private array $areaColumns = [
        'Plaza BRI'    => 0, 'Bumi Mandiri' => 0, 'POP Gresik' => 0, 'POP SMK8' => 0, 'POP Rajasa' => 0,
        'Intiland'     => 1, 'POP SMK-YRM' => 1, 'POP Sham FM' => 1, 'EPON-BU' => 1,
        'POP Krian'    => 2, 'POP SMK2' => 2, 'POP Vuri' => 2, 'POP SMKN 1' => 2, 'Server & DNS' => 2,
        'Hotel Bumi'   => 3,
    ];

    /**
     * Hub (pusat star) yang EKSPLISIT per area.
     * Jika area tidak terdaftar, hub = device dengan koneksi terbanyak.
     */
    private array $areaHubs = [
        'Bumi Mandiri' => 78,
        'Intiland'     => 2,
        'POP SMK-YRM'  => 11,
        'POP Sham FM'  => 48,
        'Hotel Bumi'   => 28,
    ];

    /**
     * Parameter BOXING AREA / visual grouping (hanya tampilan).
     * Koordinat device hanya ditranslasikan mengikuti kotaknya.
     */
    private const AREA_GAP_X   = 56;
    private const AREA_GAP_Y   = 48;
    private const AREA_MARGIN  = 28;
    private const AREA_PAD_LR  = 64;
    private const AREA_PAD_TOP = 56;
    private const AREA_PAD_BOT = 40;
    private const AREA_MIN_W   = 200;

    /**
     * ARAH connection yang TERVERIFIKASI (hanya untuk render arrow/panah).
     *
     * Key  = pasangan undirected "minId:maxId" (sama seperti tabel device_connections).
     * Value= [sourceId, targetId] sesuai mapping real (source -> target).
     *
     * "A -> B" = panah dari A mengarah ke B (A = source, B = target).
     */
    private array $edgeDirections = [
        // ---- Plaza BRI ----
        '25:26' => [25, 26], // TPS -> TPS 1

        // ---- Bumi Mandiri (hub Sw Mandiri mengarah ke semua leaf) ----
        '78:81' => [78, 81], // Sw Mandiri -> Buchi
        '78:79' => [78, 79], // Sw Mandiri -> YKK
        '78:80' => [78, 80], // Sw Mandiri -> FPG

        // ---- Intiland (hub Intiland 1 mengarah ke semua leaf) ----
        '2:3'   => [2, 3],   // Intiland -> PJB
        '2:5'   => [2, 5],   // Intiland -> SMKN-1
        '2:12'  => [2, 12],  // Intiland -> SM-FM
        '2:37'  => [2, 37],  // Intiland -> DPRD
        '2:42'  => [2, 42],  // Intiland -> DINKES
        '2:44'  => [2, 44],  // Intiland -> H Kampi
        '2:53'  => [2, 53],  // Intiland -> TPS
        '2:55'  => [2, 55],  // Intiland -> POP SMK WP
        '2:58'  => [2, 58],  // Intiland -> PANDI
        '2:59'  => [2, 59],  // Intiland -> DP3AK
        '2:65'  => [2, 65],  // Intiland -> BTN Pemuda
        '2:83'  => [2, 83],  // Intiland -> Kominfo
        '4:6'   => [4, 6],   // Border -> SBY-JKT
        '4:7'   => [4, 7],   // Border -> IIX-JI
        '55:56' => [55, 56], // POP SMK WP -> SMK WP
        '65:66' => [65, 66], // BTN Pemuda -> BTN Kenongo

        // ---- POP SMK-YRM (hub POP YPM mengarah ke semua spoke) ----
        '10:11' => [11, 10], // POP YPM -> 93.11
        '11:19' => [11, 19], // POP YPM -> 92.39
        '11:38' => [11, 38], // POP YPM -> 92.37
        '11:43' => [11, 43], // POP YPM -> To Yapalis
        '11:62' => [11, 62], // POP YPM -> To Yakult
        '11:71' => [11, 71], // POP YPM -> 92.10
        '11:88' => [11, 88], // POP YPM -> ToKarantina
        '20:21' => [21, 20], // 56.78 -> Kedaung
        '38:39' => [39, 38], // 92.44 -> 92.37
        '39:40' => [40, 39], // BTN Wiyung -> 92.44
        '62:63' => [63, 62], // AP Yakult -> To Yakult

        // ---- POP Krian ----
        '13:94' => [94, 13], // To SMK Nasional -> Pop Krian

        // ---- POP SMK2 ----
        '18:50' => [18, 50], // SMKN2 -> to SMKN8

        // ---- POP SMK8 ----
        '72:73' => [73, 72], // to SMK2 -> R.SMK8
        '72:74' => [74, 72], // AP SMK8 -> R.SMK8

        // ---- POP Rajasa ----
        '61:67' => [67, 61], // to SMK8 -> SMK Rajasa
        '61:77' => [77, 61], // Dispenduk Siola -> SMK Rajasa

        // ---- POP Sham FM (hub Router) ----
        '45:49' => [45, 49],  // Kos Jojoran -> AP Furi
        '47:48' => [47, 48],  // To Furi -> Router
        '47:49' => [49, 47],  // AP Furi -> To Furi
        '48:57' => [57, 48],  // to YPM -> Router
        '48:68' => [68, 48],  // To PDI -> Router
        '48:84' => [84, 48],  // To BU -> Router
        '48:92' => [92, 48],  // To-DISNAKER -> Router
        '68:69' => [69, 68],  // PDI -> To PDI
        '69:70' => [70, 69],  // PDI-Router -> PDI
        '92:113' => [113, 92], // PB-DISNAKER -> To-DISNAKER
        '113:114' => [114, 113], // RMIX-DISNAKER -> PB-DISNAKER
        '114:115' => [115, 114], // AP Disnaker -> RMIX-DISNAKER

        // ---- POP Vuri ----
        '75:76' => [75, 76], // RMIX Core -> RMIX hAP

        // ---- POP SMKN 1 (Disnaker -> 93.60 -> 93.59 -> 4.50) ----
        '110:111' => [111, 110], // Disnaker -> 93.60
        '109:110' => [110, 109], // 93.60 -> 93.59
        '108:109' => [109, 108], // 93.59 -> 4.50

        // ---- EPON-BU (EPON-BU -> RMIX-BU -> To SHAMFM) ----
        '85:86' => [85, 86], // RMIX-BU -> To SHAMFM
        '85:87' => [87, 85], // EPON-BU -> RMIX-BU

        // ---- Hotel Bumi (Core -> Function & lantai) ----
        '28:29' => [28, 29], // Core -> Function
        '28:30' => [28, 30], // Core -> LT-7
        '28:31' => [28, 31], // Core -> LT-7-2
        '28:32' => [28, 32], // Core -> LT 11
        '28:33' => [28, 33], // Core -> LT 15
        '28:34' => [28, 34], // Core -> LT 22
        '28:35' => [28, 35], // Core -> LT 19
    ];

    /**
     * Bangun payload topology: node berposisi MANUAL + edge dari
     * device_connections (data final).
     */
    public function build(): array
    {
        $devices = Device::whereIn('id', array_keys($this->topologyPositions))
            ->get()
            ->keyBy('id');

        $rawConnections = DeviceConnection::select('from_device_id', 'to_device_id', 'connection_type')
            ->get()
            ->filter(fn ($c) => isset($devices[$c->from_device_id]) && isset($devices[$c->to_device_id]));

        $degree = [];
        $edges = [];
        foreach ($rawConnections as $c) {
            [$min, $max] = $this->normalizePair((int) $c->from_device_id, (int) $c->to_device_id);
            $key = $min . ':' . $max;

            if (isset($this->edgeDirections[$key])) {
                [$fromId, $toId] = $this->edgeDirections[$key];
            } else {
                $fromId = (int) $c->from_device_id;
                $toId = (int) $c->to_device_id;
            }

            $edges[] = [
                'from' => $fromId,
                'to'   => $toId,
                'type' => $c->connection_type,
            ];
            $degree[$c->from_device_id] = ($degree[$c->from_device_id] ?? 0) + 1;
            $degree[$c->to_device_id] = ($degree[$c->to_device_id] ?? 0) + 1;
        }

        // Kelompokkan node ke area berdasarkan mapping EKSPLISIT ($areaDevices).
        $idArea = [];
        foreach ($this->areaDevices as $area => $ids) {
            foreach ($ids as $id) {
                $idArea[$id] = $area;
            }
        }

        $byArea = [];
        foreach ($this->topologyPositions as $id => [$x, $y]) {
            $device = $devices[$id] ?? null;
            if (! $device) {
                continue;
            }
            $area = $idArea[$id] ?? 'Lainnya';
            $byArea[$area][] = [
                'id'     => (int) $id,
                'name'   => $device->name,
                'ip'     => $device->ip_address,
                'icon'   => $device->icon_type ?? 'server',
                'status' => $device->status,
                'pop'    => $device->pop_name,
                'degree' => $degree[$id] ?? 0,
                'x'      => $x,
                'y'      => $y,
            ];
        }

        // Ukuran alami kotak group = bounding konten device + padding internal.
        $size = [];
        foreach ($this->areaDevices as $name => $ids) {
            $nodes = $byArea[$name] ?? [];
            if (! $nodes) {
                $size[$name] = null;
                continue;
            }
            $xs = array_column($nodes, 'x');
            $ys = array_column($nodes, 'y');
            $minX = min($xs); $maxX = max($xs);
            $minY = min($ys); $maxY = max($ys);
            $size[$name] = [
                'minX'   => $minX,
                'maxX'   => $maxX,
                'minY'   => $minY,
                'maxY'   => $maxY,
                'width'  => max(($maxX - $minX) + 2 * self::AREA_PAD_LR, self::AREA_MIN_W),
                'height' => ($maxY - $minY) + self::AREA_PAD_TOP + self::AREA_PAD_BOT,
            ];
        }

        // Letakkan kotak group dalam grid kolom (komposisi map asli).
        $box = [];
        $cursorX = self::AREA_MARGIN;
        $maxCol = max($this->areaColumns);
        for ($ci = 0; $ci <= $maxCol; $ci++) {
            $colRight = $cursorX;
            $cursorY = self::AREA_MARGIN;
            foreach ($this->areaDevices as $name => $ids) {
                if (($this->areaColumns[$name] ?? -1) !== $ci || ! $size[$name]) {
                    continue;
                }
                $box[$name] = [
                    'x' => $cursorX,
                    'y' => $cursorY,
                    'w' => $size[$name]['width'],
                    'h' => $size[$name]['height'],
                ];
                $cursorY += $size[$name]['height'] + self::AREA_GAP_Y;
                $colRight = max($colRight, $cursorX + $size[$name]['width']);
            }
            $cursorX = $colRight + self::AREA_GAP_X;
        }

        // Re-proyeksi node ke koordinat tampilan di dalam kotaknya.
        foreach ($box as $name => $b) {
            $sz = $size[$name] ?? null;
            if (! $sz || empty($byArea[$name])) {
                continue;
            }
            $contentX = $b['x'] + ($b['w'] - ($sz['maxX'] - $sz['minX'])) / 2;
            foreach ($byArea[$name] as &$n) {
                $n['x'] = $contentX + ($n['x'] - $sz['minX']);
                $n['y'] = $b['y'] + self::AREA_PAD_TOP + ($n['y'] - $sz['minY']);
            }
            unset($n);
        }

        // Tentukan hub per area (override eksplisit, fallback koneksi terbanyak).
        $areas = [];
        $maxRight = 0;
        $maxBottom = 0;
        foreach ($this->areaDevices as $name => $ids) {
            $nodes = $byArea[$name] ?? [];

            $hubId = $this->areaHubs[$name] ?? null;
            if ($hubId === null) {
                $max = -1;
                foreach ($nodes as $n) {
                    if ($n['degree'] > $max) {
                        $max = $n['degree'];
                        $hubId = $n['id'];
                    }
                }
            }

            foreach ($nodes as &$n) {
                $n['isHub'] = $n['id'] === $hubId && $n['degree'] > 0;
            }
            unset($n);

            $b = $box[$name] ?? ['x' => 0, 'y' => 0, 'w' => 0, 'h' => 0];
            $maxRight = max($maxRight, $b['x'] + $b['w']);
            $maxBottom = max($maxBottom, $b['y'] + $b['h']);

            $areas[] = [
                'name'  => $name,
                'x'     => $b['x'],
                'y'     => $b['y'],
                'w'     => $b['w'],
                'h'     => $b['h'],
                'nodes' => array_values($nodes),
            ];
        }

        return [
            'viewBox' => sprintf(
                '0 0 %d %d',
                (int) ceil($maxRight + self::AREA_MARGIN),
                (int) ceil($maxBottom + self::AREA_MARGIN)
            ),
            'areas'   => $areas,
            'edges'   => $edges,
        ];
    }

    /**
     * Pasangan undirected dinormalisasi -> [minId, maxId].
     */
    private function normalizePair(int $a, int $b): array
    {
        return $a <= $b ? [$a, $b] : [$b, $a];
    }

    /**
     * Detail satu device untuk popup MAP.
     *
     * Read-only: tidak menjalankan ping dan tidak mengubah database.
     */
    public function deviceDetail(Device $device): array
    {
        $links = DeviceConnection::where(function ($query) use ($device) {
            $query->where('from_device_id', $device->id)
                ->orWhere('to_device_id', $device->id);
        })->count();

        $lastLog = MonitoringLog::where('device_id', $device->id)
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->first();

        $logs = MonitoringLog::where('device_id', $device->id)
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return [
            'device' => [
                'id'         => $device->id,
                'name'       => $device->name,
                'ip_address' => $device->ip_address,
                'type'       => $device->type,
                'pop_name'   => $device->pop_name,
                'status'     => $device->status,
                'icon_type'  => $device->icon_type,
                'deskripsi'  => $device->deskripsi,
                'links'      => $links,
            ],
            'stats' => [
                'type'           => $device->type ?? 'ICMP',
                'total_polls'    => (int) $device->polls,
                'responded_pct'  => $device->responded_percentage,
                'missed_pct'     => $device->missed_percentage,
                'down_time'      => $device->down_time,
                'avg_delay'      => $device->avg_delay,
                'min_delay'      => $device->min_delay,
                'max_delay'      => $device->max_delay,
                'period'         => $device->period,
                'alerts_count'   => (int) $device->alerts_count,
                'last_poll'      => $lastLog?->checked_at?->format('d/m/y H:i:s'),
            ],
            'logs' => $logs->map(fn (MonitoringLog $log) => [
                'time'    => $log->checked_at?->format('d/m/y H:i:s'),
                'status'  => $log->status,
                'message' => match ($log->status) {
                    'up'      => $log->response_time !== null
                        ? 'Respon ' . $log->response_time . ' ms'
                        : 'Device up',
                    'down'    => 'Timeout',
                    'warning' => 'Latensi tinggi',
                    default   => strtoupper((string) $log->status),
                },
            ])->values(),
        ];
    }
}