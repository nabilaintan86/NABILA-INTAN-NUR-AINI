{{--
    KOMPONEN TOPOLOGY JARINGAN WHATSUP GOLD (RADNET WIRELESS NETWORK 2026)
    Dipakai oleh: admin.monitoring (admin.index)
--}}
@php
    $topology            = $topology ?? null;
    $deviceList          = $deviceList ?? [];
    $deviceTypes         = $deviceTypes ?? \App\Helpers\DeviceType::types();
    $protocols           = $protocols ?? \App\Helpers\DeviceType::protocols();
    $networkAreas        = $networkAreas ?? collect();
    $endpoint            = $endpoint ?? route('admin.monitoring.data');
    $deviceEndpoint      = $deviceEndpoint ?? route('admin.monitoring.device', ['device' => '__DEVICE_ID__']);
    $positionEndpoint    = $positionEndpoint ?? route('admin.monitoring.device.position', ['device' => '__DEVICE_ID__']);
    $directionEndpoint   = $directionEndpoint ?? route('admin.monitoring.connection.direction', ['connection' => '__CONN_ID__']);
    $areaBoundsEndpoint  = $areaBoundsEndpoint ?? route('admin.monitoring.area.bounds', ['area' => '__AREA_ID__']);
    $saveLayoutEndpoint  = $saveLayoutEndpoint ?? route('admin.monitoring.map-layout.save');
    $resetLayoutEndpoint = $resetLayoutEndpoint ?? route('admin.monitoring.map-layout.reset');
    $isSuperAdmin        = $isSuperAdmin ?? (auth()->check() && auth()->user()->role === 'super_admin');
    $title               = $title ?? 'Map: RADNET Wireless Network 2026';
@endphp

