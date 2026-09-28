<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceConnection;
use App\Models\MapSetting;
use App\Models\MonitoringLog;
use App\Models\NetworkArea;

/**
 * Sumber tunggal topology jaringan "RADNET Wireless Network 2026".
 *
 * Menghasilkan payload topology yang SAMA untuk semua halaman
 * (admin.monitoring) sehingga konsisten.
 *
 * Layout PANEL/TILE (referensi visual WhatsUp):
 *  - Seluruh group dipadatkan dalam satu area map yang menyatu (bukan kolom
 *    vertikal panjang; bukan stack yang membuat pop kecil jatuh ke bawah).
 *  - Setiap group adalah PANEL yang disusun berdampingan secara horizontal;
 *    bila baris penuh lanjut ke baris berikutnya yang dekat. Group kecil
 *    mengisi ruang kosong di antara group besar.
 *  - Device di dalam tiap panel disusun GRID beberapa kolom/baris sehingga
 *    panel tidak memanjang ke bawah (icon tetap besar, gap antar-device kecil).
 *  - Canvas (viewBox) mengikuti bounding box hasil packing + margin kecil.
 *
 * NAMA AREA memakai penamaan awal: POP SMK-YPM, POP Yapalis, GRUP HIJAU.
 */
class NetworkTopologyService
{
    /**
     * ANGGOTA device per area (nama area -> [id device]). Menentukan isi tiap
     * panel; posisi device DI DALAM panel dihitung otomatis (grid kompak) saat
     * build. Nama area memakai penamaan awal.
     */
    private array $areaDevices = [
        // Posisi internal dihitung otomatis (grid); urutan di sini hanya pengelompokan.
        'Plaza BRI'    => [8, 51, 52, 24, 46, 82, 25, 26],
        'Bumi Mandiri' => [78, 79, 80, 81],
        'Intiland'     => [2, 12, 37, 42, 83, 58, 3, 44, 59, 53, 5, 65, 66, 55, 56, 4, 6, 7],
        'EPON-BU'      => [85, 86, 87],

        'POP SMK-YPM'  => [10, 11, 19, 20, 21, 38, 39, 40, 43, 62, 63, 71, 88, 89, 90, 91],
        'POP Sham FM'  => [92, 48, 57, 113, 47, 68, 114, 69, 70, 115, 49, 84, 45, 112],
        'POP SMK8'     => [72, 73, 74],
        'POP Rajasa'   => [61, 67, 77],
        'POP Gresik'   => [1],

        'POP Yapalis'  => [93, 94, 13, 96],
        'POP SMK2'     => [18, 50],
        'POP Vuri'     => [75, 76],
        'POP SMKN 1'   => [108, 109, 110, 111],
        'GRUP HIJAU'   => [64, 16, 36, 15, 14, 17, 106, 105, 22, 23, 95, 104, 107, 101, 102, 103, 98, 99, 100, 9, 60, 97],

        'Hotel Bumi'   => [27, 28, 29, 30, 31, 32, 33, 34, 35, 54],
    ];

