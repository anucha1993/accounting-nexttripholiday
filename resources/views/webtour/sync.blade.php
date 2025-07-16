@extends('layouts.template')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="fa fa-sync fa-2x text-primary me-2"></i>
        <h2 class="mb-0">WebTour Sync <span class="fs-6 text-muted">ระบบซิงค์ข้อมูลระหว่างฐานข้อมูล</span></h2>
    </div>
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card shadow border-{{ (isset($sourceStatus) && $sourceStatus === 'success') ? 'success' : 'danger' }}">
                <div class="card-header bg-{{ (isset($sourceStatus) && $sourceStatus === 'success') ? 'success' : 'danger' }} text-white">
                    <i class="fa fa-database"></i> Source DB <span class="badge bg-light text-dark ms-2">WEB_TOUR</span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><b>Host:</b> <span class="text-monospace">{{ $sourceConfig['host'] ?? '-' }}</span></li>
                        <li><b>Database:</b> <span class="text-monospace">{{ $sourceConfig['database'] ?? '-' }}</span></li>
                        <li><b>User:</b> <span class="text-monospace">{{ $sourceConfig['username'] ?? '-' }}</span></li>
                        <li><b>Status:</b> <span class="badge bg-{{ (isset($sourceStatus) && $sourceStatus === 'success') ? 'success' : 'danger' }}">{{ (isset($sourceStatus) && $sourceStatus === 'success') ? 'Connected' : 'N/A' }}</span></li>
                    </ul>
                    @if(isset($sourceStatus) && $sourceStatus === 'error')
                        <div class="alert alert-danger mt-2 p-2 small">{{ $sourceError ?? '' }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow border-{{ (isset($targetStatus) && $targetStatus === 'success') ? 'success' : 'danger' }}">
                <div class="card-header bg-{{ (isset($targetStatus) && $targetStatus === 'success') ? 'success' : 'danger' }} text-white">
                    <i class="fa fa-database"></i> Target DB <span class="badge bg-light text-dark ms-2">vdragon_next</span>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><b>Host:</b> <span class="text-monospace">{{ $targetConfig['host'] ?? '-' }}</span></li>
                        <li><b>Database:</b> <span class="text-monospace">{{ $targetConfig['database'] ?? '-' }}</span></li>
                        <li><b>User:</b> <span class="text-monospace">{{ $targetConfig['username'] ?? '-' }}</span></li>
                        <li><b>Status:</b> <span class="badge bg-{{ (isset($targetStatus) && $targetStatus === 'success') ? 'success' : 'danger' }}">{{ (isset($targetStatus) && $targetStatus === 'success') ? 'Connected' : 'N/A' }}</span></li>
                    </ul>
                    @if(isset($targetStatus) && $targetStatus === 'error')
                        <div class="alert alert-danger mt-2 p-2 small">{{ $targetError ?? '' }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="fa fa-cogs me-2"></i> <b>ตั้งค่าการ Sync Table</b>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ url('/web-tour/sync') }}">
                <div class="mb-3">
                    <label><b>เลือก Table ที่ต้องการ Sync</b></label>
                    <div class="row g-2">
                        @foreach($tableList as $table)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tables[]" value="{{ $table }}" id="table_{{ $table }}" 
                                        @if(is_array(old('tables', $selectedTables ?? [])) ? in_array($table, old('tables', $selectedTables ?? [])) : false) checked @endif>
                                    <label class="form-check-label" for="table_{{ $table }}">{{ $table }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button class="btn btn-success"><i class="fa fa-sync"></i> Sync Now</button>
            </form>
        </div>
    </div>
    @if(isset($results))
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white"><i class="fa fa-check-circle"></i> ผลลัพธ์การ Sync</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Table</th>
                            <th>อัปเดต</th>
                            <th>เพิ่มใหม่</th>
                            <th>รวม</th>
                            <th>สถานะ</th>
                            <th>ข้อความผิดพลาด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $r)
                            <tr>
                                <td>{{ $r['table'] }}</td>
                                <td>{{ $r['updated'] }}</td>
                                <td>{{ $r['inserted'] }}</td>
                                <td>{{ $r['total'] }}</td>
                                <td><span class="badge bg-{{ $r['status'] === 'success' ? 'success' : 'danger' }}">{{ $r['status'] }}</span></td>
                                <td class="text-danger small">{{ $r['error'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <div class="card shadow">
        <div class="card-header bg-secondary text-white"><i class="fa fa-history"></i> ประวัติการ Sync ล่าสุด</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>เวลา</th>
                        <th>Table</th>
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
