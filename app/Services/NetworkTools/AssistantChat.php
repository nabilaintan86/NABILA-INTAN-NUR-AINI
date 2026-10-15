<?php

namespace App\Services\NetworkTools;

/**
 * Chat "NOC AI Chat" di tab AI Assistant halaman Network Tools.
 *
 * Tugasnya menjawab pertanyaan engineer secara tanya-jawab: "kenapa target
 * down?", "apa solusinya?", "gimana latency-nya?", "gimana cara kerja sistem
 * ini?", dan sejenisnya, ketika jaringan bermasalah atau sedang dilakukan
 * troubleshooting.
 *
 * Bentuk jawabannya aturan (rule-based), bukan model luar:
 *
 *  1. Tanpa API key, tanpa biaya per pesan, dan tanpa jeda jaringan —
 *     pertanyaan yang menyangkut kondisi darurat jangan menunggu layanan
 *     eksternal.
 *  2. Jawaban yang menyangkut kondisi dibaca dari KONTEKS hasil ping
 *     terakhir yang dikirim halaman, dan saran tindakannya dihasilkan
 *     oleh NetworkAssistant yang sama dengan kartu asisten, jadi chat dan
 *     kartu tidak pernah memberi anjuran yang berbeda.
 *  3. Jawaban tentang cara kerja sistem / fitur / istilah jaringan tidak
 *     butuh konteks ping sama sekali, sehingga tetap terjawab sebelum
 *     pemeriksaan dijalankan.
 *  4. Kelas ini murni: tidak menyentuh database, tidak menjalankan
 *     perintah, dan tidak pernah melempar exception.
 *
 * Topiknya dipilih berurutan dari yang paling spesifik ke yang paling umum
 * supaya satu pertanyaan yang mengandung beberapa kata kunci tetap
 * dijawab dengan yang paling diinginkan penanya.
 */
final class AssistantChat
{
    /** Panjang pertanyaan yang masih dilayani ( karakter ). */
    private const MAX_QUESTION = 500;

    private NetworkAssistant $assistant;

    public function __construct()
    {
        $this->assistant = new NetworkAssistant;
    }

