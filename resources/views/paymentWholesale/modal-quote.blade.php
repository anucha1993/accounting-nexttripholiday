<div class="modal-body">
    <div class="header">
        <h5>แจ้งชำระเงินโฮลเซลล์</h5>
    </div>
    <form action="{{ route('paymentWholesale.store') }}" enctype="multipart/form-data" method="post" id="whosalePayment">
        @csrf
        @method('POST')
        <input type="hidden" name="payment_wholesale_quote_id" value="{{ $quotationModel->quote_id }}">
        <input type="hidden" name="payment_wholesale_doc" value="{{ $quotationModel->quote_number }}">
        <input type="hidden" name="payment_wholesale_doc_type" value="quote">
        <div class="row">
            <div class="col-md-3">
                <label>จำนวนเงินที่ชำระ</label>
                <input type="number" name="payment_wholesale_total" step="0.01" class="form-control" placeholder="0.00">
            </div>
           
            <div class="col-md-3">
                <label>ประเภทการชำระเงิน</label>
                <select name="payment_wholesale_type" class="form-select">
                    <option value="deposit">ชำระเงินมัดจำ</option>
                    <option value="full">ชำระเงินเต็มจำนวน</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="">วันที่ชำระ</label>
                <input type="datetime-local" name="payment_wholesale_date" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>หลักฐานการชำระเงิน</label>
                <input type="file" name="file" required>
            </div>
        </div>
   
</div>
<div class="modal-footer">
    <button class="btn btn-success" form="whosalePayment" type="submit"> <i class="fa fa-save"></i>
        บันทึก</button>
</div>

</form>
