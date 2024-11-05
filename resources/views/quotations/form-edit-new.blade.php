@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">
        <div class="row">
            <div class="col-md-9">

                <div class="card">
                    <div class="card-header bg-info text-white">
                        Quotation No. : {{ $quotationModel->quote_number }}
                        <span class="float-end">วันที่ออกใบเสนอราคา :
                            {{ thaidate('j F Y', $quotationModel->created_at) }} เวลา :
                            {{ date('H:m:s', strtotime($quotationModel->created_at)) }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-header ">
                                <i data-feather="user-check" class="feather-sm fill-white me-2 text-primary "></i>
                                รายละเอียดลูกค้า (Customer)
                            </div>
                            <div class="card-body">
                                <table style="font-size: 12px">
                                    <tbody>
                                        <tr>
                                            <td align="right" class="text-info">เลขเสียภาษี :</td>
                                            <td>&nbsp;{{ $customer->customer_texid ? $customer->customer_texid : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">ชื่อลูกค้า :</td>
                                            <td>&nbsp; คุณ {{ $customer->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">ที่อยู่ :</td>
                                            <td>&nbsp; {{ $customer->customer_address ? $customer->customer_address : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">อีเมล์ :</td>
                                            <td>&nbsp; {{ $customer->customer_email ? $customer->customer_email : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">เบอร์มือถือ :</td>
                                            <td>&nbsp; {{ $customer->customer_tel ? $customer->customer_tel : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">เบอร์โทรสาร (Fax) :</td>
                                            <td>&nbsp; {{ $customer->customer_fax ? $customer->customer_fax : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">ลูกค้าจาก :</td>
                                            <td>&nbsp;
                                                {{ $customer->campaign_source_name ? $customer->campaign_source_name : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">Social ID :</td>
                                            <td>&nbsp;
                                                {{ $customer->customer_social_id ? $customer->customer_social_id : '-' }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header ">
                                <i data-feather="file-text" class="feather-sm fill-white me-2 text-primary "></i>
                                รายละเอียดใบจองทัวร์ (Booking Form)
                            </div>
                            <div class="card-body">
                                <table style="font-size: 12px">
                                    <tbody>
                                        <tr>
                                            <td align="right" class="text-info">Quotation No. :</td>
                                            <td>&nbsp; {{ $quotationModel->quote_number }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">Booking No. :</td>
                                            <td>&nbsp; {{ $quotationModel->quote_booking }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">วันที่จองทัวร์ :</td>
                                            <td>&nbsp;
                                                {{ $customer->quotationModel ? thaidate('j F Y', $quotationModel->quote_booking_create) : thaidate('j F Y', $quotationModel->quote_booking_create) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td align="right" class="text-info">รหัสทัวร์ :</td>
                                            <td>&nbsp;
                                                {{ $quotationModel->quote_tour ? $quotationModel->quote_tour : $quotationModel->quote_tour_code }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td align="right" class="text-info">พนักงานขาย :</td>
                                            <td>&nbsp; {{ $sale->name }}</td>
                                        </tr>

                                        <tr>
                                            <td align="right" class="text-info">Tel :</td>
                                            <td>&nbsp; 091-091-6364 </td>
                                        </tr>

                                        <tr>
                                            <td align="right" class="text-info">แก้ไขล่าสุดโดย :</td>
                                            <td>&nbsp;
                                                {{ $quotationModel->updated_by ? $quotationModel->updated_by : $quotationModel->created_by }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">วันที่แก้ไขล่าสุด :</td>
                                            <td>&nbsp; {{ date('d/m/Y H:m:s', strtotime($quotationModel->updated_at)) }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header ">
                                <i data-feather="flag" class="feather-sm fill-white me-2 text-primary "></i>
                                รายละเอียดแพคเกจที่ซื้อ/วันเดินทาง
                            </div>
                            <div class="card-body">
                                <table style="font-size: 12px">
                                    <tbody>
                                        <tr>
                                            <td align="right" class="text-info">ชื่อแพคเกจ :</td>
                                            <td>&nbsp;
                                                {{ $quotationModel->quote_tour_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">สายการบิน :</td>
                                            <td>&nbsp; {{ $airline->travel_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">ช่วงเวลาเดินทาง :</td>
                                            <td>&nbsp;
                                                {{ thaidate('j F Y', $quotationModel->quote_date_start) . ' -ถึง- ' . thaidate('j F Y', $quotationModel->quote_date_end) }}
                                                ({{ $quotationModel->quote_numday }})
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">ผู้เดินทาง (PAX) :</td>
                                            <td>&nbsp;
                                                {{ $quotationModel->quote_pax_total ? $quotationModel->quote_pax_total : '-' }}
                                                ท่าน
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">โฮลเซลล์ :</td>
                                            <td>&nbsp; {{ $wholesale->wholesale_name_th }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header ">
                                <i data-feather="dollar-sign" class="feather-sm fill-white me-2 text-primary "></i>
                                ยอดรวมสุทธิและกำหนดชำระเงิน
                            </div>
                            <div class="card-body">
                                <table style="font-size: 12px">
                                    <tbody>
                                        <tr>
                                            <td align="right" class="text-info">ราคารวมสุทธิ :</td>
                                            <td align="align-center" style="font-size: 28px">&nbsp; <b
                                                    class="text-danger">{{ number_format($quotationModel->quote_grand_total, 2, '.', ',') }}
                                                    .-</b></td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">จำนวนเงินอักษร :</td>
                                            <td>&nbsp; <span>(@bathText($quotationModel->quote_grand_total)) </span></td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">กำหนดชำระมัดจำ: :</td>
                                            <td>&nbsp;
                                                @if ($quotationModel->quote_payment_date)
                                                    {{ thaidate('j F Y', $quotationModel->quote_payment_date) . ' ก่อนเวลา ' . date('H:m', strtotime($quotationModel->quote_payment_date)) . ' น.' }}
                                                    &nbsp;
                                                    {{ 'จำนวนเงิน :' . number_format($quotationModel->quote_payment_total, 2, '.', ',') . '.-' }}
                                                @else
                                                    -ไม่มียอดมัดจำ-
                                                @endif

                                            </td>
                                        </tr>
                                        <tr>

                                            <td align="right" class="text-info">กำหนดชำระเต็ม: :</td>
                                            <td>&nbsp;
                                                {{ thaidate('j F Y', $quotationModel->quote_payment_date_full) . ' ก่อนเวลา ' . date('H:m', strtotime($quotationModel->quote_payment_date_full)) . ' น.' }}
                                                &nbsp;
                                                {{ 'จำนวนเงิน :' . number_format($quotationModel->quote_payment_total_full, 2, '.', ',') . '.-' }}
                                            </td>

                                        </tr>


                                        <tr>
                                            <td align="right" class="text-info">สถานะการชำระเงิน :</td>
                                            <td>
                                                @php
                                                    use Carbon\Carbon;

                                                    // กำหนดวันที่ปัจจุบัน
                                                    $now = Carbon::now();

                                                    // กำหนดสถานะเริ่มต้น
                                                    $status = '';

                                                    // ตรวจสอบสถานะการสั่งซื้อ
                                                    if ($quotationModel->quote_status === 'cancel') {
                                                        $status =
                                                            '<span class="badge rounded-pill bg-danger">ยกเลิกการสั่งซื้อ</span>';
                                                    } elseif ($quotationModel->quote_status === 'success') {
                                                        $status =
                                                            '<span class="badge rounded-pill bg-success">ชำระเงินครบแล้ว</span>';
                                                    } elseif ($quotationModel->payment > 0) {
                                                        // หากมีการชำระเงินมัดจำแล้ว
                                                        $status =
                                                            '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
                                                    } elseif ($quotationModel->quote_payment_type === 'deposit') {
                                                        // ตรวจสอบกำหนดชำระเงินมัดจำ
                                                        if (
                                                            $now->gt(Carbon::parse($quotationModel->quote_payment_date))
                                                        ) {
                                                            $status =
                                                                '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
                                                        } else {
                                                            $status =
                                                                '<span class="badge rounded-pill bg-warning text-dark"">รอชำระเงินมัดจำ</span>';
                                                        }
                                                    } elseif ($quotationModel->quote_payment_type === 'full') {
                                                        // ตรวจสอบกำหนดชำระเงินเต็มจำนวน
                                                        if (
                                                            $now->gt(
                                                                Carbon::parse($quotationModel->quote_payment_date_full),
                                                            )
                                                        ) {
                                                            $status =
                                                                '<span class="badge rounded-pill bg-danger">เกินกำหนดชำระเงิน</span>';
                                                        } else {
                                                            $status =
                                                                '<span class="badge rounded-pill bg-info">รอชำระเงินเต็มจำนวน</span>';
                                                        }
                                                    } else {
                                                        // กรณีที่ไม่ตรงเงื่อนไขใดๆ
                                                        $status =
                                                            '<span class="badge rounded-pill bg-secondary">สถานะไม่ระบุ</span>';
                                                    }
                                                @endphp

                                                {!! $status !!}
                                            </td>

                                        </tr>

                                        {{-- <tr>
                                            <td align="right" class="text-info">สถานะการชำระเงิน :</td>
                                            <td>&nbsp;
                                                @if ($quotationModel->quote_payment_status === null)
                                                <span class="badge rounded-pill bg-primary">รอชำระเงิน</span>
                                            @endif
                                                @if ($quotationModel->quote_payment_status === 'wait')
                                                    <span class="badge rounded-pill bg-primary">รอชำระเงิน</span>
                                                @endif
                                                @if ($quotationModel->quote_payment_status === 'success')
                                                    <span class="badge rounded-pill bg-success">ชำระเงินครบจำนวนแล้ว</span>
                                                @endif
                                                @if ($quotationModel->quote_payment_status === 'cancel')
                                                    <span class="badge rounded-pill bg-danger">ยกเลิก</span>
                                                @endif
                                                @if ($quotationModel->quote_payment_status === 'payment')
                                                    <span class="badge rounded-pill bg-warning">ชำระมัดจำแล้ว</span>
                                                @endif

                                            </td>
                                        </tr> --}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">

                        {{-- <a href="{{ route('mpdf.quote', $quotationModel->quote_id) }}"
                            onclick="openPdfPopup(this.href); return false;"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3 quote-table">
                            <i data-feather="printer" class="feather-sm fill-white me-2 text-danger"></i>
                            พิมพ์ใบเสนอราคา
                        </a>

                        <a href="{{ route('invoice.create', $quotationModel->quote_id) }}"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3 modal-invoice">
                            <i data-feather="folder-plus" class="feather-sm fill-white me-2 text-success"></i>
                            ออกใบแจ้งหนี้
                        </a> --}}


                        <a href="{{ route('quote.modalEditCopy', $quotationModel->quote_id) }}"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3 modal-quote-copy">
                            <i data-feather="repeat" class="feather-sm fill-white me-2 text-info"></i>
                            คัดลอกใบเสนอราคา
                        </a>

                        <a href="{{ route('payment.quotation', $quotationModel->quote_id) }}"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3 invoice-modal">
                            <i data-feather="dollar-sign" class="feather-sm fill-white me-2 text-success"></i>
                            แจ้งชำระเงิน
                        </a>


                        {{-- 
                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="file" class="feather-sm fill-white me-2 text-info"></i>
                            ยกเลิกใบเสนอราคา
                        </button> --}}

                        <a href="{{ route('paymentWholesale.quote', $quotationModel->quote_id) }}"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3 payment-wholesale">
                            <i data-feather="dollar-sign" class="feather-sm fill-white me-2 "></i>
                            แจ้งชำระเงินโฮลเซลล์
                        </a>


                        <a href="{{ route('inputtax.createWholesale', $quotationModel->quote_id) }}"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-primary d-flex align-items-center mb-3 modal-input-tax ">
                            <i data-feather="file-minus" class="feather-sm fill-white me-2 "></i>
                            บันทึกภาษีซื้อ , ต้นทุนอื่นๆ
                        </a>

                       

                        @php
                            use Illuminate\Support\Facades\Crypt;
                            $encryptedId = Crypt::encryptString($quotationModel->quote_id);
                        @endphp
                        <a href="{{ route('quotationView.index', $encryptedId) }}" id="shareLinkButton"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="link" class="feather-sm fill-white me-2 text-info"></i>
                            Share
                        </a>

                        <a href="{{route('quoteLog.index',$quotationModel->quote_id)}}"
                        class="justify-content-left w-100 btn btn-rounded btn-outline-success d-flex align-items-center mb-3 modal-quote-check ">
                        <i data-feather="align-justify" class="feather-sm fill-white me-2 "></i>
                        Check List 
                    </a>

                    <a href="{{route('inputtax.inputtaxCreateWholesale',$quotationModel->quote_id)}}"
                        class="justify-content-left w-100 btn btn-rounded btn-outline-warning d-flex align-items-center mb-3 modal-inputtax-wholesale">
                        <i data-feather="percent" class="feather-sm fill-white me-2 "></i>
                       ต้นทุนโฮลเซลล์
                    </a>

                    </div>

                    <div class="card-body">

                        @php
                        $paymentCustomer = 0;
                        $paymentWhosale = 0;
                        $paymentInputtaxTotal = 0;
                        $TotalPayment = 0;
                        $TotalGrand = 0;
                    
                        // เรียกข้อมูลการฝากเงินของลูกค้า
                        $paymentCustomer = $quotationModel->GetDeposit();
                    
                        // เรียกข้อมูลการฝากเงินของผู้ค้าส่ง
                        $paymentWhosale = $quotationModel->GetDepositWholesale();
                    
                        // เรียกข้อมูลยอดรวมภาษีซื้อ (Input Tax)
                        $paymentInputtaxTotal = $quotationModel->inputtaxTotal();

                        $invoiceVatAmount = $quotationModel->invoicetaxTotal() + $paymentInputtaxTotal;
                        // คำนวณยอดรวม โดยหักเงินฝากของผู้ค้าส่งและภาษีออกจากเงินฝากของลูกค้า
                       

                        $TotalPayment = $paymentCustomer - $paymentWhosale;

                        $TotalGrand = $TotalPayment - $invoiceVatAmount ;

                    @endphp
                  
                    

                        <h5 class="card-title">คำนวนกำไรขั้นต้น</h5>
                        <hr/>
                        <span class="float-end"> ยอดรวมต้นทุนโฮลเซลล์: {{ number_format($quotationModel->inputtaxTotalWholesale(), 2) }}</span><br>
                        <span class="float-end"> ยอดโอนโฮลเซลล์: {{ number_format($quotationModel->GetDepositWholesale(), 2) }}</span><br>
                        <span class="float-end">ชำระแล้ว : {{ number_format($quotationModel->GetDeposit(), 2) }}</span><br>
                        <span class="float-end"> กำไร : {{ number_format($TotalPayment, 2) }}</span><br>

                      
                        <span class="float-end"> กำไรสุทธิ: {{ number_format($TotalGrand, 2) }} </span><br>
                        <hr/>

                        {{-- <button class="btn btn-success">Checkout</button>
                        <button class="btn btn-secondary btn-outline">Cancel</button> --}}
                    </div>
                </div>
            </div>
        </div>

        <style>
            .rotate {
                transition: transform 0.3s;
                transform: rotate(180deg);
            }
        </style>






        <div class="row">
            <div class="col-md-12" id="quote-centent">

            </div>

            <div class="col-md-12" id="quote-payment">

            </div>

            <div class="col-md-12" id="wholesale-payment">

            </div>

            <div class="col-md-12" id="files">

            </div>
            <div class="col-md-12" id="inputtax">

            </div>

            <div class="col-md-12" id="inputtax-wholesale-table">

            </div>



        </div>



        {{-- invoice payment Modal
        <div class="modal fade bd-example-modal-sm modal-lg" id="invoice-payment" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div> --}}

        {{-- debit payment Modal --}}
        {{-- <div class="modal fade bd-example-modal-sm modal-lg" id="debit-payment" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div> --}}

        {{-- credit payment Modal --}}
        {{-- <div class="modal fade bd-example-modal-sm modal-lg" id="credit-payment" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div> --}}

        {{-- credit payment WholeSale  Quote --}}
        {{-- <div class="modal fade bd-example-modal-sm modal-lg" id="quote-payment-wholesale" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div> --}}

        {{-- mail form quote --}}
        {{-- <div class="modal fade bd-example-modal-sm modal-lg" id="modal-mail-quote" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div> --}}



        {{-- Payment Wholesale --}}
        <div class="modal fade bd-example-modal-sm modal-lg" id="payment-wholesale" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div>

        {{-- modal-input-tax  --}}
        <div class="modal fade bd-example-modal-sm modal-lg" id="input-tax" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-sm modal-lg" id="modal-quote-copy" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-sm modal-lg" id="modal-quote-check" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-sm modal-lg" id="modal-inputtax-wholesale" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


    


        <script>
            function toggleAccordion(contentId, arrowId) {
                const content = document.getElementById(contentId);
                const arrow = document.getElementById(arrowId);
                if (content.style.display === "block") {
                    content.style.display = "none";
                    arrow.classList.remove("rotate"); // หมุนลูกศรกลับ
                } else {
                    content.style.display = "block";
                    arrow.classList.add("rotate"); // หมุนลูกศรลง
                }
            }
        </script>



        <script>
            
            document.getElementById('shareLinkButton').addEventListener('click', function(event) {
                event.preventDefault(); // ป้องกันการคลิกที่ลิงก์เพื่อให้ไม่โหลดหน้าใหม่

                // สร้าง element ชั่วคราวเพื่อเก็บ URL ที่ต้องการคัดลอก
                const tempInput = document.createElement('input');
                tempInput.value = this.href;
                document.body.appendChild(tempInput);

                // เลือกและคัดลอก URL ไปยัง clipboard
                tempInput.select();
                tempInput.setSelectionRange(0, 99999); // สำหรับอุปกรณ์มือถือ
                document.execCommand('copy');

                // ลบ element ชั่วคราวเมื่อเสร็จแล้ว
                document.body.removeChild(tempInput);

                // แจ้งให้ผู้ใช้ทราบว่าลิงก์ได้ถูกคัดลอกแล้ว
                alert('ลิงก์ถูกคัดลอกไปที่คลิปบอร์ดแล้ว');
            });
        </script>



        <script>
            $(document).ready(function() {
                $(".modal-inputtax-wholesale").click("click", function(e) {
                    e.preventDefault();
                    $("#modal-inputtax-wholesale")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });

                $(".modal-quote-check").click("click", function(e) {
                    e.preventDefault();
                    $("#modal-quote-check")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });

                // modal add payment wholesale quote
                $(".modal-quote-copy").click("click", function(e) {
                    e.preventDefault();
                    $("#modal-quote-copy")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });


                // modal Payment Wholesale
                $(".modal-input-tax").click("click", function(e) {
                    e.preventDefault();
                    $("#input-tax")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });

                // modal Payment Wholesale
                $(".payment-wholesale").click("click", function(e) {
                    e.preventDefault();
                    $("#payment-wholesale")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });


                var quoteId = "{{ $quotationModel->quote_id }}"

                function quoteEdit(quoteId) {
                    // โหลดเนื้อหาของไฟล์ฟอร์มและแสดงใน DOM
                    $.ajax({
                        url: "{{ route('quote.editAjax', '') }}/" + quoteId, // ประกอบ URL แบบถูกต้อง
                        type: 'GET',
                        success: function(response) {
                            $('#quote-centent').html(response); // แสดง response ใน #quote-centent
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                        }
                    });
                }
                quoteEdit(quoteId);

                function paymentTable(quoteId) {
                    // โหลดเนื้อหาของไฟล์ฟอร์มและแสดงใน DOM
                    $.ajax({
                        url: "{{ route('payments', '') }}/" + quoteId, // ประกอบ URL แบบถูกต้อง
                        type: 'GET',
                        success: function(response) {
                            $('#quote-payment').html(response); // แสดง response ใน #quote-centent
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                        }
                    });
                }

                paymentTable(quoteId);

                function paymentWholesale(quoteId) {
                    // โหลดเนื้อหาของไฟล์ฟอร์มและแสดงใน DOM
                    $.ajax({
                        url: "{{ route('wholesale.payment', '') }}/" + quoteId, // ประกอบ URL แบบถูกต้อง
                        type: 'GET',
                        success: function(response) {
                            $('#wholesale-payment').html(response); // แสดง response ใน #quote-centent
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                        }
                    });
                }

                paymentWholesale(quoteId);


                function files(quoteId) {
                    // โหลดเนื้อหาของไฟล์ฟอร์มและแสดงใน DOM
                    $.ajax({
                        url: "{{ route('quotefile.index', '') }}/" + quoteId, // ประกอบ URL แบบถูกต้อง
                        type: 'GET',
                        success: function(response) {
                            $('#files').html(response); // แสดง response ใน #quote-centent
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                        }
                    });
                }

                files(quoteId);

                function inputtax(quoteId) {
                    // โหลดเนื้อหาของไฟล์ฟอร์มและแสดงใน DOM
                    $.ajax({
                        url: "{{ route('inputtax.table', '') }}/" + quoteId, // ประกอบ URL แบบถูกต้อง
                        type: 'GET',
                        success: function(response) {
                            $('#inputtax').html(response); // แสดง response ใน #quote-centent
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                        }
                    });
                }
                inputtax(quoteId)

                function inputtaxWholesale(quoteId) {
                    // โหลดเนื้อหาของไฟล์ฟอร์มและแสดงใน DOM
                    $.ajax({
                        url: "{{ route('inputtax.tableWholesale', '') }}/" + quoteId, // ประกอบ URL แบบถูกต้อง
                        type: 'GET',
                        success: function(response) {
                            $('#inputtax-wholesale-table').html(response); // แสดง response ใน #quote-centent
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // แสดงข้อผิดพลาดใน console
                        }
                    });
                }
                inputtaxWholesale(quoteId)



                // // modal add payment wholesale quote
                // $(".mail-quote").click("click", function(e) {
                //     e.preventDefault();
                //     $("#modal-mail-quote")
                //         .modal("show")
                //         .addClass("modal-lg")
                //         .find(".modal-content")
                //         .load($(this).attr("href"));
                // });

                // // modal add payment wholesale quote
                // $(".payment-quote-wholesale").click("click", function(e) {
                //     e.preventDefault();
                //     $("#quote-payment-wholesale")
                //         .modal("show")
                //         .addClass("modal-lg")
                //         .find(".modal-content")
                //         .load($(this).attr("href"));
                // });



                // // modal add payment invoice
                // $(".invoice-modal").click("click", function(e) {
                //     e.preventDefault();
                //     $("#invoice-payment")
                //         .modal("show")
                //         .addClass("modal-lg")
                //         .find(".modal-content")
                //         .load($(this).attr("href"));
                // });
                // // modal add payment debit
                // $(".debit-modal").click("click", function(e) {
                //     e.preventDefault();
                //     $("#debit-payment")
                //         .modal("show")
                //         .addClass("modal-lg")
                //         .find(".modal-content")
                //         .load($(this).attr("href"));
                // });
                // // modal add payment credit
                // $(".credit-modal").click("click", function(e) {
                //     e.preventDefault();
                //     $("#credit-payment")
                //         .modal("show")
                //         .addClass("modal-lg")
                //         .find(".modal-content")
                //         .load($(this).attr("href"));
                // });
            });
        </script>


        <script>
            function openPdfPopup(url) {
                var width = 800; // กำหนดความกว้างของหน้าต่าง
                var height = 600; // กำหนดความสูงของหน้าต่าง
                var left = (window.innerWidth - width) / 2; // คำนวณตำแหน่งจากด้านซ้ายของหน้าจอ
                var top = (window.innerHeight - height) / 2; // คำนวณตำแหน่งจากด้านบนของหน้าจอ

                // เปิดหน้าต่างใหม่ด้วยการคำนวณตำแหน่งและขนาด
                window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
            }
        </script>
    @endsection
