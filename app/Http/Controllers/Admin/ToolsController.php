<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\MonitoringLog;
use App\Services\DeviceCsvService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Support\Facades\Process;

class ToolsController extends Controller
{
    public function index(DeviceCsvService $csv)
    {
        $devices = $csv->devices()
            ->map(fn (array $device) => (object) [
                'id'                => $device['id'],
                'name'              => $device['name'],
                'type'              => $device['type'],
                'ip_address'        => $device['ip_address'],
                'status'            => $device['status'],
                'avg_delay'         => $device['avg_delay'],
                'missed_percentage' => $device['missed_percentage'],
            ])
            ->sortBy(fn ($device) => match ($device->status) {
                'down'    => 0,
                'warning' => 1,
                'up'      => 2,
                default   => 3,
            }, SORT_REGULAR)
            ->values();

        return view('admin.tools', ['devices' => $devices]);
    }

    /**
     * Ping tujuan dengan flag standar Linux:
     *   ping -c <count> -W <timeout> <target>
     */
    public function ping(Request $request)
    {
        $target = $this->validateTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $count = $request->integer('count', 5);
        if ($count < 1 || $count > 100) {
            return $this->errorResponse('Ping count harus antara 1 hingga 100.');
        }

        $command = 'ping -4 -c '.$count.' -W 3 '.escapeshellarg($target);
        $timeout = max(30, $count * 4 + 5);

        $response = $this->runCommand('Ping', $target, $command, $timeout);

        // Lampirkan status asli perangkat dari database (bila target adalah
        // perangkat terdaftar), agar Terminal Output sinkron dengan status
        // sebenarnya alih-alih hanya menebak dari packet loss uji manual.
        $payload = $response->getData(true);
        $payload['device'] = $this->deviceInfo($request->input('device_id'), $target);

        return response()->json($payload);
    }

    public function trace(Request $request)
    {
        $target = $this->validateTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $hops = $request->integer('hops', 32);
        if ($hops < 1 || $hops > 64) {
            return $this->errorResponse('Trace max hops harus antara 1 hingga 64.');
        }

        if ($this->commandExists('traceroute')) {
            $command = 'traceroute -m '.$hops.' -w 2 -q 1 '.escapeshellarg($target);
        } elseif ($this->commandExists('tracepath')) {
            $command = 'tracepath -m '.$hops.' '.escapeshellarg($target);
        } else {
            return $this->errorResponse('Perintah traceroute / tracepath tidak tersedia di server ini.');
        }

        return $this->runCommand('Trace', $target, $command, 90);
    }

    public function lookup(Request $request)
    {
        $target = $this->validateTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $types  = ['A', 'AAAA', 'CNAME', 'MX', 'NS'];
        $lookup = strtoupper($request->input('lookup_type', 'A'));
        if (!in_array($lookup, $types, true)) {
            return $this->errorResponse('Lookup type harus salah satu dari: '.implode(', ', $types).'.');
        }

        $server = trim($request->input('lookup_server', ''));
        if ($server !== '' && $this->validateTarget($server) === null) {
            return $this->errorResponse('Lookup server tidak valid.');
        }

        if ($this->commandExists('nslookup')) {
            $command = 'nslookup -type='.$lookup.' -timeout=5 '.escapeshellarg($target);
            if ($server !== '') {
                $command .= ' '.escapeshellarg($server);
            }
        } else {
            $command = 'getent ahosts '.escapeshellarg($target);
        }

        return $this->runCommand('Lookup ('.$lookup.')', $target, $command, 30);
    }

    public function scan(Request $request)
    {
        $target = $this->validateTarget($request->input('target'));
        if ($target === null) {
            return $this->errorResponse('Target IP / domain tidak valid.');
        }

        $first = $request->integer('first_port', 1);
        $last  = $request->integer('last_port', 512);

        if ($first < 1 || $last < 1 || $first > 65535 || $last > 65535) {
            return $this->errorResponse('Port harus berada pada rentang 1 hingga 65535.');
        }

        if ($first > $last) {
            return $this->errorResponse('Scan first port harus lebih kecil atau sama dengan scan last port.');
        }

        $range = $last - $first + 1;
        if ($range > 1024) {
            return $this->errorResponse('Rentang port maksimal 1024 port per scan.');
        }

        $timeout = $request->integer('scan_timeout', 300);
        if ($timeout < 100 || $timeout > 2000) {
            return $this->errorResponse('Scan timeout harus antara 100 hingga 2000 ms.');
        }

        $phpScript = <<<'PHP'
$target  = $argv[1];
$first   = (int) $argv[2];
$last    = (int) $argv[3];
$timeout = max(0.1, min(2.0, (int) $argv[4] / 1000));
$deadline = microtime(true) + $timeout;
$socks  = [];
foreach (range($first, $last) as $p) {
    $fp = @stream_socket_client("tcp://{$target}:{$p}", $errno, $errstr, 0.0);
    if (is_resource($fp)) {
        stream_set_blocking($fp, false);
        $socks[$p] = $fp;
    }
}
$left = $socks;
while ($left && microtime(true) < $deadline) {
    $r = array_values($left);
    $w = $r;
    $e = null;
    if (@stream_select($r, $w, $e, 0, 50000) === false) {
        break;
    }
    foreach ($w as $fp) {
        $p = array_search($fp, $left, true);
        if ($p === false) {
            continue;
        }
        $peer = '';
        $sock = @socket_import_stream($fp);
        if ($sock !== false && @socket_getpeername($sock, $peer) === true) {
            printf("%-6d  open\n", $p);
            fclose($fp);
            unset($left[$p]);
        }
    }
}
foreach ($left as $fp) {
    if (is_resource($fp)) {
        fclose($fp);
    }
}
PHP;

        $command = 'php -r '.escapeshellarg($phpScript).' '.escapeshellarg($target).' '.$first.' '.$last.' '.$timeout;

        return $this->runCommand('Scan :'.$first.'-'.$last, $target.':'.$first.'-'.$last, $command, 90);
    }

