@php($i = 1)
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ลำดับ</th>
            <th>Payment No.</th>
            <th>วันที่ทำรายการ</th>
            <th>วันที่ชำระ</th>
            <th>จำนวนเงิน</th>
            <th>ยอดคืน</th>
            <th>สถานะกาคืน</th>
            <th>โฮลเซลล์</th>
            <th>Quotation No.</th>
            <th>ประเภทการ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paymentWholesale as $item)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $item->payment_wholesale_number }}</td>
                <td>{{ date('d/m/Y : H:i:s', strtotime($item->created_at)) }}</td>
                <td>{{ $item->payment_wholesale_date ? date('d/m/Y : H:i:s', strtotime($item->payment_wholesale_date)) : 'N/A' }}</td>
                <td>{{ number_format($item->payment_wholesale_total, 2, '.', ',') }}</td>
                <td>{{ $item->payment_wholesale_refund_type !== null ? number_format($item->payment_wholesale_refund_total, 2) : '-' }}</td>
                <td>{{ $item->payment_wholesale_refund_status }}</td>
                <td>{{ $item->quote?->quoteWholesale->wholesale_name_th }}</td>
                <td>{{ $item->quote?->quote_number }}</td>
                <td>{{ $item->payment_wholesale_type === 'full' ? 'ชำระเงินเต็มจำนวน' : 'ชำระมัดจำ' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
