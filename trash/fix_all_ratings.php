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

    echo "=== SỬA CHỮA TẤT CẢ RATINGS CỦA CÔNG TY 57 ===\n";
    
    // Get all ratings that need fixing
    $stmt = $pdo->prepare("
        SELECT r.id, r.avg_rating as stored_avg,
               AVG(rd.rating) as calculated_avg,
               COUNT(rd.rating) as feature_count
        FROM ratings r
        LEFT JOIN rating_details rd ON r.id = rd.rating_id
        WHERE r.company_id = 57 AND r.status = 1
        GROUP BY r.id, r.avg_rating
        HAVING COUNT(rd.rating) > 0
        ORDER BY r.id
    ");
    $stmt->execute();
    $ratingsToFix = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($ratingsToFix as $rating) {
        $oldAvg = round($rating['stored_avg'], 2);
        $newAvg = round($rating['calculated_avg'], 2);

        if (abs($oldAvg - $newAvg) > 0.01) {
            echo "Rating ID {$rating['id']}: {$oldAvg} → {$newAvg} (Features: {$rating['feature_count']})\n";
            
            $updateStmt = $pdo->prepare("UPDATE ratings SET avg_rating = ? WHERE id = ?");
            $updateStmt->execute([$newAvg, $rating['id']]);
        } else {
            echo "Rating ID {$rating['id']}: {$oldAvg} ✓ (Features: {$rating['feature_count']})\n";
        }
    }

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
    echo "✅ Đã cập nhật company avg_rating\n";

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