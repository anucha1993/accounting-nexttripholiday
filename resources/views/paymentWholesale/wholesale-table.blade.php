<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-info">
            <h5 class="mb-0 text-white"><i class="fas fa-dollar-sign"></i>
                รายการชำระเงินโฮลเซลล์ / Payment Wholesale </h5>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr class="custom-row-height" style="line-height: -500px;">
                            <th>ลำดับ</th>
                            <th>ประเภท</th>
                            <th>Payment No.</th>
                            <th>วันที่ชำระเงิน</th>
                            <th>จำนวนเงิน</th>
                            <th>ไฟล์แนบ</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($paymentWholesale as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $item->payment_wholesale_doc_type === 'quote' ? 'Quotation' : '' }}
                                    {{ $item->payment_wholesale_doc_type === 'debit-note' ? 'Debit Note' : '' }}
                                    {{ $item->payment_wholesale_doc_type === 'credit-note' ? 'Credit Note' : '' }}
                                </td>
                                <td>{{ $item->payment_wholesale_number }}</td>
                                <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                <td>{{ number_format($item->payment_wholesale_total, 2, '.', ',') }}</td>
                                <td><a onclick="openPdfPopup(this.href); return false;"
                                        href="{{ asset($item->payment_wholesale_file_path) }}">{{ $item->payment_wholesale_file_name }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('paymentWholesale.delete', $item->payment_wholesale_id) }}"
                                        onclick="return confirm('ยืนยันการลบ');" class="text-danger"><i
                                            class="fa fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>