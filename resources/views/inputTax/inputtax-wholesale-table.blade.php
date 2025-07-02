<div class="col-md-12">
    <div class="card info-card">
        <div class="card-header">
            <i class="fa fa-receipt me-2"></i>รายการต้นทุนโฮลเซลล์
            <a href="javascript:void(0)" class="float-end text-white" onclick="toggleAccordion('table-inputtax', 'toggle-arrow-inputtax')">
                <i class="fas fa-chevron-down" id="toggle-arrow-inputtax"></i>
            </a>
        </div>

        <div class="card-body" id="table-inputtax" style="display: block">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>ประเภท</th>
                            <th>วันที่</th>
                            <th class="text-center">ไฟล์แนบ</th>
                            <th class="text-end">ยอดทั้งสิ้น</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    @php
                        $key = 0;
                        $inputTaxTotal = 0;
                    @endphp
                    <tbody>
                        @forelse ($inputTax as $item)
                        @php
                        if ($item->input_tax_status === 'success') {
                        $inputTaxTotal += $item->input_tax_grand_total;
                        }
                        @endphp
                            <tr class="@if($item->input_tax_status === 'cancel') text-danger @endif">
                                <td>{{++$key}}</td>
                                <td>
                                    
                                    @if ($item->input_tax_type === 0)
                                    ภาษีซื้อ
                                    @elseif($item->input_tax_type === 1)
                                    ต้นทุนอื่นๆ
                                    @elseif($item->input_tax_type === 2)
                                    ต้นทุนโฮลเซลล์
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
                                    {{date('d/m/Y : H:m:s',strtotime($item->created_at))}}
                                </td>

                                <td>
                                    @if ($item->input_tax_file)
                                    <a href="{{ asset('storage/' . $item->input_tax_file) }}"
                                        onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-file text-danger"></i> ไฟล์แนบ</a>
                                    @else
                                        -
                                    @endif
                                    
                                </td>
                                
                                <td>{{number_format($item->input_tax_grand_total,2)}}</td>

                                <td>
                                    @if ($item->input_tax_status === 'success')
                                    @if ($item->input_tax_type === 2)
                                    <a href="{{route('inputtax.inputtaxEditWholesale',$item->input_tax_id)}}" class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>
                                    {{-- <a href="{{route('inputtax.cancelWholesale',$item->input_tax_id)}}" class="text-danger input-tax-cancel"> <i class="fas fa-minus-circle"> ยกเลิก</i></a> --}}
                                    <a href="{{route('inputtax.delete',$item->input_tax_id)}}" class="text-danger input-tax-cancel" onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"> ลบ</i></a>
                                    @else
                                    <a href="{{route('inputtax.inputtaxEditWholesale',$item->input_tax_id)}}" class="input-tax-edit" > <i class="fa fa-edit"> แก้ไข</i></a>
                                    {{-- <a href="{{route('inputtax.cancelWholesale',$item->input_tax_id)}}" class="text-danger input-tax-cancel"> <i class="fas fa-minus-circle"> ยกเลิก</i></a> --}}
                                    <a href="{{route('inputtax.delete',$item->input_tax_id)}}" class="text-danger input-tax-cancel" onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"> ลบ</i></a>
                                    @endif
                                    @else
                                        {{$item->input_tax_cancel}}
                                    @endif
                                    
                                </td>
                            </tr>
                            
                        @empty
                            
                        @endforelse

                        <tr>
                            <tr>
 
                                <td align="right" class="text-success"  colspan="5"><b>(@bathText($quotationModel->inputtaxTotalWholesale()))</b></td>
                                <td align="left" class="text-danger" colspan="1"><b>{{number_format($quotationModel->inputtaxTotalWholesale(),2)}}</b></td>
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