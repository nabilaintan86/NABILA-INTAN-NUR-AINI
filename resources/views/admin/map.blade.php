@extends('layouts.admin')

@section('title', 'Network Map · RADNET NOC')

@push('styles')
<style>
    /* ===========================================================
       NETWORK MAP — Pure HTML/CSS (WhaTsUp-style)
       =========================================================== */

    .nm-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
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
    .nm-dot-up      { background: #10b981; }
    .nm-dot-down    { background: #ef4444; }
    .nm-dot-warning { background: #f59e0b; }
    .nm-dot-unknown { background: #cbd5e1; }

    .nm-refresh {
        font-size: 11px;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .nm-refresh .icon { font-size: 12px; }

    /* ---- Grid: POP groups ---- */
    .nm-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }

    /* ---- Single POP group ---- */
    .nm-group {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        overflow: hidden;
    }

    .nm-group-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }
    .nm-group-head-title {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: .3px;
    }
    .nm-group-head-count {
        font-size: 10px;
        font-weight: 600;
        color: #94a3b8;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 1px 7px;
    }
    .nm-group-head-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-left: auto;
        flex-shrink: 0;
    }

    .nm-group-body {
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* ---- Device card (compact) ---- */
    .nm-dev {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fff;
        cursor: pointer;
        transition: box-shadow .15s, border-color .15s;
        min-width: 0;
        flex: 1 1 auto;
        max-width: 220px;
    }
    .nm-dev:hover {
        border-color: #93c5fd;
        box-shadow: 0 1px 4px rgba(59,130,246,.15);
    }

    .nm-dev-icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 4px;
        object-fit: contain;
        background: #f8fafc;
        padding: 2px;
    }

    .nm-dev-info {
        min-width: 0;
        flex: 1;
    }
    .nm-dev-name {
        font-size: 11px;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .nm-dev-name .nm-dot {
        width: 7px;
        height: 7px;
        flex-shrink: 0;
    }
    .nm-dev-ip {
        font-size: 10px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .nm-dev-links {
        flex-shrink: 0;
        font-size: 9px;
        font-weight: 600;
        color: #3b82f6;
        background: #eff6ff;
        border-radius: 8px;
        padding: 1px 6px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    /* ---- Detail popup (shown on device click) ---- */
    .nm-detail-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,.25);
        z-index: 999;
        align-items: center;
        justify-content: center;
    }
    .nm-detail-overlay.active { display: flex; }
    .nm-detail-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px 24px;
        min-width: 280px;
        max-width: 380px;
        box-shadow: 0 8px 30px rgba(0,0,0,.18);
        position: relative;
    }
    .nm-detail-card .close-btn {
        position: absolute;
        top: 10px;
        right: 14px;
        background: none;
        border: none;
        font-size: 18px;
        color: #94a3b8;
        cursor: pointer;
        line-height: 1;
    }
    .nm-detail-card .close-btn:hover { color: #334155; }
    .nm-detail-card h3 {
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .nm-detail-card h3 img {
        width: 24px;
        height: 24px;
    }
    .nm-detail-row {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        font-size: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .nm-detail-row:last-child { border-bottom: none; }
    .nm-detail-label { color: #64748b; }
    .nm-detail-value { font-weight: 600; color: #0f172a; }

    /* ---- Empty state ---- */
    .nm-empty {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .nm-empty .icon { font-size: 48px; margin-bottom: 8px; }

    /* ---- Responsive ---- */
    @media (max-width: 640px) {
        .nm-grid {
            grid-template-columns: 1fr;
        }
        .nm-dev { max-width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="nk-content">
    <div class="container">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <nav>
                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Network Map
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="nk-block-head-content">
                            <span class="nm-refresh">
                                <em class="icon ni ni-reload"></em>
                                <span id="lastUpdated">Memuat...</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="card">
                        <div class="card-body">

                            @if(!empty($groups['groups']) && count($groups['groups']) > 0)

                                <div class="nm-header">
                                    <div class="nm-header-title">RADNET Wireless Network 2025</div>
                                    <div class="nm-legend">
                                        <span><span class="nm-dot nm-dot-up"></span> UP</span>
                                        <span><span class="nm-dot nm-dot-down"></span> DOWN</span>
                                        <span><span class="nm-dot nm-dot-warning"></span> WARNING</span>
                                    </div>
                                </div>

                                <div class="nm-grid" id="nmGrid">
                                    @foreach($groups['groups'] as $popName => $group)
                                        @php
                                            $upCount = collect($group['devices'])->where('status', 'up')->count();
                                            $downCount = collect($group['devices'])->where('status', 'down')->count();
                                            $totalCount = count($group['devices']);
                                            $groupDot = $downCount > 0 ? 'down' : ($upCount === $totalCount ? 'up' : 'warning');
                                        @endphp
                                        <div class="nm-group">
                                            <div class="nm-group-head">
                                                <span class="nm-group-head-title">{{ $popName }}</span>
                                                <span class="nm-group-head-count">{{ $totalCount }}</span>
                                                <span class="nm-group-head-dot nm-dot nm-dot-{{ $groupDot }}"></span>
                                            </div>
                                            <div class="nm-group-body">
                                                @foreach($group['devices'] as $device)
                                                    <div class="nm-dev"
                                                         data-id="{{ $device['id'] }}"
                                                         data-name="{{ $device['name'] }}"
                                                         data-ip="{{ $device['ip'] }}"
                                                         data-status="{{ $device['status'] }}"
                                                         data-pop="{{ $popName }}"
                                                         data-icon="{{ asset('images/devices/' . $device['icon_type'] . '.png') }}"
                                                         onclick="showDetail(this)">
                                                        <img class="nm-dev-icon"
                                                             src="{{ asset('images/devices/' . $device['icon_type'] . '.png') }}"
                                                             alt="{{ $device['icon_type'] }}"
                                                             onerror="this.src='{{ asset('images/devices/server.png') }}'">
                                                        <div class="nm-dev-info">
                                                            <div class="nm-dev-name">
                                                                <span class="nm-dot nm-dot-{{ $device['status'] }}"
                                                                      data-status-dot="{{ $device['id'] }}"></span>
                                                                {{ $device['name'] }}
                                                            </div>
                                                            <div class="nm-dev-ip">{{ $device['ip'] }}</div>
                                                        </div>
                                                        @if($device['connections'] > 0)
                                                            <span class="nm-dev-links"
                                                                  title="{{ $device['connections'] }} link aktif">
                                                                <em class="icon ni ni-link"></em>{{ $device['connections'] }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @else
                                <div class="nm-empty">
                                    <div class="icon">🗺️</div>
                                    <p>Belum ada data jaringan untuk ditampilkan.</p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Detail popup -->
<div class="nm-detail-overlay" id="detailOverlay" onclick="if(event.target===this)closeDetail()">
    <div class="nm-detail-card">
        <button class="close-btn" onclick="closeDetail()">&times;</button>
        <h3>
            <img id="detailIcon" src="" alt="">
            <span id="detailName"></span>
        </h3>
        <div class="nm-detail-row">
            <span class="nm-detail-label">IP Address</span>
            <span class="nm-detail-value" id="detailIP"></span>
        </div>
        <div class="nm-detail-row">
            <span class="nm-detail-label">Lokasi / POP</span>
            <span class="nm-detail-value" id="detailPOP"></span>
        </div>
        <div class="nm-detail-row">
            <span class="nm-detail-label">Status</span>
            <span class="nm-detail-value" id="detailStatus"></span>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
(function () {
    var lastUpdated = document.getElementById('lastUpdated');

    function updateTimestamp() {
        lastUpdated.textContent = 'Diperbarui: ' + new Date().toLocaleTimeString('id-ID');
    }

    updateTimestamp();

    /* ---- Auto refresh status tiap 30 detik ---- */
    setInterval(function () {
        fetch('{{ route('admin.map.data') }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) { return res.json(); })
        .then(function (payload) {
            var groups = payload.groups || {};
            for (var popName in groups) {
                var devs = groups[popName].devices || [];
                for (var i = 0; i < devs.length; i++) {
                    var d = devs[i];
                    var dot = document.querySelector('[data-status-dot="' + d.id + '"]');
                    if (dot) {
                        dot.className = 'nm-dot nm-dot-' + d.status;
                    }
                }
            }
            updateTimestamp();
        })
        .catch(function () {
            lastUpdated.textContent = 'Gagal memperbarui status.';
        });
    }, 30000);
})();

/* ---- Detail popup ---- */
function showDetail(el) {
    document.getElementById('detailIcon').src = el.dataset.icon;
    document.getElementById('detailName').textContent = el.dataset.name;
    document.getElementById('detailIP').textContent = el.dataset.ip;
    document.getElementById('detailPOP').textContent = el.dataset.pop;

    var st = el.dataset.status.toUpperCase();
    var stEl = document.getElementById('detailStatus');
    stEl.textContent = st;
    stEl.style.color = el.dataset.status === 'down' ? '#ef4444' :
                        el.dataset.status === 'warning' ? '#f59e0b' : '#10b981';

    document.getElementById('detailOverlay').classList.add('active');
}

function closeDetail() {
    document.getElementById('detailOverlay').classList.remove('active');
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDetail();
});
</script>
@endpush
