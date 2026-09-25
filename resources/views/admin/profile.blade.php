@extends('layouts.admin')
@section('title', 'Users Profile - RadneXt Responsive Admin Dashboard Template')
    @section('content')
<div class="nk-block-head">
    <div class="nk-block-head">
        <div class="nk-block-head-between flex-wrap gap g-2 align-items-start">
            <div class="nk-block-head-content">
                <div class="d-flex flex-column flex-md-row align-items-md-center">
                    <div class="media media-huge media-circle">
                        <img src="{{ asset('admin-assets/images/avatar/a.jpg') }}" class="img-thumbnail" alt="">
                    </div>
                    <div class="mt-3 mt-md-0 ms-md-3">
                        <h3 class="title mb-1">
                            Wesley Burland
                        </h3>
                        <span class="small">Owner & Founder</span>
                        <ul class="nk-list-option pt-1">
                            <li>
                                <em class="icon ni ni-map-pin"></em><span class="small">California, United States</span>
                            </li>
                            <li>
                                <em class="icon ni ni-building"></em><span class="small">Softnio</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="nk-block-head-content">
                <div class="d-flex gap g-3">
                    <div class="gap-col">
                        <div class="box-dotted py-2">
                            <div class="d-flex align-items-center">
                                <div class="h4 mb-0">
                                    44.3K
                                </div>
                                <span class="change up ms-1 small"><em class="icon ni ni-arrow-down"></em></span>
                            </div>
                            <div class="smaller">
                                Followers
                            </div>
                        </div>
                    </div>
                    <div class="gap-col">
                        <div class="box-dotted py-2">
                            <div class="d-flex align-items-center">
                                <div class="h4 mb-0">
                                    4.5k
                                </div>
                                <span class="change up ms-1 small"><em class="icon ni ni-arrow-up"></em></span>
                            </div>
                            <div class="smaller">
                                Following
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nk-block-head-between gap g-2">
        <div class="gap-col">
            <ul class="nav nav-pills nav-pills-border gap g-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-1" type="button"> Overview </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" type="button"> Project </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" type="button"> Documents </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" type="button"> Disabled </button>
                </li>
            </ul>
        </div>
        <div class="gap-col">
            <ul class="d-flex gap g-2">
                <li class="d-none d-md-block">
                    <a href="{{ url('/admin/user-manage/user-edit') }}" class="btn btn-soft btn-primary"><em class="icon ni ni-edit"></em><span>Edit Profile</span></a>
                </li>
                <li class="d-md-none">
                    <a href="{{ url('/admin/user-manage/user-edit') }}" class="btn btn-soft btn-primary btn-icon"><em class="icon ni ni-edit"></em></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="nk-block">
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane show active" id="tab-1" tabindex="0">
            <div class="card card-gutter-md">
                <div class="card-row card-row-lg col-sep col-sep-lg">
                    <div class="card-aside">
                        <div class="card-body">
                            <div class="bio-block">
                                <h4 class="bio-block-title">
                                    Details
                                </h4>
                                <ul class="list-group list-group-borderless small">
                                    <li class="list-group-item">
                                        <span class="title fw-medium w-40 d-inline-block">Account ID:</span><span class="text">ID-45453423</span>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="title fw-medium w-40 d-inline-block">Full Name:</span><span class="text">Wesley Burland</span>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="title fw-medium w-40 d-inline-block">Email:</span><span class="text">wesley@gmail.com</span>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="title fw-medium w-40 d-inline-block">Address:</span><span class="text">California, United States</span>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="title fw-medium w-40 d-inline-block">Joining Date</span><span class="text">2 Dec 2021</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="bio-block">
                                <h4 class="bio-block-title mb-2">
                                    Skills
                                </h4>
                                <ul class="d-flex flex-wrap gap gx-1">
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">Photoshop</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">illustrator</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">HTML</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">CSS</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">Javascript</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">React</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">Vue</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">Angular</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="badge text-bg-secondary-soft">Python</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bio-block">
                                <h4 class="bio-block-title mb-3">
                                    Social
                                </h4>
                                <ul class="d-flex flex-wrap gap g-2">
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle media-middle text-bg-dark"><em class="icon ni ni-github-circle"></em></a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle media-middle text-bg-danger"><em class="icon ni ni-dribbble"></em></a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle media-middle text-bg-info"><em class="icon ni ni-twitter"></em></a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle media-middle text-bg-pink"><em class="icon ni ni-linkedin"></em></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="bio-block">
                                <h4 class="bio-block-title mb-3">
                                    Suggestions
                                </h4>
                                <ul class="d-flex flex-column gap gy-2">
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle"><img src="{{ asset('admin-assets/images/avatar/a.jpg') }}" alt="user"></a>
                                            <div class="media-text">
                                                <div class="lead-text">
                                                    Kamran Ahmed
                                                </div>
                                                <span class="sub-text">Frontend Developer</span>
                                            </div>
                                            <div class="media-action media-action-end">
                                                <button class="btn btn-sm btn-icon btn-soft btn-secondary"><em class="icon ni ni-user-add"></em></button>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle"><img src="{{ asset('admin-assets/images/avatar/b.jpg') }}" alt="user"></a>
                                            <div class="media-text">
                                                <div class="lead-text">
                                                    Harriett Adkins
                                                </div>
                                                <span class="sub-text">UI/UX Designer</span>
                                            </div>
                                            <div class="media-action media-action-end">
                                                <button class="btn btn-sm btn-icon btn-soft btn-secondary"><em class="icon ni ni-user-add"></em></button>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle"><img src="{{ asset('admin-assets/images/avatar/c.jpg') }}" alt="user"></a>
                                            <div class="media-text">
                                                <div class="lead-text">
                                                    George Whalen
                                                </div>
                                                <span class="sub-text">Backend Developer</span>
                                            </div>
                                            <div class="media-action media-action-end">
                                                <button class="btn btn-sm btn-icon btn-soft btn-secondary"><em class="icon ni ni-user-add"></em></button>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media media-md media-circle"><img src="{{ asset('admin-assets/images/avatar/d.jpg') }}" alt="user"></a>
                                            <div class="media-text">
                                                <div class="lead-text">
                                                    Claudia Chandler
                                                </div>
                                                <span class="sub-text">Node.js Developer</span>
                                            </div>
                                            <div class="media-action media-action-end">
                                                <button class="btn btn-sm btn-icon btn-soft btn-secondary"><em class="icon ni ni-user-add"></em></button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="bio-block">
                                <h4 class="bio-block-title mb-3">
                                    Popular Posts
                                </h4>
                                <ul class="d-flex flex-wrap gap gy-3">
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media"><img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt=""></a>
                                            <div class="media-text">
                                                <a href="{{ url('/admin/profile') }}#" class="lead-text">How to get creative in your work</a><span class="sub-text">15 Dec 2021</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media"><img src="{{ asset('admin-assets/images/product/b.jpg') }}" alt=""></a>
                                            <div class="media-text">
                                                <a href="{{ url('/admin/profile') }}#" class="lead-text">Create FureStibe branding logo</a><span class="sub-text">13 Dec 2021</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media"><img src="{{ asset('admin-assets/images/product/c.jpg') }}" alt=""></a>
                                            <div class="media-text">
                                                <a href="{{ url('/admin/profile') }}#" class="lead-text">9 Degree Project Estimation</a><span class="sub-text">10 Dec 2021</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media"><img src="{{ asset('admin-assets/images/product/d.jpg') }}" alt=""></a>
                                            <div class="media-text">
                                                <a href="{{ url('/admin/profile') }}#" class="lead-text">Smartest Applications for Business</a><span class="sub-text">10 Dec 2021</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="media-group">
                                            <a href="{{ url('/admin/profile') }}#" class="media"><img src="{{ asset('admin-assets/images/product/e.jpg') }}" alt=""></a>
                                            <div class="media-text">
                                                <a href="{{ url('/admin/profile') }}#" class="lead-text">A well-written bio allows viewers</a><span class="sub-text">20 Dec 2021</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="pt-3">
                                    <a href="{{ url('/admin/profile') }}#" class="btn btn-sm btn-soft btn-primary">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content col-sep">
                        <div class="card-body">
                            <div class="bio-block">
                                <h4 class="bio-block-title">
                                    About Me
                                </h4>
                                <p>
                                    <strong>Hey, I'am Wesley Burland</strong> I code and design websites worldwide. Mauris varius tellus vitae tristique sagittis. Sed aliquet, est nec auctor aliquet, orci ex vestibulum ex, non pharetra lacus erat ac nulla.
                                </p>
                                <p>
                                    Sed vulputate, ligula eget mollis auctor, lectus elit feugiat urna, eget euismod turpis lectus sed ex. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc ut velit finibus, scelerisque sapien vitae, pharetra est. Nunc accumsan ligula vehicula scelerisque vulputate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Deleniti, dolore?
                                </p>
                                <div class="row g-gs">
                                    <div class="col-lg-6">
                                        <div class="small">
                                            Designation:
                                        </div>
                                        <h5 class="small">
                                            Node.js Developer
                                        </h5>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="small">
                                            Website:
                                        </div>
                                        <h5 class="small">
                                            www.softnio.com
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="bio-block">
                                <h4 class="bio-block-title">
                                    Recent Activity
                                </h4>
                                <ul class="nk-schedule mt-4">
                                    <li class="nk-schedule-item">
                                        <div class="nk-schedule-item-inner">
                                            <div class="nk-schedule-symbol active">
                                            </div>
                                            <div class="nk-schedule-content">
                                                <span class="smaller">2:12 PM</span>
                                                <div class="h6">
                                                    Added 3 New Images
                                                </div>
                                                <ul class="d-flex flex-wrap gap g-2 py-2">
                                                    <li>
                                                        <div class="media media-xxl">
                                                            <img src="{{ asset('admin-assets/images/product/a.jpg') }}" alt="" class="img-thumbnail">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="media media-xxl">
                                                            <img src="{{ asset('admin-assets/images/product/b.jpg') }}" alt="" class="img-thumbnail">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="media media-xxl">
                                                            <img src="{{ asset('admin-assets/images/product/c.jpg') }}" alt="" class="img-thumbnail">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nk-schedule-item">
                                        <div class="nk-schedule-item-inner">
                                            <div class="nk-schedule-symbol active">
                                            </div>
                                            <div class="nk-schedule-content">
                                                <span class="smaller">4:23 PM</span>
                                                <div class="h6">
                                                    Invitation for creative designs pattern
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nk-schedule-item">
                                        <div class="nk-schedule-item-inner">
                                            <div class="nk-schedule-symbol active">
                                            </div>
                                            <div class="nk-schedule-content nk-schedule-content-no-border">
                                                <span class="smaller">10:30 PM</span>
                                                <div class="h6">
                                                    Task report - uploaded weekly reports
                                                </div>
                                                <div class="list-group-dotted mt-3">
                                                    <div class="list-group-wrap">
                                                        <div class="p-3">
                                                            <div class="media-group">
                                                                <div class="media rounded-0">
                                                                    <img src="{{ asset('admin-assets/images/icon/file-type-pdf.svg') }}" alt="">
                                                                </div>
                                                                <div class="media-text ms-1">
                                                                    <a href="{{ url('/admin/profile') }}#" class="title">Modern Designs Pattern</a><span class="text smaller">1.6.mb</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="p-3">
                                                            <div class="media-group">
                                                                <div class="media rounded-0">
                                                                    <img src="{{ asset('admin-assets/images/icon/file-type-doc.svg') }}" alt="">
                                                                </div>
                                                                <div class="media-text ms-1">
                                                                    <a href="{{ url('/admin/profile') }}#" class="title">cPanel Upload Guidelines</a><span class="text smaller">18kb</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="p-3">
                                                            <div class="media-group">
                                                                <div class="media rounded-0">
                                                                    <img src="{{ asset('admin-assets/images/icon/file-type-code.svg') }}" alt="">
                                                                </div>
                                                                <div class="media-text ms-1">
                                                                    <a href="{{ url('/admin/profile') }}#" class="title">Weekly Finance Reports</a><span class="text smaller">10mb</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nk-schedule-item">
                                        <div class="nk-schedule-item-inner">
                                            <div class="nk-schedule-symbol active">
                                            </div>
                                            <div class="nk-schedule-content">
                                                <span class="smaller">3:23 PM</span>
                                                <div class="h6">
                                                    Assigned you to new database design project
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nk-schedule-item">
                                        <div class="nk-schedule-item-inner">
                                            <div class="nk-schedule-symbol active">
                                            </div>
                                            <div class="nk-schedule-content nk-schedule-content-no-border flex-grow-1">
                                                <span class="smaller">5:05 PM</span>
                                                <div class="h6">
                                                    You have received a new order
                                                </div>
                                                <div class="alert alert-info mt-2" role="alert">
                                                    <div class="d-flex">
                                                        <em class="icon icon-lg ni ni-file-code opacity-75"></em>
                                                        <div class="ms-2 d-flex flex-wrap flex-grow-1 justify-content-between">
                                                            <div>
                                                                <h6 class="alert-heading mb-0">
                                                                    Business Template - UI/UX design
                                                                </h6>
                                                                <span class="smaller">Shared information with your team to understand and contribute to your project.</span>
                                                            </div>
                                                            <div class="d-block mt-1">
                                                                <a href="{{ url('/admin/profile') }}#" class="btn btn-md btn-info"><em class="icon ni ni-download"></em><span>Download</span></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nk-schedule-item">
                                        <div class="nk-schedule-item-inner">
                                            <div class="nk-schedule-symbol active">
                                            </div>
                                            <div class="nk-schedule-content">
                                                <span class="smaller">2:45 PM</span>
                                                <div class="h6">
                                                    Project status updated successfully
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
