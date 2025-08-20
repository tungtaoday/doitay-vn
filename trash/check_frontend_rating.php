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

    // Check ratings for company 57 - separate appointment-based vs frontend-based
    echo "=== KIỂM TRA RATINGS CỦA CÔNG TY 57 ===\n";
    
    // Appointment-based ratings (có appointment_id)
    echo "\n1. RATINGS TỪ APPOINTMENT (có appointment_id):\n";
    $stmt = $pdo->prepare("
        SELECT r.id, r.user_id, r.appointment_id, r.avg_rating, r.suggest, r.status, r.created_at,
               u.name as user_name, a.id as appointment_id
        FROM ratings r
        LEFT JOIN users u ON r.user_id = u.id
        LEFT JOIN appointments a ON r.appointment_id = a.id
        WHERE r.company_id = 57 AND r.appointment_id IS NOT NULL
        ORDER BY r.id
    ");
    $stmt->execute();
    $appointmentRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($appointmentRatings) {
        foreach ($appointmentRatings as $rating) {
            echo "   - Rating ID: {$rating['id']}\n";
            echo "     User: {$rating['user_name']} (ID: {$rating['user_id']})\n";
            echo "     Appointment ID: {$rating['appointment_id']}\n";
            echo "     Avg Rating: {$rating['avg_rating']}\n";
            echo "     Comment: {$rating['suggest']}\n";
            echo "     Status: {$rating['status']}\n";
            echo "     Created: {$rating['created_at']}\n\n";
        }
    } else {
        echo "   ❌ Không có rating nào từ appointment\n";
    }

    // Frontend-based ratings (không có appointment_id)
    echo "\n2. RATINGS TỪ FRONTEND (không có appointment_id):\n";
    $stmt = $pdo->prepare("
        SELECT r.id, r.user_id, r.appointment_id, r.avg_rating, r.suggest, r.status, r.created_at,
               u.name as user_name
        FROM ratings r
        LEFT JOIN users u ON r.user_id = u.id
        WHERE r.company_id = 57 AND r.appointment_id IS NULL
        ORDER BY r.id
    ");
    $stmt->execute();
    $frontendRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($frontendRatings) {
        foreach ($frontendRatings as $rating) {
            echo "   - Rating ID: {$rating['id']}\n";
            echo "     User: {$rating['user_name']} (ID: {$rating['user_id']})\n";
            echo "     Appointment ID: " . ($rating['appointment_id'] ?: 'NULL') . "\n";
            echo "     Avg Rating: {$rating['avg_rating']}\n";
            echo "     Comment: {$rating['suggest']}\n";
            echo "     Status: {$rating['status']}\n";
            echo "     Created: {$rating['created_at']}\n\n";
        }
    } else {
        echo "   ❌ Không có rating nào từ frontend\n";
    }

    // Check rating details for all ratings
    echo "\n3. RATING DETAILS CHO TẤT CẢ RATINGS:\n";
    $stmt = $pdo->prepare("
        SELECT r.id, r.appointment_id, r.avg_rating,
               COUNT(rd.rating) as feature_count,
               AVG(rd.rating) as calculated_avg
        FROM ratings r
        LEFT JOIN rating_details rd ON r.id = rd.rating_id
        WHERE r.company_id = 57
        GROUP BY r.id, r.appointment_id, r.avg_rating
        ORDER BY r.id
    ");
    $stmt->execute();
    $ratingDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ratingDetails as $detail) {
        $appointmentInfo = $detail['appointment_id'] ? "Appointment {$detail['appointment_id']}" : "Frontend";
        $stored = round($detail['avg_rating'], 2);
        $calculated = round($detail['calculated_avg'], 2);
        $status = $stored == $calculated ? "✅" : "❌";
        
        echo "   - Rating ID {$detail['id']} ({$appointmentInfo}):\n";
        echo "     Stored: {$stored}, Calculated: {$calculated}, Features: {$detail['feature_count']} {$status}\n";
    }

    // Check company overall rating
    echo "\n4. TỔNG QUAN CÔNG TY 57:\n";
    $stmt = $pdo->prepare("
        SELECT 
            c.avg_rating as company_avg,
            COUNT(r.id) as total_ratings,
            COUNT(CASE WHEN r.appointment_id IS NOT NULL THEN 1 END) as appointment_ratings,
            COUNT(CASE WHEN r.appointment_id IS NULL THEN 1 END) as frontend_ratings
        FROM companies c
        LEFT JOIN ratings r ON c.id = r.company_id AND r.status = 1
        WHERE c.id = 57
        GROUP BY c.id, c.avg_rating
    ");
    $stmt->execute();
    $companyStats = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($companyStats) {
        echo "   - Company avg_rating: {$companyStats['company_avg']}\n";
        echo "   - Total ratings: {$companyStats['total_ratings']}\n";
        echo "   - Appointment ratings: {$companyStats['appointment_ratings']}\n";
        echo "   - Frontend ratings: {$companyStats['frontend_ratings']}\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 