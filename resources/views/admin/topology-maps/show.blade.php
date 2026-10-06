@extends('layouts.admin')

@section('title', $map->name . ' - Topology Map')

@push('styles')
<style>
    /* ══ TABS MAPS ══ */
    .topo-tabs {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 0 4px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 8px;
        overflow-x: auto;
        scrollbar-width: thin;
    }
    .topo-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px 8px 0 0;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        border: 1px solid transparent;
        border-bottom: none;
        white-space: nowrap;
        transition: all .15s;
    }
    .topo-tab:hover { color: #4F46E5; background: #f8fafc; }
    .topo-tab.active {
        background: #fff;
        color: #4F46E5;
        border-color: #e2e8f0;
        border-bottom-color: #fff;
        margin-bottom: -2px;
    }
    .topo-tab.active .dot { background: #4F46E5; }
    .topo-tab .dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #cbd5e1;
    }
    .topo-tab-add {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #059669;
        text-decoration: none;
        border: 1px dashed #10b981;
        background: #f0fdf4;
        white-space: nowrap;
    }
    .topo-tab-add:hover { background: #dcfce7; color: #059669; }

    /* ══ HEADER ACTIONS ══ */
    .topo-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }
    .topo-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
    }
    .topo-btn-pop    { background: #7c3aed; color: #fff; }
    .topo-btn-pop:hover    { background: #6d28d9; color: #fff; }
    .topo-btn-device { background: #f97316; color: #fff; }
    .topo-btn-device:hover { background: #ea580c; color: #fff; }
    .topo-btn-conn   { background: #2563eb; color: #fff; }
    .topo-btn-conn:hover   { background: #1d4ed8; color: #fff; }
    .topo-btn-conn.active  { background: #f59e0b; color: #000; box-shadow: 0 0 0 3px rgba(245,158,11,.3); }
    .topo-btn-back   { background: #fff; color: #475569; border-color: #cbd5e1; }
    .topo-btn-back:hover   { background: #f1f5f9; color: #1e293b; }
    .topo-btn-save   { background: #10b981; color: #fff; }
    .topo-btn-save:hover   { background: #059669; color: #fff; }

    /* ══ CANVAS ══ */
    .topo-canvas-wrap {
        display: flex;
        gap: 12px;
        height: calc(100vh - 250px);
        min-height: 520px;
    }
    .topo-panel-left {
        width: 260px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        flex-shrink: 0;
    }
    .topo-panel-header {
        padding: 10px 14px;
        background: #0f172a;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .topo-panel-body {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
    }
    .topo-panel-section-title {
        font-size: 10.5px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: .5px;
        color: #94a3b8;
        padding: 6px 8px 4px;
    }
    .topo-panel-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 10px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: background .12s;
    }
    .topo-panel-item:hover { background: #f1f5f9; }
    .topo-panel-item .status-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .topo-panel-item .status-dot.up { background: #22c55e; }
    .topo-panel-item .status-dot.down { background: #ef4444; }
    .topo-panel-item .status-dot.warning { background: #f59e0b; }
    .topo-panel-item .status-dot.unknown { background: #94a3b8; }
    .topo-panel-item .item-name { flex: 1; font-weight: 600; color: #1e293b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .topo-panel-item .item-ip { font-size: 10.5px; color: #94a3b8; font-family: monospace; }

    .topo-canvas {
        flex: 1;
        position: relative;
        background: #cbd5e1;
        border: 2px solid #2563eb;
        border-radius: 10px;
        overflow: hidden;
        cursor: grab;
    }
    .topo-canvas:active { cursor: grabbing; }
    .topo-canvas.is-panning { cursor: grabbing !important; }
    .topo-canvas svg {
        width: 100%;
        height: 100%;
        display: block;
        touch-action: none;
    }

    /* ══ NODE & EDGE ══ */
    .topo-node { cursor: grab; user-select: none; }
    .topo-node.is-dragging { cursor: grabbing; opacity: .8; }
    .topo-node.selected image { filter: brightness(1.2) drop-shadow(0 0 12px #f59e0b); }
    .topo-node:hover image { filter: brightness(1.12); }
    .topo-edge { stroke: #16a34a; stroke-width: 2; fill: none; cursor: pointer; }
    .topo-edge:hover { stroke: #f59e0b; stroke-width: 4; }
    .topo-area-box { fill: #f8fafc; stroke-width: 2.5; }

    /* ══ ZOOM CONTROLS ══ */
    .topo-zoom-controls {
        position: absolute;
        bottom: 16px;
        right: 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: rgba(255,255,255,.95);
        backdrop-filter: blur(6px);
        padding: 5px;
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(0,0,0,.15);
        z-index: 20;
    }
    .topo-zoom-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #cbd5e1;
        background: #fff;
        border-radius: 8px;
        color: #334155;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .15s;
    }
    .topo-zoom-btn:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }
    .topo-zoom-btn.topo-zoom-reset {
        background: #f1f5f9;
        color: #475569;
    }
    .topo-zoom-btn.topo-zoom-reset:hover {
        background: #10b981;
        color: #fff;
        border-color: #10b981;
    }

    /* ══ MODAL ══ */
    .device-type-preview {
        width: 40px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════
     TABS MAPS
══════════════════════════════════════════════════════ --}}
<div class="topo-tabs">
    @foreach($allMaps as $m)
        <a href="{{ route('admin.topology-maps.show', $m) }}"
           class="topo-tab {{ $m->id === $map->id ? 'active' : '' }}">
            <span class="dot"></span>
            {{ $m->name }}
        </a>
    @endforeach
    <a href="{{ route('admin.topology-maps.create') }}" class="topo-tab-add">
        <em class="icon ni ni-plus"></em> Maps Baru
    </a>
</div>

{{-- ══════════════════════════════════════════════════════
     PAGE HEAD + ACTIONS
══════════════════════════════════════════════════════ --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h4 class="mb-0 fw-bold">{{ $map->name }}</h4>
        <small class="text-muted">{{ $map->description ?? 'Topology view' }}</small>
    </div>
    <div class="topo-header-actions mb-0">
        <a href="{{ route('admin.topology-maps.index') }}" class="topo-btn topo-btn-back">
            <em class="icon ni ni-arrow-left"></em> Kembali
        </a>
        <button type="button" class="topo-btn topo-btn-pop" id="btnAddPop">
            <em class="icon ni ni-plus"></em> Tambah POP
        </button>
        <button type="button" class="topo-btn topo-btn-device" id="btnAddDevice">
            <em class="icon ni ni-plus"></em> Tambah Device
        </button>
        <button type="button" class="topo-btn topo-btn-conn" id="btnConnectMode">
            <em class="icon ni ni-link"></em> Hubungkan
        </button>
        <button type="button" class="topo-btn topo-btn-save" id="btnSaveLayout">
            <em class="icon ni ni-save"></em> Simpan Layout
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     CANVAS WRAP
══════════════════════════════════════════════════════ --}}
<div class="topo-canvas-wrap">

    {{-- PANEL KIRI --}}
    <div class="topo-panel-left">
        <div class="topo-panel-header">
            <span><em class="icon ni ni-layers me-1"></em>Daftar Item</span>
            <span class="badge bg-primary" id="itemCount">0</span>
        </div>
        <div class="topo-panel-body">
            <div class="topo-panel-section-title">POP / Area</div>
            <div id="panelPops"></div>

            <div class="topo-panel-section-title mt-2">Device</div>
            <div id="panelDevices"></div>
        </div>
    </div>

    {{-- CANVAS --}}
    <div class="topo-canvas" id="topoCanvas">
        <svg id="topoSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid meet">
            <defs>
                <marker id="arrowEnd" viewBox="0 0 14 14" refX="12" refY="7"
                        markerWidth="8" markerHeight="8" orient="auto">
                    <path d="M 1 2 L 13 7 L 1 12 z" fill="#16a34a"/>
                </marker>
            </defs>
            <g id="topoTransformGroup">
                <g id="topoRoot"></g>
            </g>
        </svg>

        <div class="topo-zoom-controls">
            <button type="button" class="topo-zoom-btn" id="btnZoomIn" title="Perbesar">
                <em class="icon ni ni-plus"></em>
            </button>
            <button type="button" class="topo-zoom-btn" id="btnZoomOut" title="Perkecil">
                <em class="icon ni ni-minus"></em>
            </button>
            <button type="button" class="topo-zoom-btn topo-zoom-reset" id="btnZoomReset" title="Reset & Fit ke Konten">
                <em class="icon ni ni-reload"></em>
            </button>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════
     MODAL TAMBAH POP
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAddPop" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formAddPop">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title text-white"><em class="icon ni ni-layers me-1"></em>Tambah POP / Area</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama POP <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                               placeholder="Misal: POP Gresik, Lantai 1, Gedung A">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Warna Border</label>
                            <input type="color" name="border_color" class="form-control form-control-color" value="#4F46E5">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Warna Background</label>
                            <input type="color" name="bg_color" class="form-control form-control-color" value="#f8fafc">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-save me-1"></em>Simpan POP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL TAMBAH DEVICE
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAddDevice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formAddDevice">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title"><em class="icon ni ni-server me-1"></em>Tambah Perangkat ke Map</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <em class="icon ni ni-info me-1"></em>
                        Perangkat baru akan otomatis masuk ke tabel <code>devices</code> dan
                        langsung muncul di canvas map ini.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                               placeholder="Contoh: POP Gresik">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <select name="type" class="form-select" id="selectDeviceType" required style="flex:1">
                                    <option value="Router">Router</option>
                                    <option value="Switch">Switch</option>
                                    <option value="OLT">OLT</option>
                                    <option value="EPON">EPON</option>
                                    <option value="Server">Server</option>
                                    <option value="Access Point">Access Point</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="device-type-preview" title="Preview icon">
                                    <img id="deviceTypeIcon"
                                         src="{{ asset('images/devices/router.png') }}"
                                         alt="Preview"
                                         style="width:32px;height:32px;object-fit:contain;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Metode Monitoring <span class="text-danger">*</span></label>
                            <select name="protocol" class="form-select" required>
                                <option value="ICMP">ICMP</option>
                                <option value="HTTP">HTTP</option>
                                <option value="SNMP">SNMP</option>
                                <option value="TCP">TCP</option>
                                <option value="SSH">SSH</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" class="form-control" required
                                   placeholder="Contoh: 202.154.x.x">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Router Version</label>
                        <input type="text" name="router_version" class="form-control"
                               placeholder="Contoh: IOS-XE 17.3.1 (opsional)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">POP / Area</label>
                        <select name="topology_map_area_id" class="form-select">
                            <option value="">-- Tanpa area (langsung taruh lewat map) --</option>
                            @foreach($map->areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Bila diisi, device langsung tampil di panel area tersebut.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <em class="icon ni ni-save me-1"></em>Simpan Perangkat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- HIDDEN: Info Map --}}
<input type="hidden" id="currentMapId" value="{{ $map->id }}">
<input type="hidden" id="currentMapDataUrl" value="{{ route('admin.topology-maps.data', $map) }}">
<input type="hidden" id="availableDevicesUrl" value="{{ route('admin.topology-maps.available-devices', $map) }}">
<input type="hidden" id="storeDeviceUrl" value="{{ route('admin.topology-maps.devices.store', $map) }}">
<input type="hidden" id="storeAreaUrl" value="{{ route('admin.topology-maps.areas.store', $map) }}">
<input type="hidden" id="storeConnectionUrl" value="{{ route('admin.topology-maps.connections.store', $map) }}">

{{-- Data dari backend (buat JS) --}}
@php
    $initialDevices = $map->devices->map(function($d) {
        return [
            'id'                    => $d->id,
            'device_id'             => $d->device_id,
            'name'                  => $d->device->name ?? '-',
            'ip_address'            => $d->device->ip_address ?? '-',
            'type'                  => $d->device->type ?? '-',
            'status'                => $d->device->status ?? 'unknown',
            'pos_x'                 => $d->pos_x,
            'pos_y'                 => $d->pos_y,
            'topology_map_area_id'  => $d->topology_map_area_id,
        ];
    });
@endphp

<script>
    window.TOPO_INITIAL_DATA = {
        areas:       @json($map->areas),
        devices:     @json($initialDevices),
        connections: @json($map->connections),
    };
    window.CSRF_TOKEN = '{{ csrf_token() }}';
</script>

@endsection

@push('scripts')
<script>
/* ═════════════════════════════════════════════════════════════
   TOPOLOGY MAP - FRONTEND LOGIC
═════════════════════════════════════════════════════════════ */
(function () {
    'use strict';

    // ═══ KONSTANTA ═══
    const SVG_NS = 'http://www.w3.org/2000/svg';
    const ICON_BASE = '{{ asset('images/devices') }}';
    const ICON_SIZE = 44;
    const ICON_MAP = {
        'router':       'router.png',
        'switch':       'switch.png',
        'olt':          'switch.png',
        'epon':         'router.png',
        'server':       'server.png',
        'pc':           'pc.png',
        'other':        'pc.png',
        'wifi':         'wifi.png',
        'access point': 'wifi.png',
        'access_point': 'wifi.png',
    };

    function getIconFile(type) {
        const key = String(type || '').toLowerCase().replace(/[_]/g, ' ').trim();
        return ICON_MAP[key] || 'server.png';
    }

    // ═══ ELEMEN DOM ═══
    const svg        = document.getElementById('topoSvg');
    const root       = document.getElementById('topoRoot');
    const gTransform = document.getElementById('topoTransformGroup');
    const canvasEl   = document.getElementById('topoCanvas');
    const csrf       = window.CSRF_TOKEN;

    // ═══ STATE ═══
    const state = {
        map: { id: parseInt(document.getElementById('currentMapId').value) },
        areas: window.TOPO_INITIAL_DATA.areas || [],
        devices: window.TOPO_INITIAL_DATA.devices || [],
        connections: window.TOPO_INITIAL_DATA.connections || [],
        selectedForConnect: null,
        isConnectMode: false,
        drag: { active: false, target: null, targetType: null, offsetX: 0, offsetY: 0, moved: false },
    };

    const urls = {
        storeDevice:     document.getElementById('storeDeviceUrl').value,
        storeArea:       document.getElementById('storeAreaUrl').value,
        storeConnection: document.getElementById('storeConnectionUrl').value,
    };

    // ═══ PAN & ZOOM ═══
    const view = { scale: 1, panX: 0, panY: 0 };
    const MIN_SCALE = 0.3;
    const MAX_SCALE = 4;

    function applyTransform() {
        gTransform.setAttribute(
            'transform',
            `translate(${view.panX.toFixed(2)},${view.panY.toFixed(2)}) scale(${view.scale.toFixed(4)})`
        );
    }

    function fitToContent() {
        if (!state.areas.length && !state.devices.length) {
            svg.setAttribute('viewBox', '0 0 1200 800');
            return;
        }

        let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;

        state.areas.forEach(a => {
            const x = a.pos_x || 0, y = a.pos_y || 0;
            const w = a.width || 240, h = a.height || 200;
            minX = Math.min(minX, x);
            minY = Math.min(minY, y);
            maxX = Math.max(maxX, x + w);
            maxY = Math.max(maxY, y + h);
        });

        state.devices.forEach(d => {
            const x = d.pos_x || 0, y = d.pos_y || 0;
            minX = Math.min(minX, x - 60);
            minY = Math.min(minY, y - 60);
            maxX = Math.max(maxX, x + 60);
            maxY = Math.max(maxY, y + 80);
        });

        const PAD = 80;
        const MIN_W = 900, MIN_H = 600;
        const w = Math.max(maxX - minX + PAD * 2, MIN_W);
        const h = Math.max(maxY - minY + PAD * 2, MIN_H);

        svg.setAttribute('viewBox', `${minX - PAD} ${minY - PAD} ${w} ${h}`);
    }

    function resetView() {
        view.scale = 1;
        view.panX = 0;
        view.panY = 0;
        applyTransform();
        fitToContent();
    }

    // ═══ EVENT: WHEEL ZOOM ═══
    canvasEl.addEventListener('wheel', e => {
        e.preventDefault();
        const factor = e.deltaY < 0 ? 1.12 : 0.89;
        const newScale = Math.min(Math.max(view.scale * factor, MIN_SCALE), MAX_SCALE);
        const rect = canvasEl.getBoundingClientRect();
        const mx = e.clientX - rect.left;
        const my = e.clientY - rect.top;
        view.panX = mx - (mx - view.panX) * (newScale / view.scale);
        view.panY = my - (my - view.panY) * (newScale / view.scale);
        view.scale = newScale;
        applyTransform();
    }, { passive: false });

    // ═══ EVENT: ZOOM BUTTONS ═══
    document.getElementById('btnZoomIn').addEventListener('click', () => {
        view.scale = Math.min(view.scale * 1.25, MAX_SCALE);
        applyTransform();
    });
    document.getElementById('btnZoomOut').addEventListener('click', () => {
        view.scale = Math.max(view.scale * 0.8, MIN_SCALE);
        applyTransform();
    });
    document.getElementById('btnZoomReset').addEventListener('click', resetView);

    // ═══ EVENT: PAN DRAG CANVAS ═══
    let isPanning = false, panStartX = 0, panStartY = 0;
    canvasEl.addEventListener('mousedown', e => {
        if (e.button !== 0 && e.button !== 1) return;
        if (state.drag.active) return;
        isPanning = true;
        panStartX = e.clientX - view.panX;
        panStartY = e.clientY - view.panY;
        canvasEl.classList.add('is-panning');
    });

    // ═══ RENDER ═══
    function render() {
        root.innerHTML = '';

        // 1. AREAS (POP)
        state.areas.forEach(area => {
            const g = createEl('g', {
                class: 'topo-area-group',
                'data-id': area.id,
            });
            g.appendChild(createEl('rect', {
                x: area.pos_x || 40,
                y: area.pos_y || 40,
                width: area.width || 240,
                height: area.height || 200,
                rx: 12,
                class: 'topo-area-box',
                stroke: area.border_color || '#4F46E5',
                fill: area.bg_color || '#f8fafc',
            }));
            g.appendChild(createEl('text', {
                x: (area.pos_x || 40) + 14,
                y: (area.pos_y || 40) + 24,
                fill: area.title_color || '#4F46E5',
                'font-size': 14,
                'font-weight': 800,
                'pointer-events': 'none',
            }, area.name));

            g.style.cursor = 'move';
            g.addEventListener('mousedown', e => onPopMouseDown(e, area));

            root.appendChild(g);
        });

        // 2. EDGES
        state.connections.forEach(conn => {
            const from = state.devices.find(d => d.id === conn.from_map_device_id);
            const to   = state.devices.find(d => d.id === conn.to_map_device_id);
            if (!from || !to) return;

            const path = createEl('path', {
                class: 'topo-edge',
                d: `M ${from.pos_x ?? 100} ${from.pos_y ?? 100} L ${to.pos_x ?? 100} ${to.pos_y ?? 100}`,
                'marker-end': 'url(#arrowEnd)',
            });
            path.addEventListener('click', () => deleteConnection(conn));
            root.appendChild(path);
        });

        // 3. DEVICES
        state.devices.forEach(dev => {
            const x = dev.pos_x ?? 100;
            const y = dev.pos_y ?? 100;

            const g = createEl('g', {
                class: 'topo-node' + (state.selectedForConnect?.id === dev.id ? ' selected' : ''),
                transform: `translate(${x},${y})`,
                'data-id': dev.id,
            });

            const statusColor = {
                up: '#22c55e', down: '#ef4444',
                warning: '#f59e0b', unknown: '#94a3b8'
            }[dev.status] || '#94a3b8';

            g.appendChild(createEl('rect', {
                x: -36, y: -36, width: 72, height: 80,
                fill: '#000', 'fill-opacity': 0, 'pointer-events': 'all',
            }));

            if (dev.status === 'down') {
                g.appendChild(createEl('circle', {
                    cx: 0, cy: -2, r: 26,
                    fill: '#dc2626', 'fill-opacity': 0.18,
                }));
            }

            g.appendChild(createEl('image', {
                href: `${ICON_BASE}/${getIconFile(dev.type)}`,
                x: -ICON_SIZE / 2,
                y: -ICON_SIZE / 2 - 2,
                width: ICON_SIZE,
                height: ICON_SIZE,
                preserveAspectRatio: 'xMidYMid meet',
            }));

            g.appendChild(createEl('circle', {
                cx: ICON_SIZE / 2 - 3,
                cy: -(ICON_SIZE / 2) + 3,
                r: 5,
                fill: statusColor,
                stroke: '#fff',
                'stroke-width': 1.5,
            }));

            const labelName = String(dev.name || '');
            const textW = Math.max(labelName.length * 6.5 + 12, 48);
            const labelY = ICON_SIZE / 2 + 8;

            g.appendChild(createEl('rect', {
                x: -textW / 2, y: labelY - 7,
                width: textW, height: 15, rx: 3,
                fill: '#fff', stroke: '#1e293b', 'stroke-width': 1,
            }));
            g.appendChild(createEl('text', {
                x: 0, y: labelY + 1,
                'text-anchor': 'middle',
                'font-size': 11,
                'font-weight': 700,
                fill: '#0f172a',
                'pointer-events': 'none',
            }, labelName));

            if (dev.ip_address) {
                g.appendChild(createEl('text', {
                    x: 0, y: labelY + 15,
                    'text-anchor': 'middle',
                    'font-size': 9.5,
                    'font-weight': 600,
                    fill: '#475569',
                    'pointer-events': 'none',
                }, dev.ip_address));
            }

            g.addEventListener('mousedown', e => onNodeMouseDown(e, dev));
            g.addEventListener('click', () => onNodeClick(dev));

            root.appendChild(g);
        });

        renderPanel();
        document.getElementById('itemCount').textContent = state.devices.length + state.areas.length;
    }

    function renderPanel() {
        const popsEl = document.getElementById('panelPops');
        const devsEl = document.getElementById('panelDevices');
        popsEl.innerHTML = '';
        devsEl.innerHTML = '';

        if (!state.areas.length) {
            popsEl.innerHTML = '<div class="text-muted small px-2 py-1">Belum ada POP.</div>';
        } else {
            state.areas.forEach(a => {
                const el = document.createElement('div');
                el.className = 'topo-panel-item';
                el.innerHTML = `<em class="icon ni ni-layers text-primary"></em>
                                <span class="item-name">${escapeHtml(a.name)}</span>`;
                popsEl.appendChild(el);
            });
        }

        if (!state.devices.length) {
            devsEl.innerHTML = '<div class="text-muted small px-2 py-1">Belum ada device.</div>';
        } else {
            state.devices.forEach(d => {
                const el = document.createElement('div');
                el.className = 'topo-panel-item';
                el.innerHTML = `
                    <span class="status-dot ${d.status || 'unknown'}"></span>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="item-name">${escapeHtml(d.name)}</div>
                        <div class="item-ip">${escapeHtml(d.ip_address || '')}</div>
                    </div>`;
                devsEl.appendChild(el);
            });
        }
    }

    // ═══ HELPERS ═══
    function createEl(tag, attrs, text) {
        const el = document.createElementNS(SVG_NS, tag);
        if (attrs) for (const k in attrs) {
            if (attrs[k] !== null && attrs[k] !== undefined) el.setAttribute(k, attrs[k]);
        }
        if (text !== undefined && text !== null) el.textContent = text;
        return el;
    }

    function escapeHtml(s) {
        return String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));
    }

    function getSvgPoint(e) {
        const pt = svg.createSVGPoint();
        pt.x = e.clientX;
        pt.y = e.clientY;
        return pt.matrixTransform(gTransform.getScreenCTM().inverse());
    }

    // ═══ DRAG HANDLERS ═══
    function onNodeMouseDown(e, dev) {
        if (e.button !== 0 || state.isConnectMode) return;
        e.preventDefault();
        e.stopPropagation();
        state.drag.active = true;
        state.drag.target = dev;
        state.drag.targetType = 'device';
        state.drag.moved = false;
        const pt = getSvgPoint(e);
        state.drag.offsetX = pt.x - (dev.pos_x ?? 100);
        state.drag.offsetY = pt.y - (dev.pos_y ?? 100);
        e.currentTarget.classList.add('is-dragging');
    }

    function onPopMouseDown(e, area) {
        if (e.button !== 0) return;
        e.preventDefault();
        e.stopPropagation();
        state.drag.active = true;
        state.drag.target = area;
        state.drag.targetType = 'pop';
        state.drag.moved = false;
        const pt = getSvgPoint(e);
        state.drag.offsetX = pt.x - (area.pos_x || 40);
        state.drag.offsetY = pt.y - (area.pos_y || 40);
    }

    document.addEventListener('mousemove', e => {
        // Pan dulu (prioritas)
        if (isPanning) {
            view.panX = e.clientX - panStartX;
            view.panY = e.clientY - panStartY;
            applyTransform();
            return;
        }

        if (!state.drag.active) return;
        const pt = getSvgPoint(e);

        if (state.drag.targetType === 'device') {
            state.drag.target.pos_x = Math.round(pt.x - state.drag.offsetX);
            state.drag.target.pos_y = Math.round(pt.y - state.drag.offsetY);
            state.drag.moved = true;
            const g = root.querySelector(`.topo-node[data-id="${state.drag.target.id}"]`);
            if (g) g.setAttribute('transform', `translate(${state.drag.target.pos_x},${state.drag.target.pos_y})`);

        } else if (state.drag.targetType === 'pop') {
            state.drag.target.pos_x = Math.round(pt.x - state.drag.offsetX);
            state.drag.target.pos_y = Math.round(pt.y - state.drag.offsetY);
            state.drag.moved = true;
            const g = root.querySelector(`.topo-area-group[data-id="${state.drag.target.id}"]`);
            if (g) {
                const rect = g.querySelector('rect');
                const text = g.querySelector('text');
                if (rect) {
                    rect.setAttribute('x', state.drag.target.pos_x);
                    rect.setAttribute('y', state.drag.target.pos_y);
                }
                if (text) {
                    text.setAttribute('x', state.drag.target.pos_x + 14);
                    text.setAttribute('y', state.drag.target.pos_y + 24);
                }
            }
        }
    });

    document.addEventListener('mouseup', () => {
        // Selesai pan
        if (isPanning) {
            isPanning = false;
            canvasEl.classList.remove('is-panning');
            return;
        }

        if (!state.drag.active) return;

        const target = state.drag.target;
        const type = state.drag.targetType;
        const moved = state.drag.moved;

        state.drag.active = false;
        state.drag.target = null;
        state.drag.targetType = null;

        if (!moved || !target) return;

        if (type === 'device') {
            fetch(`/admin/topology-maps/${state.map.id}/devices/${target.id}/position`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ pos_x: target.pos_x, pos_y: target.pos_y }),
            }).catch(() => toast('Gagal simpan posisi device', true));

        } else if (type === 'pop') {
            fetch(`/admin/topology-maps/${state.map.id}/areas/${target.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ pos_x: target.pos_x, pos_y: target.pos_y }),
            }).catch(() => toast('Gagal simpan posisi POP', true));
        }

        render();
    });

    // ═══ CONNECT MODE ═══
    document.getElementById('btnConnectMode').addEventListener('click', function () {
        state.isConnectMode = !state.isConnectMode;
        state.selectedForConnect = null;
        this.classList.toggle('active', state.isConnectMode);
        render();
        if (state.isConnectMode) toast('Klik device sumber, lalu device tujuan.');
    });

    function onNodeClick(dev) {
        if (!state.isConnectMode) return;
        if (!state.selectedForConnect) {
            state.selectedForConnect = dev;
            render();
            toast(`Sumber: ${dev.name}. Pilih device tujuan.`);
        } else if (state.selectedForConnect.id === dev.id) {
            state.selectedForConnect = null;
            render();
        } else {
            createConnection(state.selectedForConnect.id, dev.id);
            state.selectedForConnect = null;
            state.isConnectMode = false;
            document.getElementById('btnConnectMode').classList.remove('active');
            render();
        }
    }

    function createConnection(fromId, toId) {
        fetch(urls.storeConnection, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                from_map_device_id: fromId,
                to_map_device_id: toId,
                direction: 'forward',
            }),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                state.connections.push(res.connection);
                toast('Relasi berhasil dibuat.');
                render();
            } else {
                toast(res.message || 'Gagal buat relasi', true);
            }
        })
        .catch(() => toast('Error koneksi', true));
    }

    function deleteConnection(conn) {
        if (!confirm('Hapus relasi ini?')) return;
        fetch(`/admin/topology-maps/${state.map.id}/connections/${conn.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                state.connections = state.connections.filter(c => c.id !== conn.id);
                toast('Relasi dihapus.');
                render();
            }
        });
    }

    // ═══ MODAL TAMBAH POP ═══
    const modalAddPop = new bootstrap.Modal(document.getElementById('modalAddPop'));
    document.getElementById('btnAddPop').addEventListener('click', () => modalAddPop.show());

    document.getElementById('formAddPop').addEventListener('submit', e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const payload = {
            name:         fd.get('name'),
            border_color: fd.get('border_color'),
            bg_color:     fd.get('bg_color'),
            pos_x: 80 + ((state.areas.length * 280) % 1300),
            pos_y: 80 + Math.floor(state.areas.length / 4) * 220,
            width:  240,
            height: 200,
        };

        fetch(urls.storeArea, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                state.areas.push(res.area);
                toast('POP berhasil ditambahkan.');
                modalAddPop.hide();
                e.target.reset();
                render();
                fitToContent();
            } else {
                toast(res.message || 'Gagal simpan POP', true);
            }
        })
        .catch(() => toast('Error koneksi', true));
    });

    // ═══ MODAL TAMBAH DEVICE ═══
    const deviceTypeIcon   = document.getElementById('deviceTypeIcon');
    const selectDeviceType = document.getElementById('selectDeviceType');

    function updateDevicePreview() {
        if (!deviceTypeIcon || !selectDeviceType) return;
        deviceTypeIcon.src = `${ICON_BASE}/${getIconFile(selectDeviceType.value)}`;
    }
    if (selectDeviceType) {
        selectDeviceType.addEventListener('change', updateDevicePreview);
        updateDevicePreview();
    }

    const modalAddDevice = new bootstrap.Modal(document.getElementById('modalAddDevice'));
    document.getElementById('btnAddDevice').addEventListener('click', () => modalAddDevice.show());

    document.getElementById('formAddDevice').addEventListener('submit', e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const payload = {
            name:                 fd.get('name'),
            type:                 fd.get('type'),
            protocol:             fd.get('protocol'),
            ip_address:           fd.get('ip_address'),
            router_version:       fd.get('router_version') || null,
            topology_map_area_id: fd.get('topology_map_area_id') || null,
            pos_x: 150 + ((state.devices.length * 90) % 1200),
            pos_y: 150 + Math.floor(state.devices.length / 12) * 110,
        };

        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch(`/admin/topology-maps/${state.map.id}/devices/new`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"></em>Simpan Perangkat';
            if (res.success) {
                state.devices.push(res.map_device);
                toast(`${payload.name} berhasil ditambahkan.`);
                modalAddDevice.hide();
                e.target.reset();
                render();
                fitToContent();
            } else {
                toast(res.message || 'Gagal tambah device', true);
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"></em>Simpan Perangkat';
            toast('Error koneksi', true);
        });
    });

    // ═══ SIMPAN LAYOUT ═══
    document.getElementById('btnSaveLayout').addEventListener('click', function () {
        const btn = this;
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        const payload = {
            areas: state.areas.map(a => ({
                id: a.id, pos_x: a.pos_x, pos_y: a.pos_y,
                width: a.width, height: a.height,
            })),
            devices: state.devices.map(d => ({
                id: d.id, pos_x: d.pos_x, pos_y: d.pos_y,
            })),
        };

        fetch(`/admin/topology-maps/${state.map.id}/save-layout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = original;
            if (res.success) toast('Layout berhasil disimpan.');
            else toast(res.message || 'Gagal simpan layout', true);
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = original;
            toast('Error koneksi', true);
        });
    });

    // ═══ TOAST ═══
    function toast(msg, isError) {
        const ex = document.getElementById('topoToast');
        if (ex) ex.remove();
        const t = document.createElement('div');
        t.id = 'topoToast';
        t.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:999999;padding:10px 20px;border-radius:24px;font-size:13px;font-weight:700;color:#fff;background:' + (isError ? '#dc2626' : '#059669') + ';box-shadow:0 6px 18px rgba(0,0,0,.3);';
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transition = 'opacity .4s';
            setTimeout(() => t.remove(), 400);
        }, 2800);
    }

    // ═══ INIT ═══
    applyTransform();
    render();
    fitToContent();

    window.TopoMap = { state, render, resetView, view, fitToContent };
})();
</script>
@endpush