    /**
     * @param  array<string, mixed>  $context  ringkasan hasil ping terakhir dari halaman
     * @return array{answer: string, topic: string}
     */
    public function answer(string $question, array $context = []): array
    {
        $q = $this->normalise($question);
        $state = $this->state($context);

        if ($q === '') {
            return $this->reply('fallback', 'Tulis pertanyaannya dulu, contoh: "kenapa target down?"');
        }

        if ($this->isGreeting($q)) {
            return $this->reply('greeting', $this->greeting($state));
        }

        if ($this->mentions($q, ['apa itu', 'apa yang dimaksud', 'definisi', 'pengertian', 'apa fungsi', 'apa gunanya', 'apa maksud', 'apa arti'])) {
            return $this->reply('definition', $this->definition($q));
        }

        if ($this->mentions($q, ['bisa apa', 'apa saja yang bisa', 'bantuan', 'cara pakai', 'gimana cara pake', 'help', 'fitur', 'kemampuan', 'bisa ngapain'])) {
            return $this->reply('capabilities', $this->capabilities());
        }

        if ($this->mentions($q, ['solusi', 'saran', 'rekomendasi', 'perbaiki', 'mengatasi', 'apa yang harus', 'harus diapakan', 'langkah', 'tindakan', 'mitigasi', 'ngapain', 'diapain', 'handling', 'penanganan', 'obat', 'diperbaiki', 'pemulihan', 'recovery'])) {
            return $this->reply('solution', $this->solution($state));
        }

        if ($this->mentions($q, ['kenapa', 'mengapa', 'penyebab', 'masalahnya', 'error', 'gagal', 'down', 'mati', 'putus', 'gangguan', 'tidak merespons', 'tidak bisa diakses', 'timeout', 'hilang', 'lost', 'internet', 'sinyal', 'wifi', 'lelet', 'lola', 'lemot', 'lambat', 'disconnect', 'macet', 'overload', 'keluhan', 'komplain', 'eror', 'rusak', 'server'])) {
            return $this->reply('cause', $this->cause($state));
        }

        if ($this->mentions($q, ['status', 'kondisi', 'sekarang', 'terkini', 'terakhir', 'normal', 'gimana kabar', 'masih hidup', 'masih jalan'])) {
            return $this->reply('status', $this->status($state));
        }

        if ($this->mentions($q, ['gateway', 'router', 'modem', 'onu', 'olt', 'uplink'])) {
            return $this->reply('gateway', $this->gateway($state));
        }

        if ($this->mentions($q, ['kabel', 'serat', 'fiber', 'utp', 'colokan', 'pon', 'lampu', 'power supply'])) {
            return $this->reply('cable', $this->cable($state));
        }

        if ($this->mentions($q, ['latency', 'latensi', 'delay', 'lambat', 'lemot', 'respons lama'])) {
            return $this->reply('latency', $this->latency($state));
        }

        if ($this->mentions($q, ['packet loss', 'paket hilang', 'paket tidak sampai', 'loss'])) {
            return $this->reply('loss', $this->loss($state));
        }

        if ($this->mentions($q, ['jitter', 'fluktuatif', 'tidak stabil'])) {
            return $this->reply('jitter', $this->jitter($state));
        }

        if ($this->mentions($q, ['cara kerja', 'sistem ini', 'tools ini', 'menu ini', 'halaman ini', 'aplikasi ini', 'website ini', 'siapa kamu', 'kamu siapa', 'siapa anda', 'anda siapa', 'dipakai untuk apa', 'untuk apa ini', 'gimana cara kerjanya', 'kenapa ada fitur'])) {
            return $this->reply('system', $this->system($q, $state));
        }

        if ($this->mentions($q, ['hop', 'lompatan', 'traceroute', 'jalur', 'rute', 'route'])) {
            return $this->reply('path', $this->path($state));
        }

        return $this->reply('fallback', $this->fallback());
    }

    /* ══ JAWABAN PER TOPIK ═══════════════════════════════════ */

    private function greeting(array $state): string
    {
        return 'Halo, siap membantu. '.self::CAPABILITIES_LINE
            .($this->hasResult($state)
                ? 'Hasil ping terakhir untuk '.$this->target($state).' sudah saya pegang, jadi tinggal tanyakan status atau solusinya.'
                : 'Jalankan ping pada tab di atas dulu supaya jawabannya menyentuh kondisi jaringan yang sebenarnya.');
    }

    private function capabilities(): string
    {
        return self::CAPABILITIES_LINE
            .'Halaman Network Tools punya tiga tab: Ping Server Radnet, Ping Server Luar, dan AI Assistant '
            .'(tempat chat ini). Contoh pertanyaan: "status sekarang gimana?", "kenapa down?", "apa solusinya?", '
            .'"gimana latency-nya?", "gateway hidup nggak?", "apa itu packet loss?", '
            .'"gimana cara kerja sistem ini?", dan "apa saja fitur halaman ini?".';
    }

    private function status(array $state): string
    {
        if (! $this->hasResult($state)) {
            return 'Belum ada hasil pengecekan terbaru. Jalankan ping pada tab di atas dulu, '
                .'lalu tanya lagi — saya membaca angka dari hasil itu, bukan menebak.';
        }

        $label = match ($state['status']) {
            'down' => 'DOWN (gangguan total)',
            'warning' => 'WARNING (terindikasi masalah)',
            'up' => 'UP (normal)',
            default => 'belum terbaca',
        };

        $answer = 'Status terakhir untuk '.$this->target($state).': '.$label.' — '.$this->numbers($state).'.';

        $state['level'] === 'ok'
            ? $answer .= ' Jalur terlihat bersih; lanjutkan pemantauan berkala.'
            : $answer .= ' Lihat kotak saran di atas untuk langkah penanganannya, atau tanya "apa solusinya?".';

        if ($state['checked_at'] !== null) {
            $answer .= ' Dicek pukul '.$state['checked_at'].'.';
        }

        return $answer;
    }

