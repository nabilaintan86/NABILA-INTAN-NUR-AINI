@extends('layouts.admin')

@section('title', 'Dashboard · RADNET NOC')

@push('styles')
<style>
    /* ============================================================
       GLOBAL DASHBOARD
    ============================================================ */
    .noc-dashboard {
        --noc-primary: #3b82f6;
        --noc-success: #22c55e;
        --noc-danger: #ef4444;
        --noc-warning: #eab308;
        --noc-muted: #64748b;
        --noc-border: #e2e8f0;
    }

    .noc-dashboard .card {
        border: 1px solid #edf0f3;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .noc-dashboard .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        padding: 15px 20px;
    }

    /* ============================================================
       HEADER
    ============================================================ */
    .noc-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 600;
    }

    .noc-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, .12);
    }

    /* ============================================================
       KPI CARDS
    ============================================================ */
    .kpi-card {
        position: relative;
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        border: 1px solid #edf0f3;
        border-left: 4px solid #ccc;
        transition: all .2s ease;
        cursor: pointer;
        height: 100%;
        overflow: hidden;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 18px rgba(15, 23, 42, .08);
    }

    .kpi-card .kpi-number {
        font-size: 29px;
        font-weight: 700;
        line-height: 1.2;
    }

    .kpi-card .kpi-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
    }

    .kpi-card .kpi-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
    }

    .kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .kpi-card.up {
        border-left-color: #22c55e;
    }

    .kpi-card.down {
        border-left-color: #ef4444;
    }

    .kpi-card.warning {
        border-left-color: #eab308;
    }

    .kpi-card.availability {
        border-left-color: #3b82f6;
    }

    .kpi-card.up .kpi-icon {
        background: #ecfdf5;
        color: #16a34a;
    }

    .kpi-card.down .kpi-icon {
        background: #fef2f2;
        color: #dc2626;
    }

    .kpi-card.warning .kpi-icon {
        background: #fffbeb;
        color: #ca8a04;
    }

    .kpi-card.availability .kpi-icon {
        background: #eff6ff;
        color: #2563eb;
    }

    /* ============================================================
       SECTION TITLE
    ============================================================ */
    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }

    .section-subtitle {
        font-size: 11px;
        color: #94a3b8;
    }

    /* ============================================================
       LATENCY
    ============================================================ */
    .latency-card .card-body {
        padding: 20px 24px;
    }

    .latency-chart-wrapper {
        height: 330px;
        position: relative;
    }

    .latency-summary {
        display: flex;
        gap: 22px;
        flex-wrap: wrap;
    }

    .latency-summary-item {
        display: flex;
        flex-direction: column;
    }

    .latency-summary-item .label {
        font-size: 11px;
        color: #94a3b8;
    }

    .latency-summary-item .value {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
    }

    /* ============================================================
       NETWORK HEALTH
    ============================================================ */
    .health-item {
        padding: 13px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .health-item:last-child {
        border-bottom: none;
    }

    .health-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .health-name {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #334155;
        font-weight: 600;
    }

    .health-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .health-value {
        font-size: 13px;
        font-weight: 700;
    }

    .health-bar {
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
    }

    .health-bar span {
        display: block;
        height: 100%;
        border-radius: 10px;
        transition: width .4s ease;
    }

    /* ============================================================
       ALERTS
    ============================================================ */
    .alert-item {
        padding: 10px 12px;
        border-radius: 8px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 1px solid #f1f5f9;
        border-left: 3px solid;
        transition: .2s ease;
        cursor: pointer;
    }

    .alert-item:hover {
        background: #f8fafc;
    }

    .alert-item.critical {
        border-left-color: #ef4444;
        background: #fffafa;
    }

    .alert-item.warning {
        border-left-color: #eab308;
        background: #fffdf5;
    }

    .alert-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-item.critical .alert-icon {
        background: #fee2e2;
        color: #dc2626;
    }

    .alert-item.warning .alert-icon {
        background: #fef3c7;
        color: #ca8a04;
    }

    .alert-name {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
    }

    .alert-ip {
        font-size: 10px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .alert-time {
        margin-left: auto;
        color: #94a3b8;
        font-size: 10px;
        white-space: nowrap;
    }

    /* ============================================================
       ACTIVITY FEED
    ============================================================ */
    .activity-feed {
        padding: 0;
    }

    .feed-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background .2s ease;
    }

    .feed-item:hover {
        background: #f8fafc;
    }

    .feed-item:last-child {
        border-bottom: none;
    }

    .feed-time {
        min-width: 55px;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        padding-top: 2px;
    }

    .feed-marker {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        margin-top: 4px;
        flex-shrink: 0;
    }

    .feed-body {
        flex: 1;
    }

    .feed-title {
        font-weight: 600;
        font-size: 12px;
        color: #0f172a;
    }

    .feed-desc {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
    }

    /* ============================================================
       MONITORING SUMMARY
    ============================================================ */
    .monitor-summary {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .monitor-summary .summary-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
    }

    .monitor-summary .summary-label {
        font-size: 10px;
        color: #94a3b8;
    }

    .monitor-summary .summary-value {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    /* ============================================================
       DEVICE DETAIL
    ============================================================ */
    .site-summary {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 9px;
        padding: 13px;
    }

    .site-summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #e2e8f0;
        padding: 8px 0;
        font-size: 12px;
    }

    .site-summary-item:last-child {
        border-bottom: none;
    }

    .site-summary-item > span:first-child {
        color: #64748b;
    }

    .site-summary-item strong {
        color: #0f172a;
        text-align: right;
    }

    .detail-stat {
        text-align: center;
        padding: 13px 8px;
        background: #f8fafc;
        border-radius: 8px;
    }

    .detail-stat .value {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .detail-stat .label {
        font-size: 10px;
        color: #94a3b8;
        margin-top: 2px;
    }

    .device-log {
        background: #0f172a;
        color: #cbd5e1;
        padding: 13px;
        border-radius: 8px;
        font-size: 11px;
        line-height: 1.6;
        margin: 0;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state {
        text-align: center;
        padding: 35px 15px;
        color: #94a3b8;
        font-size: 12px;
    }

    .empty-state i {
        font-size: 25px;
        margin-bottom: 8px;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767px) {
        .latency-chart-wrapper {
            height: 260px;
        }

        .latency-summary {
            gap: 14px;
        }

        .feed-time {
            min-width: 45px;
        }
    }
</style>
@endpush


@section('content')

<div class="nk-content-body noc-dashboard">

    {{-- ============================================================
         HEADER
    ============================================================ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="mb-0">
                    <i class="fas fa-satellite-dish text-primary me-2"></i>
                    RadneXt Wireless Network Monitoring
                </h4>

                <span class="noc-status text-success">
                    <span class="noc-status-dot"></span>
                    Monitoring Active
                </span>
            </div>

            <small class="text-muted">
                Network Operations Center · Real-time status &amp; performance
            </small>
        </div>

        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">

            <span class="badge bg-light text-dark border px-3 py-2">
                <i class="far fa-clock me-1"></i>
                <span id="dashboardClock">
                    {{ now()->format('Y/m/d H:i:s') }}
                </span>
            </span>

            <button
                class="btn btn-outline-secondary btn-sm"
                onclick="refreshDashboard()"
                title="Refresh Dashboard">
                <i class="fas fa-sync-alt" id="refreshIcon"></i>
            </button>

        </div>
    </div>


    {{-- ============================================================
         KPI CARDS
    ============================================================ --}}
    <div class="row g-3 mb-3" id="kpiContainer"></div>


    {{-- ============================================================
         MONITORING SUMMARY
    ============================================================ --}}
    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body py-3">
                    <div class="monitor-summary">
                        <div class="summary-icon">
                            <i class="fas fa-server"></i>
                        </div>

                        <div>
                            <div class="summary-label">TOTAL MONITORED DEVICE</div>
                            <div class="summary-value" id="totalDeviceSummary">
                                -
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body py-3">
                    <div class="monitor-summary">
                        <div class="summary-icon">
                            <i class="fas fa-stopwatch"></i>
                        </div>

                        <div>
                            <div class="summary-label">AVERAGE LATENCY</div>
                            <div class="summary-value" id="averageLatencySummary">
                                -
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body py-3">
                    <div class="monitor-summary">
                        <div class="summary-icon">
                            <i class="fas fa-clock"></i>
                        </div>

                        <div>
                            <div class="summary-label">LAST POLLING</div>
                            <div class="summary-value" id="lastPollingSummary">
                                -
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- ============================================================
         LATENCY MONITORING
    ============================================================ --}}
    <div class="row g-3 mb-3">

        <div class="col-12">

            <div class="card latency-card">

                <div class="card-header">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                        <div>
                            <div class="section-title">
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                Network Latency Monitoring
                            </div>

                            <div class="section-subtitle">
                                Average ICMP latency across monitored network links
                            </div>
                        </div>

                        <select
                            id="latencyPeriod"
                            class="form-select form-select-sm"
                            style="width:auto;">

                            <option value="6">Last 6 Hours</option>
                            <option value="12">Last 12 Hours</option>
                            <option value="24" selected>Last 24 Hours</option>
                            <option value="168">Last 7 Days</option>

                        </select>

                    </div>

                </div>


                <div class="card-body">

                    <div class="latency-summary mb-3">

                        <div class="latency-summary-item">
                            <span class="label">Current</span>
                            <span class="value" id="latencyCurrent">-</span>
                        </div>

                        <div class="latency-summary-item">
                            <span class="label">Average</span>
                            <span class="value" id="latencyAverage">-</span>
                        </div>

                        <div class="latency-summary-item">
                            <span class="label">Peak</span>
                            <span class="value" id="latencyPeak">-</span>
                        </div>

                    </div>


                    <div class="latency-chart-wrapper">
                        <canvas id="latencyChart"></canvas>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         NETWORK HEALTH + RECENT ALERTS
    ============================================================ --}}
    <div class="row g-3 mb-3">

        {{-- NETWORK HEALTH --}}
        <div class="col-lg-6">

            <div class="card h-100">

                <div class="card-header">

                    <div class="section-title">
                        <i class="fas fa-heartbeat text-success me-2"></i>
                        Network Health
                    </div>

                    <div class="section-subtitle">
                        Current health by network segment
                    </div>

                </div>

                <div class="card-body" id="healthContainer"></div>

            </div>

        </div>


        {{-- RECENT ALERTS --}}
        <div class="col-lg-6">

            <div class="card h-100">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="section-title">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                Recent Alerts
                            </div>

                            <div class="section-subtitle">
                                Device requiring attention
                            </div>
                        </div>

                        <a
                            href="{{ route('admin.notifikasi') }}"
                            class="text-primary small">
                            Lihat semua →
                        </a>

                    </div>

                </div>

                <div class="card-body" id="alertContainer"></div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         RECENT ACTIVITY
    ============================================================ --}}
    <div class="row g-3 mb-3">

        <div class="col-12">

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="section-title">
                                <i class="fas fa-history text-primary me-2"></i>
                                Recent Activity
                            </div>

                            <div class="section-subtitle">
                                Latest device status activity
                            </div>
                        </div>

                        <a
                            href="{{ route('admin.log') }}"
                            class="text-primary small">
                            Lihat semua →
                        </a>

                    </div>

                </div>

                <div class="card-body p-0">

                    <div
                        class="activity-feed"
                        id="activityContainer">
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         KPI DETAIL MODAL
    ============================================================ --}}
    <div
        class="modal fade"
        id="kpiDetailModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5
                        class="modal-title"
                        id="kpiDetailTitle">
                        Daftar Device
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div
                    class="modal-body"
                    id="kpiDetailBody">
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         DEVICE DETAIL MODAL
    ============================================================ --}}
    <div
        class="modal fade"
        id="routerDetailModal"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <div>
                        <h5
                            class="modal-title mb-1"
                            id="detailModalTitle">
                            Device Detail
                        </h5>

                        <small
                            class="text-muted"
                            id="detailModalSubtitle">
                        </small>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div
                    class="modal-body"
                    id="detailModalBody">
                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Tutup
                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         FOOTER
    ============================================================ --}}
    <div class="mt-4 text-center text-muted small border-top pt-3">

        Copyright PT Radnet Digital Indonesia
        &copy; {{ date('Y') }}.
        All Right Reserved.

    </div>

