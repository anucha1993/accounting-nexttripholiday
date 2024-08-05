@extends('layouts.template')

@section('content')
    <div class="email-app todo-box-container">
        <!-- -------------------------------------------------------------- -->
        <!-- Left Part -->
        <!-- -------------------------------------------------------------- -->
        <div class="left-part list-of-tasks">
            <a class="ti-menu ti-close btn btn-success show-left-part d-block d-md-none" href="javascript:void(0)"></a>
            <div class="scrollable" style="height: 100%">
                <div class="p-3">

                </div>
                <div class="divider"></div>
                <ul class="list-group">
                    <li>
                        <small class="p-3 d-block text-uppercase text-dark font-weight-medium">ข้อมูลการขาย</small>
                    </li>
                    <li class="list-group-item p-0 border-0">
                        <a href="javascript:void(0)" data-id="{{ $invoiceModel->invoice_id }}"
                            class="todo-link active list-group-item-action p-3 d-flex align-items-center btn-booking"
                            id="invoice">
                            <i class="far fa-file-alt"></i>
                            &nbsp; ใบจองทัวร์
                            <span class="todo-badge badge bg-light-info text-info rounded-pill px-3 font-weight-medium ms-auto"></span>
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
        <div class="right-part mail-list bg-white overflow-auto">
            <div id="todo-list-container">
                <div class="p-3 border-bottom" >

                </div>
                <!-- Todo list-->
                <div class="todo-listing">
                    <div id="content" class="p-3" >

                    </div>

                </div>
            </div>
        </div>


    </div>

    <script>
        $(document).ready(function() {
            // table invoice index
           $('.btn-booking').click("click", function (e) {
                var invoiceID = $('#invoice').attr('data-id');
               $.ajax({
                   url: '{{route("invoiceBooking.index")}}',
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




@endsection
