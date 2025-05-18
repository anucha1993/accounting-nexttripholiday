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
                                     <option value="{{$item->id}}" @if ($request->wholsale_id == $item->id) selected @endif >{{$item->code}}</option>
                                     @empty
                                         
                                     @endforelse
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">ประเทศ</label>
                                    <select name="country_id" id="" class="form-select">
                                        <option value="">---กรุณาเลือก---</option>
                                     @forelse ($country as $item)
                                     <option value="{{$item->id}}" @if ($request->country_id == $item->id) selected @endif >{{$item->iso2}}</option>
                                     @empty
                                         
                                     @endforelse
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">เซลล์ผู้ขาย</label>
                                    <select name="sale_id" id="" class="form-select">
                                        <option value="">---กรุณาเลือก---</option>
                                     @forelse ($sales as $item)
                                     <option value="{{$item->id}}" @if ($request->sale_id == $item->id) selected @endif >{{$item->name}}</option>
                                     @empty
                                         
                                     @endforelse
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">ประเภทคำนวนค่าคอมมิชชั่น</label>
                                    <select name="commission" id="" class="form-select">
                                        <option value="step"  @if ($request->commission == 'step') selected @endif>step</option>
                                        <option value="%"  @if ($request->commission == '%') selected @endif>%</option>
                                    
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
                    <input type="hidden" name="taxinvoice_ids" value="{{$taxinvoices->pluck('taxinvoice_id')}}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To
                        Excel</button>
                </form>
            </div>
            <div class="card-body">

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
                        @endphp
                        @forelse ($taxinvoices as $item)
                            @php
                                $withholdingTaxAmount = $item->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                $getTotalInputTaxVat = $item->invoice->quote?->getTotalInputTaxVat() ?? 0;
                                $hasInputTaxFile = $item->invoice->quote
                                    ->InputTaxVat()
                                    ->whereNotNull('input_tax_file')
                                    ->exists();

                                if ($hasInputTaxFile) {
                                    // กรณี input_tax_file !== NULL
                                    $paymentInputtaxTotal = $withholdingTaxAmount - $getTotalInputTaxVat;
                                } else {
                                    // กรณี input_tax_file === NULL
                                    $paymentInputtaxTotal = $withholdingTaxAmount + $getTotalInputTaxVat;
                                }

                                $profit =
                                    $item->invoice->quote->GetDepositWholesale() -
                                    ($item->invoice->quote->GetDepositWholesaleRefund() - $paymentInputtaxTotal);
                                 

                                $totalSale = $item->invoice->quote->quote_grand_total - $profit;
                                $totalSales +=  $totalSale;
                                $WhosaleTotal += $profit;
                                $granTotal += $item->invoice->quote->quote_grand_total;
                                $discountTotal += $item->invoice->quote->quote_discount;
                                $serviceTotal += $item->invoice->quote->quote_grand_total + $item->invoice->quote->quote_discount;
                                $paxTotal += $item->invoice->quote->quote_pax_total;

                                $commission = $totalSale - $paymentInputtaxTotal;
                                // 1) กำไรรวม (total profit) — ใช้เป็นฐานคูณ %
                                $people = $item->invoice->quote->quote_pax_total;
                                $totalProfit = $totalSale - $paymentInputtaxTotal;
                                // 2) กำไร “ต่อคน” (profit per person) — ใช้จับช่วง Step
                                $profitPerPerson = $people > 0 ? $totalProfit / $people : 0;

                                $totalPeople += $profitPerPerson;
                                $InputtaxTotal += $paymentInputtaxTotal;
                             
                                /// คำนวนค่าคมมิซชั่น
                                // $matches = \App\Models\CommissionRule::matchBoth($profitPerPerson, $totalProfit);
                               
                                // $totalMath += $matches['step']['commission']*$item->invoice->quote->quote_pax_total;
                                $saleId = $item->invoice->quote->Salename->id ?? null;
                                $totalMath += $commissionTotal = $saleId ? calculateCommission($totalSale, $saleId) : 0;

                            @endphp
                            <tr>
                                <td> <a target="_blank"
                                        href="{{ route('quote.editNew', $item->invoice->quote->quote_id) }}">{{ $item->invoice->quote->quote_number ? $item->invoice->quote->quote_number : 'ใบเสนอราคาถูกลบ' }}</a>
                                </td>

                                <td>{{ date('d/m/Y', strtotime($item->invoice->quote->quote_date_start)) . ' - ' . date('d/m/Y', strtotime($item->invoice->quote->quote_date_start)) }}
                                </td>
                                <td>{{ $item->invoice->quote->quoteWholesale->code }}</td>
                                <td>{{ $item->invoice->customer->customer_name }}</td>
                                <td>{{ $item->invoice->quote->quoteCountry->iso2 }}</td>
                                <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ $item->invoice->quote->quote_tour_name ? $item->invoice->quote->quote_tour_name : $item->invoice->quote->quote_tour_name1 }}">{{ $item->invoice->quote->quote_tour_name ? mb_substr($item->invoice->quote->quote_tour_name, 0, 20) . '...' : mb_substr($item->invoice->quote->quote_tour_name1, 0, 20) . '...' }}</span>
                                </td>
                                <td> {{ $item->invoice->quote->Salename->name }}</td>
                                <td>{{ $item->invoice->quote->quote_pax_total }}</td>
                                <td>{{ number_format($item->invoice->quote->quote_grand_total + $item->invoice->quote->quote_discount, 2) }}
                                </td>
                                <td>{{ number_format($item->invoice->quote->quote_discount, 2) }}</td>
                                <td>{{ number_format($item->invoice->quote->quote_grand_total, 2) }}</td>
                                <td>{{ number_format($profit, 2) }}</td>
                                <td>{{ number_format($paymentInputtaxTotal, 2) }}</td>
                                <td>{{ number_format($totalSale, 2) }}</td>
                                <td>{{ number_format($commission / $item->invoice->quote->quote_pax_total , 2) }}</td>
                                <td>{{number_format($commissionTotal,2)}}</td>
                            </tr>

                        @empty
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="7" style=""></th>
                            <th class="text-danger">{{ number_format($paxTotal)}} </th>
                            <th class="text-danger">{{ number_format($serviceTotal,2)}} </th>
                            <th class="text-danger">{{ number_format($discountTotal,2)}} </th>
                            <th class="text-danger">{{ number_format($granTotal,2)}} </th>
                            <th class="text-danger">{{ number_format($WhosaleTotal,2)}} </th>
                            <th class="text-danger">{{ number_format($InputtaxTotal,2)}} </th>
                            <th class="text-danger">{{ number_format($totalSales,2)}} </th>
                            <th class="text-danger">{{ number_format($totalPeople,2)}} </th>
                            <th style="" class="text-danger">
                               {{ number_format($totalMath,2)}} 
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
