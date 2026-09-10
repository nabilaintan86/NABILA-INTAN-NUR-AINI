@extends('layouts.admin')

@section('title', 'Dashboard Monitoring')

@section('content')
<div class="row g-gs">
    <!-- Statistik Items Up -->
    <div class="col-sm-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-title-group align-items-start">
                    <div class="card-title">
                        <h4 class="title">Items Up</h4>
                    </div>
                    <div class="media media-middle media-circle media-sm text-bg-success">
                        <em class="icon icon-md ni ni-arrow-up"></em>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="amount h1">92</div>
                    <div class="d-flex align-items-center smaller">
                        <span class="text-soft">Perangkat aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Items Down -->
    <div class="col-sm-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-title-group align-items-start">
                    <div class="card-title">
                        <h4 class="title">Items Down</h4>
                    </div>
                    <div class="media media-middle media-circle media-sm text-bg-danger">
                        <em class="icon icon-md ni ni-arrow-down"></em>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="amount h1">14</div>
                    <div class="d-flex align-items-center smaller">
                        <span class="text-soft">Perangkat bermasalah</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Services Down -->
    <div class="col-sm-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-title-group align-items-start">
                    <div class="card-title">
                        <h4 class="title">Services Down</h4>
                    </div>
                    <div class="media media-middle media-circle media-sm text-bg-warning">
                        <em class="icon icon-md ni ni-alert"></em>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="amount h1">0</div>
                    <div class="d-flex align-items-center smaller">
                        <span class="text-soft">Service bermasalah</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map / Topologi -->
    <div class="col-xxl-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-title-group">
                    <div class="card-title">
                        <h5 class="title">Topologi Jaringan</h5>
                    </div>
                    <span class="text-xs text-soft">RADNET Wireless Network 2025</span>
                </div>
                <div class="mt-3 p-4 bg-light rounded-3 text-center">
                    <em class="icon ni ni-map text-soft display-4"></em>
                    <p class="text-soft mt-2">Visualisasi peta interaktif akan ditampilkan di sini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi Terbaru -->
    <div class="col-xxl-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-title-group">
                    <div class="card-title">
                        <h5 class="title">Notifikasi Terbaru</h5>
                    </div>
                    <a href="{{ route('admin.notifikasi') }}" class="link text-sm">Lihat semua</a>
                </div>
                <ul class="nk-timeline mt-3">
                    <li class="nk-timeline-item">
                        <div class="nk-timeline-item-inner">
                            <div class="nk-timeline-symbol text-danger">
                                <em class="icon ni ni-cross-circle"></em>
                            </div>
                            <div class="nk-timeline-content">
                                <p class="small"><strong>Router_A</strong> DOWN - Host Unreachable</p>
                            </div>
                        </div>
                    </li>
                    <li class="nk-timeline-item">
                        <div class="nk-timeline-item-inner">
                            <div class="nk-timeline-symbol text-success">
                                <em class="icon ni ni-check-circle"></em>
                            </div>
                            <div class="nk-timeline-content">
                                <p class="small"><strong>Router_B</strong> BACK ONLINE - Response 12ms</p>
                            </div>
                        </div>
                    </li>
                    <li class="nk-timeline-item">
                        <div class="nk-timeline-item-inner">
                            <div class="nk-timeline-symbol text-danger">
                                <em class="icon ni ni-cross-circle"></em>
                            </div>
                            <div class="nk-timeline-content">
                                <p class="small"><strong>Router_C</strong> DOWN - Timed Out</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>