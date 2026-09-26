@extends('layouts.admin')

@section('title', 'Kelola User · RADNET NOC')

@push('styles')
<style>
    .role-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
    }
    .role-super_admin { background: #eff6ff; color: #2563eb; }
    .role-staff       { background: #f0fdf4; color: #16a34a; }

    .table-user td,
    .table-user th {
        vertical-align: middle;
        font-size: 12px;
    }

    .table-user .user-name {
        font-weight: 700;
        color: #0f172a;
    }

    .table-user .user-email {
        font-size: 11px;
        color: #64748b;
    }
</style>
@endpush

@section('content')

{{-- ============================================================
     PAGE HEAD
============================================================ --}}
<x-admin.page-head
    title="Kelola User"
    subtitle="Kelola akun admin dan moderator yang mengakses NOC."
    icon="users"
    :crumbs="[['label' => 'Kelola User']]"
>
    <x-slot:actions>
        <a href="{{ route('admin.kelola-user.create') }}" class="btn btn-primary d-none d-md-inline-flex">
            <em class="icon ni ni-plus"></em>
            <span>Tambah User</span>
        </a>
    </x-slot:actions>
</x-admin.page-head>

{{-- ============================================================
     ALERTS
============================================================ --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <em class="icon ni ni-check-circle"></em>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <em class="icon ni ni-alert-circle"></em>
        {{ session('error') }}
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
     CARD: SEARCH
============================================================ --}}
<div class="nk-block">
    <div class="card">
        <div class="card-body card-body-lg pt-4 pb-4">
            <form action="{{ route('admin.kelola-user') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-8 col-lg-10">
                    <div class="form-group">
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-left">
                                <em class="icon ni ni-search"></em>
                            </div>
                            <input type="text"
                                   name="search"
                                   value="{{ $search }}"
                                   class="form-control form-control-lg"
                                   placeholder="Cari nama atau email user...">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <em class="icon ni ni-search me-1"></em>
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- ============================================================
     TABLE USER
============================================================ --}}
<div class="nk-block">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-user">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>
                                    <span class="user-name">{{ $user->name }}</span>
                                    @if($user->id === auth()->id())
                                        <span class="text-muted small ms-1">(Anda)</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="user-email">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="role-badge role-{{ $user->role }}">
                                        {{ $user->role === 'super_admin' ? 'Super Admin' : 'Staff' }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $user->created_at?->format('d/m/Y H:i') }}
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
                                                        <a href="{{ route('admin.kelola-user.edit', $user) }}">
                                                            <em class="icon ni ni-edit"></em>
                                                            <span>Edit</span>
                                                        </a>
                                                    </li>
                                                    @if($user->id !== auth()->id())
                                                        <li>
                                                            <form action="{{ route('admin.kelola-user.destroy', $user) }}"
                                                                  method="POST"
                                                                  class="d-inline form-delete-user">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="btn-delete-user text-decoration-none"
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
                                <td colspan="6" class="text-center text-muted py-5">
                                    <em class="icon ni ni-inbox d-block mb-2" style="font-size:32px;"></em>
                                    {{ $search ? 'Tidak ada user yang cocok dengan pencarian.' : 'Belum ada user terdaftar.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="d-flex justify-content-center pt-3">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.form-delete-user').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
            }
        });
    });

});
</script>
@endpush