@extends('layouts.admin')

@section('title', 'Edit User · RADNET NOC')

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
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.kelola-user') }}">Kelola User</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Edit User
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
                     FORM EDIT USER
                ============================================================ --}}
                <div class="nk-block">
                    <div class="card card-user">
                        <div class="card-inner">
                            <form action="{{ route('admin.kelola-user.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name"
                                                   value="{{ old('name', $user->name) }}"
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
                                                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
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
                                                   value="{{ old('email', $user->email) }}"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   placeholder="contoh@email.com" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password <small class="text-muted">(opsional)</small></label>
                                            <input type="password" name="password" id="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   placeholder="Kosongkan jika tidak diubah">
                                            <div class="input-hint mt-1">
                                                Minimal 8 karakter. Isi hanya jika ingin mengganti password.
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                   class="form-control"
                                                   placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.kelola-user') }}" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-warning">
                                        <em class="icon ni ni-save me-1"></em> Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection