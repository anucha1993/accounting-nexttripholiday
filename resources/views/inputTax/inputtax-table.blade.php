<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success">
            <h5 class="mb-0 text-white"><i class="fa fa-file"></i>
                รายการต้นทุน<span class="float-end"></span>
                &nbsp; <a href="javascript:void(0)" class="text-white float-end"
                    onclick="toggleAccordion('table-inputtax', 'toggle-arrow-inputtax')">
                    <span class="fas fa-chevron-down" id="toggle-arrow-inputtax"></span>
                </a>
            </h5>
        </div>

        <div class="card-body" id="table-inputtax" style="display: block">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th style="width: 100px">ลำดับ</th>
                            <th>ประเภท</th>
                            <th>เลขที่เอกสารอ้างอิง</th>
                            <th>ไฟล์แนบ</th>
                            <th>ยอดค่าบริการ</th>
                            <th>ใบหัก ณ ที่จ่าย</th>
                            <th>จำนวน:ภาษีหัก</th>
                            <th>จำนวน:ภาษี 7%</th>
                            <th>ยอดทั้งสิ้น</th>
                            <th>Actions</th>
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

                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>ภาษีขาย</td>
                                <td>{{ $item->invoice_number }}</td>
                                <td>
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
                                <td>
                                    {{ number_format($item->invoice_pre_vat_amount, 2) }}
                                </td>
                                <td>N/A</td>
                                <td>{{ number_format($item->invoice_withholding_tax, 2) }}</td>
                                <td>{{ number_format($item->invoice_vat, 2) }}</td>
                                <td>{{ number_format($invoice->getWithholdingTaxAmountAttribute(), 2) }}</td>
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
                        <td>
                          @if ($item->input_tax_file)
                            <a href="{{ asset('storage/' . $item->input_tax_file) }}" class="btn btn-info btn-sm" onclick="openPdfPopup(this.href); return false;">
                             เปิดดูไฟล์</a>

                             <a href="{{route('inputtax.deletefile',$item->input_tax_id)}}" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete file?');"> ลบไฟล์</a>
                            @else

                            <form action="{{route('inputtax.update',$item->input_tax_id)}}" method="POST" enctype="multipart/form-data"  id="upload-file-{{$item->input_tax_id}}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="customer_id" value="{{$quotationModel->customer_id}}">
                                <input type="hidden" name="input_tax_quote_number" value="{{$quotationModel->quote_number}}">
                                <input type="file"   name="file" id="input-file-{{$item->input_tax_id}}">
                            </form>
                        
                            <script>
                                $(document).ready(function() {
                                    $("#input-file-{{$item->input_tax_id}}").change(function() {
                                        $(this).closest("form").submit();
                                    });
                                });
                            </script>
                            @endif 
                           



                        </td>
                        <td>
                            {{ number_format($item->input_tax_service_total, 2) }}

                        </td>
                        <td>
                            @if ($item->input_tax_withholding_status === 'Y' && ($document))
                                <a href="{{ route('MPDF.generatePDFwithholding', $document->id) }}"
                                    onclick="openPdfPopup(this.href); return false;"> <i
                                        class="fa fa-file-pdf text-danger"></i> ปริ้นใบหัก ณ ที่จ่าย</a>
                            @else
                                N/A
                            @endif

                        </td>

                        <td>{{ number_format($item->input_tax_withholding, 2) }} </td>
                        <td>{{ number_format($item->input_tax_vat, 2) }}</td>

                        <td>
                            {{-- @if ($item->input_tax_withholding_status === 'Y') 
                            {{ number_format($item->input_tax_grand_total, 2) }}
                            @elseif($item->input_tax_wholesale_type === 'Y' )
                            {{ number_format($item->input_tax_grand_total, 2) }}
                            @else
                            {{ number_format(0, 2) }}
                            @endif --}}
                            {{ $item->input_tax_grand_total }}
                        </td>

                        <td>
                            @if ($item->input_tax_withholding_status === 'Y' && ($document))
                            
                                <a href="{{ route('withholding.modalEdit', $document->id) }}" class="input-tax-edit text-primary">
                                    <i class="fa fa-edit text-primary "></i>แก้ไขใบหัก ณ ที่จ่าย</a>

                                    {{-- <a href="{{ route('inputtax.cancelWholesale', $item->input_tax_id) }}"
                                        class="text-danger input-tax-cancel"> <i class="fas fa-minus-circle">
                                            ยกเลิก</i></a> --}}

                                            {{-- <a href="{{ route('inputtax.delete', $item->input_tax_id) }}" class="text-danger"
                                                onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"></i>
                                                ลบ</a> --}}
                                              
                            @else
                                
                            @endif
                            <br>
                            @if ($item->input_tax_status === 'success')
                                @if ($item->input_tax_wholesale_type === 'Y')
                                    <a href="{{ route('inputtax.inputtaxEditWholesale', $item->input_tax_id) }}"
                                        class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>


                                    {{-- <a href="{{ route('inputtax.cancelWholesale', $item->input_tax_id) }}"
                                        class="text-danger input-tax-cancel"> <i class="fas fa-minus-circle">
                                            ยกเลิก</i></a> --}}
                                @else
                                    <a href="{{ route('inputtax.editWholesale', $item->input_tax_id) }}"
                                        class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>

                                        <a href="{{ route('inputtax.delete', $item->input_tax_id) }}" class="text-danger"
                                            onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"></i>
                                            ลบ</a>

                                    {{-- @if ($item->input_tax_withholding_status !== 'Y')
                                    <a href="{{ route('inputtax.delete', $item->input_tax_id) }}" class="text-danger"
                                        onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"></i>
                                        ลบ</a>
                                       @endif --}}
                                @endif
                            @else
                                {{-- {{ $item->input_tax_cancel }} --}}
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
                                // echo $withholdingTaxAmount."</br>";
                                // echo $getTotalInputTaxVat."</br>";

                                // ตรวจสอบว่า input_tax_file === NULL หรือไม่
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
                            <td align="right" class="text-success" colspan="7">
                                <b>(@bathText($paymentInputtaxTotal+$quotationModel->getTotalInputTaxVatType()))</b>
                            </td>
                            <td align="center" class="text-danger" colspan="1">
                                <b>
                                    {{ number_format($paymentInputtaxTotal+$quotationModel->getTotalInputTaxVatType(), 2) }}
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
