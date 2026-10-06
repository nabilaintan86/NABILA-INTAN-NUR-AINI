@extends('layouts.admin')

@section('title', 'Topology Maps')

@section('content')
<x-admin.page-head
    title="Topology Maps"
    subtitle="Kelola daftar maps untuk visualisasi jaringan."
>
    <x-slot:actions>
        <a href="{{ route('admin.topology-maps.create') }}" class="btn btn-primary">
            <em class="icon ni ni-plus"></em>
            <span>Tambah Maps</span>
        </a>
    </x-slot:actions>
</x-admin.page-head>

@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($maps->isEmpty())
            <div class="text-center py-5">
                <em class="icon ni ni-map" style="font-size:3rem;opacity:.3"></em>
                <p class="text-muted mt-3">Belum ada maps. Klik <strong>+ Tambah Maps</strong> untuk mulai.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Area/POP</th>
                            <th>Device</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maps as $map)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.topology-maps.show', $map) }}" class="fw-bold text-decoration-none">
                                        {{ $map->name }}
                                    </a>
                                </td>
                                <td>{{ $map->description ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ $map->areas_count }}</span></td>
                                <td><span class="badge bg-primary">{{ $map->devices_count }}</span></td>
                                <td>
                                    @if($map->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.topology-maps.show', $map) }}"
                                       class="btn btn-sm btn-light" title="Lihat">
                                        <em class="icon ni ni-eye"></em>
                                    </a>
                                    <a href="{{ route('admin.topology-maps.edit', $map) }}"
                                       class="btn btn-sm btn-light" title="Edit">
                                        <em class="icon ni ni-edit"></em>
                                    </a>
                                    <form action="{{ route('admin.topology-maps.destroy', $map) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin hapus maps {{ $map->name }}? Semua device & area di dalamnya akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus">
                                            <em class="icon ni ni-trash"></em>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection