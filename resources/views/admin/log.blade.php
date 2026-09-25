@extends('layouts.admin')

@section('title', 'Log History · RADNeXt NOC')

@push('styles')
<style>
    .realtime-log-list {
        max-height: 620px;
        overflow-y: auto;
    }

    .log-item {
        transition: background-color .12s ease;
    }

    .log-item:hover {
        background-color: #f6f8fa;
    }
</style>
@endpush

@section('content')
<div class="nk-block">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="nk-block-head">
        <div class="nk-block-head-between flex-wrap gap g-2">
            <div class="nk-block-head-content">
                <nav>
                    <ol class="breadcrumb breadcrumb-arrow mb-0">
                        <li class="breadcrumb-item active" aria-current="page">
                            Log History
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="nk-block-head-content">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill text-bg-primary-soft">
                        <em class="icon ni ni-clock me-1"></em>
                        {{ number_format($statusCounts['total']) }} total entri log
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         KPI SUMMARY (TOTAL / UP / DOWN / WARNING)
    ========================================================== --}}
    <div class="row g-gs">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="media media-md media-middle media-circle text-bg-primary-soft">
                            <em class="icon ni ni-file-text"></em>
                        </div>
                        <div>
                            <span class="lead-text">{{ number_format($statusCounts['total']) }}</span>
                            <span class="sub-text d-block">Total Log</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="media media-md media-middle media-circle text-bg-success-soft">
                            <em class="icon ni ni-check-circle"></em>
                        </div>
                        <div>
                            <span class="lead-text">{{ number_format($statusCounts['up']) }}</span>
                            <span class="sub-text d-block">Status UP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="media media-md media-middle media-circle text-bg-danger-soft">
                            <em class="icon ni ni-cross-circle"></em>
                        </div>
                        <div>
                            <span class="lead-text">{{ number_format($statusCounts['down']) }}</span>
                            <span class="sub-text d-block">Status DOWN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-bordered">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="media media-md media-middle media-circle text-bg-warning-soft">
                            <em class="icon ni ni-alert-circle"></em>
                        </div>
                        <div>
                            <span class="lead-text">{{ number_format($statusCounts['warning']) }}</span>
                            <span class="sub-text d-block">Status Warning</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         TAB: REAL-TIME  /  ARSIP
    ========================================================== --}}
    <ul class="nav nav-tabs mt-4" id="logTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="realtime-tab" data-bs-toggle="tab"
                    data-bs-target="#realtime-pane" type="button" role="tab"
                    aria-controls="realtime-pane" aria-selected="true">
                Real-time
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="archive-tab" data-bs-toggle="tab"
                    data-bs-target="#archive-pane" type="button" role="tab"
                    aria-controls="archive-pane" aria-selected="false">
                Arsip
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3">
        {{-- =========================================================
             PANEL REAL-TIME
        ========================================================== --}}
        <div class="tab-pane fade show active" id="realtime-pane" role="tabpanel" aria-labelledby="realtime-tab">
            <div class="card card-bordered">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="d-inline-block rounded-circle bg-success" style="width:8px;height:8px;"></span>
                        <h6 class="mb-0">Aktivitas Monitoring</h6>
                        <span class="small text-muted ms-2">Auto-refresh tiap 10 detik</span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div id="realtimeList" class="realtime-log-list">
                        @include('admin.partials.log-items', ['logs' => $logs])

                        <div id="realtimeSentinel" class="text-center text-muted py-3">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================================================
             PANEL ARSIP
        ========================================================== --}}
        <div class="tab-pane fade" id="archive-pane" role="tabpanel" aria-labelledby="archive-tab">
            <div class="card card-bordered">
                <div class="card-header">
                    <div class="row g-2 align-items-end">
                        <div class="col-auto">
                            <label for="archiveFrom" class="form-label small text-muted mb-1">Dari</label>
                            <input type="date" class="form-control" id="archiveFrom">
                        </div>
                        <div class="col-auto">
                            <label for="archiveTo" class="form-label small text-muted mb-1">Sampai</label>
                            <input type="date" class="form-control" id="archiveTo">
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" id="btnArchiveFilter">
                                <em class="icon ni ni-search"></em>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                        <div class="col text-end">
                            <span class="small text-muted" id="archiveInfo">0 entri</span>
                        </div>
                    </div>
                </div>

                <div id="archiveContainer">
                    <div class="text-center text-muted py-5">
                        Pilih rentang tanggal lalu klik Tampilkan.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- =========================================================
     MODAL DETAIL LOG / DEVICE
