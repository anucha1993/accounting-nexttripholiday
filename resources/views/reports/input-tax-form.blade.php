@extends('layouts.template')

@section('content')
<style>
    /* Basic Styles */
    .main-container {
        background: #fff;
        padding: 15px 0;
    }
    
    .card {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 12px 20px;
        font-weight: 600;
    }
    
    .card-body {
        padding: 20px;
    }
    
    /* Form Controls */
    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 14px;
        transition: border-color 0.15s ease-in-out;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 6px;
        font-size: 14px;
        color: #495057;
    }
    
    .form-label i {
        color: #6c757d;
    }
    
    /* Buttons */
    .btn {
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    /* Table */
    .table {
        font-size: 13px;
        margin: 0;
    }
    
    .table th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 10px 8px;
        font-weight: 600;
        font-size: 12px;
        text-align: center;
        color: #495057;
    }
    
    .table td {
        padding: 8px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background: rgba(0,123,255,0.05);
    }
    
    /* Badges */
    .badge {
        font-size: 10px;
        padding: 4px 6px;
        border-radius: 4px;
    }
    
    /* Icons */
    .fas, .fa {
        font-size: 12px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }
        .table {
            font-size: 11px;
        }
        .table th, .table td {
            padding: 6px 4px;
        }
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


    <div class="main-container">
        <div class="container-fluid">
            
            <!-- ฟอร์มค้นหา -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>ค้นหารายงานภาษีซื้อ</h5>
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
                                    <option value="not_null" {{request('status') == 'not_null' ? 'selected' : ''}}>ได้รับแล้ว</option>
                                    <option value="is_null" {{request('status') == 'is_null' ? 'selected' : ''}}>ยังไม่ได้รับ</option>
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

                            {{-- <div class="col-lg-2 col-md-4 col-sm-6">
                                <label class="form-label"><i class="fas fa-building me-1"></i>ชื่อผู้จำหน่าย</label>
                                <input type="text" name="seller_name" class="form-control" 
                                       value="{{request('seller_name')}}" 
                                       placeholder="ชื่อผู้จำหน่าย">
                            </div> --}}

                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <label class="form-label"><i class="fas fa-building me-1"></i>ชื่อผู้จำหน่าย</label>
                                <select name="wholesale_id" id="" class="form-select select2" style="width: 100%;">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($wholesale as $item)
                                        <option value="{{ $item->id }}" {{ request('wholesale_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->wholesale_name_th }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>ค้นหา
                                    </button>
                                    <a href="{{route('report.input-tax')}}" class="btn btn-outline-secondary">
                                        <i class="fas fa-eraser me-1"></i>ล้างการค้นหา
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- สรุปผลและส่งออกข้อมูล -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>สรุปผลการค้นหา</h6>
                   @canany(['report.inputtax.export'])
    
                    <form action="{{route('export.inputtax')}}" method="post" class="d-inline">
                        @csrf
                        @method('post')
                        <input type="hidden" name="input_tax_ids" value="{{$inputTaxs->pluck('input_tax_id')}}">
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </button>
                        
                    </form>
                    @endcanany
                </div>
                <div class="card-body py-2">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted d-block">จำนวนรายการ</small>
                            <strong>{{number_format($inputTaxs->count())}}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">มูลค่ารวม</small>
                            <strong>{{ number_format($grandTotalSum, 2) }} บาท</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">ภาษีรวม</small>
                            <strong>{{ number_format($vat, 2) }} บาท</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตารางข้อมูล -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-table me-2"></i>รายงานภาษีซื้อตามเอกสาร</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr >
                                    <th style="width: 60px;">#</th>
                                    <th style="width: 110px;">วันที่</th>
                                    <th style="width: 140px;">เลขที่เอกสาร</th>
                                    <th style="width: 80px;">ไฟล์</th>
                                    <th style="width: 140px;">เอกสารอ้างอิง</th>
                                    <th style="width: 200px;">ชื่อผู้จำหน่าย</th>
                                    <th style="width: 120px;">เลขผู้เสียภาษี</th>
                                    <th style="width: 100px;">มูลค่า</th>
                                    <th style="width: 100px;">ภาษี</th>
                                    <th style="width: 80px;">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inputTaxs as $key => $item)
                                    <tr class="text-center">
                                        <td >{{++$key}}</td>
                                        <td>
                                            <small>{{date('d/m/Y',strtotime($item->created_at))}}</small>
                                        </td>
                                        <td>
                                            <small>{{$item->input_tax_number_tax}}</small>
                                        </td>
                                        <td >
                                            @if ($item->input_tax_file)
                                                <button class="btn btn-outline-info btn-sm" 
                                                        onclick="openPdfPopup('{{ asset('storage/' . $item->input_tax_file) }}'); return false;">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-minus"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @canany(['quote.edit','quote.view'])
                                            <a href="{{route('quote.editNew',$item->quote->quote_id)}}" 
                                               class="text-decoration-none">
                                                <small>{{ $item->invoice->taxinvoice->taxinvoice_number ?? 'ไม่มีข้อมูล' }}</small>
                                            </a>
                                            @endcanany
                                        </td>
                                        <td>
                                            <small>
                                                {{ mb_substr($item->quote->quoteWholesale->wholesale_name_th ?? 'ไม่มีข้อมูล', 0, 25) }}
                                                {{ strlen($item->quote->quoteWholesale->wholesale_name_th ?? '') > 25 ? '...' : '' }}
                                            </small>
                                        </td>
                                        <td>
                                            <small>
                                                {{$item->quote->quoteWholesale->textid ?? 'ไม่มีข้อมูล'}}
                                            </small>
                                        </td>
                                        <td >
                                            <small>{{number_format($item->input_tax_service_total,2)}}</small>
                                        </td>
                                        <td >
                                            <small>{{number_format($item->input_tax_vat,2)}}</small>
                                        </td>
                                        <td >
                                            @if ($item->input_tax_file)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-3">
                                            <div class="text-muted">
                                                <i class="fas fa-search fa-2x mb-2"></i>
                                                <div>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</div>
                                                <small>กรุณาปรับเงื่อนไขการค้นหาและลองใหม่</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($inputTaxs->count() > 0)
                            <tfoot style="background: #f8f9fa;">
                                <tr>
                                    <td colspan="7" class="text-end py-2">
                                        <strong>รวมทั้งสิ้น:</strong>
                                    </td>
                                    <td class="text-end py-2">
                                        <strong>{{ number_format($grandTotalSum, 2) }}</strong>
                                    </td>
                                    <td class="text-end py-2">
                                        <strong>{{ number_format($vat, 2) }}</strong>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            // DateRangePicker
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
        
        function openPdfPopup(url) {
            window.open(url, 'PDFViewer', 'width=900,height=700,scrollbars=yes,resizable=yes');
        }
    </script>

@endsection
