@extends('layouts.admin')
@section('title', 'Marketing - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="row g-gs">
                            <div class="col-xxl-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title-group mb-4 align-items-start">
                                            <div class="card-title">
                                                <h4 class="title mb-0">
                                                    Highlights
                                                </h4>
                                                <span class="small">Latest social statistics</span>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-marketing') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <div class="dropdown-header pt-2 pb-0">
                                                                <h6 class="mb-0">
                                                                    Actions
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Ticket</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Customer</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Contact</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-sm-6 col-md-4 col-lg-6  col-xl-4">
                                                <div class="box-dotted h-100">
                                                    <em class="icon icon-lg ni ni-chart-up text-primary"></em>
                                                    <h5 class="title mt-2 mb-3">
                                                        Avg. Client Rating
                                                    </h5>
                                                    <div class="amount h3">
                                                        7.8/10
                                                    </div>
                                                    <div class="d-flex align-items-center smaller flex-wrap">
                                                        <div class="change up">
                                                            <em class="icon ni ni-trend-up"></em> +2.5%
                                                        </div>
                                                        <span>than last Week</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4 col-lg-6  col-xl-4">
                                                <div class="box-dotted h-100">
                                                    <em class="icon icon-lg ni ni-user-add text-success"></em>
                                                    <h5 class="title mt-2 mb-3">
                                                        Instagram Followers
                                                    </h5>
                                                    <div class="amount h3">
                                                        7784k
                                                    </div>
                                                    <div class="d-flex align-items-center smaller flex-wrap">
                                                        <div class="change down">
                                                            <em class="icon ni ni-trend-down"></em> -1.5%
                                                        </div>
                                                        <span>than last Week</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-12 col-xl-4">
                                                <div class="box-dotted h-100">
                                                    <em class="icon icon-lg ni ni-coins text-warning"></em>
                                                    <h5 class="title mt-2 mb-3">
                                                        Google Ads CPC
                                                    </h5>
                                                    <div class="amount h3">
                                                        $5.02
                                                    </div>
                                                    <div class="d-flex align-items-center smaller flex-wrap">
                                                        <div class="change up">
                                                            <em class="icon ni ni-trend-up"></em> +2.6%
                                                        </div>
                                                        <span>than last Week</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xxl-3">
                                <div class="card h-100 overflow-hidden">
                                    <div class="col-sep">
                                        <div class="card-body">
                                            <div class="card-title-group align-items-start">
                                                <div class="card-title">
                                                    <h4 class="title mb-0">
                                                        External Links
                                                    </h4>
                                                    <span class="small">Most used resources</span>
                                                </div>
                                                <div class="card-tools">
                                                    <div class="dropdown">
                                                        <a href="{{ url('/admin/index-marketing') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
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
                                                                <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">Create Invoice</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">Create Payment</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">Generate Bill</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">Settings</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item py-3 d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('admin/images/icon/a.png') }}" class="me-2" alt=""><a href="{{ url('/admin/index-marketing') }}#" class="h6">Google Analytics</a>
                                                </div>
                                                <a href="{{ url('/admin/index-marketing') }}#" class="btn-link"><em class="icon ni ni-external-alt"></em></a>
                                            </li>
                                            <li class="list-group-item py-3 d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('admin/images/icon/b.png') }}" class="me-2" alt=""><a href="{{ url('/admin/index-marketing') }}#" class="h6">Facebook Ads</a>
                                                </div>
                                                <a href="{{ url('/admin/index-marketing') }}#" class="btn-link"><em class="icon ni ni-external-alt"></em></a>
                                            </li>
                                            <li class="list-group-item py-3 d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('admin/images/icon/c.png') }}" class="me-2" alt=""><a href="{{ url('/admin/index-marketing') }}#" class="h6">Seranking</a>
                                                </div>
                                                <a href="{{ url('/admin/index-marketing') }}#" class="btn-link"><em class="icon ni ni-external-alt"></em></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xxl-3">
                                <div class="card h-100 overflow-hidden">
                                    <div class="nk-chart-marketing-campaign bg-primary">
                                        <div class="card-body">
                                            <div class="nk-chart-marketing-campaign-visitor">
                                                <canvas data-nk-chart="bar" id="campaignChart">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h4 class="title mb-0">
                                                    Campaign Visitors
                                                </h4>
                                                <p class="small mt-1">
                                                    Last Campaign Performance
                                                </p>
                                            </div>
                                            <div class="amount-wrap text-end">
                                                <div class="amount h3 mb-0">
                                                    784k
                                                </div>
                                                <div class="change down small">
                                                    <em class="icon ni ni-trend-down"></em> -1.4%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h4 class="title mb-0">
                                                    Campaign Overview
                                                </h4>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-marketing') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
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
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">7 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">15 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">30 Days</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="amount-wrap mt-3 mb-4">
                                            <div class="d-flex align-items-center">
                                                <div class="amount h1 mb-0">
                                                    $560.93
                                                </div>
                                                <div class="change up smaller ms-1">
                                                    <em class="icon ni ni-trend-up"></em> +2.7%
                                                </div>
                                            </div>
                                            <span class="smaller">Avarage cost per interaction</span>
                                        </div>
                                        <div class="nk-chart-marketing-campaign-overview mb-2">
                                            <canvas data-nk-chart="line" id="campaignOverviewChart">
                                            </canvas>
                                        </div>
                                        <div class="chart-legend-group justify-content-center flex-wrap gap gy-2 gx-4">
                                            <div class="gap-col">
                                                <div class="chart-legend chart-legend-small">
                                                    <span class="dot bg-primary"></span>
                                                    <div class="chart-legend-text">
                                                        <div class="title">
                                                            Social Campaigns
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gap-col">
                                                <div class="chart-legend chart-legend-small">
                                                    <span class="dot bg-success"></span>
                                                    <div class="chart-legend-text">
                                                        <div class="title">
                                                            Email Newsletter
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gap-col">
                                                <div class="chart-legend chart-legend-small">
                                                    <span class="dot bg-warning"></span>
                                                    <div class="chart-legend-text">
                                                        <div class="title">
                                                            Google Ads
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
                                    <div class="card-body flex-grow-0">
                                        <div class="card-title-group mb-4 align-items-start">
                                            <div class="card-title">
                                                <h4 class="title mb-0">
                                                    Top Referral Sources
                                                </h4>
                                                <span class="small">80% activity growth</span>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-marketing') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
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
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">7 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">15 Days</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">30 Days</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="nav nav-pills nav-pills-border gap g-1">
                                            <li class="nav-item">
                                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button">
                                                <div class="d-flex align-items-center">
                                                    <div class="media media-middle media-sharp media-tiny">
                                                        <img src="{{ asset('admin/images/icon/d.png') }}" alt="">
                                                    </div>
                                                    <div class="h6 d-none d-md-inline-block ms-2">
                                                        Google
                                                    </div>
                                                </div>
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button">
                                                <div class="d-flex align-items-center">
                                                    <div class="media media-middle media-sharp media-tiny">
                                                        <img src="{{ asset('admin/images/icon/b.png') }}" alt="">
                                                    </div>
                                                    <div class="h6 d-none d-md-inline-block ms-2">
                                                        Facebook
                                                    </div>
                                                </div>
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button">
                                                <div class="d-flex align-items-center">
                                                    <div class="media media-middle media-sharp media-tiny">
                                                        <img src="{{ asset('admin/images/icon/e.png') }}" alt="">
                                                    </div>
                                                    <div class="h6 d-none d-md-inline-block ms-2">
                                                        Instagram
                                                    </div>
                                                </div>
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button">
                                                <div class="d-flex align-items-center">
                                                    <div class="media media-middle media-sharp media-tiny">
                                                        <img src="{{ asset('admin/images/icon/c.png') }}" alt="">
                                                    </div>
                                                    <div class="h6 d-none d-md-inline-block ms-2">
                                                        Seranking
                                                    </div>
                                                </div>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane show active" id="home-tab-pane" tabindex="0">
                                            <div class="table-responsive">
                                                <table class="table table-middle mb-0">
                                                    <thead class="table-light table-head-sm">
                                                        <tr>
                                                            <th class="tb-col">
                                                                <span class="overline-title">parameter</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">status</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">conversion</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end">
                                                                <span class="overline-title">revenue</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Email Marketing Campaign
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">37%(438)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$84,034</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google Workspace
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">100%(1.6k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$3,326</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google AdSense
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(634)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$28,359</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    New Model BS 2000 X
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0%(0)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$14,945</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Affiliation Program
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0.1%(153)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$5,806</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Best Rated Headsets of 2022
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(6.4k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$15,342</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="profile-tab-pane" tabindex="0">
                                            <div class="table-responsive">
                                                <table class="table table-middle mb-0">
                                                    <thead class="table-light table-head-sm">
                                                        <tr>
                                                            <th class="tb-col">
                                                                <span class="overline-title">parameter</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">status</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">conversion</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end">
                                                                <span class="overline-title">revenue</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google AdSense
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(634)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$28,359</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    New Model BS 2000 X
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0%(0)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$14,945</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Email Marketing Campaign
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">37%(438)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$84,034</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google Workspace
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">100%(1.6k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$3,326</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Affiliation Program
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0.1%(153)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$5,806</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Best Rated Headsets of 2022
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(6.4k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$15,342</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="contact-tab-pane" tabindex="0">
                                            <div class="table-responsive">
                                                <table class="table table-middle mb-0">
                                                    <thead class="table-light table-head-sm">
                                                        <tr>
                                                            <th class="tb-col">
                                                                <span class="overline-title">parameter</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">status</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">conversion</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end">
                                                                <span class="overline-title">revenue</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Email Marketing Campaign
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">37%(438)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$84,034</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google Workspace
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">100%(1.6k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$3,326</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google AdSense
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(634)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$28,359</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    New Model BS 2000 X
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0%(0)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$14,945</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Affiliation Program
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0.1%(153)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$5,806</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Best Rated Headsets of 2022
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(6.4k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$15,342</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="disabled-tab-pane" tabindex="0">
                                            <div class="table-responsive">
                                                <table class="table table-middle mb-0">
                                                    <thead class="table-light table-head-sm">
                                                        <tr>
                                                            <th class="tb-col">
                                                                <span class="overline-title">parameter</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">status</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end tb-col-sm">
                                                                <span class="overline-title">conversion</span>
                                                            </th>
                                                            <th class="tb-col tb-col-end">
                                                                <span class="overline-title">revenue</span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google AdSense
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(634)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$28,359</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    New Model BS 2000 X
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0%(0)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$14,945</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Email Marketing Campaign
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-info-soft">Active</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">37%(438)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$84,034</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Google Workspace
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">100%(1.6k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$3,326</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Affiliation Program
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-danger-soft">In draft</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">0.1%(153)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$5,806</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="tb-col">
                                                                <div class="h6">
                                                                    Best Rated Headsets of 2022
                                                                </div>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="badge text-bg-success-soft">Completed</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end tb-col-sm">
                                                                <span class="small">18%(6.4k)</span>
                                                            </td>
                                                            <td class="tb-col tb-col-end">
                                                                <span class="small">$15,342</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-xxl-4">
                                <div class="card h-100">
                                    <div class="card-body flex-grow-0 py-2">
                                        <div class="card-title-group align-items-start">
                                            <div class="card-title">
                                                <h4 class="title mb-0">
                                                    Top Channels
                                                </h4>
                                                <span class="small">Users from all channels</span>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-marketing') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
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
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Ticket</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Customer</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Contact</a>
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
                                                        <span class="overline-title">source</span>
                                                    </th>
                                                    <th class="tb-col tb-col-end">
                                                        <span class="overline-title">visitors</span>
                                                    </th>
                                                    <th class="tb-col tb-col-end">
                                                        <span class="overline-title">revenues</span>
                                                    </th>
                                                    <th class="tb-col tb-col-end">
                                                        <span class="overline-title">conversion</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-sm media-middle media-circle text-bg-dark">
                                                                <em class="icon ni ni-github-circle"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">github.com</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">2.4K</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="change up small">$3.877</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="text-info small">4.7%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-sm media-middle media-circle text-bg-info">
                                                                <em class="icon ni ni-twitter"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">twitter.com</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">2.4K</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="change up small">$3.426</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="text-info small">4.4%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-sm media-middle media-circle text-bg-danger">
                                                                <em class="icon ni ni-google"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">google.com</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">2.0K</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="change up small">$2.444</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="text-info small">4.2%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-sm media-middle media-circle text-bg-info">
                                                                <em class="icon ni ni-vimeo"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">vimeo.com</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">1.9K</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="change up small">$2.236</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="text-info small">4.2%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-sm media-middle media-circle text-bg-danger">
                                                                <em class="icon ni ni-youtube"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">youtube.com</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">1.9K</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="change up small">$2.236</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="text-info small">4.2%</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="tb-col">
                                                        <div class="media-group">
                                                            <div class="media media-sm media-middle media-circle text-bg-info">
                                                                <em class="icon ni ni-facebook-f"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">facebook.com</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="small">1.9K</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="change up small">$2.236</span>
                                                    </td>
                                                    <td class="tb-col tb-col-end">
                                                        <span class="text-info small">4.2%</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-xxl-4">
                                <div class="card h-100">
                                    <div class="col-sep">
                                        <div class="card-body py-2">
                                            <div class="card-title-group">
                                                <div class="card-title">
                                                    <h4 class="title mb-0">
                                                        Feedback
                                                    </h4>
                                                </div>
                                                <div class="card-tools">
                                                    <a href="{{ url('/admin/index-marketing') }}#" class="btn btn-sm btn-soft btn-secondary">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="nk-timeline nk-timeline-center">
                                                <ul class="nk-timeline-list">
                                                    <li class="nk-timeline-item">
                                                        <div class="nk-timeline-item-inner">
                                                            <div class="nk-timeline-symbol">
                                                                <div class="media media-md media-middle media-circle">
                                                                    <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="nk-timeline-content">
                                                                <p class="small">
                                                                    <strong>Timothy Smith</strong> Commented on Cloud <strong>Killan James</strong>
                                                                </p>
                                                                <span class="smaller time">1 hour ago</span>
                                                                <p class="small">
                                                                    It's an Affiliate commissions SaaS application that allows you to track your affiliate revenue.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="nk-timeline-item">
                                                        <div class="nk-timeline-item-inner">
                                                            <div class="nk-timeline-symbol">
                                                                <div class="media media-md media-middle media-circle">
                                                                    <img src="{{ asset('admin/images/avatar/b.jpg') }}" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="nk-timeline-content">
                                                                <p class="small">
                                                                    <strong>Nancy Martino</strong> Commented on Cloud <strong>Glen Matney</strong>
                                                                </p>
                                                                <span class="smaller time">2 hours ago</span>
                                                                <p class="small">
                                                                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="nk-timeline-item">
                                                        <div class="nk-timeline-item-inner">
                                                            <div class="nk-timeline-symbol">
                                                                <div class="media media-md media-middle media-circle">
                                                                    <img src="{{ asset('admin/images/avatar/c.jpg') }}" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="nk-timeline-content">
                                                                <p class="small">
                                                                    <strong>Michael Morris</strong> Commented on Cloud <strong>Williams Son</strong>
                                                                </p>
                                                                <span class="smaller time">3 hours ago</span>
                                                                <p class="small">
                                                                    But I must explain to you how all this mistaken idea of denouncing pleasure
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="card-title-group align-items-start">
                                            <div class="card-title">
                                                <h4 class="title mb-0">
                                                    Effectiveness
                                                </h4>
                                                <span class="small">User involment by ads type</span>
                                            </div>
                                            <div class="card-tools">
                                                <div class="dropdown">
                                                    <a href="{{ url('/admin/index-marketing') }}#" class="btn btn-sm btn-icon btn-zoom me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
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
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Ticket</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Customer</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/index-marketing') }}#" class="dropdown-item">New Contact</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-chart-marketing-effectiveness mt-3">
                                            <canvas data-nk-chart="radar" id="effectivenessChart">
                                            </canvas>
                                        </div>
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
        <script src="{{ asset('admin/assets/js/charts/marketing-chart.js') }}"></script>
    @endpush
