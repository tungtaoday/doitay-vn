<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);

    echo "✅ Kết nối database thành công!\n\n";

    // Check all ratings for company 57
    echo "=== TẤT CẢ RATINGS CỦA CÔNG TY 57 ===\n";
    $stmt = $pdo->prepare("
        SELECT r.id, r.user_id, r.appointment_id, r.avg_rating, r.suggest, r.status, r.created_at,
               u.name as user_name
        FROM ratings r
        LEFT JOIN users u ON r.user_id = u.id
        WHERE r.company_id = 57
        ORDER BY r.id
    ");
    $stmt->execute();
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($ratings) {
        foreach ($ratings as $rating) {
            $source = $rating['appointment_id'] ? "Appointment {$rating['appointment_id']}" : "Frontend (trực tiếp)";
            echo "Rating ID: {$rating['id']}\n";
            echo "  - Nguồn: {$source}\n";
            echo "  - User: {$rating['user_name']} (ID: {$rating['user_id']})\n";
            echo "  - Avg Rating: {$rating['avg_rating']}\n";
            echo "  - Comment: {$rating['suggest']}\n";
            echo "  - Status: {$rating['status']}\n";
            echo "  - Created: {$rating['created_at']}\n\n";
        }
    } else {
        echo "❌ Không có rating nào cho công ty 57\n";
    }

    // Check if there are any frontend ratings (without appointment_id)
    echo "=== KIỂM TRA RATINGS TỪ FRONTEND ===\n";
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count
        FROM ratings r
        WHERE r.company_id = 57 AND r.appointment_id IS NULL
    ");
    $stmt->execute();
    $frontendCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($frontendCount > 0) {
        echo "✅ Có {$frontendCount} rating từ frontend (không qua appointment)\n";
    } else {
        echo "❌ Không có rating nào từ frontend\n";
    }

    // Check company current rating
    echo "\n=== RATING HIỆN TẠI CỦA CÔNG TY 57 ===\n";
    $stmt = $pdo->prepare("SELECT name, avg_rating FROM companies WHERE id = 57");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "Tên công ty: {$company['name']}\n";
        echo "Avg Rating: {$company['avg_rating']}\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 