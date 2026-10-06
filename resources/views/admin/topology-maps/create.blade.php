@extends('layouts.admin')

@section('title', 'Tambah Maps')

@section('content')
<x-admin.page-head
    title="Tambah Maps"
    subtitle="Buat maps baru untuk visualisasi jaringan."
/>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.topology-maps.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Maps <span class="text-danger">*</span></label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Misal: Topology Utama, Gedung A, dll"
                       required
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description"
                          class="form-control @error('description') is-invalid @enderror"
                          rows="3"
                          placeholder="Deskripsi opsional...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <em class="icon ni ni-save"></em>
                    <span>Simpan Maps</span>
                </button>
                <a href="{{ route('admin.topology-maps.index') }}" class="btn btn-light">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection