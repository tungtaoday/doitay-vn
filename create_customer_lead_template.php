<?php

echo "=== CREATING CUSTOMER LEAD NOTIFICATION TEMPLATE ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Template for customer lead confirmation
    $template = [
        'act' => 'LEAD_CREATED_CONFIRMATION',
        'name' => 'Lead Created Confirmation',
        'subject' => '✅ Lead đã được tạo thành công - {{lead_title}}',
        'email_body' => '
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #28a745;">✅ Lead đã được tạo thành công!</h2>
    
    <p>Xin chào <strong>{{customer_name}}</strong>!</p>
    
    <div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h3 style="margin: 0 0 10px 0; color: #155724;">🎉 Lead của bạn đã được đăng!</h3>
        <p style="margin: 0; color: #155724;">
            Chúng tôi đã gửi thông tin công việc của bạn tới <strong>{{contractors_count}} thợ</strong> phù hợp trong khu vực.
        </p>
    </div>
    
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4>📋 Thông tin lead của bạn:</h4>
        <p><strong>Tiêu đề:</strong> {{lead_title}}</p>
        <p><strong>Địa điểm:</strong> {{lead_location}}</p>
        <p><strong>Ngân sách:</strong> {{lead_budget}}</p>
        <p><strong>Mức độ:</strong> {{lead_urgency}}</p>
        <p><strong>Ngày tạo:</strong> {{current_time}}</p>
        <p><strong>Mã lead:</strong> #{{lead_id}}</p>
    </div>
    
    <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4 style="color: #856404; margin: 0 0 10px 0;">⏰ Điều gì sẽ xảy ra tiếp theo?</h4>
        <ol style="margin: 0; padding-left: 20px; color: #856404;">
            <li>Các thợ phù hợp sẽ xem thông tin lead của bạn</li>
            <li>Thợ quan tâm sẽ liên hệ trực tiếp với bạn trong 24-48h</li>
            <li>Bạn sẽ nhận được cuộc gọi/tin nhắn từ các thợ</li>
            <li>Chọn thợ phù hợp nhất và thống nhất công việc</li>
        </ol>
    </div>
    
    <div style="background: #e3f2fd; border: 1px solid #90caf9; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4 style="color: #1565c0; margin: 0 0 10px 0;">💡 Mẹo để nhận được báo giá tốt:</h4>
        <ul style="margin: 0; padding-left: 20px; color: #1565c0;">
            <li>Trả lời điện thoại từ các thợ một cách lịch sự</li>
            <li>Cung cấp thông tin chi tiết về công việc</li>
            <li>So sánh ít nhất 2-3 báo giá trước khi quyết định</li>
            <li>Hỏi về kinh nghiệm và xem ảnh công việc trước đó</li>
        </ul>
    </div>
    
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{lead_url}}" style="background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">
            🔗 Xem chi tiết lead
        </a>
    </div>
    
    <div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">
        <h4 style="color: #721c24; margin: 0 0 10px 0;">⚠️ Lưu ý quan trọng:</h4>
        <ul style="margin: 0; padding-left: 20px; color: #721c24;">
            <li>Không đưa tiền trước khi công việc hoàn thành</li>
            <li>Yêu cầu xem giấy tờ tuỳ thân của thợ</li>
            <li>Thống nhất rõ giá cả trước khi bắt đầu</li>
            <li>Liên hệ {{support_phone}} nếu có vấn đề</li>
        </ul>
    </div>
    
    <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi! 🙏</p>
    
    <p style="color: #6c757d; font-size: 12px; margin-top: 20px;">
        📧 Email được gửi từ {{site_name}}<br>
        🕒 Thời gian: {{current_time}}<br>
        📱 Hotline hỗ trợ: {{support_phone}}
    </p>
</div>',
        'sms_body' => 'Lead "{{lead_title}}" đã được tạo thành công! {{contractors_count}} thợ sẽ liên hệ bạn trong 24-48h. Mã: #{{lead_id}}',
        'email_status' => 1,
        'sms_status' => 1,
        'push_status' => 1
    ];
    
    echo "1. Creating LEAD_CREATED_CONFIRMATION template...\n";
    
    // Check if template already exists
    $stmt = $pdo->prepare("SELECT id FROM notification_templates WHERE act = ?");
    $stmt->execute([$template['act']]);
    
    if ($stmt->rowCount() > 0) {
        echo "⚠️ Template already exists, updating...\n";
        $stmt = $pdo->prepare("
            UPDATE notification_templates 
            SET name = ?, subject = ?, email_body = ?, sms_body = ?, 
                email_status = ?, sms_status = ?, push_status = ?, updated_at = NOW()
            WHERE act = ?
        ");
        $stmt->execute([
            $template['name'],
            $template['subject'],
            $template['email_body'],
            $template['sms_body'],
            $template['email_status'],
            $template['sms_status'],
            $template['push_status'],
            $template['act']
        ]);
        echo "✅ Updated template: {$template['name']}\n";
    } else {
        echo "Creating new template...\n";
        $stmt = $pdo->prepare("
            INSERT INTO notification_templates 
            (act, name, subject, email_body, sms_body, email_status, sms_status, push_status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
            $template['act'],
            $template['name'],
            $template['subject'],
            $template['email_body'],
            $template['sms_body'],
            $template['email_status'],
            $template['sms_status'],
            $template['push_status']
        ]);
        echo "✅ Created template: {$template['name']}\n";
    }
    
    // 2. Verify template
    echo "\n2. Verifying template...\n";
    $stmt = $pdo->prepare("SELECT act, name, email_status FROM notification_templates WHERE act = ?");
    $stmt->execute([$template['act']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "✅ Template verified:\n";
        echo "   ACT: {$result['act']}\n";
        echo "   Name: {$result['name']}\n";
        echo "   Email status: " . ($result['email_status'] ? 'ENABLED' : 'DISABLED') . "\n";
    } else {
        echo "❌ Template verification failed\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEMPLATE CREATION COMPLETED ===\n";
echo "Next step: Add notify() call for customers in CustomerLeadController\n"; 