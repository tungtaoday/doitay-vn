<?php

echo "=== CHECKING NOTIFICATION TEMPLATES ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. List all templates
    echo "1. All notification templates:\n";
    $stmt = $pdo->query("SELECT act, name, email_status, LENGTH(email_body) as body_length FROM notification_templates");
    $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($templates as $template) {
        echo "   - {$template['act']}: {$template['name']} (Email: " . ($template['email_status'] ? 'ON' : 'OFF') . ", Body: {$template['body_length']} chars)\n";
    }
    
    // 2. Look for LEAD related templates
    echo "\n2. LEAD related templates:\n";
    $stmt = $pdo->prepare("SELECT * FROM notification_templates WHERE act LIKE '%LEAD%' OR name LIKE '%lead%'");
    $stmt->execute();
    $leadTemplates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($leadTemplates)) {
        echo "❌ No LEAD templates found!\n";
        
        // Try different search patterns
        $patterns = ['%new%', '%notification%', '%contractor%'];
        foreach ($patterns as $pattern) {
            $stmt = $pdo->prepare("SELECT act, name FROM notification_templates WHERE act LIKE ? OR name LIKE ?");
            $stmt->execute([$pattern, $pattern]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($results)) {
                echo "\nTemplates matching '$pattern':\n";
                foreach ($results as $result) {
                    echo "   - {$result['act']}: {$result['name']}\n";
                }
            }
        }
    } else {
        foreach ($leadTemplates as $template) {
            echo "✅ Found: {$template['act']}\n";
            echo "   Name: {$template['name']}\n";
            echo "   Email status: " . ($template['email_status'] ? 'ENABLED' : 'DISABLED') . "\n";
            echo "   Body length: " . strlen($template['email_body']) . " characters\n";
            
            if (strlen($template['email_body']) > 5000) {
                echo "   ⚠️  Large email body - possible timeout cause\n";
            }
        }
    }
    
    // 3. Check if notification system is using correct template key
    echo "\n3. Checking common template keys:\n";
    $commonKeys = ['NEW_LEAD_NOTIFICATION', 'LEAD_NOTIFICATION', 'CONTRACTOR_NOTIFICATION', 'NEW_LEAD'];
    
    foreach ($commonKeys as $key) {
        $stmt = $pdo->prepare("SELECT count(*) as count FROM notification_templates WHERE act = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            echo "✅ Template key '$key' exists\n";
        } else {
            echo "❌ Template key '$key' not found\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== CHECK COMPLETED ===\n"; 