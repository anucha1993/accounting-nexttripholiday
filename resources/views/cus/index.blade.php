@extends('layouts.template')
@section('title','ลูกค้า')
@section('content')
<div class="container-fluid">
    <h1 class="mt-4"><i class="fas fa-users text-primary"></i> ลูกค้า</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header bg-light border-bottom-0">
            
            <form method="GET" action="{{ route('cus.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="mb-1 font-weight-bold">ชื่อลูกค้า</label>
                        <input type="text" name="name" class="form-control rounded-pill" placeholder="ชื่อลูกค้า" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="mb-1 font-weight-bold">Email</label>
                        <input type="text" name="email" class="form-control rounded-pill" placeholder="Email" value="{{ request('email') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="mb-1 font-weight-bold">เบอร์โทร</label>
                        <input type="text" name="phone" class="form-control rounded-pill" placeholder="เบอร์โทร" value="{{ request('phone') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-block rounded-pill mr-2"><i class="fas fa-search"></i> ค้นหา</button>
                        <a href="{{ route('cus.index') }}" class="btn btn-outline-secondary btn-block rounded-pill mr-2"><i class="fas fa-sync-alt"></i> รีเซ็ต</a>
                        <button type="submit" name="export" value="1" class="btn btn-success btn-block rounded-pill"><i class="fas fa-file-excel"></i> Export Excel</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            @canany(['customer.create'])
            <a href="{{ route('cus.create') }}" class="btn btn-success mb-3 rounded-pill px-4"><i class="fas fa-plus"></i> เพิ่มลูกค้า</a>
            @endcanany
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle bg-white shadow-sm rounded">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>รหัสลูกค้า</th>
                            <th>ชื่อลูกค้า</th>
                            <th>Email</th>
                            <th>เบอร์โทร</th>
                            <th>วันที่สร้าง</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->customer_id }}</td>
                            <td>{{ $customer->customer_number }}</td>
                            <td>
                                {{ $customer->customer_name }}<br>
                                <span class="text-muted small">{{ $customer->customer_address }}</span>
                            </td>
                            <td>{{ $customer->customer_email }}</td>
                            <td>{{ $customer->customer_tel }}</td>
                            <td class="d-none">{{ $customer->customer_address }}</td>
                            <td>{{ $customer->created_at }}</td>
                            <td class="text-center">
                                @canany(['customer.edit'])
                                <a href="{{ route('cus.edit', $customer->customer_id) }}" class="btn btn-warning btn-sm rounded-pill mr-1"><i class="fas fa-edit"></i> แก้ไข</a>
                                @endcanany
                                @canany(['customer.delete'])
                                <form action="{{ route('cus.destroy', $customer->customer_id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('ยืนยันการลบ?')"><i class="fas fa-trash"></i> ลบ</button>
                                </form>
                                @endcanany
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">ไม่พบข้อมูล</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $customers->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