    private function cause(array $state): string
    {
        if (! $this->hasResult($state)) {
            return 'Belum ada hasil pengecekan untuk dianalisis penyebabnya. Jalankan ping dulu — '
                .'umumnya gangguan di jaringan NOC berasal dari jalur fisik (kabel/serat), gateway yang kehilangan rute, '
                .'atau perangkat target yang mati.';
        }

        if ($state['level'] === 'ok') {
            return 'Dari hasil terakhir, sebenarnya tidak ada gangguan: '.$this->numbers($state).'. '
                .'Kalau ada laporan gangguan di sisi user, kemungkinan masalahnya di segmentasi jaringan mereka, '
                .'bukan di jalur yang saya uji ini.';
        }

        if ($state['level'] === 'danger') {
            if ($state['gateway_reachable'] === false) {
                return 'Indikasi kuat gangguan di jalur fisik menuju gateway: gateway '
                    .$this->gatewayName($state).' tidak membalas paket sama sekali, padahal server NOC masih '
                    .'bisa mengirim. Periksa kabel, konektor, dan LED PON/uplink di titik itu lebih dulu.';
            }

            if ($state['gateway_reachable'] === true) {
                return 'Gateway '.$this->gatewayName($state).' masih hidup, tetapi target '
                    .$this->target($state).' tidak merespons ('.$this->numbers($state).'). '
                    .'Artinya jalur putus SETELAH gateway: bisa di perangkat antara gateway dan target, '
                    .'atau targetnya sendiri yang mati.';
            }

            return 'Hasil pengecekan menunjukkan gangguan pada jalur: '.$this->numbers($state).'. '
                .'Penyebabnya paling sering kabel/serat putus, gateway kehilangan rute, atau perangkat target mati.';
        }

        return 'Belum berstatus down, tapi kualitas jalur menurun: '.$this->numbers($state).'. '
            .'Penyebabnya biasanya trafik menumpuk (bottleneck) atau paket hilang di salah satu titik antrean.';
    }

    private function solution(array $state): string
    {
        if (! $this->hasResult($state)) {
            return 'Jalankan ping dulu supaya saran saya menyasar masalah yang nyata. '
                .'Untuk kasus umum, urutan penanganannya: 1) cek kabel dan lampu LED perangkat, '
                .'2) pastikan gateway masih punya rute, 3) ulangi pengecekan, 4) kalau tetap down, naikkan ke tim lapangan.';
        }

        $advice = $this->assistant->fromMetrics(
            $state['status'],
            $state['loss'],
            $state['latency'],
            $state['jitter'],
        );

        $lines = [];
        foreach ($advice['actions'] as $index => $action) {
            $lines[] = ($index + 1).') '.$action;
        }

        return $advice['title'].' untuk '.$this->target($state).' — '.$advice['message']
            ."\n".implode("\n", $lines);
    }

    private function gateway(array $state): string
    {
        if ($state['gateway'] === null) {
            return 'Alamat gateway belum terbaca dari hasil pengecekan terakhir'
                .($this->hasResult($state)
                    ? ', kemungkinan perintah rute tidak tersedia di server ini.'
                    : ' karena belum ada hasil pengecekan. Jalankan ping dulu.')
                .' Jalankan ping pada tab di atas, lalu tanya lagi.';
        }

        if ($state['gateway_reachable'] === true) {
            return 'Gateway '.$state['gateway'].' hidup dan merespons '.self::num($state['gateway_latency'] ?? null).' ms. '
                .'Artinya segmen NOC → gateway sehat; kalau target bermasalah, perhatikan segmen setelah gateway.';
        }

        if ($state['gateway_reachable'] === false) {
            return 'Gateway '.$state['gateway'].' TIDAK merespons paket. Gangguan sudah terjadi di segmen ini, '
                .'sebelum sampai ke target — periksa kabel, ONU/OLT, dan daya perangkat di jalur itu.';
        }

        $gateway = $state['gateway'] ?? '';

        return 'Gateway terakhir yang dipakai adalah '.$gateway.' (status pemeriksaannya belum tercatat).';
    }

