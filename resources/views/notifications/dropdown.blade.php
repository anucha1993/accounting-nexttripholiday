{{-- ส่วนแสดงรายการแจ้งเตือนในแถบ Navbar --}}
<li class="nav-item dropdown" id="notification-dropdown">
    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#"
        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fs-5 mdi mdi-bell"></i>
        <div class="notify" id="notification-badge" style="display: none;">
            <span class="heartbit"></span>
            <span class="point"></span>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up notification-dropdown"
        style="width: 300px; max-height: 420px; overflow-y: auto;">
        <ul class="list-style-none">
            <li>
                <div class="border-bottom rounded-top py-3 px-4 d-flex justify-content-between align-items-center">
                    <div class="mb-0 font-weight-medium fs-4">
                        การแจ้งเตือน <span class="badge bg-danger rounded-pill" id="notification-count" style="display: none;"></span>
                    </div>
                    <div>
                        <a href="javascript:void(0)" id="mark-all-read" class="link text-decoration-none me-1">
                            <i class="fas fa-check-double"></i> อ่านทั้งหมด
                        </a>
                    </div>
                </div>
            </li>
            <li>
                <div id="notification-items" class="message-center notification-body position-relative"
                    style="height: 300px;">
                    <div class="text-center py-5 d-flex align-items-center justify-content-center h-100">
                        <div>
                            <div class="spinner-border text-primary mb-2" role="status">
                                <span class="visually-hidden">กำลังโหลด...</span>
                            </div>
                            <p class="mb-0 text-muted">กำลังโหลดข้อมูล...</p>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <a class="nav-link text-center border-top text-dark pt-3" href="{{ route('notifications.index') }}">
                    <strong>ดูการแจ้งเตือนทั้งหมด</strong>
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </div>
</li>
