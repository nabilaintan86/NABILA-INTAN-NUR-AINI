@extends('layouts.admin')

@section('title', 'Profile · RADNET NOC')

@push('styles')
<style>
    .profile-card {
        border: 1px solid #e8ecf3;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
    }

    .profile-avatar {
        width: 132px;
        height: 132px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .12);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.03);
        box-shadow: 0 14px 30px rgba(15, 23, 42, .18);
    }

    .profile-avatar-preview {
        width: 132px;
        height: 132px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #e2e8f0;
        transition: transform .25s ease;
    }

    .profile-avatar-preview:hover {
        transform: scale(1.03);
    }

    .profile-label {
        color: #8094ae;
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .profile-value {
        color: #1c2b4a;
        font-weight: 700;
        word-break: break-word;
    }

    .profile-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 800;
    }

    .profile-role-super_admin { background: #ede9fe; color: #6d28d9; }
    .profile-role-staff { background: #e0f2fe; color: #0369a1; }

    .profile-photo-picker {
        border: 1.5px dashed #cbd5e1;
        border-radius: 14px;
        padding: 1.1rem;
        cursor: pointer;
        transition: border-color .2s ease, background-color .2s ease;
    }

    .profile-photo-picker:hover {
        border-color: #94a3b8;
        background-color: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="nk-block-head">
    <x-admin.page-head
        title="Profile"
        subtitle="Informasi akun dan foto profil Anda."
    />
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-3">
    {{-- =========================================================
         FOTO PROFIL
    ========================================================== --}}
    <div class="col-xl-5">
        <div class="card profile-card">
            <div class="card-body text-center">
                <img
                    src="{{ $user->avatar_url }}"
                    alt="Foto profil {{ $user->name }}"
                    class="profile-avatar mb-3"
                    id="profileAvatarCurrent"
                >

                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-soft small mb-3">{{ $user->email }}</p>

                <span class="profile-role profile-role-{{ $user->role }}">
                    <em class="icon ni ni-shield"></em>
                    {{ $user->role_label }}
                </span>

                <hr class="my-4">

                <form
                    method="POST"
                    action="{{ route('admin.profile.photo.update') }}"
                    enctype="multipart/form-data"
                    id="profilePhotoForm"
                >
                    @csrf

                    <div class="profile-photo-picker mb-3" id="profilePhotoPicker">
                        <img
                            src="{{ $user->avatar_url }}"
                            alt="Preview foto profil"
                            class="profile-avatar-preview mb-2"
                            id="profileAvatarPreview"
                        >
                        <div class="profile-label mb-1">Foto Profil Baru</div>
                        <p class="text-soft small mb-0">Klik untuk pilih gambar dari komputer</p>
                    </div>

                    <input
                        type="file"
                        name="photo"
                        id="profilePhotoInput"
                        class="d-none"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <p class="text-soft small mb-3">
                        Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-primary" id="profilePhotoPickButton">
                            <em class="icon ni ni-camera"></em>
                            Ganti Foto
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <em class="icon ni ni-check"></em>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================================================
         INFORMASI AKUN (nama + email bisa diedit)
    ========================================================== --}}
    <div class="col-xl-7">
        <div class="card profile-card">
            <div class="card-body">
                <h6 class="profile-label mb-4">Informasi Akun</h6>

                <form method="POST" action="{{ route('admin.profile.update') }}" id="profileAccountForm" novalidate>
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="profile_name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="profile_name" name="name" value="{{ old('name', $user->name) }}"
                                   maxlength="255" autocomplete="name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="profile_email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="profile_email" name="email" value="{{ old('email', $user->email) }}"
                                   maxlength="255" autocomplete="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary" id="profileAccountSubmit">
                            <em class="icon ni ni-check"></em> Simpan Perubahan
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="profile-label mb-1">Role</div>
                        <div class="profile-value">
                            {{ $user->role_label }}
                            <span class="badge bg-light text-dark border ms-1">Read-only</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-label mb-1">Status Akun</div>
                        <div class="profile-value">
                            {{ $user->email_verified_at ? 'Email terverifikasi' : 'Email belum diverifikasi' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-label mb-1">Terdaftar Since</div>
                        <div class="profile-value">
                            {{ $user->created_at?->format('d M Y, H:i') ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-label mb-1">Total Catatan</div>
                        <div class="profile-value">{{ $notesCount }} catatan</div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-label mb-1">Telegram</div>
                        <div class="profile-value">
                            {{ $user->telegram_chat_id ? 'Terhubung' : 'Belum terhubung' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-label mb-1">Foto Profil</div>
                        <div class="profile-value">
                            {{ $user->avatar ? 'Diunggah' : 'Menggunakan avatar bawaan' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================
             KEAMANAN AKUN
        ========================================================== --}}
        <div class="card profile-card mt-4">
            <div class="card-body">
                <h6 class="profile-label mb-4">Keamanan Akun</h6>

                <form method="POST" action="{{ route('admin.profile.password.update') }}" id="profilePasswordForm" novalidate>
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" autocomplete="current-password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" autocomplete="new-password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" autocomplete="new-password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary" id="profilePasswordSubmit">
                            <em class="icon ni ni-lock"></em> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var input = document.getElementById('profilePhotoInput');
        var picker = document.getElementById('profilePhotoPicker');
        var pickButton = document.getElementById('profilePhotoPickButton');
        var preview = document.getElementById('profileAvatarPreview');
        var current = document.getElementById('profileAvatarCurrent');
        var form = document.getElementById('profilePhotoForm');

        if (!input || !preview) {
            return;
        }

        function openPicker(event) {
            if (event) {
                event.preventDefault();
            }
            input.click();
        }

        if (picker) {
            picker.addEventListener('click', openPicker);
        }

        if (pickButton) {
            pickButton.addEventListener('click', openPicker);
        }

        /* Preview foto sebelum diunggah, tanpa mengirim file ke server. */
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];

            if (!file) {
                return;
            }

            if (!file.type || file.type.indexOf('image/') !== 0) {
                input.value = '';
                return;
            }

            var reader = new FileReader();

            reader.onload = function (event) {
                preview.src = event.target.result;

                if (current) {
                    current.src = event.target.result;
                }
            };

            reader.readAsDataURL(file);
        });

        /* Cegah submit kosong (tombol Simpan tanpa foto baru). */
        if (form) {
            form.addEventListener('submit', function (event) {
                if (!input.files || input.files.length === 0) {
                    event.preventDefault();
                }
            });
        }
    })();
</script>
@endpush
