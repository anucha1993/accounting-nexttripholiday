<div class="modal-body">
    <div class="header">
        <h5>แก้ไข แจ้งชำระเงินโฮลเซลล์ # {{$paymentWholesaleModel->payment_wholesale_number}}</h5>
    </div>
    <form action="{{ route('paymentWholesale.update',$paymentWholesaleModel->payment_wholesale_id) }}" enctype="multipart/form-data" method="post" id="whosalePayment">
        @csrf
        @method('PUT')
        {{-- <input type="hidden" name="payment_wholesale_doc" value="{{ $quotationModel->quote_number }}"> --}}
        <input type="hidden" name="payment_wholesale_doc_type" value="quote">
        <div class="row">
            <div class="col-md-3">
                <label>จำนวนเงินที่ชำระ</label>
                <input type="number" name="payment_wholesale_total" step="0.01" class="form-control" value="{{$paymentWholesaleModel->payment_wholesale_total}}" placeholder="0.00">
            </div>
            <div class="col-md-3">
                <label>ประเภทการชำระเงิน</label>
                <select name="payment_wholesale_type" class="form-select">
                    <option @if($paymentWholesaleModel->payment_wholesale_type === 'deposit') selected @endif value="deposit">ชำระเงินมัดจำ</option>
                    <option @if($paymentWholesaleModel->payment_wholesale_type === 'full') selected @endif value="full">ชำระเงินเต็มจำนวน</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>หลักฐานการชำระเงิน</label>
                <input type="file" name="file">
            </div>
        </div>
   
</div>
<div class="modal-footer">
    <button class="btn btn-success" form="whosalePayment" type="submit"> <i class="fa fa-save"></i>
        บันทึก</button>
</div>

</form>


