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
                        Add New Role
                    </div>
                    <div class="float-end">
                        <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="post">
                        @csrf

                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start">ชื่อสิทธิ์</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <hr>

                        <div class="mb-3 row">
                            <h6>ระบบสมาชิก</h6>

                            <label for="permissions"
                            class="col-md-4 col-form-label text-md-end text-start">สมาชิก</label>

                        <div class="col-md-6">
                            @forelse ($permissionsUser as $role)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input success" name="permissions[]" type="checkbox"
                                        id="success-check" value="{{ $role->id }}">
                                    <label class="form-check-label" for="success-check">{{ $role->label }}</label>
                                </div>

                            @empty
                            @endforelse
                        </div>


                            <label for="permissions"
                                class="col-md-4 col-form-label text-md-end text-start">กำหนดสิทธิ์</label>
                            <div class="col-md-6">
                                @forelse ($permissionsRole as $role)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input success" name="permissions[]" type="checkbox"
                                            id="success-check" value="{{ $role->id }}">
                                        <label class="form-check-label" for="success-check">{{ $role->label }}</label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                      
                          
                  

                        </div>
                        <hr>
                        

                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Role">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
