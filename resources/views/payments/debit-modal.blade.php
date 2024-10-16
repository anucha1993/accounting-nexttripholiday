<div class="card">
    <div class="card-header">
        <h4>แจ้งชำระเงิน Debit Note</h4>
    </div>
    <div class="card-body">
        <form action="{{route('payment.debit-payment',$debitModel->debit_id)}}" method="post" enctype="multipart/form-data">
            @csrf
            {{-- <input type="hidden" name="payment_doc_number" value="{{$debitModel->debit_note_number}}"> --}}
            <input type="hidden" name="payment_doc_type" value="debit-note">
            <div class="row">
                <div class="col-md-3 mt-3">
                    <label for="">จำนวนเงินที่จะชำระ</label>
                    <input type="number" value="{{ $debitModel->debit_grand_total -  $debitModel->payment }}" name="payment_total"
                        class="form-control bg-success" step="0.01" >
                </div>
                <div class="col-md-3 mt-3">
                    <label>การชำระเงิน </label>
                    <select name="payment_type" id="payment-type" class="form-select">
                        <option value="deposit">ชำระเงินมัดจำ</option>
                        <option value="full">ชำระเงินเต็มจำนวน</option>
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label>วิธีการชำระเงิน </label>
                    <select id="payment-method" class="form-select" name="payment_method">
                        <option value="cash">เงินสด</option>
                        <option value="transfer-money">โอนเงินเข้าบัญชี</option>
                        <option value="check">เช็คธนาคาร</option>
                        <option value="credit">บัตรเครดิต</option>
                    </select>
                    
                </div>
                <div class="col-md-3 mt-3">
                    <label>วันที่ชำะเงิน</label>
                    <input type="date" name="payment_in_date" class="form-control" value="{{date('Y-m-d')}}">
                </div>

            </div>
            {{-- โอนเงินเข้าบัญชี  transfer-money --}}
            <div class="row mt-3" id="transfer-money" style="display: none">
                <div class="col-md-3 mt-3">
                    <label>ธนาคาร</label>
                    <select name="payment_bank_number" id="bank-number" class="form-select">
                        <option value="">--กรุณาเลือก--</option>
                        @forelse ($bankCompany as $item)
                        <option value="{{$item->bank_company_id}}">{{$item->bank_company_name}}</option>
                        @empty
                            
                        @endforelse
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label for="">วันที่โอนเงิน</label>
                    <input type="datetime-local" name="payment_date_time" class="form-control">
                </div>
            </div>

            {{-- เช็คธนาคาร check --}}
            <div class="row mt-3" id="check" style="display: none">
               <div class="col-md-3">
                <label for="">ธนาคาร</label>
                <select name="payment_bank" id="bank" class="form-select">
                    <option value="">--กรุณาเลือก--</option>
                    @forelse ($bank as $item)
                        <option value="{{$item->bank_id}}">{{$item->bank_name}}</option>
                        @empty
                            
                        @endforelse
                </select>
               </div>
               <div class="col-md-3">
                <label for="">เลขที่เช็ค</label>
                <input type="text" class="form-control" name="payment_check_number">
               </div>
               <div class="col-md-3">
                <label for="">ลงวันที่</label>
                <input type="date" class="form-control" name="payment_check_date">
               </div>
            </div>
            {{-- บัตรเครดิต credit --}}
            <div class="row mt-3" id="credit" style="display: none">
                <div class="col-md-3">
                    <label for="">เลขที่สลิป</label>
                    <input type="text" class="form-control" name="payment_credit_slip_number">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="">แนบไฟล์เอกสาร</label>
                    <input type="file" name="payment_file">
                </div>
            </div>
            

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success float-end"> บันทึกข้อมูล</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
 $(document).ready(function (){
    $('#payment-method').on('change', function() {
        var paymentMethod = $(this).val();

        if(paymentMethod === 'transfer-money') {
            $('#transfer-money').show();
            $('#check, #credit').hide();
        } else if(paymentMethod === 'check') {
            $('#check').show();
            $('#transfer-money, #credit').hide();
        } else if(paymentMethod === 'credit') {
            $('#credit').show();
            $('#transfer-money, #check').hide();
        } else {
            $('#transfer-money, #check, #credit').hide(); // ซ่อนฟอร์มทั้งหมด
        }
    });
});



</script>
