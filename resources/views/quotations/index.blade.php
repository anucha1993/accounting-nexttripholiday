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

       


        <div class="card">
            <div class="card-body">
                <h4 class="card-title">ใบเสนอราคา/ใบแจ้งหนี้
                    @can('edit-quote')
                     <a href="{{ route('quote.createNew') }}"
                        class="btn btn-primary float-end">สร้างใบเสนอราคา</a></h4>
                        @endcan
                <hr>

                <form action="">
                    <input type="hidden" name="search" value="Y">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>คีย์เวิร์ด</label>
                            <input type="hidden" name="check" value="Y">
                            <input type="text" class="form-control" name="search_keyword" value="{{$request->search_keyword}}" placeholder="คียร์เวิร์ด" data-bs-toggle="tooltip" data-bs-placement="top" title="ชื่อแพคเกจทัวร์,เลขที่ใบเสนอราคา,เลขที่ใบแจ้งหนี้,ชื่อลูกค้า,เลขที่ใบจองทัวร์,ใบกำกับภาษีของโฮลเซลล์,เลขที่ใบหัก ณ ที่จ่ายของลูกค้า"> 
                        </div>
                        <div class="col-md-2">
                            <label>Booking Date </label>
                            <input type="date" class="form-control" value="{{$request->search_booking_start}}" name="search_booking_start" >
                        </div>
                        <div class="col-md-2">
                            <label>ถึงวันที่ </label>
                            <input type="date" class="form-control" value="{{$request->search_booking_end}}" name="search_booking_end" >
                        </div>
                        <div class="col-md-2">
                            <label>ช่วงวันเดินทาง</label>
                            <input type="date" class="form-control" value="{{$request->search_period_start}}" name="search_period_start" >
                        </div>
                        <div class="col-md-2 ">
                            <label>ถึงวันที่</label>
                            <input type="date" class="form-control" value="{{$request->search_period_end}}" name="search_period_end" >
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>ประเทศ</label>
                            <select name="search_country" id="country" class="form-select select2" style="width: 100%">
                                <option value="all">ทั้งหมด</option>
                                @forelse ($country as $item)
                                <option {{ request('search_country') == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->country_name_th }}</option>
                            @empty
                                <option value="" disabled>ไม่มีข้อมูล</option>
                            @endforelse
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>โฮลเซลล์:</label>
                            <select name="search_wholesale" class="form-select select2" style="width: 100%">
                                <option value="all">ทั้งหมด</option>
                                    @forelse ($wholesales as $item)
                                        <option  {{ request('search_wholesale') == $item->id ? 'selected' : '' }} value="{{ $item->id }}">
                                            {{ $item->wholesale_name_th }}</option>
                                    @empty
                                        <option value="" disabled>ไม่มีข้อมูล</option>
                                    @endforelse
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>สถานะชำระโฮลเซลล์</label>
                            <select name="search_wholesale_payment" class="form-select">
                                <option value="all" {{ request('search_wholesale_payment') === 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                                <option value="NULL" {{ request('search_wholesale_payment') === 'NULL' ? 'selected' : '' }}>รอชำระเงิน</option>
                                <option value="deposit" {{ request('search_wholesale_payment') == 'deposit' ? 'selected' : '' }}>รอชำระเงินเต็มจำนวน</option>
                                <option value="full" {{ request('search_wholesale_payment') == 'full' ? 'selected' : '' }}>ชำระเงินครบแล้ว</option>
                                <option value="wait-payment-wholesale" {{ request('search_wholesale_payment') == 'wait-payment-wholesale' ? 'selected' : '' }}>รอโฮลเซลล์คืนเงิน</option>
                            </select>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>สถานะการชำระของลูกค้า</label>
                            <select name="search_customer_payment" class="form-select" style="width: 100%">
                                <option {{ request('search_customer_payment') === 'all' ? 'selected' : '' }} value="all">ทั้งหมด</option>
                                <option {{ request('search_customer_payment') === 'รอคืนเงิน' ? 'selected' : '' }} value="รอคืนเงิน">รอคืนเงิน</option>
                                <option {{ request('search_customer_payment') === 'รอชำระเงินมัดจำ' ? 'selected' : '' }} value="รอชำระเงินมัดจำ">รอชำระเงินมัดจำ</option>
                                <option {{ request('search_customer_payment') === 'รอชำระเงินเต็มจำนวน' ? 'selected' : '' }} value="รอชำระเงินเต็มจำนวน">รอชำระเงินเต็มจำนวน</option>
                                <option {{ request('search_customer_payment') === 'ชำระเงินครบแล้ว' ? 'selected' : '' }} value="ชำระเงินครบแล้ว">ชำระเงินครบแล้ว</option>
                                <option {{ request('search_customer_payment') === 'เกินกำหนดชำระเงิน' ? 'selected' : '' }} value="เกินกำหนดชำระเงิน">เกินกำหนดชำระเงิน</option>
                                <option {{ request('search_customer_payment') === 'ยกเลิกการสั่งซื้อ' ? 'selected' : '' }} value="ยกเลิกการสั่งซื้อ">ยกเลิกการสั่งซื้อ</option>

                            </select>
                        </div>

                       
                        
                        <div class="col-md-2">
                            <label>เซลล์ผู้ขาย</label>
                            <select name="search_sale" class="form-select">
                                <option value="all">ทั้งหมด</option>
                                @forelse ($sales as $item)
                                    <option  {{ request('search_sale') == $item->id  ? 'selected' : '' }} value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @empty
                                    <option value="" disabled>ไม่มีข้อมูล</option>
                                @endforelse
                            </select>

                        </div>
                    </div>
                        <div class="row mt-3">
                            {{-- <div class="col-md-2">
                                <label for="">เอกสารโฮลล์</label>
                                <select name="search_doc_wholesale" class="form-select">
                                    <option value="all">ทังหมด</option>
                                    <option value="Y">ได้รับแล้ว</option>
                                    <option value="N">ยั้งไม่ได้รับ</option>
                                </select>
                            </div> --}}
                            <div class="col-md-2">
                                <label for="">AIRLINE</label>
                               <select name="search_airline" class="form-select select2" style="width: 100%" >
                                <option value="all">ทั้งหมด</option>
                                @forelse ($airlines as $airline)
                                    <option  {{ request('search_airline') == $airline->id  ? 'selected' : '' }} value="{{$airline->id}}">{{$airline->travel_name}}</option>
                                @empty
                                    
                                @endforelse
                               </select>
                            </div>
                            {{-- <div class="col-md-2">
                                <label>จำนวน (PAX)</label>
                                <input type="number" class="form-control" value="{{$request->search_pax}}" name="search_pax" placeholder="ไม่ระบุ">
                            </div> --}}

                        <div class="col-md-2">
                            <label for="">Check List</label>
                            <select name="search_check_list" class="form-select" style="width: 100%">
                                <option {{ request('search_check_list') === 'all' ? 'selected' : '' }} value="all">ทั้งหมด</option>
                                <option {{ request('search_check_list') === 'allCheck' ? 'selected' : '' }} value="allCheck">ทำหมดแล้ว</option>
                                <option {{ request('search_check_list') === 'booking_email_status' ? 'selected' : '' }} value="booking_email_status">ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</option>
                                <option {{ request('search_check_list') === 'invoice_status' ? 'selected' : '' }} value="invoice_status">อินวอยโฮลเซลล์</option>
                                <option {{ request('search_check_list') === 'slip_status' ? 'selected' : '' }} value="slip_status">ส่งสลิปให้โฮลเซลล์</option>
                                <option {{ request('search_check_list') === 'passport_status' ? 'selected' : '' }} value="passport_status">ส่งพาสปอตให้โฮลเซลล์</option>
                                <option {{ request('search_check_list') === 'appointment_status' ? 'selected' : '' }} value="appointment_status">ส่งใบนัดหมายให้ลูกค้า</option>
                                <option {{ request('search_check_list') === 'withholding_tax_status' ? 'selected' : '' }} value="withholding_tax_status">ออกใบหัก ณ ที่จ่าย</option>
                                <option {{ request('search_check_list') === 'wholesale_tax_status' ? 'selected' : '' }} value="wholesale_tax_status">ใบกำกับภาษีโฮลเซลล์</option>
         
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="">ยังไม่ได้ทำ Check List</label>
                            <select name="search_not_check_list" class="form-select" style="width: 100%">
                                <option {{ request('search_not_check_list') === 'all' ? 'selected' : '' }} value="all">ทั้งหมด</option>
                                <option {{ request('search_not_check_list') === 'booking_email_status' ? 'selected' : '' }} value="booking_email_status">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</option>
                                <option {{ request('search_not_check_list') === 'invoice_status' ? 'selected' : '' }} value="invoice_status">ยังไม่ได้อินวอยโฮลเซลล์</option>
                                <option {{ request('search_not_check_list') === 'slip_status' ? 'selected' : '' }} value="slip_status">ยังไม่ส่งสลิปให้โฮลเซลล์</option>
                                <option {{ request('search_not_check_list') === 'passport_status' ? 'selected' : '' }} value="passport_status">ยังไม่ส่งพาสปอตให้โฮลเซลล์</option>
                                <option {{ request('search_not_check_list') === 'appointment_status' ? 'selected' : '' }} value="appointment_status">ยังไม่ส่งใบนัดหมายให้ลูกค้า</option>
                                <option {{ request('search_not_check_list') === 'withholding_tax_status' ? 'selected' : '' }} value="withholding_tax_status">ยังไม่ออกใบหัก ณ ที่จ่าย</option>
                                <option {{ request('search_not_check_list') === 'wholesale_tax_status' ? 'selected' : '' }} value="wholesale_tax_status">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</option>
                            </select>
                        </div>

                        </div>
                        <div class="row ">
                        
                            <div class="input-group-append">
                                <button class="btn btn-outline-success float-end mx-3" type="submit">ค้นหา</button>
                                <a href="{{ route('quote.index') }}" class="btn btn-outline-danger float-end mx-3"
                                    type="submit">ล้างข้อมูล</a>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
           
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        {{-- <form method="GET"  class="mb-3" id="page">
                            <label for="per_page">แสดงจำนวน:</label>
                            <select name="per_page" id="per_page" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                <option value="50" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                                <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
                            </select>
                        </form> --}}

                        <form action="{{route('export.quote')}}" id="export-excel" method="post">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="quote_ids" value="{{$quotations->pluck('quote_id')}}">
                            <button class="btn btn-success" type="submit">EXCEL</button>
                        </form>
                        <br>
                        <table class="table customize-table table-hover mb-0 v-middle table-striped table-bordered" id="quote-table"
                            style="font-size: 12px">
                            <thead class="table text-white bg-info">
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>ใบเสนอราคา</th>
                                        <th>เลขที่ใบจองทัวร์</th>
                                        <th>โปรแกรมทัวร์</th>
                                        <th>Booking Date</th>
                                        <th>วันที่เดินทาง</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>Pax</th>
                                        <th>ประเทศ</th>
                                        <th>สายการบิน</th>
                                        <th>โฮลเซลล์</th>
                                        <th>การชำระของลูกค้า</th>
                                        <th>ยอดใบแจ้งหนี้</th>
                                        <th>การชำระโฮลเซลล์</th>
                                        <th>CheckLists</th>
                                        <th>ผู้ขาย</th>
                                        <th>การจัดการ</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @forelse ($quotations as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->quote_number }} {!!$item->debitNote ? '<span class="badge rounded-pill bg-success">DBN</span>' : ''!!} {!!$item->creditNote ? '<span class="badge rounded-pill bg-danger">CDN</span>' : ''!!} </td>
                                        <td>{{ $item->quote_booking }}</td>
                                        <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ $item->quote_tour_name ? $item->quote_tour_name : $item->quote_tour_name1 }}">{{ $item->quote_tour_name ? mb_substr($item->quote_tour_name, 0, 20) . '...' : mb_substr($item->quote_tour_name1, 0, 20) . '...' }}</span>
                                    </td>
                                        <td>{{ date('d/m/Y', strtotime($item->created_at))}}</td>
                                        <td>{{ date('d/m/Y', strtotime($item->quote_date_start)) . '-' . date('d/m/Y', strtotime($item->quote_date_end)) }}
                                        </td>
                                        <td>{{ $item->quotecustomer->customer_name }}</td>
                                        <td>{{$item->quote_pax_total}}</td>
                                        <td>{{ $item->airline->code }}</td>
                                        <td>
                                            {{$item->quoteCountry->country_name_th}}
                                        </td>
                                        <td>{{ $item->quoteWholesale->code }}</td>
                                        
                                        <td>
                                            {!! getQuoteStatusPayment($item) !!}
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

                                        {{-- <td>
                                            @if ($item->quoteLogStatus->booking_email_status === 'ยังไม่ได้ส่ง' || $item->quoteLogStatus->booking_email_status === NULL )
                                            <span class="badge rounded-pill bg-danger">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์</span>
                                            @endif

                                            @if ($item->quoteLogStatus->invoice_status !== 'ได้แล้ว' || $item->quoteLogStatus->invoice_status ===  NULL)
                                            <span class="badge rounded-pill bg-danger">ยังไม่ได้อินวอยโฮลเซลล์</span>
                                            @endif

                                            @if ($item->quoteLogStatus->slip_status === 'ยังไม่ได้ส่ง' || $item->quoteLogStatus->slip_status === NULL)
                                            <span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งสลิปให้โฮลเซลล์</span>
                                            @endif

                                            @if ($item->quoteLogStatus->passport_status === 'ยังไม่ได้ส่ง' || $item->quoteLogStatus->passport_status === NULL)
                                            <span class="badge rounded-pill bg-danger">ยังไม่ได้ส่งพาสปอตให้โฮลเซลล์</span>
                                            @endif

                                            @if ($item->quoteLogStatus->appointment_status === 'ยังไม่ได้ส่ง' || $item->quoteLogStatus->appointment_status === NULL)
                                            <span class="badge rounded-pill bg-danger">ส่งใบนัดหมายให้ลูกค้า</span>
                                            @endif

                                            @if ($item->quoteLogStatus->wholesale_tax_status !== 'ได้รับแล้ว' || $item->quoteLogStatus->wholesale_tax_status === NULL)
                                            <span class="badge rounded-pill bg-danger">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</span>
                                            @endif

                                            @if ($item->quoteLogStatus->wholesale_skip_status !== 'ไม่ต้องการออก')

                                            @if ($item->quoteLogStatus->withholding_tax_status === 'ได้รับแล้ว' || $item->quoteLogStatus->withholding_tax_status === NULL)
                                            <span class="badge rounded-pill bg-danger">ยังไม่ได้ออกใบหัก ณ ที่จ่าย</span>
                                            @endif
                                                
                                            @else
                                                
                                            @endif

                                            

                                        </td> --}}

                                        <td>
                                            {!! \App\CustomHelpers\getStatusBadge($item->quoteLogStatus) !!}
                                        </td>

                                        <td> {{ $item->Salename->name }}</td>
                                        <td><a href="{{ route('quote.editNew', $item->quote_id) }}"
                                                class="btn btn-info btn-sm">จัดการข้อมูล</a>
                                                
                                            </td>
                                    </tr>
                                    
                                @empty
                                    No data
                                @endforelse
                                <tr>
                                    <td class="text-success">ข้อมูลผลรวม</td>
                                    <td class="text-danger" colspan="12" align="right"> จำนวน {{number_format($SumPax)}} (PAX) | จำนวนมูลค่าใบเสนอราคา {{number_format($SumTotal,2)}} บาท </td>
                                </tr>

                            </tbody>
                        </table>
                        {{-- {!! $quotations->withQueryString()->links('pagination::bootstrap-5') !!} --}}
                    </div>
                </div>
            </div>
        </div>


    @endsection
    
    