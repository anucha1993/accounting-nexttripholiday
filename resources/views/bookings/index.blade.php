@extends('layouts.template')

@section('content')


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


<div class="card" >
    
    <div class="card-body">
      
        <h4 class="card-title">ใบจองทัวร์ จากเว็บไซต์</h4>
        <hr>
       
        <form action="" method="GET">
         <div class="row">
            <div class="col-md-2">
                <label>ค้นหา ชื่อ-นามสกุล</label>
                <div class="input-group mb-3 pull-right">
                    <input type="text" class="form-control" placeholder="ค้นหา... ชื่อ-นามสกุล" name="search_name" value="{{ request('search_name') }}">
                  
                </div>
            </div>
           
            <div class="col-md-2">
                <label>ค้นหา วันที่เดินทางเริ่มต้น</label>
                <div class="input-group mb-3 pull-right">
                    <input type="date" class="form-control"  name="search_tour_date_start" value="{{ request('search_tour_date_start') }}">
                  
                </div>
            </div>
            <div class="col-md-2">
                <label>ถึงวันที่</label>
                <div class="input-group mb-3 pull-right">
                    <input type="date" class="form-control"  name="search_tour_date_end" value="{{ request('search_tour_date_end') }}">
                  
                </div>
            </div>

            <div class="col-md-2">
                <label>ค้นหา วันที่จอง เริ่มต้น</label>
                <div class="input-group mb-3 pull-right">
                    <input type="date" class="form-control"  name="search_tour_date_start_created" value="{{ request('search_tour_date_start_created') }}">
                  
                </div>
            </div>
            <div class="col-md-2">
                <label>ถึงวันที่</label>
                <div class="input-group mb-3 pull-right">
                    <input type="date" class="form-control"  name="search_tour_date_end_created" value="{{ request('search_tour_date_end_created') }}">
                  
                </div>
            </div>

            <div class="col-md-2">
         
                <label>Sales</label>
                <div class="input-group mb-3 pull-right">
                    <select name="search_sale" class="form-select">
                        <option value="all">ทั้งหมด</option>
                        @forelse ($sales as $item)
                            <option @if((string)$keyword_sale === (string)$item->id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                        @empty
                            <option value="" disabled>ไม่มีข้อมูล</option>
                        @endforelse
                    </select>
                    
                  
                </div>
            </div>


            <div class="input-group-append">
                <button class="btn btn-outline-secondary float-end mx-3" type="submit">ค้นหา</button>
                <a href="{{route('booking.index')}}" class="btn btn-outline-secondary float-end mx-3" type="submit">ล้างข้อมูล</a>

            </div>
         </div>
       </form>

    </div>
    
</div>

<div class="card" >
    <div class="card-body">
          
        <div class="table-responsive">
            <table class="table customize-table table-hover mb-0 v-middle table-striped" style="font-size: 14px">
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>เลขที่ใบจองทัวร์</th>
                        <th>รหัสทัวร์</th>
                        <th>ลูกค้า</th>
                        <th>โปรแกรมทัวร์</th>
                        <th>วันที่เดินทาง</th>
                        <th>PAX</th>
                        <th>เซลล์</th>
                        <th>สถานะ</th>
                        <th>วันที่จอง</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($booking as $key => $item)
                        <tr>
                            <td>{{ $key+1}}</td>
                            <td>{{ $item->code}}</td>
                            <td>{{ $item->tour_code}}</td>
                            <td>{{ 'คุณ'.$item->name.' '.$item->surname}}</td>
                            <td>{{ Str::limit($item->tour_name, 30) }}</td>
                            <td>{{ date('d-m-Y', strtotime($item->start_date))}}</td>
                            <td>{{ $item->total_qty.' คน'}}</td>
                            <td>{{ $item->sale_name}}</td>
                            <td>
                               
                                @if ($item->status ==='Success')
                                <span class="badge rounded-pill bg-success">Success</span>
                                @endif
                                {{-- @if ($item->status ==='Cancel')
                                <span class="badge rounded-pill bg-danger">Cancel</span>
                                @endif
                                @if ($item->status ==='Booked')
                                <span class="badge rounded-pill bg-danger">Booked</span>
                                @endif --}}
                        
                            </td>
                            <td>{{date('d-m-Y H:m', strtotime($item->created_at)) }}</td>
                            <td>
                                <form action="{{route('booking.convert')}}" method="get">

                                    <input type="hidden" name="customer_name" value="{{$item->name.' '.$item->surname}}">
                                    <input type="hidden" name="customer_email" value="{{$item->email}}">
                                    <input type="hidden" name="customer_tel" value="{{$item->phone}}">

                                    <input type="hidden" name="booking_number" value="{{$item->code}}">
                                    <input type="hidden" name="booking_sale" value="{{$item->sale_name}}">
                                    <input type="hidden" name="booking_tour_number" value="{{$item->tour_code}}">
                                     
                                    <input type="hidden" name="product[0]" value="จำนวนผู้ใหญ่">
                                    <input type="hidden" name="qty[0]" value="{{$item->num_twin}}">
                                    <input type="hidden" name="price[0]" value="{{$item->price1}}">
                                    <input type="hidden" name="sum[0]" value="{{$item->sum_price1}}">

                                   

                                    <button type="submit" class="mx-3 btn btn-sm btn-primary"><i class=" fas fa-redo " ></i> Convert</button>

                                    @can('edit-booking')
                                    <a href="#" class="  mx-3"><i class="fas fa-edit"></i> แก้ไข</a>
                                    @endcan
                                    @can('delete-booking')
                                    <a href="#" class="text-danger  mx-3"><i class="fas fa-trash"></i> ลบ</a>
                                    @endcan
                                </form>
                            

                             
                            </td>
           
                        </tr>
                    @empty
                        No found data
                    @endforelse
                </tbody>
            </table>
            {!! $booking->withQueryString()->links('pagination::bootstrap-5') !!}
        </div>
    </div>
</div>



@endsection