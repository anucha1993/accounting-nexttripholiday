@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">รายชื่อสายการบินทั้งหมด </h4>
           
                <a href="{{route('wholesale.create')}}" class="btn btn-info btn-sm float-end mb-3"><i class="fas fa-plus"></i> เพิ่มข้อมูล</a>
          
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
                        <th>รหัสสายการบิน</th>
                        <th>ชื่อสายการบิน</th>
                        <th>สถานะ</th>
                        <th>อัพเดทล่าสุด</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($airline as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->travel_name ?: '-' }}</td>
                            <td>
                                @if ($item->status === 'on')
                                    <span class="badge rounded-pill bg-success">เปิดใช้งาน</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">ปิดใช้งาน</span>
                                @endif
                            </td>
                            <td>{{ date('d-m-Y', strtotime($item->updated_at)) }}</td>
                            <td>
                              {{-- @canany(['edit-wholesale'])
                              <a href="{{route('wholesale.edit',$item->id)}}" class="ml-3"><i class=" fas fa-edit "> </i> แก้ไข</a>
                              @endcanany

                              @canany(['delete-wholesale'])
                           
                              <a href="{{ route('wholesale.destroy', $item->id) }}" type="submit" class="text-danger mx-3" onclick="return confirm('Do you want to delete this Wholesale?');"><i class=" fas fa-trash"> </i> ลบ</a>
                             
                              @endcanany --}}

                            </td>
                        </tr>
                    @empty
                        Not found Data Wholesale
                    @endforelse
                </tbody>
            </table>
            <br>
            {!! $airline->withQueryString()->links('pagination::bootstrap-5') !!}
        </div>
    </div>
@endsection
