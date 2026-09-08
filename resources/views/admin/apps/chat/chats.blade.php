@extends('layouts.admin')
@section('title', 'Messages | Chats - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content nk-content-stretch">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-chat profile-shown" data-chat-collapse="xxl" id="chatWrap">
                        <div class="nk-chat-aside">
                            <div class="nk-aside-heading pt-4">
                                <div class="gap-col">
                                    <h2 class="title">
                                        Chats
                                    </h2>
                                </div>
                                <div class="gap-col">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-zoom btn-icon me-n1" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <div class="dropdown-content py-1">
                                                <ul class="link-list is-compact">
                                                    <li>
                                                        <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-plus"></em><span>New Chat</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-user"></em><span>New Contact</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-chat-aside-scroll" data-simplebar>
                                <div class="nk-aside-content py-2">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <div class="form-control-icon end">
                                                <em class="icon ni ni-search"></em>
                                            </div>
                                            <input type="text" class="form-control form-control-md rounded-pill" id="searchChat" placeholder="Search">
                                        </div>
                                    </div>
                                </div>
                                <ul class="nk-chat-list">
                                    <li class="toggle-chat-item" data-target="chatBody">
                                        <div class="media-group">
                                            <div class="media media-circle media-middle">
                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title"><span>Jessica Drew</span> <span class="date">18:30</span></span><span class="text smaller"><span>Ok, see you later</span> <span class="badge bg-success rounded-pill">2</span></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="toggle-chat-item" data-target="chatBody">
                                        <div class="media-group">
                                            <div class="media media-circle media-middle">
                                                <img src="{{ asset('admin/images/avatar/b.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title"><span>David Moore</span> <span class="date">18:16</span></span><span class="text smaller"><span>You: i don't remember anything 😄 </span></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="toggle-chat-item" data-target="chatBody">
                                        <div class="media-group">
                                            <div class="media media-circle media-middle">
                                                <img src="{{ asset('admin/images/avatar/c.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title"><span>Greg James</span> <span class="date">18:02</span></span><span class="text smaller"><span>I got a job at SpaceX 🎉 🚀</span></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="toggle-chat-item" data-target="chatBody">
                                        <div class="media-group">
                                            <div class="media media-circle media-middle">
                                                <img src="{{ asset('admin/images/avatar/d.jpg') }}" alt="">
                                            </div>
                                            <div class="media-text">
                                                <span class="title"><span>Emily Dorson</span> <span class="date">17:42</span></span><span class="text smaller"><span>Table for four, 5PM. Be there.</span></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-chat-body" id="chatBody">
                            <div class="nk-chat-head">
                                <ul class="nk-chat-head-tools me-1 ms-n1 d-md-none">
                                    <li>
                                        <button class="btn btn-icon btn-zoom btn-sm toggle-close-chat" data-target="chatBody"><em class="icon ni ni-arrow-left"></em></button>
                                    </li>
                                </ul>
                                <div class="media-group">
                                    <div class="media media-circle media-middle">
                                        <img src="{{ asset('admin/images/avatar/c.jpg') }}" alt="">
                                    </div>
                                    <div class="media-text">
                                        <span class="title"><span>David Moore</span></span><span class="text smaller"><span>Active</span></span>
                                    </div>
                                </div>
                                <ul class="nk-chat-head-tools gap gx-md-2 me-n2">
                                    <li>
                                        <button class="btn btn-icon btn-zoom btn-md"><em class="icon ni ni-call"></em></button>
                                    </li>
                                    <li>
                                        <button class="btn btn-icon btn-zoom btn-md"><em class="icon ni ni-video"></em></button>
                                    </li>
                                    <li>
                                        <button class="btn btn-icon btn-zoom btn-md toggle-chat-profile active" data-target="chatProfile" data-parent="chatWrap"><em class="icon ni ni-info-fill"></em></button>
                                    </li>
                                </ul>
                            </div>
                            <div class="nk-chat-panel">
                                <div class="nk-message-wrap">
                                    <div class="nk-message is-you">
                                        <div class="nk-message-avatar">
                                            <div class="media media-circle media-middle media-sm">
                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                            </div>
                                        </div>
                                        <div class="nk-message-content">
                                            <ul class="nk-message-bubbles">
                                                <li>
                                                    <div class="nk-message-bubble has-media">
                                                        <ul class="nk-message-media">
                                                            <li>
                                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                                            </li>
                                                            <li>
                                                                <img src="{{ asset('admin/images/avatar/c.jpg') }}" alt="">
                                                            </li>
                                                        </ul>
                                                        <ul class="nk-message-meta">
                                                            <li>
                                                                15:42
                                                            </li>
                                                            <li>
                                                                Unread
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <ul class="nk-message-actions">
                                                        <li class="d-none d-sm-block">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-reply-fill"></em></button>
                                                        </li>
                                                        <li class="dropdown-center">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                                            <div class="dropdown-menu dropdown-menu-sm">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list is-compact">
                                                                        <li class="d-sm-none">
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-pen-alt-fill"></em><span>Edit</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="nk-message is-me">
                                        <div class="nk-message-content">
                                            <ul class="nk-message-bubbles">
                                                <li>
                                                    <div class="nk-message-bubble">
                                                        <div class="nk-message-text">
                                                            Hi, yes, David have found it, ask our concierge 👀
                                                        </div>
                                                        <ul class="nk-message-meta">
                                                            <li>
                                                                15:42
                                                            </li>
                                                            <li>
                                                                Seen
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <ul class="nk-message-actions">
                                                        <li class="d-none d-sm-block">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-reply-fill"></em></button>
                                                        </li>
                                                        <li class="dropdown-center">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                                            <div class="dropdown-menu dropdown-menu-sm">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list is-compact">
                                                                        <li class="d-sm-none">
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-pen-alt-fill"></em><span>Edit</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="nk-message is-you">
                                        <div class="nk-message-avatar">
                                            <div class="media media-circle media-middle media-sm">
                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                            </div>
                                        </div>
                                        <div class="nk-message-content">
                                            <ul class="nk-message-bubbles">
                                                <li>
                                                    <div class="nk-message-bubble">
                                                        <div class="nk-message-text">
                                                            Hello! Have you seen my backpack anywhere in office?
                                                        </div>
                                                        <ul class="nk-message-meta">
                                                            <li>
                                                                15:42
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <ul class="nk-message-actions">
                                                        <li class="d-none d-sm-block">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-reply-fill"></em></button>
                                                        </li>
                                                        <li class="dropdown-center">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                                            <div class="dropdown-menu dropdown-menu-sm">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list is-compact">
                                                                        <li class="d-sm-none">
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-pen-alt-fill"></em><span>Edit</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="nk-message is-me">
                                        <div class="nk-message-content">
                                            <ul class="nk-message-bubbles">
                                                <li>
                                                    <div class="nk-message-bubble">
                                                        <div class="nk-message-text">
                                                            While you win in love, you continue to win in other things.
                                                        </div>
                                                        <ul class="nk-message-meta">
                                                            <li>
                                                                15:42
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <ul class="nk-message-actions">
                                                        <li class="d-none d-sm-block">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-reply-fill"></em></button>
                                                        </li>
                                                        <li class="dropdown-center">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                                            <div class="dropdown-menu dropdown-menu-sm">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list is-compact">
                                                                        <li class="d-sm-none">
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-pen-alt-fill"></em><span>Edit</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="nk-message is-you">
                                        <div class="nk-message-avatar">
                                            <div class="media media-circle media-middle media-sm">
                                                <img src="{{ asset('admin/images/avatar/a.jpg') }}" alt="">
                                            </div>
                                        </div>
                                        <div class="nk-message-content">
                                            <ul class="nk-message-bubbles">
                                                <li>
                                                    <div class="nk-message-bubble">
                                                        <div class="nk-message-text">
                                                            Didn't I tell you not to put your phone on charge just because it's the weekend?
                                                        </div>
                                                        <ul class="nk-message-meta">
                                                            <li>
                                                                12:42
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <ul class="nk-message-actions">
                                                        <li class="d-none d-sm-block">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-reply-fill"></em></button>
                                                        </li>
                                                        <li class="dropdown-center">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                                            <div class="dropdown-menu dropdown-menu-sm">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list is-compact">
                                                                        <li class="d-sm-none">
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-pen-alt-fill"></em><span>Edit</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    <div class="nk-message-bubble">
                                                        <div class="nk-message-text">
                                                            next time you'll be awake at this hour why not now
                                                        </div>
                                                        <ul class="nk-message-meta">
                                                            <li>
                                                                12:48
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <ul class="nk-message-actions">
                                                        <li class="d-none d-sm-block">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-reply-fill"></em></button>
                                                        </li>
                                                        <li class="dropdown-center">
                                                            <button class="btn btn-icon btn-zoom btn-sm text-light" data-bs-toggle="dropdown"><em class="icon ni ni-more-v"></em></button>
                                                            <div class="dropdown-menu dropdown-menu-sm">
                                                                <div class="dropdown-content py-1">
                                                                    <ul class="link-list is-compact">
                                                                        <li class="d-sm-none">
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-reply-fill"></em><span>Reply</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-pen-alt-fill"></em><span>Edit</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-trash-fill"></em><span>Delete</span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-chat-form">
                                <div class="nk-chat-input">
                                    <ul class="nk-chat-input-btns">
                                        <li>
                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-mic"></em></button>
                                        </li>
                                        <li>
                                            <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-clip"></em></button>
                                        </li>
                                    </ul>
                                    <div class="nk-chat-input-field">
                                        <div class="form-control-wrap">
                                            <input type="text" placeholder="Start a new message" class="form-control form-control-md form-control-plaintext">
                                        </div>
                                    </div>
                                    <div class="nk-chat-input-send">
                                        <button class="btn btn-icon btn-zoom btn-sm text-light"><em class="icon ni ni-send"></em></button>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-chat-profile show-profile toggle-visiable" data-simplebar id="chatProfile" data-overlay="true" data-break="xxl" data-visiable="xxl">
                                <div class="nk-aside-content py-4 border-bottom border-light position-relative">
                                    <div class="media-group media-group-column text-center py-2">
                                        <div class="media media-big media-circle media-middle">
                                            <img src="{{ asset('admin/images/avatar/c.jpg') }}" alt="">
                                        </div>
                                        <div class="media-text">
                                            <h3 class="mb-1">
                                                David Moore
                                            </h3>
                                            <span class="text smaller"><span>Product Designer @uikings</span></span>
                                        </div>
                                    </div>
                                    <div class="dropdown position-absolute top-0 end-0 p-4">
                                        <button class="btn btn-sm btn-zoom btn-icon" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <div class="dropdown-content py-1">
                                                <ul class="link-list is-compact">
                                                    <li>
                                                        <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-bell-off"></em><span>Mute</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-user-cross"></em><span>Block</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-alert"></em><span>Report</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="nk-aside-content py-4">
                                    <div class="gap gy-3">
                                        <div class="gap-col">
                                            <h5 class="title">
                                                Options
                                            </h5>
                                            <ul class="link-list link-list-base-light is-compact">
                                                <li>
                                                    <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-edit"></em><span>Edit Nickname</span></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-search"></em><span>Search In Conversation</span></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-color-palette"></em><span>Change Color</span></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-bell-off"></em><span>Mute Notifications</span></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="gap-col">
                                            <h5 class="title">
                                                Files
                                            </h5>
                                            <ul class="link-list link-list-base-light is-compact">
                                                <li>
                                                    <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-folder-list"></em><span>Data_Component.zip</span></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-file-pdf"></em><span>Checkout_responsive.pdf</span></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/admin/apps/chat/chats') }}#"><em class="icon ni ni-img"></em><span>Commercial.jpeg</span></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="gap-col">
                                            <h5 class="title mb-3">
                                                Media
                                            </h5>
                                            <ul class="nk-chat-media-list gap g-2 list4col">
                                                <li>
                                                    <a class="nk-chat-media-item" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/a.jpg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a class="nk-chat-media-item" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/b.jpg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a class="nk-chat-media-item" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/c.jpg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a class="nk-chat-media-item" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/d.jpg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a class="nk-chat-media-item" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/e.jpg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a class="nk-chat-media-item" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/f.jpg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a class="nk-chat-media-item" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/g.jpg') }}" alt=""></a>
                                                </li>
                                                <li>
                                                    <a class="nk-chat-media-item is-video" href="{{ url('/admin/apps/chat/chats') }}#"><img src="{{ asset('admin/images/avatar/h.jpg') }}" alt=""><em class="icon ni ni-play-fill"></em></a>
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
        <script src="{{ asset('admin/assets/js/apps/chat.js') }}"></script>
    @endpush