    /**
     * REGISTRY id device yang TURUT dalam topology (key = id record `device`).
     * Nilai [x, y] DI BAWAH INI ADALAH LEGACY (koordinat relatif layout lama)
     * dan SEKARANG TIDAK DIPAKAI: posisi device dihitung ulang otomatis dalam
     * grid kompak saat build. Array ini hanya menjadi daftar id yang dimuat.
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

        // ---- POP SMK-YPM : star dari POP YPM (hub tengah), chain AP Yakult di kiri-bawah ----
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

        // ---- POP Yapalis : chain POP Mjkt -> To SMK Nasional -> Pop Yapalis ----
        93  => [980, 200],  // POP Mjkt
        94  => [980, 360],  // To SMK Nasional
        13  => [980, 520],  // Pop Yapalis
        96  => [1150, 520], // pop Yapalis via ypm

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

        // ---- GRUP HIJAU : jajaran switch SW 35.x berbaris rapi ----
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
        28  => [2080, 290], // Core
        35  => [2080, 450], // LT 19
    ];

    /**
     * Parameter LAYOUT PANEL/TILE (hanya tampilan, tidak menyentuh data):
     *  - DEVICE_DX / DEVICE_DY : pitch antar-device di dalam panel (grid).
     *    Icon tetap besar (44) namun jarak antar-device dikecilkan agar panel
     *    tidak memanjang ke bawah (label nama di bawah icon tetap muat).
     *  - AREA_PAD_* / AREA_MIN_W : padding internal & lebar minimum panel.
     *    PAD_TOP cukup besar agar header/tag POP tidak bertabrakan dengan
     *    icon baris pertama (icon mulai di y+PAD_TOP-22 setelah tag).
     *  - AREA_ICON : diameter icon device (sinkron dgn renderer ICON=44)
     *    dipakai agar lebar panel SELALU membungkus icon paling kiri/kanan.
     *  - AREA_GAP_* / AREA_MARGIN : jarak antar panel & margin luar canvas.
     */
    private const DEVICE_DX    = 70;
    private const DEVICE_DY    = 86;
    private const AREA_ICON    = 44;
    private const AREA_PAD_LR  = 16;
    private const AREA_PAD_TOP = 70;
    private const AREA_PAD_BOT = 56;
    private const AREA_MIN_W   = 96;
    private const AREA_GAP_X   = 16;
    private const AREA_GAP_Y   = 20;
    private const AREA_MARGIN  = 20;

    /**
     * LEBAR GRID device per area (MANUAL, terkontrol).
     * Jumlah baris dihitung otomatis = ceil(device / kolom). GRUP HIJAU (2 kolom
     * x 11 baris) & Hotel Bumi (2 kolom) sengaja dibuat 2 kolom agar berbentuk
     * TRUK JULUR KE BAWAH dan menutup sisi kanan map, bukan melebar ke kanan.
     * Truk-truk BAWAH (EPON-BU, SMK8, Rajasa, Sham FM, SMK-YPM) dibuat kolom
     * sempit (1-5 kolom) agar berjajar ke BAWAH, semua RATA BAWAH pada satu
     * garis lurus.
     */
    private const GRID_COLS = [
        'Plaza BRI'    => 4,
        'Bumi Mandiri' => 1,
        'Intiland'     => 4,
        'EPON-BU'      => 1,
        'POP SMK-YPM'  => 3,
        'POP Sham FM'  => 5,
        'POP SMK8'     => 1,
        'POP Rajasa'   => 1,
        'POP Gresik'   => 1,
        'POP Yapalis'  => 2,
        'POP SMK2'     => 2,
        'POP Vuri'     => 1,
        'POP SMKN 1'   => 1,
        'GRUP HIJAU'   => 2,
        'Hotel Bumi'   => 2,
    ];

    /**
     * POSISI ABSOLUT semua box (prioritas di atas packing otomatis), sehingga
     * tata letak TERKONTROL penuh: konsisten, tanpa overlap, jarak antar POP
     * seragam (GAP_X=16 / GAP_Y=20). Struktur dipertahankan:
     *  - Baris atas (y=18) sejajar: Plaza BRI, Intiland, POP Yapalis, POP SMK2,
     *    GRUP HIJAU, Hotel Bumi (tembok kanan, SAMA PANJANG via HEIGHT_SYNC).
     *  - Kolom kedua/tengah: Bumi Mandiri->Gresik->EPON (kiri), Vuri (bawah
     *    Intiland), SMKN 1 (bawah Yapalis), SMK-YPM (bawah SMK2, menjulang).
     *  - Truk BAWAH (EPON-BU, SMK8, Rajasa, Sham FM) RATA BAWAH satu garis.
     * Koordinat = posisi absolut canvas (x, y); lebar/tinggi dihitung otomatis
     * dari isi device. Urutan group tidak berubah.
     */
    private const POSITION_OVERRIDE = [
        // Baris atas (y=18), sejajar atas.
        'Plaza BRI'    => [18, 18],
        'Intiland'     => [320, 18],
        'POP Yapalis'  => [622, 18],
        'POP SMK2'     => [788, 18],
        // Tembok kanan - HIJAU & Hotel SAMA PANJANG, menutup sisi kanan.
        'GRUP HIJAU'   => [1020, 18],
        'Hotel Bumi'   => [1182, 18],
        // Kolom kiri: Bumi Mandiri -> Gresik -> EPON (isi penuh ke bawah).
        'Bumi Mandiri' => [18, 250],
        'POP Gresik'   => [18, 654],
        'EPON-BU'      => [18, 800],
        // Bawah Intiland -> Vuri.
        'POP Vuri'     => [320, 508],
        // Bawah Yapalis -> SMKN 1.
        'POP SMKN 1'   => [622, 250],
        // Bawah SMK2 -> SMK-YPM menjulang; sejajar kiri dgn SMK2.
        'POP SMK-YPM'  => [788, 164],
        // Truk bawah, RATA BAWAH pada garis yang sama.
        'POP SMK8'     => [152, 800],
        'POP Rajasa'   => [294, 800],
        'POP Sham FM'  => [452, 800],
    ];

