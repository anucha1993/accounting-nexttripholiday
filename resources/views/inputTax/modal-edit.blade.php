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
                <label for="">โฮลเซลล์ </label>
                <select name="input_tax_wholesale" class="form-select selectpicker selectpicker-select" data-live-search="true">
                    <option value="0">ไม่ระบุ</option>
                    @forelse ($wholesale as $item)
                    <option @if($item->id === $inputTaxModel->input_tax_wholesale) selected @endif value="{{$item->id}}">{{$item->wholesale_name_th}}</option>
                    @empty
                        
                    @endforelse
                </select>
            </div>
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
                <input type="number" class="form-control" name="input_tax_service_total" id="service-total" placeholder="0.0" value="{{$inputTaxModel->input_tax_service_total}}">
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
        let withholding = parseFloat($('#withholding').val()) || 0;
        let serviceTotal = parseFloat($('#service-total').val()) || 0;
        let vat = parseFloat($('#vat').val()) || 0;
        total = withholding+vat;
        withholdingTotal = serviceTotal * 0.03 || 0;
        $('#withholding').val(withholdingTotal);
        $('#total').val((total).toFixed(2));;
    });
  });
</script>