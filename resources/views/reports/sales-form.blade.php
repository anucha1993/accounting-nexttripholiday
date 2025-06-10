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
                <h4 class="text-info">รายงานยอดขาย </h4>
            </div>
            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-2">
                                    <label for="">ช่วงเวลา</label>
                                    <input type="text" name="daterange" id="rangDate" class="form-control rangDate"
                                        autocomplete="off" value="" placeholder="Search by Range Date" />

                                    <input type="hidden" name="date_start">
                                    <input type="hidden" name="date_end">
                                </div>

                                <div class="col-md-2">
                                    <label for="">สถานะ</label>
                                    <select name="status" id="" class="form-select">
                                        <option value="">---กรุณาเลือก---</option>
                                        <option value="success">สำเร็จ</option>
                                        <option value="cancel">ยกเลิก</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">เงือนไข</label>
                                    <select name="column_name" class="form-select">
                                        <option @if ($request->column_name === 'all') selected @endif value="all">ทั้งหมด
                                        </option>
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
                                    <label for="">คียร์เวิร์ด</label>
                                    <input type="text" name="keyword" class="form-control" placeholder="คียร์เวิร์ด"
                                        value="{{ $request->keyword }}">
                                </div>


                                <div class="col-md-2">
                                    <br>
                                    <button type="submit" class="btn  btn-info float-end ml-2">แสดงรายงาน</button>
                                </div>
                                <div class="col-md-1">
                                    <br>
                                    <a href="{{ route('report.sales') }}"
                                        class="btn  btn-danger float-end ml-2">ล้างการค้นหา</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="">โฮลเซลล์</label>
                                    <select name="wholsale_id" id="" class="form-select">
                                        <option value="">---กรุณาเลือก---</option>
                                        @forelse ($wholesales as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($request->wholsale_id == $item->id) selected @endif>{{ $item->code }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">ประเทศ</label>
                                    <select name="country_id" id="" class="form-select">
                                        <option value="">---กรุณาเลือก---</option>
                                        @forelse ($country as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($request->country_id == $item->id) selected @endif>{{ $item->iso2 }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">เซลล์ผู้ขาย</label>
                                    <select name="sale_id" id="" class="form-select">
                                        <option value="">---กรุณาเลือก---</option>
                                        @forelse ($sales as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($request->sale_id == $item->id) selected @endif>{{ $item->name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>


                                <div class="col-md-2">
                                    <label for="">ประเภทคำนวนค่าคอมมิชชั่น</label>
                                    <select name="commission_mode" class="form-select">
                                        <option @if ($request->commission_mode == 'qt') selected @endif value="qt">แบบ QT
                                        </option>
                                        <option @if ($request->commission_mode == 'total') selected @endif value="total">แบบรวม
                                        </option>
                                    </select>
                                </div>

                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </div>





        <div class="card">
            <div class="card-header">
                <h3 class="text-info">Sales Report</h3><br>
                <form action="{{ route('export.sales') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="taxinvoice_ids" value="{{ $taxinvoices->pluck('taxinvoice_id') }}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To
                        Excel</button>
                </form>
            </div>
            <div class="responsive table-responsive ">
                <div class="card-body">
                    @if ($request->commission_mode !== 'total')
                        <table class="table table quote-table " style="font-size: 12px; width: 100%">
                            <thead>
                                <tr>
                                    <th>Quotes</th>
                                    <th>ช่วงเวลาเดินทาง</th>
                                    <th>โฮลเซลล์</th>
                                    <th>ชื่อลูกค้า</th>
                                    <th>ประเทศ</th>
                                    <th>แพคเกจทัวร์ที่ซื้อ</th>
                                    <th>เซลล์ผู้ขาย</th>
                                    <th>PAX</th>
                                    <th>ค่าบริการ</th>
                                    <th>ส่วนลด</th>
                                    <th>ยอดรวมสุทธิ</th>
                                    <th>ยอดชำระโฮลเซลล์</th>
                                    <th>ต้นทุนอื่นๆ</th>
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
                                       $paymentWhosale = $item->invoice->quote->GetDepositWholesale();
                                        $withholdingTaxAmount =
                                            $item->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                        $getTotalInputTaxVat = $item->invoice->quote?->getTotalInputTaxVat() ?? 0;

                                        $hasInputTaxFile = $item->invoice->quote
                                            ->InputTaxVat()
                                            ->whereNotNull('input_tax_file')
                                            ->exists();

                                        $paymentInputtaxTotal = $hasInputTaxFile
                                            ? $withholdingTaxAmount - $getTotalInputTaxVat
                                            : $withholdingTaxAmount + $getTotalInputTaxVat;

                                        $deposit = $item->invoice->quote->GetDepositWholesale();
                                        $refund = $item->invoice->quote->GetDepositWholesaleRefund();
                                        $profit = $deposit - ($refund - $paymentInputtaxTotal);

                                        $totalSale = $item->invoice->quote->quote_grand_total - $profit;
                                        $totalProfit = $totalSale - $paymentInputtaxTotal;

                                        $people = $item->invoice->quote->quote_pax_total;
                                        // $profitPerPerson = $people > 0 ? $totalProfit / $people : 0;
                                        $profitPerPerson = $people > 0 ? $totalSale / $people : 0;
                                        $totalSales += $totalSale;
                                        $WhosaleTotal += $profit;
                                        $granTotal += $item->invoice->quote->quote_grand_total;
                                        $discountTotal += $item->invoice->quote->quote_discount;
                                        $serviceTotal +=
                                            $item->invoice->quote->quote_grand_total +
                                            $item->invoice->quote->quote_discount;
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
                                            {{ date('d/m/Y', strtotime($item->invoice->quote->quote_date_start)) }}</td>
                                        <td>{{ $item->invoice->quote->quoteWholesale->code }}</td>
                                        <td>{{ $item->invoice->customer->customer_name }}</td>
                                        <td>{{ $item->invoice->quote->quoteCountry->iso2 }}</td>
                                        <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $item->invoice->quote->quote_tour_name ?? $item->invoice->quote->quote_tour_name1 }}">{{ Str::limit($item->invoice->quote->quote_tour_name ?? $item->invoice->quote->quote_tour_name1, 20) }}</span>
                                        </td>
                                        <td>{{ $item->invoice->quote->Salename->name }}</td>
                                        <td>{{ $people }}</td>
                                        <td>{{ number_format($item->invoice->quote->quote_grand_total + $item->invoice->quote->quote_discount, 2) }}
                                        </td>
                                        <td>{{ number_format($item->invoice->quote->quote_discount, 2) }}</td>
                                        <td>{{ number_format($paymentWhosale, 2) }}</td>
                                        <td>{{ number_format($profit, 2) }}</td>
                                        <td>{{ number_format($paymentInputtaxTotal, 2) }}</td>
                                        <td>{{ number_format($totalSale, 2) }}</td>
                                        <td>{{ number_format($profitPerPerson, 2) }}</td>

                                        <td>{{ number_format($result['calculated'], 2) }}</td>
                                        <td>({{ number_format($result['amount'], 2) }}) {{ $result['group_name'] }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="18" class="text-center text-muted">ไม่พบรายการ</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot>
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
                                    <th colspan="7"></th>
                                    <th class="text-danger">{{ number_format($paxTotal) }}</th>
                                    <th class="text-danger">{{ number_format($serviceTotal, 2) }}</th>
                                    <th class="text-danger">{{ number_format($discountTotal, 2) }}</th>
                                    <th class="text-danger">{{ number_format($granTotal, 2) }}</th>
                                    <th class="text-danger">{{ number_format($WhosaleTotal, 2) }}</th>
                                    <th class="text-danger">{{ number_format($InputtaxTotal, 2) }}</th>
                                    <th class="text-danger">{{ number_format($totalSales, 2) }}</th>
                                    <th class="text-danger">{{ number_format($totalPeople, 2) }}</th>
                                    <th class="text-danger">
                                        {{ $request->commission_mode === 'total' ? number_format($commissionTotal, 2) : number_format($totalMath, 2) }}
                                    </th>
                                    <th class="text-danger">
                                        {{ $request->commission_mode === 'total' ? $commissionGroupName : '-' }}
                                    </th>
                                </tr>
                            </tfoot>


                        </table>
                    @else
                        <table class="table table quote-table " style="font-size: 12px; width: 100%">
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
                                        $saleName =
                                            optional($groupedQuotes->first()->invoice->quote->Salename)->name ??
                                            'ไม่ระบุเซลล์';

                                        $paxTotal = $groupedQuotes->sum(
                                            fn($i) => $i->invoice->quote->quote_pax_total ?? 0,
                                        );
                                        $serviceTotal = $groupedQuotes->sum(
                                            fn($i) => ($i->invoice->quote->quote_grand_total ?? 0) +
                                                ($i->invoice->quote->quote_discount ?? 0),
                                        );
                                        $discountTotal = $groupedQuotes->sum(
                                            fn($i) => $i->invoice->quote->quote_discount ?? 0,
                                        );
                                        $grandTotal = $groupedQuotes->sum(
                                            fn($i) => $i->invoice->quote->quote_grand_total ?? 0,
                                        );

                                        $wholesaleTotal = $groupedQuotes->sum(function ($i) {
                                            $deposit = $i->invoice->quote->GetDepositWholesale();
                                            $refund = $i->invoice->quote->GetDepositWholesaleRefund();
                                            $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                            $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                            $hasInputTaxFile = $i->invoice->quote
                                                ->InputTaxVat()
                                                ->whereNotNull('input_tax_file')
                                                ->exists();
                                            $paymentInputTax = $hasInputTaxFile
                                                ? $withholding - $inputTax
                                                : $withholding + $inputTax;
                                            return $deposit - ($refund - $paymentInputTax);
                                        });

                                        $otherCostTotal = $groupedQuotes->sum(function ($i) {
                                            $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                            $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                            $hasInputTaxFile = $i->invoice->quote
                                                ->InputTaxVat()
                                                ->whereNotNull('input_tax_file')
                                                ->exists();
                                            return $hasInputTaxFile
                                                ? $withholding - $inputTax
                                                : $withholding + $inputTax;
                                        });

                                        $totalSale = $serviceTotal - $wholesaleTotal;
                                        $profitAvgPerPax = $paxTotal > 0 ? $totalSale / $paxTotal : 0;

                                        $mode = $request->commission_mode ?? 'qt';
                                        $people = $paxTotal;
                                        $result = ['amount' => 0, 'group_name' => '-', 'calculated' => 0];

                                        if ($saleId) {
                                            $res = calculateCommission($totalSale, $saleId, $mode, $people);
                                            $result['amount'] = $res['amount']; // เช่น 10%
                                            $result['group_name'] = $res['group_name'];
                                            $result['calculated'] = $res['calculated']; // ✅ ค่าคอมที่แท้จริง
                                            $result['type'] = $res['type']; // ✅ ค่าคอมที่แท้จริง
                                            $totalMath += $res['calculated'];
                                        }

                                        $totalMath += $result['calculated']; // ✅ ค่าคอมรวม
                                    @endphp

                                    <tr>
                                        <td>{{ $quoteCount }}</td>
                                        <td>{{ $saleName }}</td>
                                        <td>{{ number_format($paxTotal) }}</td>
                                        <td>{{ number_format($serviceTotal, 2) }}</td>
                                        <td>{{ number_format($serviceTotal, 2) }}</td> {{-- ค่าบริการซ้ำ ตามที่คุณระบุ --}}
                                        <td>{{ number_format($discountTotal, 2) }}</td>
                                        <td>{{ number_format($grandTotal, 2) }}</td>
                                        <td>{{ number_format($wholesaleTotal, 2) }}</td>
                                        <td>{{ number_format($otherCostTotal, 2) }}</td>
                                        <td>{{ number_format($totalSale, 2) }}</td>
                                        <td>{{ number_format($profitAvgPerPax, 2) }}</td>
                                        <td>{{ number_format($result['calculated'], 2) }}</td>
                                        <td>
                                            @if ($result['type'] === 'step-QT' || $result['type'] === 'step-Total')
                                                ({{ number_format($result['amount']) . 'บาท' }})
                                                {{ $result['group_name'] }}
                                        </td>
                                    @else
                                        ({{ number_format($result['amount']) . '%' }})
                                        {{ $result['group_name'] }}</td>
                                @endif


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
                            $sumGrand = 0;
                            $sumWholesale = 0;
                            $sumOtherCost = 0;
                            $sumProfit = 0;
                            $sumCommission = 0;

                            foreach ($taxinvoices as $saleId => $groupedQuotes) {
                                $paxTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_pax_total ?? 0);
                                $serviceTotal = $groupedQuotes->sum(
                                    fn($i) => ($i->invoice->quote->quote_grand_total ?? 0) +
                                        ($i->invoice->quote->quote_discount ?? 0),
                                );
                                $discountTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_discount ?? 0);
                                $grandTotal = $groupedQuotes->sum(fn($i) => $i->invoice->quote->quote_grand_total ?? 0);

                                $wholesaleTotal = $groupedQuotes->sum(function ($i) {
                                    $deposit = $i->invoice->quote->GetDepositWholesale();
                                    $refund = $i->invoice->quote->GetDepositWholesaleRefund();
                                    $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                    $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                    $hasInputTaxFile = $i->invoice->quote
                                        ->InputTaxVat()
                                        ->whereNotNull('input_tax_file')
                                        ->exists();
                                    $paymentInputTax = $hasInputTaxFile
                                        ? $withholding - $inputTax
                                        : $withholding + $inputTax;
                                    return $deposit - ($refund - $paymentInputTax);
                                });

                                $otherCostTotal = $groupedQuotes->sum(function ($i) {
                                    $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                    $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                    $hasInputTaxFile = $i->invoice->quote
                                        ->InputTaxVat()
                                        ->whereNotNull('input_tax_file')
                                        ->exists();
                                    return $hasInputTaxFile ? $withholding - $inputTax : $withholding + $inputTax;
                                });

                                $totalSale = $serviceTotal - $wholesaleTotal;
                                $people = $paxTotal;
                                $mode = $request->commission_mode ?? 'qt';

                                $res = calculateCommission($totalSale, $saleId, $mode, $people);
                                $commission = $res['calculated'] ?? 0;

                                // ✔ สะสมรวม
                                $sumQuotes += $groupedQuotes->count();
                                $sumPax += $paxTotal;
                                $sumService += $serviceTotal;
                                $sumDiscount += $discountTotal;
                                $sumGrand += $grandTotal;
                                $sumWholesale += $wholesaleTotal;
                                $sumOtherCost += $otherCostTotal;
                                $sumProfit += $totalSale;
                                $sumCommission += $commission;
                            }

                            $sumProfitAvg = $taxinvoices->sum(function ($quotes) {
                                $pax = $quotes->sum(fn($i) => $i->invoice->quote->quote_pax_total ?? 0);

                                $service = $quotes->sum(
                                    fn($i) => ($i->invoice->quote->quote_grand_total ?? 0) +
                                        ($i->invoice->quote->quote_discount ?? 0),
                                );

                                $wholesale = $quotes->sum(function ($i) {
                                    $deposit = $i->invoice->quote->GetDepositWholesale();
                                    $refund = $i->invoice->quote->GetDepositWholesaleRefund();
                                    $withholding = $i->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                    $inputTax = $i->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                    $hasInputTaxFile = $i->invoice->quote
                                        ->InputTaxVat()
                                        ->whereNotNull('input_tax_file')
                                        ->exists();
                                    $paymentInputTax = $hasInputTaxFile
                                        ? $withholding - $inputTax
                                        : $withholding + $inputTax;
                                    return $deposit - ($refund - $paymentInputTax);
                                });

                                $profit = $service - $wholesale;

                                return $pax > 0 ? $profit / $pax : 0;
                            });
                        @endphp

                        <tr class="text-danger fw-bold">
                            <td>{{ $sumQuotes }}</td>
                            <td class="text-end">รวมทั้งหมด</td>
                            <td>{{ number_format($sumPax) }}</td>
                            <td>{{ number_format($sumService, 2) }}</td>
                            <td>{{ number_format($sumService, 2) }}</td>
                            <td>{{ number_format($sumDiscount, 2) }}</td>
                            <td>{{ number_format($sumGrand, 2) }}</td>
                            <td>{{ number_format($sumWholesale, 2) }}</td>
                            <td>{{ number_format($sumOtherCost, 2) }}</td>
                            <td>{{ number_format($sumProfit, 2) }}</td>
                            <td>{{ number_format($sumProfitAvg, 2) }}</td>
                            <td>{{ number_format($sumCommission, 2) }}</td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                    </tbody>
                    </table>
                    @endif
                </div>

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
