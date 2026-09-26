@extends('layouts.admin')

@section('title', 'Telegram Users · RADNET NOC')

@push('styles')
<style>
    body, .nk-main { background: #f4f6fb; }
    .telegram-kpi { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
    .telegram-stat { background: #fff; border: 1px solid #e8ecf3; border-radius: 14px; padding: 18px 20px; }
    .telegram-stat-label { color: #6b7a99; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
    .telegram-stat-value { color: #1c2b4a; font-size: 27px; font-weight: 800; margin-top: 4px; }
    .telegram-stat-icon { float: right; width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 17px; }
    .telegram-card { background: #fff; border: 1px solid #e8ecf3; }
    .telegram-card-body { padding: 20px; }
    .telegram-filter { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .telegram-filter .form-control, .telegram-filter .form-select { min-width: 160px; border-color: #dfe5ef; font-size: 12.5px; }
    .telegram-table th, .telegram-table td { vertical-align: middle; font-size: 12px; }
    .telegram-user-name { font-weight: 700; color: #17233d; }
    .telegram-muted { color: #7a879e; font-size: 11.5px; }
    .telegram-row-pending { background: #fffaf0; }
    .telegram-role, .telegram-status { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 9px; font-size: 10.5px; font-weight: 700; }
    .telegram-role-pending { background: #fef3c7; color: #92400e; }
    .telegram-role-staff { background: #dbeafe; color: #1d4ed8; }
    .telegram-role-admin { background: #ede9fe; color: #6d28d9; }
    .telegram-role-super_admin { background: #fce7f3; color: #be185d; }
    .telegram-status-approved { background: #dcfce7; color: #15803d; }
    .telegram-status-pending { background: #fef3c7; color: #b45309; }
    .telegram-chat-id { font-family: monospace; color: #52627d; }
    .telegram-actions { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
    .telegram-role-form { display: flex; gap: 5px; align-items: center; }
    .telegram-role-form .form-select { min-width: 115px; font-size: 11px; padding: 5px 24px 5px 8px; }
    .telegram-log-message { max-width: 360px; white-space: normal; word-break: break-word; }
    .telegram-empty { padding: 36px 20px; text-align: center; color: #7a879e; }
    @media (max-width: 767px) {
        .telegram-kpi { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
</style>
@endpush

@section('content')

{{-- ============================================================
     PAGE HEAD
============================================================ --}}
<x-admin.page-head
    title="Telegram Users"
    subtitle="Kelola registrasi, approval, role, dan broadcast bot RADNET."
    icon="telegram"
    icon-class="is-telegram"
    :crumbs="[['label' => 'Telegram Users']]"
>
    <x-slot:actions>
        <a href="{{ route('admin.telegram-users') }}" class="btn btn-outline-primary">
            <em class="icon ni ni-reload"></em>
            <span>Refresh</span>
        </a>
    </x-slot:actions>
</x-admin.page-head>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <em class="icon ni ni-check-circle"></em> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <em class="icon ni ni-alert-circle"></em> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <em class="icon ni ni-alert-circle"></em>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="telegram-kpi">
    <div class="telegram-stat">
        <span class="telegram-stat-icon" style="background:#e0f2fe;color:#0284c7;"><em class="icon ni ni-users"></em></span>
        <div class="telegram-stat-label">Total User</div>
        <div class="telegram-stat-value">{{ $kpi['total'] }}</div>
    </div>
    <div class="telegram-stat">
        <span class="telegram-stat-icon" style="background:#fef3c7;color:#d97706;"><em class="icon ni ni-clock"></em></span>
        <div class="telegram-stat-label">Menunggu</div>
        <div class="telegram-stat-value">{{ $kpi['pending'] }}</div>
    </div>
    <div class="telegram-stat">
        <span class="telegram-stat-icon" style="background:#dcfce7;color:#16a34a;"><em class="icon ni ni-check-circle"></em></span>
        <div class="telegram-stat-label">Disetujui</div>
        <div class="telegram-stat-value">{{ $kpi['approved'] }}</div>
    </div>
    <div class="telegram-stat">
        <span class="telegram-stat-icon" style="background:#ede9fe;color:#7c3aed;"><em class="icon ni ni-activity"></em></span>
        <div class="telegram-stat-label">Aktif Hari Ini</div>
        <div class="telegram-stat-value">{{ $kpi['active_today'] }}</div>
    </div>
</div>
<div class="telegram-card">
    <div class="telegram-card-body">
        <form method="GET" action="{{ route('admin.telegram-users') }}" class="telegram-filter">
            <div class="flex-grow-1">
                <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama, username, atau chat ID">
            </div>
            <select name="status" class="form-select">
                <option value="all" @selected($status === 'all')>Semua status</option>
                <option value="pending" @selected($status === 'pending')>Menunggu</option>
                <option value="approved" @selected($status === 'approved')>Disetujui</option>
            </select>
                <select name="role" class="form-select">
                <option value="all" @selected($role === 'all')>Semua role</option>
                <option value="super_admin" @selected($role === 'super_admin')>Super Admin</option>
                <option value="admin" @selected($role === 'admin')>Admin</option>
                <option value="pending" @selected($role === 'pending')>Pending</option>
            </select>
            <button class="btn btn-primary" type="submit"><em class="icon ni ni-search"></em> Filter</button>
        </form>
    </div>
</div>
<div class="telegram-card">
    <div class="telegram-card-body">
        <div class="table-responsive">
            <table class="table table-hover telegram-table mb-0">
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
                        <tr class="{{ !$telegramUser->is_approved ? 'telegram-row-pending' : '' }}">
                            <td>
                                <div class="telegram-user-name">{{ trim(($telegramUser->first_name ?? '').' '.($telegramUser->last_name ?? '')) ?: ($telegramUser->username ?? 'Tanpa nama') }}</div>
                                <div class="telegram-muted">{{ $telegramUser->username ? '@'.ltrim($telegramUser->username, '@') : 'Tanpa username' }}</div>
                            </td>
                            <td><span class="telegram-chat-id">{{ $telegramUser->chat_id }}</span></td>
                            <td><span class="telegram-role telegram-role-{{ $telegramUser->role }}">{{ str_replace('_', ' ', ucfirst($telegramUser->role)) }}</span></td>
                            <td>
                                <span class="telegram-status {{ $telegramUser->is_approved ? 'telegram-status-approved' : 'telegram-status-pending' }}">
                                    {{ $telegramUser->is_approved ? 'Disetujui' : 'Menunggu' }}
                                </span>
                            </td>
                            <td>
                                <span class="telegram-muted">{{ $telegramUser->last_active_at?->diffForHumans() ?? 'Belum pernah' }}</span>
                            </td>
                            <td>
                                <div class="telegram-actions justify-content-end">
                                    @if(!$telegramUser->is_approved)
                                        <form method="POST" action="{{ route('admin.telegram-users.approve', $telegramUser) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success" type="submit"><em class="icon ni ni-check"></em> Setujui</button>
                                        </form>
                                    @endif
                                        <form class="telegram-role-form" method="POST" action="{{ route('admin.telegram-users.set-role', $telegramUser) }}">
                                        @csrf
                                        <select name="role" class="form-select form-select-sm" aria-label="Role user">
                                            @foreach(['super_admin', 'admin', 'pending'] as $roleOption)
                                                <option value="{{ $roleOption }}" @selected($telegramUser->role === $roleOption)>{{ str_replace('_', ' ', ucfirst($roleOption)) }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary" type="submit">Simpan</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.telegram-users.destroy', $telegramUser) }}" data-confirm="Hapus user Telegram ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit"><em class="icon ni ni-trash"></em></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="telegram-empty">Belum ada user Telegram.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($telegramUsers->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $telegramUsers->links() }}
        </div>
    @endif
</div>
<div class="telegram-card">
    <div class="telegram-card-body">
        <h6 class="mb-3"><em class="icon ni ni-send me-1"></em> Broadcast ke User Disetujui</h6>
        <form method="POST" action="{{ route('admin.telegram-users.broadcast') }}" data-confirm="Kirim broadcast ini ke semua user Telegram yang disetujui?">
            @csrf
            <textarea name="message" class="form-control mb-3" rows="4" maxlength="5000" placeholder="Tulis pesan broadcast..." required>{{ old('message') }}</textarea>
            <button class="btn btn-primary" type="submit"><em class="icon ni ni-send"></em> Kirim Broadcast</button>
        </form>
    </div>
</div>
<div class="telegram-card">
    <div class="telegram-card-body">
        <h6 class="mb-3"><em class="icon ni ni-clock me-1"></em> Log Activity Terakhir</h6>
        <div class="table-responsive">
            <table class="table table-sm telegram-table mb-0">
                <thead class="table-light">
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
                            <td class="telegram-muted text-nowrap">{{ $log->created_at?->diffForHumans() }}</td>
                            <td><span class="telegram-chat-id">{{ $log->chat_id }}</span></td>
                            <td class="telegram-log-message">{{ $log->message_text ?: '-' }}</td>
                            <td class="telegram-log-message">{{ $log->response ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="telegram-empty">Belum ada activity Telegram.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
</script>
@endpush
