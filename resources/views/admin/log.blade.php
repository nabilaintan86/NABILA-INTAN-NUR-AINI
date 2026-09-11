@extends('layouts.admin')

@section('title', 'Log History · RADNET NOC')

@push('styles')
<style>
    .log-kpi .log-kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .log-kpi .log-kpi-number {
        font-size: 20px;
        font-weight: 800;
        line-height: 1.1;
    }
    .log-kpi .log-kpi-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        letter-spacing: .3px;
        text-transform: uppercase;
    }
    .kpi-box-total   { background: #eff6ff; color: #2563eb; }
    .kpi-box-up      { background: #dcfce7; color: #16a34a; }
    .kpi-box-down    { background: #fee2e2; color: #dc2626; }
    .kpi-box-warning { background: #fef9c3; color: #ca8a04; }

    /* ============================================================
       ACTIVITY LOG BOX (monospaced viewer)
    ============================================================ */
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
        max-height: 640px;
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

    .log-filter .form-label {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .log-filter .form-control,
    .log-filter .form-select {
        font-size: 13px;
        border-color: #e2e8f0;
        box-shadow: none;
    }
    .log-filter .form-control:focus,
    .log-filter .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .log-detail-card {
        font-size: 13px;
    }
    .log-detail-card .detail-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .log-detail-card .detail-value {
        font-weight: 600;
        color: #0f172a;
    }
</style>
@endpush

@section('content')
<div class="nk-content">

    <div class="container">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                {{-- ============================================================
                     HEADER
                ============================================================ --}}
                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title">
                                <i class="fas fa-history text-primary me-2"></i>
                                Log History Perangkat
                            </h2>
                            <nav>
                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Log History
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="nk-block-head-content">
                            <span class="badge rounded-pill bg-soft-primary">
                                <em class="icon ni ni-clock me-1"></em>
                                {{ $logs->total() }} total entri log
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ============================================================
                     KPI SUMMARY
                ============================================================ --}}
                <div class="row g-gs mb-3">

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered h-100">
                            <div class="card-body log-kpi">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="log-kpi-icon kpi-box-total">
                                        <em class="icon ni ni-file-text"></em>
                                    </div>
                                    <div>
                                        <div class="log-kpi-number">
                                            {{ number_format($statusCounts['total']) }}
                                        </div>
                                        <div class="log-kpi-label">Total Log</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered h-100">
                            <div class="card-body log-kpi">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="log-kpi-icon kpi-box-up">
                                        <em class="icon ni ni-check-circle"></em>
                                    </div>
                                    <div>
                                        <div class="log-kpi-number">
                                            {{ number_format($statusCounts['up']) }}
                                        </div>
                                        <div class="log-kpi-label">Status UP</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered h-100">
                            <div class="card-body log-kpi">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="log-kpi-icon kpi-box-down">
                                        <em class="icon ni ni-close-circle"></em>
                                    </div>
                                    <div>
                                        <div class="log-kpi-number">
                                            {{ number_format($statusCounts['down']) }}
                                        </div>
                                        <div class="log-kpi-label">Status DOWN</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered h-100">
                            <div class="card-body log-kpi">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="log-kpi-icon kpi-box-warning">
                                        <em class="icon ni ni-alert-circle"></em>
                                    </div>
                                    <div>
                                        <div class="log-kpi-number">
                                            {{ number_format($statusCounts['warning']) }}
                                        </div>
                                        <div class="log-kpi-label">Status WARNING</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ============================================================
                     FILTER
                ============================================================ --}}
                <div class="card card-bordered mb-3">
                    <div class="card-body log-filter">
                        <form method="GET" action="{{ route('admin.log') }}">
                            <div class="row g-2 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label" for="filterDevice">Perangkat</label>
                                    <select class="form-select" name="device" id="filterDevice">
                                        <option value="">Semua perangkat</option>
                                        @foreach($devices as $device)
                                            <option value="{{ $device->id }}"
                                                {{ request('device') == $device->id ? 'selected' : '' }}>
                                                {{ $device->name }} ({{ $device->ip_address }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="filterStatus">Status</label>
                                    <select class="form-select" name="status" id="filterStatus">
                                        <option value="">Semua status</option>
                                        <option value="up" {{ request('status') === 'up' ? 'selected' : '' }}>UP</option>
                                        <option value="down" {{ request('status') === 'down' ? 'selected' : '' }}>DOWN</option>
                                        <option value="warning" {{ request('status') === 'warning' ? 'selected' : '' }}>WARNING</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label" for="filterQ">Kata Kunci</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="filterQ"
                                        name="q"
                                        value="{{ request('q') }}"
                                        placeholder="Nama / IP / alasan…">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="filterFrom">Dari Tanggal</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="filterFrom"
                                        name="from"
                                        value="{{ request('from') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label" for="filterTo">Sampai Tanggal</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="filterTo"
                                        name="to"
                                        value="{{ request('to') }}">
                                </div>

                                <div class="col-12 text-end">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <a href="{{ route('admin.log') }}" class="btn btn-outline-secondary btn-dim btn-sm">
                                            <em class="icon ni ni-reload me-1"></em>Reset
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <em class="icon ni ni-filter me-1"></em>Terapkan Filter
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- ============================================================
                     ACTIVITY LOG BOX
                ============================================================ --}}
                <div class="row g-gs">

                    <div class="col-xl-8">

                        <div class="activity-log-box">

                            <div class="log-box-header">
                                <span class="log-dot log-dot-red"></span>
                                <span class="log-dot log-dot-yellow"></span>
                                <span class="log-dot log-dot-green"></span>
                                <h6 class="log-box-title ms-2">Activity Log Box</h6>
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
                                        <span class="ms-auto">
                                            <button
                                                type="button"
                                                class="btn btn-icon btn-xs btn-dim btn-outline-light rounded-circle"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailLogModal"
                                                data-log-device="{{ $log->device_name }}"
                                                data-log-ip="{{ $log->ip_address }}"
                                                data-log-status="{{ $log->status }}"
                                                data-log-reason="{{ $log->reason ?? '—' }}"
                                                data-log-latency="{{ $log->latency ?? '-' }}"
                                                data-log-time="{{ $log->log_time->format('Y-m-d H:i:s') }}"
                                                title="Detail log">
                                                <em class="icon ni ni-eye"></em>
                                            </button>
                                        </span>
                                    </div>
                                @empty
                                    <div class="log-box-empty">
                                        &gt; Tidak ada data log yang cocok dengan filter…<br>
                                        &gt; Jalankan <span class="text-info">php artisan network:poll</span> untuk menghasilkan riwayat baru.
                                    </div>
                                @endforelse

                            </div>

                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            {{ $logs->links() }}
                        </div>

                    </div>

                    {{-- ============================================================
                         SIDE PANEL — KETERANGAN STATUS
                    ============================================================ --}}
                    <div class="col-xl-4">

                        <div class="card card-bordered">
                            <div class="card-header">
                                <h6 class="overline-title">Legend Status</h6>
                            </div>
                            <div class="card-body">

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex align-items-center gap-3">
                                        <span class="badge rounded-pill" style="background:#dcfce7; color:#15803d;">UP</span>
                                        <span class="small">Perangkat merespons normal (<span class="fw-bold">ICMP reply</span> diterima).</span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-3">
                                        <span class="badge rounded-pill" style="background:#fef9c3; color:#a16207;">WARNING</span>
                                        <span class="small">Degradasi / timeout ringan, contoh: <span class="fw-bold">Timed Out</span>.</span>
                                    </li>
                                    <li class="list-group-item d-flex align-items-center gap-3">
                                        <span class="badge rounded-pill" style="background:#fee2e2; color:#dc2626;">DOWN</span>
                                        <span class="small">Gagal total, contoh: <span class="fw-bold">Host Unreachable</span>, <span class="fw-bold">IP_TTL_EXPIRED_TRANSIT</span>.</span>
                                    </li>
                                </ul>

                            </div>
                        </div>

                        <div class="card card-bordered">
                            <div class="card-header">
                                <h6 class="overline-title">Alasan Status (Reason)</h6>
                            </div>
                            <div class="card-body">

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="small">
                                            <span class="fw-bold text-success">Normal</span><br>
                                            <span class="text-muted">Ping balas OK, latency tertampil dalam ms.</span>
                                        </div>
                                        <span class="badge bg-success-subtle text-success mt-1">UP</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="small">
                                            <span class="fw-bold text-warning">Timed Out</span><br>
                                            <span class="text-muted">Request ping tidak dibalas dalam batas waktu.</span>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning mt-1">WARNING</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="small">
                                            <span class="fw-bold text-danger">Host Unreachable</span><br>
                                            <span class="text-muted">Tujuan tidak dapat dijangkau pada lapisan jaringan.</span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger mt-1">DOWN</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="small">
                                            <span class="fw-bold text-danger">IP_TTL_EXPIRED_TRANSIT</span><br>
                                            <span class="text-muted">TTL habis di transit — sering terkait misrouting/loop.</span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger mt-1">DOWN</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="small">
                                            <span class="fw-bold text-danger">No Answer / DNS Resolution Failed</span><br>
                                            <span class="text-muted">Host tidak merespons atau nama gagal di-resolve.</span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger mt-1">DOWN</span>
                                    </li>
                                </ul>

                            </div>
                        </div>

                    </div>

                </div>

                {{-- ============================================================
                     DETAIL LOG MODAL
                ============================================================ --}}
                <div
                    class="modal fade"
                    id="detailLogModal"
                    tabindex="-1"
                    aria-hidden="true">

                    <div class="modal-dialog modal-md modal-dialog-centered">

                        <div class="modal-content log-detail-card">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-file-lines text-primary me-2"></i>
                                    Detail Log Aktivitas
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="row g-3">

                                    <div class="col-12">
                                        <div class="log-time detail-label">Waktu / Timestamp</div>
                                        <div class="detail-value log-time-detail">—</div>
                                    </div>

                                    <div class="col-6">
                                        <div class="log-time detail-label">Status</div>
                                        <div class="detail-value">
                                            <span class="badge rounded-pill log-status-badge">—</span>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="log-time detail-label">Latency</div>
                                        <div class="detail-value log-latency-detail">—</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="log-time detail-label">Nama Perangkat</div>
                                        <div class="detail-value log-device-detail">—</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="log-time detail-label">IP Address</div>
                                        <div class="detail-value log-ip-detail" style="font-family: monospace;">—</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="log-time detail-label">Alasan Status (Reason)</div>
                                        <div class="detail-value log-reason-detail" style="font-family: monospace;">—</div>
                                    </div>

                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-dim btn-light" data-bs-dismiss="modal">Tutup</button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Isi status pada modal detail
    const modal = document.getElementById('detailLogModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        if (!btn) return;

        const status    = btn.getAttribute('data-log-status') || 'up';
        const statusMap = {
            up:      { label: 'UP',      bg: 'bg-success',      color: '#15803d' },
            down:    { label: 'DOWN',    bg: 'bg-danger',       color: '#dc2626' },
            warning: { label: 'WARNING', bg: 'bg-warning',      color: '#a16207' },
        };
        const pick = statusMap[status] || statusMap.up;

        modal.querySelector('.log-time-detail').textContent    = btn.getAttribute('data-log-time');
        modal.querySelector('.log-status-badge').textContent   = pick.label;
        modal.querySelector('.log-status-badge').className     = 'badge rounded-pill log-status-badge ' + pick.bg;
        modal.querySelector('.log-device-detail').textContent  = btn.getAttribute('data-log-device');
        modal.querySelector('.log-ip-detail').textContent      = btn.getAttribute('data-log-ip');
        modal.querySelector('.log-reason-detail').textContent  = btn.getAttribute('data-log-reason');
        modal.querySelector('.log-latency-detail').textContent = status === 'up'
            ? (btn.getAttribute('data-log-latency') + ' ms')
            : '—';
    });

    // Auto-submit saat status / perangkat diganti cepat dari pagination-safe (opsional)
    document.getElementById('filterStatus')?.addEventListener('change', function (e) {
        const reset = document.querySelector('.log-filter form');
        reset && reset.submit();
    });
</script>
@endpush