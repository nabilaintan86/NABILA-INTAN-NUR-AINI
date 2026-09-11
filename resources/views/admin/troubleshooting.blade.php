@extends('layouts.admin')

@section('title', 'Troubleshooting · RADNET NOC')

@push('styles')
<style>
    .ts-kpi .ts-kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .ts-kpi .ts-kpi-number {
        font-size: 26px;
        font-weight: 800;
        line-height: 1.1;
    }
    .ts-kpi .ts-kpi-label {
        font-size: 11.5px;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .kpi-blue  { background: #eff6ff; color: #2563eb; }
    .kpi-red   { background: #fee2e2; color: #dc2626; }
    .kpi-green { background: #dcfce7; color: #16a34a; }

    .ts-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
    }
    .ts-badge-down    { background: #fee2e2; color: #dc2626; }
    .ts-badge-warning { background: #fef9c3; color: #a16207; }
    .ts-badge-up      { background: #dcfce7; color: #15803d; }

    .filter-chip {
        font-size: 12.5px;
        font-weight: 600;
        padding: 7px 16px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        cursor: pointer;
        transition: all .15s ease;
    }
    .filter-chip.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #fff;
        box-shadow: 0 4px 10px rgba(37, 99, 235, .25);
    }

    .ts-table td, .ts-table th { vertical-align: middle; font-size: 12.5px; }
    .ts-table .device-name { font-weight: 700; color: #0f172a; }
    .ts-table .device-ip   { font-family: 'Consolas', monospace; font-size: 11.5px; color: #64748b; }

    .btn-diagnose {
        font-size: 12px;
        font-weight: 700;
        border-radius: 8px;
    }

    .diag-output {
        background: #0f172a;
        color: #cbd5e1;
        font-family: 'Consolas', 'Menlo', monospace;
        font-size: 12px;
        line-height: 1.55;
        border-radius: 12px;
        padding: 16px;
        max-height: 240px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .diag-empty-state {
        color: #475569;
        font-family: 'Consolas', monospace;
        font-size: 12.5px;
        text-align: center;
        padding: 40px 20px;
    }

    .log-entry {
        border-left: 3px solid #e2e8f0;
        padding: 10px 14px;
    }
    .log-entry.log-up      { border-left-color: #22c55e; background: #f8fafc; }
    .log-entry.log-down    { border-left-color: #ef4444; background: #fff7f7; }
    .log-entry.log-warning { border-left-color: #f59e0b; background: #fffbeb; }
    .log-entry .log-meta { font-size: 10.5px; color: #94a3b8; letter-spacing: .3px; }

    .spinner-mini {
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255,255,255,.35);
        border-top-color: #fff;
        border-radius: 50%;
        display: inline-block;
        animation: ts-spin .7s linear infinite;
    }
    @keyframes ts-spin { to { transform: rotate(360deg); } }
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
                                <i class="fas fa-screwdriver-wrench text-primary me-2"></i>
                                Troubleshooting Center
                            </h2>
                            <nav>
                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Troubleshooting
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="nk-block-head-content">
                            <span class="badge rounded-pill bg-soft-warning">
                                <em class="icon ni ni-alert me-1"></em>
                                {{ $problemDevices->count() }} perangkat perlu perhatian
                            </span>
                        </div>
                    </div>
                </div>

                {{-- KPI CARDS --}}
                <div class="row g-gs mb-2">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-bordered h-100">
                            <div class="card-body ts-kpi">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="ts-kpi-icon kpi-blue"><em class="icon ni ni-server"></em></div>
                                    <div>
                                        <div class="ts-kpi-number">{{ number_format($totalChecked) }}</div>
                                        <div class="ts-kpi-label">Total Perangkat Diperiksa</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-bordered h-100">
                            <div class="card-body ts-kpi">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="ts-kpi-icon kpi-red"><em class="icon ni ni-alert-circle"></em></div>
                                    <div>
                                        <div class="ts-kpi-number">{{ $criticalCount }}</div>
                                        <div class="ts-kpi-label">Gangguan Kritis (DOWN)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="card card-bordered h-100">
                            <div class="card-body ts-kpi">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="ts-kpi-icon kpi-green"><em class="icon ni ni-check-circle"></em></div>
                                    <div>
                                        <div class="ts-kpi-number">{{ $recoveryRate }}%</div>
                                        <div class="ts-kpi-label">Tingkat Pemulihan (Recovery)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PROBLEM DEVICE TABLE --}}
                <div class="nk-block mt-3">
                    <div class="card card-bordered">
                        <div class="card-header border-0 pt-3 px-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h6 class="overline-title mb-0 mt-2">
                                    <em class="icon ni ni-activity me-1"></em>
                                    Perangkat Bermasalah ({{ $problemDevices->count() }})
                                </h6>
                                <div class="d-flex flex-wrap gap-2" id="tsFilterBar">
                                    <button type="button" class="filter-chip active" data-filter="all">
                                        Semua ({{ $problemDevices->count() }})
                                    </button>
                                    <button type="button" class="filter-chip" data-filter="down">
                                        DOWN ({{ $problemDevices->where('status','down')->count() }})
                                    </button>
                                    <button type="button" class="filter-chip" data-filter="warning">
                                        Warning ({{ $problemDevices->where('status','warning')->count() }})
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-2">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle ts-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Perangkat</th>
                                            <th>IP Address</th>
                                            <th>Type</th>
                                            <th>Responded</th>
                                            <th>Avg Latency</th>
                                            <th>Down time</th>
                                            <th>Alerts</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tsDeviceRows">
                                        @forelse($problemDevices as $index => $device)
                                            <tr class="ts-row" data-status="{{ $device->status }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.router.detail', $device->id) }}"
                                                       class="device-name text-decoration-none">
                                                        {{ $device->name }}
                                                    </a>
                                                </td>
                                                <td><span class="device-ip">{{ $device->ip_address }}</span></td>
                                                <td><span class="badge bg-soft-primary">{{ $device->type }}</span></td>
                                                <td>
                                                    <span class="{{ $device->responded_percentage < 90 ? 'text-danger fw-bold' : 'text-warning fw-bold' }}">
                                                        {{ $device->responded_percentage }}%
                                                    </span>
                                                </td>
                                                <td>{{ $device->avg_delay }} ms</td>
                                                <td>{{ $device->down_time ?? '-' }}</td>
                                                <td>
                                                    @if($device->alerts_count > 0)
                                                        <span class="badge bg-danger">{{ $device->alerts_count }}</span>
                                                    @else
                                                        <span class="text-muted">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="ts-badge ts-badge-{{ $device->status }}">
                                                        {{ strtoupper($device->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button"
                                                            class="btn btn-sm btn-primary btn-diagnose"
                                                            data-device-id="{{ $device->id }}"
                                                            data-name="{{ $device->name }}"
                                                            data-ip="{{ $device->ip_address }}">
                                                        <em class="icon ni ni-reload me-1"></em>Re-Test Connection
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-5">
                                                    <em class="icon ni ni-shield-check d-block mb-2" style="font-size:32px;"></em>
                                                    Tidak ada perangkat bermasalah. Semua perangkat dalam kondisi normal.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DIAGNOSTIC LOG + DASHBOARD SIDE --}}
                <div class="nk-block mt-3">
                    <div class="row g-gs">
                        <div class="col-xl-8">
                            <div class="card card-bordered h-100">
                                <div class="card-header">
                                    <h6 class="overline-title">
                                        <em class="icon ni ni-file-text me-1"></em>
                                        Troubleshooting Log — Riwayat Pemeriksaan Terakhir
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="tsLogFeed">
                                        @forelse($logs as $log)
                                            <div class="log-entry log-{{ $log->status }} mb-2">
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span class="ts-badge ts-badge-{{ $log->status }}">
                                                        {{ strtoupper($log->status) }}
                                                    </span>
                                                    <strong class="small">{{ $log->device_name }}</strong>
                                                    <span class="device-ip small" style="font-family:monospace; color:#94a3b8;">
                                                        {{ $log->ip_address }}
                                                    </span>
                                                    <span class="ms-auto log-meta text-end">
                                                        {{ $log->checked_at->format('d M Y · H:i:s') }}
                                                        @if($log->duration_ms) · {{ $log->duration_ms }} ms @endif
                                                    </span>
                                                </div>
                                                <div class="mt-1 small text-muted">
                                                    {{ $log->action }} — <span class="fw-semibold">{{ $log->diagnostic }}</span>
                                                    @if($log->status === 'up' && $log->latency !== null)
                                                        · Latency <span class="fw-bold text-success">{{ $log->latency }} ms</span>
                                                    @endif
                                                    @if($log->error)
                                                        · <span class="text-danger">{{ $log->error }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="diag-empty-state">
                                                &gt; Belum ada riwayat diagnostik.<br>
                                                &gt; Jalankan <strong>Re-Test Connection</strong> pada perangkat bermasalah.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card card-bordered h-100">
                                <div class="card-header">
                                    <h6 class="overline-title">
                                        <em class="icon ni ni-info me-1"></em>
                                        Panduan Cepat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                            <span class="ts-badge ts-badge-down mt-1">DOWN</span>
                                            <span class="small">
                                                Gagal total — <strong>Re-Test Connection</strong> untuk verifikasi
                                                dan memicu diagnostik ICMP real-time.
                                            </span>
                                        </li>
                                        <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                            <span class="ts-badge ts-badge-warning mt-1">WARNING</span>
                                            <span class="small">
                                                Degradasi / timeout parsial — jalankan diagnostik untuk melihat
                                                detail latensi dan packet loss terbaru.
                                            </span>
                                        </li>
                                        <li class="list-group-item px-0 d-flex gap-3 align-items-start">
                                            <span class="ts-badge ts-badge-up mt-1">UP</span>
                                            <span class="small">
                                                Normal — hasil diagnostik terakhir mencatat perangkat merespons,
                                                tercatat pada log di samping.
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="mt-3 p-3 rounded" style="background:#f8fafc; font-size:12px; color:#475569;">
                                        <em class="icon ni ni-info-circle text-primary me-1"></em>
                                        Setiap pemeriksaan tersimpan otomatis ke <strong>Troubleshooting Log</strong>
                                        untuk rekam jejak tindakan perbaikan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- DIAGNOSTIC RESULT MODAL --}}
<div class="modal fade" id="diagnosticModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-stethoscope text-primary me-2"></i>
                    Instant Diagnostic Result
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div id="diagLoading" class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <div class="text-muted">Menjalankan pemeriksaan koneksi real-time…</div>
                </div>

                <div id="diagContent" style="display:none;">
                    <input type="hidden" id="diagDeviceId">

                    <div class="ts-kpi d-flex align-items-center gap-3 mb-3">
                        <div id="diagBadgeWrap"></div>
                        <div>
                            <div class="fw-bold fs-5" id="diagDeviceName">—</div>
                            <div class="text-muted small" style="font-family:monospace;" id="diagDeviceIp">—</div>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="small text-muted">Waktu</div>
                            <div class="fw-semibold" id="diagTime">—</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded" style="background:#f8fafc;">
                                <div class="small text-muted">Status</div>
                                <div class="fw-bold" id="diagResultStatus">—</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded" style="background:#f8fafc;">
                                <div class="small text-muted">Latency</div>
                                <div class="fw-bold" id="diagLatency">—</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded" style="background:#f8fafc;">
                                <div class="small text-muted">Metode</div>
                                <div class="fw-bold" id="diagMethod">—</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded" style="background:#f8fafc;">
                                <div class="small text-muted">Durasi</div>
                                <div class="fw-bold" id="diagDuration">—</div>
                            </div>
                        </div>
                    </div>

                    <label class="small fw-bold text-muted text-uppercase mb-2 d-block">RAW Output</label>
                    <div class="diag-output" id="diagOutput">—</div>

                    {{-- KESIMPULAN RINGKAS (RAMAH HR / NON-TEKNIS) --}}
                    <div id="diagSummaryPanel" class="mt-4" style="display:none;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <em class="icon ni ni-briefcase text-primary"></em>
                            <strong class="small text-uppercase text-dark" style="letter-spacing:.4px;">
                                Ringkasan untuk Manajemen
                            </strong>
                        </div>
                        <div class="p-3 rounded d-flex align-items-start gap-2" id="diagSummaryBody">
                            <em class="icon ni ni-info-fill" style="font-size:20px; flex-shrink:0;"></em>
                            <div id="diagSummaryText" class="small" style="line-height:1.55;">—</div>
                        </div>
                    </div>

                    {{-- ROOT CAUSE ANALYSIS --}}
                    <div id="diagAnalysisPanel" class="mt-4" style="display:none;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <em class="icon ni ni-zoom-in text-primary"></em>
                            <strong class="small text-uppercase text-dark" style="letter-spacing:.4px;">
                                Analisis Penyebab (Root Cause)
                            </strong>
                        </div>
                        <div class="p-3 rounded" id="diagAnalysisBody" style="background:#fffbeb; border:1px solid #fde68a;">
                            <div class="d-flex align-items-center gap-2">
                                <span id="diagCauseBadge"><span class="ts-badge ts-badge-warning">TERDETEKSI</span></span>
                                <strong id="diagCauseText">—</strong>
                            </div>
                            <div class="mt-2 small text-muted">Rekomendasi langkah teknis:</div>
                            <ol class="mt-1 mb-0 ps-3 small" id="diagAnalysisSteps"></ol>
                        </div>
                    </div>

                    {{-- RECOVERY SUCCESS BANNER --}}
                    <div id="diagRecoverySuccess" class="mt-4 p-3 rounded d-flex align-items-center gap-2"
                         style="display:none; background:#dcfce7; border:1px solid #86efac; color:#15803d;">
                        <em class="icon ni ni-check-circle" style="font-size:20px;"></em>
                        <div>
                            <strong>Status perangkat berhasil dipulihkan ke UP.</strong>
                            <div class="small" id="diagRecoveryMsg"></div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dim btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="btnResolveStatus">
                    <em class="icon ni ni-check-circle me-1"></em>Pulihkan Status (Set to UP)
                </button>
                <button type="button" class="btn btn-primary" id="btnRunAgain">
                    <em class="icon ni ni-reload me-1"></em>Jalankan Ulang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]');
    const routeDiagnose = '{{ route("admin.troubleshooting.diagnose") }}';
    const routeResolve  = '{{ route("admin.troubleshooting.resolve", ["id" => "__ID__"]) }}';

    const modal = document.getElementById('diagnosticModal');
    const modalBootstrap = new bootstrap.Modal(modal);

    const loading = document.getElementById('diagLoading');
    const content = document.getElementById('diagContent');

    function escHTML(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function statusBadge(status) {
        const map = {
            up:      'ts-badge-up',
            down:    'ts-badge-down',
            warning: 'ts-badge-warning',
        };
        return '<span class="ts-badge ' + (map[status] || 'ts-badge-warning') + '">' + status.toUpperCase() + '</span>';
    }

    function resetAnalysis() {
        document.getElementById('diagAnalysisPanel').style.display = 'none';
        document.getElementById('diagSummaryPanel').style.display = 'none';
        document.getElementById('diagRecoverySuccess').style.display = 'none';
        const btn = document.getElementById('btnResolveStatus');
        btn.style.display = '';
        btn.disabled = false;
        btn.innerHTML = '<em class="icon ni ni-check-circle me-1"></em>Pulihkan Status (Set to UP)';
    }

    function renderAnalysis(analysis) {
        const panel = document.getElementById('diagAnalysisPanel');
        if (!analysis || !analysis.cause) {
            panel.style.display = 'none';
            return;
        }

        const isOffline = analysis.category === 'offline';
        const isLatency = analysis.category === 'latency';

        panel.style.display = 'block';

        const body = document.getElementById('diagAnalysisBody');
        body.style.background = isOffline ? '#fef2f2' : (isLatency ? '#fffbeb' : '#f8fafc');
        body.style.borderColor = isOffline ? '#fecaca' : (isLatency ? '#fde68a' : '#e2e8f0');

        const badge = isOffline
            ? '<span class="ts-badge ts-badge-down">KRITIS</span>'
            : (isLatency ? '<span class="ts-badge ts-badge-warning">PERINGATAN</span>' : '<span class="ts-badge ts-badge-warning">TERDETEKSI</span>');
        document.getElementById('diagCauseBadge').innerHTML = badge;
        document.getElementById('diagCauseText').textContent = analysis.cause;

        if (analysis.summary) {
            const sumPanel = document.getElementById('diagSummaryPanel');
            const sumBody  = document.getElementById('diagSummaryBody');
            sumPanel.style.display = 'block';
            sumBody.style.background = isOffline ? '#fef2f2' : (isLatency ? '#fffbeb' : '#f0fdf4');
            sumBody.style.border = isOffline ? '1px solid #fecaca' : (isLatency ? '1px solid #fde68a' : '1px solid #bbf7d0');
            sumBody.style.color = isOffline ? '#991b1b' : (isLatency ? '#92400e' : '#166534');
            document.getElementById('diagSummaryText').textContent = analysis.summary;
        } else {
            document.getElementById('diagSummaryPanel').style.display = 'none';
        }

        const steps = document.getElementById('diagAnalysisSteps');
        steps.innerHTML = '';
        (analysis.steps || []).forEach(function (step) {
            const li = document.createElement('li');
            li.textContent = step;
            steps.appendChild(li);
        });
    }

    function runDiagnostic(deviceId, name, ip) {
        loading.style.display = 'block';
        content.style.display = 'none';
        resetAnalysis();

        document.getElementById('diagDeviceId').value = deviceId;
        document.getElementById('diagDeviceName').textContent = name;
        document.getElementById('diagDeviceIp').textContent = ip;

        modalBootstrap.show();

        const body = new FormData();
        body.append('device_id', deviceId);

        fetch(routeDiagnose, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf ? csrf.content : '' },
            body: body,
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            loading.style.display = 'none';
            content.style.display = 'block';

            if (!res.success) {
                document.getElementById('diagBadgeWrap').innerHTML = statusBadge('warning');
                document.getElementById('diagResultStatus').textContent = 'GAGAL';
                document.getElementById('diagLatency').textContent = '—';
                document.getElementById('diagMethod').textContent = '—';
                document.getElementById('diagDuration').textContent = '—';
                document.getElementById('diagTime').textContent = '—';
                document.getElementById('diagOutput').textContent = res.message || 'Terjadi kesalahan menjalankan diagnostik.';
                return;
            }

            const log = res.log;
            document.getElementById('diagBadgeWrap').innerHTML = statusBadge(log.status);
            document.getElementById('diagResultStatus').textContent = log.status.toUpperCase();
            document.getElementById('diagLatency').innerHTML = log.latency !== null && log.latency !== undefined
                ? log.latency + ' ms'
                : '<span class="text-muted">—</span>';
            document.getElementById('diagMethod').textContent = log.diagnostic;
            document.getElementById('diagDuration').textContent = (Number(log.duration_ms) || 0) + ' ms';
            document.getElementById('diagTime').textContent = log.checked_at;

            const output = log.output || (log.error ? 'ERROR: ' + log.error : 'No output.');
            document.getElementById('diagOutput').textContent = output;

            renderAnalysis(res.analysis);
            prependLogEntry(log);
        })
        .catch(function () {
            loading.style.display = 'none';
            content.style.display = 'block';
            document.getElementById('diagOutput').textContent =
                'Tidak dapat terhubung ke server. Coba lagi nanti.';
        });
    }

    function resolveDevice() {
        if (!confirm('Yakin ingin memulihkan status perangkat ini ke UP? (simulasi perbaikan)')) {
            return;
        }

        const id    = document.getElementById('diagDeviceId').value;
        const url   = routeResolve.replace('__ID__', String(id));
        const btn   = document.getElementById('btnResolveStatus');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-mini me-1"></span>Menyimpan…';

        const body = new FormData();
        body.append('_method', 'POST');
        body.append('id', id);

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf ? csrf.content : '' },
            body: body,
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (!res.success) {
                alert(res.message || 'Gagal memulihkan status.');
                btn.disabled = false;
                btn.innerHTML = '<em class="icon ni ni-check-circle me-1"></em>Pulihkan Status (Set to UP)';
                return;
            }

            document.getElementById('diagSummaryPanel').style.display = 'none';
            document.getElementById('diagAnalysisPanel').style.display = 'none';
            btn.style.display = 'none';
            document.getElementById('diagBadgeWrap').innerHTML = statusBadge('up');
            document.getElementById('diagResultStatus').textContent = 'UP';
            document.getElementById('diagRecoveryMsg').textContent = res.message;
            document.getElementById('diagRecoverySuccess').style.display = 'flex';

            prependLogEntry(res.log);

            setTimeout(function () {
                modalBootstrap.hide();
                refreshTroubleshootingPage();
            }, 1400);
        })
        .catch(function () {
            alert('Tidak dapat terhubung ke server. Coba lagi nanti.');
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-check-circle me-1"></em>Pulihkan Status (Set to UP)';
        });
    }

    function refreshTroubleshootingPage() {
        fetch('{{ route("admin.troubleshooting") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const fresh = doc.querySelector('.nk-content-body');
            const target = document.querySelector('.nk-content-body');
            if (fresh && target) {
                target.innerHTML = fresh.innerHTML;
            }
        })
        .catch(function () {});
    }

    function prependLogEntry(log) {
        const feed = document.getElementById('tsLogFeed');
        if (!feed) return;
        const empty = feed.querySelector('.diag-empty-state');
        if (empty) empty.remove();

        const entry = document.createElement('div');
        entry.className = 'log-entry log-' + log.status + ' mb-2';
        entry.innerHTML =
            '<div class="d-flex align-items-center gap-2 flex-wrap">' +
                statusBadge(log.status) +
                '<strong class="small">' + escHTML(log.device_name) + '</strong>' +
                '<span class="small" style="font-family:monospace; color:#94a3b8;">' + escHTML(log.ip_address) + '</span>' +
                '<span class="ms-auto text-end small" style="color:#94a3b8;">' + escHTML(log.checked_at) + (log.duration_ms ? ' · ' + log.duration_ms + ' ms' : '') + '</span>' +
            '</div>' +
            '<div class="mt-1 small text-muted">' +
                escHTML(log.action) + ' — <span class="fw-semibold">' + escHTML(log.diagnostic) + '</span>' +
                (log.status === 'up' && log.latency ? ' · Latency <span class="fw-bold text-success">' + log.latency + ' ms</span>' : '') +
            '</div>';
        feed.prepend(entry);
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-diagnose');
        if (btn) {
            runDiagnostic(btn.dataset.deviceId, btn.dataset.name, btn.dataset.ip);
            return;
        }

        const chip = e.target.closest('#tsFilterBar .filter-chip');
        if (chip) {
            document.querySelectorAll('#tsFilterBar .filter-chip').forEach(function (c) {
                c.classList.remove('active');
            });
            chip.classList.add('active');
            const filter = chip.dataset.filter;
            document.querySelectorAll('#tsDeviceRows .ts-row').forEach(function (row) {
                row.style.display = (filter === 'all' || row.dataset.status === filter) ? '' : 'none';
            });
        }
    });

    document.getElementById('btnRunAgain').addEventListener('click', function () {
        const id   = document.getElementById('diagDeviceId').value;
        const name = document.getElementById('diagDeviceName').textContent;
        const ip   = document.getElementById('diagDeviceIp').textContent;
        if (id) runDiagnostic(id, name, ip);
    });

    document.getElementById('btnResolveStatus').addEventListener('click', resolveDevice);
})();
</script>
@endpush