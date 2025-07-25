@extends('layouts.template')

@section('content')
    <style>
        .info-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px 8px 0 0;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .info-table {
            margin: 0;
            font-size: 13px;
        }

        .info-table td {
            padding: 6px 0;
            border: none;
            vertical-align: top;
        }

        .info-table .label {
            width: 35%;
            color: #6c757d;
            font-weight: 500;
            text-align: right;
            padding-right: 12px;
        }

        .info-table .value {
            color: #495057;
            font-weight: 400;
        }

        .price-highlight {
            font-size: 24px !important;
            font-weight: 700;
            color: #e74c3c;
        }

        .action-btn {
            border-radius: 6px;
            font-size: 13px;
            padding: 10px 16px;
            margin-bottom: 8px;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .action-btn:hover {
            background: #f8f9fa;
            border-color: #007bff;
            color: #007bff;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
        }

        .action-btn i {
            width: 16px;
            margin-right: 8px;
        }

        .profit-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin-top: 15px;
        }

        .profit-section h6 {
            color: #495057;
            margin-bottom: 12px;
            font-weight: 600;
            font-size: 14px;
        }

        .profit-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            font-size: 13px;
            border-bottom: 1px solid #e9ecef;
        }

        .profit-item:last-child {
            border-bottom: none;
            font-weight: 600;
            color: #28a745;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 2px solid #28a745;
        }

        .profit-item .label {
            color: #6c757d;
        }

        .profit-item .value {
            color: #495057;
            font-weight: 500;
        }

        .quote-header {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .quote-header h5 {
            margin: 0;
            color: #495057;
            font-weight: 600;
        }

        .quote-header .quote-date {
            color: #6c757d;
            font-size: 13px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .col-md-6 {
                margin-bottom: 15px;
            }

            .action-btn {
                font-size: 12px;
                padding: 8px 12px;
            }

            .info-table {
                font-size: 12px;
            }
        }
    </style>
    ิ

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-7">
                <!-- Quote Header -->
                <div class="quote-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5><i data-feather="file-text" class="feather-sm me-2"></i>{{ $quotationModel->quote_number }}</h5>
                        <p class="quote-date mb-0">{{ thaidate('j M Y', $quotationModel->created_at) }}
                            {{ date('H:i', strtotime($quotationModel->created_at)) }}</p>
                    </div>
                </div>

                <!-- Information Cards -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card info-card">
                            <div class="card-header">
                                <i data-feather="user" class="feather-sm me-2"></i>ข้อมูลลูกค้า
                            </div>
                            <div class="card-body">
                                <table class="info-table w-100">
                                    <tr>
                                        <td class="label">ชื่อลูกค้า:</td>
                                        <td class="value">{{ $customer->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">เลขเสียภาษี:</td>
                                        <td class="value">{{ $customer->customer_texid ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">ที่อยู่:</td>
                                        <td class="value">{{ $customer->customer_address ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">อีเมล:</td>
                                        <td class="value">{{ $customer->customer_email ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">เบอร์โทร:</td>
                                        <td class="value">{{ $customer->customer_tel ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">ที่มา:</td>
                                        <td class="value">
                                            @if (!empty($customer->campaign_source_name) && !empty($customer->customer_social_id))
                                                {{ $customer->campaign_source_name }} : {{ $customer->customer_social_id }}
                                            @elseif(!empty($customer->campaign_source_name))
                                                {{ $customer->campaign_source_name }}
                                            @elseif(!empty($customer->customer_social_id))
                                                {{ $customer->customer_social_id }}
                                            @else
                                                None
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card info-card">
                            <div class="card-header">
                                <i data-feather="calendar" class="feather-sm me-2"></i>ข้อมูลการจอง
                            </div>
                            <div class="card-body">
                                <table class="info-table w-100">
                                    <tr>
                                        <td class="label">Booking No:</td>
                                        <td class="value">{{ $quotationModel->quote_booking }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">รหัสทัวร์:</td>
                                        <td class="value">
                                            {{ $quotationModel->quote_tour ?: $quotationModel->quote_tour_code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">พนักงานขาย:</td>
                                        <td class="value">{{ $sale->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">วันที่จอง:</td>
                                        <td class="value">{{ thaidate('j M Y', $quotationModel->quote_booking_create) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">แก้ไขล่าสุด:</td>
                                        <td class="value">{{ date('d/m/Y H:i', strtotime($quotationModel->updated_at)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">แก้ไขล่าสุดโดย:</td>
                                        <td class="value">{{ $quotationModel->updated_by }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card info-card">
                            <div class="card-header">
                                <i data-feather="map-pin" class="feather-sm me-2"></i>รายละเอียดแพคเกจ
                            </div>
                            <div class="card-body">
                                <table class="info-table w-100">
                                    <tr>
                                        <td class="label">ชื่อแพคเกจ:</td>
                                        <td class="value">{{ $quotationModel->quote_tour_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">สายการบิน:</td>
                                        <td class="value">{{ $airline->travel_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">วันเดินทาง:</td>
                                        <td class="value">
                                            @if ($quotationModel->quote_date_start && $quotationModel->quote_date_end)
                                                {{ thaidate('j M Y', $quotationModel->quote_date_start) }} -
                                            {{ thaidate('j M Y', $quotationModel->quote_date_end) }}
                                            <small class="text-muted">({{ $quotationModel->quote_numday }})</small>
                                            @else
                                                -
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">ผู้เดินทาง:</td>
                                        <td class="value">{{ $quotationModel->quote_pax_total ?: '-' }} ท่าน</td>
                                    </tr>
                                    <tr>
                                        <td class="label">โฮลเซลล์:</td>
                                        <td class="value">{{ $wholesale->wholesale_name_th }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card info-card">
                            <div class="card-header">
                                <i data-feather="dollar-sign" class="feather-sm me-2"></i>ข้อมูลการชำระเงิน
                            </div>
                            <div class="card-body">
                                <table class="info-table w-100">
                                    <tr>
                                        <td class="label">ราคารวม:</td>
                                        <td class="value price-highlight">
                                            {{ number_format($quotationModel->quote_grand_total, 2) }} บาท</td>
                                    </tr>
                                    <tr>
                                        <td class="label">ตัวอักษร:</td>
                                        <td class="value"><small>(@bathText($quotationModel->quote_grand_total))</small></td>
                                    </tr>
                                    @if ($quotationModel->quote_payment_date)
                                        <tr>
                                            <td class="label">กำหนดมัดจำ:</td>
                                            <td class="value">
                                                {{ thaidate('j M Y', $quotationModel->quote_payment_date) }}
                                                <br><strong>{{ number_format($quotationModel->quote_payment_total, 2) }}
                                                    บาท</strong>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="label">กำหนดชำระเต็ม:</td>
                                        <td class="value">
                                            {{ thaidate('j M Y', $quotationModel->quote_payment_date_full) }}
                                            <br><strong>{{ number_format($quotationModel->quote_payment_total_full, 2) }}
                                                บาท</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">สถานะ:</td>
                                        <td class="value">{!! getQuoteStatusPayment($quotationModel) !!}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Actions & Profit Sidebar -->
            <div class="col-lg-4 col-md-5">
                <div class="card info-card">
                    <div class="card-header">
                        <i data-feather="settings" class="feather-sm me-2"></i>การจัดการ
                    </div>
                    <div class="card-body">
                         @can('quote.create')
                        <a href="{{ route('quote.modalEditCopy', $quotationModel->quote_id) }}"
                            class="d-flex align-items-center action-btn modal-quote-copy">
                            <i data-feather="copy"></i> คัดลอกใบเสนอราคา
                        </a>
                          @endcan
                          @can('manage.menu.payment')
                        <a href="{{ route('payment.quotation', $quotationModel->quote_id) }}"
                            class="d-flex align-items-center action-btn invoice-modal">
                            <i data-feather="credit-card"></i> แจ้งชำระเงิน / แจ้งคืนเงิน
                        </a>
                           @endcan
                      
                    
                            <a href="{{ route('paymentWholesale.quote', $quotationModel->quote_id) }}"
                                class="d-flex align-items-center action-btn payment-wholesale{{ ($quotationModel->inputtaxTotalWholesale() <= 0) ? ' disabled' : '' }}"
                                @if($quotationModel->inputtaxTotalWholesale() <= 0) tabindex="-1" aria-disabled="true" style="pointer-events: none; opacity: 0.6;" @endif>
                                <i data-feather="dollar-sign"></i> ชำระเงินโฮลเซลล์
                            </a>
                        


                        {{-- <a href="{{ route('paymentWholesale.quote', $quotationModel->quote_id) }}"
                            class="d-flex align-items-center action-btn payment-wholesale">
                            <i data-feather="dollar-sign"></i> ชำระเงินโฮลเซลล์
                        </a> --}}

                        <a href="{{ route('inputtax.createWholesale', $quotationModel->quote_id) }}"
                            class="d-flex align-items-center action-btn modal-input-tax">
                            <i data-feather="file-minus"></i> บันทึกภาษีซื้อ / ต้นทุนอืนๆ
                        </a>

                        @php
                            use Illuminate\Support\Facades\Crypt;
                            $encryptedId = Crypt::encryptString($quotationModel->quote_id);
                        @endphp
                        <a href="{{ route('quotationView.index', $encryptedId) }}" id="shareLinkButton"
                            class="d-flex align-items-center action-btn">
                            <i data-feather="share-2"></i> แชร์ลิงก์
                        </a>

                        <a href="{{ route('quoteLog.index', $quotationModel->quote_id) }}"
                            class="d-flex align-items-center action-btn modal-quote-check">
                            <i data-feather="check-circle"></i> เช็คลิสต์
                        </a>

                        <a href="{{ route('inputtax.inputtaxCreateWholesale', $quotationModel->quote_id) }}"
                            class="d-flex align-items-center action-btn modal-inputtax-wholesale">
                            <i data-feather="percent"></i> ต้นทุนโฮลเซลล์ 
                        </a>
                    </div>

                    <!-- Profit Calculation -->
                    <div class="profit-section">
                        @php
                            $paymentCustomer = 0;
                            $paymentWhosale = 0;
                            $paymentInputtaxTotal = 0;
                            $TotalPayment = 0;
                            $TotalGrand = 0;

                            $paymentCustomer = $quotationModel->GetDeposit();
                            $paymentWhosale = $quotationModel->GetDepositWholesale();
                            $withholdingTaxAmount = $invoiceModel?->getWithholdingTaxAmountAttribute() ?? 0;
                            $getTotalInputTaxVat = $quotationModel?->getTotalInputTaxVat() ?? 0;
                            $invoiceVatAmount = $quotationModel->invoicetaxTotal() + $paymentInputtaxTotal;
                            $wholesalePayment =
                                $quotationModel->GetDepositWholesale() - $quotationModel->GetDepositWholesaleRefund();

                            $hasInputTaxFile = $quotationModel->InputTaxVat()->whereNotNull('input_tax_file')->exists();
                            if ($hasInputTaxFile) {
                                $paymentInputtaxTotal = $withholdingTaxAmount - $getTotalInputTaxVat;
                            } else {
                                $paymentInputtaxTotal = $withholdingTaxAmount + $getTotalInputTaxVat;
                            }

                            $TotalPayment = $quotationModel->GetDeposit() - $quotationModel->inputtaxTotalWholesale();
                            $TotalGrand =
                                $TotalPayment - ($paymentInputtaxTotal + $quotationModel->getTotalInputTaxVatType());
                            $GetDepositWholesale =
                                $quotationModel->GetDepositWholesale() - $quotationModel->GetDepositWholesaleRefund();
                        @endphp

                        <h6><i data-feather="trending-up" class="feather-sm me-2"></i>สรุปกำไร</h6>

                        <div class="profit-item">
                            <span class="label">ลูกค้าชำระแล้ว</span>
                            <span class="value">{{ number_format($quotationModel->GetDeposit()-$quotationModel->Refund(), 2) }}</span>
                        </div>

                        <div class="profit-item">
                            <span class="label">ต้นทุนโฮลเซลล์</span>
                            <span
                                class="value">{{ number_format($GetDepositWholesale + $quotationModel->inputtaxTotalWholesale() - $wholesalePayment, 2) }}</span>
                        </div>

                        <div class="profit-item">
                            <span class="label">ชำระโฮลเซลล์แล้ว</span>
                            <span class="value">{{ number_format($quotationModel->getWholesalePaidNet(), 2) }}</span>
                        </div>

                        <div class="profit-item">
                            <span class="label">ค้างชำระโฮลเซลล์</span>
                            <span class="value">{{ number_format($quotationModel->inputtaxTotalWholesale() - $quotationModel->getWholesalePaidNet(), 2) }}</span>
                        </div>

                        <div class="profit-item">
                            <span class="label">ต้นทุนอื่นๆ</span>
                            <span
                                class="value">{{ number_format($quotationModel->getTotalOtherCost(), 2) }}</span>
                        </div>

                        <div class="profit-item">
                            <span class="label">กำไรสุทธิ</span>
                            <span class="value">
                                {{-- {{$quotationModel->CountPaymentWholesale()}} --}}

                                @if ($quotationModel->CountPaymentWholesale() > 0 && $quotationModel->inputtaxTotalWholesale() > 0)


                                @if (
                                    ($wholesalePayment > 0 || $paymentInputtaxTotal > 0) &&
                                    (number_format($quotationModel->getWholesalePaidNet(), 2) === number_format($quotationModel->inputtaxTotalWholesale(), 2))
                                )
                                    {{ number_format($quotationModel->getNetProfit(), 2) }}
                                @else
                                    {{ number_format(0, 2) }}
                                @endif

                                 @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="row mt-4">
            @canany(['quote.view', 'invoice.view', 'taxinvoice.view'])
            <div class="col-12" id="quote-centent"></div>
            @endcanany
             @canany(['payment.view'])
            <div class="col-12" id="quote-payment"></div>
            @endcanany
            @canany(['wholesale.payment.view'])
            <div class="col-12" id="wholesale-payment"></div>
            @endcanany
            @canany(['filepassport.create', 'filepassport.view'])
            <div class="col-12" id="files"></div>
             @endcanany
               @canany(['inputtax.create', 'inputtax.edit'])
            <div class="col-12" id="inputtax"></div>
              @endcanany
              @canany(['wholesale.inputtax.create', 'wholesale.inputtax.edit'])
            <div class="col-12" id="inputtax-wholesale-table"></div>
              @endcanany
        </div>
    </div>



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
        .load($(this).attr("href"), function() {
            // เรียกฟังก์ชันหลังโหลดเนื้อหาใหม่
            if (typeof updateInvoiceStatus === 'function') updateInvoiceStatus();
            if (typeof updateSlipStatus === 'function') updateSlipStatus();
        });
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
                        $('#inputtax-wholesale-table').html(
                            response); // แสดง response ใน #quote-centent
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
