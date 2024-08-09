<style>
    .custom-row-height {
        height: 50px;
        /* กำหนดความสูงที่ต้องการ */
    }
</style>

<h4 class="text-info">ข้อมูลการขาย</h4>
<div class="card border">

    <div class="card-body" style="height: 600px">
        <a href="javascript:void(0)" data-id="{{$invoice->invoice_id}}" class="btn btn-primary btn-sm btn-addbebt">เพิ่มใบเพิ่มหนี้</a>
        <a href="javascript:void(0)" class="btn btn-danger btn-sm">เพิ่มใบลดหนี้</a>
        <br>
        <br>
        <table class="table"  >
            <thead>
                <tr class="bg-info text-white custom-row-height" style="line-height: -500px;">
                    <th>ประเภท</th>
                    <th>วันที่</th>
                    <th>Doc. Number</th>
                    <th>ชื่อลูกค้า</th>
                    <th>ยอดรวมสุทธิ</th>
                    <th>สถานะ </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody >
            

                @forelse ($invoices as $item)
                    <tr>
                        <td style="width: 100px">ใบแจ้งหนี้</td>
                        <td style="width: 10px">{{date('d/m/Y',strtotime($item->created_at))}}</td>
                        <td style="width: 30px">{{$item->invoice_number}}</td>
                        <td style="width: 1000px">{{$item->customer_name}}</td>
                        <td >{{ number_format($item->invoice_grand_total, 2, '.', ',');  }}</td>
                        <td >
                            @if ($item->invoice_status === 'wait')
                            <span class="badge bg-primary">รอชำระ</span>
                            @endif
                            @if ($item->invoice_status === 'success')
                            <span class="badge bg-success">ชำระแล้ว</span>
                            @endif
                            @if ($item->invoice_status === 'cancel')
                            <span class="badge bg-danger">ยกเลิก</span>
                            @endif
                        </td>
                        <td><a href="javascript:void(0)" data-id="{{$item->invoice_id}}" class="btn-invoice-edit btn btn-sm btn-info btn-booking">จัดการ</a></td>
                    </tr>
                @empty
                    No data invoice
                @endforelse

                {{-- ใบเพิ่มหนี้ --}}
                @forelse ($debts as $item)
                    <tr>
                        <td style="width: 100px">ใบเพิ่มหนี้</td>
                        <td style="width: 10px">{{date('d/m/Y',strtotime($item->created_at))}}</td>
                        <td style="width: 30px">{{$item->debt_number}}</td>
                        <td style="width: 1000px">{{$item->customer_name}}</td>
                        <td >{{ number_format($item->debt_grand_total, 2, '.', ',');  }}</td>
                        <td >
                            @if ($item->debt_status === 'wait')
                            <span class="badge bg-primary">รอชำระ</span>
                            @endif
                            @if ($item->debt_status === 'success')
                            <span class="badge bg-success">ชำระแล้ว</span>
                            @endif
                            @if ($item->debt_status === 'cancel')
                            <span class="badge bg-danger">ยกเลิก</span>
                            @endif
                        </td>
                        <td><a href="javascript:void(0)" data-id="{{$item->debt_id}}" class="btn-invoice-edit btn btn-sm btn-info btn-booking">จัดการ</a></td>
                    </tr>
                @empty
                    
                @endforelse

                
                
            </tbody>
        </table>
    </div>
</div>

<script>
      $(document).ready(function() {
            // table invoice edit
           $('.btn-invoice-edit').click("click", function (e) {
                 
                var invoiceID = $(this).attr('data-id');
                var cachedContent = ""; // ตัวแปรสำหรับเก็บเนื้อหา
               $.ajax({
                   url: '{{route("invoiceBooking.edit")}}',
                   type: 'GET',
                   data : {
                    invoiceID: invoiceID
                   },
                   success: function(response) {
                      $('#content').html(response)
                   }
               });
           });

           //add Debt
           $('.btn-addbebt').click("click", function (e) {
                 
                 var invoiceID = $(this).attr('data-id');
                 var cachedContent = ""; // ตัวแปรสำหรับเก็บเนื้อหา
                $.ajax({
                    url: '{{route("adddebt.create")}}',
                    type: 'GET',
                    data : {
                     invoiceID: invoiceID
                    },
                    success: function(response) {
                       $('#content').html(response)
                    }
                });
            });
           
        });





</script>
