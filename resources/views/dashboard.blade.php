@extends('layouts.admin')

@section('title', 'Dashboard · RADNET NOC')

@push('styles')
<style>
    :root {
        --noc-primary: #3b82f6;
        --noc-success: #10b981;
        --noc-danger: #ef4444;
        --noc-warning: #f59e0b;
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.9); opacity: 0.7; }
        70% { transform: scale(1.4); opacity: 0; }
        100% { transform: scale(1.4); opacity: 0; }
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .noc-dashboard { animation: fadeInScale 0.4s ease-out; }

    .noc-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eef2f7;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .noc-card:hover {
        box-shadow: 0 12px 32px -8px rgba(15, 23, 42, .12);
        transform: translateY(-2px);
    }
    .noc-card-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #ffffff 0%, #fcfdff 100%);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .noc-card-body { padding: 20px 22px; }

    /* HEADER */
    .noc-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 60%, #1e1b4b 100%);
        border-radius: 18px;
        padding: 22px 26px;
        color: #fff;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px rgba(30, 41, 59, .4);
    }
    .noc-header::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(59,130,246,.35) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }
    .noc-header::after {
        content: '';
        position: absolute;
        bottom: -60%; left: -5%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(139,92,246,.25) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }
    .noc-header > * { position: relative; z-index: 1; }
    .noc-header h4 {
        color: #fff;
        font-weight: 700;
        letter-spacing: -0.3px;
        margin-bottom: 4px;
        font-size: 1.4rem;
    }
    .noc-header .subtitle { color: #94a3b8; font-size: 0.8rem; font-weight: 500; }

    .live-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 11.5px;
        font-weight: 600;
        border: 1px solid rgba(16, 185, 129, 0.3);
        backdrop-filter: blur(10px);
    }
    .live-dot {
        position: relative;
        width: 8px; height: 8px;
        background: #10b981;
        border-radius: 50%;
    }
    .live-dot::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: #10b981;
        animation: pulse-ring 1.8s ease-out infinite;
    }

    /* KPI CARDS */
    .kpi-card-new {
        position: relative;
        border-radius: 16px;
        padding: 22px 22px 20px;
        color: #fff;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 158px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        animation: slideInUp 0.5s ease-out backwards;
        box-shadow: 0 4px 14px -4px rgba(15, 23, 42, .15);
    }
    .kpi-card-new:nth-child(1) { animation-delay: 0.05s; }
    .kpi-card-new:nth-child(2) { animation-delay: 0.10s; }
    .kpi-card-new:nth-child(3) { animation-delay: 0.15s; }
    .kpi-card-new:nth-child(4) { animation-delay: 0.20s; }
    .kpi-card-new::before {
        content: '';
        position: absolute;
        top: -40%; right: -30%;
        width: 200px; height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transition: all 0.5s ease;
    }
    .kpi-card-new::after {
        content: '';
        position: absolute;
        bottom: -60%; right: -20%;
        width: 150px; height: 150px;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 50%;
        transition: all 0.5s ease;
    }
    .kpi-card-new:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 40px -12px rgba(15, 23, 42, .3);
    }
    .kpi-card-new:hover::before { transform: scale(1.3); }
    .kpi-card-new > * { position: relative; z-index: 1; }
    .kpi-card-new.up { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .kpi-card-new.down { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); }
    .kpi-card-new.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .kpi-card-new.availability { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); }

    .kpi-icon-circle {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        font-size: 20px;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: all 0.3s ease;
    }
    .kpi-card-new:hover .kpi-icon-circle {
        transform: rotate(-8deg) scale(1.1);
        background: rgba(255, 255, 255, 0.3);
    }
    .kpi-number-new {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    .kpi-label-new { font-size: 0.85rem; font-weight: 600; opacity: 0.95; margin-top: 4px; }
    .kpi-sub-new { font-size: 0.72rem; opacity: 0.8; margin-top: 6px; display: flex; align-items: center; gap: 6px; }

    /* SECTION TITLE */
    .section-title-new {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }
    .section-title-new i { font-size: 1rem; color: var(--noc-primary); }
    .section-subtitle-new { font-size: 0.72rem; color: #94a3b8; margin-top: 3px; font-weight: 500; }

    /* SUMMARY CARDS (CLICKABLE) */
    .summary-card-new {
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 16px;
        background: #fff;
        border: 1px solid #eef2f7;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        position: relative;
        overflow: hidden;
    }
    .summary-card-new::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: var(--accent, #3b82f6);
        transition: width 0.3s ease;
    }
    .summary-card-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px -8px rgba(15, 23, 42, .15);
        border-color: var(--accent, #3b82f6);
    }
    .summary-card-new:hover::before { width: 6px; }
    .summary-icon-new {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .summary-card-new:hover .summary-icon-new {
        transform: rotate(-8deg) scale(1.08);
    }

    /* LATENCY */
    .latency-chart-wrapper { height: 340px; position: relative; }
    .latency-summary-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 12px 18px;
        border-radius: 12px;
        min-width: 110px;
        border: 1px solid #eef2f7;
        transition: all 0.3s ease;
    }
    .latency-summary-item:hover {
        border-color: var(--noc-primary);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -8px rgba(59, 130, 246, .3);
    }
    .latency-summary-item .label {
        font-size: 0.7rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
    }
    .latency-summary-item .value {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 2px;
        letter-spacing: -0.3px;
    }

    /* ALERTS */
    .alert-item-new {
        padding: 12px 14px;
        border-radius: 12px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        border: 1px solid #f1f5f9;
        transition: all 0.25s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .alert-item-new::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        transition: width 0.3s ease;
    }
    .alert-item-new.critical::before { background: linear-gradient(180deg, #ef4444, #b91c1c); }
    .alert-item-new.warning::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
    .alert-item-new:hover {
        background: #f8fafc;
        transform: translateX(4px);
        box-shadow: 0 4px 12px -2px rgba(15, 23, 42, .08);
    }
    .alert-item-new:hover::before { width: 6px; }
    .alert-icon-new {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 15px;
    }
    .alert-item-new.critical .alert-icon-new {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
    }
    .alert-item-new.warning .alert-icon-new {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #ca8a04;
    }

    /* ACTIVITY FEED */
    .feed-item-new {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 22px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.25s ease;
        animation: slideInUp 0.3s ease-out backwards;
    }
    .feed-item-new:hover {
        background: linear-gradient(90deg, #f8fafc 0%, #fff 100%);
        padding-left: 26px;
    }
    .feed-item-new:last-child { border-bottom: none; }

    /* MODAL */
    .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 24px 60px -12px rgba(15, 23, 42, .25);
    }
    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 26px;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        border-radius: 18px 18px 0 0;
    }
    .kpi-table-wrap {
        max-height: 62vh;
        overflow: auto;
        border-radius: 12px;
        border: 1px solid #eef2f7;
    }
    .kpi-table { width: 100%; margin: 0; font-size: 12.5px; }
    .kpi-table thead th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        color: #475569;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 12px 14px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    .kpi-table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .kpi-table tbody tr:hover { background: #f8fafc; }
    .kpi-table tbody tr { cursor: pointer; }

    @media (max-width: 767px) {
        .latency-chart-wrapper { height: 260px; }
        .kpi-number-new { font-size: 1.9rem; }
        .noc-header { padding: 18px 20px; }
    }
</style>
@endpush


@section('content')
<div class="noc-dashboard">

    {{-- HEADER --}}
    <div class="noc-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h4 class="mb-0">
                        <i class="fas fa-satellite-dish me-2" style="color:#60a5fa;"></i>
                        RadneXt Network Monitoring
                    </h4>
                    <span class="live-status">
                        <span class="live-dot"></span>
                        Live Monitoring
                    </span>
                </div>
                <div class="subtitle mt-2">
                    Network Operations Center · Real-time status &amp; performance
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge" style="background: rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.15); padding: 8px 14px; border-radius: 10px; font-weight: 500;">
                    <i class="far fa-clock me-1"></i>
                    <span id="dashboardClock">{{ now()->format('Y/m/d H:i:s') }}</span>
                </span>
                <button class="btn btn-sm" onclick="refreshDashboard()" style="background: rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.15); border-radius: 10px;">
                    <i class="fas fa-sync-alt" id="refreshIcon"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="row g-3 mb-3" id="kpiContainer"></div>

    {{-- SUMMARY CARDS (CLICKABLE) --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="summary-card-new" style="--accent:#3b82f6;" onclick="showSummary('total')">
                <div class="summary-icon-new" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb;">
                    <i class="fas fa-server"></i>
                </div>
                <div style="flex:1;">
                    <div class="section-subtitle-new">TOTAL MONITORED DEVICE</div>
                    <div class="fw-bold" style="font-size:1.2rem;color:#0f172a;letter-spacing:-0.3px;" id="totalDeviceSummary">-</div>
                </div>
                <i class="fas fa-chevron-right text-muted" style="font-size:12px;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card-new" style="--accent:#10b981;" onclick="showSummary('latency')">
                <div class="summary-icon-new" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669;">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <div style="flex:1;">
                    <div class="section-subtitle-new">AVERAGE LATENCY</div>
                    <div class="fw-bold" style="font-size:1.2rem;color:#0f172a;letter-spacing:-0.3px;" id="averageLatencySummary">-</div>
                </div>
                <i class="fas fa-chevron-right text-muted" style="font-size:12px;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card-new" style="--accent:#ef4444;" onclick="showSummary('alerts')">
                <div class="summary-icon-new" style="background:linear-gradient(135deg,#fee2e2,#fecaca);color:#dc2626;">
                    <i class="fas fa-bell"></i>
                </div>
                <div style="flex:1;">
                    <div class="section-subtitle-new">TOTAL ALERTS</div>
                    <div class="fw-bold" style="font-size:1.2rem;color:#0f172a;letter-spacing:-0.3px;" id="lastPollingSummary">-</div>
                </div>
                <i class="fas fa-chevron-right text-muted" style="font-size:12px;"></i>
            </div>
        </div>
    </div>

    {{-- LATENCY CHART --}}
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="noc-card">
                <div class="noc-card-header">
                    <div>
                        <h6 class="section-title-new">
                            <i class="fas fa-chart-area"></i>
                            Network Latency Monitoring
                        </h6>
                        <div class="section-subtitle-new">Average ICMP latency across monitored network links</div>
                    </div>
                    <select id="latencyPeriod" class="form-select form-select-sm" style="width:auto;border-radius:10px;">
                        <option value="6">Last 6 Hours</option>
                        <option value="12">Last 12 Hours</option>
                        <option value="24" selected>Last 24 Hours</option>
                        <option value="168">Last 7 Days</option>
                    </select>
                </div>
                <div class="noc-card-body">
                    <div class="d-flex gap-3 mb-4 flex-wrap">
                        <div class="latency-summary-item">
                            <div class="label">Current</div>
                            <div class="value" id="latencyCurrent">-</div>
                        </div>
                        <div class="latency-summary-item">
                            <div class="label">Average</div>
                            <div class="value" id="latencyAverage">-</div>
                        </div>
                        <div class="latency-summary-item">
                            <div class="label">Peak</div>
                            <div class="value" id="latencyPeak">-</div>
                        </div>
                    </div>
                    <div class="latency-chart-wrapper">
                        <canvas id="latencyChart" style="display:block"></canvas>
                        <div id="latencyEmpty" class="text-center text-muted" style="display:none;position:absolute;inset:0;align-items:center;justify-content:center;flex-direction:column;font-size:13px;">
                            <i class="fas fa-chart-line" style="font-size:40px;color:#cbd5e1;margin-bottom:12px;"></i>
                            <p class="mb-0">Belum ada data riwayat ping.<br>Scheduler mengisi otomatis setiap 1 menit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ALERTS + RECENT ACTIVITY --}}
<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="noc-card h-100">
            <div class="noc-card-header">
                <div>
                    <h6 class="section-title-new">
                        <i class="fas fa-exclamation-triangle" style="color:#ef4444;"></i>
                        Recent Alerts
                    </h6>
                    <div class="section-subtitle-new">Device yang membutuhkan perhatian</div>
                </div>
                <a href="{{ route('admin.notifikasi') }}" class="small text-primary">Lihat semua →</a>
            </div>
            <div class="noc-card-body" id="alertContainer"></div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="noc-card h-100">
            <div class="noc-card-header">
                <div>
                    <h6 class="section-title-new">
                        <i class="fas fa-history"></i>
                        Recent Activity
                    </h6>
                    <div class="section-subtitle-new">Aktivitas status device terbaru</div>
                </div>
                <a href="{{ route('admin.log') }}" class="small text-primary">Lihat semua →</a>
            </div>
            <div id="activityContainer"></div>
        </div>
    </div>
</div>

    {{-- FOOTER --}}
    <div class="mt-4 text-center text-muted small border-top pt-3">
        Copyright PT Radnet Digital Indonesia &copy; {{ date('Y') }}. All Right Reserved.
    </div>
</div>

{{-- KPI DETAIL MODAL --}}
<div class="modal fade" id="kpiDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width:min(96vw,1440px);">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="kpiDetailTitle">Daftar Device</h5>
                    <div class="text-muted small mt-1" id="kpiDetailSubtitle"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="kpiDetailBody"></div>
        </div>
    </div>
</div>

{{-- DEVICE DETAIL MODAL --}}
<div class="modal fade" id="routerDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="detailModalTitle">Device Detail</h5>
                    <small class="text-muted" id="detailModalSubtitle"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       DATA
    ============================================================ */
    const rawDevices = @json($devices);
    const allDevices = rawDevices.map(d => ({
        id: String(d.id),
        name: d.name,
        type: d.type,
        ip: d.ip_address,
        polls: d.polls,
        responded: d.responded_percentage,
        missed: d.missed_percentage,
        downTime: d.down_time || '-',
        period: d.period || '-',
        alerts: d.alerts_count,
        avgDelay: d.avg_delay,
        minDelay: d.min_delay,
        maxDelay: d.max_delay,
        status: d.status
    }));

    const deviceMap = {};
    allDevices.forEach(d => { deviceMap[d.id] = d; });

    const statusColor = s => ({ up: '#10b981', down: '#ef4444', warning: '#f59e0b' }[s] || '#94a3b8');
    const statusBadge = s => ({ up: 'success', down: 'danger', warning: 'warning' }[s] || 'secondary');
    const statusText = s => ({ up: 'UP', down: 'DOWN', warning: 'WARNING' }[s] || s.toUpperCase());

    /* ============================================================
       KPI CALC
    ============================================================ */
    const total = allDevices.length;
    const upCount = allDevices.filter(d => d.status === 'up').length;
    const downCount = allDevices.filter(d => d.status === 'down').length;
    const warningCount = allDevices.filter(d => d.status === 'warning').length;

    let respondedSum = 0;
    allDevices.forEach(d => {
        const r = parseFloat(d.responded);
        if (!isNaN(r)) respondedSum += r;
    });
    const availability = total > 0 ? respondedSum / total : 0;

    /* ============================================================
       KPI RENDER
    ============================================================ */
    document.getElementById('kpiContainer').innerHTML = `
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card-new up" onclick="showKpiDetail('up')">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-number-new" id="kpiUpNumber">${upCount}</div>
                        <div class="kpi-label-new">Devices UP</div>
                        <div class="kpi-sub-new">
                            <i class="fas fa-arrow-up"></i>
                            ${total ? Math.round((upCount / total) * 100) : 0}% of monitored
                        </div>
                    </div>
                    <div class="kpi-icon-circle"><i class="fas fa-check"></i></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card-new down" onclick="showKpiDetail('down')">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-number-new" id="kpiDownNumber">${downCount}</div>
                        <div class="kpi-label-new">Devices DOWN</div>
                        <div class="kpi-sub-new">
                            <i class="fas fa-exclamation-circle"></i>
                            ${downCount > 0 ? 'Perlu tindakan segera' : 'Semua device normal'}
                        </div>
                    </div>
                    <div class="kpi-icon-circle"><i class="fas fa-power-off"></i></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card-new warning" onclick="showKpiDetail('warning')">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-number-new" id="kpiWarningNumber">${warningCount}</div>
                        <div class="kpi-label-new">Warning</div>
                        <div class="kpi-sub-new">
                            <i class="fas fa-exclamation-triangle"></i>
                            ${warningCount > 0 ? 'Latency tinggi' : 'Tidak ada warning'}
                        </div>
                    </div>
                    <div class="kpi-icon-circle"><i class="fas fa-exclamation"></i></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card-new availability" onclick="showKpiDetail('all')">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-number-new" id="kpiAvailabilityNumber">${availability.toFixed(1)}%</div>
                        <div class="kpi-label-new">Availability</div>
                        <div class="kpi-sub-new">
                            <i class="fas fa-signal"></i>
                            Last 24 hours
                        </div>
                    </div>
                    <div class="kpi-icon-circle"><i class="fas fa-wifi"></i></div>
                </div>
            </div>
        </div>
    `;

    /* ============================================================
       SUMMARY
    ============================================================ */
    const averageLatency = total > 0
        ? allDevices.reduce((s, d) => s + Number(d.avgDelay || 0), 0) / total
        : 0;
    const totalAlerts = allDevices.reduce((s, d) => s + Number(d.alerts || 0), 0);

    document.getElementById('totalDeviceSummary').textContent = `${total} devices`;
    document.getElementById('averageLatencySummary').textContent = `${averageLatency.toFixed(1)} ms`;
    document.getElementById('lastPollingSummary').textContent = `${totalAlerts} alerts`;

    /* ============================================================
       LATENCY CHART
    ============================================================ */
    let latencyHistory = @json($latencyHistory);
    const initialLatency = latencyHistory['24'].values;

    const ctx = document.getElementById('latencyChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 340);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
    gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.12)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

    const latencyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: latencyHistory['24'].labels,
            datasets: [{
                label: 'Average Latency',
                data: initialLatency,
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: '#1e40af',
                spanGaps: true,
                animation: { duration: 1200, easing: 'easeOutQuart' }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#e2e8f0',
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: { label: c => `Latency: ${c.parsed.y} ms` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                    title: { display: true, text: 'Latency (ms)', color: '#64748b', font: { size: 11, weight: '600' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } },
                    title: { display: true, text: 'Time', color: '#64748b', font: { size: 11, weight: '600' } }
                }
            }
        }
    });

    function updateLatencySummary(values) {
        const present = (values || []).filter(v => v !== null && v !== undefined && !isNaN(v));
        if (!present.length) {
            ['latencyCurrent', 'latencyAverage', 'latencyPeak'].forEach(id =>
                document.getElementById(id).textContent = '-');
            return;
        }
        const current = present[present.length - 1];
        const average = present.reduce((s, v) => s + v, 0) / present.length;
        const peak = Math.max(...present);
        document.getElementById('latencyCurrent').textContent = `${current} ms`;
        document.getElementById('latencyAverage').textContent = `${average.toFixed(1)} ms`;
        document.getElementById('latencyPeak').textContent = `${peak} ms`;
    }

    function syncLatencyState(values) {
        const hasData = (values || []).some(v => v !== null && v !== undefined);
        document.getElementById('latencyEmpty').style.display = hasData ? 'none' : 'flex';
        document.getElementById('latencyChart').style.display = hasData ? 'block' : 'none';
    }

    updateLatencySummary(initialLatency);
    syncLatencyState(initialLatency);

    document.getElementById('latencyPeriod').addEventListener('change', function () {
        const data = latencyHistory[this.value];
        if (!data) return;
        latencyChart.data.labels = data.labels;
        latencyChart.data.datasets[0].data = data.values;
        latencyChart.update();
        updateLatencySummary(data.values);
        syncLatencyState(data.values);
    });

    /* ============================================================
       RECENT ALERTS
    ============================================================ */
    const alerts = allDevices
        .filter(d => d.status === 'down' || d.status === 'warning')
        .sort((a, b) => b.alerts - a.alerts)
        .slice(0, 6);

    document.getElementById('alertContainer').innerHTML = alerts.length === 0
    ? `<div class="text-center text-muted py-5">
            <i class="fas fa-check-circle" style="font-size:42px;color:#10b981;display:block;margin-bottom:12px;"></i>
            <div class="fw-semibold">Tidak ada alert aktif</div>
            <div class="small mt-1">Semua device dalam kondisi normal</div>
       </div>`
    : alerts.map(d => `
        <div class="alert-item-new ${d.status === 'down' ? 'critical' : 'warning'}" onclick="showDetail('${d.id}')">
            <div class="alert-icon-new">
                <i class="fas ${d.status === 'down' ? 'fa-times' : 'fa-exclamation'}"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:12.5px;font-weight:700;color:#0f172a;">${d.name}</div>
                <div style="font-size:10.5px;color:#94a3b8;margin-top:2px;">
                    ${d.ip} · ${d.avgDelay} ms · ${d.alerts} alerts
                </div>
            </div>
            <div style="font-size:10.5px;color:#94a3b8;white-space:nowrap;">${d.period}</div>
        </div>
    `).join('');

    /* ============================================================
       RECENT ACTIVITY
    ============================================================ */
    const activities = [...allDevices].sort((a, b) => b.alerts - a.alerts).slice(0, 6);

    document.getElementById('activityContainer').innerHTML = activities.length === 0
        ? `<div class="text-center text-muted py-5">
                <i class="fas fa-history" style="font-size:42px;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
                Belum ada aktivitas tercatat
           </div>`
        : activities.map((d, i) => `
            <div class="feed-item-new" style="animation-delay:${i * 0.05}s" onclick="showDetail('${d.id}')">
                <div style="min-width:60px;color:#64748b;font-size:11px;font-weight:700;padding-top:2px;">${d.period}</div>
                <span style="width:10px;height:10px;border-radius:50%;margin-top:5px;flex-shrink:0;background:${statusColor(d.status)};box-shadow:0 0 0 4px ${statusColor(d.status)}22;"></span>
                <div style="flex:1;">
                    <div style="font-weight:700;font-size:12.5px;color:#0f172a;">${d.name} · ${d.ip}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:3px;">
                        ${d.responded}% responded · ${d.avgDelay} ms avg
                        <span class="badge bg-${statusBadge(d.status)} ms-2" style="font-size:9.5px;">${statusText(d.status)}</span>
                    </div>
                </div>
            </div>
        `).join('');

    /* ============================================================
       HELPER: RENDER TABLE
    ============================================================ */
    function renderDeviceTable(devices, options = {}) {
        if (devices.length === 0) {
            return `<div class="text-center text-muted py-5">
                <i class="fas fa-inbox" style="font-size:42px;color:#cbd5e1;display:block;margin-bottom:12px;"></i>
                <div class="fw-semibold">Tidak ada device</div>
            </div>`;
        }

        const sortBy = options.sortBy || null;

        // Urutkan kalau ada sortBy (khusus untuk latency & alerts)
        if (sortBy === 'latency') {
            devices = [...devices].sort((a, b) => Number(b.avgDelay || 0) - Number(a.avgDelay || 0));
        } else if (sortBy === 'alerts') {
            devices = [...devices].sort((a, b) => Number(b.alerts || 0) - Number(a.alerts || 0));
        }

        const hasLatencyHighlight = sortBy === 'latency';
        const hasAlertsHighlight = sortBy === 'alerts';

        return `
            <div class="kpi-table-wrap">
                <table class="table align-middle kpi-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th><th>Type</th>
                            <th class="text-end">Latency</th>
                            <th class="text-end">Alerts</th>
                            <th class="text-end">Responded</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${devices.map((d, i) => {
                            const latColor = hasLatencyHighlight
                                ? (Number(d.avgDelay) > 100 ? '#ef4444' : Number(d.avgDelay) > 50 ? '#f59e0b' : '#10b981')
                                : '#0f172a';
                            const alertColor = hasAlertsHighlight && d.alerts > 0 ? '#ef4444' : '#0f172a';

                            return `
                            <tr onclick="showDetail('${d.id}')">
                                <td style="color:#94a3b8;font-weight:600;">${i + 1}</td>
                                <td>
                                    <div style="font-weight:700;color:#0f172a;">${d.name}</div>
                                    <div style="font-family:monospace;font-size:11px;color:#64748b;">${d.ip}</div>
                                </td>
                                <td>${d.type}</td>
                                <td class="text-end" style="color:${latColor};font-weight:${hasLatencyHighlight ? '700' : '500'};">
                                    ${d.avgDelay} ms
                                </td>
                                <td class="text-end" style="color:${alertColor};font-weight:${hasAlertsHighlight ? '700' : '500'};">
                                    ${d.alerts}
                                </td>
                                <td class="text-end">${d.responded}%</td>
                                <td>
                                    <span class="badge bg-${statusBadge(d.status)}">${statusText(d.status)}</span>
                                </td>
                            </tr>`;
                        }).join('')}
                    </tbody>
                </table>
            </div>
            <div class="text-muted small mt-3">
                <i class="fas fa-info-circle me-1"></i> Klik baris device untuk melihat detail monitoring.
            </div>
        `;
    }

    /* ============================================================
       KPI DETAIL
    ============================================================ */
    window.showKpiDetail = function (filter) {
        let title, subtitle, devices;
        if (filter === 'up') { title = 'Daftar Device UP'; subtitle = 'Device yang sedang aktif & merespon'; devices = allDevices.filter(d => d.status === 'up'); }
        else if (filter === 'down') { title = 'Daftar Device DOWN'; subtitle = 'Device yang tidak merespon'; devices = allDevices.filter(d => d.status === 'down'); }
        else if (filter === 'warning') { title = 'Daftar Device WARNING'; subtitle = 'Device dengan latency tinggi'; devices = allDevices.filter(d => d.status === 'warning'); }
        else { title = 'Semua Device'; subtitle = 'Seluruh device yang dimonitor'; devices = allDevices; }

        document.getElementById('kpiDetailTitle').textContent = title;
        document.getElementById('kpiDetailSubtitle').textContent = subtitle + ` · Total: ${devices.length}`;
        document.getElementById('kpiDetailBody').innerHTML = renderDeviceTable(devices);
        new bootstrap.Modal(document.getElementById('kpiDetailModal')).show();
    };

    /* ============================================================
       SUMMARY CARD CLICK (TOTAL / LATENCY / ALERTS)
    ============================================================ */
    window.showSummary = function (type) {
        let title, subtitle, devices, sortBy = null;

        if (type === 'total') {
            title = 'Total Monitored Device';
            subtitle = 'Seluruh device yang terdaftar di sistem';
            devices = allDevices;
        } else if (type === 'latency') {
            title = 'Average Latency — Ranking Device';
            subtitle = 'Device diurutkan dari latency tertinggi';
            devices = allDevices.filter(d => Number(d.avgDelay) > 0);
            sortBy = 'latency';
        } else if (type === 'alerts') {
            title = 'Total Alerts — Ranking Device';
            subtitle = 'Device diurutkan dari jumlah alert terbanyak';
            devices = allDevices.filter(d => Number(d.alerts) > 0);
            sortBy = 'alerts';
        }

        document.getElementById('kpiDetailTitle').textContent = title;
        document.getElementById('kpiDetailSubtitle').textContent = subtitle + ` · Total: ${devices.length}`;
        document.getElementById('kpiDetailBody').innerHTML = renderDeviceTable(devices, { sortBy });
        new bootstrap.Modal(document.getElementById('kpiDetailModal')).show();
    };

    /* ============================================================
       DEVICE DETAIL
    ============================================================ */
    window.showDetail = function (deviceId) {
        const device = deviceMap[deviceId];
        if (!device) return;

        const badge = statusBadge(device.status);
        const kpiModal = bootstrap.Modal.getInstance(document.getElementById('kpiDetailModal'));
        if (kpiModal) kpiModal.hide();

        document.getElementById('detailModalTitle').textContent = `Device Detail: ${device.name}`;
        document.getElementById('detailModalSubtitle').textContent = `${device.ip} · ${device.name}`;

        document.getElementById('detailModalBody').innerHTML = `
            <div class="row g-3 mb-3">
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);"><div class="fw-bold text-${badge}" style="font-size:1.3rem;">${statusText(device.status)}</div><div class="small text-muted mt-1">Current Status</div></div></div>
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);"><div class="fw-bold" style="font-size:1.3rem;">${device.responded}%</div><div class="small text-muted mt-1">Responded</div></div></div>
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);"><div class="fw-bold" style="font-size:1.3rem;">${device.missed}%</div><div class="small text-muted mt-1">Missed</div></div></div>
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);"><div class="fw-bold" style="font-size:1.3rem;">${device.alerts}</div><div class="small text-muted mt-1">Alerts</div></div></div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);"><div class="fw-bold" style="font-size:1.1rem;">${device.polls.toLocaleString()}</div><div class="small text-muted mt-1">Total Polls</div></div></div>
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);"><div class="fw-bold" style="font-size:1.1rem;">${device.avgDelay} ms</div><div class="small text-muted mt-1">Avg Delay</div></div></div>
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);"><div class="fw-bold" style="font-size:1.1rem;">${device.minDelay} ms</div><div class="small text-muted mt-1">Min Delay</div></div></div>
                <div class="col-md-3"><div class="text-center p-3 rounded-3" style="background:linear-gradient(135deg,#fef2f2,#fee2e2);"><div class="fw-bold" style="font-size:1.1rem;">${device.maxDelay.toLocaleString()} ms</div><div class="small text-muted mt-1">Max Delay</div></div></div>
            </div>
            <div class="rounded-3 p-3" style="background:#f8fafc;border:1px solid #eef2f7;">
                <div class="row g-2">
                    <div class="col-md-6"><strong class="small text-muted">Name:</strong> <span class="ms-2">${device.name}</span></div>
                    <div class="col-md-6"><strong class="small text-muted">IP:</strong> <span class="ms-2" style="font-family:monospace;">${device.ip}</span></div>
                    <div class="col-md-6"><strong class="small text-muted">Type:</strong> <span class="ms-2">${device.type}</span></div>
                    <div class="col-md-6"><strong class="small text-muted">Down Time:</strong> <span class="ms-2">${device.downTime}</span></div>
                </div>
            </div>
            <h6 class="mb-2 mt-4 fw-bold"><i class="fas fa-terminal text-primary me-2"></i>Recent Log Extract</h6>
            <pre style="background:#0f172a;color:#cbd5e1;padding:14px;border-radius:10px;font-size:11.5px;line-height:1.6;margin:0;">${
                device.status === 'up' ? `UP ${device.name} (${device.ip}) avg=${device.avgDelay}ms` :
                device.status === 'down' ? `DOWN ${device.name} (${device.ip}) - No response` :
                `WARNING ${device.name} (${device.ip}) - High latency`
            }</pre>
        `;
        new bootstrap.Modal(document.getElementById('routerDetailModal')).show();
    };

    /* ============================================================
       CLOCK
    ============================================================ */
    function updateClock() {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        document.getElementById('dashboardClock').textContent =
            `${now.getFullYear()}/${pad(now.getMonth()+1)}/${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    /* ============================================================
       REFRESH
    ============================================================ */
    window.refreshDashboard = function () {
        document.getElementById('refreshIcon').classList.add('fa-spin');
        setTimeout(() => window.location.reload(), 500);
    };

    /* ============================================================
       AUTO-REFRESH
    ============================================================ */
    setInterval(function () {
        fetch("{{ route('admin.dashboard.data') }}", {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        .then(r => r.json())
        .then(payload => {
            if (!payload || !payload.latencyHistory) return;
            latencyHistory = payload.latencyHistory;
            const period = document.getElementById('latencyPeriod').value;
            const data = latencyHistory[period];
            if (data) {
                latencyChart.data.labels = data.labels;
                latencyChart.data.datasets[0].data = data.values;
                latencyChart.update('none');
                updateLatencySummary(data.values);
                syncLatencyState(data.values);
            }
            const summary = payload.summary;
            if (summary) {
                const setText = (id, t) => { const el = document.getElementById(id); if (el) el.textContent = t; };
                setText('kpiUpNumber', summary.up);
                setText('kpiDownNumber', summary.down);
                setText('kpiWarningNumber', summary.warning);
                setText('kpiAvailabilityNumber', `${summary.availability.toFixed(1)}%`);
                setText('totalDeviceSummary', `${summary.total} devices`);
                setText('averageLatencySummary', `${summary.averageLatency} ms`);
                setText('lastPollingSummary', `${summary.totalAlerts} alerts`);
            }
        })
        .catch(() => {});
    }, 30000);
});
</script>
@endpush