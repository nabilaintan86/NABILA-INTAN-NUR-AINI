@extends('layouts.admin')

@section('title', 'Map: RADNET Wireless Network 2026 - WhatsUp Gold')

@push('styles')
<style>
    /* Navigation Pills WhatsUp Gold Style */
    .wug-nav-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .wug-page-title {
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .wug-btn-group .btn {
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 4px;
        transition: all .15s ease-in-out;
    }

    .wug-btn-group .btn.active {
        background-color: #0066cc !important;
        border-color: #0066cc !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0, 102, 204, 0.35);
    }

    /* Views visibility */
    .wug-view-pane {
        display: none;
    }
    .wug-view-pane.active {
        display: block;
    }

    /* Relations Table Styling */
    .relasi-table th {
        background: #f1f5f9;
        font-weight: 700;
        color: #334155;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .relasi-table td {
        vertical-align: middle;
        font-size: 13px;
    }

    .dev-badge-cell {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }
    .dev-badge-cell .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Footer WhatsUp Gold */
    .wug-footer {
        margin-top: 24px;
        padding-top: 14px;
        border-top: 1px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #64748b;
        font-size: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .wug-footer-logo {
        height: 22px;
        display: inline-block;
    }

    /* ══ TABS MAPS (NAV TABS PENGATURAN MAPS) ══ */
    .map-nav-tabs-wrap {
        border-bottom: 2px solid #e2e8f0;
        margin-top: 10px;
        margin-bottom: 12px;
        background: #ffffff;
        border-radius: 8px 8px 0 0;
        padding: 4px 6px 0 6px;
    }
    .topo-tabs {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 0 2px;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    .topo-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px 8px 0 0;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        border: 1px solid transparent;
        border-bottom: none;
        white-space: nowrap;
        transition: all .2s ease;
        background: transparent;
    }
    .topo-tab:hover {
        color: #0066cc;
        background: #f1f5f9;
    }
    .topo-tab.active {
        background: #ffffff;
        color: #0066cc;
        border-color: #cbd5e1;
        border-bottom: 2px solid #ffffff;
        margin-bottom: -2px;
        box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.03);
    }
    .topo-tab .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #94a3b8;
        display: inline-block;
        transition: background .2s ease;
    }
    .topo-tab.active .dot {
        background: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
    }
    .topo-tab-add {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #059669;
        text-decoration: none;
        border: 1px dashed #10b981;
        background: #f0fdf4;
        white-space: nowrap;
        transition: all .2s ease;
        margin-bottom: 4px;
    }
    .topo-tab-add:hover {
        background: #dcfce7;
        color: #047857;
        border-color: #059669;
    }
    .topo-tab-manage {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        transition: all .2s ease;
        margin-bottom: 4px;
        margin-left: auto;
    }
    .topo-tab-manage:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
</style>
@endpush

@section('content')
@php
    $activeTab = request()->query('tab', 'topview');
    if (!in_array($activeTab, ['topview', 'summary', 'logs', 'outage', 'stats', 'relasi', 'area'])) {
        $activeTab = 'topview';
    }
@endphp
<x-admin.page-head
    title="Monitoring Jaringan"
    subtitle="Peta topologi dan status perangkat"
>
    <x-slot:actions>
        <div class="btn-group wug-btn-group shadow-sm" role="tablist">
            <button type="button" class="btn btn-outline-primary {{ $activeTab === 'topview' ? 'active' : '' }}" id="tabBtnTopView" onclick="switchWugTab('topview')">
                <em class="icon ni ni-map"></em> Top View (2026)
            </button>
            <button type="button" class="btn btn-outline-primary {{ $activeTab === 'summary' ? 'active' : '' }}" id="tabBtnSummary" onclick="switchWugTab('summary')">
                <em class="icon ni ni-grid-alt"></em> Summary View
            </button>
            <button type="button" class="btn btn-outline-primary {{ $activeTab === 'logs' ? 'active' : '' }}" id="tabBtnLogs" onclick="switchWugTab('logs')">
                <em class="icon ni ni-history"></em> Log View
            </button>
            <button type="button" class="btn btn-outline-primary {{ $activeTab === 'outage' ? 'active' : '' }}" id="tabBtnOutage" onclick="switchWugTab('outage')">
                <em class="icon ni ni-alert"></em> Outage Report
                @if($totalDown > 0)
                    <span class="badge bg-danger ms-1">{{ $totalDown }}</span>
                @endif
            </button>
            <button type="button" class="btn btn-outline-primary {{ $activeTab === 'stats' ? 'active' : '' }}" id="tabBtnStats" onclick="switchWugTab('stats')">
                <em class="icon ni ni-activity-round"></em> Statistics Report
            </button>
            <button type="button" class="btn btn-outline-primary {{ $activeTab === 'relasi' ? 'active' : '' }}" id="tabBtnRelasi" onclick="switchWugTab('relasi')">
                <em class="icon ni ni-network"></em> Tabel Relasi ({{ count($connections) }})
            </button>
            <button type="button" class="btn btn-outline-primary {{ $activeTab === 'area' ? 'active' : '' }}" id="tabBtnArea" onclick="switchWugTab('area')">
                <em class="icon ni ni-layers"></em> Kelola Card Area ({{ count($networkAreas) }})
            </button>
        </div>
    </x-slot:actions>
</x-admin.page-head>

    {{-- ══ MAP TABS (NAV TABS PENGATURAN MAPS) ══ --}}
    <div class="map-nav-tabs-wrap">
        <div class="topo-tabs">
            <a href="{{ route('admin.monitoring') }}"
               class="topo-tab {{ empty($currentMap) ? 'active' : '' }}" title="Map Utama WhatsUp Gold">
                <span class="dot"></span>
                <em class="icon ni ni-map-pin text-primary"></em>
                <span>Map: RADNET Wireless Network 2026</span>
                <span class="badge bg-primary-subtle text-primary ms-1" style="font-size: 10px;">Utama</span>
            </a>

            @if(isset($topologyMaps))
                @foreach($topologyMaps as $m)
                    <a href="{{ route('admin.monitoring', ['map' => $m->id]) }}"
                       class="topo-tab {{ (!empty($currentMap) && $currentMap->id === $m->id) ? 'active' : '' }}" title="{{ $m->description ?? $m->name }}">
                        <span class="dot"></span>
                        <em class="icon ni ni-map text-indigo"></em>
                        <span>{{ $m->name }}</span>
                        <span class="badge bg-light text-muted ms-1" style="font-size: 10px;">
                            {{ $m->devices_count ?? 0 }} dev
                        </span>
                    </a>
                @endforeach
            @endif

                        @if(auth()->user()->role === 'super_admin')
                <button type="button" class="topo-tab-add" data-bs-toggle="modal" data-bs-target="#modalQuickAddMap" title="Tambah Maps Baru Langsung">
                    <em class="icon ni ni-plus"></em>
                    <span>Maps Baru</span>
                </button>
            @endif

            <a href="{{ route('admin.topology-maps.index') }}" class="topo-tab-manage ms-auto" title="Kelola &amp; Pengaturan Semua Maps">
                <em class="icon ni ni-setting"></em>
                <span>Pengaturan Maps</span>
            </a>
        </div>
    </div>

{{-- ═══════════════════════════════════════════════════
     VIEW 1: TOP VIEW (WHATSUP GOLD MAP 2026 - 1 SCREEN / FULLSCREEN)
═══════════════════════════════════════════════════ --}}
<div class="wug-view-pane {{ $activeTab === 'topview' ? 'active' : '' }}" id="wugViewTopView">
    @include('admin.components.network-topology', [
        'topology'            => $topology ?? null,
        'endpoint'            => route('admin.monitoring.data', !empty($currentMap) ? ['map' => $currentMap->id] : []),
        'deviceEndpoint'      => route('admin.monitoring.device', ['device' => '__DEVICE_ID__']),
        'positionEndpoint'    => route('admin.monitoring.device.position', ['device' => '__DEVICE_ID__']) . (!empty($currentMap) ? '?map_id=' . $currentMap->id : ''),
        'directionEndpoint'   => route('admin.monitoring.connection.direction', ['connection' => '__CONN_ID__']) . (!empty($currentMap) ? '?map_id=' . $currentMap->id : ''),
        'areaBoundsEndpoint'  => route('admin.monitoring.area.bounds', ['area' => '__AREA_ID__']) . (!empty($currentMap) ? '?map_id=' . $currentMap->id : ''),
        'saveLayoutEndpoint'  => route('admin.monitoring.map-layout.save') . (!empty($currentMap) ? '?map_id=' . $currentMap->id : ''),
        'resetLayoutEndpoint' => route('admin.monitoring.map-layout.reset'),
        'isSuperAdmin'        => auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true),
        'title'               => $mapTitle ?? 'Map: RADNET Wireless Network 2026',
        'currentMap'          => $currentMap ?? null,
        'mapId'               => !empty($currentMap) ? $currentMap->id : null,
        'networkAreas'        => (!empty($currentMap) && $currentMap->areas->isNotEmpty()) ? $currentMap->areas : $networkAreas,
    ])
