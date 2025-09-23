@extends('layouts.template')

@section('content')
    <!-- buttons -->
    <style>
        span[titlespan]:hover::after {
                <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="text-info mb-0">รายงานใบแจ้งหนี้</h3>
                    @canany(['report.invoice.export'])
                    <button id="export-table-excel" class="btn btn-success">
                        <i class="fa fa-file-excel me-2"></i>ส่งออกไฟล์ Excel
                    </button>
                    @endcanany
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover quote-table" style="font-size: 12px; width: 100%">ttr(titlespan);
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
                                    <input type="text" name="daterange" id="rangDate" class="form-control rangDate" autocomplete="off"
                                        value="{{ $request->date_start && $request->date_end ? (\Carbon\Carbon::parse($request->date_start)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($request->date_end)->format('d/m/Y')) : '' }}"
                                        placeholder="Search by Range Date" />

                                    <input type="hidden" name="date_start" value="{{ $request->date_start }}">
                                    <input type="hidden" name="date_end" value="{{ $request->date_end }}">
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
                            <div class="col-md-2">
                                <label for="">เงือนไข</label>
                                <select name="column_name" class="form-select">
                                    <option @if($request->column_name === 'all') selected @endif value="all">ทั้งหมด</option>
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

                @canany(['report.invoice.export'])
                <button id="export-table-excel" class="btn btn-warning mb-3"><i class="fa fa-download"></i> Export Table to Excel</button>

                {{-- <form action="{{route('export.invoice')}}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="invoice_ids" value="{{$invoices->pluck('invoice_id')}}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To Excel</button>
                </form> --}}
                @endcanany

            </div>
            <div class="card-body">
                  {{ $invoices->links('pagination::bootstrap-5') }}
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
                            <th>สถานะ</th>

                        </tr>
                    </thead>


                        
                    <tbody>
                        @forelse ($invoices as $key => $item)
                        <tr>
                          
                            <td>
                                   {{ $invoices->total() - ($invoices->firstItem() + $key - 1) }}
                            </td>
                            <td> 
                                @canany(['invoice.view'])
                                <a href="{{route('mpdf.invoice',$item->invoice_id)}}" target="_blank">{{$item->invoice_number}}</a>
                                @endcanany
                            </td>

                            <td> 
                                @canany(['quote.view','quote.edit'])
                                <a target="_blank" href="{{route('quote.editNew',$item->quote->quote_id)}}">{{$item->quote->quote_number ? $item->quote->quote_number : 'ใบเสนอราคาถูกลบ'}}</a> 
                                @endcanany
                            </td>
                            <td>{{date('d/m/Y',strtotime($item->invoice_date))}}</td>
                            <td>{{$item->invoiceCustomer->customer_name}}</td>
                            <td>{{$item->invoice_booking}}</td>
                            <td>{{number_format($item->invoice_grand_total,2)}}</td>
                            <td>{{number_format($item->invoice_withholding_tax,2)}}</td>
                            <td>
                                {{$item->invoice_status === 'wait' ? 'รอดำเนินการ' : ''  }}
                                {{$item->invoice_status === 'cancel' ? 'ยกเลิก' : ''  }}
                                {{$item->invoice_status === 'success' ? 'สำเร็จ' : ''  }}
                            </td>
                           
                   
                        </tr>
                            
                        @empty
                            
                        @endforelse
                       
                    </tbody>
                    <tfoot>
    {{-- <tr class="text-danger">
        <th colspan="6" style="text-align:left">รวมในหน้านี้</th>
        <th style="text-align:left">มูลค่ารวม: {{number_format($pageTotals['grand_total'], 2)}}</th>
        <th style="text-align:left">มูลค่า.หัก.ณ.ที่จ่าย รวม: {{number_format($pageTotals['withholding_tax'], 2)}}</th>
    </tr> --}}
    @if($invoices->count() > 0)
    <tr class="text-primary">
        <th colspan="6" style="text-align:left">รวมทั้งหมด</th>
        <th style="text-align:left">มูลค่ารวม: {{number_format($invoices->first()->total_grand_total, 2)}}</th>
        <th style="text-align:left">มูลค่า.หัก.ณ.ที่จ่าย รวม: {{number_format($invoices->first()->total_withholding_tax, 2)}}</th>
    </tr>
    @endif
</tfoot>
                </table>
                    </div>
                    {{ $invoices->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

<!-- SheetJS CDN -->
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script>
    function handleExport() {
        // Get button reference
        const btn = document.getElementById('export-table-excel');
        
        // Disable button and show loading state
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>กำลังดาวน์โหลด...';

        // Get current URL and build export URL with per_page=1000 to get all records
        const currentUrl = new URL(window.location.href);
        // Add or replace per_page parameter to get more records
        const searchParams = new URLSearchParams(currentUrl.search);
        searchParams.set('per_page', '1000'); // Request 1000 records (or more if needed)
        const exportUrl = `${currentUrl.pathname}/export?${searchParams.toString()}`;
        console.log('Fetching from URL:', exportUrl);

        // Fetch data from API
        fetch(exportUrl)
            .then(response => {
                console.log('Response received:', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (!data || data.length === 0) {
                    throw new Error('No data received');
                }

                // Log first record for debugging
                if (data[0]) {
                    console.log('Sample record:', {
                        invoice_number: data[0].invoice_number,
                        customer_name: data[0].invoice_customer?.customer_name,
                        invoiceCustomer: data[0].invoiceCustomer?.customer_name
                    });
                }

                // Create workbook
                const wb = XLSX.utils.book_new();
                
                // Transform data
                const exportData = data.map((item, index) => ({
                    'ลำดับ': index + 1,
                    'เลขที่ใบแจ้งหนี้': item.invoice_number,
                    'เลขที่ใบเสนอราคา': item.quote?.quote_number || 'ไม่มีข้อมูล',
                    'วันที่ออกใบแจ้งหนี้': new Date(item.invoice_date).toLocaleDateString('th-TH'),
                    'ชื่อลูกค้า': item.invoice_customer?.customer_name || item.invoiceCustomer?.customer_name || 'ไม่มีข้อมูล',
                    'Booking Code': item.invoice_booking,
                    'จำนวนเงิน (บาท)': parseFloat(item.invoice_grand_total).toFixed(2),
                    'ภาษีหัก ณ ที่จ่าย (บาท)': parseFloat(item.invoice_withholding_tax).toFixed(2),
                    'สถานะ': item.invoice_status === 'wait' ? 'รอดำเนินการ' : 
                             item.invoice_status === 'cancel' ? 'ยกเลิก' : 
                             item.invoice_status === 'success' ? 'สำเร็จ' : ''
                }));

                // Create worksheet
                const ws = XLSX.utils.json_to_sheet(exportData);

                // Set column widths
                ws['!cols'] = [
                    {wch: 8},  // ลำดับ
                    {wch: 15}, // เลขที่ใบแจ้งหนี้
                    {wch: 15}, // เลขที่ใบเสนอราคา
                    {wch: 15}, // วันที่
                    {wch: 30}, // ชื่อลูกค้า
                    {wch: 15}, // Booking
                    {wch: 15}, // จำนวนเงิน
                    {wch: 15}, // ภาษี
                    {wch: 15}, // สถานะ
                ];

                // Add worksheet to workbook
                XLSX.utils.book_append_sheet(wb, ws, "รายงานใบแจ้งหนี้");

                // Generate filename
                const today = new Date();
                const date = today.toISOString().split('T')[0];
                const filename = `invoice-report-${date}.xlsx`;

                // Export file
                XLSX.writeFile(wb, filename);
            })
            .catch(error => {
                console.error('Export error:', error);
                alert('เกิดข้อผิดพลาดในการส่งออกข้อมูล กรุณาลองใหม่อีกครั้ง');
            })
            .finally(() => {
                // Reset button state
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-file-excel me-2"></i>ส่งออกไฟล์ Excel';
            });
    }

    // Add click event listener
    document.getElementById('export-table-excel').addEventListener('click', handleExport);
</script>

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