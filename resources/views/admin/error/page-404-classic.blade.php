@extends('layouts.error')
@section('title', 'Page not found! - RadneXt Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-wrap align-items-center justify-content-center">
            <div class="container">
                <div class="nk-block">
                    <div class="nk-block-content wide-sm text-center mx-auto">
                        <img src="{{ asset('admin-assets/images/error/b.png') }}" alt="" class="mb-5">
                        <h2 class="nk-error-title mb-2">
                            OOPS! Page not found!
                        </h2>
                        <p class="nk-error-text">
                            We are very sorry for inconvenience. It looks like you’re try to access a page that either has been deleted or never existed.
                        </p>
                        <a href="{{ url('/admin') }}" class="btn btn-primary mt-1"><em class="icon ni ni-arrow-left"></em><span>Back To Home</span></a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
