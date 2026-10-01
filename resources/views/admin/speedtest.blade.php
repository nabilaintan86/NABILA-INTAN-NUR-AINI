@extends('layouts.admin')

@section('title', 'Speed Test · RADNET NOC')

@push('styles')
<style>
    /* ══ PANEL ════════════════════════════════════════════ */
    .st-panel {
        background: #fff;
        border: 1px solid #e5e9f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .st-panel-head {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
        background: #fbfcfe;
        border-bottom: 1px solid #eef1f6;
    }
    .st-panel-no {
        width: 28px; height: 28px; flex-shrink: 0;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        background: #1d4ed8; color: #fff;
        font-size: 13px; font-weight: 800;
    }
    .st-panel-title {
        margin: 0; font-size: 15px; font-weight: 800; color: #16223c; line-height: 1.3;
    }
    .st-panel-tag {
        display: inline-block;
        font-size: 10px; font-weight: 800; letter-spacing: .3px;
        text-transform: uppercase;
        padding: 2px 7px; border-radius: 999px;
        background: #eef2ff; color: #4338ca;
        vertical-align: 2px;
    }
    .st-panel-sub {
        margin: 2px 0 0; font-size: 12.5px; color: #64748b; line-height: 1.5;
    }
    .st-panel-body { padding: 20px 22px; }

    /* ══ KOTAK TARGET AKTIF ═══════════════════════════════ */
    .st-target-box {
        border: 1px solid #e5e9f0;
        border-radius: 14px;
        background: #f8fafc;
        padding: 16px 18px;
    }
    .st-target-now {
        display: flex; align-items: center; gap: 12px;
        padding-bottom: 14px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .st-target-now-ic {
        width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #1d4ed8; color: #fff; font-size: 18px;
    }
    .st-target-box.is-manual .st-target-now-ic { background: #6d28d9; }
    .st-target-now-txt { min-width: 0; flex: 1 1 auto; }
    .st-target-now-label {
        font-size: 10px; font-weight: 800; letter-spacing: .6px;
        text-transform: uppercase; color: #64748b;
    }
    .st-target-now-name {
        font-size: 15px; font-weight: 800; color: #16223c;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .st-target-now-ip {
        font-size: 12.5px; color: #475569;
        font-family: 'Consolas', 'Menlo', monospace;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .st-status-badge {
        flex-shrink: 0;
        font-size: 10.5px; font-weight: 800; letter-spacing: .3px;
        padding: 3px 8px; border-radius: 999px;
    }
    .st-status-up      { background: #dcfce7; color: #15803d; }
    .st-status-down    { background: #fee2e2; color: #dc2626; }
    .st-status-warning { background: #fef9c3; color: #a16207; }

    /* ══ OPSI A · PILIH DARI DAFTAR ═══════════════════════ */
    .st-option { margin-top: 15px; }
    .st-option-head {
        display: flex; align-items: center; gap: 7px;
        font-size: 12.5px; font-weight: 700; color: #334155; margin-bottom: 8px;
    }
    .st-option-key {
        width: 21px; height: 21px; border-radius: 6px; flex-shrink: 0;
        display: inline-flex; align-items: center; justify-content: center;
        background: #1d4ed8; color: #fff;
        font-size: 10.5px; font-weight: 800;
    }
    .st-option-key--b { background: #6d28d9; }

    .st-picker { position: relative; }
    .st-picker-btn {
        display: flex; align-items: center; gap: 11px;
        width: 100%; text-align: left;
        padding: 11px 14px;
        border: 1px solid #e2e8f0; border-radius: 10px;
        background: #fff; cursor: pointer;
        transition: border-color .15s, background .15s, box-shadow .15s;
    }
    .st-picker-btn:hover { border-color: #93c5fd; background: #f8fbff; }
    .st-picker.is-open .st-picker-btn {
        border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, .12);
    }
    .st-picker-btn-ic {
        width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #eef2ff; color: #1d4ed8; font-size: 15px;
    }
    .st-picker-btn-txt { min-width: 0; flex: 1 1 auto; }
    .st-picker-btn-label { display: block; font-size: 13.5px; font-weight: 700; color: #16223c; }
    .st-picker-btn-hint {
        display: block; font-size: 11.5px; color: #64748b; margin-top: 1px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .st-picker-btn .st-caret {
        flex-shrink: 0; font-size: 13px; color: #94a3b8;
        transition: transform .2s;
    }
    .st-picker.is-open .st-caret { transform: rotate(180deg); }

    /* Panel daftar hanya muncul saat tombol diklik, jadi daftar panjang tidak
       pernah memenuhi layar sejak halaman dibuka. */
    .st-picker-panel {
        display: none;
        margin-top: 8px;
        border: 1px solid #e2e8f0; border-radius: 11px;
        background: #fff; overflow: hidden;
    }
    .st-picker.is-open .st-picker-panel { display: block; }
    .st-picker-search { position: relative; border-bottom: 1px solid #eef2f7; }
    .st-picker-search .icon {
        position: absolute; left: 12px; top: 50%;
        transform: translateY(-50%);
        font-size: 13px; color: #94a3b8; pointer-events: none;
    }
    .st-picker-search input {
        width: 100%; border: 0; outline: 0; background: #fff;
        padding: 12px 14px 12px 33px;
        font-size: 13.5px; color: #16223c;
    }
    .st-picker-list {
        max-height: 260px; min-height: 120px;
        overflow-y: auto; padding: 6px;
    }
    .st-picker-list::-webkit-scrollbar { width: 8px; }
    .st-picker-list::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .st-picker-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .st-picker-list::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    .st-picker-opt {
        display: flex; align-items: center; gap: 10px;
        width: 100%; text-align: left;
        padding: 8px 10px; margin-bottom: 3px;
        border: 1px solid transparent; border-radius: 9px;
        background: #fff; cursor: pointer;
        transition: background .12s, border-color .12s;
    }
    .st-picker-opt:last-child { margin-bottom: 0; }
    .st-picker-opt:hover { background: #f1f5f9; border-color: #e2e8f0; }
    .st-picker-opt.is-active {
        background: #eff6ff; border-color: #93c5fd;
    }
    .st-picker-opt-ic {
        width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #e2e8f0; color: #475569; font-size: 13px;
    }
    .st-picker-opt.is-active .st-picker-opt-ic { background: #1d4ed8; color: #fff; }
    .st-picker-opt-txt { min-width: 0; flex: 1 1 auto; }
    .st-picker-opt-name {
        display: block; font-size: 13px; font-weight: 700; color: #0f172a;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .st-picker-opt-ip {
        display: block; font-size: 11.5px; color: #64748b; margin-top: 1px;
        font-family: 'Consolas', 'Menlo', monospace;
    }
    .st-picker-foot {
        padding: 8px 12px; border-top: 1px solid #eef2f7;
        background: #fbfcfe;
        font-size: 11.5px; color: #64748b;
    }
    .st-picker-empty {
        padding: 22px 12px; text-align: center;
        color: #64748b; font-size: 12.5px; line-height: 1.6;
    }

    /* ══ OPSI B · KETIK ALAMAT SENDIRI ═══════════════════ */
    .st-input-group .input-group-text {
        background: #f1f5f9; border-color: #e2e8f0;
        color: #64748b; font-size: 14px;
    }
    .st-input {
        font-size: 14px; border-color: #e2e8f0;
        border-radius: 10px; box-shadow: none;
        font-family: 'Consolas', 'Menlo', monospace; min-height: 46px;
    }
    .st-input:focus {
        border-color: #6d28d9;
        box-shadow: 0 0 0 3px rgba(109, 40, 217, .12);
    }
    .st-hint { font-size: 12.5px; color: #64748b; margin: 8px 0 0; line-height: 1.6; }
    .st-hint--warn { color: #b45309; }

    /* ══ TOMBOL UTAMA ═════════════════════════════════════ */
    .st-run-wrap { text-align: center; margin-top: 20px; }
    .st-run-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; max-width: 380px;
        padding: 17px 40px;
        border: 0; border-radius: 14px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 55%, #1e40af 100%);
        color: #fff; font-size: 15.5px; font-weight: 800; letter-spacing: .3px;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 10px 24px -12px rgba(29, 78, 216, .85);
        transition: filter .15s ease, transform .06s ease, box-shadow .15s ease;
    }
    .st-run-btn:hover {
        filter: brightness(1.04);
        box-shadow: 0 14px 30px -12px rgba(29, 78, 216, .9);
    }
    .st-run-btn:active { transform: translateY(1px); }
    .st-run-btn:disabled { opacity: .55; cursor: not-allowed; box-shadow: none; filter: none; }
    .st-run-btn .icon { font-size: 20px; }
    .st-run-note { margin: 11px 0 0; font-size: 12px; color: #64748b; text-align: center; }

    .st-status-line {
        display: flex; align-items: center; gap: 9px;
        font-size: 13px; color: #52627d;
        margin-top: 18px; padding: 11px 15px;
        border-radius: 10px; background: #f8fafc; border: 1px solid #eef2f7;
        min-height: 46px;
    }
    .st-status-line.is-ok { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
    .st-status-line.is-error { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
    .st-spinner {
        width: 15px; height: 15px; flex-shrink: 0;
        border: 2px solid #dfe5ef; border-top-color: #1d4ed8;
        border-radius: 50%;
        animation: st-spin .7s linear infinite;
    }
    @keyframes st-spin { to { transform: rotate(360deg); } }

    /* ══ PROGRES BERTAHAP ═══════════════════════════════ */
    .st-progress {
        margin-top: 12px; padding: 15px 17px;
        border: 1px solid #e2e8f2; border-radius: 12px;
        background: linear-gradient(180deg, #fbfdff, #f5f8fd);
    }
    .st-progress[hidden] { display: none; }
    .st-progress-head {
        display: flex; align-items: baseline; justify-content: space-between; gap: 10px;
        font-size: 13px; font-weight: 700; color: #334155;
    }
    .st-progress-pct { color: #1d4ed8; font-size: 15px; font-weight: 800; }
    .st-progress-track {
        height: 8px; margin-top: 10px; border-radius: 999px;
        background: #e6ecf6; overflow: hidden;
    }
    .st-progress-bar {
        height: 100%; width: 0;
        border-radius: 999px;
        background: linear-gradient(90deg, #1d4ed8, #0ea5e9);
        transition: width .45s ease;
    }
    .st-progress-steps {
        list-style: none; margin: 13px 0 0; padding: 0;
        display: flex; flex-wrap: wrap; gap: 8px 18px;
    }
    .st-step {
        display: flex; align-items: center; gap: 6px;
        font-size: 12px; color: #94a3b8; font-weight: 600;
    }
    .st-step .icon { font-size: 14px; }
    .st-step.is-active { color: #1d4ed8; }
    .st-step.is-active .icon { animation: st-pulse 1.1s ease-in-out infinite; }
    .st-step.is-done { color: #15803d; }
    @keyframes st-pulse { 50% { opacity: .35; } }
    .st-progress-foot {
        display: flex; justify-content: space-between; gap: 10px;
        margin-top: 12px; font-size: 11.5px; color: #8b97ad;
    }

    /* ══ KARTU HASIL ═════════════════════════════════════ */
    .st-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }
    .st-metric {
        background: #fff; border: 1px solid #e8ecf3;
        border-radius: 14px; padding: 20px 22px;
    }
    .st-metric-label {
        color: #6b7a99; font-size: 11px; font-weight: 800;
        text-transform: uppercase; letter-spacing: .5px;
    }
    .st-metric-value {
        color: #1c2b4a; font-size: 34px; font-weight: 800;
        margin-top: 8px; line-height: 1.05;
    }
    .st-metric-value.is-empty { color: #aab4c6; }
    .st-metric-unit { font-size: 13px; font-weight: 700; color: #6b7a99; margin-left: 4px; }
    .st-metric-note { color: #8b97ad; font-size: 12px; margin-top: 6px; }
    .st-metric-icon {
        float: right; width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }
    .st-metric--down .st-metric-icon { background: #dbeafe; color: #1d4ed8; }
    .st-metric--up   .st-metric-icon { background: #dcfce7; color: #15803d; }
    .st-metric--ping .st-metric-icon { background: #ede9fe; color: #6d28d9; }
    .st-metric.is-busy .st-metric-icon { background: #eef2f9; color: #94a3b8; }

    /* ══ TABEL RIWAYAT ═══════════════════════════════════ */
    .st-table th, .st-table td { vertical-align: middle; font-size: 12.5px; }
    .st-time { font-family: 'Consolas', 'Menlo', monospace; color: #52627d; white-space: nowrap; }
    .st-num { font-weight: 700; color: #17233d; white-space: nowrap; }
    .st-target-name-cell { font-weight: 600; color: #33415c; }
    .st-badge {
        display: inline-flex; align-items: center;
        border-radius: 999px; padding: 4px 9px;
        font-size: 10.5px; font-weight: 700;
    }
    .st-badge--ok        { background: #dcfce7; color: #15803d; }
    .st-badge--gagal     { background: #fee2e2; color: #b91c1c; }
    .st-badge--cli       { background: #ede9fe; color: #6d28d9; }
    .st-badge--curl      { background: #e0f2fe; color: #0369a1; }
    .st-badge--tidak-ada { background: #fef3c7; color: #b45309; }
    .st-row-new { background: #f0fdf4; }
    .st-empty { padding: 36px 20px; text-align: center; color: #7a879e; }

    /* ══ RESPONSIF ═══════════════════════════════════════ */
    @media (max-width: 991.98px) {
        .st-metrics { grid-template-columns: minmax(0, 1fr); }
    }
</style>
@endpush

@section('content')
    @php
        $totalDevices = $devices->count();
    @endphp

    <x-admin.page-head title="Speed Test"
        subtitle="Cek laju internet dari Server NOC atau langsung dari perangkat jaringan yang dipilih." />

    {{-- ══ PANEL 1 · TARGET AKTIF & AKSI ══════════════════ --}}
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-7">
            <div class="st-panel h-100">
                <div class="st-panel-head">
                    <span class="st-panel-no">1</span>
                    <div>
                        <h2 class="st-panel-title">Target Aktif <span class="st-panel-tag">Siap Diuji</span></h2>
                        <p class="st-panel-sub">Perangkat yang dipilih, atau alamat yang diketik sendiri.</p>
                    </div>
                </div>
                <div class="st-panel-body">

                    {{-- Ringkasan target yang akan diuji --}}
                    <div class="st-target-box" id="stTargetBox">
                        <div class="st-target-now">
                            <span class="st-target-now-ic" id="stTargetIc"><em class="icon ni ni-server"></em></span>
                            <div class="st-target-now-txt">
                                <div class="st-target-now-label">Asal target</div>
                                <div class="st-target-now-name" id="stTargetName">Server NOC</div>
                                <div class="st-target-now-ip" id="stTargetIp">Diuji langsung dari server NOC</div>
                            </div>
                            <span class="st-status-badge st-status-up" id="stTargetStatus" hidden>UP</span>
                        </div>

                        {{-- Opsi A: pilih dari daftar jaringan. Daftarnya sendiri
                             sengaja disembunyikan di balik tombol supaya 115
                             perangkat tidak memenuhi layar sejak halaman dibuka. --}}
                        <div class="st-option">
                            <div class="st-option-head">
                                <span class="st-option-key">A</span>
                                Pilih dari daftar jaringan
                            </div>

                            <div class="st-picker" id="stPicker">
                                <button type="button" class="st-picker-btn" id="stPickerBtn"
                                        aria-haspopup="listbox" aria-expanded="false"
                                        aria-controls="stPickerPanel">
                                    <span class="st-picker-btn-ic"><em class="icon ni ni-server"></em></span>
                                    <span class="st-picker-btn-txt">
                                        <span class="st-picker-btn-label" id="stPickerLabel">Server NOC (Lokal)</span>
                                        <span class="st-picker-btn-hint" id="stPickerHint">Menguji tanpa perangkat lain</span>
                                    </span>
                                    <em class="icon ni ni-chevron-down st-caret"></em>
                                </button>

                                <div class="st-picker-panel" id="stPickerPanel" role="listbox"
                                     aria-label="Daftar perangkat jaringan">
                                    <div class="st-picker-search">
                                        <em class="icon ni ni-search"></em>
                                        <input type="text" id="stPickerSearch"
                                               placeholder="Cari nama atau alamat IP"
                                               autocomplete="off" spellcheck="false"
                                               aria-label="Cari perangkat berdasarkan nama atau alamat IP">
                                    </div>

                                    <div class="st-picker-list" id="stPickerList">
                                        <button type="button" class="st-picker-opt is-active" data-local="1">
                                            <span class="st-picker-opt-ic"><em class="icon ni ni-server"></em></span>
                                            <span class="st-picker-opt-txt">
                                                <span class="st-picker-opt-name">Server NOC (Lokal)</span>
                                                <span class="st-picker-opt-ip">Diuji dari server NOC</span>
                                            </span>
                                        </button>

                                        @forelse($devices as $device)
                                            @php
                                                $status = in_array($device->status, ['down', 'warning', 'up'], true)
                                                    ? $device->status : 'up';
                                            @endphp
                                            <button type="button" class="st-picker-opt"
                                                    role="option"
                                                    data-id="{{ $device->id }}"
                                                    data-name="{{ $device->name }}"
                                                    data-ip="{{ $device->ip_address }}"
                                                    data-status="{{ $status }}"
                                                    data-delay="{{ round((float) $device->avg_delay, 1) }}"
                                                    data-loss="{{ round((float) $device->missed_percentage, 1) }}">
                                                <span class="st-picker-opt-ic"><em class="icon ni ni-server"></em></span>
                                                <span class="st-picker-opt-txt">
                                                    <span class="st-picker-opt-name">{{ $device->name }}</span>
                                                    <span class="st-picker-opt-ip">{{ $device->ip_address }}</span>
                                                </span>
                                                <span class="st-status-badge st-status-{{ $status }}">{{ strtoupper($status) }}</span>
                                            </button>
                                        @empty
                                            <div class="st-picker-empty">
                                                Belum ada perangkat terdaftar.<br>
                                                Tambahkan lewat menu «Kelola Perangkat».
                                            </div>
                                        @endforelse
                                    </div>

                                    <div class="st-picker-foot" id="stPickerFoot">
                                        {{ $totalDevices }} perangkat terdaftar
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi B: alamat yang diketik sendiri --}}
                        <div class="st-option">
                            <div class="st-option-head">
                                <span class="st-option-key st-option-key--b">B</span>
                                Atau tulis alamat target sendiri
                            </div>

                            <div class="input-group st-input-group">
                                <span class="input-group-text"><em class="icon ni ni-globe"></em></span>
                                <input type="text" class="form-control st-input" id="customHost"
                                       placeholder="Contoh: 10.0.0.1 atau router.pop-a"
                                       autocomplete="off" spellcheck="false"
                                       aria-label="IP atau nama host target manual">
                            </div>

                            @if (! $remoteReady)
                                <p class="st-hint st-hint--warn">
                                    <em class="icon ni ni-alert-circle"></em>
                                    Uji ke perangkat belum aktif di server ini (SSH_ENABLED).
                                </p>
                            @elseif (empty($allowedCidrs))
                                <p class="st-hint st-hint--warn">
                                    <em class="icon ni ni-alert-circle"></em>
                                    Target manual belum diizinkan. Tambahkan jaringan ke <b>SSH_ALLOWED_CIDRS</b> di server ini.
                                </p>
                            @else
                                <p class="st-hint">
                                    Hanya alamat dalam jaringan yang diizinkan server yang bisa diuji lewat opsi ini.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="st-run-wrap">
                        <button type="button" class="st-run-btn" id="stRunBtn">
                            <em class="icon ni ni-play-circle"></em>
                            Mulai Speed Test
                        </button>
                        <p class="st-run-note">
                            Pengujian ke perangkat berjalan dari sisi perangkatnya, jadi butuh sekitar 60 detik.
                        </p>
                    </div>

                    <div class="st-status-line" id="stStatus">
                        <em class="icon ni ni-clock"></em>
                        <span id="stStatusText">Pilih target di atas, lalu tekan tombol untuk mulai menguji.</span>
                    </div>

                    {{-- Progres bertahap: mengisi sendiri selama pengujian
                         berjalan, jadi angka di panel hasil tidak menunggu
                         semuanya selesai. --}}
                    <div class="st-progress" id="stProgress" hidden
                         role="progressbar"
                         aria-valuemin="0"
                         aria-valuemax="100"
                         aria-valuenow="0"
                         aria-label="Progres pengujian">
                        <div class="st-progress-head">
                            <span class="st-progress-stage" id="stProgressStage">Menyiapkan pengujian…</span>
                            <span class="st-progress-pct" id="stProgressPct">0%</span>
                        </div>
                        <div class="st-progress-track">
                            <div class="st-progress-bar" id="stProgressBar" style="width:0%"></div>
                        </div>
                        <ul class="st-progress-steps" id="stProgressSteps">
                            @foreach (\App\Services\SpeedTestService::STAGE_LABELS as $stageKey => $stageLabel)
                                <li class="st-step" data-step="{{ $stageKey }}">
                                    <em class="icon ni ni-check-circle"></em>
                                    <span>{{ $stageLabel }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="st-progress-foot">
                            <span id="stElapsed">0 detik</span>
                            <span id="stProgressTarget"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ PANEL 2 · KARTU HASIL ═══════════════════════ --}}
        <div class="col-12 col-lg-5">
            <div class="st-panel h-100">
                <div class="st-panel-head">
                    <span class="st-panel-no">2</span>
                    <div>
                        <h2 class="st-panel-title">Hasil Pengujian <span class="st-panel-tag">Terbaru</span></h2>
                        <p class="st-panel-sub">Laju unduh, laju unggah, dan ping.</p>
                    </div>
                </div>
                <div class="st-panel-body">
                    <div class="st-metrics" id="stMetrics" style="grid-template-columns:minmax(0,1fr);gap:12px">
                        <div class="st-metric st-metric--down" data-metric="download">
                            <span class="st-metric-icon"><em class="icon ni ni-download"></em></span>
                            <div class="st-metric-label">Kecepatan Download</div>
                            <div class="st-metric-value is-empty" data-slot>—<span class="st-metric-unit">Mbps</span></div>
                            <div class="st-metric-note" data-note>Belum diuji</div>
                        </div>
                        <div class="st-metric st-metric--up" data-metric="upload">
                            <span class="st-metric-icon"><em class="icon ni ni-upload"></em></span>
                            <div class="st-metric-label">Kecepatan Upload</div>
                            <div class="st-metric-value is-empty" data-slot>—<span class="st-metric-unit">Mbps</span></div>
                            <div class="st-metric-note" data-note>Belum diuji</div>
                        </div>
                        <div class="st-metric st-metric--ping" data-metric="ping">
                            <span class="st-metric-icon"><em class="icon ni ni-activity"></em></span>
                            <div class="st-metric-label">Respon Jaringan / Ping</div>
                            <div class="st-metric-value is-empty" data-slot>—<span class="st-metric-unit">ms</span></div>
                            <div class="st-metric-note" data-note>Belum diuji</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ PANEL 3 · RIWAYAT ══════════════════════════════ --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="st-panel">
                <div class="st-panel-head">
                    <span class="st-panel-no">3</span>
                    <div>
                        <h2 class="st-panel-title">Riwayat Pengujian</h2>
                        <p class="st-panel-sub">Maksimal {{ $historyLimit }} pengujian terakhir, khusus Super Admin.</p>
                    </div>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-light btn-dim" id="stRefreshBtn">
                            <em class="icon ni ni-reload me-1"></em>Segarkan
                        </button>
                    </div>
                </div>
                <div class="st-panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover st-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Target</th>
                                    <th>Dilakukan oleh</th>
                                    <th class="text-end">Download</th>
                                    <th class="text-end">Upload</th>
                                    <th class="text-end">Ping</th>
                                    <th>Sumber</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="stLogBody">
                                <tr><td colspan="8" class="st-empty">Belum ada riwayat pengujian.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const startUrl  = '{{ route('admin.speed-test.start') }}';
    const stepUrl   = '{{ route('admin.speed-test.step') }}';
    const probeUrl  = '{{ route('admin.speed-test.probe') }}';
    const histUrl   = '{{ route('admin.speed-test.history') }}';
    const csrf      = document.querySelector('meta[name="csrf-token"]');

    const runBtn      = document.getElementById('stRunBtn');
    const refreshBtn  = document.getElementById('stRefreshBtn');
    const statusText  = document.getElementById('stStatusText');
    const statusBox   = document.getElementById('stStatus');
    const logBody     = document.getElementById('stLogBody');
    const customInput = document.getElementById('customHost');

    const targetBox   = document.getElementById('stTargetBox');
    const targetIc    = document.getElementById('stTargetIc');
    const targetName  = document.getElementById('stTargetName');
    const targetIp    = document.getElementById('stTargetIp');
    const tStatus     = document.getElementById('stTargetStatus');

    const picker       = document.getElementById('stPicker');
    const pickerBtn    = document.getElementById('stPickerBtn');
    const pickerPanel  = document.getElementById('stPickerPanel');
    const pickerSearch = document.getElementById('stPickerSearch');
    const pickerList   = document.getElementById('stPickerList');
    const pickerFoot   = document.getElementById('stPickerFoot');
    const pickerLabel  = document.getElementById('stPickerLabel');
    const pickerHint   = document.getElementById('stPickerHint');

    // Elemen progres bertahap.
    const progressBox   = document.getElementById('stProgress');
    const progressBar   = document.getElementById('stProgressBar');
    const progressPct   = document.getElementById('stProgressPct');
    const progressStage = document.getElementById('stProgressStage');
    const progressTarget = document.getElementById('stProgressTarget');
    const elapsedText   = document.getElementById('stElapsed');
    const steps         = Array.prototype.slice.call(
        document.querySelectorAll('#stProgressSteps .st-step')
    );
    const pickerOpts   = Array.prototype.slice.call(pickerList.querySelectorAll('.st-picker-opt'));

    const COLUMNS = 8;
    const totalDevices = pickerOpts.filter(function (o) { return !o.hasAttribute('data-local'); }).length;

    let selected = { type: 'local', device_id: 0, custom_host: '', label: 'Server NOC' };
    let busy = false;

    const SLOTS = {
        download: { unit: 'Mbps', note: function (v) { return speedWord(v, 100, 25); } },
        upload:   { unit: 'Mbps', note: function (v) { return speedWord(v, 50, 12); } },
        ping:     { unit: 'ms',   note: pingWord }
    };

    /* ══ UTILITAS ═══════════════════════════════════════ */

    function speedWord(mbps, good, poor) {
        if (mbps >= good) { return 'Baik'; }
        if (mbps >= poor) { return 'Cukup'; }
        return 'Lambat';
    }

    function pingWord(ms) {
        if (ms <= 30) { return 'Baik'; }
        if (ms <= 80) { return 'Cukup'; }
        return 'Lambat';
    }

    function fmt(value, digits) {
        if (value === null || value === undefined || isNaN(parseFloat(value))) { return '—'; }
        return parseFloat(value).toFixed(digits === undefined ? 2 : digits);
    }

    function esc(value) {
        const div = document.createElement('div');
        div.textContent = (value === null || value === undefined || value === '') ? '—' : String(value);
        return div.innerHTML;
    }

    function num(value) {
        const n = parseFloat(value);
        return isNaN(n) ? null : n;
    }

    function trimNum(value) {
        return String(Math.round(value * 10) / 10);
    }

    /* ══ PANEL PEMILIH (Opsi A) ══════════════════════════ */

    function setPickerOpen(open) {
        picker.classList.toggle('is-open', open);
        pickerBtn.setAttribute('aria-expanded', open ? 'true' : 'false');

        if (open) {
            pickerSearch.value = '';
            applyPickerFilter();
            pickerSearch.focus();
        }
    }

    function applyPickerFilter() {
        const q = pickerSearch.value.trim().toLowerCase();
        let shown = 0;

        pickerOpts.forEach(function (opt) {
            if (opt.hasAttribute('data-local')) { return; }

            const name = (opt.getAttribute('data-name') || '').toLowerCase();
            const ip   = (opt.getAttribute('data-ip') || '').toLowerCase();
            const show = q === '' || name.indexOf(q) !== -1 || ip.indexOf(q) !== -1;

            opt.style.display = show ? '' : 'none';
            if (show) { shown++; }
        });

        let none = pickerList.querySelector('.st-picker-empty-none');

        if (shown === 0 && totalDevices > 0) {
            if (!none) {
                none = document.createElement('div');
                none.className = 'st-picker-empty st-picker-empty-none';
                none.textContent = 'Tidak ada perangkat yang cocok. Coba kata kunci lain.';
                pickerList.appendChild(none);
            }
            none.style.display = '';
        } else if (none) {
            none.style.display = 'none';
        }

        pickerFoot.textContent = q === ''
            ? totalDevices + ' perangkat terdaftar'
            : shown + ' dari ' + totalDevices + ' perangkat';
    }

    pickerBtn.addEventListener('click', function () {
        setPickerOpen(!picker.classList.contains('is-open'));
    });

    pickerSearch.addEventListener('input', applyPickerFilter);

    // Escape menutup panel, klik di luar menutup panel.
    document.addEventListener('click', function (e) {
        if (picker.classList.contains('is-open') && !picker.contains(e.target)) {
            setPickerOpen(false);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && picker.classList.contains('is-open')) {
            setPickerOpen(false);
            pickerBtn.focus();
        }
    });

    /* ══ TARGET AKTIF ═══════════════════════════════════ */

    function markPickerActive(opt) {
        pickerOpts.forEach(function (o) { o.classList.remove('is-active'); });
        if (opt) { opt.classList.add('is-active'); }
    }

    function chooseLocal() {
        selected = { type: 'local', device_id: 0, custom_host: '', label: 'Server NOC' };

        targetBox.classList.remove('is-manual');
        targetName.textContent = 'Server NOC';
        targetIp.textContent   = 'Diuji langsung dari server NOC';
        tStatus.hidden = true;

        pickerLabel.textContent = 'Server NOC (Lokal)';
        pickerHint.textContent  = 'Menguji tanpa perangkat lain';
        markPickerActive(pickerOpts[0]);

        if (customInput) { customInput.value = ''; }
    }

    function chooseDevice(opt) {
        const name   = opt.getAttribute('data-name') || '';
        const ip     = opt.getAttribute('data-ip') || '';
        const status = opt.getAttribute('data-status') || 'up';
        const delay  = num(opt.getAttribute('data-delay'));
        const loss   = num(opt.getAttribute('data-loss'));

        selected = { type: 'device', device_id: opt.getAttribute('data-id') || '0', custom_host: '', label: name };

        targetBox.classList.remove('is-manual');
        targetName.textContent = name;
        targetIp.textContent   = ip;
        tStatus.hidden    = false;
        tStatus.className = 'st-status-badge st-status-' + status;
        tStatus.textContent = status.toUpperCase();

        pickerLabel.textContent = name;
        pickerHint.textContent  = ip
            + (delay === null || delay <= 0 ? '' : ' · respons ' + trimNum(delay) + ' ms')
            + (loss === null ? '' : ' · hilang ' + trimNum(loss) + ' %');
        markPickerActive(opt);

        if (customInput) { customInput.value = ''; }
    }

    pickerList.addEventListener('click', function (e) {
        const opt = e.target.closest('.st-picker-opt');
        if (!opt) { return; }

        if (opt.hasAttribute('data-local')) {
            chooseLocal();
        } else {
            chooseDevice(opt);
        }

        setPickerOpen(false);
    });

    /* Ketik manual = Super Admin memilih target sendiri, jadi pilihan dari
       daftar dilepas supaya hasilnya tidak dikira milik perangkat itu. */
    customInput.addEventListener('input', function () {
        const v = customInput.value.trim();

        if (!v) {
            chooseLocal();
            return;
        }

        selected = { type: 'manual', device_id: 0, custom_host: v, label: v };

        targetBox.classList.add('is-manual');
        targetName.textContent = 'Alamat manual';
        targetIp.textContent   = v;
        tStatus.hidden = true;

        pickerLabel.textContent = 'Alamat manual';
        pickerHint.textContent  = v;
        markPickerActive(null);
    });

    /* ══ KARTU HASIL ═════════════════════════════════════ */

    function setSlot(metric, value, note) {
        const card = document.querySelector('[data-metric="' + metric + '"]');
        if (!card) { return; }

        const slot = card.querySelector('[data-slot]');
        const text = (value === null || value === undefined) ? '—' : fmt(value);

        slot.textContent = text;
        slot.classList.toggle('is-empty', text === '—');

        const unit = document.createElement('span');
        unit.className = 'st-metric-unit';
        unit.textContent = ' ' + SLOTS[metric].unit;
        slot.appendChild(unit);

        card.querySelector('[data-note]').textContent = note
            || (value === null || value === undefined ? 'Tidak terbaca' : SLOTS[metric].note(parseFloat(value)));
    }

    function resetSlots() {
        Object.keys(SLOTS).forEach(function (k) { setSlot(k, null, 'Belum diuji'); });
    }

    function setBusy(on) {
        busy = on;
        runBtn.disabled = on;
        refreshBtn.disabled = on;
        runBtn.innerHTML = on
            ? '<span class="spinner-border spinner-border-sm me-2" style="width:16px;height:16px"></span> Menguji…'
            : '<em class="icon ni ni-play-circle"></em> Mulai Speed Test';

        document.querySelectorAll('[data-metric]').forEach(function (card) {
            card.classList.toggle('is-busy', on);
        });

        const spin = statusBox.querySelector('.st-spinner');
        if (on && !spin) {
            const el = document.createElement('span');
            el.className = 'st-spinner';
            statusBox.prepend(el);
        } else if (!on && spin) {
            spin.remove();
        }
    }

    /* ══ RIWAYAT ═════════════════════════════════════════ */

    function logRow(log, isNew) {
        const tr = document.createElement('tr');
        if (isNew) { tr.className = 'st-row-new'; }

        tr.innerHTML =
            '<td><span class="st-time">' + esc(log.tested_at) + '</span></td>' +
            '<td><span class="st-target-name-cell">' + esc(log.target_label) + '</span></td>' +
            '<td>' + esc(log.user_name) + '</td>' +
            '<td class="text-end"><span class="st-num">' + fmt(log.download_mbps) + ' Mbps</span></td>' +
            '<td class="text-end"><span class="st-num">' + fmt(log.upload_mbps) + ' Mbps</span></td>' +
            '<td class="text-end"><span class="st-num">' + fmt(log.ping_ms) + ' ms</span></td>' +
            '<td><span class="st-badge st-badge--' + esc(log.source) + '">' + esc(log.source_label) + '</span></td>' +
            '<td><span class="st-badge st-badge--' + esc(log.status) + '">' + esc(log.status_label) + '</span></td>';

        return tr;
    }

    function renderHistory(logs, newestId) {
        if (!logBody) { return; }
        logBody.innerHTML = '';

        if (!logs.length) {
            logBody.innerHTML = '<tr><td colspan="' + COLUMNS + '" class="st-empty">Belum ada riwayat pengujian.</td></tr>';
            return;
        }

        logs.forEach(function (log) {
            logBody.appendChild(logRow(log, String(log.id) === String(newestId)));
        });
    }

    function loadHistory(newestId) {
        if (!logBody) { return Promise.resolve(); }

        return fetch(histUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) { renderHistory(data.logs || [], newestId || null); })
        .catch(function () { /* tabel dibiarkan apa adanya */ });
    }

    /* ══ MENJALANKAN PENGUJIAN ═══════════════════════════
     *
     * Alurnya dua tahap, bukan satu request panjang:
     *
     *   start  → backend membuat sesi dan mengembalikan daftar tahap
     *   step   → dipanggil berulang; setiap panggilan mengerjakan SATU
     *            tahap, jadi angka Download/Upload/Ping muncul satu per
     *            satu dan progres bar bergerak tiap detik, bukan melompat
     *            dari 0 ke 100 setelah 30 detik diam.
     *
     * Setiap tahap memang butuh beberapa detik (perintah di server), jadi
     * polling 1 detik dipakai untuk: menampilkan tahap mana yang sedang
     * berjalan, mengisi angka yang sudah keluar, dan menggerakkan timer.
     * Timeout-nya longgar supaya tahap yang lambat tidak dipotong.
     */

    const STEP_INTERVAL_MS = 1000;
    const STEP_TIMEOUT_MS = 150000;

    /* Cuplikan live. Satu sampel nyata per detik, dikirim BERBEDA dari polling
       'step' supaya dua hal ini tidak saling menunggu: 'step' satu panggilan
       bisa makan puluhan detik, sementara cuplikan harus tetap datang tiap
       detik supaya angka di kartu bergerak. */
    const PROBE_INTERVAL_MS = 1000;
    const PROBE_TIMEOUT_MS = 20000;

    // Posteran di luar supaya tetap true walau tab ditutup tengah jalan.
    let pollTimer = null;
    let tickTimer = null;
    let probeTimer = null;
    let running = false;

    function setStatus(text, kind) {
        statusText.textContent = text;
        statusBox.classList.toggle('is-error', kind === 'error');
        statusBox.classList.toggle('is-ok', kind === 'ok');
    }

    /**
     * Satu POST JSON. Mengembalikan { status, data } supaya pemanggil bisa
     * membedakan HTTP 422 (gagal di level bisnis, body tetap berisi pesan)
     * dari kegagalan jaringan yang tidak punya respons sama sekali.
     */
    function postJson(url, body, timeoutMs) {
        const controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
        let timer = null;

        if (controller && timeoutMs) {
            timer = window.setTimeout(function () { controller.abort(); }, timeoutMs);
        }

        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf ? csrf.content : '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            signal: controller ? controller.signal : undefined,
            body: JSON.stringify(body)
        })
        .then(function (r) {
            return r.json()
                .catch(function () { return { message: 'Respons server tidak terbaca.' }; })
                .then(function (data) { return { ok: r.ok, status: r.status, data: data }; });
        })
        .finally(function () {
            if (timer) { window.clearTimeout(timer); }
        });
    }

    function paintProgress(d) {
        const pct = Math.max(0, Math.min(100, parseInt(d.progress, 10) || 0));

        progressBar.style.width = pct + '%';
        progressPct.textContent = pct + '%';
        progressBox.setAttribute('aria-valuenow', String(pct));

        if (d.elapsed_ms !== undefined && d.elapsed_ms !== null) {
            elapsedText.textContent = Math.round(d.elapsed_ms / 1000) + ' detik';
        }

        if (d.target_label) {
            progressTarget.textContent = 'Target: ' + d.target_label;
        }

        // Tahap yang baru selesai ditandai centang hijau; tahap berikutnya
        // berdenyut biru supaya jelas apa yang sedang dikerjakan.
        steps.forEach(function (step) {
            const key = step.getAttribute('data-step');
            step.classList.remove('is-active', 'is-done');

            if (d.completed_stage && key === d.completed_stage) {
                step.classList.add('is-done');
            } else if (d.next_stage && key === d.next_stage) {
                step.classList.add('is-active');
                progressStage.textContent = d.stage_label || 'Mengukur…';
            } else if (d.done) {
                step.classList.add('is-done');
            }
        });

        if (d.done) {
            progressStage.textContent = 'Pengujian selesai';
        }
    }

    /**
     * Isi kartu metrik dari hasil resmi ('step').
     *
     * PENTING: kartu hanya ditulis kalau tahapnya memang sudah selesai —
     * angkanya ada, atau backend sudah menyatakan tahap itu dicoba tapi gagal
     * ('status' = 'gagal'). Metrik yang masih 'pending' DILEWATI sepenuhnya:
     *
     *  - Menuliskannya sebagai "Gagal terbaca" salah, karena tahapnya belum
     *    dijalankan sama sekali.
     *  - Yang lebih penting, setiap polling akan menimpa cuplikan langsung
     *    yang sedang tampil dengan tanda hubung, sehingga angka di layar
     *    berkedip antara nilai dan "—" sepanjang tahap berjalan.
     */
    function paintMetrics(metrics) {
        if (!metrics) { return; }

        paintSlot('download', metrics);
        paintSlot('upload', metrics);
        paintSlot('ping', metrics);
    }

    const METRIC_KEYS = {
        download: 'download_mbps',
        upload: 'upload_mbps',
        ping: 'ping_ms'
    };

    function paintSlot(metric, metrics) {
        const key = METRIC_KEYS[metric];
        const value = metrics[key];
        const status = (metrics.status || {})[key];

        if (value === undefined) { return; }

        if (value !== null) {
            setSlot(metric, value);
            return;
        }

        // null: hanya bermakna "Gagal terbaca" kalau backend sudah selesai
        // mencoba tahap ini. Selama masih pending, biarkan kartu apa adanya
        // supaya cuplikan langsung tidak tertimpa.
        if (status === 'gagal') {
            setSlot(metric, null, 'Gagal terbaca');
        }
    }

    /**
     * Catat metrik yang SUDAH final, supaya cuplikan live tidak menimpa hasil
     * pengukuran resmi.
     *
     * 'step' yang mengisi inilah angka yang masuk ke riwayat. Cuplikan hanya
     * boleh mengisi slot yang belum terkunci. Slot yang dinyatakan 'gagal'
     * ikut dikunci: angkanya memang tidak ada, tapi sudah pasti tidak akan
     * datang, jadi cuplikan tidak boleh lagi mengisinya.
     */
    const lockedSlots = { download: false, upload: false, ping: false };

    function paintLiveMetrics(metrics) {
        if (!metrics) { return; }

        Object.keys(METRIC_KEYS).forEach(function (metric) {
            // Sudah ada angka resmi untuk metrik ini: jangan diubah.
            if (lockedSlots[metric]) { return; }

            const value = metrics[METRIC_KEYS[metric]];

            // null berarti sampelnya gagal diambil. Angka sebelumnya (kalau ada)
            // sengaja ditahan — sengaja diisi 0 atau placeholder, karena itu
            // akan terlihat seperti hasil ukur yang sah.
            if (value === null || value === undefined) { return; }

            setSlot(metric, value, 'Cuplikan langsung · tiap 1 detik');
        });
    }

    function lockSlots(metrics) {
        if (!metrics) { return; }

        const status = metrics.status || {};

        Object.keys(METRIC_KEYS).forEach(function (metric) {
            const key = METRIC_KEYS[metric];
            const value = metrics[key];

            if (value !== null && value !== undefined) {
                lockedSlots[metric] = true;
            } else if (status[key] === 'gagal') {
                // Sudah dipastikan gagal: kunci juga supaya cuplikan berhenti
                // menulis ke slot ini.
                lockedSlots[metric] = true;
            }
        });
    }

    function resetLocks() {
        lockedSlots.download = false;
        lockedSlots.upload = false;
        lockedSlots.ping = false;
    }

    /**
     * Ucapan yang menjelaskan kegagalan sebenar-benarnya. Umumnya 422 dengan
     * pesan dari backend (SSH belum aktif, CIDR tidak diizinkan, target
     * tidak ada); sisanya pesan umum per kode HTTP.
     */
    function failureMessage(status, data) {
        if (data && data.message) { return data.message; }

        if (data && data.errors && typeof data.errors === 'object') {
            const first = Object.keys(data.errors).map(function (k) { return data.errors[k]; })[0];
            if (first && first.length) { return first[0]; }
        }

        if (status === 401 || status === 419) {
            return 'Sesi Anda sudah berakhir. Muat ulang halaman lalu coba lagi.';
        }
        if (status === 403) {
            return 'Hanya Super Admin yang bisa memakai pengujian ini.';
        }
        if (status === 429) {
            return 'Terlalu banyak permintaan. Tunggu sebentar sebelum menguji lagi.';
        }
        if (status === 0) {
            return 'Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi.';
        }

        return 'Pengujian gagal diproses server. Silakan coba lagi.';
    }

    function stopTimers() {
        if (pollTimer) { window.clearTimeout(pollTimer); pollTimer = null; }
        if (tickTimer) { window.clearInterval(tickTimer); tickTimer = null; }
        if (probeTimer) { window.clearTimeout(probeTimer); probeTimer = null; }
    }

    /**
     * Pemadog dapat membekukan timer background saat tab tidak aktif, jadi
     * angka detik dihitung dari waktu mulai yang benar-benar dicatat backend.
     */
    function startTicker() {
        let base = performance.now();

        stopTimers();
        tickTimer = window.setInterval(function () {
            const seconds = Math.round((performance.now() - base) / 1000);

            if (seconds > 0) {
                elapsedText.textContent = seconds + ' detik';
            }
        }, 1000);

        return function () { base = performance.now(); };
    }

    runBtn.addEventListener('click', function () {
        if (running) { return; }

        const target = selected;

        // Backend menolak alamat kosong untuk target manual, jadi jangan
        // diam-diam jatuh ke server lokal — beri tahu di layar saja.
        if (target.type === 'manual' && !target.custom_host) {
            setStatus('Isi dulu kolom IP atau nama host pada Opsi B.', 'error');
            customInput.focus();
            return;
        }

        resetSlots();
        resetLocks();
        setBusy(true);
        progressBox.hidden = false;
        progressStage.textContent = 'Menyiapkan pengujian…';
        paintProgress({ progress: 0 });
        progressTarget.textContent = 'Target: ' + target.label;

        running = true;
        const isRemote = target.type !== 'local';

        setStatus(isRemote
            ? 'Menguji dari ' + target.label + '. Angka akan muncul bertahap.'
            : 'Menguji laju unduh, unggah, dan ping dari server ini. Angka akan muncul bertahap.');

        postJson(startUrl, {
            target_type: target.type,
            device_id: target.device_id,
            custom_host: target.custom_host
        }, STEP_TIMEOUT_MS)
        .then(function (res) {
            if (!res.ok) {
                return Promise.reject({ status: res.status, data: res.data });
            }

            const stepsPlan = (res.data && res.data.stages) || [];
            stepsPlan.forEach(function (s) {
                const el = document.querySelector('[data-step="' + s.key + '"]');
                if (el && s.position === 1) { el.classList.add('is-active'); }
            });

            startTicker();

            // Cuplikan live jalan berdampingan dengan poll(): ini yang bikin
            // angka Download/Upload/Ping berubah tiap detik selama tahapnya
            // masih berjalan, alih-alih diam sampai tahap selesai.
            probeLoop(res.data.run_id);

            return poll(res.data.run_id);
        })
        .then(function (d) {
            if (!d) { return; }

            paintProgress(d);

            // Respons akhir sudah membawa 'metrics' lengkap beserta statusnya.
            // Bentuk lama (tanpa 'metrics') hanya dipakai sebagai jaring
            // pengaman, dan di situ tiap tahap dianggap sudah dicoba — karena
            // pengujiannya memang sudah selesai.
            const finalMetrics = d.metrics || {
                download_mbps: d.download_mbps,
                upload_mbps: d.upload_mbps,
                ping_ms: d.ping_ms,
                status: {
                    download_mbps: 'gagal',
                    upload_mbps: 'gagal',
                    ping_ms: 'gagal'
                }
            };

            // lockSlots DILETAKKAN SEBELUM paintMetrics: begitu tahap selesai,
            // angka resmi harus langsung menggantikan cuplikan live di kartu
            // yang sama — bukan menunggu tahap berikutnya menggantikannya.
            lockSlots(finalMetrics);
            paintMetrics(finalMetrics);

            const stamp = d.timestamp ? ' pada ' + d.timestamp : '';

            setStatus((d.message || 'Pengujian selesai.') + stamp
                + (d.target_label ? ' Target: ' + d.target_label + '.' : '')
                + (d.server ? ' Server uji: ' + d.server + '.' : '')
                + (d.duration_ms ? ' Durasi ' + Math.round(d.duration_ms / 1000) + ' detik.' : ''),
                d.status === 'ok' ? 'ok' : 'error');

            if (d.log) { loadHistory(d.log.id); }
        })
        .catch(function (err) {
            const status = (err && err.status) || 0;
            const data = (err && err.data) || null;

            stopTimers();
            progressBox.hidden = true;
            setStatus(failureMessage(status, data), 'error');
        })
        .finally(function () {
            running = false;
            setBusy(false);
            stopTimers();
        });
    });

    /**
     * Rantai panggilan 'step': tiap panggilan mengerjakan satu tahap, lalu
     * dijadwalkan ulang satu detik kemudian sampai backend menyatakan selesai.
     *
     * Sengaja memakai setTimeout berurutan (bukan setInterval) supaya tidak
     * menumpuk request ketika satu tahap ternyata berjalan lama.
     */
    function poll(runId) {
        if (!running) { return Promise.resolve(null); }

        return postJson(stepUrl, { run_id: runId }, STEP_TIMEOUT_MS)
            .then(function (res) {
                if (!res.ok) {
                    return Promise.reject({ status: res.status, data: res.data });
                }

                const d = res.data || {};

                if (!d.done) {
                    paintProgress(d);
                    paintMetrics(d.metrics);
                    lockSlots(d.metrics);

                    if (d.stage_label) {
                        progressStage.textContent = d.stage_label;
                    }
                }

                pollTimer = window.setTimeout(function () { poll(runId); }, STEP_INTERVAL_MS);

                return d.done ? d : null;
            });
    }

    /**
     * Rantai cuplikan live: satu sampel nyata per detik untuk tahap yang
     * sedang berjalan.
     *
     * Sengaja terpisah dari poll() dengan dua alasan:
     *
     *  1. poll() satu panggilan bisa berjalan puluhan detik (tahap unduh),
     *     jadi cuplikan tidak akan pernah datang tepat waktu kalau dibuat
     *     berurutan dengannya.
     *  2. Kegagalan cuplikan tidak boleh menghentikan pengujian. Jadi
     *     responsnya hanya dipakai untuk mengisi kartu; kalau gagal, angka
     *     sebelumnya ditahan dan rantai dilanjutkan saja.
     */
    function probeLoop(runId) {
        if (!running) { return Promise.resolve(); }

        return postJson(probeUrl, { run_id: runId }, PROBE_TIMEOUT_MS)
            .then(function (res) {
                // 422 dengan done = true artinya sesi sudah ditutup 'step',
                // jadi tidak ada lagi yang perlu dicuplik.
                if (!res.ok && res.data && res.data.done) {
                    return;
                }

                if (res.ok && res.data && res.data.live) {
                    paintLiveMetrics(res.data.metrics);
                }

                probeTimer = window.setTimeout(function () { probeLoop(runId); }, PROBE_INTERVAL_MS);
            })
            .catch(function () {
                // Jaringan putus sesaat atau server sedang sibuk: coba lagi
                // pada detik berikutnya. Cuplikan bersifat pelengkap, jadi
                // kegagalan di sini tidak boleh menggagalkan pengujian.
                probeTimer = window.setTimeout(function () { probeLoop(runId); }, PROBE_INTERVAL_MS);
            });
    }

    refreshBtn.addEventListener('click', function () { loadHistory(); });

    /* ══ MULAI ══════════════════════════════════════════ */

    // Default: Server NOC, jadi halaman sudah bisa langsung diuji tanpa
    // pengguna mencari perangkat lebih dulu.
    chooseLocal();

    loadHistory();
})();
</script>
@endpush
