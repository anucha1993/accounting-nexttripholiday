@extends('layouts.template')

@section('content')
    <style>
        .table-sm td,
        .table-sm th {
            padding: 0.4rem;
            vertical-align: middle;
        }
        .badge-sm {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
        }
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .table-dark th {
            border-color: #495057;
            font-size: 11px;
            font-weight: 600;
        }
        .text-truncate-custom {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 150px;
        }
        .status-badges .badge {
            margin: 1px;
            display: inline-block;
        }
        .quote-summary {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            padding: 0.5rem;
        }
        /* Responsive table: force horizontal scroll on mobile */
        @media (max-width: 767.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table th, .table td {
                white-space: nowrap;
                font-size: 12px;
            }
            .btn, .form-select, .form-control {
                font-size: 13px !important;
            }
            .table thead th {
                font-size: 13px;
            }
            .select2-container .select2-selection--single {
                font-size: 13px;
            }
        }
        /* Make filter/search section stack vertically on mobile */
        @media (max-width: 767.98px) {
            .row.mb-3 > [class^="col-md-"] {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 0.5rem;
            }
            .d-flex.justify-content-between.align-items-center.mb-3 > div,
            .d-flex.align-items-center.gap-2 {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
            }
        }
    </style>
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
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice"></i> ใบเสนอราคา/ใบแจ้งหนี้
                    </h5>
                  
                    @can('quote.create')
                        <a href="{{ route('quote.createModern') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> สร้างใบเสนอราคา
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="collapse hidden" id="searchCollapse">
                    <form action="" class="border rounded p-3 bg-light mb-3">
                        <input type="hidden" name="search" value="Y">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label>คีย์เวิร์ด</label>
                                <input type="hidden" name="check" value="Y">
                                <input type="text" class="form-control" name="search_keyword"
                                    value="{{ $request->search_keyword }}" placeholder="คียร์เวิร์ด"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="ชื่อแพคเกจทัวร์,เลขที่ใบเสนอราคา,เลขที่ใบแจ้งหนี้,ชื่อลูกค้า,เลขที่ใบจองทัวร์,ใบกำกับภาษีของโฮลเซลล์,เลขที่ใบหัก ณ ที่จ่ายของลูกค้า">
                            </div>
                            <div class="col-md-2">
                                <label><i class="fas fa-calendar me-1"></i>ช่วงเวลา (Booking Date)</label>
                                <input type="text" name="daterange" id="rangDate" class="form-control rangDate"
                                    autocomplete="off" value="{{ request('daterange') }}" placeholder="เลือกช่วงวันที่" />
                                <input type="hidden" name="search_booking_start"
                                    value="{{ request('search_booking_start') }}">
                                <input type="hidden" name="search_booking_end" value="{{ request('search_booking_end') }}">
                            </div>

                            <div class="col-md-2">
                                <label><i class="fas fa-calendar me-1"></i>ช่วงวันเดินทาง</label>
                                <input type="text" name="period_daterange" id="periodRangDate"
                                    class="form-control periodRangDate" autocomplete="off"
                                    value="{{ request('period_daterange') }}" placeholder="เลือกช่วงวันเดินทาง" />
                                <input type="hidden" name="search_period_start"
                                    value="{{ request('search_period_start') }}">
                                <input type="hidden" name="search_period_end" value="{{ request('search_period_end') }}">
                            </div>
                            <div class="col-md-2">
                                <label>ประเทศ</label>
                                <select name="search_country" id="country" class="form-select select2"
                                    style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    @forelse ($country as $item)
                                        <option {{ request('search_country') == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->iso2 }}-{{ $item->country_name_th }}</option>
                                    @empty
                                        <option value="" disabled>ไม่มีข้อมูล</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">AIRLINE</label>
                                <select name="search_airline" class="form-select select2" style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    @forelse ($airlines as $airline)
                                        <option {{ request('search_airline') == $airline->id ? 'selected' : '' }}
                                            value="{{ $airline->id }}">{{ $airline->code }}-{{ $airline->travel_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>โฮลเซลล์:</label>
                                <select name="search_wholesale" class="form-select select2" style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    @forelse ($wholesales as $item)
                                        <option {{ request('search_wholesale') == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">
                                            {{ $item->code }}-{{ $item->wholesale_name_th }}</option>
                                    @empty
                                        <option value="" disabled>ไม่มีข้อมูล</option>
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>เซลล์ผู้ขาย</label>
                                <select name="search_sale" class="form-select">
                                    <option value="all">ทั้งหมด</option>
                                    @forelse ($sales as $item)
                                        <option {{ request('search_sale') == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">
                                            {{ $item->name }}</option>
                                    @empty
                                        <option value="" disabled>ไม่มีข้อมูล</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>ที่มาของลูกค้า</label>
                                <select name="search_campaign_source" class="form-select select2" style="width: 100%">
                                    <option value="all">ทั้งหมด</option>
                                    @foreach ($campaignSource as $source)
                                        <option value="{{ $source->campaign_source_id }}"
                                            {{ request('search_campaign_source') == $source->campaign_source_id ? 'selected' : '' }}>
                                            {{ $source->campaign_source_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>





                            <div class="col-md-2">
                                <label>สถานะการชำระของลูกค้า</label>
                                <select name="search_customer_payment" class="form-select" style="width: 100%">
                                    <option {{ request('search_customer_payment') == 'all' ? 'selected' : '' }}
                                        value="all">ทั้งหมด</option>
                                    <option {{ request('search_customer_payment') == 'รอชำระเงินมัดจำ' ? 'selected' : '' }}
                                        value="รอชำระเงินมัดจำ">รอชำระเงินมัดจำ</option>
                                    <option
                                        {{ request('search_customer_payment') == 'รอชำระเงินเต็มจำนวน' ? 'selected' : '' }}
                                        value="รอชำระเงินเต็มจำนวน">รอชำระเงินเต็มจำนวน</option>
                                    <option {{ request('search_customer_payment') == 'ชำระเงินครบแล้ว' ? 'selected' : '' }}
                                        value="ชำระเงินครบแล้ว">ชำระเงินครบแล้ว</option>
                                    <option
                                        {{ request('search_customer_payment') == 'เกินกำหนดชำระเงิน' ? 'selected' : '' }}
                                        value="เกินกำหนดชำระเงิน">เกินกำหนดชำระเงิน</option>
                                    <option {{ request('search_customer_payment') == 'ชำระเงินเกิน' ? 'selected' : '' }}
                                        value="ชำระเงินเกิน">ชำระเงินเกิน</option>
                                    <option
                                        {{ request('search_customer_payment') == 'ยกเลิกการสั่งซื้อ' ? 'selected' : '' }}
                                        value="ยกเลิกการสั่งซื้อ">ยกเลิกการสั่งซื้อ</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="">สถานะลูกค้าชำระเงินเกิน (AutoCheck)</label>
                                <select name="search_payment_overpays" class="form-select" style="width: 100%">
                                    <option {{ request('search_payment_overpays') == 'all' ? 'selected' : '' }}
                                        value="all">ทั้งหมด</option>
                                    <option {{ request('search_payment_overpays') == 'รอคืนเงินลูกค้า' ? 'selected' : '' }}
                                        value="รอคืนเงินลูกค้า">รอคืนเงินให้ลูกค้า</option>
                                    <option
                                        {{ request('search_payment_overpays') == 'คืนเงินให้ลูกค้าแล้ว' ? 'selected' : '' }}
                                        value="คืนเงินให้ลูกค้าแล้ว">คืนเงินให้ลูกค้าแล้ว</option>
                                    <option
                                        {{ request('search_payment_overpays') == 'รอใบหัก จากลูกค้า' ? 'selected' : '' }}
                                        value="รอใบหัก จากลูกค้า">รอใบหัก จากลูกค้า</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>สถานะชำระโฮลเซลล์</label>
                                <select name="search_wholesale_payment" class="form-select">
                                    <option {{ request('search_wholesale_payment') == 'all' ? 'selected' : '' }}
                                        value="all">ทั้งหมด</option>
                                    <option {{ request('search_wholesale_payment') == '5' ? 'selected' : '' }}
                                        value="5">รอชำระมัดจำโฮลเซลล์</option>
                                    <option {{ request('search_wholesale_payment') == '1' ? 'selected' : '' }}
                                        value="1">รอชำระส่วนที่เหลือ</option>
                                    <option {{ request('search_wholesale_payment') == '2' ? 'selected' : '' }}
                                        value="2">ชำระครบแล้ว</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">สถานะชำระเงินโฮลเซลเกิน(AutoCheck)</label>
                                <select name="search_payment_wholesale_overpays" class="form-select" style="width: 100%">
                                    <option {{ request('search_payment_wholesale_overpays') == 'all' ? 'selected' : '' }}
                                        value="all">ทั้งหมด</option>
                                    <option
                                        {{ request('search_payment_wholesale_overpays') == 'โอนเงินให้โฮลเซลล์เกิน' ? 'selected' : '' }}
                                        value="โอนเงินให้โฮลเซลล์เกิน">โอนเงินให้โฮลเซลล์เกิน</option>
                                    <option
                                        {{ request('search_payment_wholesale_overpays') == 'รอโฮลเซลล์คืนเงิน' ? 'selected' : '' }}
                                        value="รอโฮลเซลล์คืนเงิน">รอโฮลเซลล์คืนเงิน</option>
                                    <option
                                        {{ request('search_payment_wholesale_overpays') == 'โฮลเซลล์คืนเงินแล้ว' ? 'selected' : '' }}
                                        value="โฮลเซลล์คืนเงินแล้ว">โฮลเซลล์คืนเงินแล้ว</option>
                                </select>
                            </div>



                            <div class="col-md-2">
                                <label for="">ยังไม่ได้ทำ Check List</label>
                                <select name="search_not_check_list" class="form-select" style="width: 100%">
                                    <option {{ request('search_not_check_list') == 'all' ? 'selected' : '' }}
                                        value="all">ทั้งหมด</option>
                                    <option
                                        {{ request('search_not_check_list') == 'ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์' ? 'selected' : '' }}
                                        value="ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์">ยังไม่ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์
                                    </option>
                                    <option
                                        {{ request('search_not_check_list') == 'ยังไม่ได้อินวอยโฮลเซลล์' ? 'selected' : '' }}
                                        value="ยังไม่ได้อินวอยโฮลเซลล์">ยังไม่ได้อินวอยโฮลเซลล์</option>
                                    <option
                                        {{ request('search_not_check_list') == 'ยังไม่ได้ส่งสลิปให้โฮลเซลล์' ? 'selected' : '' }}
                                        value="ยังไม่ได้ส่งสลิปให้โฮลเซลล์">ยังไม่ได้ส่งสลิปให้โฮลเซลล์</option>
                                    <option
                                        {{ request('search_not_check_list') == 'ยังไม่ได้ส่งพาสปอตให้โฮลเซลล์' ? 'selected' : '' }}
                                        value="ยังไม่ได้ส่งพาสปอตให้โฮลเซลล์">ยังไม่ได้ส่งพาสปอตให้โฮลเซลล์</option>
                                    <option
                                        {{ request('search_not_check_list') == 'ส่งใบนัดหมายให้ลูกค้า' ? 'selected' : '' }}
                                        value="ส่งใบนัดหมายให้ลูกค้า">ส่งใบนัดหมายให้ลูกค้า</option>
                                    <option
                                        {{ request('search_not_check_list') == 'ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์' ? 'selected' : '' }}
                                        value="ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์">ยังไม่ได้รับใบกำกับภาษีโฮลเซลล์</option>
                                         <option
                                        {{ request('search_not_check_list') == 'ยังไม่ได้ออกใบหัก.ณ.ที่จ่ายโฮลเซลล์' ? 'selected' : '' }}
                                        value="ยังไม่ได้ออกใบหัก.ณ.ที่จ่ายโฮลเซลล์">ยังไม่ได้ออกใบหัก.ณ.ที่จ่ายโฮลเซลล์</option>

                                </select>
                            </div>
                            {{-- 
                            <div class="col-md-2">
                                <label for="">ส่วนที่ต้องตาม</label>
                                <select name="search_followed" class="form-select" style="width: 100%">
                                    <option  {{ request('search_followed') == 'รอใบกำกับภาษีโฮลเซลล์' ? 'selected' : '' }} value="รอใบกำกับภาษีโฮลเซลล์  ">รอใบกำกับภาษีโฮลเซลล์  </option>
                                </select>
                            </div> --}}








                        </div>

                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="fas fa-search"></i> ค้นหา
                                    </button>
                                    <a href="{{ route('quotelist.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> ล้างข้อมูล
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="mb-3">
                    <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse"
                        data-bs-target="#searchCollapse" aria-expanded="true">
                        <i class="fas fa-filter"></i> แสดง/ซ่อน ตัวกรอง
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">


                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap flex-md-nowrap">
                        <div class="mb-2 mb-md-0">
                            @can('report.salesreport.export')
                                <form action="{{ route('export.quote') }}" id="export-excel" method="post"
                                    class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="quote_ids" id="export-quote-ids"
                                        value="{{ $quotations->pluck('quote_id') }}">
                                    <button class="btn btn-success btn-sm mb-1 mb-md-0" type="submit">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </form>
                            @endcan
                            @can('report.salesreport.view')
                            <a href="{{ route('quotelist.index') }}" class="btn btn-info btn-sm ms-0 ms-md-2 mt-1 mt-md-0">
                                <i class="fas fa-chart-bar"></i> รายงานใบเสนอราคา
                            </a>
                            @endcan

                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap flex-md-nowrap">
                            <form method="GET" id="page" class="mb-0">
                                <label for="per_page" class="me-1">แสดงจำนวน:</label>
                                <select name="per_page" id="per_page"
                                    class="form-select form-select-sm d-inline-block w-auto"
                                    onchange="this.form.submit()">
                                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    <option value="150" {{ request('per_page') == 150 ? 'selected' : '' }}>150</option>
                                    <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                                </select>
                                @foreach (request()->except('per_page', 'page') as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach
                            </form>
                            <div class="text-muted ms-0 ms-md-2 mt-1 mt-md-0">
                                <small>พบข้อมูล {{ number_format($quotations->total()) }} รายการ | รวม
                                    {{ number_format($SumPax) }} PAX | มูลค่า {{ number_format($SumTotal, 2) }}
                                    บาท</small>
                            </div>
                        </div>
                    </div>
                    

                    {!! $quotations->withQueryString()->links('pagination::bootstrap-5') !!}
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped table-bordered" id="quote-table"
                            style="font-size: 11px;">
                            <thead class="table-dark sticky-top" >
                                <tr>
                                    <th style="width: 40px;" class="text-center">#</th>
                                    <th style="width: 80px;">วันที่จอง</th>
                                    <th style="width: 80px;">ใบเสนอราคา</th>
                                    <th style="width: 100px;">เลขจองทัวร์</th>
                                    <th style="width: 200px;">โปรแกรมทัวร์</th>
                                    <th style="width: 120px;">วันเดินทาง</th>
                                    <th style="width: 170px;">ลูกค้า</th>
                                    <th style="width: 120px;">ที่มา</th> <!-- New column -->
                                    <th style="width: 50px;" class="text-center">PAX</th>
                                    <th style="width: 80px;">ประเทศ</th>
                                    <th style="width: 60px;">สายการบิน</th>
                                    <th style="width: 80px;">โฮลเซลล์</th>
                                    <th style="width: 120px;">สถานะลูกค้า</th>
                                    <th style="width: 100px;" class="text-end">ยอดเงิน</th>
                                    <th style="width: 120px;">สถานะโฮลเซลล์</th>
                                    <th style="width: 100px;">ค้างชำระโฮลเซล</th>
                                    <th style="width: 100px;">CheckList</th>
                                    <th style="width: 80px;">ผู้ขาย</th>
                                    <th style="width: 80px;" class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $inputtaxTotalWholesale = 0;
                                @endphp
                                @forelse ($quotations as $key => $item)
                                    <tr class="align-middle" data-quote-id="{{ $item->quote_id }} " {{ $item->quote_commission == 'N' ? 'style=background-color:#f8d7da' : '' }}>
                                        <td class="text-center fw-bold">
                                            {{ $quotations->total() - $quotations->firstItem() + 1 - $key }}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="text-muted">{{ date('d/m/y', strtotime($item->quote_booking_create)) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-primary">{{ $item->quote_number }}</span>
                                                <div>
                                                    @if ($item->quote_commission == 'N')
                                                        <span class="badge bg-danger badge-sm">N</span>
                                                        
                                                    @endif

                                                    @if ($item->debitNote)
                                                        <span class="badge bg-success badge-sm">DBN</span>
                                                    @endif
                                                    @if ($item->creditNote)
                                                        <span class="badge bg-danger badge-sm">CDN</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $item->quote_booking }}</span>
                                        </td>
                                        <td>
                                            <div data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $item->quote_tour_name ?: $item->quote_tour_name1 }}">
                                                {{ mb_substr($item->quote_tour_name ?: $item->quote_tour_name1, 0, 25) }}
                                                {{ strlen($item->quote_tour_name ?: $item->quote_tour_name1) > 25 ? '...' : '' }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            
                                            <div class="d-flex flex-column">
                                                <span>{{ date('d/m/Y', strtotime($item->quote_date_start)) }}</span> 
                                                ถึง
                                                <span
                                                    class="text-muted">{{ date('d/m/y', strtotime($item->quote_date_end)) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div data-bs-toggle="tooltip" title="">
                                                {{ mb_substr($item->quotecustomer->customer_name, 0, 20) }}{{ mb_strlen($item->quotecustomer->customer_name) > 20 ? '...' : '' }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $sourceName = '';
                                                if (
                                                    isset($item->quotecustomer->customer_campaign_source) &&
                                                    !empty($item->quotecustomer->customer_campaign_source) &&
                                                    isset($campaignSource)
                                                ) {
                                                    $source = $campaignSource->firstWhere(
                                                        'campaign_source_id',
                                                        $item->quotecustomer->customer_campaign_source,
                                                    );
                                                    $sourceName = $source ? $source->campaign_source_name : '';
                                                }
                                            @endphp
                                            {{ $sourceName ?: 'none' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $item->quote_pax_total }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $item->quoteCountry->country_name_th }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary badge-sm">{{ $item->airline->code }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-warning text-dark badge-sm">{{ $item->quoteWholesale->code }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                {!! getQuoteStatusPayment($item) !!}
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <strong
                                                class="text-success">{{ number_format($item->quote_grand_total, 0) }}</strong>
                                            <span class="text-muted d-block">บาท</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                {!! getStatusPaymentWhosale($item) !!}
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            @php
                                                $totalWholesale = $item->inputtaxTotalWholesale() ?? 0;
                                                $wholesalePaid =
                                                    $item->GetDepositWholesale() - $item->GetDepositWholesaleRefund();
                                                $wholesaleOutstanding = $totalWholesale - $wholesalePaid;
                                            @endphp
                                            @if ($totalWholesale != 0 && $wholesaleOutstanding != 0)
                                                @php
                                                    $inputtaxTotalWholesale += $wholesaleOutstanding;
                                                @endphp

                                                <span
                                                    class="text-danger fw-bold">{{ number_format($wholesaleOutstanding, 2) }}</span>
                                            @elseif ($totalWholesale == 0)
                                                <span class="text-muted">ยังไม่มีต้นทุน</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif


                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1" style="max-width: 100px;">
                                              {{-- {!! getStatusBadge($item->quoteCheckStatus, $item) !!} --}}
                                                @php
                                                    $badgeCount = getStatusBadgeCount($item->quoteCheckStatus, $item);
                                                @endphp
                                                @if ($badgeCount > 0)
                                                <span class="badge rounded-pill bg-danger">ยังไม่ได้ทำ {{ $badgeCount }} รายการ</span>
                                             
                                                @endif

                                                {!! getQuoteStatusQuotePayment($item) !!}
                                                {!! getStatusWithholdingTax($item->quoteInvoice) !!}
                                                {!! getQuoteStatusWithholdingTax($item->quoteLogStatus) !!}


                                                {!! getStatusWhosaleInputTax($item->checkfileInputtax) !!}
                                                {!! getStatusCustomerRefund($item->quoteLogStatus) !!}

                                                {!! getStatusWholesaleRefund($item->quoteLogStatus) !!}


                                                {{-- สามารถเพิ่ม helper อื่นๆ ที่เกี่ยวข้องกับ CheckList ได้ที่นี่ --}}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span>{{ $item->Salename->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            @canany(['quote.view', 'quote.edit'])
                                                <a href="{{ route('quote.editNew', $item->quote_id) }}"
                                                    class="btn btn-primary btn-sm mb-1" data-bs-toggle="tooltip"
                                                    title="จัดการข้อมูล">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcanany

                                            @can('quote.delete')
                                                <form action="{{ route('quotelist.destroy', $item->quote_id) }}"
                                                    method="POST" style="display:inline-block;"
                                                    onsubmit="return confirm('ยืนยันการลบข้อมูลใบเสนอราคานี้?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm " title="ลบข้อมูล">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <br>
                                            @endcan

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="17" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-search fa-2x mb-3"></i>
                                                <p>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="8" class="text-end fw-bold">สรุปรวม:</td>
                                    <td class="text-center fw-bold text-primary">{{ number_format($SumPax) }}</td>
 
                                    <td colspan="5" class="text-end fw-bold text-success">{{ number_format($SumTotal, 2) }}</td>
                                    <td colspan="1" class="text-muted"><small>บาท</small></td>
                                     <td colspan="2" class="text-end fw-bold text-danger">

                                        ค้างชำระโฮลเซล : {{ number_format($inputtaxTotalWholesale, 2) }} บาท
                                    </td>
                                   
                                   
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // === ลบ Client-side CheckList Filter ออก ใช้ backend filter เท่านั้น ===
            // $('[data-bs-toggle="tooltip"]').tooltip();
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('.select2').select2({
                placeholder: 'เลือก...',
                allowClear: true,
                width: '100%'
            });
            $('select[name^="search_"]').on('change', function() {
                if ($(this).val() !== 'all' && $(this).val() !== '') {
                    $(this).closest('form').submit();
                }
            });
            $('select[name="search_customer_payment"]').on('change', function() {
                if ($(this).val() === 'all') {
                    $('select[name^="search_"]').not(this).val('all');
                }
                $(this).closest('form').submit();
            });
            var searchKeyword = $('input[name="search_keyword"]').val();
            if (searchKeyword) {
                $('#quote-table tbody').highlight(searchKeyword, {
                    className: 'bg-warning'
                });
            }
            $('#quote-table tbody tr').on('click', function(e) {
                if (!$(e.target).closest('a, button').length) {
                    var editLink = $(this).find('a[href*="editNew"]').attr('href');
                    if (editLink) {
                        window.location.href = editLink;
                    }
                }
            });
            $('form').on('submit', function() {
                var submitBtn = $(this).find('button[type="submit"]');
                var originalText = submitBtn.html();
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> กำลังค้นหา...');
                submitBtn.prop('disabled', true);
                setTimeout(function() {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }, 3000);
            });
            // daterangepicker แบบ saletax-form
            if ($('.rangDate').length) {
                $('.rangDate').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear',
                        applyLabel: 'เลือก',
                        customRangeLabel: 'กำหนดเอง',
                        daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
                        monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                        ],
                        firstDay: 0
                    },
                    ranges: {
                        'วันนี้': [moment(), moment()],
                        'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '7 วันที่แล้ว': [moment().subtract(6, 'days'), moment()],
                        '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
                        'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
                        'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(
                            1, 'month').endOf('month')]
                    }
                });
                $('.rangDate').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                        'DD/MM/YYYY'));
                    $("input[name='search_booking_start']").val(picker.startDate.format('YYYY-MM-DD'));
                    $("input[name='search_booking_end']").val(picker.endDate.format('YYYY-MM-DD'));
                });
                $('.rangDate').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $("input[name='search_booking_start']").val('');
                    $("input[name='search_booking_end']").val('');
                });
                // set value from request
                if ($("input[name='search_booking_start']").val() && $("input[name='search_booking_end']").val()) {
                    var start = moment($("input[name='search_booking_start']").val());
                    var end = moment($("input[name='search_booking_end']").val());
                    $('.rangDate').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }
            }
            // daterangepicker สำหรับช่วงวันเดินทาง
            if ($('.periodRangDate').length) {
                $('.periodRangDate').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear',
                        applyLabel: 'เลือก',
                        customRangeLabel: 'กำหนดเอง',
                        daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
                        monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                        ],
                        firstDay: 0
                    },
                    ranges: {
                        'วันนี้': [moment(), moment()],
                        'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '7 วันที่แล้ว': [moment().subtract(6, 'days'), moment()],
                        '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
                        'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
                        'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(
                            1, 'month').endOf('month')]
                    }
                });
                $('.periodRangDate').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                        'DD/MM/YYYY'));
                    $("input[name='search_period_start']").val(picker.startDate.format('YYYY-MM-DD'));
                    $("input[name='search_period_end']").val(picker.endDate.format('YYYY-MM-DD'));
                });
                $('.periodRangDate').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $("input[name='search_period_start']").val('');
                    $("input[name='search_period_end']").val('');
                });
                // set value from request
                if ($("input[name='search_period_start']").val() && $("input[name='search_period_end']").val()) {
                    var start = moment($("input[name='search_period_start']").val());
                    var end = moment($("input[name='search_period_end']").val());
                    $('.periodRangDate').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
                }
            }
        });
        jQuery.fn.highlight = function(pat, options) {
            var opts = jQuery.extend({}, jQuery.fn.highlight.defaults, options);
            return this.each(function() {
                var regex = new RegExp('(' + pat + ')', 'gi');
                $(this).html($(this).html().replace(regex, '<span class="' + opts.className + '">$1</span>'));
            });
        };
        jQuery.fn.highlight.defaults = {
            className: 'highlight'
        };
    </script>
@endsection
