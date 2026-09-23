<div class="offcanvas offcanvas-end offcanvas-size-lg" id="notificationOffcanvas">
    <div class="offcanvas-header border-bottom border-light">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">
            Recent Notification
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" data-simplebar>
        @php
            $recentNotifications = \App\Models\DeviceNotification::query()
                ->orderByDesc('checked_at')
                ->limit(6)
                ->get();
        @endphp

        @if ($recentNotifications->isEmpty())
            <div class="text-center text-soft py-5">
                <em class="icon ni ni-bell-off" style="font-size:2rem;opacity:.35"></em>
                <p class="mb-0 mt-2">Belum ada notifikasi.</p>
            </div>
        @else
            <ul class="nk-schedule">
                @foreach ($recentNotifications as $item)
                    <li class="nk-schedule-item">
                        <div class="nk-schedule-item-inner">
                            <div class="nk-schedule-symbol {{ $item->status === 'down' ? 'active' : 'success' }}">
                            </div>
                            <div class="nk-schedule-content">
                                <span class="smaller">{{ $item->checked_at ? $item->checked_at->format('d M H:i') : '' }}</span>
                                <div class="h6">
                                    {{ $item->device_name }}
                                    <span class="badge {{ $item->status === 'down' ? 'bg-danger' : 'bg-success' }}">{{ $item->event }}</span>
                                </div>
                                <p class="mb-1 text-soft small">{{ $item->ip_address }}</p>
                                <div class="small text-soft">@if ($item->message){{ $item->message }}@endif</div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="text-center mt-3">
                <a href="{{ route('admin.notifikasi') }}" class="btn btn-sm btn-outline-primary w-100">Lihat semua notifikasi →</a>
            </div>
        @endif
    </div>
</div>