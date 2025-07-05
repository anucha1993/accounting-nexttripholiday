/**
 * ไฟล์ JavaScript สำหรับระบบแจ้งเตือน (Notification System)
 */
$(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // ตรวจสอบว่ามีการล็อกอินหรือไม่
    if ($('#notification-dropdown').length === 0 || typeof notificationRoutes === 'undefined') {
        console.log("Notification dropdown not found or routes not defined");
        return;
    }
    
    console.log("Notification system initialized");
    
    // แก้ไข URL สำหรับ markAsRead เพื่อรองรับการแทนที่ ID
    notificationRoutes.markAsRead = notificationRoutes.markAsRead.replace('ID_PLACEHOLDER', '');

    // โหลดรายการแจ้งเตือนล่าสุด
    function loadNotifications() {
        console.log("Loading notifications from:", notificationRoutes.getRecent);
        $.ajax({
            url: notificationRoutes.getRecent,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log("Notifications loaded:", response);
                renderNotifications(response.notifications, response.unreadCount);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching notifications:', error);
                console.error('Response:', xhr.responseText);
                // แสดงข้อความ error
                $('#notification-items').html(`
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-circle text-warning fa-2x mb-2"></i>
                        <p class="mb-0 text-muted">ไม่สามารถโหลดข้อมูลได้</p>
                    </div>
                `);
            }
        });
    }

    // แสดงรายการแจ้งเตือน
    function renderNotifications(notifications, unreadCount) {
        console.log("Rendering notifications:", notifications.length, "items, unread:", unreadCount);
        
        // อัปเดตจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน
        updateNotificationBadge(unreadCount);

        // แสดงรายการการแจ้งเตือน
        const $container = $('#notification-items');
        $container.empty();

        if (notifications.length === 0) {
            // ไม่มีการแจ้งเตือน
            console.log("No notifications to display");
            $container.html(`
                <div class="text-center py-5 d-flex align-items-center justify-content-center h-100">
                    <div>
                        <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                        <p class="mb-0 text-muted">ไม่มีการแจ้งเตือน</p>
                    </div>
                </div>
            `);
            return;
        }

        // วนลูปแสดงรายการแจ้งเตือน
        notifications.forEach(function(item) {
            let icon = getNotificationIcon(item.related_type);
            // เงื่อนไข: ถ้า user_id ของ notify ไม่ตรงกับ user ที่ login (response.user_id) ให้ซ่อนปุ่ม mark-as-read
            const isOwnNotify = (item.user_id == response.user_id);
            const markAsReadBtn = isOwnNotify && item.status === 'unread' ? '<span class="badge rounded-pill bg-danger ms-1">ใหม่</span>' : '';
            // สร้าง HTML สำหรับแต่ละรายการ
            const itemHtml = `
                <a href="javascript:void(0)" class="notification-item message-item d-flex align-items-center border-bottom px-3 py-2 ${item.status === 'unread' ? 'unread-notification' : ''}" 
                   data-id="${item.id}" data-url="${item.action_url || '#'}" data-user-id="${item.user_id}">
                    <div class="notification-icon rounded-circle d-flex align-items-center justify-content-center me-3 ${getNotificationClass(item.related_type)}" style="width: 40px; height: 40px;">
                        ${icon}
                    </div>
                    <div class="w-75">
                        <h5 class="message-title mb-0 mt-1 font-weight-medium">${truncateText(item.message, 60)}</h5>
                        <span class="fs-3 text-nowrap d-block time text-muted">${item.time_ago}</span>
                    </div>
                    ${markAsReadBtn}
                </a>
            `;
            $container.append(itemHtml);
        });

        // กำหนดการคลิกที่รายการแจ้งเตือน (เฉพาะของตัวเองเท่านั้น)
        $('.notification-item').on('click', function() {
            const notificationId = $(this).data('id');
            const url = $(this).data('url');
            const notifyUserId = $(this).data('user-id');
            // ต้องมี id จริงเท่านั้นถึงจะเรียก markAsRead
            if (notifyUserId == response.user_id && notificationId) {
                markAsRead(notificationId, url);
            }
        });
    }

    // อัปเดตแสดงจำนวนการแจ้งเตือนที่ยังไม่อ่าน
    function updateNotificationBadge(count) {
        if (count > 0) {
            $('#notification-badge').show();
            $('#notification-count').text(count).show();
        } else {
            $('#notification-badge').hide();
            $('#notification-count').hide();
        }
    }

    // ทำเครื่องหมายว่าอ่านแล้ว
    function markAsRead(notificationId, url) {
        $.ajax({
            url: notificationRoutes.markAsRead + notificationId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                // ถ้ามี redirectUrl ในการตอบกลับ ใช้ URL นั้น
                if (response.redirectUrl) {
                    window.location.href = response.redirectUrl;
                }
                // ถ้ามี url จาก parameter และไม่มี redirectUrl จากการตอบกลับ
                else if (url && url !== '#') {
                    window.location.href = url;
                } else {
                    // ถ้าไม่มี URL ให้โหลดข้อมูลการแจ้งเตือนใหม่
                    loadNotifications();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking notification as read:', error);
                // แสดงข้อความผิดพลาด
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert('ข้อผิดพลาด: ' + xhr.responseJSON.message);
                }
            }
        });
    }

    // ทำเครื่องหมายว่าอ่านแล้วทั้งหมด
    function markAllAsRead() {
        $.ajax({
            url: notificationRoutes.markAllAsRead,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                loadNotifications(); // โหลดข้อมูลใหม่
            },
            error: function(xhr, status, error) {
                console.error('Error marking all notifications as read:', error);
                // แสดง alert ถ้าต้องการ
            }
        });
    }

    // ฟังก์ชันย่อข้อความที่ยาวเกินไป
    function truncateText(text, maxLength) {
        if (text.length > maxLength) {
            return text.substring(0, maxLength) + '...';
        }
        return text;
    }

    // ฟังก์ชันดึงไอคอนตามประเภทการแจ้งเตือน
    function getNotificationIcon(type) {
        switch (type) {
            case 'refund':
                return '<i class="fas fa-money-bill-wave text-white"></i>';
            case 'wholesale_refund':
                return '<i class="fas fa-money-check-alt text-white"></i>';
            case 'wholesale_payment':
                return '<i class="fas fa-hand-holding-usd text-white"></i>';
            case 'booking':
                return '<i class="fas fa-calendar-alt text-white"></i>';
            default:
                return '<i class="fas fa-bell text-white"></i>';
        }
    }

    // ฟังก์ชันดึง class ตามประเภทการแจ้งเตือน
    function getNotificationClass(type) {
        switch (type) {
            case 'refund':
                return 'bg-danger';
            case 'wholesale_refund':
                return 'bg-warning';
            case 'wholesale_payment':
                return 'bg-success';
            case 'booking':
                return 'bg-primary';
            default:
                return 'bg-info';
        }
    }

    // Event handler สำหรับปุ่ม "อ่านทั้งหมด"
    $('#mark-all-read').on('click', function(e) {
        e.preventDefault();
        markAllAsRead();
    });

    // โหลดรายการแจ้งเตือนเมื่อโหลดหน้าเว็บ
    loadNotifications();

    // โหลดรายการแจ้งเตือนทุก 1 นาที
    setInterval(loadNotifications, 60000);

    // เพิ่มสไตล์ CSS สำหรับแจ้งเตือนที่ยังไม่อ่าน
    $('<style>')
        .append(`
            .unread-notification {
                background-color: rgba(0, 123, 255, 0.05);
            }
        `)
        .appendTo('head');
});
