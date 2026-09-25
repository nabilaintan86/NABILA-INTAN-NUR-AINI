@extends('layouts.admin')

@section('title', 'Tambah User · RADNET NOC')

@push('styles')
<style>
    .card-user .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .card-user .input-hint {
        font-size: 11px;
        color: #94a3b8;
    }
</style>
@endpush

@section('content')

{{-- ============================================================
     HEADER
============================================================ --}}
<div class="nk-block-head">
    <div class="nk-block-head-between flex-wrap gap g-2">
        <div class="nk-block-head-content">
            <nav>
                <ol class="breadcrumb breadcrumb-arrow mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.kelola-user') }}">Kelola User</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Tambah User
                    </li>
                </ol>
            </nav>
        </div>
        <div class="nk-block-head-content">
            <a href="{{ route('admin.kelola-user') }}" class="btn btn-outline-secondary">
                <em class="icon ni ni-arrow-left"></em>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>
{{-- ============================================================
     ALERTS
============================================================ --}}
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
     FORM TAMBAH USER
============================================================ --}}
<div class="nk-block">
    <div class="card card-user">
        <div class="card-inner">
            <form action="{{ route('admin.kelola-user.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Contoh: Budi Santoso" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role"
                                    class="form-select @error('role') is-invalid @enderror" required>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="contoh@email.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control"
                                   placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.kelola-user') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-save me-1"></em> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection