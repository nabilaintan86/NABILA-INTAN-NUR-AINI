{{--
    PANEL "DAFTAR DEVICE" — sisi kiri workspace Map Monitoring (RADNET 2026).

    Berfungsi sebagai workspace=device & topology map:
      1. Menampilkan SEMUA device (yang di map maupun belum).
      2. Device bisa di-drag ke kanvas map untuk ditempatkan.
      3. Device bisa ditambah, diedit, dan dihapus permanen dari sini.
      4. Hapus dari sidebar = HAPUS PERMANEN (cascade ke semua map).
         Hapus dari canvas = keluarkan dari map saja.
--}}
@php
    $deviceList     = $deviceList ?? [];
    $deviceTypes    = $deviceTypes ?? \App\Helpers\DeviceType::types();
    $protocols      = $protocols ?? \App\Helpers\DeviceType::protocols();
    $areaOptions    = $areaOptions ?? (isset($networkAreas) ? $networkAreas : collect());
    $panelSuperAdmin = $isSuperAdmin ?? (auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin'], true));
    $deviceIconBase  = $deviceIconBase ?? asset('images/devices');
    $mapId           = $mapId ?? request()->query('map') ?? request()->query('map_id') ?? null;
@endphp

<style>
    /* ===========================================================
       PANEL DAFTAR DEVICE (KIRI MAP)
       =========================================================== */
    .wug-map-body {
        display: flex;
        flex: 1 1 auto;
        min-height: 0;
        position: relative;
    }
    .wug-device-panel {
        width: 286px;
        flex: 0 0 286px;
        background: #ffffff;
        border-right: 1px solid #cbd5e1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        height: 100%;
        max-height: 100%;
        overflow: hidden;
        transition: width .2s ease, flex-basis .2s ease;
        z-index: 20;
    }
        .wug-fullscreen-btn {
        position: absolute;
        right: 14px;
        top: 14px;        
        z-index: 300;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 1px solid rgba(15,23,42,.18);
        background: rgba(255,255,255,.95);
        color: #0f172a;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        box-shadow: 0 4px 12px rgba(15,23,42,.18);
        transition: transform .12s ease, background .12s ease, color .12s ease;
        min-width: 40px;
        max-width: 40px;
        min-height: 40px;
        max-height: 40px;
        box-sizing: border-box;
    }
    .wug-fullscreen-btn:hover {
        background: #0066cc;
        color: #fff;
        border-color: #0066cc;
        transform: translateY(-1px);
    }
    .wug-fullscreen-btn.is-active {
        background: #0066cc;
        color: #fff;
        border-color: #0066cc;
    }
    @media (max-width: 575.98px) {
        .wug-fullscreen-btn {
            width: 34px;
            height: 34px;
            right: 10px;
            top: 10px;
            font-size: 15px;
        }
    }
        .wug-map-body:fullscreen .wug-fullscreen-btn,
    .wug-map-body:-webkit-full-screen .wug-fullscreen-btn {
        width: 40px !important;
        height: 40px !important;
        max-width: 40px !important;
        max-height: 40px !important;
        min-width: 40px !important;
        min-height: 40px !important;
    }
    
    .wug-map-body:fullscreen,
    .wug-map-body:-webkit-full-screen,
    .wug-map-body:-moz-full-screen,
    .wug-map-body:-ms-fullscreen {
        position: fixed !important;
        inset: 0 !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        min-height: 100vh !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #f1f5f9 !important;
        display: flex !important;
        flex-direction: row !important;
        overflow: hidden !important;
    }
    .wug-map-body:fullscreen .wug-device-panel,
    .wug-map-body:-webkit-full-screen .wug-device-panel {
        height: 100vh !important;
        max-height: 100vh !important;
    }
    .wug-map-body:fullscreen > *:not(.wug-fullscreen-btn),
    .wug-map-body:-webkit-full-screen > *:not(.wug-fullscreen-btn) {
        height: 100vh !important;
        max-height: 100vh !important;
    }
    .wug-map-body:fullscreen .wug-canvas-viewport,
    .wug-map-body:fullscreen .wug-map-container,
    .wug-map-body:fullscreen #wugViewport,
    .wug-map-body:-webkit-full-screen .wug-canvas-viewport,
    .wug-map-body:-webkit-full-screen .wug-map-container,
    .wug-map-body:-webkit-full-screen #wugViewport {
        height: 100vh !important;
        max-height: 100vh !important;
        flex: 1 1 auto !important;
        overflow: hidden !important;
    }
    .wug-map-body:fullscreen svg,
    .wug-map-body:-webkit-full-screen svg {
        height: 100% !important;
        width: 100% !important;
    }
    .wug-device-panel.is-collapsed {
        width: 44px;
        flex-basis: 44px;
    }
    .wug-device-panel.is-collapsed .wug-devpanel-head-text,
    .wug-device-panel.is-collapsed .wug-devpanel-search,
    .wug-device-panel.is-collapsed .wug-devpanel-list,
    .wug-device-panel.is-collapsed .wug-devpanel-foot {
        display: none;
    }
    .wug-device-panel.is-collapsed .wug-devpanel-head {
        justify-content: center;
        padding: 10px 4px;
    }

    .wug-devpanel-head {
        background: #0f172a;
        color: #ffffff;
        padding: 9px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        flex-shrink: 0;
    }
    .wug-devpanel-head-text { min-width: 0; }
    .wug-devpanel-title {
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: .3px;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .wug-devpanel-count {
        font-size: 10.5px;
        opacity: .8;
        display: block;
        margin-top: 1px;
    }
    .wug-devpanel-toggle {
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.28);
        color: #fff;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 13px;
    }
    .wug-devpanel-toggle:hover { background: rgba(255,255,255,.3); }

    .wug-devpanel-search {
        padding: 8px 10px;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .wug-devpanel-search .form-control,
    .wug-devpanel-search .form-select {
        font-size: 12px;
        padding: 4px 8px;
    }

    .wug-devpanel-list {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 6px;
        background: #f8fafc;
    }

    .wug-dev-item {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-left: 3px solid #94a3b8;
        border-radius: 6px;
        padding: 6px 7px;
        margin-bottom: 5px;
        cursor: {{ $panelSuperAdmin ? 'grab' : 'default' }};
        transition: box-shadow .12s, border-color .12s, transform .12s;
    }
    .wug-dev-item:hover {
        border-color: #94a3b8;
        box-shadow: 0 2px 8px rgba(15,23,42,.12);
    }
    .wug-dev-item.is-dragging { opacity: .45; }
    .wug-dev-item.st-up      { border-left-color: #22c55e; }
    .wug-dev-item.st-down    { border-left-color: #dc2626; background: #fef2f2; }
    .wug-dev-item.st-warning { border-left-color: #f59e0b; background: #fffbeb; }
    .wug-dev-item.is-placed {
        background: #f1f5f9;
        border-left-color: #22c55e;
        cursor: not-allowed !important;
    }
    .wug-dev-item.is-placed .wug-dev-icon,
    .wug-dev-item.is-placed .wug-dev-info {
        opacity: .55;
    }
    .wug-dev-item.is-placed:hover {
        box-shadow: none;
        border-color: #e2e8f0;
        border-left-color: #22c55e;
    }
    /* Tombol edit & hapus tetap full opacity walau item disable */
    .wug-dev-item.is-placed .wug-dev-actions {
        opacity: 1;
    }
    .wug-dev-item.is-placed .wug-dev-btn {
        background: #fff;
        border-color: #cbd5e1;
    }
    .wug-dev-item.is-placed .wug-dev-btn:hover {
        background: #0066cc;
        color: #fff;
        border-color: #0066cc;
    }
    .wug-dev-item.is-placed .wug-dev-btn.danger:hover {
        background: #dc2626;
        border-color: #dc2626;
    }

    .wug-dev-icon {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        object-fit: contain;
    }
    .wug-dev-info { min-width: 0; flex: 1 1 auto; }
    .wug-dev-name {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .wug-dev-meta {
        font-size: 10px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .wug-dev-meta .dot { margin: 0 3px; color: #cbd5e1; }
    .wug-dev-tags {
        display: flex;
        align-items: center;
        gap: 3px;
        margin-top: 2px;
        flex-wrap: wrap;
    }
    .wug-dev-tag {
        font-size: 9px;
        font-weight: 700;
        padding: 1px 5px;
        border-radius: 8px;
        background: #e2e8f0;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .2px;
        white-space: nowrap;
    }
    .wug-dev-tag.area     { background: #dbeafe; color: #1e40af; }
    .wug-dev-tag.unplaced { background: #fef3c7; color: #92400e; }
    .wug-dev-tag.placed   { background: #dcfce7; color: #166534; }

    .wug-dev-status {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex: 0 0 8px;
        display: inline-block;
    }
    .wug-dev-status.up      { background: #22c55e; }
    .wug-dev-status.down    { background: #dc2626; }
    .wug-dev-status.warning { background: #f59e0b; }
    .wug-dev-status.other   { background: #94a3b8; }

    .wug-dev-actions {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex-shrink: 0;
    }
    .wug-dev-btn {
        width: 24px;
        height: 22px;
        border: 1px solid #cbd5e1;
        background: #fff;
        border-radius: 4px;
        color: #475569;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        padding: 0;
        line-height: 1;
    }
    .wug-dev-btn:hover { background: #0066cc; color: #fff; border-color: #0066cc; }
    .wug-dev-btn.danger:hover { background: #dc2626; border-color: #dc2626; }

    .wug-devpanel-empty {
        text-align: center;
        padding: 26px 12px;
        color: #94a3b8;
        font-size: 12px;
    }
    .wug-devpanel-foot {
        border-top: 1px solid #e2e8f0;
        padding: 8px 10px;
        font-size: 10.5px;
        color: #64748b;
        background: #fff;
        flex-shrink: 0;
    }

    .wug-canvas-viewport.is-drop-target {
        outline: 2px dashed #2563eb;
        outline-offset: -6px;
        background: #c7ddf7;
    }
    .wug-drop-hint {
        position: absolute;
        z-index: 90;
        pointer-events: none;
        display: none;
        background: rgba(15,23,42,.92);
        color: #fff;
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 5px;
        white-space: nowrap;
        transform: translate(12px, 12px);
        box-shadow: 0 4px 12px rgba(0,0,0,.3);
    }
    .wug-drop-hint.is-visible { display: block; }
    .wug-drop-hint .area { color: #93c5fd; }

    .wug-map-container.connect-mode .topo-node { cursor: crosshair; }
    .wug-map-container.connect-mode .topo-node:hover image {
        filter: drop-shadow(0 0 6px #f59e0b) brightness(1.15);
    }
    .wug-connect-banner {
        position: absolute;
        top: 12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 95;
        background: #f59e0b;
        color: #000;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        box-shadow: 0 4px 14px rgba(0,0,0,.28);
        display: none;
        align-items: center;
        gap: 10px;
    }
    .wug-connect-banner.is-visible { display: inline-flex; }
    .wug-connect-banner button {
        background: rgba(0,0,0,.18);
        border: none;
        border-radius: 10px;
        padding: 2px 9px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        color: #000;
    }
    .wug-node-selected { filter: drop-shadow(0 0 7px #f59e0b); }

    @media (max-width: 991.98px) {
        .wug-device-panel {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            box-shadow: 4px 0 18px rgba(0,0,0,.2);
        }
        .wug-device-panel.is-collapsed {
            width: 40px;
            flex-basis: 40px;
        }
    }
</style>

<div class="wug-device-panel" id="wugDevicePanel">
    <div class="wug-devpanel-head">
        <div class="wug-devpanel-head-text">
            <div class="wug-devpanel-title">
                <em class="icon ni ni-hard-drive"></em> Daftar Device
            </div>
            <span class="wug-devpanel-count" id="wugDeviceCount">0 perangkat</span>
        </div>
        <button type="button" class="wug-devpanel-toggle" id="btnToggleDevicePanel" title="Sembunyikan / Tampilkan Daftar Device">
            <em class="icon ni ni-align-left" id="iconToggleDevicePanel"></em>
        </button>
    </div>

    <div class="wug-devpanel-search">
        <input type="text" class="form-control form-control-sm mb-1" id="wugDeviceSearch"
               placeholder="Cari nama / IP device..." autocomplete="off">
        <select class="form-select form-select-sm" id="wugDeviceAreaFilter">
            <option value="">Semua POP / Area</option>
            @foreach($areaOptions as $ar)
                <option value="{{ $ar->name }}">{{ $ar->name }}</option>
            @endforeach
            <option value="__Belum ditempatkan">Belum ditempatkan</option>
            <option value="__Di map">Di Map</option>
        </select>
    </div>

    <div class="wug-devpanel-list" id="wugDeviceList">
        {{-- Diisi server-side agar langsung tampil tanpa menunggu fetch. --}}
    </div>

    <div class="wug-devpanel-foot">
        @if($panelSuperAdmin)
            <em class="icon ni ni-info me-1"></em> Seret device ke kanvas untuk menempatkan posisinya.
            Tombol <em class="icon ni ni-trash"></em> = hapus permanen dari sistem.
        @else
            <em class="icon ni ni-eye me-1"></em> Mode baca saja. Konfigurasi map hanya untuk Super Admin.
        @endif
    </div>
</div>

{{-- ============================================================
     MODAL: TAMBAH DEVICE
     ============================================================ --}}
<div class="modal fade" id="wugModalAddDevice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="wugFormAddDevice">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <em class="icon ni ni-plus-circle text-primary me-2"></em>
                        Tambah Perangkat ke Map
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-primary-dim small py-2 mb-3">
                        <em class="icon ni ni-info me-1"></em>
                        Perangkat disimpan ke tabel <code>device</code> dengan aturan yang sama seperti
                        halaman <strong>Kelola Router</strong>, lalu otomatis didaftarkan ke
                        <code>devices.csv</code> sehingga ikut diping dan masuk status UP/DOWN.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: POP Gresik" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                @foreach($deviceTypes as $deviceType)
                                    <option value="{{ $deviceType }}"
                                            data-icon="{{ \App\Helpers\DeviceType::iconUrl($deviceType) }}"
                                            @selected($deviceType === 'Router')>{{ $deviceType }}</option>
                                @endforeach
                            </select>
                            <img id="wugAddTypePreview" src="{{ \App\Helpers\DeviceType::iconUrl('Router') }}"
                                 alt="Preview perangkat" class="mt-2" style="height:32px">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Metode Monitoring <span class="text-danger">*</span></label>
                            <select name="protocol" class="form-select" required>
                                @foreach($protocols as $protocol)
                                    <option value="{{ $protocol }}" @selected($protocol === 'ICMP')>{{ $protocol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" class="form-control" placeholder="Contoh: 202.154.4.254" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Router Version</label>
                            <input type="text" name="router_version" class="form-control" placeholder="Contoh: IOS-XE 17.3.1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">POP / Area</label>
                            <select name="network_area_id" class="form-select">
                                <option value="">— Tanpa area (langsung taruh lewat drag) —</option>
                                @foreach($areaOptions as $ar)
                                    <option value="{{ $ar->id }}">{{ $ar->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">
                                Bila diisi, device langsung tampil di panel area tersebut. Bila kosong,
                                seret device dari Daftar Device ke area yang diinginkan.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="wugBtnSubmitAddDevice">
                        <em class="icon ni ni-save me-1"></em> Simpan Perangkat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: EDIT DEVICE
     ============================================================ --}}
<div class="modal fade" id="wugModalEditDevice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="wugFormEditDevice">
                <input type="hidden" name="id" id="wugEditDeviceId">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <em class="icon ni ni-edit text-warning me-2"></em>
                        Edit Perangkat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning-dim small py-2 mb-3">
                        <em class="icon ni ni-alert me-1"></em>
                        Perubahan memperbarui record <code>device</code> yang sudah ada
                        (<span id="wugEditDeviceHint">-</span>) — tidak membuat perangkat duplikat.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="wugEditName" class="form-control" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                            <select name="type" id="wugEditType" class="form-select" required>
                                @foreach($deviceTypes as $deviceType)
                                    <option value="{{ $deviceType }}"
                                            data-icon="{{ \App\Helpers\DeviceType::iconUrl($deviceType) }}">{{ $deviceType }}</option>
                                @endforeach
                            </select>
                            <img id="wugEditTypePreview" alt="Preview perangkat" class="mt-2" style="height:32px">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Metode Monitoring <span class="text-danger">*</span></label>
                            <select name="protocol" id="wugEditProtocol" class="form-select" required>
                                @foreach($protocols as $protocol)
                                    <option value="{{ $protocol }}">{{ $protocol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" id="wugEditIp" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Router Version</label>
                            <input type="text" name="router_version" id="wugEditRouterVersion" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">POP / Area</label>
                            <select name="network_area_id" id="wugEditArea" class="form-select">
                                <option value="">— Tanpa area —</option>
                                @foreach($areaOptions as $ar)
                                    <option value="{{ $ar->id }}">{{ $ar->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="wugBtnSubmitEditDevice">
                        <em class="icon ni ni-save me-1"></em> Perbarui Perangkat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: KONFIRMASI HAPUS
     ============================================================ --}}
<div class="modal fade" id="wugModalConfirmDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title fs-14px">
                    <em class="icon ni ni-alert-circle text-danger me-1"></em>
                    <span id="wugDeleteTitle">Hapus?</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 text-center">
                <p class="mb-0" id="wugDeleteName">-</p>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="wugBtnConfirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: INFO — Device sudah ada di map
     ============================================================ --}}
<div class="modal fade" id="wugModalAlreadyPlaced" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h6 class="modal-title text-white fs-14px">
                    <em class="icon ni ni-info me-1"></em> Device Sudah Ada di Map
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 text-center">
                <em class="icon ni ni-map-pin fs-1 text-info d-block mb-2"></em>
                <p class="mb-1 small text-muted">Device:</p>
                <p class="fw-bold text-dark mb-2" id="wugAlreadyPlacedName">-</p>
                <p class="mb-1 small text-muted">Sudah ditempatkan di:</p>
                <p class="fw-bold text-primary mb-0" id="wugAlreadyPlacedArea">-</p>
                <p class="text-muted small mt-3 mb-0">
                    Keluarkan device ini dari canvas jika ingin memindahkannya.
                </p>
            </div>
            <div class="modal-footer py-2 justify-content-center">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">OK, Mengerti</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL: HUBUNGKAN DUA DEVICE
     ============================================================ --}}
<div class="modal fade" id="wugModalConnect" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title text-white fs-14px">
                    <em class="icon ni ni-link me-1"></em> Hubungkan Dua Perangkat
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3">
                <div class="text-center mb-3">
                    <div class="text-muted small">Sambungan:</div>
                    <div class="fw-bold text-dark fs-14px">
                        <span id="wugConnFrom">-</span>
                        <span class="text-primary px-1" id="wugConnSymbol">&rarr;</span>
                        <span id="wugConnTo">-</span>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold small">Tipe Media</label>
                    <select class="form-select form-select-sm" id="wugConnType">
                        <option value="ethernet">Ethernet</option>
                        <option value="fiber">Fiber Optic</option>
                        <option value="wireless">Wireless Link</option>
                        <option value="serial">Serial / WAN</option>
                    </select>
                </div>

                <label class="form-label fw-bold small">Arah Panah</label>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm text-start" data-wug-dir="forward">
                        <strong>&rarr; Forward</strong> <small class="text-muted d-block">Parent &rarr; Child Uplink (Standar)</small>
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm text-start" data-wug-dir="reverse">
                        <strong>&larr; Reverse</strong> <small class="text-muted d-block">Inbound Downlink (&larr;)</small>
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm text-start" data-wug-dir="bidirectional">
                        <strong>&harr; Dua Arah</strong> <small class="text-muted d-block">Active Peering / Duplex</small>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm text-start" data-wug-dir="none">
                        <strong>&mdash; Tanpa Panah</strong> <small class="text-muted d-block">Layer-2 Pasif</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var ICONS = {!! json_encode([
        'router' => 'router.png', 'switch' => 'switch.png', 'olt' => 'switch.png',
        'epon' => 'router.png', 'server' => 'server.png', 'pc' => 'pc.png',
        'other' => 'pc.png', 'wifi' => 'wifi.png', 'access_point' => 'wifi.png',
    ]) !!};
    var iconBase = {!! json_encode($deviceIconBase) !!};
    var isSuperAdmin = {!! json_encode($panelSuperAdmin) !!};
    var mapId = {!! json_encode($mapId) !!};

    function appendMapParam(url) {
        if (!mapId) return url;
        return url + (url.indexOf('?') === -1 ? '?' : '&') + 'map_id=' + encodeURIComponent(mapId);
    }

    var URLS = {
        list:        appendMapParam({!! json_encode(route('admin.monitoring.devices')) !!}),
        store:       appendMapParam({!! json_encode(route('admin.monitoring.device.store')) !!}),
        updateTpl:   appendMapParam({!! json_encode(route('admin.monitoring.device.update', ['device' => '__DEVICE_ID__'])) !!}),
        destroyTpl:  appendMapParam({!! json_encode(route('admin.monitoring.device.destroy', ['device' => '__DEVICE_ID__'])) !!}),
        positionTpl: appendMapParam({!! json_encode(route('admin.monitoring.device.position', ['device' => '__DEVICE_ID__'])) !!}),
        connection:  appendMapParam({!! json_encode(route('admin.monitoring.connection.store')) !!}),
        detailTpl:   {!! json_encode(route('admin.monitoring.device', ['device' => '__DEVICE_ID__'])) !!},
        positionDestroyTpl: appendMapParam({!! json_encode(route('admin.monitoring.position.destroy', ['position' => '__POSITION_ID__'])) !!})
    };
    var csrfToken = @json(csrf_token());

    var listEl   = document.getElementById('wugDeviceList');
    var countEl  = document.getElementById('wugDeviceCount');
    var searchEl = document.getElementById('wugDeviceSearch');
    var areaEl   = document.getElementById('wugDeviceAreaFilter');
    var panelEl  = document.getElementById('wugDevicePanel');
    var viewport = document.getElementById('wugViewport');
    var dropHint = document.createElement('div');

    if (!listEl) { return; }

    dropHint.className = 'wug-drop-hint';
    dropHint.id = 'wugDropHint';
    if (viewport) viewport.appendChild(dropHint);

    var devices = {!! json_encode(array_values($deviceList)) !!};
    var draggedDevice = null;
    var pendingConnect = null;
    var pendingDelete = null;

    function fsElement() {
        return document.fullscreenElement
            || document.webkitFullscreenElement
            || document.mozFullScreenElement
            || document.msFullscreenElement
            || null;
    }

    function enterFullscreen(el) {
        if (!el) return;
        var fn = el.requestFullscreen
            || el.webkitRequestFullscreen
            || el.mozRequestFullScreen
            || el.msRequestFullscreen;
        if (!fn) return;
        var p = fn.call(el);
        if (p && typeof p.catch === 'function') {
            p.catch(function () { /* browser tolak, diamkan */ });
        }
    }

    function exitFullscreen() {
        if (!fsElement()) return;
        var fn = document.exitFullscreen
            || document.webkitExitFullscreen
            || document.mozCancelFullScreen
            || document.msExitFullscreen;
        if (fn) fn.call(document);
    }

    function iconFile(key) {
        return ICONS[String(key || '').toLowerCase()] || 'server.png';
    }

    function escapeHtml(value) {
        if (value === null || value === undefined) return '';
        return String(value)
            .split('&').join('&amp;')
            .split('<').join('&lt;')
            .split('>').join('&gt;')
            .split('"').join('&quot;')
            .split("'").join('&#39;');
    }

    function isPlacedDevice(d) {
        return !!(d.placed || (d.position_id && d.position_id > 0));
    }

    function statusClass(status) {
        var s = String(status || '').toLowerCase();
        if (s === 'up' || s === 'online' || s === 'active') return 'up';
        if (s === 'down' || s === 'offline') return 'down';
        if (s === 'warning' || s === 'degraded') return 'warning';
        return 'other';
    }

        function visibleDevices() {
        var q = (searchEl && searchEl.value || '').toLowerCase().trim();
        var areaFilter = areaEl && areaEl.value ? areaEl.value : '';

        return devices.filter(function (d) {
            var placed = isPlacedDevice(d);

            if (areaFilter === '__Belum ditempatkan') {
                if (placed) return false;
            } else if (areaFilter === '__Di map') {
                if (!placed) return false;
            } else if (areaFilter && d.area !== areaFilter) {
                return false;
            }
            if (!q) return true;
            return (d.name + ' ' + (d.ip || '') + ' ' + (d.type || '') + ' ' + (d.protocol || ''))
                .toLowerCase().indexOf(q) !== -1;
        });
    }

    function render() {
        if (!listEl) return;
        var placed   = devices.filter(function (d) { return isPlacedDevice(d); });
        var unplaced = devices.filter(function (d) { return !isPlacedDevice(d); });
        var rows = visibleDevices();

        if (countEl) {
            countEl.textContent = devices.length + ' total (' + placed.length + ' di map)';
        }

        if (!rows.length) {
            listEl.innerHTML = '<div class="wug-devpanel-empty py-4 text-center">'
                + '<em class="icon ni ni-search fs-2 d-block mb-2 text-muted"><\/em>'
                + '<div class="small text-muted">Tidak ada perangkat yang cocok.<\/div>'
                + '<\/div>';
            window.WugDeviceList = devices;
            return;
        }

        var html = '';
        rows.forEach(function (d) {
            var st = statusClass(d.status);
            var isPlaced = isPlacedDevice(d);

            // Badge: kalau placed → tampilkan nama area/Map
            var areaTag;
            if (isPlaced) {
                var areaName = d.area ? escapeHtml(d.area) : 'Map';
                areaTag = '<span class="wug-dev-tag placed" title="Sudah ada di: ' + areaName + '">'
                    + '✓ Di Map: ' + areaName + '<\/span>';
            } else if (d.area) {
                areaTag = '<span class="wug-dev-tag area">' + escapeHtml(d.area) + '<\/span>';
            } else {
                areaTag = '<span class="wug-dev-tag unplaced">Belum di map<\/span>';
            }

            // Tombol edit & hapus — selalu ada untuk Super Admin
            var actions = '';
            if (isSuperAdmin) {
                actions = '<div class="wug-dev-actions">'
                    + '<button type="button" class="wug-dev-btn" data-wug-act="edit" data-id="' + d.id + '" title="Edit perangkat">'
                    + '<em class="icon ni ni-edit"><\/em><\/button>'
                    + '<button type="button" class="wug-dev-btn danger" data-wug-act="delete" data-id="' + d.id + '" title="Hapus permanen dari sistem">'
                    + '<em class="icon ni ni-trash"><\/em><\/button>'
                    + '<\/div>';
            }

            // 🔑 Device yang sudah di map: TIDAK draggable
            //    Tapi tetap punya draggable="true" supaya bisa intercept dragstart
            //    dan tampilkan popup info (lihat bindDrag).
            var canDrag = isSuperAdmin;

            html += '<div class="wug-dev-item st-' + escapeHtml(st)
                + (isPlaced ? ' is-placed' : '') + '"'
                + ' data-id="' + d.id + '"'
                + ' data-position-id="' + (d.position_id || '') + '"'
                + ' data-name="' + escapeHtml(d.name) + '"'
                + ' data-placed="' + (isPlaced ? '1' : '0') + '"'
                + (canDrag ? ' draggable="true"' : '') + '>'
                + '<img class="wug-dev-icon" src="' + iconBase + '/' + iconFile(d.icon) + '" alt="">'
                + '<div class="wug-dev-info">'
                + '<div class="wug-dev-name"><span class="wug-dev-status ' + escapeHtml(st) + '"><\/span>'
                + escapeHtml(d.name) + '<\/div>'
                + '<div class="wug-dev-meta">' + escapeHtml(d.ip || '-')
                + '<span class="dot">•<\/span>' + escapeHtml(d.type || '-')
                + '<span class="dot">•<\/span>' + escapeHtml(d.protocol || '-')
                + (d.router_version ? '<span class="dot">•<\/span>' + escapeHtml(d.router_version) : '')
                + '<\/div>'
                + '<div class="wug-dev-tags">' + areaTag
                + '<span class="wug-dev-tag">' + escapeHtml(String(d.status || '-').toUpperCase()) + '<\/span>'
                + '<\/div>'
                + '<\/div>' + actions
                + '<\/div>';
        });
        window.WugDeviceList = devices;

        listEl.innerHTML = html;

        if (isSuperAdmin) {
            listEl.querySelectorAll('.wug-dev-item[draggable="true"]').forEach(bindDrag);
        }
    }

    function refreshDevices() {
        fetch(URLS.list, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res && res.devices) {
                    devices = res.devices;
                    render();
                }
            })
            .catch(function () {});
    }

    /* ------------------------------------------------------------------
       DRAG & DROP DARI DAFTAR DEVICE KE MAP
       ------------------------------------------------------------------ */
    function bindDrag(item) {
        item.addEventListener('dragstart', function (e) {
            var dev = devices.find(function (d) { return String(d.id) === item.dataset.id; }) || null;

            // 🚫 Device yang sudah di map → blokir drag + tampilkan popup info
            if (dev && isPlacedDevice(dev)) {
                e.preventDefault();
                showAlreadyPlaced(dev);
                return false;
            }

            draggedDevice = dev;
            item.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
            try {
                e.dataTransfer.setData('text/plain', item.dataset.id);
                e.dataTransfer.setData('application/x-wug-device', item.dataset.id);
            } catch (err) { /* browser lama */ }
        });

        item.addEventListener('dragend', function () {
            item.classList.remove('is-dragging');
            draggedDevice = null;
            hideDropHint();
            if (viewport) viewport.classList.remove('is-drop-target');
        });
    }
    function hideDropHint() {
        dropHint.classList.remove('is-visible');
    }

    function showDropHint(x, y, deviceName, areaName) {
        var vp = getViewport();
        if (!vp) return;
        var rect = vp.getBoundingClientRect();
        dropHint.style.left = (x - rect.left) + 'px';
        dropHint.style.top = (y - rect.top) + 'px';
        dropHint.innerHTML = 'Taruh <strong>' + escapeHtml(deviceName) + '<\/strong>'
            + (areaName ? ' di <span class="area">' + escapeHtml(areaName) + '<\/span>' : ' (luar area)');
        dropHint.classList.add('is-visible');
    }

    function getViewport() {
        return document.getElementById('wugViewport');
    }

    document.addEventListener('dragover', function (e) {
        var vp = getViewport();
        if (!vp || !vp.contains(e.target)) return;
        if (!isSuperAdmin) return;

        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        vp.classList.add('is-drop-target');

        if (!draggedDevice) return;
        var topo = window.WugTopology;
        if (!topo || !topo.getSvgPoint) return;
        var pt = topo.getSvgPoint(e);
        showDropHint(e.clientX, e.clientY, draggedDevice.name,
            (topo.areaAt(pt.x, pt.y) || {}).name);
    });

    document.addEventListener('dragleave', function (e) {
        var vp = getViewport();
        if (!vp) return;
        if (e.target === vp || !vp.contains(e.relatedTarget)) {
            vp.classList.remove('is-drop-target');
            hideDropHint();
        }
    });

    document.addEventListener('drop', function (e) {
        var vp = getViewport();
        if (!vp || !vp.contains(e.target)) return;
        if (!isSuperAdmin) return;

        e.preventDefault();
        vp.classList.remove('is-drop-target');
        hideDropHint();

        var id = null;
        try {
            id = e.dataTransfer.getData('application/x-wug-device')
                || e.dataTransfer.getData('text/plain');
        } catch (err) { }

        var device = draggedDevice
            || devices.find(function (d) { return String(d.id) === String(id); });
        if (!device) return;

        var topo = window.WugTopology;
        if (!topo || !topo.getSvgPoint) {
            showToast('Map belum siap menerima device.', true);
            return;
        }

        var pt = topo.getSvgPoint(e);
        placeDevice(device, Math.round(pt.x), Math.round(pt.y));
    });
        function placeDevice(device, x, y) {
        if (isPlacedDevice(device)) {
            showAlreadyPlaced(device);
            return;
        }

        var topo = window.WugTopology;
        var area = topo && topo.areaAt ? topo.areaAt(x, y) : null;

        var body = { pos_x: x, pos_y: y };
        if (area && area.id) {
            body.network_area_id = area.id;
        }
        if (mapId) {
            body.map_id = mapId;
        }

        var url = URLS.positionTpl.replace('__DEVICE_ID__', device.id);

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(body)
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (!res || !res.success) {
                showToast((res && res.message) || 'Gagal menyimpan posisi device.', true);
                return;
            }
            if (res.devices) { devices = res.devices; render(); }
            if (res.topology && topo && topo.render) { topo.render(res.topology); }
            showToast('Device ' + device.name + ' ditempatkan di ' + x + ', ' + y
                + (res.area ? ' (area ' + res.area + ')' : ''));
        })
        .catch(function () {
            showToast('Gagal menyimpan posisi device.', true);
        });
    }

    /* ------------------------------------------------------------------
       MODAL TAMBAH / EDIT / HAPUS DEVICE
       ------------------------------------------------------------------ */
    function showToast(message, isError) {
        if (window.WugTopology && window.WugTopology.showToast) {
            window.WugTopology.showToast(message, isError);
            return;
        }
        alert(message);
    }

    function showAlreadyPlaced(device) {
        if (!device) return;
        var nameEl = document.getElementById('wugAlreadyPlacedName');
        var areaEl = document.getElementById('wugAlreadyPlacedArea');
        if (nameEl) nameEl.textContent = device.name || 'Device';
        if (areaEl) areaEl.textContent = device.area || 'Map (area tidak diketahui)';
        modalOf('wugModalAlreadyPlaced').show();
    }

    function modalOf(id) {
        var el = document.getElementById(id);
        return el ? bootstrap.Modal.getOrCreateInstance(el) : null;
    }

    function fieldErrors(res) {
        var bag = (res && res.errors) ? res.errors : {};
        var out = [];
        for (var key in bag) {
            if (Object.prototype.hasOwnProperty.call(bag, key)) {
                out.push(bag[key][0]);
            }
        }
        return out.join(' ');
    }

    var addForm = document.getElementById('wugFormAddDevice');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = document.getElementById('wugBtnSubmitAddDevice');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"><\/span> Menyimpan...';

            fetch(URLS.store, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({
                    name: addForm.name.value,
                    type: addForm.type.value,
                    protocol: addForm.protocol.value,
                    ip_address: addForm.ip_address.value,
                    router_version: addForm.router_version.value || null,
                    network_area_id: addForm.network_area_id.value || null,
                    map_id: mapId || null
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                btn.disabled = false;
                btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Simpan Perangkat';

                if (res && res.success) {
                    if (res.devices) { devices = res.devices; render(); }
                    if (res.topology && window.WugTopology && window.WugTopology.render) {
                        window.WugTopology.render(res.topology);
                    }
                    modalOf('wugModalAddDevice').hide();
                    addForm.reset();
                    showToast(res.message);
                } else {
                    showToast(fieldErrors(res) || (res && res.message) || 'Gagal menambah perangkat.', true);
                }
            })
            .catch(function () {
                btn.disabled = false;
                btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Simpan Perangkat';
                showToast('Terjadi kesalahan saat menambah perangkat.', true);
            });
        });

        addForm.querySelector('select[name="type"]').addEventListener('change', function () {
            var opt = this.options[this.selectedIndex];
            var preview = document.getElementById('wugAddTypePreview');
            if (opt && preview && opt.dataset.icon) preview.src = opt.dataset.icon;
        });
    }

    function openEditDevice(device) {
        if (!device) return;
        document.getElementById('wugEditDeviceId').value = device.id;
        document.getElementById('wugEditName').value = device.name || '';
        document.getElementById('wugEditIp').value = device.ip || '';
        document.getElementById('wugEditRouterVersion').value = device.router_version || '';
        document.getElementById('wugEditArea').value = device.area_id || '';
        document.getElementById('wugEditDeviceHint').textContent = '#' + device.id + ' ' + device.name;

        var typeSel = document.getElementById('wugEditType');
        var typeVal = String(device.type || 'Router').toLowerCase();
        for (var i = 0; i < typeSel.options.length; i++) {
            if (typeSel.options[i].value.toLowerCase() === typeVal) {
                typeSel.selectedIndex = i;
                break;
            }
        }

        var protoSel = document.getElementById('wugEditProtocol');
        var protoVal = String(device.protocol || 'ICMP').toLowerCase();
        for (var j = 0; j < protoSel.options.length; j++) {
            if (protoSel.options[j].value.toLowerCase() === protoVal) {
                protoSel.selectedIndex = j;
                break;
            }
        }

        var preview = document.getElementById('wugEditTypePreview');
        if (typeSel.selectedIndex >= 0 && preview) {
            preview.src = typeSel.options[typeSel.selectedIndex].dataset.icon || preview.src;
        }

        modalOf('wugModalEditDevice').show();
    }

    var editForm = document.getElementById('wugFormEditDevice');
    if (editForm) {
        document.getElementById('wugEditType').addEventListener('change', function () {
            var opt = this.options[this.selectedIndex];
            var preview = document.getElementById('wugEditTypePreview');
            if (opt && preview && opt.dataset.icon) preview.src = opt.dataset.icon;
        });

        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var id = document.getElementById('wugEditDeviceId').value;
            var btn = document.getElementById('wugBtnSubmitEditDevice');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"><\/span> Menyimpan...';

            fetch(URLS.updateTpl.replace('__DEVICE_ID__', id), {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({
                    name: editForm.name.value,
                    type: editForm.type.value,
                    protocol: editForm.protocol.value,
                    ip_address: editForm.ip_address.value,
                    router_version: editForm.router_version.value || null,
                    network_area_id: editForm.network_area_id.value || null,
                    map_id: mapId || null
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                btn.disabled = false;
                btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Perbarui Perangkat';

                if (res && res.success) {
                    if (res.devices) { devices = res.devices; render(); }
                    if (res.topology && window.WugTopology && window.WugTopology.render) {
                        window.WugTopology.render(res.topology);
                    }
                    modalOf('wugModalEditDevice').hide();
                    showToast(res.message);
                } else {
                    showToast(fieldErrors(res) || (res && res.message) || 'Gagal memperbarui perangkat.', true);
                }
            })
            .catch(function () {
                btn.disabled = false;
                btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Perbarui Perangkat';
                showToast('Terjadi kesalahan saat memperbarui perangkat.', true);
            });
        });
    }

    /* ============================================================
       FIX #3: destroyDevice(device, mode)
       mode = 'sidebar' → HAPUS PERMANEN
       mode = 'canvas'  → KELUARKAN dari map
       ============================================================ */
    function destroyDevice(device, mode) {
        if (!device) return;

        mode = mode || 'sidebar';
        var posId = device.position_id || null;
        var isPlaced = isPlacedDevice(device);
        var isPermanent = (mode === 'sidebar');

        var titleEl = document.getElementById('wugDeleteTitle');
        var nameEl  = document.getElementById('wugDeleteName');
        var btnConfirm = document.getElementById('wugBtnConfirmDelete');

        if (isPermanent) {
            if (titleEl) titleEl.textContent = 'Hapus ' + (device.name || 'perangkat') + ' Permanen?';
            if (nameEl) {
                nameEl.innerHTML = 'Perangkat <strong>' + escapeHtml(device.name || '') + '</strong> akan dihapus '
                    + '<span class="text-danger fw-bold">PERMANEN</span> dari sistem, termasuk dari '
                    + '<strong>SEMUA map</strong> yang memuatnya.<br><br>'
                    + '<span class="text-muted small">Tindakan ini tidak bisa dibatalkan.</span>';
            }
            if (btnConfirm) btnConfirm.textContent = 'Hapus Permanen';
        } else {
            if (titleEl) titleEl.textContent = 'Keluarkan ' + (device.name || 'perangkat') + ' dari Map?';
            if (nameEl) {
                nameEl.innerHTML = 'Perangkat akan dikeluarkan dari map ini dan posisinya dilepas.<br><br>'
                    + '<span class="text-muted small">Perangkat <strong>tetap ada di sistem</strong> dan bisa dimasukkan kembali ke map kapan saja.</span>';
            }
            if (btnConfirm) btnConfirm.textContent = 'Keluarkan dari Map';
        }

        pendingDelete = {
            device: device,
            posId: posId,
            isPlaced: isPlaced,
            mode: mode,
            isPermanent: isPermanent,
        };
        modalOf('wugModalConfirmDelete').show();
    }

    /* ============================================================
       FIX #4: Handler btnConfirmDelete — pakai pd.mode
       ============================================================ */
    var btnConfirmDelete = document.getElementById('wugBtnConfirmDelete');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', function () {
            if (!pendingDelete) return;
            var pd = pendingDelete;
            pendingDelete = null;

            var url;
            if (pd.mode === 'canvas') {
                // DARI CANVAS → keluarkan dari map saja
                url = URLS.positionDestroyTpl.replace('__POSITION_ID__', pd.posId || pd.device.id);
            } else {
                // DARI SIDEBAR → HAPUS PERMANEN dari sistem
                url = URLS.destroyTpl.replace('__DEVICE_ID__', pd.device.id);
                url += (url.indexOf('?') === -1 ? '?' : '&') + 'permanent=1';
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('wugModalConfirmDelete'));
            if (modal) modal.hide();

            fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res && res.success) {
                    if (res.devices) { devices = res.devices; render(); }
                    if (res.topology && window.WugTopology && window.WugTopology.render) {
                        window.WugTopology.render(res.topology);
                    }
                    showToast(res.message);
                } else {
                    showToast((res && res.message) || 'Gagal memproses.', true);
                }
            })
            .catch(function () { showToast('Gagal memproses permintaan.', true); });
        });
    }

    /* ============================================================
       FIX #5: AKSI DI DALAM PANEL — kirim mode 'sidebar'
       ============================================================ */
    if (listEl) {
        listEl.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-wug-act]');
            if (btn) {
                var itemEl = btn.closest('.wug-dev-item');
                var posId = itemEl ? itemEl.dataset.positionId : '';
                var device = devices.find(function (d) {
                    return String(d.id) === btn.dataset.id
                        && (posId ? String(d.position_id) === String(posId) : true);
                });
                if (btn.dataset.wugAct === 'edit') openEditDevice(device);
                if (btn.dataset.wugAct === 'delete') destroyDevice(device, 'sidebar');
                if (btn.dataset.wugAct === 'focus' && window.WugTopology) {
                    window.WugTopology.focusNode(parseInt(btn.dataset.id, 10));
                }
                return;
            }

            var item = e.target.closest('.wug-dev-item');
            if (item) {
                var target = devices.find(function (d) { return String(d.id) === item.dataset.id; });
                if (target && window.openWugDeviceModal) {
                    window.openWugDeviceModal(target.id, target);
                }
            }
        });
    }

    if (searchEl) searchEl.addEventListener('input', render);
    if (areaEl) areaEl.addEventListener('change', render);
        var panelToggle = document.getElementById('btnToggleDevicePanel');
    if (panelToggle) {
        panelToggle.addEventListener('click', function () {
            var collapsed = panelEl.classList.toggle('is-collapsed');
            var iconEl = this.querySelector('em');
            if (iconEl) iconEl.className = collapsed ? 'icon ni ni-align-right' : 'icon ni ni-align-left';

            // Trigger resize biar SVG canvas re-layout setelah lebar berubah
            setTimeout(function () {
                window.dispatchEvent(new Event('resize'));
                if (window.WugTopology && window.WugTopology.refresh) {
                    try { window.WugTopology.refresh(); } catch (e) {}
                }
            }, 220);
        });
    }

    /* ============================================================
       TOMBOL FULLSCREEN MANUAL — Floating di pojok kanan bawah map
       ============================================================ */
    (function initFullscreenButton() {
        var mapBody = document.querySelector('.wug-map-body');
        if (!mapBody) return;

        // Cegah duplikat kalau file di-include lebih dari sekali
        if (mapBody.querySelector('.wug-fullscreen-btn')) return;

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'wug-fullscreen-btn';
        btn.title = 'Fullscreen map (ESC untuk keluar)';
        btn.innerHTML = '<em class="icon ni ni-expand"></em>';
        mapBody.appendChild(btn);

        btn.addEventListener('click', function () {
            if (fsElement()) {
                exitFullscreen();
            } else {
                enterFullscreen(mapBody);
            }
        });

        function syncBtn() {
            var active = !!fsElement();
            btn.classList.toggle('is-active', active);
            var icon = btn.querySelector('em');
            if (icon) {
                icon.className = active
                    ? 'icon ni ni-shrink'
                    : 'icon ni ni-expand';
            }
            btn.title = active ? 'Keluar fullscreen (ESC)' : 'Fullscreen map';
        }

        ['fullscreenchange', 'webkitfullscreenchange',
         'mozfullscreenchange', 'MSFullscreenChange'].forEach(function (evt) {
            document.addEventListener(evt, function () {
                syncBtn();
                setTimeout(function () {
                    window.dispatchEvent(new Event('resize'));
                    if (window.WugTopology && window.WugTopology.refresh) {
                        try { window.WugTopology.refresh(); } catch (e) {}
                    }
                }, 120);
            });
        });

        syncBtn();
    })();

    /* ------------------------------------------------------------------
       MODE HUBUNGKAN
       ------------------------------------------------------------------ */
    window.WugConnectRequested = function (fromNode, toNode) {
        pendingConnect = { from: fromNode, to: toNode };
        document.getElementById('wugConnFrom').textContent = fromNode.name;
        document.getElementById('wugConnTo').textContent = toNode.name;
        document.getElementById('wugConnSymbol').textContent = '→';
        modalOf('wugModalConnect').show();
    };

    var connModalEl = document.getElementById('wugModalConnect');
    if (connModalEl) {
        connModalEl.querySelectorAll('[data-wug-dir]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!pendingConnect) return;
                var dir = btn.dataset.wugDir;
                var type = document.getElementById('wugConnType').value;
                var symbol = dir === 'bidirectional' ? '↔' : (dir === 'reverse' ? '←' : (dir === 'none' ? '—' : '→'));

                var label = btn.querySelector('strong');
                if (label) label.textContent = 'Menyimpan...';
                btn.disabled = true;

                fetch(URLS.connection, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({
                        from_device_id: pendingConnect.from.device_id || pendingConnect.from.id,
                        to_device_id: pendingConnect.to.device_id || pendingConnect.to.id,
                        from_position_id: pendingConnect.from.id > 0 ? pendingConnect.from.id : null,
                        to_position_id: pendingConnect.to.id > 0 ? pendingConnect.to.id : null,
                        connection_type: type,
                        direction: dir
                    })
                })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    btn.disabled = false;
                    if (label) {
                        label.textContent = dir === 'bidirectional' ? '↔ Dua Arah'
                            : (dir === 'reverse' ? '← Reverse' : (dir === 'none' ? '— Tanpa Panah' : '→ Forward'));
                    }

                    if (res && res.success) {
                        bootstrap.Modal.getInstance(connModalEl).hide();
                        if (res.topology && window.WugTopology && window.WugTopology.render) {
                            window.WugTopology.render(res.topology);
                        }
                        if (window.WugTopology && window.WugTopology.setConnectMode) {
                            window.WugTopology.setConnectMode(false);
                        }
                        showToast('Relasi ' + pendingConnect.from.name + ' ' + symbol + ' '
                            + pendingConnect.to.name + ' berhasil disimpan.');
                        pendingConnect = null;
                    } else {
                        showToast(fieldErrors(res) || (res && res.message) || 'Gagal membuat relasi.', true);
                    }
                })
                .catch(function () {
                    btn.disabled = false;
                    showToast('Gagal membuat relasi.', true);
                });
            });
        });
    }

    /* ============================================================
       FIX #6: JEMBATAN UNTUK KOMPONEN MAP — deleteDevice mode 'canvas'
       ============================================================ */
    window.WugDevicePanel = {
        refresh: refreshDevices,
        render: render,
        getDevices: function () { return devices; },
        find: function (id) {
            return devices.find(function (d) { return String(d.id) === String(id); }) || null;
        },
        openAdd: function () { modalOf('wugModalAddDevice').show(); },
        openEdit: function (id) {
            var dev = this.find(id);
            if (dev) {
                openEditDevice(dev);
            } else {
                fetch(URLS.detailTpl.replace('__DEVICE_ID__', id), { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res && res.device) {
                            var d = res.device;
                            openEditDevice({
                                id: d.id,
                                name: d.name,
                                type: d.type,
                                protocol: d.protocol,
                                ip: d.ip_address,
                                router_version: d.router_version,
                                area_id: d.network_area_id
                            });
                        }
                    })
                    .catch(function () {
                        alert('Gagal mengambil data perangkat untuk diedit.');
                    });
            }
        },
        // Dipanggil dari canvas (tombol hapus di detail modal map)
        // → mode 'canvas' = keluarkan dari map saja
        deleteDevice: function (device) { destroyDevice(device, 'canvas'); }
    };

    // Render awal + refresh berkala
    render();
    setInterval(refreshDevices, 30000);
})();
</script>