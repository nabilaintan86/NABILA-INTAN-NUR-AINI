@extends('layouts.admin')

@section('title', 'Network Map · RADNET NOC')

@push('styles')
<style>
    .nm2-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }
    .nm2-badge-count { font-size: 12px; }
    .nm2-stage {
        position: relative;
        overflow: auto;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fbfdff;
        padding: 12px;
    }
    .nm2-canvas {
        position: relative;
        display: inline-block;
        min-width: 100%;
    }
    .nm2-canvas img {
        display: block;
        max-width: none;
        user-select: none;
        -webkit-user-drag: none;
    }
    .nm2-marker {
        position: absolute;
        width: 32px;
        height: 32px;
        transform: translate(-50%, -50%);
        border-radius: 8px;
        background: #fff;
        border: 2px solid #94a3b8;
        box-shadow: 0 2px 6px rgba(15, 23, 42, .30);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 5;
        transition: transform .1s ease, box-shadow .1s ease;
    }
    .nm2-marker:hover {
        transform: translate(-50%, -50%) scale(1.12);
        box-shadow: 0 3px 10px rgba(15, 23, 42, .40);
    }
    .nm2-marker img {
        width: 22px;
        height: 22px;
        object-fit: contain;
    }
    .nm2-marker .nm2-status {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        border: 2px solid #fff;
    }
    .nm2-marker.st-up      { border-color: #22c55e; }
    .nm2-marker.st-down    { border-color: #ef4444; }
    .nm2-marker.st-other   { border-color: #f59e0b; }
    .nm2-marker.st-up .nm2-status      { background: #22c55e; }
    .nm2-marker.st-down .nm2-status    { background: #ef4444; }
    .nm2-marker.st-other .nm2-status   { background: #f59e0b; }
    .nm2-marker-label {
        position: absolute;
        bottom: -19px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(15, 23, 42, .85);
        color: #fff;
        font-size: 10.5px;
        line-height: 1;
        padding: 3px 6px;
        border-radius: 4px;
        white-space: nowrap;
        pointer-events: none;
    }
    .nm2-empty {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }

    /* ── Detail popup device ── */
    .nm2-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, .38);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .25s ease, visibility .25s ease;
    }
    .nm2-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .nm2-card {
        background: #fff;
        border-radius: 12px;
        width: 100%;
        max-width: 700px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 18px 50px rgba(15, 23, 42, .28);
    }
    .nm2-head-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        background: #1e40af;
        color: #fff;
    }
    .nm2-head-card h5 { margin: 0; color: #fff; }
    .nm2-close {
        border: none;
        background: rgba(255,255,255,.08);
        border-radius: 6px;
        color: #fff;
        font-size: 20px;
        line-height: 1;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }
    .nm2-close:hover { background: rgba(255,255,255,.2); }
    .nm2-body { padding: 16px 20px 20px; }
    .nm2-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 40px 0;
        color: #64748b;
        font-size: 13px;
    }
    .nm2-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 9px 0;
        font-size: 12.5px;
        border-bottom: 1px solid #f1f5f9;
    }
    .nm2-row:last-child { border-bottom: none; }
    .nm2-label { color: #64748b; }
    .nm2-value { font-weight: 600; color: #0f172a; text-align: right; word-break: break-word; }
    .nm2-status-badge { font-weight: 700; }
    .nm2-status-badge.up    { color: #16a34a; }
    .nm2-status-badge.down  { color: #dc2626; }
    .nm2-status-badge.warning { color: #d97706; }
    .nm2-status-badge.other { color: #64748b; }
    .nm2-log {
        margin-top: 4px;
        padding: 8px 10px;
        border-left: 3px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 0 6px 6px 0;
        font-size: 12px;
        color: #475569;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .nm2-log.up { border-left-color: #22c55e; }
    .nm2-log.down { border-left-color: #ef4444; }
    .nm2-log.warning { border-left-color: #f59e0b; }
    .nm2-log .t { color: #94a3b8; font-family: ui-monospace, monospace; }
    .nm2-log .s { font-weight: 700; }
    .nm2-log.up .s { color: #16a34a; }
    .nm2-log.down .s { color: #dc2626; }
    .nm2-log.warning .s { color: #d97706; }
</style>
@endpush

@section('content')

<div class="nm2-head">
    <div class="d-flex align-items-center gap-3">
        <h5 class="title mb-0">Network Map</h5>
        <span class="badge bg-primary nm2-badge-count">
            {{ $positionedDevices->count() }} / {{ $devices->count() }} device diposisikan
        </span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.map.editor') }}" class="btn btn-primary btn-sm">
            <em class="icon ni ni-edit"></em> Map Editor
        </a>
    </div>
</div>

{{-- PETA ── --}}
<div class="card">
    <div class="card-body">
        <div class="nm2-stage">
            <div class="nm2-canvas" id="mapCanvas">
                <img src="{{ asset('images/devices/MAP.png') }}" alt="Network Map" id="mapImg" draggable="false">

                @forelse ($positionedDevices as $device)
                    @php
                        $iconMap = ['router' => 'router.png', 'server' => 'server.png', 'switch' => 'switch.png', 'wifi' => 'wifi.png', 'pc' => 'pc.png'];
                        $iconFile = $iconMap[strtolower((string) $device->icon_type)] ?? 'pc.png';
                    @endphp
                    <div
                        class="nm2-marker st-{{ $device->status === 'down' ? 'down' : ($device->status === 'up' ? 'up' : 'other') }}"
                        data-id="{{ $device->id }}"
                        data-x="{{ $device->pos_x }}"
                        data-y="{{ $device->pos_y }}"
                        title="{{ $device->name }} ({{ $device->ip_address }})"
                    >
                        <img src="{{ asset('images/devices/' . $iconFile) }}" alt="">
                        <span class="nm2-status"></span>
                        <span class="nm2-marker-label">{{ $device->name }}</span>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- DEVICE BELUM DIPOSISIKAN ── --}}
@if ($unpositionedDevices->isNotEmpty())
<div class="card mt-3">
    <div class="card-body">
        <div class="card-title-group mb-3">
            <div class="card-title">
                <h6 class="title">
                    Belum diposisikan
                    <span class="badge bg-danger ms-1">{{ $unpositionedDevices->count() }}</span>
                </h6>
            </div>
            <a href="{{ route('admin.map.editor') }}" class="btn btn-sm btn-outline-primary">Atur sekarang</a>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>IP Address</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unpositionedDevices as $device)
                        <tr>
                            <td><strong>{{ $device->name }}</strong></td>
                            <td><code>{{ $device->ip_address }}</code></td>
                            <td>{{ $device->type }}</td>
                            <td>
                                <span class="badge bg-{{ $device->status === 'up' ? 'success' : ($device->status === 'down' ? 'danger' : 'warning') }}">
                                    {{ strtoupper($device->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- POPUP DETAIL ── --}}
<div class="nm2-overlay" id="nm2Overlay" role="dialog" aria-modal="true">
    <div class="nm2-card">
        <div class="nm2-head-card">
            <h5 id="nm2Name">-</h5>
            <button type="button" class="nm2-close" id="nm2Close" aria-label="Tutup">&times;</button>
        </div>
        <div class="nm2-body">
            <div class="nm2-loading" id="nm2Loading">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Memuat detail device...
            </div>
            <div id="nm2Content" style="display:none;">
                <div class="nm2-row"><span class="nm2-label">Address</span><span class="nm2-value" id="nm2Addr">-</span></div>
                <div class="nm2-row"><span class="nm2-label">Last Poll</span><span class="nm2-value" id="nm2LastPoll">-</span></div>
                <div class="nm2-row"><span class="nm2-label">Status</span><span class="nm2-value nm2-status-badge" id="nm2Status">-</span></div>
                <div class="nm2-row"><span class="nm2-label">Type</span><span class="nm2-value" id="nm2Type">-</span></div>
                <div class="nm2-row"><span class="nm2-label">POP</span><span class="nm2-value" id="nm2Pop">-</span></div>
                <div class="nm2-row"><span class="nm2-label">Responded</span><span class="nm2-value" id="nm2Responded">-</span></div>
                <div class="nm2-row"><span class="nm2-label">Avg Delay</span><span class="nm2-value" id="nm2Avg">-</span></div>
                <div class="nm2-row"><span class="nm2-label">Down Time</span><span class="nm2-value" id="nm2Down">-</span></div>
                <h6 class="mt-4 mb-2">Log terakhir</h6>
                <div id="nm2Logs"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('mapCanvas');
    var img    = document.getElementById('mapImg');
    var deviceUrl = @json(route('admin.monitoring.device', ['device' => '__DEVICE_ID__']));

    var naturalW = 0, naturalH = 0;

    img.addEventListener('load', positionMarkers);
    if (img.complete && img.naturalWidth) positionMarkers();

    function positionMarkers() {
        naturalW = img.naturalWidth;
        naturalH = img.naturalHeight;
        if (!naturalW || !naturalH) return;
        canvas.querySelectorAll('.nm2-marker').forEach(function (m) {
            m.style.left = (Number(m.dataset.x) / naturalW * 100) + '%';
            m.style.top  = (Number(m.dataset.y) / naturalH * 100) + '%';
        });
    }

    /* ── Detail popup ── */
    var overlay   = document.getElementById('nm2Overlay');
    var loading   = document.getElementById('nm2Loading');
    var content   = document.getElementById('nm2Content');
    var logsBox   = document.getElementById('nm2Logs');

    function esc(v) {
        return String(v === null || v === undefined ? '' : v)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function fmt(v) { return (v === null || v === undefined || v === '') ? '-' : String(v); }
    function pct(v) {
        if (v === null || v === undefined || v === '') return '-';
        return Number(v).toFixed(2) + '%';
    }
    function normalize(s) {
        s = String(s || '').toLowerCase();
        return (s === 'up' || s === 'down' || s === 'warning') ? s : 'other';
    }

    function renderDetail(d) {
        d = d || {};
        var dev = d.device || d;
        var st  = d.stats || {};

        document.getElementById('nm2Name').textContent = fmt(dev.name);
        document.getElementById('nm2Addr').textContent = fmt(dev.ip_address || dev.ip);
        document.getElementById('nm2LastPoll').textContent = fmt(st.last_poll);
        document.getElementById('nm2Type').textContent = fmt(st.type || dev.type || 'ICMP');
        document.getElementById('nm2Pop').textContent = fmt(dev.pop_name || dev.pop);
        document.getElementById('nm2Responded').textContent = pct(st.responded_pct);
        document.getElementById('nm2Avg').textContent = (st.avg_delay === null || st.avg_delay === '' || st.avg_delay === undefined) ? '-' : (Number(st.avg_delay) + ' ms');
        document.getElementById('nm2Down').textContent = fmt(st.down_time);

        var status = normalize(dev.status);
        var stEl = document.getElementById('nm2Status');
        stEl.className = 'nm2-value nm2-status-badge ' + status;
        stEl.textContent = String(status).toUpperCase();

        var logs = d.logs || [];
        logsBox.innerHTML = logs.length
            ? logs.map(function (l) {
                var s = normalize(l.status);
                return '<div class="nm2-log ' + s + '">' +
                    '<span class="t">' + esc(l.time) + '</span>' +
                    '<span class="s">' + s.toUpperCase() + '</span>' +
                    '<span>' + esc(l.message || '') + '</span>' +
                    '</div>';
            }).join('')
            : (d.error ? '<div class="nm2-log">' + esc(d.error) + '</div>' : '<div class="nm2-log">Belum ada log.</div>');

        loading.style.display = 'none';
        content.style.display = '';
        overlay.classList.add('active');
    }

    canvas.querySelectorAll('.nm2-marker').forEach(function (m) {
        m.addEventListener('click', function () {
            var id = m.dataset.id;
            overlay.classList.add('active');
            loading.style.display = '';
            content.style.display = 'none';

            var url = String(deviceUrl).replace('__DEVICE_ID__', encodeURIComponent(id));
            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(function (res) {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(renderDetail)
                .catch(function () {
                    renderDetail({ device: {}, stats: {}, logs: [], error: 'Data device tidak ditemukan.' });
                });
        });
    });

    function closeDetail() { overlay.classList.remove('active'); }
    document.getElementById('nm2Close').addEventListener('click', closeDetail);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeDetail();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('active')) closeDetail();
    });
});
</script>
@endpush