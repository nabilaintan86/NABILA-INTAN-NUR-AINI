@extends('layouts.admin')

@section('title', 'Report Catatan Perangkat · RADNET NOC')

@push('styles')
<style>
    .note-report-filter {
        background: #fff;
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        padding: 1rem;
    }
    .note-report-filter .form-label {
        color: #64748b;
        font-size: .74rem;
        font-weight: 800;
        margin-bottom: 6px;
    }
    .note-kpi {
        border: 1px solid #e8ecf3;
        border-radius: 14px;
        background: #fff;
        padding: 1rem 1.1rem;
        height: 100%;
    }
    .note-kpi-label {
        color: #8094ae;
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .note-kpi-value {
        color: #1c2b4a;
        font-size: 1.45rem;
        font-weight: 800;
        margin-top: 5px;
    }
    .note-report-table td,
    .note-report-table th {
        vertical-align: middle;
        font-size: .8rem;
    }
    .note-report-table .note-text {
        color: #334155;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
        min-width: 220px;
    }
    .note-priority {
        display: inline-flex;
        padding: 4px 9px;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 800;
    }
    .note-priority-normal { background: #dcfce7; color: #15803d; }
    .note-priority-urgent { background: #fee2e2; color: #b91c1c; }
    .note-device-icon {
        width: 30px;
        height: 30px;
        object-fit: contain;
        padding: 3px;
        border-radius: 8px;
        background: #f8fafc;
    }
    .note-action-group { display: flex; gap: 10px; justify-content: flex-end; }
    .note-action-link { color: #64748b; font-size: .74rem; font-weight: 700; }
    .note-action-link:hover { color: #4f46e5; }
    .note-delete-button { color: #64748b; font-size: .74rem; font-weight: 700; }
    .note-delete-button:hover { color: #dc2626; }
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
</style>
@endpush

@section('content')

{{-- ============================================================
     PAGE HEAD
============================================================ --}}
<x-admin.page-head
    title="Report Catatan Perangkat"
    subtitle="Histori catatan operasional dari database."
    icon="reports"
    :crumbs="[
        ['label' => 'Kelola Router', 'url' => route('admin.kelola-router')],
        ['label' => 'Report Catatan'],
    ]"
>
    <x-slot:actions>
        @can('create', \App\Models\Note::class)
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportNoteModal">
                <em class="icon ni ni-plus"></em> Tambah Catatan
            </button>
        @endcan
        <a href="{{ route('admin.kelola-router') }}" class="btn btn-outline-primary">
            <em class="icon ni ni-arrow-left"></em> Kembali ke Kelola Router
        </a>
    </x-slot:actions>
</x-admin.page-head>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <em class="icon ni ni-check-circle"></em> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="row g-3">
    <div class="col-6 col-lg-3">
        <div class="note-kpi">
            <div class="note-kpi-label">Total Catatan</div>
            <div class="note-kpi-value">{{ number_format($totalCount) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="note-kpi">
            <div class="note-kpi-label">Urgent</div>
            <div class="note-kpi-value text-danger">{{ number_format($urgentCount) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="note-kpi">
            <div class="note-kpi-label">Normal</div>
            <div class="note-kpi-value text-success">{{ number_format($normalCount) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="note-kpi">
            <div class="note-kpi-label">Halaman</div>
            <div class="note-kpi-value">{{ number_format($notes->currentPage()) }}</div>
        </div>
    </div>
</div>
<div class="note-report-filter">
    <form method="GET" action="{{ route('admin.device.notes.report') }}" class="row g-3 align-items-end">
        <div class="col-12 col-lg-3">
            <label for="q" class="form-label">Pencarian</label>
            <input type="search" name="q" id="q" class="form-control" value="{{ $filters['q'] }}" placeholder="Catatan, perangkat, atau pengguna">
        </div>
        <div class="col-6 col-lg-2">
            <label for="device_id" class="form-label">Perangkat</label>
            <select name="device_id" id="device_id" class="form-select">
                <option value="">Semua perangkat</option>
                @foreach($devices as $filterDevice)
                    <option value="{{ $filterDevice->id }}" @selected((int) $filters['device_id'] === (int) $filterDevice->id)>{{ $filterDevice->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <label for="user_id" class="form-label">Pengguna</label>
            <select name="user_id" id="user_id" class="form-select">
                <option value="">Semua pengguna</option>
                @foreach($users as $filterUser)
                    <option value="{{ $filterUser->id }}" @selected((int) $filters['user_id'] === (int) $filterUser->id)>{{ $filterUser->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <label for="priority" class="form-label">Prioritas</label>
            <select name="priority" id="priority" class="form-select">
                <option value="">Semua prioritas</option>
                <option value="Normal" @selected($filters['priority'] === 'Normal')>Normal</option>
                <option value="Urgent" @selected($filters['priority'] === 'Urgent')>Urgent</option>
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <label for="date_from" class="form-label">Dari tanggal</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $filters['date_from'] }}">
        </div>
        <div class="col-6 col-lg-2">
            <label for="date_to" class="form-label">Sampai tanggal</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $filters['date_to'] }}">
        </div>
        <div class="col-6 col-lg-2">
            <label for="sort" class="form-label">Urutan</label>
            <select name="sort" id="sort" class="form-select">
                <option value="latest" @selected($filters['sort'] === 'latest')>Terbaru</option>
                <option value="oldest" @selected($filters['sort'] === 'oldest')>Terlama</option>
            </select>
        </div>
        <div class="col-12 col-lg-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><em class="icon ni ni-filter"></em> Terapkan</button>
            <a href="{{ route('admin.device.notes.report') }}" class="btn btn-light">Reset</a>
        </div>
    </form>
</div>
<div class="nk-block">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover note-report-table">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Perangkat</th>
                            <th>Pengguna</th>
                            <th>Prioritas</th>
                            <th>Catatan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                            <tr>
                                <td class="text-nowrap">{{ $note->tanggal?->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($note->device)
                                            <img src="{{ $note->device->icon_url }}" alt="{{ $note->device->display_type }}" class="note-device-icon">
                                            <div>
                                                <a href="{{ route('admin.device.detail', $note->device) }}" class="fw-bold text-decoration-none">{{ $note->device->name }}</a>
                                                <div class="text-muted small">{{ $note->device->ip_address }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Perangkat dihapus</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($note->user)
                                        <span class="note-user">
                                            <span class="note-user-avatar" aria-hidden="true">{{ strtoupper(substr(trim($note->user->name), 0, 2)) }}</span>
                                            <span>{{ $note->user->name }}</span>
                                        </span>
                                    @else
                                        <span class="text-muted">User tidak ditemukan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="note-priority note-priority-{{ strtolower($note->priority_label) }}">{{ $note->priority_label }}</span>
                                </td>
                                <td class="note-text">{{ $note->catatan }}</td>
                                <td>
                                    <div class="note-action-group">
                                        @can('update', $note)
                                            <a class="note-action-link" href="{{ route('admin.device.detail', ['device' => $note->device_id, 'edit_note' => $note->id]) }}">Edit</a>
                                        @endcan
                                        @can('delete', $note)
                                            <form method="POST" action="{{ route('admin.device.notes.destroy', $note) }}" class="note-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="note-delete-button">Hapus</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <em class="icon ni ni-inbox d-block mb-2" style="font-size:32px;"></em>
                                    Tidak ada catatan yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notes->hasPages())
                <div class="mt-3">{{ $notes->links() }}</div>
            @endif
        </div>
    </div>
</div>

@can('create', \App\Models\Note::class)
    <div class="modal fade" id="reportNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.device.notes.store-report') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <em class="icon ni ni-reports text-primary me-2"></em>
                            Tambah Catatan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="report_note_device" class="form-label">Perangkat <span class="text-danger">*</span></label>
                            <select name="device_id" id="report_note_device" class="form-select" required>
                                <option value="">Pilih perangkat</option>
                                @foreach($devices as $filterDevice)
                                    <option value="{{ $filterDevice->id }}" @selected((int) old('device_id') === (int) $filterDevice->id)>{{ $filterDevice->name }}</option>
                                @endforeach
                            </select>
                            @error('device_id')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="report_note_user" class="form-label">Dicatat oleh</label>
                            <input type="text" id="report_note_user" value="{{ auth()->user()->name }}" readonly class="form-control bg-light" style="cursor: not-allowed;">
                            <small class="form-text text-muted">Otomatis menggunakan akun yang sedang login.</small>
                        </div>

                        <div class="mb-3">
                            <label for="report_note_date" class="form-label">Tanggal &amp; Waktu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="tanggal" id="report_note_date" class="form-control" value="{{ old('tanggal', now()->format('Y-m-d\\TH:i')) }}" required>
                            @error('tanggal')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="report_note_priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select name="priority" id="report_note_priority" class="form-select" required>
                                <option value="Normal" @selected(old('priority', 'Normal') === 'Normal')>Normal</option>
                                <option value="Urgent" @selected(old('priority') === 'Urgent')>Urgent</option>
                            </select>
                            @error('priority')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <label for="report_note_content" class="form-label">Catatan <span class="text-danger">*</span></label>
                            <textarea name="catatan" id="report_note_content" rows="5" class="form-control" placeholder="Tuliskan kondisi, tindakan, atau informasi penting perangkat..." required>{{ old('catatan') }}</textarea>
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
@endcan
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.note-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Yakin ingin menghapus catatan ini?')) {
                event.preventDefault();
            }
        });
    });
});
</script>
@endpush