========================================================== --}}
<div class="modal fade" id="logDetailModal" tabindex="-1" aria-labelledby="logDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailModalLabel">Detail Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="logDetailBody">
                <div class="text-center text-muted py-4">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const listEl      = document.getElementById('realtimeList');
        const sentinelEl  = document.getElementById('realtimeSentinel');
        let offset        = {{ count($logs) }};
        let loading       = false;
        let hasMore       = true;
        let refreshTimer  = null;

        function loadMore() {
            if (loading || !hasMore) return;
            loading = true;

            fetch('{{ route('admin.log.load-more') }}?offset=' + offset, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.html && data.html.trim()) {
                        sentinelEl.insertAdjacentHTML('beforebegin', data.html);
                    }
                    offset  = data.next_offset;
                    hasMore = data.has_more;
                    if (!hasMore) {
                        sentinelEl.style.display = 'none';
                    }
                })
                .catch(function () {})
                .finally(function () { loading = false; });
        }

        function refreshRealtime() {
            loading = true;

            fetch('{{ route('admin.log.load-more') }}?offset=0', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    listEl.querySelectorAll('.log-item').forEach(function (el) {
                        el.remove();
                    });
                    sentinelEl.insertAdjacentHTML('beforebegin', data.html);
                    offset  = data.next_offset;
                    hasMore = data.has_more;
                    sentinelEl.style.display = hasMore ? 'block' : 'none';
                })
                .catch(function () {})
                .finally(function () { loading = false; });
        }

        // Infinite scroll
        const io = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) {
                loadMore();
            }
        }, { root: listEl, rootMargin: '200px' });
        io.observe(sentinelEl);

        // Auto-refresh tiap 10 detik
        function startAutoRefresh() {
            if (refreshTimer) clearInterval(refreshTimer);
            refreshTimer = setInterval(refreshRealtime, 10000);
        }
        startAutoRefresh();

        // Klik log -> modal detail device
        listEl.addEventListener('click', function (e) {
            const item = e.target.closest('.log-item');
            if (item && item.dataset.logId) {
                openLogDetail(item.dataset.logId);
            }
        });

        // =========================================================
        //  ARSIP
        // =========================================================
        const archiveTab      = document.getElementById('archive-tab');
        const archiveBtn      = document.getElementById('btnArchiveFilter');
        const archiveInfo     = document.getElementById('archiveInfo');
        const archiveContainer = document.getElementById('archiveContainer');
        let archiveLoaded     = false;

        function loadArchive() {
            const from = document.getElementById('archiveFrom').value;
            const to   = document.getElementById('archiveTo').value;
            const params = new URLSearchParams();

            if (from) params.set('from', from);
            if (to)   params.set('to', to);

            archiveContainer.innerHTML = '<div class="text-center text-muted py-5">' +
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>';

            fetch('{{ route('admin.log.archive') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    archiveContainer.innerHTML = data.html;
                    archiveInfo.textContent    = numberFormat(data.total) + ' entri';
                });
        }

        archiveTab.addEventListener('shown.bs.tab', function () {
            if (!archiveLoaded) {
                archiveLoaded = true;
                loadArchive();
            }
        });

        archiveBtn.addEventListener('click', loadArchive);

        archiveContainer.addEventListener('click', function (e) {
            const link = e.target.closest('a.page-link');
            if (link) {
                e.preventDefault();
                fetch(link.getAttribute('href'), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        archiveContainer.innerHTML = data.html;
                    });
            }

            const row = e.target.closest('.log-item');
            if (row && row.dataset.logId) {
                openLogDetail(row.dataset.logId);
            }
        });

        // =========================================================
        //  MODAL DETAIL
        // =========================================================
        function openLogDetail(id) {
            const modalEl = document.getElementById('logDetailModal');
            const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);

            document.getElementById('logDetailModalLabel').textContent = 'Detail Log';
            document.getElementById('logDetailBody').innerHTML =
                '<div class="text-center text-muted py-4">' +
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div>';

            modal.show();

            fetch('{{ route('admin.log.detail', ['id' => '__ID__']) }}'.replace('__ID__', id), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    document.getElementById('logDetailModalLabel').textContent =
                        'Detail Log \u2014 ' + escapeHtml(data.log.device_name);
                    document.getElementById('logDetailBody').innerHTML = buildDetailHtml(data);
                })
                .catch(function () {
                    document.getElementById('logDetailBody').innerHTML =
                        '<p class="text-center text-danger py-4 mb-0">Gagal memuat detail log.</p>';
                });
        }

        function buildDetailHtml(data) {
            const log = data.log;
            const st  = log.status;
            const badgeCls = st === 'up'   ? 'text-bg-success-soft'
                           : st === 'down' ? 'text-bg-danger-soft'
                                           : 'text-bg-warning-soft';

            let html = '<div class="d-flex align-items-center gap-3 mb-3">' +
                '<span class="badge ' + badgeCls + ' fs-6">' + st.toUpperCase() + '</span>' +
                '<div>' +
                    '<div class="fw-semibold text-body">' + escapeHtml(log.device_name) + '</div>' +
                    '<div class="small text-muted">' + escapeHtml(log.ip_address) + ' \u2022 ' + escapeHtml(log.checked_at) + '</div>' +
                '</div>' +
            '</div>';

            html += '<table class="table table-sm table-bordered align-middle mb-3">' +
                '<tbody>' +
                    '<tr><td class="fw-semibold">Waktu</td><td>' + escapeHtml(log.checked_at) + '</td></tr>' +
                    '<tr><td class="fw-semibold">IP Address</td><td><code>' + escapeHtml(log.ip_address) + '</code></td></tr>' +
                    '<tr><td class="fw-semibold">Response Time</td><td>' +
                        (log.response_time !== null && log.response_time !== undefined
                            ? escapeHtml(String(log.response_time)) + ' ms' : '\u2014') +
                    '</td></tr>' +
                '</tbody>' +
            '</table>';

            if (data.device) {
                const dev = data.device;
                const devStatusColor = dev.status === 'up'   ? 'text-bg-success-soft'
                                     : dev.status === 'down' ? 'text-bg-danger-soft'
                                                             : 'text-bg-warning-soft';

                html += '<div class="d-flex align-items-center justify-content-between mb-2">' +
                    '<h6 class="text-uppercase small text-muted mb-0">Detail Device</h6>' +
                    '<span class="badge ' + devStatusColor + '">' + escapeHtml(String(dev.status).toUpperCase()) + '</span>' +
                '</div>';

                html += '<table class="table table-sm table-bordered table-striped align-middle mb-0">' +
                    '<tbody>' +
                        '<tr><td class="fw-semibold w-50">Nama</td><td>' + escapeHtml(dev.name) + '</td></tr>' +
                        '<tr><td class="fw-semibold">Tipe</td><td>' + escapeHtml(dev.type || '\u2014') + '</td></tr>' +
                        '<tr><td class="fw-semibold">IP Address</td><td><code>' + escapeHtml(dev.ip_address) + '</code></td></tr>' +
                        '<tr><td class="fw-semibold">Total Polls</td><td>' + numberFormat(dev.polls) + '</td></tr>' +
                        '<tr><td class="fw-semibold">% Responded</td><td>' + dev.responded_percentage + '%</td></tr>' +
                        '<tr><td class="fw-semibold">% Missed</td><td>' + dev.missed_percentage + '%</td></tr>' +
                        '<tr><td class="fw-semibold">Avg Latency</td><td>' + numberFormat(dev.avg_delay) + ' ms</td></tr>' +
                        '<tr><td class="fw-semibold">Min Latency</td><td>' + numberFormat(dev.min_delay) + ' ms</td></tr>' +
                        '<tr><td class="fw-semibold">Max Latency</td><td>' + numberFormat(dev.max_delay) + ' ms</td></tr>' +
                        '<tr><td class="fw-semibold">Down Time</td><td>' + escapeHtml(dev.down_time || '\u2014') + '</td></tr>' +
                        '<tr><td class="fw-semibold">Periode</td><td>' + escapeHtml(dev.period || '\u2014') + '</td></tr>' +
                        '<tr><td class="fw-semibold">Jumlah Alert</td><td>' + numberFormat(dev.alerts_count) + '</td></tr>' +
                    '</tbody>' +
                '</table>';
            } else {
                html += '<p class="small text-muted mb-0">Perangkat sudah tidak terdaftar lagi.</p>';
            }

            return html;
        }

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function numberFormat(value) {
            return new Intl.NumberFormat('id-ID').format(Number(value) || 0);
        }
    })();
</script>
@endpush