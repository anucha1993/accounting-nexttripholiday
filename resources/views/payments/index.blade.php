@extends('layouts.template')

@section('content')
    <div class="email-app todo-box-container">
        <!-- -------------------------------------------------------------- -->
        <!-- Left Part -->
        <!-- -------------------------------------------------------------- -->


        <div class="left-part list-of-tasks bg-white">
            <a class="ti-menu ti-close btn btn-success show-left-part d-block d-md-none" href="javascript:void(0)"></a>
            <div class="scrollable" style="height: 100%">
                <div class="p-3">

                </div>
                <div class="divider"></div>
                <ul class="list-group">
                    <li>
                        <small class="p-3 d-block text-uppercase text-dark font-weight-medium"> ข้อมูลการขาย</small>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('saleInfo.info', $quotationModel->quote_id) }}" id="invoice-dashboard"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center">
                            <i class="far fa-file-alt"></i>
                            &nbsp; รายละเอียดรวม
                            <span
                                class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
                        </a>

                    </li>

                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('saleInfo.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center btn-booking active">
                            <i class="far fa-file-alt"></i>
                            &nbsp; ข้อมูลการขาย
                            <span
                                class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
                        </a>

                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('payments', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center"
                            id="current-task-important">
                            <i data-feather="star" class="feather-sm me-2"></i>
                            แจ้งชำระเงิน
                            <span
                                class="todo-badge badge rounded-pill px-3 bg-light-danger ms-auto text-danger font-weight-medium"></span>
                        </a>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="javascript:void(0)" class="todo-link list-group-item-action p-3 d-flex align-items-center"
                            id="current-task-done">
                            <i data-feather="send" class="feather-sm me-2"></i>
                            Complete
                            <span
                                class="todo-badge badge rounded-pill px-3 text-success font-weight-medium bg-light-success ms-auto"></span>
                        </a>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <hr />
                    </li>


                    <li class="list-group-item p-0 border-0">
                        <a href="javascript:void(0)" class="list-group-item-action p-3 d-flex align-items-center"
                            id="current-todo-delete">
                            <i data-feather="trash-2" class="feather-sm me-2"></i>
                            Trash
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- Right Part -->
        <!-- -------------------------------------------------------------- -->


        <br>



        <div class="right-part mail-list overflow-auto">
            <div id="todo-list-container">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                        role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Success - </strong>{{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                        role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Error - </strong>{{ session('error') }}
                    </div>
                @endif

                <!-- Todo list-->
                <div class="todo-listing ">
                    <div class="container border bg-white">
                        <h4 class="text-center my-4">แจ้งชำระเงิน Payments</h4>



                        <table class="table table">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>เลขที่ชำระ</th>
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
                                    <td colspan="9">แจ้งชำระเงิน ใบเสนอราคา</td>
                                </tr>
                                @forelse ($payments as $key => $item)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item->payment_in_date)) }}</td>
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
                                            <a href="#"><i class="fa fa-print"></i> พิมพ์</a>
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
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        จัดการข้อมูล
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
                                                        <a class="dropdown-item payment-modal" href="{{route('payment.edit',$item->payment_id)}}"><i class="fa fa-edit"></i>
                                                            แก้ไข</a>

                                                        <a class="dropdown-item text-danger"
                                                            onclick="return confirm('หากยกเลิกระบบจะ คืนจำนวนเงิน ที่ชำระ ไปยังยอดค้างชำระ')"
                                                            href="{{ route('payment.cancel', $item->payment_id) }}"><i
                                                                class="fas fa-minus-circle "></i> ยกเลิก</a>


                                                    </div>
                                                </div>
                                            @else
                                                -
                                            @endif



                                        </td>
                                    </tr>
                                   
                                @empty
                                @endforelse

                                <tr>
                                    <td colspan="9">แจ้งชำระเงิน ใบเพิ่มหนี้</td>
                                </tr>


                                @forelse ($paymentDebit as $key => $item)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item->payment_in_date)) }}</td>
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
                                            <a href="#"><i class="fa fa-print"></i> พิมพ์</a>
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
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        จัดการข้อมูล
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
                                                        <a class="dropdown-item debit-modal" href="{{route('payment.debit-edit',$item->payment_id)}}"><i class="fa fa-edit"></i>
                                                            แก้ไข</a>

                                                        <a class="dropdown-item text-danger"
                                                            onclick="return confirm('หากยกเลิกระบบจะ คืนจำนวนเงิน ที่ชำระ ไปยังยอดค้างชำระ')"
                                                            href="{{ route('payment.debit-cancel', $item->payment_id) }}"><i
                                                                class="fas fa-minus-circle "></i> ยกเลิก</a>


                                                    </div>
                                                </div>
                                            @else
                                                -
                                            @endif



                                        </td>
                                    </tr>
                                   
                                @empty
                                @endforelse


                                <tr>
                                    <td colspan="9">แจ้งชำระเงิน ใบลดหนี้</td>
                                </tr>
                                @forelse ($paymentCredit as $key => $item)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ date('d-m-Y', strtotime($item->payment_in_date)) }}</td>
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
                                            <a href="#"><i class="fa fa-print"></i> พิมพ์</a>
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
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        จัดการข้อมูล
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
                                                        <a class="dropdown-item credit-modal" href="{{route('payment.credit-edit',$item->payment_id)}}"><i class="fa fa-edit"></i>
                                                            แก้ไข</a>

                                                        <a class="dropdown-item text-danger"
                                                            onclick="return confirm('หากยกเลิกระบบจะ คืนจำนวนเงิน ที่ชำระ ไปยังยอดค้างชำระ')"
                                                            href="{{ route('payment.credit-cancel', $item->payment_id) }}"><i
                                                                class="fas fa-minus-circle "></i> ยกเลิก</a>


                                                    </div>
                                                </div>
                                            @else
                                                -
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

           {{-- invoice payment Modal --}}
           <div class="modal fade bd-example-modal-sm modal-lg" id="payment-edit" tabindex="-1" role="dialog"
           aria-labelledby="mySmallModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-xl">
               <div class="modal-content">
                   ...
               </div>
           </div>
       </div>

        {{-- debit payment Modal --}}
        <div class="modal fade bd-example-modal-sm modal-lg" id="debit-edit" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    
        {{-- Credit payment Modal --}}
        <div class="modal fade bd-example-modal-sm modal-lg" id="credit-edit" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

       <script>
           $(document).ready(function() {

               // modal add user
               $(".payment-modal").click("click", function(e) {
                   e.preventDefault();
                   $("#payment-edit")
                       .modal("show")
                       .addClass("modal-lg")
                       .find(".modal-content")
                       .load($(this).attr("href"));
               });

                // modal debit
                $(".debit-modal").click("click", function(e) {
                   e.preventDefault();
                   $("#debit-edit")
                       .modal("show")
                       .addClass("modal-lg")
                       .find(".modal-content")
                       .load($(this).attr("href"));
               });
                  // modal Credit
                  $(".credit-modal").click("click", function(e) {
                   e.preventDefault();
                   $("#credit-edit")
                       .modal("show")
                       .addClass("modal-lg")
                       .find(".modal-content")
                       .load($(this).attr("href"));
               });
           });
       </script>


    @endsection
