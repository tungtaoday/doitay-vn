<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CHECKING EMAIL TEMPLATES ===\n\n";
    
    // 1. Kiểm tra bảng notification_templates
    echo "1. Checking notification_templates table...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'notification_templates'");
    if ($stmt->rowCount() > 0) {
        echo "✅ notification_templates table exists\n";
        
        // Lấy tất cả templates
        $stmt = $pdo->query("SELECT name, subject, email_status, email_body FROM notification_templates ORDER BY name");
        $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "📧 Found " . count($templates) . " email templates:\n";
        foreach ($templates as $template) {
            $status = $template['email_status'] ? '✅' : '❌';
            echo "   {$status} {$template['name']}\n";
            echo "      Subject: " . substr($template['subject'], 0, 50) . "...\n";
            if (!$template['email_status']) {
                echo "      ⚠️ Email is DISABLED for this template\n";
            }
            echo "\n";
        }
        
        // Tìm templates liên quan đến contractor
        echo "🔍 Contractor-related templates:\n";
        $stmt = $pdo->query("
            SELECT name, subject, email_status, email_body 
            FROM notification_templates 
            WHERE name LIKE '%contractor%' OR name LIKE '%lead%' OR name LIKE '%selected%'
            ORDER BY name
        ");
        $contractorTemplates = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($contractorTemplates) {
            foreach ($contractorTemplates as $template) {
                $status = $template['email_status'] ? '✅ Enabled' : '❌ Disabled';
                echo "   📄 {$template['name']} - {$status}\n";
                echo "      Subject: {$template['subject']}\n";
                echo "      Body preview: " . substr(strip_tags($template['email_body']), 0, 100) . "...\n\n";
            }
        } else {
            echo "   ❌ No contractor-related templates found!\n";
            echo "   🔧 Need to create templates for:\n";
            echo "      - CONTRACTOR_REPORTS_SELECTED\n";
            echo "      - CUSTOMER_CONFIRMED_SELECTION\n";
            echo "      - NEW_LEAD_NOTIFICATION\n\n";
        }
        
    } else {
        echo "❌ notification_templates table not found!\n";
        
        // Check for email_templates table
        $stmt = $pdo->query("SHOW TABLES LIKE 'email_templates'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Found email_templates table instead\n";
            $stmt = $pdo->query("SELECT * FROM email_templates LIMIT 5");
            $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($templates as $template) {
                echo "   📄 Template found: " . json_encode(array_keys($template)) . "\n";
            }
        }
    }
    
    // 2. Kiểm tra general settings email status
    echo "\n2. Checking email notification status...\n";
    $stmt = $pdo->query("SELECT en, email_from, site_name FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($settings) {
        echo "📧 Email notifications: " . ($settings['en'] ? '✅ ENABLED' : '❌ DISABLED') . "\n";
        echo "📧 Email from: {$settings['email_from']}\n";
        echo "📧 Site name: {$settings['site_name']}\n";
        
        if (!$settings['en']) {
            echo "\n⚠️ WARNING: Email notifications are DISABLED in general settings!\n";
            echo "   Fix: Update general_settings SET en = 1\n";
        }
    }
    
    // 3. Kiểm tra notification logs gần đây
    echo "\n3. Checking recent notification logs...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'notification_logs'");
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->query("
            SELECT * FROM notification_logs 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($logs) {
            echo "📝 Recent notification logs (last 24h):\n";
            foreach ($logs as $log) {
                echo "   📨 " . ($log['sent_via'] ?? 'unknown') . " - " . ($log['template_name'] ?? 'no template') . "\n";
                echo "      To: " . ($log['sent_to'] ?? 'unknown') . " at {$log['created_at']}\n";
            }
        } else {
            echo "❌ No recent notification logs\n";
        }
    } else {
        echo "❌ notification_logs table not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 