    private function cable(array $state): string
    {
        $advice = $this->assistant->fromMetrics($state['status'], $state['loss'], $state['latency'], $state['jitter']);

        return 'Untuk kendala kabel/colokan/LED, urutan cepatnya: 1) lihat LED PON dan uplink — '
            .'mati berarti segmen itu putus, 2) cabut-pasang konektor serat/UTP dan pastikan dudukannya rata, '
            .'3) cek power supply perangkat, 4) jalankan ulang ping dari tab di atas.'
            .($this->hasResult($state) ? "\nCatatan hasil terakhir: ".$advice['message'] : '');
    }

    private function latency(array $state): string
    {
        $answer = 'Latensi adalah waktu tunggu dari kirim paket sampai balasan diterima. '
            .'Batas normal di jaringan ini '.self::num(DiagnosisAnalyzer::GOOD_LATENCY_MS).' ms; '
            .'di atas '.self::num(DiagnosisAnalyzer::POOR_LATENCY_MS).' ms sudah berat.';

        if ($state['latency'] !== null) {
            $answer .= ' Hasil terakhir untuk '.$this->target($state).' adalah '
                .self::num($state['latency']).' ms — '
                .($state['latency'] > DiagnosisAnalyzer::GOOD_LATENCY_MS
                    ? 'di atas batas normal, jadi ada yang menumpuk di jalur (bottleneck).'
                    : 'masih wajar.');
        } elseif ($this->hasResult($state)) {
            $answer .= ' Angka latensi pada hasil terakhir tidak terbaca.';
        } else {
            $answer .= ' Belum ada hasil terbaru — jalankan ping dulu untuk angka jaringan Anda.';
        }

        return $answer;
    }

    private function loss(array $state): string
    {
        $answer = 'Packet loss adalah paket yang dikirim tapi tidak pernah kembali. '
            .'DiagnosisAnalyzer memakai ambang '.self::num(DiagnosisAnalyzer::MINOR_LOSS_PCT)
            .'% (ringan) dan '.self::num(DiagnosisAnalyzer::MAJOR_LOSS_PCT).'% (berat).';

        if ($state['loss'] !== null) {
            $answer .= ' Hasil terakhir: '.self::num($state['loss']).'% — '
                .($state['loss'] >= DiagnosisAnalyzer::MAJOR_LOSS_PCT
                    ? 'sudah berat, ini pola putusnya jalur fisik / gateway, bukan sekadar sibuk.'
                    : ($state['loss'] > 0 ? 'masih kecil, pantau ulang beberapa menit lagi.' : 'bersih, tidak ada paket hilang.'));
        } elseif (! $this->hasResult($state)) {
            $answer .= ' Belum ada hasil terbaru — jalankan ping dulu.';
        }

        return $answer;
    }

    private function jitter(array $state): string
    {
        $answer = 'Jitter adalah selisih latensi antar paket; makin besar, makin tidak stabil rasanya di aplikasi '
            .'(telepon, video, VPN). Ambang wajar di sini '.self::num(DiagnosisAnalyzer::JITTER_MS).' ms.';

        if ($state['jitter'] !== null) {
            $answer .= ' Hasil terakhir '.self::num($state['jitter']).' ms — '
                .($state['jitter'] > DiagnosisAnalyzer::JITTER_MS
                    ? 'melebihi ambang, biasanya karena antrean antarmuka penuh atau jalur dipakai bersamaan.'
                    : 'masih aman.');
        } elseif (! $this->hasResult($state)) {
            $answer .= ' Belum ada hasil terbaru — jalankan ping dulu.';
        }

        return $answer;
    }

