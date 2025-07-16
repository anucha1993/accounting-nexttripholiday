@extends('layouts.template')
@section('content')
<div class="container py-4">
    <h3>WebTour Sync</h3>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-{{ $sourceStatus === 'success' ? 'success' : 'danger' }}">
                <div class="card-header bg-{{ $sourceStatus === 'success' ? 'success' : 'danger' }} text-white">
                    <i class="fa fa-database"></i> Source DB (WEB_TOUR)
                </div>
                <div class="card-body">
                    <b>Host:</b> {{ $sourceConfig['host'] }}<br>
                    <b>Database:</b> {{ $sourceConfig['database'] }}<br>
                    <b>User:</b> {{ $sourceConfig['username'] }}<br>
                    <b>Status:</b> <span class="badge bg-{{ $sourceStatus === 'success' ? 'success' : 'danger' }}">{{ $sourceStatus === 'success' ? 'Connected' : 'Failed' }}</span>
                    @if($sourceStatus === 'error')
                        <div class="text-danger small mt-2">{{ $sourceError }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-{{ $targetStatus === 'success' ? 'success' : 'danger' }}">
                <div class="card-header bg-{{ $targetStatus === 'success' ? 'success' : 'danger' }} text-white">
                    <i class="fa fa-database"></i> Target DB (vdragon_next)
                </div>
                <div class="card-body">
                    <b>Host:</b> {{ $targetConfig['host'] }}<br>
                    <b>Database:</b> {{ $targetConfig['database'] }}<br>
                    <b>User:</b> {{ $targetConfig['username'] }}<br>
                    <b>Status:</b> <span class="badge bg-{{ $targetStatus === 'success' ? 'success' : 'danger' }}">{{ $targetStatus === 'success' ? 'Connected' : 'Failed' }}</span>
                    @if($targetStatus === 'error')
                        <div class="text-danger small mt-2">{{ $targetError }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <form method="GET" action="{{ url('/web-tour/sync') }}">
        <button class="btn btn-primary mb-3"><i class="fa fa-sync"></i> Sync Now</button>
    </form>
    @if(isset($status) && $status === 'error')
        <div class="alert alert-danger">
            <strong>เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูลหรือซิงค์ข้อมูล:</strong><br>
            {{ $error }}
        </div>
    @else
        <div class="alert alert-info">
            <strong>ผลลัพธ์:</strong><br>
            อัปเดต {{ $updated }} รายการ, เพิ่มใหม่ {{ $inserted }} รายการ, ทั้งหมด {{ $total }} รายการ
        </div>
    @endif
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white"><i class="fa fa-history"></i> ประวัติการ Sync ล่าสุด</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>เวลา</th>
                        <th>ผลลัพธ์</th>
                        <th>อัปเดต</th>
                        <th>เพิ่มใหม่</th>
                        <th>รวม</th>
                        <th>สถานะ</th>
                        <th>ข้อความผิดพลาด</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->synced_at }}</td>
                            <td>{{ $log->table_name }}</td>
                            <td>{{ $log->total_updated }}</td>
                            <td>{{ $log->total_inserted }}</td>
                            <td>{{ $log->total_synced }}</td>
                            <td><span class="badge bg-{{ $log->status === 'success' ? 'success' : 'danger' }}">{{ $log->status }}</span></td>
                            <td class="text-danger small">{{ $log->error_message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
