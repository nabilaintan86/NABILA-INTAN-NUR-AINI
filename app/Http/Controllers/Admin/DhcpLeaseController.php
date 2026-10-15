<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DhcpLease;
use App\Services\DeviceCsvService;
use App\Services\DhcpImporter;
use App\Services\DhcpRecognition;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class DhcpLeaseController extends Controller
{
    public const PER_PAGE_DEFAULT = 25;

    public const PER_PAGE_OPTIONS = [25, 50, 100];

    public function index(Request $request, DeviceCsvService $deviceSource)
    {
        $syncNotice = null;
        if (DhcpImporter::autoSyncEnabled() && ! DhcpLease::exists()) {
            $sync = $this->syncFromDefaultFile();
            $syncNotice = $sync['notice'];
        }

        $status = (string) $request->query('status', 'all');
        $status = in_array($status, ['all', 'aktif', 'expired', 'unknown'], true) ? $status : 'all';
        $location = trim((string) $request->query('location', ''));
        $vendor = trim((string) $request->query('vendor', ''));
        $router = trim((string) $request->query('router', ''));
        $q = trim((string) $request->query('q', ''));
        $perPage = (int) $request->query('per_page', self::PER_PAGE_DEFAULT);
        $perPage = in_array($perPage, self::PER_PAGE_OPTIONS, true)
            ? $perPage
            : self::PER_PAGE_DEFAULT;

        $now = now();
        $query = DhcpLease::query()->with('device');

        if ($q !== '') {
            $like = '%'.addcslashes($q, '%_').'%';
            $query->where('hostname', 'like', $like);
        }

        if ($location !== '') {
            $query->where('location', $location);
        }

        if ($vendor !== '') {
            $query->where('vendor', $vendor);
        }

        if ($router !== '') {
            $query->where('router', $router);
        }

        $this->applyStatus($query, $status, $now);

        $leases = $query
            ->orderByDesc('lease_end')
            ->orderByDesc('last_seen')
            ->paginate($perPage)
            ->withQueryString();

        $kpi = $this->kpi($now);
        $locations = $this->distinctValues('location');
        $vendors = $this->distinctValues('vendor');
        $routers = $this->distinctValues('router');
        $activeFilters = $this->activeFilters($request, [
            'status' => $status === 'all' ? null : ['label' => 'Status', 'value' => $status],
            'location' => $location === '' ? null : ['label' => 'Lokasi / POP', 'value' => $location],
            'vendor' => $vendor === '' ? null : ['label' => 'Vendor', 'value' => $vendor],
            'router' => $router === '' ? null : ['label' => 'Router', 'value' => $router],
            'q' => $q === '' ? null : ['label' => 'Hostname', 'value' => $q],
        ]);

        $recognition = new DhcpRecognition($deviceSource);
        $lastUpdatedAt = DhcpLease::query()->max('last_seen') ?: DhcpLease::query()->max('updated_at');

        return view('admin.dhcp-lease', [
            'kpi' => $kpi,
            'leases' => $leases,
            'status' => $status,
            'location' => $location,
            'vendor' => $vendor,
            'router' => $router,
            'q' => $q,
            'locations' => $locations,
            'vendors' => $vendors,
            'routers' => $routers,
            'activeFilters' => $activeFilters,
            'perPage' => $perPage,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
            'knownDeviceCount' => $recognition->knownCount(),
            'deviceSource' => $deviceSource->officialSummary(),
            'hasData' => DhcpLease::exists(),
            'syncNotice' => $syncNotice,
            'lastUpdatedAt' => $lastUpdatedAt ? Carbon::parse($lastUpdatedAt)->format('d M Y H:i:s') : '-',
        ]);
    }

    /**
     * Endpoint POST untuk import dari devices.csv di path default.
     */
    public function sync(Request $request)
    {
        $sync = $this->syncFromDefaultFile();

        if (! $sync['ok']) {
            return response()->json([
                'success' => false,
                'message' => $sync['notice'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $sync['message'],
            'counts' => $sync['counts'],
        ]);
    }

    /**
     * Import dari devices.csv di path default (storage/app/dhcp/devices.csv).
     * Dipakai oleh auto-sync (index) dan endpoint sync.
     *
     * @return array{ok: bool, notice: string, message?: string, counts?: array}
     */
    protected function syncFromDefaultFile(): array
    {
        $path = DhcpImporter::defaultFilePath();

        if (! is_file($path)) {
            return [
                'ok' => false,
                'notice' => 'File devices.csv belum ditemukan di storage/app/dhcp/devices.csv. '.
                    'Letakkan file tersebut lalu jalankan php artisan dhcp:import.',
            ];
        }

        try {
            $result = (new DhcpImporter)->importFile($path, 'auto:'.basename($path));

            return [
                'ok' => true,
                'message' => sprintf(
                    '%d lease berhasil di-import, %d Known, %d New, %d Unknown.',
                    $result['imported'],
                    $result['known'],
                    $result['new'],
                    $result['unknown']
                ),
                'counts' => $result,
                'notice' => sprintf(
                    '%d lease di-sinkronkan otomatis dari %s.',
                    $result['imported'],
                    basename($path)
                ),
            ];
        } catch (InvalidArgumentException $e) {
            return [
                'ok' => false,
                'notice' => $e->getMessage(),
            ];
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('csv_file');
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if ($extension !== 'csv') {
            return response()->json([
                'message' => 'File harus berformat .csv.',
            ], 422);
        }

        try {
            $result = (new DhcpImporter)->importString(
                (string) file_get_contents($file->getRealPath()),
                'upload:'.($file->getClientOriginalName() ?: 'devices.csv')
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil import {$result['imported']} lease, {$result['known']} device dikenali",
            'counts' => $result,
        ]);
    }

    protected function distinctValues(string $column)
    {
        return DhcpLease::query()
            ->whereNotNull($column)
            ->where($column, '<>', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->values();
    }

    protected function activeFilters(Request $request, array $filters): array
    {
        $active = [];

        foreach ($filters as $key => $filter) {
            if ($filter === null) {
                continue;
            }

            $query = $request->except('page');
            unset($query[$key]);

            $active[] = [
                'label' => $filter['label'],
                'value' => $filter['value'],
                'url' => route('admin.dhcp-lease', $query),
            ];
        }

        return $active;
    }

    protected function applyStatus($query, string $status, Carbon $now): void
    {
        if ($status === 'unknown') {
            $query->where('classification', 'unknown');

            return;
        }

        if ($status === 'aktif') {
            $query->where('classification', '!=', 'unknown')
                ->where(function ($builder) use ($now) {
                    $builder->whereNull('lease_end')
                        ->orWhere('lease_end', '>=', $now);
                });

            return;
        }

        if ($status === 'expired') {
            $query->where('classification', '!=', 'unknown')
                ->where('lease_end', '<', $now);
        }
    }

    protected function kpi(Carbon $now): array
    {
        $base = DhcpLease::query();

        $aktif = (clone $base)
            ->where('classification', '!=', 'unknown')
            ->where(function ($builder) use ($now) {
                $builder->whereNull('lease_end')
                    ->orWhere('lease_end', '>=', $now);
            })
            ->count();

        $expired = (clone $base)
            ->where('classification', '!=', 'unknown')
            ->where('lease_end', '<', $now)
            ->count();

        $unknown = (clone $base)
            ->where('classification', 'unknown')
            ->count();

        return [
            'total' => $base->count(),
            'aktif' => $aktif,
            'expired' => $expired,
            'unknown' => $unknown,
        ];
    }
}
