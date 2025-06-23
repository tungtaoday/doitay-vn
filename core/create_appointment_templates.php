<?php

require_once 'bootstrap/app.php';

use App\Models\NotificationTemplate;

echo "Creating appointment notification templates...\n";

// Template cho New Appointment
$newAppointment = NotificationTemplate::updateOrCreate(
    ['act' => 'NEW_APPOINTMENT'],
    [
        'name' => 'New Appointment Created',
        'subject' => 'Lịch hẹn mới từ {{customer_name}}',
        'email_body' => '<p>Xin chào {{company_name}},</p>
<p>Bạn có một lịch hẹn mới:</p>
<ul>
<li><strong>Khách hàng:</strong> {{customer_name}}</li>
<li><strong>Số điện thoại:</strong> {{customer_phone}}</li>
<li><strong>Địa chỉ:</strong> {{customer_address}}</li>
<li><strong>Ngày hẹn:</strong> {{appointment_date}}</li>
<li><strong>Giờ hẹn:</strong> {{appointment_time}}</li>
<li><strong>Ghi chú:</strong> {{notes}}</li>
</ul>
<p>Vui lòng xác nhận lịch hẹn trong thời gian sớm nhất.</p>',
        'sms_body' => 'Lịch hẹn mới từ {{customer_name}} vào {{appointment_date}} {{appointment_time}}. Địa chỉ: {{customer_address}}',
        'shortcodes' => json_encode([
            'customer_name' => 'Tên khách hàng',
            'customer_phone' => 'Số điện thoại khách hàng', 
            'customer_address' => 'Địa chỉ khách hàng',
            'appointment_date' => 'Ngày hẹn',
            'appointment_time' => 'Giờ hẹn',
            'notes' => 'Ghi chú',
            'company_name' => 'Tên công ty'
        ]),
        'email_status' => 1,
        'sms_status' => 1
    ]
);

echo "✓ NEW_APPOINTMENT template created\n";

// Template cho Appointment Confirmed
$confirmedAppointment = NotificationTemplate::updateOrCreate(
    ['act' => 'APPOINTMENT_CONFIRMED'],
    [
        'name' => 'Appointment Confirmed',
        'subject' => 'Lịch hẹn đã được xác nhận',
        'email_body' => '<p>Xin chào {{customer_name}},</p>
<p>Lịch hẹn của bạn đã được xác nhận:</p>
<ul>
<li><strong>Công ty:</strong> {{company_name}}</li>
<li><strong>Ngày hẹn:</strong> {{appointment_date}}</li>
<li><strong>Giờ hẹn:</strong> {{appointment_time}}</li>
<li><strong>Địa chỉ:</strong> {{customer_address}}</li>
</ul>
<p>Thợ sẽ liên hệ với bạn trước khi đến.</p>',
        'sms_body' => 'Lịch hẹn với {{company_name}} vào {{appointment_date}} {{appointment_time}} đã được xác nhận.',
        'shortcodes' => json_encode([
            'customer_name' => 'Tên khách hàng',
            'customer_phone' => 'Số điện thoại khách hàng', 
            'customer_address' => 'Địa chỉ khách hàng',
            'appointment_date' => 'Ngày hẹn',
            'appointment_time' => 'Giờ hẹn',
            'notes' => 'Ghi chú',
            'company_name' => 'Tên công ty'
        ]),
        'email_status' => 1,
        'sms_status' => 1
    ]
);

echo "✓ APPOINTMENT_CONFIRMED template created\n";

// Template cho Appointment Canceled
$canceledAppointment = NotificationTemplate::updateOrCreate(
    ['act' => 'APPOINTMENT_CANCELED'],
    [
        'name' => 'Appointment Canceled',
        'subject' => 'Lịch hẹn đã bị hủy',
        'email_body' => '<p>Xin chào {{customer_name}},</p>
<p>Rất tiếc, lịch hẹn của bạn đã bị hủy:</p>
<ul>
<li><strong>Công ty:</strong> {{company_name}}</li>
<li><strong>Ngày hẹn:</strong> {{appointment_date}}</li>
<li><strong>Giờ hẹn:</strong> {{appointment_time}}</li>
</ul>
<p>Bạn có thể đặt lịch hẹn mới bất cứ lúc nào.</p>',
        'sms_body' => 'Lịch hẹn với {{company_name}} vào {{appointment_date}} {{appointment_time}} đã bị hủy.',
        'shortcodes' => json_encode([
            'customer_name' => 'Tên khách hàng',
            'customer_phone' => 'Số điện thoại khách hàng', 
            'customer_address' => 'Địa chỉ khách hàng',
            'appointment_date' => 'Ngày hẹn',
            'appointment_time' => 'Giờ hẹn',
            'notes' => 'Ghi chú',
            'company_name' => 'Tên công ty'
        ]),
        'email_status' => 1,
        'sms_status' => 1
    ]
);

echo "✓ APPOINTMENT_CANCELED template created\n";

// Template cho Appointment Completed
$completedAppointment = NotificationTemplate::updateOrCreate(
    ['act' => 'APPOINTMENT_COMPLETED'],
    [
        'name' => 'Appointment Completed',
        'subject' => 'Lịch hẹn đã hoàn thành',
        'email_body' => '<p>Xin chào {{customer_name}},</p>
<p>Lịch hẹn của bạn đã được hoàn thành:</p>
<ul>
<li><strong>Công ty:</strong> {{company_name}}</li>
<li><strong>Ngày hẹn:</strong> {{appointment_date}}</li>
<li><strong>Giờ hẹn:</strong> {{appointment_time}}</li>
</ul>
<p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>',
        'sms_body' => 'Lịch hẹn với {{company_name}} vào {{appointment_date}} đã hoàn thành. Cảm ơn bạn!',
        'shortcodes' => json_encode([
            'customer_name' => 'Tên khách hàng',
            'customer_phone' => 'Số điện thoại khách hàng', 
            'customer_address' => 'Địa chỉ khách hàng',
            'appointment_date' => 'Ngày hẹn',
            'appointment_time' => 'Giờ hẹn',
            'notes' => 'Ghi chú',
            'company_name' => 'Tên công ty'
        ]),
        'email_status' => 1,
        'sms_status' => 1
    ]
);

echo "✓ APPOINTMENT_COMPLETED template created\n";

echo "\n🎉 All appointment notification templates created successfully!\n";
echo "You can now manage them in Admin Panel > Notification > Templates\n"; 