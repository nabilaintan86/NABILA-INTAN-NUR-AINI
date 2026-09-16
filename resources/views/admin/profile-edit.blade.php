@extends('layouts.admin')
@section('title', 'Users Edit - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
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
                                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-1" type="button"> Account </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-2" type="button"> Security </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-3" type="button"> Notifications </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-4" type="button"> Connections </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="gap-col">
                                    <ul class="d-flex gap g-2">
                                        <li class="d-none d-md-block">
                                            <a href="{{ url('/admin/user-manage/user-profile') }}" class="btn btn-soft btn-primary"><em class="icon ni ni-user"></em><span>View Profile</span></a>
                                        </li>
                                        <li class="d-md-none">
                                            <a href="{{ url('/admin/user-manage/user-profile') }}" class="btn btn-soft btn-primary btn-icon"><em class="icon ni ni-user"></em></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane show active" id="tab-1" tabindex="0">
                                    <div class="card card-gutter-md">
                                        <div class="card-body">
                                            <div class="bio-block">
                                                <h4 class="bio-block-title mb-4">
                                                    Edit Profile
                                                </h4>
                                                <form action="profile-edit.html#">
                                                    <div class="row g-3">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="firstname" class="form-label">First Name</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="firstname" placeholder="First name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="lastname" class="form-label">Last Name</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="lastname" placeholder="Last name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="username" class="form-label">Username</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="username" placeholder="Username">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="email" class="form-label">Email address</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="email" placeholder="Email address">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for="company" class="form-label">Company</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="company" placeholder="Company name">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="address" class="form-label">Address</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="address" placeholder="e.g. California, United States">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label for="city" class="form-label">City</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="city" placeholder="City">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label for="country" class="form-label">Country</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="js-select" id="country" data-search="true" data-sort="false">
                                                                        <option value="">
                                                                            Select a country
                                                                        </option>
                                                                        <option value="1">
                                                                            Germany
                                                                        </option>
                                                                        <option value="2">
                                                                            Canada
                                                                        </option>
                                                                        <option value="3">
                                                                            Usa
                                                                        </option>
                                                                        <option value="4">
                                                                            Aus
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label for="postalcode" class="form-label">Postal Code</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" id="postalcode" placeholder="Zip code">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label for="aboutme" class="form-label">About Me</label>
                                                                <div class="form-control-wrap">
                                                                    <textarea class="form-control" id="aboutme" rows="3">On the other hand, we denounce with righteous indignation</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <button class="btn btn-primary" type="submit">Update Profile</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-2" tabindex="0">
                                    <div class="card col-sep card-gutter-md">
                                        <div class="card-body">
                                            <div class="bio-block">
                                                <h4 class="bio-block-title mb-4">
                                                    Change Password
                                                </h4>
                                                <form action="profile-edit.html#">
                                                    <div class="row g-3">
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label for="oldPassword" class="form-label">Old Password</label>
                                                                <div class="form-control-wrap">
                                                                    <a href="https://html.radnext.themenio.com/oldPassword" class="form-control-icon end password-toggle" title="Toggle show/hide password"><em class="on icon ni ni-eye-off"></em><em class="off icon ni ni-eye"></em></a><input type="password" class="form-control" id="oldPassword" placeholder="Old password">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label for="newPassword" class="form-label">New Password</label>
                                                                <div class="form-control-wrap">
                                                                    <a href="https://html.radnext.themenio.com/newPassword" class="form-control-icon end password-toggle" title="Toggle show/hide password"><em class="on icon ni ni-eye-off"></em><em class="off icon ni ni-eye"></em></a><input type="password" class="form-control" id="newPassword" placeholder="New password">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group">
                                                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                                                <div class="form-control-wrap">
                                                                    <a href="https://html.radnext.themenio.com/confirmPassword" class="form-control-icon end password-toggle" title="Toggle show/hide password"><em class="on icon ni ni-eye-off"></em><em class="off icon ni ni-eye"></em></a><input type="password" class="form-control" id="confirmPassword" placeholder="Confirm New Password">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="d-flex flex-wrap align-items-center gap g-3">
                                                                <div class="gap-col">
                                                                    <button class="btn btn-primary" type="submit">Change Password</button>
                                                                </div>
                                                                <div class="gap-col">
                                                                    <a href="{{ url('/admin/auths/auth-reset') }}" class="text-light small" target="_blank">Forgot Password?</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="bio-block">
                                                <div class="d-flex flex-wrap align-items-center justify-content-between pb-4">
                                                    <h4 class="bio-block-title">
                                                        Login History
                                                    </h4>
                                                    <a href="{{ url('/admin/profile-edit') }}#" class="text-light small">All Logout</a>
                                                </div>
                                                <ul class="gap gy-3">
                                                    <li>
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle text-bg-secondary-soft">
                                                                <em class="icon ni ni-mobile"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <div class="lead-text">
                                                                    iPhone 12 Pro
                                                                </div>
                                                                <span class="sub-text">Los Angeles, United States - April 18 at 2:47 PM</span>
                                                            </div>
                                                            <div class="media-action media-action-end">
                                                                <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-danger-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><em class="ni ni-cross"></em></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle text-bg-secondary-soft">
                                                                <em class="icon ni ni-mobile"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <div class="lead-text">
                                                                    Apple iPad Pro
                                                                </div>
                                                                <span class="sub-text">Washington, United States - November 06 at 10:43AM</span>
                                                            </div>
                                                            <div class="media-action media-action-end">
                                                                <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-danger-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><em class="ni ni-cross"></em></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle text-bg-secondary-soft">
                                                                <em class="icon ni ni-mobile"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <div class="lead-text">
                                                                    Galaxy S21 Ultra 5G
                                                                </div>
                                                                <span class="sub-text">Washington, United States - November 08 at 10:43AM</span>
                                                            </div>
                                                            <div class="media-action media-action-end">
                                                                <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-danger-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><em class="ni ni-cross"></em></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="media-group">
                                                            <div class="media media-md flex-shrink-0 media-middle text-bg-secondary-soft">
                                                                <em class="icon ni ni-laptop"></em>
                                                            </div>
                                                            <div class="media-text">
                                                                <div class="lead-text">
                                                                    Dell Inspiron 14
                                                                </div>
                                                                <span class="sub-text">Washington, United States - November 08 at 10:43AM</span>
                                                            </div>
                                                            <div class="media-action media-action-end">
                                                                <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-danger-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><em class="ni ni-cross"></em></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="bio-block">
                                                <h4 class="bio-block-title mb-4">
                                                    Delete Account
                                                </h4>
                                                <div class="alert alert-warning mt-2" role="alert">
                                                    <div class="d-flex">
                                                        <em class="icon icon-lg ni ni-question-alt opacity-75"></em>
                                                        <div class="ms-2">
                                                            <h6 class="alert-heading mb-1">
                                                                You Are Deleting Your Account!
                                                            </h6>
                                                            <span class="smaller d-block">For extra security, this requires you to confirm your email or phone number when you reset yousignr password.</span><a class="smaller" href="{{ url('/admin/profile-edit') }}#" target="_blank">Learn more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check form-check-sm">
                                                    <input class="form-check-input" type="checkbox" value="" id="confirmAccountDeteting"><label class="form-check-label" for="confirmAccountDeteting"> I confirm my account deleting! </label>
                                                </div>
                                                <div class="mt-3">
                                                    <a href="{{ url('/admin/profile-edit') }}#" class="btn btn-danger delete-account-button">Delete Account</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-3" tabindex="0">
                                    <div class="card card-gutter-md">
                                        <div class="card-body">
                                            <div class="bio-block">
                                                <h4 class="bio-block-title">
                                                    Notifications
                                                </h4>
                                                <p class="mb-4">
                                                    You will get only notification what have enabled.
                                                </p>
                                                <ul class="list-group list-group-borderless">
                                                    <li class="list-group-item d-flex align-items-md-center">
                                                        <div class="flex-grow-1">
                                                            <label for="directMessage" class="form-check-label ps-0 fw-medium">Direct messages</label>
                                                            <p class="text-light">
                                                                Messages from people you follow
                                                            </p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="directMessage" checked>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-md-center">
                                                        <div class="flex-grow-1">
                                                            <label class="form-check-label ps-0 fw-medium" for="desktopNotification"> Show desktop notifications </label>
                                                            <p class="text-light">
                                                                Choose the option you want as your default setting. Block a site: Next to "Not allowed to send notifications," click Add.
                                                            </p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="desktopNotification" checked>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-md-center">
                                                        <div class="flex-grow-1">
                                                            <label class="form-check-label ps-0 fw-medium" for="emailNotification"> Show email notifications </label>
                                                            <p class="text-light">
                                                                Under Settings, choose Notifications. Under Select an account, choose the account to enable notifications for.
                                                            </p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="emailNotification" checked>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-md-center">
                                                        <div class="flex-grow-1">
                                                            <label class="form-check-label ps-0 fw-medium" for="chatNotification"> Show chat notifications </label>
                                                            <p class="text-light">
                                                                To prevent duplicate mobile notifications from the Gmail and Chat apps, in settings, turn off Chat notifications.
                                                            </p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="chatNotification">
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-md-center">
                                                        <div class="flex-grow-1">
                                                            <label class="form-check-label ps-0 fw-medium" for="purchaseNotification"> Show purchase notifications </label>
                                                            <p class="text-light">
                                                                Get real-time purchase alerts to protect yourself from fraudulent charges.
                                                            </p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="purchaseNotification">
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-4" tabindex="0">
                                    <div class="card col-sep card-gutter-md">
                                        <div class="card-body">
                                            <div class="bio-block">
                                                <h4 class="bio-block-title">
                                                    Connected accounts
                                                </h4>
                                                <p class="mb-4">
                                                    Display content from your connected accounts on your site.
                                                </p>
                                                <ul class="list-group list-group-borderless">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/d.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Google</span><span class="text smaller">Calendar and contacts</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="checkboxGoogle" checked>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/f.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Slack</span><span class="text smaller">Communication</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="checkboxGoogle">
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/g.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Github</span><span class="text smaller">Manage your Git repositories</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="checkboxGoogle" checked>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/h.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Mailchimp</span><span class="text smaller">Email marketing service</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="checkboxGoogle" checked>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/i.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Asana</span><span class="text smaller">Communication</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="checkboxGoogle" checked>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="bio-block">
                                                <h4 class="bio-block-title">
                                                    Social accounts
                                                </h4>
                                                <p class="mb-4">
                                                    Display content from social accounts on your site
                                                </p>
                                                <ul class="list-group list-group-borderless">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/b.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Facebook</span><span class="text smaller text-success">Connected</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-danger-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Disconnect"><em class="ni ni-cross"></em></a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/e.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Instagram</span><span class="text smaller text-danger">Not Connected</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-secondary-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Connect"><em class="ni ni-link-alt"></em></a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/j.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Behance</span><span class="text smaller text-danger">Not Connected</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-secondary-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Connect"><em class="ni ni-link-alt"></em></a>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="media-group">
                                                            <div class="media media-middle flex-shrink-0 text-bg-secondary-soft">
                                                                <img src="{{ asset('admin-assets/images/icon/k.png') }}" alt="">
                                                            </div>
                                                            <div class="media-text">
                                                                <span class="title">Behance</span><span class="text smaller text-danger">Not Connected</span>
                                                            </div>
                                                        </div>
                                                        <a href="{{ url('/admin/profile-edit') }}#" class="media media-xs media-circle media-middle text-bg-secondary-soft" data-bs-toggle="tooltip" data-bs-placement="top" title="Connect"><em class="ni ni-link-alt"></em></a>
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
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin-assets/assets/js/sweetalert/user-edit-alert.js') }}"></script>
    @endpush
