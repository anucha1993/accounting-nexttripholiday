<div class="modal-body">
    <div class="header">
        <h5>ยกเลิกใบกำกับภาษี #{{ $taxinvoiceModel->taxinvoice_number }}</h5>
    </div>

    <form action="{{ route('taxinvoice.cancel', $taxinvoiceModel->taxinvoice_id) }} " method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12 mb-2">
                <label for="">ระบุเหตุผลที่ต้องการยกเลิกใบกับภาษี</label>
                <textarea name="taxinvoice_cancel_note" class="form-control" cols="30" rows="3" required>{{$taxinvoiceModel->taxinvoice_cancel_note}}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> ยืนยัน</button>
            </div>
        </div>
    </form>
</div>
