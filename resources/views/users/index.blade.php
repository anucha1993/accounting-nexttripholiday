@extends('layouts.template')

@section('content')

<div class="container-fluid page-content">


    

<div class="card">
    <div class="card-header">การจัดการสมาชิก
        @can('create-user')
        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm  float-end"><i class="bi bi-plus-circle"></i> เพิ่มสมาชิก</a>
    @endcan

    </div>
    
    <div class="card-body">

      
        <form action="" method="GET">
            <div class="input-group mb-3 pull-right">
                <input type="text" class="form-control" placeholder="ค้นหาข้อมูล..." name="search" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">ค้นหา</button>
                </div>
            </div>
        </form>


        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">ชื่อ-นามสกุล</th>
                <th scope="col">อีเมล</th>
                <th scope="col"class="text-center">ระดับสิทธิ์</th>
                <th scope="col"class="text-center">สถานะ</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
               
                @forelse ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td  class="text-center">
                        @forelse ($user->getRoleNames() as $role)
                            <span class="badge bg-primary">{{ $role }}</span>
                        @empty
                        @endforelse
                    </td>
                    <td class="text-center">
                        @if ($user->status === 'enable')
                        <span class="badge bg-success">เปิดใช้งาน</span>
                        @else
                        <span class="badge bg-danger">ปิดใช้งาน</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <form action="{{ route('users.destroy', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary btn-sm"><i class=" fas fa-eye"></i> Show</a>
                            @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                @if (Auth::user()->hasRole('Super Admin'))
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class=" fas fa-pencil-alt"></i> Edit</a>
                                @endif
                            @else
                                @can('edit-user')
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class=" fas fa-pencil-alt"></i> Edit</a>   
                                @endcan
                                @can('delete-user')
                                    @if (Auth::user()->id!=$user->id)
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this user?');"><i class=" fas fa-trash"></i> Delete</button>
                                    @endif
                                @endcan
                            @endif

                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="5">
                        <span class="text-danger">
                            <strong>No User Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}

    </div>
</div>
</div>
</div>
    
@endsection