    private function path(array $state): string
    {
        if (! $this->hasResult($state)) {
            return 'Untuk melihat lompatan paket, jalankan ping dulu — kartu Hop Route Mapper di bawah akan '
                .'menggambar Server NOC → Gateway → Target beserta latensinya.';
        }

        $line = 'Jalur terakhir: Server NOC'
            .($state['gateway'] !== null ? ' → gateway '.$state['gateway'] : '')
            .' → '.$this->target($state).' ('.$this->numbers($state).').';

        if ($state['gateway_reachable'] === false) {
            $line .= ' Gateway tidak merespons, jadi lompatan pertama bermasalah — lihat kartu Hop Route Mapper untuk titiknya.';
        } else {
            $line .= ' Klik titik/garis pada kartu Hop Route Mapper untuk rincian tiap lompatan.';
        }

        return $line;
    }

    /**
     * Cara kerja halaman/layanan ini: dijawab dari pengetahuan tentang
     * sistem sendiri, bukan dari konteks ping, sehingga tetap benar walau
     * belum ada pemeriksaan yang dijalankan.
     */
    private function system(string $q, array $state): string
    {
        $lines = ['Halaman Network Tools punya tiga tab: Ping Server Radnet, Ping Server Luar, '
            .'dan tab AI Assistant tempat chat ini berada.'];

        if ($this->mentions($q, ['ping'])) {
            $lines[] = 'Ping: server menjalankan perintah ping ICMP ke target (bukan browser yang bekerja), '
                .'lalu DiagnosisAnalyzer membaca packet loss, latensi, dan jitter untuk menentukan status '
                .'UP/WARNING/DOWN — ambangnya '.self::num(DiagnosisAnalyzer::GOOD_LATENCY_MS).' ms wajar, '
                .self::num(DiagnosisAnalyzer::POOR_LATENCY_MS).' ms berat, dan '
                .self::num(DiagnosisAnalyzer::MAJOR_LOSS_PCT).'% loss dianggap jalur putus.';
        }

        if ($this->mentions($q, ['traceroute', 'tracepath', 'hop', 'lompatan', 'jalur', 'rute'])) {
            $lines[] = 'Hop Route Mapper: jalur digambar dari perintah `ip -4 route get` (alamat asal + gateway) '
                .'ditambah satu ping singkat ke gateway, lalu ditampilkan sebagai Server NOC → Gateway → Target '
                .'beserta latensi tiap lompatan.';
        }

        if ($this->mentions($q, ['dns', 'domain', 'lookup', 'dig'])) {
            $lines[] = 'Lookup DNS: tab ping juga bisa memanggil `dig` untuk mengecek resolusi nama domain, '
                .'berguna kalau target berupa nama bukan IP.';
        }

        if ($this->mentions($q, ['nmap', 'scan', 'port'])) {
            $lines[] = 'Scan port: memakai `nmap` pada rentang port yang dipilih untuk melihat layanan yang '
                .'terbuka di perangkat target.';
        }

        if ($this->mentions($q, ['chat', 'asisten', 'ai', 'tanya'])) {
            $lines[] = 'Chat ini dijawab AssistantChat: kumpulan aturan lokal di server, tanpa API key dan tanpa '
                .'layanan AI luar, jadi masih menjawab walau internet sedang terputus. Kartu AI Network Assistant '
                .'di tab ping memakai NetworkAssistant dengan aturan yang sama, sehingga saran di chat dan saran '
                .'di kartu tidak pernah bertentangan.';
        }

        if ($this->mentions($q, ['riwayat', 'histori', 'log', 'bersihkan', 'hapus', 'catatan'])) {
            $lines[] = 'Riwayat: setiap pemeriksaan disimpan ke tabel monitoring_logs / ping_history beserta '
                .'ringkasan latency per perangkat, dan bisa dikosongkan lewat tombol "Bersihkan Semua" '
                .'(khusus super admin) tanpa menghapus daftar perangkat.';
        }

        if ($this->mentions($q, ['perangkat', 'daftar', 'devices', 'csv', 'ip'])) {
            $lines[] = 'Daftar perangkat: sumber resminya file devices.csv berisi IP statis (bukan DHCP), '
                .'lalu dipakai bersama oleh tab pilihan perangkat, dashboard, dan laporan.';
        }

        if (count($lines) === 1) {
            $lines[] = 'Alurnya: pilih target → jalankan ping → DiagnosisAnalyzer membaca packet loss/latensi/jitter, '
                .'lalu kartu AI (NetworkAssistant) mengubahnya jadi ringkasan + saran penanganan → tanya-jawab di '
                .'chat ini (AssistantChat, aturan lokal tanpa API key) bila butuh penjelasan lebih lanjut. '
                .'Karena aturannya lokal, jawabannya konsisten dan tetap tersedia saat jaringan sedang bermasalah.';
        }

        $lines[] = $this->hasResult($state)
            ? 'Hasil terakhir yang saya pegang: '.$this->target($state).' — '.$this->numbers($state).'.'
            : 'Belum ada hasil ping yang saya pegang; jalankan ping dulu supaya jawaban saya menyentuh kondisi nyata.';

        return implode("\n", $lines);
    }

