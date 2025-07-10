@extends('layouts.template')
@section('title','เพิ่มลูกค้า')
@section('content')
<div class="container-fluid">
    <h1 class="mt-4">เพิ่มลูกค้า</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('cus.store') }}">
                @csrf
                <div class="form-group">
                    <label>ชื่อลูกค้า <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email') }}">
                </div>
                <div class="form-group">
                    <label>เบอร์โทร</label>
                    <input type="text" name="customer_tel" class="form-control" value="{{ old('customer_tel') }}">
                </div>
                <div class="form-group">
                    <label>ที่อยู่</label>
                    <textarea name="customer_address" class="form-control">{{ old('customer_address') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="{{ route('cus.index') }}" class="btn btn-secondary">ยกเลิก</a>
            </form>
        </div>
    </div>
</div>
@endsection
