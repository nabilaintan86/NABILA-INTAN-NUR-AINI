<div class="offcanvas offcanvas-end offcanvas-size-lg" id="notificationOffcanvas">
    <div class="offcanvas-header border-bottom border-light">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">
            Recent Notification
        </h5>
        <button
            type="button"
            id="notificationMarkAllRead"
            class="btn btn-sm btn-outline-primary{{ $unreadCount > 0 ? '' : ' d-none' }}"
            data-unread="{{ $unreadCount }}"
        >
            <em class="icon ni ni-check-circle"></em> Baca Semua
        </button>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" data-simplebar>
        <div id="notificationReadAllDone" class="alert alert-success d-none" role="status">
            Semua notifikasi sudah dibaca.
        </div>

        @if ($recentNotifications->isEmpty())
            <div class="text-center text-soft py-5">
                <em class="icon ni ni-bell-off" style="font-size:2rem;opacity:.35"></em>
                <p class="mb-0 mt-2">Belum ada notifikasi.</p>
            </div>
        @else
            <ul class="nk-schedule" id="notificationOffcanvasList">
                @foreach ($recentNotifications as $item)
                    <li class="nk-schedule-item" data-notification-id="{{ $item->id }}">
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

@push('scripts')
<script>
/*
 * Badge lonceng = jumlah notifikasi yang belum dibaca.
 *
 * Notifikasi yang sudah ditampilkan di panel Recent Notification langsung
 * ditandai sudah dibaca (is_read = 1) sehingga badge hilang / turun, dan
 * statusnya tersimpan di database. Badge juga disegarkan ulang lewat AJAX
 * supaya tidak perlu reload halaman.
 */
(function () {
    var UNREAD_URL = '{{ route('admin.notifikasi.unread-count') }}';
    var READ_URL   = '{{ route('admin.notifikasi.read-displayed') }}';
    var READ_ALL_URL = '{{ route('admin.notifikasi.read-all') }}';

    var markAllButton = document.getElementById('notificationMarkAllRead');
    var readAllDone = document.getElementById('notificationReadAllDone');
    var markAllInFlight = false;
    var feedbackTimer = null;

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function jsonHeaders() {
        return {
            'X-CSRF-TOKEN': csrfToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
    }

    /* Tombol "Baca Semua" ikut disembunyikan saat tidak ada unread. */
    function setMarkAllVisible(visible) {
        if (markAllButton) {
            markAllButton.classList.toggle('d-none', !visible);
        }
    }

    function setUnread(count) {
        var value = Number(count);
        if (!isFinite(value) || value < 0) {
            return;
        }
        value = Math.floor(value);

        document.querySelectorAll('.js-notification-badge').forEach(function (badge) {
            badge.textContent = value > 99 ? '99+' : String(value);
            badge.setAttribute('data-unread', String(value));
            badge.classList.toggle('d-none', value === 0);
        });

        setMarkAllVisible(value > 0);
    }

    function showReadAllDone() {
        if (!readAllDone) {
            return;
        }

        readAllDone.classList.remove('d-none');
        window.clearTimeout(feedbackTimer);
        feedbackTimer = window.setTimeout(function () {
            readAllDone.classList.add('d-none');
        }, 3000);
    }

    /* Dipakai halaman Notifikasi setelah membuka detail satu notifikasi. */
    function refreshFromServer() {
        return fetch(UNREAD_URL, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
            .then(function (response) {
                return response.ok ? response.json() : null;
            })
            .then(function (data) {
                if (data && typeof data.unread !== 'undefined') {
                    setUnread(data.unread);
                }
                return data;
            })
            .catch(function () { return null; });
    }

    function displayedIds() {
        var list = document.getElementById('notificationOffcanvasList');
        if (!list) {
            return [];
        }

        return Array.prototype.map.call(
            list.querySelectorAll('[data-notification-id]'),
            function (item) {
                return parseInt(item.getAttribute('data-notification-id'), 10);
            }
        ).filter(function (id) {
            return !isNaN(id);
        });
    }

    /* Badge langsung dimunculkan/sembunyikan, lalu disinkronkan ke server. */
    function markDisplayedAsRead() {
        var ids = displayedIds();
        if (ids.length === 0) {
            refreshFromServer();
            return;
        }

        setUnread(0);

        fetch(READ_URL, {
            method: 'PATCH',
            headers: jsonHeaders(),
            credentials: 'same-origin',
            body: JSON.stringify({ ids: ids })
        })
            .then(function (response) {
                return response.ok ? response.json() : null;
            })
            .then(function (data) {
                /* Hitungan sebenarnya dari server (notifikasi baru mungkin
                 * muncul di antara render halaman dan klik lonceng). */
                if (data && typeof data.unread !== 'undefined') {
                    setUnread(data.unread);
                }
            })
            .catch(function () { refreshFromServer(); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var offcanvas = document.getElementById('notificationOffcanvas');
        if (!offcanvas) {
            return;
        }

        offcanvas.addEventListener('show.bs.offcanvas', function () {
            markDisplayedAsRead();
        });

        if (markAllButton) {
            markAllButton.addEventListener('click', function (event) {
                event.preventDefault();

                /* Cegah klik berkali-kali saat request masih berjalan. */
                if (markAllInFlight) {
                    return;
                }

                markAllInFlight = true;
                markAllButton.disabled = true;

                fetch(READ_ALL_URL, {
                    method: 'PATCH',
                    headers: jsonHeaders(),
                    credentials: 'same-origin'
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Gagal menandai notifikasi');
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        setUnread(typeof data.unread === 'undefined' ? 0 : data.unread);
                        showReadAllDone();
                    })
                    .catch(function () {
                        refreshFromServer();
                    })
                    .then(function () {
                        markAllInFlight = false;
                        if (markAllButton) {
                            markAllButton.disabled = false;
                        }
                    });
            });
        }
    });

    window.AdminNotificationBadge = {
        set: setUnread,
        refresh: refreshFromServer
    };
})();
</script>
@endpush
