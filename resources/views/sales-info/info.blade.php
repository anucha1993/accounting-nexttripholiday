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
                        <a href="javascript:void(0)" id="invoice-dashboard"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center active">
                            <i class="far fa-file-alt"></i>
                            &nbsp; รายละเอียดรวม
                            <span
                                class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
                        </a>

                    </li>

                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('saleInfo.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center btn-booking ">
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
                        <a href="{{route('quotefile.index',$quotationModel->quote_id)}}" class="todo-link list-group-item-action p-3 d-flex align-items-center"
                            id="current-task-done">
                            <i data-feather="send" class="feather-sm me-2"></i>
                            ไฟล์เอกสาร
                            <span
                                class="todo-badge badge rounded-pill px-3 text-success font-weight-medium bg-light-success ms-auto"></span>
                        </a>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('paymentWholesale.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center" id="current-task-done">
                            <i data-feather="dollar-sign" class="feather-sm me-2"></i>
                            การชำระเงินโฮลเซลล์
                            <span
                                class="todo-badge badge rounded-pill px-3 text-success font-weight-medium bg-light-success ms-auto"></span>
                        </a>
                    </li>

                    <li class="list-group-item p-0 border-0">
                        <hr />
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
               


                    <div class="col-md-12" style="width: 99%">
                        <h4 class="text-center my-4">รายละเอียดการขาย Quotation No #{{ $quotationModel->quote_number }}</h4>
                        <div class="row">
                            <div class="col-6">
                                <div class="card border">
                                    <div class="card-header bg-primary">
                                        <h4 class="mb-0 text-white">รายละเอียดลูกค้า (Customer)</h4>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <table>
                                                    <tr>
                                                        <td class="text-end"><b>ชื่อลูกค้า :</b> </td>
                                                        <td>{{ $customer->customer_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end"><b>ที่อยู่ :</b> </td>
                                                        <td>{{ $customer->customer_address }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end"><b>Tex ID :</b> </td>
                                                        <td>{{ $customer->customer_texid }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end"><b>เบอร์ติดต่อ :</b> </td>
                                                        <td>{{ $customer->customer_tel }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end"><b>email :</b> </td>
                                                        <td>{{ $customer->customer_email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end"><b>Fax :</b> </td>
                                                        <td>{{ $customer->customer_fax ?: '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="col-6" >
                              <div class="card border">
                                  <div class="card-header bg-info">
                                      <h4 class="mb-0 text-white">รายละเอียดใบจองทัวร์ (Booking Form)</h4>
                                  </div>
                                  <div class="card-body">
                                      <table class="">
                                          <tr>
                                              <td class="text-end"><b>เลขที่ใบแจ้งหนี้ :</b> </td>
                                              <td>{{ $invoice ? $invoice->invoice_number : '-'  }}</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>วันที่ออกใบแจ้งหนี้ :</b> </td>
                                              <td>{{ date('d/M/Y', strtotime($invoice ? $invoice->crated_at : "")) }}</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>เลขที่ใบจองทัวร์ :</b> </td>
                                              <td>{{ $quotationModel ? $quotationModel->quote_booking : '' }}</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>ผู้ขาย :</b> </td>
                                              <td>{{ $sale->name }}</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>เมลผู้ขาย :</b> </td>
                                              <td>{{ $sale->email }}</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>เลขที่ทัวร์ :</b> </td>
                                              <td>{{ $tour->code }}</td>
                                          </tr>
                                      </table>
              
                                  </div>
                              </div>
                          </div>

                          <div class="col-6">
                              <div class="card border">
                                  <div class="card-header bg-danger">
                                      <h4 class="mb-0 text-white">รายละเอียดแพคเกจที่ซื้อ/วันเดินทาง</h4>
                                  </div>
                                  <div class="card-body">
                                      <table>
                                          <tr>
                                              <td class="text-end"><b>ชื่อแพคเกจ:</b></td>
                                              <td>{{ $tour->name }}</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>สายการบิน:</b></td>
                                              <td>{{ $airline->travel_name }}</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>ช่วงเวลาเดินทาง:</b></td>
                                              <td>{{ date('d', strtotime($booking->start_date)) }}-{{ date('d-M-Y', strtotime($booking->end_date)) }}
                                                  <b>({{ $tour->num_day }})</b>
                                              </td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>ผู้เดินทาง (PAX):</b></td>
                                              <td>{{ $booking->total_qty }} ท่าน</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>โฮลเซลล์:</b></td>
                                              <td>{{ $wholesale->wholesale_name_th }}</td>
                                          </tr>
                                          <tr>
                                             <td class="text-end"><b>-</b></td>
                                             <td></td>
                                         </tr>
                                      </table>
                                  </div>
                              </div>
                          </div>
              


                          <div class="col-6">
                              <div class="card border">
                                  <div class="card-header bg-success">
                                      <h4 class="mb-0 text-white">ยอดรวมสุทธิและกำหนดชำระเงิน</h4>
                                  </div>
                                  <div class="card-body">
                                      <table>
                                          <tr>
                                              <td class="text-end"><b>ราคารวมสุทธิ Quotation :</b></td>
                                              <td style="border-bottom: 1px solid;" class="text-primary align-top"> <b  style="" class=" text-primary" id="TotalAllLabel"> </b> {{ number_format($quotationModel ? $quotationModel->quote_grand_total : $quotationModel->quote_total , 2, '.', ',') }} บาท  </td>
                                          </tr>

                                          <tr>
                                              <td class="text-end"><b>ราคารวมสุทธิ Debit Note :</b></td>
                                              <td class="text-success"> <b  class=" text-success" id=""></b>00.00 บาท</td>
                                          </tr>

                                          <tr>
                                              <td class="text-end"><b>ราคารวมสุทธิ Credit Note:</b></td>
                                              <td class="text-danger"> <b  class=" text-danger" id=""></b>00.00 บาท</td>
                                          </tr>
                                          <tr>
                                              <td class="text-end"><b>ชำระให้โฮลเซลล์ : </b></td>
                                              <td class="text-info"> <b  class=" text-info" id=""></b>00.00 บาท</td>
                                          </tr>
                                          <tr>
                                             <td class="text-end"><b>ยอดรวมทั้งหมด : </b></td>
                                             <td class="text-info"> <b  class=" text-info" id=""></b>00.00 บาท</td>
                                         </tr>
                                         <tr>
                                             <td class="text-end"><b>กำไรขั้นต้น : </b></td>
                                             <td class="text-info"> <b  class=" text-info" id=""></b>00.00 บาท</td>
                                         </tr>
                                         
                                      </table>
              
                                  </div>
                              </div>
                          </div>

                        </div>
                    </div>
            </div>


    @endsection
