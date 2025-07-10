@extends('layouts.template')

@section('content')
<style>
    /* Basic Styles */
    .main-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px 0;
        min-height: 100vh;
    }
    
    .card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: #fff;
    }
    
    .card-header {
        background: linear-gradient(135deg, #4a6cf7 0%, #667eea 100%);
        color: #fff;
        border-bottom: none;
        padding: 15px 25px;
        font-weight: 600;
        border-radius: 8px 8px 0 0;
    }
    
    .card-header h3, .card-header h4, .card-header h5, .card-header h6 {
        color: #fff;
        margin: 0;
    }
    
    .card-body {
        padding: 25px;
    }
    
    /* Form Controls */
    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        padding: 10px 15px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        transform: translateY(-1px);
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 15px;
        color: #2d3748;
    }
    
    .form-label i {
        color: #667eea;
        margin-right: 6px;
    }
    
    /* Buttons */
    .btn {
        border-radius: 6px;
        padding: 10px 20px;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #fff;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        border-color: #48bb78;
        color: #fff;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background: #6c757d;
        transform: translateY(-1px);
    }
    
    /* Table */
    .table {
        font-size: 14px;
        margin: 0;
    }
    
    .table th {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
        color: #fff;
        border: none;
        padding: 12px 8px;
        font-weight: 600;
        font-size: 12px;
        text-align: center;
    }
    
    .table td {
        padding: 10px 8px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        font-size: 13px;
    }
    
    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: scale(1.005);
        transition: all 0.2s ease;
    }
    
    .table tfoot th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-weight: 700;
        padding: 12px 8px;
    }
    
    /* Links */
    a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }
    
    a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    /* Text Colors */
    .text-danger {
        color: #e53e3e !important;
        font-weight: 600;
    }
    
    .text-info {
        color: #667eea !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .main-container {
            padding: 15px 0;
        }
        .card-body {
            padding: 20px;
        }
        .table {
            font-size: 11px;
        }
        .table th, .table td {
            padding: 6px 4px;
        }
        .form-control, .form-select {
            font-size: 14px;
            padding: 8px 12px;
        }
    }
    
    span[titlespan]:hover::after {
        content: attr(titlespan);
        background-color: #f0f0f0;
        padding: 5px;
        border: 1px solid #ccc;
        position: absolute;
        z-index: 1;
    }

    /* Select2 Styles */
    .select2-container {
        width: 100% !important;
        font-family: inherit;
    }

    .select2-container--default .select2-selection--single {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        height: 45px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #fff;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single:hover {
        border-color: #667eea;
    }

    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default .select2-selection--single:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        transform: translateY(-1px);
        outline: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #2d3748;
        line-height: 41px;
        padding-left: 15px;
        padding-right: 35px;
        font-size: 15px;
        font-weight: 500;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
        font-style: italic;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 41px;
        right: 10px;
        width: 25px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #667eea transparent transparent transparent;
        border-style: solid;
        border-width: 6px 5px 0 5px;
        height: 0;
        left: 50%;
        margin-left: -5px;
        margin-top: -3px;
        position: absolute;
        top: 50%;
        width: 0;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #667eea transparent;
        border-width: 0 5px 6px 5px;
    }

    .select2-dropdown {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
    }

    .select2-container--default .select2-results__option {
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.2s ease;
        color: #2d3748;
        font-weight: 500;
    }

    .select2-container--default .select2-results__option--highlighted {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        color: #667eea;
        font-weight: 600;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-weight: 600;
    }

    .select2-container--default .select2-results__option[aria-selected=true].select2-results__option--highlighted {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        color: #fff;
    }

    .select2-search--dropdown {
        padding: 8px;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
    }

    .select2-search--dropdown .select2-search__field {
        border: 2px solid #e9ecef;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 14px;
        width: 100%;
        transition: all 0.2s ease;
    }

    .select2-search--dropdown .select2-search__field:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }

    .select2-results__message {
        padding: 10px 15px;
        color: #6c757d;
        font-style: italic;
        text-align: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        color: #dc3545;
        cursor: pointer;
        float: right;
        font-weight: bold;
        margin-right: 30px;
        margin-top: 10px;
        position: relative;
        font-size: 18px;
        line-height: 1;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear:hover {
        color: #c82333;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .select2-container--default .select2-selection--single {
            height: 40px;
            font-size: 14px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            font-size: 14px;
            padding-left: 12px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    }

    /* Summary Cards Styles */
    .summary-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .summary-card .card-body {
        padding: 20px;
    }

    .summary-card .icon-box {
        transition: all 0.3s ease;
    }

    .summary-card:hover .icon-box {
        transform: scale(1.1);
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .summary-card h4 {
        animation: pulse 2s infinite;
    }
</style>

<div class="main-container">
    <div class="container-fluid">

        <!-- ฟอร์มค้นหาและรายงาน -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-chart-line me-2"></i>รายงานยอดขาย</h4>
            </div>
            <div class="card-body">
                <form action="">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-calendar me-1"></i>ช่วงเวลา</label>
                            <input type="text" name="daterange" id="rangDate" class="form-control rangDate"
                                autocomplete="off" value="" placeholder="เลือกช่วงวันที่" />
                            <input type="hidden" name="date_start">
                            <input type="hidden" name="date_end">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-flag me-1"></i>สถานะ</label>
                            <select name="status" class="form-select">
                                <option value="">ทั้งหมด</option>
                                <option value="success">สำเร็จ</option>
                                <option value="cancel">ยกเลิก</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-filter me-1"></i>เงื่อนไข</label>
                            <select name="column_name" class="form-select">
                                <option @if ($request->column_name === 'all') selected @endif value="all">ทั้งหมด</option>
                                <option @if ($request->column_name === 'quote_number') selected @endif value="quote_number">
                                            เลขที่ใบเสนอราคา</option>
                                        <option @if ($request->column_name === 'taxinvoice_number') selected @endif value="taxinvoice_number">
                                            เลขที่ใบกำกับภาษี</option>
                                        <option @if ($request->column_name === 'invoice_number') selected @endif value="invoice_number">
                                            เลขที่ใบแจ้งหนี้</option>
                                        <option @if ($request->column_name === 'invoice_booking') selected @endif value="invoice_booking">
                                            เลขที่ใบจองทัวร์</option>
                                        <option @if ($request->column_name === 'customer_name') selected @endif value="customer_name">
                                            ชื่อลูกค้า</option>
                                        <option @if ($request->column_name === 'customer_texid') selected @endif value="customer_texid">
                                            เลขประจำตัวผู้เสียภาษี</option>
                                    </select>
                                </div>
                                
                                
                                <div class="col-md-2">
                                    <label class="form-label"><i class="fas fa-search me-1"></i>คียร์เวิร์ด</label>
                                    <input type="text" name="keyword" class="form-control" placeholder="คียร์เวิร์ด"
                                        value="{{ $request->keyword }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label"><i class="fas fa-building me-1"></i>โฮลเซลล์</label>
                                    <select name="wholsale_id" class="form-select select2-dropdown" data-placeholder="เลือกโฮลเซลล์">
                                        <option value="">ทั้งหมด</option>
                                        @forelse ($wholesales as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($request->wholsale_id == $item->id) selected @endif>{{ $item->code }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label"><i class="fas fa-globe me-1"></i>ประเทศ</label>
                                    <select name="country_id" class="form-select select2-dropdown" data-placeholder="เลือกประเทศ">
                                        <option value="">ทั้งหมด</option>
                                        @forelse ($country as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($request->country_id == $item->id) selected @endif>{{ $item->iso2 }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label class="form-label"><i class="fas fa-user me-1"></i>เซลล์ผู้ขาย</label>
                                    <select name="sale_id" class="form-select select2-dropdown" data-placeholder="เลือกเซลล์ผู้ขาย">
                                        <option value="">ทั้งหมด</option>
                                        @forelse ($sales as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($request->sale_id == $item->id) selected @endif>{{ $item->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label"><i class="fas fa-bullhorn me-1"></i>ที่มาของลูกค้า</label>
                                    <select name="campaign_source_id" class="form-select select2-dropdown" data-placeholder="เลือกที่มา">
                                        <option value="">ทั้งหมด</option>
                                        @foreach ($campaignSource as $source)
                                            <option value="{{ $source->campaign_source_id }}" @if ($request->campaign_source_id == $source->campaign_source_id) selected @endif>
                                                {{ $source->campaign_source_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label"><i class="fas fa-calculator me-1"></i>ประเภทคำนวนค่าคอมมิชชั่น</label>
                                    <select name="commission_mode" class="form-select">
                                        <option @if ($request->commission_mode == 'qt') selected @endif value="qt">แบบ QT</option>
                                        <option @if ($request->commission_mode == 'total') selected @endif value="total">แบบรวม</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-chart-bar me-1"></i>แสดงรายงาน
                                        </button>
                                        <a href="{{ route('report.sales') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-eraser me-1"></i>ล้างการค้นหา
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- แบ่งเส้น -->
                <hr class="my-4" style="border-top: 2px solid #e9ecef; margin: 30px 0;">

                <!-- Summary Cards -->
                @php
                    // คำนวณยอดรวมสำหรับแสดงใน Summary Cards
                    $summaryTotalMath = 0;
                    $summaryTotalPeople = 0;
                    $summaryTotalSales = 0;
                    $summaryInputtaxTotal = 0;
                    $summaryWhosaleTotal = 0;
                    $summaryGranTotal = 0;
                    $summaryDiscountTotal = 0;
                    $summaryServiceTotal = 0;
                    $summaryPaxTotal = 0;
                    $summaryTotalCost = 0; // ต้นทุนรวม
                    $summaryTotalProfit = 0; // กำไรรวม

                    if ($request->commission_mode !== 'total') {
                        // คำนวณแบบปกติ
                        foreach ($taxinvoices as $item) {
                            $withholdingTaxAmount = $item->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                            $getTotalInputTaxVat = $item->invoice->quote?->getTotalInputTaxVat() ?? 0;
                            $hasInputTaxFile = $item->invoice->quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();
                            $paymentInputtaxTotal = $hasInputTaxFile ? $withholdingTaxAmount - $getTotalInputTaxVat : $withholdingTaxAmount + $getTotalInputTaxVat;
                            
                            $people = $item->invoice->quote->quote_pax_total;
                            
                            // 1. ยอดสุทธิ = ค่าบริการ + ส่วนลด
                            $serviceAmount = $item->invoice->quote->quote_grand_total + $item->invoice->quote->quote_discount;
                            $discountAmount = $item->invoice->quote->quote_discount;
                            $netAmount = $serviceAmount; // ค่าบริการ+ส่วนลด = ยอดสุทธิ (เนื่องจาก serviceAmount คำนวณแล้ว)
                            
                            // 2. ต้นทุนรวม = ยอดชำระโฮลเซลล์ + ต้นทุนอื่นๆ
                            $wholesalePayment = $item->invoice->quote->GetDepositWholesale() - $item->invoice->quote->GetDepositWholesaleRefund();
                            $totalCost = $wholesalePayment + $paymentInputtaxTotal;
                            
                            // 3. กำไร = ยอดสุทธิ - ต้นทุนรวม
                            $profit = $netAmount - $totalCost;
                            
                            $profitPerPerson = $people > 0 ? $profit / $people : 0;
                            
                            $summaryPaxTotal += $people;
                            $summaryServiceTotal += $serviceAmount;
                            $summaryDiscountTotal += $discountAmount;
                            $summaryGranTotal += $netAmount; // ยอดสุทธิ
                            $summaryWhosaleTotal += $wholesalePayment;
                            $summaryInputtaxTotal += $paymentInputtaxTotal;
                            $summaryTotalCost += $totalCost; // ต้นทุนรวม
                            $summaryTotalProfit += $profit; // กำไรรวม
                            $summaryTotalPeople += $profitPerPerson;
                            
                            $mode = $request->commission_mode ?? 'qt';
                            $saleId = $item->invoice->quote->Salename->id ?? null;
                            if ($saleId) {
                                $res = calculateCommission($profitPerPerson, $saleId, $mode, $people);
                                $summaryTotalMath += $res['calculated'];
                            }
                        }
                    } else {
                        // คำนวณแบบ Total
                        foreach ($taxinvoices as $saleId => $groupedQuotes) {
                            $paxTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_pax_total ?? 0);
                            
                            // 1. ยอดสุทธิ = ค่าบริการ + ส่วนลด
                            $serviceTotal = $groupedQuotes->sum(fn($i) => ($i->invoice->quote->quote_grand_total ?? 0) + ($i->invoice->quote->quote_discount ?? 0));
                            $discountTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_discount ?? 0);
                            $netTotal = $serviceTotal; // ยอดสุทธิ
                            
                            // 2. ต้นทุนรวม = ยอดชำระโฮลเซลล์ + ต้นทุนอื่นๆ
                            $wholesaleTotal = $groupedQuotes->sum(function ($i) {
                                return $i->invoice->quote->GetDepositWholesale() - $i->invoice->quote->GetDepositWholesaleRefund();
                            });
                            
                            $otherCostTotal = $groupedQuotes->sum(function ($i) {
                                $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                $hasInputTaxFile = $i->invoice->quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();
                                return $hasInputTaxFile ? $withholding - $inputTax : $withholding + $inputTax;
                            });
                            
                            $totalCost = $wholesaleTotal + $otherCostTotal;
                            
                            // 3. กำไร = ยอดสุทธิ - ต้นทุนรวม  
                            $totalProfit = $netTotal - $totalCost;
                            
                            $people = $paxTotal;
                            $mode = $request->commission_mode ?? 'qt';
                            $res = calculateCommission($totalProfit, $saleId, $mode, $people);
                            
                            $summaryPaxTotal += $paxTotal;
                            $summaryServiceTotal += $serviceTotal;
                            $summaryDiscountTotal += $discountTotal;
                            $summaryGranTotal += $netTotal; // ยอดสุทธิ
                            $summaryWhosaleTotal += $wholesaleTotal;
                            $summaryInputtaxTotal += $otherCostTotal;
                            $summaryTotalCost += $totalCost; // ต้นทุนรวม
                            $summaryTotalProfit += $totalProfit; // กำไรรวม
                            $summaryTotalMath += $res['calculated'];
                        }
                    }
                @endphp

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body text-white text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: rgba(255,255,255,0.9);">PAX รวม</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($summaryPaxTotal) }}</h4>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-users" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
                            <div class="card-body text-white text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: rgba(255,255,255,0.9);">ยอดสุทธิรวม</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($summaryGranTotal, 2) }}</h4>
                                        <small style="color: rgba(255,255,255,0.8);">บาท</small>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-money-bill-wave" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                            <div class="card-body text-white text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: rgba(255,255,255,0.9);">ต้นทุนรวม</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($summaryTotalCost, 2) }}</h4>
                                        <small style="color: rgba(255,255,255,0.8);">บาท</small>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-coins" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-white text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: rgba(255,255,255,0.9);">กำไรรวม</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($summaryTotalProfit, 2) }}</h4>
                                        <small style="color: rgba(255,255,255,0.8);">บาท</small>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-chart-line" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- แถวที่สองของ Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: #666;">ค่าบริการรวม</h6>
                                        <h4 class="mb-0 fw-bold" style="color: #333;">{{ number_format($summaryServiceTotal, 2) }}</h4>
                                        <small style="color: #666;">บาท</small>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(0,0,0,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-credit-card" style="font-size: 24px; color: #333;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333;">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: #666;">ส่วนลดรวม</h6>
                                        <h4 class="mb-0 fw-bold" style="color: #333;">{{ number_format($summaryDiscountTotal, 2) }}</h4>
                                        <small style="color: #666;">บาท</small>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(0,0,0,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-tags" style="font-size: 24px; color: #333;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%); color: #333;">
                            <div class="card-body text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: #666;">ยอดชำระโฮลเซลล์</h6>
                                        <h4 class="mb-0 fw-bold" style="color: #333;">{{ number_format($summaryWhosaleTotal, 2) }}</h4>
                                        <small style="color: #666;">บาท</small>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(0,0,0,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-building" style="font-size: 24px; color: #333;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm summary-card h-100" style="background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);">
                            <div class="card-body text-white text-center">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1" style="color: rgba(255,255,255,0.9);">คอมมิชชั่นรวม</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($summaryTotalMath, 2) }}</h4>
                                        <small style="color: rgba(255,255,255,0.8);">บาท</small>
                                    </div>
                                    <div class="icon-box" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-percentage" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ปุ่ม Export และ Header ตาราง -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="fas fa-table me-2"></i>Sales Report</h5>
                    <form action="{{ route('export.sales') }}" method="post" class="d-inline">
                        @csrf
                        @method('post')
                        <input type="hidden" name="taxinvoice_ids" value="{{ $taxinvoices->pluck('taxinvoice_id') }}">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-excel me-1"></i>Export Excel
                        </button>
                    </form>
                </div>

                <!-- ตาราง -->
                <div class="table-responsive">
                    @if ($request->commission_mode !== 'total')
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Quotes</th>
                                    <th>ช่วงเวลาเดินทาง</th>
                                    <th>โฮลเซลล์</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>ประเทศ</th>
                                    <th>แพคเกจทัวร์ที่ซื้อ</th>
                                    <th>ที่มา</th>
                                    <th>เซลล์ผู้ขาย</th>
                                    <th>PAX</th>
                                    <th>ค่าบริการ</th>
                                    <th>ส่วนลด</th>
                                    <th>ยอดรวมสุทธิ</th>
                                    <th>ยอดชำระโฮลเซลล์</th>
                                    <th>ต้นทุนอื่นๆ</th>
                                    <th>ต้นทุนรวม</th>
                                    <th>กำไร</th>
                                    <th>กำไรเฉลี่ย:คน</th>
                                    <th>คอมมิชชั่นทั้งสิ้น</th>
                                    <th>CommissionGroup</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalMath = 0;
                                    $totalPeople = 0;
                                    $totalSales = 0;
                                    $InputtaxTotal = 0;
                                    $WhosaleTotal = 0;
                                    $granTotal = 0;
                                    $discountTotal = 0;
                                    $serviceTotal = 0;
                                    $paxTotal = 0;
                                    $paymentWhosale = 0;
                                @endphp
                                @forelse ($taxinvoices as $item)
                                    @php
                                        $withholdingTaxAmount = $item->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                        $getTotalInputTaxVat = $item->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                        $hasInputTaxFile = $item->invoice->quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();
                                        $paymentInputtaxTotal = $hasInputTaxFile ? $withholdingTaxAmount - $getTotalInputTaxVat : $withholdingTaxAmount + $getTotalInputTaxVat;

                                        $people = $item->invoice->quote->quote_pax_total;
                                        
                                        // 1. ยอดสุทธิ = ค่าบริการ + ส่วนลด  
                                        $serviceAmount = $item->invoice->quote->quote_grand_total + $item->invoice->quote->quote_discount;
                                        $discountAmount = $item->invoice->quote->quote_discount;
                                        $netAmount = $serviceAmount; // ยอดสุทธิ
                                        
                                        // 2. ต้นทุนรวม = ยอดชำระโฮลเซลล์ + ต้นทุนอื่นๆ
                                        $wholesalePayment = $item->invoice->quote->GetDepositWholesale() - $item->invoice->quote->GetDepositWholesaleRefund();
                                        $totalCost = $wholesalePayment + $paymentInputtaxTotal;
                                        
                                        // 3. กำไร = ยอดสุทธิ - ต้นทุนรวม
                                        $profit = $netAmount - $totalCost;
                                        
                                        $profitPerPerson = $people > 0 ? $profit / $people : 0;
                                        
                                        // สะสมยอดรวม
                                        $totalSales += $profit;
                                        $WhosaleTotal += $wholesalePayment;
                                        $granTotal += $netAmount;
                                        $discountTotal += $discountAmount;
                                        $serviceTotal += $serviceAmount;
                                        $paxTotal += $people;
                                        $InputtaxTotal += $paymentInputtaxTotal;
                                        $totalPeople += $profitPerPerson;

                                        $mode = $request->commission_mode ?? 'qt';
                                        $saleId = $item->invoice->quote->Salename->id ?? null;
                                        $result = ['amount' => 0, 'group_name' => '-', 'calculated' => 0];

                                        if ($saleId) {
                                            $res = calculateCommission($profitPerPerson, $saleId, $mode, $people);
                                            $result['amount'] = $res['amount']; // ค่า base เช่น 10, 100
                                            $result['group_name'] = $res['group_name']; // ชื่อกลุ่ม
                                            $result['calculated'] = $res['calculated']; // ✅ ค่าคอมที่แท้จริง
                                            $totalMath += $res['calculated'];
                                        }
                                    @endphp

                                    <tr>
                                        <td><a target="_blank"
                                                href="{{ route('quote.editNew', $item->invoice->quote->quote_id) }}">{{ $item->invoice->quote->quote_number ?? 'ใบเสนอราคาถูกลบ' }}</a>
                                        </td>
                                        <td>{{ date('d/m/Y', strtotime($item->invoice->quote->quote_date_start)) }} -
                                            {{ date('d/m/Y', strtotime($item->invoice->quote->quote_date_end)) }}</td>
                                        <td>{{ $item->invoice->quote->quoteWholesale->code }}</td>
                                        
                                        <td>{{ $item->invoice->customer->customer_name }}</td>
                                        <td>{{ $item->invoice->quote->quoteCountry->iso2 }}</td>
                                        <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $item->invoice->quote->quote_tour_name ?? $item->invoice->quote->quote_tour_name1 }}">{{ Str::limit($item->invoice->quote->quote_tour_name ?? $item->invoice->quote->quote_tour_name1, 20) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $sourceName = '';
                                                if(isset($item->invoice->customer->customer_campaign_source) && !empty($item->invoice->customer->customer_campaign_source) && isset($campaignSource)) {
                                                    $source = $campaignSource->firstWhere('campaign_source_id', $item->invoice->customer->customer_campaign_source);
                                                    $sourceName = $source ? $source->campaign_source_name : '';
                                                }
                                            @endphp
                                            {{ $sourceName ?: 'none' }}
                                        </td>
                                        <td>{{ $item->invoice->quote->Salename->name }}</td>
                                        <td>{{ $people }}</td>
                                        <td>{{ number_format($serviceAmount, 2) }}</td>
                                        <td>{{ number_format($discountAmount, 2) }}</td>
                                        <td>{{ number_format($netAmount, 2) }}</td>
                                        <td>{{ number_format($wholesalePayment, 2) }}</td>
                                        <td>{{ number_format($paymentInputtaxTotal, 2) }}</td>
                                        <td>{{ number_format($totalCost, 2) }}</td>
                                        <td>{{ number_format($profit, 2) }}</td>
                                        <td>{{ number_format($profitPerPerson, 2) }}</td>
                                        <td>{{ number_format($result['calculated'], 2) }}</td>
                                        <td>({{ number_format($result['amount'], 2) }}) {{ $result['group_name'] }}</td>
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="19" class="text-center text-muted">ไม่พบรายการ</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                                @php
                                    $commissionTotal = 0;
                                    $commissionGroupName = '-';
                                    if ($request->commission_mode === 'total') {
                                        $saleId = $request->sale_id ?? null;
                                        if ($saleId) {
                                            $result = calculateCommission($profitPerPerson, $saleId);
                                            $commissionGroupName = $result['group_name'] ?? '-';
                                            $commissionPercent = $result['percent'] ?? null;
                                            // ถ้า percent ให้คิดตามเปอร์เซ็นต์
                                            if ($commissionPercent !== null) {
                                                $commissionTotal = ($profitPerPerson * $commissionPercent) / 100;
                                            } else {
                                                $commissionTotal = $result['amount']; // fallback
                                            }
                                        }
                                    }
                                @endphp
                                <tr>
                                    <th colspan="7" style="color: #fff;"><i class="fas fa-calculator me-2"></i>รวมทั้งสิ้น:</th>
                                    <th style="color: #fff;">{{ number_format($paxTotal) }}</th>
                                    <th style="color: #fff;">{{ number_format($serviceTotal, 2) }}</th>
                                    <th style="color: #fff;">{{ number_format($discountTotal, 2) }}</th>
                                    <th style="color: #fff;">{{ number_format($granTotal, 2) }}</th>
                                    <th style="color: #fff;">{{ number_format($WhosaleTotal, 2) }}</th>
                                    <th style="color: #fff;">{{ number_format($InputtaxTotal, 2) }}</th>
                                    <th style="color: #fff;">{{ number_format($WhosaleTotal + $InputtaxTotal, 2) }}</th>
                                    <th style="color: #fff;">{{ number_format($totalSales, 2) }}</th>
                                    <th style="color: #fff;">{{ number_format($totalPeople, 2) }}</th>
                                    <th style="color: #fff;">
                                        {{ $request->commission_mode === 'total' ? number_format($commissionTotal, 2) : number_format($totalMath, 2) }}
                                    </th>
                                    <th style="color: #fff;">
                                        {{ $request->commission_mode === 'total' ? $commissionGroupName : '-' }}
                                    </th>
                                </tr>
                            </tfoot>

                        </table>
                    @else
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>จำนวน Quote</th>
                                    <th>เซลล์ผู้ขาย</th>
                                    <th>PAX รวม</th>
                                    <th>ค่าบริการ</th>
                                    <th>ค่าบริการ</th>
                                    <th>ส่วนลด</th>
                                    <th>ยอดรวมสุทธิ</th>
                                    <th>ยอดชำระโฮลเซลล์</th>
                                    <th>ต้นทุนอื่นๆ</th>
                                    <th>กำไร</th>
                                    <th>กำไรเฉลี่ย:คน</th>
                                    <th>คอมมิชชั่นทั้งสิ้น</th>
                                    <th>Total CommissionGroup</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalMath = 0;
                                @endphp
                                @forelse ($taxinvoices as $saleId => $groupedQuotes)
                                    @php
                                        $quoteCount = $groupedQuotes->count();
                                        $saleName = optional($groupedQuotes->first()->invoice->quote->Salename)->name ?? 'ไม่ระบุเซลล์';

                                        $paxTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_pax_total ?? 0);
                                        
                                        // 1. ยอดสุทธิ = ค่าบริการ + ส่วนลด
                                        $serviceTotal = $groupedQuotes->sum(fn($i) => ($i->invoice->quote->quote_grand_total ?? 0) + ($i->invoice->quote->quote_discount ?? 0));
                                        $discountTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_discount ?? 0);
                                        $netTotal = $serviceTotal; // ยอดสุทธิ
                                        
                                        // 2. ต้นทุนรวม = ยอดชำระโฮลเซลล์ + ต้นทุนอื่นๆ
                                        $wholesaleTotal = $groupedQuotes->sum(function ($i) {
                                            return $i->invoice->quote->GetDepositWholesale() - $i->invoice->quote->GetDepositWholesaleRefund();
                                        });

                                        $otherCostTotal = $groupedQuotes->sum(function ($i) {
                                            $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                            $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                            $hasInputTaxFile = $i->invoice->quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();
                                            return $hasInputTaxFile ? $withholding - $inputTax : $withholding + $inputTax;
                                        });
                                        
                                        $totalCost = $wholesaleTotal + $otherCostTotal;
                                        
                                        // 3. กำไร = ยอดสุทธิ - ต้นทุนรวม
                                        $totalProfit = $netTotal - $totalCost;
                                        
                                        $profitAvgPerPax = $paxTotal > 0 ? $totalProfit / $paxTotal : 0;

                                        $mode = $request->commission_mode ?? 'qt';
                                        $people = $paxTotal;
                                        $result = ['amount' => 0, 'group_name' => '-', 'calculated' => 0];

                                        if ($saleId) {
                                            $res = calculateCommission($totalProfit, $saleId, $mode, $people);
                                            $result['amount'] = $res['amount']; // เช่น 10%
                                            $result['group_name'] = $res['group_name'];
                                            $result['calculated'] = $res['calculated']; // ✅ ค่าคอมที่แท้จริง
                                            $result['type'] = $res['type']; // ✅ ค่าคอมที่แท้จริง
                                            $totalMath += $res['calculated'];
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $quoteCount }}</td>
                                        <td>{{ $saleName }}</td>
                                        <td>{{ number_format($paxTotal) }}</td>
                                        <td>{{ number_format($serviceTotal, 2) }}</td>
                                        <td>{{ number_format($serviceTotal, 2) }}</td> {{-- ค่าบริการซ้ำ ตามที่คุณระบุ --}}
                                        <td>{{ number_format($discountTotal, 2) }}</td>
                                        <td>{{ number_format($netTotal, 2) }}</td>
                                        <td>{{ number_format($wholesaleTotal, 2) }}</td>
                                        <td>{{ number_format($otherCostTotal, 2) }}</td>
                                        <td>{{ number_format($totalProfit, 2) }}</td>
                                        <td>{{ number_format($profitAvgPerPax, 2) }}</td>
                                        <td>{{ number_format($result['calculated'], 2) }}</td>
                                        <td>
                                            @if ($result['type'] === 'step-QT' || $result['type'] === 'step-Total')
                                                ({{ number_format($result['amount']) . 'บาท' }})
                                                {{ $result['group_name'] }}
                                            @else
                                                ({{ number_format($result['amount']) . '%' }})
                                                {{ $result['group_name'] }}
                                            @endif
                                        </td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted">ไม่พบรายการ</td>
                                </tr>
                    @endforelse

                    <tfoot>
                        @php
                            $sumQuotes = 0;
                            $sumPax = 0;
                            $sumService = 0;
                            $sumDiscount = 0;
                            $sumNet = 0;
                            $sumWholesale = 0;
                            $sumOtherCost = 0;
                            $sumProfit = 0;
                            $sumCommission = 0;

                            foreach ($taxinvoices as $saleId => $groupedQuotes) {
                                $paxTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_pax_total ?? 0);
                                
                                // 1. ยอดสุทธิ = ค่าบริการ + ส่วนลด
                                $serviceTotal = $groupedQuotes->sum(fn($i) => ($i->invoice->quote->quote_grand_total ?? 0) + ($i->invoice->quote->quote_discount ?? 0));
                                $discountTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_discount ?? 0);
                                $netTotal = $serviceTotal; // ยอดสุทธิ
                                
                                // 2. ต้นทุนรวม = ยอดชำระโฮลเซลล์ + ต้นทุนอื่นๆ
                                $wholesaleTotal = $groupedQuotes->sum(function ($i) {
                                    return $i->invoice->quote->GetDepositWholesale() - $i->invoice->quote->GetDepositWholesaleRefund();
                                });

                                $otherCostTotal = $groupedQuotes->sum(function ($i) {
                                    $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                    $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                    $hasInputTaxFile = $i->invoice->quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();
                                    return $hasInputTaxFile ? $withholding - $inputTax : $withholding + $inputTax;
                                });
                                
                                $totalCost = $wholesaleTotal + $otherCostTotal;
                                
                                // 3. กำไร = ยอดสุทธิ - ต้นทุนรวม
                                $totalProfit = $netTotal - $totalCost;

                                $people = $paxTotal;
                                $mode = $request->commission_mode ?? 'qt';

                                $res = calculateCommission($totalProfit, $saleId, $mode, $people);
                                $commission = $res['calculated'] ?? 0;

                                // ✔ สะสมรวม
                                $sumQuotes += $groupedQuotes->count();
                                $sumPax += $paxTotal;
                                $sumService += $serviceTotal;
                                $sumDiscount += $discountTotal;
                                $sumNet += $netTotal;
                                $sumWholesale += $wholesaleTotal;
                                $sumOtherCost += $otherCostTotal;
                                $sumProfit += $totalProfit;
                                $sumCommission += $commission;
                            }

                            $sumProfitAvg = $taxinvoices->sum(function ($quotes) {
                                $pax = $quotes->sum(fn($i) => $i->invoice->quote->quote_pax_total ?? 0);
                                
                                // 1. ยอดสุทธิ = ค่าบริการ + ส่วนลด
                                $service = $quotes->sum(fn($i) => ($i->invoice->quote->quote_grand_total ?? 0) + ($i->invoice->quote->quote_discount ?? 0));
                                
                                // 2. ต้นทุนรวม = ยอดชำระโฮลเซลล์ + ต้นทุนอื่นๆ
                                $wholesale = $quotes->sum(function ($i) {
                                    return $i->invoice->quote->GetDepositWholesale() - $i->invoice->quote->GetDepositWholesaleRefund();
                                });
                                
                                $otherCost = $quotes->sum(function ($i) {
                                    $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                    $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                    $hasInputTaxFile = $i->invoice->quote->InputTaxVat()->whereNotNull('input_tax_file')->exists();
                                    return $hasInputTaxFile ? $withholding - $inputTax : $withholding + $inputTax;
                                });
                                
                                $totalCost = $wholesale + $otherCost;

                                // 3. กำไร = ยอดสุทธิ - ต้นทุนรวม
                                $profit = $service - $totalCost;

                                return $pax > 0 ? $profit / $pax : 0;
                            });
                        @endphp

                        <tfoot style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
                            <tr>
                                <td style="color: #fff;">{{ $sumQuotes }}</td>
                                <td style="color: #fff;" class="text-end"><i class="fas fa-calculator me-2"></i>รวมทั้งหมด</td>
                                <td style="color: #fff;">{{ number_format($sumPax) }}</td>
                                <td style="color: #fff;">{{ number_format($sumService, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumService, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumDiscount, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumNet, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumWholesale, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumOtherCost, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumProfit, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumProfitAvg, 2) }}</td>
                                <td style="color: #fff;">{{ number_format($sumCommission, 2) }}</td>
                                <td style="color: #fff;">-</td>
                            </tr>
                        </tfoot>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

            // Initialize Select2 for all select elements with class select2-dropdown
            $('.select2-dropdown').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || 'เลือก...';
                },
                allowClear: true,
                language: {
                    noResults: function() {
                        return "ไม่พบผลลัพธ์";
                    },
                    searching: function() {
                        return "กำลังค้นหา...";
                    },
                    clearResult: function() {
                        return "ล้างการเลือก";
                    }
                },
                dropdownCssClass: 'select2-dropdown-custom',
                containerCssClass: 'select2-container-custom',
                width: '100%',
                minimumResultsForSearch: 0, // Always show search box
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            // Custom event handling for better UX
            $('.select2-dropdown').on('select2:open', function() {
                // Focus on search field when dropdown opens
                setTimeout(function() {
                    $('.select2-search__field').focus();
                }, 100);
            });

            $('.select2-dropdown').on('select2:close', function() {
                // Remove focus styling when closed
                $(this).blur();
            });
        });
    </script>
@endsection
