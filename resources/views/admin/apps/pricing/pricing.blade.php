@extends('layouts.admin')
@section('title', 'Pricing - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Pricing Table
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/pricing/pricing') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/pricing/pricing') }}#">Pricing</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Pricing
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/apps/pricing/pricing') }}#" class="btn btn-md d-md-none btn-primary"><em class="icon ni ni-plus"></em><span>Add</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/apps/pricing/pricing') }}#" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Project</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card nk-pricing-wrap">
                                <div class="nk-pricing-header">
                                    <div class="nk-pricing-header-content text-center">
                                        <div class="h1 nk-pricing-title mb-3">
                                            Build your package by picking best plans
                                        </div>
                                        <p class="nk-pricing-desc">
                                            Our affordable pricing scales with your business. We don’t lock our features behind expensive plans. You get all the features on every plan.
                                        </p>
                                        <ul class="d-flex flex-wrap justify-content-center gap g-3 pt-4">
                                            <li>
                                                <a href="{{ url('/admin/apps/pricing/pricing') }}#" class="btn btn-soft btn-outline-primary"><span class="fw-medium">Monthly</span></a>
                                            </li>
                                            <li>
                                                <a href="{{ url('/admin/apps/pricing/pricing') }}#" class="btn btn-soft btn-outline-light"><span class="fw-medium">Annual</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="nk-pricing-body">
                                    <div class="row g-gs">
                                        <div class="col-lg-6 col-xxl-4">
                                            <div class="card card-gutter-md nk-pricing-card h-100">
                                                <div class="card-body">
                                                    <div class="nk-pricing-card-title mb-4">
                                                        <h2 class="title">
                                                            Basic
                                                        </h2>
                                                        <p class="text text-light">
                                                            For individuals and teams getting started.
                                                        </p>
                                                    </div>
                                                    <div class="amount mb-4">
                                                        <span class="display-4 text-primary fw-medium">$99</span><span class="text-light small">/ Monthly</span>
                                                    </div>
                                                    <h4 class="mb-3">
                                                        Includes
                                                    </h4>
                                                    <ul class="nk-list-option-list mb-4">
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Single user licence with one workspace</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Access to your individual asset library</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Access to standard templates</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span> Export content as PNG, PDF and MP4</span>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/pricing/pricing') }}#" class="btn btn-primary">Choose this plan</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-4">
                                            <div class="card card-gutter-md nk-pricing-card h-100">
                                                <div class="card-body">
                                                    <div class="nk-pricing-card-title mb-4">
                                                        <h2 class="title">
                                                            Premium
                                                        </h2>
                                                        <p class="text text-light">
                                                            For power users and growing teams.
                                                        </p>
                                                    </div>
                                                    <div class="amount mb-4">
                                                        <span class="display-4 text-primary fw-medium">$189</span><span class="text-light small">/ Monthly</span>
                                                    </div>
                                                    <h4 class="mb-3">
                                                        Eveything in Basic, plus
                                                    </h4>
                                                    <ul class="nk-list-option-list mb-4">
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Up to 3 members inside your workspace.</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Unlimited customisation for Design</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Import static & animated templates.</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Access to pro templates</span>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/pricing/pricing') }}#" class="btn btn-primary">Choose this plan</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xxl-4">
                                            <div class="card card-gutter-md nk-pricing-card h-100">
                                                <div class="card-body">
                                                    <div class="nk-pricing-card-title mb-4">
                                                        <h2 class="title">
                                                            Enterprise
                                                        </h2>
                                                        <p class="text text-light">
                                                            For individuals and teams getting started.
                                                        </p>
                                                    </div>
                                                    <div class="amount mb-4">
                                                        <span class="display-4 text-primary fw-medium">$480</span><span class="text-light small">/ Monthly</span>
                                                    </div>
                                                    <h4 class="mb-3">
                                                        Everything we can do
                                                    </h4>
                                                    <ul class="nk-list-option-list mb-4">
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Unlimited members for your workspace.</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Define individual roles and permissions.</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Custom designed templates</span>
                                                        </li>
                                                        <li>
                                                            <em class="icon ni ni-check-circle-fill"></em><span>Priority support</span>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/pricing/pricing') }}#" class="btn btn-primary">Choose this plan</a>
                                                </div>
                                            </div>
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
