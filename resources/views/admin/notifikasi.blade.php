@extends('layouts.admin')

@section('title', 'Notifikasi')

@push('styles')
<style>
    /* ── Filter bar ────────────────────────────────────── */
    .notif-filters .btn {
        min-width: 110px;
        font-weight: 500;
        font-size: .85rem;
    }
    .notif-filters .btn .notif-count {
        display: inline-block;
        min-width: 20px;
        padding: .15em .45em;
        margin-left: .35rem;
        font-size: .7rem;
        font-weight: 600;
        border-radius: 10px;
        background: rgba(255,255,255,.25);
        line-height: 1.3;
    }

    /* ── Notification list ─────────────────────────────── */
    .notif-list {
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        padding: 1rem 1.15rem;
        background: #fff;
        border: 1px solid #edf0f3;
        border-radius: .65rem;
        cursor: pointer;
        transition: background .15s, border-color .15s, box-shadow .15s;
        position: relative;
    }
    .notif-item:hover {
        background: #f8f9fc;
        border-color: #d9e2ec;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .notif-item.unread {
        background: #f0f7ff;
        border-left: 3px solid #3b82f6;
    }
    .notif-item.unread:hover {
        background: #e8f2ff;
    }

    /* unread dot */
    .notif-item .unread-dot {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3b82f6;
    }

    /* status indicator */
    .notif-indicator {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .notif-indicator.down {
        background: rgba(239,68,68,.1);
        color: #ef4444;
    }
    .notif-indicator.warning {
        background: rgba(234,179,8,.1);
        color: #d97706;
    }
    .notif-indicator.online {
        background: rgba(34,197,94,.1);
        color: #22c55e;
    }

    .notif-body {
        flex: 1;
        min-width: 0;
    }
    .notif-body .notif-title {
        font-weight: 600;
        font-size: .925rem;
        color: #1a2233;
        margin-bottom: .15rem;
    }
    .notif-body .notif-ip {
        font-size: .8rem;
        color: #8094ae;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    }
    .notif-body .notif-event {
        display: inline-block;
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: .2em .55em;
        border-radius: 4px;
        margin-top: .35rem;
    }
    .notif-body .notif-event.down {
        background: rgba(239,68,68,.1);
        color: #dc2626;
    }
    .notif-body .notif-event.warning {
        background: rgba(234,179,8,.12);
        color: #b45309;
    }
    .notif-body .notif-event.online {
        background: rgba(34,197,94,.1);
        color: #16a34a;
    }
    .notif-body .notif-message {
        font-size: .84rem;
        color: #5e6b7b;
        margin-top: .3rem;
    }
    .notif-meta {
        flex-shrink: 0;
        text-align: right;
        min-width: 100px;
    }
    .notif-meta .notif-time {
        font-size: .78rem;
        color: #8094ae;
        white-space: nowrap;
    }

    /* ── Modal overrides ───────────────────────────────── */
    .modal-notif .modal-content {
        border: none;
        border-radius: .75rem;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .modal-notif .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.1rem 1.25rem;
    }
    .modal-notif .modal-body {
        padding: 1.25rem;
    }
    .modal-notif .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .85rem 1.5rem;
    }
    .modal-notif .detail-label {
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #8094ae;
        margin-bottom: .2rem;
    }
    .modal-notif .detail-value {
        font-size: .9rem;
        color: #1a2233;
        font-weight: 500;
    }
    .modal-notif .detail-message {
        grid-column: 1 / -1;
        background: #f8f9fc;
        border-radius: .5rem;
        padding: .85rem 1rem;
        margin-top: .35rem;
        font-size: .85rem;
        color: #5e6b7b;
    }

    @media (max-width: 575.98px) {
        .notif-item { padding: .85rem; gap: .65rem; }
        .notif-indicator { width: 34px; height: 34px; font-size: .85rem; }
        .notif-meta { min-width: auto; }
        .notif-meta .notif-time { font-size: .72rem; }
        .modal-notif .detail-grid { grid-template-columns: 1fr; }
        .notif-filters .btn { min-width: 0; font-size: .78rem; padding: .35rem .6rem; }
    }
</style>
@endpush

@section('content')
{{-- HEADER --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h5 class="title mb-1">Notifikasi</h5>
        <p class="text-soft small mb-0">Pantau perubahan status dan gangguan perangkat jaringan RADNET.</p>
    </div>
</div>

{{-- FILTER --}}
<div class="d-flex flex-wrap gap-2 mb-4 notif-filters" id="notifFilters">
    <button type="button" class="btn btn-outline-primary active" data-filter="all">
        Semua<span class="notif-count" id="countAll">0</span>
    </button>
    <button type="button" class="btn btn-outline-danger" data-filter="down">
        DOWN<span class="notif-count" id="countDown">0</span>
    </button>
    <button type="button" class="btn btn-outline-warning" data-filter="warning">
        WARNING<span class="notif-count" id="countWarning">0</span>
    </button>
    <button type="button" class="btn btn-outline-success" data-filter="online">
        BACK ONLINE<span class="notif-count" id="countOnline">0</span>
    </button>
</div>

{{-- DAFTAR NOTIFIKASI --}}
<div class="notif-list" id="notifList"></div>

{{-- EMPTY STATE --}}
<div class="text-center py-5 d-none" id="notifEmpty">
    <em class="icon ni ni-bell-off text-soft" style="font-size:2.5rem;opacity:.35"></em>
    <p class="text-soft mt-2 mb-0">Tidak ada notifikasi untuk filter ini.</p>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade modal-notif" id="notifDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notifDetailTitle">Detail Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notifDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── PLACEHOLDER DATA: ganti dengan data asli dari backend ── */
    var notifications = [
        {
            id: 1,
            device: 'PJB',
            ip: '202.154.4.134',
            status: 'down',
            event: 'DOWN',
            message: 'Host Unreachable - 100% packet loss, no response sejak 14:50',
            time: '2026-09-09T14:50:00',
            timeLabel: '4 jam yang lalu',
            latency: null,
            monitorType: 'ICMP',
            read: false
        },
        {
            id: 2,
            device: 'SBY-JKT',
            ip: '202.154.0.29',
            status: 'warning',
            event: 'WARNING',
            message: 'Packet loss 15%, avg delay 120ms - koneksi tidak stabil',
            time: '2026-09-09T15:30:00',
            timeLabel: '30 menit yang lalu',
            latency: 120,
            monitorType: 'ICMP',
            read: false
        },
        {
            id: 3,
            device: 'POP Gresik',
            ip: '202.154.4.254',
            status: 'online',
            event: 'BACK ONLINE',
            message: 'Device kembali aktif - Response 12ms',
            time: '2026-09-09T15:47:00',
            timeLabel: '13 menit yang lalu',
            latency: 12,
            monitorType: 'ICMP',
            read: true
        },
        {
            id: 4,
            device: 'Border',
            ip: '202.154.4.3',
            status: 'warning',
            event: 'WARNING',
            message: 'Packet loss 1% - latency meningkat dari 3ms ke 8ms',
            time: '2026-09-09T15:10:00',
            timeLabel: '50 menit yang lalu',
            latency: 8,
            monitorType: 'ICMP',
            read: true
        },
        {
            id: 5,
            device: 'SMKN-1',
            ip: '202.154.4.241',
            status: 'online',
            event: 'BACK ONLINE',
            message: 'Device recovered - Response 11ms',
            time: '2026-09-09T15:35:00',
            timeLabel: '25 menit yang lalu',
            latency: 11,
            monitorType: 'ICMP',
            read: true
        },
        {
            id: 6,
            device: 'Intiland 1',
            ip: '202.154.4.4',
            status: 'down',
            event: 'DOWN',
            message: 'Request timeout - Host Unreachable',
            time: '2026-09-09T13:20:00',
            timeLabel: '2 jam yang lalu',
            latency: null,
            monitorType: 'ICMP',
            read: false
        },
        {
            id: 7,
            device: 'IIX-JI',
            ip: '218.100.32.1',
            status: 'online',
            event: 'BACK ONLINE',
            message: 'Normal - Response 5ms, 0% packet loss',
            time: '2026-09-09T15:45:00',
            timeLabel: '15 menit yang lalu',
            latency: 5,
            monitorType: 'ICMP',
            read: true
        },
        {
            id: 8,
            device: '56.17',
            ip: '202.154.56.17',
            status: 'down',
            event: 'DOWN',
            message: 'Host Unreachable - Missed 200 consecutive polls',
            time: '2026-09-09T12:00:00',
            timeLabel: '3 jam yang lalu',
            latency: null,
            monitorType: 'ICMP',
            read: false
        },
        {
            id: 9,
            device: 'SBY-JKT',
            ip: '202.154.0.29',
            status: 'down',
            event: 'DOWN',
            message: 'Complete outage - 100% packet loss',
            time: '2026-09-09T11:00:00',
            timeLabel: '4 jam yang lalu',
            latency: null,
            monitorType: 'ICMP',
            read: true
        },
        {
            id: 10,
            device: 'POP Gresik',
            ip: '202.154.4.254',
            status: 'warning',
            event: 'WARNING',
            message: 'High latency detected - avg delay 45ms (normal: 12ms)',
            time: '2026-09-09T14:15:00',
            timeLabel: '1 jam yang lalu',
            latency: 45,
            monitorType: 'ICMP',
            read: true
        }
    ];
    /* ── END PLACEHOLDER ────────────────────────────────────────── */

    var currentFilter = 'all';
    var notifList     = document.getElementById('notifList');
    var notifEmpty    = document.getElementById('notifEmpty');

    /* ── Status color map ──────────────────────────────────────── */
    function indicatorClass(status) {
        if (status === 'down')     return 'down';
        if (status === 'warning')  return 'warning';
        return 'online';
    }

    function iconFor(status) {
        if (status === 'down')     return 'ni ni-cross-c';
        if (status === 'warning')  return 'ni ni-alert';
        return 'ni ni-check';
    }

    /* ── Render list ───────────────────────────────────────────── */
    function render() {
        var filtered = notifications.filter(function (n) {
            return currentFilter === 'all' || n.status === currentFilter;
        });

        if (!filtered.length) {
            notifList.innerHTML = '';
            notifEmpty.classList.remove('d-none');
            return;
        }

        notifEmpty.classList.add('d-none');

        var html = '';
        filtered.forEach(function (n) {
            var cls = indicatorClass(n.status);
            html +=
                '<div class="notif-item' + (n.read ? '' : ' unread') + '" data-id="' + n.id + '">' +
                    (n.read ? '' : '<span class="unread-dot"></span>') +
                    '<div class="notif-indicator ' + cls + '">' +
                        '<em class="icon ' + iconFor(n.status) + '"></em>' +
                    '</div>' +
                    '<div class="notif-body">' +
                        '<div class="notif-title">' + n.device + '</div>' +
                        '<div class="notif-ip">' + n.ip + '</div>' +
                        '<span class="notif-event ' + cls + '">' + n.event + '</span>' +
                        '<div class="notif-message">' + n.message + '</div>' +
                    '</div>' +
                    '<div class="notif-meta">' +
                        '<div class="notif-time">' + n.timeLabel + '</div>' +
                    '</div>' +
                '</div>';
        });

        notifList.innerHTML = html;

        /* attach click handlers */
        notifList.querySelectorAll('.notif-item').forEach(function (el) {
            el.addEventListener('click', function () {
                var id = parseInt(el.getAttribute('data-id'), 10);
                openDetail(id);
            });
        });
    }

    /* ── Update filter counts ──────────────────────────────────── */
    function updateCounts() {
        var counts = { all: 0, down: 0, warning: 0, online: 0 };
        notifications.forEach(function (n) {
            counts.all++;
            if (counts[n.status] !== undefined) counts[n.status]++;
        });
        document.getElementById('countAll').textContent    = counts.all;
        document.getElementById('countDown').textContent   = counts.down;
        document.getElementById('countWarning').textContent = counts.warning;
        document.getElementById('countOnline').textContent  = counts.online;
    }

    /* ── Filter click ──────────────────────────────────────────── */
    document.querySelectorAll('#notifFilters .btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#notifFilters .btn').forEach(function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');
            currentFilter = btn.getAttribute('data-filter');
            render();
        });
    });

    /* ── Detail modal ──────────────────────────────────────────── */
    function openDetail(id) {
        var n = notifications.find(function (x) { return x.id === id; });
        if (!n) return;

        /* mark as read */
        n.read = true;
        updateCounts();
        render();

        var cls = indicatorClass(n.status);

        document.getElementById('notifDetailTitle').textContent = 'Detail Notifikasi';

        var latencyRow = n.latency !== null
            ? '<div><div class="detail-label">Response / Latency</div><div class="detail-value">' + n.latency + ' ms</div></div>'
            : '<div><div class="detail-label">Response / Latency</div><div class="detail-value text-muted">-</div></div>';

        document.getElementById('notifDetailBody').innerHTML =
            '<div class="detail-grid">' +
                '<div><div class="detail-label">Nama Perangkat</div><div class="detail-value">' + n.device + '</div></div>' +
                '<div><div class="detail-label">IP Address</div><div class="detail-value"><code>' + n.ip + '</code></div></div>' +
                '<div><div class="detail-label">Status</div><div class="detail-value"><span class="notif-event ' + cls + '">' + n.event + '</span></div></div>' +
                '<div><div class="detail-label">Jenis Monitoring</div><div class="detail-value">' + n.monitorType + '</div></div>' +
                '<div><div class="detail-label">Waktu Kejadian</div><div class="detail-value">' + new Date(n.time).toLocaleString('id-ID', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' }) + '</div></div>' +
                latencyRow +
                '<div class="detail-message"><div class="detail-label">Pesan / Keterangan</div><div class="detail-value mt-1">' + n.message + '</div></div>' +
            '</div>';

        var modal = new bootstrap.Modal(document.getElementById('notifDetailModal'));
        modal.show();
    }

    /* ── Init ──────────────────────────────────────────────────── */
    updateCounts();
    render();

});
</script>
@endpush
