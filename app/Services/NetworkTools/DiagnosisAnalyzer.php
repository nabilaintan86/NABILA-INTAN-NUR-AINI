<?php

namespace App\Services\NetworkTools;

/**
 * Menerjemahkan hasil perintah mentah (ping / trace / lookup / scan port)
 * menjadi satu status dan satu alasan bahasa Indonesia yang langsung bisa
 * dibaca manusia — gaya WhatsUp Gold.
 *
 * Alasan kelas ini ada:
 *
 *  1. Log terminal mentah itu bukti, tapi bukan kesimpulan. "100% packet
 *     loss" harus diterjemahkan jadi "perangkat ini tidak merespons", dan
 *     angka latency harus diterjemahkan jadi "responsnya lambat".
 *  2. Aturan penilaiannya (batas latency, batas packet loss, jitter) harus
 *     hidup di SATU tempat. Kalau tiap alat di halaman punya ambangnya
 *     sendiri, hasilnya pasti berbeda antar tombol dan tidak bisa diuji.
 *  3. Status di sini selalu punya arti yang sama di seluruh halaman:
 *
 *       UP      perangkat hidup dan merespons normal: tidak ada packet loss
 *                dan latency masih dalam batas wajar.
 *       WARNING perangkat masih hidup tapi ada yang bermasalah: latency
 *                tinggi, sedikit paket hilang, atau ada router yang diam di
 *                tengah jalur.
 *       DOWN    perangkat tidak merespons sama sekali: Request Timed Out,
 *                alat mati, atau kabel jalannya putus.
 *
 * Kelas ini murni: tidak menyentuh database, tidak menjalankan perintah, dan
 * tidak pernah melempar exception. Semua kegagalan di-cast menjadi status DOWN
 * dengan alasan yang bisa ditampilkan, jadi halaman tidak pernah gagal kosong.
 */
final class DiagnosisAnalyzer
{
    /** Respons (ms) yang masih dianggap normal untuk jaringan Radnet. */
    public const GOOD_LATENCY_MS = 100.0;

    /** Di atas nilai ini sudah tidak normal dan layak diperingatkan. */
    public const POOR_LATENCY_MS = 300.0;

    /** Selisih latency min–max (ms) yang menandakan jitter / delay. */
    public const JITTER_MS = 20.0;

    /** Packet loss (%) yang masih dianggap "sedikit", yaitu warning. */
    public const MINOR_LOSS_PCT = 1.0;

    /** Di atas ini packet loss sudah berat, peringatan dinaikkan. */
    public const MAJOR_LOSS_PCT = 20.0;

    /**
     * SATU-SATUNYA aturan penetapan status dari angka hasil probe. Semua
     * bagian sistem yang menampilkan status (badge daftar perangkat, kartu
     * hasil ping, polling database) wajib memakai fungsi ini supaya tidak
     * pernah ada perbedaan "badge di atas vs hasil di bawah".
     *
     *   - packet loss 100 %       → DOWN (tidak merespons sama sekali)
     *   - packet loss 1-99 %      → WARNING (masih membalas, tapi tidak penuh)
     *   - latency rata-rata >100ms → WARNING (respons lambat, di atas wajar)
     *   - selain itu              → UP
     *
     * Jitter tidak pernah mengubah status: hanya dilaporkan sebagai angka.
     *
     * @param  float|int|null  $lossPct  persen paket hilang (0-100)
     * @param  float|int|null  $avgMs  rata-rata latency (ms)
     */
    public static function statusFromProbe(float|int|null $lossPct, float|int|null $avgMs): string
    {
        $loss = $lossPct === null ? null : (float) $lossPct;
        $avg = $avgMs === null ? null : (float) $avgMs;

        if ($loss !== null && $loss >= 100) {
            return 'down';
        }

        if ($loss !== null && $loss > 0) {
            return 'warning';
        }

        if ($avg !== null && $avg > self::GOOD_LATENCY_MS) {
            return 'warning';
        }

        return 'up';
    }

    /* ══════════════════════════════════════════════════════════════
     *  Pintu masuk per alat
     * ══════════════════════════════════════════════════════════════ */

