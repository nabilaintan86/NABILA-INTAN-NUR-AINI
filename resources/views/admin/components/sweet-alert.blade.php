@extends('layouts.admin')
@section('title', 'Sweetalert - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Sweetalert2
                                </h1>
                                <p>
                                    Inside Nioboard, we integrated the <a href="https://sweetalert2.github.io/" target="_blank">SweetAlert2</a> which is beautiful, responsive, highly customizable JavaScript's popup boxes.
                                </p>
                                <p>
                                    You can see full uses documentation details from <a href="https://sweetalert2.github.io/" target="_blank">here</a>.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="gap g-3 d-flex flex-wrap">
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-primary eg-swal-default">Default</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-success eg-swal-success">Success</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-info eg-swal-info">Info</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-warning eg-swal-warning">Warning</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-danger eg-swal-error">Error</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-light eg-swal-question">Question</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Advanced Example
                                    </h3>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="gap g-3 d-flex flex-wrap">
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-primary eg-swal-positioned">Positioned dialog</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-primary eg-swal-confirm-button">Confirm button</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-primary eg-swal-auto-close-timer">Auto close timer</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/admin/components/sweet-alert') }}#" class="btn btn-primary eg-swal-ajax-request">Ajax request</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="{{ asset('admin/assets/js/sweetalert/sweetalert-example.js') }}"></script>
    @endpush
