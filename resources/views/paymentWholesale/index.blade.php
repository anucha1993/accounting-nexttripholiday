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
                        <a href="{{ route('quotefile.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center" id="current-task-done">
                            <i data-feather="send" class="feather-sm me-2"></i>
                            ไฟล์เอกสาร
                            <span
                                class="todo-badge badge rounded-pill px-3 text-success font-weight-medium bg-light-success ms-auto"></span>
                        </a>
                    </li>

                    <li class="list-group-item p-0 border-0">
                        <a href="{{ route('paymentWholesale.index', $quotationModel->quote_id) }}"
                            class="todo-link list-group-item-action p-3 d-flex align-items-center active" id="current-task-done">
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

                {{-- @if (session('success'))
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
                @endif --}}

                <!-- Todo list-->
                <div class="todo-listing ">
                    <div class="container border bg-white">


                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Success - </strong>{{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Error - </strong>{{ session('error') }}
                            </div>
                        @endif



                        <h4 class="text-center my-4">ไฟล์เอกสาร Quotation No #{{ $quotationModel->quote_number }}</h4>

                        {{-- <button class="btn btn-danger float-end" data-bs-toggle="modal"
                            data-bs-target="#bs-example-modal-xlg">
                            <i class="fa fa-file"></i> แจ้งชำระเงินโฮลเซลล์
                        </button> --}}
                        <br>

                        <table class="table mt-3">
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
                                        <td><a target="_blank"
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




        <!-- sample modal content -->
        <div class="modal fade" id="bs-example-modal-xlg" tabindex="-1" aria-labelledby="bs-example-modal-lg"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            แจ้งชำระเงินโฮลเซลล์
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('paymentWholesale.store') }}" enctype="multipart/form-data" method="post"
                            id="form-upload">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="payment_wholesale_doc"
                                value="{{ $quotationModel->quote_number }}">
                            <input type="hidden" name="payment_wholesale_doc_type" value="quote">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>จำนวนเงินที่ชำระ</label>
                                    <input type="number" name="payment_wholesale_total" step="0.01"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label>ประเภทการชำระเงิน</label>
                                    <select name="payment_wholesale_type" class="form-select">
                                        <option value="deposit">ชำระเงินมัดจำ</option>
                                        <option value="full">ชำระเงินเต็มจำนวน</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>หลักฐานการชำระเงิน</label>
                                    <input type="file" name="file">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" form="form-upload" type="submit"> <i class="fa fa-save"></i>
                            บันทึก</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    @endsection
