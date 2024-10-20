
<div class="modal-body">
    <div class="header">
        <h5>ยกเลิกการชำระเงิน</h5>
    </div>
    <form action="{{route('payment.cancel',$paymentModel->payment_id )}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <label for="">ระบุเหตุผล <span class="text-danger"> *</span></label>
                <textarea name="payment_cancel_note" id="" cols="30" rows="3" class="form-control" required></textarea>
            </div>
        </div>
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