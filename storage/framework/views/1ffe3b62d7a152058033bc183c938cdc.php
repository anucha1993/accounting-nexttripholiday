<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, ample admin admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, material design, material dashboard bootstrap 5 dashboard template" />
    <meta name="description" content="Admin Pro is powerful and clean admin dashboard template" />
    <meta name="robots" content="noindex,nofollow" />
    <title>Accounting Nexttripholiday </title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ampleadmin/" />
    <!-- Favicon icon -->

    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo e(URL::asset('template/assets/images/favicon.png')); ?>" />
    <!-- This page plugin CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    
    <link href="<?php echo e(URL::asset('template/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css')); ?>"
        rel="stylesheet" />
    <link rel="stylesheet" type="text/css"
        href="<?php echo e(URL::asset('template/assets/extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css')); ?>" />

    <link rel="stylesheet" type="text/css"
        href="<?php echo e(URL::asset('template/assets/libs/select2/dist/css/select2.min.css')); ?>" />
    <!-- Custom CSS -->
    <link href="<?php echo e(URL::asset('template/assets/libs/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('template/assets/extra-libs/prism/prism.css')); ?>" rel="stylesheet" />


    <link href="<?php echo e(URL::asset('template/assets/libs/sweetalert2/dist/sweetalert2.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('template/dist/css/style.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('css/notification.css')); ?>" rel="stylesheet" />
    <script src="<?php echo e(URL::asset('template/assets/libs/jquery/dist/jquery.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('template/assets/libs/ckeditor/ckeditor.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/libs/ckeditor/samples/js/sample.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- รวมไฟล์ jQuery และ jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>




    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css')); ?>">

    <!-- Bootstrap JS -->
    <script src="<?php echo e(URL::asset('bootstrap-select@1.14.0-beta3/dist/js/bootstrap.bundle.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('bootstrap-select@1.14.0-beta3/dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- Bootstrap Select JS -->
    <script src="<?php echo e(URL::asset('bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js')); ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('template/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('template/assets/libs/daterangepicker/daterangepicker.css')); ?>" />

    


   



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    <style>
        .dot-success {
            width: 10px;
            /* กำหนดความกว้าง */
            height: 10px;
            /* กำหนดความสูง */
            background-color: rgb(0, 203, 47);
            /* กำหนดสีพื้นหลัง */
            border-radius: 50%;
            /* ทำให้เป็นวงกลม */
            display: inline-block;
            /* เพื่อให้สามารถวางต่อกับข้อความหรือองค์ประกอบอื่นๆ ได้ */
            margin-right: 5px;
            /* กำหนดระยะห่างด้านขวา (ถ้าต้องการ) */
        }
        .dot-danger {
            width: 10px;
            /* กำหนดความกว้าง */
            height: 10px;
            /* กำหนดความสูง */
            background-color: rgb(214, 66, 32);
            /* กำหนดสีพื้นหลัง */
            border-radius: 50%;
            /* ทำให้เป็นวงกลม */
            display: inline-block;
            /* เพื่อให้สามารถวางต่อกับข้อความหรือองค์ประกอบอื่นๆ ได้ */
            margin-right: 5px;
            /* กำหนดระยะห่างด้านขวา (ถ้าต้องการ) */
        }
        .dot-warning {
            width: 10px;
            /* กำหนดความกว้าง */
            height: 10px;
            /* กำหนดความสูง */
            background-color: rgb(213, 192, 0);
            /* กำหนดสีพื้นหลัง */
            border-radius: 50%;
            /* ทำให้เป็นวงกลม */
            display: inline-block;
            /* เพื่อให้สามารถวางต่อกับข้อความหรือองค์ประกอบอื่นๆ ได้ */
            margin-right: 5px;
            /* กำหนดระยะห่างด้านขวา (ถ้าต้องการ) */
        }
    </style>
</head>