    protected function validateTarget(?string $value): ?string
    {
        if (!is_string($value) || $value === '' || mb_strlen($value) > 255) {
            return null;
        }

        $value = trim($value);

        if ((bool) filter_var($value, FILTER_VALIDATE_IP) ||
            (bool) filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return $value;
        }

        return null;
    }

    protected function commandExists(string $name): bool
    {
        try {
            $result = Process::timeout(5)->run('command -v '.escapeshellarg($name).' 2>/dev/null');

            return $result->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Status perangkat dari database (dipakai Terminal Output agar ringkasan
     * status sinkron dengan data monitoring, bukan sekadar tebakan dari hasil
     * uji ping manual).
     *
     * - device_id dipakai bila dikirim (target berasal dari daftar perangkat).
     * - Fallback: cari berdasarkan IP/hostname target (cek tunjuk ke perangkat
     *   terdaftar walau dites lewat Target Kustom).
     * - device_id yang tidak konsisten dengan target diabaikan.
     *
     * @return array<string, mixed>|null
     */
    protected function deviceInfo(mixed $deviceId, string $target): ?array
    {
        $device = null;

        if (is_numeric($deviceId)) {
            $device = Device::find((int) $deviceId);
            if ($device && (string) $device->ip_address !== $target) {
                $device = null; // target berbeda dari perangkat terpilih
            }
        }

        if (! $device) {
            $device = Device::where('ip_address', $target)->first();
        }

        if (! $device) {
            return null;
        }

        $lastLog = MonitoringLog::query()
            ->where('device_id', $device->id)
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->first();

        return [
            'id'           => (int) $device->id,
            'name'         => (string) $device->name,
            'ip_address'   => (string) $device->ip_address,
            'status'       => in_array($device->status, ['up', 'down', 'warning'], true)
                ? (string) $device->status
                : 'unknown',
            'avg_delay'    => $lastLog?->response_time !== null
                ? (float) $lastLog->response_time
                : null,
            'last_checked' => $lastLog?->checked_at?->format('d/m/y H:i:s'),
        ];
    }

    /**
     * Jalankan perintah melalui Laravel Process facade (pembungkus Symfony Process).
     * Tangkap exception asli agar pesan error yang muncul informatif.
     */
    protected function runCommand(string $label, string $summary, string $command, int $timeout): JsonResponse
    {
        $startedAt = microtime(true);
        $output    = null;
        $exitCode  = 0;
        $timedOut  = false;
        $errorMsg  = null;

        try {
            $result = Process::timeout($timeout)->run($command);

            $output   = trim($result->output());
            $exitCode = $result->exitCode();
        } catch (ProcessTimedOutException $e) {
            $timedOut  = true;
            $exitCode  = 127;
            $errorMsg  = $e->getMessage();
        } catch (\Throwable $e) {
            $exitCode  = 126;
            $errorMsg  = $e->getMessage();
        }

        $duration = round(microtime(true) - $startedAt, 2);

        $payload = [
            'success'  => $exitCode === 0,
            'label'    => $label,
            'summary'  => $summary,
            'command'  => $command,
            'exit'     => $exitCode,
            'duration' => $duration,
            'output'   => $output,
            'timedout' => $timedOut,
            'network_fault' => false,
            'error'    => $errorMsg,
        ];

        if ($exitCode !== 0) {
            foreach (['Host Unreachable', 'Destination Host Unreachable', 'No route to host', 'Request timeout', 'Name or service not known', 'Timed Out', 'packet loss', '0 received'] as $fault) {
                if (($output !== null && str_contains($output, $fault)) || ($errorMsg !== null && str_contains($errorMsg, $fault))) {
                    $payload['network_fault'] = true;
                    break;
                }
            }
        }

        return response()->json($payload);
    }

    protected function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 422);
    }
}