</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       1. NETWORK DATA
       ============================================================ */

    const network = {

        sites: [

            {
                id: 'plaza-bri',
                name: 'Plaza BRI',

                devices: [

                    {
                        id: 'pop-gresik',
                        name: 'POP Gresik',
                        hostname: 'pop-gresik',
                        type: 'router',
                        ip: '202.154.4.254',
                        status: 'up',
                        uptime: '99.8%',
                        lastPoll: '2026-09-09 15:47:00',
                        avgDelay: 12,
                        minDelay: 2,
                        maxDelay: 45,
                        missed: 0,
                        packetLoss: 0,
                        info1: '202.154.62.144/29',
                        info2: 'Link via FIC',
                        log: '20260909 154700 UP POP Gresik'
                    },

                    {
                        id: 'intiland-1',
                        name: 'Intiland 1',
                        hostname: 'intiland-1',
                        type: 'router',
                        ip: '202.154.4.4',
                        status: 'up',
                        uptime: '99.99%',
                        lastPoll: '2026-09-09 15:46:30',
                        avgDelay: 9,
                        minDelay: 1,
                        maxDelay: 30,
                        missed: 0,
                        packetLoss: 0,
                        info1: '-',
                        info2: '-',
                        log: '20260909 154630 UP Intiland 1'
                    },

                    {
                        id: 'pjb',
                        name: 'PJB',
                        hostname: 'pjb',
                        type: 'router',
                        ip: '202.154.4.134',
                        status: 'down',
                        uptime: '0%',
                        lastPoll: '2026-09-09 14:50:00',
                        avgDelay: 0,
                        minDelay: 0,
                        maxDelay: 0,
                        missed: 200,
                        packetLoss: 100,
                        info1: '-',
                        info2: '-',
                        log: '20260909 145000 DOWN PJB'
                    },

                    {
                        id: 'border',
                        name: 'Border',
                        hostname: 'border',
                        type: 'router',
                        ip: '202.154.4.3',
                        status: 'up',
                        uptime: '99.2%',
                        lastPoll: '2026-09-09 15:47:10',
                        avgDelay: 8,
                        minDelay: 1,
                        maxDelay: 22,
                        missed: 2,
                        packetLoss: 1,
                        info1: '-',
                        info2: '-',
                        log: '20260909 154710 UP Border'
                    },

                    {
                        id: 'smkn-1',
                        name: 'SMKN-1',
                        hostname: 'smkn-1',
                        type: 'router',
                        ip: '202.154.4.241',
                        status: 'up',
                        uptime: '98.7%',
                        lastPoll: '2026-09-09 15:46:40',
                        avgDelay: 11,
                        minDelay: 2,
                        maxDelay: 40,
                        missed: 1,
                        packetLoss: 0.5,
                        info1: '-',
                        info2: '-',
                        log: '20260909 154640 UP SMKN-1'
                    },

                    {
                        id: 'sby-jkt',
                        name: 'SBY-JKT',
                        hostname: 'sby-jkt',
                        type: 'router',
                        ip: '202.154.0.29',
                        status: 'warning',
                        uptime: '72%',
                        lastPoll: '2026-09-09 15:30:00',
                        avgDelay: 120,
                        minDelay: 10,
                        maxDelay: 550,
                        missed: 30,
                        packetLoss: 15,
                        info1: '-',
                        info2: '-',
                        log: '20260909 153000 WARNING SBY-JKT'
                    },

                    {
                        id: 'iix-ji',
                        name: 'IIX-JI',
                        hostname: 'iix-ji',
                        type: 'router',
                        ip: '218.100.32.1',
                        status: 'up',
                        uptime: '99.5%',
                        lastPoll: '2026-09-09 15:47:15',
                        avgDelay: 5,
                        minDelay: 1,
                        maxDelay: 18,
                        missed: 0,
                        packetLoss: 0,
                        info1: '-',
                        info2: '-',
                        log: '20260909 154715 UP IIX-JI'
                    },

                    {
                        id: '56-17',
                        name: '56.17',
                        hostname: '56.17',
                        type: 'router',
                        ip: '202.154.56.17',
                        status: 'up',
                        uptime: '99.9%',
                        lastPoll: '2026-09-09 15:47:20',
                        avgDelay: 7,
                        minDelay: 1,
                        maxDelay: 25,
                        missed: 0,
                        packetLoss: 0,
                        info1: '-',
                        info2: '-',
                        log: '20260909 154720 UP 56.17'
                    }

                ]
            }

        ]

    };


    /* ============================================================
       2. FLATTEN DEVICE DATA
    ============================================================ */

    const allDevices = [];
    const deviceMap = {};
    const siteMap = {};

    network.sites.forEach(site => {

        siteMap[site.id] = site;

        site.devices.forEach(device => {

            device.siteId = site.id;

            allDevices.push(device);

            deviceMap[device.id] = device;

        });

    });


    /* ============================================================
       3. HELPERS
    ============================================================ */

    function statusBadge(status) {

        const map = {
            up: 'success',
            down: 'danger',
            warning: 'warning'
        };

        return map[status] || 'secondary';

    }


    function statusColor(status) {

        const map = {
            up: '#22c55e',
            down: '#ef4444',
            warning: '#eab308'
        };

        return map[status] || '#94a3b8';

    }


    function statusText(status) {

        const map = {
            up: 'UP',
            down: 'DOWN',
            warning: 'WARNING'
        };

        return map[status] || status.toUpperCase();

    }


    function formatTime(datetime) {

        if (!datetime) {
            return '-';
        }

        return datetime.split(' ')[1] || datetime;

    }


    /* ============================================================
       4. KPI CALCULATION
    ============================================================ */

    const total = allDevices.length;

    const upCount =
        allDevices.filter(d => d.status === 'up').length;

    const downCount =
        allDevices.filter(d => d.status === 'down').length;

    const warningCount =
        allDevices.filter(d => d.status === 'warning').length;


    let uptimeSum = 0;

    allDevices.forEach(device => {

        const uptime = parseFloat(device.uptime);

        if (!isNaN(uptime)) {
            uptimeSum += uptime;
        }

    });


    const availability =
        total > 0
            ? uptimeSum / total
            : 0;


    /* ============================================================
       5. KPI RENDER
    ============================================================ */

    document.getElementById('kpiContainer').innerHTML = `

        <div class="col-sm-6 col-xl-3">

            <div
                class="kpi-card up"
                onclick="showKpiDetail('up')">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="kpi-number text-success">
                            ${upCount}
                        </div>

                        <div class="kpi-label">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Devices UP
                        </div>

                        <div class="kpi-sub">
                            ${total ? Math.round((upCount / total) * 100) : 0}% of monitored devices
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="fas fa-check"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div
                class="kpi-card down"
                onclick="showKpiDetail('down')">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="kpi-number text-danger">
                            ${downCount}
                        </div>

                        <div class="kpi-label">
                            <i class="fas fa-times-circle text-danger me-1"></i>
                            Devices DOWN
                        </div>

                        <div class="kpi-sub">
                            ${downCount > 0
                                ? 'Perlu tindakan segera'
                                : 'Semua device normal'}
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="fas fa-power-off"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div
                class="kpi-card warning"
                onclick="showKpiDetail('warning')">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="kpi-number text-warning">
                            ${warningCount}
                        </div>

                        <div class="kpi-label">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            Warning
                        </div>

                        <div class="kpi-sub">
                            ${warningCount > 0
                                ? 'Latency / packet loss tinggi'
                                : 'Tidak ada warning'}
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="fas fa-exclamation"></i>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-sm-6 col-xl-3">

            <div
                class="kpi-card availability"
                onclick="showKpiDetail('all')">

                <div class="d-flex justify-content-between">

                    <div>

                        <div class="kpi-number text-primary">
                            ${availability.toFixed(1)}%
                        </div>

                        <div class="kpi-label">
                            <i class="fas fa-signal text-primary me-1"></i>
                            Availability
                        </div>

                        <div class="kpi-sub">
                            Monitoring period: 24 hours
                        </div>

                    </div>

                    <div class="kpi-icon">
                        <i class="fas fa-wifi"></i>
                    </div>

                </div>

            </div>

        </div>

    `;


    /* ============================================================
       6. MONITORING SUMMARY
    ============================================================ */

    const averageLatency =
        total > 0
            ? allDevices.reduce(
                (sum, device) => sum + Number(device.avgDelay || 0),
                0
            ) / total
            : 0;


    const latestDevice =
        [...allDevices].sort(
            (a, b) =>
                new Date(b.lastPoll) - new Date(a.lastPoll)
        )[0];


    document.getElementById('totalDeviceSummary').textContent =
        `${total} devices`;


    document.getElementById('averageLatencySummary').textContent =
        `${averageLatency.toFixed(1)} ms`;


    document.getElementById('lastPollingSummary').textContent =
        latestDevice
            ? formatTime(latestDevice.lastPoll)
            : '-';


    /* ============================================================
       7. NETWORK HEALTH
    ============================================================ */

    const health = [

        {
            name: 'Core Network',
            value: 99
        },

        {
            name: 'Distribution',
            value: 96
        },

        {
            name: 'Access Network',
            value: 98
        }

    ];


    function healthColor(value) {

        if (value >= 98) {
            return '#22c55e';
        }

        if (value >= 90) {
            return '#eab308';
        }

        return '#ef4444';

    }


    document.getElementById('healthContainer').innerHTML =
        health.map(item => {

            const color = healthColor(item.value);

            return `

                <div class="health-item">

                    <div class="health-top">

                        <span class="health-name">

                            <span
                                class="health-dot"
                                style="background:${color}">
                            </span>

                            ${item.name}

                        </span>

                        <span
                            class="health-value"
                            style="color:${color}">
                            ${item.value}%
                        </span>

                    </div>

                    <div class="health-bar">

                        <span
                            style="
                                width:${item.value}%;
                                background:${color};
                            ">
                        </span>

                    </div>

                </div>

            `;

        }).join('');


    /* ============================================================
       8. LATENCY HISTORY
    ============================================================ */

    const latencyHistory = {

        '6': {
            labels: [
                '10:00',
                '11:00',
                '12:00',
                '13:00',
                '14:00',
                '15:00'
            ],
            values: [
                18,
                21,
                19,
                25,
                22,
                23
            ]
        },

        '12': {
            labels: [
                '04:00',
                '05:00',
                '06:00',
                '07:00',
                '08:00',
                '09:00',
                '10:00',
                '11:00',
                '12:00',
                '13:00',
                '14:00',
                '15:00'
            ],
            values: [
                17,
                18,
                21,
                20,
                19,
                24,
                18,
                21,
                19,
                25,
                22,
                23
            ]
        },

        '24': {
            labels: [
                '16:00',
                '18:00',
                '20:00',
                '22:00',
                '00:00',
                '02:00',
                '04:00',
                '06:00',
                '08:00',
                '10:00',
                '12:00',
                '14:00'
            ],
            values: [
                20,
                21,
                18,
                23,
                19,
                17,
                18,
                21,
                19,
                21,
                19,
                23
            ]
        },

        '168': {
            labels: [
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
                'Sun'
            ],
            values: [
                19,
                21,
                20,
                24,
                22,
                18,
                23
            ]
        }

    };


    /* ============================================================
       9. LATENCY CHART
    ============================================================ */

    const initialLatency =
        latencyHistory['24'].values;


    const latencyChart =
        new Chart(
            document.getElementById('latencyChart'),
            {

                type: 'line',

                data: {

                    labels:
                        latencyHistory['24'].labels,

                    datasets: [

                        {

                            label:
                                'Average Latency',

                            data:
                                initialLatency,

                            borderColor:
                                '#3b82f6',

                            backgroundColor:
                                'rgba(59,130,246,.08)',

                            borderWidth:
                                2,

                            tension:
                                .35,

                            fill:
                                true,

                            pointRadius:
                                3,

                            pointHoverRadius:
                                5,

                            pointBackgroundColor:
                                '#3b82f6'

                        }

                    ]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    interaction: {

                        intersect: false,

                        mode: 'index'

                    },

                    plugins: {

                        legend: {
                            display: false
                        },

                        tooltip: {

                            callbacks: {

                                label: function (context) {

                                    return `${context.parsed.y} ms`;

                                }

                            }

                        }

                    },

                    scales: {

                        y: {

                            beginAtZero: true,

                            grid: {
                                color: '#f1f5f9'
                            },

                            title: {

                                display: true,

                                text:
                                    'Latency (ms)'

                            }

                        },

                        x: {

                            grid: {
                                display: false
                            },

                            title: {

                                display: true,

                                text:
                                    'Time'

                            }

                        }

                    }

                }

            }
        );


    function updateLatencySummary(values) {

        if (!values || !values.length) {
            return;
        }

        const current =
            values[values.length - 1];

        const average =
            values.reduce(
                (sum, value) => sum + value,
                0
            ) / values.length;

        const peak =
            Math.max(...values);


        document.getElementById('latencyCurrent').textContent =
            `${current} ms`;

        document.getElementById('latencyAverage').textContent =
            `${average.toFixed(1)} ms`;

        document.getElementById('latencyPeak').textContent =
            `${peak} ms`;

    }


    updateLatencySummary(initialLatency);


    document
        .getElementById('latencyPeriod')
        .addEventListener(
            'change',
            function () {

                const data =
                    latencyHistory[this.value];

                if (!data) {
                    return;
                }

                latencyChart.data.labels =
                    data.labels;

                latencyChart.data.datasets[0].data =
                    data.values;

                latencyChart.update();

                updateLatencySummary(
                    data.values
                );

            }
        );


    /* ============================================================
       10. RECENT ALERTS
    ============================================================ */

    const alerts =
        allDevices
            .filter(
                device =>
                    device.status === 'down' ||
                    device.status === 'warning'
            )
            .sort(
                (a, b) =>
                    new Date(b.lastPoll) -
                    new Date(a.lastPoll)
            )
            .slice(0, 5);


    document.getElementById('alertContainer').innerHTML =

        alerts.length === 0

            ? `

                <div class="empty-state">

                    <i class="fas fa-check-circle text-success d-block"></i>

                    Tidak ada alert aktif

                </div>

            `

            :

            alerts.map(device => `

                <div
                    class="alert-item ${device.status === 'down'
                        ? 'critical'
                        : 'warning'}"
                    onclick="showDetail('${device.id}')">

                    <div class="alert-icon">

                        <i class="fas ${
                            device.status === 'down'
                                ? 'fa-times'
                                : 'fa-exclamation'
                        }"></i>

                    </div>

                    <div>

                        <div class="alert-name">
                            ${device.name}
                        </div>

                        <div class="alert-ip">
                            ${device.ip}
                            · ${device.avgDelay} ms
                        </div>

                    </div>

                    <div class="alert-time">
                        ${formatTime(device.lastPoll)}
                    </div>

                </div>

            `).join('');


    /* ============================================================
       11. RECENT ACTIVITY
    ============================================================ */

    const activities =
        [...allDevices]
            .filter(
                device =>
                    device.log &&
                    device.log.trim() !== ''
            )
            .sort(
                (a, b) =>
                    new Date(b.lastPoll) -
                    new Date(a.lastPoll)
            )
            .slice(0, 6);


    document.getElementById('activityContainer').innerHTML =

        activities.length === 0

            ? `

                <div class="empty-state">

                    <i class="fas fa-history d-block"></i>

                    Belum ada aktivitas tercatat

                </div>

            `

            :

            activities.map(device => {

                return `

                    <div
                        class="feed-item"
                        onclick="showDetail('${device.id}')">

                        <div class="feed-time">
                            ${formatTime(device.lastPoll)}
                        </div>

                        <span
                            class="feed-marker"
                            style="
                                background:${statusColor(device.status)}
                            ">
                        </span>

                        <div class="feed-body">

                            <div class="feed-title">
                                ${device.name}
                            </div>

                            <div class="feed-desc">

                                Status monitoring →

                                <span
                                    class="badge bg-${statusBadge(device.status)}">
                                    ${statusText(device.status)}
                                </span>

                            </div>

                        </div>

                    </div>

                `;

            }).join('');


    /* ============================================================
       12. KPI DETAIL
    ============================================================ */

    window.showKpiDetail = function (filter) {

        let title;
        let devices;


        if (filter === 'up') {

            title = 'Daftar Device UP';

            devices =
                allDevices.filter(
                    d => d.status === 'up'
                );

        }

        else if (filter === 'down') {

            title = 'Daftar Device DOWN';

            devices =
                allDevices.filter(
                    d => d.status === 'down'
                );

        }

        else if (filter === 'warning') {

            title = 'Daftar Device WARNING';

            devices =
                allDevices.filter(
                    d => d.status === 'warning'
                );

        }

        else {

            title = 'Semua Device';

            devices = allDevices;

        }


        document.getElementById('kpiDetailTitle')
            .textContent = title;


        document.getElementById('kpiDetailBody')
            .innerHTML = `

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>Device</th>

                                <th>Type</th>

                                <th>IP Address</th>

                                <th>Site</th>

                                <th>Latency</th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            ${
                                devices.length === 0

                                ?

                                `

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center text-muted py-4">

                                        Tidak ada device

                                    </td>

                                </tr>

                                `

                                :

                                devices.map(device => `

                                    <tr
                                        style="cursor:pointer"
                                        onclick="
                                            showDetail('${device.id}')
                                        ">

                                        <td>

                                            <strong>
                                                ${device.name}
                                            </strong>

                                        </td>

                                        <td>
                                            ${device.type}
                                        </td>

                                        <td>
                                            ${device.ip}
                                        </td>

                                        <td>
                                            ${
                                                siteMap[device.siteId]?.name
                                                || '-'
                                            }
                                        </td>

                                        <td>
                                            ${device.avgDelay} ms
                                        </td>

                                        <td>

                                            <span
                                                class="badge bg-${statusBadge(device.status)}">

                                                ${statusText(device.status)}

                                            </span>

                                        </td>

                                    </tr>

                                `).join('')
                            }

                        </tbody>

                    </table>

                </div>

                <div class="text-muted small mt-2">

                    <i class="fas fa-info-circle me-1"></i>

                    Klik baris device untuk melihat detail monitoring.

                </div>

            `;


        new bootstrap.Modal(
            document.getElementById('kpiDetailModal')
        ).show();

    };


    /* ============================================================
       13. DEVICE DETAIL
    ============================================================ */

    window.showDetail = function (deviceId) {

        const device =
            deviceMap[deviceId];

        if (!device) {
            return;
        }


        const site =
            siteMap[device.siteId];


        const badge =
            statusBadge(device.status);


        /* Tutup KPI modal */

        const kpiModalElement =
            document.getElementById('kpiDetailModal');

        const kpiModal =
            bootstrap.Modal.getInstance(
                kpiModalElement
            );

        if (kpiModal) {
            kpiModal.hide();
        }


        document.getElementById('detailModalTitle')
            .textContent =
                `Device Detail: ${device.name}`;


        document.getElementById('detailModalSubtitle')
            .textContent =
                `${device.ip} · ${device.hostname || device.name}`;


        document.getElementById('detailModalBody')
            .innerHTML = `

                <div class="row g-3 mb-3">

                    <div class="col-md-3">

                        <div class="detail-stat">

                            <div
                                class="value text-${badge}">
                                ${statusText(device.status)}
                            </div>

                            <div class="label">
                                Current Status
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="detail-stat">

                            <div class="value">
                                ${device.avgDelay} ms
                            </div>

                            <div class="label">
                                Average Latency
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="detail-stat">

                            <div class="value">
                                ${device.packetLoss}%
                            </div>

                            <div class="label">
                                Packet Loss
                            </div>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="detail-stat">

                            <div class="value">
                                ${device.uptime}
                            </div>

                            <div class="label">
                                Uptime
                            </div>

                        </div>

                    </div>

                </div>


                <div class="row g-3">

                    <div class="col-md-6">

                        <div class="site-summary">

                            <div class="site-summary-item">

                                <span>Site</span>

                                <strong>
                                    ${site ? site.name : '-'}
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Hostname</span>

                                <strong>
                                    ${device.hostname || '-'}
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>IP Address</span>

                                <strong>
                                    ${device.ip}
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Device Type</span>

                                <strong>
                                    ${device.type}
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Status</span>

                                <span class="badge bg-${badge}">
                                    ${statusText(device.status)}
                                </span>

                            </div>


                            <div class="site-summary-item">

                                <span>Last Poll</span>

                                <strong>
                                    ${device.lastPoll}
                                </strong>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="site-summary">

                            <div class="site-summary-item">

                                <span>Missed Poll</span>

                                <strong>
                                    ${device.missed}
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Packet Loss</span>

                                <strong>
                                    ${device.packetLoss}%
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Min Delay</span>

                                <strong>
                                    ${device.minDelay} ms
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Average Delay</span>

                                <strong>
                                    ${device.avgDelay} ms
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Max Delay</span>

                                <strong>
                                    ${device.maxDelay} ms
                                </strong>

                            </div>


                            <div class="site-summary-item">

                                <span>Uptime</span>

                                <strong>
                                    ${device.uptime}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                <div class="row g-3 mb-3">

                    <div class="col-md-6">

                        <div class="site-summary">

                            <div class="site-summary-item">

                                <span>Info 1</span>

                                <strong>
                                    ${device.info1 || '-'}
                                </strong>

                            </div>

                            <div class="site-summary-item">

                                <span>Info 2</span>

                                <strong>
                                    ${device.info2 || '-'}
                                </strong>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6">

                        <div class="site-summary">

                            <div class="site-summary-item">

                                <span>Monitoring Status</span>

                                <span class="badge bg-${badge}">
                                    ${statusText(device.status)}
                                </span>

                            </div>

                            <div class="site-summary-item">

                                <span>Polling</span>

                                <strong>
                                    ICMP
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                <h6 class="mb-2">

                    <i class="fas fa-terminal text-primary me-2"></i>

                    Recent Log Extract

                </h6>

                <pre class="device-log">${
                    device.log || '-'
                }</pre>

            `;


        new bootstrap.Modal(
            document.getElementById('routerDetailModal')
        ).show();

    };


    /* ============================================================
       14. LIVE CLOCK
    ============================================================ */

    function updateClock() {

        const now =
            new Date();


        const year =
            now.getFullYear();


        const month =
            String(
                now.getMonth() + 1
            ).padStart(2, '0');


        const day =
            String(
                now.getDate()
            ).padStart(2, '0');


        const hours =
            String(
                now.getHours()
            ).padStart(2, '0');


        const minutes =
            String(
                now.getMinutes()
            ).padStart(2, '0');


        const seconds =
            String(
                now.getSeconds()
            ).padStart(2, '0');


        document.getElementById(
            'dashboardClock'
        ).textContent =
            `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;

    }


    updateClock();

    setInterval(
        updateClock,
        1000
    );


    /* ============================================================
       15. DASHBOARD REFRESH
    ============================================================ */

    window.refreshDashboard = function () {

        const icon =
            document.getElementById('refreshIcon');


        icon.classList.add(
            'fa-spin'
        );


        setTimeout(
            function () {

                window.location.reload();

            },
            500
        );

    };


});

</script>

@endpush