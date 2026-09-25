@extends('layouts.admin')

@section('title', 'Laporan Monitoring · RADNEXT NOC')

@php
    use Illuminate\Support\Arr;

    // Filter query saat ini (untuk PDF & pagination) tanpa param kosong.
    $currentQuery = collect(request()->query())
        ->filter(fn ($v) => $v !== null && $v !== '')
        ->toArray();

    $pdfUrl  = route('admin.laporan.pdf', $currentQuery);

    $detailPageQuery = Arr::except($currentQuery, ['page']);
    $pageUrl = fn ($page) => route('admin.laporan', array_merge($detailPageQuery, ['page' => $page]));

    // ── Validasi nilai agar tidak menampilkan angka tidak masuk akal ──
    $uptimeVal  = ($kpi['uptime']   ?? null) !== null  && $kpi['uptime']   >= 0 && $kpi['uptime']   <= 100 ? (float) $kpi['uptime'] : null;
    $avgRespVal = ($kpi['avg_response'] ?? null) !== null && $kpi['avg_response'] > 0 ? (float) $kpi['avg_response'] : null;
    $maxRespVal = ($kpi['max_response'] ?? null) !== null && $kpi['max_response'] > 0 ? (float) $kpi['max_response'] : null;
    $minRespVal = ($kpi['min_response'] ?? null) !== null && $kpi['min_response'] > 0 ? (float) $kpi['min_response'] : null;

    // Nama perangkat terdampak (digabung ke card Total DOWN).
    $affectedNames = $topDevices->pluck('device_name')
        ->map(fn ($n) => $n ?: 'NaN')
        ->take(8)
        ->implode(', ');
    $affectedNames = $affectedNames === '' ? null : $affectedNames;

    // ── Chart: @json($chartData) + fallback dummy saat kosong ──
    $dummyAvail = [
        'labels' => ['00:00', '06:00', '12:00', '18:00', '23:59'],
        'availability' => [99.87, 99.92, 100, 99.98, 99.90],
    ];
    $dummyResp = [
        'labels' => ['00:00', '06:00', '12:00', '18:00', '23:59'],
        'avg' => [42, 45, 41, 44, 43],
        'min' => [12, 11, 13, 12, 11],
        'max' => [175, 180, 170, 178, 181],
    ];
    $chartData = [
        'availability' => (array) ($availability ?? []),
        'response'     => (array) ($response ?? []),
        'dummy'        => ['availability' => $dummyAvail, 'response' => $dummyResp],
        'modal'        => (array) ($modalData ?? []),
    ];
@endphp

