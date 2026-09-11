<div class="nk-header nk-header-fixed">
    <div class="container-fluid">
        <div class="nk-header-wrap">

            @php
                $pageTitles = [
                    'admin.dashboard' => 'Dashboard',
                    'admin.monitoring' => 'Monitoring Jaringan',
                    'admin.detail-router' => 'Detail Router',
                    'admin.log' => 'Log History',
                    'admin.notifikasi' => 'Notifikasi',
                    'admin.troubleshooting' => 'Troubleshooting',
                    'admin.kelola-router' => 'Kelola Router',
                    'admin.laporan' => 'Laporan & Grafik',
                    'admin.tools' => 'Network Tools',
                ];
                $currentRoute = request()->route() ? request()->route()->getName() : '';
            @endphp

            <div class="d-flex align-items-center">
                <div class="nk-sidebar-toggle">
                    <button class="btn btn-sm btn-icon btn-zoom sidebar-toggle d-sm-none"><em class="icon ni ni-menu"></em></button><button class="btn btn-md btn-icon btn-zoom sidebar-toggle d-none d-sm-inline-flex"><em class="icon ni ni-menu"></em></button>
                </div>
                <div class="nk-navbar-toggle me-2">
                    <button class="btn btn-sm btn-icon btn-zoom navbar-toggle d-sm-none"><em class="icon ni ni-menu-right"></em></button><button class="btn btn-md btn-icon btn-zoom navbar-toggle d-none d-sm-inline-flex"><em class="icon ni ni-menu-right"></em></button>
                </div>
                <div class="d-none d-sm-flex flex-column justify-content-center ms-1 ms-xl-2">
                    <small class="overline-title mb-0">RadneXt NOC</small>
                    <h5 class="mb-0">{{ $pageTitles[$currentRoute] ?? 'Dashboard' }}</h5>
                </div>
            </div>

            <div class="nk-header-tools ms-auto">
                <ul class="nk-quick-nav ms-2">
                    <li class="dropdown">
                        <button class="btn btn-icon btn-sm btn-zoom d-sm-none" data-bs-toggle="dropdown" data-bs-auto-close="outside"><em class="icon ni ni-search"></em></button><button class="btn btn-icon btn-md btn-zoom d-none d-sm-inline-flex" data-bs-toggle="dropdown" data-bs-auto-close="outside"><em class="icon ni ni-search"></em></button>
                        <div class="dropdown-menu dropdown-menu-lg">
                            <div class="dropdown-content dropdown-content-x-lg py-1">
                                <div class="search-inline">
                                    <div class="form-control-wrap flex-grow-1">
                                        <input placeholder="Type Query" type="text" class="form-control-plaintext">
                                    </div>
                                    <em class="icon icon-sm ni ni-search"></em>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <button class="btn btn-icon btn-sm btn-zoom d-sm-none" data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas"><em class="icon ni ni-bell"></em></button><button class="btn btn-icon btn-md btn-zoom d-none d-sm-inline-flex" data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas"><em class="icon ni ni-bell"></em></button>
                    </li>
                    <li class="dropdown">
                        <a href="{{ url('/admin') }}#" data-bs-toggle="dropdown">
                        <div class="d-sm-none">
                            <div class="media media-md media-circle">
                                <img src="{{ asset('admin-assets/images/avatar/a.jpg') }}" alt="" class="img-thumbnail">
                            </div>
                        </div>
                        <div class="d-none d-sm-block">
                            <div class="media media-circle">
                                <img src="{{ asset('admin-assets/images/avatar/a.jpg') }}" alt="" class="img-thumbnail">
                            </div>
                        </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md">
                            <div class="dropdown-content dropdown-content-x-lg py-3 border-bottom border-light">
                                <div class="media-group">
                                    <div class="media media-xl media-middle media-circle">
                                        <img src="{{ asset('admin-assets/images/avatar/a.jpg') }}" alt="" class="img-thumbnail">
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