    /**
     * Analisis hasil ping.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    public function ping(array $raw): array
    {
        $output = (string) ($raw['output'] ?? '');
        $stats = $this->parsePing($output);

        /* Nama domain tidak bisa dipecah = tidak ada target yang bisa
           dihubungi, jadi dibaca sebagai DOWN yang penyebabnya jelas. */
        if ($this->matches($output, [
            'Name or service not known',
            'cannot resolve',
            'unknown host',
            'Temporary failure in name resolution',
        ])) {
            return $this->build(
                'down',
                'Nama tidak ditemukan di internet',
                'Alamat yang diuji tidak ada di internet, jadi tidak ada perangkat yang bisa dihubungi. '
                    .'Kemungkinan besar salah ketik, belum terdaftar, atau sudah dihapus.',
                [
                    ['Indikator', 'Nama domain tidak bisa dipecah jadi alamat IP'],
                    ['Dampak', 'Tidak ada jalur data ke tujuan ini sama sekali'],
                    ['Lama menunggu', $this->duration($raw)],
                ],
                'Coba pakai alamat IP langsung (mis. 8.8.8.8) atau periksa kembali ejaan nama domainnya.',
                'Host Not Found',
            );
        }

        $unroutable = $this->matches($output, [
            'Destination Host Unreachable',
            'Host Unreachable',
            'No route to host',
        ]);

        // Tidak ada satu pun balasan: 100% hilang, atau perintah timeout.
        if (($stats['loss'] !== null && $stats['loss'] >= 100)
            || ($stats['received'] === 0 && ($stats['sent'] ?? 0) > 0)
            || (($raw['timedout'] ?? false) && $stats['received'] === 0)) {
            return $this->unreachable($raw, $unroutable);
        }

        $facts = [];

        if ($stats['sent'] !== null && $stats['received'] !== null) {
            $facts[] = ['Paket terkirim / dijawab', $stats['sent'].' / '.$stats['received']];
        }

        if ($stats['loss'] !== null) {
            $facts[] = ['Packet loss', $this->num($stats['loss']).' %'];
        }

        if ($stats['avg'] !== null) {
            $facts[] = ['Respons rata-rata (avg)', $this->num($stats['avg']).' ms'];
        }

        if ($stats['min'] !== null && $stats['max'] !== null) {
            $facts[] = ['Respons terkecil / terbesar', $this->num($stats['min']).' / '.$this->num($stats['max']).' ms'];
        }

        if ($stats['mdev'] !== null) {
            $facts[] = ['Variasi / jitter (mdev)', $this->num($stats['mdev']).' ms'];
        }

        $facts[] = ['Lama pemeriksaan', $this->duration($raw)];

        $loss = $stats['loss'];
        $avg = $stats['avg'];

        /* Aturan status (kunci sinkronisasi muka Network Tools):
           - packet loss 100%  → DOWN (ditangani cabang di atas)
           - packet loss 1-99% → WARNING
           - latency rata-rata > GOOD_LATENCY_MS (100 ms) → WARNING
           - packet loss 0% dan latency normal → UP
           Jitter tetap dilaporkan sebagai fakta, tapi tidak menaikkan
           status sendirian — supaya hasilnya konsisten dengan badge
           daftar perangkat (UP/WARNING/DOWN). */
        $slow = $avg !== null && $avg > self::GOOD_LATENCY_MS;
        $lossy = $loss !== null && $loss > 0 && $loss < 100;

        /* Aturan status didelegasikan ke statusFromProbe() — fungsi yang sama
           dipakai badge daftar perangkat, polling, dan semua alat Network
           Tools. $slow/$lossy di bawah hanya untuk menyusun kalimat alasan. */
        if (self::statusFromProbe($loss, $avg) === 'warning') {
            $causes = [];

            if ($lossy) {
                $causes[] = 'ada packet loss ('.$this->num($loss).'%)';
            }
            if ($slow) {
                $causes[] = 'latency tinggi, rata-rata '.$this->num($avg).' ms'
                    .' (wajar di bawah '.$this->num(self::GOOD_LATENCY_MS).' ms)';
            }

            $severe = ($loss !== null && $loss >= self::MAJOR_LOSS_PCT)
                || ($avg !== null && $avg > self::POOR_LATENCY_MS);

            // Detail status untuk indikator: sebut PENYEBAB warning tanpa
            // menampilkan angka packet loss, supaya badge/indikator tetap
            // bersih (mis. "High Latency (245ms) · Packet Loss").
            $detailParts = [];

            if ($slow) {
                $detailParts[] = 'High Latency ('.$this->numDot($avg).'ms)';
            }
            if ($lossy) {
                $detailParts[] = 'Packet Loss';
            }

            return $this->build(
                'warning',
                $severe
                    ? 'Bisa dihubungi, tapi kondisinya sangat buruk'
                    : 'Bisa dihubungi, tapi responsnya kurang bagus',
                'Perangkat masih hidup dan membalas, namun '.implode(' serta ', $causes)
                    .'. Penyebabnya adalah delay (latency tinggi) dan/atau sedikit packet loss, bukan perangkat yang mati.',
                $facts,
                $severe
                    ? 'Periksa kabel, colokan, dan beban uplink di titik ini secepatnya — layanan di perangkat ini kemungkinan sudah terganggu.'
                    : 'Kalau berlangsung lama, periksa kabel, colokan, atau beban jaringan di titik tersebut.',
                $detailParts === [] ? 'Warning' : implode(' · ', $detailParts),
            );
        }

