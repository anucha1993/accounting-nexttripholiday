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
                                    <a href="{{ route('report.taxinvoice') }}"
                                        class="btn  btn-danger float-end ml-2">ล้างการค้นหา</a>
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
                <form action="{{ route('export.taxinvoice') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="taxinvoice_ids" value="">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To
                        Excel</button>
                </form>
            </div>
            <div class="card-body">

                <table class="table table quote-table " style="font-size: 12px; width: 100%">
                    <thead>
                        <tr>
                            <th>เลขที่ใบแจ้งหนี้</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($taxinvoices as $item)
                            @php
                                $withholdingTaxAmount = $item->invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                $getTotalInputTaxVat =  $item->invoice->quote?->getTotalInputTaxVat() ?? 0;
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

                                $wholesaleTotal = $item->invoice->quote->GetDepositWholesale() - $item->invoice->quote->GetDepositWholesaleRefund();

                                $totalSale = $item->invoice->quote->quote_grand_total - $wholesaleTotal;

                            @endphp
                            <tr>
                                <td>{{ $item->invoice_number }}</td>
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
                                <td>{{ number_format($wholesaleTotal, 2) }}</td>
                                <td>{{ number_format($paymentInputtaxTotal, 2) }}</td>
                                <td>{{ number_format($totalSale, 2) }}</td>
                                <td>{{ number_format(($totalSale-$paymentInputtaxTotal)/2, 2) }}</td>
                            </tr>

                        @empty
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="10" style="text-align:left"></th>
                            <th style="text-align:left" class="text-danger">
                                มูลค่ารวม :
                            </th>
                            <th style="text-align:left" class="text-danger">
                                มูลค่าภาษีรวม:
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
