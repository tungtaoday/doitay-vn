<?php

echo "=== TESTING EMAIL TEMPLATE WITH PROPER SHORTCODES ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get email template
    $stmt = $pdo->query("SELECT * FROM notification_templates WHERE act LIKE '%appointment%' OR name LIKE '%appointment%' LIMIT 1");
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$template) {
        echo "❌ No appointment template found\n";
        exit;
    }
    
    echo "📧 Template found: {$template['name']} (ID: {$template['id']})\n\n";
    
    // Test shortcodes
    $testShortcodes = [
        'user_name' => 'Nguyễn Văn A',
        'user_email' => 'nguyenvana@example.com',
        'appointment_id' => '123',
        'appointment_date' => '17/07/2025',
        'appointment_time' => '16:00',
        'company_name' => 'Nguyễn Hoàng Tùng',
        'company_phone' => '0123456789',
        'appointment_address' => '123 Đường ABC, Quận XYZ',
        'site_url' => 'http://localhost',
        'current_year' => date('Y')
    ];
    
    // Replace shortcodes in email body
    $emailBody = $template['email_body'];
    $subject = $template['subject'];
    
    foreach ($testShortcodes as $key => $value) {
        $emailBody = str_replace('{{'.$key.'}}', $value, $emailBody);
        $subject = str_replace('{{'.$key.'}}', $value, $subject);
    }
    
    echo "📝 Subject: $subject\n\n";
    
    // Save processed email to file for viewing
    $htmlContent = "<!DOCTYPE html>
<html>
<head>
    <title>Email Template Test</title>
    <meta charset='UTF-8'>
</head>
<body>
    <h1>Subject: $subject</h1>
    <hr>
    $emailBody
</body>
</html>";
    
    file_put_contents('email_preview.html', $htmlContent);
    echo "📧 Email preview saved to: email_preview.html\n";
    
    // Check for remaining unreplaced shortcodes
    preg_match_all('/{{([^}]+)}}/', $emailBody, $matches);
    if (!empty($matches[1])) {
        echo "\n⚠️ Unreplaced shortcodes found:\n";
        foreach (array_unique($matches[1]) as $shortcode) {
            echo "   - {{$shortcode}}\n";
        }
    } else {
        echo "\n✅ All shortcodes replaced successfully!\n";
    }
    
    echo "\n📋 To view email: Open email_preview.html in browser\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 