@extends('layouts.admin')
@section('title', 'NFT - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="row g-gs">
                            <div class="col-xl-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex flex-column flex-sm-row-reverse flex-xxl-row-reverse justify-content-between align-items-sm-center align-items-xxl-center gap g-3">
                                            <div class="gap-col d-xl-none d-xxl-block">
                                                <img src="{{ asset('admin-assets/images/award/b.png') }}" alt="" class="pt-lg-3">
                                            </div>
                                            <div class="gap-col">
                                                <div class="card-title mb-4">
                                                    <h4 class="title mb-1 lh-base">
                                                        Discover, Collect, Sell and Create your own NFTs.
                                                    </h4>
                                                    <p class="small">
                                                        The world's first and largest digital marketplace.
                                                    </p>
                                                </div>
                                                <div class="d-flex flex-wrap gap g-3">
                                                    <div class="gap-col">
                                                        <div class="d-flex align-items-center">
                                                            <div class="amount h3 mb-0">
                                                                25k
                                                            </div>
                                                            <span class="ms-1 small">At work</span>
                                                        </div>
                                                    </div>
                                                    <div class="gap-col">
                                                        <div class="d-flex align-items-center">
                                                            <div class="amount h3 mb-0">
                                                                80k
                                                            </div>
                                                            <span class="ms-1 small">Auction</span>
                                                        </div>
                                                    </div>
                                                    <div class="gap-col">
                                                        <div class="d-flex align-items-center">
                                                            <div class="amount h3 mb-0">
                                                                22k
                                                            </div>
                                                            <span class="ms-1 small">Artist</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="d-flex flex-wrap gap g-2 pt-4">
                                                    <li>
                                                        <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-primary">Explore Now</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-secondary">Create Your Own</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card h-100 overflow-hidden">
                                    <div class="card-body pb-0">
                                        <div class="card-title-group">
                                            <div class="media-group">
                                                <div class="media media-sm media-middle media-circle text-bg-primary">
                                                    <em class="icon ni ni-coins"></em>
                                                </div>
                                                <div class="media-text">
                                                    <h4>
                                                        Total Revenue
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <li>
                                                            <div class="dropdown-header pt-2 pb-0">
                                                                <h6 class="mb-0">
                                                                    Options
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">7 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">15 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">30 Days</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="amount-wrap mt-3">
                                            <div class="amount h2 mb-2">
                                                $2,526.56
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap g-1">
                                                <div class="gap-col">
                                                    <span class="badge text-bg-success-soft"><em class="icon ni ni-arrow-up"></em> 4.55% </span>
                                                </div>
                                                <div class="gap-col">
                                                    <span class="small">Previous month</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-chart-nft-total-revenue m-n1">
                                        <canvas data-nk-chart="line" id="totalRevenueChart">
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-3">
                                <div class="card h-100 overflow-hidden">
                                    <div class="card-body pb-0">
                                        <div class="card-title-group">
                                            <div class="media-group">
                                                <div class="media media-sm media-middle media-circle text-bg-warning">
                                                    <em class="icon ni ni-wallet-fill text-white"></em>
                                                </div>
                                                <div class="media-text">
                                                    <h4>
                                                        Estimated
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <li>
                                                            <div class="dropdown-header pt-2 pb-0">
                                                                <h6 class="mb-0">
                                                                    Options
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">7 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">15 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">30 Days</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="amount-wrap mt-3">
                                            <div class="amount h2 mb-2">
                                                $5,625.64
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap g-1">
                                                <div class="gap-col">
                                                    <span class="badge text-bg-danger-soft"><em class="icon ni ni-arrow-down"></em> 3.88% </span>
                                                </div>
                                                <div class="gap-col">
                                                    <span class="small">Previous month</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-chart-nft-estimated m-n1">
                                        <canvas data-nk-chart="line" id="estimatedChart">
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title-group mb-4">
                                            <div class="card-title">
                                                <h5 class="title">
                                                    Marketplace
                                                </h5>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <li>
                                                            <div class="dropdown-header pt-2 pb-0">
                                                                <h6 class="mb-0">
                                                                    Options
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">7 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">15 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">30 Days</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-chart-nft-marketplace mb-3">
                                            <canvas data-nk-chart="line" id="marketplaceChart">
                                            </canvas>
                                        </div>
                                        <div class="chart-legend-group justify-content-center flex-wrap gy-2 gap gx-4">
                                            <div class="gap-col">
                                                <div class="chart-legend chart-legend-small">
                                                    <span class="dot bg-warning"></span>
                                                    <div class="chart-legend-text">
                                                        <div class="title">
                                                            Total Atwork
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gap-col">
                                                <div class="chart-legend chart-legend-small">
                                                    <span class="dot bg-success"></span>
                                                    <div class="chart-legend-text">
                                                        <div class="title">
                                                            Total Auction
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gap-col">
                                                <div class="chart-legend chart-legend-small">
                                                    <span class="dot bg-pink"></span>
                                                    <div class="chart-legend-text">
                                                        <div class="title">
                                                            Total Artist
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6">
                                <div class="card h-100">
                                    <div class="card-body flex-grow-0 py-2">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h5 class="title">
                                                    Recent NFT Collections
                                                </h5>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <div class="dropdown-header pt-2 pb-0">
                                                                <h6 class="mb-0">
                                                                    Quick Actions
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">New Ticket</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">New Customer</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">New Contact</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-middle mb-0">
                                            <thead class="table-light table-head-sm">
                                                <tr>
                                                    <th class="tb-col">
                                                        <span class="overline-title">Collections</span>
                                                    </th>
                                                    <th class="tb-col tb-col-end">
                                                        <span class="overline-title">Volume</span>
                                                    </th>
                                                    <th class="tb-col tb-col-end">
                                                        <span class="overline-title">24h%</span>
                                                    </th>
                                                    <th class="tb-col tb-col-end">
                                                        <span class="overline-title">creators</span>
                                                    </th>
                                                    <th class="tb-col tb-col-end">
                                                        <span class="overline-title">items</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title text-truncate">Nike v22 Running</span><span class="text smaller">Artworks</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">8,568.02</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="change up small">
                                                            <em class="icon ni ni-trend-up"></em><span>3.08</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">6.0k</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">18.6k</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/b.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title text-truncate">Business Kit (Mug)</span><span class="text smaller">16 Oct 2022</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">24,718.40</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="change up small">
                                                            <em class="icon ni ni-trend-up"></em><span>1.20</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">3.0k</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">13.6k</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/c.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title text-truncate">Borosil Paper Cup</span><span class="text smaller">21 Feb 2022</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">3,280.45</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="change down small">
                                                            <em class="icon ni ni-trend-down"></em><span>0.34</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">5.0k</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">12.1k</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/d.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title text-truncate">Mountain Trip Kit</span><span class="text smaller">14 Jun 2022</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">28,843.25</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="change up small">
                                                            <em class="icon ni ni-trend-up"></em><span>8.26</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">5.0k</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">19.6k</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/e.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title text-truncate">One Seater Sofa</span><span class="text smaller">28 Jul 2022</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">18,735.20</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="change down small">
                                                            <em class="icon ni ni-trend-down"></em><span>7.65</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">7.5k</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">15.6k</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4">
                                <div class="card h-100">
                                    <div class="card-body flex-grow-0 py-2">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h5 class="title">
                                                    History of Bids
                                                </h5>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                        <li>
                                                            <div class="dropdown-header pt-2 pb-0">
                                                                <h6 class="mb-0">
                                                                    Options
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">7 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">15 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-nft') }}#" class="dropdown-item">30 Days</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-middle mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/index-nft') }}#" class="title">Jane Cooper</a><span class="text smaller">@janeCooper12</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">346.47 ETH</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/b.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/index-nft') }}#" class="title">Esther Howard</a><span class="text smaller">@estherHoward</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">852.34 ETH</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/c.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/index-nft') }}#" class="title">Tower Hill</a><span class="text smaller">@towerHill</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">754.15 ETH</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/d.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/index-nft') }}#" class="title">Michael Morris</a><span class="text smaller">@michael21</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">14.92 ETH</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/product/e.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/index-nft') }}#" class="title">Alexis Clarke</a><span class="text smaller">@alexis_30</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">30.749 ETH</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title-group mb-4">
                                            <div class="card-title">
                                                <h5 class="title">
                                                    Popularity
                                                </h5>
                                            </div>
                                            <div class="chart-legend-group gap gx-3 align-items-center">
                                                <div class="gap-col">
                                                    <div class="chart-legend chart-legend-small">
                                                        <span class="dot bg-success"></span>
                                                        <div class="chart-legend-text">
                                                            <div class="title">
                                                                Like
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="gap-col">
                                                    <div class="chart-legend chart-legend-small">
                                                        <span class="dot bg-info"></span>
                                                        <div class="chart-legend-text">
                                                            <div class="title">
                                                                Share
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-chart-nft-popularity">
                                            <canvas data-nk-chart="bar" id="popularityChart">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body flex-grow-0 py-2">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h5 class="title">
                                                    Popular Creators
                                                </h5>
                                            </div>
                                            <div class="card-tools d-none d-sm-inline-block">
                                                <a href="{{ url('/admin/user-manage/user-list') }}" class="btn btn-sm btn-soft btn-secondary"><span>See all</span> <em class="icon ni ni-chevron-right"></em></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-middle mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/avatar/a.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="title">Timothy Smith</a><span class="text smaller">68,945 ETH</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="dropdown">
                                                            <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Share</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-flag"></em><span>Report</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/avatar/b.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="title">Alexis Clarke</a><span class="text smaller">58,943 ETH</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="dropdown">
                                                            <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Share</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-flag"></em><span>Report</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/avatar/c.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="title">Herbert Stokes</a><span class="text smaller">45,657 ETH</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="dropdown">
                                                            <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Share</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-flag"></em><span>Report</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/avatar/d.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="title">Nancy Martino</a><span class="text smaller">38,945 ETH</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="dropdown">
                                                            <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Share</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-flag"></em><span>Report</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle media-circle">
                                                                <img src="{{ asset('admin-assets/images/avatar/e.jpg') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="title">Michael Morris</a><span class="text smaller">18,945 ETH</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <div class="dropdown">
                                                            <a href="{{ url('/admin/index-nft') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Share</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-flag"></em><span>Report</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin-assets/assets/js/charts/nft-chart.js') }}"></script>
    @endpush
