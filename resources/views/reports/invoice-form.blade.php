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

<?php

use Carbon\Carbon;

if (!function_exists('getQuoteStatusPaymentReport')) {
    function getQuoteStatusPaymentReport($quotationModel)
    {
        $now = Carbon::now();
        $status = '';
        // ตรวจสอบ payment_status ผ่านความสัมพันธ์ quotePayment
        if ($quotationModel->quotePayment && $quotationModel->quotePayment->payment_status === 'refund') {

            $status = 'รอคืนเงิน';
        } elseif ($quotationModel->quote_status === 'cancel') {
            $status = 'ยกเลิกการสั่งซื้อ';
    
        } elseif ($quotationModel->quote_status === 'success') {
            $status = 'ชำระเงินครบแล้ว';
        } elseif ($quotationModel->payment > 0) {
            $status = 'รอชำระเงินเต็มจำนวน';
        } elseif ($quotationModel->quote_payment_type === 'deposit') {
            if ($now->gt(Carbon::parse($quotationModel->quote_payment_date))) {
                $status = 'เกินกำหนดชำระเงิน';
            } else {
                $status = 'รอชำระเงินมัดจำ';
            }
        } elseif ($quotationModel->quote_payment_type === 'full') {
            if ($now->gt(Carbon::parse($quotationModel->quote_payment_date_full))) {
                $status = 'เกินกำหนดชำระเงิน';
            } else {
                $status = 'รอชำระเงินเต็มจำนวน';
            }
        } else {
            $status = 'รอชำระเงิน';
        }
        return $status;
    }
}

?>


    <div class="email-app todo-box-container container-fluid">

        <div class="card">
            <div class="card-header mt-2">
                <h4 class="text-info">รายงานใบแจ้งหนี้ตามเอกสาร </h4>
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
                                    <option value="wait">กำลังดำนเนินการ</option>
                                    <option value="success">สำเร็จ</option>
                                    <option value="cancel">ยกเลิก</option>
                                </select>
                            </div>

                            <div class="col-md-7">
                               <br>
                                <button type="submit" class="btn  btn-info float-end ml-2">แสดงรายงาน</button>
                            </div>
                            <div class="col-md-1">
                                <br>
                                 <a href="{{route('report.invoice')}}"  class="btn  btn-danger float-end ml-2">ล้างการค้นหา</a>
                             </div>
                        </div>
                     
                        </div>
                       
                    </div>
                </form>
            </div>
        </div>





        <div class="card">
            <div class="card-header">
                <h3 class="text-info">Report Invoice</h3><br>
                <form action="{{route('export.invoice')}}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="invoice_ids" value="{{$invoices->pluck('invoice_id')}}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To Excel</button>
                </form>
            </div>
            <div class="card-body">

                <table class="table table quote-table " style="font-size: 12px; width: 100%">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ใบแจ้งหนี้</th>
                            <th>เลขที่ใบเสนอราคา</th>
                            <th>วันที่ออกใบแจ้งหนี้</th>
                            <th>ชื่อลูกค้า</th>
                            <th>Booking Code</th>
                            <th>จำนวนเงิน:บาท</th>
                            <th>ภาษีหัก ณ ที่จ่าย:บาท</th>
                            <th>ผู้จัดทำ</th>

       
                        </tr>
                    </thead>


                        
                    <tbody>
                        @forelse ($invoices as $key => $item)
                        <tr>
                            {{-- <td>{{++$key}}</td>
                            <td>{{date('d/m/Y',strtotime($item->invoice_date))}}</td>
                            <td>{{$item->invoice_number}}</td>
                            <td>{{$item->quote->quote_number ? $item->quote->quote_number : 'ใบเสนอราคาถูกลบ'}}</td>
                            <td>{{$item->customer->customer_name}}</td>
                            <td>{{$item->customer->customer_texid ? $item->customer->customer_texid : 'N/A'}}</td>
                            <td>{{number_format($item->invoice_withholding_tax,2)}}</td>
                            <td>{{number_format($item->invoice_grand_total,2)}}</td>
                            <td>{{number_format($item->invoice_vat,2)}}</td> --}}
                            <td>{{++$key}}</td>
                            <td>{{$item->invoice_number}}</td>
                            <td>{{$item->quote->quote_number ? $item->quote->quote_number : 'ใบเสนอราคาถูกลบ'}}</td>
                            <td>{{date('d/m/Y',strtotime($item->invoice_date))}}</td>
                            <td>{{$item->customer->customer_name}}</td>
                            <td>{{$item->invoice_booking}}</td>
                            <td>{{number_format($item->invoice_grand_total,2)}}</td>
                            <td>{{number_format($item->invoice_withholding_tax,2)}}</td>
                            <td>{{$item->created_by}}</td>
                   
                        </tr>
                            
                        @empty
                            
                        @endforelse
                       
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align:left"></th>
                            <th style="text-align:left">มูลค่ารวม : {{number_format($invoices->sum('invoice_grand_total',2))}}</th>
                            <th style="text-align:left">มูลค่า.หัก.ณ.ที่จ่าย รวม: {{number_format($invoices->sum('invoice_withholding_tax',2))}}</th>
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
