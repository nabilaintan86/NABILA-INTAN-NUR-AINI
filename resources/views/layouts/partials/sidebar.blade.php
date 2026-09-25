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

                    @auth
                        <li class="nk-menu-item">
                            <a
                                href="{{ route('admin.device.notes.report') }}"
                                class="nk-menu-link"
                            >
                                <span class="nk-menu-icon">
                                    <em class="icon ni ni-note"></em>
                                </span>

                                <span class="nk-menu-text">
                                    Report Catatan
                                </span>
                            </a>
                        </li>
                    @endauth

                    {{-- DHCP Lease --}}
                    <li class="nk-menu-item">
                        <a
                            href="{{ route('admin.dhcp-lease') }}"
                            class="nk-menu-link"
                        >
                            <span class="nk-menu-icon">
                                <em class="icon ni ni-wifi"></em>
                            </span>

                            <span class="nk-menu-text">
                                DHCP Lease
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

                        @php
                            $telegramPendingCount = \Illuminate\Support\Facades\Schema::hasTable('telegram_users')
                                ? \App\Models\TelegramUser::where('is_approved', false)->count()
                                : 0;
                        @endphp
                        <li class="nk-menu-item">
                            <a
                                href="{{ route('admin.telegram-users') }}"
                                class="nk-menu-link"
                            >
                                <span class="nk-menu-icon">
                                    <em class="icon ni ni-telegram"></em>
                                </span>

                                <span class="nk-menu-text">
                                    Telegram Users
                                </span>

                                @if($telegramPendingCount > 0)
                                    <span class="badge badge-warning rounded-pill ms-auto">{{ $telegramPendingCount }}</span>
                                @endif
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
                                <em class="icon ni ni-chart-up"></em>
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
                                <em class="icon ni ni-terminal"></em>
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