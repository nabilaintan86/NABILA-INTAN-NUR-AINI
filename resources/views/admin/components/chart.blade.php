@extends('layouts.admin')
@section('title', 'Chart Js - NioBoard Responsive Admin Dashboard Template')
    @section('content')
        <div class="nk-content">
            <div class="container">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-page-head wide-md">
                            <div class="nk-block-head-content">
                                <h1 class="nk-block-title">
                                    Chart Js
                                </h1>
                                <p>
                                    <a href="https://www.chartjs.org/" target="_blank">ChartJs</a> is a very simple and flexible JavaScript charting for designers & developers. You can visualize your data in different ways. We present some of examples for you.
                                </p>
                                <p>
                                    For more info see the <a href="https://www.chartjs.org/" target="_blank">official site</a>.
                                </p>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Line & Bar Chart
                                    </h3>
                                </div>
                            </div>
                            <div class="row g-gs">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="line" id="lineChart">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="bar" id="barChart">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Filled Line Chart
                                    </h3>
                                </div>
                            </div>
                            <div class="row g-gs">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>
                                                Rounded Chart
                                            </h6>
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="line" id="lineChartFilled">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>
                                                Straight Chart
                                            </h6>
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="line" id="lineChartStraight">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Bar Chart - Multiple
                                    </h3>
                                </div>
                            </div>
                            <div class="row g-gs">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>
                                                Multiple Bar
                                            </h6>
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="bar" id="barChartMultiple">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>
                                                Stacked Bar
                                            </h6>
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="bar" id="barChartStacked">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-block">
                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title">
                                        Pie & Donught Charts
                                    </h3>
                                </div>
                            </div>
                            <div class="row g-gs">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-center mb-3">
                                                Pie Chart
                                            </h6>
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="pie" id="pieChart">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-center mb-4">
                                                Doughnut Chart
                                            </h6>
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="doughnut" id="doughnutChart">
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="text-center mb-4">
                                                Polar Chart
                                            </h6>
                                            <div class="nk-chart-overview">
                                                <canvas data-nk-chart="polar" id="polarChart">
                                                </canvas>
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
        <script src="{{ asset('admin/assets/js/charts/chart-example.js') }}"></script>
    @endpush
