@extends('layouts.admin')

@section('title', 'Monitoring Jaringan')

@push('styles')
<style>
    /* ── Map area ─────────────────────────────────────── */
    .monitoring-map-area {
        min-height: 520px;
        background: linear-gradient(135deg, #f8f9fc 0%, #eef1f6 100%);
        border: 2px dashed #d9e2ec;
        border-radius: .5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: border-color .2s;
    }
    .monitoring-map-area:hover {
        border-color: #b4bdd4;
    }

    /* ── KPI cards clickable ─────────────────────────── */
    .kpi-card {
        cursor: pointer;
        transition: transform .15s, box-shadow .15s;
        border: 1px solid transparent;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,.08);
        border-color: #d9e2ec;
    }
    .kpi-card:active {
        transform: translateY(-1px);
    }
    .kpi-card .kpi-arrow {
        opacity: 0;
        transition: opacity .15s;
    }
    .kpi-card:hover .kpi-arrow {
        opacity: 1;
    }

    /* ── Modal overrides ──────────────────────────────── */
    .modal-monitoring .modal-content {
        border: none;
        border-radius: .75rem;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .modal-monitoring .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.1rem 1.25rem;
    }
    .modal-monitoring .modal-body {
        padding: 0;
    }
    .modal-monitoring .table {
        margin-bottom: 0;
        font-size: .875rem;
    }
    .modal-monitoring .table thead th {
        background: #f8f9fc;
        font-weight: 600;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .3px;
        color: #5e6b7b;
        border-bottom: 2px solid #edf0f3;
        padding: .7rem .75rem;
        white-space: nowrap;
    }
    .modal-monitoring .table tbody td {
        padding: .65rem .75rem;
        vertical-align: middle;
        border-color: #f1f5f9;
        white-space: nowrap;
    }
    .modal-monitoring .table tbody tr:hover {
        background: #f8f9fc;
    }
    .modal-monitoring .badge {
        font-weight: 500;
        font-size: .75rem;
        padding: .3em .6em;
    }
    .modal-monitoring .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: #8094ae;
    }
    .modal-monitoring .empty-state .icon {
        font-size: 2.5rem;
        opacity: .4;
    }

    @media (max-width: 767.98px) {
        .monitoring-map-area { min-height: 320px; }
        .modal-monitoring .table { font-size: .8rem; }
        .modal-monitoring .table thead th,
        .modal-monitoring .table tbody td { padding: .5rem; }
    }
</style>
@endpush

@section('content')
{{-- RINGKASAN STATUS --}}
<div class="row g-gs mb-4">
    {{-- Items Up --}}
    <div class="col-sm-6 col-xl-4">
        <div class="card h-100 kpi-card" data-kpi="up" onclick="openKpiModal('up')">
            <div class="card-body">
                <div class="card-title-group align-items-start">
                    <div class="card-title">
                        <h4 class="title">Items Up</h4>
                    </div>
                    <div class="media media-middle media-circle media-sm text-bg-success">
                        <em class="icon icon-md ni ni-arrow-up"></em>
                    </div>
                </div>
                <div class="mt-2 d-flex align-items-end justify-content-between">
                    <div>
                        <div class="amount h1 mb-0" id="kpiUpCount">-</div>
                        <span class="text-soft small">Perangkat aktif</span>
                    </div>
                    <em class="icon ni ni-chevron-right text-soft kpi-arrow"></em>
                </div>
            </div>
        </div>
    </div>

    {{-- Items Down --}}
    <div class="col-sm-6 col-xl-4">
        <div class="card h-100 kpi-card" data-kpi="down" onclick="openKpiModal('down')">
            <div class="card-body">
                <div class="card-title-group align-items-start">
                    <div class="card-title">
                        <h4 class="title">Items Down</h4>
                    </div>
                    <div class="media media-middle media-circle media-sm text-bg-danger">
                        <em class="icon icon-md ni ni-arrow-down"></em>
                    </div>
                </div>
                <div class="mt-2 d-flex align-items-end justify-content-between">
                    <div>
                        <div class="amount h1 mb-0" id="kpiDownCount">-</div>
                        <span class="text-soft small">Perangkat bermasalah</span>
                    </div>
                    <em class="icon ni ni-chevron-right text-soft kpi-arrow"></em>
                </div>
            </div>
        </div>
    </div>

    {{-- Services Down --}}
    <div class="col-sm-6 col-xl-4">
        <div class="card h-100 kpi-card" data-kpi="services" onclick="openKpiModal('services')">
            <div class="card-body">
                <div class="card-title-group align-items-start">
                    <div class="card-title">
                        <h4 class="title">Services Down</h4>
                    </div>
                    <div class="media media-middle media-circle media-sm text-bg-warning">
                        <em class="icon icon-md ni ni-alert"></em>
                    </div>
                </div>
                <div class="mt-2 d-flex align-items-end justify-content-between">
                    <div>
                        <div class="amount h1 mb-0" id="kpiServicesCount">-</div>
                        <span class="text-soft small">Service bermasalah</span>
                    </div>
                    <em class="icon ni ni-chevron-right text-soft kpi-arrow"></em>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAP RADNET --}}
