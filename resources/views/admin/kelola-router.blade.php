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
    .type-icmp { background: #eff6ff; color: #2563eb; }
    .type-http { background: #f0fdf4; color: #16a34a; }
    .type-snmp { background: #fefce8; color: #ca8a04; }

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
<div class="nk-content">

    <div class="container">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                {{-- ============================================================
                     HEADER
                ============================================================ --}}
                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <nav>
                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Kelola Router
                                    </li>
                                </ol>
                            </nav>
                        </div>

                        <div class="nk-block-head-content">
                            <ul class="d-flex">
                                <li>
                                    <button
                                        class="btn btn-primary d-none d-md-inline-flex"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addDeviceModal">
                                        <em class="icon ni ni-plus"></em>
                                        <span>Tambah Perangkat</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

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
                                            <th>Name</th>
                                            <th>Type</th>
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
                                                    <span class="device-name">{{ $device->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="device-type-badge type-{{ strtolower($device->type) }}">
                                                        {{ $device->type }}
                                                    </span>
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
                                                                        <a href="#"
                                                                           class="btn-edit-device"
                                                                           data-id="{{ $device->id }}"
                                                                           data-name="{{ $device->name }}"
                                                                           data-type="{{ $device->type }}"
                                                                           data-ip="{{ $device->ip_address }}">
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
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="15" class="text-center text-muted py-5">
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

            </div>
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
                        <div class="col-md-6">
                            <label for="add_type" class="form-label">Type / Protokol <span class="text-danger">*</span></label>
                            <select name="type" id="add_type" class="form-select" required>
                                <option value="ICMP" selected>ICMP</option>
                                <option value="HTTP">HTTP</option>
                                <option value="SNMP">SNMP</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" id="add_ip_address" class="form-control"
                                   placeholder="Contoh: 202.154.4.254" required>
                        </div>
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
                        <div class="col-md-6">
                            <label for="edit_type" class="form-label">Type / Protokol <span class="text-danger">*</span></label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="ICMP">ICMP</option>
                                <option value="HTTP">HTTP</option>
                                <option value="SNMP">SNMP</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_ip_address" class="form-label">IP Address <span class="text-danger">*</span></label>
                            <input type="text" name="ip_address" id="edit_ip_address" class="form-control" required>
                        </div>
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

            var id   = this.dataset.id;
            var name = this.dataset.name;
            var type = this.dataset.type;
            var ip   = this.dataset.ip;

            document.getElementById('editDeviceForm').action =
                '{{ url("admin/kelola-router") }}/' + id;

            document.getElementById('edit_name').value       = name;
            document.getElementById('edit_type').value       = type;
            document.getElementById('edit_ip_address').value = ip;

            new bootstrap.Modal(
                document.getElementById('editDeviceModal')
            ).show();
        });
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