    private function definition(string $q): string
    {
        if ($this->mentions($q, ['packet loss', 'loss'])) {
            return 'Packet loss: persentase paket yang dikirim tapi tidak sampai kembali. '
                .'Di sini '.self::num(DiagnosisAnalyzer::MINOR_LOSS_PCT).'% masih ringan, '
                .self::num(DiagnosisAnalyzer::MAJOR_LOSS_PCT).'% ke atas dianggap jalur putus.';
        }

        if ($this->mentions($q, ['jitter'])) {
            return 'Jitter: variasi waktu antar paket. Besarnya menandakan antrean/turbulensi di jalur; '
                .'ambang wajar '.self::num(DiagnosisAnalyzer::JITTER_MS).' ms.';
        }

        if ($this->mentions($q, ['latency', 'latensi', 'delay'])) {
            return 'Latency: waktu tunggu paket pergi–pulang (RTT). Normal di bawah '
                .self::num(DiagnosisAnalyzer::GOOD_LATENCY_MS).' ms, waspada di atas itu, berat di atas '
                .self::num(DiagnosisAnalyzer::POOR_LATENCY_MS).' ms.';
        }

        if ($this->mentions($q, ['gateway', 'rute', 'route'])) {
            return 'Gateway: perangkat pertama yang dilewati paket keluar dari server NOC menuju jaringan lain. '
                .'Kalau gateway hidup tapi target mati, masalahnya ada setelah gateway.';
        }

        if ($this->mentions($q, ['hop', 'lompatan'])) {
            return 'Hop: satu perpindahan paket antar perangkat di jalur. Makin banyak hop, makin banyak '
                .'titik yang bisa jadi sumber gangguan.';
        }

        if ($this->mentions($q, ['ping'])) {
            return 'Ping: mengirim paket ICMP ke target lalu mengukur waktunya balik. Status UP/WARNING/DOWN di '
                .'halaman ini diturunkan dari hasil ping — 100% loss berarti target atau jalurnya mati.';
        }

        if ($this->mentions($q, ['traceroute', 'tracepath'])) {
            return 'Traceroute: memetakan tiap perangkat yang dilewati paket menuju target. Kalau berhenti di satu '
                .'titik, titik itulah yang bermasalah — ini yang dilengkapi Hop Route Mapper di halaman ini.';
        }

        if ($this->mentions($q, ['dns'])) {
            return 'DNS: penerjemah nama domain jadi IP (misalnya nama layanan jadi 10.x.x.x). Kalau DNS bermasalah, '
                .'target pakai nama tidak akan terbaca walaupun jaringannya sehat.';
        }

        if ($this->mentions($q, ['dhcp'])) {
            return 'DHCP: layanan yang membagi IP otomatis ke perangkat yang join jaringan. Di proyek ini daftar '
                .'perangkat resmi memakai IP statis dari devices.csv, jadi IP tidak berubah-ubah sendiri.';
        }

        if ($this->mentions($q, ['vlan'])) {
            return 'VLAN: pemisahan jaringan logis pada kabel/port yang sama, misalnya VLAN layanan terpisah dari '
                .'VLAN pengelolaan. Salah VLAN sering bikin perangkat terlihat "hidup" tapi tidak bisa saling jangkau.';
        }

        if ($this->mentions($q, ['snmp'])) {
            return 'SNMP: protokol bawaan alat monitoring untuk membaca status dan trafik perangkat jarak jauh '
                .'tanpa harus login ke tiap perangkat.';
        }

        if ($this->mentions($q, ['bandwidth', 'kapasitas', 'throughput'])) {
            return 'Bandwidth: kapasitas maksimum jalur dalam satu waktu. Beda dengan latency — bandwidth besar '
                .'bikin transfer file cepat, latency kecil bikin respons terasa instan.';
        }

        if ($this->mentions($q, ['ip'])) {
            return 'IP: alamat perangkat di jaringan. Di proyek ini semua IP statis dan tercatat di devices.csv '
                .'(contoh default 192.168.93.59 untuk tab Radnet).';
        }

        if ($this->mentions($q, ['router', 'switch'])) {
            return 'Router meneruskan paket antar jaringan (dan jadi gateway pertama keluar dari NOC), switch '
                .'menghubungkan perangkat dalam satu jaringan lokal. Tanda umum rusak: LED port mati atau port '
                .'tetap up padahal tidak ada trafik.';
        }

        if ($this->mentions($q, ['onu', 'olt', 'pop', 'wifi'])) {
            return 'ONU/OLT adalah perangkat serat (fiber) di sisi pelanggan dan sisi penyedia; POP adalah titik '
                .'kumpul jaringan; WiFi adalah akses nirkabel ke jaringan lokal. Kalau keduanya mati, LED PON biasanya '
                .'ikut padam — itu yang disebut gangguan jalur fisik di saran asisten.';
        }

        return 'Istilah yang paling sering dipakai di sini: packet loss, latency, jitter, gateway, dan hop. '
            .'Sebut satu di antaranya, misalnya "apa itu jitter?";';
    }

