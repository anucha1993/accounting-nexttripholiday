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
            <h4 class="card-title">ใบเสนอราคา/ใบแจ้งหนี้  <a href="{{route('booking.create')}}" class="btn btn-primary float-end">เพิ่มใบจองทัวร์</a></h4>
            <hr>
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-2">
                        <label>ค้นหา ชื่อ-นามสกุล</label>
                        <div class="input-group mb-3 pull-right">
                            <input type="text" class="form-control" placeholder="ค้นหา... ชื่อ-นามสกุล"
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


                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary float-end mx-3" type="submit">ค้นหา</button>
                        <a href="{{ route('booking.index') }}" class="btn btn-outline-secondary float-end mx-3"
                            type="submit">ล้างข้อมูล</a>

                    </div>
                </div>
            </form>

        </div>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table customize-table table-hover mb-0 v-middle table-striped table-bordered " style="font-size: 12px">
                    <thead class="table text-white bg-info">
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ใบแจ้งหนี้</th>
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
                       @forelse ($invoices as $key => $item)
                         <tr>
                              <td>{{$key+1}}</td>
                              <td >{{$item->invoice_number}}</td>
                              <td>{{$item->invoice_booking}}</td>
                              <td>{{date('d/m/Y',strtotime($item->invoicebooking->start_date)).'-'.date('d/m/Y',strtotime($item->invoicebooking->end_date))}}</td>
                              <td>{{$item->invoicecustomer->customer_name}}</td>
                              {{-- <td>{{$item->invoiceCountry->country_name_th}}</td> --}}
                              <td>
                                @foreach($item->invoice_countries as $country)
                                {{ $country->country_name_th }}
                               @endforeach

                              </td>

                              <td>{{$item->invoiceWholesale->wholesale_name_th}}</td>
                              <td><span class="badge rounded-pill bg-primary">รอชำระ</span> </td>
                              <td>{{ number_format($item->invoice_total, 2, '.', ',');  }}</td>
                              <td> <span class="badge rounded-pill bg-primary">รอชำระ</span></td>
                              <td> {{$item->invoiceBooking->bookingSale->name}}</td>
                              <td><a href="{{route('invoice.edit',$item->invoice_id)}}" class="btn btn-info btn-sm">จัดการข้อมูล</a></td>
                              
                          </tr>
                       @empty
                           No data 
                       @endforelse
                       
                    </tbody>
                </table>
                {!! $invoices->withQueryString()->links('pagination::bootstrap-5') !!}
            </div>
            </div>
            </div>
        </div>
        
   
     


@endsection
