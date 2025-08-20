<?php
// Fix notification cho production
echo "=== 🔧 FIX PRODUCTION NOTIFICATION ===\n\n";

try {
    // Production database connection
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_production;charset=utf8mb4", "treview_user", "StrongPassword123!");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to production database\n\n";
    
    // 1. Kiểm tra bảng notification_templates
    echo "1. 🗄️ KIỂM TRA NOTIFICATION_TEMPLATES:\n";
    echo "======================================\n";
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'notification_templates'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Bảng notification_templates: EXISTS\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM notification_templates");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "📋 Tổng số templates: " . $result['count'] . "\n";
    } else {
        echo "❌ Bảng notification_templates: NOT FOUND\n";
        echo "   Cần tạo bảng này trước!\n";
        exit;
    }
    
    // 2. Kiểm tra NEW_APPOINTMENT template
    echo "\n2. 🔍 KIỂM TRA NEW_APPOINTMENT TEMPLATE:\n";
    echo "=======================================\n";
    
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE act = 'NEW_APPOINTMENT'");
    $stmt->execute();
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "✅ NEW_APPOINTMENT template: FOUND\n";
        echo "📋 Current status:\n";
        echo "   - ID: " . $template['id'] . "\n";
        echo "   - Email Status: " . ($template['email_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        echo "   - SMS Status: " . ($template['sms_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        if (isset($template['push_status'])) {
            echo "   - Push Status: " . ($template['push_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        }
        
        // Update template to enable all notifications
        echo "\n🔧 UPDATING TEMPLATE:\n";
        $updateSql = "UPDATE notification_templates SET 
                      email_status = 1, 
                      sms_status = 1, 
                      push_status = 1 
                      WHERE act = 'NEW_APPOINTMENT'";
        
        $result = $pdo->exec($updateSql);
        if ($result !== false) {
            echo "✅ Template updated successfully!\n";
        } else {
            echo "❌ Failed to update template\n";
        }
        
    } else {
        echo "❌ NEW_APPOINTMENT template: NOT FOUND\n";
        
        // Create NEW_APPOINTMENT template
        echo "\n🔧 CREATING NEW_APPOINTMENT TEMPLATE:\n";
        echo "=====================================\n";
        
        $insertSql = "INSERT INTO notification_templates (
            act, name, subject, email_body, sms_body, 
            email_status, sms_status, push_status, 
            created_at, updated_at
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
            1, 1, 1, NOW(), NOW()
        )";
        
        $result = $pdo->exec($insertSql);
        if ($result) {
            echo "✅ NEW_APPOINTMENT template created successfully!\n";
        } else {
            echo "❌ Failed to create NEW_APPOINTMENT template\n";
        }
    }
    
    // 3. Kiểm tra email configuration
    echo "\n3. 📧 KIỂM TRA EMAIL CONFIG:\n";
    echo "============================\n";
    
    $stmt = $pdo->query("SELECT mail_config, email_from FROM general_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        echo "✅ Email config found:\n";
        echo "   - email_from: " . $settings['email_from'] . "\n";
        
        $mailConfig = json_decode($settings['mail_config'], true);
        if ($mailConfig) {
            echo "   - host: " . ($mailConfig['host'] ?? 'not set') . "\n";
            echo "   - port: " . ($mailConfig['port'] ?? 'not set') . "\n";
            echo "   - username: " . ($mailConfig['username'] ?? 'not set') . "\n";
            echo "   - encryption: " . ($mailConfig['enc'] ?? 'not set') . "\n";
            
            // Update với ElasticEmail nếu cần
            if ($mailConfig['host'] !== 'smtp.elasticemail.com') {
                echo "\n🔧 UPDATING EMAIL CONFIG:\n";
                $newMailConfig = [
                    'name' => 'elasticemail',
                    'host' => 'smtp.elasticemail.com',
                    'port' => 2525,
                    'username' => 'hotro@doitay.vn',
                    'password' => 'ED430DB8FF4A17CCE00E2B4D10D45161E9FD',
                    'enc' => 'tls'
                ];
                
                $updateEmailSql = "UPDATE general_settings SET 
                                   mail_config = ?, 
                                   email_from = 'hotro@doitay.vn' 
                                   WHERE id = 1";
                
                $stmt = $pdo->prepare($updateEmailSql);
                $result = $stmt->execute([json_encode($newMailConfig)]);
                
                if ($result) {
                    echo "✅ Email config updated to ElasticEmail\n";
                } else {
                    echo "❌ Failed to update email config\n";
                }
            } else {
                echo "✅ Email config already correct (ElasticEmail)\n";
            }
        }
    } else {
        echo "❌ No email config found\n";
    }
    
    // 4. Kiểm tra các bảng cần thiết
    echo "\n4. 🗄️ KIỂM TRA CÁC BẢNG CẦN THIẾT:\n";
    echo "===================================\n";
    
    $requiredTables = [
        'notifications' => 'Laravel notifications',
        'device_tokens' => 'Push notification tokens',
        'appointments' => 'Appointments',
        'users' => 'Users',
        'companies' => 'Companies'
    ];
    
    foreach ($requiredTables as $table => $description) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        echo ($exists ? "✅" : "❌") . " $table ($description): " . ($exists ? "EXISTS" : "NOT FOUND") . "\n";
        
        if ($exists && in_array($table, ['appointments', 'users'])) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "   - Records: " . $result['count'] . "\n";
        }
    }
    
    // 5. Tạo SQL script cho missing tables
    echo "\n5. 📋 SQL SCRIPTS FOR MISSING TABLES:\n";
    echo "=====================================\n";
    
    // Check and create notifications table
    $stmt = $pdo->query("SHOW TABLES LIKE 'notifications'");
    if ($stmt->rowCount() == 0) {
        echo "🔧 SQL to create notifications table:\n";
        echo "CREATE TABLE notifications (\n";
        echo "    id CHAR(36) NOT NULL PRIMARY KEY,\n";
        echo "    type VARCHAR(255) NOT NULL,\n";
        echo "    notifiable_type VARCHAR(255) NOT NULL,\n";
        echo "    notifiable_id BIGINT UNSIGNED NOT NULL,\n";
        echo "    data TEXT NOT NULL,\n";
        echo "    read_at TIMESTAMP NULL,\n";
        echo "    created_at TIMESTAMP NULL,\n";
        echo "    updated_at TIMESTAMP NULL,\n";
        echo "    INDEX idx_notifiable (notifiable_type, notifiable_id)\n";
        echo ");\n\n";
    }
    
    // Check and create device_tokens table
    $stmt = $pdo->query("SHOW TABLES LIKE 'device_tokens'");
    if ($stmt->rowCount() == 0) {
        echo "🔧 SQL to create device_tokens table:\n";
        echo "CREATE TABLE device_tokens (\n";
        echo "    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,\n";
        echo "    user_id INT UNSIGNED NOT NULL DEFAULT 0,\n";
        echo "    is_app TINYINT(1) NOT NULL DEFAULT 0,\n";
        echo "    machine TEXT NULL,\n";
        echo "    token TEXT NULL,\n";
        echo "    created_at TIMESTAMP NULL,\n";
        echo "    updated_at TIMESTAMP NULL,\n";
        echo "    INDEX idx_user_id (user_id)\n";
        echo ");\n\n";
    }
    
    // 6. Test email với production config
    echo "\n6. 🧪 TEST EMAIL PRODUCTION:\n";
    echo "============================\n";
    
    echo "📤 Production email test script:\n";
    echo "<?php\n";
    echo "require_once 'vendor/autoload.php';\n";
    echo "use PHPMailer\\PHPMailer\\PHPMailer;\n";
    echo "\n";
    echo "\$mail = new PHPMailer(true);\n";
    echo "\$mail->isSMTP();\n";
    echo "\$mail->Host = 'smtp.elasticemail.com';\n";
    echo "\$mail->SMTPAuth = true;\n";
    echo "\$mail->Username = 'hotro@doitay.vn';\n";
    echo "\$mail->Password = 'ED430DB8FF4A17CCE00E2B4D10D45161E9FD';\n";
    echo "\$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;\n";
    echo "\$mail->Port = 2525;\n";
    echo "\$mail->setFrom('hotro@doitay.vn', 'DoiTay Support');\n";
    echo "\$mail->addAddress('nguyentung0910@gmail.com');\n";
    echo "\$mail->Subject = 'Production Notification Test';\n";
    echo "\$mail->Body = 'Test notification from production';\n";
    echo "\$mail->send();\n";
    echo "?>\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== 🚀 FIX COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Run this script on production\n";
echo "2. Create missing tables if needed\n";
echo "3. Test appointment creation\n";
echo "4. Check email delivery\n";
echo "5. Monitor notification logs\n";
?> 
 