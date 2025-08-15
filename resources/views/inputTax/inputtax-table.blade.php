<div class="col-md-12">
    <style>
        .inputtax-table-header {
            background: linear-gradient(135deg, #ff9800 0%, #f44336 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 20px;
        }
        .inputtax-table th {
            background: #f3f6fb;
            color: #495057;
            font-weight: 500;
            font-size: 13px;
            border-bottom: 2px solid #dee2e6;
        }
        .inputtax-table td {
            font-size: 13px;
            vertical-align: middle;
        }
        .inputtax-table .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 12px;
        }
        .inputtax-table .fa-calculator {
            color: #ff9800;
        }
        .inputtax-table .fa-edit {
            color: #17a2b8;
        }
        .inputtax-table .fa-trash {
            color: #e74c3c;
        }
        .inputtax-table .fa-file {
            color: #e74c3c;
        }
        .inputtax-table-summary {
            background: #f8f9fa;
            font-weight: 600;
            color: #ff9800;
            border-top: 2px solid #ff9800;
        }
    </style>
    <div class="card info-card shadow-sm">
        <div class="inputtax-table-header d-flex justify-content-between align-items-center">
            <span><i class="fa fa-calculator me-2"></i>รายการต้นทุน</span>
            <a href="javascript:void(0)" class="text-white" onclick="toggleAccordion('table-inputtax', 'toggle-arrow-inputtax')">
                <i class="fas fa-chevron-down" id="toggle-arrow-inputtax"></i>
            </a>
        </div>

        <div class="card-body" id="table-inputtax" style="display: block">
            <div class="table-responsive">
                <table class="table inputtax-table  table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>ประเภท</th>
                            <th>เลขที่เอกสาร</th>
                            <th class="text-center">ไฟล์แนบ</th>
                            <th class="text-end">ค่าบริการ</th>
                            <th class="text-center">ใบหัก ณ ที่จ่าย</th>
                            <th class="text-end">ภาษีหัก</th>
                            <th class="text-end">ภาษี 7%</th>
                            <th class="text-end">ยอดรวม</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    @php
                        $key = 0;
                        $inputTaxTotal = 0;
                    @endphp
                    <tbody>
                        @forelse ($invoiceModel as $item)
                            @php
                                $inputTaxTotal += $invoice->getWithholdingTaxAmountAttribute();
                                $inputTaxTotal += $item->invoice_vat;
                            @endphp

                            <tr style="background-color: #fabb5c1a">
                                <td>{{ ++$key }}</td>
                                <td>ภาษีขาย</td>
                                <td>{{ $item->invoice_number }}</td>
                                <td class="text-center">
                                    <div id="invoice-file-container-{{ $item->invoice_id }}">
                                        <!-- กรณีไม่มีไฟล์ ให้แสดง input สำหรับอัปโหลด -->

                                        @if (empty($item->invoice_image))
                                            <input type="file" name="invoice_file" data-id="{{ $item->invoice_id }}"
                                                onchange="uploadInvoiceImage(this)">
                                        @else
                                            <!-- กรณีมีไฟล์ ให้แสดงปุ่มดูไฟล์และลบไฟล์ -->

                                            <a class="btn btn-sm btn-info"
                                                href="{{ asset('storage/' . $item->invoice_image) }}"
                                                onclick="openPdfPopup(this.href); return false;">เปิดดูไฟล์</a>

                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteInvoiceImage({{ $item->invoice_id }})">ลบไฟล์</button>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end">
                                    {{ number_format($item->invoice_pre_vat_amount, 2) }}
                                </td>
                                <td class="text-center">N/A</td>
                                <td class="text-end">{{ number_format($item->invoice_withholding_tax, 2) }}</td>
                                <td class="text-end">{{ number_format($item->invoice_vat, 2) }}</td>
                                <td class="text-end text-danger">{{ number_format($invoice->getWithholdingTaxAmountAttribute(), 2) }}</td>
                                <td>N/A</td>
                            @empty
                        @endforelse
                        </tr>


                        @forelse ($inputTax as $item)

                            @php

                                if ($item->input_tax_status === 'success') {
                                    // $inputTaxTotal += $item->input_tax_withholding;
                                    // $inputTaxTotal += $item->input_tax_vat;
                                    $inputTaxTotal += $item->input_tax_grand_total;
                                }

                            @endphp
                            <tr class="@if ($item->input_tax_status === 'cancel') text-danger @endif">
                                <td>{{ ++$key }}</td>
                                {{-- <td>type : {{ $item->input_tax_type }}</td> --}}

                                <td>
                                    @if ($item->input_tax_type === 0)
                                        ภาษีซื้อ
                                    @elseif($item->input_tax_type === 1)
                                        ต้นทุนอื่นๆ
                                    @elseif($item->input_tax_type === 2)
                                        ต้นทุนโฮลเซลล์
                                    @elseif($item->input_tax_type === 3)
                                        ค่าธรรมเนียมรูดบัตร
                                    @elseif($item->input_tax_type === 4)
                                        ค่าทัวร์รวมทั้งหมด
                                    @elseif($item->input_tax_type === 5)
                                        ค่าอาหาร
                                    @elseif($item->input_tax_type === 6)
                                        ค่าตั๋วเครื่องบิน
                                    @elseif($item->input_tax_type === 7)
                                        อื่นๆ
                                    @endif


                                </td>
                                <td>
                                    @if ($item->input_tax_ref)
                                        {{ $item->input_tax_ref }}
                                </td>
                            @else
                                N/A
                        @endif
                        <td class="text-center">
                            @if ($item->input_tax_file)
                                <a href="{{ asset('storage/' . $item->input_tax_file) }}" class="btn btn-info btn-sm"
                                    onclick="openPdfPopup(this.href); return false;">
                                    เปิดดูไฟล์</a>

                                    @canany(['filepassport.delete'])
                                <a href="{{ route('inputtax.deletefile', $item->input_tax_id) }}"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Do you want to delete file?');"> ลบไฟล์</a>
                                    @endcanany
                            @elseif($item->input_tax_type !== 0)
                            @canany(['inputtax.edit'])
                                <form action="{{ route('inputtax.update', $item->input_tax_id) }}" method="POST"
                                    enctype="multipart/form-data" id="upload-file-{{ $item->input_tax_id }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="customer_id"
                                        value="{{ $quotationModel->customer_id }}">
                                    <input type="hidden" name="input_tax_quote_number"
                                        value="{{ $quotationModel->quote_number }}">
                                    <input type="file" name="file" id="input-file-{{ $item->input_tax_id }}">
                                </form>
                                @endcanany

                                <script>
                                    $(document).ready(function() {
                                        $("#input-file-{{ $item->input_tax_id }}").change(function() {
                                            $(this).closest("form").submit();
                                        });
                                    });
                                </script>
                            @else
                                รอแนบไฟล์เอกสาร
                            @endif




                        </td>
                        <td class="text-end ">
                            {{ number_format($item->input_tax_service_total, 2) }}

                        </td>
                        <td>
                            @canany(['withholdingtax.export'])
                            @if ($item->input_tax_withholding_status === 'Y' && $document)
                                <a href="{{ route('MPDF.generatePDFwithholding', $document->id) }}"
                                    onclick="openPdfPopup(this.href); return false;"> <i
                                        class="fa fa-file-pdf text-danger"></i> ปริ้นใบหัก ณ ที่จ่าย</a>
                            @else
                                N/A
                            @endif
                            @endcanany

                        </td>

                        <td class="text-end">{{ number_format($item->input_tax_withholding, 2) }} </td>
                        <td class="text-end">{{ number_format($item->input_tax_vat, 2) }}</td>

                        <td class="text-end text-danger">
                          
                            {{ $item->input_tax_grand_total }}
                        </td>

                        <td>
                            @canany(['wholesale.inputtax.edit'])
                             <a href="{{ route('inputtax.editWholesale', $item->input_tax_id) }}"
                                    class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>
                            @if ($item->input_tax_withholding_status === 'Y' && $document)
                            @endcanany
                            
                            @canany(['withholdingtax.edit'])
                             @if ($item->input_tax_withholding_status === 'Y' && $document)
                                   <br>
                                <a href="{{ route('withholding.modalEdit', $document->id) }}"
                                    class="input-tax-edit text-primary">
                                    <i class="fa fa-edit text-primary "></i>แก้ไขใบหัก ณ ที่จ่าย</a>
                             @endif
                              
                            @endcanany
                            @else
                            @endif
                            

                            @if ($item->input_tax_status === 'success')
                            
                                @if ($item->input_tax_wholesale_type === 'Y')
                                <br>
                                @else
                                @canany(['inputtax.delete'])
                                    <a href="{{ route('inputtax.delete', $item->input_tax_id) }}" class="text-danger"
                                        onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"></i>
                                        ลบ</a>
                                @endcanany
                                @endif
                            @else
                            @endif

                        </td>
                        </tr>
                    @empty
                        @endforelse
                        <tr>
                        <tr>

                            @php
                                $withholdingTaxAmount = $invoice?->getWithholdingTaxAmountAttribute() ?? 0;
                                $getTotalInputTaxVat = $quotationModel?->getTotalInputTaxVat() ?? 0;
                              
                                $hasInputTaxFile = $quotationModel
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
                            @endphp
                             <span class="text-danger">รายการต้นทุน ภาษีมูลค่าเพิ่ม VAT + หัก ณ. ที่จ่าย  : {{$withholdingTaxAmount}} บาท </br></span>


                            <td class="text-danger text-end" colspan="8">
                                <b>
                                    @if (isset($inputTax) && count($inputTax) > 0)
                                        <b>(@bathText($quotationModel->getTotalOtherCost()))</b>
                                    @else
                                        <b>(@bathText(0))</b>
                                    @endif
                                </b>
                            </td>



                            <td  class="text-danger text-end" colspan="1">
                                {{-- DEBUG getTotalInputTaxVatType  : {{$quotationModel->getTotalInputTaxVatType()}} <br>
                                DEBUG ภาษีซื้อ getTotalInputTaxVat  : {{$quotationModel->getTotalInputTaxVat()}} <br>
                                DEBUG ภาษีซื้อภาษีหัก getTotalInputTaxVatWithholding  : {{$quotationModel->getTotalInputTaxVatWithholding()}} <br>
                                DEBUG ภาษีขาย : {{$withholdingTaxAmount}} <br>
                                DEBUG getTotalOtherCost : {{$quotationModel->getTotalOtherCost()}} <br>
                                <br/>
                                ------------------------
                                DEBUG ภาษีซื้อ ยังไม่มีไฟล์ : {{ $quotationModel->getTotalInputTaxVatNULL() }} <br>
                                DEBUG ภาษีซื้อ มีไฟล์ : {{ $quotationModel->getTotalInputTaxVatNotNULL() }} <br> --}}

                                <b>
                                    @if (isset($inputTax) && count($inputTax) > 0)
                                        {{ number_format($quotationModel->getTotalOtherCost(), 2) }}
                                    @else
                                        {{ number_format(0, 2) }}
                                    @endif
                                </b>
                            </td>
                            

                        </tr>
                        </tr>


                    </tbody>
                </table>

                {{-- ภาษีขาย : {{ $invoice->getWithholdingTaxAmountAttribute() }} <br>
                ภาษีซื้อ : {{ $quotationModel->getTotalInputTaxVat() }} --}}
            </div>
        </div>
    </div>
