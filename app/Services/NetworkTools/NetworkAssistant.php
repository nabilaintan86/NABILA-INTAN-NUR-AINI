<?php

namespace App\Services\NetworkTools;

/**
 * Asisten Pintar NOC: menerjemahkan hasil ping jadi SATU saran tindakan
 * yang langsung bisa dieksekusi operator, bukan sekadar status UP/WARNING/
 * DOWN yang angkanya saja.
 *
 * Kenapa dipisah dari DiagnosisAnalyzer:
 *
 *  1. DiagnosisAnalyzer menjawab "apa yang terjadi"; kelas ini menjawab
 *     "harus diapakan". Opsi tindakannya berbeda walaupun datanya sama.
 *  2. Aturan prioritasnya harus tertulis di satu tempat: DOWN (target tidak
 *     merespons sama sekali / packet loss 100%) dulu — gangguan jalur kabel
 *     atau gateway, baru latency tinggi (>100 ms) dan packet loss parsial
 *     (1-99%) sebagai WARNING. Jitter hanya dilaporkan sebagai angka, tidak
 *     mengubah status. Packet loss parsial berapa pun tetap WARNING selama
 *     target masih membalas, supaya perangkat berstatus WARNING tidak pernah
 *     tampil sebagai DOWN. Kalau urutan ini ditulis di beberapa tempat,
 *     hasilnya akan berbeda antar halaman.
 *  3. Sama seperti DiagnosisAnalyzer: kelas ini murni, tidak menyentuh
 *     database, tidak menjalankan perintah, dan tidak pernah melempar
 *     exception.
 */
final class NetworkAssistant
{
    /** Angka yang ditampilkan ke layar, dibulatkan supaya tidak panjang. */
    private const PRECISION = 1;

    /**
     * @param  array<string, mixed>  $analysis  hasil DiagnosisAnalyzer
     * @return array{level:string, title:string, message:string, actions:list<string>, metrics:array<string, float|null>}
     */
    public function suggest(?string $status, string $output, array $analysis = []): array
    {
        $stats = (new DiagnosisAnalyzer)->parsePing($output);

        return $this->fromMetrics($status, $stats['loss'], $stats['avg'], $this->jitter($stats));
    }

