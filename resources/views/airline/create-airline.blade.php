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
    
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Airline Edit</h4>
        <h6 class="card-subtitle lh-base">
            แก้ไขข้อมูลสายการบิน
        </h6>
        <hr>

        <form action="{{route('airline.store')}}" method="post">
            @csrf
            @method('post')
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">รหัสการเดินทาง (IATA)<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="code" placeholder="IATA"
                            required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12 mb-3">
                        <label class="form-label"> รหัสการเดินทาง (ICAO)<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="code1" placeholder="ICAO"
                            required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12 mb-3">
                        <label class="form-label"> ประเภทการเดินทาง<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" name="travel_name"  placeholder="ประเภทการเดินทาง"
                            required>
                    </div>
                </div>
                <br>

                <div class="col-md-6 mb-3">
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input success" type="radio"  name="status" id="success-radio" value="on" checked>
                        <label class="form-check-label" for="success-radio">เปิดใช้งาน</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input success" type="radio"  name="status" id="success2-radio" value="off">
                        <label class="form-check-label" for="success2-radio">ปิดใช้งาน</label>
                      </div>
                </div>
                <div class="col-md-12 mb-3">
                    <button type="submit" class="btn btn-success float-end"><i class="fas fa-save"></i> บันทึก</button>
                </div>

                
               

            </div>
        </form>

    </div>

   
</div>
</div>


@endsection