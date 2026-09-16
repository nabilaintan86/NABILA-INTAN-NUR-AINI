@forelse($logs as $log)
    <a href="javascript:void(0)"
       class="log-item d-flex align-items-center gap-3 px-3 py-3 text-decoration-none"
       data-log-id="{{ $log->id }}">
        <span class="dot {{ $log->status === 'up' ? 'bg-success' : ($log->status === 'down' ? 'bg-danger' : 'bg-warning') }}"></span>

        <span class="badge {{ $log->status === 'up' ? 'text-bg-success-soft' : ($log->status === 'down' ? 'text-bg-danger-soft' : 'text-bg-warning-soft') }}">
            {{ strtoupper($log->status) }}
        </span>

        <span class="flex-grow-1 min-w-0">
            <span class="d-block fw-semibold text-body text-truncate">{{ $log->device_name }}</span>
            <span class="d-block small text-muted text-truncate">{{ $log->ip_address }}</span>
        </span>

        <span class="text-end ms-auto">
            <span class="d-block small fw-semibold text-body">{{ $log->checked_at?->format('H:i:s') }}</span>
            <span class="d-block small text-muted">
                @if($log->status === 'up' && $log->response_time !== null)
                    {{ $log->response_time }} ms
                @elseif($log->status === 'warning')
                    Latensi tinggi
                @else
                    Timeout
                @endif
            </span>
        </span>
    </a>
@empty
    <div class="text-center text-muted py-5">
        <em class="icon ni ni-search fs-3 d-block mb-2 text-light"></em>
        Belum ada log. Jalankan
        <code class="text-body">php artisan monitor:ping</code>
        untuk menghasilkan riwayat pertama.
    </div>
@endforelse