
<div class="modal-body">
    <div class="header">
        <h5>คืนเงินลูกค้า</h5>
    </div>
    <form action="{{route('payment.cancel',$paymentModel->payment_id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <label>จำนวนเงินคืน <span id="totalNewText" class="text-success"></span></label>
                <input type="number" step="0.01" class="form-control" name="payment_refund_total" id="payment-refund" value="{{$paymentModel->payment_total}}"  placeholder="0.0">
                <input type="hidden" step="0.01" class="form-control"  id="payment-total" value="{{$paymentModel->payment_total}}"  placeholder="0.0">
                <input type="hidden" step="0.01" class="form-control" name="payment_total" id="payment-total-new" value="{{$paymentModel->payment_total}}"  >
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <label for="">ระบุเหตุผลเงิน <span class="text-danger"> *</span></label>
                <textarea name="payment_cancel_note" id="" cols="30" rows="3" class="form-control" required>{{$paymentModel->payment_cancel_note}}</textarea>
            </div>
        </div>
        <br>
        
        <br>
        <div class="row">
            <div class="col">
                <label for="">แนบหลักฐานการคืนเงิน</label>
                <input type="file" name="payment_cancel_file_path">
            </div>
        </div>

        <br>
        <button type="submit" class="btn btn-success">ยืนยัน</button>
    </form>
</div>

<script>
    $(document).ready(function () {
        function calculateTotal() {
            let paymentTotal = parseFloat($('#payment-total').val()) || 0; // ตรวจสอบค่าและใส่ 0 ถ้าไม่ใช่ตัวเลข
            let paymentRefund = parseFloat($('#payment-refund').val()) || 0;
            let totalNew = paymentTotal - paymentRefund;
            $('#payment-total-new').val(totalNew);
            $('#totalNewText').text('ยอดคงเหลือ : ' + totalNew.toFixed(2));
        }
        // เรียกใช้ฟังก์ชันทันทีเมื่อหน้าเว็บโหลด
        calculateTotal();
        // เรียกใช้ฟังก์ชันอีกครั้งเมื่อมีการเปลี่ยนแปลงในฟิลด์ #payment-refund
        $('#payment-refund').on('keyup', function() {
            calculateTotal();
        });
    });
</script>