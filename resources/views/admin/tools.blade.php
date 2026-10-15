@extends('layouts.admin')

@section('title', 'Network Tools · RADNET NOC')

{{-- Halaman ini memakai identitas konsol NOC: header kotak biru ala WhatsUp
     Gold dan dua tab pemeriksaan, Ping Server Radnet dan Ping Server Luar.
     Tab "Status Ringkasan", tab "AI Assistant" (chat tanya-jawab), kartu
     "AI Network Assistant", dan "Live Visual Packet Tracer" (Hop Route
     Mapper) sudah dihapus total sesuai arahan — kartu di bawah tab hanya
     menampilkan hasil pemeriksaan beserta rekomendasinya, tanpa branding AI.
     Tailwind dimuat lewat ping-monitor.css (page-scoped, TANPA preflight) supaya
     tidak merusak sidebar/template halaman admin lain. --}}
@push('styles')
@php
    $pmEntry = 'resources/css/ping-monitor.css';
    $pmManifestPath = public_path('build/manifest.json');
    $pmHasEntry = false;

    if (is_file($pmManifestPath)) {
        $pmManifest = json_decode((string) file_get_contents($pmManifestPath), true);
        $pmHasEntry = is_array($pmManifest) && ! empty($pmManifest[$pmEntry]['file']);
    }
@endphp
@if ($pmHasEntry)
    @vite([$pmEntry])
@else
    <link rel="stylesheet" href="{{ asset('css/ping-monitor.css') }}">
