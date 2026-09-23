@extends('layouts.admin')

@section('title', 'Laporan & Grafik')

@push('styles')
<style>
    .laporan-filters .btn {
        min-width: 100px;
    }
    .chart-card .card-body {
        padding: 1.25rem;
    }
    .chart-container {
        position: relative;
        width: 100%;
        height: 340px;
    }
</style>
@endpush

@section('content')
{{-- FILTER PERIODE --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <p class="text-soft small mb-0">Analisis kestabilan jaringan RADNET</p>
    </div>
    <div class="btn-group laporan-filters" role="group" id="periodFilter">
        <button type="button" class="btn btn-outline-primary active" data-period="24h">24 Jam</button>
        <button type="button" class="btn btn-outline-primary" data-period="7d">7 Hari</button>
        <button type="button" class="btn btn-outline-primary" data-period="30d">30 Hari</button>
    </div>
</div>

{{-- GRAFIK --}}
<div class="row g-gs">
    {{-- Network Availability --}}
    <div class="col-lg-6">
        <div class="card chart-card h-100">
            <div class="card-body">
                <h6 class="title mb-3">Network Availability</h6>
                <div class="chart-container">
                    <canvas id="availabilityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Average Response Time --}}
    <div class="col-lg-6">
        <div class="card chart-card h-100">
            <div class="card-body">
                <h6 class="title mb-3">Average Response Time</h6>
                <div class="chart-container">
                    <canvas id="responseChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── DATA DARI BACKEND (monitoring_logs via LaporanController) ── */
    var chartData = @json($chartData);

    /* fallback aman bila belum ada data */
    if (!chartData['24h']) {
        chartData = {
            '24h': { labels: [], availability: [], response: [] },
            '7d':  { labels: [], availability: [], response: [] },
            '30d': { labels: [], availability: [], response: [] }
        };
    }

    var fontColor    = '#8094ae';
    var gridColor    = 'rgba(134,148,174,.12)';
    var tooltipBg    = '#162447';
    var tooltipBorder = '#1e3054';

    /* ── Shared tooltip config ──────────────────────────────────── */
    function tooltipOpts(callback) {
        return {
            backgroundColor: tooltipBg,
            titleColor: '#fff',
            bodyColor: '#c5d0e6',
            borderColor: tooltipBorder,
            borderWidth: 1,
            padding: 10,
            cornerRadius: 6,
            displayColors: false,
            boxPadding: 4,
            callbacks: { label: callback }
        };
    }

    /* ── Network Availability ───────────────────────────────────── */
    var ctxAvail = document.getElementById('availabilityChart').getContext('2d');
    var availChart = new Chart(ctxAvail, {
        type: 'line',
        data: {
            labels: chartData['24h'].labels,
            datasets: [{
                label: 'Availability %',
                data: chartData['24h'].availability,
                borderColor: '#198754',
                backgroundColor: 'rgba(25,135,84,.08)',
                borderWidth: 2.5,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#198754',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: tooltipOpts(function (ctx) {
                    return 'Availability: ' + ctx.parsed.y + '%';
                })
            },
            scales: {
                x: {
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { maxRotation: 0, autoSkipPadding: 20 }
                },
                y: {
                    min: 90,
                    max: 100,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: {
                        stepSize: 2,
                        callback: function (v) { return v + '%'; }
                    }
                }
            }
        }
    });

    /* ── Average Response Time ──────────────────────────────────── */
    var ctxResp = document.getElementById('responseChart').getContext('2d');
    var respChart = new Chart(ctxResp, {
        type: 'line',
        data: {
            labels: chartData['24h'].labels,
            datasets: [{
                label: 'Response (ms)',
                data: chartData['24h'].response,
                borderColor: '#e8534f',
                backgroundColor: 'rgba(232,83,79,.08)',
                borderWidth: 2.5,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#e8534f',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: tooltipOpts(function (ctx) {
                    return 'Response: ' + ctx.parsed.y + ' ms';
                })
            },
            scales: {
                x: {
                    grid: { color: gridColor, drawBorder: false },
                    ticks: { maxRotation: 0, autoSkipPadding: 20 }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: {
                        callback: function (v) { return v + ' ms'; }
                    }
                }
            }
        }
    });

    /* ── Period filter ──────────────────────────────────────────── */
    var btns = document.querySelectorAll('#periodFilter .btn');
    btns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            btns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');

            var period = btn.getAttribute('data-period');
            var d = chartData[period];

            availChart.data.labels = d.labels;
            availChart.data.datasets[0].data = d.availability;
            availChart.update();

            respChart.data.labels = d.labels;
            respChart.data.datasets[0].data = d.response;
            respChart.update();
        });
    });

});
</script>
@endpush