<body style="min-height:100vh;display:flex;flex-direction:column;">
    <!-- -------------------------------------------------------------- -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- -------------------------------------------------------------- -->
    <div class="preloader">
        <svg class="tea lds-ripple" width="37" height="48" viewbox="0 0 37 48" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z"
                stroke="#20222a" stroke-width="2"></path>
            <path
                d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34"
                stroke="#20222a" stroke-width="2"></path>
            <path id="teabag" fill="#20222a" fill-rule="evenodd" clip-rule="evenodd"
                d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z">
            </path>
            <path id="steamL" d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" stroke="#20222a"></path>
            <path id="steamR" d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13" stroke="#20222a"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- -------------------------------------------------------------- -->
    <div id="main-wrapper" style="flex:1 0 auto;">
        <!-- -------------------------------------------------------------- -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- -------------------------------------------------------------- -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header border-end">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo e(URL::asset('logo/Logo-docs.png')); ?>" alt="homepage" style="width: 150px; "
                                class="dark-logo" />
                            <!-- Light Logo icon -->
                            
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu fs-5"></i></a>
                        </li>
                        <?php
                            $user = Auth::user();
                            $group = getUserGroup();
                            if ($group === 'admin') {
                                $notifications = \App\Models\NotificationSA::with(['reads' => function($q) use ($user) {
                                    $q->where('user_id', $user->id);
                                }])->orderByDesc('created_at')->limit(20)->get();
                                $unreadCount = $notifications->filter(function($n) use ($user) {
                                    return $n->reads->isEmpty();
                                })->count();
                            } elseif ($group === 'sale') {
                                $notifications = \App\Models\NotificationSale::where('sale_id', $user->sale_id)->orderByDesc('created_at')->limit(20)->get();
                                $unreadCount = $notifications->where('is_read', false)->count();
                            } elseif ($group === 'accounting') {
                                $notifications = \App\Models\NotificationAcc::with(['reads' => function($q) use ($user) {
                                    $q->where('user_id', $user->id);
                                }])->orderByDesc('created_at')->limit(20)->get();
                                $unreadCount = $notifications->filter(function($n) use ($user) {
                                    return $n->reads->isEmpty();
                                })->count();
                            } else {
                                $notifications = collect();
                                $unreadCount = 0;
                            }
                        ?>
                        <?php echo $__env->make('components.notifications', ['unreadCount' => $unreadCount, 'notifications' => $notifications], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        </li>
                        
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav">
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item search-box">
                            <form class="app-search d-none d-lg-block">
                                <input type="text" class="form-control" placeholder="Search..." />
                                <a href="" class="active"><i class="fa fa-search"></i></a>
                            </form>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                
                                <span class="ms-2 font-weight-medium"><?php echo e(auth::user()->name); ?></span><span
                                    class="fas fa-angle-down ms-2"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end user-dd animated flipInY">
                                <div class="d-flex no-block align-items-center p-3 bg-info text-white mb-2">
                                    <div class="">
                                      
                                    </div>
                                    <div class="ms-2">
                                        <h4 class="mb-0 text-white">
                                           <?php echo e(auth::user()->name); ?>

                                        </h4>
                                        <p class="mb-0"><?php echo e(auth::user()->email); ?></p>
                                    </div>
                                </div>
                               
                                <div class="dropdown-divider"></div>
                               <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                <div class="dropdown-divider"></div>
                                
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
                       
                       
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" alt href="<?php echo e(route('products.index')); ?>"
                                aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span
                                    class="hide-menu">รายการสินค้า
                                </span></a>
                        </li>
                      
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="<?php echo e(route('booking.index')); ?>"
                                aria-expanded="false"><i class="mdi mdi-clipboard-text"></i><span
                                    class="hide-menu">ใบจองทัวร์</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="<?php echo e(route('quote.index')); ?>"
                                aria-expanded="false"><i class="mdi mdi-clipboard-text"></i><span
                                    class="hide-menu">ใบเสนอราคา</span></a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-clipboard-text"></i><span
                                    class="hide-menu">ระบบบัญชี</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                               
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="<?php echo e(route('withholding.index')); ?>" aria-expanded="false"><i
                                            class="mdi mdi-cube-send"></i><span class="hide-menu">ใบหัก ณ ที่จ่าย</span></a>
                                </li>

                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="<?php echo e(route('debit-note.index')); ?>" aria-expanded="false"><i
                                            class="mdi mdi-cube-send"></i><span class="hide-menu">ใบเพิ่มหนี้ Debit Note</span></a>
                                </li>


                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="<?php echo e(route('credit-note.index')); ?>" aria-expanded="false"><i
                                            class="mdi mdi-cube-send"></i><span class="hide-menu">ใบลดหนี้ Credit Note</span></a>
                                </li>
                               
                            </ul>
                        </li>
                        <li class="nav-small-cap">
                            <i class="mdi mdi-dots-horizontal"></i>
                            <span class="hide-menu">Tables</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-table"></i><span
                                    class="hide-menu">ข้อมูลทั่วไป</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                            
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['create-wholesale', 'edit-wholesale', 'delete-wholesale'])): ?>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                            href="<?php echo e(route('wholesale.index')); ?>" aria-expanded="false"><i
                                                class="mdi mdi-border-top"></i><span
                                                class="hide-menu">ข้อมูลโฮลเซลล์</span></a>
                                    </li>
                                <?php endif; ?>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="<?php echo e(route('airline.index')); ?>" aria-expanded="false"><i
                                            class="mdi mdi-border-style"></i><span class="hide-menu">ข้อมูลสายการบิน</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="table-footable.html" aria-expanded="false"><i
                                            class="mdi mdi-tab-unselected"></i><span class="hide-menu">Table
                                            Footable</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="table-bootstrap.html" aria-expanded="false"><i
                                            class="mdi mdi-border-horizontal"></i><span class="hide-menu">Table
                                            Bootstrap</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-small-cap">
                            <i class="mdi mdi-dots-horizontal"></i>
                            <span class="hide-menu">Appss</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link two-column has-arrow waves-effect waves-dark"
                                href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-apps"></i><span
                                    class="hide-menu">รายงาน </span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="<?php echo e(route('report.input-tax')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">รายงานภาษีซื้อ</span>
                                    </a>
                                    <a href="<?php echo e(route('report.receipt')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">รายงานใบเสร็จรับเงิน</span>
                                    </a>
                                    <a href="<?php echo e(route('report.invoice')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">รายงานใบแจ้งหนี้</span>
                                    </a>
                                    <a href="<?php echo e(route('report.taxinvoice')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">รายงานใบกำกับภาษี</span>
                                    </a>
                                    <a href="<?php echo e(route('report.saletax')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">รายงานภาษีขาย</span>
                                    </a>
                                    <a href="<?php echo e(route('report.sales')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">รายงานยอดขาย</span>
                                    </a>
                                     <a href="<?php echo e(route('report.payment-wholesale')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu"> รายงานใบเสร็จโฮลเซลล์</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        




                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span
                                    class="hide-menu">ระบบสมาชิก </span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="<?php echo e(route('users.index')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">
                                            สมาชิก</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="<?php echo e(route('roles.index')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-bulletin-board"></i>
                                        <span class="hide-menu">
                                            การกำหนดสิทธิ์</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="<?php echo e(route('commissions.index')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-calendar"></i>
                                        <span class="hide-menu">
                                            Commissions
                                        </span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark" href="<?php echo e(route('cus.index')); ?>"
                                aria-expanded="false"><i class="fas fa-users"></i><span
                                    class="hide-menu">ลูกค้า</span></a>
                        </li>
                     
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- -------------------------------------------------------------- -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Page wrapper  -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- -------------------------------------------------------------- -->
            <!-- Container fluid  -->
            <!-- -------------------------------------------------------------- -->



            <!-- -------------------------------------------------------------- -->
            <!-- Start Page Content -->
            <!-- -------------------------------------------------------------- -->
            <!-- basic table -->

            <?php echo $__env->yieldContent('content'); ?>


            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 50000">
                <div id="statusToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-white" id="toastMessage">
                            Status updated successfully!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>


            <!-- -------------------------------------------------------------- -->
            <!-- End PAge Content -->
            <!-- -------------------------------------------------------------- -->
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Container fluid  -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <footer class="footer py-3 bg-light text-center">
        <div class="container">
            <span class="text-muted">All Rights Reserved by Ample admin.</span>
        </div>
    </footer>
    <!-- -------------------------------------------------------------- -->
    <!-- End footer -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- customizer Panel -->
    <!-- -------------------------------------------------------------- -->
    
    <div class="chat-windows"></div>
    <!-- -------------------------------------------------------------- -->
    <!-- All Jquery -->
    <!-- -------------------------------------------------------------- -->

    <!-- Bootstrap tether Core JavaScript -->

    <script src="<?php echo e(URL::asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- apps -->
    <script src="<?php echo e(URL::asset('template/dist/js/app.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/dist/js/app.init.horizontal.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/dist/js/app-style-switcher.horizontal.js')); ?>"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo e(URL::asset('template/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/extra-libs/sparkline/sparkline.js')); ?>"></script>
    <!--Wave Effects -->
    <script src="<?php echo e(URL::asset('template/dist/js/waves.js')); ?>"></script>

    
    <!--Menu sidebar -->
    <script src="<?php echo e(URL::asset('template/dist/js/sidebarmenu.js')); ?>"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo e(URL::asset('template/dist/js/feather.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/dist/js/custom.min.js')); ?>"></script>
    <!--This page plugins -->
    <script src="<?php echo e(URL::asset('template/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js')); ?>"> </script>



<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
   
    <script src="<?php echo e(URL::asset('template/dist/js/pages/datatable/datatable-basic.init.js')); ?>"></script>
    
    <script src="<?php echo e(URL::asset('template/assets/libs/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/libs/select2/dist/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/dist/js/pages/forms/select2/select2.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/extra-libs/prism/prism.js')); ?>"></script>

    
    <script src="<?php echo e(URL::asset('template/assets/libs/moment/min/moment.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/libs/daterangepicker/daterangepicker.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('template/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')); ?>"></script>

    
   
    

</body>

</html>
<?php /**PATH C:\laragon\www\accounting-nexttripholiday\resources\views/layouts/template.blade.php ENDPATH**/ ?>