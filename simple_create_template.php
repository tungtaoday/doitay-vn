<?php

echo "=== CREATING CUSTOMER TEMPLATE ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $act = 'LEAD_CREATED_CONFIRMATION';
    $name = 'Lead Created Confirmation';
    $subject = 'Lead đã được tạo thành công - {{lead_title}}';
    $body = '<h2>Lead đã được tạo thành công!</h2><p>Xin chào {{customer_name}}!</p><p>Lead "{{lead_title}}" đã được gửi tới {{contractors_count}} thợ phù hợp.</p><p>Chi tiết:</p><ul><li>Địa điểm: {{lead_location}}</li><li>Ngân sách: {{lead_budget}}</li><li>Mã lead: #{{lead_id}}</li></ul><p>Các thợ sẽ liên hệ bạn trong 24-48h!</p>';
    
    // Check existing
    $stmt = $pdo->prepare("SELECT id FROM notification_templates WHERE act = ?");
    $stmt->execute([$act]);
    
    if ($stmt->rowCount() > 0) {
        echo "Template exists, updating...\n";
        $stmt = $pdo->prepare("UPDATE notification_templates SET name=?, subject=?, email_body=?, email_status=1 WHERE act=?");
        $stmt->execute([$name, $subject, $body, $act]);
    } else {
        echo "Creating new template...\n";
        $stmt = $pdo->prepare("INSERT INTO notification_templates (act, name, subject, email_body, email_status) VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([$act, $name, $subject, $body]);
    }
    
    echo "✅ Template created: $act\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 