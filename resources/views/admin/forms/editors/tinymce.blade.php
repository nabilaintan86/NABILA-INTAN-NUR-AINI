@extends('layouts.admin')
@section('title', 'Tinymce Editor - NioBoard Responsive Admin Dashboard Template')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/libs/editors/tinymce.css') }}">
    @endpush
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Tinymce Editor
                                </h1>
                                <p class="lead">
                                    Using the <a href="https://www.tiny.cloud/" target="_blank">Tinymce</a> plugin, you can simply make some awesome rich text editor.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Default settings Editor
                                    </h3>
                                    <p>
                                        Tinymce rich text editor with default options.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="js-tinymce" id="tinymceDefault">
                                        Hello tinymce
                                    </div>
                                    <div class="form-note mt-3">
                                        Use <code>.js-tinymce</code> class to any div to enable basic tinymce editor example.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        No Toolbar Editor
                                    </h3>
                                    <p>
                                        Tinymce rich text editor with only menubar.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="js-tinymce" id="tinymceNoToolbar" data-toolbar="false">
                                        Hello tinymce
                                    </div>
                                    <div class="form-note mt-3">
                                        Use <code>data-toolbar="false"</code> attribute to <code>.js-tinymce</code> to hide toolbar.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        No Menubar Editor
                                    </h3>
                                    <p>
                                        Tinymce rich text editor with only toolbar.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="js-tinymce" id="tinymceNoMenubar" data-menubar="false">
                                        Hello tinymce
                                    </div>
                                    <div class="form-note mt-3">
                                        Use <code>data-menubar="false"</code> attribute to <code>.js-tinymce</code> to hide menubar.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Inline Editor
                                    </h3>
                                    <p>
                                        Tinymce rich text editor with only toolbar.
                                    </p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="js-tinymce" id="tinymceInline" data-inline="true" data-menubar="false">
                                        <p>
                                            Here are some of our customer’s most common use cases for TinyMCE:
                                        </p>
                                        <p>
                                            And those use cases are just the start. TinyMCE is incredibly flexible, and with hundreds of APIs there’s likely a solution for your editor project. If you haven’t experienced Tiny Cloud, get started today. You’ll even get a free trial of our premium plugins – no credit card required!
                                        </p>
                                    </div>
                                    <div class="form-note mt-3">
                                        Use <code>data-inline="true"</code> attribute to <code>.js-tinymce</code> to enable inline editor mode.
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
        <script src="{{ asset('admin-assets/assets/js/libs/editors/tinymce.js') }}"></script>
        <script src="{{ asset('admin-assets/assets/js/editors/tinymce.js') }}"></script>
    @endpush
