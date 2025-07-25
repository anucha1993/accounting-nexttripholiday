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
                <select name="input_tax_type" id="input_tax_type" class="form-select" disabled>
                    <option @if($inputTaxModel->input_tax_type === 0) selected @endif value="0">ภาษีซื้อ</option>
                    <option @if($inputTaxModel->input_tax_type === 1) selected @endif value="1">ต้นทุนอื่นๆ</option>
                    <option @if($inputTaxModel->input_tax_type === 3) selected @endif value="3">ค่าธรรมเนียมรูดบัตร</option>
                </select>
            </div>

            <input type="hidden" name="input_tax_quote_id" class="form-control" value="{{ $quotationModel->quote_id }}">
            <input type="hidden" name="input_tax_quote_number" class="form-control"value="{{ $quotationModel->quote_number }}">
            <input type="hidden" name="customer_id" class="form-control" value="{{ $quotationModel->customer_id }}">
            <input type="hidden" name="input_tax_wholesale" class="form-control" value="{{ $quotationModel->quote_wholesale }}">
            <div class="col-md-6" style="display: none" id="date-doc-show">
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
            <div class="col-md-12 mb-3" id="tax-date-show">
                <label for=""> วันเดือน ปีภาษี ที่จ่าย <span class="text-danger">*</span></label>
                <input type="date" name="input_tax_date" class="form-control" placeholder="tax number"  value="{{$inputTaxModel->input_tax_date}}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label for="">เลขที่เอกสารอ้างอิง <span class="text-danger">*</span></label>
                <input type="text" name="input_tax_ref" class="form-control" value="{{$inputTaxModel->input_tax_ref}}" required>
            </div>

            <div class="col-md-12 mb-3">
                <label>ยอดค่าบริการ <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="input_tax_service_total" id="service-total" step="0.01" placeholder="0.0" value="{{$inputTaxModel->input_tax_service_total}}" required>
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ภาษี ณ ที่จ่าย </label>
                <input type="number" name="input_tax_withholding" step="0.01" value="{{$inputTaxModel->input_tax_withholding}}" class="form-control" placeholder="0.0" id="withholding" readonly style="background-color: antiquewhite">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ภาษีซื้อ</label>
                <input type="number" name="input_tax_vat" step="0.01" value="{{$inputTaxModel->input_tax_vat}}" class="form-control" placeholder="0.0" id="vat">
            </div>

            <div class="col-md-12 mb-3">
                <label for="">ผลรวมต้นทุน</label>
                <input type="number" name="input_tax_grand_total" step="0.01" value="{{$inputTaxModel->input_tax_grand_total}}"  class="form-control" placeholder="0.0" id="total" >
            </div>

            <div class="col-md-12" id="tax-input" style="display: none">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">วันที่ภาษีซื้อ</label>
                        <input type="date" class="form-control" name="input_tax_date_tax" value="{{$inputTaxModel->input_tax_date_tax}}">
                    </div>
                    <div class="col-md-6">
                        <label for="">เลขที่ใบกำกับภาษีซื้อ</label>
                        <input type="text" class="form-control" name="input_tax_number_tax" placeholder="เลขที่ใบกำกับภาษีซื้อ" value="{{$inputTaxModel->input_tax_number_tax}}">
                    </div>
                </div>
            </div>
            <br>
            <br>

            <div class="col-md-12 mt-3">
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

          


            <div class="col-md-12" style="display: none" id="withholding-show">
                @if (!$document)
                <label for="">ต้องการออกใบหัก ณ ที่จ่ายหรือไม่</label>
                <br>
                <input type="radio" id="input_tax_withholding_status1" name="input_tax_withholding_status" value="Y" @if($inputTaxModel->input_tax_withholding_status === 'Y') checked @endif>
                <label for="html">ใช่</label>
                <input type="radio" id="input_tax_withholding_status2" name="input_tax_withholding_status" value="N" @if($inputTaxModel->input_tax_withholding_status === 'N') checked @endif>
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
        // เมื่อค่า input_tax_type มีการเปลี่ยนแปลง
        $('#input_tax_type').change(function() {
            var inputTaxType = $(this).val(); // รับค่าจาก input_tax_type

            if (inputTaxType === '0') {
                // ถ้าเป็นภาษีซื้อ ให้เปิดใช้งานสถานะและแสดงฟิลด์ที่เกี่ยวข้อง
                $('#input_tax_withholding_status1').prop('disabled', false);
                $('#input_tax_withholding_status2').prop('disabled', false);
                $('#withholding-show').show();
                $('#tax-input').show();
                
                // ตรวจสอบสถานะการออกใบหัก ณ ที่จ่าย
                var withholdingStatus = $('input[name="input_tax_withholding_status"]:checked').val();
                if (withholdingStatus === 'Y') {
                    $('#date-doc-show').show();
                    $('#tax-date-show').show();
                } else {
                    $('#date-doc-show').hide();
                    $('#tax-date-show').hide();
                }

            } else {
                // ถ้าไม่ใช่ภาษีซื้อ ให้ปิดใช้งานและซ่อนฟิลด์ทั้งหมด
                $('#input_tax_withholding_status1').prop('disabled', true);
                $('#input_tax_withholding_status2').prop('disabled', true);
                $('#date-doc-show').hide();
                $('#tax-date-show').show(); // แสดงวันที่ภาษีสำหรับประเภทอื่น
                $('#withholding-show').hide();
                $('#tax-input').hide();
            }
        });

        // เรียกใช้งานทันทีเพื่อให้รองรับค่าเริ่มต้น
        $('#input_tax_type').trigger('change');

        // เพิ่มการควบคุมการแสดง/ซ่อนฟิลด์ตามสถานะการออกใบหัก ณ ที่จ่าย
        $('input[name="input_tax_withholding_status"]').change(function() {
            var withholdingStatus = $(this).val();
            
            if (withholdingStatus === 'N') {
                // ถ้าเลือก "ไม่ใช่" ให้ซ่อนฟิลด์
                $('#date-doc-show').hide();
                $('#tax-date-show').hide();
            } else {
                // ถ้าเลือก "ใช่" ให้แสดงฟิลด์ (เฉพาะเมื่อประเภทเป็นภาษีซื้อ)
                var inputTaxType = $('#input_tax_type').val();
                if (inputTaxType === '0') {
                    $('#date-doc-show').show();
                    $('#tax-date-show').show();
                }
            }
        });
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

            if (inputTaxType === '1' || inputTaxType === '3') {
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
                $('#total').val(total.toFixed(2)); // แก้ไข: ใส่ total แทน withholdingTotal
            }
        });

    });
</script>