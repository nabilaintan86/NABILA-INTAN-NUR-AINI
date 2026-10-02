@extends('layouts.admin')

@section('title', 'Network Tools · RADNET NOC')

{{-- Komponen tab pemantauan ping (header kotak biru, tombol, terminal) ikut
     dimuat di halaman ini. CSS-nya page-scoped, tanpa preflight, jadi tidak
     menimpa layout yang sudah ada. --}}
@push('styles')
@vite(['resources/css/ping-monitor.css'])
<style>
    /* ══ 1. KARTU / BAGIAN HALAMAN ══════════════════════ */
    /* Jarak antar bagian & radius outer surface mengikuti standar layout global
       (.admin-page > * + * dan daftar surface di layout admin), jadi di sini
       cukup isi isi panelnya. */
    .tools-panel {
        background: #fff;
        border: 1px solid #e5e9f0;
        overflow: hidden;
    }
    .tools-panel-head {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 15px 18px;
        background: #fbfcfe;
        border-bottom: 1px solid #eef1f6;
    }
    .tools-panel-no {
        width: 34px; height: 34px;
        flex-shrink: 0;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        background: #1d4ed8; color: #fff;
        font-size: 16px; font-weight: 800;
    }
    .tools-panel-title {
        margin: 0;
        font-size: 21px; font-weight: 800; color: #16223c;
        line-height: 1.3;
    }
    .tools-panel-tag {
        display: inline-block;
        font-size: 12.5px; font-weight: 800; letter-spacing: .3px;
        text-transform: uppercase;
        padding: 3px 9px; border-radius: 999px;
        background: #eef2ff; color: #4338ca;
        vertical-align: 2px;
    }
    .tools-panel-sub {
        margin: 3px 0 0;
        font-size: 16px; color: #64748b; line-height: 1.5;
    }
    .tools-panel-body { padding: 16px 18px; }
    .tools-panel-badge { margin-left: auto; flex-shrink: 0; }

    /* CATATAN — tidak ada lagi bar/badge ringkasan statistik di halaman ini.
       Kemarin masih ada strip "Total Perangkat · UP · WARNING · DOWN" di
       bagian atas, sekarang DIHAPUS total sesuai permintaan: angkanya
       mengulang apa yang sudah terlihat di daftar perangkat di bawah
       (jumlah baris + badge status tiap baris), jadi strip itu hanya
       menambah(layer yang sama dua kali).

       Konsekuensinya halaman ini tidak lagi mengirim $summary maupun
       $areaSummary ke view. Jumlah dan kondisi perangkat tetap punya
       SATU sumber, yaitu DeviceCsvService (tabel device yang sudah
       disinkronkan dengan devices.csv) — lihat ToolsController::index(). */

    /* Jarak antar-bagian.
       Setiap <section class="tools-section"> adalah satu bagian tersendiri,
       jadi harus ada jarak jelas di antaranya. Tanpa aturan ini section
       hanya jadi blok biasa yang menempel satu sama lain, dan panelnya
       terlihat bertumpuk tanpa pemisah. */
    .tools-section { margin-bottom: 16px; }
    .tools-section:last-child { margin-bottom: 0; }

    /* Hasil pemeriksaan: Lives di dalam bagian Pemeriksaan, bukan panel
       sendiri, jadi memakai gaya sub-bagian supaya jelas "bagian dari"
       dan bukan Surface baru yang menumpuk. */
    .tools-subsection {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e6ecf5;
    }
    .tools-subsection-head {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 12px; flex-wrap: wrap;
    }
    .tools-subsection-title {
        margin: 0; font-size: 18px; font-weight: 800; color: #16223c;
    }

    /* ══ 1c. TIGA PILIHAN TARGET (SUSUNAN PANEL KANAN) ═══
       Panel kanan adalah SATU panel berisi tiga pilihan berurutan. Setiap
       pilihan memakai bagian yang sama (kepala + isi) sehingga urutannya
       terbaca sekilas: 1 Lokal → 2 Perangkat → 3 target dari luar.

       Dulu tiap pilihan dibungkus panelnya sendiri, sehingga bagian paling
       atas tampil sebagai kotak "Server Radnet / NOC (LOKAL)" yang isinya
       mengulang tombol di bawahnya. Sekarang tidak ada lagi kotak terpisah:
       kepala bagian hanya memberi nama pilihan, isinya satu permukaan. */
    .tools-tier { padding-bottom: 16px; border-bottom: 1px solid #eef1f6; }

    /* Penanda eksplisit (bukan :last-of-type) karena di bawah pilihan terakhir
       masih ada sub-bagian hasil, jadi posisi anak tidak bisa dipakai. */
    .tools-tier--last { padding-bottom: 0; border-bottom: 0; }
    .tools-tier-head {
        display: flex; align-items: flex-start; gap: 10px; margin-bottom: 11px;
    }
    .tools-tier-no {
        flex-shrink: 0;
        font-size: 14px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase;
        padding: 4px 10px; border-radius: 999px;
        background: #eef2ff; color: #4338ca;
    }
    .tools-tier-txt { min-width: 0; }
    .tools-tier-title {
        margin: 0; font-size: 20px; font-weight: 800; color: #16223c; line-height: 1.35;
    }
    .tools-tier-sub {
        margin: 3px 0 0; font-size: 16px; color: #64748b; line-height: 1.5;
    }

    /* Isi setiap pilihan menyentuh bagiannya sendiri, jadi jarak sisa
       (margin-bottom) dari kartu di dalam sudah ditangani .tools-tier. */
    .tools-tier > .tool-local-option,
    .tools-tier > .tool-target { margin-bottom: 0; }

    /* ══ 2. DAFTAR PERANGKAT RADNET ═════════════════════ */
    /* SELURUH pembungkus kartu panel kiri DIHAPUS total: tidak ada lagi
       <div class="tools-panel tools-list-panel"> maupun tools-panel-body
       di sisi kiri. Kolom dimulai langsung dari baris pencarian, lalu
       filter status, lalu daftar 115 perangkat — bersih tanpa kotak putih
       atau border yang mengapit. Warna hijau tetap hidup di ikon perangkat
       (.device-ic) sebagai penanda visual daftar perangkat. */

    .tool-search { position: relative; }
    .tool-search .icon {
        position: absolute; left: 11px; top: 50%;
        transform: translateY(-50%);
        font-size: 15px; color: #94a3b8;
        pointer-events: none;
    }
    .tool-search .form-control {
        padding-left: 38px;
        font-size: 18px;
        border-color: #e2e8f0;
        border-radius: 9px;
        box-shadow: none;
    }
    .tool-search .form-control:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
    }
    .tool-count { font-size: 17px; color: #475569; font-weight: 700; }

    .tool-filters { display: flex; gap: 7px; flex-wrap: wrap; }
    .tool-filter-btn {
        display: inline-flex; align-items: center; gap: 6px;
        border: 1px solid #e2e8f0; background: #fff; color: #475569;
        font-size: 16px; font-weight: 800; letter-spacing: .3px;
        padding: 7px 13px; border-radius: 999px; cursor: pointer; line-height: 1;
        transition: border-color .15s, background .15s, color .15s;
    }
    /* Warna tiap filter selalu terlihat, bukan hanya saat aktif, supaya
       makna warna (biru = semua, hijau = up, kuning = wrn, merah = down)
       bisa dibaca sekilas. */
    .tool-filter-btn:hover { filter: brightness(.97); }
    .tool-filter-btn[data-status="all"]    { background: #e0f2fe; color: #0369a1; border-color: #7dd3fc; }
    .tool-filter-btn[data-status="up"]     { background: #dcfce7; color: #15803d; border-color: #86efac; }
    .tool-filter-btn[data-status="warning"] { background: #fef9c3; color: #a16207; border-color: #fde047; }
    .tool-filter-btn[data-status="down"]   { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .tool-filter-btn.active { box-shadow: 0 0 0 3px rgba(13, 148, 136, .16); }
    .tool-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }

    /* Daftar perangkat tanpa card pembungkus: baris-baris duduk langsung di
       kolom, rata dan menyatu dengan pencarian & filter di atasnya. */
    .device-list {
        display: flex; flex-direction: column; gap: 6px;
        max-height: 520px; min-height: 200px;
        overflow-y: auto; padding-right: 4px;
    }
    .device-list::-webkit-scrollbar { width: 8px; }
    .device-list::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .device-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .device-list::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    .device-item {
        display: flex; align-items: center; gap: 11px;
        width: 100%; text-align: left;
        padding: 11px 13px;
        border: 1px solid #eef2f7; border-radius: 11px;
        background: #f8fafc; cursor: pointer;
        transition: border-color .15s, background .15s, box-shadow .15s;
    }
    .device-item:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .device-item.active {
        background: #f0fdfa; border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
    }
    /* Ikon perangkat selalu dalam kotak hijau: penanda visual devices, bukan
       status. Statusnya dibaca dari badge di kanan. Ikonnya sendiri mengikuti
       tipe perangkat (Router / Switch / EPON / OLT / Perangkat). */
    .device-ic {
        width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        color: #d7f5ef; font-size: 19px;
    }
    .device-item-txt { min-width: 0; flex: 1 1 auto; }
    .device-name {
        display: block; font-size: 19px; font-weight: 700; color: #0f172a;
        line-height: 1.35;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .device-ip {
        display: block; font-size: 16px; color: #475569; margin-top: 2px;
        font-family: 'Consolas', 'Menlo', monospace;
    }
    /* Tipe perangkat (Router / Switch / EPON / OLT / Perangkat). Baris ketiga
       ini yang memberitahu arti ikon di sebelahnya, jadi teksnya sengaja
       dibuat paling kecil dari tiga baris nama. */
    .device-type {
        display: block; font-size: 14.5px; color: #94a3b8; margin-top: 2px;
        font-weight: 600; letter-spacing: .2px;
    }
    .device-empty {
        text-align: center; color: #64748b;
        padding: 30px 10px; font-size: 16.5px; line-height: 1.6;
    }

    .status-badge {
        flex-shrink: 0;
        font-size: 15.5px; font-weight: 800; letter-spacing: .3px;
        padding: 6px 13px; border-radius: 999px;
    }
    .status-up      { background: #dcfce7; color: #15803d; }
    .status-down    { background: #fee2e2; color: #dc2626; }
    .status-warning { background: #fef9c3; color: #a16207; }

    /* ══ 3. KARTU TARGET TERPILIH ═══════════════════════ */
    /* Pilihan 1 — Server Radnet (Server Lokal): paling atas dan paling
       menonjol karena ini target yang paling sering dipakai. Jarak bawahnya
       sudah ditangani .tools-tier, jadi di sini hanya margin sementara
       untuk jaga-jaga kalau nanti dipakai di luar tier. */
    .tool-local-option {
        display: flex; align-items: center; gap: 12px;
        width: 100%; text-align: left;
        padding: 14px 15px;
        border: 1px solid #e2e8f0; border-radius: 13px;
        background: #fff; cursor: pointer;
        margin-bottom: 14px;
        transition: border-color .15s, background .15s, box-shadow .15s;
    }
    .tool-local-option:hover { border-color: #93c5fd; background: #f8fbff; }
    .tool-local-option.is-active {
        background: #eff6ff; border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, .12);
    }
    .tool-local-ic {
        width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #1d4ed8; color: #fff; font-size: 24px;
    }
    .tool-local-txt { min-width: 0; flex: 1 1 auto; }
    .tool-local-name {
        display: block;
        font-size: 20px; font-weight: 800; color: #16223c;
    }
    .tool-local-hint {
        display: block; font-size: 16px; color: #64748b; margin-top: 3px; line-height: 1.5;
    }
    .tool-local-check {
        flex-shrink: 0;
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #dcfce7; color: #15803d; font-size: 16px;
        opacity: 0; transition: opacity .15s;
    }
    .tool-local-option.is-active .tool-local-check { opacity: 1; }

    /* Pilihan 3 — Target dari luar: dipisah jelas dari target internal, dan
       memakai garis putus-putus supaya terbaca "bukan bagian dari sistem". */
    .tool-outside {
        margin-top: 16px;
        padding: 14px 15px;
        border: 1px dashed #dbe3ee;
        border-radius: 12px;
        background: #fbfcfe;
    }
    .tool-outside-head {
        display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px;
    }
    .tool-outside-head .icon { font-size: 18px; color: #6d28d9; margin-top: 1px; }
    .tool-outside-title {
        font-size: 17px; font-weight: 800; color: #16223c; line-height: 1.4;
    }
    .tool-outside-sub {
        margin: 2px 0 0; font-size: 14.5px; color: #64748b; line-height: 1.5;
    }

    .tool-target {
        border: 1px solid #e5e9f0; border-radius: 12px;
        background: #f8fafc; padding: 13px 14px; margin-bottom: 14px;
    }
    /* Pemilih langsung semua perangkat Radnet (Pilihan 2). Tiap opsi membawa
       nama, alamat, status, dan tipe ikon dari tabel device (sinkron dengan
       devices.csv), sehingga 115 perangkat bisa dipilih sebagai target tanpa
       perlu mengingat-nghaf vig nama di panel kiri. */
    .tool-picker { margin: -2px 0 14px; }
    .tool-picker .tool-field-label { font-size: 15.5px; }
    .tool-picker .form-select {
        font-size: 17px; padding: 11px 13px; border-radius: 10px;
        border-color: #cbd5e1; box-shadow: none; background-color: #fff;
    }
    .tool-target.is-empty { border-style: dashed; background: #fff; }
    .tool-target-top { display: flex; align-items: center; gap: 11px; }
    .tool-target-ic {
        width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: #1d4ed8; color: #fff; font-size: 21px;
    }
    .tool-target.is-empty .tool-target-ic { background: #e2e8f0; color: #94a3b8; }
    .tool-target-txt { min-width: 0; flex: 1 1 auto; }
    .tool-target-label {
        font-size: 14px; font-weight: 800; letter-spacing: .6px;
        text-transform: uppercase; color: #64748b;
    }
    .tool-target-name {
        font-size: 21px; font-weight: 800; color: #16223c;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .tool-target-ip {
        font-size: 17px; color: #475569;
        font-family: 'Consolas', 'Menlo', monospace;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .tool-target-meta {
        display: flex; gap: 18px; flex-wrap: wrap;
        margin-top: 11px; padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        font-size: 16px; color: #475569;
    }
    .tool-target-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .tool-target-meta b { color: #16223c; }

    /* ══ 4. TOMBOL AKSI + PANDUAN ═══════════════════════ */
    .tool-actions {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 9px;
    }
    .tool-btn {
        display: flex; align-items: center; justify-content: center; gap: 7px;
        padding: 15px 10px;
        border: 0; border-radius: 10px;
        color: #fff; font-size: 18px; font-weight: 700;
        cursor: pointer;
        transition: filter .15s, transform .05s, opacity .15s;
    }
    .tool-btn:hover  { filter: brightness(.94); }
    .tool-btn:active { transform: translateY(1px); }
    .tool-btn:disabled { opacity: .5; cursor: not-allowed; }
    .tool-btn--ping   { background: #059669; }
    .tool-btn--trace  { background: #6d28d9; }
    .tool-btn--lookup { background: #2563eb; }
    .tool-btn--scan   { background: #dc2626; }

    .tool-guide {
        list-style: none; margin: 13px 0 0; padding: 12px 0 0;
        border-top: 1px dashed #e2e8f0;
        display: grid; gap: 7px;
    }
    .tool-guide-item {
        display: flex; align-items: center; gap: 9px;
        font-size: 16px; line-height: 1.5; color: #475569;
    }
    .tool-guide-text { min-width: 0; }
    .tool-guide-name {
        display: block; font-weight: 800; color: #16223c; margin-bottom: 1px;
    }
    .tool-guide-color {
        font-style: normal; font-size: 12px; font-weight: 800;
        letter-spacing: .3px; text-transform: uppercase;
        padding: 2px 8px; border-radius: 999px;
        vertical-align: 1px;
    }
    .tool-guide-color--ping   { background: #d1fae5; color: #047857; }
    .tool-guide-color--trace  { background: #ede9fe; color: #6d28d9; }
    .tool-guide-color--lookup { background: #dbeafe; color: #1d4ed8; }
    .tool-guide-color--scan   { background: #fee2e2; color: #b91c1c; }
    .tool-guide-dot {
        width: 8px; height: 8px; border-radius: 50%;
        margin-top: 5px; flex-shrink: 0;
    }
    .tool-guide-dot--ping   { background: #059669; }
    .tool-guide-dot--trace  { background: #6d28d9; }
    .tool-guide-dot--lookup { background: #2563eb; }
    .tool-guide-dot--scan   { background: #dc2626; }

    /* ══ 5. INPUT TARGET BEBAS ══════════════════════════ */
    .tool-input {
        font-size: 16.5px; border-color: #e2e8f0;
        border-radius: 10px; box-shadow: none;
        font-family: 'Consolas', 'Menlo', monospace;
    }
    .tool-input:focus {
        border-color: #1d4ed8;
        box-shadow: 0 0 0 3px rgba(29, 78, 216, .12);
    }
    .tool-input-group .input-group-text {
        background: #f1f5f9; border-color: #e2e8f0;
        color: #64748b; font-size: 16px;
    }
    .tool-hint {
        font-size: 14.5px; color: #64748b; margin: 7px 0 13px; line-height: 1.5;
    }

    /* ══ 6. PARAMETER PEMERIKSAAN (OPSIONAL) ══════════════ */
    /* Blok ini duduk DI DALAM panel Bagian 2, jadi digambar sebagai kotak
       sekunder (bukan panel utama) agar tidak membingungkan. */
    .tools-advanced {
        margin-top: 14px;
        border: 1px dashed #dbe3ee;
        border-radius: 11px;
        background: #fbfcfe;
    }
    .tools-advanced > summary {
        list-style: none;
        cursor: pointer;
        display: flex; align-items: center; gap: 9px;
        padding: 12px 14px;
        font-size: 15px; font-weight: 700; color: #475569;
    }
    .tools-advanced > summary::-webkit-details-marker { display: none; }
    .tools-advanced > summary::after {
        content: '';
        margin-left: auto;
        border: 5px solid transparent;
        border-top-color: #94a3b8;
        transform: translateY(2px);
        transition: transform .2s;
    }
    .tools-advanced[open] > summary::after { transform: rotate(180deg) translateY(-2px); }
    .tools-advanced > summary .icon { font-size: 17px; color: #94a3b8; }
    .tools-advanced-hint {
        font-weight: 600; color: #94a3b8;
    }
    .tools-advanced-body { padding: 0 13px 14px; }
    .tool-field-label {
        display: block;
        font-size: 13.5px; font-weight: 700; color: #334155;
        margin-bottom: 5px;
    }
    .tool-field-note { font-size: 13.5px; color: #94a3b8; margin-top: 4px; }
    .tools-advanced .form-control,
    .tools-advanced .form-select {
        font-size: 15.5px; border-color: #e2e8f0; border-radius: 9px; box-shadow: none;
    }

    /* ══ 7. HASIL PEMERIKSAAN ═══════════════════════════ */
    .tools-result { display: grid; gap: 12px; }
    .tools-result-empty {
        text-align: center; color: #64748b;
        padding: 34px 14px; font-size: 15.5px; line-height: 1.7;
    }
    .tools-result-empty .icon {
        display: block; font-size: 30px; opacity: .35; margin-bottom: 8px;
    }

    .result {
        background: #fff;
        border: 1px solid #e5e9f0;
        border-left: 4px solid #94a3b8;
        overflow: hidden;
    }
    .result--up      { border-left-color: #10b981; }
    .result--warning { border-left-color: #f59e0b; }
    .result--down,
    .result--error   { border-left-color: #ef4444; }
    .result--info    { border-left-color: #3b82f6; }

    .result-head {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 15px;
        background: #f8fafc;
        border-bottom: 1px solid #eef1f6;
    }
    .result-title {
        margin: 0; font-size: 18px; font-weight: 800; color: #16223c; line-height: 1.35;
    }
    .result-badge {
        display: inline-flex; align-items: center; gap: 5px;
        flex-shrink: 0;
        font-size: 13px; font-weight: 800; letter-spacing: .4px;
        padding: 4px 9px; border-radius: 999px;
    }
    .result-badge--up      { background: #dcfce7; color: #15803d; }
    .result-badge--warning { background: #fef9c3; color: #a16207; }
    .result-badge--down,
    .result-badge--error   { background: #fee2e2; color: #b91c1c; }
    .result-badge--info    { background: #dbeafe; color: #1d4ed8; }

    .result-body { padding: 14px 15px; }

    /* Bar waktu + durasi: selalu muncul di setiap hasil supaya pengguna tahu
       persis kapan diperiksa dan berapa lama server menunggu. */
    .result-when {
        display: flex; align-items: center; flex-wrap: wrap; gap: 6px 14px;
        padding: 0 15px 11px;
        background: #f8fafc;
        border-bottom: 1px solid #eef1f6;
        font-size: 14px; color: #64748b;
    }
    .result-when-item { display: inline-flex; align-items: center; gap: 5px; }
    .result-when-item .icon { font-size: 15px; color: #94a3b8; }
    .result-when-item b { color: #16223c; font-weight: 800; }
    .result-when-item.is-target b { font-family: 'Consolas', 'Menlo', monospace; }

    .result-text {
        margin: 0; font-size: 15.5px; line-height: 1.65; color: #334155;
    }
    .result-text + .result-text { margin-top: 7px; }

    .result-metrics {
        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 9px; margin-top: 13px;
    }
    .metric {
        background: #f8fafc; border: 1px solid #eef1f6;
        border-radius: 10px; padding: 9px 11px;
    }
    .metric-label {
        font-size: 12px; font-weight: 800; letter-spacing: .4px;
        text-transform: uppercase; color: #64748b;
    }
    .metric-value {
        font-size: 16px; font-weight: 800; color: #16223c; margin-top: 2px;
    }
    .metric-value small { font-size: 14px; font-weight: 600; color: #64748b; }

    .result-list { margin-top: 13px; }
    .result-row {
        display: flex; align-items: baseline; justify-content: space-between; gap: 14px;
        padding: 7px 0;
        border-bottom: 1px dashed #eef1f6;
        font-size: 15px; color: #334155;
    }
    .result-row:last-child { border-bottom: 0; }
    .result-row-key { flex-shrink: 0; font-weight: 700; color: #16223c; }
    .result-row-val {
        text-align: right; min-width: 0; word-break: break-word;
        font-family: 'Consolas', 'Menlo', monospace; font-size: 14.5px;
    }
    .result-row-val.is-plain { font-family: inherit; font-size: 15px; }
    .result-row.is-dim { color: #94a3b8; }

    .result-note {
        margin-top: 13px;
        background: #f8fafc; border: 1px solid #eef1f6; border-radius: 9px;
        padding: 9px 11px;
        font-size: 14px; line-height: 1.6; color: #475569;
    }
    .result-note strong { color: #16223c; }

    .result-list-head {
        margin: 15px 0 6px;
        font-size: 13px; font-weight: 800; letter-spacing: .4px;
        text-transform: uppercase; color: #64748b;
    }

    /* ══ 7b. ANALISIS STATUS / ALASAN (ALA WHATSUP GOLD) ══
       Kotak ini yang menjawab pertanyaan paling penting dari hasil
       pemeriksaan: "kenapa statusnya begini?". Warnanya mengikuti status,
       sehingga kesimpulannya terbaca sebelum kalimatnya dibaca. */
    .analysis {
        margin-top: 14px;
        border: 1px solid #e5e9f0;
        border-left: 4px solid #94a3b8;
        border-radius: 12px;
        background: #f8fafc;
        overflow: hidden;
    }
    .analysis--up      { border-left-color: #10b981; background: #f2fdf7; border-color: #d1fae5; }
    .analysis--warning { border-left-color: #f59e0b; background: #fffdf5; border-color: #fde68a; }
    .analysis--down,
    .analysis--error   { border-left-color: #ef4444; background: #fef6f6; border-color: #fecaca; }
    .analysis--info    { border-left-color: #3b82f6; background: #f5f9ff; border-color: #bfdbfe; }

    .analysis-head {
        display: flex; align-items: center; gap: 9px; flex-wrap: wrap;
        padding: 11px 14px;
        border-bottom: 1px solid rgba(148, 163, 184, .22);
    }
    .analysis-title {
        font-size: 13px; font-weight: 800; letter-spacing: .6px;
        text-transform: uppercase; color: #64748b;
    }
    .analysis-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 13px; font-weight: 800; letter-spacing: .4px;
        padding: 3px 9px; border-radius: 999px;
    }
    .analysis-badge--up      { background: #dcfce7; color: #15803d; }
    .analysis-badge--warning { background: #fef9c3; color: #a16207; }
    .analysis-badge--down,
    .analysis-badge--error   { background: #fee2e2; color: #b91c1c; }
    .analysis-badge--info    { background: #dbeafe; color: #1d4ed8; }

    .analysis-headline {
        margin: 0;
        font-size: 16px; font-weight: 800; color: #16223c; line-height: 1.45;
    }
    .analysis-body { padding: 12px 14px 14px; }

    .analysis-reason {
        display: flex; align-items: flex-start; gap: 9px;
        font-size: 15.5px; line-height: 1.7; color: #334155;
    }
    .analysis-reason .icon { font-size: 18px; flex-shrink: 0; margin-top: 2px; }
    .analysis--up      .analysis-reason .icon { color: #16a34a; }
    .analysis--warning .analysis-reason .icon { color: #d97706; }
    .analysis--down,
    .analysis--error   .analysis-reason .icon { color: #dc2626; }
    .analysis--info    .analysis-reason .icon { color: #2563eb; }

    .analysis-facts {
        display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 16px; margin-top: 12px;
    }
    .analysis-fact {
        display: flex; align-items: baseline; justify-content: space-between; gap: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dashed rgba(148, 163, 184, .3);
        font-size: 14.5px;
    }
    .analysis-fact-key { color: #64748b; }
    .analysis-fact-val {
        font-weight: 800; color: #16223c; text-align: right; min-width: 0;
        word-break: break-word;
    }

    .analysis-reco {
        display: flex; align-items: flex-start; gap: 9px;
        margin-top: 12px; padding: 10px 12px;
        background: rgba(255, 255, 255, .75);
        border: 1px solid rgba(148, 163, 184, .25);
        border-radius: 10px;
        font-size: 14.8px; line-height: 1.65; color: #475569;
    }
    .analysis-reco .icon { font-size: 17px; color: #0d9488; flex-shrink: 0; margin-top: 1px; }
    .analysis-reco b { color: #16223c; }

    /* ══ 7c. LOG TERMINAL MENTAH ══
       Log mentah tetap tersedia sebagai bukti, tapi disembunyikan di balik
       <details> supaya ringkasan analyze yang jadi focus utama. */
    .result-log {
        margin-top: 12px;
        border: 1px solid #e5e9f0; border-radius: 11px;
        background: #fff; overflow: hidden;
    }
    .result-log > summary {
        list-style: none; cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        padding: 9px 13px;
        background: #f8fafc;
        font-size: 14.8px; font-weight: 800; color: #475569;
    }
    .result-log > summary::-webkit-details-marker { display: none; }
    .result-log > summary .icon { font-size: 17px; color: #94a3b8; }
    .result-log > summary::after {
        content: '';
        margin-left: auto;
        border: 5px solid transparent;
        border-top-color: #94a3b8;
        transform: translateY(2px);
        transition: transform .2s;
    }
    .result-log[open] > summary::after { transform: rotate(180deg) translateY(-2px); }
    .result-log-hint {
        font-weight: 600; color: #94a3b8; font-size: 13px;
    }
    .result-log-body {
        margin: 0; padding: 12px 14px;
        border-top: 1px solid #eef1f6;
        background: #0f172a; color: #cbd5f5;
        font-family: 'Consolas', 'Menlo', monospace;
        font-size: 14.8px; line-height: 1.65;
        white-space: pre-wrap; word-break: break-word;
        max-height: 340px; overflow: auto;
    }
    .result-log-body:empty::before {
        content: 'Server tidak mengeluarkan log untuk pemeriksaan ini.';
        color: #64748b;
    }

    /* ══ 7d. SOROT KOTAK TARGET SAAT DISINKRONKAN ══
        memberitahu pengguna bahwa klik di panel kiri benar-benar sampai ke
        panel kanan. */
    @keyframes targetSyncFlash {
        0%   { box-shadow: 0 0 0 0 rgba(13, 148, 136, .55); }
        100% { box-shadow: 0 0 0 14px rgba(13, 148, 136, 0); }
    }
    .tool-target.is-synced { animation: targetSyncFlash .85s ease-out; }

    /* ══ 8. RESPONSIF ═══════════════════════════════════ */
    @media (max-width: 991.98px) {
        .device-list { max-height: 300px; }
    }
    @media (max-width: 767.98px) {
        .tool-actions { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .result-metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 575.98px) {
        .result-metrics { grid-template-columns: minmax(0, 1fr); }
        .analysis-facts { grid-template-columns: minmax(0, 1fr); }
    }
</style>
@endpush

@section('content')
    @php
        $totalDevices   = $devices->count();
    @endphp

    <x-admin.page-head
        title="Network Tools"
        subtitle="Cek perangkat & jaringan Radnet. Pilih target, lalu tekan tombolnya." />

    {{-- ══════════════════════════════════════════════════════════════════
         PEMANTAUAN PING — TAB RADNET / LUAR / RINGKASAN
         ══════════════════════════════════════════════════════════════════
         Tampilan ini sebelumnya berdiri sendiri di /admin/ping-monitor,
         sekarang sudah pindah ke sini dan aktif sepenuhnya: header kotak
         biru ala WhatsUp Gold, terminal log per paket, dan kotak analisis
         bahasa sehari-hari untuk atasan.

         Sengaja diletakkan paling atas supaya kondisi server yang
         dipantau terlihat di halaman yang sama dengan daftar perangkat
         dan alat diagnosanya.

         Tombol "Return to Tools" yang dulu ada tidak ikut, karena halaman
         ini sudah Network Tools: tombol tersebut hanya akan menaut ke
         halaman yang sedang dibuka.
         ══════════════════════════════════════════════════════════════════ --}}
    <section aria-labelledby="pmHeading">
        <h2 id="pmHeading" class="visually-hidden">Pemantauan Ping Server</h2>

        @include('admin.ping-monitor._tabs')
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         PEMISAH ANTARA TAB PEMANTAUAN PING DAN ALAT DIAGNOSA DI BAWAHNYA
         ══════════════════════════════════════════════════════════════════ --}}
    <div class="border-t border-slate-800" role="presentation"></div>

    {{-- ══════════════════════════════════════════════════════════════════
         RINGKASAN STATISTIK — SUDAH DIHAPUS TOTAL
         ══════════════════════════════════════════════════════════════════
         Di titik ini dulu ada satu baris ringkasan ("115 Perangkat Radnet"
         plus chip UP / WARNING / DOWN) dan sebelumnya juga empat kartu KPI
         besar serta tabel sebaran per Area/POP. Semuanya DIHAPUS sesuai
         permintaan supaya bagian atas halaman benar-benar bersih: angkanya
         hanya mengulang apa yang sudah terlihat di daftar perangkat di
         bawah — jumlah baris pada deviceCountHint dan badge status
         UP / WARNING / DOWN pada tiap baris.

         ToolsController::index() karena itu tidak lagi mengirim $summary
         maupun $areaSummary ke view ini, dan tidak ada lagi kelas CSS
         .sum-strip / .sum-chip di stylesheet halaman.

         Angka total TETAP bisa dibaca di satu tempat saja yang jujur:
         DeviceCsvService::officialSummary() (tabel `device`, yang sudah
         disinkronkan dengan devices.csv), dipakai Dashboard, Monitoring,
         DHCP Lease, dan Log — bukan dihitung ulang di halaman ini.
    --}}

    {{-- Satu-satunya penaga bila view ini someday dirender tanpa
         $analysisLimits: nilai default-nya nol, lalu dipakai JavaScript
         sebagai cadangan kalau respons JSON datang tanpa field 'analysis'. --}}
    @php
        $analysisLimits = $analysisLimits ?? [];
    @endphp

    <form id="networkToolForm" autocomplete="off">

        <input type="hidden" name="target" id="toolTarget">
        <input type="hidden" name="device_id" id="toolDeviceId">
        <input type="hidden" name="action" id="toolAction" value="ping">

        <div class="row g-4">

        {{-- ══════════════ PANEL KIRI · PERANGKAT / ROUTER ══════════════
             Kolom kiri khusus perangkat internal: pencarian, filter status,
             lalu daftar perangkat. Klik satu baris untuk menjadikannya target.

             ENTIRE kartu pembungkus panel kiri DIHAPUS (tidak tersisa): kolom
             dimulai langsung dari baris pencarian, lalu filter status, lalu
             daftar 115 perangkat — tanpa kotak putih/berborder yang mengapit.
             Pencarian duduk paling atas secara bersih dan rapi.

             Catatan: tidak ada baris yang ditandai `active` dari awal. Target
             bawaan halaman ini adalah Server Radnet (Server Lokal) di panel
             kanan, jadi menandai salah satu perangkat sejak awal akan
             menyesatkan — yang jadi acuan adalah panel kanan, bukan sorotan
             di daftar. --}}
        <div class="col-12 col-lg-5 col-xxl-4">
        <section class="tools-section" id="tierRadnet">
            {{-- Pencarian --}}
            <div class="tool-search mb-2">
                <em class="icon ni ni-search"></em>
                <input type="text" class="form-control" id="deviceSearch"
                       placeholder="Cari nama atau alamat IP…" autocomplete="off"
                       aria-label="Cari perangkat Radnet berdasarkan nama atau alamat IP">
            </div>

            {{-- Filter status --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <span class="tool-count" id="deviceCountHint">
                    {{ $totalDevices }} perangkat
                </span>
                <div class="tool-filters" id="statusFilter" role="group" aria-label="Saring status perangkat">
                    <button type="button" class="tool-filter-btn active" data-status="all">
                        <span class="tool-dot" style="background:#0284c7"></span>Semua</button>
                    <button type="button" class="tool-filter-btn" data-status="up">
                        <span class="tool-dot" style="background:#22c55e"></span>UP</button>
                    <button type="button" class="tool-filter-btn" data-status="warning">
                        <span class="tool-dot" style="background:#eab308"></span>WRN</button>
                    <button type="button" class="tool-filter-btn" data-status="down">
                        <span class="tool-dot" style="background:#ef4444"></span>DOWN</button>
                </div>
            </div>

            {{-- Daftar perangkat. Tanpa card pembungkus (device-box): baris-baris
                 perangkat duduk langsung di kolom, rata dan menyatu. --}}
<div class="device-list" id="deviceList">
                @forelse($devices as $device)
                    @php
                        $status = in_array($device->status, ['down', 'warning', 'up'], true)
                            ? $device->status
                            : 'up';
                    @endphp
                    <button type="button"
                            class="device-item"
                            aria-pressed="false"
                            data-id="{{ $device->id }}"
                            data-name="{{ $device->name }}"
                            data-ip="{{ $device->ip_address }}"
                            data-status="{{ $status }}"
                            data-type="{{ $device->type_icon }}"
                            data-delay="{{ round((float) $device->avg_delay, 1) }}"
                            data-loss="{{ round((float) $device->missed_percentage, 1) }}">
                        {{-- Ikon mengikuti TIPE perangkat, bukan satu ikon
                             WiFi untuk semua baris. Peta tipe -> ikon ada
                             di ToolsController::DEVICE_TYPES dan sudah
                             divalidasi ada di font ikon tema, jadi tidak
                             mungkin baris tanpa gambar. --}}
                        <span class="device-ic"><em class="icon ni ni-{{ $device->type_icon }}"></em></span>
                        <span class="device-item-txt">
                            <span class="device-name">{{ $device->name }}</span>
                            <span class="device-ip">{{ $device->ip_address }}</span>
                            <span class="device-type">{{ $device->type }}</span>
                        </span>
                        <span class="status-badge status-{{ $status }}">{{ strtoupper($status) }}</span>
                    </button>
                @empty
                    <div class="device-empty">
                        <em class="icon ni ni-wifi-off" style="font-size:28px;opacity:.35"></em>
                        Belum ada perangkat terdaftar.<br>
                        Tambahkan lewat menu «Kelola Perangkat».
                    </div>
                @endforelse
            </div>
        </section>
        </div>

        {{-- ══════════════ PANEL KANAN · TIGA PILIHAN TARGET ══════════════
             Kolom kanan adalah SATU panel berisi tiga pilihan berurutan dari
             atas ke bawah, bukan tiga kotak yang saling menumpuk:

               Pilihan 1 · Server Radnet (Server Lokal) — cek dari NOC
               Pilihan 2 · Target dari daftar jaringan     — klik di panel kiri
               Pilihan 3 · Target dari Luar               — IP / domain bebas

             Kotak "Server Radnet / NOC (LOKAL)" yang dulu berdiri sendiri di
             paling atas sudah dihapus: isinya mengulang tombol di bawahnya,
             sehingga yang terbaca oleh pengguna ada dua target lokal, bukan
             satu. Sekarang setiap pilihan hanya punya satu permukaan: kepala
             yang menamainya, lalu isinya. --}}
        <div class="col-12 col-lg-7 col-xxl-8">
        <section class="tools-section" id="tierPemeriksaan">
            <div class="tools-panel tools-target-panel">
                <div class="tools-panel-head">
                    <div>
                        <h2 class="tools-panel-title">Target &amp; Pemeriksaan</h2>
                        <p class="tools-panel-sub">Pilih salah satu target di bawah, lalu tekan tombolnya. Semua tombol memakai target aktif ini.</p>
                    </div>
                </div>

                <div class="tools-panel-body">

                {{-- ═══ PILIHAN 1 · SERVER RADNET (SERVER LOKAL) ═══
                     Target paling atas karena paling sering dipakai: pemeriksaan
                     dijalankan dari server NOC, tanpa memilih perangkat apa pun. --}}
                <section class="tools-section tools-tier" id="tierLokal">
                    <div class="tools-tier-head">
                        <span class="tools-tier-no">Pilihan 1</span>
                        <div class="tools-tier-txt">
                            <h3 class="tools-tier-title">Server Radnet (Server Lokal)</h3>
                            <p class="tools-tier-sub">Periksa koneksi langsung dari server NOC, tanpa memilih perangkat.</p>
                        </div>
                    </div>
                    <button type="button" class="tool-local-option is-active" id="toolLocalBtn"
                            aria-pressed="true">
                        <span class="tool-local-ic"><em class="icon ni ni-server"></em></span>
                        <span class="tool-local-txt">
                            <span class="tool-local-name">Jalankan pemeriksaan dari server ini</span>
                            <span class="tool-local-hint">Perintah dikirim dari server NOC, lalu hasilnya dikembalikan ke halaman ini</span>
                        </span>
                        <span class="tool-local-check"><em class="icon ni ni-check-thick"></em></span>
                    </button>
                </section>

                {{-- ═══ PILIHAN 2 · TARGET DARI DAFTAR JARINGAN ═══
                     Cermin dari perangkat yang diklik di panel kiri. Alamat, nama,
                     status, dan latency-nya ikut tersinkron otomatis ke sini,
                     jadi hanya ada satu tempat yang perlu dibaca. --}}
                <section class="tools-section tools-tier" id="tierTarget">
                    <div class="tools-tier-head">
                        <span class="tools-tier-no">Pilihan 2</span>
                        <div class="tools-tier-txt">
                            <h3 class="tools-tier-title">Target dari daftar jaringan</h3>
                            <p class="tools-tier-sub">Pilih perangkat Radnet di bawah ini, lalu alamat, status, dan ikonnya otomatis ikut di panel kanan.</p>
                        </div>
                    </div>
                    <div class="tool-picker">
                        <label class="tool-field-label" for="devicePicker">
                            {{ $totalDevices }} perangkat Radnet — pilih salah satu:
                        </label>
                        <select class="form-select" id="devicePicker" aria-label="Pilih perangkat Radnet dari daftar jaringan">
                            <option value="">— Pilih perangkat dari daftar jaringan —</option>
                            @foreach($devices as $device)
                                @php
                                    $pickStatus = in_array($device->status, ['down', 'warning', 'up'], true)
                                        ? $device->status
                                        : 'up';
                                @endphp
                                <option value="{{ $device->id }}">
                                    {{ $device->name }} · {{ $device->ip_address }} · {{ strtoupper($pickStatus) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tool-target" id="toolTargetCard" role="status" aria-live="polite">
                        <div class="tool-target-top">
                            <span class="tool-target-ic"><em class="icon ni ni-server" id="toolTargetIcon"></em></span>
                            <div class="tool-target-txt">
                                <div class="tool-target-label">Perangkat terpilih</div>
                                <div class="tool-target-name" id="toolTargetName">Server Radnet (Server Lokal)</div>
                                <div class="tool-target-ip" id="toolTargetIp">Klik perangkat di panel kiri, atau pakai Pilihan 1 di atas</div>
                            </div>
                            <span class="status-badge status-up" id="toolTargetStatus" hidden>UP</span>
                        </div>
                        <div class="tool-target-meta" id="toolTargetMeta" hidden>
                            <span><em class="icon ni ni-speed"></em>Respons terakhir <b id="toolTargetDelay">—</b></span>
                            <span><em class="icon ni ni-alert"></em>Paket hilang <b id="toolTargetLoss">—</b></span>
                        </div>
                    </div>
                </section>

                {{-- ═══ PILIHAN 3 · TARGET DARI LUAR ═══
                     Alamat yang diketik sendiri, yaitu perangkat eksternal yang
                     tidak terdaftar di daftar jaringan Radnet. Di bawahnya ada
                     tombol aksi, lalu hasilnya. --}}
                <section class="tools-section tools-tier tools-tier--last" id="tierLuar">
                    <div class="tools-tier-head">
                        <span class="tools-tier-no">Pilihan 3</span>
                        <div class="tools-tier-txt">
                            <h3 class="tools-tier-title">Target dari Luar <span class="tools-panel-tag">IP / Domain Bebas</span></h3>
                            <p class="tools-tier-sub">Untuk perangkat eksternal yang tidak terdaftar di daftar jaringan.</p>
                        </div>
                    </div>

                    {{-- ═══ PENGATURAN PEMERIKSAAN ═══
                         Parameter ini milik semua tombol, jadi diletakkan di
                         dalam Pilihan 3 tepat sebelum target dan tombolnya.
                         Tetap tertutup secara default karena sebagian besar
                         pengguna tidak perlu mengubahnya. --}}
                    <details class="tools-advanced">
                        <summary>
                            <em class="icon ni ni-setting"></em>
                            Pengaturan Pemeriksaan <span class="tools-advanced-hint">(opsional, boleh diisi nanti)</span>
                        </summary>
                        <div class="tools-advanced-body">
                            <p class="tool-hint">
                                Kalau tidak perlu, biarkan saja. Pengaturan ini dipakai semua tombol di halaman ini.
                            </p>
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <label class="tool-field-label" for="toolCount">Jumlah cek (Ping)</label>
                                    <input type="number" class="form-control" id="toolCount" name="count" value="5" min="1" max="100">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="tool-field-label" for="toolHops">Maksimal hop (Trace)</label>
                                    <input type="number" class="form-control" id="toolHops" name="hops" value="32" min="1" max="64">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="tool-field-label" for="toolLookupType">Info yang dicari (Lookup)</label>
                                    <select class="form-select" id="toolLookupType" name="lookup_type">
                                        <option value="A" selected>Alamat IP versi 4</option>
                                        <option value="AAAA">Alamat IP versi 6</option>
                                        <option value="CNAME">Nama lain</option>
                                        <option value="MX">Server email</option>
                                        <option value="NS">Server nama</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="tool-field-label" for="toolLookupServer">Server DNS</label>
                                    <input type="text" class="form-control" id="toolLookupServer" name="lookup_server"
                                           placeholder="Kosong = bawaan">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="tool-field-label" for="toolFirstPort">Port dari</label>
                                    <input type="number" class="form-control" id="toolFirstPort" name="first_port" value="1" min="1" max="65535">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="tool-field-label" for="toolLastPort">Port sampai</label>
                                    <input type="number" class="form-control" id="toolLastPort" name="last_port" value="512" min="1" max="65535">
                                    <div class="tool-field-note">Sekali cek maksimal 1024 port.</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="tool-field-label" for="toolScanTimeout">Tunggu per port (ms)</label>
                                    <input type="number" class="form-control" id="toolScanTimeout" name="scan_timeout" value="300" min="100" max="2000">
                                </div>
                            </div>
                        </div>
                    </details>

                    {{-- ═══ INPUT TARGET LUAR ═══
                         Alamat yang diketik sendiri adalah target aktif,
                         jadi isian ini otomatis menggantikan pilihan di
                         Pilihan 1 dan Pilihan 2. --}}
                    <div class="tool-outside" id="toolOutside">
                        <label class="tool-field-label" for="customTarget">IP atau nama host</label>
                        <div class="input-group tool-input-group">
                            <span class="input-group-text"><em class="icon ni ni-globe"></em></span>
                            <input type="text" class="form-control tool-input" id="customTarget" name="custom_target"
                                   placeholder="Contoh: 10.0.0.1 atau router.pop-a"
                                   autocomplete="off" spellcheck="false" aria-label="Alamat IP atau nama host">
                        </div>
                        <p class="tool-hint">
                            Diisi manual berarti target bukan dari daftar jaringan Radnet, jadi hasil pemeriksaannya
                            tidak dibandingkan dengan data monitoring.
                        </p>
                    </div>

                    {{-- ═══ TOMBOL AKSI ═══
                         Satu set tombol untuk target aktif, apa pun asalnya, dan
                         tetap paling bawah supaya tidak ada lagi yang harus diisi
                         setelah menekan tombol. --}}
                    @include('admin.components.tools-action-buttons', ['guide' => true])
                </section>

                {{-- ═══ HASIL PEMERIKSAAN ═══
                     Hasil menempel di bawah Pilihan 3, jadi alur
                     "isi target → tekan tombol → baca hasil" terjadi di
                     satu kolom dan tidak perlu melompat ke panel lain. Di
                     dalamnya ada kotak "Analisis Status / Alasan" ala
                     WhatsUp Gold: status UP/WARNING/DOWN beserta sebabnya
                     dalam bahasa manusia. --}}
                <section class="tools-subsection" id="hasil">
                    <div class="tools-subsection-head">
                        <h3 class="tools-subsection-title">Hasil Pemeriksaan</h3>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-light btn-dim" id="btnResultCopy" disabled>
                                <em class="icon ni ni-copy me-1"></em>Salin
                            </button>
                            <button type="button" class="btn btn-sm btn-light btn-dim" id="btnResultClear">
                                <em class="icon ni ni-reload me-1"></em>Kosongkan
                            </button>
                        </div>
                    </div>

                    <div class="tools-result" id="toolResult">
                        <div class="tools-result-empty" id="toolResultEmpty">
                            <em class="icon ni ni-file-text"></em>
                            Belum ada hasil.<br>
                            Target aktif adalah Server Radnet (Server Lokal). Tekan salah satu tombol pemeriksaan untuk mulai.
                        </div>
                    </div>
                </section>

                </div>
            </div>
        </section>
        </div>

    </div>

    </form>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const form          = document.getElementById('networkToolForm');
    const actionBox     = document.getElementById('toolAction');
    const resultBox     = document.getElementById('toolResult');
    const csrf          = document.querySelector('meta[name="csrf-token"]');

    const deviceList    = document.getElementById('deviceList');
    const deviceSearch  = document.getElementById('deviceSearch');
    const deviceHint    = document.getElementById('deviceCountHint');
    const deviceItems   = Array.prototype.slice.call(document.querySelectorAll('.device-item'));
    const statusFilter  = document.getElementById('statusFilter');
    const customInput   = document.getElementById('customTarget');

    const targetCard    = document.getElementById('toolTargetCard');
    const targetName    = document.getElementById('toolTargetName');
    const targetIp      = document.getElementById('toolTargetIp');
    const targetStatus  = document.getElementById('toolTargetStatus');
    const targetIcon    = document.getElementById('toolTargetIcon');
    const targetMeta    = document.getElementById('toolTargetMeta');
    const targetDelay   = document.getElementById('toolTargetDelay');
    const targetLoss    = document.getElementById('toolTargetLoss');
    const localBtn      = document.getElementById('toolLocalBtn');
    const devicePicker  = document.getElementById('devicePicker');

    /* Alamat yang dipakai saat target aktif = Server Radnet (Lokal). Awalnya
       kosong karena "memeriksa server lokal" berarti tidak ada alamat yang
       perlu ping — frontend menandainya dengan nilai khusus ini, bukan dengan
       alamat sembarang, supaya tidak pernah benar-benar mengirim ping ke
       127.0.0.1 dan menyamar sebagai pemeriksaan server. */
    const LOCAL_TARGET = '@local';

    const toolTarget    = document.getElementById('toolTarget');
    const toolDeviceId  = document.getElementById('toolDeviceId');
    const lookupTypeSel = document.getElementById('toolLookupType');

    const routes = {
        ping:   '{{ route("admin.tools.ping") }}',
        trace:  '{{ route("admin.tools.trace") }}',
        lookup: '{{ route("admin.tools.lookup") }}',
        scan:   '{{ route("admin.tools.scan") }}'
    };

    /* Ambang penilaian yang SAMA dengan yang dipakai backend
       (App\Services\NetworkTools\DiagnosisAnalyzer). Dikirim dari server saat
       render supaya kotak "Analisis Status / Alasan" tidak punya aturan sendiri
       yang bisa berbeda dari hasil analisis backend. Dipakai hanya sebagai
       cadangan, kalau-kalau responsnya datang tanpa field 'analysis'. */
    const LIMITS = @json($analysisLimits);

    const totalDevices = deviceItems.length;
    let activeFilter   = 'all';
    let selectedItem   = null;
    let busy           = false;

    /* ══ KAMUS BAHASA SEHARI-HARI ══════════════════════ */

    const STATUS_TEXT = {
        up:      { badge: 'UP',      icon: 'ni-check-thick' },
        warning: { badge: 'WARNING', icon: 'ni-alert-circle' },
        down:    { badge: 'DOWN',    icon: 'ni-close' },
        error:   { badge: 'GAGAL',   icon: 'ni-alert-circle' },
        info:    { badge: 'INFO',    icon: 'ni-info' }
    };

    const SLOW_MS = Number(LIMITS.goodLatencyMs) || 100;
    const VERY_SLOW_MS = Number(LIMITS.poorLatencyMs) || 300;

    const LOOKUP_LABEL = {
        A:     'Alamat IP versi 4',
        AAAA:  'Alamat IP versi 6',
        CNAME: 'Nama lain yang menunjuk ke sini',
        MX:    'Server penerima email',
        NS:    'Server yang menyimpan nama domain'
    };

    const LOOKUP_SHORT = {
        A:     'Alamat IP',
        AAAA:  'Alamat IP6',
        CNAME: 'Nama lain',
        MX:    'Server email',
        NS:    'Server nama'
    };

    const PORT_NAME = {
        21: 'FTP — file sharing',        22: 'SSH — remote login',
        23: 'Telnet — remote login lama', 25: 'SMTP — email',
        53: 'DNS — info nama domain',    80: 'HTTP — website',
        110: 'POP3 — email',            143: 'IMAP — email',
        389: 'LDAP — daftar pengguna',  443: 'HTTPS — website aman',
        445: 'SMB — file sharing Windows', 465: 'SMTPS — email aman',
        587: 'SMTP — kirim email',       993: 'IMAPS — email aman',
        995: 'POP3S — email aman',      1433: 'MS SQL Server — database',
        3306: 'MySQL — database',        3389: 'RDP — remote desktop',
        5060: 'SIP — telepon/VoIP',     5432: 'PostgreSQL — database',
        5900: 'VNC — remote desktop',    8080: 'HTTP — website (port alternatif)',
        8443: 'HTTPS — website aman (port alternatif)', 9100: 'Printer',
        27017: 'MongoDB — database'
    };

    /* ══ PEMBANTU KECIL ════════════════════════════════ */

    function el(tag, cls, text) {
        const node = document.createElement(tag);
        if (cls) { node.className = cls; }
        if (text !== undefined && text !== null) { node.textContent = text; }
        return node;
    }

    function iconEl(name) {
        const em = document.createElement('em');
        em.className = 'icon ni ni-' + name;
        return em;
    }

    function num(value) {
        const n = parseFloat(value);
        return isNaN(n) ? null : n;
    }

    function trimNum(value) {
        return String(Math.round(value * 10) / 10);
    }

    function readPort(id, fallback) {
        const n = parseInt(document.getElementById(id).value, 10);
        return isNaN(n) ? fallback : n;
    }

    /* ══ WAKTU & DURASI ═════════════════════════════════
       Dua angka ini selalu ditampilkan di setiap hasil supaya jelas kapan
       pemeriksaan dijalankan dan berapa lama server menunggu balasan. */

    /* Jam pengujian (HH:MM:SS) dari server; dipakai jam dinding browser
       sebagai cadangan kalau server tidak mengirimkannya. */
    function clockTime(res) {
        const fromServer = String((res && res.checked_at) || '').trim();
        if (/^\d{2}:\d{2}:\d{2}$/.test(fromServer)) { return fromServer; }
        return new Date().toLocaleTimeString('id-ID', { hour12: false });
    }

    /* Tanggal pengujian (dd/mm/yyyy), supaya jam di atas tidak ambigu
       kalau hasil ini dibuka lagi di hari berikutnya. */
    function clockDate(res) {
        const fromServer = String((res && res.checked_date) || '').trim();
        return /^\d{2}\/\d{2}\/\d{4}$/.test(fromServer) ? fromServer : '';
    }

    function stampText(res) {
        const day = clockDate(res);
        return day ? day + ' · ' + clockTime(res) : clockTime(res);
    }

    /* Durasi dalam milidetik, mis. "420 ms". Null kalau tidak terbaca. */
    function durationMs(res) {
        let ms = num(res && res.duration_ms);
        if (ms === null && res && res.duration !== undefined) {
            ms = Math.round(parseFloat(res.duration) * 1000);
        }
        return (ms === null || isNaN(ms)) ? null : Math.max(0, Math.round(ms));
    }

    /* Frasa siap pakai: "420 ms" atau "1.2 detik (1204 ms)". */
    function tookText(res) {
        const ms = durationMs(res);
        if (ms === null) { return '—'; }
        if (ms < 1000) { return ms + ' ms'; }
        return trimNum(ms / 1000) + ' detik (' + ms + ' ms)';
    }

    /* Frasa "dicek jam HH:MM:SS". */
    function whenText(res) {
        return 'dicek jam ' + clockTime(res);
    }

    function capFirst(text) {
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    /* ══ KARTU HASIL ═══════════════════════════════════ */

    function clearResult() {
        while (resultBox.firstChild) { resultBox.removeChild(resultBox.firstChild); }
    }

    function showEmpty() {
        clearResult();
        const empty = el('div', 'tools-result-empty');
        empty.id = 'toolResultEmpty';
        empty.appendChild(iconEl('file-text'));
        empty.appendChild(document.createTextNode('Belum ada hasil.'));
        empty.appendChild(el('br'));
        empty.appendChild(document.createTextNode(
            'Tekan salah satu tombol di panel kanan untuk mulai memeriksa target aktif.'
        ));
        resultBox.appendChild(empty);
        document.getElementById('btnResultCopy').disabled = true;
    }

    /* Catatan singkat di kotak hasil — dipakai saat target berubah supaya
       atasan tahu apa yang sedang aktif tanpa perlu menekan tombol dulu. */
    function setNotice(text) {
        clearResult();
        const note = el('div', 'result result--info');
        const head = el('div', 'result-head');
        const badge = el('span', 'result-badge result-badge--info', 'INFO');
        badge.insertBefore(iconEl('info'), badge.firstChild);
        head.appendChild(badge);
        head.appendChild(el('h3', 'result-title', 'Target aktif'));
        note.appendChild(head);

        const body = el('div', 'result-body');
        body.appendChild(el('p', 'result-text', text));
        note.appendChild(body);

        resultBox.appendChild(note);
        document.getElementById('btnResultCopy').disabled = true;
    }

    function makeCard(tone, title, res, target) {
        const meta = STATUS_TEXT[tone] || STATUS_TEXT.info;
        const card = el('div', 'result result--' + tone);

        const head = el('div', 'result-head');
        const badge = el('span', 'result-badge result-badge--' + tone, meta.badge);
        badge.insertBefore(iconEl(meta.icon), badge.firstChild);
        head.appendChild(badge);
        head.appendChild(el('h5', 'result-title', title));
        card.appendChild(head);

        // Bar waktu: kapan diperiksa, berapa lama menunggu, dan targetnya.
        if (res) {
            const when = el('div', 'result-when');
            addWhenItem(when, 'clock', 'Waktu cek', stampText(res));
            addWhenItem(when, 'speed', 'Durasi', tookText(res));
            if (target) { addWhenItem(when, 'globe', 'Target', target, true); }
            card.appendChild(when);
        }

        const body = el('div', 'result-body');
        card.appendChild(body);

        return { card: card, body: body };
    }

    function addWhenItem(bar, icon, label, value, mono) {
        const item = el('span', 'result-when-item' + (mono ? ' is-target' : ''));
        item.appendChild(iconEl(icon));
        item.appendChild(document.createTextNode(label + ' '));
        item.appendChild(el('b', null, value));
        bar.appendChild(item);
    }

    function addText(body, text) {
        body.appendChild(el('p', 'result-text', text));
    }

    function addMetrics(body, items) {
        const grid = el('div', 'result-metrics');
        items.forEach(function (item) {
            const box  = el('div', 'metric');
            box.appendChild(el('div', 'metric-label', item[0]));
            const val = el('div', 'metric-value');
            val.appendChild(document.createTextNode(item[1]));
            if (item[2]) { val.appendChild(el('small', null, ' ' + item[2])); }
            box.appendChild(val);
            grid.appendChild(box);
        });
        body.appendChild(grid);
    }

    function addListHead(body, text) {
        body.appendChild(el('div', 'result-list-head', text));
    }

    function addRow(body, key, value, plain, dim) {
        const row = el('div', 'result-row' + (dim ? ' is-dim' : ''));
        row.appendChild(el('span', 'result-row-key', key));
        row.appendChild(el('span', 'result-row-val' + (plain ? ' is-plain' : ''), value));
        body.appendChild(row);
    }

    function addNoteText(body, text) {
        body.appendChild(el('div', 'result-note', text));
    }

    /* ══ KOTAK "ANALISIS STATUS / ALASAN" ═════════════════
     *
     * Wajah "ala WhatsUp Gold" dari hasil diagnosa: satu kotak yang selalu
     * menjawab pertanyaan "kenapa statusnya begini?" dalam kalimat bahasa
     * Indonesia, apa pun alat yang dipakai (Ping / Trace / Lookup / Scan).
     *
     * Sumber kebenarannya adalah backend (field `analysis` di respons JSON),
     * karena ambang penilaiannya harus sama dengan yang dipakai service
     * DiagnosisAnalyzer. Fungsi fallbackPingAnalysis hanya dipakai kalau
     * responsnya somehow tidak membawa field itu.
     */
    function addAnalysis(body, analysis) {
        if (!analysis) { return; }

        const status = String(analysis.status || 'info').toLowerCase();
        const tone   = ['up', 'warning', 'down', 'error', 'info'].indexOf(status) === -1 ? 'info' : status;

        const box = el('div', 'analysis analysis--' + tone);

        /* Kepala: judul kotak + badge status yang warnanya sama dengan garis
           kiri kartu hasil di atasnya. */
        const head = el('div', 'analysis-head');
        head.appendChild(el('span', 'analysis-title', 'Analisis Status / Alasan'));

        const badge = el('span', 'analysis-badge analysis-badge--' + tone,
            String(analysis.label || STATUS_TEXT[tone].badge));
        badge.insertBefore(iconEl(tone === 'up' ? 'check-thick'
            : tone === 'warning' ? 'alert-circle'
            : tone === 'down' ? 'close' : 'info'), badge.firstChild);
        head.appendChild(badge);

        if (analysis.headline) {
            head.appendChild(el('p', 'analysis-headline', analysis.headline));
        }

        box.appendChild(head);

        const bodyBox = el('div', 'analysis-body');

        /* Alasan: kalimat inti status, selalu ada. */
        const reason = el('div', 'analysis-reason');
        reason.appendChild(iconEl(tone === 'up' ? 'emotsmile'
            : tone === 'warning' ? 'clock' : 'close-circle'));
        reason.appendChild(el('span', null, analysis.reason || ''));
        bodyBox.appendChild(reason);

        /* Angka pendukung: apa yang sebenarnya diukur. */
        const facts = Array.isArray(analysis.facts) ? analysis.facts : [];
        if (facts.length) {
            const grid = el('div', 'analysis-facts');
            facts.forEach(function (f) {
                const row = el('div', 'analysis-fact');
                row.appendChild(el('span', 'analysis-fact-key', f[0]));
                row.appendChild(el('span', 'analysis-fact-val', f[1]));
                grid.appendChild(row);
            });
            bodyBox.appendChild(grid);
        }

        /* Saran tindak lanjut: apa yang harus dilakukan berikutnya. */
        if (analysis.recommendation) {
            const reco = el('div', 'analysis-reco');
            reco.appendChild(iconEl('bulb'));
            reco.appendChild(el('span', null, analysis.recommendation));
            bodyBox.appendChild(reco);
        }

        box.appendChild(bodyBox);
        body.appendChild(box);
    }

    /* Log mentah tetap ikut dibawa sebagai bukti, tapi tidak ditampilkan
       langsung. Log disimpan di balik <details> supaya tidak mengalihkan
       perhatian dari analisis status. */
    function addRawLog(body, output) {
        const text = String(output === null || output === undefined ? '' : output).trim();

        const wrap = document.createElement('details');
        wrap.className = 'result-log';

        const sum = document.createElement('summary');
        sum.appendChild(iconEl('terminal'));
        sum.appendChild(document.createTextNode('Log Terminal (mentah)'));
        sum.appendChild(el('span', 'result-log-hint', text ? 'klik untuk melihat' : ''));
        wrap.appendChild(sum);

        const pre = document.createElement('pre');
        pre.className = 'result-log-body';
        pre.textContent = text;
        wrap.appendChild(pre);

        body.appendChild(wrap);
    }

    /**
     * Analisis dari backend, atau tebakan lokal kalau field-nya hilang.
     *
     * Fungsi fallback sengaja dibuat PENDEK: kalau respons tidak membawa
     * analisis, yang penting kotak "Alasan" tetap ada dan tidak kosong —
     * keputusan status tetap milik backend.
     */
    function analysisOf(res, fallbackStatus, fallbackReason, fallbackFacts) {
        if (res && res.analysis && res.analysis.status) { return res.analysis; }

        const tone = fallbackStatus || 'info';

        return {
            status: tone,
            label: STATUS_TEXT[tone] ? STATUS_TEXT[tone].badge : 'INFO',
            headline: '',
            reason: fallbackReason || 'Backend tidak mengirim analisis untuk hasil ini.',
            facts: fallbackFacts || [],
            recommendation: ''
        };
    }

    /* Fallback untuk ping, dipakai hanya kalau `analysis` tidak ikut di
       respons. Ambangnya dibaca dari LIMITS supaya sama dengan backend. */
    function fallbackPingAnalysis(ping, res, target) {
        const lost = ping.rx === 0 || ping.loss === 100;
        const facts = [];

        if (ping.avg !== null) { facts.push(['Respons rata-rata (avg)', trimNum(ping.avg) + ' ms']); }
        if (ping.loss !== null) { facts.push(['Packet loss', trimNum(ping.loss) + ' %']); }
        facts.push(['Lama pemeriksaan', tookText(res)]);

        if (ping.badName || lost) {
            return analysisOf(null, 'down',
                'Perangkat tidak merespons (Request Timed Out / mati / kabel putus). '
                + 'Tidak ada balasan yang masuk dari ' + target + ', sehingga perangkat ini dianggap mati, '
                + 'kabelnya putus, atau alamat IP-nya sudah tidak berlaku.', facts);
        }

        if ((ping.loss !== null && ping.loss > 0) || (ping.avg !== null && ping.avg > SLOW_MS)) {
            return analysisOf(null, 'warning',
                'Ada latency tinggi atau sedikit packet loss (delay/jitter), '
                + 'sehingga perangkat masih hidup tapi responsnya tidak stabil.', facts);
        }

        return analysisOf(null, 'up',
            'Perangkat merespons dengan sangat stabil dan tanpa packet loss.', facts);
    }

    function publish() {
        document.getElementById('btnResultCopy').disabled = false;
        try {
            resultBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } catch (e) {
            resultBox.scrollIntoView();
        }
    }

    /* ══ 1 · PING ═══════════════════════════════════════ */

    function readPing(res) {
        const out = String(res.output || '');

        const pair = out.match(/(\d+)\s+packets transmitted,\s+(\d+)\s+(?:packets\s+)?received/i);
        const tx  = pair ? parseInt(pair[1], 10) : null;
        const rx  = pair ? parseInt(pair[2], 10) : null;

        const lossMatch = out.match(/(\d+(?:\.\d+)?)%\s*packet loss/i);
        let loss = lossMatch ? parseFloat(lossMatch[1]) : null;
        if (loss === null && tx !== null && tx > 0 && rx !== null) {
            loss = Math.round(((tx - rx) / tx) * 1000) / 10;
        }

        const rtt = out.match(/rtt\s+min\/avg\/max(?:\/mdev)?\s*=\s*[\d.]+\/([\d.]+)\//i);
        const avg = rtt ? parseFloat(rtt[1]) : null;

        return {
            tx: tx,
            rx: rx,
            loss: loss,
            avg: avg,
            noRoute: /(Destination Host Unreachable|Host Unreachable|No route to host|Request timeout)/i.test(out),
            badName: /(Name or service not known|cannot resolve|unknown host|Temporary failure in name resolution)/i.test(out),
            empty: out.trim() === ''
        };
    }

    function speedWord(avg) {
        if (avg === null) { return 'tidak terbaca'; }
        if (avg <= SLOW_MS) { return 'Cepat'; }
        if (avg <= VERY_SLOW_MS) { return 'Agak lambat'; }
        return 'Lambat';
    }

    /* Ringkasan sesuai kondisi IP. Setiap cabang menyebut waktu pengecekan
       dan durasi respons supaya hasilnya transparan, bukan sekadar "UP/DOWN". */
    function pingVerdict(ping, res, target) {
        const lost = ping.rx === 0 || ping.loss === 100;
        const when = whenText(res);
        const took = tookText(res);
        const got  = (ping.tx !== null && ping.rx !== null)
            ? 'dari ' + ping.tx + ' pesan yang dikirim, ' + ping.rx + ' dijawab perangkat'
            : 'sebagian pesan yang dikirim tidak dijawab perangkat';

        if (ping.badName) {
            return {
                tone: 'down',
                title: 'Alamat tidak ditemukan',
                texts: [
                    'Nama "' + target + '" tidak ada di internet, ' + when + ' selama ' + took + '.',
                    'Kemungkinannya salah ketik, namanya belum terdaftar, atau sudah dihapus. Coba pakai alamat IP langsung, misal 8.8.8.8.'
                ]
            };
        }
        if (lost) {
            return {
                tone: 'down',
                title: 'Perangkat tidak merespons (mati / terputus)',
                texts: [
                    'Tidak ada balasan sama sekali dari perangkat ini, ' + when + ', menunggu selama ' + took + '.',
                    'Kemungkinan penyebabnya: perangkatnya mati atau sedang restart, kabel jaringan dicabut atau kendur, sakelar / router di tengah jalan mati, atau alamat IP-nya sudah berganti.',
                    'Cek dulu lampu dan kabel di perangkat. Kalau lampunya masih menyala, periksa juga perangkat di seberang titik ini.'
                ]
            };
        }
        if ((ping.loss !== null && ping.loss > 0) || (ping.avg !== null && ping.avg > SLOW_MS)) {
            const causes = [];
            if (ping.loss !== null && ping.loss > 0) {
                causes.push('ada paket yang hilang (' + trimNum(ping.loss) + '%)');
            }
            if (ping.avg !== null && ping.avg > SLOW_MS) {
                causes.push('responsnya lambat, sekitar ' + trimNum(ping.avg) + ' ms (normalnya di bawah ' + SLOW_MS + ' ms)');
            }
            return {
                tone: 'warning',
                title: 'Masih bisa dihubungi, tapi responsnya jelek',
                texts: [
                    'Perangkat ini menyala dan masih bisa dihubungi, cuma ada yang agak bermasalah: ' + causes.join(' dan ') + '.',
                    'Peringatan ini muncul karena ' + got + ' pada ' + when + ', lama ' + took + '.',
                    'Kalau berlangsung lama, periksa kabel, colokan, atau beban jaringan di titik tersebut.'
                ]
            };
        }
        return {
            tone: 'up',
            title: 'Perangkat merespons dengan baik',
                texts: [
                    'Perangkat ini menyala dan merespons normal. ' + capFirst(got)
                        + ', ' + when + ', total lama ' + took + '.',
                ping.avg === null
                    ? 'Semua pesan dijawab tanpa ada yang hilang. Kondisi ini baik, tidak perlu tindakan apa pun.'
                    : 'Rata-rata balasannya ' + trimNum(ping.avg) + ' ms (' + speedWord(ping.avg)
                        + ') dan tidak ada paket yang hilang. Kondisi ini normal, tidak perlu tindakan apa pun.'
            ]
        };
    }

    function renderPing(res, target) {
        const ping = readPing(res);
        const v    = pingVerdict(ping, res, target);
        const out  = makeCard(v.tone, v.title, res, target);

        // Analisis Status / Alasan: diterjemahkan dari log mentah yang sama,
        // jadi bisa muncul sebelum atau sesudah kalimat ringkasan panjang.
        addAnalysis(out.body, fallbackPingAnalysis(ping, res, target));

        v.texts.forEach(function (t) { addText(out.body, t); });

        const speedVal  = ping.avg === null ? '—' : trimNum(ping.avg);
        const speedNote = ping.avg === null ? null : 'ms · ' + speedWord(ping.avg);
        const lossVal   = ping.loss === null ? '—' : trimNum(ping.loss) + ' %';

        addMetrics(out.body, [
            ['Respons', speedVal, speedNote],
            ['Paket hilang', lossVal],
            ['Lama cek', tookText(res)]
        ]);

        if (res.timedout) {
            addNoteText(out.body, 'Pemeriksaan ini sudah melewati batas waktu, jadi datanya belum lengkap. Coba lagi dengan jumlah cek yang lebih sedikit.');
        }

        addMonitoringNote(out.body, res.device, v.tone);
        addRawLog(out.body, res.output);

        resultBox.appendChild(out.card);
        publish();
    }

    /* Bandingkan hasil cek manual dengan status yang tercatat di sistem. */
    function addMonitoringNote(body, device, tone) {
        if (!device) { return; }

        const label = String(device.status || '').toLowerCase();
        if (label !== 'up' && label !== 'warning' && label !== 'down') { return; }

        let text = 'Di sistem monitoring, perangkat ini tercatat ' + STATUS_TEXT[label].badge;

        if (device.avg_delay !== null && device.avg_delay !== undefined) {
            text += ' dengan respons sekitar ' + trimNum(device.avg_delay) + ' ms';
        }
        if (device.last_checked) {
            text += ' (dicek ' + device.last_checked + ')';
        }
        text += '.';

        if (label !== tone) {
            text += ' Status di atas berasal dari pemeriksaan yang baru saja dilakukan, jadi wajar berbeda bila kondisinya memang baru saja berubah.';
        }

        addNoteText(body, text);
    }

    /* ══ 2 · TRACE ══════════════════════════════════════ */

    /* traceroute dan tracepath menulis format berbeda, dan tracepath bisa
       mengulang nomor lompatan dalam satu baris. Keduanya dinormalkan ke
       satu bentuk: { no, ip, name, time, silent }. */
    function readTrace(res) {
        const byHop = {};

        String(res.output || '').split('\n').forEach(function (raw) {
            const line = raw.trim();
            if (/^(resume:|too many hops|traceroute to|tracepath to|ping:)/i.test(line)) { return; }

            const m = line.match(/^(\d+)\s*[:?]?\s+(.*)$/);
            if (!m) { return; }

            const no   = parseInt(m[1], 10);
            let   rest = m[2];

            const nameMatch = rest.match(/^([A-Za-z_][\w.\-]*)\s+/);
            const name = nameMatch ? nameMatch[1] : '';
            if (nameMatch) { rest = rest.slice(nameMatch[0].length); }

            const ipMatch = rest.match(/\b(?:\d{1,3}\.){3}\d{1,3}\b/);
            const times   = rest.match(/([\d.]+)\s*ms/g) || [];
            const values  = times.map(function (t) {
                return parseFloat(t.replace(/\s*ms/, ''));
            }).filter(function (v) { return !isNaN(v); });

            const hop = {
                no: no,
                ip: ipMatch ? ipMatch[0] : '',
                name: name,
                time: values.length
                    ? Math.round((values.reduce(function (a, b) { return a + b; }, 0) / values.length) * 10) / 10
                    : null,
                silent: values.length === 0
            };

            // tracepath bisa mencetak lompatan yang sama beberapa kali; simpan
            // yang paling informatif (punya alamat IP dan/atau waktu).
            const prev = byHop[no];
            if (!prev) {
                byHop[no] = hop;
                return;
            }
            if ((!prev.ip && hop.ip) || (prev.time === null && hop.time !== null)) {
                byHop[no] = hop;
            }
        });

        return Object.keys(byHop).map(function (k) {
            return byHop[k];
        }).sort(function (a, b) { return a.no - b.no; });
    }

    function targetReached(hops, target) {
        const ip   = target.trim();
        const name = ip.replace(/^\d{1,3}(\.\d{1,3}){3}$/, '').toLowerCase();

        for (let i = 0; i < hops.length; i++) {
            if (hops[i].ip && hops[i].ip === ip) { return hops[i].no; }
            if (name && hops[i].name && hops[i].name.toLowerCase() === name) { return hops[i].no; }
        }

        // Tidak ada nama/IP tujuan yang cocok (misal target ditulis sebagai
        // "localhost"). Kalau jalur selesai sebelum batas maksimal, jalur itu
        // dianggap sampai.
        const maxHops = readPort('toolHops', 32);
        const last = hops.length ? hops[hops.length - 1] : null;
        if (last && !last.silent && last.no < maxHops) { return last.no; }

        return null;
    }

    function renderTrace(res, target) {
        const hops    = readTrace(res);
        const arrived = targetReached(hops, target);
        const lastOn  = hops.filter(function (h) { return !h.silent; }).pop();
        const slowest = hops.filter(function (h) { return h.time !== null; })
            .reduce(function (acc, h) {
                if (acc === null || h.time > acc.time) { return h; }
                return acc;
            }, null);

        const out = arrived
            ? makeCard('up', 'Data sampai ke tujuan', res, target)
            : makeCard('down', 'Jalur data terputus sebelum sampai tujuan', res, target);

        addAnalysis(out.body, analysisOf(res, arrived ? 'up' : 'down'));

        if (arrived) {
            addText(out.body, 'Perjalanan data berhasil melewati ' + arrived + ' router lalu sampai ke '
                + target + ', ' + whenText(res) + ' dengan lama ' + tookText(res)
                + '. Tidak ada router yang menghalangi di jalur ini.');
        } else if (lastOn) {
            addText(out.body, 'Data berhenti di router nomor ' + lastOn.no
                + (lastOn.ip ? ' (' + lastOn.ip + ')' : '') + ', ' + whenText(res)
                + ' dengan lama ' + tookText(res) + '. Router di titik itu tidak meneruskan data ke tujuan.');
            addText(out.body, 'Kemungkinan besar masalah ada di router tersebut — bisa mati, salah konfigurasi, atau kehabisan jalur. Router sebelum nomor '
                + lastOn.no + ' masih normal.');
        } else {
            addText(out.body, 'Tidak ada satu pun router yang menjawab dari perangkat ini, ' + whenText(res)
                + ' dengan lama ' + tookText(res) + '. Jalur data terputus sejak awal, jadi periksa dulu koneksi lokal dan perangkat tujuan.');
        }

        addMetrics(out.body, [
            ['Jumlah lompatan', String(hops.length), hops.length ? 'router' : null],
            ['Paling lambat', slowest ? slowest.time + ' ms' : '—', slowest ? 'no. ' + slowest.no : null],
            ['Sampai tujuan', arrived ? 'Ya' : 'Tidak']
        ]);

        if (hops.length) {
            addListHead(out.body, 'Rute yang dilalui');
            hops.forEach(function (h) {
                let who;
                if (h.name) {
                    who = h.name + (h.ip && h.ip !== h.name ? ' (' + h.ip + ')' : '');
                } else if (h.ip) {
                    who = h.ip;
                } else {
                    who = 'nama & alamat tidak terbaca';
                }
                if (h.silent) {
                    who = who + ' — tidak menjawab';
                } else if (h.time !== null) {
                    who = who + ' · ' + h.time + ' ms';
                }
                addRow(out.body, 'Lompatan ' + h.no, who, true, h.silent);
            });
            addNoteText(out.body, 'Nomor 1 adalah router paling dekat dengan perangkat ini. Semakin besar nomornya, semakin jauh dari perangkat. Router yang tidak menjawab berarti pesan dibuang di titik itu.');
        }

        if (res.timedout) {
            addNoteText(out.body, 'Pencarian jalur dihentikan karena terlalu lama. Coba perkecil "Maksimal hop" di panel pengaturan pemeriksaan.');
        }

        addRawLog(out.body, res.output);

        resultBox.appendChild(out.card);
        publish();
    }

    /* ══ 3 · LOOKUP ═════════════════════════════════════ */

    function readLookup(res) {
        const out = String(res.output || '');
        const type = (lookupTypeSel.value || 'A').toUpperCase();
        const found = [];

        // "Nama ada tapi tidak punya data jenis ini" berbeda dengan
        // "namanya sendiri tidak ada", jadi keduanya dibedakan.
        const noRecord = /no answer|no data|has no records|SERVFAIL|REFUSED|Temporary failure in name resolution/i.test(out);
        const notFound = /NXDOMAIN|NOTFOUND|non-existent|can't find|cannot find|no such host/i.test(out);

        const nameMatch = out.match(/^Name:\s*(.+)$/im);
        // Alamat server DNS ikut tercetak sebagai "Address:" — bukan hasil
        // pencarian, jadi harus dikecualikan.
        const serverAddr = (out.match(/^Address:\s*(\S+)\s*#\d+/im) || [])[1] || '';

        String(out).split('\n').some(function (raw) {
            // Setelah baris ini isinya data tambahan server, bukan jawaban
            // untuk nama yang ditanyakan — berhenti supaya tidak tercampur.
            if (/^(authoritative answers|additional information|additional records)/i.test(raw.trim())) {
                return true;
            }

            let m;

            // Bentuk jawaban nslookup.
            m = raw.match(/mail exchanger\s*=\s*\d+\s+(\S+)/i);
            if (!m) { m = raw.match(/nameserver\s*=\s*(\S+)/i); }
            if (!m) { m = raw.match(/canonical name\s*=\s*(\S+)/i); }
            if (!m) { m = raw.match(/^\S+\s+origin\s*=\s*(\S+)/i); }
            if (m) { found.push(m[1].replace(/\.$/, '')); return false; }

            m = raw.match(/^Address(?:es)?:\s*(.+)$/i);
            if (m) {
                m[1].split(/[\n,]+/).forEach(function (v) {
                    const ip = v.trim().split('#')[0].trim();
                    if (ip && ip !== serverAddr) { found.push(ip); }
                });
                return false;
            }

            // Bentuk jawaban getent (dipakai kalau nslookup tidak ada).
            m = raw.match(/^(\S+)\s+STREAM\s+(\S+)/i);
            if (m && m[1] !== 'STREAM') { found.push(m[1]); return false; }

            return false;
        });

        return {
            type: type,
            label: LOOKUP_LABEL[type] || 'Informasi domain',
            short: LOOKUP_SHORT[type] || 'Data',
            name: nameMatch ? nameMatch[1].trim() : '',
            found: found,
            notFound: notFound && !noRecord,
            noRecord: noRecord,
            server: (out.match(/^Server:\s*(.+)$/im) || [])[1] || ''
        };
    }

    function renderLookup(res, target) {
        const info = readLookup(res);
        let out;
        let tone;

        if (info.notFound) {
            tone = 'down';
            out = makeCard('down', 'Nama tidak ditemukan', res, target);
            addText(out.body, 'Nama "' + target + '" tidak ada di daftar internet, ' + whenText(res)
                + ' dengan lama ' + tookText(res) + '. Periksa ejaan nama domainnya — kemungkinan besar salah ketik, belum terdaftar, atau sudah dihapus.');
        } else if (info.noRecord) {
            tone = 'warning';
            out = makeCard('warning', 'Nama ada, tapi info ini tidak ada', res, target);
            addText(out.body, 'Nama "' + target + '" terdaftar, tapi tidak punya data ' + info.label.toLowerCase() + '. Itu wajar, misal tidak semua nama domain punya alamat email (MX).');
            addText(out.body, 'Pilih jenis info lain di panel pengaturan pemeriksaan untuk melihat data yang tersedia.');
        } else if (info.found.length) {
            tone = 'up';
            out = makeCard('up', 'Informasi nama berhasil diambil', res, target);
            addText(out.body, 'Nama "' + target + '" terdaftar dan bisa dihubungi, ' + whenText(res)
                + ' dengan lama ' + tookText(res) + '. Berikut data yang berhasil dibaca dari server nama (DNS).');
        } else {
            tone = 'warning';
            out = makeCard('warning', 'Informasi belum tersedia', res, target);
            addText(out.body, 'Server nama menjawab, tetapi tidak ada data ' + info.label.toLowerCase()
                + ' untuk "' + target + '", ' + whenText(res) + ' dengan lama ' + tookText(res) + '.');
        }

        addAnalysis(out.body, analysisOf(res, tone));

        addMetrics(out.body, [
            ['Jenis info', info.type, info.label],
            ['Data ditemukan', String(info.found.length)],
            ['Server DNS', info.server ? info.server : 'bawaan sistem']
        ]);

        if (info.found.length) {
            addListHead(out.body, 'Hasil pencarian');
            if (info.name && info.name !== target) {
                addRow(out.body, 'Nama lengkap', info.name, true);
            }
            info.found.forEach(function (value, i) {
                addRow(out.body, info.found.length > 1 ? info.short + ' ' + (i + 1) : info.short, value, false);
            });
        }

        addRawLog(out.body, res.output);

        resultBox.appendChild(out.card);
        publish();
    }

    /* ══ 4 · SCAN PORT ══════════════════════════════════ */

    function readScan(res) {
        const open = [];
        String(res.output || '').split('\n').forEach(function (raw) {
            const m = raw.match(/^\s*(\d+)\s+open\s*$/i);
            if (m) { open.push(parseInt(m[1], 10)); }
        });
        open.sort(function (a, b) { return a - b; });
        return open;
    }

    function renderScan(res, target) {
        const open  = readScan(res);
        const first = readPort('toolFirstPort', 1);
        const last  = readPort('toolLastPort', 512);

        const out = open.length
            ? makeCard('warning', open.length + ' port terbuka ditemukan', res, target)
            : makeCard('up', 'Tidak ada port terbuka di rentang ini', res, target);

        addAnalysis(out.body, analysisOf(res, open.length ? 'warning' : 'up'));

        if (open.length) {
            addText(out.body, 'Ada ' + open.length + ' pintu yang bisa dimasuki orang lain pada port ' + first + ' sampai ' + last
                + ', ' + whenText(res) + ' dengan lama ' + tookText(res) + '.');
            addText(out.body, 'Tiap port terbuka adalah kesempatan orang masuk ke perangkat ini. Cocokkan dengan kebutuhan: port yang tidak dipakai sebaiknya ditutup, dan layanan yang terbuka sebaiknya memakai HTTPS/SSH, bukan versi tanpa sandi.');
        } else {
            addText(out.body, 'Semua port dari ' + first + ' sampai ' + last + ' tertutup, ' + whenText(res)
                + ' dengan lama ' + tookText(res) + '. Dari sisi port, perangkat ini aman pada rentang tersebut.');
        }

        addMetrics(out.body, [
            ['Port terbuka', String(open.length)],
            ['Rentang dicek', first + '–' + last],
            ['Lama cek', tookText(res)]
        ]);

        if (open.length) {
            addListHead(out.body, 'Rincian port yang terbuka');
            open.forEach(function (port) {
                addRow(out.body, 'Port ' + port, PORT_NAME[port] || 'layanan tidak dikenal', true);
            });
        }

        addNoteText(out.body, 'Hanya rentang ' + first + '–' + last + ' yang diperiksa. Port di luar rentang itu belum dicek.');

        addRawLog(out.body, res.output);

        resultBox.appendChild(out.card);
        publish();
    }

    /* ══ PESAN GAGAL ════════════════════════════════════ */

    const FRIENDLY_ERROR = [
        { find: /Rentang port maksimal/i, text: 'Rentang port yang diminta terlalu luas. Maksimal 1024 port sekaligus — perkecil angka "Port sampai" di panel pengaturan pemeriksaan.' },
        { find: /Port harus berada/i,       text: 'Nomor port tidak valid. Gunakan angka antara 1 sampai 65535.' },
        { find: /lebih kecil atau sama/i,   text: '"Port dari" tidak boleh lebih besar dari "Port sampai". Tukar dulu angkanya di panel pengaturan pemeriksaan.' },
        { find: /Scan timeout/i,            text: 'Waktu tunggu tiap port harus antara 100 sampai 2000 milidetik.' },
        { find: /Scan first port/i,         text: '"Port dari" tidak boleh lebih besar dari "Port sampai". Tukar dulu angkanya di panel pengaturan pemeriksaan.' },
        { find: /Ping count/i,              text: 'Jumlah cek Ping harus antara 1 sampai 100.' },
        { find: /Trace max hops/i,          text: 'Maksimal hop Trace harus antara 1 sampai 64.' },
        { find: /Lookup type/i,             text: 'Jenis info yang dicari belum dikenali. Pilih ulang di panel pengaturan pemeriksaan.' },
        { find: /tidak tersedia di server/i, text: 'Perangkat ini tidak punya alat yang dibutuhkan di server. Hubungi administrator jaringan.' },
        { find: /tidak valid/i,             text: 'Alamat yang diketik tidak dikenali. Isi dengan alamat IP (misal 8.8.8.8) atau nama domain (misal google.com).' },
        { find: /csrf|token/i,              text: 'Sesi halaman sudah habis. Muat ulang halaman lalu coba lagi.' }
    ];

    function friendlyError(message) {
        const text = String(message || '');
        for (let i = 0; i < FRIENDLY_ERROR.length; i++) {
            if (FRIENDLY_ERROR[i].find.test(text)) { return FRIENDLY_ERROR[i].text; }
        }
        return text || 'Pemeriksaan tidak bisa dijalankan.';
    }

    function renderError(message, res, target) {
        const out = makeCard('error', 'Pemeriksaan tidak bisa dijalankan', res, target);

        // Backend sudah menyertakan kotak analisisnya sendiri (status + alasan),
        // termasuk untuk respons 422. Kalau tidak ada, tampilkan fallback
        // supaya kolom "Alasan" tidak pernah kosong di layar.
        addAnalysis(out.body, analysisOf(res, 'error',
            message || 'Pemeriksaan tidak bisa dijalankan. Server tidak memberikan jawaban apa pun '
            + 'untuk target ini, jadi status perangkat belum bisa dipastikan.'));

        addText(out.body, friendlyError(message));

        if (res && (res.checked_at || res.duration_ms !== undefined)) {
            addText(out.body, 'Permintaan ini ' + whenText(res) + ' dengan lama ' + tookText(res) + '.');
        }
        if (res && res.network_fault) {
            addText(out.body, 'Ini bukan masalah program, melainkan masalah jaringan atau nama domain di target itu sendiri.');
        }
        if (res && res.timedout) {
            addText(out.body, 'Waktu tunggu sudah habis sebelum target menjawab. Coba lagi dengan pengaturan yang lebih ringan.');
        }

        addRawLog(out.body, res && res.output);

        resultBox.appendChild(out.card);
        publish();
    }

    /* ══ MENJALANKAN PEMERIKSAAN ═══════════════════════ */

    const RENDERERS = {
        ping:   renderPing,
        trace:  renderTrace,
        lookup: renderLookup,
        scan:   renderScan
    };

    const ACTION_TITLE = {
        ping:   'Memeriksa perangkat…',
        trace:  'Melacak jalur data…',
        lookup: 'Mencari informasi nama…',
        scan:   'Memeriksa port terbuka…'
    };

    function setBusy(on) {
        busy = on;
        form.querySelectorAll('button[data-action]').forEach(function (btn) {
            btn.disabled = on;
        });
    }

    function execute(action, target, deviceId) {
        if (busy) { return; }

        clearResult();
        const loading = makeCard('info', ACTION_TITLE[action] || 'Memeriksa…');
        addText(loading.body, 'Mohon tunggu sebentar, server sedang mengecek ' + target + '.');
        resultBox.appendChild(loading.card);
        publish();

        actionBox.value = action;
        toolTarget.value = target;

        const body = new FormData(form);
        body.set('target', target);
        body.set('action', action);
        body.set('device_id', deviceId || '');

        setBusy(true);

        fetch(routes[action], {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf ? csrf.content : '' },
            body: body
        })
        .then(function (r) {
            return r.json().then(function (data) {
                return [r.status, data];
            });
        })
        .catch(function () {
            return [0, { success: false, message: 'Tidak dapat menghubungi server. Periksa koneksi internet Anda lalu coba lagi.' }];
        })
        .then(function (payload) {
            setBusy(false);

            const status = payload[0];
            const res    = payload[1] || {};
            const text   = String(res.output || '').trim();

            if (status === 0) {
                renderError('Tidak dapat menghubungi server. Periksa koneksi internet lalu coba lagi.', res, target);
                return;
            }
            if (status === 419) {
                renderError('Sesi halaman sudah habis. Muat ulang halaman lalu coba lagi.', res, target);
                return;
            }
            if (status === 422) {
                renderError(res.message || 'Isian tidak sesuai.', res, target);
                return;
            }
            if (res.timedout && text === '') {
                renderError('Waktu tunggu habis sebelum target menjawab.', res, target);
                return;
            }
            if ((res.exit === 126 || res.exit === 127) && text === '') {
                renderError('Server ini tidak punya alat untuk pemeriksaan ini. Hubungi administrator jaringan.', res, target);
                return;
            }

            // Ping dengan 100% paket hilang tetap hasil pengukuran yang sah
            // (perangkat DOWN), dan Scan tanpa output berarti memang tidak ada
            // port terbuka — keduanya tetap diringkas, bukan dianggap error.
            const worthShowing = text !== '' || (action === 'scan' && res.success);

            if (!worthShowing) {
                renderError(res.message || 'Server tidak memberikan jawaban apa pun untuk target ini. Alamatnya mungkin salah atau target sedang tidak aktif.', res, target);
                return;
            }

            (RENDERERS[action] || renderPing)(res, target);
        });
    }

    /* ══ TARGET AKTIF ═══════════════════════════════════
       Tiga sumber target, urut dari atas ke bawah di panel kanan:
         1. Server Radnet (Lokal)  — tombol paling atas
         2. Perangkat Radnet       — diklik di panel kiri
         3. Alamat dari luar       — diketik sendiri di kotak paling bawah
       Semua tombol aksi membaca target yang aktif di sini, jadi hanya ada
       satu alamat yang berlaku di satu waktu.
       Token LOCAL_TARGET sudah dideklarasikan di atas bersama konstanta
       halaman lain, jadi di sini tidak perlu diulang. */

    /* Ikon kartu target mengikuti asal target: server lokal memakai ikon
       server, perangkat terpilih memakai ikon tipenya, target luar memakai
       ikon globe. Tanpa ini, kartu target selalu menampilkan satu ikon yang
       sama padahal target-nya sudah berganti. */
    function setTargetIcon(icon) {
        if (!targetIcon) { return; }
        targetIcon.className = 'icon ni ' + (icon || 'ni-server');
    }

    function markLocalActive(on) {
        localBtn.classList.toggle('is-active', on);
        localBtn.setAttribute('aria-pressed', on ? 'true' : 'false');
    }

    function setLocalTarget() {
        selectedItem = null;
        deviceItems.forEach(function (d) {
            d.classList.remove('active');
            d.setAttribute('aria-pressed', 'false');
        });
        if (devicePicker) { devicePicker.value = ''; }

        markLocalActive(true);

        toolTarget.value   = LOCAL_TARGET;
        toolDeviceId.value = '';
        if (customInput) { customInput.value = ''; }

        targetCard.classList.remove('is-empty');
        setTargetIcon('ni-server');
        targetName.textContent = 'Server Radnet (Server Lokal)';
        targetIp.textContent   = 'Diperiksa langsung dari server NOC';
        targetStatus.hidden    = true;
        targetMeta.hidden      = true;
    }

    function setExternalTarget(host) {
        selectedItem = null;
        deviceItems.forEach(function (d) {
            d.classList.remove('active');
            d.setAttribute('aria-pressed', 'false');
        });
        if (devicePicker) { devicePicker.value = ''; }

        markLocalActive(false);

        toolTarget.value   = host;
        toolDeviceId.value = '';

        targetCard.classList.remove('is-empty');
        setTargetIcon('ni-globe');
        targetName.textContent = 'Target dari luar';
        targetIp.textContent   = host + ' · di luar daftar jaringan Radnet';
        targetStatus.hidden    = true;
        targetMeta.hidden      = true;
    }

    localBtn.addEventListener('click', function () {
        setLocalTarget();
        showEmpty();
        setNotice('Target aktif: Server Radnet (Server Lokal). Tekan salah satu tombol di bawah untuk mulai memeriksa.');
    });

    /* Alamat yang diketik manual berarti pengguna memilih target sendiri,
       jadi data perangkat dilepas dan kartu "Asal target" diperbarui supaya
       hasilnya tidak dikira milik perangkat Radnet yang tadi diklik. */
    customInput.addEventListener('input', function () {
        const host = customInput.value.trim();

        if (!host) {
            // Kolom dikosongkan = kembali ke Server Radnet (Lokal), karena
            // itu target yang selalu tersedia.
            if (toolTarget.value === LOCAL_TARGET) { return; }
            setLocalTarget();
            setNotice('Kolom IP dikosongkan. Target kembali ke Server Radnet (Server Lokal).');
            return;
        }

        if (host === toolTarget.value) { return; }
        setExternalTarget(host);
        flashTargetSync();
    });

    form.querySelectorAll('button[data-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const action = btn.getAttribute('data-action');
            const target = toolTarget.value.trim();

            if (!target) {
                customInput.focus();
                renderError('Pilih Server Radnet (Server Lokal), klik salah satu perangkat di panel kiri, atau isi kolom IP di bagian target dari luar.');
                return;
            }

            execute(action, target, toolDeviceId.value);
        });
    });

    customInput.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') { return; }
        e.preventDefault();

        const host = customInput.value.trim();
        if (!host) { customInput.focus(); return; }

        execute('ping', host, '');
    });

    /* ══ DAFTAR PERANGKAT ═══════════════════════════════ */

    /**
     * Sorot singkat pada kotak target setelah diisi.
     *
     * Fungsinya memberi tahu pengguna bahwa klik di panel kiri benar-benar
     * tersinkron ke panel kanan — tanpa sorotan, proses ini hanya terlihat dari
     * teks yang berganti, yang mudah terlewat.
     */
    function flashTargetSync() {
        targetCard.classList.remove('is-synced');
        // reflow dipaksakan supaya animasi bisa diputar ulang setiap kali
        // perangkat lain diklik.
        void targetCard.offsetWidth;
        targetCard.classList.add('is-synced');
    }

    function selectDevice(item) {
        deviceItems.forEach(function (d) {
            d.classList.remove('active');
            d.setAttribute('aria-pressed', 'false');
        });
        item.classList.add('active');
        item.setAttribute('aria-pressed', 'true');
        selectedItem = item;
        if (devicePicker) {
            devicePicker.value = item.getAttribute('data-id') || '';
        }

        // Trim dipakai di semua bacaan: spasi sisa dari kolom IP di daftar
        // perangkat akan membuat alamat yang dikirim tidak cocok dengan
        // database, dan kotak target akan menampilkan alamat yang berbeda dari
        // yang benar-benar diuji.
        const ip     = (item.getAttribute('data-ip') || '').trim();
        const name   = (item.getAttribute('data-name') || '').trim();
        const status = item.getAttribute('data-status') || 'up';
        const delay  = num(item.getAttribute('data-delay'));
        const loss   = num(item.getAttribute('data-loss'));
        /* data-type diisi dari peta ToolsController::DEVICE_TYPES, jadi nama
           ikon di sini selalu ada di font ikon tema. */
        const icon   = (item.getAttribute('data-type') || '').trim();

        targetCard.classList.remove('is-empty');
        markLocalActive(false);

        /* Perangkat tanpa alamat IP tidak bisa jadi target: tidak ada yang
           bisa di-ping darinya. Daripada diam-diam meninggalkan target kosong
           (tombol aksi nanti akan gagal dengan pesan yang membingungkan), isi
           kotak target dengan penjelasan yang jelas. */
        if (ip === '') {
            selectedItem = null;
            item.classList.remove('active');
            item.setAttribute('aria-pressed', 'false');

            toolTarget.value   = '';
            toolDeviceId.value = '';
            if (customInput) { customInput.value = ''; }

            setTargetIcon(num(icon) ? icon : 'ni-help');
            targetName.textContent = name || 'Perangkat tanpa nama';
            targetIp.textContent   = 'Belum punya alamat IP, jadi belum bisa diperiksa';
            targetStatus.hidden    = true;
            targetMeta.hidden      = true;

            showEmpty();
            renderError(
                'Perangkat "' + (name || 'tanpa nama') + '" belum punya alamat IP, jadi belum bisa diperiksa. '
                + 'Isi kolom IP atau nama host di bagian "Target dari Luar" untuk mengujinya.',
                null,
                ''
            );
            return;
        }

        // Hanya ada satu alamat yang berlaku: perangkat yang diklik. Kolom
        // "Target dari Luar" sengaja dikosongkan supaya isinya tidak
        // bercampur dengan target internal.
        toolTarget.value   = ip;
        toolDeviceId.value = (item.getAttribute('data-id') || '').trim();
        if (customInput) { customInput.value = ''; }

        setTargetIcon(icon);
        targetName.textContent = name || ip;
        targetIp.textContent   = ip;
        targetStatus.hidden    = false;
        targetStatus.className = 'status-badge status-' + status;
        targetStatus.textContent = status.toUpperCase();

        targetMeta.hidden    = false;
        targetDelay.textContent = delay === null || delay <= 0 ? 'belum ada data' : trimNum(delay) + ' ms';
        targetLoss.textContent  = loss === null ? 'belum ada data' : trimNum(loss) + ' %';

        flashTargetSync();
        setNotice('Target aktif: ' + (name || ip) + ' (' + ip + '). Tekan salah satu tombol di bawah untuk mulai memeriksa.');
    }

    deviceList.addEventListener('click', function (e) {
        const item = e.target.closest('.device-item');
        if (item) { selectDevice(item); }
    });

    /* Pemilih 115 perangkat di panel kanan. Opsinya membawa data yang sama
       dengan baris di panel kiri (data-id) dan dipakai lewat jalur yang sama
       (selectDevice), jadi memilih dari dropdown dan mengklik di daftar
       menghasilkan target, ikon, dan status yang persis sama. */
    if (devicePicker) {
        devicePicker.addEventListener('change', function () {
            const id = this.value;
            if (!id) { return; }
            const item = deviceItems.find(function (d) {
                return d.getAttribute('data-id') === id;
            });
            if (item) {
                selectDevice(item);
                item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }
        });
    }

    /* Daftar perangkat sudah berupa <button>, jadi Enter dan Space sudah
       bekerja. Yang ditambah di sini adalah panah atas/bawah supaya pengguna
       bisa pindah pilihan tanpa mouse, dan tiap pilihan langsung terbaca
       oleh screen reader lewat aria-pressed. */
    deviceList.addEventListener('keydown', function (e) {
        if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') { return; }

        const visible = deviceItems.filter(function (d) { return d.style.display !== 'none'; });
        if (!visible.length) { return; }

        e.preventDefault();

        const current = visible.indexOf(selectedItem);
        const next = e.key === 'ArrowDown'
            ? visible[(current + 1 + visible.length) % visible.length]
            : visible[(current - 1 + visible.length) % visible.length];

        selectDevice(next);
        next.focus();
    });

    function applyFilters() {
        const q = deviceSearch.value.trim().toLowerCase();
        let shown = 0;

        deviceItems.forEach(function (d) {
            const name = (d.getAttribute('data-name') || '').toLowerCase();
            const ip   = (d.getAttribute('data-ip') || '').toLowerCase();
            const okSearch = q === '' || name.indexOf(q) !== -1 || ip.indexOf(q) !== -1;
            const okStatus = activeFilter === 'all' || (d.getAttribute('data-status') || 'up') === activeFilter;
            const show = okSearch && okStatus;
            d.style.display = show ? '' : 'none';
            if (show) { shown++; }
        });

        let none = deviceList.querySelector('.device-empty-none');
        if (shown === 0) {
            if (!none) {
                none = el('div', 'device-empty device-empty-none',
                    'Tidak ada perangkat yang cocok. Coba kata kunci lain atau ganti filter status.');
                deviceList.appendChild(none);
            }
            none.style.display = '';
        } else if (none) {
            none.style.display = 'none';
        }

        deviceHint.textContent = shown === totalDevices
            ? totalDevices + ' perangkat'
            : shown + ' dari ' + totalDevices + ' perangkat';
    }

    deviceSearch.addEventListener('input', applyFilters);

    statusFilter.addEventListener('click', function (e) {
        const btn = e.target.closest('.tool-filter-btn');
        if (!btn) { return; }
        activeFilter = btn.getAttribute('data-status');
        statusFilter.querySelectorAll('.tool-filter-btn').forEach(function (b) {
            b.classList.toggle('active', b === btn);
        });
        applyFilters();
    });

    /* ══ TOMBOL KELUAR ══════════════════════════════════ */

    document.getElementById('btnResultClear').addEventListener('click', showEmpty);

    document.getElementById('btnResultCopy').addEventListener('click', function () {
        const text = resultBox.innerText.trim();
        if (!text) { return; }

        const btn = this;
        const done = function () {
            const old = btn.innerHTML;
            btn.innerHTML = '<em class="icon ni ni-check-thick me-1"></em>Tersalin';
            setTimeout(function () { btn.innerHTML = old; }, 1800);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(done, function () {});
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            done();
        }
    });

    /* ══ MULAI ═════════════════════════════════════════ */

    /* Target default adalah Server Radnet (Server Lokal): pilihan paling
       sering dipakai di panel kanan, jadi halaman siap dipakai tanpa
       perlu menggulir daftar perangkat dulu. */
    setLocalTarget();

    /* Daftar perangkat kosong: Blade sudah menanganinya sendiri lewat blok
       forelse/empty di dalam #deviceList, jadi tidak ada badge atau kartu lagi
       yang perlu disembunyikan dari JavaScript. */
})();
</script>

{{-- Skrip tab pemantauan ping, dibungkus IIFE sendiri supaya tidak
     menabrak variabel di atas yang memang hidup di halaman yang sama. --}}
@include('admin.ping-monitor._script')
@endpush
