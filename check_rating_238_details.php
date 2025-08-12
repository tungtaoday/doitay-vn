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

    // Check Rating ID 238 specifically
    echo "=== KIỂM TRA RATING ID 238 (APPOINTMENT 127) ===\n";
    $stmt = $pdo->prepare("
        SELECT r.id, r.user_id, r.company_id, r.appointment_id, r.avg_rating, r.suggest, r.status, r.created_at,
               u.name as user_name, c.name as company_name
        FROM ratings r
        LEFT JOIN users u ON r.user_id = u.id
        LEFT JOIN companies c ON r.company_id = c.id
        WHERE r.id = 238
    ");
    $stmt->execute();
    $rating = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rating) {
        echo "Rating ID: {$rating['id']}\n";
        echo "User: {$rating['user_name']} (ID: {$rating['user_id']})\n";
        echo "Company: {$rating['company_name']} (ID: {$rating['company_id']})\n";
        echo "Appointment ID: {$rating['appointment_id']}\n";
        echo "Avg Rating: {$rating['avg_rating']}\n";
        echo "Comment: {$rating['suggest']}\n";
        echo "Status: {$rating['status']}\n";
        echo "Created: {$rating['created_at']}\n";
    } else {
        echo "❌ Không tìm thấy rating ID 238\n";
    }
    echo "\n";

    // Check Rating Details for Rating ID 238
    echo "=== RATING DETAILS CỦA RATING ID 238 ===\n";
    $stmt = $pdo->prepare("
        SELECT rd.rating_id, rd.feature_id, rd.rating,
               f.name as feature_name
        FROM rating_details rd
        JOIN features f ON rd.feature_id = f.id
        WHERE rd.rating_id = 238
        ORDER BY rd.feature_id
    ");
    $stmt->execute();
    $ratingDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($ratingDetails) {
        echo "Tổng số rating details: " . count($ratingDetails) . "\n";
        foreach ($ratingDetails as $detail) {
            echo "- Feature: {$detail['feature_name']} (ID: {$detail['feature_id']})\n";
            echo "  Rating: {$detail['rating']}\n";
            echo "\n";
        }

        // Calculate average manually
        $totalRating = 0;
        $count = 0;
        foreach ($ratingDetails as $detail) {
            $totalRating += $detail['rating'];
            $count++;
        }
        $avgRating = $count > 0 ? $totalRating / $count : 0;
        echo "Tính toán thủ công:\n";
        echo "Tổng rating: {$totalRating}\n";
        echo "Số lượng features: {$count}\n";
        echo "Trung bình: " . round($avgRating, 2) . "\n";
        
        // Check if this matches the stored avg_rating
        $storedAvg = $rating['avg_rating'];
        if (abs($storedAvg - $avgRating) < 0.01) {
            echo "✅ Stored avg_rating ({$storedAvg}) khớp với calculated avg ({$avgRating})\n";
        } else {
            echo "❌ Stored avg_rating ({$storedAvg}) KHÔNG khớp với calculated avg ({$avgRating})\n";
        }
    } else {
        echo "❌ Không có rating details nào cho rating ID 238\n";
    }

    // Check if this rating is being displayed correctly on frontend
    echo "\n=== KIỂM TRA HIỂN THỊ TRÊN FRONTEND ===\n";
    
    // Check company details view
    $stmt = $pdo->prepare("
        SELECT c.avg_rating, c.name,
               COUNT(r.id) as total_ratings,
               COUNT(CASE WHEN r.status = 1 THEN 1 END) as active_ratings
        FROM companies c
        LEFT JOIN ratings r ON c.id = r.company_id
        WHERE c.id = 57
        GROUP BY c.id, c.avg_rating, c.name
    ");
    $stmt->execute();
    $companyInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($companyInfo) {
        echo "Company: {$companyInfo['name']}\n";
        echo "Company avg_rating: {$companyInfo['avg_rating']}\n";
        echo "Total ratings: {$companyInfo['total_ratings']}\n";
        echo "Active ratings: {$companyInfo['active_ratings']}\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 