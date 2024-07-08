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
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name }}">
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
                                <input class="form-check-input success" name="permissions[]" type="checkbox"  {{ in_array($role->id, $rolePermissions ?? []) ? 'checked' : '' }}
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
                                    <input class="form-check-input success" name="permissions[]" type="checkbox"   {{ in_array($role->id, $rolePermissions ?? []) ? 'checked' : '' }}
                                        id="success-check" value="{{ $role->id }}">
                                    <label class="form-check-label" for="success-check">{{ $role->label }}</label>
                                </div>
                            @empty
                            @endforelse
                        </div>

                  
                      
              

                    </div>
                    <hr>
                    <div class="mb-3 row">
                        <h6>ข้อมูลระบบ</h6>

                        <label for="permissions"
                        class="col-md-4 col-form-label text-md-end text-start">ข้อมูลโฮลเซลล์</label>

                    <div class="col-md-6">
                        @forelse ($permissionsWholesale as $wholesale)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input success" name="permissions[]" type="checkbox"  {{ in_array($wholesale->id, $rolePermissions ?? []) ? 'checked' : '' }}
                                    id="success-check" value="{{ $wholesale->id }}">
                                <label class="form-check-label" for="success-check">{{ $wholesale->label }}</label>
                            </div>

                        @empty
                        @endforelse
                    </div>

                    <label for="permissions"
                    class="col-md-4 col-form-label text-md-end text-start">ข้อมูลสายการบิน</label>
                    <div class="col-md-6">
                        @forelse ($permissionsAirline as $airline)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input success" name="permissions[]" type="checkbox"  {{ in_array($airline->id, $rolePermissions ?? []) ? 'checked' : '' }}
                                    id="success-check" value="{{ $airline->id }}">
                                <label class="form-check-label" for="success-check">{{ $airline->label }}</label>
                            </div>

                        @empty
                        @endforelse
                    </div>

                    </div>
                    <hr>
                    <div class="mb-3 row">
                        <h6>ใบจองทัวร์</h6>
                        <label for="permissions"
                        class="col-md-4 col-form-label text-md-end text-start">ข้อมูลโฮลเซลล์</label>
                    <div class="col-md-6">
                        @forelse ($permissionsBooking as $Booking)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input success" name="permissions[]" type="checkbox"  {{ in_array($Booking->id, $rolePermissions ?? []) ? 'checked' : '' }}
                                    id="success-check" value="{{ $Booking->id }}">
                                <label class="form-check-label" for="success-check">{{ $Booking->label }}</label>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    </div>
                    <hr>
                    <div class="mb-3 row">
                        <h6>Invoice</h6>
                        <label for="permissions"
                        class="col-md-4 col-form-label text-md-end text-start">ใบแจ้งหนี้</label>
                    <div class="col-md-6">
                        @forelse ($permissionsInvoice as $invoice)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input success" name="permissions[]" type="checkbox"  {{ in_array($invoice->id, $rolePermissions ?? []) ? 'checked' : '' }}
                                    id="success-check" value="{{ $invoice->id }}">
                                <label class="form-check-label" for="success-check">{{ $invoice->label }}</label>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <label for="permissions"
                    class="col-md-4 col-form-label text-md-end text-start">รายการค่าบริการ</label>
                    <div class="col-md-6">
                        @forelse ($permissionsProducts as $product)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input success" name="permissions[]" type="checkbox"  {{ in_array($product->id, $rolePermissions ?? []) ? 'checked' : '' }}
                                    id="success-check" value="{{ $product->id }}">
                                <label class="form-check-label" for="success-check">{{ $product->label }}</label>
                            </div>
                        @empty
                        @endforelse
                    </div>

                    </div>

                    
                    

{{-- 
                    <div class="mb-3 row">
                        <label for="permissions" class="col-md-4 col-form-label text-md-end text-start">Permissions</label>
                        <div class="col-md-6">           
                            <select class="form-select @error('permissions') is-invalid @enderror" multiple aria-label="Permissions" id="permissions" name="permissions[]" style="height: 210px;">
                                @forelse ($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions ?? []) ? 'selected' : '' }}>
                                        {{ $permission->name }}
                                    </option>
                                @empty

                                @endforelse
                            </select>
                            @if ($errors->has('permissions'))
                                <span class="text-danger">{{ $errors->first('permissions') }}</span>
                            @endif
                        </div>
                    </div> --}}
                    
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