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
                <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="">ชื่อลูกค้า: </label>
                                <input type="text" id="customer_name" class="form-control" placeholder="ชื่อลูกค้า" name="customer_name" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">เลขประจำตัวผู้เสียภาษี: </label>
                                <input type="text" id="customer_texid" class="form-control" placeholder="เลขประจำตัวผู้เสียภาษี" name="customer_texid">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">อีเมล์:</label>
                                <input type="email" id="customer_email" class="form-control" placeholder="Email" name="customer_email">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">เบอร์โทรศัพท์: </label>
                                <input type="text" id="customer_tel" class="form-control" placeholder="+66" name="customer_tel">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">เบอร์โทรสาร: </label>
                                <input type="text" id="customer_fax" class="form-control" placeholder="+66" name="customer_fax">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="">ลูกค้าจาก : </label>
                                <select id="customer_campaign_source" class="form-select" name="customer_campaign_source">
                                    @forelse ($campaignSource as $item)
                                        <option value="{{ $item->campaign_source_id }}">
                                            {{ $item->campaign_source_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="">Social id : </label>
                                <input type="text" id="customer_social_id" class="form-control" placeholder="+66" name="customer_social_id">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="">ที่อยู่ลูกค้า: </label>
                                <textarea id="customer_address" class="form-control" cols="30" rows="2" placeholder="ที่อยู่ลูกค้า" name="customer_address"></textarea>
                            </div>
                        </div>
                <button type="submit" class="btn btn-primary">บันทึก</button>
                <a href="{{ route('cus.index') }}" class="btn btn-secondary">ยกเลิก</a>
            </form>
        </div>
    </div>
</div>
@endsection