@push('styles')
<style>
    body, .nk-main { background: #f4f6fb; }

    /* ── Kartu dasar ─────────────────────────────────────────── */
    .lp-card {
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(28, 43, 74, .04);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .lp-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px -12px rgba(79, 70, 229, .35);
        border-color: #cdd6ef;
    }

    /* ── Header halaman ──────────────────────────────────────── */
    .lp-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .lp-title-group { display: flex; align-items: center; gap: 14px; }
    .lp-title-icon {
        width: 46px; height: 46px; border-radius: 14px;
        background: linear-gradient(135deg, #4F46E5, #7c3aed);
        color: #fff; font-size: 1.2rem;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 20px rgba(79, 70, 229, .28);
        flex-shrink: 0;
    }
    .lp-title h1 { font-size: 1.25rem; font-weight: 800; color: #1c2b4a; margin: 0; }
    .lp-title p { margin: 2px 0 0; font-size: .8rem; color: #6b7a99; }

    .btn-export {
        display: inline-flex; align-items: center; gap: 6px;
        background: #1c2b4a; color: #fff;
        padding: 9px 18px; border: none; border-radius: 10px;
        font-size: .8rem; font-weight: 700; text-decoration: none;
        transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
    }
    .btn-export:hover { background: #2a3d66; color: #fff; transform: translateY(-1px); box-shadow: 0 8px 18px -8px #1c2b4a; }

    /* ── Filter bar tunggal ──────────────────────────────────── */
    .lp-filter { padding: 16px; }
    .filter-row { display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap; }
    .f-field { display: flex; flex-direction: column; gap: 5px; }
    .f-field label { font-size: .72rem; font-weight: 700; color: #6b7a99; text-transform: uppercase; letter-spacing: .04em; }
    .f-field .form-control, .f-field .form-select {
        border: 1px solid #e8ecf3; border-radius: 10px; font-size: .84rem; padding: 8px 12px;
        min-width: 170px; color: #1c2b4a; background: #fff;
    }
    .f-field .form-control:focus, .f-field .form-select:focus {
        border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
    }
    .btn-apply {
        display: inline-flex; align-items: center; gap: 6px;
        background: #4F46E5; color: #fff; border: none; border-radius: 10px;
        padding: 9px 18px; font-size: .8rem; font-weight: 700;
        transition: background .2s ease, box-shadow .2s ease;
    }
    .btn-apply:hover { background: #4338ca; color: #fff; box-shadow: 0 8px 18px -8px rgba(79, 70, 229, .6); }

    /* ── Stat cards ──────────────────────────────────────────── */
    .stat-card { padding: 18px; height: 100%; display: flex; flex-direction: column; gap: 12px; }
    .stat-top { display: flex; align-items: center; gap: 12px; }
    .icon-box {
        width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.15rem;
    }
    .icon-green  { background: rgba(25, 135, 84, .12);  color: #198754; }
    .icon-red    { background: rgba(232, 83, 79, .12);  color: #e8534f; }
    .icon-violet { background: rgba(79, 70, 229, .12);  color: #4F46E5; }
    .icon-amber  { background: rgba(241, 179, 50, .16); color: #d99a1c; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: #1c2b4a; line-height: 1.1; }
    .stat-unit { font-size: .85rem; font-weight: 700; color: #6b7a99; }
    .stat-label { font-size: .74rem; font-weight: 700; color: #6b7a99; text-transform: uppercase; letter-spacing: .04em; }
    .stat-foot {
        margin-top: auto; padding-top: 10px; border-top: 1px dashed #e8ecf3;
        display: flex; align-items: center; justify-content: space-between; gap: 6px; flex-wrap: wrap;
        font-size: .72rem; color: #6b7a99;
    }
    .stat-foot b { color: #1c2b4a; }

    /* Tooltip CSS-only (Device Bermasalah → card Total DOWN) */
    .tip-wrap { position: relative; display: inline-flex; }
    .tip-wrap .tip {
        position: absolute; bottom: calc(100% + 10px); left: 50%; z-index: 60;
        transform: translateX(-50%) translateY(4px);
        background: #1c2b4a; color: #fff;
        font-size: .7rem; line-height: 1.45; padding: 8px 12px; border-radius: 10px;
        width: max-content; max-width: 280px; text-align: left;
        opacity: 0; visibility: hidden; transition: .2s ease; pointer-events: none;
    }
    .tip-wrap .tip::after {
        content: ""; position: absolute; top: 100%; left: 50%;
        transform: translateX(-50%); border: 6px solid transparent; border-top-color: #1c2b4a;
    }
    .tip-wrap:hover .tip { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
    .tip-trigger { cursor: help; border-bottom: 1px dashed #b6bfd8; }

    /* ── Panel ───────────────────────────────────────────────── */
    .panel { padding: 18px; height: 100%; display: flex; flex-direction: column; }
    .panel-title { font-size: .86rem; font-weight: 800; color: #1c2b4a; display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 14px; }
    .panel-title .hint { font-size: .72rem; font-weight: 700; color: #9aa6bd; }

    .chart-container { position: relative; height: 280px; flex: 1; }

    /* Penyebab (otomatis + manual) */
    .cause-main { min-width: 150px; display: flex; flex-direction: column; gap: 4px; align-items: flex-start; }
    .cause-badge {
        display: inline-flex; padding: 3px 9px; border-radius: 99px;
        font-size: .66rem; font-weight: 800; white-space: nowrap;
    }
    .cause-badge-danger { background: rgba(232, 83, 79, .13); color: #c73b37; }
    .cause-badge-warn   { background: rgba(241, 179, 50, .18); color: #b07d10; }
    .cause-badge-info   { background: rgba(79, 70, 229, .12);  color: #4F46E5; }
    .cause-manual {
        background: rgba(79, 70, 229, .08); color: #4338ca; border: 1px dashed rgba(79, 70, 229, .35);
        border-radius: 9px; padding: 5px 10px; font-size: .74rem; font-weight: 600;
        line-height: 1.45; white-space: normal; text-align: left; max-width: 280px;
    }
    .cause-sub { font-size: .68rem; color: #6b7a99; margin-top: 4px; line-height: 1.4; }

    /* Tabel */
    .table-monitoring { width: 100%; border-collapse: collapse; }
    .table-monitoring thead th {
        font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
        color: #9aa6bd; padding: 10px 12px; border-bottom: 1px solid #eef1f6; white-space: nowrap;
    }
    .table-monitoring tbody td {
        font-size: .8rem; color: #44506b; padding: 11px 12px; border-bottom: 1px solid #f3f5fa;
        vertical-align: middle;
    }
    .table-monitoring tbody tr:last-child td { border-bottom: none; }
    .table-monitoring tbody tr { transition: background .15s ease; }
    .table-monitoring tbody tr:hover { background: #f7f8ff; }

    .rank-badge {
        width: 26px; height: 26px; border-radius: 9px; display: inline-flex;
        align-items: center; justify-content: center;
        font-size: .74rem; font-weight: 800; color: #fff;
    }
    .rank-1 { background: #e8534f; }
    .rank-2 { background: #f1b332; color: #5c4300; }
    .rank-3 { background: #198754; }
    .rank-4 { background: #8b5cf6; }

    .cause-main { min-width: 150px; }
    .cause-badge {
        display: inline-flex; padding: 3px 9px; border-radius: 99px;
        font-size: .66rem; font-weight: 800; white-space: nowrap;
    }
    .cause-badge-danger { background: rgba(232, 83, 79, .13); color: #c73b37; }
    .cause-badge-warn   { background: rgba(241, 179, 50, .18); color: #b07d10; }
    .cause-badge-info   { background: rgba(79, 70, 229, .12);  color: #4F46E5; }
    .cause-sub { font-size: .68rem; color: #6b7a99; margin-top: 4px; line-height: 1.4; }

    .progress-thin { height: 7px; background: #eef1f6; border-radius: 99px; overflow: hidden; width: 100%; }
    .progress-thin .progress-bar { height: 100%; background: #198754; border-radius: 99px; }
    .progress-thin .progress-bar.bar-danger { background: #e8534f; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px; border-radius: 99px; font-size: .7rem; font-weight: 800;
    }
    .status-down      { background: rgba(232, 83, 79, .13); color: #c73b37; }
    .status-unreach   { background: rgba(241, 179, 50, .18); color: #b07d10; }
    .status-recovered { background: rgba(25, 135, 84, .13); color: #106b41; }
    .status-ongoing   { background: rgba(79, 70, 229, .12); color: #4F46E5; }

    /* Detail incident header + filter */
    .detail-header { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; }
    .detail-filter { min-width: 220px; }
    .detail-filter .form-select {
        border: 1px solid #e8ecf3; border-radius: 10px; font-size: .8rem;
        padding: 7px 12px; color: #1c2b4a; background: #fff;
    }
    .detail-filter .form-select:focus { border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79, 70, 229, .12); }

    /* Pagination */
    .pagination-laporan { display: flex; align-items: center; gap: 5px; }
    .page-btn {
        min-width: 30px; height: 30px; border-radius: 9px; display: inline-flex;
        align-items: center; justify-content: center;
        font-size: .76rem; font-weight: 700; color: #44506b;
        border: 1px solid #e8ecf3; background: #fff; text-decoration: none; transition: .15s ease;
    }
    .page-btn:hover { background: #eef2ff; color: #4F46E5; }
    .page-btn.active { background: #4F46E5; border-color: #4F46E5; color: #fff; }
    .page-btn.disabled { opacity: .4; pointer-events: none; }

    /* Empty state */
    .empty-state { padding: 36px; text-align: center; color: #6b7a99; }
    .empty-state .empty-icon {
        width: 52px; height: 52px; border-radius: 16px; margin: 0 auto 12px;
        background: #f0f1ff; color: #4F46E5; font-size: 1.3rem;
        display: flex; align-items: center; justify-content: center;
    }

    /* Loading overlay */
    .laporan-loading {
        position: fixed; inset: 0; z-index: 3000;
        background: rgba(255, 255, 255, .6); backdrop-filter: blur(2px);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: .2s ease;
    }
    .laporan-loading.show { opacity: 1; visibility: visible; }
    .spinner-box {
        background: #fff; border: 1px solid #e8ecf3; border-radius: 14px;
        padding: 18px 22px; box-shadow: 0 14px 30px -12px rgba(79, 70, 229, .3);
        display: flex; align-items: center; gap: 10px; font-size: .8rem; font-weight: 700; color: #4F46E5;
    }

    /* ── Stat card interaktif ────────────────────────────────── */
    .stat-card { cursor: pointer; position: relative; user-select: none; }
    .stat-card::before {
        content: ""; position: absolute; inset: 0; border-radius: 14px;
        box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
        transition: box-shadow .25s ease;
        pointer-events: none;
    }
    .stat-card:hover::before { box-shadow: 0 0 0 3px rgba(79, 70, 229, .18); }

    .click-hint {
        position: absolute; left: 50%; bottom: 9px; transform: translateX(-50%);
        font-size: .64rem; font-weight: 700; color: #8b5cf6; white-space: nowrap;
        background: rgba(79, 70, 229, .09); padding: 3px 10px; border-radius: 99px;
        opacity: 0; transition: opacity .2s ease; pointer-events: none;
    }
    .stat-card:hover .click-hint { opacity: 1; }

    /* ── Modal detail stat card ──────────────────────────────── */
    .modal-custom .modal-content { border: none; border-radius: 16px; overflow: hidden; }
    .modal-custom .modal-dialog { max-width: 760px; }
    .modal-head { color: #fff; display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; }
    .modal-head .modal-title { color: inherit; font-weight: 800; font-size: 1rem; }
    .modal-head-icon {
        width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
        background: rgba(255, 255, 255, .2);
        display: flex; align-items: center; justify-content: center; font-size: 1.05rem;
    }
    .modal-head .btn-close { filter: invert(1) grayscale(1); }
    .head-green  { background: linear-gradient(135deg, #198754, #0f6b42); }
    .head-red    { background: linear-gradient(135deg, #e8534f, #c73b37); }
    .head-amber  { background: linear-gradient(135deg, #f1b332, #d99a1c); color: #5c4300; }
    .head-violet { background: linear-gradient(135deg, #4F46E5, #7c3aed); }
    .head-amber .btn-close { filter: none; }
    .head-amber .modal-head-icon { color: #5c4300; }

    .modal-scroll { max-height: 62vh; overflow: auto; }
    .modal-scroll .table-monitoring thead th { background: #f8f9fd; }
    .modal-footer { border-top: 1px solid #eef1f6; padding: 12px 20px; }
    .modal-footer .btn-export { padding: 8px 16px; font-size: .76rem; }

    /* Animasi fade + slide-up halus */
    .modal-custom.modal .modal-dialog {
        transform: translateY(26px) scale(.98); opacity: 0;
        transition: transform .28s cubic-bezier(.2, .8, .25, 1), opacity .28s ease;
    }
    .modal-custom.modal.show .modal-dialog { transform: translateY(0) scale(1); opacity: 1; }

    /* Responsive: modal full-width di mobile */
    @media (max-width: 575.98px) {
        .modal-custom .modal-dialog { margin: 8px; max-width: none; }
        .modal-custom .modal-body { padding: 12px; }
        .modal-scroll { max-height: 58vh; }
        .modal-custom .table-monitoring { white-space: nowrap; }
        .modal-custom .modal-head { padding: 13px 14px; }
    }

    /* ── Animasi fade-in-up bertahap ─────────────────────────── */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in-up { animation: fadeInUp .45s ease both; animation-delay: var(--d, 0s); }

    /* ── Responsive mobile-first ─────────────────────────────── */
    .chart-container { height: 240px; }
    .stat-value { font-size: 1.25rem; }
    .table-responsive { margin: 0 -18px; padding: 0 18px; }
    .table-monitoring { white-space: nowrap; }

    @media (min-width: 992px) {
        .chart-container { height: 280px; }
        .stat-value { font-size: 1.5rem; }
        .table-responsive { margin: 0; padding: 0; }
        .table-monitoring { white-space: normal; }
    }

    /* ── Cetak: sembunyikan filter & tombol export ───────────── */
    @media print {
        .lp-filter, .btn-export, .pagination-laporan, .detail-filter, .laporan-loading { display: none !important; }
        .lp-card, .panel { box-shadow: none !important; border: 1px solid #ddd !important; }
        body, .nk-main { background: #fff !important; }
    }
</style>
@endpush

@section('content')

{{-- Loading overlay --}}
<div class="laporan-loading" id="laporanLoading">
    <div class="spinner-box"><span class="spinner-border spinner-border-sm"></span> Memuat laporan…</div>
</div>
{{-- Modal detail stat card --}}
<div class="modal fade modal-custom" id="statModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-head head-green" id="statModalHead">
                <div class="d-flex align-items-center gap-2">
                    <span class="modal-head-icon"><em class="icon ni ni-check-circle"></em></span>
                    <h5 class="modal-title mb-0" id="statModalTitle">Detail</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-0">
                <div class="modal-scroll" id="statModalBody"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-export" data-bs-dismiss="modal" id="statModalClose">Tutup</button>
                <a href="{{ $pdfUrl }}" class="btn-export" id="statModalPdf"><em class="icon ni ni-download"></em> Export PDF</a>
            </div>
        </div>
    </div>
</div>
{{-- ── HEADER + Export PDF ───────────────────────────── --}}
<div class="lp-header fade-in-up">
    <div class="lp-title-group">
        <div class="lp-title-icon"><em class="icon ni ni-chart-pie"></em></div>
        <div class="lp-title">
            <h1>Laporan Monitoring</h1>
            <p>
                Dashboard Laporan Monitoring <strong>RADNEXT NOC</strong>
                &middot; Periode: <span class="fw-semibold text-dark">{{ $period['label'] }}</span>
                &middot; <span id="updatedAt">{{ $generatedAt }}</span>
            </p>
        </div>
    </div>
    <a href="{{ $pdfUrl }}" class="btn-export" id="btnExport">
        <em class="icon ni ni-download"></em> Export PDF
    </a>
</div>
{{-- ── FILTER BAR (SATU) ─────────────────────────────── --}}
<form method="GET" action="{{ route('admin.laporan') }}" class="lp-card lp-filter fade-in-up" id="laporanForm" style="--d:.05s">
    <div class="filter-row">
        <div class="f-field">
            <label for="periodSelect">Jenis Periode</label>
            <select id="periodSelect" name="period" class="form-select">
                <option value="daily"   @selected($period['type'] === 'daily')>Harian (Daily)</option>
                <option value="weekly"  @selected($period['type'] === 'weekly')>Mingguan (Weekly)</option>
                <option value="monthly" @selected($period['type'] === 'monthly')>Bulanan (Monthly)</option>
            </select>
        </div>
        <div class="f-field" data-period-group="daily">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') ?: $period['start']->format('Y-m-d') }}">
        </div>
        <div class="f-field" data-period-group="weekly">
            <label>Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') ?: $period['start']->format('Y-m-d') }}">
        </div>
        <div class="f-field" data-period-group="weekly">
            <label>Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') ?: $period['end']->format('Y-m-d') }}">
        </div>
        <div class="f-field" data-period-group="monthly">
            <label>Bulan</label>
            <input type="month" name="month" class="form-control" value="{{ request('month') ?: $period['start']->format('Y-m') }}">
        </div>
        <button type="submit" class="btn-apply"><em class="icon ni ni-filter"></em> Terapkan</button>
    </div>
</form>
@if(! $hasData)
    <div class="lp-card empty-state fade-in-up" style="--d:.10s">
        <span class="empty-icon"><em class="icon ni ni-inbox"></em></span>
        <h6 class="mb-1 text-dark fw-bold">Belum ada data monitoring</h6>
        <p class="mb-0 small">Tidak ditemukan pengecekan pada periode <strong>{{ $period['label'] }}</strong>.
            Jalankan monitoring terlebih dahulu atau pilih periode lain.</p>
    </div>
@else
{{-- ── STAT CARDS (4 sejajar) ────────────────────────── --}}
<div class="row g-3">
    <div class="col-6 col-lg-3 fade-in-up" style="--d:.05s">
        <div class="lp-card stat-card" data-card="uptime">
            <div class="stat-top">
                <div class="icon-box icon-green"><em class="icon ni ni-check-circle"></em></div>
                <div>
                    <div class="stat-value">{{ $uptimeVal !== null ? number_format($uptimeVal, 2) : '—' }}<span class="stat-unit">%</span></div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>
            <div class="stat-foot">
                <span><b>{{ number_format($kpi['checks']) }}</b> cek</span>
                <span><b>{{ number_format($kpi['up']) }}</b> UP</span>
            </div>
            <span class="click-hint">Klik untuk lihat detail</span>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-in-up" style="--d:.10s">
        <div class="lp-card stat-card" data-card="down">
            <div class="stat-top">
                <div class="icon-box icon-red"><em class="icon ni ni-cross-circle"></em></div>
                <div>
                    <div class="stat-value">{{ number_format($kpi['down_checks']) }}</div>
                    <div class="stat-label">Total DOWN</div>
                </div>
            </div>
            <div class="stat-foot">
                <span><b>{{ number_format($kpi['down']) }}</b> episode</span>
                @if($affectedNames)
                    <span class="tip-wrap">
                        <span class="tip-trigger"><b>{{ number_format($kpi['affected']) }}</b> perangkat terdampak</span>
                        <span class="tip">{{ $affectedNames }}</span>
                    </span>
                @else
                    <span>— perangkat terdampak</span>
                @endif
            </div>
            <span class="click-hint">Klik untuk lihat detail</span>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-in-up" style="--d:.15s">
        <div class="lp-card stat-card" data-card="incidents">
            <div class="stat-top">
                <div class="icon-box icon-amber"><em class="icon ni ni-alert-circle"></em></div>
                <div>
                    <div class="stat-value">{{ number_format($kpi['incidents']) }}</div>
                    <div class="stat-label">Total Incident</div>
                </div>
            </div>
            <div class="stat-foot">
                <span><b>{{ number_format($summary['down']) }}</b> DOWN</span>
                <span><b>{{ number_format($summary['unreachable']) }}</b> UNREACH</span>
            </div>
            <span class="click-hint">Klik untuk lihat detail</span>
        </div>
    </div>
    <div class="col-6 col-lg-3 fade-in-up" style="--d:.20s">
        <div class="lp-card stat-card" data-card="response">
            <div class="stat-top">
                <div class="icon-box icon-violet"><em class="icon ni ni-bolt-alt"></em></div>
                <div>
                    <div class="stat-value">
                        {{ $avgRespVal !== null ? number_format($avgRespVal, 0) : '—' }}@if($avgRespVal !== null)<span class="stat-unit"> ms</span>@endif
                    </div>
                    <div class="stat-label">Avg Response</div>
                </div>
            </div>
            <div class="stat-foot">
                <span>Max <b>{{ $maxRespVal !== null ? number_format($maxRespVal, 0).' ms' : '—' }}</b></span>
                <span>Min <b>{{ $minRespVal !== null ? number_format($minRespVal, 0).' ms' : '—' }}</b></span>
            </div>
            <span class="click-hint">Klik untuk lihat detail</span>
        </div>
    </div>
</div>
{{-- ── CHARTS (2 sejajar) ─────────────────────────────── --}}
<div class="row g-3">
    <div class="col-lg-6 fade-in-up" style="--d:.05s">
        <div class="lp-card panel">
            <div class="panel-title">
                Network Availability
                <span class="hint">{{ $period['granularity'] === 'hour' ? 'per jam' : 'per hari' }}</span>
            </div>
            <div class="chart-container">
                <canvas id="availabilityChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 fade-in-up" style="--d:.10s">
        <div class="lp-card panel">
            <div class="panel-title">
                Response Time <span class="hint">ms</span>
            </div>
            <div class="chart-container">
                <canvas id="responseChart"></canvas>
            </div>
        </div>
    </div>
</div>
{{-- ── PERANGKAT PALING BERMASALAH ────────────────────── --}}
<div class="row g-3">
    <div class="col-12 fade-in-up" style="--d:.05s">
        <div class="lp-card panel">
            <div class="panel-title">Perangkat Paling Bermasalah</div>
            @if($topDevices->isEmpty())
                <div class="empty-state">
                    <span class="empty-icon"><em class="icon ni ni-shield-check"></em></span>
                    Tidak ada perangkat bermasalah pada periode ini.
                </div>
            @else
            <div class="table-responsive">
                <table class="table-monitoring">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Perangkat</th>
                            <th>IP Address</th>
                            <th class="text-center">Cek</th>
                            <th class="text-center">UP</th>
                            <th class="text-center">DOWN</th>
                            <th class="text-center">Incident</th>
                            <th>Penyebab</th>
                            <th style="width:150px;">Uptime</th>
                            <th class="text-end">Downtime</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDevices as $i => $device)
                        @php
                            $rawUptime = (float) ($device['uptime'] ?? 0);
                            // Nilai tidak masuk akal (>100 / negatif) → "—" saja.
                            $uptime = $rawUptime >= 0 && $rawUptime <= 100 ? $rawUptime : null;
                            $barWidth = $uptime !== null ? min(100, max(0, $uptime)) : 0;
                        @endphp
                        <tr>
                            <td><span class="rank-badge rank-{{ min($i + 1, 4) }}">{{ $i + 1 }}</span></td>
                            <td class="fw-semibold text-dark">{{ $device['device_name'] ?: 'NaN' }}</td>
                            <td class="font-monospace" style="font-size:.76rem; color:#6b7a99;">{{ $device['ip_address'] ?: '—' }}</td>
                            <td class="text-center">{{ number_format($device['checks']) }}</td>
                            <td class="text-center text-success fw-semibold">{{ number_format($device['up']) }}</td>
                            <td class="text-center {{ $device['down'] > 0 ? 'text-danger fw-semibold' : '' }}">{{ number_format($device['down']) }}</td>
                            <td class="text-center">
                                <span class="status-badge {{ $device['incidents'] >= 10 ? 'status-down' : ($device['incidents'] >= 4 ? 'status-unreach' : 'status-recovered') }}">
                                    {{ number_format($device['incidents']) }}
                                </span>
                            </td>
                            <td>
                                @if(isset($device['problem']))
                                    @php $pr = $device['problem']; @endphp
                                    <div class="cause-main">
                                        @if($pr['cause_manual'])
                                            <div class="cause-manual">{{ $pr['cause_manual'] }}</div>
                                            <span class="cause-badge cause-badge-{{ $pr['tone'] }}">{{ $pr['cause'] }}</span>
                                        @else
                                            <span class="cause-badge cause-badge-{{ $pr['tone'] }}">{{ $pr['cause'] }}</span>
                                        @endif
                                    </div>
                                    <div class="cause-sub">
                                        Minggu ini {{ number_format($pr['week_count']) }}x &middot;
                                        Sebulan ini {{ number_format($pr['month_count']) }}x &middot;
                                        Terakhir {{ $pr['last_label'] ?: '—' }}
                                    </div>
                                @else
                                    <span class="text-soft">—</span>
                                @endif
                            </td>
                            <td>
                                @if($uptime !== null)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress-thin">
                                            <div class="progress-bar {{ $uptime < 90 ? 'bar-danger' : '' }}" style="width:{{ $barWidth . '%' }}"></div>
                                        </div>
                                        <span class="small fw-semibold {{ $uptime < 90 ? 'text-danger' : 'text-success' }}" style="min-width:40px;">{{ number_format($uptime, 1) }}%</span>
                                    </div>
                                @else
                                    <span class="text-soft">—</span>
                                @endif
                            </td>
                            <td class="text-end text-danger fw-semibold">{{ \App\Helpers\LaporanFormat::duration($device['downtime'] ?? 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
{{-- ── DETAIL INCIDENT ────────────────────────────────── --}}
<div class="lp-card panel fade-in-up" style="--d:.05s">
    <div class="detail-header">
        <div class="panel-title" style="margin-bottom:0;">
            Detail Incident
            <span class="hint">{{ number_format($history['total']) }} kejadian</span>
        </div>
        <form method="GET" action="{{ route('admin.laporan') }}" class="detail-filter d-inline-flex align-items-center gap-2">
            @foreach(Arr::except($currentQuery, ['device', 'page']) as $key => $value)
                @if(! is_array($value))
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <select name="device" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Perangkat</option>
                @foreach($historyFilter['options'] as $name => $deviceId)
                    <option value="{{ $deviceId }}" @selected((string) $historyFilter['device'] === (string) $deviceId)>{{ $name }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @if($history['items']->isEmpty())
        <div class="empty-state">
            <span class="empty-icon"><em class="icon ni ni-inbox"></em></span>
            Tidak ada incident pada periode ini.
        </div>
    @else
    <div class="table-responsive">
        <table class="table-monitoring">
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Status</th>
                    <th>Mulai</th>
                    <th>Durasi</th>
                    <th class="text-end">Response Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history['items'] as $episode)
                @php
                    $isOngoing = (bool) ($episode['ongoing'] ?? false);
                    $isDown = $episode['status'] === 'down';
                    $respTime = (isset($episode['response_time']) && $episode['response_time'] !== null && (int) $episode['response_time'] > 0)
                        ? (int) $episode['response_time'] : null;
                @endphp
                <tr>
                    <td class="fw-semibold text-dark">{{ $episode['device_name'] ?: 'NaN' }}</td>
                    <td>
                        @if($isOngoing)
                            <span class="status-badge status-ongoing">{{ strtoupper($episode['status']) }}</span>
                            <small class="text-soft ms-1">berlangsung</small>
                        @else
                            <span class="status-badge status-recovered">RECOVERED</span>
                            <small class="text-soft ms-1">{{ strtoupper($episode['status']) }}</small>
                        @endif
                    </td>
                    <td style="color:#6b7a99;">{{ $episode['start'] }}</td>
                    <td>
                        {{ \App\Helpers\LaporanFormat::duration($episode['duration_seconds'] ?? 0) }}
                        @if($isOngoing)
                            <span class="badge bg-light text-soft ms-1">s/d sekarang</span>
                        @endif
                    </td>
                    <td class="text-end {{ $respTime !== null ? 'fw-semibold text-success' : 'text-soft' }}">
                        {{ $respTime !== null ? number_format($respTime, 0).' ms' : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($history['lastPage'] > 1)
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 pt-3" style="border-top:1px solid #eef1f6;">
        <span class="small text-soft">
            Halaman {{ $history['page'] }} dari {{ $history['lastPage'] }} &middot; {{ number_format($history['total']) }} incident
        </span>
        <div class="pagination-laporan">
            <a href="{{ $pageUrl(max(1, $history['page'] - 1)) }}" class="page-btn {{ $history['page'] <= 1 ? 'disabled' : '' }}"><em class="icon ni ni-chevron-left"></em></a>
            @php
                $startPage = max(1, $history['page'] - 2);
                $endPage = min($history['lastPage'], $startPage + 4);
                $startPage = max(1, $endPage - 4);
            @endphp
            @for($p = $startPage; $p <= $endPage; $p++)
                <a href="{{ $pageUrl($p) }}" class="page-btn @selected($p === (int) $history['page'])">{{ $p }}</a>
            @endfor
            <a href="{{ $pageUrl(min($history['lastPage'], $history['page'] + 1)) }}" class="page-btn {{ $history['page'] >= $history['lastPage'] ? 'disabled' : '' }}"><em class="icon ni ni-chevron-right"></em></a>
        </div>
    </div>
    @endif
    @endif
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        console.warn('[laporan] Chart.js tidak tersedia di bundle.');
        return;
    }

    var gridColor = 'rgba(134,148,174,.12)';
    var tooltipBg = '#162447';
    var tooltipBorder = '#1e3054';

    function tooltipOpts(extra) {
        return {
            backgroundColor: tooltipBg,
            titleColor: '#fff',
            bodyColor: '#c5d0e6',
            borderColor: tooltipBorder,
            borderWidth: 1,
            padding: 10,
            cornerRadius: 6,
            displayColors: true,
            callbacks: extra
        };
    }

    /* ── Data chart via @json($chartData), fallback dummy ─────── */
    var chartData = @json($chartData);
    var availability = chartData.availability || {};
    var response    = chartData.response || {};

    var availRaw     = Array.isArray(availability.availability) ? availability.availability : [];
    var availLabels  = Array.isArray(availability.labels) ? availability.labels : [];
    var hasAvailData = availRaw.some(function (v) { return v !== null && v !== undefined; });
    if (!availLabels.length || !hasAvailData) {
        availLabels = chartData.dummy.availability.labels;
        availRaw    = chartData.dummy.availability.availability;
    }

    var respRaw  = Array.isArray(response.avg) ? response.avg : [];
    var respMin  = Array.isArray(response.min) ? response.min : [];
    var respMax  = Array.isArray(response.max) ? response.max : [];
    var respLbl  = Array.isArray(response.labels) ? response.labels : [];
    var hasRespData = respRaw.some(function (v) { return v !== null && v !== undefined; });
    if (!respLbl.length || !hasRespData) {
        respLbl  = chartData.dummy.response.labels;
        respRaw  = chartData.dummy.response.avg;
        respMin  = chartData.dummy.response.min;
        respMax  = chartData.dummy.response.max;
    }

    /* ── Network Availability (hijau #198754, y 90–100%) ──────── */
    var nonNullAvail = availRaw.filter(function (v) { return v !== null && v !== undefined; });
    var availMin = nonNullAvail.length ? Math.min.apply(null, nonNullAvail) : 99;
    var availSuggestedMin = Math.max(0, Math.floor(availMin - 2));

    new Chart(
        document.getElementById('availabilityChart').getContext('2d'),
        {
            type: 'line',
            data: {
                labels: availLabels,
                datasets: [{
                    label: 'Network Availability',
                    data: availRaw,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,.08)',
                    borderWidth: 2.5,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#198754',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    tension: 0.35,
                    spanGaps: true,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipOpts({
                        label: function (ctx) {
                            var v = ctx.parsed.y;
                            return 'Availability: ' + (v === null || v === undefined ? '—' : v.toFixed(2) + '%');
                        }
                    })
                },
                scales: {
                    x: {
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { maxRotation: 0, autoSkipPadding: 18, font: { size: 10 } }
                    },
                    y: {
                        min: availSuggestedMin,
                        max: 100,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { stepSize: 2, font: { size: 10 }, callback: function (v) { return v + '%'; } }
                    }
                }
            }
        }
    );

    /* ── Response Time (Avg merah #e8534f) ─────────────────────── */
    new Chart(
        document.getElementById('responseChart').getContext('2d'),
        {
            type: 'line',
            data: {
                labels: respLbl,
                datasets: [
                    {
                        label: 'Avg',
                        data: respRaw,
                        borderColor: '#e8534f',
                        backgroundColor: 'rgba(232,83,79,.10)',
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#e8534f',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        tension: 0.35,
                        spanGaps: true,
                        fill: true
                    },
                    {
                        label: 'Max',
                        data: respMax,
                        borderColor: '#1c2b4a',
                        backgroundColor: 'transparent',
                        borderWidth: 1.5,
                        borderDash: [5, 4],
                        pointRadius: 2,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#1c2b4a',
                        tension: 0.35,
                        spanGaps: true,
                        fill: false
                    },
                    {
                        label: 'Min',
                        data: respMin,
                        borderColor: '#198754',
                        backgroundColor: 'transparent',
                        borderWidth: 1.5,
                        borderDash: [5, 4],
                        pointRadius: 2,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#198754',
                        tension: 0.35,
                        spanGaps: true,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, boxHeight: 12, font: { size: 10 } }, reverse: true },
                    tooltip: tooltipOpts({
                        label: function (ctx) {
                            var v = ctx.parsed.y;
                            return ctx.dataset.label + ': ' + (v === null || v === undefined ? '—' : v + ' ms');
                        }
                    })
                },
                scales: {
                    x: {
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { maxRotation: 0, autoSkipPadding: 18, font: { size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { font: { size: 10 }, callback: function (v) { return v + ' ms'; } }
                    }
                }
            }
        }
    );

    /* ── Filter: tampilkan input sesuai jenis periode ─────────── */
    var periodSelect = document.getElementById('periodSelect');
    function syncPeriodInputs() {
        var v = periodSelect.value;
        document.querySelectorAll('[data-period-group]').forEach(function (group) {
            group.classList.toggle('d-none', group.dataset.periodGroup !== v);
        });
    }
    if (periodSelect) {
        periodSelect.addEventListener('change', syncPeriodInputs);
        syncPeriodInputs();
    }

    /* ── Loading overlay ───────────────────────────────────────── */
    var loader = document.getElementById('laporanLoading');
    function showLoading(e) {
        if (!loader) return;
        if (e && (e.ctrlKey || e.metaKey)) return;
        loader.classList.add('show');
    }
    var form = document.getElementById('laporanForm');
    if (form) form.addEventListener('submit', showLoading);
    document.querySelectorAll('.detail-filter').forEach(function (f) {
        f.addEventListener('submit', showLoading);
    });
    document.querySelectorAll('.detail-filter select').forEach(function (s) {
        s.addEventListener('change', showLoading);
    });
    var exportBtn = document.getElementById('btnExport');
    if (exportBtn) exportBtn.addEventListener('click', showLoading);
    document.querySelectorAll('.pagination-laporan a').forEach(function (a) {
        a.addEventListener('click', showLoading);
    });

    /* ── Modal stat card (data dari chartData.modal, ikut periode) ── */
    var modalStore = chartData.modal || {};

    function fmtDur(s) {
        s = Math.max(0, Math.round(Number(s) || 0));
        if (s <= 0) return '<1 dtk';
        var d = Math.floor(s / 86400), h = Math.floor((s % 86400) / 3600), m = Math.floor((s % 3600) / 60), sec = s % 60;
        var parts = [];
        if (d > 0) parts.push(d + ' hari');
        if (h > 0) parts.push(h + ' jam');
        if (m > 0 && d === 0) parts.push(m + ' mnt');
        if (sec > 0 && d === 0 && h === 0) parts.push(sec + ' dtk');
        return parts.join(' ') || s + ' dtk';
    }
    function fmtNum(n) {
        var v = parseFloat(n);
        return isNaN(v) ? '—' : n.toLocaleString('id-ID');
    }
    function fmtPct(v) {
        var x = parseFloat(v);
        if (isNaN(x) || x < 0 || x > 100) return '—';
        return x.toFixed(2) + '%';
    }
    function fmtMs(v) {
        var x = parseFloat(v);
        if (isNaN(x) || x <= 0) return '—';
        return x.toLocaleString('id-ID') + ' ms';
    }

    function cell(text, cls) { return { t: text, c: cls || '' }; }
    function nodeCell(node, cls) { return { n: node, c: cls || '' }; }

    function buildTable(headings, aligns, rows) {
        var table = document.createElement('table');
        table.className = 'table-monitoring';
        var thead = document.createElement('thead');
        var hr = document.createElement('tr');
        headings.forEach(function (h, i) {
            var th = document.createElement('th');
            th.textContent = h;
            if (aligns && aligns[i]) th.className = aligns[i];
            hr.appendChild(th);
        });
        thead.appendChild(hr);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');
        if (!rows.length) {
            var tr0 = document.createElement('tr');
            var td0 = document.createElement('td');
            td0.colSpan = headings.length;
            td0.className = 'text-center py-4 text-soft';
            td0.textContent = 'Tidak ada data pada periode ini.';
            tr0.appendChild(td0);
            tbody.appendChild(tr0);
        } else {
            rows.forEach(function (cells) {
                var tr = document.createElement('tr');
                cells.forEach(function (c) {
                    var td = document.createElement('td');
                    if (c.c) td.className = c.c;
                    if (c.n) td.appendChild(c.n);
                    else td.textContent = c.t === null || c.t === undefined ? '—' : c.t;
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
        }
        table.appendChild(tbody);
        return table;
    }

    function statusBadge(status, ongoing) {
        var span = document.createElement('span');
        span.className = 'status-badge';
        if (ongoing) {
            span.classList.add('status-ongoing');
            span.textContent = 'BERLANGSUNG';
        } else if (status === 'down') {
            span.classList.add('status-down');
            span.textContent = 'DOWN';
        } else if (status === 'unreachable' || status === 'warning' || status === 'timeout') {
            span.classList.add('status-unreach');
            span.textContent = 'UNREACHABLE';
        } else {
            span.classList.add('status-recovered');
            span.textContent = 'RECOVERED';
        }
        return span;
    }

    function renderUptimeModal() {
        var rows = (modalStore.uptime || []).map(function (d) {
            return [
                cell(d.name || 'NaN', 'fw-semibold text-dark'),
                cell(d.ip || '—', 'font-monospace'),
                cell(fmtNum(d.checks), 'text-center'),
                cell(fmtNum(d.up), 'text-center text-success fw-semibold'),
                cell(fmtNum(d.down), 'text-center' + (Number(d.down) > 0 ? ' text-danger fw-semibold' : '')),
                cell(fmtPct(d.uptime), 'text-center fw-semibold')
            ];
        });
        return buildTable(
            ['Perangkat', 'IP Address', 'Cek', 'UP', 'DOWN', 'Uptime'],
            ['', '', 'text-center', 'text-center', 'text-center', 'text-center'],
            rows
        );
    }

    function renderDownModal() {
        var rows = (modalStore.down || []).map(function (ep) {
            var dur = nodeCell(
                (function () {
                    var span = document.createElement('span');
                    span.textContent = fmtDur(ep.duration);
                    if (ep.ongoing) {
                        var b = document.createElement('span');
                        b.className = 'badge bg-light text-soft ms-1';
                        b.textContent = 's/d sekarang';
                        span.appendChild(b);
                    }
                    return span;
                })()
            );
            return [
                cell(ep.name || 'NaN', 'fw-semibold text-dark'),
                cell(ep.ip || '—', 'font-monospace'),
                cell(ep.start || '—', ''),
                dur
            ];
        });
        return buildTable(
            ['Device', 'IP Address', 'Mulai', 'Durasi'],
            ['', '', '', ''],
            rows
        );
    }

    function renderIncidentsModal() {
        var rows = (modalStore.incidents || []).map(function (ep) {
            var status = nodeCell(statusBadge(ep.status, ep.ongoing));
            return [
                cell(ep.name || 'NaN', 'fw-semibold text-dark'),
                status,
                cell(ep.start || '—', ''),
                cell(fmtDur(ep.duration), ''),
                cell(fmtMs(ep.response), 'text-end' + (Number(ep.response) > 0 ? ' fw-semibold text-success' : ''))
            ];
        });
        return buildTable(
            ['Device', 'Status', 'Mulai', 'Durasi', 'Response'],
            ['', '', '', '', 'text-end'],
            rows
        );
    }

    function renderResponseModal() {
        var rows = (modalStore.response || []).map(function (d) {
            return [
                cell(d.name || 'NaN', 'fw-semibold text-dark'),
                cell(d.ip || '—', 'font-monospace'),
                cell(fmtMs(d.avg), 'text-end fw-semibold'),
                cell(fmtMs(d.max), 'text-end'),
                cell(fmtMs(d.min), 'text-end')
            ];
        });
        return buildTable(
            ['Perangkat', 'IP Address', 'Avg', 'Max', 'Min'],
            ['', '', 'text-end', 'text-end', 'text-end'],
            rows
        );
    }

    var MODAL_META = {
        uptime:    { head: 'head-green',  icon: 'ni ni-check-circle', title: 'Detail Uptime' },
        down:      { head: 'head-red',    icon: 'ni ni-cross-circle', title: 'Detail Total DOWN' },
        incidents: { head: 'head-amber',  icon: 'ni ni-alert-circle', title: 'Detail Total Incident' },
        response:  { head: 'head-violet', icon: 'ni ni-bolt-alt',     title: 'Detail Avg Response' }
    };
    var renderers = {
        uptime: renderUptimeModal,
        down: renderDownModal,
        incidents: renderIncidentsModal,
        response: renderResponseModal
    };

    var statModalEl = document.getElementById('statModal');
    var statCards = document.querySelectorAll('.stat-card[data-card]');
    statCards.forEach(function (card) {
        card.addEventListener('click', function () {
            var key = card.dataset.card;
            var meta = MODAL_META[key];
            var render = renderers[key];
            if (!statModalEl || !meta || !render) return;

            var head = document.getElementById('statModalHead');
            head.className = 'modal-header modal-head ' + meta.head;
            var iconEl = head.querySelector('.modal-head-icon em');
            if (iconEl) iconEl.className = 'icon ' + meta.icon;
            document.getElementById('statModalTitle').textContent = meta.title;

            var body = document.getElementById('statModalBody');
            body.textContent = '';
            body.appendChild(render());

            bootstrap.Modal.getOrCreateInstance(statModalEl).show();
        });
    });

    var modalPdf = document.getElementById('statModalPdf');
    if (modalPdf) modalPdf.addEventListener('click', showLoading);

});
</script>
@endpush