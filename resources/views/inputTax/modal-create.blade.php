<div class="modal-body">
    <div class="header">
        <h5>บันทึกต้นทุน</h5>
    </div>
    <form action="{{ route('inputtax.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>ประเภท</label>
                <select name="input_tax_type" id="input_tax_type" class="form-select">
                    <option value="0">ภาษีซื้อ</option>
                    <option value="1">ต้นทุนอื่นๆ</option>
                    <option value="3">ค่าธรรมเนียมรูดบัตร</option>
                </select>
            </div>

            <input type="hidden" name="input_tax_quote_id" class="form-control" value="{{ $quotationModel->quote_id }}">
            <input type="hidden" name="input_tax_quote_number" class="form-control"value="{{ $quotationModel->quote_number }}">
            <input type="hidden" name="customer_id" class="form-control" value="{{ $quotationModel->customer_id }}">
            <input type="hidden" name="input_tax_wholesale" class="form-control" value="{{ $quotationModel->quote_wholesale }}">

            <div class="col-md-6" style="display: none" id="date-doc-show">
                <label for="">วันที่ออกเอกสาร</label>
                <input type="date" name="input_tax_date_doc" class="form-control" value="{{ date('Y-m-d') }}">
            </div>


            <div class="col-md-12 mb-3">
                <label for=""> วันเดือน ปีภาษี ที่จ่าย </label>
                <input type="date" name="input_tax_date" class="form-control" placeholder="tax number"
                    value="{{ date('Y-m-d') }}">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">เลขที่เอกสารอ้างอิง </label>
                <input type="text" name="input_tax_ref" class="form-control" placeholder="tax number">
            </div>


            <div class="col-md-12 mb-3">
                <label>ยอดค่าบริการ</label>
                <input type="number" class="form-control" name="input_tax_service_total" id="service-total"
                    step="0.01" placeholder="0.0">
            </div>


            <div class="col-md-12 mb-3">
                <label for="">ภาษี ณ ที่จ่าย </label>
                <input type="number" name="input_tax_withholding" step="0.01" class="form-control" placeholder="0.0"
                    id="withholding" readonly style="background-color: antiquewhite">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ภาษีซื้อ</label>
                <input type="number" name="input_tax_vat" step="0.01" class="form-control" placeholder="0.0"
                    id="vat">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ผลรวมต้นทุน</label>
                <input type="number" name="input_tax_grand_total" step="0.01" class="form-control" placeholder="0.0"
                    id="total">
            </div>

            {{-- <div class="col-md-12 mb-3">
                <label for="">ไฟล์เอกสารแนบ</label>
                <input type="file" name="file">
            </div> --}}

        </div>
        <div class="col-md-12" id="tax-input" style="display: none">
            <div class="row">
                <div class="col-md-6">
                    <label for="">วันที่ภาษีซื้อ</label>
                    <input type="date" class="form-control" name="input_tax_date_tax" >
                </div>
                <div class="col-md-6">
                    <label for="">เลขที่ใบกำกับภาษีซื้อ</label>
                    <input type="text" class="form-control" name="input_tax_number_tax" placeholder="เลขที่ใบกำกับภาษีซื้อ">
                </div>
            </div>
        </div>
        <br>

        <div class="col-md-12" style="display: none" id="withholding-show">
            {{-- @if ($document)
            <a href="{{ route('withholding.edit', $document->id) }}">ออกใบหัก ณ ที่จ่ายแล้ว <i
                class="fa fa-edit text-info"></i> {{$document->document_number}}</a>
            @else --}}
            @if (!$document)
            <label for="">ต้องการออกใบหัก ณ ที่จ่ายหรือไม่</label>
            <br>
            <input type="radio" id="input_tax_withholding_status1" name="input_tax_withholding_status" value="Y">
            <label for="html">ใช่</label>
            <input type="radio" id="input_tax_withholding_status2" name="input_tax_withholding_status" value="N"
                checked>
            <label for="css">ไม่ใช่</label><br>
            @endif
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

            //alert(inputTaxType);

            if (inputTaxType === '0') {
                
                // ถ้า input_tax_type === 1 ให้ disable input_tax_withholding_status
                $('#input_tax_withholding_status1').prop('disabled', false);
                $('#input_tax_withholding_status2').prop('disabled', false);
                $('#date-doc-show').show();
                $('#withholding-show').show();
                $('#tax-input').show();

            } else {
                // ถ้า input_tax_type ไม่ใช่ 1 ให้ enable input_tax_withholding_status
                $('#input_tax_withholding_status1').prop('disabled', true);
                $('#input_tax_withholding_status2').prop('disabled', true);
                $('#date-doc-show').hide();
                $('#withholding-show').hide();
                $('#tax-input').hide();
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

        $('#service-total, #vat').on('keyup', function() {
            var inputTaxType = $('#input_tax_type').val();
            let total = 0;
            let vat7 = 0;
            let withholdingTotal = 0;

            let serviceTotal = parseFloat($('#service-total').val()) || 0;
            let vat = parseFloat($('#vat').val()) || 0;

            if (inputTaxType === '1' | inputTaxType === '3') {
                $('#total').val(serviceTotal.toFixed(2));
            } else {
                // คำนวณ VAT 7%
                vat7 = serviceTotal * 0.07;
                $('#vat').val(vat7.toFixed(2)); // อัปเดตค่า VAT ในช่อง input

                // คำนวณภาษี ณ ที่จ่าย (3%)
                withholdingTotal = serviceTotal * 0.03;
                $('#withholding').val(withholdingTotal.toFixed(2));
                // คำนวณผลรวมต้นทุน (รวมค่าบริการ ภาษี ณ ที่จ่าย และ VAT)
                total = serviceTotal + withholdingTotal + vat7;
                $('#total').val(withholdingTotal.toFixed(2));
            }


        });

    });
</script>
