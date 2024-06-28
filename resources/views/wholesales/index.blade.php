@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Wholesales</h4>
            <h6 class="card-subtitle lh-base">
                รายชื่อโฮลเซลล์ทั้งหมด
            </h6>
            <form action="" method="GET">
               <div class="input-group mb-3 pull-right">
                   <input type="text" class="form-control" placeholder="ค้นหาข้อมูล..." name="search" value="{{ request('search') }}">
                   <div class="input-group-append">
                       <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
                   </div>
               </div>
           </form>
   
        </div>
        
        <div class="table-responsive">
            <table class="table customize-table table-hover mb-0 v-middle">
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>รหัส</th>
                        <th>ชื่อ โฮลเซลล์</th>
                        <th>เบอร์โทรศัพท์</th>
                        <th>ชื่อผู้ติดต่อ</th>
                        <th>สถานะ</th>
                        <th>วันที่อัพเดท</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wholesales as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->wholesale_name_th }}</td>
                            <td>{{ $item->tel ?: '-' }}</td>
                            <td>{{ $item->contact_person ?: '-' }}</td>
                            <td>
                                @if ($item->status === 'on')
                                    <span class="badge rounded-pill bg-success">เปิดใช้งาน</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td>{{ date('d-m-Y', strtotime($item->updated_at)) }}</td>
                            <td>
                              @canany(['edit-wholesale'])
                              <a href="{{route('wholesale.edit',$item->id)}}" class="ml-3"><i class=" fas fa-edit "> </i> แก้ไข</a>
                              @endcanany

                              @canany(['delete-wholesale'])
                              <a href="" class="text-danger mx-3"><i class=" fas fa-trash"> </i> ลบ</a>
                              @endcanany

                            </td>
                        </tr>
                    @empty
                        Not found Data Wholesale
                    @endforelse
                </tbody>
            </table>
            <br>
            {!! $wholesales->withQueryString()->links('pagination::bootstrap-5') !!}
        </div>
    </div>
@endsection
