<div class="card">
    <div class="card-header">
        <h4>แจ้งชำระเงิน/คืนเงิน</h4>
    </div>
    <div class="card-body">
        <form action="{{route('payment.payment')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="payment_quote_id" value="{{$quotationModel->quote_id}}">
            <input type="hidden" name="payment_doc_number" value="{{$quotationModel->quote_number}}">
            <input type="hidden" name="payment_doc_type" value="quote">
            <div class="row">
                <div class="col-md-3 mt-3">
                    <label for="">จำนวนเงินที่จะชำระ</label>
                    <input type="number" value="{{$totaPayment}}" name="payment_total"
                        class="form-control bg-success" step="0.01" >
                </div>
                <div class="col-md-3 mt-3">
                    <label>การชำระเงิน </label>
                    <select name="payment_type" id="payment-type" class="form-select">
                        <option {{$paymentType === 'deposit' ? 'selected' : '' }} value="deposit">ชำระเงินมัดจำ</option>
                        <option {{$paymentType === 'full' ? 'selected' : '' }} value="full">ชำระเงินเต็มจำนวน</option>
                        <option {{$paymentType === 'refund' ? 'selected' : '' }} value="refund">คืนเงิน</option>
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label>วิธีการชำระเงิน </label>
                    <select id="payment-method" class="form-select" name="payment_method" required>
                        <option value="">--กรุณาเลือก--</option>
                        <option value="cash">เงินสด</option>
                        <option value="transfer-money">โอนเงินเข้าบัญชี</option>
                        <option value="check">เช็คธนาคาร</option>
                        <option value="credit">บัตรเครดิต</option>
                    </select>
                    
                </div>
                <div class="col-md-3 mt-3" id="payment-in-date">
                    <label>วันที่ชำะเงิน/วันที่คืนเงิน</label>
                    <input type="datetime-local" name="payment_in_date" class="form-control" value="{{date('Y-m-d\TH:i')}}">
                </div>

            </div>
            {{-- โอนเงินเข้าบัญชี  transfer-money --}}
            <div class="row mt-3" id="transfer-money" style="display: none">
                <div class="col-md-3 ">
                    <label>ธนาคาร</label>
                    <select name="payment_bank_number" id="bank-number" class="form-select">
                        <option value="">--กรุณาเลือก--</option>
                        @forelse ($bankCompany as $item)
                        <option @if($item->bank_company_id === 4) selected  @endif  value="{{$item->bank_company_id}}">{{$item->bank_company_name}}</option>
                        @empty
                            
                        @endforelse
                    </select>
                </div>

                <div class="col-md-3" id="payment-account">
                    <label>เลขบัญชีลูกค้า</label>
                    <input type="text" class="form-control" name="payment_bank_customer_number" placeholder="เลขบัญชีลูกค้า">
                </div>

                <div class="col-md-3" style="display: none" id="payment-refund-note">
                    <label>ระบุเหตุผล : </label>
                   <textarea name="payment_cancel_note" class="form-control" cols="30" rows="2" placeholder="ระบุเหตุผล"></textarea>
                </div>

            
            </div>
            {{-- เช็คธนาคาร check --}}
            <div class="row mt-3" id="check" style="display: none">
               <div class="col-md-3">
                <label for="">ธนาคาร</label>
                <select name="payment_bank" id="bank" class="form-select">
                    <option value="">--กรุณาเลือก--</option>
                    @forelse ($bank as $item)
                    <option @if($item->bank_id === 4) selected  @endif value="{{$item->bank_id}}">{{$item->bank_name}}</option>
                    @empty
                    @endforelse
                </select>
               </div>
               <div class="col-md-3">
                <label for="">เลขที่เช็ค</label>
                <input type="text" class="form-control" name="payment_check_number" placeholder="เลขที่เช็ค">
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
                    <input type="text" class="form-control" name="payment_credit_slip_number" placeholder="เลขที่สลิป">
                </div>
            </div>

            <div class="row mt-3" id="payment-file">
                <div class="col-md-3">
                    <label for="">แนบไฟล์เอกสาร</label>
                    <input type="file" name="payment_file" >
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

$(document).ready(function () {
    paymentAccount()
  function paymentAccount() {
    var paymentType = $('#payment-type').val();
    if(paymentType === 'refund') {
        $('#payment-account').show();
        $('#payment-refund-note').show();
        $('#payment-in-date').hide();
        $('#payment-file').hide();
        // remove required attribute when hidden
        $('input[name="payment_in_date"]').removeAttr('required');
        $('input[name="payment_file"]').removeAttr('required');
    }else{
        $('#payment-account').hide();
        $('#payment-refund-note').hide();
        $('#payment-in-date').show();
        $('#payment-file').show();
        // add required attribute when shown
        $('input[name="payment_in_date"]').attr('required', true);
        $('input[name="payment_file"]').attr('required', true);
    }
  }

  $('#payment-type').on('change', function() {
    paymentAccount()
    });
});




</script>
