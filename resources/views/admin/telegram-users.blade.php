@extends('layouts.admin')

@section('title', 'Telegram Bot · RADNET NOC')

@push('styles')
<style>
/* ── PAGE ── */
body, .nk-main { background: #f1f5fa; }

/* ── LAYOUT GRID ── */
.tg-layout { display: grid; gap: 20px; }
.tg-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media (max-width: 1024px) { .tg-two-col { grid-template-columns: 1fr; } }

/* ── CARD ── */
.tg-card {
    background: #fff;
    border: 1px solid #e2e8f2;
    border-radius: 14px;
    overflow: hidden;
}
.tg-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f3f8;
    background: #fafcff;
}
.tg-card-header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.tg-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.tg-card-title { font-size: 13.5px; font-weight: 700; color: #17233d; margin: 0; }
.tg-card-sub { font-size: 11.5px; color: #8492a6; margin: 0; }
.tg-card-body { padding: 20px; }

/* ── KPI GRID ── */
.tg-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
@media (max-width: 1100px) { .tg-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px) { .tg-kpi-grid { grid-template-columns: 1fr 1fr; } }

.tg-kpi {
    background: #fff;
    border: 1px solid #e2e8f2;
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    transition: box-shadow .15s;
}
.tg-kpi:hover { box-shadow: 0 4px 16px rgba(0,0,0,.06); }
.tg-kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.tg-kpi-body { flex: 1; min-width: 0; }
.tg-kpi-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #8492a6; margin-bottom: 4px; }
.tg-kpi-value { font-size: 26px; font-weight: 800; color: #1c2b4a; line-height: 1; }
.tg-kpi-sub { font-size: 11px; color: #a0aec0; margin-top: 3px; }

/* ── BOT STATUS BADGE ── */
.bot-status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 6px;
    flex-shrink: 0;
}
.bot-status-online { background: #22c55e; box-shadow: 0 0 0 2px rgba(34,197,94,.3); }
.bot-status-offline { background: #ef4444; box-shadow: 0 0 0 2px rgba(239,68,68,.3); }
.bot-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}
.bot-badge-online { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.bot-badge-offline { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.bot-badge-unknown { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

/* ── SETTINGS FORM ── */
.tg-form-row { display: grid; gap: 14px; }
.tg-form-group { display: flex; flex-direction: column; gap: 5px; }
.tg-form-label {
    font-size: 11.5px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.tg-input {
    width: 100%;
    background: #f8fafc;
    border: 1px solid #dde3ee;
    border-radius: 9px;
    padding: 8px 12px;
    font-size: 13px;
    color: #17233d;
    transition: border-color .15s, box-shadow .15s;
    font-family: 'SFMono-Regular', monospace;
}
.tg-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    background: #fff;
}
.tg-input-help { font-size: 11px; color: #94a3b8; }
.tg-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; padding-top: 6px; }

/* ── WEBHOOK PANEL ── */
.webhook-url-badge {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12px;
    color: #166534;
    font-family: monospace;
    word-break: break-all;
}
.webhook-url-badge.inactive {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #64748b;
}

/* ── TABLE ── */
.tg-table th, .tg-table td { vertical-align: middle; font-size: 12px; }
.tg-user-name { font-weight: 700; color: #17233d; font-size: 12.5px; }
.tg-muted { color: #8492a6; font-size: 11.5px; }
.tg-row-pending { background: #fffaf0; }
.tg-tag {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 3px 9px;
    font-size: 10.5px;
    font-weight: 700;
    gap: 4px;
}
.tg-tag-pending { background: #fef3c7; color: #92400e; }
.tg-tag-staff { background: #dbeafe; color: #1d4ed8; }
.tg-tag-admin { background: #ede9fe; color: #6d28d9; }
.tg-tag-super_admin { background: #fce7f3; color: #be185d; }
.tg-tag-approved { background: #dcfce7; color: #15803d; }
.tg-tag-not-approved { background: #fef3c7; color: #b45309; }
.tg-chat-id { font-family: monospace; color: #52627d; font-size: 11px; }
.tg-row-actions { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; justify-content: flex-end; }
.tg-role-form { display: inline-flex; gap: 5px; align-items: center; }
.tg-role-form .form-select { min-width: 110px; font-size: 11px; padding: 5px 24px 5px 8px; border-color: #dde3ee; }
.tg-empty { padding: 40px 20px; text-align: center; color: #94a3b8; }
.tg-empty-icon { font-size: 40px; opacity: .35; margin-bottom: 10px; }
.tg-log-msg { max-width: 340px; white-space: normal; word-break: break-word; }

/* ── SPINNER ── */
.spin { animation: spin .8s linear infinite; display: inline-block; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── FILTER ROW ── */
.tg-filter { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
.tg-filter .form-control, .tg-filter .form-select {
    border-color: #dde3ee;
    font-size: 12.5px;
    border-radius: 9px;
}

/* ── CONNECTION TEST ── */
#testConnectionResult {
    transition: all .2s;
    font-size: 12.5px;
}

/* ── SPIN ANIMATION ── */
@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
</style>
@endpush

@section('content')

{{-- PAGE HEAD --}}
<x-admin.page-head
    title="Telegram Bot"
    subtitle="Kelola koneksi bot, pengaturan kunci API, user yang bergabung, dan broadcast notifikasi."
    icon="telegram"
    icon-class="is-telegram"
    :crumbs="[['label' => 'Telegram Bot']]"
>
    <x-slot:actions>
        <a href="{{ route('admin.telegram-users') }}" class="btn btn-sm btn-outline-secondary">
            <em class="icon ni ni-reload"></em>
            <span>Refresh</span>
        </a>
    </x-slot:actions>
</x-admin.page-head>

{{-- ALERTS --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <em class="icon ni ni-check-circle me-1"></em> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <em class="icon ni ni-alert-circle me-1"></em> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <em class="icon ni ni-alert-circle me-1"></em>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="tg-layout">

    {{-- ═══════════════════════════════════════════════════════════
         KPI STATS
    ═══════════════════════════════════════════════════════════ --}}
    <div class="tg-kpi-grid">
        {{-- Total User --}}
        <div class="tg-kpi">
            <div class="tg-kpi-icon" style="background:#e0f2fe;color:#0284c7;">
                <em class="icon ni ni-users"></em>
            </div>
            <div class="tg-kpi-body">
                <div class="tg-kpi-label">Total User</div>
                <div class="tg-kpi-value">{{ $kpi['total'] }}</div>
                <div class="tg-kpi-sub">bergabung ke bot</div>
            </div>
        </div>

        {{-- Disetujui --}}
        <div class="tg-kpi">
            <div class="tg-kpi-icon" style="background:#dcfce7;color:#16a34a;">
                <em class="icon ni ni-check-circle"></em>
            </div>
            <div class="tg-kpi-body">
                <div class="tg-kpi-label">Disetujui</div>
                <div class="tg-kpi-value">{{ $kpi['approved'] }}</div>
                <div class="tg-kpi-sub">
                    @if(($kpi['super_admin'] ?? 0) + ($kpi['admin'] ?? 0) + ($kpi['staff'] ?? 0) > 0)
                        {{ $kpi['super_admin'] ?? 0 }} SA · {{ $kpi['admin'] ?? 0 }} Admin · {{ $kpi['staff'] ?? 0 }} Staff
                    @else
                        user aktif
                    @endif
                </div>
            </div>
        </div>

        {{-- Menunggu --}}
        <div class="tg-kpi">
            <div class="tg-kpi-icon" style="background:#fef3c7;color:#d97706;">
                <em class="icon ni ni-clock"></em>
            </div>
            <div class="tg-kpi-body">
                <div class="tg-kpi-label">Menunggu</div>
                <div class="tg-kpi-value">{{ $kpi['pending'] }}</div>
                <div class="tg-kpi-sub">perlu persetujuan</div>
            </div>
        </div>

        {{-- Aktif Hari Ini --}}
        <div class="tg-kpi">
            <div class="tg-kpi-icon" style="background:#ede9fe;color:#7c3aed;">
                <em class="icon ni ni-activity"></em>
            </div>
            <div class="tg-kpi-body">
                <div class="tg-kpi-label">Aktif Hari Ini</div>
                <div class="tg-kpi-value">{{ $kpi['active_today'] }}</div>
                <div class="tg-kpi-sub">interaksi bot hari ini</div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         ROW: Bot Settings + Bot Status / Webhook Info
    ═══════════════════════════════════════════════════════════ --}}
    <div class="tg-two-col">

        {{-- ── Panel Pengaturan Kunci Bot ── --}}
        <div class="tg-card">
            <div class="tg-card-header">
                <div class="tg-card-header-left">
                    <div class="tg-card-icon" style="background:#e0f2fe;color:#0369a1;">
                        <em class="icon ni ni-setting-alt"></em>
                    </div>
                    <div>
                        <div class="tg-card-title">Konfigurasi Bot</div>
                        <div class="tg-card-sub">Token API, Chat ID default, Webhook Secret</div>
                    </div>
                </div>
            </div>
            <div class="tg-card-body">
                <form method="POST" action="{{ route('admin.telegram-users.settings') }}" id="formSettings">
                    @csrf
                    <div class="tg-form-row">
                        <div class="tg-form-group">
                            <label class="tg-form-label">Bot Token <span class="text-danger">*</span></label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input
                                    type="password"
                                    id="botTokenInput"
                                    name="bot_token"
                                    class="tg-input"
                                    value="{{ $botToken ?? '' }}"
                                    placeholder="123456789:ABCdef..."
                                    autocomplete="off"
                                >
                                <button type="button" onclick="toggleVisible('botTokenInput', this)"
                                    class="btn btn-sm btn-outline-secondary" style="flex-shrink:0;border-radius:8px;">
                                    <em class="icon ni ni-eye"></em>
                                </button>
                            </div>
                            <div class="tg-input-help">
                                Dapatkan token dari <a href="https://t.me/BotFather" target="_blank" rel="noopener">@BotFather</a> di Telegram.
                            </div>
                        </div>

                        <div class="tg-form-group">
                            <label class="tg-form-label">Default Chat ID</label>
                            <input
                                type="text"
                                name="chat_id"
                                class="tg-input"
                                value="{{ $chatId ?? '' }}"
                                placeholder="-100123456789 atau 987654321"
                                autocomplete="off"
                            >
                            <div class="tg-input-help">
                                Chat ID grup/channel atau user penerima notifikasi default.
                                Kirim <code>/start</code> ke bot lalu cek log di bawah untuk mendapatkan ID Anda.
                            </div>
                        </div>

                        <div class="tg-form-group">
                            <label class="tg-form-label">Webhook Secret <small class="fw-normal text-muted">(opsional)</small></label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input
                                    type="password"
                                    id="webhookSecretInput"
                                    name="webhook_secret"
                                    class="tg-input"
                                    value="{{ $webhookSecret ?? '' }}"
                                    placeholder="Kata sandi rahasia webhook..."
                                    autocomplete="off"
                                >
                                <button type="button" onclick="toggleVisible('webhookSecretInput', this)"
                                    class="btn btn-sm btn-outline-secondary" style="flex-shrink:0;border-radius:8px;">
                                    <em class="icon ni ni-eye"></em>
                                </button>
                            </div>
                            <div class="tg-input-help">Secret token untuk validasi request webhook dari Telegram.</div>
                        </div>

                        <div class="tg-actions">
                            <button type="submit" class="btn btn-primary btn-sm" id="btnSaveSettings">
                                <em class="icon ni ni-save"></em> Simpan Pengaturan
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" id="btnTestConnection" onclick="testConnection()">
                                <em class="icon ni ni-plug"></em> Uji Koneksi
                            </button>
                        </div>

                        <div id="testConnectionResult" style="display:none;">
                            {{-- filled by JS --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Panel Status Bot + Webhook ── --}}
        <div style="display:flex;flex-direction:column;gap:16px;">

            {{-- Bot Info / Status --}}
            <div class="tg-card">
                <div class="tg-card-header">
                    <div class="tg-card-header-left">
                        <div class="tg-card-icon" style="background:#f0fdf4;color:#16a34a;">
                            <em class="icon ni ni-telegram"></em>
                        </div>
                        <div>
                            <div class="tg-card-title">Status Bot</div>
                            <div class="tg-card-sub">Profil &amp; koneksi bot saat ini</div>
                        </div>
                    </div>
                    @php $isOnline = $botProfile['ok'] ?? false; @endphp
                    <span class="bot-badge {{ $isOnline ? 'bot-badge-online' : ($botToken ? 'bot-badge-offline' : 'bot-badge-unknown') }}">
                        <span class="bot-status-dot {{ $isOnline ? 'bot-status-online' : 'bot-status-offline' }}"></span>
                        {{ $isOnline ? 'Online' : ($botToken ? 'Offline' : 'Belum dikonfigurasi') }}
                    </span>
                </div>
                <div class="tg-card-body">
                    @if($isOnline && !empty($botProfile['bot']))
                        @php $bot = $botProfile['bot']; @endphp
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                            <div>
                                <div class="tg-form-label">Nama Bot</div>
                                <div style="font-size:13px;font-weight:600;color:#17233d;">{{ $bot['first_name'] ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="tg-form-label">Username</div>
                                <div style="font-size:13px;color:#17233d;">
                                    <a href="https://t.me/{{ $bot['username'] ?? '' }}" target="_blank" rel="noopener" class="text-primary">
                                    {{ '@'.($bot['username'] ?? '-') }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <div class="tg-form-label">Bot ID</div>
                                <div class="tg-chat-id">{{ $bot['id'] ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="tg-form-label">Bisa Bergabung Grup</div>
                                <div style="font-size:12px;">
                                    @if($bot['can_join_groups'] ?? false)
                                        <span class="tg-tag tg-tag-approved"><em class="icon ni ni-check"></em> Ya</span>
                                    @else
                                        <span class="tg-tag tg-tag-not-approved">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('admin.telegram-users.test-send') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success" data-confirm="Kirim pesan uji coba ke default Chat ID?">
                                    <em class="icon ni ni-send"></em> Kirim Pesan Uji Coba
                                </button>
                            </form>
                        </div>
                    @elseif($botToken)
                        <div class="tg-empty" style="padding:20px;">
                            <div class="tg-empty-icon"><em class="icon ni ni-alert-circle"></em></div>
                            <div style="font-size:13px;font-weight:600;color:#374151;">Bot tidak dapat terhubung</div>
                            <div style="font-size:12px;color:#94a3b8;margin-top:4px;">{{ $botProfile['description'] ?? 'Periksa token bot Anda.' }}</div>
                        </div>
                    @else
                        <div class="tg-empty" style="padding:20px;">
                            <div class="tg-empty-icon"><em class="icon ni ni-telegram"></em></div>
                            <div style="font-size:13px;font-weight:600;color:#374151;">Bot belum dikonfigurasi</div>
                            <div style="font-size:12px;color:#94a3b8;margin-top:4px;">Masukkan Bot Token di panel konfigurasi untuk menghubungkan bot.</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Webhook / Long Poll Info --}}
            <div class="tg-card">
                <div class="tg-card-header">
                    <div class="tg-card-header-left">
                        <div class="tg-card-icon" style="background:#f0f4ff;color:#4f46e5;">
                            <em class="icon ni ni-link-alt"></em>
                        </div>
                        <div>
                            <div class="tg-card-title">Mode Koneksi Bot</div>
                            <div class="tg-card-sub">Webhook atau Long Polling</div>
                        </div>
                    </div>
                </div>
                <div class="tg-card-body">
                    @php
                        $whInfo = $webhookInfo['info'] ?? null;
                        $whUrl = $whInfo['url'] ?? '';
                        $hasWebhook = !empty($whUrl);
                    @endphp

                    @if($hasWebhook)
                        <div class="mb-3">
                            <div class="tg-form-label mb-1">URL Webhook Aktif</div>
                            <div class="webhook-url-badge">{{ $whUrl }}</div>
                        </div>
                        @if(!empty($whInfo['last_error_message']))
                            <div class="alert alert-warning py-2 px-3" style="font-size:12px;border-radius:9px;">
                                <em class="icon ni ni-alert-circle me-1"></em>
                                Error terakhir: {{ $whInfo['last_error_message'] }}
                            </div>
                        @endif
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <form method="POST" action="{{ route('admin.telegram-users.delete-webhook') }}" data-confirm="Hapus webhook dan beralih ke Long Polling?">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                    <em class="icon ni ni-trash"></em> Hapus Webhook
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="webhook-url-badge inactive">
                                <em class="icon ni ni-activity me-1"></em>
                                Mode: <strong>Long Polling</strong> — Jalankan <code>php artisan telegram:poll</code>
                            </div>
                        </div>
                        {{-- Atur Webhook manual --}}
                        <div style="font-size:12.5px;">
                            <button type="button"
                                onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none';this.querySelector('em').className=this.querySelector('em').className.includes('ni-plus')?this.querySelector('em').className.replace('ni-plus','ni-minus'):this.querySelector('em').className.replace('ni-minus','ni-plus')"
                                class="btn btn-sm btn-outline-secondary mb-2" style="font-size:12px;">
                                <em class="icon ni ni-plus me-1"></em> Atur Webhook (opsional)
                            </button>
                            <div style="display:none;">
                                <form method="POST" action="{{ route('admin.telegram-users.set-webhook') }}" class="mt-1">
                                    @csrf
                                    <div class="tg-form-group mb-2">
                                        <label class="tg-form-label">URL Webhook</label>
                                        <input type="url" name="webhook_url" class="tg-input" placeholder="https://yourdomain.com/telegram/webhook" required>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <em class="icon ni ni-link-alt"></em> Set Webhook
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         DAFTAR USER
    ═══════════════════════════════════════════════════════════ --}}
    <div class="tg-card">
        <div class="tg-card-header">
            <div class="tg-card-header-left">
                <div class="tg-card-icon" style="background:#ede9fe;color:#7c3aed;">
                    <em class="icon ni ni-users"></em>
                </div>
                <div>
                    <div class="tg-card-title">User Bergabung</div>
                    <div class="tg-card-sub">
                        {{ $kpi['total'] }} user · {{ $kpi['approved'] }} disetujui · {{ $kpi['pending'] }} menunggu
                    </div>
                </div>
            </div>
            {{-- Filter Form --}}
            <form method="GET" action="{{ route('admin.telegram-users') }}" class="tg-filter">
                <input type="search" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari nama / chat ID…" style="min-width:170px;">
                <select name="status" class="form-select form-select-sm" style="min-width:130px;">
                    <option value="all" @selected($status === 'all')>Semua status</option>
                    <option value="pending" @selected($status === 'pending')>Menunggu</option>
                    <option value="approved" @selected($status === 'approved')>Disetujui</option>
                </select>
                <select name="role" class="form-select form-select-sm" style="min-width:120px;">
                    <option value="all" @selected($role === 'all')>Semua role</option>
                    <option value="super_admin" @selected($role === 'super_admin')>Super Admin</option>
                    <option value="admin" @selected($role === 'admin')>Admin</option>
                    <option value="staff" @selected($role === 'staff')>Staff</option>
                    <option value="pending" @selected($role === 'pending')>Pending</option>
                </select>
                <button class="btn btn-sm btn-primary" type="submit">
                    <em class="icon ni ni-search"></em>
                </button>
            </form>
        </div>
        <div style="overflow-x:auto;">
            <table class="table table-hover tg-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Chat ID</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Terakhir Aktif</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($telegramUsers as $telegramUser)
                        <tr class="{{ !$telegramUser->is_approved ? 'tg-row-pending' : '' }}">
                            <td>
                                <div class="tg-user-name">
                                    {{ trim(($telegramUser->first_name ?? '').' '.($telegramUser->last_name ?? '')) ?: ($telegramUser->username ?? 'Tanpa nama') }}
                                </div>
                                <div class="tg-muted">
                                    {{ $telegramUser->username ? '@'.ltrim($telegramUser->username, '@') : 'Tanpa username' }}
                                </div>
                            </td>
                            <td><span class="tg-chat-id">{{ $telegramUser->chat_id }}</span></td>
                            <td>
                                <span class="tg-tag tg-tag-{{ $telegramUser->role }}">
                                    {{ str_replace('_', ' ', ucfirst($telegramUser->role)) }}
                                </span>
                            </td>
                            <td>
                                <span class="tg-tag {{ $telegramUser->is_approved ? 'tg-tag-approved' : 'tg-tag-not-approved' }}">
                                    {{ $telegramUser->is_approved ? 'Disetujui' : 'Menunggu' }}
                                </span>
                            </td>
                            <td>
                                <span class="tg-muted">{{ $telegramUser->last_active_at?->diffForHumans() ?? 'Belum pernah' }}</span>
                            </td>
                            <td>
                                <div class="tg-row-actions">
                                    @if(!$telegramUser->is_approved)
                                        <form method="POST" action="{{ route('admin.telegram-users.approve', $telegramUser) }}">
                                            @csrf
                                            <button class="btn btn-xs btn-success" type="submit" title="Setujui">
                                                <em class="icon ni ni-check"></em> Setujui
                                            </button>
                                        </form>
                                    @endif
                                    <form class="tg-role-form" method="POST" action="{{ route('admin.telegram-users.set-role', $telegramUser) }}">
                                        @csrf
                                        <select name="role" class="form-select" aria-label="Role user">
                                            @foreach(['super_admin', 'admin', 'staff', 'pending'] as $roleOption)
                                                <option value="{{ $roleOption }}" @selected($telegramUser->role === $roleOption)>
                                                    {{ str_replace('_', ' ', ucfirst($roleOption)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-xs btn-outline-primary" type="submit">Simpan</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.telegram-users.destroy', $telegramUser) }}" data-confirm="Hapus user Telegram ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-xs btn-outline-danger" type="submit" title="Hapus">
                                            <em class="icon ni ni-trash"></em>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="tg-empty">
                                    <div class="tg-empty-icon"><em class="icon ni ni-users"></em></div>
                                    <div style="font-size:13px;font-weight:600;color:#374151;">Belum ada user Telegram</div>
                                    <div style="font-size:12px;color:#94a3b8;margin-top:4px;">User akan muncul setelah mengirim <code>/start</code> ke bot.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($telegramUsers->hasPages())
            <div class="p-3 border-top">
                {{ $telegramUsers->links() }}
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         ROW: Broadcast + Log
    ═══════════════════════════════════════════════════════════ --}}
    <div class="tg-two-col">

        {{-- Broadcast --}}
        <div class="tg-card">
            <div class="tg-card-header">
                <div class="tg-card-header-left">
                    <div class="tg-card-icon" style="background:#fff7ed;color:#ea580c;">
                        <em class="icon ni ni-send"></em>
                    </div>
                    <div>
                        <div class="tg-card-title">Broadcast ke User Disetujui</div>
                        <div class="tg-card-sub">Kirim pesan massal ke {{ $kpi['approved'] }} user disetujui</div>
                    </div>
                </div>
            </div>
            <div class="tg-card-body">
                <form method="POST" action="{{ route('admin.telegram-users.broadcast') }}" data-confirm="Kirim broadcast ke semua user yang disetujui?">
                    @csrf
                    <textarea
                        name="message"
                        class="form-control mb-3"
                        rows="5"
                        maxlength="4096"
                        placeholder="Tulis pesan broadcast Telegram di sini..."
                        required
                        style="border-radius:10px;font-size:13px;"
                    >{{ old('message') }}</textarea>
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                        <div style="font-size:11.5px;color:#94a3b8;">
                            Dikirim ke <strong>{{ $kpi['approved'] }}</strong> user disetujui
                        </div>
                        <button class="btn btn-primary btn-sm" type="submit">
                            <em class="icon ni ni-send"></em> Kirim Broadcast
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Log Activity --}}
        <div class="tg-card">
            <div class="tg-card-header">
                <div class="tg-card-header-left">
                    <div class="tg-card-icon" style="background:#f1f5f9;color:#64748b;">
                        <em class="icon ni ni-clock"></em>
                    </div>
                    <div>
                        <div class="tg-card-title">Log Aktivitas Bot</div>
                        <div class="tg-card-sub">20 aktivitas terakhir</div>
                    </div>
                </div>
            </div>
            <div style="overflow-x:auto;max-height:320px;overflow-y:auto;">
                <table class="table table-sm tg-table mb-0">
                    <thead class="table-light" style="position:sticky;top:0;z-index:1;">
                        <tr>
                            <th>Waktu</th>
                            <th>Chat ID</th>
                            <th>Pesan</th>
                            <th>Respons</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="tg-muted text-nowrap">{{ $log->created_at?->diffForHumans() }}</td>
                                <td><span class="tg-chat-id">{{ $log->chat_id }}</span></td>
                                <td class="tg-log-msg">{{ $log->message_text ?: '-' }}</td>
                                <td class="tg-log-msg">{{ $log->response ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="tg-empty" style="padding:24px;">
                                        <div class="tg-empty-icon" style="font-size:28px;"><em class="icon ni ni-clock"></em></div>
                                        <div style="font-size:12px;font-weight:600;color:#374151;">Belum ada aktivitas</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>{{-- /.tg-layout --}}

@endsection

@push('scripts')
<script>
/* ── Toggle show/hide password ── */
function toggleVisible(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('em');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = icon.className.replace('ni-eye', 'ni-eye-off');
    } else {
        input.type = 'password';
        icon.className = icon.className.replace('ni-eye-off', 'ni-eye');
    }
}

/* ── Test connection via AJAX ── */
async function testConnection() {
    const btn = document.getElementById('btnTestConnection');
    const result = document.getElementById('testConnectionResult');
    const token = document.getElementById('botTokenInput').value.trim();

    btn.disabled = true;
    btn.innerHTML = '<span style="display:inline-block;animation:spin 1s linear infinite">⟳</span> Menguji...';

    // Tampilkan dan reset result
    result.style.display = 'block';
    result.style.background = '#f8fafc';
    result.style.border = '1px solid #e2e8f0';
    result.style.borderRadius = '10px';
    result.style.padding = '12px 16px';
    result.style.marginTop = '12px';
    result.innerHTML = '<span style="font-size:12.5px;color:#64748b;">⟳ Menghubungi Telegram API...</span>';

    try {
        const res = await fetch('{{ route('admin.telegram-users.test-connection') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ bot_token: token || null }),
        });

        const data = await res.json();

        if (data.success && data.bot) {
            result.style.background = '#f0fdf4';
            result.style.border = '1px solid #bbf7d0';
            result.innerHTML = `
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <span style="font-size:20px;">✅</span>
                    <div>
                        <div style="font-size:12.5px;font-weight:700;color:#15803d;">${data.message}</div>
                        <div style="font-size:11px;color:#16a34a;margin-top:4px;">
                            Bot: <strong>@${data.bot.username ?? 'bot'}</strong> &nbsp;·&nbsp;
                            ID: <code>${data.bot.id ?? '-'}</code> &nbsp;·&nbsp;
                            Latensi: <strong>${data.latency_ms ?? '?'} ms</strong>
                        </div>
                    </div>
                </div>`;
        } else {
            result.style.background = '#fef2f2';
            result.style.border = '1px solid #fecaca';
            result.innerHTML = `
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:20px;">❌</span>
                    <div style="font-size:12.5px;font-weight:700;color:#dc2626;">${data.message ?? 'Gagal terhubung.'}</div>
                </div>`;
        }
    } catch (e) {
        result.style.background = '#fef2f2';
        result.style.border = '1px solid #fecaca';
        result.innerHTML = `<div style="font-size:12.5px;color:#dc2626;">❌ Error jaringan: ${e.message}</div>`;
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<em class="icon ni ni-plug"></em> Uji Koneksi';
    }
}

/* ── Confirm dialogs ── */
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});
</script>
@endpush
