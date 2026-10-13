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
    .topo-panel-item .item-icon {
        width: 28px;
        height: 28px;
        object-fit: contain;
        flex-shrink: 0;
    }
    .topo-panel-item .item-name { flex: 1; font-weight: 600; color: #1e293b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .topo-panel-item .item-ip { font-size: 10.5px; color: #94a3b8; font-family: monospace; }
    .topo-panel-empty-hint {
        font-size: 10.5px;
        color: #94a3b8;
        padding: 6px 10px;
        line-height: 1.4;
    }
    .topo-panel-item[draggable="true"] { cursor: grab; }
    .topo-panel-item[draggable="true"]:active { cursor: grabbing; opacity: .7; }
    .topo-panel-item.is-placed { opacity: .55; cursor: default; background: #f8fafc; }
    .topo-panel-item .badge-placed {
        color: #059669;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
    }

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
    .topo-canvas.drag-over {
        border-color: #10b981;
        box-shadow: inset 0 0 0 4px rgba(16,185,129,.2);
    }
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
    .topo-area-resize-handle {
        cursor: nwse-resize;
        fill: #2563eb;
        stroke: #fff;
        stroke-width: 2;
    }
    .topo-area-resize-handle:hover {
        fill: #ef4444;
    }

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

    /* ══ MODAL DETAIL ══ */
    .nm-info-row {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }
    .nm-info-label { color: #64748b; font-weight: 500; }
    .nm-info-value { font-weight: 700; color: #0f172a; }

    .nm-stat-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin-top: 8px;
    }
    .nm-stat-table th,
    .nm-stat-table td {
        border: 1px solid #e2e8f0;
        padding: 6px 10px;
    }
    .nm-stat-table th {
        background: #f8fafc;
        font-weight: 700;
        color: #475569;
    }

    .nm-log-list {
        max-height: 200px;
        overflow-y: auto;
        background: #0f172a;
        border-radius: 6px;
        padding: 8px 12px;
        font-family: monospace;
        font-size: 11.5px;
        color: #cbd5e1;
        margin-top: 8px;
    }
    .nm-log-list .log-down    { color: #f87171; }
    .nm-log-list .log-up      { color: #4ade80; }
    .nm-log-list .log-warning { color: #fbbf24; }

    /* ══ MODAL CONNECTION ══ */
    .conn-dir-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        background: #fff;
        border-radius: 8px;
        cursor: pointer;
        transition: all .15s;
        font-size: 13px;
        color: #1e293b;
    }
    .conn-dir-btn:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    .conn-dir-btn.active {
        border-color: #2563eb;
        background: #dbeafe;
        box-shadow: 0 0 0 2px rgba(37,99,235,.2);
    }
    .conn-dir-btn small {
        display: block;
        font-size: 11px;
        margin-top: 2px;
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
        @if(auth()->user()->role === 'super_admin')
        <a href="{{ route('admin.topology-maps.create') }}" class="topo-tab-add">
            <em class="icon ni ni-plus"></em> Maps Baru
        </a>
    @endif
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
        <svg id="topoSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1600 1000" preserveAspectRatio="xMidYMid meet">
            <defs>
                <marker id="arrowEnd" viewBox="0 0 14 14" refX="12" refY="7"
                        markerWidth="8" markerHeight="8" orient="auto">
                    <path d="M 1 2 L 13 7 L 1 12 z" fill="#16a34a"/>
                </marker>
                <marker id="arrowStart" viewBox="0 0 14 14" refX="2" refY="7"
                        markerWidth="8" markerHeight="8" orient="auto">
                    <path d="M 13 2 L 1 7 L 13 12 z" fill="#16a34a"/>
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
            <button type="button" class="topo-zoom-btn topo-zoom-reset" id="btnZoomReset" title="Reset View">
                <em class="icon ni ni-reload"></em>
            </button>
        </div>
    </div>

</div>

<div class="modal fade" id="modalAddPop" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formAddPop">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title text-white"><em class="icon ni ni-layers me-1"></em>Tambah POP / Area</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- Row 1: Nama + Kode --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Area / POP <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   placeholder="Contoh: POP Rungkut, POP Bandara">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Area (Singkatan)</label>
                            <input type="text" name="code" class="form-control"
                                   placeholder="Contoh: POP-RKT">
                        </div>
                    </div>

                    {{-- Row 2: Size & Posisi --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Lebar Card (W px)</label>
                            <input type="number" name="width" id="popWidth" class="form-control"
                                   min="100" max="2000" value="280">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tinggi Card (H px)</label>
                            <input type="number" name="height" id="popHeight" class="form-control"
                                   min="80" max="2000" value="220">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Posisi Awal X (px)</label>
                            <input type="number" name="pos_x" class="form-control" placeholder="Auto">
                            <small class="text-muted" style="font-size:10.5px">Kosongkan untuk auto</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Posisi Awal Y (px)</label>
                            <input type="number" name="pos_y" class="form-control" placeholder="Auto">
                            <small class="text-muted" style="font-size:10.5px">Kosongkan untuk auto</small>
                        </div>
                    </div>

                    {{-- Row 3: Warna --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Warna Border</label>
                            <div class="d-flex gap-2">
                                <input type="color" name="border_color" id="popBorderColor"
                                       class="form-control form-control-color" value="#2563eb"
                                       style="max-width:44px;height:38px;padding:3px;">
                                <input type="text" class="form-control" value="#2563eb" readonly
                                       id="popBorderText" style="font-family:monospace;font-size:12px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Warna Background</label>
                            <div class="d-flex gap-2">
                                <input type="color" name="bg_color" id="popBgColor"
                                       class="form-control form-control-color" value="#f8fafc"
                                       style="max-width:44px;height:38px;padding:3px;">
                                <input type="text" class="form-control" value="#f8fafc" readonly
                                       id="popBgText" style="font-family:monospace;font-size:12px;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Warna Judul</label>
                            <div class="d-flex gap-2">
                                <input type="color" name="title_color" id="popTitleColor"
                                       class="form-control form-control-color" value="#2563eb"
                                       style="max-width:44px;height:38px;padding:3px;">
                                <input type="text" class="form-control" value="#2563eb" readonly
                                       id="popTitleText" style="font-family:monospace;font-size:12px;">
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Keterangan --}}
                    <div class="mb-3">
                        <label class="form-label">Keterangan / Lokasi</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Catatan lokasi atau deskripsi teknis POP..."></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <em class="icon ni ni-check me-1"></em>Simpan Card Area
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
                        Perangkat baru masuk ke tabel <code>devices</code>. Setelah disimpan, device muncul
                        di panel kiri — <strong>drag ke canvas</strong> untuk menempatkan.
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
                            <option value="">-- Tanpa area --</option>
                            @foreach($map->areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
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

{{-- ══════════════════════════════════════════════════════
     MODAL DETAIL DEVICE
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalDeviceDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title text-white" id="detailDeviceName">Detail Perangkat</h6>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-warning fw-bold" id="btnOpenEditDevice">
                        <em class="icon ni ni-edit me-1"></em>Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-danger fw-bold" id="btnDeleteDevice">
                        <em class="icon ni ni-trash me-1"></em>Hapus
                    </button>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body">
                <div class="nm-info-row">
                    <span class="nm-info-label">Hostname</span>
                    <span class="nm-info-value" id="detailFieldName">-</span>
                </div>
                <div class="nm-info-row">
                    <span class="nm-info-label">IP Address</span>
                    <span class="nm-info-value" id="detailFieldIp">-</span>
                </div>
                <div class="nm-info-row">
                    <span class="nm-info-label">Area / POP</span>
                    <span class="nm-info-value" id="detailFieldArea">-</span>
                </div>
                <div class="nm-info-row">
                    <span class="nm-info-label">Status</span>
                    <span class="nm-info-value" id="detailFieldStatus">-</span>
                </div>
                <div class="nm-info-row">
                    <span class="nm-info-label">Last Poll</span>
                    <span class="nm-info-value" id="detailFieldLastPoll">-</span>
                </div>

                <h6 class="mt-3 mb-1 text-uppercase fw-bold text-muted small">Statistics</h6>
                <table class="nm-stat-table">
                    <thead>
                        <tr>
                            <th>Protocol</th>
                            <th>Total Polls</th>
                            <th>Responded</th>
                            <th>Missed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="statProtocol">-</td>
                            <td id="statPolls">-</td>
                            <td id="statResponded">-</td>
                            <td id="statMissed">-</td>
                        </tr>
                        <tr>
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

                <h6 class="mt-3 mb-1 text-uppercase fw-bold text-muted small">Recent Log Extract</h6>
                <div class="nm-log-list" id="detailLogs">Belum ada riwayat log.</div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL EDIT DEVICE
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEditDevice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="formEditDevice">
                <input type="hidden" name="map_device_id" id="editMapDeviceId">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title text-white">
                        <em class="icon ni ni-edit me-1"></em>Edit Perangkat
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <select name="type" id="editType" class="form-select" required style="flex:1">
                                    <option value="Router">Router</option>
                                    <option value="Switch">Switch</option>
                                    <option value="OLT">OLT</option>
                                    <option value="EPON">EPON</option>
                                    <option value="Server">Server</option>
                                    <option value="Access Point">Access Point</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="device-type-preview" title="Preview icon">
                                    <img id="editTypeIcon"
                                         src="{{ asset('images/devices/router.png') }}"
                                         alt="Preview"
                                         style="width:32px;height:32px;object-fit:contain;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Metode Monitoring <span class="text-danger">*</span></label>
                            <select name="protocol" id="editProtocol" class="form-select" required>
                                <option value="ICMP">ICMP</option>
                                <option value="HTTP">HTTP</option>
                                <option value="SNMP">SNMP</option>
                                <option value="TCP">TCP</option>
                                <option value="SSH">SSH</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" id="editIp" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Router Version</label>
                        <input type="text" name="router_version" id="editRouterVersion" class="form-control"
                               placeholder="Contoh: IOS-XE 17.3.1 (opsional)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">POP / Area</label>
                        <select name="topology_map_area_id" id="editArea" class="form-select">
                            <option value="">-- Tanpa area --</option>
                            @foreach($map->areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-save me-1"></em>Update Perangkat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL HUBUNGKAN DUA PERANGKAT (Relasi)
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalConnection" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title text-white">
                    <em class="icon ni ni-link me-1"></em>Hubungkan Dua Perangkat
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="text-center mb-3">
                    <div class="text-muted small">Sambungan:</div>
                    <div class="fw-bold" style="font-size:14px;">
                        <span id="connFromName">-</span>
                        <span id="connSymbol" class="text-primary px-1">→</span>
                        <span id="connToName">-</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe Media</label>
                    <select id="connType" class="form-select">
                        <option value="ethernet">Ethernet</option>
                        <option value="fiber">Fiber Optic</option>
                        <option value="wireless">Wireless Link</option>
                        <option value="serial">Serial / WAN</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Arah Panah</label>
                    <div class="d-grid gap-2">
                        <button type="button" class="conn-dir-btn" data-dir="forward">
                            <span class="fw-bold">→ Parent → Child Uplink</span>
                            <small class="text-muted">(Forward)</small>
                        </button>
                        <button type="button" class="conn-dir-btn" data-dir="reverse">
                            <span class="fw-bold">← Reverse</span>
                            <small class="text-muted">(Inbound Downlink)</small>
                        </button>
                        <button type="button" class="conn-dir-btn" data-dir="bidirectional">
                            <span class="fw-bold">↔ Dua Arah</span>
                            <small class="text-muted">(Active Peering / Duplex)</small>
                        </button>
                        <button type="button" class="conn-dir-btn" data-dir="none">
                            <span class="fw-bold">— Tanpa Panah</span>
                            <small class="text-muted">(Layer-2 Pasif)</small>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-danger" id="btnDeleteConnection">
                        <em class="icon ni ni-trash me-1"></em>Hapus Relasi
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSaveConnection">
                        <em class="icon ni ni-save me-1"></em>Simpan Relasi
                    </button>
                </div>
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
        // ViewBox fixed → konsisten, device nggak kegedean
        svg.setAttribute('viewBox', '0 0 1600 1000');
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

    // ═══ EVENT: DRAG OVER (dari panel) ═══
    canvasEl.addEventListener('dragover', e => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        canvasEl.classList.add('drag-over');
    });

    canvasEl.addEventListener('dragleave', e => {
        if (!canvasEl.contains(e.relatedTarget)) {
            canvasEl.classList.remove('drag-over');
        }
    });

    // ═══ EVENT: DROP DEVICE KE CANVAS ═══
    canvasEl.addEventListener('drop', e => {
        e.preventDefault();
        canvasEl.classList.remove('drag-over');

        const idStr = e.dataTransfer.getData('text/plain');
        const mapDeviceId = parseInt(idStr, 10);
        if (!mapDeviceId) return;

        const dev = state.devices.find(d => d.id === mapDeviceId);
        if (!dev) return;

        if (dev.pos_x !== null && dev.pos_y !== null) {
            toast('Device sudah ada di canvas.', true);
            return;
        }

        const pt = getSvgPoint(e);
        const posX = Math.round(pt.x);
        const posY = Math.round(pt.y);

        dev.pos_x = posX;
        dev.pos_y = posY;

        fetch(`/admin/topology-maps/${state.map.id}/devices/${dev.id}/position`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ pos_x: posX, pos_y: posY }),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                toast(`${dev.name} ditambahkan ke canvas.`);
                render();
            } else {
                toast(res.message || 'Gagal place device', true);
                dev.pos_x = null;
                dev.pos_y = null;
                render();
            }
        })
        .catch(() => {
            toast('Error koneksi', true);
            dev.pos_x = null;
            dev.pos_y = null;
            render();
        });
    });

    // ═══ RESIZE POP ═══
    let isResizingArea = false, resizedArea = null, resizeStart = { x: 0, y: 0, w: 0, h: 0 };

    function onAreaResizeStart(e, area) {
        if (e.button !== 0) return;
        e.stopPropagation();
        e.preventDefault();
        isResizingArea = true;
        resizedArea = area;
        const pt = getSvgPoint(e);
        resizeStart = {
            x: pt.x,
            y: pt.y,
            w: area.width || 140,
            h: area.height || 100,
        };
    }

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
                width: area.width || 140,
                height: area.height || 100,
                rx: 12,
                class: 'topo-area-box',
                stroke: area.border_color || '#4F46E5',
                fill: area.bg_color || '#f8fafc',
            }));

                g.appendChild(createEl('text', {
                x: (area.pos_x || 40) + 14,
                y: (area.pos_y || 40) + 24,
                fill: area.title_color || area.border_color || '#4F46E5',
                'font-size': 14,
                'font-weight': 800,
                'pointer-events': 'none',
            }, area.name));

            const handle = createEl('rect', {
                x: (area.pos_x || 40) + (area.width || 140) - 10,
                y: (area.pos_y || 40) + (area.height || 100) - 10,
                width: 14,
                height: 14,
                rx: 3,
                class: 'topo-area-resize-handle',
                'data-area-id': area.id,
            });
            handle.addEventListener('mousedown', e => onAreaResizeStart(e, area));
            g.appendChild(handle);

            g.style.cursor = 'move';
            g.addEventListener('mousedown', e => onPopMouseDown(e, area));

            root.appendChild(g);
        });

        // 2. EDGES
        state.connections.forEach(conn => {
            const from = state.devices.find(d => d.id === conn.from_map_device_id);
            const to   = state.devices.find(d => d.id === conn.to_map_device_id);
            if (!from || !to) return;
            if (from.pos_x === null || from.pos_y === null) return;
            if (to.pos_x === null || to.pos_y === null) return;

            const dir = conn.direction || 'forward';
            let markerEnd   = null;
            let markerStart = null;

            if (dir === 'forward')       markerEnd   = 'url(#arrowEnd)';
            else if (dir === 'reverse')  markerStart = 'url(#arrowStart)';
            else if (dir === 'bidirectional') {
                markerEnd   = 'url(#arrowEnd)';
                markerStart = 'url(#arrowStart)';
            }

            const path = createEl('path', {
                class: 'topo-edge',
                d: `M ${from.pos_x} ${from.pos_y} L ${to.pos_x} ${to.pos_y}`,
                'data-conn-id': conn.id,
            });
            if (markerEnd)   path.setAttribute('marker-end',   markerEnd);
            if (markerStart) path.setAttribute('marker-start', markerStart);

            path.addEventListener('click', e => {
                e.stopPropagation();
                openConnectionModal(conn.id);
            });
            root.appendChild(path);
        });

        // 3. DEVICES (cuma yang udah di-place)
        state.devices
            .filter(d => d.pos_x !== null && d.pos_y !== null)
            .forEach(dev => {
                const x = dev.pos_x;
                const y = dev.pos_y;

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

        // ── POP List ──
        if (!state.areas.length) {
            popsEl.innerHTML = '<div class="topo-panel-empty-hint">Belum ada POP.</div>';
        } else {
            state.areas.forEach(a => {
                const el = document.createElement('div');
                el.className = 'topo-panel-item';
                el.innerHTML = `<em class="icon ni ni-layers text-primary"></em>
                                <span class="item-name">${escapeHtml(a.name)}</span>`;
                popsEl.appendChild(el);
            });
        }

        // ── Device List ──
        if (!state.devices.length) {
            devsEl.innerHTML = '<div class="topo-panel-empty-hint">Belum ada device. Klik <strong>+ Tambah Device</strong> untuk mulai.</div>';
        } else {
            state.devices.forEach(d => {
                const isPlaced = d.pos_x !== null && d.pos_y !== null;
                const el = document.createElement('div');
                el.className = 'topo-panel-item' + (isPlaced ? ' is-placed' : '');

                if (isPlaced) {
                    el.innerHTML = `
                        <img class="item-icon"
                             src="${ICON_BASE}/${getIconFile(d.type)}"
                             alt="${escapeHtml(d.type || '')}">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="item-name">${escapeHtml(d.name)}</div>
                            <div class="item-ip">${escapeHtml(d.ip_address || '')}</div>
                        </div>
                        <span class="badge-placed">✓</span>`;
                    el.addEventListener('click', () => openDeviceDetail(d.id));
                } else {
                    el.setAttribute('draggable', 'true');
                    el.innerHTML = `
                        <img class="item-icon"
                             src="${ICON_BASE}/${getIconFile(d.type)}"
                             alt="${escapeHtml(d.type || '')}">
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="item-name">${escapeHtml(d.name)}</div>
                            <div class="item-ip">${escapeHtml(d.ip_address || '')}</div>
                        </div>`;

                    el.addEventListener('dragstart', e => {
                        e.dataTransfer.setData('text/plain', d.id.toString());
                        e.dataTransfer.effectAllowed = 'move';
                    });
                }

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

    // ═══ DRAG NODE / POP DI CANVAS ═══
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

        // Guard: pastikan koordinat valid
        const safeX = area.pos_x ?? 40;
        const safeY = area.pos_y ?? 40;
        area.pos_x = safeX;
        area.pos_y = safeY;

        state.drag.active = true;
        state.drag.target = area;
        state.drag.targetType = 'pop';
        state.drag.moved = false;
        const pt = getSvgPoint(e);
        state.drag.offsetX = pt.x - safeX;
        state.drag.offsetY = pt.y - safeY;

        // ── Capture device yang berada di dalam POP ini ──
        const aw = area.width || 140;
        const ah = area.height || 100;

        state.drag.insideDevices = [];
        state.devices.forEach(d => {
            if (d.pos_x === null || d.pos_y === null) return;
            if (d.pos_x >= safeX && d.pos_x <= safeX + aw &&
                d.pos_y >= safeY && d.pos_y <= safeY + ah) {
                state.drag.insideDevices.push({
                    dev: d,
                    offsetX: d.pos_x - safeX,
                    offsetY: d.pos_y - safeY,
                });
            }
        });
    }
    document.addEventListener('mousemove', e => {
        if (isPanning) {
            view.panX = e.clientX - panStartX;
            view.panY = e.clientY - panStartY;
            applyTransform();
            return;
        }

        if (isResizingArea && resizedArea) {
            const pt = getSvgPoint(e);
            const newW = Math.max(100, Math.round(resizeStart.w + (pt.x - resizeStart.x)));
            const newH = Math.max(70,  Math.round(resizeStart.h + (pt.y - resizeStart.y)));
            resizedArea.width = newW;
            resizedArea.height = newH;

            const g = root.querySelector(`.topo-area-group[data-id="${resizedArea.id}"]`);
            if (g) {
                const rect = g.querySelector('rect.topo-area-box');
                const handle = g.querySelector('.topo-area-resize-handle');
                if (rect) {
                    rect.setAttribute('width', newW);
                    rect.setAttribute('height', newH);
                }
                if (handle) {
                    handle.setAttribute('x', (resizedArea.pos_x || 40) + newW - 10);
                    handle.setAttribute('y', (resizedArea.pos_y || 40) + newH - 10);
                }
            }
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
            const newX = Math.round(pt.x - state.drag.offsetX);
            const newY = Math.round(pt.y - state.drag.offsetY);

            // Guard: skip kalau NaN
            if (!Number.isFinite(newX) || !Number.isFinite(newY)) return;

            state.drag.target.pos_x = newX;
            state.drag.target.pos_y = newY;
            state.drag.moved = true;

            // Update visual POP
            const g = root.querySelector(`.topo-area-group[data-id="${state.drag.target.id}"]`);
            if (g) {
                const rect = g.querySelector('rect.topo-area-box');
                const text = g.querySelector('text');
                const handle = g.querySelector('.topo-area-resize-handle');
                if (rect) {
                    rect.setAttribute('x', newX);
                    rect.setAttribute('y', newY);
                }
                if (text) {
                    text.setAttribute('x', newX + 14);
                    text.setAttribute('y', newY + 24);
                }
                if (handle) {
                    handle.setAttribute('x', newX + (state.drag.target.width || 140) - 10);
                    handle.setAttribute('y', newY + (state.drag.target.height || 100) - 10);
                }
            }

            // ── Update device pakai OFFSET (bukan delta) — KUNCI ANTI NUMPUK ──
            const movedIds = [];
            (state.drag.insideDevices || []).forEach(item => {
                const nd = item.dev;

                // Posisi device = posisi POP baru + offset yang disimpan saat dragstart
                nd.pos_x = newX + item.offsetX;
                nd.pos_y = newY + item.offsetY;

                // Guard: clamp
                if (!Number.isFinite(nd.pos_x)) nd.pos_x = 100;
                if (!Number.isFinite(nd.pos_y)) nd.pos_y = 100;

                movedIds.push(nd.id);

                const ng = root.querySelector(`.topo-node[data-id="${nd.id}"]`);
                if (ng) ng.setAttribute('transform', `translate(${nd.pos_x},${nd.pos_y})`);
            });

            // Update edges
            if (movedIds.length) {
                state.connections.forEach(conn => {
                    const fromMoved = movedIds.includes(conn.from_map_device_id);
                    const toMoved   = movedIds.includes(conn.to_map_device_id);
                    if (!fromMoved && !toMoved) return;

                    const from = state.devices.find(d => d.id === conn.from_map_device_id);
                    const to   = state.devices.find(d => d.id === conn.to_map_device_id);
                    if (!from || !to) return;
                    if (from.pos_x === null || to.pos_x === null) return;

                    const path = root.querySelector(`.topo-edge[data-conn-id="${conn.id}"]`);
                    if (path) {
                        path.setAttribute('d', `M ${from.pos_x} ${from.pos_y} L ${to.pos_x} ${to.pos_y}`);
                    }
                });
            }
        }
});

    document.addEventListener('mouseup', () => {
        if (isPanning) {
            isPanning = false;
            canvasEl.classList.remove('is-panning');
            return;
        }

        if (isResizingArea) {
            isResizingArea = false;
            const ar = resizedArea;
            resizedArea = null;
            if (ar && ar.id) {
                fetch(`/admin/topology-maps/${state.map.id}/areas/${ar.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        width:  ar.width,
                        height: ar.height,
                    }),
                }).catch(() => toast('Gagal simpan ukuran POP', true));
            }
            render();
            return;
        }

        if (!state.drag.active) return;

        const target = state.drag.target;
        const type = state.drag.targetType;
        const moved = state.drag.moved;

        const savedInsideDevices = state.drag.insideDevices || [];   // ← BARU

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
            // 1. Simpan posisi POP
            fetch(`/admin/topology-maps/${state.map.id}/areas/${target.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ pos_x: target.pos_x, pos_y: target.pos_y }),
            }).catch(() => toast('Gagal simpan posisi POP', true));

            // 2. Simpan posisi SEMUA device yang ikut kegeser
            savedInsideDevices.forEach(item => {
                const d = item.dev;
                fetch(`/admin/topology-maps/${state.map.id}/devices/${d.id}/position`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ pos_x: d.pos_x, pos_y: d.pos_y }),
                }).catch(() => {});
            });
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
        if (state.isConnectMode) {
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
            return;
        }
        openDeviceDetail(dev.id);
    }

    function createConnection(fromId, toId) {
        openCreateConnectionModal(fromId, toId);
    }

    // ═══ MODAL TAMBAH POP ═══
    const modalAddPop = new bootstrap.Modal(document.getElementById('modalAddPop'));
    ['popBorder', 'popBg', 'popTitle'].forEach(function(prefix) {
        const colorEl = document.getElementById(prefix + 'Color');
        const textEl  = document.getElementById(prefix + 'Text');
        if (colorEl && textEl) {
            colorEl.addEventListener('input', () => { textEl.value = colorEl.value; });
        }
    });
    document.getElementById('btnAddPop').addEventListener('click', () => modalAddPop.show());
    document.getElementById('formAddPop').addEventListener('submit', e => {
        e.preventDefault();
        const fd = new FormData(e.target);

        const w  = parseInt(fd.get('width'))  || 280;
        const h  = parseInt(fd.get('height')) || 220;
        const px = fd.get('pos_x');
        const py = fd.get('pos_y');

        const payload = {
            name:         fd.get('name'),
            code:         fd.get('code') || null,
            description:  fd.get('description') || null,
            border_color: fd.get('border_color') || '#2563eb',
            bg_color:     fd.get('bg_color') || '#f8fafc',
            title_color:  fd.get('title_color') || '#2563eb',
            width:  w,
            height: h,
            pos_x: (px !== '' && px !== null) ? parseInt(px) : (80 + ((state.areas.length * 320) % 1300)),
            pos_y: (py !== '' && py !== null) ? parseInt(py) : (80 + Math.floor(state.areas.length / 4) * 260),
        };

        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

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
            btn.disabled = false;
            btn.innerHTML = original;
            if (res.success) {
                state.areas.push(res.area);
                toast('POP berhasil ditambahkan.');
                modalAddPop.hide();
                e.target.reset();

                // Reset warna ke default
                document.getElementById('popBorderColor').value = '#2563eb';
                document.getElementById('popBorderText').value  = '#2563eb';
                document.getElementById('popBgColor').value     = '#f8fafc';
                document.getElementById('popBgText').value      = '#f8fafc';
                document.getElementById('popTitleColor').value  = '#2563eb';
                document.getElementById('popTitleText').value   = '#2563eb';

                render();
            } else {
                toast(res.message || 'Gagal simpan POP', true);
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = original;
            toast('Error koneksi', true);
        });
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
                toast(`${payload.name} ditambahkan. Drag ke canvas untuk menempatkan.`);
                modalAddDevice.hide();
                e.target.reset();
                render();
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

    // ═══ DEVICE DETAIL MODAL ═══
    const modalDeviceDetail = new bootstrap.Modal(document.getElementById('modalDeviceDetail'));
    const modalEditDevice   = new bootstrap.Modal(document.getElementById('modalEditDevice'));

    let currentDetailDeviceId = null;

    function openDeviceDetail(mapDeviceId) {
        currentDetailDeviceId = mapDeviceId;

        const dev = state.devices.find(d => d.id === mapDeviceId);
        if (dev) {
            document.getElementById('detailDeviceName').textContent = dev.name || '-';
            document.getElementById('detailFieldName').textContent   = dev.name || '-';
            document.getElementById('detailFieldIp').textContent     = dev.ip_address || '-';
        }

        modalDeviceDetail.show();

        fetch(`/admin/topology-maps/${state.map.id}/devices/${mapDeviceId}`, {
            headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const d = res.device;

            document.getElementById('detailDeviceName').textContent = d.name;
            document.getElementById('detailFieldName').textContent   = d.name;
            document.getElementById('detailFieldIp').textContent     = d.ip_address;
            document.getElementById('detailFieldArea').textContent   = d.area_name || d.pop_name || '-';
            document.getElementById('detailFieldLastPoll').textContent = d.last_poll || '-';

            const statusMap = {
                up:      { text: 'UP',      color: '#16a34a' },
                down:    { text: 'DOWN',    color: '#dc2626' },
                warning: { text: 'WARNING', color: '#d97706' },
                unknown: { text: 'UNKNOWN', color: '#64748b' },
            };
            const s = statusMap[d.status] || statusMap.unknown;
            document.getElementById('detailFieldStatus').innerHTML =
                `<span style="color:${s.color};font-weight:800;">&bull; ${s.text}</span>`;

            document.getElementById('statProtocol').textContent  = d.protocol || '-';
            document.getElementById('statPolls').textContent     = d.polls || '0';
            document.getElementById('statResponded').textContent = (d.responded_percentage || 0) + '%';
            document.getElementById('statMissed').textContent    = (d.missed_percentage || 0) + '%';
            document.getElementById('statDowntime').textContent  = d.down_time || '00:00:00';
            document.getElementById('statAvgDelay').textContent  = (d.avg_delay || 0) + ' ms';
            document.getElementById('statMinDelay').textContent  = (d.min_delay || 0) + ' ms';
            document.getElementById('statMaxDelay').textContent  = (d.max_delay || 0) + ' ms';

            const logBox = document.getElementById('detailLogs');
            if (Array.isArray(res.logs) && res.logs.length) {
                let html = '';
                res.logs.forEach(l => {
                    const cls = l.status === 'down' ? 'log-down'
                              : l.status === 'up'   ? 'log-up'
                              : l.status === 'warning' ? 'log-warning'
                              : '';
                    html += `<div><span class="text-muted">${l.time}</span> <span class="${cls}">[${(l.status || '').toUpperCase()}]</span> ${l.message}</div>`;
                });
                logBox.innerHTML = html;
            } else {
                logBox.innerHTML = '<div class="text-muted">Belum ada riwayat log.</div>';
            }
        })
        .catch(() => toast('Gagal memuat detail device', true));
    }

    document.getElementById('btnOpenEditDevice').addEventListener('click', () => {
        if (!currentDetailDeviceId) return;

        fetch(`/admin/topology-maps/${state.map.id}/devices/${currentDetailDeviceId}`, {
            headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const d = res.device;

            document.getElementById('editMapDeviceId').value = d.id;
            document.getElementById('editName').value = d.name;
            document.getElementById('editType').value = d.type;
            document.getElementById('editProtocol').value = d.protocol;
            document.getElementById('editIp').value = d.ip_address;
            document.getElementById('editRouterVersion').value = d.router_version || '';
            document.getElementById('editArea').value = d.topology_map_area_id || '';
            document.getElementById('editTypeIcon').src = `${ICON_BASE}/${getIconFile(d.type)}`;

            modalDeviceDetail.hide();
            modalEditDevice.show();
        })
        .catch(() => toast('Gagal memuat data edit', true));
    });

    document.getElementById('editType').addEventListener('change', function () {
        document.getElementById('editTypeIcon').src = `${ICON_BASE}/${getIconFile(this.value)}`;
    });

    document.getElementById('formEditDevice').addEventListener('submit', e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        const id = fd.get('map_device_id');

        const payload = {
            name:                 fd.get('name'),
            type:                 fd.get('type'),
            protocol:             fd.get('protocol'),
            ip_address:           fd.get('ip_address'),
            router_version:       fd.get('router_version') || null,
            topology_map_area_id: fd.get('topology_map_area_id') || null,
        };

        const btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch(`/admin/topology-maps/${state.map.id}/devices/${id}`, {
            method: 'PUT',
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
            btn.innerHTML = '<em class="icon ni ni-save me-1"></em>Update Perangkat';

            if (res.success) {
                const idx = state.devices.findIndex(d => d.id === parseInt(id, 10));
                if (idx !== -1) {
                    state.devices[idx] = { ...state.devices[idx], ...res.device };
                }
                toast('Device berhasil diupdate.');
                modalEditDevice.hide();
                render();
            } else {
                toast(res.message || 'Gagal update device', true);
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"></em>Update Perangkat';
            toast('Error koneksi', true);
        });
    });

    document.getElementById('btnDeleteDevice').addEventListener('click', () => {
        if (!currentDetailDeviceId) return;
        const dev = state.devices.find(d => d.id === currentDetailDeviceId);
        const name = dev ? dev.name : 'device ini';

        if (!confirm(`Hapus "${name}" dari map?`)) return;

        fetch(`/admin/topology-maps/${state.map.id}/devices/${currentDetailDeviceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                state.devices = state.devices.filter(d => d.id !== currentDetailDeviceId);
                state.connections = state.connections.filter(c =>
                    c.from_map_device_id !== currentDetailDeviceId &&
                    c.to_map_device_id !== currentDetailDeviceId
                );
                toast('Device berhasil dihapus dari map.');
                modalDeviceDetail.hide();
                render();
            } else {
                toast(res.message || 'Gagal hapus device', true);
            }
        })
        .catch(() => toast('Error koneksi', true));
    });

    // ═══ MODAL CONNECTION ═══
    const modalConnection = new bootstrap.Modal(document.getElementById('modalConnection'));

    let connectionModalMode = 'create';
    let currentConnectionId = null;
    let pendingFromId = null;
    let pendingToId   = null;

    function openCreateConnectionModal(fromId, toId) {
        connectionModalMode = 'create';
        currentConnectionId = null;
        pendingFromId = fromId;
        pendingToId   = toId;

        const from = state.devices.find(d => d.id === fromId);
        const to   = state.devices.find(d => d.id === toId);

        document.getElementById('connFromName').textContent = from ? from.name : '-';
        document.getElementById('connToName').textContent   = to ? to.name : '-';

        document.getElementById('connType').value = 'ethernet';
        document.querySelectorAll('.conn-dir-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.dir === 'forward');
        });
        updateConnSymbol('forward');

        document.getElementById('btnDeleteConnection').style.display = 'none';
        document.getElementById('btnSaveConnection').style.display   = '';

        modalConnection.show();
    }

    function openConnectionModal(connId) {
        const conn = state.connections.find(c => c.id === connId);
        if (!conn) return;

        connectionModalMode = 'edit';
        currentConnectionId = connId;

        const from = state.devices.find(d => d.id === conn.from_map_device_id);
        const to   = state.devices.find(d => d.id === conn.to_map_device_id);

        document.getElementById('connFromName').textContent = from ? from.name : '-';
        document.getElementById('connToName').textContent   = to ? to.name : '-';

        document.getElementById('connType').value = conn.connection_type || 'ethernet';
        document.querySelectorAll('.conn-dir-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.dir === (conn.direction || 'forward'));
        });
        updateConnSymbol(conn.direction || 'forward');

        document.getElementById('btnDeleteConnection').style.display = '';
        document.getElementById('btnSaveConnection').style.display   = 'none';

        modalConnection.show();
    }

    function updateConnSymbol(dir) {
        const symbolMap = {
            forward: '→',
            reverse: '←',
            bidirectional: '↔',
            none: '—',
        };
        document.getElementById('connSymbol').textContent = symbolMap[dir] || '→';
    }

    document.querySelectorAll('.conn-dir-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const dir = btn.dataset.dir;
            document.querySelectorAll('.conn-dir-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.dir === dir);
            });
            updateConnSymbol(dir);
        });
    });

    document.getElementById('btnSaveConnection').addEventListener('click', function () {
        if (connectionModalMode !== 'create') return;
        if (!pendingFromId || !pendingToId) return;

        const dir = document.querySelector('.conn-dir-btn.active')?.dataset.dir || 'forward';
        const connType = document.getElementById('connType').value;

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        fetch(urls.storeConnection, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                from_map_device_id: pendingFromId,
                to_map_device_id:   pendingToId,
                direction:          dir,
                connection_type:    connType,
            }),
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"></em>Simpan Relasi';

            if (res.success) {
                state.connections.push(res.connection);
                toast('Relasi berhasil dibuat.');
                modalConnection.hide();
                render();
            } else {
                toast(res.message || 'Gagal buat relasi', true);
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<em class="icon ni ni-save me-1"></em>Simpan Relasi';
            toast('Error koneksi', true);
        });
    });

    document.getElementById('btnDeleteConnection').addEventListener('click', () => {
        if (!currentConnectionId) return;
        if (!confirm('Hapus relasi ini?')) return;

        fetch(`/admin/topology-maps/${state.map.id}/connections/${currentConnectionId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                state.connections = state.connections.filter(c => c.id !== currentConnectionId);
                toast('Relasi berhasil dihapus.');
                modalConnection.hide();
                render();
            } else {
                toast(res.message || 'Gagal hapus relasi', true);
            }
        })
        .catch(() => toast('Error koneksi', true));
    });

    document.querySelectorAll('.conn-dir-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (connectionModalMode !== 'edit' || !currentConnectionId) return;

            const dir = btn.dataset.dir;
            const connType = document.getElementById('connType').value;

            fetch(`/admin/topology-maps/${state.map.id}/connections/${currentConnectionId}/direction`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    direction: dir,
                    connection_type: connType,
                }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    const idx = state.connections.findIndex(c => c.id === currentConnectionId);
                    if (idx !== -1) {
                        state.connections[idx].direction = dir;
                        state.connections[idx].connection_type = connType;
                    }
                    toast('Arah panah diubah.');
                    render();
                } else {
                    toast(res.message || 'Gagal ubah arah', true);
                }
            })
            .catch(() => toast('Error koneksi', true));
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
            devices: state.devices
                .filter(d => d.pos_x !== null && d.pos_y !== null)
                .map(d => ({
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
