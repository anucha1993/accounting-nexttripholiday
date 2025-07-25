@extends('layouts.template')

@section('content')

<div class="container-fluid page-content">

    @if (session('success'))
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
    role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Success - </strong>{{session('success')}}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
    role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>Error - </strong>{{session('error')}}
    </div>
    @endif
    
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Role
                </div>
                <div class="float-end">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">ชื่อสิทธิ์</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        @foreach ($permissionGroups as $group)
                            <div class="col-md-6 mb-4">
                                <div class="card border-primary h-100">
                                    <div class="card-header bg-primary text-white py-2">
                                        <strong>{{ $group }}</strong>
                                    </div>
                                    <div class="card-body py-2">
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($permissions->where('group', $group) as $perm)
                                                <li class="mb-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" type="checkbox" id="perm-{{ $perm->id }}" value="{{ $perm->id }}" {{ in_array($perm->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm-{{ $perm->id }}">{{ $perm->label ?? $perm->name }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update Role">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
</div>
    
@endsection