<div class="card">
    <div class="card-body">
        <div class="card-title-group">
            <div class="card-title">
                <h5 class="title">MAP RADNET</h5>
            </div>
            <span class="text-xs text-soft">RADNET Wireless Network 2025</span>
        </div>
        <div class="monitoring-map-area mt-3">
            <em class="icon ni ni-map text-soft display-4"></em>
            <p class="text-soft mt-2 mb-0">Visualisasi peta interaktif akan ditampilkan di sini</p>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODALS
================================================== --}}

{{-- Modal: Items Up --}}
<div class="modal fade modal-monitoring" id="modalUp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Items Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalUpBody"></div>
        </div>
    </div>
</div>

{{-- Modal: Items Down --}}
<div class="modal fade modal-monitoring" id="modalDown" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Items Down</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDownBody"></div>
        </div>
    </div>
</div>

{{-- Modal: Services Down --}}
<div class="modal fade modal-monitoring" id="modalServices" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Services Down</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalServicesBody"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── PLACEHOLDER DATA: ganti dengan data asli dari backend ── */
    var devices = [
        { name: 'POP Gresik',  ip: '202.154.4.254', status: 'up',      latency: 12,  info: 'Uptime 99.8%' },
        { name: 'Intiland 1',  ip: '202.154.4.4',   status: 'up',      latency: 9,   info: 'Uptime 99.99%' },
        { name: 'Border',      ip: '202.154.4.3',   status: 'up',      latency: 8,   info: 'Uptime 99.2%' },
        { name: 'SMKN-1',      ip: '202.154.4.241', status: 'up',      latency: 11,  info: 'Uptime 98.7%' },
        { name: 'IIX-JI',      ip: '218.100.32.1',  status: 'up',      latency: 5,   info: 'Uptime 99.5%' },
        { name: '56.17',       ip: '202.154.56.17', status: 'up',      latency: 7,   info: 'Uptime 99.9%' },
        { name: 'Core Router', ip: '10.0.0.1',      status: 'up',      latency: 3,   info: 'Uptime 99.95%' },
        { name: 'Switch SW-1', ip: '10.0.0.2',      status: 'up',      latency: 2,   info: 'Uptime 99.99%' },
        { name: 'AP Tower-3',  ip: '192.168.1.30',  status: 'up',      latency: 15,  info: 'Uptime 99.1%' },
        { name: 'MikroTik-7',  ip: '192.168.1.7',   status: 'up',      latency: 6,   info: 'Uptime 99.7%' },
        { name: 'OLT-BRI',     ip: '202.154.4.1',   status: 'up',      latency: 4,   info: 'Uptime 99.9%' },
        { name: 'RB-SJ-01',    ip: '192.168.10.1',  status: 'up',      latency: 18,  info: 'Uptime 98.5%' },
        { name: 'RB-SJ-02',    ip: '192.168.10.2',  status: 'up',      latency: 22,  info: 'Uptime 97.8%' },
        { name: 'Backup GW',   ip: '10.0.0.254',    status: 'up',      latency: 1,   info: 'Uptime 99.99%' }
    ];

    var services = [
        { name: 'SSH',        device: 'POP Gresik',  status: 'up',   info: 'Port 22 open' },
        { name: 'HTTP',       device: 'Border',      status: 'up',   info: 'Port 80/443 open' },
        { name: 'SNMP',       device: 'Core Router', status: 'up',   info: 'Port 161 responding' },
        { name: 'DNS',        device: 'POP Gresik',  status: 'up',   info: 'Port 53 responding' },
        { name: 'NTP',        device: 'Switch SW-1', status: 'up',   info: 'Port 123 open' },
        { name: 'ICMP Ping',  device: 'All Devices', status: 'up',   info: 'Monitoring active' }
    ];
    /* ── END PLACEHOLDER ────────────────────────────────────────── */

    var upDevices     = devices.filter(function (d) { return d.status === 'up'; });
    var downDevices   = devices.filter(function (d) { return d.status === 'down' || d.status === 'warning'; });
    var downServices  = services.filter(function (s) { return s.status === 'down'; });

    /* ── Update KPI counts ─────────────────────────────────────── */
    document.getElementById('kpiUpCount').textContent        = upDevices.length;
    document.getElementById('kpiDownCount').textContent      = downDevices.length;
    document.getElementById('kpiServicesCount').textContent  = downServices.length;

    /* ── Status helpers ────────────────────────────────────────── */
    function statusBadge(status) {
        var map = { up: 'success', down: 'danger', warning: 'warning' };
        return '<span class="badge bg-' + (map[status] || 'secondary') + '">' + status.toUpperCase() + '</span>';
    }

    /* ── Build table HTML ──────────────────────────────────────── */
    function buildDeviceTable(list) {
        if (!list.length) {
            return '<div class="empty-state"><em class="icon ni ni-check-circle"></em><p class="mt-2 mb-0">Tidak ada perangkat yang bermasalah.</p></div>';
        }
        var html = '<div class="table-responsive"><table class="table"><thead><tr>' +
            '<th>Nama Perangkat</th><th>IP Address</th><th>Status</th><th>Keterangan</th>' +
            '</tr></thead><tbody>';
        list.forEach(function (d) {
            html += '<tr>' +
                '<td><strong>' + d.name + '</strong></td>' +
                '<td><code>' + d.ip + '</code></td>' +
                '<td>' + statusBadge(d.status) + '</td>' +
                '<td>' + d.info + '</td>' +
                '</tr>';
        });
        html += '</tbody></table></div>';
        return html;
    }

    function buildServiceTable(list) {
        if (!list.length) {
            return '<div class="empty-state"><em class="icon ni ni-check-circle"></em><p class="mt-2 mb-0">Tidak ada service yang bermasalah.</p></div>';
        }
        var html = '<div class="table-responsive"><table class="table"><thead><tr>' +
            '<th>Nama Service</th><th>Perangkat</th><th>Status</th><th>Keterangan</th>' +
            '</tr></thead><tbody>';
        list.forEach(function (s) {
            html += '<tr>' +
                '<td><strong>' + s.name + '</strong></td>' +
                '<td>' + s.device + '</td>' +
                '<td>' + statusBadge(s.status) + '</td>' +
                '<td>' + s.info + '</td>' +
                '</tr>';
        });
        html += '</tbody></table></div>';
        return html;
    }

    /* ── Open modal ────────────────────────────────────────────── */
    window.openKpiModal = function (type) {
        var bsModal;
        if (type === 'up') {
            document.getElementById('modalUpBody').innerHTML = buildDeviceTable(upDevices);
            bsModal = new bootstrap.Modal(document.getElementById('modalUp'));
        } else if (type === 'down') {
            document.getElementById('modalDownBody').innerHTML = buildDeviceTable(downDevices);
            bsModal = new bootstrap.Modal(document.getElementById('modalDown'));
        } else {
            document.getElementById('modalServicesBody').innerHTML = buildServiceTable(downServices);
            bsModal = new bootstrap.Modal(document.getElementById('modalServices'));
        }
        bsModal.show();
    };

});
</script>
@endpush
