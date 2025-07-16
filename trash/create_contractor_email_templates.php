<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CREATING CONTRACTOR EMAIL TEMPLATES ===\n\n";
    
    // Templates to create
    $templates = [
        [
            'name' => 'CONTRACTOR_REPORTS_SELECTED',
            'subject' => '🎯 Thợ {{contractor_name}} báo bạn đã chọn họ',
            'email_body' => '
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #007bff;">🎯 Xác nhận chọn thợ</h2>
    
    <p>Xin chào <strong>{{customer_name}}</strong>!</p>
    
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3 style="margin: 0 0 10px 0; color: #856404;">📞 Thợ báo cáo được chọn</h3>
        <p style="margin: 0;">Thợ <strong>{{contractor_name}}</strong> báo rằng bạn đã chọn họ cho công việc này.</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4>📋 Chi tiết công việc:</h4>
        <p><strong>Tiêu đề:</strong> {{lead_title}}</p>
        <p><strong>Địa điểm:</strong> {{lead_location}}</p>
        <p><strong>Ngân sách:</strong> {{lead_budget}}</p>
        <p><strong>Ghi chú từ thợ:</strong> {{report_notes}}</p>
    </div>
    
    <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4 style="color: #155724; margin: 0 0 10px 0;">✅ Vui lòng xác nhận:</h4>
        <p style="margin: 0; color: #155724;">
            Nếu bạn thực sự đã chọn thợ này, vui lòng vào hệ thống để xác nhận. 
            Nếu chưa, bạn có thể từ chối để thợ biết và tiếp tục liên hệ.
        </p>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{confirm_url}}" style="background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
            🔗 Xác nhận ngay
        </a>
    </div>
    
    <p style="color: #6c757d; font-size: 12px; margin-top: 20px;">
        📧 Email được gửi từ {{site_name}}<br>
        🕒 Thời gian: {{current_time}}
    </p>
</div>',
            'sms_body' => 'Thợ {{contractor_name}} báo bạn đã chọn họ cho "{{lead_title}}". Vui lòng vào {{site_name}} để xác nhận.',
            'email_status' => 1,
            'sms_status' => 1
        ],
        
        [
            'name' => 'CUSTOMER_CONFIRMED_SELECTION',
            'subject' => '🎉 Chúc mừng! Khách hàng đã xác nhận chọn bạn',
            'email_body' => '
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #28a745;">🎉 Chúc mừng!</h2>
    
    <p>Xin chào <strong>{{contractor_name}}</strong>!</p>
    
    <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3 style="margin: 0 0 10px 0; color: #155724;">✅ Khách hàng đã xác nhận chọn bạn!</h3>
        <p style="margin: 0; color: #155724;">
            Khách hàng <strong>{{customer_name}}</strong> đã xác nhận chọn bạn làm thợ cho công việc này.
        </p>
    </div>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4>📋 Chi tiết công việc:</h4>
        <p><strong>Tiêu đề:</strong> {{lead_title}}</p>
        <p><strong>Địa điểm:</strong> {{lead_location}}</p>
        <p><strong>Ngân sách:</strong> {{lead_budget}}</p>
        <p><strong>Ghi chú khách hàng:</strong> {{confirmation_notes}}</p>
    </div>
    
    <div style="background: #e3f2fd; border: 1px solid #90caf9; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4 style="color: #1565c0; margin: 0 0 10px 0;">📞 Thông tin liên hệ khách hàng:</h4>
        <p style="margin: 0; color: #1565c0;">
            <strong>Tên:</strong> {{customer_name}}<br>
            <strong>Điện thoại:</strong> {{customer_phone}}<br>
            <strong>Email:</strong> {{customer_email}}
        </p>
    </div>
    
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4 style="color: #856404; margin: 0 0 10px 0;">🔥 Bước tiếp theo:</h4>
        <ol style="margin: 0; padding-left: 20px; color: #856404;">
            <li>Liên hệ khách hàng để thống nhất chi tiết</li>
            <li>Thực hiện công việc chất lượng cao</li>
            <li>Yêu cầu khách hàng đánh giá sau khi hoàn thành</li>
        </ol>
    </div>
    
    <p>Chúc bạn thành công! 🚀</p>
    
    <p style="color: #6c757d; font-size: 12px; margin-top: 20px;">
        📧 Email được gửi từ {{site_name}}<br>
        🕒 Thời gian: {{current_time}}
    </p>
</div>',
            'sms_body' => 'Chúc mừng! Khách hàng {{customer_name}} đã chọn bạn cho "{{lead_title}}". Liên hệ: {{customer_phone}}',
            'email_status' => 1,
            'sms_status' => 1
        ],
        
        [
            'name' => 'NEW_LEAD_NOTIFICATION',
            'subject' => '🎯 Lead ưu tiên dành cho bạn - {{lead_title}}',
            'email_body' => '
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #007bff;">🎯 Lead Ưu Tiên</h2>
    
    <p>Xin chào <strong>{{contractor_name}}</strong>!</p>
    
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3 style="margin: 0 0 10px 0; color: #856404;">🔥 Bạn được chọn trong TOP 3!</h3>
        <p style="margin: 0;">Dựa trên rating cao và vị trí phù hợp, bạn có cơ hội độc quyền với lead này trong 24h.</p>
    </div>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4>📋 Chi tiết lead:</h4>
        <p><strong>Tiêu đề:</strong> {{lead_title}}</p>
        <p><strong>Ngân sách:</strong> {{lead_budget}}</p>
        <p><strong>Địa điểm:</strong> {{lead_location}}</p>
        <p><strong>Danh mục:</strong> {{lead_category}}</p>
        <p><strong>Mức độ:</strong> {{lead_urgency}}</p>
        <p><strong>Điểm ưu tiên:</strong> {{priority_score}}/5.0</p>
    </div>
    
    <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4 style="color: #155724; margin: 0 0 10px 0;">✅ Hành động tiếp theo:</h4>
        <ol style="margin: 0; padding-left: 20px; color: #155724;">
            <li>Đăng nhập hệ thống để xem chi tiết</li>
            <li>Mua lead với giá {{lead_price}}₫</li>
            <li>Liên hệ khách hàng trong 24h</li>
            <li>Báo cáo khi được khách chọn</li>
        </ol>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{lead_url}}" style="background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
            🔗 Xem lead ngay
        </a>
    </div>
    
    <p style="color: #6c757d; font-size: 12px; margin-top: 20px;">
        📧 Email được gửi từ {{site_name}}<br>
        🕒 Thời gian: {{current_time}}<br>
        ⏰ Lead hết hạn: {{expires_at}}
    </p>
