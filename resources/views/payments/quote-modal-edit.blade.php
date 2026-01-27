<div class="card">
    <div class="card-header">
        <h4>แก้ไขการแจ้งชำระเงิน</h4>
    </div>
    <div class="card-body">
        <form action="{{route('payment.update',$paymentModel->payment_id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="payment_doc_number" value="{{$quotationModel->quote_number}}">
            <input type="hidden" name="payment_doc_type" value="quote">
            <div class="row">
                <div class="col-md-3 mt-3">
                    <label for="">จำนวนเงินที่จะชำระ</label>
                    <input type="number" value="{{$paymentModel->payment_total}}" name="payment_total" class="form-control bg-success" step="0.01" >
                    <input type="hidden" value="{{$paymentModel->payment_total}}" name="payment_total_old" class="form-control bg-success" step="0.01" >
                </div>

                <div class="col-md-3 mt-3">
                    <label>การชำระเงิน </label>
                    <select name="payment_type" id="payment-type" class="form-select">
                        <option @if($paymentModel->payment_type === 'deposit') selected @endif value="deposit">ชำระเงินมัดจำ</option>
                        <option @if($paymentModel->payment_type === 'full') selected @endif value="full">ชำระเงินเต็มจำนวน</option>
                        <option @if($paymentModel->payment_type === 'refund') selected @endif value="refund">คืนเงิน</option>
                    </select>
                </div>

                <div class="col-md-3 mt-3">
                    <label>วิธีการชำระเงิน </label>
                    <select id="payment-method" class="form-select" name="payment_method">
                        <option value="">--กรุณาเลือก--</option>
                        <option @if($paymentModel->payment_method === 'cash') selected @endif  value="cash">เงินสด</option>
                        <option  @if($paymentModel->payment_method === 'transfer-money') selected @endif value="transfer-money">โอนเงินเข้าบัญชี</option>
                        <option  @if($paymentModel->payment_method === 'check') selected @endif value="check">เช็คธนาคาร</option>
                        <option  @if($paymentModel->payment_method === 'credit') selected @endif value="credit">บัตรเครดิต</option>
                    </select>
                </div> 

              
            </div>
            {{-- โอนเงินเข้าบัญชี  transfer-money --}}
            <div class="row mt-3" id="transfer-money" style="display: none">
                <div class="col-md-3">
                    <label>ธนาคาร</label>
                    <select name="payment_bank_number" id="bank-number" class="form-select">
                        <option value="">--กรุณาเลือก--</option>

                        @forelse ($bankCompany as $item)
                        <option @if($paymentModel->payment_bank_number == $item->bank_company_id) selected @endif value="{{$item->bank_company_id}}">{{$item->bank_company_name}}</option>
                        @empty
                            
                        @endforelse
                       
                    </select>
                </div>

                <div class="col-md-3" id="payment-account">
                    <label>เลขบัญชีลูกค้า</label>
                    <input type="text" class="form-control" name="payment_bank_customer_number" placeholder="เลขบัญชีลูกค้า" value="{{$paymentModel->payment_bank_customer_number}}">
                </div>

                <div class="col-md-3" style="display: none" id="payment-refund-note">
                    <label>ระบุเหตุผล : </label>
                   <textarea name="payment_cancel_note" class="form-control" cols="30" rows="2" placeholder="ระบุเหตุผล">{{$paymentModel->payment_cancel_note}}</textarea>
                </div>
            </div>

            {{-- เช็คธนาคาร check --}}
            <div class="row mt-3" id="check" style="display: none">
               <div class="col-md-3">
                <label for="">ธนาคาร</label>
                <select name="payment_bank" id="bank" class="form-select">
                    <option value="">--กรุณาเลือก--</option>
                        @forelse ($bank as $item)
                        <option @if($paymentModel->payment_bank == $item->bank_id) selected @endif value="{{$item->bank_id}}">{{$item->bank_name}}</option>
                        @empty
                            
                        @endforelse
                    
                </select>
               </div>
               <div class="col-md-3">
                <label for="">เลขที่เช็ค</label>
                <input type="text" class="form-control" name="payment_check_number" value="{{$paymentModel->payment_check_number}}">
               </div>
               <div class="col-md-3">
                <label for="">ลงวันที่</label>
                <input type="date" class="form-control" name="payment_check_date" value="{{$paymentModel->payment_check_date}}">
               </div>
            </div>

            {{-- บัตรเครดิต credit --}}
            <div class="row mt-3" id="credit" style="display: none">
                
                <div class="col-md-3">
                    <label for="">เลขที่สลิป</label>
                    <input type="text" class="form-control" name="payment_credit_slip_number" value="{{$paymentModel->payment_credit_slip_number}}">
                </div>
                <div class="col-md-3">
                    <label for="">ประเภทการรูดบัตร</label>
                    <div class="mt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_credit_type" id="credit-charge-customer" value="charge_customer" @if($paymentModel->payment_credit_type === 'charge_customer') checked @endif>
                            <label class="form-check-label" for="credit-charge-customer">ชาร์จลูกค้า</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_credit_type" id="credit-pro-swipe" value="pro_swipe" @if($paymentModel->payment_credit_type === 'pro_swipe' || !$paymentModel->payment_credit_type) checked @endif>
                            <label class="form-check-label" for="credit-pro-swipe">โปร รูดบัตร</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" id="credit-amount-wrapper">
                    <label for="">จำนวนเงินรูดบัตร <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="payment_credit_amount" id="payment_credit_amount" placeholder="จำนวนเงิน" step="0.01" value="{{$paymentModel->payment_credit_amount}}">
                </div>
            </div>
            <br>


            <label for="">แนบไฟล์เอกสาร</label></br>
            @if ($paymentModel->payment_file_path)
            <a href="{{ asset('storage/' . $paymentModel->payment_file_path) }}"  onclick="openPdfPopup(this.href); return false;"><i
                class="fa fa-file text-danger"></i> {{$paymentModel->payment_file_path}}</a>
            @else
                ไม่มีไฟล์แนบ
            @endif

             <div class="col-md-3 mt-3">
                    <label>วันที่ชำะเงิน</label>
                    <input type="datetime-local" name="payment_in_date" class="form-control" value="{{$paymentModel->payment_in_date}}" required>
                </div>
                
        
            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="">แนบไฟล์ใหม่</label></br>
                    <input type="file" name="payment_file">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success float-end"> อัพเดทข้อมูล</button>
                </div>
            </div>
            
        </form>
    </div>