    /**
     * Nama area => area referensi: tinggi KOTAK panel dipaksa SAMA dengan area
     * referensi. Dipakai agar Hotel Bumi SAMA PANJANG dengan GRUP HIJAU.
     */
    private const HEIGHT_SYNC = [
        'Hotel Bumi' => 'GRUP HIJAU',
    ];

    /**
     * Kotak AREA (slot) hasil perhitungan (diisi saat build).
     * name => [x, y, w, h] dalam koordinat tampilan absolut.
     */
    private array $areaLayout = [];

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
     * Bangun payload topology (layout PANEL/TILE, referensi WhatsUp).
     *
     * 1. Device tiap area disusun GRID kompak (beberapa kolom/baris), hub
     *    (degree tertinggi) di pojok kiri-atas. Group tidak lagi jadi kolom
     *    vertikal panjang.
     * 2. Panel-panel group di-PACKING: disusun horizontal; bila baris penuh
     *    lanjut baris berikutnya di dekat baris sebelumnya. Group tertinggi
     *    diproses lebih dulu agar tak ada POP kecil jatuh ke bawah.
     * 3. Node diterjemahkan ke koordinat tampilan; area via klasifikasi
     *    geometris (areaFor). Edge/hub/status/data TIDAK diubah.
     */
    public function build(): array
    {
        // 1. Ambil seluruh model NetworkArea aktif dari database
        $dbAreas = NetworkArea::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $areasByName = [];
        $areasById = [];
        foreach ($dbAreas as $a) {
            $areasByName[$a->name] = $a;
            $areasById[$a->id] = $a;
        }

        // 2. Ambil seluruh perangkat
        $devices = Device::all()->keyBy('id');

        // 3. Ambil seluruh relasi koneksi
        $rawConnections = DeviceConnection::all()
            ->filter(fn ($c) => isset($devices[$c->from_device_id]) && isset($devices[$c->to_device_id]));

        $degree = [];
        $edges = [];
        foreach ($rawConnections as $c) {
            $fromId = (int) $c->from_device_id;
            $toId = (int) $c->to_device_id;
            $direction = $c->direction ?: 'forward';

            $edges[] = [
                'id'        => (int) $c->id,
                'from'      => $fromId,
                'to'        => $toId,
                'type'      => $c->connection_type ?: 'ethernet',
                'direction' => $direction,
            ];
            $degree[$fromId] = ($degree[$fromId] ?? 0) + 1;
            $degree[$toId]   = ($degree[$toId] ?? 0) + 1;
        }

        // 4. Petakan perangkat ke masing-masing area
        $areaDevicesMap = [];
        foreach ($dbAreas as $ar) {
            $areaDevicesMap[$ar->name] = [];
        }

        foreach ($devices as $dev) {
            $assignedAreaName = null;

            if ($dev->network_area_id && isset($areasById[$dev->network_area_id])) {
                $assignedAreaName = $areasById[$dev->network_area_id]->name;
            } elseif ($dev->pop_name && isset($areasByName[$dev->pop_name])) {
                $assignedAreaName = $dev->pop_name;
            } else {
                // Cek fallback legacy dictionary $this->areaDevices
                foreach ($this->areaDevices as $legacyName => $legacyIds) {
                    if (in_array($dev->id, $legacyIds, true)) {
                        $assignedAreaName = $legacyName;
                        break;
                    }
                }
            }

            if ($assignedAreaName && isset($areaDevicesMap[$assignedAreaName])) {
                $areaDevicesMap[$assignedAreaName][] = $dev->id;
            } elseif ($assignedAreaName) {
                $areaDevicesMap[$assignedAreaName] = [$dev->id];
            }
        }

        // 5. Layout kotak area dan grid internal
        $grid = [];
        foreach ($areaDevicesMap as $name => $ids) {
            usort($ids, function ($a, $b) use ($degree) {
                return ($degree[$b] ?? 0) <=> ($degree[$a] ?? 0) ?: $a <=> $b;
            });

            $n = count($ids);
            $cols = self::GRID_COLS[$name] ?? max(1, (int) ceil(sqrt($n ?: 1)));
            $rows = max(1, (int) ceil($n / $cols));

            $points = [];
            foreach ($ids as $i => $id) {
                $points[$id] = [
                    ($i % $cols) * self::DEVICE_DX,
                    intdiv($i, $cols) * self::DEVICE_DY,
                ];
            }

            $areaModel = $areasByName[$name] ?? null;
            $defaultW = max(
                ($cols - 1) * self::DEVICE_DX + self::AREA_ICON + 2 * self::AREA_PAD_LR,
                14 + mb_strlen((string) $name) * 8 + 48,
                self::AREA_MIN_W
            );
            $defaultH = ($rows - 1) * self::DEVICE_DY + self::AREA_PAD_TOP + self::AREA_PAD_BOT;

            $grid[$name] = [
                'cols'   => $cols,
                'rows'   => $rows,
                'points' => $points,
                'w'      => $areaModel && $areaModel->width > 60 ? $areaModel->width : $defaultW,
                'h'      => $areaModel && $areaModel->height > 60 ? $areaModel->height : $defaultH,
                'x'      => $areaModel ? $areaModel->pos_x : 20,
                'y'      => $areaModel ? $areaModel->pos_y : 20,
            ];
        }

        // Box koordinat awal dari database
        $box = [];
        foreach ($grid as $name => $g) {
            $box[$name] = [
                'x' => $g['x'],
                'y' => $g['y'],
                'w' => $g['w'],
                'h' => $g['h'],
            ];
        }

        // Ambil layout custom dari MapSetting jika ada
        $customLayout = MapSetting::get('topology_2026_layout', []);
        if (!empty($customLayout['areas']) && is_array($customLayout['areas'])) {
            foreach ($customLayout['areas'] as $ar) {
                if (!empty($ar['name']) && isset($box[$ar['name']])) {
                    $box[$ar['name']]['x'] = (int) round($ar['x']);
                    $box[$ar['name']]['y'] = (int) round($ar['y']);
                    $box[$ar['name']]['w'] = (int) round($ar['w']);
                    $box[$ar['name']]['h'] = (int) round($ar['h']);
                }
            }
        }

        $this->areaLayout = [];
        foreach ($box as $name => $b) {
            $this->areaLayout[$name] = array_values($b);
        }

        // 6. Node di koordinat tampilan
        $nodes = [];
        foreach ($grid as $name => $g) {
            $b = $box[$name];
            $contentW = ($g['cols'] - 1) * self::DEVICE_DX;
            $ox = $b['x'] + max(20, ($b['w'] - $contentW) / 2);
            $oy = $b['y'] + self::AREA_PAD_TOP;

            foreach ($g['points'] as $id => [$lx, $ly]) {
                $device = $devices[$id] ?? null;
                if (! $device) {
                    continue;
                }

                if ($device->map_positioned && $device->pos_x !== null && $device->pos_y !== null) {
                    $x = (int) $device->pos_x;
                    $y = (int) $device->pos_y;
                } else {
                    $x = (int) round($ox + $lx);
                    $y = (int) round($oy + $ly);
                }

                $nodes[] = [
                    'id'       => (int) $id,
                    'name'     => $device->name,
                    'ip'       => $device->ip_address,
                    'icon'     => $device->icon_key,
                    'type'     => $device->display_type,
                    'protocol' => $device->display_protocol,
                    'status'   => $device->status,
                    'pop'      => $device->pop_name ?: $name,
                    'degree'   => $degree[$id] ?? 0,
                    'x'        => $x,
                    'y'        => $y,
                    'area'     => $name,
                ];
            }
        }

        // 7. Kelompokkan node per area
        $byArea = [];
        foreach ($nodes as $n) {
            $byArea[$n['area']][] = $n;
        }

        $areas = [];
        $maxRight = 1300;
        $maxBottom = 1100;

        foreach ($this->areaLayout as $name => [$bx, $by, $bw, $bh]) {
            $group = $byArea[$name] ?? [];
            $areaModel = $areasByName[$name] ?? null;

            $hubId = null;
            $max = -1;
            foreach ($group as $n) {
                if ($n['degree'] > $max) {
                    $max = $n['degree'];
                    $hubId = $n['id'];
                }
            }

            foreach ($group as &$n) {
                $n['isHub'] = $n['id'] === $hubId && $n['degree'] > 0;
            }
            unset($n);

            $maxRight = max($maxRight, $bx + $bw);
            $maxBottom = max($maxBottom, $by + $bh);

            $areas[] = [
                'id'           => $areaModel?->id,
                'name'         => $name,
                'code'         => $areaModel?->code,
                'x'            => $bx,
                'y'            => $by,
                'w'            => $bw,
                'h'            => $bh,
                'border_color' => $areaModel?->border_color ?: '#1a8024',
                'bg_color'     => $areaModel?->bg_color ?: '#f8fafc',
                'title_color'  => $areaModel?->title_color ?: '#1a8024',
                'nodes'        => array_values($group),
            ];
        }

        // Arrow settings
        $arrowSettings = $customLayout['arrow_settings'] ?? [
            'clearance'    => 28,
            'needle_size'  => 7,
            'stroke_width' => 2.2,
        ];

        return [
            'viewBox'       => sprintf(
                '0 0 %d %d',
                (int) ceil($maxRight + self::AREA_MARGIN),
                (int) ceil($maxBottom + self::AREA_MARGIN)
            ),
            'areas'         => $areas,
            'edges'         => $edges,
            'arrowSettings' => $arrowSettings,
            'isCustomized'  => !empty($customLayout),
        ];
    }

