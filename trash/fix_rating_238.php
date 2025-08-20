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

    // Start transaction
    $pdo->beginTransaction();

    echo "=== SỬA CHỮA RATING ID 238 ===\n";
    
    // Calculate correct average for Rating ID 238
    $stmt = $pdo->prepare("
        SELECT AVG(rd.rating) as correct_avg
        FROM rating_details rd
        WHERE rd.rating_id = 238
    ");
    $stmt->execute();
    $correctAvg = $stmt->fetch(PDO::FETCH_ASSOC)['correct_avg'];
    $correctAvg = round($correctAvg, 2);

    echo "Avg rating hiện tại: 4.2\n";
    echo "Avg rating đúng: {$correctAvg}\n";

    // Update Rating ID 238
    $updateStmt = $pdo->prepare("UPDATE ratings SET avg_rating = ? WHERE id = 238");
    $updateStmt->execute([$correctAvg]);
    echo "✅ Đã cập nhật Rating ID 238: 4.2 → {$correctAvg}\n";

    // Recalculate company avg_rating
    echo "\n=== TÍNH LẠI COMPANY AVG_RATING ===\n";
    $stmt = $pdo->prepare("
        SELECT AVG(rd.rating) as company_avg_rating
        FROM rating_details rd
        JOIN ratings r ON rd.rating_id = r.id
        WHERE r.company_id = 57 AND r.status = 1
    ");
    $stmt->execute();
    $companyAvg = $stmt->fetch(PDO::FETCH_ASSOC)['company_avg_rating'];
    $companyAvg = round($companyAvg, 2);

    echo "Company avg_rating mới: {$companyAvg}\n";

    $updateCompanyStmt = $pdo->prepare("UPDATE companies SET avg_rating = ? WHERE id = 57");
    $updateCompanyStmt->execute([$companyAvg]);
    echo "✅ Đã cập nhật company avg_rating: 4.25 → {$companyAvg}\n";

    // Commit transaction
    $pdo->commit();
    echo "\n✅ Tất cả thay đổi đã được lưu!\n";

    // Verify the fix
    echo "\n=== KIỂM TRA SAU KHI SỬA CHỮA ===\n";
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
    $verifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($verifications as $ver) {
        $stored = round($ver['stored_avg'], 2);
        $calculated = round($ver['calculated_avg'], 2);
        $status = $stored == $calculated ? "✅" : "❌";
        echo "Rating ID {$ver['id']}: Stored={$stored}, Calculated={$calculated}, Features={$ver['feature_count']} {$status}\n";
    }

    // Check company avg_rating
    $stmt = $pdo->prepare("SELECT avg_rating FROM companies WHERE id = 57");
    $stmt->execute();
    $companyRating = $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'];
    echo "\nCông ty 57 avg_rating: {$companyRating}\n";

} catch (PDOException $e) {
    // Rollback on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
}
?> 