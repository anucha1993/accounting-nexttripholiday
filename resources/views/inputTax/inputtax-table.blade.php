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
                            <th>จำนวน:หัก ณ ที่จ่าย</th>
                            <th>จำนวน:ภาษี</th>
                            <th>ยอดทั้งสิ้น</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    @php
                        $key = 0;
                        $inputTaxTotal = 0;
                    @endphp
                    <tbody>

                        @php
                        $inputTaxTotal += $quotationModel->quote_withholding_tax;
                        $inputTaxTotal += $quotationModel->quote_vat;
                        @endphp

                        <tr>
                            <td>{{++$key}}</td>
                            <td>ภาษีขาย</td>
                            <td>{{$quotationModel->quote_number}}</td>
                            <td>-</td>
                            <td>
                                {{number_format($quotationModel->quote_withholding_tax,2)}}
                              
                            </td>
                            <td>{{number_format($quotationModel->quote_vat,2)}}</td>
                            <td>{{number_format($quotationModel->quote_vat + $quotationModel->quote_withholding_tax,2)}}</td>
                            <td>-</td>
                        </tr>

                        @forelse ($inputTax as $item)

                        @php
                        $inputTaxTotal += $item->input_tax_withholding;
                        $inputTaxTotal += $item->input_tax_vat;
                        @endphp

                            <tr>
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
                                    {{number_format($item->input_tax_withholding,2)}}
                                   
                                </td>
                                <td>{{number_format($item->input_tax_vat,2)}}</td>
                                <td>{{number_format($item->input_tax_grand_total,2)}}</td>

                                <td>
                                    <a href=""> <i class="fa fa-edit"> แก้ไข</i></a>
                                    <a href="" class="text-danger"> <i class="fas fa-minus-circle"> ยกเลิก</i></a>
                                </td>
                            </tr>
                        @empty
                            
                        @endforelse

                        <tr>
                            <tr>
 
                                <td align="right" class="text-success" colspan="7"><b>(@bathText($inputTaxTotal))</b></td>
                                <td align="center" class="text-danger" colspan="1"><b>{{number_format($inputTaxTotal,2)}}</b></td>
                            </tr>
                        </tr>

                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>