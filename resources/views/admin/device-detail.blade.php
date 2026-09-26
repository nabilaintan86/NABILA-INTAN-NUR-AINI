@extends('layouts.admin')

@section('title', $device->name . ' · RADNET NOC')

@push('styles')
<style>
    body, .nk-main { background: #f4f6fb; }

    .status-badge-dd {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .72rem; font-weight: 700;
        padding: 5px 12px; border-radius: 999px;
    }
    .status-dd-up      { background: #dcfce7; color: #15803d; }
    .status-dd-down    { background: #fee2e2; color: #dc2626; }
    .status-dd-unknown { background: #fef3c7; color: #b45309; }
    .dd-dot { width: 7px; height: 7px; border-radius: 50%; }
    .dd-dot-up      { background: #22c55e; }
    .dd-dot-down    { background: #ef4444; }
    .dd-dot-unknown { background: #f59e0b; }

    .panel { background:#fff; border:1px solid #e8ecf3; border-radius:14px; padding:1.2rem 1.3rem; }
    .panel-title {
        font-size: .9rem; font-weight: 800; color: #1c2b4a;
        display: flex; align-items: center; gap: .5rem; margin-bottom: 1rem;
    }
    .panel-title::before {
        content: ""; width: 4px; height: 16px; border-radius: 4px;
        background: linear-gradient(180deg, #4F46E5, #7c73f0);
    }

    .dd-desc { font-size: .85rem; color: #526484; line-height: 1.7; }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 12px;
    }
    .stat-chip { background: #f8fafc; border: 1px solid #eef1f8; border-radius: 12px; padding: 12px 14px; }
    .stat-chip .sc-label {
        font-size: .64rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: .05em; color: #8094ae; margin-bottom: 4px;
    }
    .stat-chip .sc-value { font-size: 1.05rem; font-weight: 800; color: #1c2b4a; }

    .meta-table { width: 100%; }
    .meta-table td {
        padding: .55rem .25rem;
        font-size: .84rem;
        border-bottom: 1px solid #f1f4f9;
        vertical-align: top;
    }
    .meta-table tr:last-child td { border-bottom: none; }
    .meta-table td:first-child {
        color: #8094ae; font-weight: 600; width: 190px; white-space: nowrap;
    }
    .meta-table td:last-child { color: #1c2b4a; font-weight: 600; word-break: break-word; }

    @media (max-width: 575.98px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .note-timeline { position: relative; margin: 0; padding: 0; list-style: none; }
    .note-timeline::before {
        content: "";
        position: absolute;
        left: 9px;
        top: 12px;
        bottom: 12px;
        width: 2px;
        background: #e2e8f0;
    }
    .note-item { position: relative; display: flex; gap: 14px; padding: 0 0 18px; }
    .note-item:last-child { padding-bottom: 0; }
    .note-dot {
        position: relative;
        z-index: 1;
        width: 20px;
        height: 20px;
        margin-top: 2px;
        border-radius: 50%;
        background: #4F46E5;
        border: 4px solid #eef2ff;
        flex-shrink: 0;
    }
    .note-dot-urgent { background: #ef4444; border-color: #fee2e2; }
    .note-content { flex: 1; min-width: 0; }
    .note-meta { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; font-size: .75rem; color: #8094ae; }
    .note-body { color: #334155; font-size: .86rem; line-height: 1.6; margin-top: 5px; white-space: pre-wrap; word-break: break-word; }
    .priority-badge { display: inline-flex; font-size: .68rem; font-weight: 800; padding: 3px 8px; border-radius: 999px; }
    .priority-normal { background: #dcfce7; color: #15803d; }
    .priority-urgent { background: #fee2e2; color: #b91c1c; }
    .note-actions { margin-top: 7px; display: flex; gap: 10px; }
    .note-action { border: 0; background: none; padding: 0; color: #64748b; font-size: .74rem; font-weight: 700; }
    .note-action:hover { color: #4F46E5; }
    .note-action-danger:hover { color: #dc2626; }
    .note-user { display: inline-flex; align-items: center; gap: 8px; }
    .note-user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #4f46e5;
        font-size: .65rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    .note-empty { color: #8094ae; font-size: .85rem; padding: 10px 0 4px 34px; }
</style>
@endpush

@section('content')

@php
    $backFrom = (string) request()->headers->get('referer');
    $backUrl  = ($backFrom !== '' && str_contains($backFrom, '/admin/'))
        ? $backFrom
        : route('admin.dashboard');
    $fromDhcp    = str_contains($backFrom, '/admin/dhcp-lease');
    $fromLaporan = !$fromDhcp && str_contains($backFrom, '/admin/laporan');
    $parentLabel = $fromDhcp ? 'DHCP Lease' : ($fromLaporan ? 'Laporan & Grafik' : null);
    $parentUrl   = $fromDhcp ? route('admin.dhcp-lease')
        : ($fromLaporan ? route('admin.laporan') : null);
    $crumbs = [];
    if ($parentLabel !== null) {
        $crumbs[] = ['label' => $parentLabel, 'url' => $parentUrl];
    }
    $crumbs[] = ['label' => 'Detail Device'];
@endphp

{{-- =========================================================
     PAGE HEAD
========================================================== --}}
<x-admin.page-head
    :title="$device->name"
    :subtitle="($device->ip_address ?: 'IP tidak tercatat') . ' · ' . ($device->display_type ?: '—') . ' · ' . ($device->display_protocol ?: '—')"
    :icon-url="$device->icon_url"
    :crumbs="$crumbs"
>
    <x-slot:actions>
        @php
            $status = strtolower((string) $device->status);
            $statusLabel = $status === 'up' ? 'UP' : ($status === 'down' ? 'DOWN' : 'UNKNOWN');
            $statusClass = $status === 'up' ? 'up' : ($status === 'down' ? 'down' : 'unknown');
        @endphp
        <span class="status-badge-dd status-dd-{{ $statusClass }}">
            <span class="dd-dot dd-dot-{{ $statusClass }}"></span> {{ $statusLabel }}
        </span>
        <a href="{{ $backUrl }}" class="btn btn-outline-primary">
            <em class="icon ni ni-arrow-left"></em>
            @if($fromDhcp) DHCP Lease
            @elseif($fromLaporan) Laporan
            @else Kembali @endif
        </a>
    </x-slot:actions>
</x-admin.page-head>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="panel h-100">
            <div class="panel-title">Informasi Perangkat</div>
            <table class="meta-table">
                <tr><td>Nama Device</td><td>{{ $device->name }}</td></tr>
                <tr><td>Hostname</td><td>{{ $device->name }}</td></tr>
                <tr><td>IP Address</td><td>{{ $device->ip_address ?: '—' }}</td></tr>
                <tr><td>Tipe Perangkat</td><td>{{ $device->display_type ?: '—' }}</td></tr>
                <tr><td>Metode Monitoring</td><td>{{ $device->display_protocol ?: '—' }}</td></tr>
                <tr><td>Router / Lokasi</td><td>{{ $device->pop_name ?: '—' }}</td></tr>
                <tr><td>Status</td><td>{{ $statusLabel }}</td></tr>
                <tr>
                    <td>Deskripsi</td>
                    <td>
                        @if($device->deskripsi)
                            <span class="dd-desc">{{ $device->deskripsi }}</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Router Version</td>
                    <td>{{ $device->router_version ?: '—' }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel h-100">
            <div class="panel-title">Statistik Monitoring</div>
            <div class="stat-grid">
                <div class="stat-chip">
                    <div class="sc-label">Polls</div>
                    <div class="sc-value">{{ number_format((int) $device->polls) }}</div>
                </div>
                <div class="stat-chip">
                    <div class="sc-label">Responded %</div>
                    <div class="sc-value">{{ $device->responded_percentage !== null ? number_format((float) $device->responded_percentage, 2).'%' : '—' }}</div>
                </div>
                <div class="stat-chip">
                    <div class="sc-label">Missed %</div>
                    <div class="sc-value text-danger">{{ $device->missed_percentage !== null ? number_format((float) $device->missed_percentage, 2).'%' : '—' }}</div>
                </div>
                <div class="stat-chip">
                    <div class="sc-label">Down Time</div>
                    <div class="sc-value text-danger">{{ $device->down_time !== null ? number_format((int) $device->down_time).' s' : '—' }}</div>
                </div>
                <div class="stat-chip">
                    <div class="sc-label">Periode</div>
                    <div class="sc-value">{{ $device->period ?: '—' }}</div>
                </div>
                <div class="stat-chip">
                    <div class="sc-label">Alerts</div>
                    <div class="sc-value">{{ number_format((int) $device->alerts_count) }}</div>
                </div>
                <div class="stat-chip">
                    <div class="sc-label">Avg Delay</div>
                    <div class="sc-value">{{ $device->avg_delay !== null ? number_format((int) $device->avg_delay).' ms' : '—' }}</div>
                </div>
                <div class="stat-chip">
                    <div class="sc-label">Min / Max</div>
                    <div class="sc-value">
                        {{ $device->min_delay !== null ? number_format((int) $device->min_delay).' / ' : '— / ' }}{{ $device->max_delay !== null ? number_format((int) $device->max_delay).' ms' : '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @auth
        @if(\Illuminate\Support\Facades\Schema::hasTable('device_notes'))
            <div class="col-12">
                <div class="panel">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                        <div class="panel-title mb-0">Riwayat Catatan</div>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show py-2 px-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    @endif
                    @if($notes->isNotEmpty())
                        <ol class="note-timeline">
                            @foreach($notes as $note)
                                <li class="note-item">
                                    <span class="note-dot {{ $note->priority === 'Urgent' ? 'note-dot-urgent' : '' }}"></span>
                                    <div class="note-content">
                                        <div class="note-meta">
                                            <strong>{{ $note->tanggal?->format('d M Y, H:i') }}</strong>
                                            <span>•</span>
                                            @if($note->user)
                                                <span class="note-user">
                                                    <span class="note-user-avatar" aria-hidden="true">{{ strtoupper(substr(trim($note->user->name), 0, 2)) }}</span>
                                                    <span>{{ $note->user->name }}</span>
                                                </span>
                                            @else
                                                <span class="text-muted">User tidak ditemukan</span>
                                            @endif
                                            <span class="priority-badge {{ $note->priority_class }}">{{ $note->priority_label }}</span>
                                        </div>
                                        <div class="note-body">{{ $note->catatan }}</div>
                                        <div class="note-actions">
                                            @can('update', $note)
                                                <a class="note-action" href="{{ route('admin.device.detail', [$device, 'edit_note' => $note->id]) }}">
                                                    <em class="icon ni ni-edit"></em> Edit
                                                </a>
                                            @endcan
                                            @can('delete', $note)
                                                <form method="POST" action="{{ route('admin.device.notes.destroy', $note) }}" class="note-delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="note-action note-action-danger">
                                                        <em class="icon ni ni-trash"></em> Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <div class="note-empty">Belum ada catatan untuk perangkat ini.</div>
                    @endif
                </div>
            </div>
        @endif
    @endauth
</div>
@endsection

@auth
    @if(\Illuminate\Support\Facades\Schema::hasTable('device_notes') && $editNote)
        <div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true"
             data-update-url="{{ route('admin.device.notes.update', 0) }}"
             data-edit-id="{{ $editNote->id }}"
             data-edit-date="{{ $editNote->tanggal?->format('Y-m-d\\TH:i') ?? '' }}"
             data-edit-priority="{{ $editNote->priority ?? 'Normal' }}"
             data-edit-note="{{ $editNote->catatan ?? '' }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="noteForm" method="POST" action="{{ route('admin.device.notes.update', $editNote) }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title" id="noteModalTitle">
                                <em class="icon ni ni-edit text-warning me-2"></em>
                                Edit Catatan
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="note_user" class="form-label">Dicatat oleh</label>
                                <input type="text" id="note_user" value="{{ $editNote->user?->name ?? 'User tidak ditemukan' }}" readonly class="form-control bg-light" style="cursor: not-allowed;">
                                <small class="form-text text-muted">Otomatis menggunakan akun yang sedang login.</small>
                            </div>
                            <div class="mb-3">
                                 <label for="note_date" class="form-label">Tanggal &amp; Waktu <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="tanggal" id="note_date" class="form-control" value="{{ old('tanggal', now()->format('Y-m-d\\TH:i')) }}" required>
                                @error('tanggal')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="note_priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select name="priority" id="note_priority" class="form-select" required>
                                    <option value="Normal" @selected(old('priority', $editNote->priority) === 'Normal')>Normal</option>
                                    <option value="Urgent" @selected(old('priority', $editNote->priority) === 'Urgent')>Urgent</option>
                                </select>
                                @error('priority')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <label for="note_content" class="form-label">Catatan <span class="text-danger">*</span></label>
                                <textarea name="catatan" id="note_content" rows="5" class="form-control" placeholder="Tuliskan kondisi, tindakan, atau informasi penting perangkat..." required>{{ old('catatan', $editNote->catatan) }}</textarea>
                                @error('catatan')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <em class="icon ni ni-save me-1"></em> Simpan Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalElement = document.getElementById('noteModal');
    var form = document.getElementById('noteForm');

    if (!modalElement || !form) {
        return;
    }

    var updateUrl = modalElement.dataset.updateUrl;
    var editId = modalElement.dataset.editId;
    var editDate = modalElement.dataset.editDate;
    var editPriority = modalElement.dataset.editPriority;
    var editNote = modalElement.dataset.editNote;
    var dateInput = document.getElementById('note_date');
    var priorityInput = document.getElementById('note_priority');
    var contentInput = document.getElementById('note_content');

    if (editId) {
        form.action = updateUrl.replace(/\/0$/, '/' + editId);
        dateInput.value = editDate;
        priorityInput.value = editPriority || 'Normal';
        contentInput.value = editNote || '';
    }

    new bootstrap.Modal(modalElement).show();

    document.querySelectorAll('.note-delete-form').forEach(function (deleteForm) {
        deleteForm.addEventListener('submit', function (event) {
            if (!confirm('Yakin ingin menghapus catatan ini?')) {
                event.preventDefault();
            }
        });
    });
});
</script>
@endpush