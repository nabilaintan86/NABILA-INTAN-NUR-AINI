<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\TroubleshootingLog;
use Illuminate\Http\Request;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Facades\Process;

class TroubleshootingController extends Controller
{
    public function index()
    {
        $problemDevices = Device::whereIn('status', ['down', 'warning'])
            ->orderByRaw("CASE WHEN status = 'down' THEN 0 ELSE 1 END")
            ->orderByDesc('alerts_count')
            ->get();

        $devices = Device::all();
        $totalChecked  = $devices->count();
        $criticalCount = $devices->where('status', 'down')->count();

        $logs    = TroubleshootingLog::latest('checked_at')->take(20)->get();
        $upCount = $logs->where('status', 'up')->count();
        $recoveryRate = $logs->count() > 0 ? round(($upCount / $logs->count()) * 100) : 0;

        return view('admin.troubleshooting', compact(
            'problemDevices', 'totalChecked', 'criticalCount', 'recoveryRate', 'logs'
        ));
    }

    public function diagnose(Request $request)
    {
        $request->validate([
            'device_id' => 'required|integer|exists:devices,id',
        ]);

        $device = Device::findOrFail($request->integer('device_id'));

        $started = microtime(true);
        $status  = 'down';
        $latency = null;
        $output  = null;
        $error   = null;
        $timedOut = false;
        $exit    = 1;

        try {
            $command = 'ping -4 -c 3 -W 2 '.escapeshellarg($device->ip_address);

            $result = Process::timeout(20)->run($command);

            $exit   = $result->exitCode();
            $output = trim($result->output());

            if ($exit === 0) {
                $status = 'up';
                if (preg_match('/time=([\d.]+)\s?ms/', $output, $m)) {
                    $latency = (int) round((float) $m[1]);
                }
            } else {
                $status = $exit === 2 ? 'warning' : 'down';
                $error  = trim($result->errorOutput());
            }
        } catch (ProcessTimedOutException $e) {
            $timedOut = true;
            $status   = 'warning';
            $error    = 'Diagnostic timeout: '.$e->getMessage();
        } catch (\Throwable $e) {
            $status = 'warning';
            $error  = 'Failed to run diagnostic: '.$e->getMessage();
        }

        $durationMs = (int) round((microtime(true) - $started) * 1000);

        $log = TroubleshootingLog::create([
            'device_id'    => $device->id,
            'device_name'  => $device->name,
            'ip_address'   => $device->ip_address,
            'status'       => $status,
            'latency'      => $latency,
            'diagnostic'   => 'ICMP Ping (3 probe)',
            'action'       => 'Run Instant Diagnostic',
            'error'        => $error,
            'output'       => $output,
            'duration_ms'  => $durationMs,
            'checked_at'   => now(),
        ]);

        return response()->json([
            'success'   => true,
            'log'       => [
                'id'          => $log->id,
                'device_name' => $log->device_name,
                'ip_address'  => $log->ip_address,
                'status'      => $log->status,
                'latency'     => $latency,
                'diagnostic'  => $log->diagnostic,
                'action'      => $log->action,
                'error'       => $log->error,
                'output'      => $log->output,
                'duration_ms' => $log->duration_ms,
                'checked_at'  => $log->checked_at->format('d M Y H:i:s'),
            ],
            'analysis' => $this->analyzeDiagnosticResult($status, $output, $latency),
            'timed_out' => $timedOut,
        ]);
    }

    /**
     * Simulasi pemulihan: paksa status perangkat dari DOWN/WARNING menjadi UP.
     */
    public function resolve(Request $request, int $id)
    {
        $device = Device::findOrFail($id);

        $device->update(['status' => 'up']);

        $log = TroubleshootingLog::create([
            'device_id'    => $device->id,
            'device_name'  => $device->name,
            'ip_address'   => $device->ip_address,
            'status'       => 'up',
            'latency'      => $device->avg_delay ?: null,
            'diagnostic'   => 'Manual Override',
            'action'       => 'Pulihkan Status (Set to UP)',
            'error'        => null,
            'output'       => "Status {$device->name} ({$device->ip_address}) dipulihkan secara manual menjadi UP.",
            'duration_ms'  => null,
            'checked_at'   => now(),
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Status perangkat {$device->name} berhasil dipulihkan ke UP.",
            'device'   => [
                'id'     => $device->id,
                'name'   => $device->name,
                'status' => $device->status,
            ],
            'log' => [
                'id'          => $log->id,
                'device_name' => $log->device_name,
                'ip_address'  => $log->ip_address,
                'status'      => $log->status,
                'latency'     => $log->latency,
                'diagnostic'  => $log->diagnostic,
                'action'      => $log->action,
                'output'      => $log->output,
                'checked_at'  => $log->checked_at->format('d M Y H:i:s'),
            ],
        ]);
    }

    /**
     * Analisis otomatis hasil diagnostik (Root Cause Analysis)
     * — merangkum kondisi secara ramah pengguna non-teknis/HR.
     */
    protected function analyzeDiagnosticResult(string $status, ?string $output, ?int $latency): ?array
    {
        $output = strtolower((string) $output);

        if ($status === 'down') {
            return [
                'category' => 'offline',
                'cause'    => 'Koneksi Terputus / Perangkat Offline',
                'summary'  => 'Gangguan Terdeteksi: Perangkat tidak merespons (100% Packet Loss). Kemungkinan besar terjadi pemutusan fisik pada kabel jaringan, gangguan catu daya (power/mati lampu), atau router mengalami hang.',
                'steps'    => $this->offlineRecoverySteps(),
            ];
        }

        if ($status === 'up') {
            return [
                'category' => 'normal',
                'cause'    => 'Perangkat Normal',
                'summary'  => 'Status Normal: Perangkat terhubung stabil dan merespons paket data dengan baik tanpa ada kendala latensi.',
                'steps'    => [],
            ];
        }

        if ($status === 'warning') {
            return [
                'category' => 'latency',
                'cause'    => 'Bottleneck / High Latency',
                'summary'  => 'Perhatian: Perangkat masih merespons tetapi kinerjanya melambat (latensi tinggi). Potensi beban link berlebih atau degradasi kualitas sinyal.',
                'steps'    => [
                    'Identifikasi segmen link yang jenuh — ukur utilisasi bandwidth pada port uplink.',
                    'Cek traffic spike pada router / switch (netflow, interface counters, drop packet).',
                    'Optimalkan Quality of Service (QoS) prioritas layanan penting.',
                    'Bila perlu, naikkan kapasitas link atau lakukan load balancing.',
                ],
            ];
        }

        return null;
    }

    protected function offlineRecoverySteps(): array
    {
        return [
            'Periksa status fisik perangkat (indikator power, kipas, LED port).',
            'Cek kabel uplink / patchcord serta status port pada switch/core.',
            'Biarkan perangkat restart (power-cycle) bila tidak merespons.',
            'Verifikasi konfigurasi routing / IP yang dipakai perangkat.',
            'Hubungi site engineer bila gangguan berlanjut.',
        ];
    }
}