@extends('layouts.admin')

@section('title', 'Monitoring Jaringan')

@push('styles')
<style>
    /* Topology map disediakan oleh komponen
       admin.components.network-topology (single source of truth). */

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
        .modal-monitoring .table { font-size: .8rem; }
        .modal-monitoring .table thead th,
        .modal-monitoring .table tbody td { padding: .5rem; }
    }
</style>
@endpush

@section('content')
{{-- =========================================================
     PAGE HEAD
========================================================== --}}
<x-admin.page-head
    title="Monitoring Jaringan"
    subtitle="Peta topologi dan status real-time perangkat RADNET."
/>

{{-- RINGKASAN STATUS --}}
<div class="row g-gs">
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
        <div class="mt-3">
            @include('admin.components.network-topology', [
                'topology' => $topology ?? null,
                'endpoint' => route('admin.monitoring.data'),
                'deviceEndpoint' => route('admin.monitoring.device', ['device' => '__DEVICE_ID__']),
            ])
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

});
</script>
@endpush