<div class="modal-body">
    <div class="header">
        <h5>แก้ไขต้นทุน</h5>
    </div>
    <form action="{{route('inputtax.update',$inputTaxModel->input_tax_id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- <input type="text" name="document_id" value="{{$document->id}}"> --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>ประเภท</label>
                <select name="input_tax_type" id="" class="form-select">
                    <option @if($inputTaxModel->input_tax_type === 0) selected @endif value="0">ภาษีซื้อ</option>
                    <option @if($inputTaxModel->input_tax_type === 1) selected @endif value="1">ต้นทุนอื่นๆ</option>
                    <option @if($inputTaxModel->input_tax_type === 3) selected @endif value="1">ค่าธรรมเนียมรูดบัตร</option>
                </select>
            </div>
            <input type="hidden" name="input_tax_quote_id" class="form-control" value="{{ $quotationModel->quote_id }}">
            <input type="hidden" name="input_tax_quote_number" class="form-control"value="{{ $quotationModel->quote_number }}">
            <input type="hidden" name="customer_id" class="form-control" value="{{ $quotationModel->customer_id }}">
            <input type="hidden" name="input_tax_wholesale" class="form-control" value="{{ $quotationModel->quote_wholesale }}">
            <div class="col-md-6">
                <label for="">วันที่ออกเอกสาร</label>
                <input type="date" name="input_tax_date_doc" class="form-control" value="{{$inputTaxModel->input_tax_date_doc}}">
            </div>

            {{-- <div class="col-md-12 mb-3">
                <label for="">โฮลเซลล์ </label>
                <select name="input_tax_wholesale" class="form-select selectpicker selectpicker-select" data-live-search="true">
                    <option value="0">ไม่ระบุ</option>
                    @forelse ($wholesale as $item)
                    <option @if($item->id === $inputTaxModel->input_tax_wholesale) selected @endif value="{{$item->id}}">{{$item->wholesale_name_th}}</option>
                    @empty
                        
                    @endforelse
                </select>
            </div> --}}
            <div class="col-md-12 mb-3">
                <label for=""> วันเดือน ปีภาษี ที่จ่าย </label>
                <input type="date" name=" input_tax_date" class="form-control" placeholder="tax number"  value="{{$inputTaxModel->input_tax_date}}">
            </div>
            <div class="col-md-12 mb-3">
                <label for="">เลขที่เอกสารอ้างอิง </label>
                <input type="text" name="input_tax_ref" class="form-control" value="{{$inputTaxModel->input_tax_ref}}" >
            </div>

            <div class="col-md-12 mb-3">
                <label>ยอดค่าบริการ</label>
                <input type="number" class="form-control" name="input_tax_service_total" id="service-total" step="0.01" placeholder="0.0" value="{{$inputTaxModel->input_tax_service_total}}">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ภาษี ณ ที่จ่าย </label>
                <input type="number" name="input_tax_withholding" step="0.01" value="{{$inputTaxModel->input_tax_withholding}}" class="form-control" placeholder="0.0" id="withholding" readonly style="background-color: antiquewhite">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ภาษี 7%</label>
                <input type="number" name="input_tax_vat" step="0.01" value="{{$inputTaxModel->input_tax_vat}}" class="form-control" placeholder="0.0" id="vat">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ผลรวมต้นทุน</label>
                <input type="number" name="input_tax_grand_total" step="0.01" value="{{$inputTaxModel->input_tax_grand_total}}"  class="form-control" placeholder="0.0" id="total" >
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ไฟล์เอกสารแนบ</label>
                <input type="file" name="file">
            </div>

            <div class="col-md-12 mb-3">
                
              
                <br>
                @if ( $inputTaxModel->input_tax_file)
                <input type="checkbox" name="delete_file" value="Y">
                <label for="">ต้องการลบไฟล์แนบ</label>
                <a href="{{ asset('storage/' . $inputTaxModel->input_tax_file) }}"
                    onclick="openPdfPopup(this.href); return false;"><i class="fa fa-file text-danger"></i>
                    {{$inputTaxModel->input_tax_file}}</a>
                @else
                    
                @endif
               
            </div>


            <div class="col-md-12">
                @if (!$document)
                <label for="">ต้องการออกใบหัก ณ ที่จ่ายหรือไม่</label>
                <br>
                <input type="radio" id="html" name="input_tax_withholding_status" value="Y" @if($inputTaxModel->input_tax_withholding_status === 'Y') checked @endif>
                <label for="html">ใช่</label>
                <input type="radio" id="css" name="input_tax_withholding_status" value="N" @if($inputTaxModel->input_tax_withholding_status === 'N') checked @endif>
                <label for="css">ไม่ใช่</label><br>
                @endif
            </div>
            
        </div>
       
        <br>
        <button type="submit" class="btn btn-sm btn-success">บันทึก</button>
    </form>
</div>


  

<script>
      $(document).ready(function() {
                 $('.selectpicker').selectpicker({
                     width: '100%'
                 });
             });
             
  $(document).ready(function() {

    $('#service-total, #vat').on('keyup', function () {
    let total = 0;
    let vat7 = 0;
    let withholdingTotal = 0;
    
    let serviceTotal = parseFloat($('#service-total').val()) || 0;
    let vat = parseFloat($('#vat').val()) || 0;
    
    // คำนวณ VAT 7%
    vat7 = serviceTotal * 0.07;
    $('#vat').val(vat7.toFixed(2));  // อัปเดตค่า VAT ในช่อง input

    // คำนวณภาษี ณ ที่จ่าย (3%)
    withholdingTotal = serviceTotal * 0.03;
    $('#withholding').val(withholdingTotal.toFixed(2));

    // คำนวณผลรวมต้นทุน (รวมค่าบริการ ภาษี ณ ที่จ่าย และ VAT)
    total = serviceTotal + withholdingTotal + vat7;
    $('#total').val(withholdingTotal.toFixed(2));
});

  });
</script>