    /**
     * Pembagian area GEOMETRIS: nama area yang kotaknya memuat titik.
     *
     * Bila titik berada di dalam beberapa kotak sekaligus (kotak lokal yang
     * tumpang-tindih), dipilih kotak TERKECIL yang memuat titik dan yang
     * membersihkan ambiguitas dengan daftar anggota device ($areaDevices).
     *
     * @return string|null nama area, atau null bila di luar semua kotak.
     */
    private function areaFor(float $x, float $y, int $deviceId): ?string
    {
        $containing = [];
        foreach ($this->areaLayout as $name => [$ax, $ay, $aw, $ah]) {
            if ($x >= $ax && $x <= $ax + $aw && $y >= $ay && $y <= $ay + $ah) {
                $containing[$name] = $aw * $ah;
            }
        }

        if (count($containing) === 1) {
            return array_key_first($containing);
        }
        if (count($containing) < 1) {
            return null;
        }

        // Ambiguitas: pilih kotak terkecil yang memuat device di daftar anggota.
        asort($containing);
        foreach ($containing as $name => $_area) {
            if (in_array($deviceId, $this->areaDevices[$name], true)) {
                return $name;
            }
        }
        return array_key_first($containing);
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
                'type'       => $device->display_type,
                'protocol'   => $device->display_protocol,
                'pop_name'   => $device->pop_name,
                'status'     => $device->status,
                'icon_type'  => $device->icon_key,
                'deskripsi'  => $device->deskripsi,
                'links'      => $links,
            ],
            'stats' => [
                'type'           => $device->display_type,
                'protocol'       => $device->display_protocol,
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