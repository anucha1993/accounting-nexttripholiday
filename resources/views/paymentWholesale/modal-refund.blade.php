<div class="modal-body">
    <div class="header">
        <h5>ยกเลิก คืนยอดเงินโฮลเซลล์ # {{ $paymentWholesaleModel->payment_wholesale_number }}</h5>
    </div>
    <form action="{{ route('paymentWholesale.updateRefund', $paymentWholesaleModel->payment_wholesale_id) }}"
        enctype="multipart/form-data" method="post" id="whosalePayment">
        @csrf
        @method('PUT')

        {{-- <div class="row">
           <div class="col">
            <label>สาเหตุระบุ :</label>
            <textarea name="payment_wholesale_refund_note" class="form-control" cols="30" rows="3">{{$paymentWholesaleModel->payment_wholesale_refund_note}}</textarea>
           </div>
        </div> --}}

        <div class="row">
            <div class="col-md-3">
                <label>ระบุยอดคืน</label>
                <input type="number" name="payment_wholesale_refund_total" step="0.01" class="form-control" value="{{$paymentWholesaleModel->payment_wholesale_total}}" placeholder="0.00">
            </div>
            <div class="col-md-3">
                <label>ประเภทการชำระเงินคืน</label>
                <select name="payment_wholesale_refund_type" class="form-select">
                   <option value="some">คืนยอดบางส่วน</option>
                   <option value="full">คืนยอดเต็ม</option>
                </select>
            </div>
        </div>

</div>
<div class="modal-footer">
    <button class="btn btn-success" form="whosalePayment" type="submit"> <i class="fa fa-save"></i>
        บันทึก
    </button>
</div>

</form>
