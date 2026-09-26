{{--
    PAGE HEAD GLOBAL ADMIN
    ---------------------------------------------------------
    Satu komponen untuk seluruh halaman admin agar heading,
    breadcrumb, dan tombol aksi selalu berada pada garis
    kiri/kanan yang sama dengan card, filter, dan tabel.

    Pemakaian:
        <x-admin.page-head
            title="Kelola Router"
            subtitle="Kelola daftar router RADNET."
            icon="wifi"
            :crumbs="[['label' => 'Kelola Router']]">
            <x-slot:actions>
                <button class="btn btn-primary">Tambah</button>
            </x-slot:actions>
        </x-admin.page-head>

    Props:
        title      string  judul halaman
        subtitle   string  deskripsi singkat (opsional)
        icon       string  nama icon Nio (opsional), mis. "note"
        iconUrl    string  URL gambar sebagai icon (opsional)
        iconClass  string  modifier icon, mis. "is-telegram"
        crumbs     array   breadcrumb tanpa item "Home"
        boxed      bool    judul berada di dalam surface penuh
--}}
@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'iconUrl' => null,
    'iconClass' => '',
    'crumbs' => [],
    'boxed' => false,
])

<div {{ $attributes->class(['admin-page-head', 'admin-page-head-boxed' => $boxed]) }}>
    <div class="admin-page-head-main">
        @if(!empty($crumbs))
            <nav class="admin-page-head-breadcrumb" aria-label="Breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow mb-0">
                    @foreach($crumbs as $crumb)
                        @if(!empty($crumb['url']) && !$loop->last)
                            <li class="breadcrumb-item">
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">{{ $crumb['label'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif

        <div class="admin-page-head-title">
            @if(!empty($iconUrl))
                <span class="admin-page-head-icon {{ $iconClass }}" aria-hidden="true">
                    <img src="{{ $iconUrl }}" alt="">
                </span>
            @elseif(!empty($icon))
                <span class="admin-page-head-icon {{ $iconClass }}" aria-hidden="true">
                    <em class="icon ni ni-{{ $icon }}"></em>
                </span>
            @endif

            <div class="admin-page-head-text">
                <h1>{{ $title }}</h1>
                @if(!empty($subtitle))
                    <p>{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>

    @if(isset($actions) && trim((string) $actions) !== '')
        <div class="admin-page-head-actions">
            {{ $actions }}
        </div>
    @endif
</div>