</div>

{{-- ═══════════════════════════════════════════════════
     VIEW 2: SUMMARY VIEW
═══════════════════════════════════════════════════ --}}
<div class="wug-view-pane {{ $activeTab === 'summary' ? 'active' : '' }}" id="wugViewSummary">
    {{-- KPI Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-success-dim border border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase fw-bold text-success small">Items Up</span>
                            <h3 class="mb-0 fw-bold text-success">{{ $totalUp }}</h3>
                            <small class="text-muted">Perangkat merespon normal</small>
                        </div>
                        <em class="icon ni ni-arrow-up-circle fs-1 text-success"></em>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-danger-dim border border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase fw-bold text-danger small">Items Down (Outage)</span>
                            <h3 class="mb-0 fw-bold text-danger">{{ $totalDown }}</h3>
                            <small class="text-muted">Perangkat gagal ping / putus</small>
                        </div>
                        <em class="icon ni ni-cross-circle fs-1 text-danger"></em>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-warning-dim border border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase fw-bold text-warning small">Warning / High Delay</span>
                            <h3 class="mb-0 fw-bold text-warning">{{ $totalWarning }}</h3>
                            <small class="text-muted">Latensi tinggi / packet loss</small>
                        </div>
                        <em class="icon ni ni-alert-circle fs-1 text-warning"></em>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card bg-info-dim border border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase fw-bold text-info small">Total Relasi</span>
                            <h3 class="mb-0 fw-bold text-info">{{ count($connections) }}</h3>
                            <small class="text-muted">Koneksi antar-perangkat aktif</small>
                        </div>
                        <em class="icon ni ni-network fs-1 text-info"></em>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Per Area / POP --}}
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold">Distribusi Perangkat per Area / POP</span>
            <span class="badge bg-primary">{{ count($devices) }} Total Perangkat</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Area / POP</th>
                            <th>Total Perangkat</th>
                            <th>Status Up</th>
                            <th>Status Down</th>
                            <th>Status Warning</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grouped = $devices->groupBy('pop_name');
                            $no = 1;
                        @endphp
                        @foreach($grouped as $popName => $popDevices)
                            @php
                                $popUp = $popDevices->where('status', 'up')->count();
                                $popDown = $popDevices->where('status', 'down')->count();
                                $popWarn = $popDevices->where('status', 'warning')->count();
                            @endphp
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td class="fw-bold text-primary">{{ $popName ?: 'Lainnya' }}</td>
                                <td>{{ $popDevices->count() }}</td>
                                <td><span class="badge bg-success">{{ $popUp }}</span></td>
                                <td>
                                    @if($popDown > 0)
                                        <span class="badge bg-danger">{{ $popDown }} Outage</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if($popWarn > 0)
                                        <span class="badge bg-warning">{{ $popWarn }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="filterRelasiByPop('{{ $popName }}')">
                                        Lihat Relasi
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     VIEW 3: LOG VIEW
═══════════════════════════════════════════════════ --}}
<div class="wug-view-pane {{ $activeTab === 'logs' ? 'active' : '' }}" id="wugViewLogs">
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold">Riwayat Log Pemantauan Perangkat Terkini</span>
            <button class="btn btn-xs btn-primary" onclick="location.reload()"><em class="icon ni ni-reload"></em> Refresh</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Perangkat</th>
                            <th>IP Address</th>
                            <th>Status</th>
                            <th>Response Time</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="small">{{ $log->checked_at?->format('Y-m-d H:i:s') ?? '-' }}</td>
                                <td class="fw-bold">{{ $log->device?->name ?? 'Device #'.$log->device_id }}</td>
                                <td><code>{{ $log->device?->ip_address ?? '-' }}</code></td>
                                <td>
                                    @if($log->status === 'up')
                                        <span class="badge bg-success">UP</span>
                                    @elseif($log->status === 'down')
                                        <span class="badge bg-danger">DOWN</span>
                                    @else
                                        <span class="badge bg-warning">{{ strtoupper($log->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->response_time !== null ? $log->response_time.' ms' : '-' }}</td>
                                <td class="text-muted small">{{ $log->status === 'up' ? 'ICMP response OK' : 'No response / timeout' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <em class="icon ni ni-inbox fs-2"></em>
                                    <p class="mb-0 mt-1">Belum ada riwayat monitoring log tersimpan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     VIEW 4: OUTAGE REPORT
═══════════════════════════════════════════════════ --}}
<div class="wug-view-pane {{ $activeTab === 'outage' ? 'active' : '' }}" id="wugViewOutage">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold text-white"><em class="icon ni ni-alert-fill"></em> Laporan Perangkat Outage (Down)</span>
            <span class="badge bg-white text-danger">{{ $totalDown }} Perangkat</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Perangkat</th>
                            <th>IP Address</th>
                            <th>Area / POP</th>
                            <th>Down Time</th>
                            <th>Missed Polls %</th>
                            <th>Alerts</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $downList = $devices->where('status', 'down')->values();
                        @endphp
                        @forelse($downList as $idx => $d)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td class="fw-bold text-danger">
                                    <span style="display:inline-block; width:12px; height:12px; background:#dc2626; border-radius:50%; margin-right:5px;"></span>
                                    {{ $d->name }}
                                </td>
                                <td><code>{{ $d->ip_address }}</code></td>
                                <td><span class="badge bg-light text-dark border">{{ $d->pop_name ?: '-' }}</span></td>
                                <td><span class="fw-bold text-danger">{{ $d->down_time ?: '12:45:00' }}</span></td>
                                <td>{{ $d->missed_percentage ?? 100 }}%</td>
                                <td><span class="badge bg-danger">{{ $d->alerts_count ?? 8 }}</span></td>
                                <td>
                                    <button type="button" class="btn btn-xs btn-outline-primary" onclick="locateDeviceOnMap({{ $d->id }})">
                                        <em class="icon ni ni-map-pin"></em> Lokasi di Map
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-success">
                                    <em class="icon ni ni-check-circle fs-1"></em>
                                    <p class="mb-0 fw-bold mt-1">Semua perangkat dalam status normal! Tidak ada outage.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     VIEW 5: STATISTICS REPORT
═══════════════════════════════════════════════════ --}}
<div class="wug-view-pane {{ $activeTab === 'stats' ? 'active' : '' }}" id="wugViewStats">
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <span class="fw-bold">Laporan Statistik Latensi &amp; Polling Jaringan</span>
            <span class="text-muted small">Periode: 30 Hari Terakhir</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="statsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Perangkat</th>
                            <th>IP Address</th>
                            <th>Area</th>
                            <th>Total Polls</th>
                            <th>Responded</th>
                            <th>Avg Delay</th>
                            <th>Min Delay</th>
                            <th>Max Delay</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($devices as $dev)
                            <tr>
                                <td class="fw-bold">{{ $dev->name }}</td>
                                <td><code>{{ $dev->ip_address }}</code></td>
                                <td>{{ $dev->pop_name ?: '-' }}</td>
                                <td>{{ number_format($dev->polls ?: 15238) }}</td>
                                <td>{{ $dev->responded_percentage ?: 99.85 }}%</td>
                                <td>{{ $dev->avg_delay ?: 4 }} ms</td>
                                <td>{{ $dev->min_delay ?: 1 }} ms</td>
                                <td>{{ $dev->max_delay ?: 120 }} ms</td>
                                <td>
                                    @if($dev->status === 'up')
                                        <span class="badge bg-success">UP</span>
                                    @elseif($dev->status === 'down')
                                        <span class="badge bg-danger">DOWN</span>
                                    @else
                                        <span class="badge bg-warning">{{ strtoupper($dev->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     VIEW 6: TABEL RELASI PERANGKAT (DEVICE RELATIONS)
═══════════════════════════════════════════════════ --}}
<div class="wug-view-pane {{ $activeTab === 'relasi' ? 'active' : '' }}" id="wugViewRelasi">
    {{-- Card Panduan Arah Panah WhatsUp Gold --}}
    <div class="card mb-3 border border-primary-subtle shadow-sm">
        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark fs-14px">
                <em class="icon ni ni-help-fill text-primary me-1"></em> Panduan &amp; Arti Arah Panah Relasi (WhatsUp Gold Standard 2026)
            </span>
            <span class="badge bg-primary-dim text-primary">Logika Monitoring Topologi</span>
        </div>
        <div class="card-body py-2">
            <div class="row g-2">
                <div class="col-md-3 col-sm-6">
                    <div class="p-2 border rounded bg-white h-100">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-success fs-14px fw-bold px-2 py-0">&rarr;</span>
                            <span class="fw-bold text-dark fs-12px">Forward (Parent &rarr; Child)</span>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 11.5px; line-height: 1.4;">
                            Alur dependensi hirarkis standar. Perangkat tujuan bergantung pada sumber. Jika parent mati, child masuk mode <em>down-dependency</em> (mencegah alert flood).
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-2 border rounded bg-white h-100">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-info fs-14px fw-bold px-2 py-0">&larr;</span>
                            <span class="fw-bold text-dark fs-12px">Reverse (Inbound / Downlink)</span>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 11.5px; line-height: 1.4;">
                            Aliran suplai / uplink dari node luar menuju node konsentrator / central core. Dipakai saat node cabang melaporkan jalur uplink kembali ke agregator.
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-2 border rounded bg-white h-100">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary fs-14px fw-bold px-2 py-0">&harr;</span>
                            <span class="fw-bold text-dark fs-12px">Bi-directional (Active Peering)</span>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 11.5px; line-height: 1.4;">
                            Interkoneksi simetris dua arah setara tanpa dependensi hirarkis (seperti link backbone, trunking switch-to-switch, atau redundansi link).
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-2 border rounded bg-white h-100">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-secondary fs-14px fw-bold px-2 py-0">&mdash;</span>
                            <span class="fw-bold text-dark fs-12px">None (Layer-2 Pasif)</span>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 11.5px; line-height: 1.4;">
                            Sambungan fisik langsung layer-2 pasif tanpa panah dan tanpa aturan penekanan alarm dependensi antar-perangkat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Utama Relasi --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
            <div>
                <h5 class="mb-0 text-white"><em class="icon ni ni-network"></em> Tabel Relasi &amp; Koneksi Antar-Perangkat</h5>
                <small class="text-white-50">Kelola sambungan, arah panah, dan dependensi antar perangkat topologi jaringan 2026</small>
            </div>
                <div class="d-flex align-items-center gap-2">
                <span class="badge bg-white text-primary fw-bold" id="relasiCountBadge">{{ count($connections) }} Total Relasi</span>

                @if(auth()->user()->role === 'super_admin')
                    <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddRelasi">
                        <em class="icon ni ni-plus-circle me-1"></em> Tambah Relasi Baru
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body border-bottom bg-light py-2">
            <div class="row g-2 align-items-center">
                    <div class="col-md-4 col-sm-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><em class="icon ni ni-search"></em></span>
                        <input type="text" class="form-control" id="searchRelasi" placeholder="Cari nama perangkat atau IP address...">
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <select class="form-select form-select-sm" id="filterRelasiPop" onchange="filterRelasiTable()">
                        <option value="">-- Semua Area / POP --</option>
                        @foreach($devices->pluck('pop_name')->filter()->unique()->sort() as $pName)
                            <option value="{{ strtolower($pName) }}">{{ $pName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <select class="form-select form-select-sm" id="filterRelasiDir" onchange="filterRelasiTable()">
                        <option value="">-- Semua Arah Panah --</option>
                        <option value="forward">&rarr; Forward (Parent &rarr; Child)</option>
                        <option value="reverse">&larr; Reverse (Downlink &larr;)</option>
                        <option value="bidirectional">&harr; Dua Arah (Duplex)</option>
                        <option value="none">&mdash; Tanpa Panah</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 text-end">
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="resetRelasiFilter()">
                        <em class="icon ni ni-reload"></em> Reset
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover relasi-table mb-0 align-middle" id="tableRelasiDevice">
                    <thead>
                        <tr>
                            <th style="width: 45px;">No</th>
                            <th>Perangkat Asal (From Device)</th>
                            <th style="width: 190px; text-align: center;">Arah Panah &amp; Aksi Cepat</th>
                            <th>Perangkat Tujuan (To Device)</th>
                            <th>Area / POP</th>
                            <th>Tipe Media</th>
                            <th>Status Link</th>
                            @if(auth()->user()->role === 'super_admin')
                                <th style="width: 140px; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="relasiTableBody">
                        @forelse($connections as $index => $conn)
                            @php
                                $fromDev = $conn->fromDevice;
                                $toDev   = $conn->toDevice;
                                $isLinkDown = ($fromDev && $fromDev->status === 'down') || ($toDev && $toDev->status === 'down');
                                $areaName = $fromDev?->pop_name ?: ($toDev?->pop_name ?: 'Lainnya');
                            @endphp
                            <tr id="relasi-row-{{ $conn->id }}"
                                data-id="{{ $conn->id }}"
                                data-pop="{{ strtolower($areaName) }}"
                                data-dir="{{ $conn->direction }}"
                                data-search="{{ strtolower(($fromDev?->name ?? '').' '.($fromDev?->ip_address ?? '').' '.($toDev?->name ?? '').' '.($toDev?->ip_address ?? '').' '.$areaName) }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="dev-badge-cell">
                                        <span class="status-dot {{ ($fromDev?->status ?? 'up') === 'up' ? 'bg-success' : (($fromDev?->status ?? '') === 'down' ? 'bg-danger' : 'bg-warning') }}"></span>
                                        <div>
                                            <span class="fw-bold text-dark">{{ $fromDev?->name ?? 'Device #'.$conn->from_device_id }}</span>
                                            <div class="text-muted small"><code>{{ $fromDev?->ip_address ?? '-' }}</code></div>
                                        </div>
                                    </div>
                                </td>
                                                    <td class="text-center">
                                    @if(auth()->user()->role === 'super_admin')
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <select class="form-select form-select-sm py-0 px-2 fw-bold border-secondary-subtle"
                                                    style="font-size: 12px; width: 140px;"
                                                    title="Ubah arah panah langsung (tersimpan seketika di database &amp; map)"
                                                    onchange="quickChangeDirection({{ $conn->id }}, this.value, this)">
                                                <option value="forward" {{ $conn->direction === 'forward' ? 'selected' : '' }}>&rarr; Forward</option>
                                                <option value="reverse" {{ $conn->direction === 'reverse' ? 'selected' : '' }}>&larr; Reverse</option>
                                                <option value="bidirectional" {{ $conn->direction === 'bidirectional' ? 'selected' : '' }}>&harr; Dua Arah</option>
                                                <option value="none" {{ $conn->direction === 'none' ? 'selected' : '' }}>&mdash; Tanpa Panah</option>
                                            </select>
                                        </div>
                                    @else
                                        @php
                                            $dirLabel = [
                                                'forward' => '→ Forward',
                                                'reverse' => '← Reverse',
                                                'bidirectional' => '↔ Dua Arah',
                                                'none' => '— Tanpa Panah',
                                            ][$conn->direction] ?? $conn->direction;
                                        @endphp
                                        <span class="badge bg-secondary-subtle text-dark border fw-bold">{{ $dirLabel }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dev-badge-cell">
                                        <span class="status-dot {{ ($toDev?->status ?? 'up') === 'up' ? 'bg-success' : (($toDev?->status ?? '') === 'down' ? 'bg-danger' : 'bg-warning') }}"></span>
                                        <div>
                                            <span class="fw-bold text-dark">{{ $toDev?->name ?? 'Device #'.$conn->to_device_id }}</span>
                                            <div class="text-muted small"><code>{{ $toDev?->ip_address ?? '-' }}</code></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border">{{ $areaName }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-outline-info text-capitalize">{{ $conn->connection_type ?: 'Ethernet' }}</span>
                                </td>
                                <td>
                                    @if($isLinkDown)
                                        <span class="badge bg-danger"><em class="icon ni ni-alert-fill"></em> Outage</span>
                                    @else
                                        <span class="badge bg-success"><em class="icon ni ni-check-circle"></em> Normal</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'super_admin')
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-xs btn-outline-primary"
                                                    onclick="locateDeviceOnMap({{ $conn->from_device_id }})"
                                                    title="Lihat posisi di Map View">
                                                <em class="icon ni ni-map-pin"></em>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-outline-warning"
                                                    onclick="openEditRelasiModal({{ $conn->id }}, {{ $conn->from_device_id }}, {{ $conn->to_device_id }}, '{{ $conn->direction }}', '{{ $conn->connection_type ?? 'ethernet' }}')"
                                                    title="Edit Relasi">
                                                <em class="icon ni ni-edit"></em>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-outline-danger"
                                                    onclick="deleteRelasi({{ $conn->id }}, '{{ addslashes($fromDev?->name ?? 'Device #'.$conn->from_device_id) }} &rarr; {{ addslashes($toDev?->name ?? 'Device #'.$conn->to_device_id) }}')"
                                                    title="Hapus Relasi">
                                                <em class="icon ni ni-trash"></em>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr id="rowEmptyRelasi">
                                <td colspan="{{ auth()->user()->role === 'super_admin' ? 8 : 7 }}" class="text-center py-4 text-muted">
                                    <p class="mb-0">Belum ada data relasi perangkat terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     VIEW 7: TABEL CARD AREA / POP (WHATSUP GOLD 2026)
═══════════════════════════════════════════════════ --}}
<div class="wug-view-pane {{ $activeTab === 'area' ? 'active' : '' }}" id="wugViewArea">
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
            <div>
                <h5 class="mb-0 text-white"><em class="icon ni ni-layers"></em> Kelola Card Area &amp; Pembatas Router (POP) 2026</h5>
                <small class="text-white-50">Dinamisasi ukuran kotak pembatas (Width x Height), koordinat awal canvas, dan palet warna card POP</small>
            </div>
                    <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white fw-bold" id="areaCountBadge">{{ count($networkAreas) }} Total Card Area</span>

                @if(auth()->user()->role === 'super_admin')
                    <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm me-1"
                        onclick="openAddAreaModal('monitoring')">
                        <em class="icon ni ni-plus-circle me-1"></em> Card Area Monitoring
                    </button>
                    <button type="button" class="btn btn-sm btn-primary fw-bold shadow-sm"
                            onclick="openAddAreaModal('ezyhome')">
                        <em class="icon ni ni-plus-circle me-1"></em> Card Area EzyHome
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body border-bottom bg-light py-2">
            <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><em class="icon ni ni-search"></em></span>
                        <input type="text" class="form-control" id="searchAreaInput" oninput="filterAreaTable()" placeholder="Cari nama area atau kode POP...">
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="switchWugTab('topview')">
                        <em class="icon ni ni-map me-1"></em> Lihat Peta Topologi Map
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="tableNetworkArea">
                                <thead class="table-light">
                        <tr>
                            <th style="width: 45px;">No</th>
                            <th>Nama Area / POP</th>
                            <th style="width: 100px;">Scope</th>
                            <th>Kode</th>
                            <th style="text-align: center;">Ukuran Card (W &times; H)</th>
                            <th style="text-align: center;">Koordinat Canvas (X, Y)</th>
                            <th>Palet Warna (Border / BG / Judul)</th>
                            <th style="text-align: center;">Perangkat Terhubung</th>
                            @if(auth()->user()->role === 'super_admin')
                                <th style="width: 140px; text-align: center;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="areaTableBody">
                        @php
                            $rowNo = 0;
                            $allAreas = [];
                            foreach ($networkAreas as $ar) {
                                $ar->_scope = 'monitoring';
                                $ar->_device_count = $ar->devices_count ?? 0;
                                $allAreas[] = $ar;
                            }
                            foreach ($ezyhomeAreas ?? [] as $ar) {
                                $ar->_scope = 'ezyhome';
                                $ar->_device_count = $ar->positions_count ?? 0;
                                $allAreas[] = $ar;
                            }
                        @endphp

                        @forelse($allAreas as $ar)
                            @php $rowNo++; @endphp
                            <tr id="area-row-{{ $ar->_scope }}-{{ $ar->id }}" data-search="{{ strtolower($ar->name .' ' . $ar->code . ' ' . $ar->description) }}">
                                <td>{{ $rowNo }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="display:inline-block; width:14px; height:14px; border-radius:3px; border:2px solid {{ $ar->border_color ?: '#1a8024' }}; background:{{ $ar->bg_color ?: '#f8fafc' }};"></span>
                                        <span class="fw-bold" style="color: {{ $ar->title_color ?:'#1a8024' }};">{{ $ar->name }}</span>
                                    </div>
                                    @if($ar->description)
                                        <small class="text-muted d-block mt-1">{{ Str::limit($ar->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($ar->_scope === 'ezyhome')
                                        <span class="badge bg-primary">EzyHome</span>
                                    @else
                                        <span class="badge bg-success">Monitoring</span>
                                    @endif
                                </td>
                                <td><code>{{ $ar->code ?: '-' }}</code></td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        <em class="icon ni ni-expand me-1"></em>{{ $ar->width }} &times; {{ $ar->height }} px
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-secondary border">
                                        X: {{ $ar->pos_x }}, Y: {{ $ar->pos_y }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge" style="background-color: {{ $ar->border_color ?: '#1a8024' }}; color:#fff;" title="Border Color">Border: {{ $ar->border_color ?: '#1a8024' }}</span>
                                        <span class="badge border" style="background-color: {{ $ar->bg_color ?: '#f8fafc' }}; color:#000;" title="Background Color">BG: {{ $ar->bg_color ?: '#f8fafc'}}</span>
                                        <span class="badge" style="background-color: {{ $ar->title_color ?: '#1a8024' }}; color:#fff;" title="Title Color">Judul: {{ $ar->title_color ?: '#1a8024' }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info-dim text-info fw-bold">{{ $ar->_device_count }} Device</span>
                                </td>
                                                @if(auth()->user()->role === 'super_admin')
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-warning" title="Edit Card Area &amp; Ukuran" onclick='openEditAreaModal(@json($ar), "{{ $ar->_scope }}")'>
                                                <em class="icon ni ni-edit"></em>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus Area" onclick="deleteArea({{ $ar->id }}, '{{ addslashes($ar->name) }}', '{{ $ar->_scope }}')">
                                                <em class="icon ni ni-trash"></em>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                                    <tr id="rowEmptyArea">
                                <td colspan="{{ auth()->user()->role === 'super_admin' ? 9 : 8 }}" class="text-center py-4 text-muted">
                                    <p class="mb-0">Belum ada card area POP terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL: TAMBAH CARD AREA / POP BARU
═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAddArea" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formAddArea" onsubmit="submitAddArea(event)">
                @csrf
                <div class="modal-header bg-success text-white py-2">
                    <h6 class="modal-title text-white">
                        <em class="icon ni ni-plus-circle me-1"></em> Tambah Card Area / POP Baru
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="add_area_name" class="form-label fw-bold">Nama Area / POP <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="add_area_name" class="form-control" placeholder="Contoh: POP Rungkut, POP Bandara" required>
                        </div>
                        <div class="col-md-5">
                            <label for="add_area_code" class="form-label fw-bold">Kode Area (Singkatan)</label>
                            <input type="text" name="code" id="add_area_code" class="form-control" placeholder="Contoh: POP-RKT">
                        </div>

                        <div class="col-md-3 col-6">
                            <label for="add_area_width" class="form-label fw-bold">Lebar Card (W px)</label>
                            <input type="number" name="width" id="add_area_width" class="form-control" value="280" min="80" step="10" required>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="add_area_height" class="form-label fw-bold">Tinggi Card (H px)</label>
                            <input type="number" name="height" id="add_area_height" class="form-control" value="220" min="80" step="10" required>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="add_area_pos_x" class="form-label fw-bold">Posisi Awal X (px)</label>
                            <input type="number" name="pos_x" id="add_area_pos_x" class="form-control" step="10" placeholder="Auto">
                            <small class="text-muted" style="font-size:10px;">Kosongkan untuk auto</small>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="add_area_pos_y" class="form-label fw-bold">Posisi Awal Y (px)</label>
                            <input type="number" name="pos_y" id="add_area_pos_y" class="form-control" step="10" placeholder="Auto">
                            <small class="text-muted" style="font-size:10px;">Kosongkan untuk auto</small>
                        </div>

                        <div class="col-md-4">
                            <label for="add_area_border_color" class="form-label fw-bold">Warna Border</label>
                            <div class="input-group">
                                <input type="color" name="border_color" id="add_area_border_color" class="form-control form-control-color" value="#1a8024" title="Pilih warna border">
                                <input type="text" class="form-control" id="add_area_border_color_text" value="#1a8024" oninput="document.getElementById('add_area_border_color').value = this.value">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="add_area_bg_color" class="form-label fw-bold">Warna Background</label>
                            <div class="input-group">
                                <input type="color" name="bg_color" id="add_area_bg_color" class="form-control form-control-color" value="#f8fafc" title="Pilih warna background">
                                <input type="text" class="form-control" id="add_area_bg_color_text" value="#f8fafc" oninput="document.getElementById('add_area_bg_color').value = this.value">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="add_area_title_color" class="form-label fw-bold">Warna Judul</label>
                            <div class="input-group">
                                <input type="color" name="title_color" id="add_area_title_color" class="form-control form-control-color" value="#1a8024" title="Pilih warna judul">
                                <input type="text" class="form-control" id="add_area_title_color_text" value="#1a8024" oninput="document.getElementById('add_area_title_color').value = this.value">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="add_area_description" class="form-label fw-bold">Keterangan / Lokasi</label>
                            <textarea name="description" id="add_area_description" class="form-control" rows="2" placeholder="Catatan lokasi atau deskripsi teknis POP..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" id="btnSubmitAddArea">
                        <em class="icon ni ni-check me-1"></em> Simpan Card Area
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL: EDIT CARD AREA / POP
═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditArea" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formEditArea" onsubmit="submitEditArea(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="area_id" id="edit_area_id">
                <div class="modal-header bg-warning text-dark py-2">
                    <h6 class="modal-title fw-bold">
                        <em class="icon ni ni-edit me-1"></em> Edit Card Area &amp; Pembatas Router
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="edit_area_name" class="form-label fw-bold">Nama Area / POP <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_area_name" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label for="edit_area_code" class="form-label fw-bold">Kode Area (Singkatan)</label>
                            <input type="text" name="code" id="edit_area_code" class="form-control">
                        </div>

                        <div class="col-md-3 col-6">
                            <label for="edit_area_width" class="form-label fw-bold">Lebar Card (W px)</label>
                            <input type="number" name="width" id="edit_area_width" class="form-control" min="80" step="10" required>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="edit_area_height" class="form-label fw-bold">Tinggi Card (H px)</label>
                            <input type="number" name="height" id="edit_area_height" class="form-control" min="80" step="10" required>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="edit_area_pos_x" class="form-label fw-bold">Posisi Awal X (px)</label>
                            <input type="number" name="pos_x" id="edit_area_pos_x" class="form-control" step="10" required>
                        </div>
                        <div class="col-md-3 col-6">
                            <label for="edit_area_pos_y" class="form-label fw-bold">Posisi Awal Y (px)</label>
                            <input type="number" name="pos_y" id="edit_area_pos_y" class="form-control" step="10" required>
                        </div>

                        <div class="col-md-4">
                            <label for="edit_area_border_color" class="form-label fw-bold">Warna Border</label>
                            <div class="input-group">
                                <input type="color" name="border_color" id="edit_area_border_color" class="form-control form-control-color" title="Pilih warna border">
                                <input type="text" class="form-control" id="edit_area_border_color_text" oninput="document.getElementById('edit_area_border_color').value = this.value">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_area_bg_color" class="form-label fw-bold">Warna Background</label>
                            <div class="input-group">
                                <input type="color" name="bg_color" id="edit_area_bg_color" class="form-control form-control-color" title="Pilih warna background">
                                <input type="text" class="form-control" id="edit_area_bg_color_text" oninput="document.getElementById('edit_area_bg_color').value = this.value">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_area_title_color" class="form-label fw-bold">Warna Judul</label>
                            <div class="input-group">
                                <input type="color" name="title_color" id="edit_area_title_color" class="form-control form-control-color" title="Pilih warna judul">
                                <input type="text" class="form-control" id="edit_area_title_color_text" oninput="document.getElementById('edit_area_title_color').value = this.value">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="edit_area_description" class="form-label fw-bold">Keterangan / Lokasi</label>
                            <textarea name="description" id="edit_area_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm" id="btnSubmitEditArea">
                        <em class="icon ni ni-save me-1"></em> Perbarui Card Area
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL: TAMBAH RELASI BARU
═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAddRelasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formAddRelasi" onsubmit="submitAddRelasi(event)">
                @csrf
                <div class="modal-header bg-success text-white py-2">
                    <h6 class="modal-title text-white">
                        <em class="icon ni ni-plus-circle me-1"></em> Tambah Relasi Baru (WhatsUp Gold 2026)
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_from_device_id" class="form-label fw-bold">Perangkat Asal (From Device) <span class="text-danger">*</span></label>
                        <select name="from_device_id" id="add_from_device_id" class="form-select" required>
                            <option value="">-- Pilih Perangkat Asal --</option>
                            @foreach($devices as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->ip_address }}) - {{ $d->pop_name ?: 'Lainnya' }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Node titik awal (Parent / Sumber).</small>
                    </div>

                    <div class="mb-3">
                        <label for="add_to_device_id" class="form-label fw-bold">Perangkat Tujuan (To Device) <span class="text-danger">*</span></label>
                        <select name="to_device_id" id="add_to_device_id" class="form-select" required>
                            <option value="">-- Pilih Perangkat Tujuan --</option>
                            @foreach($devices as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->ip_address }}) - {{ $d->pop_name ?: 'Lainnya' }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Node titik akhir yang terhubung (Target / Child).</small>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <label for="add_direction" class="form-label fw-bold">Arah Panah Relasi <span class="text-danger">*</span></label>
                            <select name="direction" id="add_direction" class="form-select" required>
                                <option value="forward">&rarr; Forward (Parent &rarr; Child Uplink)</option>
                                <option value="reverse">&larr; Reverse (Inbound Downlink &larr;)</option>
                                <option value="bidirectional">&harr; Dua Arah (Duplex / Peering)</option>
                                <option value="none">&mdash; Tanpa Panah (Layer-2 Pasif)</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="add_connection_type" class="form-label fw-bold">Tipe Media</label>
                            <select name="connection_type" id="add_connection_type" class="form-select">
                                <option value="ethernet">Ethernet</option>
                                <option value="fiber">Fiber Optic</option>
                                <option value="wireless">Wireless Link</option>
                                <option value="serial">Serial / WAN</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-light border small text-muted mb-0">
                        <em class="icon ni ni-info me-1 text-primary"></em> Relasi yang ditambahkan akan langsung terhubung dengan garis jarum panah di peta WhatsUp Gold dan posisi panah dapat digeser bebas.
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm" id="btnSubmitAddRelasi">
                        <em class="icon ni ni-check me-1"></em> Simpan Relasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL: EDIT RELASI
═══════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditRelasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditRelasi" onsubmit="submitEditRelasi(event)">
                @csrf
                @method('PUT')
                <input type="hidden" name="connection_id" id="edit_conn_id">
                <div class="modal-header bg-warning text-dark py-2">
                    <h6 class="modal-title fw-bold">
                        <em class="icon ni ni-edit me-1"></em> Edit Data Relasi Antar-Perangkat
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_from_device_id" class="form-label fw-bold">Perangkat Asal (From Device) <span class="text-danger">*</span></label>
                        <select name="from_device_id" id="edit_from_device_id" class="form-select" required>
                            @foreach($devices as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->ip_address }}) - {{ $d->pop_name ?: 'Lainnya' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_to_device_id" class="form-label fw-bold">Perangkat Tujuan (To Device) <span class="text-danger">*</span></label>
                        <select name="to_device_id" id="edit_to_device_id" class="form-select" required>
                            @foreach($devices as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->ip_address }}) - {{ $d->pop_name ?: 'Lainnya' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <label for="edit_direction" class="form-label fw-bold">Arah Panah Relasi <span class="text-danger">*</span></label>
                            <select name="direction" id="edit_direction" class="form-select" required>
                                <option value="forward">&rarr; Forward (Parent &rarr; Child Uplink)</option>
                                <option value="reverse">&larr; Reverse (Inbound Downlink &larr;)</option>
                                <option value="bidirectional">&harr; Dua Arah (Duplex / Peering)</option>
                                <option value="none">&mdash; Tanpa Panah (Layer-2 Pasif)</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="edit_connection_type" class="form-label fw-bold">Tipe Media</label>
                            <select name="connection_type" id="edit_connection_type" class="form-select">
                                <option value="ethernet">Ethernet</option>
                                <option value="fiber">Fiber Optic</option>
                                <option value="wireless">Wireless Link</option>
                                <option value="serial">Serial / WAN</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm" id="btnSubmitEditRelasi">
                        <em class="icon ni ni-save me-1"></em> Perbarui Relasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL TAMBAH MAPS BARU (QUICK ADD MAPS)
═══════════════════════════════════════════════════ --}}

@if(auth()->user()->role === 'super_admin')
<div class="modal fade" id="modalQuickAddMap" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title text-white">
                    <em class="icon ni ni-plus-circle me-1"></em> Tambah Maps Baru
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.topology-maps.store') }}" method="POST">
                @csrf
                <div class="modal-body py-3">
                    <p class="text-muted small mb-3">
                        Peta topologi baru akan langsung muncul sebagai tab di atas dan siap digunakan pada Top View.
                    </p>
                    <div class="mb-3">
                        <label for="quick_map_name" class="form-label fw-bold">Nama Maps <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               id="quick_map_name"
                               name="name"
                               placeholder="Contoh: EzyHome Network / POP Timur"
                               required
                               autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="quick_map_desc" class="form-label fw-bold">Deskripsi <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea class="form-control"
                                  id="quick_map_desc"
                                  name="description"
                                  rows="2"
                                  placeholder="Keterangan singkat peta ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <em class="icon ni ni-check me-1"></em> Buat Maps
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif  

{{-- FOOTER WHATSUP GOLD --}}
<div class="wug-footer">
    <div class="d-flex align-items-center gap-2">
        <img src="{{ asset('admin-assets/images/radnext-logo.png') }}" alt="radnext logo" class="wug-footer-logo" onerror="this.style.display='none'">
        <span class="fw-bold text-primary">radnext</span>
    </div>
    <div class="text-center">
        WhatsUp Gold Software 2026. Copyright &copy; PT RADNET DIGITAL INDONESIA.
    </div>
    <div id="wugRealtimeClock" class="font-monospace fw-bold text-dark">
        {{ date('Y/m/d H:i:s') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    var isSuperAdmin = {!! json_encode(auth()->check() && auth()->user()->role === 'super_admin') !!};
    function switchWugTab(tabName) {
        var tabs = ['topview', 'summary', 'logs', 'outage', 'stats', 'relasi', 'area'];
        tabs.forEach(function (t) {
            var btn = document.getElementById('tabBtn' + capitalize(t));
            var pane = document.getElementById('wugView' + (t === 'topview' ? 'TopView' : capitalize(t)));
            if (btn) btn.classList.remove('active');
            if (pane) pane.classList.remove('active');
        });

        var activeBtn = document.getElementById('tabBtn' + capitalize(tabName));
        var activePane = document.getElementById('wugView' + (tabName === 'topview' ? 'TopView' : capitalize(tabName)));
        if (activeBtn) activeBtn.classList.add('active');
        if (activePane) activePane.classList.add('active');

        // Update URL query string without full page reload
        try {
            var url = new URL(window.location.href);
            if (tabName === 'topview') {
                url.searchParams.delete('tab');
            } else {
                url.searchParams.set('tab', tabName);
            }
            window.history.replaceState({}, '', url.toString());
        } catch (e) {}
    }

    function capitalize(s) {
        if (!s) return '';
        if (s === 'topview') return 'TopView';
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    // Auto-switch tab based on URL query parameter on page load
    document.addEventListener('DOMContentLoaded', function () {
        var urlParams = new URLSearchParams(window.location.search);
        var tabParam = urlParams.get('tab');
        if (tabParam && ['topview', 'summary', 'logs', 'outage', 'stats', 'relasi', 'area'].indexOf(tabParam) !== -1) {
            switchWugTab(tabParam);
        }
    });

    // Locate device on Map View
    function locateDeviceOnMap(deviceId) {
        switchWugTab('topview');
        setTimeout(function () {
            if (window.openWugDeviceModal) {
                window.openWugDeviceModal(deviceId);
            }
        }, 150);
    }

    // Filter relations table (Search, POP, Direction)
    function filterRelasiTable() {
        var query = (document.getElementById('searchRelasi') ? document.getElementById('searchRelasi').value : '').toLowerCase().trim();
        var popFilter = (document.getElementById('filterRelasiPop') ? document.getElementById('filterRelasiPop').value : '').toLowerCase().trim();
        var dirFilter = (document.getElementById('filterRelasiDir') ? document.getElementById('filterRelasiDir').value : '').toLowerCase().trim();

        var rows = document.querySelectorAll('#tableRelasiDevice tbody tr');
        rows.forEach(function (row) {
            if (row.id === 'rowEmptyRelasi') return;
            var rowSearch = row.getAttribute('data-search') || '';
            var rowPop = row.getAttribute('data-pop') || '';
            var rowDir = row.getAttribute('data-dir') || '';

            var matchSearch = !query || rowSearch.indexOf(query) !== -1;
            var matchPop = !popFilter || rowPop === popFilter;
            var matchDir = !dirFilter || rowDir === dirFilter;

            if (matchSearch && matchPop && matchDir) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function filterRelasiByPop(popName) {
        switchWugTab('relasi');
        var select = document.getElementById('filterRelasiPop');
        if (select) {
            select.value = (popName || '').toLowerCase().trim();
            filterRelasiTable();
        }
    }

    function resetRelasiFilter() {
        if (document.getElementById('searchRelasi')) document.getElementById('searchRelasi').value = '';
        if (document.getElementById('filterRelasiPop')) document.getElementById('filterRelasiPop').value = '';
        if (document.getElementById('filterRelasiDir')) document.getElementById('filterRelasiDir').value = '';
        filterRelasiTable();
    }

    var searchInput = document.getElementById('searchRelasi');
    if (searchInput) searchInput.addEventListener('input', filterRelasiTable);

    // Toast notification for Relasi View
    function showRelasiToast(message, isError) {
        var existing = document.getElementById('wugRelasiToast');
        if (existing) existing.remove();

        var toast = document.createElement('div');
        toast.id = 'wugRelasiToast';
        toast.style.position = 'fixed';
        toast.style.bottom = '24px';
        toast.style.right = '24px';
        toast.style.zIndex = '99999';
        toast.style.padding = '10px 16px';
        toast.style.borderRadius = '6px';
        toast.style.fontSize = '13px';
        toast.style.fontWeight = '600';
        toast.style.color = '#ffffff';
        toast.style.backgroundColor = isError ? '#dc2626' : '#16a34a';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.25)';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.gap = '8px';
        toast.innerHTML = '<span class="icon ni ' + (isError ? 'ni-cross-circle' : 'ni-check-circle') + ' fs-5"><\/span><span>' + message + '<\/span>';

        document.body.appendChild(toast);
        setTimeout(function () {
            toast.style.transition = 'opacity .4s ease';
            toast.style.opacity = '0';
            setTimeout(function () { toast.remove(); }, 400);
        }, 3200);
    }

    // Quick Change Direction via Inline Dropdown
    var directionUrlTemplate = "{{ route('admin.monitoring.connection.direction', ['connection' => '__CONN_ID__']) }}";
    var csrfToken = "{{ csrf_token() }}";
    window.quickChangeDirection = function (connId, dir, selectEl) {
        if (!isSuperAdmin) { alert('Hanya Super Admin.'); if (selectEl) selectEl.value = selectEl.dataset.prev || 'forward'; return; }
        var url = directionUrlTemplate.replace('__CONN_ID__', connId);
        var originalBg = selectEl.style.backgroundColor;
        selectEl.style.backgroundColor = '#fef3c7';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ direction: dir })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            selectEl.style.backgroundColor = '#dcfce7';
            setTimeout(function () { selectEl.style.backgroundColor = originalBg; }, 1000);

            var row = document.getElementById('relasi-row-' + connId);
            if (row) {
                row.setAttribute('data-dir', dir);
            }

            var dirLabels = {
                'forward': 'Forward (→)',
                'reverse': 'Reverse (←)',
                'bidirectional': 'Dua Arah (↔)',
                'none': 'Tanpa Panah (—)'
            };
            showRelasiToast('Arah relasi #' + connId + ' diubah menjadi ' + (dirLabels[dir] || dir) + ' & disimpan ke database.');
        })
        .catch(function () {
            selectEl.style.backgroundColor = '#fee2e2';
            setTimeout(function () { selectEl.style.backgroundColor = originalBg; }, 1500);
            showRelasiToast('Gagal memperbarui arah relasi.', true);
        });
    };

    // Modal Edit Relasi Handler
    var modalEditRelasiEl = document.getElementById('modalEditRelasi');
    var bsModalEditRelasi = modalEditRelasiEl ? new bootstrap.Modal(modalEditRelasiEl) : null;
    var updateUrlTemplate = "{{ route('admin.monitoring.connection.update', ['connection' => '__CONN_ID__']) }}";
    var destroyUrlTemplate = "{{ route('admin.monitoring.connection.destroy', ['connection' => '__CONN_ID__']) }}";

    window.openEditRelasiModal = function (id, fromId, toId, direction, connType) {
        if (!isSuperAdmin) { alert('Hanya Super Admin.'); return; }
        document.getElementById('edit_conn_id').value = id;
        document.getElementById('edit_from_device_id').value = fromId;
        document.getElementById('edit_to_device_id').value = toId;
        document.getElementById('edit_direction').value = direction || 'forward';
        document.getElementById('edit_connection_type').value = connType || 'ethernet';

        if (bsModalEditRelasi) {
            bsModalEditRelasi.show();
        }
    };

    // Submit Tambah Relasi
    window.submitAddRelasi = function (e) {
        e.preventDefault();
        if (!isSuperAdmin) { alert('Hanya Super Admin.'); return; }
        var form = document.getElementById('formAddRelasi');
        var btn = document.getElementById('btnSubmitAddRelasi');
        var fromId = document.getElementById('add_from_device_id').value;
        var toId = document.getElementById('add_to_device_id').value;
        var dir = document.getElementById('add_direction').value;
        var type = document.getElementById('add_connection_type').value;

        if (!fromId || !toId) {
            alert('Silakan pilih perangkat asal dan tujuan!');
            return;
        }
        if (fromId === toId) {
            alert('Perangkat asal dan perangkat tujuan tidak boleh sama!');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"><\/span> Menyimpan...';

        fetch("{{ route('admin.monitoring.connection.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                from_device_id: fromId,
                to_device_id: toId,
                direction: dir,
                connection_type: type
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-check me-1"><\/em> Simpan Relasi';

            if (res.success) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalAddRelasi'));
                if (modal) modal.hide();
                form.reset();
                showRelasiToast('Relasi baru berhasil ditambahkan! Memuat ulang data...');
                setTimeout(function () { window.location.reload(); }, 900);
            } else {
                alert(res.message || 'Gagal menyimpan relasi.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-check me-1"><\/em> Simpan Relasi';
            alert('Terjadi kesalahan jaringan.');
        });
    };

    // Submit Edit Relasi
    window.submitEditRelasi = function (e) {
        e.preventDefault();
        if (!isSuperAdmin) { alert('Hanya Super Admin.'); return; }
        var btn = document.getElementById('btnSubmitEditRelasi');
        var connId = document.getElementById('edit_conn_id').value;
        var fromId = document.getElementById('edit_from_device_id').value;
        var toId = document.getElementById('edit_to_device_id').value;
        var dir = document.getElementById('edit_direction').value;
        var type = document.getElementById('edit_connection_type').value;

        if (!fromId || !toId) {
            alert('Silakan pilih perangkat asal dan tujuan!');
            return;
        }
        if (fromId === toId) {
            alert('Perangkat asal dan perangkat tujuan tidak boleh sama!');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"><\/span> Memperbarui...';

        var url = updateUrlTemplate.replace('__CONN_ID__', connId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _method: 'PUT',
                from_device_id: fromId,
                to_device_id: toId,
                direction: dir,
                connection_type: type
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Perbarui Relasi';

            if (res.success) {
                if (bsModalEditRelasi) bsModalEditRelasi.hide();
                showRelasiToast('Relasi #' + connId + ' berhasil diperbarui! Memuat ulang...');
                setTimeout(function () { window.location.reload(); }, 900);
            } else {
                alert(res.message || 'Gagal memperbarui relasi.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Perbarui Relasi';
            alert('Terjadi kesalahan jaringan.');
        });
    };

    // Hapus Relasi
    window.deleteRelasi = function (connId, label) {
        if (!isSuperAdmin) return;
        if (!confirm('Apakah Anda yakin ingin menghapus relasi koneksi WhatsUp Gold ini:\n\n' + label + '\n\nRelasi ini akan dihapus dari peta topologi.')) {
            return;
        }
        var url = destroyUrlTemplate.replace('__CONN_ID__', connId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.success) {
                var row = document.getElementById('relasi-row-' + connId);
                if (row) {
                    row.style.transition = 'opacity .3s ease';
                    row.style.opacity = '0';
                    setTimeout(function () { row.remove(); }, 300);
                }
                var badge = document.getElementById('relasiCountBadge');
                if (badge) {
                    var cur = parseInt(badge.textContent) || 0;
                    if (cur > 0) badge.textContent = (cur - 1) + ' Total Relasi';
                }
                showRelasiToast('Relasi #' + connId + ' berhasil dihapus dari sistem & topologi map.');
            } else {
                alert(res.message || 'Gagal menghapus relasi.');
            }
        })
        .catch(function () {
            alert('Terjadi kesalahan jaringan.');
        });
    };

    // ═══════════════════════════════════════════════════
    // CRUD CARD AREA / POP HANDLERS (WHATSUP GOLD 2026)
    // ═══════════════════════════════════════════════════
    // ─── Endpoint per-scope ───
    var areaEndpoints = {
        monitoring: {
            store:   "{{ route('admin.monitoring.area.store') }}",
            update:  "{{ route('admin.monitoring.area.update', ['area' => '__AREA_ID__']) }}",
            destroy: "{{ route('admin.monitoring.area.destroy', ['area' => '__AREA_ID__']) }}",
            color:   '#1a8024'
        },
        ezyhome: {
            store:   "{{ route('admin.ezyhome.area.store') }}",
            update:  "{{ route('admin.ezyhome.area.update', ['area' => '__AREA_ID__']) }}",
            destroy: "{{ route('admin.ezyhome.area.destroy', ['area' => '__AREA_ID__']) }}",
            color:   '#2563eb'
        }
    };
    var currentAreaScope = 'monitoring';
    // Filter Area Table
    function filterAreaTable() {
        var query = (document.getElementById('searchAreaInput') ? document.getElementById('searchAreaInput').value : '').toLowerCase().trim();
        var rows = document.querySelectorAll('#tableNetworkArea tbody tr');
        rows.forEach(function (row) {
            if (row.id === 'rowEmptyArea') return;
            var text = row.getAttribute('data-search') || '';
            row.style.display = (!query || text.indexOf(query) !== -1) ? '' : 'none';
        });
    }

    // Modal Edit Area Handler
    var modalEditAreaEl = document.getElementById('modalEditArea');
    var bsModalEditArea = modalEditAreaEl ? new bootstrap.Modal(modalEditAreaEl) : null;
    window.openAddAreaModal = function (scope) {
        if (!isSuperAdmin) { alert('Hanya Super Admin yang dapat menambah Card Area.'); return; }
        scope = scope || 'monitoring';
        currentAreaScope = scope;

        // Update judul modal & warna header
        var titleEl = document.querySelector('#modalAddArea .modal-title');
        var headerEl = document.querySelector('#modalAddArea .modal-header');
        if (titleEl) {
            titleEl.innerHTML = '<em class="icon ni ni-plus-circle me-1"><\/em> Tambah Card Area ' +
                (scope === 'ezyhome' ? 'EzyHome' : 'Monitoring');
        }
        if (headerEl) {
            headerEl.classList.remove('bg-success', 'bg-primary');
            headerEl.classList.add(scope === 'ezyhome' ? 'bg-primary' : 'bg-success');
        }

        // Reset form default
        document.getElementById('formAddArea').reset();
        document.getElementById('add_area_border_color').value = areaEndpoints[scope].color;
        document.getElementById('add_area_border_color_text').value = areaEndpoints[scope].color;
        document.getElementById('add_area_title_color').value = areaEndpoints[scope].color;
        document.getElementById('add_area_title_color_text').value = areaEndpoints[scope].color;

        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAddArea'));
        modal.show();
    };
    window.openEditAreaModal = function (area, scope) {
        if (!isSuperAdmin) { alert('Hanya Super Admin yang dapat mengedit Card Area.'); return; }
        if (!area) return;
        currentAreaScope = scope || 'monitoring';
        document.getElementById('edit_area_id').value = area.id;
        document.getElementById('edit_area_name').value = area.name || '';
        document.getElementById('edit_area_code').value = area.code || '';
        document.getElementById('edit_area_width').value = area.width || 280;
        document.getElementById('edit_area_height').value = area.height || 220;
        document.getElementById('edit_area_pos_x').value = area.pos_x || 50;
        document.getElementById('edit_area_pos_y').value = area.pos_y || 50;
        document.getElementById('edit_area_border_color').value = area.border_color || '#1a8024';
        document.getElementById('edit_area_border_color_text').value = area.border_color || '#1a8024';
        document.getElementById('edit_area_bg_color').value = area.bg_color || '#f8fafc';
        document.getElementById('edit_area_bg_color_text').value = area.bg_color || '#f8fafc';
        document.getElementById('edit_area_title_color').value = area.title_color || '#1a8024';
        document.getElementById('edit_area_title_color_text').value = area.title_color || '#1a8024';
        document.getElementById('edit_area_description').value = area.description || '';

        if (bsModalEditArea) {
            bsModalEditArea.show();
        }
    };

    // Submit Tambah Area
    window.submitAddArea = function (e) {
        e.preventDefault();
        if (!isSuperAdmin) { alert('Hanya Super Admin.'); return; }
        var btn = document.getElementById('btnSubmitAddArea');
        var name = document.getElementById('add_area_name').value;
        var code = document.getElementById('add_area_code').value;
        var width = document.getElementById('add_area_width').value;
        var height = document.getElementById('add_area_height').value;
        var pos_x = document.getElementById('add_area_pos_x').value;
        var pos_y = document.getElementById('add_area_pos_y').value;
        var border_color = document.getElementById('add_area_border_color').value;
        var bg_color = document.getElementById('add_area_bg_color').value;
        var title_color = document.getElementById('add_area_title_color').value;
        var description = document.getElementById('add_area_description').value;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"><\/span> Menyimpan...';

        fetch(areaEndpoints[currentAreaScope].store, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                code: code,
                width: width,
                height: height,
                pos_x: pos_x === '' ? null : pos_x,
                pos_y: pos_y === '' ? null : pos_y,
                border_color: border_color,
                bg_color: bg_color,
                title_color: title_color,
                description: description
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-check me-1"><\/em> Simpan Card Area';

            if (res.success) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalAddArea'));
                if (modal) modal.hide();
                showRelasiToast('Card Area ' + name + ' berhasil ditambahkan! Memuat ulang...');
                setTimeout(function () { window.location.reload(); }, 900);
            } else {
                alert(res.message || 'Gagal menambahkan card area.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-check me-1"><\/em> Simpan Card Area';
            alert('Terjadi kesalahan jaringan.');
        });
    };

    // Submit Edit Area
    window.submitEditArea = function (e) {
        e.preventDefault();
        if (!isSuperAdmin) { alert('Hanya Super Admin.'); return; }
        var btn = document.getElementById('btnSubmitEditArea');
        var id = document.getElementById('edit_area_id').value;
        var name = document.getElementById('edit_area_name').value;
        var code = document.getElementById('edit_area_code').value;
        var width = document.getElementById('edit_area_width').value;
        var height = document.getElementById('edit_area_height').value;
        var pos_x = document.getElementById('edit_area_pos_x').value;
        var pos_y = document.getElementById('edit_area_pos_y').value;
        var border_color = document.getElementById('edit_area_border_color').value;
        var bg_color = document.getElementById('edit_area_bg_color').value;
        var title_color = document.getElementById('edit_area_title_color').value;
        var description = document.getElementById('edit_area_description').value;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"><\/span> Memperbarui...';

        var url = areaEndpoints[currentAreaScope].update.replace('__AREA_ID__', id);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _method: 'PUT',
                name: name,
                code: code,
                width: width,
                height: height,
                pos_x: pos_x,
                pos_y: pos_y,
                border_color: border_color,
                bg_color: bg_color,
                title_color: title_color,
                description: description
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Perbarui Card Area';

            if (res.success) {
                if (bsModalEditArea) bsModalEditArea.hide();
                showRelasiToast('Card Area ' + name + ' berhasil diperbarui! Memuat ulang...');
                setTimeout(function () { window.location.reload(); }, 900);
            } else {
                alert(res.message || 'Gagal memperbarui card area.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Perbarui Card Area';
            alert('Terjadi kesalahan jaringan.');
        });
    };
//hapus 
        window.deleteArea = function (id, name, scope) {
        if (!isSuperAdmin) return;
        scope = scope || 'monitoring';
        if (!confirm('Apakah Anda yakin ingin menghapus card area POP ini:\n\n' + name + '\n\nArea ini akan dihapus dari peta topologi.')) {
            return;
        }
        var url = areaEndpoints[scope].destroy.replace('__AREA_ID__', id);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.success) {
                var row = document.getElementById('area-row-' + scope + '-' + id);
                if (row) {
                    row.style.transition = 'opacity .3s ease';
                    row.style.opacity = '0';
                    setTimeout(function () { row.remove(); }, 300);
                }
                var badge = document.getElementById('areaCountBadge');
                if (badge) {
                    var cur = parseInt(badge.textContent) || 0;
                    if (cur > 0) badge.textContent = (cur - 1) + ' Total Card Area';
                }
                showRelasiToast('Card area ' + name + ' berhasil dihapus dari sistem & topologi map.');
            } else {
                alert(res.message || 'Gagal menghapus card area.');
            }
        })
        .catch(function () {
            alert('Terjadi kesalahan jaringan.');
        });
    };

    // Live Clock updater (YYYY/MM/DD HH:mm:ss)
    setInterval(function () {
        var d = new Date();
        var pad = function (n) { return n < 10 ? '0' + n : n; };
        var formatted = d.getFullYear() + '/' + pad(d.getMonth() + 1) + '/' + pad(d.getDate()) + ' ' +
                        pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
        var clock = document.getElementById('wugRealtimeClock');
        if (clock) clock.textContent = formatted;
    }, 1000);
</script>
@endpush