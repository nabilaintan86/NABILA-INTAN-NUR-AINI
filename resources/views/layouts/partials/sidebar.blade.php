<div class="nk-sidebar nk-sidebar-fixed is-theme" id="sidebar">

    {{-- =========================================================
        SIDEBAR HEADER / LOGO
    ========================================================== --}}
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">

            {{-- LOGO RADNEXT --}}
            <a href="{{ url('/admin') }}" class="logo-link">
                <div class="logo-wrap">
                    <img src="{{ asset('admin-assets/images/radnext-logo.png') }}"
                         alt="RadneXt"
                         style="max-width: 140px; height: auto; object-fit: contain;">
                </div>
            </a>
            {{-- DESKTOP: COLLAPSE / COMPACT SIDEBAR --}}
            <div class="nk-compact-toggle me-n1">
                <button
                    type="button"
                    class="btn btn-md btn-icon text-light btn-no-hover compact-toggle"
                    title="Collapse Sidebar"
                    aria-label="Collapse Sidebar"
                >
                    <em class="icon off ni ni-chevrons-left"></em>
                    <em class="icon on ni ni-chevrons-right"></em>
                </button>
            </div>

            {{-- MOBILE: SIDEBAR TOGGLE --}}
            <div class="nk-sidebar-toggle me-n1">
                <button
                    type="button"
                    class="btn btn-md btn-icon text-light btn-no-hover sidebar-toggle"
                    title="Close Sidebar"
                    aria-label="Close Sidebar"
                >
                    <em class="icon ni ni-arrow-left"></em>
                </button>
            </div>

        </div>
    </div>


    {{-- =========================================================
        SIDEBAR BODY
    ========================================================== --}}
    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">

            <div class="nk-sidebar-menu" data-simplebar>

                <ul class="nk-menu">

                    {{-- =================================================
                        DASHBOARD
                    ================================================== --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-dashboard"></em>
                            </span>

                            <span class="nk-menu-text">
                                Dashboard
                            </span>
                        </a>
                    </li>


                    {{-- =================================================
                        MONITORING
                    ================================================== --}}
                    <li class="nk-menu-heading">
                        <h6 class="overline-title">
                            Monitoring
                        </h6>
                    </li>

                    {{-- Monitoring Jaringan --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.monitoring') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-server"></em>
                            </span>

                            <span class="nk-menu-text">
                                Monitoring Jaringan
                            </span>
                        </a>
                    </li>

                    {{-- Network Map --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.map') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-location"></em>
                            </span>

                            <span class="nk-menu-text">
                                Network Map
                            </span>
                        </a>
                    </li>

                    {{-- Map Editor --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.map.editor') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-edit"></em>
                            </span>

                            <span class="nk-menu-text">
                                Map Editor
                            </span>
                        </a>
                    </li>

                    {{-- Log History --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.log') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-clock"></em>
                            </span>

                            <span class="nk-menu-text">
                                Log History
                            </span>
                        </a>
                    </li>

                    {{-- Notifikasi --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.notifikasi') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-bell"></em>
                            </span>

                            <span class="nk-menu-text">
                                Notifikasi
                            </span>
                        </a>
                    </li>


                    {{-- =================================================
                        MANAJEMEN
                    ================================================== --}}
                    <li class="nk-menu-heading">
                        <h6 class="overline-title">
                            Manajemen
                        </h6>
                    </li>

                    {{-- Troubleshooting --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.troubleshooting') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-setting-alt"></em>
                            </span>

                            <span class="nk-menu-text">
                                Troubleshooting
                            </span>
                        </a>
                    </li>

                    {{-- Kelola Router --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.kelola-router') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-list-check"></em>
                            </span>

                            <span class="nk-menu-text">
                                Kelola Router
                            </span>
                        </a>
                    </li>

                    {{-- Kelola User (khusus Super Admin) --}}
                    @if(auth()->check() && auth()->user()->role === 'super_admin')
                        <li class="nk-menu-item">
                            <a
                                href="{{ route('admin.kelola-user') }}"
                                class="nk-menu-link"
                            >
                                <span class="nk-menu-icon">
                                    <em class="icon ni ni-users"></em>
                                </span>

                                <span class="nk-menu-text">
                                    Kelola User
                                </span>
                            </a>
                        </li>
                    @endif


                    {{-- =================================================
                        LAPORAN & TOOLS
                    ================================================== --}}
                    <li class="nk-menu-heading">
                        <h6 class="overline-title">
                            Laporan &amp; Tools
                        </h6>
                    </li>

                    {{-- Laporan & Grafik --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.laporan') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-chart-pie"></em>
                            </span>

                            <span class="nk-menu-text">
                                Laporan &amp; Grafik
                            </span>
                        </a>
                    </li>

                    {{-- Network Tools --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.tools') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-screwdriver"></em>
                            </span>

                            <span class="nk-menu-text">
                                Network Tools
                            </span>
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </div>

</div>