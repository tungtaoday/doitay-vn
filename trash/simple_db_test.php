<?php
echo "Testing database connection...\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    echo "Database connected successfully!\n";
    
    // Test 1: Check appointment 117 exists
    $stmt = $pdo->prepare("SELECT id, user_id, company_id, status FROM appointments WHERE id = 117");
    $stmt->execute();
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($appointment) {
        echo "Appointment 117 found:\n";
        echo "- User ID: " . $appointment['user_id'] . "\n";
        echo "- Company ID: " . $appointment['company_id'] . "\n";
        echo "- Status: " . $appointment['status'] . "\n";
    } else {
        echo "Appointment 117 NOT found!\n";
        exit;
    }
    
    // Test 2: Check user_notifications table
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM user_notifications");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total UserNotifications: " . $result['total'] . "\n";
    
    // Test 3: Check notifications for this user
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = ?");
    $stmt->execute([$appointment['user_id']]);
    $userNotifCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Notifications for User " . $appointment['user_id'] . ": " . $userNotifCount . "\n";
    
    // Test 4: Get company user
    if ($appointment['company_id']) {
        $stmt = $pdo->prepare("SELECT user_id FROM companies WHERE id = ?");
        $stmt->execute([$appointment['company_id']]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($company) {
            echo "Company User ID: " . $company['user_id'] . "\n";
            
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_notifications WHERE user_id = ?");
            $stmt->execute([$company['user_id']]);
            $companyNotifCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "Notifications for Company User " . $company['user_id'] . ": " . $companyNotifCount . "\n";
        } else {
            echo "Company not found!\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
?> 
 