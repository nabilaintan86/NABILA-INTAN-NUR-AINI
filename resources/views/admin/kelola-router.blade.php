@extends('layouts.admin')

@section('title', 'Kelola Perangkat · RADNET NOC')

@push('styles')
<style>
    .device-type-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: capitalize;
    }
    .type-router { background: #eff6ff; color: #2563eb; }
    .type-switch { background: #f0fdf4; color: #16a34a; }
    .type-olt { background: #f3e8ff; color: #7e22ce; }
    .type-epon { background: #ecfeff; color: #0e7490; }
    .type-server { background: #fef3c7; color: #b45309; }
    .type-access-point { background: #fce7f3; color: #be185d; }
    .type-other { background: #f1f5f9; color: #475569; }
    .protocol-badge {
        display: inline-flex;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        background: #f8fafc;
        color: #475569;
    }
    .device-icon {
        width: 34px;
        height: 34px;
        object-fit: contain;
        border-radius: 9px;
        background: #f8fafc;
        padding: 4px;
        flex-shrink: 0;
    }
    .device-cell {
        display: flex;
        align-items: center;
        gap: 9px;
    }
    .device-subtitle {
        display: block;
        color: #94a3b8;
        font-size: 10px;
        margin-top: 2px;
    }
    .type-preview {
        width: 32px;
        height: 32px;
        object-fit: contain;
        margin-left: auto;
        background: #f8fafc;
        border-radius: 8px;
        padding: 3px;
    }

    .status-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
    }
    .status-up      { background: #dcfce7; color: #15803d; }
    .status-down    { background: #fee2e2; color: #dc2626; }
    .status-warning { background: #fef9c3; color: #a16207; }

    .table-device td,
    .table-device th {
        vertical-align: middle;
        font-size: 12px;
    }

    .table-device .device-name {
        font-weight: 700;
        color: #0f172a;
    }

    .table-device .device-ip {
        font-family: monospace;
        font-size: 11px;
        color: #64748b;
    }

    .table-device .metric {
        font-weight: 600;
    }

    .table-device .metric-good { color: #16a34a; }
    .table-device .metric-bad  { color: #dc2626; }

    .table-device tr.row-down    { background: #fff5f5; }
    .table-device tr.row-warning { background: #fffde7; }

    .modal-device .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
</style>
@endpush

@section('content')

{{-- ============================================================
     PAGE HEAD
============================================================ --}}
<x-admin.page-head
    title="Kelola Router"
    subtitle="Kelola daftar router dan perangkat jaringan RADNET."
    icon="wifi"
>

    <x-slot:actions>
        @auth
            <a href="{{ route('admin.device.notes.report') }}" class="btn btn-outline-primary d-none d-md-inline-flex">
                <em class="icon ni ni-reports"></em>
                <span>Report Catatan</span>
            </a>
        @endauth
        @if(auth()->check() && auth()->user()->role === 'super_admin')
            <button
                class="btn btn-primary d-none d-md-inline-flex"
                data-bs-toggle="modal"
                data-bs-target="#addDeviceModal">
                <em class="icon ni ni-plus"></em>
                <span>Tambah Perangkat</span>
            </button>
        @endif
    </x-slot:actions>
</x-admin.page-head>

{{-- ============================================================
     SUCCESS ALERT
============================================================ --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <em class="icon ni ni-check-circle"></em>
        {{ session('success') }}
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
{{-- ============================================================
     DEVICE TABLE
============================================================ --}}
<div class="nk-block">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-device">
                    <thead class="table-light">
                        <tr>
                             <th>No</th>
                             <th>Perangkat</th>
                             <th>Tipe</th>
                             <th>Protokol</th>
                             <th>IP Address</th>
                            <th># Polls</th>
                            <th>% Responded</th>
                            <th>% Missed</th>
                            <th>Down time</th>
                            <th>Period</th>
                            <th># Alerts</th>
                            <th>Avg delay</th>
                            <th>Min delay</th>
                            <th>Max delay</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($devices as $index => $device)
                            <tr class="{{ $device->status === 'down' ? 'row-down' : ($device->status === 'warning' ? 'row-warning' : '') }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="device-cell">
                                        <img src="{{ $device->icon_url }}" alt="{{ $device->display_type }}" class="device-icon">
                                        <div>
                                            <a href="{{ route('admin.device.detail', $device) }}" class="device-name text-decoration-none">{{ $device->name }}</a>
                                            <span class="device-subtitle">{{ $device->pop_name ?: 'Tanpa lokasi' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="device-type-badge type-{{ strtolower(str_replace(' ', '-', $device->display_type)) }}">
                                        {{ $device->display_type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="protocol-badge">{{ $device->display_protocol }}</span>
                                </td>
                                <td>
                                    <span class="device-ip">{{ $device->ip_address }}</span>
                                </td>
                                <td class="metric">{{ number_format($device->polls) }}</td>
                                <td>
                                    <span class="metric {{ $device->responded_percentage >= 99 ? 'metric-good' : ($device->responded_percentage < 90 ? 'metric-bad' : '') }}">
                                        {{ $device->responded_percentage }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="metric {{ $device->missed_percentage <= 1 ? 'metric-good' : ($device->missed_percentage > 10 ? 'metric-bad' : '') }}">
                                        {{ $device->missed_percentage }}%
                                    </span>
                                </td>
                                <td>{{ $device->down_time ?? '-' }}</td>
                                <td>{{ $device->period ?? '-' }}</td>
                                <td>
                                    @if($device->alerts_count > 0)
                                        <span class="badge bg-danger">{{ $device->alerts_count }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="metric">{{ $device->avg_delay }} ms</td>
                                <td>{{ $device->min_delay }} ms</td>
                                <td>{{ number_format($device->max_delay) }} ms</td>
                                <td>
                                    <span class="status-badge status-{{ $device->status }}">
                                        {{ strtoupper($device->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <a href="#"
                                           class="btn btn-sm btn-icon btn-zoom me-n1"
                                           data-bs-toggle="dropdown">
                                            <em class="icon ni ni-more-v"></em>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                            <div class="dropdown-content py-1">
                                                 <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                     <li>
                                                         <a href="{{ route('admin.device.detail', $device) }}">
                                                             <em class="icon ni ni-eye"></em>
                                                             <span>Detail</span>
                                                         </a>
                                                     </li>
                                                    @if(auth()->check() && auth()->user()->role === 'super_admin')
                                                     <li>
                                                         <a href="#"
                                                            class="btn-edit-device"
                                                            data-id="{{ $device->id }}"
                                                            data-name="{{ $device->name }}"
                                                            data-type="{{ $device->display_type }}"
                                                            data-protocol="{{ $device->display_protocol }}"
                                                            data-icon="{{ $device->icon_url }}"
                                                            data-ip="{{ $device->ip_address }}"
                                                            data-router-version="{{ $device->router_version }}">
                                                             <em class="icon ni ni-edit"></em>
                                                             <span>Edit</span>
                                                         </a>
                                                     </li>
                                                    <li>
                                                        <form action="{{ route('admin.kelola-router.destroy', $device) }}"
                                                              method="POST"
                                                              class="d-inline form-delete-device">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn-delete-device text-decoration-none"
                                                                    style="background:none;border:none;padding:0;">
                                                                <em class="icon ni ni-trash"></em>
                                                                <span>Hapus</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" class="text-center text-muted py-5">
                                    <em class="icon ni ni-inbox d-block mb-2" style="font-size:32px;"></em>
                                    Belum ada data perangkat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($devices->count() > 0)
                <div class="text-muted small mt-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Total: <strong>{{ $devices->count() }}</strong> perangkat terdaftar.
                </div>
            @endif
        </div>
    </div>
</div>


{{-- ============================================================
     MODAL: TAMBAH PERANGKAT
============================================================ --}}
<div class="modal fade modal-device" id="addDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.kelola-router.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <em class="icon ni ni-plus text-primary me-2"></em>
                        Tambah Perangkat Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="add_name" class="form-control"
                               placeholder="Contoh: POP Gresik" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="add_type" class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                            <select name="type" id="add_type" class="form-select" required>
                                @foreach($deviceTypes as $deviceType)
                                    <option value="{{ $deviceType }}" data-icon="{{ \App\Helpers\DeviceType::iconUrl($deviceType) }}" @selected($deviceType === 'Router')>{{ $deviceType }}</option>
                                @endforeach
                            </select>
                            <img id="add_type_preview" src="{{ \App\Helpers\DeviceType::iconUrl('Router') }}" alt="Preview perangkat" class="type-preview d-none d-sm-block">
                        </div>
                        <div class="col-md-4">
                            <label for="add_protocol" class="form-label">Metode Monitoring <span class="text-danger">*</span></label>
                            <select name="protocol" id="add_protocol" class="form-select" required>
                                @foreach($protocols as $protocol)
                                    <option value="{{ $protocol }}" @selected($protocol === 'ICMP')>{{ $protocol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="add_ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" id="add_ip_address" class="form-control"
                                   placeholder="Contoh: 202.154.4.254" required>
                        </div>
                        @if(auth()->check() && auth()->user()->role === 'super_admin')
                        <div class="col-12 mt-2">
                            <label for="add_router_version" class="form-label">Router Version</label>
                            <input type="text" name="router_version" id="add_router_version" class="form-control"
                                   placeholder="Contoh: IOS-XE 17.3.1" value="">
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-save me-1"></em> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ============================================================
     MODAL: EDIT PERANGKAT
============================================================ --}}
<div class="modal fade modal-device" id="editDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="#" method="POST" id="editDeviceForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <em class="icon ni ni-edit text-warning me-2"></em>
                        Edit Perangkat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="edit_type" class="form-label">Tipe Perangkat <span class="text-danger">*</span></label>
                            <select name="type" id="edit_type" class="form-select" required>
                                @foreach($deviceTypes as $deviceType)
                                    <option value="{{ $deviceType }}" data-icon="{{ \App\Helpers\DeviceType::iconUrl($deviceType) }}">{{ $deviceType }}</option>
                                @endforeach
                            </select>
                            <img id="edit_type_preview" alt="Preview perangkat" class="type-preview d-none d-sm-block">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_protocol" class="form-label">Metode Monitoring <span class="text-danger">*</span></label>
                            <select name="protocol" id="edit_protocol" class="form-select" required>
                                @foreach($protocols as $protocol)
                                    <option value="{{ $protocol }}">{{ $protocol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" id="edit_ip_address" class="form-control" required>
                        </div>
                        @if(auth()->check() && auth()->user()->role === 'super_admin')
                        <div class="col-12 mt-2">
                            <label for="edit_router_version" class="form-label">Router Version</label>
                            <input type="text" name="router_version" id="edit_router_version" class="form-control" value="">
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <em class="icon ni ni-save me-1"></em> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       EDIT MODAL — Populate fields from data-* attributes
    ============================================================ */
    document.querySelectorAll('.btn-edit-device').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            var id       = this.dataset.id;
            var name     = this.dataset.name;
            var type     = this.dataset.type;
            var protocol = this.dataset.protocol;
            var ip       = this.dataset.ip;
            var icon     = this.dataset.icon;

            document.getElementById('editDeviceForm').action =
                '{{ url("admin/kelola-router") }}/' + id;

            document.getElementById('edit_name').value       = name;
            document.getElementById('edit_type').value       = type;
            document.getElementById('edit_protocol').value   = protocol;
            document.getElementById('edit_ip_address').value = ip;
            document.getElementById('edit_type_preview').src = icon;
            var editRouterVersion = document.getElementById('edit_router_version');
            if (editRouterVersion) {
                editRouterVersion.value = this.dataset.routerVersion || '';
            }

            new bootstrap.Modal(
                document.getElementById('editDeviceModal')
            ).show();
        });
    });


    function syncTypePreview(selectId, previewId) {
        var select = document.getElementById(selectId);
        var preview = document.getElementById(previewId);
        var option = select.options[select.selectedIndex];

        if (option && option.dataset.icon) {
            preview.src = option.dataset.icon;
        }
    }

    document.getElementById('add_type').addEventListener('change', function () {
        syncTypePreview('add_type', 'add_type_preview');
    });
    document.getElementById('edit_type').addEventListener('change', function () {
        syncTypePreview('edit_type', 'edit_type_preview');
    });

    /* ============================================================
       DELETE CONFIRMATION
       ============================================================ */
    document.querySelectorAll('.form-delete-device').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Yakin ingin menghapus perangkat ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
            }
        });
    });

});
</script>
@endpush
