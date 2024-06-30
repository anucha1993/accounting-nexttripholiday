@extends('layouts.template')

@section('content')

<div class="card" >
    <div class="card-body">
        <h4 class="card-title">ใบจองทัวร์ </h4>
       
            <a href="{{route('airline.create')}}" class="btn btn-info btn-sm float-end mb-3"><i class="fas fa-plus"></i> เพิ่มข้อมูล</a>
      
        <form action="" method="GET">
           <div class="input-group mb-3 pull-right">
               <input type="text" class="form-control" placeholder="ค้นหาข้อมูล..." name="search" value="{{ request('search') }}">
               <div class="input-group-append">
                   <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
               </div>
           </div>
       </form>

    </div>
</div>

<div class="card" >
    <div class="card-body">
          
        <div class="table-responsive">
            <table class="table customize-table table-hover mb-0 v-middle">
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>เลขที่ใบจองทัวร์</th>
                        <th>รหัสทัวร์</th>
                        {{-- <th>Booking Date</th>
                        <th>แพคเกจทัวร์ที่ซื้อ</th>
                        <th>ประเทศ</th>
                        <th>ลูกค้า</th>
                        <th>PAX</th>
                        <th>PP</th>
                        <th>โฮลเซลล์</th>
                        <th>การชำระของลูกค้า</th>
                        <th>ยอดใบแจ้งหนี้</th>
                        <th>การชำระโฮลเซลล์</th>
                        <th>ผู้ขาย</th>
                        <th>Actions</th> --}}

                    </tr>
                </thead>
                <tbody>
                    @forelse ($booking as $key => $item)
                        <tr>
                            <td>{{ $key+1}}</td>
                            <td>{{ $item->code}}</td>
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