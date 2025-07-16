<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG USER NOTIFICATIONS ISSUE ===\n\n";
    
    // 1. Focus on user_notifications table
    echo "1. User Notifications Table Analysis:\n";
    $stmt = $pdo->query("DESCRIBE user_notifications");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "   {$column['Field']} - {$column['Type']} - Default: {$column['Default']}\n";
    }
    echo "\n";
    
    // 2. Check unread notifications count
    echo "2. Current notification statistics:\n";
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread,
            SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read_count
        FROM user_notifications
    ");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "   Total: {$stats['total']}\n";
    echo "   Unread: {$stats['unread']}\n";
    echo "   Read: {$stats['read_count']}\n\n";
    
    // 3. Show sample unread notifications
    echo "3. Sample unread notifications:\n";
    $stmt = $pdo->query("
        SELECT id, user_id, title, is_read, read_at, created_at, action_url
        FROM user_notifications 
        WHERE is_read = 0 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($samples)) {
        echo "   No unread notifications found.\n\n";
    } else {
        foreach ($samples as $sample) {
            echo "   ID: {$sample['id']}\n";
            echo "   User: {$sample['user_id']}\n";
            echo "   Title: {$sample['title']}\n";
            echo "   is_read: {$sample['is_read']}\n";
            echo "   read_at: {$sample['read_at']}\n";
            echo "   Action URL: {$sample['action_url']}\n";
            echo "   Created: {$sample['created_at']}\n";
            echo "   ---\n";
        }
        echo "\n";
    }
    
    // 4. Test mark as read functionality
    if (!empty($samples)) {
        $testId = $samples[0]['id'];
        echo "4. Testing mark as read functionality with ID {$testId}:\n";
        
        // Before update
        $stmt = $pdo->prepare("SELECT is_read, read_at FROM user_notifications WHERE id = ?");
        $stmt->execute([$testId]);
        $before = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   Before: is_read = {$before['is_read']}, read_at = {$before['read_at']}\n";
        
        // Update to read
        $stmt = $pdo->prepare("
            UPDATE user_notifications 
            SET is_read = 1, read_at = NOW() 
            WHERE id = ?
        ");
        $updateResult = $stmt->execute([$testId]);
        echo "   Update result: " . ($updateResult ? 'SUCCESS' : 'FAILED') . "\n";
        
        // After update
        $stmt = $pdo->prepare("SELECT is_read, read_at FROM user_notifications WHERE id = ?");
        $stmt->execute([$testId]);
        $after = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "   After: is_read = {$after['is_read']}, read_at = {$after['read_at']}\n";
        
        // Reset back to unread for actual testing
        $stmt = $pdo->prepare("
            UPDATE user_notifications 
            SET is_read = 0, read_at = NULL 
            WHERE id = ?
        ");
        $stmt->execute([$testId]);
        echo "   Reset to unread for real testing\n\n";
    }
    
    // 5. Check for specific user's notifications
    echo "5. Check recent users with notifications:\n";
    $stmt = $pdo->query("
        SELECT user_id, COUNT(*) as total, 
               SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread
        FROM user_notifications 
        GROUP BY user_id 
        HAVING unread > 0
        ORDER BY unread DESC 
        LIMIT 5
    ");
    $userStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($userStats as $stat) {
        echo "   User ID {$stat['user_id']}: {$stat['unread']} unread out of {$stat['total']} total\n";
    }
    echo "\n";
    
    // 6. Check JavaScript/Browser debugging info
    echo "6. Browser debugging steps:\n";
    echo "   1. Open browser and login as a user with unread notifications\n";
    echo "   2. Open F12 → Console tab\n";
    echo "   3. Click on a notification bell or notification item\n";
    echo "   4. Look for these potential issues:\n";
    echo "      - JavaScript errors in console\n";
    echo "      - Failed AJAX requests in Network tab\n";
    echo "      - CSRF token mismatch (403 Forbidden)\n";
    echo "      - Wrong API endpoint (404 Not Found)\n";
    echo "      - Authentication issues (401 Unauthorized)\n\n";
    
    // 7. Manual test URLs
    if (!empty($userStats)) {
        $testUser = $userStats[0]['user_id'];
        echo "7. Manual test URLs (replace {id} with actual notification ID):\n";
        echo "   Test user: {$testUser}\n";
        echo "   View notifications: http://localhost/user/notifications\n";
        echo "   AJAX endpoint: http://localhost/user/notifications/header-data\n";
        echo "   Mark as read: POST http://localhost/user/notifications/{id}/read\n";
        echo "   Mark all read: POST http://localhost/user/notifications/read-all\n\n";
    }
    
    // 8. Check Laravel table
    echo "8. Checking Laravel notifications table:\n";
    $stmt = $pdo->query("
        SELECT COUNT(*) as total,
               SUM(CASE WHEN read_at IS NULL THEN 1 ELSE 0 END) as unread
        FROM notifications
    ");
    $laravelStats = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "   Laravel notifications total: {$laravelStats['total']}\n";
    echo "   Laravel notifications unread: {$laravelStats['unread']}\n\n";
    
    echo "=== CONCLUSION ===\n";
    echo "The issue might be:\n";
    echo "1. JavaScript not executing properly\n";
    echo "2. CSRF token issues\n";
    echo "3. Using wrong notification system (Laravel vs custom)\n";
    echo "4. Authentication/permission issues\n";
    echo "5. Frontend not calling the correct API endpoint\n\n";
    
    echo "Next step: Check browser console when clicking notifications!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 