@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i> รายการแจ้งเตือน</h5>
                    
                    @if($notifications->isNotEmpty())
                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-check-double me-1"></i> อ่านทั้งหมด
                            </button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5>ไม่มีการแจ้งเตือน</h5>
                            <p class="text-muted">คุณจะได้รับการแจ้งเตือนเกี่ยวกับกิจกรรมสำคัญที่นี่</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification->status === 'unread' ? 'list-group-item-light' : '' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-1 fw-{{ $notification->status === 'unread' ? 'bold' : 'normal' }}">
                                                @switch($notification->related_type)
                                                    @case('refund')
                                                        <i class="fas fa-money-bill-wave text-danger me-2"></i>
                                                        @break
                                                    @case('wholesale_refund')
                                                        <i class="fas fa-money-check-alt text-warning me-2"></i>
                                                        @break
                                                    @case('wholesale_payment')
                                                        <i class="fas fa-hand-holding-usd text-success me-2"></i>
                                                        @break
                                                    @case('booking')
                                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-bell text-info me-2"></i>
                                                @endswitch
                                                {{ $notification->message }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i> {{ $notification->time_ago }}
                                            </small>
                                        </div>
                                        
                                        <div>
                                            <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    {{ $notification->action_url ? 'ดูรายละเอียด' : 'ทำเครื่องหมายว่าอ่านแล้ว' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
