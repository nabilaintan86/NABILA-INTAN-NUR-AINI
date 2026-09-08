@extends('layouts.admin')
@section('title', 'Quill Editor - NioBoard Responsive Admin Dashboard Template')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('admin/assets/css/libs/editors/quill.css') }}">
    @endpush
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Quill Editor
                                </h1>
                                <p class="lead">
                                    Using the <a href="https://quilljs.com/" target="_blank">quilljs</a> plugin, you can simply make some awesome rich text editor.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Basic Editor Example
                                    </h3>
                                    <p>
                                        A basic demostration of quilljs rich text editor.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="js-quill">
                                        Hello Quiller
                                    </div>
                                    <div class="form-note mt-3">
                                        Use <code>.js-quill</code> class to any div to enable basic quill editor example.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Minimal Editor Example
                                    </h3>
                                    <p>
                                        A minimal demostration of quilljs rich text editor.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="js-quill" data-toolbar="minimal" data-placeholder="Write some awesome text">
                                    </div>
                                    <div class="form-note mt-3">
                                        Use <code>.js-quill</code> class to any div to enable basic quill editor example, and add <code>data-toolbar="minimal"</code> attribute for minimal quill editor example.
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
    @endpush
