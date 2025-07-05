@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">

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
            <div class="card-header">Manage Roles</div>
            <div class="card-body">
                @can('role-create')
                    <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i>
                        Add New Role</a>
                @endcan
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">S#</th>
                            <th scope="col">Name</th>
                            <th scope="col" style="width: 250px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        @can('role-view')
                                        <a href="{{ route('roles.show', $role->id) }}" class="btn btn-secondary btn-sm"><i
                                                class="fas fa-eye"></i> Show</a>
                                        @endcan

                                        @if ($role->name != 'Super Admin')
                                            @can('role-edit')
                                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm"><i
                                                        class="fas fa-pencil-alt"></i> Edit</a>
                                            @endcan

                                            @can('role-delete')
                                                @if ($role->name != Auth::user()->hasRole($role->name))
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Do you want to delete this role?');"><i
                                                            class="fas fa-trash"></i> Delete</button>
                                                @endif
                                            @endcan
                                        @endif

                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="3">
                                <span class="text-danger">
                                    <strong>No Role Found!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                </table>

                {{ $roles->links() }}

            </div>
        </div>
    </div>
@endsection
