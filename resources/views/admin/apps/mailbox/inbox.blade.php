@extends('layouts.admin')
@section('title', 'MailBox | Inbox - NioBoard Responsive Admin Dashboard Template')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('admin/assets/css/libs/editors/quill.css') }}">
    @endpush
    @section('content')
        <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-ibx" data-ibx-collapse="xl" id="ibxWrap">
                        <div class="nk-ibx-aside" data-simplebar id="ibxAside" data-overlay="true" data-break="xl">
                            <div class="nk-ibx-aside-header">
                                <button class="btn btn-primary btn-pill"><span>Compose</span></button>
                            </div>
                            <ul class="nk-ibx-menu">
                                <li class="active">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-menu-item"><em class="icon ni ni-inbox"></em><span>Inbox</span><span class="nk-ibx-menu-badge">3</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-menu-item"><em class="icon ni ni-star"></em><span>Starred</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-menu-item"><em class="icon ni ni-clock"></em><span>Snoozed</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-menu-item"><em class="icon ni ni-send-alt"></em><span>Sent</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-menu-item"><em class="icon ni ni-file-check"></em><span>Drafts</span><span class="nk-ibx-menu-badge">1</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-menu-item"><em class="icon ni ni-caution"></em><span>Spam</span><span class="nk-ibx-menu-badge">6</span></a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-menu-item"><em class="icon ni ni-trash"></em><span>Trash</span></a>
                                </li>
                            </ul>
                            <div class="nk-aside-heading mt-auto">
                                <div class="gap-col">
                                    <h6 class="overline-title">
                                        Contact
                                    </h6>
                                </div>
                                <div class="gap-col">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-zoom btn-icon me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <div class="dropdown-content py-1">
                                                <ul class="link-list is-compact">
                                                    <li>
                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-plus"></em><span>Add Contact</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-users"></em><span>All Contact</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="nk-ibx-contacts">
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#">
                                    <div class="media-group">
                                        <div class="media media-circle media-middle media-md">
                                            <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                        </div>
                                        <div class="media-text">
                                            <span class="title">Timothy Smith</span><span class="text smaller">timothysmith@gmail.com</span>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#">
                                    <div class="media-group">
                                        <div class="media media-circle media-middle media-md">
                                            <img src="{{ asset('admin/images/avatar/b.jpg') }}" alt="">
                                        </div>
                                        <div class="media-text">
                                            <span class="title">Alexis Clarke</span><span class="text smaller">alexshatov@gmail.com</span>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#">
                                    <div class="media-group">
                                        <div class="media media-circle media-middle media-md text-bg-success">
                                            <span class="smaller">HS</span>
                                        </div>
                                        <div class="media-text">
                                            <span class="title">Herbert Stokes</span><span class="text smaller">herbert@gmail.com</span>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#">
                                    <div class="media-group">
                                        <div class="media media-circle media-middle media-md">
                                            <img src="{{ asset('admin/images/avatar/d.jpg') }}" alt="">
                                        </div>
                                        <div class="media-text">
                                            <span class="title">Nancy Martino</span><span class="text smaller">nancyshatov@gmail.com</span>
                                        </div>
                                    </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="nk-ibx-body">
                            <div class="nk-ibx-head">
                                <ul class="nk-ibx-head-tools">
                                    <li>
                                        <div class="media media-middle media-md">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="d-none d-md-block">
                                        <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-trash"></em></button>
                                    </li>
                                    <li>
                                        <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-redo"></em></button>
                                    </li>
                                    <li class="d-none d-md-block">
                                        <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-label"></em></button>
                                    </li>
                                    <li class="dropdown">
                                        <button class="btn btn-md btn-zoom btn-icon" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <div class="dropdown-content py-1">
                                                <ul class="link-list">
                                                    <li>
                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-forward-arrow"></em><span>Move To</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-archive"></em><span>Archive</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="ms-auto d-flex align-items-center">
                                    <div class="nk-ibx-head-search d-none d-md-flex">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="form-control-icon start text-light md">
                                                    <em class="icon ni ni-search"></em>
                                                </div>
                                                <input class="form-control form-control-md form-control-plaintext" type="text" placeholder="Search Emaill">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-head-pagination">
                                        <div class="nk-ibx-head-info d-none d-xxl-block">
                                            1–50 of 2,619
                                        </div>
                                        <ul class="nk-ibx-head-nav">
                                            <li>
                                                <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-chevron-left"></em></button>
                                            </li>
                                            <li>
                                                <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-chevron-right"></em></button>
                                            </li>
                                            <li class="dropdown d-md-none">
                                                <button class="btn btn-md btn-zoom btn-icon" data-bs-toggle="dropdown"><em class="icon ni ni-search"></em></button>
                                                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                                                    <div class="px-4 py-1">
                                                        <div class="form-group">
                                                            <div class="form-control-wrap">
                                                                <input class="form-control form-control-plaintext" type="text" placeholder="Search Emaill">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="nk-ibx-toggle">
                                                <button class="btn btn-md btn-zoom btn-icon toggle-ibx-aside" data-target="ibxAside" data-parent=""><em class="icon ni ni-menu-right"></em></button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-ibx-list" data-simplebar>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx01">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Leslie Alexander</span><span class="text smaller">leslie@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Hiya
                                                </div>
                                                <ul class="nk-ibx-item-tags">
                                                    <li>
                                                        <span class="badge text-bg-info-soft">Promotions</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            12:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx02">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/b.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Theresa Webb</span><span class="text smaller">theresa@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Build prototypes without code
                                                </div>
                                                <ul class="nk-ibx-item-tags">
                                                    <li>
                                                        <span class="badge text-bg-warning-soft">Updates</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            12:10 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx03">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/c.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Albert Flores</span><span class="text smaller">albert@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Web Support Dennis
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            12:01 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx04">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-middle media-md text-bg-info">
                                                <span class="smaller">MF</span>
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Mirko Fisuk</span><span class="text smaller">mirko@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Don't make this bad
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Elit aute irure tempor incididunt sint deserunt ut voluptate aute id deserunt nisi.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            11:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx05">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/d.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Ralph Edwards</span><span class="text smaller">ralph@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Welcome to startmail
                                                </div>
                                                <ul class="nk-ibx-item-tags">
                                                    <li>
                                                        <span class="badge text-bg-success-soft">Socials</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            11:15 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx06">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/e.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Annette Black</span><span class="text smaller">annette@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Your account with us
                                                </div>
                                                <ul class="nk-ibx-item-tags">
                                                    <li>
                                                        <span class="badge text-bg-danger-soft">New</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            11:08 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx07">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/f.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Olga Semklo</span><span class="text smaller">olga.s@cool.design</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Some notes
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Elit aute irure tempor cupidatat incididunt sint deserunt ut voluptate aute id deserunt nisi.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            11:00 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx08">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-middle media-md text-bg-danger">
                                                <span>TB</span>
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Travus Bruntjen</span><span class="text smaller">travus@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Explore jobs similar to your job alert
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx09">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/g.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Saunder Offner</span><span class="text smaller">saunder@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Share their thoughts on LinkedIn
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx11">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/h.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Vladamir Koschek</span><span class="text smaller">vladamir@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Daily proofing digest email 10th September 2022
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx12">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/i.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Stephen MacGilfoyle</span><span class="text smaller">stephen@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    New sign in to your Nioboad account
                                                </div>
                                                <ul class="nk-ibx-item-tags">
                                                    <li>
                                                        <span class="badge text-bg-primary-soft">Important</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx12">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/j.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Jane Montgomery</span><span class="text smaller">jane@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    The most awaited product sale of the year is finally here!
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx14">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/k.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Timothy Smith</span><span class="text smaller">timothysmith@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Build prototypes without code
                                                </div>
                                                <ul class="nk-ibx-item-tags">
                                                    <li>
                                                        <span class="badge text-bg-pink-soft">Project</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx15">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/l.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Raymond Atkins</span><span class="text smaller">raymond@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Build prototypes without code
                                                </div>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-item">
                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="nk-ibx-link toggle-ibx-view" data-target="ibxView"></a>
                                    <div class="nk-ibx-item-elem nk-ibx-item-check">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="ibx16">
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-star">
                                        <div class="asterisk">
                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-user">
                                        <div class="media-group">
                                            <div class="media media-circle media-md">
                                                <img src="{{ asset('admin/images/avatar/m.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title">Joshua Mcnair</span><span class="text smaller">joshua@gmail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-content">
                                        <div class="d-flex flex-column">
                                            <div class="nk-ibx-item-title">
                                                <div class="nk-ibx-item-subject">
                                                    Be a design visionary
                                                </div>
                                                <ul class="nk-ibx-item-tags">
                                                    <li>
                                                        <span class="badge text-bg-success-soft">Socials</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-ibx-item-message">
                                                Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-item-elem nk-ibx-item-meta">
                                        <div class="time">
                                            10:41 PM
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-ibx-view" id="ibxView">
                                <div class="nk-ibx-head">
                                    <ul class="nk-ibx-head-tools">
                                        <li>
                                            <button class="btn btn-md btn-zoom btn-icon toggle-ibx-view" data-target="ibxView"><em class="icon ni ni-arrow-left"></em></button>
                                        </li>
                                        <li class="d-none d-md-block">
                                            <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-trash"></em></button>
                                        </li>
                                        <li>
                                            <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-redo"></em></button>
                                        </li>
                                        <li class="d-none d-md-block">
                                            <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-label"></em></button>
                                        </li>
                                        <li class="dropdown">
                                            <button class="btn btn-md btn-zoom btn-icon" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <div class="dropdown-content py-1">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-forward-arrow"></em><span>Move To</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-archive"></em><span>Archive</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-trash"></em><span>Delete</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="ms-auto d-flex align-items-center">
                                        <div class="nk-ibx-head-search d-none d-md-flex">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <div class="form-control-icon start text-light md">
                                                        <em class="icon ni ni-search"></em>
                                                    </div>
                                                    <input class="form-control form-control-md form-control-plaintext" type="text" placeholder="Search Emaill">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-ibx-head-pagination">
                                            <div class="nk-ibx-head-info d-none d-xxl-block">
                                                1–50 of 2,619
                                            </div>
                                            <ul class="nk-ibx-head-nav">
                                                <li>
                                                    <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-chevron-left"></em></button>
                                                </li>
                                                <li>
                                                    <button class="btn btn-md btn-zoom btn-icon"><em class="icon ni ni-chevron-right"></em></button>
                                                </li>
                                                <li class="dropdown d-md-none">
                                                    <button class="btn btn-md btn-zoom btn-icon" data-bs-toggle="dropdown"><em class="icon ni ni-search"></em></button>
                                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                                                        <div class="px-4 py-1">
                                                            <div class="form-group">
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control form-control-plaintext" type="text" placeholder="Search Emaill">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="nk-ibx-toggle">
                                                    <button class="btn btn-md btn-zoom btn-icon toggle-ibx-aside" data-target="ibxAside" data-parent=""><em class="icon ni ni-menu-right"></em></button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-ibx-reply nk-reply" data-simplebar>
                                    <div class="nk-ibx-reply-head">
                                        <div class="d-flex gap g-2">
                                            <div class="d-flex align-items-center">
                                                <h4 class="title">
                                                    Build prototypes without code
                                                </h4>
                                            </div>
                                            <ul class="nk-ibx-tags">
                                                <li>
                                                    <span class="badge text-bg-primary-soft">Important</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <ul class="d-flex g-1 me-n1">
                                            <li class="d-none d-sm-block">
                                                <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="btn btn-icon btn-zoom btn-md" title="Print"><em class="icon ni ni-printer"></em></a>
                                            </li>
                                            <li>
                                                <div class="asterisk">
                                                    <a class="btn btn-zoom btn-icon btn-md" href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="nk-ibx-reply-group">
                                        <div class="nk-ibx-reply-item nk-reply-item">
                                            <div class="nk-reply-header nk-ibx-reply-header toggle-reply">
                                                <div class="media-group nk-reply-desc">
                                                    <div class="media media-middle media-circle media-md">
                                                        <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                                    </div>
                                                    <div class="media-text nk-reply-info">
                                                        <div class="nk-reply-author lead-text">
                                                            Leslie Alexander <span class="date d-sm-none">14 Jan, 2020</span>
                                                        </div>
                                                        <div class="dropdown nk-reply-msg-info">
                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="dropdown-toggle sub-text dropdown-indicator" data-bs-toggle="dropdown">to Mildred</a>
                                                            <div class="dropdown-menu dropdown-menu-md">
                                                                <ul class="nk-reply-msg-meta">
                                                                    <li>
                                                                        <span class="label">from:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">info@softnio.com</a></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="label">to:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">team@softnio.com</a></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="label">bcc:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">team@softnio.com</a></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="label">mailed-by:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">softnio.com</a></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="nk-reply-msg-excerpt">
                                                            I am facing problem as i can not select currency on buy order page. Can you guys let me know what i am doing doing wrong? Please check attached files.
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nk-reply-tools g-1">
                                                    <li class="attach-msg d-none d-sm-inline-block">
                                                        <em class="icon ni ni-clip-h"></em>
                                                    </li>
                                                    <li class="date-msg d-none d-sm-inline-block">
                                                        <span class="date">14 Jan, 2020</span>
                                                    </li>
                                                    <li class="replyto-msg">
                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="btn btn-zoom btn-icon btn-md" title="Reply"><em class="icon ni ni-curve-up-left"></em></a>
                                                    </li>
                                                    <li class="more-actions">
                                                        <div class="dropdown">
                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="btn btn-zoom btn-icon btn-md" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply to</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-forward-arrow-fill"></em><span>Forward</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete this</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-report-fill"></em><span>Report Spam</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-reply-body nk-ibx-reply-body">
                                                <div class="nk-reply-entry entry wide-md">
                                                    <p>
                                                        Hello team,
                                                    </p>
                                                    <p>
                                                        I am facing problem as i can not select currency on buy order page. Can you guys let me know what i am doing doing wrong? Please check attached files.
                                                    </p>
                                                    <p>
                                                        Thank you
                                                        <br>
                                                        Leslie
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-ibx-reply-item nk-reply-item">
                                            <div class="nk-reply-header nk-ibx-reply-header toggle-reply">
                                                <div class="media-group nk-reply-desc">
                                                    <div class="media media-middle media-circle media-md text-bg-info">
                                                        <span class="smaller">RE</span>
                                                    </div>
                                                    <div class="media-text nk-reply-info">
                                                        <div class="nk-reply-author lead-text">
                                                            Ralph Edwards <span class="date d-sm-none">14 Jan, 2020</span>
                                                        </div>
                                                        <div class="dropdown nk-reply-msg-info">
                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="dropdown-toggle sub-text dropdown-indicator" data-bs-toggle="dropdown">to Mildred</a>
                                                            <div class="dropdown-menu dropdown-menu-md">
                                                                <ul class="nk-reply-msg-meta">
                                                                    <li>
                                                                        <span class="label">from:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">info@softnio.com</a></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="label">to:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">team@softnio.com</a></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="label">bcc:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">team@softnio.com</a></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="label">mailed-by:</span> <span class="info"><a href="{{ url('/admin/apps/mailbox/inbox') }}#">softnio.com</a></span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="nk-reply-msg-excerpt">
                                                            I am facing problem as i can not select currency on buy order page. Can you guys let me know what i am doing doing wrong? Please check attached files.
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nk-reply-tools g-1">
                                                    <li class="attach-msg d-none d-sm-inline-block">
                                                        <em class="icon ni ni-clip-h"></em>
                                                    </li>
                                                    <li class="date-msg d-none d-sm-inline-block">
                                                        <span class="date">14 Jan, 2020</span>
                                                    </li>
                                                    <li class="replyto-msg">
                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="btn btn-zoom btn-icon btn-md" title="Reply"><em class="icon ni ni-curve-up-left"></em></a>
                                                    </li>
                                                    <li class="more-actions">
                                                        <div class="dropdown">
                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="btn btn-zoom btn-icon btn-md" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list">
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply to</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-forward-arrow-fill"></em><span>Forward</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete this</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-report-fill"></em><span>Report Spam</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="nk-reply-body nk-ibx-reply-body">
                                                <div class="nk-reply-entry entry wide-md">
                                                    <p>
                                                        Hello,
                                                    </p>
                                                    <p>
                                                        Can you guys let me know what i am doing doing wrong? Please check attached files.
                                                    </p>
                                                    <p>
                                                        Thank you
                                                        <br>
                                                        Ralph Edwards
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ibx-reply-form nk-reply-form">
                                        <div class="nk-reply-form-header">
                                            <div class="form-group form-inline align-items-center flex-nowrap w-100">
                                                <label class="label me-3 fs-6">To</label>
                                                <div class="form-control-wrap w-100">
                                                    <input type="text" value="info@softnio.com" class="form-control form-control-plaintext js-tags" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-reply-form-editor">
                                            <div class="js-quill" data-toolbar="minimal" data-placeholder="Write some awesome text">
                                            </div>
                                        </div>
                                        <div class="nk-reply-form-tools">
                                            <ul class="nk-reply-form-actions g-1">
                                                <li class="me-2">
                                                    <button class="btn btn-primary rounded-pill" type="submit">Send</button>
                                                </li>
                                                <li>
                                                    <div class="dropdown">
                                                        <a class="btn btn-icon btn-sm" data-bs-toggle="dropdown" href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-hash" data-bs-toggle="tooltip" data-bs-placement="top" title="Template"></em></a>
                                                        <div class="dropdown-menu">
                                                            <div class="dropdown-content py-1">
                                                                <ul class="link-list is-compact">
                                                                    <li>
                                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><span>Thank you message</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><span>Your issues solved</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><span>Thank you message</span></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="dropdown-content py-1 border-top border-light">
                                                                <ul class="link-list is-compact">
                                                                    <li>
                                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-file-plus"></em><span>Save as Template</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-notes-alt"></em><span>Manage Template</span></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <a class="btn btn-icon btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Upload Attachment" href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-clip-v"></em></a>
                                                </li>
                                                <li>
                                                    <a class="btn btn-icon btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Upload Images" href="{{ url('/admin/apps/mailbox/inbox') }}#"><em class="icon ni ni-img"></em></a>
                                                </li>
                                            </ul>
                                            <ul class="nk-reply-form-actions g-1">
                                                <li>
                                                    <a href="{{ url('/admin/apps/mailbox/inbox') }}#" class="btn-zoom btn btn-icon me-n2"><em class="icon ni ni-trash"></em></a>
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
    @endsection
    @push('scripts')
        <script src="{{ asset('admin/assets/js/libs/editors/quill.js') }}"></script>
        <script src="{{ asset('admin/assets/js/editors/quill.js') }}"></script>
        <script src="{{ asset('admin/assets/js/apps/inbox.js') }}"></script>
    @endpush
