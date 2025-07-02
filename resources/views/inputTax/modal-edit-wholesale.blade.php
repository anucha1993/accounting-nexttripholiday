<div class="modal-body">
    <div class="header">
        <h5>แก้ไขต้นทุนโฮลเซลล์</h5>
    </div>
    <form action="{{route('inputtax.update',$inputTaxModel->input_tax_id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 mb-3">
                <label>ประเภท</label>
                <select name="input_tax_type" id="" class="form-select">
                    <option {{$inputTaxModel->input_tax_type === 4 ? 'selected' : '' }}   value="4">ค่าทัวร์รวมทั้งหมด</option>
                    <option {{$inputTaxModel->input_tax_type === 2 ? 'selected' : '' }}   value="2">ค่าห้อง</option>
                    <option {{$inputTaxModel->input_tax_type === 5 ? 'selected' : '' }}   value="5">ค่าอาหาร</option>
                    <option {{$inputTaxModel->input_tax_type === 6 ? 'selected' : '' }}   value="6">ค่าตั๋วเครื่องบิน</option>
                    <option {{$inputTaxModel->input_tax_type === 7 ? 'selected' : '' }}   value="7">อื่นๆ</option>
                </select>
            </div>
            <input type="hidden" name="input_tax_quote_id" class="form-control" value="{{$quotationModel->quote_id}}" >
            <input type="hidden" name="input_tax_quote_number" class="form-control" value="{{$quotationModel->quote_number}}" >
            <input type="hidden" name="customer_id" class="form-control" value="{{$quotationModel->customer_id}}" >

            <div class="col-md-12 mb-3">
                <label for="">วันที่บันทึกเอกสาร</label>
                <input type="date-local" name="" class="form-control" placeholder="tax number" value="{{date('d-m-Y : H:m:s')}}" disabled >
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ผลรวมต้นทุน</label>
                <input type="number" name="input_tax_grand_total" step="0.01" value="{{$inputTaxModel->input_tax_grand_total}}"  class="form-control" placeholder="0.0" id="total" >
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ไฟล์เอกสารแนบ</label>
                <input type="file" name="file">
            </div>

           

            <input type="hidden" id="css" name="input_tax_withholding_status" value="N" checked>
            {{-- <div class="col-md-12">
                <label for="">ต้องการออกใบหัก ณ ที่จ่ายหรือไม่</label>
                <br>
                <input type="radio" id="html" name="input_tax_withholding_status" value="Y" @if($inputTaxModel->input_tax_withholding_status === 'Y') checked @endif>
                <label for="html">ใช่</label>
                <input type="radio" id="css" name="input_tax_withholding_status" value="N" @if($inputTaxModel->input_tax_withholding_status === 'N') checked @endif>
                <label for="css">ไม่ใช่</label><br>
            </div> --}}
            
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