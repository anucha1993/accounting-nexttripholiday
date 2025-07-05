@extends('layouts.template')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">การแจ้งเตือนทั้งหมด</h4>
                </div>
                <div class="card-body p-0">
                    @php
                        $group = getUserGroup();
                        $user = Auth::user();
                    @endphp
                    <ul class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                            @php
                                $isRead = ($group === 'admin' || $group === 'accounting') ? !$notification->reads->isEmpty() : $notification->is_read;
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-3 {{ !$isRead ? 'bg-danger bg-opacity-10' : 'bg-white' }}">
                                <div class="w-100">
                                    <a href="{{ $notification->url ? url($notification->url) : '#' }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $notification->message }}
                                    </a>
                                    <div class="fs-2 text-nowrap subtext text-muted" style="font-size: 0.85rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="ms-2">
                                    @if(!$isRead)
                                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">อ่านแล้ว</button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">อ่านแล้ว</span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">ไม่มีการแจ้งเตือน</li>
                        @endforelse
                    </ul>
                    <div class="text-end px-3 py-2">
                        <form method="POST" action="{{ route('notifications.markAllAsRead') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-primary">ทำเครื่องหมายอ่านแล้วทั้งหมด</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
