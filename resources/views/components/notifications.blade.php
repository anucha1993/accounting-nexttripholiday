@php
    $group = getUserGroup();
    $user = Auth::user();
    $unreadCount = $notifications
        ->filter(function ($n) use ($group, $user) {
            if ($group === 'admin' || $group === 'accounting') {
                return $n->reads->isEmpty();
            } elseif ($group === 'sale') {
                return !$n->is_read;
            }
            return false;
        })
        ->count();
@endphp
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle waves-effect waves-dark position-relative" href="#" id="notificationDropdown"
        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fs-5 mdi mdi-bell"></i>
        @if ($unreadCount > 0)
            <div class="notify">
                <span class="heartbit"></span>
                <span class="point"></span>
            </div>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="notificationDropdown"
        style="width: 350px; max-height: 400px; overflow-y: auto;">
        <div class="border-bottom rounded-top py-3 px-4">
            <div class="mb-0 font-weight-medium fs-5">
                คุณมี {{ $unreadCount }} การแจ้งเตือนใหม่
            </div>
        </div>
        <div class="message-center message-body position-relative" style="max-height: 300px; overflow-y: auto;">
            @forelse($notifications as $notification)
                @php
                    $isRead = ($group === 'admin' || $group === 'accounting') ? !$notification->reads->isEmpty() : $notification->is_read;
                @endphp
                <div class="d-flex align-items-center border-bottom px-3 py-2 notification-item {{ !$isRead ? 'bg-danger bg-opacity-10' : 'bg-white' }}"
                    data-id="{{ $notification->id }}">
                    <div class="w-100 d-inline-block v-middle ps-1">
                         <a href="{{ route('notifications.goTo', $notification->id) }}" class="text-decoration-none text-dark fw-bold">
                        <span class="mb-0 mt-1 fs-2 fw-bold text-dark">
                            {{ $notification->message }}</span>
                         </a>
                        <span class="fs-2 text-nowrap d-block subtext text-muted"
                            style="font-size: 0.85rem;">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="ms-2">
                        @if (!$isRead)
                           
                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn fs-1 btn-sm btn-outline-success">อ่านแล้ว</button>
                            </form>
                        @else
                            <span class="badge bg-success">อ่านแล้ว</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-3 text-muted">ไม่มีการแจ้งเตือน</div>
            @endforelse
        </div>
        <a class="nav-link border-top text-center text-dark pt-3" href="{{ route('notifications.index') }}">
            <b>ดูการแจ้งเตือนทั้งหมด</b>
            <i class="fa fa-angle-right"></i>
        </a>
        <div class="text-center py-2">
           <form method="POST" action="{{ route('notifications.markAllAsRead') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-primary">ทำเครื่องหมายอ่านแล้วทั้งหมด</button>
                        </form>
        </div>
    </div>
</li>
<script>
    document.getElementById('mark-all-read')?.addEventListener('click', function() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    });
</script>
