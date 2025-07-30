<div class="col-md-12">
    <style>
        .inputtax-table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .inputtax-table .fa-receipt {
            color: #764ba2;
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
            color: #764ba2;
            border-top: 2px solid #764ba2;
        }
    </style>
    <div class="card info-card shadow-sm">
        <div class="inputtax-table-header d-flex justify-content-between align-items-center">
            <span><i class="fa fa-receipt me-2"></i>รายการต้นทุนโฮลเซลล์</span>
            <a href="javascript:void(0)" class="text-white" onclick="toggleAccordion('table-inputtax', 'toggle-arrow-inputtax')">
                <i class="fas fa-chevron-down" id="toggle-arrow-inputtax"></i>
            </a>
        </div>

        <div class="card-body" id="table-inputtax" style="display: block">
            <div class="table-responsive">
                <table class="table inputtax-table table-hover table-bordered mb-0">
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
                                {{-- <td class="text-center">{{ $item->input_tax_type }}</td> --}}
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
                                
                                <td class="text-end">{{number_format($item->input_tax_grand_total,2)}}</td>

                                <td >
                                    @if ($item->input_tax_status === 'success')
                                    @if ($item->input_tax_type === 2)
                                    @canany(['wholesale.inputtax.edit'])
                                    <a href="{{route('inputtax.inputtaxEditWholesale',$item->input_tax_id)}}" class="input-tax-edit"> <i class="fa fa-edit"> แก้ไข</i></a>
                                    @endcanany
                                    {{-- <a href="{{route('inputtax.cancelWholesale',$item->input_tax_id)}}" class="text-danger input-tax-cancel"> <i class="fas fa-minus-circle"> ยกเลิก</i></a> --}}
                                    @canany(['inputtax.delete'])
                                    <a href="{{route('inputtax.delete',$item->input_tax_id)}}" class="text-danger input-tax-delete" onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"> ลบ</i></a>
                                    @endcanany
                                    @else
                                    @canany(['wholesale.inputtax.edit'])
                                    <a href="{{route('inputtax.inputtaxEditWholesale',$item->input_tax_id)}}" class="input-tax-edit" > <i class="fa fa-edit"> แก้ไข</i></a>
                                    @endcanany
                                    {{-- <a href="{{route('inputtax.cancelWholesale',$item->input_tax_id)}}" class="text-danger input-tax-cancel"> <i class="fas fa-minus-circle"> ยกเลิก</i></a> --}}
                                    @canany(['inputtax.delete'])
                                    <a href="{{route('inputtax.delete',$item->input_tax_id)}}" class="text-danger input-tax-delete" onclick="return confirm('Do you want to delete?');"> <i class="fa fa-trash"> ลบ</i></a>
                                    @endcanany
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
 
                                <td align="right" class="text-success"  colspan="4"><b>(@bathText($quotationModel->inputtaxTotalWholesale()))</b></td>
                                <td class="text-danger text-end" colspan="1"><b>{{number_format($quotationModel->inputtaxTotalWholesale(),2)}}</b></td>
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