</div>',
            'sms_body' => 'Lead mới cho bạn: "{{lead_title}}" tại {{lead_location}}. Ngân sách {{lead_budget}}. Xem ngay tại {{site_name}}',
            'email_status' => 1,
            'sms_status' => 1
        ]
    ];
    
    foreach ($templates as $template) {
        echo "Creating template: {$template['name']}\n";
        
        // Check if template already exists
        $stmt = $pdo->prepare("SELECT id FROM notification_templates WHERE name = ?");
        $stmt->execute([$template['name']]);
        
        if ($stmt->rowCount() > 0) {
            echo "⚠️ Template already exists, updating...\n";
            $stmt = $pdo->prepare("
                UPDATE notification_templates 
                SET subject = ?, email_body = ?, sms_body = ?, email_status = ?, sms_status = ?, updated_at = NOW()
                WHERE name = ?
            ");
            $stmt->execute([
                $template['subject'],
                $template['email_body'],
                $template['sms_body'],
                $template['email_status'],
                $template['sms_status'],
                $template['name']
            ]);
            echo "✅ Updated template: {$template['name']}\n\n";
        } else {
            echo "➕ Creating new template...\n";
            $stmt = $pdo->prepare("
                INSERT INTO notification_templates (name, subject, email_body, sms_body, email_status, sms_status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt->execute([
                $template['name'],
                $template['subject'],
                $template['email_body'],
                $template['sms_body'],
                $template['email_status'],
                $template['sms_status']
            ]);
            echo "✅ Created template: {$template['name']}\n\n";
        }
    }
    
    echo "🎉 ALL EMAIL TEMPLATES CREATED SUCCESSFULLY!\n\n";
    
    echo "📧 Templates created:\n";
    echo "1. ✅ CONTRACTOR_REPORTS_SELECTED - Thông báo khách hàng khi thợ báo được chọn\n";
    echo "2. ✅ CUSTOMER_CONFIRMED_SELECTION - Thông báo thợ khi khách xác nhận\n";
    echo "3. ✅ NEW_LEAD_NOTIFICATION - Thông báo lead mới cho thợ\n\n";
    
    echo "🚀 NOW EMAIL NOTIFICATIONS WILL WORK!\n";
    echo "Test the contractor self-report flow again.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 
 