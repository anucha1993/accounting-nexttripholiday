@extends('layouts.template')

@section('content')


    <div class="email-app todo-box-container container-fluid">
        <br>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Success - </strong>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Error - </strong>{{ session('error') }}
            </div>
        @endif
        <div class="email-app todo-box-container">

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">ใบเสนอราคา/ใบแจ้งหนี้
                        @can('edit-quote')
                         <a href="{{ route('quote.createNew') }}"
                            class="btn btn-primary float-end">สร้างใบเสนอราคา</a></h4>
                            @endcan
                    <hr>
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-2">
                                <label>ค้นหา ชื่อ-นามสกุล , เลขที่ใบเสนอราคา</label>
                                <div class="input-group mb-3 pull-right">
                                    <input type="text" class="form-control" placeholder="ค้นหา... ชื่อ-นามสกุล,เลขที่ใบเสนอราคา"
                                        name="search_name" value="{{ request('search_name') }}">

                                </div>
                            </div>

                            <div class="col-md-2">
                                <label>ค้นหา วันที่เดินทางเริ่มต้น</label>
                                <div class="input-group mb-3 pull-right">
                                    <input type="date" class="form-control" name="search_tour_date_start"
                                        value="{{ request('search_tour_date_start') }}">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>ถึงวันที่</label>
                                <div class="input-group mb-3 pull-right">
                                    <input type="date" class="form-control" name="search_tour_date_end"
                                        value="{{ request('search_tour_date_end') }}">

                                </div>
                            </div>

                            <div class="col-md-2">
                                <label>ค้นหา วันที่จอง เริ่มต้น</label>
                                <div class="input-group mb-3 pull-right">
                                    <input type="date" class="form-control" name="search_tour_date_start_created"
                                        value="{{ request('search_tour_date_start_created') }}">

                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>ถึงวันที่</label>
                                <div class="input-group mb-3 pull-right">
                                    <input type="date" class="form-control" name="search_tour_date_end_created"
                                        value="{{ request('search_tour_date_end_created') }}">

                                </div>
                            </div>

                            <div class="col-md-2">

                                <label>Sales</label>
                                <div class="input-group mb-3 pull-right">
                                    <select name="search_sale" class="form-select">
                                        <option value="all">ทั้งหมด</option>
                                        @forelse ($sales as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @empty
                                            <option value="" disabled>ไม่มีข้อมูล</option>
                                        @endforelse
                                    </select>


                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="">ประเทศ</label>
                                <select name="search_country" class="form-select select2" style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    @forelse ($country as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->country_name_th }}</option>
                                    @empty
                                        <option value="" disabled>ไม่มีข้อมูล</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="">โฮลเซลล์</label>
                                <select name="search_wholesale" class="form-select select2" style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    @forelse ($wholesales as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->wholesale_name_th }}</option>
                                    @empty
                                        <option value="" disabled>ไม่มีข้อมูล</option>
                                    @endforelse
                                </select>
                            </div>

                            

                            <div class="col-md-2">
                                <label for="">สถานะการชำระเงินโฮลเซลล์</label>
                                <select name="search_wholesale_payment" class="form-select" style="width: 100%">
                                    <option value="all" {{ request('search_wholesale_payment') == 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                                    <option value="NULL" {{ request('search_wholesale_payment') == 'NULL' ? 'selected' : '' }}>รอชำระเงิน</option>
                                    <option value="deposit" {{ request('search_wholesale_payment') == 'deposit' ? 'selected' : '' }}>รอชำระเงินเต็มจำนวน</option>
                                    <option value="full" {{ request('search_wholesale_payment') == 'full' ? 'selected' : '' }}>ชำระเงินครบแล้ว</option>
                                    <option value="wait-payment-wholesale" {{ request('search_wholesale_payment') == 'wait-payment-wholesale' ? 'selected' : '' }}>รอโฮลเซลล์คืนเงิน</option>
                                </select>
                            </div>
                            
                            
                            
                            <div class="col-md-2">
                                <label for="">สถานะการชำระเงินลูกค้า</label>
                                <select name="search_customer_payment" class="form-select" style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    <option value="wait">รอชำระเงิน</option>
                                    <option value="payment">รอชำระเงินเต็มจำนวน</option>
                                    <option value="success">ชำระเงินครบแล้ว</option>
                                    <option value="payment-time-out">เกินกำหนดชำระเงิน</option>
                                    <option value="cancel">ยกเลิกการสั่งซื้อ</option>
                                    <option value="wait-payment">รอคืนเงินลูกค้า</option>
                                    
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">สถานะการใบเสนอราคา</label>
                                <select name="search_quote_status" class="form-select" style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    <option value="wait">รอดำเนินการ</option>
                                    <option value="success">ดำเนินการแล้วเสร็จ</option>
                                    
                                </select>
                            </div>
                            
                            <br>


                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary float-end mx-3" type="submit">ค้นหา</button>
                                <a href="{{ route('quote.index') }}" class="btn btn-outline-secondary float-end mx-3"
                                    type="submit">ล้างข้อมูล</a>

                            </div>
                        </div>
                    </form>

                </div>

            </div>
           
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table customize-table table-hover mb-0 v-middle table-striped table-bordered "
                            style="font-size: 12px">
                            <thead class="table text-white bg-info">
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>เลขที่ใบเสนอราคา</th>
                                    <th>เลขที่ใบจองทัวร์</th>
                                    <th>วันที่เดินทาง</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>ประเทศ</th>
                                    <th>โฮลเซลล์</th>
                                    <th>การชำระของลูกค้า</th>
                                    <th>ยอดใบแจ้งหนี้</th>
                                    <th>การชำระโฮลเซลล์</th>
                                    <th>ผู้ขาย</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($quotations as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->quote_number }}</td>
                                        <td>{{ $item->quote_booking }}</td>
                                        <td>{{ date('d/m/Y', strtotime($item->quote_date_start)) . '-' . date('d/m/Y', strtotime($item->quote_date_end)) }}
                                        </td>
                                        <td>{{ $item->quotecustomer->customer_name }}</td>
                                        <td>
                                            {{$item->quoteCountry->country_name_th}}
                                        </td>
                                        <td>{{ $item->quoteWholesale->wholesale_name_th }}</td>
                                        
                                        <td>
                                            @php
                                                // กำหนดวันที่ปัจจุบัน
                                                $now = date('Y-m-d');
                                        
                                                // กำหนดสถานะเริ่มต้น
                                                $status = '';
                                        
                                                // ตรวจสอบสถานะการสั่งซื้อ
                                                if ($item->quote_status === 'cancel') {
                                                    $status = '<span class="badge rounded-pill bg-danger">ยกเลิกการสั่งซื้อ</span>';
                                                } elseif ($item->quote_status === 'success') {
                                                    $status = '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>';
                                                } elseif ($item->payment> 0) {
                                                    // หากมีการชำระเงินมัดจำแล้ว
                                                    $status = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
                                                } elseif ($item->quote_payment_type === 'deposit') {
                                                    // ตรวจสอบกำหนดชำระเงินมัดจำ
                                                    if (strtotime($now) > strtotime($item->quote_payment_date)) {
                                                        $status = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
                                                    } else {
                                                        $status = '<span class="badge rounded-pill bg-warning text-dark">รอชำระเงินมัดจำ</span>';
                                                    }
                                                } elseif ($item->quote_payment_type === 'full') {
                                                    // ตรวจสอบกำหนดชำระเงินเต็มจำนวน
                                                    if (strtotime($now) > strtotime($item->quote_payment_date_full)) {
                                                        $status  = '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
                                                    } else {
                                                        $status  = '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
                                                    }
                                                } else {
                                                    // กรณีที่ไม่ตรงเงื่อนไขใดๆ
                                                    $status = '<span class="badge rounded-pill bg-secondary">สถานะไม่ระบุ</span>';
                                                }
                                            @endphp
                                        
                                            {!! $status !!}
                                        </td>
                                        

                                        <td>{{ number_format($item->quote_grand_total, 2, '.', ',') }}</td>
                                        <td>
                                            @php
                                                // ดึงข้อมูลการชำระเงินล่าสุดจาก paymentWholesale
                                                $latestPayment = $item->paymentWholesale()->latest('payment_wholesale_id')->first();
                                            @endphp
                                        
                                            @if (!$latestPayment || $latestPayment->payment_wholesale_type === null)
                                                <!-- กรณีที่ไม่มีข้อมูลใน paymentWholesale หรือ payment_wholesale_type เป็น NULL -->
                                                <span class="badge rounded-pill bg-primary">รอชำระเงิน</span>
                                            @elseif ($latestPayment->payment_wholesale_type === 'deposit')
                                                <!-- กรณีที่เป็น deposit -->
                                                <span class="badge rounded-pill bg-primary">รอชำระเงินเต็มจำนวน</span>
                                            @elseif ($latestPayment->payment_wholesale_type === 'full')
                                                <!-- กรณีที่เป็น full -->
                                                <span class="badge rounded-pill bg-success">ชำระเงินแล้ว</span>
                                            @endif
                                        </td>
                                        
                                        
                                        

                                        <td> {{ $item->Salename->name }}</td>
                                        <td><a href="{{ route('quote.editNew', $item->quote_id) }}"
                                                class="btn btn-info btn-sm">จัดการข้อมูล</a></td>

                                    </tr>
                                @empty
                                    No data
                                @endforelse

                            </tbody>
                        </table>
                        {!! $quotations->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>





    @endsection