        /* ── UP: stabil dan tanpa packet loss ── */
        return $this->build(
            'up',
            'Perangkat merespons dengan sangat stabil',
            'Perangkat merespons dengan sangat stabil dan tanpa packet loss'
                .($avg !== null ? ' — rata-rata balasannya '.$this->num($avg).' ms' : '')
                .'. Kondisi ini normal dan tidak perlu tindakan apa pun.',
            $facts,
            'Tidak perlu tindakan. Lanjutkan pemeriksaan lain atau pantau berkala.',
            $avg !== null ? 'time='.$this->numDot($avg).'ms' : 'Reachable',
        );
    }

    /**
     * Bentuk DOWN yang sama untuk ping: perangkat tidak memberi jawaban
     * sama sekali.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function unreachable(array $raw, bool $unroutable = false): array
    {
        $output = (string) ($raw['output'] ?? '');

        $reason = $unroutable
            ? 'Perangkat tidak merespons — jaringan tidak punya jalur ke alamat tersebut '
                .'(No route to host / Host Unreachable).'
            : 'Perangkat tidak merespons (Request Timed Out / mati / kabel putus).';

        return $this->build(
            'down',
            'Perangkat tidak merespons',
            $reason
                .' Tidak ada satu pun balasan yang masuk selama '.$this->duration($raw)
                .', kemungkinan perangkatnya mati, sedang restart, kabel jaringan dicabut atau kendur, '
                .'atau perangkat di tengah jalannya mati.',
            [
                ['Balasan yang diterima', '0'],
                ['Packet loss', '100 %'],
                ['Lama menunggu', $this->duration($raw)],
                ['Indikator di log', $this->matches($output, ['Request timeout', 'Timed Out'])
                    ? 'Request Timed Out'
                    : 'Tidak ada balasan sama sekali'],
            ],
            'Cek lampu dan kabel di perangkat ini dulu. Kalau lampunya masih menyala, periksa juga perangkat di seberang titik ini.',
            $unroutable ? 'Destination Host Unreachable' : 'Request Timed Out',
        );
    }

    /**
     * Analisis hasil traceroute / tracepath.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    public function trace(array $raw): array
    {
        $arrived = $raw['arrived'] ?? null;
        $silent = (int) ($raw['silent'] ?? 0);
        $hops = (int) ($raw['hops'] ?? 0);
        $slowest = $raw['slowest_ms'] ?? null;
        $slowestHop = (int) ($raw['slowest_hop'] ?? 0);

        $facts = [
            ['Jumlah lompatan', (string) $hops],
            ['Sampai ke tujuan', $arrived !== null ? 'Ya, di lompatan '.$arrived : 'Tidak'],
            ['Router yang tidak menjawab', (string) $silent],
        ];

        if ($slowest !== null) {
            $facts[] = ['Lompatan paling lambat', $this->num((float) $slowest).' ms (no. '.$slowestHop.')'];
        }

        $facts[] = ['Lama pemeriksaan', $this->duration($raw)];

        if ($arrived === null) {
            return $this->build(
                'down',
                'Jalur data terputus sebelum sampai tujuan',
                'Perangkat tidak merespons — jalur data tidak pernah sampai ke tujuan. Data berhenti '
                    .'di router pada tengah jalan, jadi perangkat tujuan sendiri belum tentu mati.',
                $facts,
                'Periksa router di titik tempat jalur terputus: bisa mati, salah konfigurasi, atau kehabisan jalur routing.',
            );
        }

        /* Sampai tujuan, tapi ada router yang diam di tengah jalur: perjalanan
           data jadi tidak mulus walaupun akhirnya sampai. */
        if ($silent > 0) {
            return $this->build(
                'warning',
                'Sampai tujuan, tapi jalur tidak mulus',
                'Data sampai ke tujuan, namun ada '.$silent.' router yang tidak merespons di tengah jalur, '
                    .'sehingga ada sedikit packet loss sepanjang perjalanan. Jalur ini tetap jalan, tapi tidak bersih.',
                $facts,
                'Periksa router yang tidak menjawab tersebut. Kalau memang sengaja dimatikan, tidak perlu tindakan; kalau tidak, periksa uplink-nya.',
            );
        }

        if ($slowest !== null && (float) $slowest > self::POOR_LATENCY_MS) {
            return $this->build(
                'warning',
                'Sampai tujuan, tapi ada lonjakan latency tinggi',
                'Data sampai ke tujuan tanpa packet loss, tapi ada lompatan dengan latency tinggi '
                    .'('.$this->num((float) $slowest).' ms). Itu menandakan ada delay di salah satu titik jalur.',
                $facts,
                'Periksa perangkat di lompatan paling lambat — biasanya antrean antarmuka (buffer) yang sudah penuh.',
            );
        }

        return $this->build(
            'up',
            'Jalur data sampai tujuan dengan bersih',
            'Seluruh router di jalur ini merespons dan data sampai ke tujuan tanpa packet loss, '
                .'tanpa ada lonjakan latency berarti. Jalur berjalan normal.',
            $facts,
            'Tidak perlu tindakan.',
        );
    }

    /**
     * Analisis hasil pencarian nama (DNS lookup).
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    public function lookup(array $raw): array
    {
        $found = (int) ($raw['found'] ?? 0);
        $type = strtoupper((string) ($raw['type'] ?? 'A'));

        $facts = [
            ['Jenis data yang dicari', $type],
            ['Data yang ditemukan', (string) $found],
            ['Lama pemeriksaan', $this->duration($raw)],
        ];

        if ($raw['not_found'] ?? false) {
            return $this->build(
                'down',
                'Nama tidak ditemukan di internet',
                'Nama domain ini tidak ada di daftar internet, jadi tidak ada perangkat yang bisa dihubungi '
                    .'(Permintaan tidak ditemukan / NXDOMAIN).',
                $facts,
                'Periksa ejaan nama domainnya. Kalau memang benar, berarti domain ini belum terdaftar atau sudah dihapus.',
            );
        }

        if ($raw['no_record'] ?? false) {
            return $this->build(
                'warning',
                'Nama ada, tapi data yang dicari tidak ada',
                'Nama domain ini terdaftar, tetapi tidak punya record '.$type
                    .'. Ini wajar — tidak semua nama domain punya data tersebut, jadi ini bukan gangguan.',
                $facts,
                'Coba pilih jenis data lain (A / MX / NS / CNAME) di pengaturan pemeriksaan.',
            );
        }

        if ($found > 0) {
            return $this->build(
                'up',
                'Informasi nama berhasil diambil',
                'Nama domain ini terdaftar dan punya '.$found.' data '.$type
                    .', jadi alamatnya bisa dihubungi. Server nama merespons normal tanpa error.',
                $facts,
                'Tidak perlu tindakan.',
            );
        }

        return $this->build(
            'warning',
            'Server nama menjawab, tapi datanya belum ada',
            'Server nama merespons, tetapi belum ada data '.$type.' untuk nama ini. '
                .'Bisa karena record-nya belum dibuat, atau nameserver-nya belum tersinkron.',
            $facts,
            'Coba lagi beberapa saat lagi. Kalau tetap kosong, periksa konfigurasi record di sisi pemilik domain.',
        );
    }

    /**
     * Analisis hasil pemindaian port.
     *
     * Status di sini menggambarkan kondisi perangkatnya, bukan risikonya:
     * perangkat yang bisa diperiksa = UP, perangkat yang portnya perlu
     * dibersihkan = WARNING, perangkat yang tidak bisa dihubungi = DOWN.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    public function scan(array $raw): array
    {
        $open = (array) ($raw['open'] ?? []);
        $first = (int) ($raw['first'] ?? 1);
        $last = (int) ($raw['last'] ?? 512);
        $count = count($open);

        $facts = [
            ['Port terbuka', (string) $count],
            ['Rentang diperiksa', $first.'–'.$last],
            ['Lama pemeriksaan', $this->duration($raw)],
        ];

        if (($raw['timedout'] ?? false) || ((int) ($raw['exit'] ?? 0) !== 0 && $count === 0)) {
            return $this->build(
                'down',
                'Perangkat tidak merespons',
                'Perangkat tidak merespons — pemindaian tidak mendapat jawaban apa pun dari target ini. '
                    .'Kemungkinan perangkat mati, kabel putus, atau alamat IP-nya salah.',
                $facts,
                'Pastikan perangkat menyala dan alamat IP-nya masih benar sebelum mengulang pemindaian.',
            );
        }

        if ($count > 0) {
            return $this->build(
                'warning',
                'Perangkat hidup, tapi ada '.$count.' port terbuka',
                'Perangkat merespons normal, tetapi ada '.$count.' port terbuka pada rentang '.$first.'–'.$last
                    .'. Setiap port terbuka adalah pintu masuk tambahan bagi perangkat ini.',
                $facts,
                'Tutup port yang tidak dipakai. Untuk layanan yang tetap dibiarkan terbuka, pakai versi terenkripsi (HTTPS / SSH / SMTPS).',
            );
        }

        return $this->build(
            'up',
            'Perangkat merespons dan tidak ada port terbuka',
            'Perangkat merespons normal dan semua port pada rentang '.$first.'–'.$last.' tertutup. '
                .'Tidak ada celah yang terlihat pada rentang ini.',
            $facts,
            'Tidak perlu tindakan pada rentang ini. Port di luar rentang belum diperiksa.',
        );
    }

    /* ══════════════════════════════════════════════════════════════
     *  Pembantu
     * ══════════════════════════════════════════════════════════════ */

    /**
     * Baca angka penting dari keluaran ping.
     *
     * Mengembalikan null untuk apa pun yang tidak terbaca, jadi pemanggil
     * tidak pernah harus menebak angka yang sebenarnya tidak ada.
     *
     * @return array{sent: ?int, received: ?int, loss: ?float, min: ?float, avg: ?float, max: ?float, mdev: ?float}
     */
    public function parsePing(string $output): array
    {
        $pair = preg_match(
            '/(\d+)\s+packets transmitted,\s+(\d+)\s+(?:packets\s+)?received/i',
            $output,
            $m
        ) === 1;

        $sent = $pair ? (int) $m[1] : null;
        $received = $pair ? (int) $m[2] : null;

        $loss = null;

        if (preg_match('/(\d+(?:\.\d+)?)%\s*packet loss/i', $output, $m) === 1) {
            $loss = (float) $m[1];
        } elseif ($sent !== null && $sent > 0 && $received !== null) {
            $loss = round((($sent - $received) / $sent) * 100, 1);
        }

        $min = $avg = $max = $mdev = null;

        if (preg_match(
            '#rtt\s+min/avg/max(?:/mdev)?\s*=\s*([\d.]+)/([\d.]+)/([\d.]+)(?:/([\d.]+))?#i',
            $output,
            $m
        ) === 1) {
            $min = (float) $m[1];
            $avg = (float) $m[2];
            $max = (float) $m[3];
            $mdev = isset($m[4]) && $m[4] !== '' ? (float) $m[4] : null;
        }

        return [
            'sent' => $sent,
            'received' => $received,
            'loss' => $loss,
            'min' => $min,
            'avg' => $avg,
            'max' => $max,
            'mdev' => $mdev,
        ];
    }

    /**
     * Selisih latency terbesar-kecil sebagai ukuran jitter.
     *
     * @param  array{min: ?float, max: ?float, mdev: ?float}  $stats
     */
    protected function jitter(array $stats): ?float
    {
        if ($stats['min'] !== null && $stats['max'] !== null) {
            return round($stats['max'] - $stats['min'], 1);
        }

        // mdev adalah simpangan baku, bukan rentang; dikali dua supaya
        // sekelas dengan selisih min–max.
        if ($stats['mdev'] !== null) {
            return round($stats['mdev'] * 2, 1);
        }

        return null;
    }

    /**
     * @param  list<string>  $needles
     */
    protected function matches(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (stripos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Bentuk analisis jadi, selalu dengan struktur yang sama untuk semua alat
     * supaya frontend cukup punya satu renderer.
     *
     * @param  list<array{0: string, 1: string}>  $facts
     * @return array<string, mixed>
     */
    protected function build(string $status, string $headline, string $reason, array $facts, string $recommendation, ?string $detail = null): array
    {
        return [
            'status' => $status,
            'label' => match ($status) {
                'up' => 'UP',
                'warning' => 'WARNING',
                default => 'DOWN',
            },
            'headline' => $headline,
            'reason' => $reason,
            'facts' => array_values($facts),
            'recommendation' => $recommendation,
            'status_detail' => $detail,
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     */
    protected function duration(array $raw): string
    {
        $ms = (int) ($raw['duration_ms'] ?? 0);

        return $ms < 1000
            ? $ms.' ms'
            : $this->num($ms / 1000).' detik ('.$ms.' ms)';
    }

    protected function num(float|int $value): string
    {
        return number_format((float) $value, 1, ',', '.');
    }

    protected function numDot(float|int $value): string
    {
        return number_format((float) $value, 1, '.', '');
    }
}
