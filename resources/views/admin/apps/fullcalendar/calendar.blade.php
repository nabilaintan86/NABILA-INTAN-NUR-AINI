@extends('layouts.admin')
@section('title', 'Calendar - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head">
                            <div class="nk-block-head-between flex-wrap gap g-2">
                                <div class="nk-block-head-content">
                                    <h1 class="nk-block-title">
                                        Calendar
                                    </h1>
                                    <p>
                                        Calendar build with JavaScript <a href="https://fullcalendar.io/" target="_blank">FullCalendar</a> Plugin. For more info see the <a href="https://fullcalendar.io/" target="_blank">official site</a>.
                                    </p>
                                </div>
                                <div class="nk-block-head-content nk-block-head-tools">
                                    <ul class="d-flex gap g-3 justify-content-center">
                                        <li>
                                            <button type="button" class="btn btn-primary" id="addEvent" data-bs-toggle="modal" data-bs-target="#eventFormModal"><em class="icon ni ni-plus"></em><span>Add Event</span></button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="card">
                                <div class="card-body">
                                    <div class="js-calendar" id="fullCalendar">
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
        <script src="{{ asset('admin-assets/assets/js/fullcalendar/fullcalendar.js') }}"></script>
    @endpush
