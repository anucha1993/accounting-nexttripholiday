<div class="modal-body">
    <div class="header">
        <h5>แก้ไขต้นทุน</h5>
    </div>
    <form action="{{route('inputtax.update',$inputTaxModel->input_tax_id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 mb-3">
                <label>ประเภท</label>
                <select name="input_tax_type" id="" class="form-select">
                    <option @if($inputTaxModel === 0) selected @endif value="0">ภาษีซื้อ</option>
                    <option @if($inputTaxModel === 1) selected @endif value="1">ต้นทุนอื่นๆ</option>
                </select>
            </div>
            <input type="hidden" name="input_tax_quote_id" class="form-control" value="{{$quotationModel->quote_id}}" >
            <input type="hidden" name="input_tax_quote_number" class="form-control" value="{{$quotationModel->quote_number}}" >
            <input type="hidden" name="customer_id" class="form-control" value="{{$quotationModel->customer_id}}" >
            <div class="col-md-12 mb-3">
                <label for="">เลขที่เอกสารอ้างอิง </label>
                <input type="text" name="input_tax_ref" class="form-control" value="{{$inputTaxModel->input_tax_ref}}" >
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ภาษี ณ ที่จ่าย </label>
                <input type="number" name="input_tax_withholding" step="0.01" value="{{$inputTaxModel->input_tax_withholding}}" class="form-control" placeholder="0.0" id="withholding">
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
            
        </div>
        <br>
        <button type="submit" class="btn btn-sm btn-success">บันทึก</button>
    </form>
</div>


  

<script>
  $(document).ready(function() {
    $('#withholding, #vat').on('keyup', function () {
        let total = 0;
        let withholding = parseFloat($('#withholding').val()) || 0;
        let vat = parseFloat($('#vat').val()) || 0;
        total = withholding+vat;
        $('#total').val((total).toFixed(2));;

    });
  });
</script>