</div>



{{-- modal-input-tax edit --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="input-tax-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

{{-- modal-input-tax cancel --}}
<div class="modal fade bd-example-modal-sm modal-lg" id="input-tax-cancel" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<script>
    function uploadInvoiceImage(input) {
        let invoiceId = $(input).data('id');
        let file = input.files[0];

        let reader = new FileReader();
        reader.onload = function(e) {
            let base64File = e.target.result;

            // ตรวจสอบข้อมูล Base64 ก่อนที่จะส่งไปยังเซิร์ฟเวอร์
            console.log("Base64 File Data:", base64File);

            $.ajax({
                url: '{{ route('uploadInvoiceImage') }}',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({
                    invoice_id: invoiceId,
                    invoice_file: base64File
                }),
                success: function(response) {
                    alert(response.message);
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.log("Error response:", xhr.responseText);
                    alert('File upload failed');
                }
            });
        };

        reader.readAsDataURL(file);
    }

    function deleteInvoiceImage(invoiceId) {
        $.ajax({
            url: '{{ route('deleteInvoiceImage') }}',
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({
                invoice_id: invoiceId
            }),
            success: function(response) {
                alert(response.message);

                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.log("Error response:", xhr.responseText);
                alert('File deletion failed');
            }
        });
    }

    $(".input-tax-edit").click("click", function(e) {
        e.preventDefault();
        $("#input-tax-edit")
            .modal("show")
            .addClass("modal-lg")
            .find(".modal-content")
            .load($(this).attr("href"));
    });


    $(".input-tax-cancel").click("click", function(e) {
        e.preventDefault();
        $("#input-tax-cancel")
            .modal("show")
            .addClass("modal-lg")
            .find(".modal-content")
            .load($(this).attr("href"));
    });
</script>
