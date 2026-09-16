@extends('layouts.admin')

@section('title', 'Monitoring Jaringan')

@push('styles')
<style>
    /* ── Map area ─────────────────────────────────────── */
    .monitoring-map-area {
        min-height: 520px;
        background: linear-gradient(135deg, #f8f9fc 0%, #eef1f6 100%);
        border: 1px solid #e2e8f0;
        border-radius: .5rem;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .monitoring-map-area:hover {
        border-color: #b4bdd4;
    }

    /* ── Grid node (CSS Grid, 1 node = 1 device) ────── */
    .monitoring-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(18px, 1fr));
        gap: 12px;
        width: 100%;
    }
    .monitoring-node {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,.9);
        padding: 0;
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(0,0,0,.12);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .monitoring-node:hover,
    .monitoring-node:focus-visible {
        transform: scale(1.35);
        box-shadow: 0 4px 12px rgba(0,0,0,.2);
        z-index: 1;
    }
    .node-up      { background-color: #22c55e; }
    .node-down    { background-color: #ef4444; animation: node-pulse 1.6s ease-out infinite; }
    .node-unknown { background-color: #94a3b8; }

    /* Animasi pulse untuk device DOWN */
    @keyframes node-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(239,68,68,.5); }
        70%  { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
        100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
    }

    /* ── Legend map ──────────────────────────────────── */
    .monitoring-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: center;
        margin-top: 1rem;
        font-size: .78rem;
        color: #5e6b7b;
    }
    .monitoring-legend .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .monitoring-legend .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .monitoring-legend .legend-dot.up      { background-color: #22c55e; }
    .monitoring-legend .legend-dot.down    { background-color: #ef4444; }
    .monitoring-legend .legend-dot.unknown { background-color: #94a3b8; }

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
        .monitoring-grid { gap: 8px; }
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
                        <div class="amount h1 mb-0" id="kpiUpCount">{{ $totalUp }}</div>
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
                        <div class="amount h1 mb-0" id="kpiDownCount">{{ $totalDown }}</div>
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
                        <div class="amount h1 mb-0" id="kpiServicesCount">{{ $totalServicesDown }}</div>
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
            {{-- Grid node: 1 bulat kecil = 1 device, warna sesuai status --}}
            <div class="monitoring-grid" id="monitoringGrid">
                @foreach($devices as $device)
                    <button
                        type="button"
                        class="monitoring-node node-{{ $device->status }}"
                        title="{{ $device->name }} ({{ $device->ip_address }})"
                        aria-label="{{ $device->name }}"
                        onclick="showDeviceDetail('{{ $device->id }}')">
                    </button>
                @endforeach
            </div>

            {{-- Legend --}}
            <div class="monitoring-legend">
                <span class="legend-item">
                    <span class="legend-dot up"></span> UP ({{ $totalUp }})
                </span>
                <span class="legend-item">
                    <span class="legend-dot down"></span> DOWN ({{ $totalDown }})
                </span>
                <span class="legend-item">
                    <span class="legend-dot unknown"></span> UNKNOWN ({{ $totalUnknown }})
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODALS
═══════════════════════════════════════════════════ --}}

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

{{-- Modal: Detail Device (dibuka dari klik node / baris) --}}
<div class="modal fade modal-monitoring" id="modalDeviceDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deviceDetailTitle">Detail Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deviceDetailBody"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── DATA ASLI dari controller (Eloquent) ─────────────── */
    var devices = @json($devices);

    var upDevices    = devices.filter(function (d) { return d.status === 'up'; });
    var downDevices  = devices.filter(function (d) { return d.status === 'down'; });
    // Data service belum tersedia di database, dikosongkan dulu
    var downServices = [];

    /* ── Status helpers ───────────────────────────────────── */
    function statusBadge(status) {
        var map = { up: 'success', down: 'danger', unknown: 'secondary' };
        return '<span class="badge bg-' + (map[status] || 'secondary') + '">' + String(status).toUpperCase() + '</span>';
    }

    /* ── Build tabel device ────────────────────────────────── */
    function buildDeviceTable(list) {
        if (!list.length) {
            return '<div class="empty-state"><em class="icon ni ni-check-circle"></em><p class="mt-2 mb-0">Tidak ada perangkat.</p></div>';
        }
        var html = '<div class="table-responsive"><table class="table"><thead><tr>' +
            '<th>Nama Perangkat</th><th>IP Address</th><th>Type</th><th>Status</th><th>Avg Delay</th>' +
            '</tr></thead><tbody>';
        list.forEach(function (d) {
            html += '<tr>' +
                '<td><strong>' + d.name + '</strong></td>' +
                '<td><code>' + d.ip_address + '</code></td>' +
                '<td>' + (d.type || '-') + '</td>' +
                '<td>' + statusBadge(d.status) + '</td>' +
                '<td>' + (d.avg_delay !== null ? d.avg_delay + ' ms' : '-') + '</td>' +
                '</tr>';
        });
        html += '</tbody></table></div>';
        return html;
    }

    /* ── Build detail device (modal) ───────────────────────── */
    function buildDeviceDetail(d) {
        function row(label, value) {
            return '<tr><td class="text-muted">' + label + '</td>' +
                '<td class="text-end"><strong>' + (value === null || value === undefined || value === '' ? '-' : value) + '</strong></td></tr>';
        }
        var html = '<div class="table-responsive"><table class="table"><tbody>';
        html += row('Nama Perangkat', d.name);
        html += row('IP Address', d.ip_address);
        html += row('Type', d.type);
        html += '<tr><td class="text-muted">Status</td><td class="text-end">' + statusBadge(d.status) + '</td></tr>';
        html += row('Jumlah Polls', d.polls);
        html += row('Responded', d.responded_percentage !== null ? d.responded_percentage + '%' : '-');
        html += row('Missed', d.missed_percentage !== null ? d.missed_percentage + '%' : '-');
        html += row('Down Time', d.down_time);
        html += row('Period', d.period);
        html += row('Alerts', d.alerts_count > 0 ? d.alerts_count : 0);
        html += row('Rata-rata Delay', d.avg_delay !== null ? d.avg_delay + ' ms' : '-');
        html += row('Min Delay', d.min_delay !== null ? d.min_delay + ' ms' : '-');
        html += row('Max Delay', d.max_delay !== null ? d.max_delay + ' ms' : '-');
        html += '</tbody></table></div>';
        return html;
    }

    /* ── Open modal KPI berdasarkan status ─────────────────── */
    window.openKpiModal = function (type) {
        var bsModal;
        if (type === 'up') {
            document.getElementById('modalUpBody').innerHTML = buildDeviceTable(upDevices);
            bsModal = new bootstrap.Modal(document.getElementById('modalUp'));
        } else if (type === 'down') {
            document.getElementById('modalDownBody').innerHTML = buildDeviceTable(downDevices);
            bsModal = new bootstrap.Modal(document.getElementById('modalDown'));
        } else {
            document.getElementById('modalServicesBody').innerHTML =
                '<div class="empty-state"><em class="icon ni ni-check-circle"></em><p class="mt-2 mb-0">Belum ada data service yang dipantau.</p></div>';
            bsModal = new bootstrap.Modal(document.getElementById('modalServices'));
        }
        bsModal.show();
    };

    /* ── Open modal detail device dari node map ─────────────── */
    window.showDeviceDetail = function (id) {
        var device = devices.find(function (d) { return String(d.id) === String(id); });
        if (!device) return;
        document.getElementById('deviceDetailTitle').textContent = 'Detail Device';
        document.getElementById('deviceDetailBody').innerHTML = buildDeviceDetail(device);
        new bootstrap.Modal(document.getElementById('modalDeviceDetail')).show();
    };

});
</script>
@endpush