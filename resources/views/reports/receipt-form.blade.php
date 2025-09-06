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
    
<<<<<<< HEAD
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
=======
        } elseif ($quotationModel->quote_status === 'success') {
            $status = 'ชำระเงินครบแล้ว';
        @php
            $quotationPaymentTotal = $quotationModel->quotePayments()
                ->where('payment_status', '!=', 'cancel')
                ->where('payment_type', '!=', 'refund')
                ->sum('payment_total');
        @endphp
        } elseif ($quotationPaymentTotal > 0) {
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
>>>>>>> 1ecbab4643b3ad4d5193e2509c91299bef78e8cd
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
                <h4 class="text-info">รายงานใบเสร็จตามเอกสาร </h4>
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
                                        <option value="wait">รอแนบสลิป</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="">เงือนไข</label>
                                    <select name="column_name" class="form-select">
                                        <option @if ($request->column_name === 'all') selected @endif value="all">ทั้งหมด
                                        </option>
                                        <option @if ($request->column_name === 'payment_number') selected @endif value="payment_number">
                                            เลขที่ใบใบเสร็จ</option>
                                        <option @if ($request->column_name === 'quote_number') selected @endif value="quote_number">
                                            เลขที่ใบเสนอราคา</option>
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
                                    <a href="{{ route('report.receipt') }}"
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
                <h3 class="text-info">Report Receipt Payments</h3><br>
                <form action="{{ route('export.receipt') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="payment_ids" value="{{ $receipts->pluck('payment_id') }}">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-file-excel"></i> Export To
                        Excel</button>
                </form>
            </div>
            <div class="card-body">
                <div class="col-md-2">
                    <label for="">แสดงรายการ</label>
                    <select name="perPage" class="form-select" onchange="this.form.submit()">
                        @foreach ([10, 50, 100, 200] as $value)
                            <option value="{{ $value }}" {{ $value == $perPage ? 'selected' : '' }}>
                                {{ $value }} รายการ
                            </option>
                        @endforeach
                    </select>
                </div>
                {{ $receipts->appends(request()->query())->links() }}
            </div>

            <table class="table table quote-table " style="font-size: 12px; width: 100%">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>Payment No.</th>
                        <th>วันที่ออกใบเสร็จ</th>
                        <th>เลขที่ใบเสนอราคา</th>
                        <th>เลขที่อ้างอิงใบจองทัวร์</th>
                        <th>รายละเอียดการชำระ</th>
                        <th>จำนวนเงิน:บาท</th>
                        <th>ไฟล์แนบ</th>
                        <th>ประเภท</th>
                        <th>สถานะการชำระเงิน</th>


                    </tr>
                </thead>



                <tbody>
                    @forelse ($receipts as $key => $item)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>
                                @canany(['report.receipt.view', 'payment.view'])
                                    <a href="{{ route('mpdf.payment', $item->payment_id) }}"
                                        target="_bank">{{ $item->payment_number }}</a>
                                @endcanany
                            </td>
                            <td>{{ date('d/m/Y ', strtotime($item->payment_in_date)) }}</td>
                            <td>
                                @canany(['quote.edit', 'quote.view'])
                                    <a
                                        href="{{ route('quote.editNew', $item->quote->quote_id) }}">{{ $item->quote->quote_number }}</a>
                                @endcanany
                            </td>
                            <td>{{ $item->quote->quote_booking }} </td>
                            <td>
                                @if ($item->payment_method === 'cash')
                                    วิธีการชำระเงิน : เงินสด </br>
                                    วันที่ :{{ date('d/m/Y : H:m', strtotime($item->payment_in_date)) }}</br>
                                @endif
                                @if ($item->payment_method === 'transfer-money')
                                    วิธีการชำระเงิน : โอนเงิน</br>
                                    วันที่ :{{ date('d/m/Y : H:m', strtotime($item->payment_in_date)) }}</br>
                                    {{ $item->banktransfer->bank_name ?? 'N/A' }}
                                @endif

                                {{-- @if ($item->payment_method === 'check')
                                วิธีการชำระเงิน : เช็ค</br>
                                โอนเข้าบัญชี : {{ $item->bank->bank }} </br>
                                เลขที่เช็ค : {{ $item->bank->bank_name }} </br>
                                วันที่ :{{ date('d/m/Y : H:m', strtotime($item->payment_check_date)) }}</br>
                            @endif --}}

                                @if ($item->payment_method === 'check')
                                    วิธีการชำระเงิน : เช็ค</br>
                                    เลขที่เช็ค : {{ $item->payment_check_number }}</br>
                                    เช็คธนาคาร : {{ $item->bank->bank_name }} </br>
                                    เช็คลงวันที่ :{{ date('d/m/Y : H:m', strtotime($item->payment_check_date)) }}</br>
                                @endif

                                @if ($item->payment_method === 'credit')
                                    วิธีการชำระเงิน : บัตรเครดิต </br>
                                    เลขที่สลิป : {{ $item->payment_credit_slip_number }} </br>
                                    วันที่ :{{ date('d/m/Y : H:m', strtotime($item->payment_in_date)) }}</br>
                                @endif
                            </td>

                            <td>{{ number_format($item->payment_total, 2) }}</td>
                            <td>
                                @if ($item->payment_file_path)
                                    <a href="{{ asset('storage/' . $item->payment_file_path) }}" class="dropdown-item"
                                        onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-file text-danger"></i> สลิปโอน</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>

                                @if ($item->payment_status === 'cancel')
                                    -
                                @else
                                    @if ($item->payment_type === 'deposit')
                                        ชำระมัดจำ
                                    @else
                                        ชำระเงินเต็มจำนวน
                                    @endif
                                @endif


                            </td>

                            <td>
                                @if ($item->payment_status === 'success')
                                    <span class="badge rounded-pill bg-success">สำเร็จ</span>
                                @elseif ($item->payment_status === 'wait')
                                    <span class="badge rounded-pill bg-warning">รอแนบสลิป</span>
                                @elseif ($item->payment_status === null)
                                    <span class="badge rounded-pill bg-secondary">ไม่มีข้อมูล</span>
                                @endif
                            </td>

                        </tr>

                    @empty
                    @endforelse

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" style="text-align:right">ยอดรวมทั้งหมด:</th>
                        <th style="text-align:right">{{ number_format($totalAmount, 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
            {{ $receipts->appends(request()->query())->links() }}
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