    /**
     * Versi yang bekerja langsung dari angka, bukan dari keluaran ping.
     *
     * Dipakai oleh chat box AI Network Assistant: browser hanya mengirim
     * ringkasan hasil ping terakhir, dan saran yang dihasilkan tetap sama
     * persis dengan yang tampil di kartu asisten karena aturannya satu.
     *
     * @return array{level:string, title:string, message:string, actions:list<string>, metrics:array<string, float|null>}
     */
    public function fromMetrics(?string $status, ?float $loss, ?float $latency, ?float $jitter): array
    {
        $metrics = [
            'packet_loss_pct' => $loss,
            'latency_ms' => $latency,
            'jitter_ms' => $jitter,
        ];

        /*
         * Status DOWN hanya dipicu kalau target benar-benar tidak merespons
         * (analisis ping mentahnya mencatat 100% hilang / timed out). Packet
         * loss parsial berapa pun — selama masih ada balasan — tetap WARNING,
         * supaya perangkat berstatus WARNING di monitoring tidak pernah
         * ditampilkan sebagai GANGGUAN (DOWN).
         */
        if ($status === 'down') {
            return [
                'level' => 'danger',
                'title' => 'GANGGUAN — Target tidak merespons (100% RTO)',
                'message' => 'Target tidak merespons sama sekali: paket hilang 100% '
                    .'(RTO / Request Timed Out). Ini pola gangguan jalur, bukan sekadar jaringan sibuk — '
                    .'jalur fisik kemungkinan terputus atau perangkat tujuan kehilangan daya.',
                'actions' => [
                    'Periksa jalur fisik kabel (serat/UTP) dan konektornya: cabut, bersihkan, pasang kembali.',
                    'Cek power supply / adaptor serta lampu indikator perangkat di titik ini — pastikan benar-benar menyala.',
                    'Pastikan gateway di jalur masih punya rute ke target, lalu jalankan ulang pengecekan.',
                ],
                'metrics' => $metrics,
            ];
        }

        /* ── 2. Lonjakan latensi: trafik menumpuk / router sibuk ── */
        if ($latency !== null && $latency > DiagnosisAnalyzer::GOOD_LATENCY_MS) {
            $buruk = $latency > DiagnosisAnalyzer::POOR_LATENCY_MS;

            return [
                'level' => 'warning',
                'title' => 'WASPADA — Lonjakan latensi (trafik / router sibuk)',
                'message' => 'Target masih merespons, tetapi terjadi lonjakan latensi: rata-rata '
                    .$this->num($latency).' ms (batas aman '.$this->num(DiagnosisAnalyzer::GOOD_LATENCY_MS).' ms)'
                    .' dengan packet loss '.$this->num($loss ?? 0).'% (bukan 100%). Jalur masih '
                    .'terhubung, namun trafik menumpuk sehingga router di jalur kemungkinan sedang sibuk.',
                'actions' => [
                    'Cek pemakaian trafik / bandwidth uplink pada jam ini; lonjakan biasanya muncul saat jam sibuk.',
                    'Periksa beban dan antrean router di jalur — router yang kewalahan membuat delay.',
                    $buruk
                        ? 'Bila latensi terus di atas '.$this->num(DiagnosisAnalyzer::POOR_LATENCY_MS).' ms, tambah kapasitas atau bagi beban trafiknya.'
                        : 'Ulangi pengecekan beberapa menit lagi untuk memastikan bukan lonjakan sesaat.',
                ],
                'metrics' => $metrics,
            ];
        }

        /* ── 3. Packet loss parsial (1-99%): masih membalas, jalur tidak stabil ── */
        $lossy = $loss !== null && $loss > 0 && $loss < 100;

        if ($lossy) {
            $berat = $loss >= DiagnosisAnalyzer::MAJOR_LOSS_PCT;

            return [
                'level' => 'warning',
                'title' => $berat
                    ? 'WASPADA — Packet loss parsial tinggi'
                    : 'WASPADA — Packet loss parsial',
                'message' => 'Target masih membalas, tetapi terdeteksi packet loss parsial '
                    .$this->num($loss).'% — tidak sampai 100%, jadi ini WARNING, bukan DOWN. '
                    .'Jalur mulai tidak stabil; periksa trafik dan router yang sedang sibuk di sepanjang jalur ini.',
                'actions' => $berat ? [
                    'Tingkat packet loss sudah tinggi — prioritaskan pengecekan kabel, sambungan, dan beban trafik di titik ini.',
                    'Cek uplink / gateway di tengah jalur; pastikan tidak overload atau ada loop routing.',
                    'Jalankan ulang pengecekan untuk mengonfirmasi polanya sebelum menaikkan ke tim lapangan.',
                ] : [
                    'Cek trafik dan tingkat pemakaian bandwidth di jalur — kemacetan ringan bisa membuat paket hilang.',
                    'Periksa beban router dan sambungan kabel/colokan yang dilewati jalur ini.',
                    'Jalankan pengecekan ulang; kalau polanya tetap, naikkan ke tim lapangan.',
                ],
                'metrics' => $metrics,
            ];
        }

        /* ── 4. Normal: jalur stabil, latensi aman, tanpa kendala ── */
        return [
            'level' => 'ok',
            'title' => 'NORMAL — Jalur stabil',
            'message' => 'Jalur ke target stabil: latensi aman'
                .($latency !== null ? ' (rata-rata '.$this->num($latency).' ms)' : '')
                .' dan tidak ada packet loss. Koneksi normal tanpa kendala.',
            'actions' => [
                'Tidak ada tindakan yang diperlukan untuk hasil ini.',
                'Lanjutkan pemantauan berkala; periksa kembali bila status berubah.',
            ],
            'metrics' => $metrics,
        ];
    }

    /**
     * Selisih latency terkecil–terbesar, konsisten dengan ambang DiagnosisAnalyzer.
     *
     * @param  array{min: ?float, max: ?float, mdev: ?float}  $stats
     */
    private function jitter(array $stats): ?float
    {
        if ($stats['min'] !== null && $stats['max'] !== null) {
            return round($stats['max'] - $stats['min'], self::PRECISION);
        }

        if ($stats['mdev'] !== null) {
            return round($stats['mdev'] * 2, self::PRECISION);
        }

        return null;
    }

    private function num(float|int|null $value): string
    {
        return number_format((float) ($value ?? 0), self::PRECISION, ',', '.');
    }
}
