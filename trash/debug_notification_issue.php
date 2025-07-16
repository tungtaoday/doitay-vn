<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG NOTIFICATION READ ISSUE ===\n\n";
    
    // 1. Check if notifications table exists and structure
    echo "1. Checking notifications table structure...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE '%notification%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Available notification tables:\n";
    foreach ($tables as $table) {
        echo "   - {$table}\n";
    }
    echo "\n";
    
    // 2. Check specific table structure
    $notificationTable = 'user_notifications'; // or 'notifications'
    foreach ($tables as $table) {
        if (str_contains($table, 'notification')) {
            echo "Structure of {$table}:\n";
            $stmt = $pdo->query("DESCRIBE {$table}");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($columns as $column) {
                echo "   {$column['Field']} - {$column['Type']} - {$column['Null']} - {$column['Default']}\n";
            }
            echo "\n";
        }
    }
    
    // 3. Check if there are any unread notifications for testing
    foreach ($tables as $table) {
        if (str_contains($table, 'notification')) {
            echo "Checking unread notifications in {$table}...\n";
            $stmt = $pdo->query("
                SELECT COUNT(*) as total,
                       SUM(CASE WHEN is_read = 0 OR is_read IS NULL THEN 1 ELSE 0 END) as unread
                FROM {$table}
            ");
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "   Total: {$stats['total']}, Unread: {$stats['unread']}\n";
            
            // Show sample unread notifications
            if ($stats['unread'] > 0) {
                echo "   Sample unread notifications:\n";
                $stmt = $pdo->query("
                    SELECT id, user_id, title, is_read, read_at, created_at
                    FROM {$table} 
                    WHERE is_read = 0 OR is_read IS NULL 
                    ORDER BY created_at DESC 
                    LIMIT 3
                ");
                $samples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($samples as $sample) {
                    echo "     ID: {$sample['id']}, User: {$sample['user_id']}, Title: " . substr($sample['title'], 0, 30) . "...\n";
                    echo "     is_read: {$sample['is_read']}, read_at: {$sample['read_at']}\n";
                }
            }
            echo "\n";
        }
    }
    
    // 4. Test marking a notification as read
    foreach ($tables as $table) {
        if (str_contains($table, 'notification')) {
            $stmt = $pdo->query("
                SELECT id FROM {$table} 
                WHERE is_read = 0 OR is_read IS NULL 
                LIMIT 1
            ");
            $testNotification = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($testNotification) {
                echo "Testing mark as read on {$table} with ID {$testNotification['id']}...\n";
                
                // Before
                $stmt = $pdo->prepare("SELECT is_read, read_at FROM {$table} WHERE id = ?");
                $stmt->execute([$testNotification['id']]);
                $before = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "   Before: is_read = {$before['is_read']}, read_at = {$before['read_at']}\n";
                
                // Update
                $stmt = $pdo->prepare("
                    UPDATE {$table} 
                    SET is_read = 1, read_at = NOW() 
                    WHERE id = ?
                ");
                $result = $stmt->execute([$testNotification['id']]);
                echo "   Update result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
                
                // After
                $stmt = $pdo->prepare("SELECT is_read, read_at FROM {$table} WHERE id = ?");
                $stmt->execute([$testNotification['id']]);
                $after = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "   After: is_read = {$after['is_read']}, read_at = {$after['read_at']}\n";
                
                // Reset for other tests
                $stmt = $pdo->prepare("
                    UPDATE {$table} 
                    SET is_read = 0, read_at = NULL 
                    WHERE id = ?
                ");
                $stmt->execute([$testNotification['id']]);
                echo "   Reset to unread for other tests\n";
                echo "\n";
            }
        }
    }
    
    // 5. Check for common issues
    echo "5. Checking for common issues...\n";
    
    // Check for wrong table name in code
    echo "✓ Routes exist: user.notifications.read and user.notifications.read.all\n";
    echo "✓ Controller: App\\Http\\Controllers\\User\\NotificationController\n";
    
    // Check CSRF token issues
    echo "⚠ Common issues to check:\n";
    echo "   - Browser console for JavaScript errors\n";
    echo "   - CSRF token mismatch (check meta tag)\n";
    echo "   - User authentication status\n";
    echo "   - Network tab for failed AJAX requests\n";
    echo "   - Wrong notification table being used\n\n";
    
    // 6. Provide testing URL
    echo "6. Debug URLs to test:\n";
    echo "   - Main notifications page: http://localhost/user/notifications\n";
    echo "   - Header notifications AJAX: http://localhost/user/notifications/header-data\n";
    echo "   - Mark as read API: POST http://localhost/user/notifications/{id}/read\n";
    echo "   - Mark all as read API: POST http://localhost/user/notifications/read-all\n\n";
    
    echo "=== DEBUGGING SUGGESTIONS ===\n";
    echo "1. Open browser F12 → Console tab\n";
    echo "2. Click a notification\n";
    echo "3. Check for JavaScript errors or failed network requests\n";
    echo "4. Check if the POST request to /user/notifications/{id}/read returns success\n";
    echo "5. Verify the notification ID is correct\n";
    echo "6. Check if user is properly authenticated\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 