<style>
    .wug-btn-hdr.btn-add-device { background: #f97316 !important; border-color: #ea580c !important; font-weight: 700; }
    .wug-btn-hdr.btn-add-device:hover { background: #ea580c !important; }
    .wug-btn-hdr.btn-connect-mode.active { background: #f59e0b !important; color: #000 !important; border-color: #d97706 !important; }
    .wug-btn-hdr.btn-custom-toggle.active { background: #f59e0b !important; color: #000 !important; border-color: #d97706 !important; }
    .wug-btn-hdr.btn-save-layout { background: #10b981 !important; border-color: #059669 !important; font-weight: 700; }
    .wug-btn-hdr.btn-save-layout:hover { background: #059669 !important; }

        .wug-map-body {
        display: flex;
        gap: 0;
        align-items: stretch;
        height: calc(100vh - 225px);
        max-height: 900px;
        min-height: 540px;
        position: relative;
    }

    @keyframes wugPulse { 0% { filter: drop-shadow(0 0 4px #2563eb); } 50% { filter: drop-shadow(0 0 16px #3b82f6) brightness(1.3); } 100% { filter: drop-shadow(0 0 4px #2563eb); } }
    .topo-node.wug-node-pulse { animation: wugPulse .8s ease-in-out infinite; }
    .topo-node.wug-node-selected image { filter: brightness(1.3) drop-shadow(0 0 12px #f59e0b); }

    .wug-map-container { position: relative; background: #cbd5e1; border: 2px solid #2563eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.14); display: flex; flex-direction: column; }
    .wug-map-container.is-fullscreen { position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; z-index: 999999 !important; border-radius: 0 !important; border: none !important; }
    .wug-map-header { background: linear-gradient(180deg,#0066cc 0%,#004c99 100%); color: #fff; padding: 8px 16px; display: flex; align-items: center; justify-content: space-between; user-select: none; flex-shrink: 0; border-bottom: 1px solid #003d80; }
    .wug-map-title { font-size: 15px; font-weight: 700; letter-spacing: .3px; display: flex; align-items: center; gap: 8px; color: #fff; }
    .wug-header-actions { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .wug-btn-hdr { background: rgba(255,255,255,.18); color: #fff; border: 1px solid rgba(255,255,255,.4); padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 4px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: all .15s; }
    .wug-btn-hdr:hover { background: rgba(255,255,255,.32); color: #fff; border-color: #fff; }
    .wug-canvas-viewport {
        position: relative;
        flex: 1 1 auto;
        min-width: 0;
        min-height: 0;
        height: auto;
        background: #d4d8de;
        overflow: hidden;
        cursor: grab;
    }
    .wug-map-container.is-fullscreen .wug-canvas-viewport { height: calc(100vh - 44px) !important; max-height: none !important; }
    .wug-canvas-viewport:active { cursor: grabbing; }

    .topo-svg { display: block; width: 100%; height: 100%; transform-origin: 0 0; }
    .topo-area-group { transition: opacity .15s; }
    .topo-area-box { fill: #f4f6f8; stroke-width: 2.5; vector-effect: non-scaling-stroke; filter: drop-shadow(0 2px 5px rgba(0,0,0,.08)); }
    .topo-area-header-bar { cursor: move; fill: rgba(0,0,0,.04); }
    .topo-area-header-bar:hover { fill: rgba(37,99,235,.12); }
    .topo-area-title { font-family: 'Segoe UI',sans-serif; font-size: 15px; font-weight: 800; user-select: none; pointer-events: none; }
    .topo-area-resize-handle { cursor: nwse-resize; fill: #2563eb; stroke: #fff; stroke-width: 2; pointer-events: all; }
    .topo-area-resize-handle:hover, .topo-area-resize-handle.active-hover { fill: #ef4444; stroke: #fff; stroke-width: 3.5; }
    .topo-edge-line { fill: none; stroke-linecap: round; stroke-linejoin: round; cursor: pointer; }
    .topo-edge-line:hover { stroke-width: 5 !important; filter: drop-shadow(0 0 5px #f59e0b); }
    .topo-node { cursor: grab; user-select: none; }
    .topo-node.is-dragging { cursor: grabbing; opacity: .85; }
    .topo-node:hover image { filter: brightness(1.12); }
    .topo-node-name-bg { fill: #fff; stroke: #1e293b; stroke-width: 1; vector-effect: non-scaling-stroke; }
    .topo-node-name-text { font-family: 'Segoe UI',sans-serif; font-size: 11px; font-weight: 700; fill: #0f172a; text-anchor: middle; dominant-baseline: middle; pointer-events: none; }
    .topo-node-ip-text { font-family: 'Segoe UI',sans-serif; font-size: 9.5px; font-weight: 600; fill: #475569; text-anchor: middle; pointer-events: none; }

    @keyframes wugPulseFast { 0% { transform: scale(1); opacity: .95; } 50% { transform: scale(1.18); opacity: 1; filter: drop-shadow(0 0 10px #ef4444); } 100% { transform: scale(1); opacity: .95; } }
    .outage-blast { transform-origin: 0 -2px; animation: wugPulseFast 1.4s infinite ease-in-out; }

    .wug-floating-controls { position: absolute; bottom: 20px; right: 20px; display: flex; flex-direction: column; gap: 6px; background: rgba(255,255,255,.92); backdrop-filter: blur(4px); padding: 6px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,.15); z-index: 50; }
    .wug-btn-icon { width: 34px; height: 34px; border: 1px solid #cbd5e1; background: #fff; border-radius: 6px; color: #334155; font-size: 16px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .wug-btn-icon:hover { background: #0066cc; color: #fff; border-color: #0066cc; }

    .wug-floating-exit-fs { display: none; position: absolute; top: 14px; right: 14px; z-index: 100; background: rgba(15,23,42,.85); color: #fff; border: 1px solid rgba(255,255,255,.3); padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; }
    .wug-map-container.is-fullscreen .wug-floating-exit-fs { display: inline-flex; align-items: center; gap: 6px; }

    .wug-custom-drawer { position: absolute; top: 0; left: 0; width: 320px; height: 100%; background: rgba(255,255,255,.97); box-shadow: 4px 0 20px rgba(0,0,0,.18); border-right: 1px solid #cbd5e1; z-index: 60; transform: translateX(-100%); transition: transform .25s; display: flex; flex-direction: column; }
    .wug-custom-drawer.is-open { transform: translateX(0); }
    .wug-drawer-header { background: #0f172a; color: #fff; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; }
    .wug-drawer-body { padding: 16px; overflow-y: auto; flex: 1 1 auto; font-size: 13px; }
    .wug-slider-group { margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; }
    .wug-slider-group label { font-weight: 700; color: #1e293b; display: flex; justify-content: space-between; margin-bottom: 4px; }

    .wug-tooltip { position: absolute; display: none; background: rgba(15,23,42,.95); color: #fff; padding: 5px 10px; font-size: 11.5px; border-radius: 4px; pointer-events: none; white-space: nowrap; z-index: 9999; }

    .nm-detail-overlay { position: fixed; inset: 0; background: rgba(15,23,42,.65); z-index: 9999999; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; visibility: hidden; pointer-events: none; transition: opacity .2s, visibility .2s; }
    .nm-detail-overlay.active { opacity: 1; visibility: visible; pointer-events: auto; }
    .nm-detail-card { background: #fff; border-radius: 10px; width: 100%; max-width: 680px; max-height: 88vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
    .nm-detail-head { position: sticky; top: 0; z-index: 1; display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; background: #0066cc; color: #fff; }
    .nm-detail-name { font-size: 17px; font-weight: 700; color: #fff; margin: 0; }
    .nm-detail-close { width: 32px; height: 32px; border: none; background: rgba(255,255,255,.15); border-radius: 6px; color: #fff; font-size: 22px; cursor: pointer; }
    .nm-detail-body { padding: 18px 20px; }
    .nm-info-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .nm-info-label { color: #64748b; font-weight: 500; }
    .nm-info-value { font-weight: 700; color: #0f172a; }
    .nm-stat-table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 8px; }
    .nm-stat-table th, .nm-stat-table td { border: 1px solid #e2e8f0; padding: 6px 10px; }
    .nm-stat-table th { background: #f8fafc; font-weight: 700; color: #475569; }
    .nm-log-list { max-height: 180px; overflow-y: auto; background: #0f172a; border-radius: 6px; padding: 8px 12px; font-family: monospace; font-size: 11.5px; color: #cbd5e1; margin-top: 8px; }
</style>

{{-- CONTAINER --}}
<div class="wug-map-container" id="wugMapWrapper">
    {{-- HEADER --}}
    <div class="wug-map-header">
        <div class="wug-map-title">
            <em class="icon ni ni-map-pin"></em>
            <span>{{ $title }}</span>
            <span class="badge bg-light text-dark fs-11px ms-1">WhatsUp Gold Standard</span>
            @if($isSuperAdmin)
                <span class="badge bg-warning text-dark fs-11px fw-bold" id="badgeLayoutStatus">
                    <em class="icon ni ni-shield-check me-1"></em>Super Admin (Full Custom)
                </span>
            @endif
        </div>
        <div class="wug-header-actions">
            @if($isSuperAdmin)
                <button type="button" class="wug-btn-hdr btn-add-device" id="btnAddDevice" title="Tambah Perangkat Baru">
                    <em class="icon ni ni-plus"></em><span>Tambah Device</span>
                </button>
                <button type="button" class="wug-btn-hdr btn-connect-mode" id="btnConnectMode" title="Klik satu perangkat lalu klik perangkat lain untuk membuat relasi">
                    <em class="icon ni ni-link"></em><span>Hubungkan</span>
                </button>
            @endif
        </div>
    </div>

    {{-- MAP BODY: PANEL KIRI + CANVAS --}}
    <div class="wug-map-body" id="wugMapBody">

        @include('admin.components.topology-device-panel', [
            'deviceList'     => $deviceList ?? [],
            'deviceTypes'    => $deviceTypes ?? [],
            'protocols'      => $protocols ?? [],
            'areaOptions'    => $networkAreas ?? collect(),
            'isSuperAdmin'   => $isSuperAdmin,
            'deviceIconBase' => asset('images/devices'),
        ])

        {{-- CANVAS VIEWPORT --}}
        <div class="wug-canvas-viewport" id="wugViewport">
            <button type="button" class="wug-floating-exit-fs" id="btnFloatingExitFs">
                <em class="icon ni ni-shrink"></em> Exit Fullscreen (Esc)
            </button>

            {{-- DRAWER KUSTOMISASI --}}
            <div class="wug-custom-drawer" id="wugCustomDrawer">
                <div class="wug-drawer-header">
                    <div class="fw-bold"><em class="icon ni ni-sliders me-1"></em> Pengaturan Map 2026</div>
                    <button type="button" class="btn btn-sm btn-icon text-white" id="btnCloseCustomDrawer">&times;</button>
                </div>
                <div class="wug-drawer-body">
                    <h6 class="text-primary fw-bold text-uppercase mb-2">1. Jarum Panah</h6>
                    <div class="wug-slider-group">
                        <label><span>Jarak Jarum</span><span id="valNeedleClearance" class="text-primary">28 px</span></label>
                        <input type="range" class="form-range" id="rangeNeedleClearance" min="10" max="65" step="1" value="28">
                    </div>
                    <div class="wug-slider-group">
                        <label><span>Ukuran Kepala Jarum</span><span id="valNeedleSize" class="text-primary">7 px</span></label>
                        <input type="range" class="form-range" id="rangeNeedleSize" min="4" max="16" step="1" value="7">
                    </div>
                    <div class="wug-slider-group">
                        <label><span>Tebal Garis</span><span id="valNeedleWidth" class="text-primary">2.2 px</span></label>
                        <input type="range" class="form-range" id="rangeNeedleWidth" min="1.0" max="5.5" step="0.2" value="2.2">
                    </div>

                    <h6 class="text-primary fw-bold text-uppercase mb-2 mt-4">2. Pembatas Router</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-1">Pilih Area:</label>
                        <select class="form-select form-select-sm" id="selectCustomArea">
                            <option value="">-- Pilih Area --</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-2" id="boxAreaSizeControls" style="display:none;">
                        <div class="col-6"><label class="small fw-bold">W:</label><input type="number" class="form-control form-control-sm" id="inputAreaW" min="80" step="10"></div>
                        <div class="col-6"><label class="small fw-bold">H:</label><input type="number" class="form-control form-control-sm" id="inputAreaH" min="80" step="10"></div>
                    </div>

                    @if($isSuperAdmin)
                        <div class="mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-success btn-sm w-100 fw-bold" onclick="triggerSaveFullLayout()">
                                <em class="icon ni ni-save me-1"></em> Simpan Kustomisasi
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- SVG --}}
            <svg id="topo" class="topo-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1348 1118" preserveAspectRatio="xMidYMid meet">
                <defs id="topoDefs"></defs>
                <g id="topoTransformGroup"></g>
            </svg>

            {{-- ZOOM CONTROLS --}}
            <div class="wug-floating-controls">
                <button type="button" class="wug-btn-icon" id="btnZoomIn" title="Zoom In"><em class="icon ni ni-plus"></em></button>
                <button type="button" class="wug-btn-icon" id="btnZoomOut" title="Zoom Out"><em class="icon ni ni-minus"></em></button>
                <button type="button" class="wug-btn-icon" id="btnZoomReset" title="Reset View"><em class="icon ni ni-reload"></em></button>
            </div>

            <div class="wug-tooltip" id="wugTooltip"></div>
        </div>
    </div>
</div>

{{-- MODAL ARAH PANAH --}}
<div class="modal fade" id="modalEdgeDirection" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title text-white" id="modalEdgeTitle">Arah Panah</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3">
                <div class="text-center mb-3">
                    <div class="text-muted small">Sambungan:</div>
                    <div class="fw-bold text-dark"><span id="modalEdgeFrom">-</span> <span class="text-primary px-1" id="modalEdgeSymbol">&rarr;</span> <span id="modalEdgeTo">-</span></div>
                </div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm text-start" onclick="setConnectionDirection('forward')"><strong>&rarr; Forward</strong></button>
                    <button type="button" class="btn btn-outline-info btn-sm text-start" onclick="setConnectionDirection('reverse')"><strong>&larr; Reverse</strong></button>
                    <button type="button" class="btn btn-outline-primary btn-sm text-start" onclick="setConnectionDirection('bidirectional')"><strong>&harr; Dua Arah</strong></button>
                    <button type="button" class="btn btn-outline-secondary btn-sm text-start" onclick="setConnectionDirection('none')"><strong>&mdash; Tanpa Panah</strong></button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- POPUP DETAIL DEVICE --}}
<div class="nm-detail-overlay" id="detailOverlay" role="dialog">
    <div class="nm-detail-card">
        <div class="nm-detail-head">
            <h3 class="nm-detail-name" id="detailName">-</h3>
            <div class="d-flex align-items-center gap-2">
                @if($isSuperAdmin)
                    <button type="button" class="btn btn-warning btn-sm py-0 px-2 fw-bold" id="btnDetailEditDevice" style="font-size:11.5px; height:28px;">
                        <em class="icon ni ni-edit me-1"></em> Edit
                    </button>
                    <button type="button" class="btn btn-danger btn-sm py-0 px-2 fw-bold" id="btnDetailDeleteDevice" style="font-size:11.5px; height:28px;">
                        <em class="icon ni ni-trash me-1"></em> Hapus
                    </button>
                @endif
                <button type="button" class="nm-detail-close" id="detailClose">&times;</button>
            </div>
        </div>
        <div class="nm-detail-body">
            <div id="detailLoading" style="display:none; text-align:center; padding:25px;">
                <span class="spinner-border text-primary"></span>
                <p class="mt-2 text-muted">Memuat...</p>
            </div>
            <div id="detailContent">
                <div class="nm-info-row"><span class="nm-info-label">Hostname</span><span class="nm-info-value" id="detailHostname">-</span></div>
                <div class="nm-info-row"><span class="nm-info-label">IP Address</span><span class="nm-info-value" id="detailAddress">-</span></div>
                <div class="nm-info-row"><span class="nm-info-label">Area / POP</span><span class="nm-info-value" id="detailPop">-</span></div>
                <div class="nm-info-row"><span class="nm-info-label">Status</span><span class="nm-info-value" id="detailStatusText">-</span></div>
                <div class="nm-info-row"><span class="nm-info-label">Last Poll</span><span class="nm-info-value" id="detailLastPoll">-</span></div>
                <h6 class="mt-3 mb-1 text-uppercase fw-bold text-muted small">Statistics</h6>
                <table class="nm-stat-table">
                    <thead><tr><th>Protocol</th><th>Total Polls</th><th>Responded</th><th>Missed</th></tr></thead>
                    <tbody>
                        <tr><td id="statType">-</td><td id="statPolls">-</td><td id="statResponded">-</td><td id="statMissed">-</td></tr>
                        <tr><th>Down Time</th><th>Avg Delay</th><th>Min Delay</th><th>Max Delay</th></tr>
                        <tr><td id="statDowntime">-</td><td id="statAvgDelay">-</td><td id="statMinDelay">-</td><td id="statMaxDelay">-</td></tr>
                    </tbody>
                </table>
                <h6 class="mt-3 mb-1 text-uppercase fw-bold text-muted small">Recent Log Extract</h6>
                <div class="nm-log-list" id="detailLogs">Belum ada riwayat log.</div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL HELP --}}
<div class="modal fade" id="modalWugHelp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Panduan WhatsUp Gold 2026</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">&bull; <strong>Resize Area:</strong> Tarik handle di pojok kanan-bawah kotak area.</li>
                    <li class="mb-2">&bull; <strong>Jarak Jarum:</strong> Buka tombol "Kustomisasi Map" untuk slider.</li>
                    <li class="mb-2">&bull; <strong>Drag & Drop:</strong> Geser bebas setiap router.</li>
                    <li class="mb-2">&bull; <strong>Simpan Layout:</strong> Super Admin bisa simpan permanen.</li>
                    <li class="mb-2">&bull; <strong>Tambah Device:</strong> Klik tombol oranye di header.</li>
                    <li class="mb-2">&bull; <strong>Hubungkan:</strong> Klik tombol lalu klik 2 device.</li>
                </ul>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Mengerti</button></div>
        </div>
    </div>
</div>

<script>
(function () {
    var SVG_NS = 'http://www.w3.org/2000/svg';
    var svg = document.getElementById('topo');
    var defs = document.getElementById('topoDefs');
    var gRoot = document.getElementById('topoTransformGroup');
    var viewport = document.getElementById('wugViewport');
    var mapWrapper = document.getElementById('wugMapWrapper');
    var tooltip = document.getElementById('wugTooltip');

    var iconBase = "{{ asset('images/devices') }}";
    var initialTopo = {!! json_encode($topology) !!};
    var refreshUrl  = {!! json_encode($endpoint) !!};
    var deviceUrlTemplate = {!! json_encode($deviceEndpoint) !!};
    var positionUrlTemplate = {!! json_encode($positionEndpoint) !!};
    var directionUrlTemplate = {!! json_encode($directionEndpoint) !!};
    var areaBoundsUrlTemplate = {!! json_encode($areaBoundsEndpoint) !!};
    var saveLayoutUrl = {!! json_encode($saveLayoutEndpoint) !!};
    var resetLayoutUrl = {!! json_encode($resetLayoutEndpoint) !!};
    var isSuperAdmin = {!! json_encode($isSuperAdmin) !!};
    var csrfToken = "{{ csrf_token() }}";

    var ICON = 44;
    var currentTopo = initialTopo;
    var currentArrowSettings = (initialTopo && initialTopo.arrowSettings) ? initialTopo.arrowSettings : { clearance: 28, needle_size: 7, stroke_width: 2.2 };
    var currentAreas = [], currentPos = {}, nodeMap = {}, activeEdgeConnection = null;

    var isConnectMode = false, connectSourceNode = null;
    var currentDetailDeviceId = null;
    var btnConnectMode = document.getElementById('btnConnectMode');
    var btnAddDevice = document.getElementById('btnAddDevice');
    var btnDetailEditDevice = document.getElementById('btnDetailEditDevice');

    function focusNode(nodeId) {
        var n = nodeMap[nodeId]; if (!n) return;
        var rect = viewport.getBoundingClientRect();
        scale = Math.max(scale, 1.15);
        panX = (rect.width / 2) - (n.x * scale);
        panY = (rect.height / 2) - (n.y * scale);
        applyTransform();
        var g = document.getElementById('node-g-' + nodeId);
        if (g) { g.classList.add('wug-node-pulse'); setTimeout(function () { g.classList.remove('wug-node-pulse'); }, 2600); }
    }


    /**
     * Cari area (POP) yang mengandung koordinat x,y.
     * Kalau ada beberapa area yang overlap, pilih yang paling kecil.
     * Return: { id, name, x, y, w, h } atau null.
     */
    function areaAt(x, y) {
        var found = null;
        (currentAreas || []).forEach(function (ar) {
            if (x >= ar.x && x <= ar.x + ar.w && y >= ar.y && y <= ar.y + ar.h) {
                if (!found || (ar.w * ar.h) < (found.w * found.h)) {
                    found = ar;
                }
            }
        });
        return found;
    }

    function setConnectMode(active) {
        isConnectMode = !!active;
        connectSourceNode = null;
        if (btnConnectMode) btnConnectMode.classList.toggle('active', isConnectMode);
        document.querySelectorAll('.topo-node.wug-node-selected').forEach(function (el) { el.classList.remove('wug-node-selected'); });
        if (isConnectMode) showToast('Mode Hubungkan: Klik device sumber, lalu device tujuan.');
    }

    if (btnConnectMode) btnConnectMode.addEventListener('click', function () { setConnectMode(!isConnectMode); });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isConnectMode) setConnectMode(false);
    });

    if (btnAddDevice) {
        btnAddDevice.addEventListener('click', function () {
            if (window.WugDevicePanel && window.WugDevicePanel.openAdd) {
                window.WugDevicePanel.openAdd();
            } else {
                var m = document.getElementById('wugModalAddDevice');
                if (m) bootstrap.Modal.getOrCreateInstance(m).show();
            }
        });
    }

    if (btnDetailEditDevice) {
        btnDetailEditDevice.addEventListener('click', function () {
            if (currentDetailDeviceId) {
                detailOverlay.classList.remove('active');
                if (window.WugDevicePanel && window.WugDevicePanel.openEdit) {
                    window.WugDevicePanel.openEdit(currentDetailDeviceId);
                }
            }
        });
    }

    // Pan & Zoom
    var scale = 1.0, panX = 0, panY = 0, isPanning = false, startX = 0, startY = 0;

    function getSvgPoint(evt) {
        var pt = svg.createSVGPoint();
        pt.x = evt.clientX; pt.y = evt.clientY;
        return pt.matrixTransform(gRoot.getScreenCTM().inverse());
    }

    var isDraggingNode = false, draggedNode = null, draggedEl = null;
    var dragNodeOffset = { x: 0, y: 0 }, dragNodeInitialPos = { x: 0, y: 0 }, hasDraggedNode = false;

    var isDraggingArea = false, draggedArea = null, dragAreaOffset = { x: 0, y: 0 };
    var dragAreaInitialNodePos = {};

    var isResizingArea = false, resizedArea = null;
    var resizeStart = { x: 0, y: 0, initialW: 0, initialH: 0 };

    var areaColors = {
        'Plaza BRI':    { border: '#8b1e1e', title: '#8b1e1e' },
        'Bumi Mandiri': { border: '#0033cc', title: '#0033cc' },
        'Intiland':     { border: '#1a8024', title: '#1a8024' },
        'POP YAPALIS':  { border: '#0000ff', title: '#0000ff' },
        'POP Yapalis':  { border: '#0000ff', title: '#0000ff' },
        'POP SMK2':     { border: '#cc0000', title: '#cc0000' },
        'POP SMKN 1':   { border: '#7b1fa2', title: '#7b1fa2' },
        'POP Vuri':     { border: '#9c27b0', title: '#9c27b0' },
        'POP SMK-YPM':  { border: '#001f66', title: '#001f66' },
        'POP SMK8':     { border: '#00838f', title: '#00838f' },
        'POP Rajasa':   { border: '#00838f', title: '#00838f' },
        'POP Sham FM':  { border: '#8b1e1e', title: '#8b1e1e' },
        'EPON-BU':      { border: '#8b1e1e', title: '#8b1e1e' },
        'POP Gresik':   { border: '#6d4c41', title: '#6d4c41' },
        'GRUP HIJAU':   { border: '#1a8024', title: '#1a8024' },
        'Hotel Bumi':   { border: '#1a8024', title: '#1a8024' }
    };

    var iconMap = { router: 'router.png', switch: 'switch.png', olt: 'switch.png', epon: 'router.png', server: 'server.png', pc: 'pc.png', other: 'pc.png', wifi: 'wifi.png', access_point: 'wifi.png' };

    function getIconFile(type) {
        var t = String(type || '').toLowerCase().replace(/[_ ]/g, '_');
        return iconMap[t] || 'server.png';
    }

    function createSvgEl(tag, attrs, text) {
        var el = document.createElementNS(SVG_NS, tag);
        if (attrs) for (var k in attrs) if (attrs[k] !== null && attrs[k] !== undefined) el.setAttribute(k, attrs[k]);
        if (text !== undefined && text !== null) el.textContent = text;
        return el;
    }

    function starburstPath(cx, cy, rO, rI, pts) {
        pts = pts || 16; var d = '', step = Math.PI / pts;
        for (var i = 0; i < 2 * pts; i++) {
            var r = (i % 2 === 0) ? rO : rI, a = i * step - Math.PI / 2;
            d += (i === 0 ? 'M ' : 'L ') + (cx + r * Math.cos(a)).toFixed(1) + ' ' + (cy + r * Math.sin(a)).toFixed(1) + ' ';
        }
        return d + 'Z';
    }

    function showToast(msg, isError) {
        var ex = document.getElementById('wugToast'); if (ex) ex.remove();
        var t = document.createElement('div');
        t.id = 'wugToast';
        t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:999999;padding:8px 18px;border-radius:20px;font-size:13px;font-weight:700;color:#fff;background:' + (isError ? '#dc2626' : '#059669') + ';box-shadow:0 4px 14px rgba(0,0,0,0.3);';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = '0'; t.style.transition = 'opacity .4s'; setTimeout(function () { t.remove(); }, 400); }, 3000);
    }

    function setupDefs() {
        defs.innerHTML = '';
        var nSize = currentArrowSettings.needle_size || 7;
        var mEndUp = createSvgEl('marker', { id: 'topoEdgeArrow', viewBox: '0 0 14 14', refX: '12', refY: '7', markerWidth: nSize, markerHeight: nSize, orient: 'auto' });
        mEndUp.appendChild(createSvgEl('path', { d: 'M 1 2 L 13 7 L 1 12 z', fill: '#16a34a' })); defs.appendChild(mEndUp);

        var mStartUp = createSvgEl('marker', { id: 'topoEdgeArrowStart', viewBox: '0 0 14 14', refX: '2', refY: '7', markerWidth: nSize, markerHeight: nSize, orient: 'auto' });
        mStartUp.appendChild(createSvgEl('path', { d: 'M 13 2 L 1 7 L 13 12 z', fill: '#16a34a' })); defs.appendChild(mStartUp);

        var mEndDown = createSvgEl('marker', { id: 'topoEdgeArrowDown', viewBox: '0 0 14 14', refX: '12', refY: '7', markerWidth: nSize, markerHeight: nSize, orient: 'auto' });
        mEndDown.appendChild(createSvgEl('path', { d: 'M 1 2 L 13 7 L 1 12 z', fill: '#dc2626' })); defs.appendChild(mEndDown);

        var mStartDown = createSvgEl('marker', { id: 'topoEdgeArrowStartDown', viewBox: '0 0 14 14', refX: '2', refY: '7', markerWidth: nSize, markerHeight: nSize, orient: 'auto' });
        mStartDown.appendChild(createSvgEl('path', { d: 'M 13 2 L 1 7 L 13 12 z', fill: '#dc2626' })); defs.appendChild(mStartDown);
    }

    function computeEdgePath(a, b, clearance) {
        var dx = b.x - a.x, dy = b.y - a.y, dist = Math.sqrt(dx * dx + dy * dy), trim = clearance || 28;
        if (dist > (trim * 2)) {
            var sx = a.x + (dx / dist) * trim, sy = a.y + (dy / dist) * trim;
            var ex = b.x - (dx / dist) * trim, ey = b.y - (dy / dist) * trim;
            return 'M ' + sx.toFixed(1) + ' ' + sy.toFixed(1) + ' L ' + ex.toFixed(1) + ' ' + ey.toFixed(1);
        }
        return 'M ' + a.x + ' ' + a.y + ' L ' + b.x + ' ' + b.y;
    }

    function updateEdgesForNode(nodeId) {
        if (!currentTopo || !Array.isArray(currentTopo.edges)) return;
        currentTopo.edges.forEach(function (c) {
            if (c.from === nodeId || c.to === nodeId) {
                var p = document.getElementById('edge-path-' + (c.id || (c.from + '-' + c.to)));
                if (p) { var a = currentPos[c.from], b = currentPos[c.to]; if (a && b) p.setAttribute('d', computeEdgePath(a, b, currentArrowSettings.clearance)); }
            }
        });
    }

    function render(payload) {
        if (!payload || !Array.isArray(payload.areas) || !payload.areas.length) return;
        currentTopo = payload;
        if (payload.arrowSettings) { currentArrowSettings = payload.arrowSettings; syncDrawerInputs(); }
        if (payload.viewBox) svg.setAttribute('viewBox', payload.viewBox);
        setupDefs();
        gRoot.innerHTML = '';
        currentPos = {}; nodeMap = {}; currentAreas = payload.areas;

        var selectAreaEl = document.getElementById('selectCustomArea');
        if (selectAreaEl) {
            var prevVal = selectAreaEl.value;
            selectAreaEl.innerHTML = '<option value="">-- Pilih Area --</option>';
            payload.areas.forEach(function (ar) {
                var opt = document.createElement('option');
                opt.value = ar.name; opt.textContent = ar.name + ' (' + ar.w + 'x' + ar.h + 'px)';
                selectAreaEl.appendChild(opt);
            });
            if (prevVal) selectAreaEl.value = prevVal;
        }

        payload.areas.forEach(function (area) {
            (area.nodes || []).forEach(function (n) { currentPos[n.id] = { x: n.x, y: n.y }; nodeMap[n.id] = n; });
        });

        payload.areas.forEach(function (area) {
            var cfg = areaColors[area.name] || { border: '#1a8024', title: '#1a8024' };
            var borderColor = area.border_color || cfg.border;
            var bgColor = area.bg_color || '#f8fafc';
            var titleColor = area.title_color || cfg.title;
            var safeName = String(area.name || '').replace(/[^a-zA-Z0-9_-]/g, '_');

            var areaGroup = createSvgEl('g', { id: 'area-group-' + safeName, 'class': 'topo-area-group' });
            areaGroup.appendChild(createSvgEl('rect', { id: 'area-rect-' + safeName, x: area.x, y: area.y, width: area.w, height: area.h, rx: 10, 'class': 'topo-area-box', stroke: borderColor, fill: bgColor }));

            var dragBar = createSvgEl('rect', { id: 'area-header-' + safeName, x: area.x, y: area.y, width: area.w, height: 34, rx: 10, 'class': 'topo-area-header-bar', fill: 'rgba(0,0,0,.04)', cursor: 'move' });
            dragBar.addEventListener('mousedown', function (e) {
                if (e.button !== 0) return;
                e.stopPropagation(); e.preventDefault();
                isDraggingArea = true; draggedArea = area;
                var pt = getSvgPoint(e);
                dragAreaOffset = { x: pt.x - area.x, y: pt.y - area.y };
                dragAreaInitialNodePos = {};
                (area.nodes || []).forEach(function (n) { dragAreaInitialNodePos[n.id] = { relX: n.x - area.x, relY: n.y - area.y }; });
            });
            areaGroup.appendChild(dragBar);
            areaGroup.appendChild(createSvgEl('text', { id: 'area-title-' + safeName, x: area.x + 14, y: area.y + 22, fill: titleColor, 'class': 'topo-area-title' }, area.name));

            var resizeHandle = createSvgEl('circle', { id: 'area-resize-' + safeName, cx: area.x + area.w - 6, cy: area.y + area.h - 6, r: 8, 'class': 'topo-area-resize-handle' });
            var resizeHitbox = createSvgEl('circle', { id: 'area-resize-hitbox-' + safeName, cx: area.x + area.w - 6, cy: area.y + area.h - 6, r: 16, fill: 'transparent', cursor: 'nwse-resize', 'pointer-events': 'all' });

            var onResizeStart = function (e) {
                if (e.button !== 0) return;
                e.stopPropagation(); e.preventDefault();
                isResizingArea = true; resizedArea = area;
                resizeHandle.classList.add('active-hover');
                var pt = getSvgPoint(e);
                resizeStart = { x: pt.x, y: pt.y, initialW: area.w, initialH: area.h };
            };
            resizeHandle.addEventListener('mousedown', onResizeStart);
            resizeHitbox.addEventListener('mousedown', onResizeStart);
            resizeHitbox.addEventListener('mouseenter', function () { resizeHandle.classList.add('active-hover'); });
            resizeHitbox.addEventListener('mouseleave', function () { if (!isResizingArea) resizeHandle.classList.remove('active-hover'); });

            areaGroup.appendChild(resizeHandle);
            areaGroup.appendChild(resizeHitbox);
            gRoot.appendChild(areaGroup);
        });

        (payload.edges || []).forEach(function (c) {
            var a = currentPos[c.from], b = currentPos[c.to];
            if (!a || !b) return;
            var devA = nodeMap[c.from], devB = nodeMap[c.to];
            var isOutage = (devA && devA.status === 'down') || (devB && devB.status === 'down');
            var strokeColor = isOutage ? '#dc2626' : '#16a34a';
            var dir = c.direction || 'forward';
            var markerEnd = null, markerStart = null;
            if (dir === 'forward') markerEnd = isOutage ? 'url(#topoEdgeArrowDown)' : 'url(#topoEdgeArrow)';
            else if (dir === 'reverse') markerStart = isOutage ? 'url(#topoEdgeArrowStartDown)' : 'url(#topoEdgeArrowStart)';
            else if (dir === 'bidirectional') { markerEnd = isOutage ? 'url(#topoEdgeArrowDown)' : 'url(#topoEdgeArrow)'; markerStart = isOutage ? 'url(#topoEdgeArrowStartDown)' : 'url(#topoEdgeArrowStart)'; }

            var edgeId = c.id || (c.from + '-' + c.to);
            var path = createSvgEl('path', {
                id: 'edge-path-' + edgeId,
                d: computeEdgePath(a, b, currentArrowSettings.clearance),
                'class': 'topo-edge-line',
                stroke: strokeColor,
                'stroke-width': currentArrowSettings.stroke_width || 2.2,
                'marker-end': markerEnd, 'marker-start': markerStart
            });

            var fromName = devA ? devA.name : ('ID ' + c.from);
            var toName = devB ? devB.name : ('ID ' + c.to);
            var symbol = dir === 'reverse' ? '←' : (dir === 'bidirectional' ? '↔' : (dir === 'none' ? '—' : '→'));
            var edgeInfo = fromName + ' ' + symbol + ' ' + toName + (isOutage ? ' [OUTAGE]' : '');

            path.addEventListener('mouseenter', function (e) { tooltip.textContent = edgeInfo; tooltip.style.display = 'block'; tooltip.style.left = (e.pageX + 10) + 'px'; tooltip.style.top = (e.pageY + 10) + 'px'; });
            path.addEventListener('mousemove', function (e) { tooltip.style.left = (e.pageX + 10) + 'px'; tooltip.style.top = (e.pageY + 10) + 'px'; });
            path.addEventListener('mouseleave', function () { tooltip.style.display = 'none'; });
            path.addEventListener('click', function () { openEdgeModal(c, devA, devB, path, isOutage); });

            gRoot.appendChild(path);
        });

        payload.areas.forEach(function (area) {
            (area.nodes || []).forEach(function (n) {
                var g = createSvgEl('g', { id: 'node-g-' + n.id, 'class': 'topo-node', transform: 'translate(' + n.x + ',' + n.y + ')' });
                g.appendChild(createSvgEl('rect', { x: -36, y: -36, width: 72, height: 80, fill: '#000', 'fill-opacity': 0, 'pointer-events': 'all' }));

                if (n.status === 'down') {
                    g.appendChild(createSvgEl('path', { d: starburstPath(0, -2, 28, 16, 16), fill: '#dc2626', stroke: '#991b1b', 'stroke-width': '1.5', 'class': 'outage-blast' }));
                    g.appendChild(createSvgEl('circle', { cx: 0, cy: -2, r: 9, fill: '#1e1b4b' }));
                }

                g.appendChild(createSvgEl('image', { href: iconBase + '/' + getIconFile(n.icon), x: -ICON / 2, y: -ICON / 2 - 2, width: ICON, height: ICON }));

                if (n.status !== 'down') {
                    var dotColor = n.status === 'up' ? '#22c55e' : (n.status === 'warning' ? '#f59e0b' : '#94a3b8');
                    g.appendChild(createSvgEl('circle', { cx: ICON / 2 - 3, cy: -(ICON / 2) + 3, r: 5, fill: dotColor, stroke: '#fff', 'stroke-width': '1.5' }));
                }

                var labelName = String(n.name || '');
                var textW = Math.max(labelName.length * 6.5 + 12, 48);
                var labelY = ICON / 2 + 8;
                g.appendChild(createSvgEl('rect', { x: -textW / 2, y: labelY - 7, width: textW, height: 15, rx: 3, 'class': 'topo-node-name-bg' }));
                g.appendChild(createSvgEl('text', { x: 0, y: labelY + 1, 'class': 'topo-node-name-text' }, labelName));
                if (n.ip) g.appendChild(createSvgEl('text', { x: 0, y: labelY + 15, 'class': 'topo-node-ip-text' }, n.ip));

                g.addEventListener('mousedown', function (e) {
                    if (e.button !== 0) return;
                    e.stopPropagation(); e.preventDefault();
                    if (isConnectMode) return;
                    isDraggingNode = true; draggedNode = n; draggedEl = g;
                    var pt = getSvgPoint(e);
                    dragNodeOffset = { x: pt.x - n.x, y: pt.y - n.y };
                    dragNodeInitialPos = { x: n.x, y: n.y };
                    hasDraggedNode = false;
                    g.classList.add('is-dragging');
                });

                g.addEventListener('click', function () {
                    if (hasDraggedNode) return;
                    if (isConnectMode) {
                        if (!connectSourceNode) {
                            connectSourceNode = n;
                            g.classList.add('wug-node-selected');
                            showToast('Sumber: ' + n.name + '. Klik device tujuan.');
                        } else if (connectSourceNode.id === n.id) {
                            g.classList.remove('wug-node-selected');
                            connectSourceNode = null;
                        } else if (window.WugConnectRequested) {
                            window.WugConnectRequested(connectSourceNode, n);
                        }
                        return;
                    }
                openDeviceDetail(n.device_id || n.id, n);
                });

                gRoot.appendChild(g);
            });
        });
    }

    function openEdgeModal(edge, devA, devB, pathEl, isOutage) {
        activeEdgeConnection = edge;
        activeEdgeConnection._pathEl = pathEl;
        activeEdgeConnection._isOutage = isOutage;
        document.getElementById('modalEdgeTitle').textContent = 'Relasi #' + (edge.id || '');
        document.getElementById('modalEdgeFrom').textContent = devA ? devA.name : ('Device #' + edge.from);
        document.getElementById('modalEdgeTo').textContent = devB ? devB.name : ('Device #' + edge.to);
        var symbol = edge.direction === 'reverse' ? '←' : (edge.direction === 'bidirectional' ? '↔' : (edge.direction === 'none' ? '—' : '→'));
        document.getElementById('modalEdgeSymbol').textContent = symbol;
        new bootstrap.Modal(document.getElementById('modalEdgeDirection')).show();
    }

    window.setConnectionDirection = function (dir) {
        if (!activeEdgeConnection || !activeEdgeConnection.id) return;
        var connId = activeEdgeConnection.id;
        var url = directionUrlTemplate.replace('__CONN_ID__', connId);
        fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ direction: dir }) })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.success) {
                activeEdgeConnection.direction = dir;
                var isOutage = activeEdgeConnection._isOutage;
                var markerEnd = null, markerStart = null;
                if (dir === 'forward') markerEnd = isOutage ? 'url(#topoEdgeArrowDown)' : 'url(#topoEdgeArrow)';
                else if (dir === 'reverse') markerStart = isOutage ? 'url(#topoEdgeArrowStartDown)' : 'url(#topoEdgeArrowStart)';
                else if (dir === 'bidirectional') { markerEnd = isOutage ? 'url(#topoEdgeArrowDown)' : 'url(#topoEdgeArrow)'; markerStart = isOutage ? 'url(#topoEdgeArrowStartDown)' : 'url(#topoEdgeArrowStart)'; }
                if (activeEdgeConnection._pathEl) {
                    activeEdgeConnection._pathEl.setAttribute('marker-end', markerEnd || '');
                    activeEdgeConnection._pathEl.setAttribute('marker-start', markerStart || '');
                }
                var modal = bootstrap.Modal.getInstance(document.getElementById('modalEdgeDirection'));
                if (modal) modal.hide();
                showToast('Arah panah diubah & disimpan.');
            }
        }).catch(function () { alert('Gagal.'); });
    };

    function applyTransform() { gRoot.setAttribute('transform', 'translate(' + panX.toFixed(1) + ',' + panY.toFixed(1) + ') scale(' + scale.toFixed(3) + ')'); }
    function resetView() { scale = 1.0; panX = 0; panY = 0; applyTransform(); }

    viewport.addEventListener('wheel', function (e) {
        e.preventDefault();
        var factor = e.deltaY < 0 ? 1.12 : 0.89;
        var newScale = Math.min(Math.max(scale * factor, 0.35), 4.5);
        var rect = viewport.getBoundingClientRect();
        var mouseX = e.clientX - rect.left, mouseY = e.clientY - rect.top;
        panX = mouseX - (mouseX - panX) * (newScale / scale);
        panY = mouseY - (mouseY - panY) * (newScale / scale);
        scale = newScale; applyTransform();
    }, { passive: false });

    viewport.addEventListener('mousedown', function (e) {
        if (e.button !== 0) return;
        if (isDraggingNode || isDraggingArea || isResizingArea) return;
        isPanning = true;
        startX = e.clientX - panX; startY = e.clientY - panY;
    });

    window.addEventListener('mousemove', function (e) {
        if (isDraggingNode && draggedNode && draggedEl) {
            var pt = getSvgPoint(e);
            var curX = Math.round(pt.x - dragNodeOffset.x), curY = Math.round(pt.y - dragNodeOffset.y);
            if (Math.abs(curX - dragNodeInitialPos.x) > 3 || Math.abs(curY - dragNodeInitialPos.y) > 3) hasDraggedNode = true;
            draggedEl.setAttribute('transform', 'translate(' + curX + ',' + curY + ')');
            currentPos[draggedNode.id] = { x: curX, y: curY };
            draggedNode.x = curX; draggedNode.y = curY;
            updateEdgesForNode(draggedNode.id);
            return;
        }
        if (isDraggingArea && draggedArea) {
            var pt = getSvgPoint(e);
            var newAx = Math.round(pt.x - dragAreaOffset.x), newAy = Math.round(pt.y - dragAreaOffset.y);
            draggedArea.x = newAx; draggedArea.y = newAy;
            var sName = String(draggedArea.name || '').replace(/[^a-zA-Z0-9_-]/g, '_');
            var rBox = document.getElementById('area-rect-' + sName);
            var rHdr = document.getElementById('area-header-' + sName);
            var rTitle = document.getElementById('area-title-' + sName);
            var rHdl = document.getElementById('area-resize-' + sName);
            var rHb = document.getElementById('area-resize-hitbox-' + sName);
            if (rBox) { rBox.setAttribute('x', newAx); rBox.setAttribute('y', newAy); }
            if (rHdr) { rHdr.setAttribute('x', newAx); rHdr.setAttribute('y', newAy); }
            if (rTitle) { rTitle.setAttribute('x', newAx + 14); rTitle.setAttribute('y', newAy + 22); }
            if (rHdl) { rHdl.setAttribute('cx', newAx + draggedArea.w - 6); rHdl.setAttribute('cy', newAy + draggedArea.h - 6); }
            if (rHb) { rHb.setAttribute('cx', newAx + draggedArea.w - 6); rHb.setAttribute('cy', newAy + draggedArea.h - 6); }
            (draggedArea.nodes || []).forEach(function (n) {
                var ini = dragAreaInitialNodePos[n.id];
                if (ini) {
                    var nx = Math.round(newAx + ini.relX), ny = Math.round(newAy + ini.relY);
                    n.x = nx; n.y = ny; currentPos[n.id] = { x: nx, y: ny };
                    var nEl = document.getElementById('node-g-' + n.id);
                    if (nEl) nEl.setAttribute('transform', 'translate(' + nx + ',' + ny + ')');
                    updateEdgesForNode(n.id);
                }
            });
            return;
        }
        if (isResizingArea && resizedArea) {
            var pt = getSvgPoint(e);
            var newW = Math.max(Math.round(resizeStart.initialW + (pt.x - resizeStart.x)), 80);
            var newH = Math.max(Math.round(resizeStart.initialH + (pt.y - resizeStart.y)), 80);
            resizedArea.w = newW; resizedArea.h = newH;
            var sName = String(resizedArea.name || '').replace(/[^a-zA-Z0-9_-]/g, '_');
            var rBox = document.getElementById('area-rect-' + sName);
            var rHdr = document.getElementById('area-header-' + sName);
            var rHdl = document.getElementById('area-resize-' + sName);
            var rHb = document.getElementById('area-resize-hitbox-' + sName);
            if (rBox) { rBox.setAttribute('width', newW); rBox.setAttribute('height', newH); }
            if (rHdr) rHdr.setAttribute('width', newW);
            if (rHdl) { rHdl.setAttribute('cx', resizedArea.x + newW - 6); rHdl.setAttribute('cy', resizedArea.y + newH - 6); }
            if (rHb) { rHb.setAttribute('cx', resizedArea.x + newW - 6); rHb.setAttribute('cy', resizedArea.y + newH - 6); }
            var sel = document.getElementById('selectCustomArea');
            if (sel && sel.value === resizedArea.name) { document.getElementById('inputAreaW').value = newW; document.getElementById('inputAreaH').value = newH; }
            return;
        }
        if (!isPanning) return;
        panX = e.clientX - startX; panY = e.clientY - startY; applyTransform();
    });

    window.addEventListener('mouseup', function () {
        if (isDraggingNode && draggedNode) {
            if (draggedEl) draggedEl.classList.remove('is-dragging');
            if (hasDraggedNode) {
                                var devId = draggedNode.device_id || draggedNode.id;
                var posId = draggedNode.id;
                var savedX = draggedNode.x, savedY = draggedNode.y, devName = draggedNode.name;
                fetch(positionUrlTemplate.replace('__DEVICE_ID__', devId), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ pos_x: savedX, pos_y: savedY, position_id: posId })
                }).then(function (r) { return r.json(); }).then(function (res) { if (res.success) showToast('Posisi ' + devName + ' disimpan.'); }).catch(function () {});
            }
            isDraggingNode = false;
            setTimeout(function () { hasDraggedNode = false; }, 100);
            return;
        }
        if (isDraggingArea) {
            isDraggingArea = false;
            var ar = draggedArea; draggedArea = null;
            if (ar) {
                if (ar.id) fetch(areaBoundsUrlTemplate.replace('__AREA_ID__', ar.id), { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ pos_x: ar.x, pos_y: ar.y, width: ar.w, height: ar.h }) }).catch(function () {});
                                (ar.nodes || []).forEach(function (n) {
                    var devId = n.device_id || n.id;
                    fetch(positionUrlTemplate.replace('__DEVICE_ID__', devId), { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ pos_x: n.x, pos_y: n.y, position_id: n.id }) }).catch(function () {});
                });
                showToast('Posisi area ' + ar.name + ' disimpan.');
            }
            return;
        }
        if (isResizingArea) {
            isResizingArea = false;
            var rar = resizedArea; resizedArea = null;
            if (rar) {
                var sName = String(rar.name || '').replace(/[^a-zA-Z0-9_-]/g, '_');
                var rHdl = document.getElementById('area-resize-' + sName);
                if (rHdl) rHdl.classList.remove('active-hover');
                if (rar.id) fetch(areaBoundsUrlTemplate.replace('__AREA_ID__', rar.id), { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ pos_x: rar.x, pos_y: rar.y, width: rar.w, height: rar.h }) }).catch(function () {});
                showToast('Ukuran area ' + rar.name + ' (' + rar.w + 'x' + rar.h + 'px) disimpan.');
            }
            return;
        }
        isPanning = false;
    });
    var btnZI = document.getElementById('btnZoomIn');
    var btnZO = document.getElementById('btnZoomOut');
    var btnZR = document.getElementById('btnZoomReset');
    var btnFS = document.getElementById('btnFitScreen');
    if (btnZI) btnZI.addEventListener('click', function () { scale = Math.min(scale * 1.25, 4.5); applyTransform(); });
    if (btnZO) btnZO.addEventListener('click', function () { scale = Math.max(scale * 0.8, 0.35); applyTransform(); });
    if (btnZR) btnZR.addEventListener('click', resetView);
    if (btnFS) btnFS.addEventListener('click', resetView);

    var btnToggleFs = document.getElementById('btnToggleFs');
    var btnFloatingExitFs = document.getElementById('btnFloatingExitFs');
    var iconFs = document.getElementById('iconFs');
    var labelFs = document.getElementById('labelFs');

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            if (mapWrapper.requestFullscreen) mapWrapper.requestFullscreen().catch(fallbackFullscreen);
            else fallbackFullscreen();
        } else if (document.exitFullscreen) document.exitFullscreen();
    }
    function fallbackFullscreen() {
        mapWrapper.classList.toggle('is-fullscreen');
        var isFs = mapWrapper.classList.contains('is-fullscreen');
        iconFs.className = isFs ? 'icon ni ni-shrink' : 'icon ni ni-expand';
        labelFs.textContent = isFs ? 'Exit' : 'Fullscreen';
        resetView();
    }
    document.addEventListener('fullscreenchange', function () {
        var isFs = !!document.fullscreenElement;
        mapWrapper.classList.toggle('is-fullscreen', isFs);
        iconFs.className = isFs ? 'icon ni ni-shrink' : 'icon ni ni-expand';
        labelFs.textContent = isFs ? 'Exit' : 'Fullscreen';
        resetView();
    });
    if (btnToggleFs) btnToggleFs.addEventListener('click', toggleFullscreen);
    if (btnFloatingExitFs) btnFloatingExitFs.addEventListener('click', toggleFullscreen);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'f' || e.key === 'F') {
            var a = document.activeElement;
            if (!a || (a.tagName !== 'INPUT' && a.tagName !== 'TEXTAREA')) toggleFullscreen();
        }
    });

    var customDrawer = document.getElementById('wugCustomDrawer');
    var btnToggleCustomDrawer = document.getElementById('btnToggleCustomDrawer');
    var btnCloseCustomDrawer = document.getElementById('btnCloseCustomDrawer');
    function toggleCustomDrawer() {
        customDrawer.classList.toggle('is-open');
        btnToggleCustomDrawer.classList.toggle('active', customDrawer.classList.contains('is-open'));
    }
        if (btnToggleCustomDrawer) btnToggleCustomDrawer.addEventListener('click', toggleCustomDrawer);
    if (btnCloseCustomDrawer) btnCloseCustomDrawer.addEventListener('click', toggleCustomDrawer);

    function syncDrawerInputs() {
        var cl = currentArrowSettings.clearance || 28, sz = currentArrowSettings.needle_size || 7, wt = currentArrowSettings.stroke_width || 2.2;
        document.getElementById('rangeNeedleClearance').value = cl; document.getElementById('valNeedleClearance').textContent = cl + ' px';
        document.getElementById('rangeNeedleSize').value = sz; document.getElementById('valNeedleSize').textContent = sz + ' px';
        document.getElementById('rangeNeedleWidth').value = wt; document.getElementById('valNeedleWidth').textContent = wt + ' px';
    }

    document.getElementById('rangeNeedleClearance').addEventListener('input', function (e) {
        var val = parseInt(e.target.value) || 28;
        currentArrowSettings.clearance = val;
        document.getElementById('valNeedleClearance').textContent = val + ' px';
        if (currentTopo && currentTopo.edges) currentTopo.edges.forEach(function (c) {
            var p = document.getElementById('edge-path-' + (c.id || (c.from + '-' + c.to)));
            if (p) { var a = currentPos[c.from], b = currentPos[c.to]; if (a && b) p.setAttribute('d', computeEdgePath(a, b, val)); }
        });
    });
    document.getElementById('rangeNeedleSize').addEventListener('input', function (e) {
        var val = parseInt(e.target.value) || 7;
        currentArrowSettings.needle_size = val;
        document.getElementById('valNeedleSize').textContent = val + ' px';
        setupDefs();
    });
    document.getElementById('rangeNeedleWidth').addEventListener('input', function (e) {
        var val = parseFloat(e.target.value) || 2.2;
        currentArrowSettings.stroke_width = val;
        document.getElementById('valNeedleWidth').textContent = val + ' px';
        if (currentTopo && currentTopo.edges) currentTopo.edges.forEach(function (c) {
            var p = document.getElementById('edge-path-' + (c.id || (c.from + '-' + c.to)));
            if (p) p.setAttribute('stroke-width', val);
        });
    });

    var selectArea = document.getElementById('selectCustomArea');
    var boxAreaSizeControls = document.getElementById('boxAreaSizeControls');
    var inputAreaW = document.getElementById('inputAreaW');
    var inputAreaH = document.getElementById('inputAreaH');

    selectArea.addEventListener('change', function () {
        var val = this.value;
        if (!val) { boxAreaSizeControls.style.display = 'none'; return; }
        var t = currentAreas.find(function (a) { return a.name === val; });
        if (t) { boxAreaSizeControls.style.display = 'flex'; inputAreaW.value = t.w; inputAreaH.value = t.h; }
    });

    function autoSaveAreaBoundsFromDrawer(t) {
        if (!t || !t.id) return;
        fetch(areaBoundsUrlTemplate.replace('__AREA_ID__', t.id), { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ pos_x: t.x, pos_y: t.y, width: t.w, height: t.h }) })
            .then(function (r) { return r.json(); })
            .then(function (res) { if (res.success) showToast('Ukuran area ' + t.name + ' disimpan.'); }).catch(function () {});
    }
    inputAreaW.addEventListener('change', function () { var a = currentAreas.find(function (x) { return x.name === selectArea.value; }); if (a) autoSaveAreaBoundsFromDrawer(a); });
    inputAreaH.addEventListener('change', function () { var a = currentAreas.find(function (x) { return x.name === selectArea.value; }); if (a) autoSaveAreaBoundsFromDrawer(a); });

    window.triggerSaveFullLayout = function () {
        if (!isSuperAdmin) { alert('Hanya Super Admin.'); return; }
        var btn = document.getElementById('btnSaveFullLayout');
        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...'; }
        var areasPayload = currentAreas.map(function (ar) { return { name: ar.name, x: ar.x, y: ar.y, w: ar.w, h: ar.h }; });
        var devicesPayload = [];
        for (var dId in currentPos) {
            var dev = nodeMap[dId];
            devicesPayload.push({ id: parseInt(dId), x: currentPos[dId].x, y: currentPos[dId].y, area: dev ? dev.area : null });
        }
        fetch(saveLayoutUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ areas: areasPayload, devices: devicesPayload, arrow_settings: currentArrowSettings }) })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (btn) { btn.disabled = false; btn.innerHTML = '<em class="icon ni ni-save"></em> <span>Simpan Layout</span>'; }
                if (res.success) { showToast('Layout disimpan.'); if (customDrawer.classList.contains('is-open')) toggleCustomDrawer(); }
                else alert(res.message || 'Gagal.');
            }).catch(function () {
                if (btn) { btn.disabled = false; btn.innerHTML = '<em class="icon ni ni-save"></em> <span>Simpan Layout</span>'; }
                alert('Error.');
            });
    };
    var btnSaveEl = document.getElementById('btnSaveFullLayout');
    if (btnSaveEl) btnSaveEl.addEventListener('click', triggerSaveFullLayout);

    var btnReset = document.getElementById('btnResetFullLayout');
    if (btnReset) btnReset.addEventListener('click', function () {
        if (!confirm('Reset semua layout ke default 2026?')) return;
        fetch(resetLayoutUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (res) { if (res.success) { showToast('Layout direset.'); if (res.topology) render(res.topology); } })
            .catch(function () { alert('Gagal reset.'); });
    });

    var btnHelp = document.getElementById('btnMapHelp');
    if (btnHelp) btnHelp.addEventListener('click', function () { new bootstrap.Modal(document.getElementById('modalWugHelp')).show(); });
    var detailOverlay = document.getElementById('detailOverlay');
    var detailLoading = document.getElementById('detailLoading');
    var detailContent = document.getElementById('detailContent');

    function openDeviceDetail(id, fallback) {
        currentDetailDeviceId = id;
        detailOverlay.classList.add('active');
        detailLoading.style.display = 'block';
        detailContent.style.display = 'none';

        var fb = fallback || {};
        document.getElementById('detailName').textContent = fb.name || 'Device #' + id;
        document.getElementById('detailHostname').textContent = fb.name || '-';
        document.getElementById('detailAddress').textContent = fb.ip || '-';
        document.getElementById('detailPop').textContent = fb.area || fb.pop || '-';
        document.getElementById('detailStatusText').textContent = (fb.status || 'UP').toUpperCase();

        var url = deviceUrlTemplate ? deviceUrlTemplate.replace('__DEVICE_ID__', id) : null;
        if (!url) { detailLoading.style.display = 'none'; detailContent.style.display = 'block'; return; }

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                var dev = data.device || fb;
                var st  = data.stats || {};
                document.getElementById('detailName').textContent = dev.name;
                document.getElementById('detailHostname').textContent = dev.name;
                document.getElementById('detailAddress').textContent = dev.ip_address || dev.ip;
                document.getElementById('detailPop').textContent = dev.pop_name || dev.area || '-';
                var stText = (dev.status || 'up').toUpperCase();
                var stColor = dev.status === 'up' ? '#16a34a' : (dev.status === 'down' ? '#dc2626' : '#d97706');
                document.getElementById('detailStatusText').innerHTML = '<span style="color:' + stColor + '; font-weight:800;">&bull; ' + stText + '</span>';
                document.getElementById('detailLastPoll').textContent = st.last_poll || '-';
                document.getElementById('statType').textContent = st.protocol || 'ICMP';
                document.getElementById('statPolls').textContent = st.total_polls || '0';
                document.getElementById('statResponded').textContent = (st.responded_pct !== undefined ? st.responded_pct : 0) + '%';
                document.getElementById('statMissed').textContent = (st.missed_pct !== undefined ? st.missed_pct : 0) + '%';
                document.getElementById('statDowntime').textContent = st.down_time || '00:00:00';
                document.getElementById('statAvgDelay').textContent = (st.avg_delay || 0) + ' ms';
                document.getElementById('statMinDelay').textContent = (st.min_delay || 0) + ' ms';
                document.getElementById('statMaxDelay').textContent = (st.max_delay || 0) + ' ms';
                var logBox = document.getElementById('detailLogs');
                if (Array.isArray(data.logs) && data.logs.length) {
                    var h = '';
                    data.logs.forEach(function (l) { h += '<div><span class="text-muted">' + l.time + '</span> [' + l.status.toUpperCase() + '] ' + l.message + '</div>'; });
                    logBox.innerHTML = h;
                } else logBox.innerHTML = '<div class="text-muted">Tidak ada log.</div>';
                detailLoading.style.display = 'none';
                detailContent.style.display = 'block';
            }).catch(function () {
                detailLoading.style.display = 'none';
                detailContent.style.display = 'block';
            });
    }
    document.getElementById('detailClose').addEventListener('click', function () { detailOverlay.classList.remove('active'); });

    // Tombol Edit di modal detail (delegasi ke panel blade)
    var btnDetailEditEl = document.getElementById('btnDetailEditDevice');
    if (btnDetailEditEl) {
        btnDetailEditEl.addEventListener('click', function () {
            if (!currentDetailDeviceId) return;
            if (window.WugDevicePanel && window.WugDevicePanel.openEdit) {
                detailOverlay.classList.remove('active');
                window.WugDevicePanel.openEdit(currentDetailDeviceId);
            } else {
                alert('Fitur edit tidak tersedia.');
            }
        });
    }

    // Tombol Hapus di modal detail (delegasi ke panel blade)
    var btnDetailDeleteEl = document.getElementById('btnDetailDeleteDevice');
    if (btnDetailDeleteEl) {
        btnDetailDeleteEl.addEventListener('click', function () {
            if (!currentDetailDeviceId) return;
                        var list = (window.WugDeviceList || []);
            var dev = list.find(function (d) {
                return String(d.id) === String(currentDetailDeviceId);
            });
            if (!dev) { alert('Data device tidak ditemukan.'); return; }
            if (window.WugDevicePanel && window.WugDevicePanel.deleteDevice) {
                detailOverlay.classList.remove('active');
                window.WugDevicePanel.deleteDevice(dev);
            } else {
                alert('Fitur hapus tidak tersedia.');
            }
        });
    }
    detailOverlay.addEventListener('click', function (e) { if (e.target === detailOverlay) detailOverlay.classList.remove('active'); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') detailOverlay.classList.remove('active'); });

    if (initialTopo) render(initialTopo);

    if (refreshUrl) setInterval(function () {
        if (isDraggingNode || isDraggingArea || isResizingArea) return;
        fetch(refreshUrl, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) { render(data); }).catch(function () {});
    }, 30000);

        window.openWugDeviceModal = openDeviceDetail;

    // ══════ Expose API untuk panel kiri (device-panel) ══════
    // Dipakai untuk: drag & drop device, focus node, refresh render, dll.
    window.WugTopology = {
        getSvgPoint:    getSvgPoint,
        areaAt:         areaAt,
        render:         render,
        focusNode:      focusNode,
        setConnectMode: setConnectMode,
        showToast:      showToast,
        getAreas:       function () { return currentAreas; },
        getScale:       function () { return { scale: scale, panX: panX, panY: panY }; }
    };
})();
</script>