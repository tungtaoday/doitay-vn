<?php

echo "=== FIXING NEW_LEAD_NOTIFICATION TEMPLATE ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Check current state
    echo "1. Current NEW_LEAD_NOTIFICATION template:\n";
    $stmt = $pdo->prepare("SELECT id, act, name, email_status FROM notification_templates WHERE name = 'NEW_LEAD_NOTIFICATION'");
    $stmt->execute();
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($template) {
        echo "   ID: {$template['id']}\n";
        echo "   ACT: '" . ($template['act'] ?: 'EMPTY') . "'\n";
        echo "   Name: {$template['name']}\n";
        echo "   Email status: " . ($template['email_status'] ? 'ENABLED' : 'DISABLED') . "\n";
        
        // 2. Fix the act field
        echo "\n2. Fixing ACT field...\n";
        $stmt = $pdo->prepare("UPDATE notification_templates SET act = 'NEW_LEAD_NOTIFICATION' WHERE id = ?");
        $result = $stmt->execute([$template['id']]);
        
        if ($result) {
            echo "✅ Successfully updated ACT field\n";
            
            // 3. Verify the fix
            echo "\n3. Verifying fix...\n";
            $stmt = $pdo->prepare("SELECT act, name FROM notification_templates WHERE id = ?");
            $stmt->execute([$template['id']]);
            $updated = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "   Updated ACT: '{$updated['act']}'\n";
            echo "   Name: {$updated['name']}\n";
            
            if ($updated['act'] === 'NEW_LEAD_NOTIFICATION') {
                echo "✅ Fix successful! Template now has correct ACT key\n";
            } else {
                echo "❌ Fix failed - ACT field still incorrect\n";
            }
        } else {
            echo "❌ Failed to update ACT field\n";
        }
        
    } else {
        echo "❌ NEW_LEAD_NOTIFICATION template not found!\n";
    }
    
    // 4. Test template lookup
    echo "\n4. Testing template lookup:\n";
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM notification_templates WHERE act = 'NEW_LEAD_NOTIFICATION'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] > 0) {
        echo "✅ Template can now be found by ACT key 'NEW_LEAD_NOTIFICATION'\n";
    } else {
        echo "❌ Template still cannot be found by ACT key\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== FIX COMPLETED ===\n";
echo "Now try creating a new lead to test email notifications!\n"; 