    private function fallback(): string
    {
        return 'Maaf, pertanyaannya belum saya tangkap. '.self::CAPABILITIES_LINE
            .'Coba salah satu: "status sekarang gimana?", "kenapa down?", "apa solusinya?", '
            .'"gimana latency-nya?", "gateway hidup nggak?".';
    }

    /* ══ BAHAN: KONTEKS HASIL PING TERAKHIR ══════════════════ */

    private const CAPABILITIES_LINE = 'Saya bisa membantu seputar status jaringan terakhir, penyebab down/loss, '
        .'solusi penanganan, latency, jitter, gateway, cara kerja sistem ini, dan arti istilah jaringan. ';

    /**
     * Ambil hanya nilai yang dikenal dari konteks kiriman browser.
     *
     * Browser tidak dipercaya untuk menentukan bentuk jawaban: semua yang
     * dipakai dinormalisasi dulu (angka harus numerik, status harus dari
     * daftar yang sah, teks dipotong), sehingga konteks aneh pun tidak
     * membuat jawaban jadi aneh.
     *
     * @param  array<string, mixed>  $context
     * @return array{target:?string, status:?string, level:?string, loss:?float, latency:?float, jitter:?float, gateway:?string, gateway_reachable:?bool, gateway_latency:?float, checked_at:?string}
     */
    private function state(array $context): array
    {
        $number = function (mixed $value): ?float {
            return is_numeric($value) ? (float) $value : null;
        };

        $text = function (mixed $value, int $max = 255): ?string {
            return is_string($value) && trim($value) !== ''
                ? mb_substr(trim($value), 0, $max)
                : null;
        };

        $level = $context['level'] ?? null;
        $status = $context['status'] ?? null;

        return [
            'target' => $text($context['target'] ?? null),
            'status' => is_string($status) && in_array($status, ['up', 'warning', 'down', 'info'], true) ? $status : null,
            'level' => is_string($level) && in_array($level, ['ok', 'warning', 'danger'], true) ? $level : null,
            'loss' => $number($context['packet_loss_pct'] ?? null),
            'latency' => $number($context['latency_ms'] ?? null),
            'jitter' => $number($context['jitter_ms'] ?? null),
            'gateway' => $text($context['gateway'] ?? null),
            'gateway_reachable' => is_bool($context['gateway_reachable'] ?? null) ? $context['gateway_reachable'] : null,
            'gateway_latency' => $number($context['gateway_latency_ms'] ?? null),
            'checked_at' => $text($context['checked_at'] ?? null, 32),
        ];
    }

