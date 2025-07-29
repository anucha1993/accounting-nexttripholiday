@extends('layouts.template')

@section('content')
    <!-- buttons -->
    <style>
        span[titlespan]:hover::after {
            content: attr(titlespan);
            background-color: #f0f0f0;
            padding: 5px;
            border: 1px solid #ccc;
            position: absolute;
            z-index: 1;
        }
    </style>

    <div class="email-app todo-box-container container-fluid">

        <div class="card">
            <div class="card-header mt-2">
                <h4 class="text-info">รายงานภาษีขาย ตามเอกสาร </h4>
            </div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label"><i class="fas fa-calendar me-1"></i>ช่วงเวลา</label>
                            <input type="text" name="daterange" id="rangDate" class="form-control rangDate" 
                                   autocomplete="off" value="{{request('daterange')}}" 
                                   placeholder="เลือกช่วงวันที่" />
                            <input type="hidden" name="date_start" value="{{request('date_start')}}">
                            <input type="hidden" name="date_end" value="{{request('date_end')}}">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label"><i class="fas fa-flag me-1"></i>สถานะ</label>
                            <select name="status" class="form-select">
                                <option value="">ทั้งหมด</option>
                                <option value="success" {{request('status') == 'success' ? 'selected' : ''}}>สำเร็จ</option>
                                <option value="cancel" {{request('status') == 'cancel' ? 'selected' : ''}}>ยกเลิก</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label"><i class="fas fa-user me-1"></i>เซลผู้ขาย</label>
                            <select name="seller_id" class="form-select">
                                <option value="">ทั้งหมด</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                        {{ $seller->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label"><i class="fas fa-file-text me-1"></i>เลขที่เอกสาร</label>
                            <input type="text" name="document_number" class="form-control" 
                                   value="{{request('document_number')}}" 
                                   placeholder="เลขที่เอกสาร">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label"><i class="fas fa-link me-1"></i>เลขที่เอกสารอ้างอิง</label>
                            <input type="text" name="reference_number" class="form-control" 
                                   value="{{request('reference_number')}}" 
                                   placeholder="เลขที่เอกสารอ้างอิง">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label"><i class="fas fa-building me-1"></i>ชื่อลูกค้า</label>
                            <input type="text" name="customer_name" class="form-control" 
                                   value="{{request('customer_name')}}" 
                                   placeholder="ชื่อลูกค้า">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search me-1"></i>แสดงรายงาน
                                </button>
                                <a href="{{route('report.saletax')}}" class="btn btn-danger">
                                    <i class="fas fa-eraser me-1"></i>ล้างการค้นหา
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>





        <div class="card">
            <div class="card-header">
                <h3 class="text-info">Report Vat</h3><br>
                @canany(['report.salestax.export'])
                <form action="{{route('export.saletax')}}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="taxinvoice_ids" value="{{$taxinvoiceSum->pluck('taxinvoice_id')}}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To Excel</button>
                </form>
                @endcanany
            </div>
            <div class="card-body">

                <table class="table table quote-table " style="font-size: 12px; width: 100%">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>วันเดือนปี</th>
                            <th>เลขที่เอกสารอ้างอิง</th>
                            <th>เลขที่เอกสาร</th>
                            <th>ชื่อลูกค้า</th>
                            <th>เซลผู้ขาย</th>
                            <th>เลขผู้เสียกับภาษี</th>
                            <th>มูลค่าสินค้า/บริการ</th>
                            <th>ภาษีมูลค่าเพิ่ม</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($taxinvoiceSearch as $key => $item)
                        <tr>
                         
                            <td>{{++$key}}</td>
                            <td>{{date('d/m/Y',strtotime($item->taxinvoice_date))}}</td>
                            <td> 
                                @canany(['invoice.view','invoice.edit'])
                                <a target="_blank" href="{{route('mpdf.invoice',$item->invoice_id)}}">{{$item->invoice_number}}</a> 
                                @endcanany
                            </td>
                            <td> 
                                @canany(['report.receipt.view'])
                                <a target="_blank" href="{{route('mpdf.taxreceipt',$item->invoice_id)}}">{{$item->taxinvoice_number}}</a> 
                                @endcanany
                            </td>
                            <td>{{$item->invoice->customer->customer_name}}</td>
                          
                             <td>{{$item->invoice->quote->Salename->name}}</td>
                               <td>{{$item->invoice->customer?->customer_texid ?? '0000000000000' }}</td>
                            <td>{{number_format($item->invoice->invoice_pre_vat_amount,2)}}</td>
                            <td>{{number_format($item->invoice->invoice_vat,2)}}</td>
                            <td>{{$item->taxinvoice_status === 'success' ? 'สำเร็จ' : 'ยกเลิก'  }}</td>
                           
                   
                        </tr>
                            
                        @empty
                            
                        @endforelse
                       
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align:left"></th>
                            <th style="text-align:left" class="text-danger">
                                มูลค่ารวม : {{ number_format($grandTotalSum, 2) }}
                            </th>
                            <th style="text-align:left" class="text-danger">
                                มูลค่าภาษีรวม: {{ number_format($vatTotal, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
                  {!! $taxinvoiceSearch->withQueryString()->links('pagination::bootstrap-5') !!}
          
            </div>
        </div>
    </div>
    

    
    <script>
        $(function() {
            $(".rangDate").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: "DD/MM/YYYY",
                    separator: " - ",
                    applyLabel: "ตกลง",
                    cancelLabel: "ยกเลิก",
                    fromLabel: "จาก",
                    toLabel: "ถึง",
                    customRangeLabel: "กำหนดเอง",
                    daysOfWeek: ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
                    monthNames: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                                "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
                    firstDay: 1
                },
                ranges: {
                    'วันนี้': [moment(), moment()],
                    'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 วันที่แล้ว': [moment().subtract(6, 'days'), moment()],
                    '30 วันที่แล้ว': [moment().subtract(29, 'days'), moment()],
                    'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
                    'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
    
            $(".rangDate").on("apply.daterangepicker", function(ev, picker) {
                $(this).val(
                    picker.startDate.format("DD/MM/YYYY") +
                    " - " +
                    picker.endDate.format("DD/MM/YYYY")
                );
    
                $("input[name='date_start']").val(picker.startDate.format("YYYY-MM-DD"));
                $("input[name='date_end']").val(picker.endDate.format("YYYY-MM-DD"));
            });
    
            $(".rangDate").on("cancel.daterangepicker", function(ev, picker) {
                $(this).val("");
                $("input[name='date_start']").val("");
                $("input[name='date_end']").val("");
            });
        });
        </script>

@endsection
