<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceNotification;
use App\Services\NetworkTools\DiagnosisAnalyzer;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Satu-satunya sumber keputusan "apakah perangkat ini perlu notifikasi baru".
 *
 * Aturan: notifikasi dibuat HANYA pada transisi status, bukan pada setiap polling.
 *
 * Status perangkat di tabel `device` (up / down / warning) dipetakan ke kondisi
 * konektivitas supaya tidak terjadi spam notifikasi:
 *
 *   up        -> reachable (UP)
 *   down      -> unreachable (DOWN)
 *   warning   -> degraded; bisa berarti latency tinggi (masih reachable) ATAU
 *                packet timeout (tidak reachable), jadi tidak boleh disamakan
 *                dengan UP maupun DOWN. Jika tidak, perangkat yang timeout
 *                lalu pulih akan memunculkan notifikasi terus penerus.
 *
 * Matriks transisi (previous -> next):
 *
 *   unknown  -> *            : tidak ada notifikasi (status awal tidak boleh dikarang)
 *   up       -> up           : tidak ada
 *   down     -> down         : tidak ada
 *   degraded -> degraded     : tidak ada
 *   up       -> down         : DEVICE DOWN
 *   down     -> up           : DEVICE RECOVERED
 *   degraded -> down         : DEVICE DOWN
 *   up       -> degraded     : WARNING (hanya sekali per episode, lihat anti-duplikat)
 *   degraded -> up           : tidak ada (perangkat tidak pernah tercatat DOWN,
 *                             jadi tidak ada yang perlu "dipulihkan")
 *   down     -> degraded     : DEVICE DOWN (masih tidak merespons)
 *
 * Anti-duplikat: sebelum INSERT, notifikasi terbaru untuk device yang sama dicek
 * di dalam transaksi dengan row lock. Kalau event-nya sama, artifact diabaikan.
 * Dengan begitu dua polling yang berjalan bersamaan tidak bisa membuat dua
 * notifikasi untuk satu transisi yang sama.
 */
class DeviceStatusNotifier
{
    public const CONNECTIVITY_UP = 'up';

    public const CONNECTIVITY_DOWN = 'down';

    public const CONNECTIVITY_DEGRADED = 'degraded';

    /**
     * Status perangkat -> kondisi konektivitas.
     */
    public function connectivityOf(?string $status): ?string
    {
        $status = strtolower(trim((string) $status));

        return match ($status) {
            'up' => self::CONNECTIVITY_UP,
            'down' => self::CONNECTIVITY_DOWN,
            'warning' => self::CONNECTIVITY_DEGRADED,
            default => null,
        };
    }

    /**
     * Event notifikasi untuk satu transisi, atau null bila tidak perlu notifikasi.
     *
     * @return array{event_type: string, event: string, status: string}|null
     */
    public function eventFor(?string $previousStatus, ?string $newStatus): ?array
    {
        $previous = $this->connectivityOf($previousStatus);
        $next = $this->connectivityOf($newStatus);

        // Status sebelumnya belum pernah diketahui: jangan mengarang state awal.
        if ($previous === null || $next === null) {
            return null;
        }

        // Status tidak berubah sama sekali (DOWN -> DOWN / UP -> UP / warning -> warning).
        if ($previous === $next) {
            return null;
        }

        if ($next === self::CONNECTIVITY_DOWN) {
            return $this->event('down', 'DOWN', 'down');
        }

        if ($next === self::CONNECTIVITY_DEGRADED) {
            return $this->event('warning', 'WARNING', 'warning');
        }

        // next === up
        if ($previous === self::CONNECTIVITY_DOWN) {
            return $this->event('recovered', 'BACK ONLINE', 'online');
        }

        // degraded -> up: device tidak pernah dicatat DOWN, tidak ada yang dipulihkan.
        return null;
    }

    /**
     * Catat notifikasi bila transisi memang terjadi.
     *
     * @return DeviceNotification|null notifikasi yang dibuat, atau null bila di-skip
     */
    public function record(
        Device $device,
        ?string $previousStatus,
        string $newStatus,
        ?int $latency,
        string $reason,
        ?string $message = null
    ): ?DeviceNotification {
        $event = $this->eventFor($previousStatus, $newStatus);

        if ($event === null && ! $this->isStableWarning($previousStatus, $newStatus)) {
            return null;
        }

        if ($event === null) {
            // Device sudah berstatus 'warning' sebelum polling ini dan belum pernah
            // ada notifikasi warning untuknya: buat tepat satu. Polling berikutnya
            // dilewati oleh pemeriksaan anti-duplikat di bawah.
            $event = $this->event('warning', 'WARNING', 'warning');
        }

        try {
            return DB::transaction(function () use ($device, $event, $latency, $message, $reason) {
                // Kunci baris device: poller lain yang jalan bersamaan akan menunggu
                // sampai transaksi ini selesai, lalu melihat notifikasi yang baru saja
                // dibuat dan melewati langkah INSERT di bawah.
                Device::query()->whereKey($device->getKey())->lockForUpdate()->first();

                $latest = DeviceNotification::query()
                    ->where('device_id', $device->getKey())
                    ->orderByDesc('checked_at')
                    ->orderByDesc('id')
                    ->first();

                if ($latest !== null && $latest->event_type === $event['event_type']) {
                    return null;
                }

                return DeviceNotification::create([
                    'device_id' => $device->getKey(),
                    'device_name' => $device->name,
                    'ip_address' => $device->ip_address,
                    'event_type' => $event['event_type'],
                    'event' => $event['event'],
                    'status' => $event['status'],
                    'message' => $message ?? $this->defaultMessage($event['event_type'], $latency, $reason),
                    'latency' => $latency,
                    'is_read' => false,
                    'checked_at' => now(),
                ]);
            });
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * @return array{event_type: string, event: string, status: string}
     */
    private function event(string $eventType, string $event, string $status): array
    {
        return [
            'event_type' => $eventType,
            'event' => $event,
            'status' => $status,
        ];
    }

    /**
     * Status 'warning' -> 'warning' (perangkat tidak berubah dan tidak merespons).
     *
     * Bukan transisi, tapi tetap boleh membuat SATU notifikasi warning kalau
     * device tersebut belum punya notifikasi warning sama sekali, supaya
     * perangkat yang sudah 'warning' saat polling pertama tidak diam-diam.
     */
    private function isStableWarning(?string $previousStatus, ?string $newStatus): bool
    {
        return $this->connectivityOf($previousStatus) === self::CONNECTIVITY_DEGRADED
            && $this->connectivityOf($newStatus) === self::CONNECTIVITY_DEGRADED;
    }

    private function defaultMessage(string $eventType, ?int $latency, string $reason): string
    {
        return match ($eventType) {
            'warning' => $latency !== null
                ? 'Ping berhasil, tetapi latency '.$latency.' ms melebihi threshold '.DiagnosisAnalyzer::GOOD_LATENCY_MS.' ms.'
                : "Paket ping terakhir tidak dijawab ({$reason}).",
            'down' => "Tidak merespons ping: {$reason}.",
            default => $latency !== null
                ? "Device kembali merespons ping dengan latency {$latency} ms."
                : 'Device kembali merespons ping.',
        };
    }
}
