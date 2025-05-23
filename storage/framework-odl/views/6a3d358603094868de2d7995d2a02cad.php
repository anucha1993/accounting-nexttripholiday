<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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

<body>

    

    
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
    <div id="main-wrapper">
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
                            <img src="<?php echo e(URL::asset('template/assets/images/logos/logo-icon.png')); ?>" alt="homepage"
                                class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="<?php echo e(URL::asset('template/assets/images/logos/logo-light-icon.png')); ?>"
                                alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text">
                            <!-- dark Logo text -->
                            <img src="<?php echo e(URL::asset('template/assets/images/logos/logo-text.png')); ?>" alt="homepage"
                                class="dark-logo" />
                            <!-- Light Logo text -->
                            <img src="<?php echo e(URL::asset('template/assets/images/logos/logo-light-text.png')); ?>"
                                class="light-logo" alt="homepage" />
                        </span>
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
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu fs-5"></i></a>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fs-5 mdi mdi-gmail"></i>
                                <div class="notify">
                                    <span class="heartbit"></span>
                                    <span class="point"></span>
                                </div>
                            </a>
                            <div class="dropdown-menu mailbox dropdown-menu-start dropdown-menu-animate-up"
                                aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="border-bottom rounded-top py-3 px-4">
                                            <div class="mb-0 font-weight-medium fs-4">
                                                You have 4 new messages
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center message-body position-relative"
                                            style="height: 230px">
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="user-img position-relative d-inline-block">
                                                    <img src="<?php echo e(URL::asset('template/assets/images/users/1.jpg')); ?>"
                                                        alt="user" class="rounded-circle w-100" />
                                                    <span class="profile-status rounded-circle online"></span>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Pavan kumar
                                                    </h5>
                                                    <span
                                                        class="fs-2 text-nowrap d-block time text-truncate fw-normal mt-1">Just
                                                        see the my
                                                        admin!</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:30
                                                        AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="user-img position-relative d-inline-block">
                                                    <img src="<?php echo e(URL::asset('template/assets/images/users/2.jpg')); ?>"
                                                        alt="user" class="rounded-circle w-100" />
                                                    <span class="profile-status rounded-circle busy"></span>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Sonu Nigam
                                                    </h5>
                                                    <span class="fs-2 text-nowrap d-block time text-truncate">I've sung
                                                        a song!
                                                        See you at</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:10
                                                        AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="user-img position-relative d-inline-block">
                                                    <img src="<?php echo e(URL::asset('template/assets/images/users/3.jpg')); ?>"
                                                        alt="user" class="rounded-circle w-100" />
                                                    <span class="profile-status rounded-circle away"></span>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Arijit Sinh
                                                    </h5>
                                                    <span class="fs-2 text-nowrap d-block time text-truncate">I am a
                                                        singer!</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:08
                                                        AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="user-img position-relative d-inline-block">
                                                    <img src="<?php echo e(URL::asset('template/assets/images/users/4.jpg')); ?>"
                                                        alt="user" class="rounded-circle w-100" />
                                                    <span class="profile-status rounded-circle offline"></span>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Pavan kumar
                                                    </h5>
                                                    <span class="fs-2 text-nowrap d-block time text-truncate">Just see
                                                        the my
                                                        admin!</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:02
                                                        AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link border-top text-center text-dark pt-3"
                                            href="javascript:void(0);">
                                            <b>See all e-Mails</b>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href=""
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-check-circle fs-5"></i>
                                <div class="notify">
                                    <span class="heartbit"></span>
                                    <span class="point"></span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-start mailbox dropdown-menu-animate-up">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="border-bottom rounded-top py-3 px-4">
                                            <div class="mb-0 font-weight-medium fs-4">
                                                Notifications
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center notifications position-relative"
                                            style="height: 230px">
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="btn btn-light-danger text-danger btn-circle">
                                                    <i data-feather="link" class="feather-sm fill-white"></i>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Luanch Admin
                                                    </h5>
                                                    <span
                                                        class="fs-2 text-nowrap d-block time text-truncate fw-normal text-muted mt-1">Just
                                                        see
                                                        the my new
                                                        admin!</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:30
                                                        AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="btn btn-light-success text-success btn-circle">
                                                    <i data-feather="calendar" class="feather-sm fill-white"></i>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Event today
                                                    </h5>
                                                    <span
                                                        class="fs-2 text-nowrap d-block time text-truncate fw-normal text-muted mt-1">Just
                                                        a
                                                        reminder
                                                        that you have
                                                        event</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:10
                                                        AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="btn btn-light-info text-info btn-circle">
                                                    <i data-feather="settings" class="feather-sm fill-white"></i>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Settings
                                                    </h5>
                                                    <span
                                                        class="fs-2 text-nowrap d-block time text-truncate fw-normal text-muted mt-1">You
                                                        can
                                                        customize
                                                        this template as you
                                                        want</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:08
                                                        AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="btn btn-light-primary text-primary btn-circle">
                                                    <i data-feather="users" class="feather-sm fill-white"></i>
                                                </span>
                                                <div class="w-75 d-inline-block v-middle ps-3">
                                                    <h5 class="message-title mb-0 mt-1 fs-3 fw-bold">
                                                        Pavan kumar
                                                    </h5>
                                                    <span
                                                        class="fs-2 text-nowrap d-block time text-truncate fw-normal text-muted mt-1">Just
                                                        see
                                                        the my
                                                        admin!</span>
                                                    <span class="fs-2 text-nowrap d-block subtext text-muted">9:02
                                                        AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link border-top text-center text-dark pt-3"
                                            href="javascript:void(0);">
                                            <strong>Check all
                                                notifications</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- mega menu -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown mega-dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-none d-md-block">Mega
                                    <i class="icon-options-vertical"></i></span>
                                <span class="d-block d-md-none"><i class="mdi mdi-dialpad font-24"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-animate-up">
                                <div class="mega-dropdown-menu row">
                                    <div class="col-lg-3 col-xl-2 mb-4">
                                        <h4 class="mb-3">CAROUSEL</h4>
                                        <!-- CAROUSEL -->
                                        <div id="carouselExampleControls" class="carousel slide carousel-dark"
                                            data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img class="d-block img-fluid"
                                                        src="<?php echo e(URL::asset('template/assets/images/big/img1.jpg')); ?>"
                                                        alt="First slide" />
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block img-fluid"
                                                        src="<?php echo e(URL::asset('template/assets/images/big/img2.jpg')); ?>"
                                                        alt="Second slide" />
                                                </div>
                                                <div class="carousel-item">
                                                    <img class="d-block img-fluid"
                                                        src="<?php echo e(URL::asset('template/assets/images/big/img3.jpg')); ?>"
                                                        alt="Third slide" />
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls"
                                                role="button" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls"
                                                role="button" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </a>
                                        </div>
                                        <!-- End CAROUSEL -->
                                    </div>
                                    <div class="col-lg-3 mb-4">
                                        <h4 class="mb-3">ACCORDION</h4>
                                        <!-- Accordian -->
                                        <div class="accordion accordion-flush" id="accordionFlushExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingOne">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                        aria-expanded="false" aria-controls="flush-collapseOne">
                                                        Accordion Item #1
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                                    aria-labelledby="flush-headingOne"
                                                    data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body">
                                                        Anim pariatur cliche
                                                        reprehenderit, enim
                                                        eiusmod high life
                                                        accusamus terry
                                                        richardson ad squid.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingTwo">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                        aria-expanded="false" aria-controls="flush-collapseTwo">
                                                        Accordion Item #2
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                                    aria-labelledby="flush-headingTwo"
                                                    data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body">
                                                        Anim pariatur cliche
                                                        reprehenderit, enim
                                                        eiusmod high life
                                                        accusamus terry
                                                        richardson ad squid.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-headingThree">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#flush-collapseThree" aria-expanded="false"
                                                        aria-controls="flush-collapseThree">
                                                        Accordion Item #3
                                                    </button>
                                                </h2>
                                                <div id="flush-collapseThree" class="accordion-collapse collapse"
                                                    aria-labelledby="flush-headingThree"
                                                    data-bs-parent="#accordionFlushExample">
                                                    <div class="accordion-body">
                                                        Anim pariatur cliche
                                                        reprehenderit, enim
                                                        eiusmod high life
                                                        accusamus terry
                                                        richardson ad squid.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-4">
                                        <h4 class="mb-3">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="mb-3 form-floating">
                                                <input type="text" class="form-control" id="exampleInputname1"
                                                    placeholder="Enter Name" />
                                                <label>Enter Name</label>
                                            </div>
                                            <div class="mb-3 form-floating">
                                                <input type="email" class="form-control"
                                                    placeholder="Enter email" />
                                                <label>Enter Email
                                                    address</label>
                                            </div>
                                            <div class="mb-3 form-floating">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                                <label>Enter Message</label>
                                            </div>
                                            <button type="submit" class="btn px-4 rounded-pill btn-info">
                                                Submit
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-lg-3 col-xlg-4 mb-4">
                                        <h4 class="mb-3">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li>
                                                <a href="#" class="font-weight-medium"><i
                                                        data-feather="check-circle"
                                                        class="feather-sm text-success me-2"></i>
                                                    You can give link</a>
                                            </li>
                                            <li>
                                                <a href="#" class="font-weight-medium"><i
                                                        data-feather="check-circle"
                                                        class="feather-sm text-success me-2"></i>
                                                    Give link</a>
                                            </li>
                                            <li>
                                                <a href="#" class="font-weight-medium"><i
                                                        data-feather="check-circle"
                                                        class="feather-sm text-success me-2"></i>
                                                    Another Give link</a>
                                            </li>
                                            <li>
                                                <a href="#" class="font-weight-medium"><i
                                                        data-feather="check-circle"
                                                        class="feather-sm text-success me-2"></i>
                                                    Forth link</a>
                                            </li>
                                            <li>
                                                <a href="#" class="font-weight-medium"><i
                                                        data-feather="check-circle"
                                                        class="feather-sm text-success me-2"></i>
                                                    Another fifth link</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End mega menu -->
                        <!-- ============================================================== -->
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
                                <img src="<?php echo e(URL::asset('template/assets/images/users/1.jpg')); ?>" alt="user"
                                    class="rounded-circle" width="36" />
                                <span class="ms-2 font-weight-medium">Steve</span><span
                                    class="fas fa-angle-down ms-2"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end user-dd animated flipInY">
                                <div class="d-flex no-block align-items-center p-3 bg-info text-white mb-2">
                                    <div class="">
                                        <img src="<?php echo e(URL::asset('template/assets/images/users/1.jpg')); ?>"
                                            alt="user" class="rounded-circle" width="60" />
                                    </div>
                                    <div class="ms-2">
                                        <h4 class="mb-0 text-white">
                                            Steave Jobs
                                        </h4>
                                        <p class="mb-0">varun@gmail.com</p>
                                    </div>
                                </div>
                                <a class="dropdown-item" href="#"><i data-feather="user"
                                        class="feather-sm text-info me-1 ms-1"></i>
                                    My Profile</a>
                                <a class="dropdown-item" href="#"><i data-feather="credit-card"
                                        class="feather-sm text-info me-1 ms-1"></i>
                                    My Balance</a>
                                <a class="dropdown-item" href="#"><i data-feather="mail"
                                        class="feather-sm text-success me-1 ms-1"></i>
                                    Inbox</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i data-feather="settings"
                                        class="feather-sm text-warning me-1 ms-1"></i>
                                    Account Setting</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"><i data-feather="log-out"
                                        class="feather-sm text-danger me-1 ms-1"></i>
                                    Logout</a>
                                <div class="dropdown-divider"></div>
                                <div class="ps-4 p-2">
                                    <a href="#" class="btn d-block w-100 btn-info rounded-pill">View Profile</a>
                                </div>
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
                        <li class="nav-small-cap">
                            <i class="mdi mdi-dots-horizontal"></i>
                            <span class="hide-menu">Personal</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-av-timer"></i><span
                                    class="hide-menu">Dashboards
                                </span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="index.html" class="sidebar-link"><i class="mdi mdi-adjust"></i><span
                                            class="hide-menu">
                                            Dashboard 1
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index2.html" class="sidebar-link"><i class="mdi mdi-adjust"></i><span
                                            class="hide-menu">
                                            Dashboard 2
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index3.html" class="sidebar-link"><i class="mdi mdi-adjust"></i><span
                                            class="hide-menu">
                                            Dashboard 3
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index4.html" class="sidebar-link"><i class="mdi mdi-adjust"></i><span
                                            class="hide-menu">
                                            Dashboard 4
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index5.html" class="sidebar-link"><i class="mdi mdi-adjust"></i><span
                                            class="hide-menu">
                                            Dashboard 5
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index6.html" class="sidebar-link"><i class="mdi mdi-adjust"></i><span
                                            class="hide-menu">
                                            Dashboard 6
                                        </span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" alt href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span
                                    class="hide-menu">รายการสินค้า
                                </span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="<?php echo e(route('products.index')); ?>" class="sidebar-link"><i
                                            class="mdi mdi-cards-variant"></i><span class="hide-menu">
                                            ค่าบริการ และส่วนลด
                                        </span></a>
                                </li>
                                
                            </ul>
                        </li>
                        <li class="nav-small-cap">
                            <i class="mdi mdi-dots-horizontal"></i>
                            <span class="hide-menu">Sample Pages</span>
                        </li>
                        <li class="sidebar-item mega-dropdown">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-content-copy"></i><span
                                    class="hide-menu">Pages </span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="authentication-login1.html" class="sidebar-link"><i
                                            class="mdi mdi-account-key"></i><span class="hide-menu">
                                            Login
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="starter-kit.html" class="sidebar-link"><i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">Starter Kit</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-animation.html" class="sidebar-link"><i
                                            class="mdi mdi-debug-step-over"></i>
                                        <span class="hide-menu">Animation</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-search-result.html" class="sidebar-link"><i
                                            class="mdi mdi-search-web"></i>
                                        <span class="hide-menu">Search Result</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="authentication-login2.html" class="sidebar-link"><i
                                            class="mdi mdi-account-key"></i><span class="hide-menu">
                                            Login 2
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-gallery.html" class="sidebar-link"><i
                                            class="mdi mdi-camera-iris"></i>
                                        <span class="hide-menu">Gallery</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-treeview.html" class="sidebar-link"><i
                                            class="mdi mdi-file-tree"></i>
                                        <span class="hide-menu">Treeview</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-block-ui.html" class="sidebar-link"><i
                                            class="mdi mdi-codepen"></i>
                                        <span class="hide-menu">Block UI</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="authentication-login3.html" class="sidebar-link"><i
                                            class="mdi mdi-account-key"></i><span class="hide-menu">
                                            Login 3
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="authentication-register1.html" class="sidebar-link"><i
                                            class="mdi mdi-account-plus"></i><span class="hide-menu">
                                            Register</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-session-timeout.html" class="sidebar-link"><i
                                            class="mdi mdi-timer-off"></i>
                                        <span class="hide-menu">Session Timeout</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-session-idle-timeout.html" class="sidebar-link"><i
                                            class="mdi mdi-timer-sand-empty"></i>
                                        <span class="hide-menu">Session Idle Timeout</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-utility-classes.html" class="sidebar-link"><i
                                            class="mdi mdi-tune"></i>
                                        <span class="hide-menu">Helper Classes</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="authentication-register2.html" class="sidebar-link"><i
                                            class="mdi mdi-account-plus"></i><span class="hide-menu">
                                            Register 2</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-maintenance.html" class="sidebar-link"><i
                                            class="mdi mdi-camera-iris"></i>
                                        <span class="hide-menu">Maintenance Page</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-user-card.html" class="sidebar-link"><i
                                            class="mdi mdi-account-box"></i>
                                        <span class="hide-menu">
                                            User Card
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-profile.html" class="sidebar-link"><i
                                            class="mdi mdi-account-network"></i><span class="hide-menu">
                                            User Profile</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="authentication-lockscreen.html" class="sidebar-link"><i
                                            class="mdi mdi-account-off"></i><span class="hide-menu">
                                            Lockscreen</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-user-contacts.html" class="sidebar-link"><i
                                            class="mdi mdi-account-star-variant"></i><span class="hide-menu">
                                            User Contact</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-invoice.html" class="sidebar-link"><i
                                            class="mdi mdi-vector-triangle"></i><span class="hide-menu">
                                            Invoice Layout
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="pages-invoice-list.html" class="sidebar-link"><i
                                            class="mdi mdi-vector-rectangle"></i><span class="hide-menu">
                                            Invoice List</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="authentication-recover-password.html" class="sidebar-link"><i
                                            class="mdi mdi-account-convert"></i><span class="hide-menu">
                                            Recover password</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="map-google.html" class="sidebar-link"><i
                                            class="mdi mdi-google-maps"></i><span class="hide-menu">
                                            Google Map
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="map-vector.html" class="sidebar-link"><i
                                            class="mdi mdi-map-marker-radius"></i><span class="hide-menu">
                                            Vector Map</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="icon-feather.html" class="sidebar-link"><i class="mdi mdi-feather"></i>
                                        <span class="hide-menu">
                                            Feather Icons
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="icon-material.html" class="sidebar-link"><i
                                            class="mdi mdi-emoticon"></i>
                                        <span class="hide-menu">
                                            Material Icons
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="icon-fontawesome.html" class="sidebar-link"><i
                                            class="mdi mdi-emoticon-cool"></i><span class="hide-menu">
                                            Fontawesome Icons</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="icon-themify.html" class="sidebar-link"><i
                                            class="mdi mdi-chart-bubble"></i><span class="hide-menu">
                                            Themify Icons</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="icon-weather.html" class="sidebar-link"><i
                                            class="mdi mdi-weather-cloudy"></i><span class="hide-menu">
                                            Weather Icons</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="icon-simple-lineicon.html" class="sidebar-link"><i
                                            class="mdi mdi mdi-image-broken-variant"></i>
                                        <span class="hide-menu">
                                            Simple Line icons</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="icon-flag.html" class="sidebar-link"><i
                                            class="mdi mdi-flag-triangle"></i><span class="hide-menu">
                                            Flag Icons</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="timeline-center.html" class="sidebar-link"><i
                                            class="mdi mdi-clock-fast"></i>
                                        <span class="hide-menu">
                                            Center Timeline
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="timeline-horizontal.html" class="sidebar-link"><i
                                            class="mdi mdi-clock-end"></i><span class="hide-menu">
                                            Horizontal Timeline</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="timeline-left.html" class="sidebar-link"><i
                                            class="mdi mdi-clock-in"></i><span class="hide-menu">
                                            Left Timeline</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="timeline-right.html" class="sidebar-link"><i
                                            class="mdi mdi-clock-start"></i><span class="hide-menu">
                                            Right Timeline</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="error-400.html" class="sidebar-link"><i
                                            class="mdi mdi-alert-outline"></i>
                                        <span class="hide-menu">
                                            Error 400
                                        </span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="error-403.html" class="sidebar-link"><i
                                            class="mdi mdi-alert-outline"></i><span class="hide-menu">
                                            Error 403</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="error-404.html" class="sidebar-link"><i
                                            class="mdi mdi-alert-outline"></i><span class="hide-menu">
                                            Error 404</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="eco-products-orders.html" class="sidebar-link"><i
                                            class="mdi mdi-chart-pie"></i>
                                        <span class="hide-menu">Eco- Product Orders</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="error-500.html" class="sidebar-link"><i
                                            class="mdi mdi-alert-outline"></i><span class="hide-menu">
                                            Error 500</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="error-503.html" class="sidebar-link"><i
                                            class="mdi mdi-alert-outline"></i><span class="hide-menu">
                                            Error 503</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link two-column has-arrow waves-effect waves-dark"
                                href="javascript:void(0)" aria-expanded="false"><i
                                    class="mdi mdi-format-color-fill"></i><span class="hide-menu">UI </span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="ui-accordian.html" class="sidebar-link">
                                        <i class="mdi mdi-box-shadow"></i>
                                        <span class="hide-menu">
                                            Accordian</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-badge.html" class="sidebar-link">
                                        <i class="mdi mdi-application"></i>
                                        <span class="hide-menu">
                                            Badge</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-buttons.html" class="sidebar-link">
                                        <i class="mdi mdi-toggle-switch"></i>
                                        <span class="hide-menu">
                                            Buttons</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-modals.html" class="sidebar-link">
                                        <i class="mdi mdi-tablet"></i>
                                        <span class="hide-menu">
                                            Modals</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow waves-effect waves-dark"
                                        href="javascript:void(0)" aria-expanded="false"><i
                                            class="mdi mdi-credit-card-multiple"></i><span
                                            class="hide-menu">Cards</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="ui-cards.html" class="sidebar-link">
                                                <i class="mdi mdi-layers"></i>
                                                <span class="hide-menu">
                                                    Basic Cards</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="ui-card-customs.html" class="sidebar-link">
                                                <i class="mdi mdi-credit-card-scan"></i>
                                                <span class="hide-menu">Custom Cards</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="ui-card-weather.html" class="sidebar-link">
                                                <i class="mdi mdi-weather-fog"></i>
                                                <span class="hide-menu">Weather Cards</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="ui-card-draggable.html" class="sidebar-link">
                                                <i class="mdi mdi-bandcamp"></i>
                                                <span class="hide-menu">Draggable Cards</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow waves-effect waves-dark"
                                        href="javascript:void(0)" aria-expanded="false"><i
                                            class="mdi mdi-credit-card-multiple"></i><span
                                            class="hide-menu">Components</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="component-sweetalert.html" class="sidebar-link">
                                                <i class="mdi mdi-layers"></i>
                                                <span class="hide-menu">
                                                    Sweet Alert</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="component-nestable.html" class="sidebar-link">
                                                <i class="mdi mdi-credit-card-scan"></i>
                                                <span class="hide-menu">Nestable</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="component-noui-slider.html" class="sidebar-link">
                                                <i class="mdi mdi-weather-fog"></i>
                                                <span class="hide-menu">Noui slider</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="component-rating.html" class="sidebar-link">
                                                <i class="mdi mdi-bandcamp"></i>
                                                <span class="hide-menu">Rating</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="component-toastr.html" class="sidebar-link">
                                                <i class="mdi mdi-poll"></i>
                                                <span class="hide-menu">Toastr</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-tab.html" class="sidebar-link">
                                        <i class="mdi mdi-sort-variant"></i>
                                        <span class="hide-menu"> Tab</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-tooltip-popover.html" class="sidebar-link">
                                        <i class="mdi mdi-image-filter-vintage"></i>
                                        <span class="hide-menu">
                                            Tooltip &amp; Popover</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-notification.html" class="sidebar-link">
                                        <i class="mdi mdi-message-bulleted"></i>
                                        <span class="hide-menu">
                                            Notification</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-progressbar.html" class="sidebar-link">
                                        <i class="mdi mdi-poll"></i>
                                        <span class="hide-menu">
                                            Progressbar</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-typography.html" class="sidebar-link">
                                        <i class="mdi mdi-format-line-spacing"></i>
                                        <span class="hide-menu">
                                            Typography</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-bootstrap.html" class="sidebar-link">
                                        <i class="mdi mdi-bootstrap"></i>
                                        <span class="hide-menu">
                                            Bootstrap Ui</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-breadcrumb.html" class="sidebar-link">
                                        <i class="mdi mdi-equal"></i>
                                        <span class="hide-menu">
                                            Breadcrumb</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-lists.html" class="sidebar-link">
                                        <i class="mdi mdi-file-video"></i>
                                        <span class="hide-menu">
                                            Lists</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-grid.html" class="sidebar-link">
                                        <i class="mdi mdi-view-module"></i>
                                        <span class="hide-menu"> Grid</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-carousel.html" class="sidebar-link">
                                        <i class="mdi mdi-view-carousel"></i>
                                        <span class="hide-menu">
                                            Carousel</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-scrollspy.html" class="sidebar-link">
                                        <i class="mdi mdi-crop-free"></i>
                                        <span class="hide-menu">
                                            Scrollspy</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-offcanvas.html" class="sidebar-link">
                                        <i class="mdi mdi-content-copy"></i>
                                        <span class="hide-menu">
                                            Offcanvas</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-spinner.html" class="sidebar-link">
                                        <i class="mdi mdi-application"></i>
                                        <span class="hide-menu">
                                            Spinner</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="ui-toasts.html" class="sidebar-link">
                                        <i class="mdi mdi-apple-safari"></i>
                                        <span class="hide-menu">
                                            Toasts</span>
                                    </a>
                                </li>
                            </ul>
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
                                            class="mdi mdi-border-style"></i><span
                                            class="hide-menu">ข้อมูลสายการบิน</span></a>
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
                                    <a href="<?php echo e(route('report.quote.form')); ?>" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">รายงานใบเสนอราคา</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        

                        
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                                aria-expanded="false"><i class="mdi mdi-settings"></i><span
                                    class="hide-menu">Widgets </span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="widgets-apps.html" class="sidebar-link">
                                        <i class="mdi mdi-comment-processing-outline"></i>
                                        <span class="hide-menu">
                                            App Widgets</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="widgets-charts.html" class="sidebar-link">
                                        <i class="mdi mdi-bulletin-board"></i>
                                        <span class="hide-menu">
                                            Chart Widgets</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="widgets-data.html" class="sidebar-link">
                                        <i class="mdi mdi-calendar"></i>
                                        <span class="hide-menu">
                                            Data Widgets</span>
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
                                    <a href="widgets-data.html" class="sidebar-link">
                                        <i class="mdi mdi-calendar"></i>
                                        <span class="hide-menu">
                                            Data Widgets</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-small-cap">
                            <i class="mdi mdi-dots-horizontal"></i>
                            <span class="hide-menu">Charts</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow waves-effect waves-dark sidebar-link"
                                href="javascript:void(0)" aria-expanded="false"><i
                                    class="mdi mdi-image-filter-tilt-shift"></i><span class="hide-menu">
                                    Charts</span></a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow waves-effect waves-dark"
                                        href="javascript:void(0)" aria-expanded="false"><i
                                            class="mdi mdi-chart-bubble"></i><span class="hide-menu">Apex
                                            Charts</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="chart-apex-line.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-line"></i>
                                                <span class="hide-menu">Apex Line Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-apex-area.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-areaspline"></i>
                                                <span class="hide-menu">Apex Area Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-apex-bar.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-gantt"></i>
                                                <span class="hide-menu">Apex Bar Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-apex-pie.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-pie"></i>
                                                <span class="hide-menu">Apex Pie Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-apex-radial.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-arc"></i>
                                                <span class="hide-menu">Apex Radial Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-apex-radar.html" class="sidebar-link"><i
                                                    class="mdi mdi-hexagon-outline"></i>
                                                <span class="hide-menu">Apex Radar Chart</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="chart-morris.html" aria-expanded="false"><i
                                            class="mdi mdi-image-filter-tilt-shift"></i><span class="hide-menu">
                                            Morris Chart</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="chart-flot.html" aria-expanded="false"><i
                                            class="mdi mdi-image-filter-tilt-shift"></i><span class="hide-menu">
                                            Flot Chart</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="chart-chart-js.html" aria-expanded="false"><i
                                            class="mdi mdi-svg"></i><span class="hide-menu">Chartjs</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="chart-sparkline.html" aria-expanded="false"><i
                                            class="mdi mdi-chart-histogram"></i><span class="hide-menu">Sparkline
                                            Chart</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                        href="chart-chartist.html" aria-expanded="false"><i
                                            class="mdi mdi-blur"></i><span class="hide-menu">Chartis
                                            Chart</span></a>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow waves-effect waves-dark"
                                        href="javascript:void(0)" aria-expanded="false"><i
                                            class="mdi mdi-chemical-weapon"></i><span class="hide-menu">C3
                                            Charts</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="chart-c3-axis.html" class="sidebar-link"><i
                                                    class="mdi mdi-arrange-bring-to-front"></i>
                                                <span class="hide-menu">Axis Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-c3-bar.html" class="sidebar-link"><i
                                                    class="mdi mdi-arrange-send-to-back"></i>
                                                <span class="hide-menu">Bar Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-c3-data.html" class="sidebar-link"><i
                                                    class="mdi mdi-backup-restore"></i>
                                                <span class="hide-menu">Data Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-c3-line.html" class="sidebar-link"><i
                                                    class="mdi mdi-backburger"></i>
                                                <span class="hide-menu">Line Chart</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow waves-effect waves-dark"
                                        href="javascript:void(0)" aria-expanded="false"><i
                                            class="mdi mdi-chart-areaspline"></i><span
                                            class="hide-menu">Echarts</span></a>
                                    <ul aria-expanded="false" class="collapse second-level">
                                        <li class="sidebar-item">
                                            <a href="chart-echart-basic.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-line"></i>
                                                <span class="hide-menu">Basic Charts</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-echart-bar.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-scatterplot-hexbin"></i>
                                                <span class="hide-menu">Bar Chart</span></a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="chart-echart-pie-doughnut.html" class="sidebar-link"><i
                                                    class="mdi mdi-chart-pie"></i>
                                                <span class="hide-menu">Pie &amp; Doughnut
                                                    Chart</span></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
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
        <!-- -------------------------------------------------------------- -->
        <!-- footer -->
        <!-- -------------------------------------------------------------- -->

        <footer class="footer text-center">
            All Rights Reserved by Ample admin.
        </footer>
        <!-- -------------------------------------------------------------- -->
        <!-- End footer -->
        <!-- -------------------------------------------------------------- -->
    </div>

    <!-- -------------------------------------------------------------- -->
    <!-- End Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Wrapper -->
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

</body>

</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/accounting-nexttripholiday/resources/views/layouts/template.blade.php ENDPATH**/ ?>