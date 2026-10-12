{{--
    PANEL "DAFTAR DEVICE" — EzyHome (mirror dari topology-device-panel).
--}}
@php
    $deviceList     = $deviceList ?? [];
    $deviceTypes    = $deviceTypes ?? ['Router', 'Switch', 'OLT', 'EPON', 'Server', 'Access Point', 'Other'];
    $protocols      = $protocols ?? ['ICMP', 'HTTP', 'SNMP', 'TCP', 'SSH'];
    $panelSuperAdmin = $isSuperAdmin ?? (auth()->check() && auth()->user()->role === 'super_admin');
    $deviceIconBase  = $deviceIconBase ?? asset('images/devices');
@endphp

<style>
    .ezy-device-panel {
        width: 286px;
        flex: 0 0 286px;
        background: #ffffff;
        border-right: 1px solid #cbd5e1;
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: 100%;
        overflow: hidden;
    }
    .ezy-devpanel-head {
        background: #0f172a;
        color: #fff;
        padding: 9px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        flex-shrink: 0;
    }
    .ezy-devpanel-title {
        font-size: 12.5px;
        font-weight: 700;
    }
    .ezy-devpanel-count {
        font-size: 10.5px;
        opacity: .8;
        display: block;
        margin-top: 1px;
    }
    .ezy-devpanel-search {
        padding: 8px 10px;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }
    .ezy-devpanel-search .form-control,
    .ezy-devpanel-search .form-select {
        font-size: 12px;
        padding: 4px 8px;
    }
    .ezy-devpanel-list {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 6px;
        background: #f8fafc;
    }
    .ezy-dev-item {
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
    }
    .ezy-dev-item:hover {
        border-color: #94a3b8;
        box-shadow: 0 2px 8px rgba(15,23,42,.12);
    }
    .ezy-dev-item.is-dragging { opacity: .45; }
    .ezy-dev-item.st-up     { border-left-color: #22c55e; }
    .ezy-dev-item.st-down   { border-left-color: #dc2626; background: #fef2f2; }
    .ezy-dev-item.st-warning{ border-left-color: #f59e0b; background: #fffbeb; }

    .ezy-dev-icon { width: 30px; height: 30px; flex: 0 0 30px; object-fit: contain; }
    .ezy-dev-info { min-width: 0; flex: 1 1 auto; }
    .ezy-dev-name {
        font-size: 12px; font-weight: 700; color: #0f172a;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        display: flex; align-items: center; gap: 4px;
    }
    .ezy-dev-meta {
        font-size: 10px; color: #64748b;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .ezy-dev-status { width: 8px; height: 8px; border-radius: 50%; flex: 0 0 8px; display: inline-block; }
    .ezy-dev-status.up      { background: #22c55e; }
    .ezy-dev-status.down    { background: #dc2626; }
    .ezy-dev-status.warning { background: #f59e0b; }
    .ezy-dev-status.other   { background: #94a3b8; }

    .ezy-dev-actions { display: flex; flex-direction: column; gap: 2px; flex-shrink: 0; }
    .ezy-dev-btn {
        width: 24px; height: 22px; border: 1px solid #cbd5e1; background: #fff;
        border-radius: 4px; color: #475569; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 12px; padding: 0; line-height: 1;
    }
    .ezy-dev-btn:hover { background: #2563eb; color: #fff; border-color: #2563eb; }
    .ezy-dev-btn.danger:hover { background: #dc2626; border-color: #dc2626; }
    .ezy-devpanel-empty { text-align: center; padding: 26px 12px; color: #94a3b8; font-size: 12px; }
    .ezy-devpanel-foot {
        border-top: 1px solid #e2e8f0; padding: 8px 10px;
        font-size: 10.5px; color: #64748b; background: #fff; flex-shrink: 0;
    }

    /* Icon preview di modal Add/Edit */
    .ezy-icon-preview {
        width: 26px;
        height: 26px;
        object-fit: contain;
        display: block;
    }
</style>

<aside class="ezy-device-panel" id="ezyDevicePanel">
    <div class="ezy-devpanel-head">
        <div>
            <div class="ezy-devpanel-title">
                <em class="icon ni ni-hard-drive"></em> Daftar Device
            </div>
            <span class="ezy-devpanel-count" id="ezyDeviceCount">0 perangkat</span>
        </div>
    </div>

    <div class="ezy-devpanel-search">
        <input type="text" class="form-control form-control-sm" id="ezyDeviceSearch"
               placeholder="Cari nama / IP device..." autocomplete="off">
    </div>

    <div class="ezy-devpanel-list" id="ezyDeviceList"></div>

    <div class="ezy-devpanel-foot">
        @if($panelSuperAdmin)
            <em class="icon ni ni-info me-1"></em> Seret device ke kanvas untuk menempatkan posisinya.
        @else
            <em class="icon ni ni-eye me-1"></em> Mode baca saja.
        @endif
    </div>
</aside>

{{-- MODAL: TAMBAH DEVICE --}}
<div class="modal fade" id="ezyModalAddDevice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="ezyFormAddDevice">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <em class="icon ni ni-plus-circle text-primary me-2"></em>
                        Tambah Perangkat EzyHome
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: POP Gresik" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text p-1" style="width:42px; justify-content:center;">
                                    <img id="ezyAddTypeIcon"
                                         src="{{ asset('images/devices/router.png') }}"
                                         alt="icon"
                                         class="ezy-icon-preview">
                                </span>
                                <select name="type" id="ezyAddType" class="form-select" required>
                                    @foreach($deviceTypes as $deviceType)
                                        <option value="{{ $deviceType }}" @selected($deviceType === 'Router')>{{ $deviceType }}</option>
                                    @endforeach
                                </select>
                            </div>
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
                            <label class="form-label">IP Address</label>
                            <input type="text" name="ip_address" class="form-control" placeholder="10.0.0.1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Router Version</label>
                            <input type="text" name="router_version" class="form-control" placeholder="Contoh: IOS-XE 17.3.1">
                        </div>
                        <div class="col-12">
                            <label class="form-label">POP / Area</label>
                            <select name="network_area_id" id="ezyAddArea" class="form-select">
                                <option value="">— Tanpa area (langsung taruh bebas) —</option>
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size:11px;">
                                <em class="icon ni ni-info"></em>
                                Kalau dipilih, device langsung ditempatkan di area tersebut. Kalau kosong, device masuk daftar panel saja — bisa di-drag manual ke canvas nanti.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="ezyBtnSubmitAddDevice">
                        <em class="icon ni ni-save me-1"></em> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: EDIT DEVICE --}}
<div class="modal fade" id="ezyModalEditDevice" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="ezyFormEditDevice">
                <input type="hidden" name="id" id="ezyEditDeviceId">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <em class="icon ni ni-edit text-warning me-2"></em> Edit Perangkat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="ezyEditName" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text p-1" style="width:42px; justify-content:center;">
                                    <img id="ezyEditTypeIcon"
                                         src="{{ asset('images/devices/server.png') }}"
                                         alt="icon"
                                         class="ezy-icon-preview">
                                </span>
                                <select name="type" id="ezyEditType" class="form-select" required>
                                    @foreach($deviceTypes as $deviceType)
                                        <option value="{{ $deviceType }}">{{ $deviceType }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Protocol <span class="text-danger">*</span></label>
                            <select name="protocol" id="ezyEditProtocol" class="form-select" required>
                                @foreach($protocols as $protocol)
                                    <option value="{{ $protocol }}">{{ $protocol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">IP Address</label>
                            <input type="text" name="ip_address" id="ezyEditIp" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Router Version</label>
                            <input type="text" name="router_version" id="ezyEditRouterVersion" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="ezyBtnSubmitEditDevice">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: KONFIRMASI HAPUS --}}
<div class="modal fade" id="ezyModalConfirmDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title fs-14px">
                    <em class="icon ni ni-alert-circle text-danger me-1"></em>
                    <span id="ezyDeleteTitle">Hapus?</span>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3 text-center">
                <p class="mb-0" id="ezyDeleteName">-</p>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-sm" id="ezyBtnConfirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: HUBUNGKAN DUA DEVICE --}}
<div class="modal fade" id="ezyModalConnect" tabindex="-1" aria-hidden="true">
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
                        <span id="ezyConnFrom">-</span>
                        <span class="text-primary px-1" id="ezyConnSymbol">&rarr;</span>
                        <span id="ezyConnTo">-</span>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small">Tipe Media</label>
                    <select class="form-select form-select-sm" id="ezyConnType">
                        <option value="ethernet">Ethernet</option>
                        <option value="fiber">Fiber Optic</option>
                        <option value="wireless">Wireless Link</option>
                        <option value="serial">Serial / WAN</option>
                    </select>
                </div>
                <label class="form-label fw-bold small">Arah Panah</label>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success btn-sm text-start" data-ezy-dir="forward">
                        <strong>&rarr; Forward</strong>
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm text-start" data-ezy-dir="reverse">
                        <strong>&larr; Reverse</strong>
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm text-start" data-ezy-dir="bidirectional">
                        <strong>&harr; Dua Arah</strong>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm text-start" data-ezy-dir="none">
                        <strong>&mdash; Tanpa Panah</strong>
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

    var URLS = {
        list:        {!! json_encode(route('admin.ezyhome.devices')) !!},
        store:       {!! json_encode(route('admin.ezyhome.device.store')) !!},
        updateTpl:   {!! json_encode(route('admin.ezyhome.device.update', ['device' => '__DEVICE_ID__'])) !!},
        destroyTpl:  {!! json_encode(route('admin.ezyhome.device.destroy', ['device' => '__DEVICE_ID__'])) !!},
        positionTpl: {!! json_encode(route('admin.ezyhome.device.position', ['device' => '__DEVICE_ID__'])) !!},
        positionDestroyTpl: {!! json_encode(route('admin.ezyhome.position.destroy', ['position' => '__POSITION_ID__'])) !!},
        connection:  {!! json_encode(route('admin.ezyhome.connection.store')) !!},
        detailTpl:   {!! json_encode(route('admin.ezyhome.device', ['device' => '__DEVICE_ID__'])) !!}
    };
    var csrfToken = @json(csrf_token());

    var listEl   = document.getElementById('ezyDeviceList');
    var countEl  = document.getElementById('ezyDeviceCount');
    var searchEl = document.getElementById('ezyDeviceSearch');
    var panelEl  = document.getElementById('ezyDevicePanel');
    var viewport = document.getElementById('ezyViewport');
    var dropHint = document.createElement('div');

    if (!listEl) return;

    dropHint.className = 'ezy-drop-hint';
    dropHint.id = 'ezyDropHint';
    if (viewport) viewport.appendChild(dropHint);

    var devices = {!! json_encode(array_values($deviceList)) !!};
    var draggedDevice = null;
    var pendingConnect = null;
    var pendingDelete = null;

    function iconFile(key) {
        return ICONS[String(key || '').toLowerCase()] || 'server.png';
    }

    // ─── Icon preview untuk modal Add/Edit ───
    function typeToIconFile(type) {
        var map = {
            'Router':       'router.png',
            'Switch':       'switch.png',
            'OLT':          'switch.png',
            'EPON':         'router.png',
            'Server':       'server.png',
            'Access Point': 'wifi.png',
            'Other':        'pc.png'
        };
        return map[type] || 'server.png';
    }

    function bindIconPreview(selectEl, imgEl) {
        if (!selectEl || !imgEl) return;
        function update() {
            imgEl.src = iconBase + '/' + typeToIconFile(selectEl.value);
        }
        update();
        selectEl.addEventListener('change', update);
    }

    // ─── Populate dropdown POP/Area dari data topology ───
    function populateAreaDropdown() {
        var sel = document.getElementById('ezyAddArea');
        if (!sel) return;

        var topo = window.EzyHomeTopology;
        var areas = (topo && typeof topo.getAreas === 'function')
            ? (topo.getAreas() || [])
            : [];

        var current = sel.value;

        sel.innerHTML = '<option value="">— Tanpa area (langsung taruh bebas) —<\/option>';
        areas.forEach(function (a) {
            var opt = document.createElement('option');
            opt.value = a.id;
            opt.textContent = a.name + (a.code ? ' (' + a.code + ')' : '');
            sel.appendChild(opt);
        });

        sel.value = current;
    }

    function escapeHtml(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;').replace(/<\/g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function statusClass(status) {
        var s = String(status || '').toLowerCase();
        return s === 'up' ? 'up' : (s === 'down' ? 'down' : (s === 'warning' ? 'warning' : 'other'));
    }

    function visibleDevices() {
        var q = (searchEl && searchEl.value || '').toLowerCase().trim();
        if (!q) return devices;
        return devices.filter(function (d) {
            return (d.name + ' ' + (d.ip || '') + ' ' + (d.type || '')).toLowerCase().indexOf(q) !== -1;
        });
    }

    function render() {
        if (!listEl) return;
        var rows = visibleDevices();
        var placed = devices.filter(function (d) { return d.placed; }).length;

        if (countEl) countEl.textContent = devices.length + ' perangkat · ' + placed + ' di map';

        if (!rows.length) {
            listEl.innerHTML = '<div class="ezy-devpanel-empty">'
                + '<em class="icon ni ni-inbox fs-3 d-block mb-1"><\/em>'
                + (devices.length ? 'Tidak ada device cocok.' : 'Belum ada perangkat.')
                + '<\/div>';
            return;
        }

        var html = '';
        rows.forEach(function (d) {
            var st = statusClass(d.status);
            var actions = '';
            if (isSuperAdmin) {
                actions = '<div class="ezy-dev-actions">'
                    + '<button type="button" class="ezy-dev-btn" data-ezy-act="edit" data-id="' + d.id + '" title="Edit"><em class="icon ni ni-edit"><\/em><\/button>'
                    + '<button type="button" class="ezy-dev-btn" data-ezy-act="focus" data-id="' + d.id + '" title="Sorot"><em class="icon ni ni-target"><\/em><\/button>'
                    + '<button type="button" class="ezy-dev-btn danger" data-ezy-act="delete" data-id="' + d.id + '" title="Hapus"><em class="icon ni ni-trash"><\/em><\/button>'
                    + '<\/div>';
            }

            html += '<div class="ezy-dev-item st-' + escapeHtml(st) + '"'
                + ' data-id="' + d.id + '" data-position-id="' + (d.position_id || '') + '"'
                + ' data-name="' + escapeHtml(d.name) + '"'
                + (isSuperAdmin ? ' draggable="true"' : '') + '>'
                + '<img class="ezy-dev-icon" src="' + iconBase + '/' + iconFile(d.icon) + '" alt="">'
                + '<div class="ezy-dev-info">'
                + '<div class="ezy-dev-name"><span class="ezy-dev-status ' + escapeHtml(st) + '"><\/span>'
                + escapeHtml(d.name) + '<\/div>'
                + '<div class="ezy-dev-meta">' + escapeHtml(d.ip || '-')
                + ' • ' + escapeHtml(d.type || '-')
                + ' • ' + escapeHtml(d.protocol || '-')
                + '<\/div>'
                + '<\/div>' + actions
                + '<\/div>';
        });
        window.EzyHomeDeviceList = devices;
        listEl.innerHTML = html;

        if (isSuperAdmin) {
            listEl.querySelectorAll('.ezy-dev-item[draggable="true"]').forEach(bindDrag);
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

    function bindDrag(item) {
        item.addEventListener('dragstart', function (e) {
            draggedDevice = devices.find(function (d) { return String(d.id) === item.dataset.id; }) || null;
            item.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
            try {
                e.dataTransfer.setData('text/plain', item.dataset.id);
                e.dataTransfer.setData('application/x-ezy-device', item.dataset.id);
            } catch (err) {}
        });
        item.addEventListener('dragend', function () {
            item.classList.remove('is-dragging');
            draggedDevice = null;
            hideDropHint();
            if (viewport) viewport.classList.remove('is-drop-target');
        });
    }

    function hideDropHint() { dropHint.classList.remove('is-visible'); }

    function showDropHint(x, y, deviceName, areaName) {
        var vp = document.getElementById('ezyViewport');
        if (!vp) return;
        var rect = vp.getBoundingClientRect();
        dropHint.style.left = (x - rect.left) + 'px';
        dropHint.style.top = (y - rect.top) + 'px';
        dropHint.innerHTML = 'Taruh <strong>' + escapeHtml(deviceName) + '<\/strong>'
            + (areaName ? ' di <span class="area">' + escapeHtml(areaName) + '<\/span>' : '');
        dropHint.classList.add('is-visible');
    }

    function getViewport() { return document.getElementById('ezyViewport'); }

    document.addEventListener('dragover', function (e) {
        var vp = getViewport();
        if (!vp || !vp.contains(e.target)) return;
        if (!isSuperAdmin) return;
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        vp.classList.add('is-drop-target');
        if (!draggedDevice) return;
        var topo = window.EzyHomeTopology;
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
            id = e.dataTransfer.getData('application/x-ezy-device')
                || e.dataTransfer.getData('text/plain');
        } catch (err) {}

        var device = draggedDevice
            || devices.find(function (d) { return String(d.id) === String(id); });
        if (!device) return;

        var topo = window.EzyHomeTopology;
        if (!topo || !topo.getSvgPoint) {
            showToast('Map belum siap.', true);
            return;
        }

        var pt = topo.getSvgPoint(e);
        placeDevice(device, Math.round(pt.x), Math.round(pt.y));
    });

    function placeDevice(device, x, y) {
        var topo = window.EzyHomeTopology;
        var area = topo && topo.areaAt ? topo.areaAt(x, y) : null;

        var body = { pos_x: x, pos_y: y };
        if (area && area.id) body.ezyhome_area_id = area.id;
        // Drag dari panel = SELALU create position baru (multi-position)       if (device.position_id) body.position_id = device.position_id;

        fetch(URLS.positionTpl.replace('__DEVICE_ID__', device.id), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(body)
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (!res || !res.success) {
                showToast((res && res.message) || 'Gagal simpan posisi.', true);
                return;
            }
            if (res.devices) { devices = res.devices; render(); }
            if (res.topology && topo && topo.render) { topo.render(res.topology); }
            showToast('Device ' + device.name + ' ditempatkan.');
        })
        .catch(function () { showToast('Gagal simpan posisi.', true); });
    }

    function showToast(message, isError) {
        if (window.EzyHomeTopology && window.EzyHomeTopology.showToast) {
            window.EzyHomeTopology.showToast(message, isError);
            return;
        }
        alert(message);
    }

    function modalOf(id) {
        var el = document.getElementById(id);
        return el ? bootstrap.Modal.getOrCreateInstance(el) : null;
    }

    function fieldErrors(res) {
        var bag = (res && res.errors) ? res.errors : {};
        var out = [];
        for (var key in bag) if (Object.prototype.hasOwnProperty.call(bag, key)) out.push(bag[key][0]);
        return out.join(' ');
    }

    var addForm = document.getElementById('ezyFormAddDevice');
    if (addForm) {
        addForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = document.getElementById('ezyBtnSubmitAddDevice');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"><\/span> Menyimpan...';

            fetch(URLS.store, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({
                    name: addForm.name.value,
                    type: addForm.type.value,
                    protocol: addForm.protocol.value,
                    ip_address: addForm.ip_address.value || null,
                    router_version: addForm.router_version.value || null,
                    network_area_id: addForm.network_area_id
                        ? (addForm.network_area_id.value || null)
                        : null,
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                btn.disabled = false;
                btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Simpan';
                if (res && res.success) {
                    if (res.devices) { devices = res.devices; render(); }
                    if (res.topology && window.EzyHomeTopology && window.EzyHomeTopology.render) {
                        window.EzyHomeTopology.render(res.topology);
                    }
                    modalOf('ezyModalAddDevice').hide();
                    addForm.reset();
                    showToast(res.message);
                    } else {
                    var msg = fieldErrors(res) || (res && res.message) || 'Gagal.';
                    showToast(msg, true);
                }
            })
            .catch(function () {
                btn.disabled = false;
                btn.innerHTML = '<em class="icon ni ni-save me-1"><\/em> Simpan';
                showToast('Terjadi kesalahan.', true);
            });
        });
    }

    function openEditDevice(device) {
        if (!device) return;
        document.getElementById('ezyEditDeviceId').value = device.id;
        document.getElementById('ezyEditName').value = device.name || '';
        document.getElementById('ezyEditType').value = device.type || 'Other';
        document.getElementById('ezyEditProtocol').value = device.protocol || 'ICMP';
        document.getElementById('ezyEditIp').value = device.ip || '';
        document.getElementById('ezyEditRouterVersion').value = device.router_version || '';

        var editTypeIconEl = document.getElementById('ezyEditTypeIcon');
        if (editTypeIconEl) {
            editTypeIconEl.src = iconBase + '/' + typeToIconFile(device.type || 'Other');
        }

        modalOf('ezyModalEditDevice').show();
    }

    var editForm = document.getElementById('ezyFormEditDevice');
    if (editForm) {
        editForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var id = document.getElementById('ezyEditDeviceId').value;
            var btn = document.getElementById('ezyBtnSubmitEditDevice');
            btn.disabled = true;

            fetch(URLS.updateTpl.replace('__DEVICE_ID__', id), {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({
                    name: editForm.name.value,
                    type: editForm.type.value,
                    protocol: editForm.protocol.value,
                    ip_address: editForm.ip_address.value || null,
                    router_version: editForm.router_version.value || null,
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                btn.disabled = false;
                if (res && res.success) {
                    if (res.devices) { devices = res.devices; render(); }
                    if (res.topology && window.EzyHomeTopology && window.EzyHomeTopology.render) {
                        window.EzyHomeTopology.render(res.topology);
                    }
                    modalOf('ezyModalEditDevice').hide();
                    showToast(res.message);
                } else {
                    showToast(fieldErrors(res) || (res && res.message) || 'Gagal.', true);
                }
            })
            .catch(function () {
                btn.disabled = false;
                showToast('Terjadi kesalahan.', true);
            });
        });
    }

    function destroyDevice(device) {
        if (!device) return;
        var posId = device.position_id || null;

        document.getElementById('ezyDeleteTitle').textContent = 'Hapus ' + (device.name || 'perangkat') + '?';
        document.getElementById('ezyDeleteName').textContent = posId && posId > 0
            ? 'Posisi di POP ini akan dihapus.'
            : 'Perangkat akan dihapus dari database.';

        pendingDelete = { device: device, posId: posId };
        modalOf('ezyModalConfirmDelete').show();
    }

    var btnConfirmDelete = document.getElementById('ezyBtnConfirmDelete');
    if (btnConfirmDelete) {
        btnConfirmDelete.addEventListener('click', function () {
            if (!pendingDelete) return;
            var pd = pendingDelete;
            pendingDelete = null;

            var url = (pd.posId && pd.posId > 0)
                ? URLS.positionDestroyTpl.replace('__POSITION_ID__', pd.posId)
                : URLS.destroyTpl.replace('__DEVICE_ID__', pd.device.id);

            var modal = bootstrap.Modal.getInstance(document.getElementById('ezyModalConfirmDelete'));
            if (modal) modal.hide();

            fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res && res.success) {
                    if (res.devices) { devices = res.devices; render(); }
                    if (res.topology && window.EzyHomeTopology && window.EzyHomeTopology.render) {
                        window.EzyHomeTopology.render(res.topology);
                    }
                    showToast(res.message);
                } else {
                    showToast((res && res.message) || 'Gagal hapus.', true);
                }
            })
            .catch(function () { showToast('Gagal hapus.', true); });
        });
    }

    if (listEl) {
        listEl.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-ezy-act]');
            if (btn) {
                var itemEl = btn.closest('.ezy-dev-item');
                var posId = itemEl ? itemEl.dataset.positionId : '';
                var device = devices.find(function (d) {
                    return String(d.id) === btn.dataset.id
                        && (posId ? String(d.position_id) === String(posId) : true);
                });
                if (btn.dataset.ezyAct === 'edit') openEditDevice(device);
                if (btn.dataset.ezyAct === 'delete') destroyDevice(device);
                if (btn.dataset.ezyAct === 'focus' && window.EzyHomeTopology) {
                    window.EzyHomeTopology.focusNode(parseInt(btn.dataset.id, 10));
                }
                return;
            }
            var item = e.target.closest('.ezy-dev-item');
            if (item) {
                var target = devices.find(function (d) { return String(d.id) === item.dataset.id; });
                if (target && window.openEzyDeviceModal) window.openEzyDeviceModal(target.id, target);
            }
        });
    }

    if (searchEl) searchEl.addEventListener('input', render);

    window.EzyConnectRequested = function (fromNode, toNode) {
        pendingConnect = { from: fromNode, to: toNode };
        document.getElementById('ezyConnFrom').textContent = fromNode.name;
        document.getElementById('ezyConnTo').textContent = toNode.name;
        document.getElementById('ezyConnSymbol').textContent = '→';
        modalOf('ezyModalConnect').show();
    };

    var connModalEl = document.getElementById('ezyModalConnect');
    if (connModalEl) {
        connModalEl.querySelectorAll('[data-ezy-dir]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!pendingConnect) return;
                var dir = btn.dataset.ezyDir;
                var type = document.getElementById('ezyConnType').value;

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
                    if (res && res.success) {
                        bootstrap.Modal.getInstance(connModalEl).hide();
                        if (res.topology && window.EzyHomeTopology && window.EzyHomeTopology.render) {
                            window.EzyHomeTopology.render(res.topology);
                        }
                        if (window.EzyHomeTopology && window.EzyHomeTopology.setConnectMode) {
                            window.EzyHomeTopology.setConnectMode(false);
                        }
                        showToast('Relasi disimpan.');
                        pendingConnect = null;
                    } else {
                        showToast(fieldErrors(res) || (res && res.message) || 'Gagal.', true);
                    }
                })
                .catch(function () { showToast('Gagal simpan relasi.', true); });
            });
        });
    }

    window.EzyHomeDevicePanel = {
        refresh: refreshDevices,
        render: render,
        find: function (id) {
            return devices.find(function (d) { return String(d.id) === String(id); }) || null;
        },
        openAdd: function () {
            populateAreaDropdown();
            modalOf('ezyModalAddDevice').show();
        },        openEdit: function (id) { openEditDevice(this.find(id)); },
        deleteDevice: function (device) { destroyDevice(device); }
    };

    // Bind icon preview di kedua modal
    bindIconPreview(
        document.getElementById('ezyAddType'),
        document.getElementById('ezyAddTypeIcon')
    );
    bindIconPreview(
        document.getElementById('ezyEditType'),
        document.getElementById('ezyEditTypeIcon')
    );

    render();
    setInterval(refreshDevices, 30000);
})();
</script>