    /**
     * @param  array{target:?string, status:?string, level:?string, loss:?float, latency:?float, jitter:?float, gateway:?string, gateway_reachable:?bool, gateway_latency:?float, checked_at:?string}  $state
     */
    private function hasResult(array $state): bool
    {
        return $state['level'] !== null
            || $state['status'] !== null
            || $state['loss'] !== null
            || $state['latency'] !== null;
    }

    /**
     * @param  array{target:?string, status:?string, level:?string, loss:?float, latency:?float, jitter:?float, gateway:?string, gateway_reachable:?bool, gateway_latency:?float, checked_at:?string}  $state
     */
    private function target(array $state): string
    {
        return $state['target'] ?? 'target yang diperiksa';
    }

    /**
     * @param  array{target:?string, status:?string, level:?string, loss:?float, latency:?float, jitter:?float, gateway:?string, gateway_reachable:?bool, gateway_latency:?float, checked_at:?string}  $state
     */
    private function gatewayName(array $state): string
    {
        return $state['gateway'] ?? 'gateway';
    }

    /**
     * @param  array{target:?string, status:?string, level:?string, loss:?float, latency:?float, jitter:?float, gateway:?string, gateway_reachable:?bool, gateway_latency:?float, checked_at:?string}  $state
     */
    private function numbers(array $state): string
    {
        $parts = [];

        $state['loss'] !== null && $parts[] = 'packet loss '.self::num($state['loss']).'%';
        $state['latency'] !== null && $parts[] = 'latensi '.self::num($state['latency']).' ms';
        $state['jitter'] !== null && $parts[] = 'jitter '.self::num($state['jitter']).' ms';

        return $parts === [] ? 'tanpa angka terukur' : implode(', ', $parts);
    }

    /* ══ PEMBANTU TEKS ═══════════════════════════════════════ */

    private function normalise(string $question): string
    {
        $question = trim(preg_replace('/\s+/u', ' ', $question) ?? '');

        return mb_strtolower(mb_substr($question, 0, self::MAX_QUESTION));
    }

    /**
     * Cocokkan kata kunci dengan batas kata, supaya "up" tidak ikut
     * cocok di dalam kata "group" dan sejenisnya.
     *
     * Akhiran umum bahasa Indonesia (nya/kah/ku/mu/an/kan) ikut diizinkan
     * supaya "apa solusinya?" atau "gimana statusnya?" tetap cocok dengan
     * kata kunci "solusi" dan "status" — pertanyaan operator ditulis apa
     * adanya, bukan disesuaikan dengan daftar ini.
     *
     * @param  list<string>  $words
     */
    private function mentions(string $q, array $words): bool
    {
        foreach ($words as $word) {
            if (preg_match('/\b'.preg_quote($word, '/').'(?:nya|kah|ku|mu|an|kan)?\b/u', $q) === 1) {
                return true;
            }
        }

        return false;
    }

    private function isGreeting(string $q): bool
    {
        if (mb_strlen($q) > 30) {
            return false;
        }

        return $this->mentions($q, ['hai', 'halo', 'hello', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum'])
            || $q === 'hi'
            || $q === 'hey';
    }

    private static function num(float|int|null $value): string
    {
        return $value === null ? '—' : number_format((float) $value, 1, ',', '.');
    }

    /**
     * @return array{answer: string, topic: string}
     */
    private function reply(string $topic, string $answer): array
    {
        return ['answer' => $answer, 'topic' => $topic];
    }
}
