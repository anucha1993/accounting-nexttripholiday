<div class="modal-body">
    <div class="header">
        <h5>ยกเลิกใบเสนอราคา #{{ $quotationModel->quote_number }}</h5>
    </div>

    <form action="{{ route('quote.cancel', $quotationModel->quote_id) }} " method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 mb-2">
                <label for="">ระบุเหตุผลที่ต้องการยกเลิกใบเสนอราคา</label>
                <textarea name="quote_cancel_note" class="form-control" cols="30" rows="3" required>{{$quotationModel->quote_cancel_note}}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> ยืนยัน</button>
            </div>
        </div>
    </form>
</div>
