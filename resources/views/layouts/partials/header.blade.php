<div class="nk-header nk-header-fixed">
    <div class="container-fluid">
        <div class="nk-header-wrap">

            <div class="d-flex align-items-center">
                <div class="nk-sidebar-toggle">
                    <button class="btn btn-sm btn-icon btn-zoom sidebar-toggle d-sm-none"><em class="icon ni ni-menu"></em></button><button class="btn btn-md btn-icon btn-zoom sidebar-toggle d-none d-sm-inline-flex"><em class="icon ni ni-menu"></em></button>
                </div>
                <div class="nk-navbar-toggle me-2">
                    <button class="btn btn-sm btn-icon btn-zoom navbar-toggle d-sm-none"><em class="icon ni ni-menu-right"></em></button><button class="btn btn-md btn-icon btn-zoom navbar-toggle d-none d-sm-inline-flex"><em class="icon ni ni-menu-right"></em></button>
                </div>
                {{-- Header atas hanya menampilkan NAMA HALAMAN AKTIF.
                     Tidak ada lagi brand "RadneXt NOC" di sini; nama halaman
                     berasal dari App\Helpers\AdminPageTitle::resolve() yang
                     di-inject lewat View::composer di AppServiceProvider.

                     Tanpa margin-left: judul ini mulai tepat di gutter yang
                     sama dengan .admin-content, sehingga sejajar vertikal
                     dengan judul intro di konten (lihat blok 1b di
                     layouts/admin.blade.php). --}}
                <div class="d-none d-sm-block nk-header-title">
                    <h5 class="mb-0">{{ $pageTitle }}</h5>
                </div>
            </div>

            <div class="nk-header-tools ms-auto">
                <ul class="nk-quick-nav ms-2">
                    @php $unreadCount = $unreadCount ?? 0; @endphp
                    <li>
                        <button class="btn btn-icon btn-sm btn-zoom position-relative d-sm-none" data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas">
                            <em class="icon ni ni-bell"></em>
                            <span class="badge bg-danger position-absolute js-notification-badge{{ $unreadCount > 0 ? '' : ' d-none' }}" style="top:-4px;right:-4px;font-size:.6rem;" data-unread="{{ $unreadCount }}">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        </button>
                        <button class="btn btn-icon btn-md btn-zoom position-relative d-none d-sm-inline-flex" data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas">
                            <em class="icon ni ni-bell"></em>
                            <span class="badge bg-danger position-absolute js-notification-badge{{ $unreadCount > 0 ? '' : ' d-none' }}" style="top:-4px;right:-4px;font-size:.6rem;" data-unread="{{ $unreadCount }}">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        </button>
                    </li>
                    <li class="dropdown">
                        <a href="{{ url('/admin') }}#" data-bs-toggle="dropdown">
                        <div class="d-sm-none">
                            <div class="media media-md media-circle">
                                <img src="{{ Auth::user()?->avatar_url }}" alt="" class="img-thumbnail">
                            </div>
                        </div>
                        <div class="d-none d-sm-block">
                            <div class="media media-circle">
                                <img src="{{ Auth::user()?->avatar_url }}" alt="" class="img-thumbnail">
                            </div>
                        </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md">
                            <div class="dropdown-content dropdown-content-x-lg py-3 border-bottom border-light">
                                <div class="media-group">
                                    <div class="media media-xl media-middle media-circle">
                                        <img src="{{ Auth::user()?->avatar_url }}" alt="" class="img-thumbnail">
                                    </div>
                                    <div class="media-text">
                                        <div class="lead-text">
                                            {{ Auth::user()->name ?? 'User' }}
                                        </div>
                                        <span class="sub-text">{{ Auth::user()->email ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-content dropdown-content-x-lg py-3">
                                <ul class="link-list">
                                    <li>
                                        <a href="{{ route('admin.profile') }}">
                                            <em class="icon ni ni-user"></em> <span>Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        >
                                            <em class="icon ni ni-signout"></em> <span>Log Out</span>
                                        </a>
                                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>