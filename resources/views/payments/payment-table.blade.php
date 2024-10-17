<div class="col-md-12">
    <div class="card">
        <div class="card-header bg-primary">
            <h5 class="mb-0 text-white"><i class="fas fa-dollar-sign"></i>
                รายการชำระเงิน / Payment information </h5>
        </div>
        <div class="card-body">
            <div class="table table-responsive">
                <table class="table product-overview">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ชำระ</th>
                            <th>วันที่ชำระ</th>
                            <th>รายละเอียดการชำระเงิน</th>
                            <th>จำนวนเงิน</th>
                            <th>ไฟล์แนบ</th>
                            <th>ประเภท</th>
                            <th>ใบเสร็จรับเงิน</th>
                            <th>Status</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="10">แจ้งชำระเงิน ใบเสนอราคา</td>
                        </tr>
                        @forelse ($payments as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    {{ $item->payment_number }}
                                </td>
                                <td>
                                    {{ date('d-m-Y', strtotime($item->payment_in_date)) }}
                                </td>
                                <td>

                                    @if ($item->payment_method === 'cash')
                                        วิธีการชำระเงิน : เงินสด </br>
                                    @endif
                                    @if ($item->payment_method === 'transfer-money')
                                        วิธีการชำระเงิน : โอนเงิน</br>
                                        วันที่ : {{ date('d-m-Y : H:m', strtotime($item->payment_date_time)) }}</br>
                                        เช็คธนาคาร : {{ $item->payment_bank }}
                                    @endif
                                    @if ($item->payment_method === 'check')
                                        วิธีการชำระเงิน : เช็ค</br>
                                        โอนเข้าบัญชี : {{ $item->payment_bank }} </br>
                                        เลขที่เช็ค : {{ $item->payment_check_number }} </br>
                                        วันที่ :
                                        {{ date('d-m-Y : H:m', strtotime($item->payment_check_date)) }}</br>
                                    @endif

                                    @if ($item->payment_method === 'credit')
                                        วิธีการชำระเงิน : บัตรเครดิต </br>
                                        เลขที่สลิป : {{ $item->payment_credit_slip_number }} </br>
                                    @endif

                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        -
                                    @else
                                        {{ number_format($item->payment_total, 2, '.', ',') }}
                                    @endif

                                </td>
                                <td>
                                    @if ($item->payment_file_path)
                                        <a href="{{ asset('storage/' . $item->payment_file_path) }}"
                                            onclick="openPdfPopup(this.href); return false;"><i
                                                class="fa fa-file text-danger"></i></a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        -
                                    @else
                                        @if ($item->payment_type === 'deposit')
                                            ชำระมัดจำ
                                        @else
                                            ชำระเงินเต็มจำนวน
                                        @endif
                                    @endif


                                </td>
                                <td>
                                    <a href="{{ route('mpdf.payment', $item->payment_id) }}" onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-print text-danger"></i> พิมพ์</a>

                                            <a class="dropdown-item " 
                                            href=""><i
                                                class="fas fa-envelope text-info"></i>
                                            ส่งเมล</a>
                                </td>
                                <td>
                                    @if ($item->payment_status === 'success')
                                        <span class="badge rounded-pill bg-success">Success</span>
                                    @endif
                                    @if ($item->payment_status === 'cancel')
                                        <span class="badge rounded-pill bg-danger">Cancel</span>
                                    @endif
                                    @if ($item->payment_status === null)
                                        <span class="badge rounded-pill bg-warning">NULL</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->payment_status !== 'cancel')
                                        <a class="dropdown-item payment-modal"
                                            href="{{ route('payment.edit', $item->payment_id) }}"><i
                                                class="fa fa-edit text-info"></i>
                                            แก้ไข</a>

                                         

                                        <a class="dropdown-item text-danger py-1"
                                            onclick="return confirm('หากยกเลิกระบบจะ คืนจำนวนเงิน ที่ชำระ ไปยังยอดค้างชำระ')"
                                            href="{{ route('payment.cancel', $item->payment_id) }}"><i
                                                class="fas fa-minus-circle "></i> ยกเลิก</a>
                                    @else
                                        -
                                    @endif



                                </td>
                            </tr>

                        @empty

                        @endforelse

                        @if ($paymentDebit->isNotEmpty())
                            <tr>
                                <td colspan="10">แจ้งชำระเงิน ใบเพิ่มหนี้</td>
                            </tr>
                        @endif

                        @forelse ($paymentDebit as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    {{ $item->payment_number }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($item->payment_in_date)) }}</td>
                                <td>
                                    @if ($item->payment_method === 'cash')
                                        วิธีการชำระเงิน : เงินสด </br>
                                    @endif
                                    @if ($item->payment_method === 'transfer-money')
                                        วิธีการชำระเงิน : โอนเงิน</br>
                                        วันที่ :
                                        {{ date('d-m-Y : H:m', strtotime($item->payment_date_time)) }}</br>
                                        เช็คธนาคาร : {{ $item->payment_bank }}
                                    @endif
                                    @if ($item->payment_method === 'check')
                                        วิธีการชำระเงิน : เช็ค</br>
                                        โอนเข้าบัญชี : {{ $item->payment_bank }} </br>
                                        เลขที่เช็ค : {{ $item->payment_check_number }} </br>
                                        วันที่ :
                                        {{ date('d-m-Y : H:m', strtotime($item->payment_check_date)) }}</br>
                                    @endif

                                    @if ($item->payment_method === 'credit')
                                        วิธีการชำระเงิน : บัตรเครดิต </br>
                                        เลขที่สลิป : {{ $item->payment_credit_slip_number }} </br>
                                    @endif

                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        -
                                    @else
                                        {{ number_format($item->payment_total, 2, '.', ',') }}
                                    @endif

                                </td>
                                <td>
                                    @if ($item->payment_file_path)
                                        <a href="{{ asset('storage/' . $item->payment_file_path) }}"><i
                                                class="fa fa-file text-danger"></i></a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        -
                                    @else
                                        @if ($item->payment_type === 'deposit')
                                            ชำระมัดจำ
                                        @else
                                            ชำระเงินเต็มจำนวน
                                        @endif
                                    @endif


                                </td>
                                <td>
                                    <a href="{{ route('mpdf.paymentDebit', $item->payment_id) }}" onclick="openPdfPopup(this.href); return false;"><i
                                            class="fa fa-print"></i> พิมพ์</a>
                                </td>
                                <td>
                                    @if ($item->payment_status === 'success')
                                        <span class="badge rounded-pill bg-success">Success</span>
                                    @endif
                                    @if ($item->payment_status === 'cancel')
                                        <span class="badge rounded-pill bg-danger">Cancel</span>
                                    @endif
                                    @if ($item->payment_status === null)
                                        <span class="badge rounded-pill bg-warning">NULL</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->payment_status !== 'cancel')
                                        <a class="dropdown-item payment-modal"
                                            href="{{ route('payment.debit-edit', $item->payment_id) }}"><i
                                                class="fa fa-edit text-info"></i>
                                            แก้ไข</a>

                                        <a class="dropdown-item text-danger py-1"
                                            onclick="return confirm('หากยกเลิกระบบจะ คืนจำนวนเงิน ที่ชำระ ไปยังยอดค้างชำระ')"
                                            href="{{ route('payment.debit-cancel', $item->payment_id) }}"><i
                                                class="fas fa-minus-circle "></i> ยกเลิก</a>
                                    @else
                                        -
                                    @endif



                                </td>

                              
                            </tr>

                        @empty

                        @endforelse

                        @if ($paymentCredit->isNotEmpty())
                            <tr>
                                <td colspan="10">แจ้งชำระเงิน ใบลดหนี้</td>
                            </tr>
                        @endif

                        @forelse ($paymentCredit as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    {{ $item->payment_number }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($item->payment_in_date)) }}</td>
                                <td>
                                    @if ($item->payment_method === 'cash')
                                        วิธีการชำระเงิน : เงินสด </br>
                                    @endif
                                    @if ($item->payment_method === 'transfer-money')
                                        วิธีการชำระเงิน : โอนเงิน</br>
                                        วันที่ :
                                        {{ date('d-m-Y : H:m', strtotime($item->payment_date_time)) }}</br>
                                        เช็คธนาคาร : {{ $item->payment_bank }}
                                    @endif
                                    @if ($item->payment_method === 'check')
                                        วิธีการชำระเงิน : เช็ค</br>
                                        โอนเข้าบัญชี : {{ $item->payment_bank }} </br>
                                        เลขที่เช็ค : {{ $item->payment_check_number }} </br>
                                        วันที่ :
                                        {{ date('d-m-Y : H:m', strtotime($item->payment_check_date)) }}</br>
                                    @endif

                                    @if ($item->payment_method === 'credit')
                                        วิธีการชำระเงิน : บัตรเครดิต </br>
                                        เลขที่สลิป : {{ $item->payment_credit_slip_number }} </br>
                                    @endif

                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        -
                                    @else
                                        {{ number_format($item->payment_total, 2, '.', ',') }}
                                    @endif

                                </td>
                                <td>
                                    @if ($item->payment_file_path)
                                        <a href="{{ asset('storage/' . $item->payment_file_path) }}"><i
                                                class="fa fa-file text-danger"></i></a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>

                                    @if ($item->payment_status === 'cancel')
                                        -
                                    @else
                                        @if ($item->payment_type === 'deposit')
                                            ชำระมัดจำ
                                        @else
                                            ชำระเงินเต็มจำนวน
                                        @endif
                                    @endif


                                </td>
                                <td>
                                    <a href="#"></i> -</a>
                                </td>
                                <td>
                                    @if ($item->payment_status === 'success')
                                        <span class="badge rounded-pill bg-success">Success</span>
                                    @endif
                                    @if ($item->payment_status === 'cancel')
                                        <span class="badge rounded-pill bg-danger">Cancel</span>
                                    @endif
                                    @if ($item->payment_status === null)
                                        <span class="badge rounded-pill bg-warning">NULL</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->payment_status !== 'cancel')
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupVerticalDrop2" type="button"
                                                class="btn btn-sm btn-light-secondary text-secondary font-weight-medium dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                จัดการข้อมูล
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
                                                <a class="dropdown-item credit-modal"
                                                    href="{{ route('payment.credit-edit', $item->payment_id) }}"><i
                                                        class="fa fa-edit"></i>
                                                    แก้ไข</a>

                                                <a class="dropdown-item text-danger"
                                                    onclick="return confirm('หากยกเลิกระบบจะ คืนจำนวนเงิน ที่ชำระ ไปยังยอดค้างชำระ')"
                                                    href="{{ route('payment.credit-cancel', $item->payment_id) }}"><i
                                                        class="fas fa-minus-circle "></i> ยกเลิก</a>


                                            </div>
                                        </div>
                                    @else
                                    @endif



                                </td>
                            </tr>

                        @empty

                        @endforelse



                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>



{{-- payment-modal --}}
<div class="modal fade bd-example-modal-sm modal-xl" id="modal-payment-edit" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>

<script>
  $(document).ready( function() {
      // modal   payment-modal
      $(".payment-modal").click("click", function(e) {
        e.preventDefault();
        $("#modal-payment-edit")
            .modal("show")
            .addClass("modal-lg")
            .find(".modal-content")
            .load($(this).attr("href"));
    });
  })
</script>