</div>

<script>
// เรียกทันทีเมื่อโหลด modal (รองรับ AJAX load)
(function() {
    function payment() {
        var paymentMethod = $('#payment-method').val();
        if(paymentMethod === 'transfer-money') {
            $('#transfer-money').show();
            $('#check, #credit').hide();
            // ลบ required จากช่องจำนวนเงินรูดบัตร
            $('#payment_credit_amount').removeAttr('required');
        } else if(paymentMethod === 'check') {
            $('#check').show();
            $('#transfer-money, #credit').hide();
            // ลบ required จากช่องจำนวนเงินรูดบัตร
            $('#payment_credit_amount').removeAttr('required');
        } else if(paymentMethod === 'credit') {
            $('#credit').show();
            $('#transfer-money, #check').hide();
            // ตรวจสอบประเภทการรูดบัตร
            toggleCreditAmount();
        } else {
            $('#transfer-money, #check, #credit').hide(); // ซ่อนฟอร์มทั้งหมด
            // ลบ required จากช่องจำนวนเงินรูดบัตร
            $('#payment_credit_amount').removeAttr('required');
        }
    }

    // ฟังก์ชันตรวจสอบประเภทการรูดบัตร
    function toggleCreditAmount() {
        var creditType = $('input[name="payment_credit_type"]:checked').val();
        if(creditType === 'pro_swipe') {
            $('#credit-amount-wrapper').show();
            $('#payment_credit_amount').attr('required', true);
        } else {
            $('#credit-amount-wrapper').hide();
            $('#payment_credit_amount').removeAttr('required');
        }
    }

    // เรียกฟังก์ชันเมื่อเปลี่ยนประเภทการรูดบัตร
    $('input[name="payment_credit_type"]').on('change', function() {
        toggleCreditAmount();
    });

    // เรียกฟังก์ชันเมื่อเปลี่ยนวิธีการชำระเงิน
    $('#payment-method').on('change', function() {
        payment();
    });

    // ฟังก์ชันตรวจสอบประเภทการชำระเงิน
    function paymentAccount() {
        var paymentType = $('#payment-type').val();
        if(paymentType === 'refund') {
            $('#payment-account').show();
            $('#payment-refund-note').show();
        } else {
            $('#payment-account').hide();
            $('#payment-refund-note').hide();
        }
    }

    $('#payment-type').on('change', function() {
        paymentAccount();
    });

    // เรียกฟังก์ชันทันทีเมื่อโหลดหน้า
    payment();
    paymentAccount();
})();
</script>
