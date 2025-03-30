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
                <h4 class="text-info">รายงานใบกำกับภาษีตามเอกสาร </h4>
            </div>
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-2">
                                    <label for="">ช่วงเวลา</label>
                                    <input type="text" name="daterange" id="rangDate" class="form-control rangDate" autocomplete="off" value="" placeholder="Search by Range Date" />

                                    <input type="hidden" name="date_start">
                                    <input type="hidden" name="date_end">
                                </div>

                            <div class="col-md-2">
                                <label for="">สถานะ</label>
                                <select name="status" id="" class="form-select" >
                                    <option value="">---กรุณาเลือก---</option>
                                    <option value="success">สำเร็จ</option>
                                    <option value="cancel">ยกเลิก</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">เงือนไข</label>
                                <select name="column_name" class="form-select">
                                    <option @if($request->column_name === 'all') selected @endif value="all">ทั้งหมด</option>
                                    <option @if($request->column_name === 'taxinvoice_number') selected @endif value="taxinvoice_number">เลขที่ใบกำกับภาษี</option>
                                    <option @if($request->column_name === 'invoice_number') selected @endif value="invoice_number">เลขที่ใบแจ้งหนี้</option>
                                    <option @if($request->column_name === 'invoice_booking') selected @endif value="invoice_booking">เลขที่ใบจองทัวร์</option>
                                    <option @if($request->column_name === 'customer_name') selected @endif value="customer_name">ชื่อลูกค้า</option>
                                    <option @if($request->column_name === 'customer_texid') selected @endif value="customer_texid">เลขประจำตัวผู้เสียภาษี</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="">คียร์เวิร์ด</label>
                                <input type="text" name="keyword" class="form-control" placeholder="คียร์เวิร์ด" value="{{$request->keyword}}">
                            </div>

                            <div class="col-md-2">
                               <br>
                                <button type="submit" class="btn  btn-info float-end ml-2">แสดงรายงาน</button>
                            </div>
                            <div class="col-md-1">
                                <br>
                                 <a href="{{route('report.taxinvoice')}}"  class="btn  btn-danger float-end ml-2">ล้างการค้นหา</a>
                             </div>
                        </div>
                     
                        </div>
                       
                    </div>
                </form>
            </div>
        </div>





        <div class="card">
            <div class="card-header">
                <h3 class="text-info">Report Tax Invoice</h3><br>
                <form action="{{route('export.taxinvoice')}}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="taxinvoice_ids" value="{{$taxinvoices->pluck('taxinvoice_id')}}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To Excel</button>
                </form>
            </div>
            <div class="card-body">

                <table class="table table quote-table " style="font-size: 12px; width: 100%">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ใบกำกับภาษี</th>
                            <th>เลขที่ใบแจ้งหนี้</th>
                            <th>วันที่ออกใบกำกับภาษี</th>
                            <th>ชื่อลูกค้า</th>
                            <th>Booking Code</th>
                            <th>จำนวนเงิน:บาท</th>
                            <th>ภาษีหัก ณ ที่จ่าย:บาท</th>
                            <th>ผู้จัดทำ</th>
                        </tr>
                    </thead>


                        
                    <tbody>
                        @forelse ($taxinvoices as $key => $item)
                        <tr>
                         
                            <td>{{++$key}}</td>
                            <td> <a href="{{route('mpdf.taxreceipt',$item->invoice_id)}}" target="_blank">{{$item->taxinvoice_number}}</a></td>
                            <td> <a target="_blank" href="{{route('mpdf.invoice',$item->invoice_id)}}">{{$item->invoice_number}}</a> </td>
                            <td>{{date('d/m/Y',strtotime($item->taxinvoice_date))}}</td>
                            <td>{{$item->invoice->customer->customer_name}}</td>
                            <td>{{$item->invoice->invoice_booking}}</td>
                            <td>{{number_format($item->invoice->invoice_grand_total,2)}}</td>
                            <td>{{number_format($item->invoice->invoice_withholding_tax,2)}}</td>
                            <td>{{$item->created_by}}</td>
                           
                   
                        </tr>
                            
                        @empty
                            
                        @endforelse
                       
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:left"></th>
                            <th style="text-align:left">
                                มูลค่ารวม : {{ number_format($grandTotalSum, 2) }}
                            </th>
                            <th style="text-align:left">
                                มูลค่า.หัก.ณ.ที่จ่าย รวม: {{ number_format($withholdingTaxSum, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
          
            </div>
        </div>
    </div>
    

    
    <script>
        $(function() {
            $(".rangDate").daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: "DD/MM/YYYY",
                },
            });
    
            $(".rangDate").on("apply.daterangepicker", function(ev, picker) {
                $(this).val(
                    picker.startDate.format("DD/MM/YYYY") +
                    " - " +
                    picker.endDate.format("DD/MM/YYYY")
                );
    
                // แปลงวันที่และใส่ลงใน input date_start และ date_end
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
