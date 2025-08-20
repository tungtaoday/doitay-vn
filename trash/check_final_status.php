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

    // Check final status of all ratings
    echo "=== TRẠNG THÁI CUỐI CÙNG CỦA TẤT CẢ RATINGS ===\n";
    $stmt = $pdo->prepare("
        SELECT r.id, r.avg_rating as stored_avg,
               AVG(rd.rating) as calculated_avg,
               COUNT(rd.rating) as feature_count
        FROM ratings r
        LEFT JOIN rating_details rd ON r.id = rd.rating_id
        WHERE r.company_id = 57 AND r.status = 1
        GROUP BY r.id, r.avg_rating
        ORDER BY r.id
    ");
    $stmt->execute();
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ratings as $rating) {
        $stored = round($rating['stored_avg'], 2);
        $calculated = round($rating['calculated_avg'], 2);
        $status = $stored == $calculated ? "✅" : "❌";
        echo "Rating ID {$rating['id']}: Stored={$stored}, Calculated={$calculated}, Features={$rating['feature_count']} {$status}\n";
    }

    // Check company avg_rating
    echo "\n=== COMPANY AVG_RATING ===\n";
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