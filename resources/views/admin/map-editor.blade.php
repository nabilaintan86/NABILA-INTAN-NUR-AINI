@extends('layouts.admin')

@section('title', 'Map Editor · RADNET NOC')

@push('styles')
<style>
    .me-wrap {
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    /* ── Sidebar ── */
    .me-sidebar {
        flex: 0 0 280px;
        width: 280px;
        max-height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
    }
    .me-sidebar-search {
        padding: 12px;
        border-bottom: 1px solid #eef2f7;
    }
    .me-sidebar-search .form-control {
        font-size: 13px;
        border-color: #e2e8f0;
    }
    .me-device-list {
        overflow-y: auto;
        flex: 1;
        padding: 8px;
    }
    .me-group-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #64748b;
        padding: 8px 10px 4px;
    }
    .me-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        color: #0f172a;
        transition: background .12s ease;
        width: 100%;
        border: 1px solid transparent;
        background: none;
        text-align: left;
    }
    .me-item:hover { background: #f1f5f9; }
    .me-item.active {
        background: #eff6ff;
        border-color: #2563eb;
        box-shadow: 0 0 0 1px #2563eb inset;
    }
    .me-item .me-dot {
        flex: 0 0 auto;
        width: 9px;
        height: 9px;
        border-radius: 50%;
    }
    .me-item .me-dot.is-unpositioned { background: #ef4444; }
    .me-item .me-dot.is-positioned   { background: #22c55e; }
    .me-item .me-name {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .me-item .me-ip {
        font-size: 11px;
        color: #94a3b8;
        font-family: ui-monospace, monospace;
    }
    .me-item-unreset {
        flex: 0 0 auto;
        border: none;
        background: none;
        color: #cbd5e1;
        font-size: 14px;
        line-height: 1;
        padding: 2px;
    }
    .me-item-unreset:hover { color: #dc2626; }

    /* ── Canvas ── */
    .me-stage {
        flex: 1;
        min-width: 0;
        position: relative;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fbfdff;
        overflow: auto;
        max-height: calc(100vh - 200px);
    }
    .me-canvas {
        position: relative;
        display: inline-block;
        min-width: 100%;
        cursor: crosshair;
    }
    .me-canvas img {
        display: block;
        max-width: none;
        user-select: none;
        -webkit-user-drag: none;
    }
    .me-marker {
        position: absolute;
        width: 26px;
        height: 26px;
        transform: translate(-50%, -50%);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #22c55e;
        box-shadow: 0 1px 4px rgba(15, 23, 42, .35);
        z-index: 5;
        transition: transform .1s ease;
    }
    .me-marker img {
        width: 18px;
        height: 18px;
        object-fit: contain;
    }
    .me-marker.selected {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .35);
        transform: translate(-50%, -50%) scale(1.18);
        z-index: 6;
    }
    .me-marker-label {
        position: absolute;
        bottom: -16px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(15, 23, 42, .85);
        color: #fff;
        font-size: 10px;
        line-height: 1;
        padding: 2px 5px;
        border-radius: 4px;
        white-space: nowrap;
        pointer-events: none;
    }
    .me-hint {
        position: sticky;
        bottom: 0;
        left: 0;
        z-index: 20;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 14px;
        background: rgba(15, 23, 42, .88);
        color: #e2e8f0;
        font-size: 12px;
        border-top: 1px solid rgba(255,255,255,.08);
    }
    .me-hint .me-coords {
        font-family: ui-monospace, monospace;
        color: #93c5fd;
    }
    .me-hint input[type=range] { accent-color: #3b82f6; }
</style>
@endpush

@section('content')

<style>
    .me-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }
</style>

<div class="me-head">
    <div class="d-flex align-items-center gap-3">
        <h5 class="title mb-0">Map Editor</h5>
        <span class="badge bg-primary" id="progressBadge">{{ $positioned }} / {{ $total }} device diposisikan</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-danger btn-sm" id="btnResetAll">
            Reset semua
        </button>
        <a href="{{ route('admin.map') }}" class="btn btn-primary btn-sm">
            Selesai → Network Map
        </a>
    </div>
</div>

<div class="me-wrap">

    {{-- SIDEBAR ── --}}
    <aside class="me-sidebar">
        <div class="me-sidebar-search">
            <input
                type="text"
                class="form-control"
                id="meSearch"
                placeholder="Cari device..."
                autocomplete="off"
            >
        </div>

        <div class="me-device-list">
            <div class="me-group-label">Belum diposisikan</div>
            <div id="meListUnpositioned"></div>

            <div class="me-group-label">Sudah diposisikan</div>
            <div id="meListPositioned"></div>

            <div id="meListEmpty" class="text-center py-4 text-soft small" style="display:none;">
                Tidak ada device. <a href="{{ route('admin.kelola-router') }}">Tambah device</a>
            </div>
        </div>
    </aside>

    {{-- CANVAS ── --}}
    <section class="me-stage" id="meStage">
        <div class="me-canvas" id="meCanvas">
            <img src="{{ asset('images/devices/MAP.png') }}" alt="Network Map" id="meMapImg" draggable="false">
        </div>

        <div class="me-hint">
            <span id="meHint">
                Klik device di samping, lalu klik posisinya pada peta.
            </span>
            <span class="me-coords" id="meCoords"></span>
            <span class="d-flex align-items-center gap-2">
                Zoom
                <input type="range" id="meZoom" min="30" max="250" value="100">
                <span id="meZoomVal">100%</span>
            </span>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var devices      = @json($devices);
    var csrfToken    = document.querySelector('meta[name="csrf-token"]').content;
    var saveUrl      = @json(route('admin.map.editor.save'));
    var resetUrl     = @json(route('admin.map.editor.reset'));
    var iconBase     = "{{ asset('images/devices') }}";

    var stage   = document.getElementById('meStage');
    var canvas  = document.getElementById('meCanvas');
    var mapImg  = document.getElementById('meMapImg');
    var search  = document.getElementById('meSearch');
    var badge   = document.getElementById('progressBadge');
    var listU   = document.getElementById('meListUnpositioned');
    var listP   = document.getElementById('meListPositioned');
    var listEmpty = document.getElementById('meListEmpty');
    var hint    = document.getElementById('meHint');
    var coordsEl = document.getElementById('meCoords');
    var zoom    = document.getElementById('meZoom');
    var zoomVal = document.getElementById('meZoomVal');

    var activeDeviceId = null;   // device yang sedang dipilih di sidebar
    var naturalW = 0, naturalH = 0;

    function iconFile(d) {
        var map = { router: 'router.png', server: 'server.png', switch: 'switch.png', wifi: 'wifi.png', pc: 'pc.png' };
        var key = String(d.icon_type || '').toLowerCase();
        return map[key] || 'pc.png';
    }

    function esc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /* ── Render list device di sidebar ── */
    function renderList() {
        var q = search.value.trim().toLowerCase();
        var up = [], p = [];
        devices.forEach(function (d) {
            if (q && d.name.toLowerCase().indexOf(q) === -1
                && String(d.ip_address || '').toLowerCase().indexOf(q) === -1) return;
            (d.map_positioned ? p : up).push(d);
        });
        up.sort(function (a, b) { return a.name.localeCompare(b.name); });
        p.sort(function (a, b) { return a.name.localeCompare(b.name); });

        listU.innerHTML = up.map(itemHtml).join('');
        listP.innerHTML = p.map(itemHtml).join('');
        listEmpty.style.display = (!up.length && !p.length) ? '' : 'none';

        renderMarkers();
        updateBadge();
        attachItemClicks();
    }

    function itemHtml(d) {
        var active = d.id === activeDeviceId ? ' active' : '';
        var resetBtn = d.map_positioned
            ? '<button type="button" class="me-item-unreset" data-reset="' + d.id + '" title="Reset posisi">&times;</button>'
            : '';
        return '<div class="me-item' + active + '" data-id="' + d.id + '">' +
            '<span class="me-dot ' + (d.map_positioned ? 'is-positioned' : 'is-unpositioned') + '"></span>' +
            '<span class="d-flex flex-column" style="min-width:0">' +
                '<span class="me-name">' + esc(d.name) + '</span>' +
                '<span class="me-ip">' + esc(d.ip_address) + '</span>' +
            '</span>' +
            resetBtn +
        '</div>';
    }

    function attachItemClicks() {
        listU.querySelectorAll('.me-item').forEach(function (el) { bindItem(el); });
        listP.querySelectorAll('.me-item').forEach(function (el) { bindItem(el); });
    }

    function bindItem(el) {
        el.addEventListener('click', function () {
            activeDeviceId = Number(el.dataset.id);
            renderList();
            var d = findDevice(activeDeviceId);
            hint.innerHTML = 'Device aktif: <strong>' + esc(d.name) + '</strong> — klik posisinya pada peta.';
        });
        var resetBtn = el.querySelector('[data-reset]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (!confirm('Reset posisi device ini?')) return;
                resetOne(Number(resetBtn.dataset.reset));
            });
        }
    }

    function findDevice(id) {
        for (var i = 0; i < devices.length; i++) if (devices[i].id === id) return devices[i];
        return null;
    }

    /* ── Marker overlay di canvas ── */
    function renderMarkers() {
        canvas.querySelectorAll('.me-marker').forEach(function (m) { m.remove(); });
        devices.forEach(function (d) {
            if (!d.map_positioned || d.pos_x === null || d.pos_y === null) return;
            var el = document.createElement('div');
            el.className = 'me-marker' + (d.id === activeDeviceId ? ' selected' : '');
            el.style.left = (d.pos_x / naturalW * 100) + '%';
            el.style.top  = (d.pos_y / naturalH * 100) + '%';
            el.innerHTML = '<img src="' + iconBase + '/' + iconFile(d) + '" alt="">' +
                '<span class="me-marker-label">' + esc(d.name) + '</span>';
            el.addEventListener('click', function (e) {
                e.stopPropagation();
                activeDeviceId = d.id;
                renderList();
            });
            canvas.appendChild(el);
        });
    }

    function updateBadge() {
        var n = devices.filter(function (d) { return d.map_positioned; }).length;
        badge.textContent = n + ' / ' + devices.length + ' device diposisikan';
    }

    /* ── Klik canvas: simpan posisi device aktif ── */
    canvas.addEventListener('click', function (e) {
        if (activeDeviceId === null || !naturalW || !naturalH) {
            hint.innerHTML = 'Pilih device dulu di panel kiri, lalu klik posisinya pada peta.';
            return;
        }
        var rect = mapImg.getBoundingClientRect();
        var x = Math.round((e.clientX - rect.left) / rect.width * naturalW);
        var y = Math.round((e.clientY - rect.top) / rect.height * naturalH);
        if (x < 0 || y < 0) return;

        coordsEl.textContent = 'Koord: ' + x + ', ' + y;
        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ device_id: activeDeviceId, pos_x: x, pos_y: y })
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (!data.success) throw new Error('save failed');
            var d = findDevice(activeDeviceId);
            d.pos_x = x;
            d.pos_y = y;
            d.map_positioned = true;
            renderList();
            hint.innerHTML = 'Posisi <strong>' + esc(d.name) + '</strong> tersimpan (' + x + ', ' + y + '). ' +
                'Klik lagi untuk menyesuaikan, atau pilih device lain.';
        })
        .catch(function () {
            hint.innerHTML = 'Gagal menyimpan posisi. Coba lagi.';
        });
    });

    canvas.addEventListener('mousemove', function (e) {
        if (!naturalW || !naturalH) return;
        var rect = mapImg.getBoundingClientRect();
        var x = Math.round((e.clientX - rect.left) / rect.width * naturalW);
        var y = Math.round((e.clientY - rect.top) / rect.height * naturalH);
        coordsEl.textContent = 'Koord: ' + Math.max(x, 0) + ', ' + Math.max(y, 0);
    });

    canvas.addEventListener('mouseleave', function () {
        coordsEl.textContent = '';
    });

    /* ── Reset ── */
    function resetRequest(payload) {
        fetch(resetUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify(payload)
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (!data.success) throw new Error('reset failed');
            if (payload.device_id) {
                var d = findDevice(payload.device_id);
                if (d) { d.pos_x = null; d.pos_y = null; d.map_positioned = false; }
            } else {
                devices.forEach(function (d) { d.pos_x = null; d.pos_y = null; d.map_positioned = false; });
                activeDeviceId = null;
            }
            hint.innerHTML = 'Reset posisi selesai.';
            renderList();
        })
        .catch(function () { hint.innerHTML = 'Gagal reset. Coba lagi.'; });
    }

    function resetOne(id) { resetRequest({ device_id: id }); }

    document.getElementById('btnResetAll').addEventListener('click', function () {
        if (!confirm('Reset posisi SEMUA device di peta?')) return;
        resetRequest({});
    });

    /* ── Search ── */
    search.addEventListener('input', renderList);

    /* ── Zoom ── */
    function applyZoom() {
        var z = Number(zoom.value) / 100;
        zoomVal.textContent = Math.round(z * 100) + '%';
        var base = defaultScale();
        mapImg.style.width = Math.round(naturalW * base * z) + 'px';
    }

    function defaultScale() {
        var padding = 40;
        var avail = stage.clientWidth - padding;
        if (!naturalW) return 1;
        return Math.min(1, avail / naturalW);
    }

    zoom.addEventListener('input', applyZoom);
    window.addEventListener('resize', function () {
        var z = Number(zoom.value) / 100;
        mapImg.style.width = Math.round(naturalW * defaultScale() * z) + 'px';
    });

    /* ── Init ── */
    mapImg.addEventListener('load', function () {
        naturalW = naturalH = 0;
        naturalW = mapImg.naturalWidth;
        naturalH = mapImg.naturalHeight;
        applyZoom();
        renderMarkers();
    });
    if (mapImg.complete && mapImg.naturalWidth) {
        naturalW = mapImg.naturalWidth;
        naturalH = mapImg.naturalHeight;
        applyZoom();
        renderMarkers();
    }
    renderList();
});
</script>
@endpush