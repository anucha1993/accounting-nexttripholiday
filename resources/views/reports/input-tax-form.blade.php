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
                <h4>รายงานภาษีซื้อตามเอกสาร </h4>
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
                                        <option value="not_null">ได้รับแล้ว</option>
                                        <option value="is_noll">ยังไม่ได้รับ</option>
                                    </select>
                                </div>

                            <div class="col-md-7">
                               <br>
                                <button type="submit" class="btn  btn-info float-end">แสดงรายงาน</button>
                            </div>
                            <div class="col-md-1">
                                <br>
                                 <a href="{{route('report.input-tax')}}"  class="btn  btn-danger float-end ml-2">ล้างการค้นหา</a>
                             </div>
                        </div>
                     
                        </div>
                       
                    </div>
                </form>
            </div>
        </div>


        </div>


        <div class="card">
            <div class="card-header">
                <h3>Report quotations</h3>
            </div>
            <div class="card-body">
                <form action="{{route('export.inputtax')}}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="input_tax_ids" value="{{$inputTaxs->pluck('input_tax_id')}}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To Excel</button>
                </form>
            </div>
                <table class="table table quote-table " style="font-size: 12px; width: 100%">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>วันที่</th>
                            <th>เลขที่เอกสาร</th>
                            <th>ไฟล์แนบ</th>
                            <th>เอกสารอ้างอิง</th>
                            <th>ชื่อผู้จำหน่าย</th>
                            <th>เลขที่ผู้เสียภาษี</th>
                            <th>มูลค่า</th>
                            <th>ภาษีมูลค่าเพิ่ม</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($inputTaxs as $key => $item)
                       <tr>
                        <td>{{++$key}}</td>
                        <td>{{date('d/m/Y',strtotime($item->input_tax_date_tax))}}</td>
                        <td>{{$item->input_tax_number_tax}}</td>
                        <td>
                            @if ($item->input_tax_file)
                            <a href="{{ asset('storage/' . $item->input_tax_file) }}" class="btn btn-info btn-sm" onclick="openPdfPopup(this.href); return false;">
                             เปิดดูไฟล์</a>
                             @else
                             ยังไม่รับเอกสาร
                             @endif
                        </td>
                        <td>{{ $item->invoice->taxinvoice->taxinvoice_number ?? 'ไม่มีข้อมูล' }}</td>
                        <td>{{$item->quote->quoteWholesale->wholesale_name_th}}</td>
                        <td>{{$item->quote->quoteWholesale->textid}}</td>
                        <td>{{number_format($item->input_tax_service_total,2)}}</td>
                        <td>{{number_format($item->input_tax_vat,2)}}</td>

                        <td>
                            @if ($item->input_tax_file)
                            ได้รับเอกสารแล้ว
                            @else
                            ยังไม่รับเอกสาร
                            @endif
                        </td>
                        
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
                                มูลค่าภาษีรวม: {{ number_format($vat, 2) }}
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
