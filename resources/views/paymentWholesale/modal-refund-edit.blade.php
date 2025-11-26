<div class="modal-body">
    <div class="header">
        <h5>ยกเลิก คืนยอดเงินโฮลเซลล์ # {{$paymentWholesaleModel->payment_wholesale_number}}</h5>
    </div>
    <form action="{{ route('paymentWholesale.updateRefund',$paymentWholesaleModel->payment_wholesale_id) }}" enctype="multipart/form-data" method="post" id="whosalePayment">
        @csrf
        @method('PUT')
        {{-- <input type="hidden" name="payment_wholesale_doc" value="{{ $quotationModel->quote_number }}"> --}}
        {{-- <input type="hidden" name="payment_wholesale_doc_type" value="quote"> --}}
        <div class="row">
            <div class="col-md-3">
                <label>ระบุยอดคืน</label>
                <input type="number" name="payment_wholesale_refund_total" step="0.01" class="form-control" value="{{$paymentWholesaleModel->payment_wholesale_refund_total}}" placeholder="0.00">
            </div>
            <div class="col-md-3">
                <label>ประเภทการชำระเงินคืน</label>
                <select name="payment_wholesale_refund_type" class="form-select">
                   <option @if($paymentWholesaleModel->payment_wholesale_refund_type === 'some') selected @endif  value="some">คืนยอดบางส่วน</option>
                   <option @if($paymentWholesaleModel->payment_wholesale_refund_type === 'full') selected @endif  value="full">คืนยอดเต็ม</option>
                </select>
            </div>
            <div class="col-md-3">
                @if ($paymentWholesaleModel->payment_wholesale_refund_file_name)
                <label>หลักฐานการคืนยอด</label>
                @endif

                {{-- File1 --}}
                <br>
                  <label for=""> สลิปคืนเงินครั้งที่ 1 : </label>
                @if ($paymentWholesaleModel->payment_wholesale_refund_file_name)
                
              
                <input type="file" name="file">
               
                <a onclick="openPdfPopup(this.href); return false;" href="{{ asset($paymentWholesaleModel->payment_wholesale_refund_file_path) }}">{{ $paymentWholesaleModel->payment_wholesale_refund_file_name }}</a>
                @elseif($paymentWholesaleModel->payment_wholesale_refund_total > 0)
                <input type="file" name="file">
                @endif
                <hr>

                 {{-- File1 --}}
                  <label for=""> สลิปคืนเงินครั้งที่ 2 : </label>
                 @if ($paymentWholesaleModel->payment_wholesale_refund_file_name1)
                
                 <input type="file" name="file1">
                 <a onclick="openPdfPopup(this.href); return false;" href="{{ asset($paymentWholesaleModel->payment_wholesale_refund_file_path1) }}">{{ $paymentWholesaleModel->payment_wholesale_refund_file_name1 }}</a>
                 @elseif($paymentWholesaleModel->payment_wholesale_refund_total > 0)
                 <input type="file" name="file1">
                 @endif
                   <hr>

                 {{-- File2 --}}
                  <label for=""> สลิปคืนเงินครั้งที่ 3 : </label>
                 @if ($paymentWholesaleModel->payment_wholesale_refund_file_name2)
                  <label for=""> สลิปคืนเงินครั้งที่  : </label>
                 <input type="file" name="file2">
                 <a onclick="openPdfPopup(this.href); return false;" href="{{ asset($paymentWholesaleModel->payment_wholesale_refund_file_path2) }}">{{ $paymentWholesaleModel->payment_wholesale_refund_file_name2 }}</a>
                 @elseif($paymentWholesaleModel->payment_wholesale_refund_total > 0)
                 <input type="file" name="file2">
                 @endif

            </div>
              <div class="col-md-6">
                <label>เหตุผลการคืนเงิน :</label>
                <textarea name="payment_wholesale_refund_note" class="form-control" cols="30" rows="2">{{$paymentWholesaleModel->payment_wholesale_refund_note}}</textarea>
              </div>

        </div>
   
</div>
<div class="modal-footer">
    <button class="btn btn-success" form="whosalePayment" type="submit"> <i class="fa fa-save"></i>
        บันทึก</button>
</div>

</form>


