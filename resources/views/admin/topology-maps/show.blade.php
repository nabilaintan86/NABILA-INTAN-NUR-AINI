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
        margin-bottom: 16px;
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

    /* ══ CANVAS ══ */
    .topo-canvas-wrap {
        display: flex;
        gap: 12px;
        height: calc(100vh - 320px);
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
    .topo-canvas svg {
        width: 100%;
        height: 100%;
        display: block;
    }

    /* ══ NODE & EDGE ══ */
    .topo-node { cursor: grab; user-select: none; }
    .topo-node.is-dragging { cursor: grabbing; opacity: .8; }
    .topo-node.selected image { filter: brightness(1.2) drop-shadow(0 0 12px #f59e0b); }
    .topo-node:hover image { filter: brightness(1.12); }
    .topo-edge { stroke: #16a34a; stroke-width: 2; fill: none; cursor: pointer; }
    .topo-edge:hover { stroke: #f59e0b; stroke-width: 4; }
    .topo-area-box { fill: #f8fafc; stroke-width: 2.5; }

    /* ══ MODAL ══ */
    .topo-modal-device-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .topo-device-pick {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 6px;
        cursor: pointer;
        transition: all .12s;
    }
    .topo-device-pick:hover {
        border-color: #4F46E5;
        background: #f5f3ff;
    }
    .topo-device-pick.selected {
        border-color: #4F46E5;
        background: #ede9fe;
        box-shadow: 0 0 0 2px rgba(79,70,229,.2);
    }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════
     TABS MAPS (navigasi antar maps)
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
        <svg id="topoSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 1000" preserveAspectRatio="xMidYMid meet">
            <defs>
                <marker id="arrowEnd" viewBox="0 0 14 14" refX="12" refY="7"
                        markerWidth="8" markerHeight="8" orient="auto">
                    <path d="M 1 2 L 13 7 L 1 12 z" fill="#16a34a"/>
                </marker>
            </defs>
            <g id="topoRoot"></g>
        </svg>
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
            <div class="modal-header bg-warning">
                <h6 class="modal-title"><em class="icon ni ni-server me-1"></em>Pilih Device untuk Ditambahkan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="searchDevice"
                       placeholder="Cari nama / IP device...">
                <div class="topo-modal-device-list" id="availableDeviceList">
                    <div class="text-center text-muted py-4">
                        <span class="spinner-border spinner-border-sm"></span> Memuat...
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="text-muted small me-auto">
                    Device yang sudah ada di map lain tidak akan muncul di sini.
                </span>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
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

    // ─── INIT ─────────────────────────────────────────────
    const SVG_NS = 'http://www.w3.org/2000/svg';
    const ICON_SIZE = 44;

    const svg  = document.getElementById('topoSvg');
    const root = document.getElementById('topoRoot');

    const state = {
        map: { id: parseInt(document.getElementById('currentMapId').value) },
        areas: window.TOPO_INITIAL_DATA.areas || [],
        devices: window.TOPO_INITIAL_DATA.devices || [],
        connections: window.TOPO_INITIAL_DATA.connections || [],
        selectedForConnect: null,
        isConnectMode: false,
        drag: { active: false, target: null, offsetX: 0, offsetY: 0 },
    };

    const urls = {
        data: document.getElementById('currentMapDataUrl').value,
        availableDevices: document.getElementById('availableDevicesUrl').value,
        storeDevice: document.getElementById('storeDeviceUrl').value,
        storeArea: document.getElementById('storeAreaUrl').value,
        storeConnection: document.getElementById('storeConnectionUrl').value,
    };

    const csrf = window.CSRF_TOKEN;

    // ─── RENDER CANVAS ────────────────────────────────────
    function render() {
        root.innerHTML = '';

        // 1. Render AREAS (POP)
        state.areas.forEach(area => {
            const g = createEl('g', { 
                class: 'topo-area-group',
                'data-id': area.id 
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
            g.addEventListener('mousedown', e => onPopMouseDown(e, area));
            g.style.cursor = 'move';
            root.appendChild(g);
        });

        // 2. Render CONNECTIONS (edges)
        state.connections.forEach(conn => {
            const from = state.devices.find(d => d.id === conn.from_map_device_id);
            const to   = state.devices.find(d => d.id === conn.to_map_device_id);
            if (!from || !to) return;

            const path = createEl('path', {
                class: 'topo-edge',
                d: computeEdgePath(from, to),
                'marker-end': 'url(#arrowEnd)',
            });
            path.addEventListener('click', () => deleteConnection(conn));
            root.appendChild(path);
        });

        // 3. Render DEVICES (nodes)
        state.devices.forEach(dev => {
            const x = dev.pos_x ?? 100;
            const y = dev.pos_y ?? 100;

            const g = createEl('g', {
                class: 'topo-node' + (state.selectedForConnect?.id === dev.id ? ' selected' : ''),
                transform: `translate(${x},${y})`,
                'data-id': dev.id,
            });

            // Status dot
            const statusColor = {
                up: '#22c55e', down: '#ef4444',
                warning: '#f59e0b', unknown: '#94a3b8'
            }[dev.status] || '#94a3b8';

            g.appendChild(createEl('rect', {
                x: -40, y: -40, width: 80, height: 88,
                fill: 'transparent', 'pointer-events': 'all',
            }));

            g.appendChild(createEl('circle', {
                cx: 0, cy: 0, r: 24,
                fill: '#fff',
                stroke: statusColor,
                'stroke-width': 3,
            }));

            g.appendChild(createEl('text', {
                x: 0, y: 5, 'text-anchor': 'middle',
                'font-size': 16, 'font-weight': 800, fill: '#1e293b',
                'pointer-events': 'none',
            }, (dev.name || '?').charAt(0).toUpperCase()));

            g.appendChild(createEl('rect', {
                x: -55, y: 32, width: 110, height: 16, rx: 3,
                fill: '#fff', stroke: '#1e293b', 'stroke-width': 1,
            }));
            g.appendChild(createEl('text', {
                x: 0, y: 44, 'text-anchor': 'middle',
                'font-size': 10.5, 'font-weight': 700, fill: '#0f172a',
                'pointer-events': 'none',
            }, (dev.name || '').substring(0, 16)));

            g.appendChild(createEl('text', {
                x: 0, y: 60, 'text-anchor': 'middle',
                'font-size': 9, fill: '#64748b',
                'pointer-events': 'none',
            }, dev.ip_address || ''));

            // Drag & click handlers
            g.addEventListener('mousedown', e => onNodeMouseDown(e, dev));
            g.addEventListener('click', () => onNodeClick(dev));

            root.appendChild(g);
        });

        renderPanel();
        document.getElementById('itemCount').textContent =
            state.devices.length + state.areas.length;
    }

    // ─── RENDER PANEL KIRI ────────────────────────────────
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
                el.addEventListener('click', () => focusArea(a));
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
                el.addEventListener('click', () => focusDevice(d));
                devsEl.appendChild(el);
            });
        }
    }

    // ─── HELPER: SVG ──────────────────────────────────────
    function createEl(tag, attrs, text) {
        const el = document.createElementNS(SVG_NS, tag);
        if (attrs) for (const k in attrs) {
            if (attrs[k] !== null && attrs[k] !== undefined) el.setAttribute(k, attrs[k]);
        }
        if (text !== undefined && text !== null) el.textContent = text;
        return el;
    }

    function computeEdgePath(from, to) {
        return `M ${from.pos_x ?? 100} ${from.pos_y ?? 100} L ${to.pos_x ?? 100} ${to.pos_y ?? 100}`;
    }

    function escapeHtml(s) {
        return String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));
    }

    // ─── DRAG & DROP ──────────────────────────────────────
    let scale = 1; // untuk konversi koordinat

    function onNodeMouseDown(e, dev) {
        if (e.button !== 0 || state.isConnectMode) return;
        e.preventDefault();
        e.stopPropagation();

        state.drag.active = true;
        state.drag.target = dev;
        state.drag.moved = false;

        const pt = getSvgPoint(e);
        state.drag.offsetX = pt.x - (dev.pos_x ?? 100);
        state.drag.offsetY = pt.y - (dev.pos_y ?? 100);

        e.currentTarget.classList.add('is-dragging');
    }
    // ─── DRAG POP ─────────────────────────────────────────
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
        // Handle drag POP
        if (state.drag.active && state.drag.targetType === 'pop') {
            const pt = getSvgPoint(e);
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
            return;
        }
        if (!state.drag.active) return;
        const pt = getSvgPoint(e);
        state.drag.target.pos_x = Math.round(pt.x - state.drag.offsetX);
        state.drag.target.pos_y = Math.round(pt.y - state.drag.offsetY);
        state.drag.moved = true;

        // Re-render only the dragged node + its edges
        const g = root.querySelector(`.topo-node[data-id="${state.drag.target.id}"]`);
        if (g) g.setAttribute('transform', `translate(${state.drag.target.pos_x},${state.drag.target.pos_y})`);

        // Update edges
        state.connections.forEach(conn => {
            if (conn.from_map_device_id === state.drag.target.id ||
                conn.to_map_device_id === state.drag.target.id) {
                const from = state.devices.find(d => d.id === conn.from_map_device_id);
                const to   = state.devices.find(d => d.id === conn.to_map_device_id);
                if (from && to) {
                    // Re-render edge (simple approach: full re-render)
                    render();
                }
            }
        });
    });

    document.addEventListener('mouseup', e => {
        // Handle simpan POP setelah drag
        if (state.drag.active && state.drag.targetType === 'pop') {
            const area = state.drag.target;
            const moved = state.drag.moved;
            state.drag.active = false;
            state.drag.target = null;
            state.drag.targetType = null;

            if (moved && area.id) {
                fetch(`/admin/topology-maps/${state.map.id}/areas/${area.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        pos_x: area.pos_x,
                        pos_y: area.pos_y,
                    }),
                }).catch(() => toast('Gagal simpan posisi POP', true));
            }
            return;
        }
        if (!state.drag.active) return;
        const dev = state.drag.target;
        const moved = state.drag.moved;
        state.drag.active = false;
        state.drag.target = null;

        if (moved && dev) {
            // Save position
            fetch(`/admin/topology-maps/${state.map.id}/devices/${dev.id}/position`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    pos_x: dev.pos_x,
                    pos_y: dev.pos_y,
                }),
            }).catch(() => toast('Gagal simpan posisi', true));
        }
    });

    function getSvgPoint(e) {
        const pt = svg.createSVGPoint();
        pt.x = e.clientX;
        pt.y = e.clientY;
        return pt.matrixTransform(svg.getScreenCTM().inverse());
    }

    // ─── CONNECT MODE ─────────────────────────────────────
    const btnConnect = document.getElementById('btnConnectMode');

    btnConnect.addEventListener('click', () => {
        state.isConnectMode = !state.isConnectMode;
        state.selectedForConnect = null;
        btnConnect.classList.toggle('active', state.isConnectMode);
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
            btnConnect.classList.remove('active');
            render();
        }
    }

    // ─── CREATE: CONNECTION ───────────────────────────────
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

    // ─── CREATE: POP ──────────────────────────────────────
    const modalAddPop = new bootstrap.Modal(document.getElementById('modalAddPop'));
    document.getElementById('btnAddPop').addEventListener('click', () => modalAddPop.show());

    document.getElementById('formAddPop').addEventListener('submit', e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const payload = {
            name: fd.get('name'),
            border_color: fd.get('border_color'),
            bg_color: fd.get('bg_color'),
            pos_x: 60 + (state.areas.length * 40),
            pos_y: 60 + (state.areas.length * 30),
            width: 240,
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
            } else {
                toast(res.message || 'Gagal simpan POP', true);
            }
        });
    });

    // ─── CREATE: DEVICE ───────────────────────────────────
    const modalAddDevice = new bootstrap.Modal(document.getElementById('modalAddDevice'));
    document.getElementById('btnAddDevice').addEventListener('click', () => {
        modalAddDevice.show();
        loadAvailableDevices();
    });

    let availableDevicesCache = [];

    function loadAvailableDevices() {
        const list = document.getElementById('availableDeviceList');
        list.innerHTML = '<div class="text-center text-muted py-4"><span class="spinner-border spinner-border-sm"></span> Memuat...</div>';

        fetch(urls.availableDevices, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                availableDevicesCache = data.devices || [];
                renderAvailableDevices(availableDevicesCache);
            })
            .catch(() => {
                list.innerHTML = '<div class="text-center text-danger py-4">Gagal memuat device.</div>';
            });
    }

    function renderAvailableDevices(items) {
        const list = document.getElementById('availableDeviceList');
        if (!items.length) {
            list.innerHTML = '<div class="text-center text-muted py-4">Semua device sudah masuk ke map. Tidak ada device baru yang tersedia.</div>';
            return;
        }
        list.innerHTML = '';
        items.forEach(d => {
            const el = document.createElement('div');
            el.className = 'topo-device-pick';
            el.innerHTML = `
                <span class="status-dot ${d.status || 'unknown'}" style="width:10px;height:10px;border-radius:50%;background:#94a3b8;display:inline-block;"></span>
                <div class="flex-grow-1">
                    <div class="fw-bold">${escapeHtml(d.name)}</div>
                    <div class="text-muted small">${escapeHtml(d.ip_address || '-')} • ${escapeHtml(d.type || '-')}</div>
                </div>
                <button class="btn btn-sm btn-primary">Tambah</button>
            `;
            el.querySelector('button').addEventListener('click', () => addDeviceToMap(d));
            list.appendChild(el);
        });
    }

    function addDeviceToMap(device) {
        const payload = {
            device_id: device.id,
            pos_x: 100 + (state.devices.length * 80) % 600,
            pos_y: 100 + Math.floor(state.devices.length / 6) * 100,
        };

        fetch(urls.storeDevice, {
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
                state.devices.push(res.map_device);
                toast(`${device.name} berhasil ditambahkan.`);
                modalAddDevice.hide();
                render();
            } else {
                toast(res.message || 'Gagal tambah device', true);
            }
        })
        .catch(() => toast('Error koneksi', true));
    }

    // Search device
    document.getElementById('searchDevice').addEventListener('input', e => {
        const q = e.target.value.toLowerCase();
        const filtered = availableDevicesCache.filter(d =>
            (d.name || '').toLowerCase().includes(q) ||
            (d.ip_address || '').toLowerCase().includes(q)
        );
        renderAvailableDevices(filtered);
    });

    // ─── FOCUS / HIGHLIGHT ────────────────────────────────
    function focusDevice(d) {
        const g = root.querySelector(`.topo-node[data-id="${d.id}"]`);
        if (g) {
            g.classList.add('selected');
            setTimeout(() => g.classList.remove('selected'), 2000);
        }
    }

    function focusArea(a) {
        // simple: scroll ke area (skip)
    }

    // ─── TOAST ────────────────────────────────────────────
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

    // ─── INIT RENDER ──────────────────────────────────────
    render();

    // Expose buat debug
    window.TopoMap = { state, render };
})();
</script>
@endpush