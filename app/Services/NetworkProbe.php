<?php

namespace App\Services;

use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Facades\Process;

class NetworkProbe
{
    /**
     * Ambang latensi (ms) sebelum status dinyatakan WARNING.
     */
    public const HIGH_LATENCY_MS = 150;

    /**
     * Jalankan ICMP ping (4 probe) dan kembalikan hasil detail per IP.
     *
     * @return array{exit_code:int, output:string, error_output:string, latency:?int, packet_loss:?float, error:?string}
     */
    public function probe(string $ip): array
    {
        $data = [
            'exit_code'    => 1,
            'output'       => '',
            'error_output' => '',
            'latency'      => null,
            'packet_loss'  => null,
            'error'        => null,
        ];

        try {
            $result = Process::timeout(20)->run('ping -4 -c 4 -i 0.2 -W 2 '.escapeshellarg($ip));

            $data['exit_code']    = $result->exitCode();
            $data['output']       = trim($result->output());
            $data['error_output'] = trim($result->errorOutput());
            $data['latency']      = $this->extractLatency($data['output']);
            $data['packet_loss']  = $this->extractPacketLoss($data['output']);
        } catch (ProcessTimedOutException $e) {
            $data['error'] = 'Diagnostic timeout: '.$e->getMessage();
        } catch (\Throwable $e) {
            $data['error'] = 'Failed to run diagnostic: '.$e->getMessage();
        }

        return $data;
    }

    /**
     * Tentukan status perangkat berdasarkan hasil ping yang terukur:
     * - up      : semua packet terkirim & latensi normal.
     * - warning : ada packet loss sebagian atau latensi tinggi.
     * - down    : 100% packet loss / tidak ada balasan sama sekali.
     */
    public function tentukanStatus(int $exitCode, ?float $packetLoss, ?int $latency): string
    {
        if ($exitCode === 0
            && ($packetLoss === null || $packetLoss === 0.0)
            && ($latency === null || $latency <= self::HIGH_LATENCY_MS)) {
            return 'up';
        }

        $adaLossSebagian = $packetLoss !== null && $packetLoss > 0 && $packetLoss < 100;
        $latensiTinggi   = $exitCode === 0 && $latency !== null && $latency > self::HIGH_LATENCY_MS;

        if ($adaLossSebagian || $latensiTinggi) {
            return 'warning';
        }

        return 'down';
    }

    /**
     * Bangun keterangan (reason) yang dinamis & spesifik berdasarkan
     * hasil pengecekan aktual per IP perangkat (packet loss, latency,
     * atau host unreachable). Dipakai di pesan Telegram NOC.
     */
    public function alasanKendala(string $status, ?float $packetLoss, ?int $latency): string
    {
        if ($status === 'up') {
            $rincian = '0% packet loss';

            if ($latency !== null) {
                $rincian .= ', latency ±'.$latency.' ms';
            }

            return '🟢 Perangkat kembali merespons dengan normal dan stabil ('.$rincian.')';
        }

        if ($status === 'down') {
            $loss = $packetLoss !== null
                ? rtrim(rtrim(number_format($packetLoss, 1), '0'), '.')
                : '100';

            $keterangan = ($loss === '100')
                ? 'Host Unreachable - 100% packet loss (Kabel jaringan terputus / Perangkat mati)'
                : 'Connection Refused / Service Down (Perangkat tidak merespons, loss '.$loss.'%)';

            return '🔴 '.$keterangan;
        }

        // ── WARNING ──
        $rincian = [];

        if ($latency !== null && $latency > 300) {
            $keterangan = '⚠️ High Latency / Delay Tinggi (>300ms)'.($latency !== null ? ' — latency ±'.$latency.' ms' : '');
        } elseif ($packetLoss !== null && $packetLoss > 0) {
            $keterangan = '⚠️ Packet Loss terdeteksi (Koneksi tidak stabil) — packet loss '.number_format($packetLoss, 1).'%';
        } elseif ($latency !== null && $latency > self::HIGH_LATENCY_MS) {
            $keterangan = '⚠️ High Latency / Delay Tinggi — latency ±'.$latency.' ms';
        } else {
            $keterangan = '⚠️ Koneksi tidak stabil (terdeteksi gangguan).';
        }

        return $keterangan;
    }

    /**
     * Ambil rata-rata latensi (avg) dari output ping.
     */
    protected function extractLatency(string $raw): ?int
    {
        if (preg_match('/= ([\d.]+)\/([\d.]+)\/([\d.]+)\/([\d.]+)\s+ms/', $raw, $matches)) {
            return (int) round((float) $matches[2]);
        }

        if (preg_match('/time=([\d.]+)\s?ms/', $raw, $matches)) {
            return (int) round((float) $matches[1]);
        }

        return null;
    }

    /**
     * Ambil persentase packet loss dari output ping (mis. "25% packet loss").
     */
    protected function extractPacketLoss(string $raw): ?float
    {
        if (preg_match('/([\d.]+)%\s*packet loss/i', $raw, $matches)) {
            return (float) $matches[1];
        }

        return null;
    }
}