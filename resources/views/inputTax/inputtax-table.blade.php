<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-success">
            <h5 class="mb-0 text-white"><i class="fa fa-file"></i>
                รายการต้นทุน <span class="float-end"></span></h5>
        </div>

        <div class="card-body">
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
                        $inputTaxTotal += $item->invoice_withholding_tax;
                        $inputTaxTotal += $item->invoice_vat;
                        @endphp

                        <tr>
                            <td>{{++$key}}</td>
                            <td>ภาษีขาย</td>
                            <td>{{$quotationModel->quote_number}}</td>
                            <td>-</td>
                            <td>
                                {{number_format($item->invoice_pre_vat_amount,2)}}
                              
                            </td>
                            <td>
                                @if ($item->invoice_withholding_tax > 0)
                                <a href="#"> <i class="fa fa-file-pdf text-danger"></i> ใบหัก ณ ที่จ่าย</a>
                                @else
                                    -
                                @endif
                                
                            </td>
                            <td>{{number_format($item->invoice_withholding_tax,2)}}</td>
                            <td>{{number_format($item->invoice_vat,2)}}</td>
                            <td>{{number_format($item->invoice_vat + $item->invoice_withholding_tax,2)}}</td>
                            <td>-</td>
                        @empty
                            
                        @endforelse

                       
                        </tr>

                        @forelse ($inputTax as $item)

                        @php

                        if ($item->input_tax_status === 'success') {
                        $inputTaxTotal += $item->input_tax_withholding;
                        $inputTaxTotal += $item->input_tax_vat;
                        }

                    
                        @endphp
                            <tr class="@if($item->input_tax_status === 'cancel') text-danger @endif">
                                <td>{{++$key}}</td>
                                <td>
                                    @if ($item->input_tax_type === 0)
                                        ภาษีซื้อ
                                    @else
                                        ต้นทุนอื่นๆ
                                    @endif
                                </td>
                                <td>

                                    @if ($item->input_tax_ref)
                                    {{$item->input_tax_ref}}</td>
                                    @else
                                        -
                                    @endif
                                    
                                   
                                
                                <td>
                                    <a href="{{ asset('storage/' . $item->input_tax_file) }}"
                                        onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-file text-danger"></i> ไฟล์แนบ</a>
                                </td>
                                <td>
                                    {{number_format($item->input_tax_service_total,2)}}
                                   
                                </td>
                                <td>
                                    @if ($item->input_tax_withholding > 0)
                                    <a href="#"> <i class="fa fa-file-pdf text-danger"></i> ใบหัก ณ ที่จ่าย</a>
                                    @else
                                        -
                                    @endif
                                    
                                </td>

                                <td>{{number_format($item->input_tax_withholding,2)}}</td>
                                <td>{{number_format($item->input_tax_vat,2)}}</td>
                                
                                <td>{{number_format($item->input_tax_grand_total,2)}}</td>

                                <td>
                                    @if ($item->input_tax_status === 'success')
                                    <a href="{{route('inputtax.editWholesale',$item->input_tax_id)}}" class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>
                                    <a href="{{route('inputtax.cancelWholesale',$item->input_tax_id)}}" class="text-danger input-tax-cancel"> <i class="fas fa-minus-circle"> ยกเลิก</i></a>
                                    @else
                                        {{$item->input_tax_cancel}}
                                    @endif
                                    
                                </td>
                            </tr>
                        @empty
                            
                        @endforelse

                        <tr>
                            <tr>
 
                                <td align="right" class="text-success"  colspan="7"><b>(@bathText($inputTaxTotal))</b></td>
                                <td align="center" class="text-danger" colspan="1"><b>{{number_format($inputTaxTotal,2)}}</b></td>
                            </tr>
                        </tr>

                      
                    </tbody>
                </table>
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