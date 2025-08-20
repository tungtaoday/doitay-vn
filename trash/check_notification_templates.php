<?php
// Kiểm tra notification templates trong database
echo "=== 🔍 CHECK NOTIFICATION TEMPLATES ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Kiểm tra bảng notification_templates
    echo "1. 🗄️ KIỂM TRA BẢNG NOTIFICATION_TEMPLATES:\n";
    echo "============================================\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'notification_templates'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Bảng notification_templates: EXISTS\n";
        
        // Kiểm tra cấu trúc bảng
        $stmt = $pdo->query("DESCRIBE notification_templates");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "📊 Cấu trúc bảng:\n";
        foreach ($columns as $column) {
            echo "   - {$column['Field']} ({$column['Type']})\n";
        }
        
        // Đếm số templates
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM notification_templates");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📋 Tổng số templates: " . $result['count'] . "\n";
        
    } else {
        echo "❌ Bảng notification_templates: NOT FOUND\n";
    }
    
    // 2. Tìm NEW_APPOINTMENT template
    echo "\n2. 🔍 TÌM NEW_APPOINTMENT TEMPLATE:\n";
    echo "===================================\n";
    
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE act = 'NEW_APPOINTMENT'");
    $stmt->execute();
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "✅ NEW_APPOINTMENT template: FOUND\n";
        echo "📋 Template info:\n";
        echo "   - ID: " . $template['id'] . "\n";
        echo "   - Name: " . $template['name'] . "\n";
        echo "   - Act: " . $template['act'] . "\n";
        echo "   - Email Status: " . ($template['email_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        echo "   - SMS Status: " . ($template['sms_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        if (isset($template['push_status'])) {
            echo "   - Push Status: " . ($template['push_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        }
        echo "   - Subject: " . ($template['subject'] ?? $template['subj'] ?? 'No subject') . "\n";
        echo "   - Email Body: " . (strlen($template['email_body'] ?? '') > 0 ? 'SET' : 'EMPTY') . "\n";
        
    } else {
        echo "❌ NEW_APPOINTMENT template: NOT FOUND\n";
        
        // Tạo NEW_APPOINTMENT template
        echo "\n🔧 CREATING NEW_APPOINTMENT TEMPLATE:\n";
        echo "=====================================\n";
        
        $insertSql = "INSERT INTO notification_templates (
            act, name, subject, email_body, sms_body, 
            email_status, sms_status, push_status, 
            flow_type, priority, created_at, updated_at
        ) VALUES (
            'NEW_APPOINTMENT',
            'New Appointment Notification',
            '🗓️ Bạn có lịch hẹn mới - {{appointment_date}}',
            '<html><body>
                <h2>🗓️ Lịch hẹn mới</h2>
                <p>Xin chào {{user_name}},</p>
                <p>Bạn vừa nhận được một lịch hẹn mới.</p>
                <hr>
                <h3>📋 Thông tin lịch hẹn:</h3>
                <p><strong>Mã lịch hẹn:</strong> {{appointment_id}}</p>
                <p><strong>Ngày hẹn:</strong> {{appointment_date}}</p>
                <p><strong>Giờ hẹn:</strong> {{appointment_time}}</p>
                <p><strong>Công ty:</strong> {{company_name}}</p>
                <p><strong>Địa chỉ:</strong> {{appointment_address}}</p>
                <p><strong>Ghi chú:</strong> {{notes}}</p>
                <hr>
                <p>Cảm ơn bạn đã sử dụng dịch vụ DoiTay.vn!</p>
                <p><a href=\"{{site_url}}\">Truy cập website</a></p>
            </body></html>',
            'Lịch hẹn mới: {{appointment_date}} - {{appointment_time}}. Chi tiết: {{notes}}',
            1, 1, 1, 'auto', 'high', NOW(), NOW()
        )";
        
        $result = $pdo->exec($insertSql);
        if ($result) {
            echo "✅ NEW_APPOINTMENT template created successfully!\n";
        } else {
            echo "❌ Failed to create NEW_APPOINTMENT template\n";
        }
    }
    
    // 3. Liệt kê tất cả templates
    echo "\n3. 📋 TẤT CẢ NOTIFICATION TEMPLATES:\n";
    echo "====================================\n";
    
    $stmt = $pdo->query("SELECT id, act, name, email_status, sms_status FROM notification_templates ORDER BY act");
    $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($templates as $tmpl) {
        $emailStatus = $tmpl['email_status'] ? '✅' : '❌';
        $smsStatus = $tmpl['sms_status'] ? '✅' : '❌';
        echo "   - {$tmpl['act']}: {$tmpl['name']} (Email: $emailStatus, SMS: $smsStatus)\n";
    }
    
    // 4. Kiểm tra general_settings email config
    echo "\n4. 📧 KIỂM TRA EMAIL CONFIG:\n";
    echo "============================\n";
    
    $stmt = $pdo->query("SELECT mail_config, email_from FROM general_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        echo "✅ General settings email config:\n";
        echo "   - email_from: " . $settings['email_from'] . "\n";
        
        $mailConfig = json_decode($settings['mail_config'], true);
        if ($mailConfig) {
            echo "   - host: " . ($mailConfig['host'] ?? 'not set') . "\n";
            echo "   - port: " . ($mailConfig['port'] ?? 'not set') . "\n";
            echo "   - username: " . ($mailConfig['username'] ?? 'not set') . "\n";
            echo "   - encryption: " . ($mailConfig['enc'] ?? 'not set') . "\n";
        }
    } else {
        echo "❌ No general settings found\n";
    }
    
    // 5. Test notification manually
    echo "\n5. 🧪 TEST NOTIFICATION MANUALLY:\n";
    echo "==================================\n";
    
    // Simulate notification call
    echo "📤 Simulating notification call...\n";
    echo "notify(\$user, 'NEW_APPOINTMENT', [\n";
    echo "    'user_name' => 'Tung Test',\n";
    echo "    'appointment_id' => '123',\n";
    echo "    'appointment_date' => '" . date('d/m/Y') . "',\n";
    echo "    'appointment_time' => '10:00:00',\n";
    echo "    'company_name' => 'Test Company',\n";
    echo "    'appointment_address' => 'Test Address',\n";
    echo "    'notes' => 'Test appointment'\n";
    echo "]);\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== 🚀 CHECK COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Ensure NEW_APPOINTMENT template exists and is enabled\n";
echo "2. Check email configuration\n";
echo "3. Test notification system\n";
echo "4. Monitor email delivery\n";
?> 
 