<div class="table-responsive">
    <table class="table table-hover table-middle align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="w-auto">#</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Perangkat</th>
                <th>IP Address</th>
                <th>Pesan</th>
                <th class="text-end">Latency</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr class="log-item" data-log-id="{{ $log->id }}" style="cursor: pointer;">
                    <td class="text-muted small">{{ $logs->firstItem() + $index }}</td>
                    <td class="text-muted small">{{ $log->checked_at?->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <span class="badge {{ $log->status === 'up' ? 'text-bg-success-soft' : ($log->status === 'down' ? 'text-bg-danger-soft' : 'text-bg-warning-soft') }}">
                            {{ strtoupper($log->status) }}
                        </span>
                    </td>
                    <td class="fw-semibold">{{ $log->device_name }}</td>
                    <td><code class="small">{{ $log->ip_address }}</code></td>
                    <td class="small text-muted">
                        @if($log->status === 'up')
                            Respons normal
                        @elseif($log->status === 'warning')
                            Latensi tinggi
                        @else
                            Tidak merespons / timeout
                        @endif
                    </td>
                    <td class="small text-end">
                        @if($log->status === 'up' && $log->response_time !== null)
                            {{ $log->response_time }} ms
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        Tidak ada data pada rentang tanggal ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($logs->hasPages())
    <div class="d-flex justify-content-center pt-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
@endif