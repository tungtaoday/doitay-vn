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

    // Direct check of ratings table
    echo "=== KIỂM TRA TRỰC TIẾP BẢNG RATINGS ===\n";
    $stmt = $pdo->prepare("
        SELECT id, avg_rating, created_at
        FROM ratings
        WHERE company_id = 57
        ORDER BY id
    ");
    $stmt->execute();
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ratings as $rating) {
        echo "Rating ID {$rating['id']}: avg_rating = {$rating['avg_rating']}, created = {$rating['created_at']}\n";
    }

    // Direct check of rating_details
    echo "\n=== KIỂM TRA TRỰC TIẾP BẢNG RATING_DETAILS ===\n";
    $stmt = $pdo->prepare("
        SELECT rating_id, feature_id, rating
        FROM rating_details
        WHERE rating_id IN (SELECT id FROM ratings WHERE company_id = 57)
        ORDER BY rating_id, feature_id
    ");
    $stmt->execute();
    $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $currentRatingId = null;
    foreach ($details as $detail) {
        if ($currentRatingId !== $detail['rating_id']) {
            $currentRatingId = $detail['rating_id'];
            echo "\nRating ID {$currentRatingId}:\n";
        }
        echo "  Feature {$detail['feature_id']}: {$detail['rating']}\n";
    }

    // Calculate averages manually
    echo "\n=== TÍNH TOÁN THỦ CÔNG ===\n";
    $stmt = $pdo->prepare("
        SELECT rating_id, AVG(rating) as avg_rating, COUNT(*) as feature_count
        FROM rating_details
        WHERE rating_id IN (SELECT id FROM ratings WHERE company_id = 57)
        GROUP BY rating_id
        ORDER BY rating_id
    ");
    $stmt->execute();
    $calculations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($calculations as $calc) {
        echo "Rating ID {$calc['rating_id']}: Avg = " . round($calc['avg_rating'], 2) . " (Features: {$calc['feature_count']})\n";
    }

    // Check company table
    echo "\n=== KIỂM TRA BẢNG COMPANIES ===\n";
    $stmt = $pdo->prepare("SELECT id, name, avg_rating FROM companies WHERE id = 57");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "Company ID: {$company['id']}\n";
        echo "Name: {$company['name']}\n";
        echo "Avg Rating: {$company['avg_rating']}\n";
    }

} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 