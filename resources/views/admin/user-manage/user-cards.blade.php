@extends('layouts.admin')
@section('title', 'Users Cards - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        Users Cards
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/user-manage/user-cards') }}#">Home</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/user-manage/user-cards') }}#">User Manage</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Users
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="nk-block-head-content">
                                    <ul class="d-flex">
                                        <li>
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-md d-md-none btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><em class="icon ni ni-plus"></em><span>Add</span></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-primary d-none d-md-inline-flex" data-bs-toggle="modal" data-bs-target="#addUserModal"><em class="icon ni ni-plus"></em><span>Add User</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="row g-gs">
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Florenza Desporte</a>
                                                <div class="small">
                                                    Administrator
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        2574
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/b.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Anna Adame</a>
                                                <div class="small">
                                                    Subscriber
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        178
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle text-bg-info-soft">
                                                SB
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Sean Bean</a>
                                                <div class="small">
                                                    Support
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        258
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/c.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Florenza Desporte</a>
                                                <div class="small">
                                                    Admin
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        578
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle text-bg-danger-soft">
                                                TB
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Travus Bruntjen</a>
                                                <div class="small">
                                                    Administrator
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        2574
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle text-bg-info-soft">
                                                SM
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Stephen MacGilfoyle</a>
                                                <div class="small">
                                                    Subscriber
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        178
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/d.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Yoko Pottie</a>
                                                <div class="small">
                                                    Editor
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        258
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/e.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Florenza Desporte</a>
                                                <div class="small">
                                                    Admin
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        578
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/d.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Vladamir Koschek</a>
                                                <div class="small">
                                                    Administrator
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        2574
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Tyne Widmore</a>
                                                <div class="small">
                                                    Subscriber
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        178
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/e.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Sean Bean</a>
                                                <div class="small">
                                                    Support
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        258
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xl-4 col-xxl-3">
                                    <div class="card text-center h-100">
                                        <div class="card-body">
                                            <div class="media media-xxl media-middle media-circle">
                                                <img src="{{ asset('admin/images/avatar/f.jpg') }}" alt="user">
                                            </div>
                                            <div class="mt-1 mb-4">
                                                <a href="{{ url('/admin/user-manage/user-profile') }}" class="mb-1 h5">Stu Delamaine</a>
                                                <div class="small">
                                                    Author
                                                </div>
                                            </div>
                                            <div class="row g-gs justify-content-center">
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        578
                                                    </div>
                                                    <div class="small">
                                                        Followers
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="h5 mb-1">
                                                        78<span>K</span>
                                                    </div>
                                                    <div class="small">
                                                        Following
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown position-absolute top-0 end-0 p-3">
                                            <a href="{{ url('/admin/user-manage/user-cards') }}#" class="btn btn-sm btn-icon btn-zoom" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list link-list-hover-bg-primary link-list-md">
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-edit') }}"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/user-manage/user-profile') }}"><em class="icon ni ni-eye"></em><span>View Details</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-5">
                                <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                                    <nav>
                                        <ul class="pagination pagination-s1">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="{{ url('/admin/user-manage/user-cards') }}#"><em class="ni ni-chevron-left icon"></em></a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="{{ url('/admin/user-manage/user-cards') }}#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/user-manage/user-cards') }}#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/user-manage/user-cards') }}#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ url('/admin/user-manage/user-cards') }}#"><em class="ni ni-chevron-right icon"></em></a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <span class="smaller py-2">Showing 1 to 12 of 50 entries</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
