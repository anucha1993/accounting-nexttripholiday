<div class="modal-body">
    <div class="header">
        <h5>บันทึกต้นทุน</h5>
    </div>
    <form action="{{route('inputtax.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-md-12 mb-3">
                <label>ประเภท</label>
                <select name="input_tax_type" id="input_tax_type" class="form-select">
                    <option value="0">ภาษีซื้อ</option>
                    <option value="1">ต้นทุนอื่นๆ</option>
                    <option value="3">ค่าธรรมเนียมรูดบัตร</option>
                </select>
            </div>
            
            <input type="hidden" name="input_tax_quote_id" class="form-control" value="{{$quotationModel->quote_id}}" >
            <input type="hidden" name="input_tax_quote_number" class="form-control" value="{{$quotationModel->quote_number}}" >
            <input type="hidden" name="customer_id" class="form-control" value="{{$quotationModel->customer_id}}" >
            <input type="hidden" name="input_tax_wholesale" class="form-control" value="{{$quotationModel->quote_wholesale}}" >
               
            {{-- <div class="col-md-12 mb-3">
                <label for="">โฮลเซลล์ </label>
                <select name="input_tax_wholesale" class="form-select selectpicker selectpicker-select" data-live-search="true">
                    <option value="0">ไม่ระบุ</option>
                    @forelse ($wholesale as $item)
                    <option value="{{$item->id}}">{{$item->wholesale_name_th}}</option>
                    @empty
                        
                    @endforelse
                </select>
            </div> --}}
          
            <div class="col-md-12 mb-3">
                <label for=""> วันเดือน ปีภาษี ที่จ่าย </label>
                <input type="date" name=" input_tax_date" class="form-control" placeholder="tax number" value="{{date('Y-m-d')}}" >
            </div>

            <div class="col-md-12 mb-3">
                <label for="">เลขที่เอกสารอ้างอิง </label>
                <input type="text" name="input_tax_ref" class="form-control" placeholder="tax number"  value="{{$taxinvoice->taxinvoice_number ? $taxinvoice->taxinvoice_number : '' }}">
            </div>

            
            <div class="col-md-12 mb-3">
                <label>ยอดค่าบริการ</label>
                <input type="number" class="form-control" name="input_tax_service_total" id="service-total" step="0.01" placeholder="0.0" >
            </div>


            <div class="col-md-12 mb-3">
                <label for="">ภาษี ณ ที่จ่าย </label>
                <input type="number" name="input_tax_withholding" step="0.01" class="form-control" placeholder="0.0" id="withholding"  readonly style="background-color: antiquewhite">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ภาษีซื้อ</label>
                <input type="number" name="input_tax_vat" step="0.01" class="form-control" placeholder="0.0" id="vat">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ผลรวมต้นทุน</label>
                <input type="number" name="input_tax_grand_total" step="0.01" class="form-control" placeholder="0.0" id="total" >
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ไฟล์เอกสารแนบ</label>
                <input type="file" name="file">
            </div>
            
        </div>

        <div class="col-md-12">
            {{-- @if ($document)
            <a href="{{ route('withholding.edit', $document->id) }}">ออกใบหัก ณ ที่จ่ายแล้ว <i
                class="fa fa-edit text-info"></i> {{$document->document_number}}</a>
            @else --}}
            <label for="">ต้องการออกใบหัก ณ ที่จ่ายหรือไม่</label>
            <br>
            <input type="radio" id="input_tax_withholding_status1" name="input_tax_withholding_status" value="Y">
            <label for="html">ใช่</label>
            <input type="radio" id="input_tax_withholding_status2" name="input_tax_withholding_status" value="N" checked>
            <label for="css">ไม่ใช่</label><br>
            {{-- @endif --}}
           
        </div>

        <br>
        <button type="submit" class="btn btn-sm btn-success">บันทึก</button>
    </form>
</div>

<script>
 $(document).ready(function() {
    // เมื่อค่า input_tax_type มีการเปลี่ยนแปลง
    $('#input_tax_type').change(function() {
        var inputTaxType = $(this).val(); // รับค่าจาก input_tax_type

        if (inputTaxType === '1') {
            // ถ้า input_tax_type === 1 ให้ disable input_tax_withholding_status
            $('#input_tax_withholding_status1').prop('disabled', true);
            $('#input_tax_withholding_status2').prop('disabled', true);
        } else {
            // ถ้า input_tax_type ไม่ใช่ 1 ให้ enable input_tax_withholding_status
            $('#input_tax_withholding_status1').prop('disabled', false);
            $('#input_tax_withholding_status2').prop('disabled', false);
        }
    });

    // เรียกใช้งานทันทีเพื่อให้รองรับค่าเริ่มต้น
    $('#input_tax_type').trigger('change');
});

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