@extends('layouts.admin')
@section('title', 'Support Center - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Help Desk
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/help-desk/overview') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/help-desk/overview') }}#">Help Desk</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Support
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/apps/help-desk/overview') }}#" class="btn btn-primary btn-md d-md-none" data-bs-toggle="modal" data-bs-target="#createTicketModal"><em class="icon ni ni-plus"></em><span>Create</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/apps/help-desk/overview') }}#" class="btn btn-primary d-none d-md-inline-flex" data-bs-toggle="modal" data-bs-target="#createTicketModal"><em class="icon ni ni-plus"></em><span>Create Ticket</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card nk-support-wrap overflow-hidden">
                                <div class="nk-support-header bg-lighter has-mask">
                                    <div class="mask mask-4">
                                    </div>
                                    <div class="nk-support-header-content text-center">
                                        <div class="h1 nk-support-title mb-3">
                                            How Can We Help You?
                                        </div>
                                        <p class="nk-support-desc">
                                            Welcome everyone! Search Articles, Public Tickets and watch Videos to find answers. Or, submit a Ticket if needed.
                                        </p>
                                        <div class="form-control-wrap">
                                            <div class="form-control-icon end px-0">
                                                <button type="button" class="btn"><em class="icon ni ni-search"></em></button>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Ask a question here...">
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-support-body">
                                    <div class="nk-block-head">
                                        <div class="nk-block-head-content">
                                            <h2 class="nk-block-title mb-2">
                                                Explore Topics
                                            </h2>
                                            <p class="text-light">
                                                Easily create Documentation, Knowledge-base, FAQ, Forum and more
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row g-gs">
                                        <div class="col-xl-6 col-xxl-4">
                                            <div class="card card-gutter-md">
                                                <div class="card-body">
                                                    <div class="media media-lg media-middle text-bg-primary-soft mb-4">
                                                        <em class="icon ni ni-ticket-fill"></em>
                                                    </div>
                                                    <h3 class="mb-3">
                                                        Popular Tickets
                                                    </h3>
                                                    <ul class="nk-list-option-list mb-3">
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How To Create A New Account?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Before submitting the ticket</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How to install on a local machine?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>What admin theme does?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Looking for theme customization?</span></a>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/help-desk/details') }}" class="btn-link small ms-1 fw-medium"><span>All Tickets</span> <em class="icon ni ni-arrow-right"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-4">
                                            <div class="card card-gutter-md">
                                                <div class="card-body">
                                                    <div class="media media-lg media-middle text-bg-success-soft mb-4">
                                                        <em class="icon ni ni-chat-circle-fill"></em>
                                                    </div>
                                                    <h3 class="mb-3">
                                                        Frequently Asked Question
                                                    </h3>
                                                    <ul class="nk-list-option-list mb-3">
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How to create a account?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Before submitting the ticket</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How to change slider transition time</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>What admin theme does?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How to edit the theme Footer?</span></a>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/help-desk/details') }}" class="btn-link small ms-1 fw-medium"><span>All FAQs</span> <em class="icon ni ni-arrow-right"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-4">
                                            <div class="card card-gutter-md">
                                                <div class="card-body">
                                                    <div class="media media-lg media-middle text-bg-info-soft mb-4">
                                                        <em class="icon ni ni-video-fill"></em>
                                                    </div>
                                                    <h3 class="mb-3">
                                                        Tutorials
                                                    </h3>
                                                    <ul class="nk-list-option-list mb-3">
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>What admin theme does?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Before submitting the ticket</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How Extended Licese works</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How long the license is valid?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Looking for theme customization?</span></a>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/help-desk/details') }}" class="btn-link small ms-1 fw-medium"><span>All Tutorials</span> <em class="icon ni ni-arrow-right"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-4">
                                            <div class="card card-gutter-md">
                                                <div class="card-body">
                                                    <div class="media media-lg media-middle text-bg-warning-soft mb-4">
                                                        <em class="icon ni ni-account-setting-fill"></em>
                                                    </div>
                                                    <h3 class="mb-3">
                                                        Account Settings
                                                    </h3>
                                                    <ul class="nk-list-option-list mb-3">
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Account Overview</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Purchase history?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Products and subscriptions</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Support requests?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How to change account passwords?</span></a>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/help-desk/details') }}" class="btn-link small ms-1 fw-medium"><span>All Settings</span> <em class="icon ni ni-arrow-right"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-4">
                                            <div class="card card-gutter-md">
                                                <div class="card-body">
                                                    <div class="media media-lg media-middle text-bg-pink-soft mb-4">
                                                        <em class="icon ni ni-opt-dot-fill"></em>
                                                    </div>
                                                    <h3 class="mb-3">
                                                        Copyright & Legal
                                                    </h3>
                                                    <ul class="nk-list-option-list mb-3">
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How Does License Activation Work?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How I receive when renewing my license?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>How much cost to renew my license?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Are the licenses valid for a lifetime?</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Authentication policy</span></a>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/help-desk/details') }}" class="btn-link small ms-1 fw-medium"><span>All Tutorials</span> <em class="icon ni ni-arrow-right"></em></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-xxl-4">
                                            <div class="card card-gutter-md">
                                                <div class="card-body">
                                                    <div class="media media-lg media-middle text-bg-purple-soft mb-4">
                                                        <em class="icon ni ni-file-fill"></em>
                                                    </div>
                                                    <h3 class="mb-3">
                                                        Documentations
                                                    </h3>
                                                    <ul class="nk-list-option-list mb-3">
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Free Admin Dashboard</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>React Admin Dashboard</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>Best VueJS Admin Template</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>HTML Admin Dashboard</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/help-desk/details') }}"><em class="icon ni ni-chevron-right text-primary"></em><span>RadneXt Front-end Template</span></a>
                                                        </li>
                                                    </ul>
                                                    <a href="{{ url('/admin/apps/help-desk/details') }}" class="btn-link small ms-1 fw-medium"><span>All Tutorials</span> <em class="icon ni ni-arrow-right"></em></a>
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
