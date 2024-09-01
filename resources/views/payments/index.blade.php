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
                        <a href="javascript:void(0)" class="todo-link list-group-item-action p-3 d-flex align-items-center"
                            id="current-task-important">
                            <i data-feather="star" class="feather-sm me-2"></i>
                            Important
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

                    <button class=" btn btn-primary btn-lg px-4 fs-4 font-weight-medium btn-sm" data-bs-toggle="modal" data-bs-target="#bs-example-modal-xlg">
                        แจ้งชำระเงิน
                    </button>

                        <table class="table table">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>เลขที่ชำระ</th>
                                    <th>รายละเอียดการชำระเงิน</th>
                                    <th>จำนวนเงิน</th>
                                    <th>ไฟล์แนบ</th>
                                    <th>ประเทภ</th>
                                    <th>ใบเสร็จรับเงิน</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>

                        </table>
                    </div>

                </div>


            </div>
        </div>

        {{-- Modal --}}
       
        {{-- <!-- sample modal content -->
        <div class="modal fade" id="bs-example-modal-xlg" tabindex="-1" aria-labelledby="bs-example-modal-lg"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            แจ้งชำระเงิน
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <form action="">
                        <div class="row">
                            <label>เลขที่เอกเอกสาร</label>
                            <select name="docs" id="" class="form-select"></select>
                        </div>
                       </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="
                                  btn btn-light-danger
                                  text-danger
                                  font-weight-medium
                                  waves-effect
                                  text-start
                                "
                            data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div> --}}
        <!-- /.modal -->
    @endsection
