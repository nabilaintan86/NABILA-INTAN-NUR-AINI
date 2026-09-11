@extends('layouts.admin')

@section('title', 'Detail Router · RADNET NOC')

@push('styles')
<style>
    .router-header .badge-status {
        font-size: 13px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 8px;
    }
    .router-header .badge-up      { background: #dcfce7; color: #15803d; }
    .router-header .badge-down    { background: #fee2e2; color: #dc2626; }
    .router-header .badge-warning { background: #fef9c3; color: #a16207; }

    .device-type-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: capitalize;
    }
    .type-icmp { background: #eff6ff; color: #2563eb; }
    .type-http { background: #f0fdf4; color: #16a34a; }
    .type-snmp { background: #fefce8; color: #ca8a04; }

    .router-info .info-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 4px;
    }
    .router-info .info-value {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }
    .router-info .info-value.mono {
        font-family: 'Consolas', 'Menlo', monospace;
    }

    .metric-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        height: 100%;
    }
    .metric-card .metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .metric-card .metric-value {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.1;
    }
    .metric-card .metric-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .metric-icon-up      { background: #dcfce7; color: #16a34a; }
    .metric-icon-service { background: #eff6ff; color: #2563eb; }
    .metric-icon-uptime  { background: #fefce8; color: #ca8a04; }

    .activity-log-box {
        background: #0f172a;
        border-radius: 14px;
        padding: 16px 0 0;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(15, 23, 42, .10);
    }
    .log-box-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 20px 14px;
        border-bottom: 1px solid #1e293b;
    }
    .log-dot {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        display: inline-block;
    }
    .log-dot-red    { background: #f87171; }
    .log-dot-yellow { background: #fbbf24; }
    .log-dot-green  { background: #34d399; }
    .log-box-title {
        color: #e2e8f0;
        font-size: 14px;
        font-weight: 700;
        margin: 0;
    }
    .log-box-subtitle {
        color: #94a3b8;
        font-size: 12px;
        margin-left: auto;
    }
    .log-lines {
        max-height: 520px;
        overflow-y: auto;
        padding: 8px 0;
        font-family: 'Consolas', 'Menlo', 'Monaco', 'Courier New', monospace;
    }
    .log-line {
        display: flex;
        align-items: baseline;
        gap: 12px;
        padding: 6px 20px;
        font-size: 12.5px;
        line-height: 1.5;
        border-bottom: 1px solid #1e293b;
        white-space: nowrap;
    }
    .log-line:last-child { border-bottom: none; }
    .log-line:hover { background: #1e293b; }
    .log-line .log-time {
        color: #64748b;
        flex-shrink: 0;
    }
    .log-line .log-status {
        width: 74px;
        flex-shrink: 0;
        font-weight: 700;
    }
    .log-status-up      { color: #4ade80; }
    .log-status-down    { color: #f87171; }
    .log-status-warning { color: #fbbf24; }
    .log-line .log-device {
        color: #e2e8f0;
        font-weight: 700;
        flex-shrink: 0;
    }
    .log-line .log-ip {
        color: #60a5fa;
        flex-shrink: 0;
    }
    .log-line .log-reason {
        color: #94a3b8;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .log-line .log-reason .latency {
        color: #c084fc;
        font-weight: 600;
    }
    .log-box-empty {
        color: #64748b;
        font-family: 'Consolas', 'Menlo', 'Monaco', 'Courier New', monospace;
        text-align: center;
        padding: 48px 20px;
    }
</style>
@endpush

@section('content')
<div class="nk-content">
    <div class="container">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                {{-- HEADER --}}
                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title">
                                <i class="fas fa-server text-primary me-2"></i>
                                Detail Perangkat: {{ $device->name }}
                            </h2>
                            <nav>
                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.kelola-router') }}">Kelola Router</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $device->name }}
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="nk-block-head-content">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.kelola-router') }}"
                                   class="btn btn-light btn-dim btn-sm">
                                    <em class="icon ni ni-arrow-left me-1"></em>Kembali
                                </a>
                                <span class="badge-status badge-{{ $device->status }}">
                                    {{ strtoupper($device->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DEVICE INFO CARDS --}}
                <div class="row g-gs mb-3">
                    <div class="col-sm-6 col-lg-2">
                        <div class="card card-bordered h-100">
                            <div class="card-body router-info">
                                <div class="info-label">Nama</div>
                                <div class="info-value">{{ $device->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <div class="card card-bordered h-100">
                            <div class="card-body router-info">
                                <div class="info-label">Tipe</div>
                                <div class="info-value">
                                    <span class="device-type-badge type-{{ strtolower($device->type) }}">
                                        {{ $device->type }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <div class="card card-bordered h-100">
                            <div class="card-body router-info">
                                <div class="info-label">IP Address</div>
                                <div class="info-value mono">{{ $device->ip_address }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <div class="card card-bordered h-100">
                            <div class="card-body router-info">
                                <div class="info-label">Site</div>
                                <div class="info-value">{{ $device->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <div class="card card-bordered h-100">
                            <div class="card-body router-info">
                                <div class="info-label">Total Polls</div>
                                <div class="info-value">{{ number_format($device->polls) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <div class="card card-bordered h-100">
                            <div class="card-body router-info">
                                <div class="info-label">Alerts</div>
                                <div class="info-value {{ $device->alerts_count > 0 ? 'text-danger' : '' }}">
                                    {{ $device->alerts_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PERFORMANCE METRICS --}}
                <div class="row g-gs mb-3">
                    <div class="col-sm-6 col-lg-4">
                        <div class="metric-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="metric-icon metric-icon-up">
                                    <em class="icon ni ni-bolt"></em>
                                </div>
                                <div>
                                    <div class="metric-value">{{ $device->avg_delay }} ms</div>
                                    <div class="metric-label">Avg Response / Latency</div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-4" style="font-size:12px; color:#64748b;">
                                <span>Min: <strong>{{ $device->min_delay }} ms</strong></span>
                                <span>Max: <strong>{{ number_format($device->max_delay) }} ms</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="metric-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="metric-icon metric-icon-service">
                                    <em class="icon ni ni-shield-check"></em>
                                </div>
                                <div>
                                    <div class="metric-value">
                                        <span class="{{ $device->responded_percentage >= 99 ? 'text-success' : ($device->responded_percentage < 90 ? 'text-danger' : '') }}">
                                            {{ $device->responded_percentage }}%
                                        </span>
                                    </div>
                                    <div class="metric-label">Service Status (Responded)</div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-4" style="font-size:12px; color:#64748b;">
                                <span>Missed: <strong class="{{ $device->missed_percentage > 10 ? 'text-danger' : '' }}">{{ $device->missed_percentage }}%</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="metric-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="metric-icon metric-icon-uptime">
                                    <em class="icon ni ni-clock"></em>
                                </div>
                                <div>
                                    <div class="metric-value">{{ $device->period ?? '-' }}</div>
                                    <div class="metric-label">Uptime / Period</div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-4" style="font-size:12px; color:#64748b;">
                                <span>Down time: <strong>{{ $device->down_time ?? '-' }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONDITION LOGS --}}
                <div class="nk-block">
                    <div class="activity-log-box">
                        <div class="log-box-header">
                            <span class="log-dot log-dot-red"></span>
                            <span class="log-dot log-dot-yellow"></span>
                            <span class="log-dot log-dot-green"></span>
                            <h6 class="log-box-title ms-2">Histori Kondisi — {{ $device->name }}</h6>
                            <span class="log-box-subtitle">
                                {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} / {{ $logs->total() }}
                            </span>
                        </div>

                        <div class="log-lines">
                            @forelse($logs as $log)
                                <div class="log-line">
                                    <span class="log-time">
                                        {{ $log->log_time->format('Y-m-d H:i:s') }}
                                    </span>
                                    <span class="log-status log-status-{{ $log->status }}">
                                        {{ strtoupper($log->status) }}
                                    </span>
                                    <span class="log-device">
                                        {{ $log->device_name }}
                                    </span>
                                    <span class="log-ip">
                                        {{ $log->ip_address }}
                                    </span>
                                    <span class="log-reason">
                                        {{ $log->reason ?? '—' }}
                                        @if($log->status === 'up' && $log->latency !== null)
                                            <span class="latency">({{ $log->latency }} ms)</span>
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <div class="log-box-empty">
                                    &gt; Belum ada log kondisi untuk perangkat ini.<br>
                                    &gt; Jalankan <span class="text-info">php artisan network:poll</span> untuk menghasilkan data.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
