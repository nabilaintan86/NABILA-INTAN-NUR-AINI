@extends('layouts.admin')
@section('title', 'Todo list - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h2 class="nk-block-title">
                                        To Do List
                                    </h1>
                                    <nav>
                                        <ol class="breadcrumb breadcrumb-arrow mb-0">
                                            <li class="breadcrumb-item">
                                                <a href="{{ url('/admin/apps/todo/todo') }}#">Todo</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                Todo List
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="nk-todo" data-todo-collapse="xl" id="todoWrap">
                                    <div class="nk-todo-aside" data-simplebar id="todoAside" data-overlay="true" data-break="xl">
                                        <div class="nk-todo-aside-header">
                                            <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addTasksModal"><em class="icon ni ni-plus"></em><span>Add Task</span></button>
                                        </div>
                                        <ul class="nk-todo-menu pb-3">
                                            <li class="active">
                                                <a href="{{ url('/admin/apps/todo/todo') }}#" class="nk-todo-menu-item"><em class="icon ni ni-inbox"></em><span>My Task</span><span class="nk-todo-menu-badge">3</span></a>
                                            </li>
                                            <li>
                                                <a href="{{ url('/admin/apps/todo/todo') }}#" class="nk-todo-menu-item"><em class="icon ni ni-star"></em><span>Important</span></a>
                                            </li>
                                            <li>
                                                <a href="{{ url('/admin/apps/todo/todo') }}#" class="nk-todo-menu-item"><em class="icon ni ni-check-circle-cut"></em><span>Completed</span><span class="nk-todo-menu-badge">1</span></a>
                                            </li>
                                            <li>
                                                <a href="{{ url('/admin/apps/todo/todo') }}#" class="nk-todo-menu-item"><em class="icon ni ni-trash"></em><span>Trash</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="nk-todo-body">
                                        <div class="nk-todo-head">
                                            <div class="nk-todo-head-search">
                                                <div class="form-group">
                                                    <div class="form-control-wrap">
                                                        <div class="form-control-icon start text-light md ps-0">
                                                            <em class="icon ni ni-search"></em>
                                                        </div>
                                                        <input class="form-control form-control-md form-control-plaintext" type="text" placeholder="Search Task">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-head-pagination ms-auto">
                                                <ul class="nk-todo-head-nav">
                                                    <li class="nk-todo-toggle">
                                                        <button class="btn btn-md btn-zoom btn-icon toggle-todo-aside" data-target="todoAside" data-parent=""><em class="icon ni ni-menu-right"></em></button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="nk-todo-list" data-simplebar>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo01">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Weekly Bigbazar Shopping
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo02">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Meeting with Shaun Park at 4:50pm
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo03">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Pay the electricity bills
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo04">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Make dinner reservation
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo05">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Fix issues in new project
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo06">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Conference call with Marketing Manager
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo07">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Meeting with photographer
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo08">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Birthday wish to best friend
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo09">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Make Wallpapers
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo10">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Fix mimetype declaration
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-todo-item">
                                                <div class="nk-todo-item-elem nk-todo-item-check">
                                                    <div class="form-check form-check-sm">
                                                        <input class="form-check-input todo-check-toggle" type="checkbox" value="" id="todo11">
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-star">
                                                    <div class="asterisk">
                                                        <a href="{{ url('/admin/apps/todo/todo') }}#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-content">
                                                    <div class="nk-todo-item-subject">
                                                        Make a video introducing
                                                    </div>
                                                </div>
                                                <div class="nk-todo-item-elem nk-todo-item-action">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="gap-col">
                                                            <a href="{{ url('/admin/apps/todo/todo') }}#" class="btn btn-sm btn-icon btn-zoom"><em class="icon ni ni-trash"></em></a>
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
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin-assets/assets/js/data-tables/data-tables.js') }}"></script>
    @endpush
