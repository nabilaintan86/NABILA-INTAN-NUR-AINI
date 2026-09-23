{{--
    KOMPONEN TOPOLOGY JARINGAN (SINGLE SOURCE OF TRUTH)
    Dipakai oleh: admin.monitoring
    Payload data wajib dari NetworkTopologyService agar identik di semua halaman.

    Properti opsional:
      $topology  => payload awal (array hasil NetworkTopologyService::build())
      $endpoint  => URL fetch data (mis. route('admin.monitoring.data')) untuk refresh status
      $deviceEndpoint => URL template detail device (mis. route('admin.monitoring.device', ['device' => '__DEVICE_ID__']))
      $title     => judul tampilan di header map (default RADNET Wireless Network 2025)
--}}
@php
    $topology = $topology ?? null;
    $endpoint = $endpoint ?? null;
    $deviceEndpoint = $deviceEndpoint ?? null;
    $title    = $title ?? 'RADNET Wireless Network 2025';
@endphp

<style>
    /* ===========================================================
       NETWORK MAP — Topology manual mengikuti map RADNET 2025
       - Posisi device manual (bukan graph otomatis)
       - Garis lurus horizontal/vertikal (orthogonal)
       - Device = PNG icon, status = badge kecil
       - Tanpa zoom/pan control
       =========================================================== */

    .nm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }
    .nm-header-title {
        font-size: 15px;
        font-weight: 600;
        color: #0f172a;
    }
    .nm-legend {
        display: flex;
        gap: 14px;
        font-size: 11px;
        color: #64748b;
    }
    .nm-legend span { display: inline-flex; align-items: center; gap: 5px; }
    .nm-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .nm-dot-up      { background: #22c55e; }
    .nm-dot-down    { background: #ef4444; }
    .nm-dot-warning { background: #f59e0b; }
    .nm-dot-unknown { background: #cbd5e1; }
    .nm-legend-line {
        width: 14px;
        height: 0;
        border-top: 2px solid #10b981;
        display: inline-block;
    }
    .nm-legend-line-warn { border-top-color: #f59e0b; }
    .nm-legend-line-down { border-top-color: #ef4444; }

    .nm-refresh {
        font-size: 11px;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .nm-refresh .icon { font-size: 12px; }

    /* Map statis, discroll bila melebihi viewport */
    .topo-wrap {
        overflow-x: auto;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background-color: #fbfdff;
        background-image:
            linear-gradient(rgba(125, 153, 178, .07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(125, 153, 178, .07) 1px, transparent 1px);
        background-size: 24px 24px;
        padding: 6px;
    }
    .topo-svg {
        display: block;
        min-width: 1080px;
        height: auto;
    }

    /* ---- Container group (bounding area) ----
       Kotak tegas sehingga tiap POP terbaca sebagai satu area topology.
       vector-effect agar border POP tetap jelas walau map di-zoom keluar. */
    .topo-area-rect {
        fill: #edf4ef;
        stroke: #7d99b2;
        stroke-width: 2;
        vector-effect: non-scaling-stroke;
        filter: drop-shadow(0 1px 2px rgba(15, 23, 42, .06));
    }
    .topo-area-inner {
        fill: none;
        stroke: #cfe3d8;
        stroke-width: 1;
        vector-effect: non-scaling-stroke;
    }
    .topo-area-band {
        fill: #0f766e;
        pointer-events: none;
    }
    .topo-area-band-label {
        font-size: 13px;
        font-weight: 800;
        fill: #ffffff;
        letter-spacing: .02em;
        pointer-events: none;
    }
    .topo-area-band-count {
        font-size: 10px;
        font-weight: 700;
        fill: #cffaf1;
        text-anchor: end;
        pointer-events: none;
    }
    .topo-edge {
        stroke: #10b981;
        stroke-width: 2;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .topo-edge-down { stroke: #ef4444; }
    .topo-edge-warn { stroke: #f59e0b; }

    .topo-node { cursor: pointer; }
    .topo-node image { pointer-events: none; }
    .topo-node:hover image { opacity: .88; }
    .topo-node-label-name {
        font-size: 10px;
        font-weight: 600;
        fill: #0f172a;
        text-anchor: middle;
        pointer-events: none;
    }
    .topo-node-label-ip {
        font-size: 8px;
        fill: #64748b;
        text-anchor: middle;
        pointer-events: none;
    }

    /* ---- Empty state ---- */
    .nm-empty { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .nm-empty .icon { font-size: 48px; margin-bottom: 8px; }

    /* ---- Detail popup (shown on device click) ---- */
    .nm-detail-overlay {
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
    .nm-detail-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .nm-detail-card {
        background: #fff;
        border-radius: 12px;
        width: 100%;
        max-width: 700px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 18px 50px rgba(15, 23, 42, .28);
        opacity: 0;
        transform: translateY(10px) scale(.97);
        transition: opacity .25s ease, transform .25s ease;
    }
    .nm-detail-overlay.active .nm-detail-card {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    .nm-detail-head {
        position: sticky;
        top: 0;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 20px;
        background: #1e40af;
    }
    .nm-detail-name {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        margin: 0;
        word-break: break-word;
    }
    .nm-detail-close {
        flex: 0 0 auto;
        width: 30px;
        height: 30px;
        border: none;
        background: rgba(255,255,255,0.08);
        border-radius: 6px;
        color: #fff;
        font-size: 22px;
        line-height: 1;
        cursor: pointer;
    }
    .nm-detail-close:hover { color: #fff; background: rgba(255,255,255,0.2); }
    .nm-detail-body {
        padding: 16px 20px 20px;
    }

    /* ---- Loading spinner ---- */
    .nm-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 40px 0;
        color: #64748b;
        font-size: 13px;
    }

    /* ---- Info ringkas ---- */
    .nm-info-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 9px 0;
        font-size: 12.5px;
        border-bottom: 1px solid #f1f5f9;
    }
    .nm-info-row:last-child { border-bottom: none; }
    .nm-info-label { color: #64748b; flex: 0 0 auto; }
    .nm-info-value {
        font-weight: 600;
        color: #0f172a;
        text-align: right;
        word-break: break-word;
    }
    .nm-status-dot {
        display: inline-block;
        width: 9px; height: 9px;
        border-radius: 50%;
        background: #94a3b8;
        margin-right: 6px;
        vertical-align: middle;
    }
    .nm-status-up      .nm-status-dot { background: #22c55e; }
    .nm-status-up      .nm-status-text { color: #16a34a; }
    .nm-status-down    .nm-status-dot { background: #ef4444; }
    .nm-status-down    .nm-status-text { color: #dc2626; }
    .nm-status-warning .nm-status-dot { background: #f59e0b; }
    .nm-status-warning .nm-status-text { color: #d97706; }
    .nm-status-unknown .nm-status-dot { background: #94a3b8; }
    .nm-status-text { font-weight: 700; }

    /* ---- Section ---- */
    .nm-section {
        margin-top: 18px;
    }
    .nm-section-title {
        background: #f1f5f9;
        font-weight: 700;
        font-size: 11.5px;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: #0f172a;
        padding: 8px 12px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    /* ---- Tabel statistics ---- */
    .nm-stat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }
    .nm-stat-table th,
    .nm-stat-table td {
        border: 1px solid #e2e8f0;
        padding: 6px 10px;
        text-align: left;
    }
    .nm-stat-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #64748b;
    }
    .nm-stat-table .nm-stat-subhead th {
        background: #f1f5f9;
        color: #94a3b8;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .03em;
        padding: 4px 10px;
    }

    /* ---- Recent log extract ---- */
    .nm-log-list {
        max-height: 200px;
        overflow-y: auto;
        background: #0f172a;
        border-radius: 8px;
        padding: 8px 12px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 11.5px;
        line-height: 1.7;
        color: #cbd5e1;
    }
    .nm-log-item { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .nm-log-time { color: #94a3b8; }
    .nm-log-badge {
        display: inline-block;
        width: 36px;
        text-align: center;
        font-weight: 700;
        border-radius: 3px;
        padding: 0 2px;
    }
    .nm-log-badge-up      { color: #4ade80; }
    .nm-log-badge-down    { color: #f87171; }
    .nm-log-badge-warning { color: #fbbf24; }
    .nm-log-name    { color: #e2e8f0; font-weight: 600; }
    .nm-log-ip      { color: #94a3b8; }
    .nm-log-msg     { color: #64748b; margin-left: 8px; }
    .nm-log-empty   { color: #64748b; }

    @media (max-width: 640px) {
        .nm-legend { flex-wrap: wrap; gap: 8px; }
        .nm-detail-card { max-width: 100%; }
        .nm-detail-head,
        .nm-detail-body { padding-left: 16px; padding-right: 16px; }
    }
</style>

<div class="nm-header">
    <div class="nm-header-title">{{ $title }}</div>
    <div class="nm-legend">
        <span><span class="nm-dot nm-dot-up"></span> UP</span>
        <span><span class="nm-dot nm-dot-down"></span> DOWN</span>
        <span><span class="nm-dot nm-dot-warning"></span> WARNING</span>
        <span><span class="nm-legend-line"></span> Link</span>
        <span><span class="nm-legend-line nm-legend-line-warn"></span> Link Warning</span>
        <span><span class="nm-legend-line nm-legend-line-down"></span> Link Down</span>
        @if($endpoint)
            <span class="nm-refresh">
                <em class="icon ni ni-reload"></em>
                <span id="lastUpdated">Memuat...</span>
            </span>
        @endif
    </div>
</div>

<div class="topo-wrap">
    <svg id="topo" class="topo-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2246 2524" preserveAspectRatio="xMidYMid meet"></svg>
</div>

<div class="nm-empty" id="topoEmpty" style="display:none;">
    <div class="icon">🗺️</div>
    <p>Belum ada data jaringan untuk ditampilkan.</p>
</div>

<!-- Detail popup device -->
<div class="nm-detail-overlay" id="detailOverlay" role="dialog" aria-modal="true">
    <div class="nm-detail-card">
        <div class="nm-detail-head">
            <h3 class="nm-detail-name" id="detailName">-</h3>
            <button type="button" class="nm-detail-close" id="detailClose" aria-label="Tutup">&times;</button>
        </div>
        <div class="nm-detail-body">

            <div class="nm-loading" id="detailLoading" style="display:none;">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Memuat detail device...
            </div>

            <div id="detailContent" style="display:none;">
                {{-- Ringkasan --}}
                <div class="nm-info">
                    <div class="nm-info-row">
                        <span class="nm-info-label">Hostname</span>
                        <span class="nm-info-value" id="detailHostname">-</span>
                    </div>
                    <div class="nm-info-row">
                        <span class="nm-info-label">Address</span>
                        <span class="nm-info-value" id="detailAddress">-</span>
                    </div>
                    <div class="nm-info-row">
                        <span class="nm-info-label">Last Poll</span>
                        <span class="nm-info-value" id="detailLastPoll">-</span>
                    </div>
                    <div class="nm-info-row">
                        <span class="nm-info-label">Status</span>
                        <span class="nm-info-value nm-status-unknown" id="detailStatus">
                            <span class="nm-status-dot"></span>
                            <span class="nm-status-text" id="detailStatusText">UNKNOWN</span>
                        </span>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="nm-section">
                    <div class="nm-section-title">Statistics</div>
                    <table class="nm-stat-table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Total Polls</th>
                                <th>Responded</th>
                                <th>Missed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="statType">ICMP</td>
                                <td id="statPolls">-</td>
                                <td id="statResponded">-</td>
                                <td id="statMissed">-</td>
                            </tr>
                            <tr class="nm-stat-subhead">
                                <th>Down Time</th>
                                <th>Avg Delay</th>
                                <th>Min Delay</th>
                                <th>Max Delay</th>
                            </tr>
                            <tr>
                                <td id="statDowntime">-</td>
                                <td id="statAvgDelay">-</td>
                                <td id="statMinDelay">-</td>
                                <td id="statMaxDelay">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Additional Information --}}
                <div class="nm-section">
                    <div class="nm-section-title">Additional Information</div>
                    <div class="nm-info">
                        <div class="nm-info-row">
                            <span class="nm-info-label">IP Address</span>
                            <span class="nm-info-value" id="addIP">-</span>
                        </div>
                        <div class="nm-info-row">
                            <span class="nm-info-label">Type</span>
                            <span class="nm-info-value" id="addType">-</span>
                        </div>
                        <div class="nm-info-row">
                            <span class="nm-info-label">POP</span>
                            <span class="nm-info-value" id="addPOP">-</span>
                        </div>
                    </div>
                </div>

                {{-- Recent Log Extract --}}
                <div class="nm-section">
                    <div class="nm-section-title">Recent Log Extract</div>
                    <div class="nm-log-list" id="detailLogs"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    var SVG_NS = 'http://www.w3.org/2000/svg',
        svg = document.getElementById('topo'),
        emptyState = document.getElementById('topoEmpty'),
        lastUpdated = document.getElementById('lastUpdated');

    // Base URL asset icon device (public/images/devices/).
    var iconBase = "{{ asset('images/devices') }}";

    var initialTopo = {!! json_encode($topology) !!};
    var refreshUrl  = {!! json_encode($endpoint) !!};
    var deviceUrlTemplate = {!! json_encode($deviceEndpoint) !!};

    var ICON = 44;
    // Jarak mundur ujung garis dari pusat node (setengah icon ~22-25 + celah),
    // agar ARROWHEAD tampil di sisi target tanpa menutupi icon.
    var EDGE_TRIM = 30;
    // icon_type -> file icon (fallback server.png)
    var iconTypes = { router: 'router', switch: 'switch', server: 'server', pc: 'pc', wifi: 'wifi' };

    function iconFile(type) {
        var t = String(type || '').toLowerCase();
        return (iconTypes[t] || 'server') + '.png';
    }

    function el(tag, attrs, text) {
        var e = document.createElementNS(SVG_NS, tag);
        if (attrs) {
            for (var k in attrs) {
                if (attrs[k] !== null && attrs[k] !== undefined) {
                    e.setAttribute(k, attrs[k]);
                }
            }
        }
        if (text !== undefined && text !== null) {
            e.textContent = text;
        }
        return e;
    }

    function statusColor(status) {
        return status === 'up' ? '#22c55e'
             : status === 'down' ? '#ef4444'
             : status === 'warning' ? '#f59e0b'
             : '#cbd5e1';
    }

    function shortName(name) {
        name = String(name || '');
        return name.length > 14 ? name.slice(0, 12) + '…' : name;
    }

    function ptDist(a, b) {
        var dx = a.x - b.x, dy = a.y - b.y;
        return Math.sqrt(dx * dx + dy * dy);
    }

    // Geser titik pts[i] sejauh `rem` menuju pts[i+1] (trimStart) / pts[i-1] (trimEnd).
    function shiftPoint(pts, i, dir, rem) {
        var p0 = pts[i], p1 = pts[i + dir], len = ptDist(p0, p1) || 1;
        return {
            x: p0.x + (p1.x - p0.x) * (rem / len),
            y: p0.y + (p1.y - p0.y) * (rem / len)
        };
    }

    function trimStart(pts, rem) {
        var i = 0;
        while (i < pts.length - 1 && rem > 0.5) {
            var len = ptDist(pts[i], pts[i + 1]);
            if (len <= rem) { rem -= len; i++; continue; }
            pts[i] = shiftPoint(pts, i, 1, rem);
            break;
        }
        return i;
    }

    function trimEnd(pts, rem) {
        var i = pts.length - 1;
        while (i > 0 && rem > 0.5) {
            var len = ptDist(pts[i - 1], pts[i]);
            if (len <= rem) { rem -= len; i--; continue; }
            pts[i] = shiftPoint(pts, i, -1, rem);
            break;
        }
        return i;
    }

    /**
     * Path orthogonal (hanya segmen horizontal/vertikal).
     * KEDUA ujung dipangkas sejauh trim dari pusat node sehingga garis
     * mulai di tepi icon source dan berakhir sebelum icon target —
     * menyisakan ruang untuk ARROWHEAD (marker-end).
     */
    function orthoPath(a, b, startTrim, endTrim) {
        var dx = b.x - a.x, dy = b.y - a.y;
        var pts = Math.abs(dx) >= Math.abs(dy)
            ? [ {x: a.x, y: a.y}, {x: b.x, y: a.y}, {x: b.x, y: b.y} ]
            : [ {x: a.x, y: a.y}, {x: a.x, y: b.y}, {x: b.x, y: b.y} ];

        // Buang titik duplikat segaris (mis. ay == by atau ax == bx).
        var clean = [];
        for (var i = 0; i < pts.length; i++) {
            var p = pts[i];
            if (!clean.length || clean[clean.length - 1].x !== p.x || clean[clean.length - 1].y !== p.y) {
                clean.push(p);
            }
        }

        // Node terlalu berdekatan (trim akan melahap seluruh path) ->
        // perkecil trim proporsional sehingga masih menyisakan segmen garis.
        var total = 0;
        for (var i = 0; i < clean.length - 1; i++) {
            total += ptDist(clean[i], clean[i + 1]);
        }
        if (startTrim + endTrim > total) {
            var avail = Math.max(total * 0.55, 4);
            var f = (startTrim + endTrim) / avail;
            startTrim = Math.max(2, Math.floor(startTrim / f));
            endTrim = Math.max(2, Math.floor(endTrim / f));
        }

        var s = trimStart(clean, startTrim);
        var e = trimEnd(clean, endTrim);

        // Jaga-jaga bila masih bentrok (path nyaris nol): fallback lurus terpotong.
        if (e <= s) {
            return 'M ' + clean[0].x + ' ' + clean[0].y +
                   ' L ' + clean[clean.length - 1].x + ' ' + clean[clean.length - 1].y;
        }

        var d = [];
        for (var i = s; i <= e; i++) {
            d.push((i === s ? 'M ' : 'L ') + clean[i].x + ' ' + clean[i].y);
        }
        return d.join(' ');
    }

    /**
     * Defs SVG: ARROWHEAD (panah) tiga warna sejalan dengan status link.
     * - topoEdgeArrow      : hijau (normal / up)
     * - topoEdgeArrowWarn  : kuning (warning)
     * - topoEdgeArrowDown  : merah (down / gangguan)
     * markerUnits=userSpaceOnUse -> ukuran panah konsisten dengan icon,
     * orient=auto -> panah mengikuti arah garis (source -> target).
     */
    function buildDefs() {
        var defs = el('defs');
        var arrows = {
            topoEdgeArrow: '#10b981',
            topoEdgeArrowWarn: '#f59e0b',
            topoEdgeArrowDown: '#ef4444'
        };
        Object.keys(arrows).forEach(function (id) {
            var marker = el('marker', {
                id: id,
                viewBox: '0 0 14 14',
                refX: '14',
                refY: '7',
                markerWidth: '14',
                markerHeight: '14',
                orient: 'auto',
                markerUnits: 'userSpaceOnUse'
            });
            marker.appendChild(el('path', {
                d: 'M 0 0 L 14 7 L 0 14 z',
                fill: arrows[id]
            }));
            defs.appendChild(marker);
        });
        return defs;
    }

    function render(payload) {
        if (!payload || !Array.isArray(payload.areas) || !payload.areas.length) {
            svg.style.display = 'none';
            emptyState.style.display = '';
            return;
        }
        svg.style.display = 'block';
        emptyState.style.display = 'none';

        if (payload.viewBox) {
            svg.setAttribute('viewBox', payload.viewBox);
        }
        svg.innerHTML = '';

        // Map device id -> posisi node & status.
        var pos = {};
        payload.areas.forEach(function (area) {
            (area.nodes || []).forEach(function (n) {
                pos[n.id] = { x: n.x, y: n.y };
            });
        });
        var statusById = {};
        payload.areas.forEach(function (area) {
            (area.nodes || []).forEach(function (n) {
                statusById[n.id] = normalizeStatus(n.status);
            });
        });

        function edgeColor(fromId, toId) {
            var s1 = statusById[fromId], s2 = statusById[toId];
            if (s1 === 'down' || s2 === 'down') {
                return { cls: 'topo-edge-down', marker: 'url(#topoEdgeArrowDown)' };
            }
            if (s1 === 'warning' || s2 === 'warning') {
                return { cls: 'topo-edge-warn', marker: 'url(#topoEdgeArrowWarn)' };
            }
            return { cls: '', marker: 'url(#topoEdgeArrow)' };
        }

        // Defs arrowhead (dibuat ulang setiap render / auto-refresh).
        svg.appendChild(buildDefs());

        // 1) Area box (paling belakang) + garis koneksi di atasnya.
        payload.areas.forEach(function (area) {
            var nodes = area.nodes || [];
            if (!nodes.length) return;

            svg.appendChild(el('rect', {
                x: area.x, y: area.y, width: area.w, height: area.h,
                rx: 14, 'class': 'topo-area-rect'
            }));
            svg.appendChild(el('rect', {
                x: area.x + 5, y: area.y + 5,
                width: Math.max(area.w - 10, 0), height: Math.max(area.h - 10, 0),
                rx: 10, 'class': 'topo-area-inner'
            }));
        });

        // 2) Garis koneksi DIRECTIONAL (panah di ujung sisi TARGET via marker-end).
        //    Warna garis ikut status: hijau normal, kuning warning, merah down.
        (payload.edges || []).forEach(function (c) {
            var a = pos[c.from], b = pos[c.to];
            if (!a || !b) return;
            var ec = edgeColor(c.from, c.to);
            svg.appendChild(el('path', {
                d: orthoPath(a, b, EDGE_TRIM, EDGE_TRIM),
                'class': 'topo-edge' + (ec.cls ? ' ' + ec.cls : ''),
                'marker-end': ec.marker
            }));
        });

        // 3) Judul group: header band (pita) di puncak kotak + nama & jumlah perangkat.
        payload.areas.forEach(function (area) {
            var nodes = area.nodes || [];
            if (!nodes.length) return;

            var nameTxt  = String(area.name) || '';
            var countTxt = nodes.length + ' perangkat';
            var bandH = 32;
            // Pita penuh selebar kotak; rect kedua menutup sudut bawah membulat.
            svg.appendChild(el('rect', {
                x: area.x, y: area.y,
                width: area.w, height: bandH,
                rx: 14, 'class': 'topo-area-band'
            }));
            svg.appendChild(el('rect', {
                x: area.x, y: area.y + bandH / 2,
                width: area.w, height: bandH / 2,
                'class': 'topo-area-band'
            }));
            svg.appendChild(el('text', {
                x: area.x + 14, y: area.y + 21, 'class': 'topo-area-band-label'
            }, nameTxt));
            svg.appendChild(el('text', {
                x: area.x + area.w - 14, y: area.y + 21, 'class': 'topo-area-band-count'
            }, countTxt));
        });

        // 4) Node = PNG icon + badge status kecil + label.
        payload.areas.forEach(function (area) {
            (area.nodes || []).forEach(function (n) {
                var size = n.isHub ? ICON + 6 : ICON;
                var g = el('g', { 'class': 'topo-node', transform: 'translate(' + n.x + ',' + n.y + ')' });

                // Area klik transparan (menutupi icon + label).
                // Icon (PNG) & teks sengaja pointer-events:none agar tidak
                // menghalangi klik pada halaman, sehingga tanpa area ini klik
                // nyata di tengah node tidak mengenai <g>. 
                g.appendChild(el('rect', {
                    x: -44,
                    y: -28,
                    width: 88,
                    height: 86,
                    fill: '#000',
                    'fill-opacity': 0,
                    'pointer-events': 'all'
                }));

                // PNG device icon (representasi utama).
                g.appendChild(el('image', {
                    href: iconBase + '/' + iconFile(n.icon),
                    x: -size / 2,
                    y: -size / 2,
                    width: size,
                    height: size
                }));

                // Status: badge kecil di pojok kanan-atas icon.
                g.appendChild(el('circle', {
                    cx: size / 2 - 3,
                    cy: -(size / 2) + 3,
                    r: 6,
                    fill: statusColor(n.status),
                    stroke: '#fff',
                    'stroke-width': 2
                }));

                // Label: nama + IP di bawah icon.
                g.appendChild(el('text', {
                    y: size / 2 + 13, 'class': 'topo-node-label-name'
                }, shortName(n.name)));
                g.appendChild(el('text', {
                    y: size / 2 + 24, 'class': 'topo-node-label-ip'
                }, n.ip || ''));

                g.appendChild(el('title', null, n.name + ' (' + (n.ip || '-') + ')\nPOP: ' + (n.pop || '-') + ' | Tipe: ' + (n.icon || '-') + ' | Status: ' + n.status + ' | Koneksi: ' + n.degree));

                g.addEventListener('click', function () {
                    openDeviceDetail(n.id, {
                        name: n.name,
                        ip_address: n.ip,
                        type: n.icon,
                        pop_name: n.pop,
                        status: n.status,
                        icon_type: n.icon,
                        link_count: n.degree
                    });
                });

                svg.appendChild(g);
            });
        });
    }

    /* ---- Load & auto refresh status ---- */
    function updateTimestamp() {
        if (lastUpdated) {
            lastUpdated.textContent = 'Diperbarui: ' + new Date().toLocaleTimeString('id-ID');
        }
    }

    function fetchAndRender() {
        fetch(refreshUrl, { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json(); })
            .then(function (payload) {
                render(payload);
                updateTimestamp();
            })
            .catch(function () {
                if (lastUpdated) lastUpdated.textContent = 'Gagal memperbarui.';
            });
    }

    if (initialTopo) {
        render(initialTopo);
    }
    if (refreshUrl) {
        if (!initialTopo) {
            fetch(refreshUrl, { headers: { 'Accept': 'application/json' } })
                .then(function (res) { return res.json(); })
                .then(render)
                .catch(function () {
                    if (lastUpdated) lastUpdated.textContent = 'Gagal memuat data.';
                });
        }
        setInterval(fetchAndRender, 30000);
    }
    updateTimestamp();

    /* ===========================================================
       DETAIL POPUP DEVICE
       Data diambil via endpoint read-only detail device.
       Tidak ada ping / update database saat popup dibuka.
       =========================================================== */
    var detailOverlay = document.getElementById('detailOverlay');
    var detailLoading = document.getElementById('detailLoading');
    var detailContent = document.getElementById('detailContent');

    function fmt(value) {
        if (value === null || value === undefined || value === '') return '-';
        return String(value);
    }

    function esc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function pct(value) {
        if (value === null || value === undefined || value === '') return '-';
        return Number(value).toFixed(2) + '%';
    }

    function delay(value) {
        if (value === null || value === undefined || value === '') return '-';
        var n = Number(value);
        return isNaN(n) ? String(value) : (n + ' ms');
    }

    function normalizeStatus(status) {
        var s = String(status || '').toLowerCase();
        return (s === 'up' || s === 'down' || s === 'warning') ? s : 'unknown';
    }

    function statusLabel(status) {
        var map = {
            up:      'Active and responding',
            down:    'Unreachable',
            warning: 'Warning / high latency'
        };
        return map[status] || 'Unknown';
    }

    function renderLogs(logs, dev, error) {
        var box = document.getElementById('detailLogs');
        if (error) {
            box.innerHTML = '<div class="nm-log-empty">' + esc(error) + '</div>';
            return;
        }
        if (!Array.isArray(logs) || !logs.length) {
            box.innerHTML = '<div class="nm-log-empty">Belum ada riwayat log untuk device ini.</div>';
            return;
        }
        var name = dev.name || '';
        var ip   = dev.ip_address || dev.ip || '';
        var html = '';
        logs.forEach(function (l) {
            var s = normalizeStatus(l.status);
            html += '<div class="nm-log-item">' +
                '<span class="nm-log-time">' + esc(l.time) + '</span> ' +
                '<span class="nm-log-badge nm-log-badge-' + s + '">' + s.toUpperCase() + '</span> ' +
                '<span class="nm-log-name">' + esc(name) + '</span> ' +
                '<span class="nm-log-ip">' + esc(ip) + '</span>' +
                (l.message ? '<span class="nm-log-msg">' + esc(l.message) + '</span>' : '') +
                '</div>';
        });
        box.innerHTML = html;
    }

    function renderDetail(d) {
        d = d || {};
        var dev = d.device || d;
        var st  = d.stats || {};

        document.getElementById('detailName').textContent = fmt(dev.name);
        document.getElementById('detailHostname').textContent = fmt(dev.name);
        document.getElementById('detailAddress').textContent = fmt(dev.ip_address || dev.ip);
        document.getElementById('detailLastPoll').textContent = fmt(st.last_poll);

        var status = normalizeStatus(dev.status);
        var statusBox = document.getElementById('detailStatus');
        statusBox.className = 'nm-info-value nm-status-' + status;
        document.getElementById('detailStatusText').textContent = statusLabel(status).toUpperCase();

        document.getElementById('statType').textContent = fmt(st.type || dev.type || dev.icon || 'ICMP');
        document.getElementById('statPolls').textContent = fmt(st.total_polls);
        document.getElementById('statResponded').textContent = pct(st.responded_pct);
        document.getElementById('statMissed').textContent = pct(st.missed_pct);
        document.getElementById('statDowntime').textContent = fmt(st.down_time);
        document.getElementById('statAvgDelay').textContent = delay(st.avg_delay);
        document.getElementById('statMinDelay').textContent = delay(st.min_delay);
        document.getElementById('statMaxDelay').textContent = delay(st.max_delay);

        document.getElementById('addIP').textContent = fmt(dev.ip_address || dev.ip);
        document.getElementById('addType').textContent = fmt(st.type || dev.type || dev.icon);
        document.getElementById('addPOP').textContent = fmt(dev.pop_name || dev.pop);

        renderLogs(d.logs, dev, d.error);

        detailLoading.style.display = 'none';
        detailContent.style.display = '';
        detailOverlay.classList.add('active');
    }

    function openDeviceDetail(id, fallback) {
        // Tampilkan spinner dulu sambil data difetch.
        detailOverlay.classList.add('active');
        detailLoading.style.display = '';
        detailContent.style.display = 'none';

        if (!deviceUrlTemplate || id === undefined || id === null) {
            renderDetail(fallback);
            return;
        }
        var url = String(deviceUrlTemplate).replace('__DEVICE_ID__', encodeURIComponent(id));
        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(renderDetail)
            .catch(function () {
                // Device tidak ditemukan / endpoint error:
                // tampilkan pesan yang jelas, bukan popup kosong.
                renderDetail({
                    device: fallback || {},
                    stats: {},
                    logs: [],
                    error: 'Data device tidak ditemukan.'
                });
            });
    }

    function closeDetail() {
        detailOverlay.classList.remove('active');
    }

    // Tutup popup: tombol X, klik overlay, dan tombol ESC.
    document.getElementById('detailClose').addEventListener('click', closeDetail);
    detailOverlay.addEventListener('click', function (e) {
        if (e.target === detailOverlay) closeDetail();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && detailOverlay.classList.contains('active')) closeDetail();
    });
})();
</script>