@endif
<style>
    .pm-page {
        width: 100%;
        padding: 0.8rem 0.75rem 1rem;
        background: #eef2f7;
    }

    .pm-shell {
        width: calc(100% - 0.2rem);
        max-width: none;
        margin: 0 auto;
        background: linear-gradient(180deg, #f8fbff 0%, #edf4ff 100%);
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 1.1rem;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .pm-surface {
        background: linear-gradient(180deg, #f8fbff 0%, #eef5ff 100%);
        padding: 0 0 1rem;
    }

    .pm-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.7rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid rgba(148, 163, 184, 0.22);
    }

    .pm-pill--light {
        background: rgba(255, 255, 255, 0.08);
        color: #dfe7f5;
    }

    .pm-pill--success {
        background: rgba(34, 197, 94, 0.12);
        color: #86efac;
        border-color: rgba(134, 239, 172, 0.25);
    }

    .pm-pill--warning {
        background: rgba(244, 114, 182, 0.12);
        color: #f9a8d4;
        border-color: rgba(249, 168, 212, 0.25);
    }

    .pm-pill--danger {
        background: rgba(248, 113, 113, 0.14);
        color: #fca5a5;
        border-color: rgba(252, 165, 165, 0.28);
    }

    .pm-tabbar {
        padding: 0.9rem 1.25rem 0;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        background: rgba(15, 23, 42, 0.72);
    }

    .tools-tab {
        background: rgba(255, 255, 255, 0.80);
        color: #334155;
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 0.8rem;
        padding: 0.72rem 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .tools-tab:hover { background: rgba(255, 255, 255, 0.96); }
    .tools-tab.is-active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        border-color: rgba(96, 165, 250, 0.7);
        box-shadow: 0 8px 18px rgba(37, 99, 235, .20);
    }

    .pm-panel-wrap {
        padding: 0.75rem 0.75rem 0.25rem;
    }

    .pm-main-panel {
        background: linear-gradient(180deg, #f8fafc 0%, #f3f7ff 100%);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 1rem;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
        overflow: hidden;
        width: 100%;
    }

    .pm-main-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.97), rgba(37, 99, 235, 0.16));
        color: #0f172a;
        font-weight: 700;
    }

    .pm-main-header h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #f8fafc;
    }

    .pm-main-header .pm-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        min-height: 36px;
        border-radius: 9999px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.18);
        color: #e2e8f0;
        padding: 0.35rem 0.8rem;
    }

    .pm-card-body {
        padding: 0.9rem 1rem 1rem;
    }

    .pm-form-grid {
        display: grid;
        gap: 1rem;
    }

    .pm-form-control {
        width: 100%;
        background: #ffffff;
        border: 1px solid #dbe3ee;
        color: #0f172a;
        border-radius: 0.8rem;
        padding: 0.8rem 0.9rem;
        font-size: 0.92rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .pm-form-control::placeholder { color: #64748b; }
    .pm-form-control:focus {
        outline: none;
        border-color: rgba(59, 130, 246, 0.8);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .pm-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .pm-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #334155;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .pm-device-meta {
        margin-top: 0.9rem;
        padding: 1rem;
        background: rgba(15, 23, 42, 0.52);
        border: 1px solid rgba(148, 163, 184, 0.15);
        border-radius: 0.75rem;
        color: #e2e8f0;
    }

    .pm-device-meta p {
        margin: 0.25rem 0;
        font-size: 0.82rem;
        color: #cbd5e1;
    }

    .pm-device-meta strong { color: #f8fafc; }

    .pm-run-btn {
        margin-top: 1rem;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 0.9rem 1rem;
        border-radius: 0.8rem;
        border: 1px solid rgba(96, 165, 250, 0.6);
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff;
        font-weight: 800;
        font-size: 1rem;
        box-shadow: 0 10px 24px rgba(37, 99, 235, .32);
    }

    .pm-run-btn:hover { filter: brightness(1.06); }

    .pm-run-actions {
        display: grid;
        gap: 0.6rem;
        margin-top: 1rem;
    }

    .pm-run-actions .pm-run-btn { margin-top: 0; }

    .pm-stop-btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 0.9rem 1rem;
        border-radius: 0.8rem;
        border: 1px solid rgba(248, 113, 113, 0.7);
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        color: #fff;
        font-weight: 800;
        font-size: 1rem;
        box-shadow: 0 10px 24px rgba(185, 28, 28, .30);
    }

    .pm-stop-btn:hover { filter: brightness(1.06); }

    /* ── Log ping realtime (terminal) ───────────────────────────
       Latar terang/putih bersih supaya teks hijau/kuning/merah sangat
       jelas dibaca (mis. saat ditunjukkan ke atasan/senior). */
    .pm-terminal {
        height: 320px;
        overflow-y: auto;
        padding: 0.9rem 1rem;
        border-radius: 0.7rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 0 0 1px rgba(226, 232, 240, 0.6);
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 0.82rem;
        line-height: 1.5;
        color: #334155;
        scroll-behavior: smooth;
    }

    /* Warna baris log SAMA PERSIS dengan badge status di daftar perangkat
       (.status-badge.up / .warning / .down) supaya indikator senada. */
    .pm-log-line { white-space: pre-wrap; word-break: break-word; }
    .pm-log-line--ok { color: #166534; }
    .pm-log-line--warn { color: #92400e; }
    .pm-log-line--bad { color: #991b1b; }
    .pm-log-line--head { color: #1e40af; }
    .pm-log-line--meta { color: #64748b; }

    .pm-log-empty {
        color: #64748b;
        font-style: italic;
    }

    /* device icon + status badge */
    .device-icon {
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        border-radius: 0.65rem;
        background: linear-gradient(180deg, #ffffff, #f1f5f9);
        border: 1px solid #e2e8f0;
        flex: 0 0 44px;
        overflow: hidden;
    }

    .device-icon img,
    .device-icon svg {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: contain;
    }

    /* Cadangan kalau file gambar tidak bisa dimuat: ikon font tema. */
    .device-icon .icon {
        font-size: 22px;
        color: #3b82f6;
    }

    .status-badge {
        display:inline-flex; align-items:center; white-space:nowrap; border-radius:9999px; padding:0.28rem 0.7rem; font-size:0.78rem; font-weight:800; letter-spacing:0.04em;
    }
    .status-badge.up { background:#dcfce7; color:#166534; }
    .status-badge.warning { background:#fef3c7; color:#92400e; }
    .status-badge.down { background:#fee2e2; color:#991b1b; }

    /* ── Direktori perangkat: pencarian multi-field + filter status/POP ── */
    .dir-search { position:relative; display:flex; align-items:center; }
    .dir-search > .icon { position:absolute; left:0.95rem; font-size:1.05rem; color:#64748b; pointer-events:none; }
    .dir-search-input { width:100%; background:#fff; border:1px solid #dbe3ee; border-radius:0.8rem; padding:0.85rem 2.6rem; font-size:0.95rem; color:#0f172a; transition:border-color .2s ease, box-shadow .2s ease; }
    .dir-search-input::placeholder { color:#64748b; }
    .dir-search-input:focus { outline:none; border-color:rgba(59,130,246,.8); box-shadow:0 0 0 3px rgba(59,130,246,.12); }
    .dir-search-clear { position:absolute; right:0.6rem; border:0; background:#eef2f7; color:#475569; width:1.6rem; height:1.6rem; border-radius:50%; font-size:1.1rem; line-height:1; cursor:pointer; }
    .dir-search-clear:hover { background:#e2e8f0; }

    .dir-filters { display:flex; flex-wrap:wrap; align-items:center; gap:0.4rem; margin-top:0.6rem; }
    .dir-chip { display:inline-flex; align-items:center; gap:0.35rem; border:1px solid #dbe3ee; background:#fff; color:#334155; border-radius:9999px; padding:0.3rem 0.7rem; font-size:0.78rem; font-weight:700; cursor:pointer; transition:background .15s ease, border-color .15s ease; }
    .dir-chip span { background:#eef2f7; border-radius:9999px; padding:0.05rem 0.45rem; font-size:0.72rem; }
    .dir-chip:hover { background:#f8fbff; }
    .dir-chip.is-active { background:#1d4ed8; border-color:#1d4ed8; color:#fff; }
    .dir-chip.is-active span { background:rgba(255,255,255,.22); color:#fff; }
    .dir-chip--down.is-active { background:#dc2626; border-color:#dc2626; }
    .dir-chip--warning.is-active { background:#d97706; border-color:#d97706; }
    .dir-chip--up.is-active { background:#16a34a; border-color:#16a34a; }

    .dir-recent { display:flex; flex-wrap:wrap; align-items:center; gap:0.4rem; margin-top:0.6rem; }
    .dir-recent-label { font-size:0.78rem; font-weight:700; color:#64748b; }
    .dir-recent-list { display:flex; flex-wrap:wrap; gap:0.4rem; }
    .dir-recent-btn { border:1px dashed #cbd5e1; background:#fff; color:#334155; border-radius:9999px; padding:0.25rem 0.65rem; font-size:0.78rem; cursor:pointer; }
    .dir-recent-btn:hover { background:#f1f5f9; }

    .dir-selected { display:none; align-items:center; gap:0.7rem; margin-top:0.6rem; padding:0.6rem 0.8rem; border-radius:0.7rem; background:linear-gradient(135deg,#eff6ff,#e0f2fe); border:1px solid #bfdbfe; }
    .dir-selected.is-visible { display:flex; }
    .dir-selected-icon { width:36px; height:36px; flex:0 0 36px; display:inline-flex; align-items:center; justify-content:center; border-radius:0.55rem; background:#fff; border:1px solid #dbe3ee; overflow:hidden; padding:3px; }
    .dir-selected-icon img { width:100%; height:100%; object-fit:contain; }
    .dir-selected-meta { display:flex; flex-direction:column; min-width:0; margin-right:auto; }
    .dir-selected-meta strong { color:#0f172a; font-size:0.95rem; }
    .dir-selected-meta span { color:#475569; font-size:0.8rem; }

    .dir-list { margin-top:0.7rem; border:1px solid #e2e8f0; background:#fff; border-radius:0.7rem; max-height:360px; overflow:auto; padding:0.35rem; }
    .dir-item { display:flex; align-items:center; gap:0.75rem; width:100%; text-align:left; padding:0.5rem; border-radius:0.55rem; cursor:pointer; border:0; background:transparent; font:inherit; }
    .dir-item + .dir-item { margin-top:0.15rem; }
    .dir-item:hover { background:#f8fbff; }
    .dir-item.is-active { background:#eef4ff; box-shadow:inset 0 0 0 1px #bfdbfe; }
    .dir-item.is-selected { background:#eff6ff; box-shadow:inset 0 0 0 1px #93c5fd; }
    .dir-item-meta { display:flex; flex-direction:column; min-width:0; flex:1; }
    .dir-item-name { font-weight:700; color:#0f172a; font-size:0.95rem; }
    .dir-item-sub { display:flex; flex-wrap:wrap; align-items:center; gap:0.5rem; font-size:0.8rem; color:#475569; margin-top:0.1rem; }
    .dir-item-ip { font-family:ui-monospace, SFMono-Regular, Menlo, monospace; color:#1d4ed8; }
    .dir-item-pop::before, .dir-item-type::before { content:'·'; margin-right:0.35rem; color:#94a3b8; }
    .dir-item-status { margin-left:0.5rem; }
    .dir-empty { padding:1rem; text-align:center; color:#64748b; font-size:0.9rem; }

    .hidden { display:none !important; }

    /* ── Kartu hasil pemeriksaan: ringkasan + rekomendasi ─────── */
    .pm-insight-stack {
        display: grid;
        gap: 1rem;
        margin-top: 1rem;
    }

    .pm-insight-card {
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 0.9rem;
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .pm-insight-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.7rem 1rem;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.97), rgba(37, 99, 235, 0.16));
    }

    .pm-insight-head h3 {
        margin: 0;
        color: #f8fafc;
        font-size: 1.125rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .pm-insight-head .pm-pill {
        font-size: 1rem;
        padding: 0.3rem 0.8rem;
    }

    .pm-insight-body { padding: 1.15rem; }

    .pm-assistant {
        position: relative;
        border: 2px solid #e2e8f0;
        border-left-width: 8px;
        border-left-style: solid;
        border-radius: 0.9rem;
        background: #f8fafc;
        padding: 1.2rem 1.25rem 1.1rem;
        color: #475569;
        font-size: 1.125rem;
        line-height: 1.65;
        overflow: hidden;
        transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    /* Tiap status punya pita warna atas + latar + aksen kiri sendiri supaya
       DOWN, WARNING, dan UP tidak mungkin tertukar saat hasil ping tampil. */
    .pm-assistant--idle { background: #f8fafc; border-color: #e2e8f0; border-left-color: #cbd5e1; color: #64748b; }
    .pm-assistant--running { background: linear-gradient(135deg, #eff6ff, #dbeafe); border-color: #bfdbfe; border-left-color: #3b82f6; color: #1e3a8a; }
    .pm-assistant--danger {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-color: #fca5a5; border-left-color: #dc2626; color: #7f1d1d;
        box-shadow: 0 12px 26px rgba(220, 38, 38, 0.14);
    }
    .pm-assistant--warning {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-color: #fcd34d; border-left-color: #d97706; color: #78350f;
        box-shadow: 0 12px 26px rgba(217, 119, 6, 0.14);
    }
    .pm-assistant--ok {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-color: #86efac; border-left-color: #16a34a; color: #14532d;
        box-shadow: 0 12px 26px rgba(22, 163, 74, 0.14);
    }
    .pm-assistant--error { background: #f8fafc; border-color: #e2e8f0; border-left-color: #64748b; color: #334155; }

    /* Pita status solid di tepi atas kartu: merah / kuning / hijau. */
    .pm-assistant--danger::before,
    .pm-assistant--warning::before,
    .pm-assistant--ok::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 6px;
    }
    .pm-assistant--danger::before { background: linear-gradient(90deg, #dc2626, #f87171); }
    .pm-assistant--warning::before { background: linear-gradient(90deg, #d97706, #fbbf24); }
    .pm-assistant--ok::before { background: linear-gradient(90deg, #16a34a, #4ade80); }

    .pm-assistant-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .pm-assistant-title {
        display: inline-flex;
        align-items: center;
        font-weight: 800;
        font-size: 1.25rem;
        color: inherit;
    }

    .pm-assistant-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        padding: 0.32rem 0.95rem;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        border: 1px solid transparent;
        color: #fff;
    }

    .pm-badge--idle { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }
    .pm-badge--running { background: #2563eb; border-color: #1d4ed8; }
    .pm-badge--danger { background: #dc2626; border-color: #b91c1c; }
    .pm-badge--warning { background: #d97706; border-color: #b45309; }
    .pm-badge--ok { background: #16a34a; border-color: #15803d; }
    .pm-badge--error { background: #64748b; border-color: #475569; }

    /* Ikon status bulat: warnanya ikut status kartu. */
    .pm-assistant-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 9999px;
        font-size: 1.15rem;
        line-height: 1;
        margin-right: 0.6rem;
        background: #f1f5f9;
        color: #334155;
    }
    .pm-assistant--danger .pm-assistant-icon { background: #fee2e2; color: #b91c1c; }
    .pm-assistant--warning .pm-assistant-icon { background: #fef3c7; color: #b45309; }
    .pm-assistant--ok .pm-assistant-icon { background: #dcfce7; color: #15803d; }
    .pm-assistant--running .pm-assistant-icon { background: #dbeafe; color: #1d4ed8; }

    .pm-assistant-metrics {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.7rem;
    }

    /* Baris detail status: "UP · time=12.4ms", "WARNING · High Latency ·
       Packet Loss", atau "DOWN · Destination Host Unreachable". Dibuat
       menonjol supaya arti status langsung terbaca tanpa membuka log mentah. */
    .pm-assistant-statusdetail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.7rem;
        padding: 0.5rem 0.85rem;
        border-radius: 0.7rem;
        background: rgba(255, 255, 255, 0.75);
        border: 1px dashed rgba(148, 163, 184, 0.5);
        font-size: 0.95rem;
        font-weight: 700;
    }

    .pm-assistant-statusdetail .pm-status-code {
        display: inline-flex;
        align-items: center;
        padding: 0.12rem 0.6rem;
        border-radius: 9999px;
        background: #64748b;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
    }

    .pm-assistant--danger .pm-status-code { background: #dc2626; }
    .pm-assistant--warning .pm-status-code { background: #d97706; }
    .pm-assistant--ok .pm-status-code { background: #16a34a; }

    .pm-assistant-statusdetail .pm-status-value {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    }

    .pm-assistant-metric {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 9999px;
        border: 1px solid rgba(148, 163, 184, 0.28);
        background: rgba(255, 255, 255, 0.85);
        padding: 0.34rem 0.85rem;
        font-size: 1rem;
        font-weight: 700;
        color: inherit;
    }
    .pm-assistant--danger .pm-assistant-metric { border-color: rgba(220, 38, 38, 0.32); }
    .pm-assistant--warning .pm-assistant-metric { border-color: rgba(217, 119, 6, 0.32); }
    .pm-assistant--ok .pm-assistant-metric { border-color: rgba(22, 163, 74, 0.32); }

    .pm-assistant-message {
        margin-top: 0.75rem;
        line-height: 1.65;
    }

    .pm-assistant-actions-title {
        margin: 0.9rem 0 0.35rem;
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        opacity: 0.9;
    }

    .pm-assistant-actions {
        margin: 0;
        padding-left: 0;
        list-style: none;
    }

    .pm-assistant-actions li {
        position: relative;
        margin-top: 0.45rem;
        padding-left: 1.5rem;
    }

    .pm-assistant-actions li::before {
        content: '▸';
        position: absolute;
        left: 0.15rem;
        font-weight: 900;
    }
    .pm-assistant--danger .pm-assistant-actions li::before { color: #dc2626; }
    .pm-assistant--warning .pm-assistant-actions li::before { color: #d97706; }
    .pm-assistant--ok .pm-assistant-actions li::before { color: #16a34a; }

    .pm-assistant-meta {
        margin-top: 0.85rem;
        padding-top: 0.65rem;
        border-top: 1px dashed rgba(148, 163, 184, 0.35);
        font-size: 1rem;
        opacity: 0.85;
    }

    @media (max-width: 768px) {
        .pm-shell {
            border-radius: 0.75rem;
        }

        .pm-browser-bar,
        .pm-surface-header,
        .pm-main-header {
            flex-direction: column;
            align-items: stretch;
        }

        .pm-browser-left,
        .pm-browser-right,
        .pm-hero-status {
            justify-content: center;
            flex-wrap: wrap;
        }

        .pm-form-row {
            grid-template-columns: 1fr;
        }

    }
</style>
@endpush

@section('content')
    @php
        $defaultIp = '192.168.93.59';
    @endphp

    <div class="pm-page">
        <div class="pm-shell">
            <div class="pm-surface">
               <nav class="pm-tabbar" role="tablist" id="toolsTabs">
                   <button type="button" class="tools-tab is-active" data-tab="radnet" role="tab" aria-selected="true">
                       🌐 Ping Server Radnet
                   </button>
                   <button type="button" class="tools-tab" data-tab="luar" role="tab" aria-selected="false">
                       🌍 Ping Server Luar
                   </button>
                   {{-- Tab "Status Ringkasan" dan tab "AI Assistant" sudah
                        dihapus total dari bagian atas halaman: tombolnya,
                        panelnya, kartu hitung UP/WARNING/DOWN, tabel daftar
                        perangkat, serta layar chat tanya-jawab ikut dibuang. --}}
               </nav>

               <div class="pm-panel-wrap">
                   <div class="pm-main-panel">
                       <div class="pm-main-header">
                           <h2>Network Tools</h2>
                           <span class="pm-pill pm-pill--light">{{ $devices->count() }} Data Real</span>
                       </div>

                       <div class="pm-card-body">
                           {{-- ── TAB: PING SERVER RADNET ───────────────────────── --}}
                           <section class="tools-pane" id="pane-radnet" role="tabpanel">
                               <div class="pm-form-grid">
                                   <div>
                                       @php
                                           $countDown = $devices->filter(fn ($d) => $d->status === 'down')->count();
                                           $countWarning = $devices->filter(fn ($d) => $d->status === 'warning')->count();
                                           $countUp = $devices->filter(fn ($d) => $d->status === 'up')->count();
                                       @endphp

                                       <label class="pm-label" for="deviceSearch">Cari &amp; Pilih Perangkat</label>

                                       <!-- Nilai perangkat terpilih, dikirim ke backend saat ping -->
                                       <input type="hidden" id="deviceSelect" name="deviceSelect">

                                       {{-- Omnibox: satu kotak untuk semua cara mengingat perangkat.
                                            Engineer bisa mengetik nama pelanggan, IP, POP, atau
                                            tipe — tidak perlu hafal IP lagi. --}}
                                       <div class="dir-search">
                                           <em class="icon ni ni-search" aria-hidden="true"></em>
                                           <input type="text" id="deviceSearch" class="dir-search-input"
                                               placeholder="Ketik nama pelanggan, IP, POP, atau tipe perangkat…"
                                               autocomplete="off" spellcheck="false"
                                               role="combobox" aria-expanded="true" aria-controls="deviceDirectoryList"
                                               aria-autocomplete="list">
                                           <button type="button" id="deviceSearchClear" class="dir-search-clear hidden" title="Bersihkan pencarian">&times;</button>
                                       </div>

                                        <div class="dir-filters">
                                            <button type="button" class="dir-chip is-active" data-filter-status="all">Semua <span>{{ $devices->count() }}</span></button>
                                            <button type="button" class="dir-chip dir-chip--down" data-filter-status="down">DOWN <span>{{ $countDown }}</span></button>
                                            <button type="button" class="dir-chip dir-chip--warning" data-filter-status="warning">WARNING <span>{{ $countWarning }}</span></button>
                                            <button type="button" class="dir-chip dir-chip--up" data-filter-status="up">UP <span>{{ $countUp }}</span></button>
                                        </div>

                                       <div id="deviceRecent" class="dir-recent hidden">
                                           <span class="dir-recent-label">Terakhir dipakai:</span>
                                           <div class="dir-recent-list" id="deviceRecentList"></div>
                                       </div>

                                       <div id="deviceSelected" class="dir-selected">
                                           <span class="dir-selected-icon" id="deviceSelectedIcon"></span>
                                           <span class="dir-selected-meta">
                                               <strong id="deviceSelectedName"></strong>
                                               <span id="deviceSelectedSub"></span>
                                           </span>
                                           <span id="deviceSelectedStatus"></span>
                                       </div>

                                       <div id="deviceDirectoryList" class="dir-list" role="listbox" aria-label="Daftar perangkat">
                                           @forelse($devices as $device)
                                               <button type="button" class="dir-item" role="option" aria-selected="false"
                                                   data-id="{{ $device->id }}"
                                                   data-ip="{{ $device->ip_address }}"
                                                   data-name="{{ $device->name }}"
                                                   data-pop="{{ $device->pop_name }}"
                                                   data-type="{{ $device->type }}"
                                                   data-status="{{ $device->status }}"
                                                   data-icon="{{ $device->icon_url }}"
                                                   data-type-icon="{{ $device->type_icon }}">
                                                   <span class="device-icon" aria-hidden="true">
                                                       <img src="{{ $device->icon_url }}" alt="" width="40" height="40" decoding="async" onerror="this.replaceWith(Object.assign(document.createElement('em'),{className:'icon ni {{ $device->type_icon }}'}))">
                                                   </span>
                                                   <span class="dir-item-meta">
                                                       <span class="dir-item-name">{{ $device->name }}</span>
                                                       <span class="dir-item-sub">
                                                           <span class="dir-item-ip">{{ $device->ip_address }}</span>
                                                           @if($device->pop_name)
                                                               <span class="dir-item-pop">{{ $device->pop_name }}</span>
                                                           @endif
                                                           <span class="dir-item-type">{{ $device->type }}</span>
                                                       </span>
                                                    </span>
                                                   <span class="dir-item-status">
                                                       @if($device->status === 'up')
                                                           <span class="status-badge up">UP</span>
                                                       @elseif($device->status === 'warning')
                                                           <span class="status-badge warning">WARNING</span>
                                                       @else
                                                           <span class="status-badge down">DOWN</span>
                                                       @endif
                                                   </span>
                                               </button>
                                           @empty
                                               <div class="dir-empty">Belum ada perangkat pada daftar resmi (devices.csv).</div>
                                           @endforelse
                                           <div id="deviceEmpty" class="dir-empty hidden">Tidak ada perangkat yang cocok. Coba kata kunci atau filter lain.</div>
                                       </div>
                                   </div>

                                    <div>
                                        <label class="pm-label" for="radnetTarget">Target IP Address</label>
                                        <input type="text" id="radnetTarget" value="{{ $defaultIp }}" placeholder="{{ $defaultIp }}" class="pm-form-control">
                                    </div>

                                    {{-- Log mentah "Terminal Log" dan kotak "Analisis
                                         Bahasa Manusia" sudah dihapus: keduanya hanya
                                         mengulang isi kartu hasil pemeriksaan di
                                         bawah. Hasil + saran sekarang hanya dibaca
                                         dari satu kotak di sana. --}}

                                    <div class="pm-run-actions">
                                        <button type="button" id="btnPingRadnet" class="pm-run-btn">
                                            ▶ Mulai Ping Server Radnet
                                        </button>
                                        <button type="button" id="btnPingRadnetStop" class="pm-stop-btn hidden">
                                            ⏹ Hentikan Ping
                                        </button>
                                    </div>
                               </div>
                           </section>

                           {{-- ── TAB: PING SERVER LUAR ─────────────────────────── --}}
                           <section class="tools-pane hidden" id="pane-luar" role="tabpanel">
                               <div class="pm-form-grid">
                                    <div>
                                        <label class="pm-label" for="externalTarget">IP / Host</label>
                                        <input type="text" id="externalTarget" value="8.8.8.8" placeholder="8.8.8.8" class="pm-form-control">
                                    </div>

                                   <div class="pm-run-actions">
                                       <button type="button" id="btnPingExternal" class="pm-run-btn">
                                           ▶ Mulai Ping Server Luar
                                       </button>
                                       <button type="button" id="btnPingExternalStop" class="pm-stop-btn hidden">
                                           ⏹ Hentikan Ping
                                       </button>
                                   </div>
                                </div>
                            </section>

                             {{-- ── KARTU HASIL PEMERIKSAAN ────────────────────
                                  Kartu diletakkan SETELAH kedua tab pemeriksaan:
                                  tab ping yang sedang aktif selalu menutup tepat
                                  di atasnya, jadi isinya selalu "di bawah hasil
                                  pengecekan" berapa pun tab yang dipakai. --}}
                            <div class="pm-insight-stack" id="insightStack">
                                {{-- Log realtime: baris ping mentah bermunculan
                                     selama proses berjalan, seperti terminal. --}}
                                <section class="pm-insight-card" aria-labelledby="pingLogTitle">
                                    <div class="pm-insight-head">
                                        <h3 id="pingLogTitle">Log Ping (Realtime)</h3>
                                        <span class="pm-pill pm-pill--light" id="pingLogState">Menunggu</span>
                                    </div>
                                    <div class="pm-insight-body">
                                        <div id="pingLog" class="pm-terminal">
                                            <div class="pm-log-empty" id="pingLogEmpty">
                                                Tekan "Mulai Ping" untuk memulai pemantauan berkelanjutan.
                                                Baris balasan akan muncul di sini satu per satu seperti di terminal.
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="pm-insight-card" aria-labelledby="aiAssistantTitle">
                                    <div class="pm-insight-head">
                                        <h3 id="aiAssistantTitle">Hasil &amp; Rekomendasi Pemeriksaan</h3>
                                        <span class="pm-pill pm-pill--light" id="aiAssistantState">Menunggu hasil tes</span>
                                    </div>
                                    <div class="pm-insight-body">
                                        {{-- ── Ringkasan + saran otomatis ───────────── --}}
                                        <div id="aiAssistant" class="pm-assistant pm-assistant--idle">
                                            Jalankan ping pada tab di atas — status (UP / WARNING /
                                            DOWN) beserta latensi dan rekomendasi penanganannya akan tampil
                                            di sini.
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
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

    var CSRF = document.querySelector('meta[name="csrf-token"]');
    CSRF = CSRF ? CSRF.content : '';
    var PING_STREAM_URL = @json(route('admin.tools.ping.stream'));
    var PING_STOP_URL = @json(route('admin.tools.ping.stop'));

    /* ── Tab navigasi ─────────────────────────────────────────── */
    var tabs = document.querySelectorAll('.tools-tab');
    var panes = document.querySelectorAll('.tools-pane');

    function activate(tab) {
        var name = tab.dataset.tab;

        tabs.forEach(function (t) {
            var on = t === tab;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panes.forEach(function (p) {
            p.classList.toggle('hidden', p.id !== 'pane-' + name);
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () { activate(tab); });
    });

    /* Badge & sinkronisasi status perangkat dipakai DUA tempat: daftar
       direktori (saat item dipilih) dan hasil ping (setelah pemeriksaan
       selesai). Keduanya didefinisikan di scope halaman, BUKAN di dalam IIFE
       direktori, supaya runPing() tidak pernah memanggil fungsi yang
       "tidak terdefinisi" (refreshItemStatus is not defined). */
    var dirItems = [];
    var selectedStatusEl = document.getElementById('deviceSelectedStatus');

    /* Badge status. Hanya menampilkan label utama (UP / WARNING / DOWN). */
    function badgeHtml(status) {
        if (status === 'up') { return '<span class="status-badge up">UP</span>'; }
        if (status === 'warning') { return '<span class="status-badge warning">WARNING</span>'; }
        return '<span class="status-badge down">DOWN</span>';
    }

    /* Samakan badge daftar perangkat dengan hasil ping yang barusan tampil.
       Status yang dipakai adalah hasil analisis ping (aturan statusFromProbe
       yang sama), sehingga badge di atas dan kartu hasil di bawah tidak
       mungkin berbeda setelah pemeriksaan dijalankan. */
    function refreshItemStatus(deviceId, status) {
        if (!deviceId || ['up', 'warning', 'down'].indexOf(status) === -1) { return; }

        dirItems.forEach(function (it) {
            if (String(it.dataset.id) !== String(deviceId)) { return; }

            it.dataset.status = status;
            var slot = it.querySelector('.dir-item-status');
            if (slot) { slot.innerHTML = badgeHtml(status); }

            if (it.classList.contains('is-selected') && selectedStatusEl) {
                selectedStatusEl.innerHTML = badgeHtml(status);
            }
        });
    }

    /* ── Direktori perangkat: cari nama/IP/POP/tipe + filter status & POP ──
         Pengganti dropdown "pilih perangkat" lama. Semua baris perangkat
         resmi dirender sekali oleh server (urut DOWN → WARNING → UP), lalu
         disaring di browser supaya engineer cukup mengetik apa yang dia
         ingat — nama pelanggan, IP, POP, atau tipe — tanpa hafal IP. */
    (function initDeviceDirectory() {
        var hiddenInput = document.getElementById('deviceSelect');
        var search = document.getElementById('deviceSearch');
        var list = document.getElementById('deviceDirectoryList');
        var empty = document.getElementById('deviceEmpty');
        var radnetTarget = document.getElementById('radnetTarget');
        var clearBtn = document.getElementById('deviceSearchClear');
        var chips = Array.prototype.slice.call(document.querySelectorAll('.dir-chip'));
        var recentBox = document.getElementById('deviceRecent');
        var recentList = document.getElementById('deviceRecentList');
        var selectedBar = document.getElementById('deviceSelected');
        var selectedIcon = document.getElementById('deviceSelectedIcon');
        var selectedName = document.getElementById('deviceSelectedName');
        var selectedSub = document.getElementById('deviceSelectedSub');
        var selectedStatus = document.getElementById('deviceSelectedStatus');

        if (!list || !search || !hiddenInput) { return; }

        var RECENT_KEY = 'radnet.recent.devices';
        var items = Array.prototype.slice.call(list.querySelectorAll('.dir-item'));
        var byId = {};
        items.forEach(function (it) { byId[it.dataset.id] = it; });

        // Bagikan daftar item ke scope halaman supaya refreshItemStatus()
        // (dipakai hasil ping) bisa menyinkronkan badge yang sama.
        dirItems = items;

        var activeIndex = -1;

        function statusFilter() {
            var on = document.querySelector('.dir-chip.is-active');
            return on ? on.dataset.filterStatus : 'all';
        }

        function visibleItems() {
            return items.filter(function (it) { return it.style.display !== 'none'; });
        }

        function applyFilter() {
            var q = (search.value || '').trim().toLowerCase();
            var status = statusFilter();
            var shown = 0;

            items.forEach(function (it) {
                var hay = [it.dataset.name, it.dataset.ip, it.dataset.pop, it.dataset.type]
                    .join(' ').toLowerCase();
                var ok = (q === '' || hay.indexOf(q) !== -1)
                    && (status === 'all' || it.dataset.status === status);

                it.style.display = ok ? '' : 'none';
                if (ok) { shown++; }
            });

            if (clearBtn) { clearBtn.classList.toggle('hidden', q === ''); }
            if (empty) { empty.classList.toggle('hidden', shown !== 0); }
            activeIndex = -1;
        }

        function highlight(index) {
            var vis = visibleItems();
            if (!vis.length) { return; }
            if (index < 0) { index = 0; }
            if (index >= vis.length) { index = vis.length - 1; }
            vis.forEach(function (it) { it.classList.remove('is-active'); });
            var el = vis[index];
            el.classList.add('is-active');
            activeIndex = index;
            if (el.scrollIntoView) { el.scrollIntoView({ block: 'nearest' }); }
        }

        function iconHtml(it) {
            return '<img src="' + esc(it.dataset.icon) + '" alt="" width="36" height="36">';
        }

        function selectItem(it) {
            if (!it) { return; }

            hiddenInput.value = it.dataset.id;
            if (radnetTarget && it.dataset.ip) { radnetTarget.value = it.dataset.ip; }

            items.forEach(function (x) {
                x.classList.remove('is-selected');
                x.setAttribute('aria-selected', 'false');
            });
            it.classList.add('is-selected');
            it.setAttribute('aria-selected', 'true');

            if (selectedBar) {
                selectedIcon.innerHTML = iconHtml(it);
                selectedName.textContent = it.dataset.name;
                selectedSub.textContent = it.dataset.ip + (it.dataset.pop ? ' · ' + it.dataset.pop : '');
                selectedStatus.innerHTML = badgeHtml(it.dataset.status);
                selectedBar.classList.add('is-visible');
            }

            rememberRecent(it.dataset.id);
        }

        function readRecent() {
            try { return JSON.parse(localStorage.getItem(RECENT_KEY) || '[]'); } catch (e) { return []; }
        }

        function rememberRecent(id) {
            var ids = readRecent().filter(function (x) { return x !== id; });
            ids.unshift(id);
            ids = ids.slice(0, 6);
            try { localStorage.setItem(RECENT_KEY, JSON.stringify(ids)); } catch (e) { /* mode privat */ }
            renderRecent();
        }

        function renderRecent() {
            if (!recentBox || !recentList) { return; }

            var ids = readRecent().filter(function (id) { return byId[id]; });
            if (!ids.length) { recentBox.classList.add('hidden'); return; }

            recentBox.classList.remove('hidden');
            recentList.innerHTML = '';
            ids.forEach(function (id) {
                var it = byId[id];
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'dir-recent-btn';
                btn.textContent = it.dataset.name + ' · ' + it.dataset.ip;
                btn.addEventListener('click', function () { selectItem(it); });
                recentList.appendChild(btn);
            });
        }

        search.addEventListener('input', applyFilter);
        search.addEventListener('focus', applyFilter);

        if (clearBtn) {
            clearBtn.addEventListener('click', function () { search.value = ''; applyFilter(); search.focus(); });
        }

        chips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                chips.forEach(function (c) { c.classList.remove('is-active'); });
                chip.classList.add('is-active');
                applyFilter();
            });
        });

        list.addEventListener('click', function (e) {
            var it = e.target.closest('.dir-item');
            if (it) { selectItem(it); }
        });

        search.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown') { e.preventDefault(); highlight(activeIndex + 1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); highlight(activeIndex - 1); }
            else if (e.key === 'Enter') {
                var vis = visibleItems();
                if (activeIndex >= 0 && vis[activeIndex]) { e.preventDefault(); selectItem(vis[activeIndex]); }
                else if (vis.length === 1) { e.preventDefault(); selectItem(vis[0]); }
            } else if (e.key === 'Escape') { search.value = ''; applyFilter(); }
        });

        renderRecent();
        applyFilter();
    })();

    /* Tabel daftar perangkat, log mentah, dan kotak analisis duplikat
       sudah dihapus dari halaman ini, jadi handler baris tabel serta
       renderer analisis lama ikut dibuang. Ringkasan hasil + saran
       penanganan sekarang hanya ditulis ke kartu hasil pemeriksaan. */

    /* ── Ringkasan hasil + saran penanganan otomatis ─────────── */
    var ASSISTANT_STYLE = {
        danger:  { cls: 'pm-assistant--danger',  badge: 'pm-badge--danger',  label: 'DOWN',    icon: '⛔' },
        warning: { cls: 'pm-assistant--warning', badge: 'pm-badge--warning', label: 'WARNING', icon: '⚠️' },
        ok:      { cls: 'pm-assistant--ok',      badge: 'pm-badge--ok',      label: 'UP',      icon: '✅' }
    };

    function esc(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function fmt(value, suffix) {
        if (value === null || value === undefined || isNaN(value)) { return '—'; }
        return Number(value).toFixed(1).replace('.', ',') + (suffix || '');
    }

    function metricChip(label, value) {
        return '<span class="pm-assistant-metric">' + esc(label) + ': <strong>' + esc(value) + '</strong></span>';
    }

    /* Ubah status analisis ping (up/warning/down) ke level tampilan kartu.
       Dipakai sebagai sumber utama badge supaya kartu hasil tidak mungkin
       berbeda dari status ping yang sesungguhnya. */
    function levelFromAnalysis(analysis) {
        if (!analysis || !analysis.status) { return null; }
        if (analysis.status === 'down') { return 'danger'; }
        if (analysis.status === 'warning') { return 'warning'; }
        if (analysis.status === 'up') { return 'ok'; }
        return null;
    }

    /* Bangun kartu tiga-status dari `analysis` backend saat `assistant`
       tidak ikut terkirim (mis. target ditolak / 422). Dengan begitu kartu
       hasil tetap tampil sebagai UP/WARNING/DOWN, bukan kotak error generik. */
    function assistantFromAnalysis(analysis, fallback) {
        var level = levelFromAnalysis(analysis) || 'danger';
        var label = { danger: 'DOWN', warning: 'WARNING', ok: 'UP' }[level];

        return {
            level: level,
            title: (analysis && analysis.headline) ? analysis.headline : (label + ' — hasil pemeriksaan'),
            message: (analysis && analysis.reason) ? analysis.reason : (fallback || ''),
            actions: (analysis && analysis.recommendation) ? [analysis.recommendation] : [],
            metrics: {},
            status_detail: (analysis && analysis.status_detail) ? analysis.status_detail : null
        };
    }

    function renderAssistant(phase, assistant, meta, detail, level) {
        var el = document.getElementById('aiAssistant');
        var badge = document.getElementById('aiAssistantState');
        if (!el) { return; }

        if (phase === 'running') {
            el.className = 'pm-assistant pm-assistant--running';
            el.innerHTML = '<div class="pm-assistant-title"><span class="pm-assistant-icon">⏳</span>' +
                'Menganalisis hasil pengecekan…</div>' +
                '<div class="pm-assistant-message">Sistem membaca packet loss, latensi, dan status jalur ' +
                'untuk menyusun rekomendasi penanganan.</div>';
            if (badge) { badge.textContent = 'Menganalisis…'; }
            return;
        }

        if (phase !== 'done' || !assistant) {
            el.className = 'pm-assistant pm-assistant--error';
            el.innerHTML = '<div class="pm-assistant-title"><span class="pm-assistant-icon">⚙️</span>' +
                'Pemeriksaan tidak bisa dianalisis</div>' +
                '<div class="pm-assistant-message">' +
                esc(detail || 'Hasil tes tidak sampai ke server, sehingga belum ada ' +
                    'rekomendasi yang bisa diberikan. Jalankan ulang pengecekan.') +
                '</div>';
            if (badge) { badge.textContent = 'GAGAL'; }
            return;
        }

        var style = ASSISTANT_STYLE[level] || ASSISTANT_STYLE[assistant.level] || ASSISTANT_STYLE.ok;
        var m = assistant.metrics || {};
        var html = '';

        html += '<div class="pm-assistant-head">';
        html += '<span class="pm-assistant-title"><span class="pm-assistant-icon">' +
            style.icon + '</span>' + esc(assistant.title) + '</span>';
        html += '<span class="pm-assistant-badge ' + style.badge + '">' + style.label + '</span>';
        html += '</div>';

        // Baris detail status: UP -> "time=12.4ms", WARNING -> penyebabnya
        // ("High Latency (245ms) · Packet Loss"), DOWN -> "Destination Host
        // Unreachable" / "Request Timed Out". Tanpa persentase packet loss
        // supaya indikator tetap bersih.
        if (assistant.status_detail) {
            html += '<div class="pm-assistant-statusdetail">' +
                '<span class="pm-status-code">' + esc(style.label) + '</span>' +
                '<span class="pm-status-value">' + esc(assistant.status_detail) + '</span>' +
                '</div>';
        }

        html += '<div class="pm-assistant-metrics">' +
            metricChip('Latensi rata-rata', fmt(m.latency_ms, ' ms')) +
            metricChip('Jitter', fmt(m.jitter_ms, ' ms')) +
            '</div>';

        html += '<div class="pm-assistant-message">' + esc(assistant.message) + '</div>';

        if (Array.isArray(assistant.actions) && assistant.actions.length) {
            html += '<div class="pm-assistant-actions-title">Langkah Penanganan</div>';
            html += '<ul class="pm-assistant-actions">';
            assistant.actions.forEach(function (action) { html += '<li>' + esc(action) + '</li>'; });
            html += '</ul>';
        }

        if (meta) { html += '<div class="pm-assistant-meta">' + esc(meta) + '</div>'; }

        el.className = 'pm-assistant ' + style.cls;
        el.innerHTML = html;
        if (badge) { badge.textContent = style.label; }
    }

    /* ══════════════════════════════════════════════════════════════
       Ping BERKELANJUTAN (streaming) — baris demi baris seperti terminal.

       Backend mengirim NDJSON: satu objek JSON per baris.
         {"type":"line", ...}     baris keluaran ping mentah
         {"type":"progress", ...} ringkasan live (paket + latency, TANPA %)
         {"type":"summary", ...}  ringkasan akhir (UP / WARNING / DOWN)
         {"type":"error", ...}    gangguan di server
       ============================================================== */
    var PING_RUN_BUTTONS = ['btnPingRadnet', 'btnPingExternal'];
    var MAX_LOG_LINES = 600;

    /* Ambang latency (ms) yang sama dengan DiagnosisAnalyzer::GOOD_LATENCY_MS:
       di atas ini balasan dianggap lambat dan diwarnai kuning/oranye. */
    var PING_LATENCY_WARN_MS = 100;

    var pingRun = {
        active: false,
        stopping: false,
        failed: false,
        id: null,
        target: '',
        status: null,
        controller: null,
        runBtn: null,
        stopBtn: null,
        stopTimer: null,
        hardTimer: null
    };

    function pingLogEl() { return document.getElementById('pingLog'); }

    function setPingPill(text, cls) {
        var pill = document.getElementById('pingLogState');
        if (!pill) { return; }
        pill.textContent = text;
        pill.className = 'pm-pill ' + (cls || 'pm-pill--light');
    }

    function clearPingLog() {
        var log = pingLogEl();
        if (log) { log.innerHTML = ''; }
    }

    function appendPingLog(line, kind) {
        var log = pingLogEl();
        if (!log) { return; }

        var empty = log.querySelector('.pm-log-empty');
        if (empty) { empty.remove(); }

        var row = document.createElement('div');
        row.className = 'pm-log-line' + (kind ? ' pm-log-line--' + kind : '');
        row.textContent = line;
        log.appendChild(row);

        // Ping berkelanjutan bisa menghasilkan ratusan baris: batasi DOM-nya
        // supaya halaman tidak melambat setelah lama berjalan.
        while (log.childElementCount > MAX_LOG_LINES) {
            log.removeChild(log.firstElementChild);
        }

        log.scrollTop = log.scrollHeight;
    }

    /* Warna baris mengikuti isinya, supaya balasan sehat, kegagalan, dan
       baris statistik ping langsung bisa dibedakan seperti di terminal.
       - Balasan ("time=XXms") hijau bila latency wajar, kuning/oranye bila
         latency tinggi atau status WARNING, merah bila status DOWN.
       - Baris paket hilang ("Request timed out for icmp_seq=X") kuning/oranye
         saat WARNING, merah saat DOWN — tidak pernah menampilkan time ms. */
    function classifyPingLine(line, status) {
        if (/(request timed out|timeout) for icmp_seq/i.test(line)) {
            return status === 'down' ? 'bad' : 'warn';
        }

        var latency = /\btime[=:]\s*([\d.]+)\s*ms/i.exec(line);
        if (latency) {
            if (status === 'down') { return 'bad'; }
            if (status === 'warning') { return 'warn'; }
            return parseFloat(latency[1]) > PING_LATENCY_WARN_MS ? 'warn' : 'ok';
        }

        if (/unreachable|no route|timed out|timeout|100% packet loss/i.test(line)) { return 'bad'; }
        if (/^---|statistics/i.test(line)) { return 'head'; }
        if (/^PING |packets transmitted|rtt min\/avg/i.test(line)) { return 'meta'; }
        return '';
    }

    function pingStatusLabel(status) {
        if (status === 'up') { return 'UP'; }
        if (status === 'warning') { return 'WARNING'; }
        if (status === 'down') { return 'DOWN'; }
        return 'PING';
    }

    function applyPingProgress(evt) {
        var parts = [];

        if (typeof evt.sent === 'number') { parts.push(evt.sent + ' terkirim'); }
        if (typeof evt.received === 'number') { parts.push(evt.received + ' dijawab'); }
        if (evt.avg !== null && evt.avg !== undefined) { parts.push('avg ' + fmt(evt.avg, ' ms')); }

        var text = parts.length ? parts.join(' · ') : 'Ping berjalan…';
        var cls = 'pm-pill--light';

        if (evt.status === 'up') { cls = 'pm-pill--success'; }
        else if (evt.status === 'warning') { cls = 'pm-pill--warning'; }
        else if (evt.status === 'down') { cls = 'pm-pill--danger'; }

        if (evt.status) {
            pingRun.status = evt.status;
            text = pingStatusLabel(evt.status) + ' · ' + text;
        }

        setPingPill(text, cls);
    }

    function setStopVisible(stopBtn, visible) {
        if (!stopBtn) { return; }
        if (visible) { stopBtn.classList.remove('hidden'); }
        else { stopBtn.classList.add('hidden'); }
    }

    function endPing() {
        if (!pingRun.active) { return; }

        pingRun.active = false;
        pingRun.stopping = false;

        PING_RUN_BUTTONS.forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) { btn.disabled = false; }
        });

        setStopVisible(pingRun.stopBtn, false);
        if (pingRun.stopBtn) { pingRun.stopBtn.disabled = false; }

        if (pingRun.stopTimer) { clearTimeout(pingRun.stopTimer); }
        if (pingRun.hardTimer) { clearTimeout(pingRun.hardTimer); }

        pingRun.stopTimer = null;
        pingRun.hardTimer = null;
        pingRun.controller = null;
        pingRun.runBtn = null;
        pingRun.stopBtn = null;
        pingRun.id = null;
    }

    function failPing(message) {
        if (!pingRun.active || pingRun.failed) { return; }

        pingRun.failed = true;
        setPingPill('GAGAL', 'pm-pill--danger');
        appendPingLog('!! ' + message, 'bad');
        renderAssistant('error', null, null, message);

        endPing();
    }

    function applyPingSummary(data) {
        if (!pingRun.active) { return; }

        var target = pingRun.target || data.summary || '';
        var meta = 'Sasaran ' + target + (data.checked_at ? ' · pukul ' + data.checked_at : '');

        if (data.analysis && data.analysis.label) {
            meta += ' · hasil: ' + String(data.analysis.label).toUpperCase();
        }
        if (data.device && data.device.name) {
            meta += ' · ' + data.device.name
                + ' (status terpantau: ' + String((data.device.status || '?')).toUpperCase() + ')';
        }

        // Selaraskan badge perangkat di daftar dengan hasil ping terbaru.
        if (data.device && data.device.id && data.analysis) {
            refreshItemStatus(data.device.id, data.analysis.status);
        }

        var analysisLevel = levelFromAnalysis(data.analysis);
        if (data.analysis && data.analysis.status) { pingRun.status = data.analysis.status; }
        var pillCls = 'pm-pill--light';
        var pillText = 'Selesai';

        if (analysisLevel === 'danger') { pillCls = 'pm-pill--danger'; pillText = 'DOWN'; }
        else if (analysisLevel === 'warning') { pillCls = 'pm-pill--warning'; pillText = 'WARNING'; }
        else if (analysisLevel === 'ok') { pillCls = 'pm-pill--success'; pillText = 'UP'; }

        setPingPill(pillText, pillCls);

        var assistant = data.assistant || null;

        if (!assistant && data.analysis && data.analysis.status) {
            assistant = assistantFromAnalysis(data.analysis, data.message);
        }

        renderAssistant('done', assistant, meta, null, analysisLevel);

        endPing();
    }

    function handlePingEvent(evt) {
        if (!evt || !evt.type) { return false; }

        if (evt.type === 'start') {
            pingRun.status = null;
            setPingPill('PING BERJALAN…', 'pm-pill--light');
            return false;
        }

        if (evt.type === 'line') {
            appendPingLog(evt.line, classifyPingLine(evt.line, pingRun.status));
            return false;
        }

        if (evt.type === 'progress') {
            applyPingProgress(evt);
            return false;
        }

        if (evt.type === 'summary') {
            applyPingSummary(evt);
            return true;
        }

        if (evt.type === 'error') {
            failPing(evt.message || 'Pemeriksaan terputus di server.');
            return true;
        }

        return false;
    }

    function startPing(opts) {
        if (pingRun.active) { return; }

        if (!opts.target) {
            alert('Isi target IP / host dulu sebelum memulai ping.');
            return;
        }

        var runBtn = document.getElementById(opts.button);
        var stopBtn = document.getElementById(opts.stopButton);

        pingRun.active = true;
        pingRun.stopping = false;
        pingRun.failed = false;
        pingRun.runBtn = runBtn;
        pingRun.stopBtn = stopBtn;
        pingRun.target = opts.target;
        pingRun.id = 'pm-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 10);
        pingRun.controller = (typeof AbortController !== 'undefined') ? new AbortController() : null;

        PING_RUN_BUTTONS.forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) { btn.disabled = true; }
        });

        setStopVisible(stopBtn, true);
        if (stopBtn) { stopBtn.disabled = false; }

        clearPingLog();
        setPingPill('Memulai…', 'pm-pill--light');
        renderAssistant('running');

        var request = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/x-ndjson',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                target: opts.target,
                device_id: opts.deviceId || null,
                id: pingRun.id
            })
        };

        if (pingRun.controller) { request.signal = pingRun.controller.signal; }

        // Batas keras: apa pun yang terjadi, kontrol dilepas setelah 140 detik
        // supaya tidak ada tombol yang menggantung selamanya.
        pingRun.hardTimer = setTimeout(function () {
            if (pingRun.controller) {
                try { pingRun.controller.abort(); } catch (e) { /* diabaikan */ }
            }
        }, 140000);

        fetch(PING_STREAM_URL, request)
            .then(function (response) {
                // Target ditolak / server membalas JSON biasa (bukan stream).
                if (!response.ok || !response.body || typeof response.body.getReader !== 'function') {
                    return response.json()
                        .catch(function () { return {}; })
                        .then(function (data) {
                            failPing((data && data.message)
                                ? data.message
                                : 'Pemeriksaan gagal dijalankan di server.');
                        });
                }

                var reader = response.body.getReader();
                var decoder = new TextDecoder('utf-8');
                var buffer = '';
                var summarySeen = false;

                function pump() {
                    return reader.read().then(function (chunk) {
                        if (chunk.done) {
                            // Stream tertutup. Kalau ringkasan tidak pernah
                            // datang (mis. server terputus), jangan biarkan
                            // kartu menggantung di status "Menganalisis…".
                            if (!summarySeen && pingRun.active && !pingRun.failed) {
                                failPing('Pemeriksaan berakhir tanpa ringkasan. Coba lagi.');
                            } else {
                                endPing();
                            }
                            return;
                        }

                        buffer += decoder.decode(chunk.value, { stream: true });

                        var idx;

                        while ((idx = buffer.indexOf('\n')) !== -1) {
                            var raw = buffer.slice(0, idx);
                            buffer = buffer.slice(idx + 1);

                            if (raw.trim() === '') { continue; }

                            var evt = null;

                            // Baris yang bukan JSON (mis. peringatan PHP) cukup
                            // dilewati, tidak boleh merusak seluruh stream.
                            try { evt = JSON.parse(raw); } catch (e) { continue; }

                            if (handlePingEvent(evt)) { summarySeen = true; }
                        }

                        return pump();
                    });
                }

                return pump();
            })
            .catch(function (err) {
                var aborted = err && (err.name === 'AbortError' || err.code === 20);

                if (aborted) { endPing(); }
                else { failPing('Tidak dapat menghubungi server. Periksa koneksi lalu coba lagi.'); }
            });
    }

    /* Hentikan ping: kabari server lewat file penanda, lalu TUNGGU ringkasan
       akhirnya datang. Koneksi sengaja tidak langsung diputus supaya kartu
       hasil tetap terisi status + rekomendasi. */
    function stopPing() {
        if (!pingRun.active || pingRun.stopping) { return; }

        pingRun.stopping = true;
        if (pingRun.stopBtn) { pingRun.stopBtn.disabled = true; }
        setPingPill('Menghentikan…', 'pm-pill--warning');

        if (pingRun.id) {
            fetch(PING_STOP_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF
                },
                body: JSON.stringify({ id: pingRun.id })
            }).catch(function () { /* ringkasan tetap menyusul dari stream */ });
        }

        pingRun.stopTimer = setTimeout(function () {
            if (pingRun.controller) {
                try { pingRun.controller.abort(); } catch (e) { /* diabaikan */ }
            }
        }, 6000);
    }

    /* Perangkat terpilih → ping berkelanjutan. */
    var btnRadnet = document.getElementById('btnPingRadnet');
    if (btnRadnet) {
        btnRadnet.addEventListener('click', function () {
            var sel = document.getElementById('deviceSelect');
            startPing({
                target: document.getElementById('radnetTarget').value.trim(),
                deviceId: sel && sel.value ? sel.value : null,
                button: 'btnPingRadnet',
                stopButton: 'btnPingRadnetStop'
            });
        });
    }

    var btnRadnetStop = document.getElementById('btnPingRadnetStop');
    if (btnRadnetStop) { btnRadnetStop.addEventListener('click', stopPing); }

    var btnExternal = document.getElementById('btnPingExternal');
    if (btnExternal) {
        btnExternal.addEventListener('click', function () {
            startPing({
                target: document.getElementById('externalTarget').value.trim(),
                deviceId: null,
                button: 'btnPingExternal',
                stopButton: 'btnPingExternalStop'
            });
        });
    }

    var btnExternalStop = document.getElementById('btnPingExternalStop');
    if (btnExternalStop) { btnExternalStop.addEventListener('click', stopPing); }

    /* ── Bersihkan semua riwayat (khusus Super Admin) ─────────── */
    var purgeBtn = document.querySelector('.js-purge-all');
    if (purgeBtn) {
        purgeBtn.addEventListener('click', function () {
            if (!confirm('Bersihkan PERMANEN seluruh riwayat hasil pemeriksaan target?\n\n' +
                '• Log Monitoring, Arsip Log, dan Riwayat Ping\n' +
                '• Ringkasan latency tiap device\n' +
                '• Cache hasil pemeriksaan tiap target\n\n' +
                'Tidak bisa dibatalkan. Yang TIDAK terhapus: daftar perangkat, topologi, catatan, notifikasi, dan audit.')) {
                return;
            }

            purgeBtn.disabled = true;
            fetch(@json(route('admin.tools.purge-all')), {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                purgeBtn.disabled = false;
                alert((data && data.message) ? data.message : 'Selesai.');
            })
            .catch(function () {
                purgeBtn.disabled = false;
                alert('Tidak dapat menghubungi server.');
            });
        });
    }
})();
</script>
@endpush
