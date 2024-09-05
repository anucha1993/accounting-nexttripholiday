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
                        <a href="{{route('payments',$quotationModel->quote_id)}}" class="todo-link list-group-item-action p-3 d-flex align-items-center"
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
                        <h4 class="text-center my-4">ข้อมูลการขาย Quotation No #{{ $quotationModel->quote_number }}</h4>


                        <table class="table">
                            <thead>
                                <tr class="bg-info text-white custom-row-height" style="line-height: -500px;">
                                    <th>ประเภท</th>
                                    <th>วันที่</th>
                                    <th>Doc. Number</th>
                                    <th style="width: 400px">ชื่อลูกค้า</th>
                                    <th>ยอดรวมสุทธิ</th>
                                    <th>ยอดชำระ</th>
                                    <th>ยอดคงค้าง</th>
                                    <th>สถานะ </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-primary">ใบเสนอราคา</td>
                                    <td>{{ date('d/m/Y', strtotime($quotationModel->created_at)) }}</td>
                                    <td><span class="badge bg-dark">{{ $quotationModel->quote_number }}</span></td>
                                    <td>คุณ{{ $quotationModel->customer_name }}</td>
                                    <td>{{ number_format($quotationModel->quote_grand_total ? $quotationModel->quote_grand_total : $quotationModel->quote_total, 2, '.', ',') }}
                                    </td>
                                    <td>{{number_format($quotationModel->payment ? $quotationModel->payment: 0, 2, '.', ',')}}</td>
                                    <td>{{number_format($quotationModel->quote_grand_total - $quotationModel->payment, 2, '.', ',')}}</td>
                                    <td>
                                        @if ($quotationModel->quote_status === 'wait')
                                            <span class="badge rounded-pill bg-primary">รอชำระเงิน</span>
                                        @endif
                                        @if ($quotationModel->quote_status === 'success')
                                            <span class="badge rounded-pill bg-success">ชำระเงินครบจำนวนแล้ว</span>
                                        @endif
                                        @if ($quotationModel->quote_status === 'cancel')
                                            <span class="badge rounded-pill bg-danger">ยกเลิก</span>
                                        @endif
                                        @if ($quotationModel->quote_status === 'payment')
                                            <span class="badge rounded-pill bg-warning">ชำระมัดจำแล้ว</span>
                                        @endif
                                        @if ($quotationModel->quote_status === 'invoice')
                                        <span class="badge rounded-pill bg-info">ออกใบแจ้งหนี้แล้ว</span>

                                        @if ($quotationModel->payment >=  $quotationModel->quote_grand_total)
                                          <span class="badge rounded-pill bg-success">ชำระเงินครบจำนวนแล้ว</span>
                                        @else

                                        @if ($quotationModel->payment > 0 )
                                        <span class="badge rounded-pill bg-warning">ชำระมัดจำแล้ว</span>
                                        @endif
                                            
                                        @endif

                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupVerticalDrop2" type="button"
                                                class="btn btn-sm btn-light-secondary text-secondary font-weight-medium dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                จัดการข้อมูล
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">

                                                <a class="dropdown-item" href="#"><i class="fa fa-print"></i>
                                                    พิมพ์ใบเสนอราคา</a>
                                                    <a class="dropdown-item invoice-modal"
                                                    href="{{ route('payment.quotation', $quotationModel->quote_id) }}"><i
                                                        class="fas fa-credit-card"></i> แจ้งชำระเงิน</a>
                                                @if ($quotationModel->quote_status === 'wait' && $quotationModel->quote_status != 'cancel' )
                                                    <a class="dropdown-item"
                                                        href="{{ route('quote.edit', $quotationModel->quote_id) }}"><i
                                                            class="fa fa-edit"></i> แก้ไข</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('invoice.create', $quotationModel->quote_id) }}"><i
                                                            class="fas fa-file-alt"></i> ออกใบแจ้งหนี้</a>
                                                   
                                                    <a class="dropdown-item" href="{{route('quote.cancel',$quotationModel->quote_id)}}" onclick="return confirm('ยืนยันการยกเลิกใบเสนอราคา')"><i
                                                            class="fas fa-minus-circle" ></i> ยกเลิกใบงาน</a>
                                                @endif



                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                @forelse ($invoices as $item)
                                    <tr>
                                        <td class="text-success">ใบแจ้งหนี้</td>
                                        <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                        <td><span class="badge bg-dark">{{ $item->invoice_number }}</span></td>
                                        <td>คุณ{{ $item->customer_name }}</td>
                                        <td>{{ number_format($item->invoice_grand_total, 2, '.', ',') }}</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>
                                            @if ($item->invoice_status === 'wait')
                                                <span class="badge rounded-pill bg-primary">กำลังดำเนินการ</span>
                                            @endif

                                            @if ($item->invoice_status === 'success')
                                                <span class="badge rounded-pill bg-success">ออกใบกำกับภาษีแล้ว</span>
                                            @endif
                                            @if ($item->invoice_status === 'cancel')
                                                <span class="badge rounded-pill bg-danger">ยกเลิก</span>
                                            @endif
                                           
                                        </td>
                                       

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupVerticalDrop2" type="button"
                                                    class="btn btn-sm btn-light-secondary text-secondary font-weight-medium dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    จัดการข้อมูล
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">

                                                    <a class="dropdown-item" href="#"><i class="fa fa-print"></i>
                                                        พิมพ์ใบแจ้งหนี้</a>


                                                    @if ($item->invoice_status === 'wait')
                                                        <a class="dropdown-item"
                                                            href="{{ route('invoice.edit', $item->invoice_id) }}"><i
                                                                class="fa fa-edit"></i> แก้ไข</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('invoice.taxinvoice', $item->invoice_id) }}"
                                                            onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                                                class="fas fa-plus"></i> สร้างใบกำกับภาษี</a>
                                                        <a class="dropdown-item"  href="{{route('invoice.cancel',$invoice->invoice_id)}}"  onclick="return confirm('ยืนยันการยกเลิกใบแจ้งหนี้')"><i
                                                                class="fas fa-minus-circle"></i> ยกเลิกใบงาน</a>
                                                    @endif

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse


                                @forelse ($taxinvoices as $item)
                                    <tr>
                                        <td class="text-secondary">ใบกำกับภาษี</td>
                                        <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                        <td><span class="badge bg-dark">{{ $item->taxinvoice_number }}</span></td>
                                        <td>คุณ{{ $item->customer_name }}</td>
                                        <td>{{ number_format($item->invoice_grand_total, 2, '.', ',') }}</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>
                                            <span class="badge rounded-pill bg-success">ออกใบกำกับภาษีแล้ว</span>
                                        </td>
                                        
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupVerticalDrop2" type="button"
                                                    class="btn btn-sm btn-light-secondary text-secondary font-weight-medium dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    จัดการข้อมูล
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
                                                    <a class="dropdown-item"
                                                        href="{{ route('taxinvoice.edit', $item->invoice_id) }}"><i
                                                            class="fa fa-edit"></i> แก้ไข</a>
                                                    <a class="dropdown-item" href="#"><i class="fa fa-print"></i>
                                                        พิมพ์ใบเสนอราคา</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('debit.create', $item->invoice_id) }}"><i
                                                            class="fa fa-file"></i> ออกใบเพิ่มหนี้</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('credit.create', $item->invoice_id) }}"><i
                                                            class="fa fa-file"></i> ออกใบลดหนี้</a>


                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse

                                {{-- debit note ใบเพิ่มหนี้ --}}

                                @forelse ($debitnote as $item)
                                    <tr>
                                        <td class="text-info">ใบเพิ่มหนี้</td>
                                        <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                        <td><span class="badge bg-info">{{ $item->debit_note_number }}</span></td>
                                        <td>คุณ{{ $item->customer_name }}</td>
                                        <td>{{ number_format($item->grand_total, 2, '.', ',') }}</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>
                                            @if ($item->debit_note_status === 'wait')
                                                <span class="badge rounded-pill bg-primary">รอชำระเงิน</span>
                                            @endif
                                            @if ($item->debit_note_status === 'success')
                                                <span class="badge rounded-pill bg-success">ชำระเงินครบจำนวนแล้ว</span>
                                            @endif
                                            @if ($item->debit_note_status === 'cancel')
                                                <span class="badge rounded-pill bg-danger">ยกเลิก</span>
                                            @endif
                                            @if ($item->debit_note_status === 'payment')
                                                <span class="badge rounded-pill bg-warning">ชำระมัดจำแล้ว</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupVerticalDrop2" type="button"
                                                    class="btn btn-sm btn-light-secondary text-secondary font-weight-medium dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    จัดการข้อมูล
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
                                                    <a class="dropdown-item debit-modal" href="{{ route('payment.debit', $item->debit_note_id) }}"><i
                                                        class="fas fa-credit-card"></i> แจ้งชำระเงิน</a>

                                                    <a class="dropdown-item"
                                                        href="{{ route('debit.edit', $item->debit_note_id) }}"><i
                                                            class="fa fa-edit"></i> แก้ไข</a>
                                                    <a class="dropdown-item" href="#"><i class="fa fa-print"></i>
                                                        พิมพ์ใบเสนอราคา</a>


                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse

                                {{-- Credit note ใบลดหนี้ --}}

                                @forelse ($creditnote as $item)
                                    <tr>
                                        <td class="text-danger">ใบลดหนี้</td>
                                        <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                        <td><span class="badge bg-danger">{{ $item->credit_note_number }}</span></td>
                                        <td>คุณ{{ $item->customer_name }}</td>
                                        <td>{{ number_format($item->grand_total, 2, '.', ',') }}</td>
                                        <td>N/A</td>
                                        <td>N/A</td>
                                        <td>
                                            @if ($item->credit_note_status === 'wait')
                                                <span class="badge rounded-pill bg-primary">รอคืนเงิน</span>
                                            @endif
                                            @if ($item->credit_note_status === 'success')
                                                <span class="badge rounded-pill bg-success">ชำระเงินครบจำนวนแล้ว</span>
                                            @endif
                                            @if ($item->credit_note_status === 'cancel')
                                                <span class="badge rounded-pill bg-danger">ยกเลิก</span>
                                            @endif
                                            @if ($item->credit_note_status === 'payment')
                                                <span class="badge rounded-pill bg-warning">ชำระมัดจำแล้ว</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupVerticalDrop2" type="button"
                                                    class="btn btn-sm btn-light-secondary text-secondary font-weight-medium dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    จัดการข้อมูล
                                                </button>
                                           

                                                <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">

                                                    <a class="dropdown-item credit-modal" href="{{ route('payment.credit', $item->credit_note_id) }}"><i
                                                        class="fas fa-credit-card"></i> แจ้งชำระเงิน</a>

                                                    <a class="dropdown-item"    
                                                        href="{{ route('credit.edit', $item->credit_note_id) }}"><i
                                                            class="fa fa-edit"></i> แก้ไข</a>
                                                    <a class="dropdown-item" href="#"><i class="fa fa-print"></i>
                                                        พิมพ์ใบเสนอราคา</a>
                                                </div>

                                            </div>
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
        <div class="modal fade bd-example-modal-sm modal-lg" id="invoice-payment" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div>

          {{-- debit payment Modal --}}
          <div class="modal fade bd-example-modal-sm modal-lg" id="debit-payment" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
              <div class="modal-content">
                  ...
              </div>
          </div>
      </div>

        {{-- credit payment Modal --}}
        <div class="modal fade bd-example-modal-sm modal-lg" id="credit-payment" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

        <script>
            $(document).ready(function() {
                // modal add payment invoice
                $(".invoice-modal").click("click", function(e) {
                    e.preventDefault();
                    $("#invoice-payment")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });
                 // modal add payment debit
                 $(".debit-modal").click("click", function(e) {
                    e.preventDefault();
                    $("#debit-payment")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });
                 // modal add payment credit
                 $(".credit-modal").click("click", function(e) {
                    e.preventDefault();
                    $("#credit-payment")
                        .modal("show")
                        .addClass("modal-lg")
                        .find(".modal-content")
                        .load($(this).attr("href"));
                });
            });

        </script>
    @endsection
