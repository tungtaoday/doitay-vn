<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    // Connect to database with UTF-8 charset
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ]);
    
    echo "✅ Kết nối database thành công!\n";
    
    // Create locations table if not exists
    $createTableSQL = "
    CREATE TABLE IF NOT EXISTS locations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        city VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
        city_code VARCHAR(10),
        district VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
        district_code VARCHAR(10),
        ward VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
        ward_code VARCHAR(10),
        level VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
        english_name VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_city_code (city_code),
        INDEX idx_district_code (district_code),
        INDEX idx_ward_code (ward_code),
        INDEX idx_level (level)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($createTableSQL);
    echo "✅ Tạo bảng locations thành công!\n";
    
    // Clear existing data
    $pdo->exec("TRUNCATE TABLE locations");
    echo "✅ Xóa dữ liệu cũ thành công!\n";
    
    // Read CSV file
    $csvFile = 'core/locations.csv';
    
    if (!file_exists($csvFile)) {
        throw new Exception("File CSV không tồn tại: $csvFile");
    }
    
    // Open file with UTF-8 encoding
    $handle = fopen($csvFile, 'r');
    
    if (!$handle) {
        throw new Exception("Không thể mở file CSV");
    }
    
    // Set locale for proper UTF-8 handling
    setlocale(LC_ALL, 'en_US.UTF-8');
    
    // Skip header row
    $header = fgetcsv($handle, 0, ',');
    echo "Header: " . implode(' | ', $header) . "\n";
    
    // Prepare insert statement
    $insertSQL = "INSERT INTO locations (city, city_code, district, district_code, ward, ward_code, level, english_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insertSQL);
    
    $rowCount = 0;
    $successCount = 0;
    
    echo "🚀 Bắt đầu import dữ liệu...\n";
    
    // Begin transaction for better performance
    $pdo->beginTransaction();
    
    while (($row = fgetcsv($handle, 0, ',')) !== FALSE) {
        $rowCount++;
        
        try {
            // Clean and process data
            $city = trim($row[0], '"');
            $city_code = trim($row[1], '"');
            $district = trim($row[2], '"');
            $district_code = trim($row[3], '"');
            $ward = trim($row[4], '"');
            $ward_code = trim($row[5], '"');
            $level = trim($row[6], '"');
            $english_name = isset($row[7]) ? trim($row[7], '"') : '';
            
            // Convert encoding if needed
            $city = mb_convert_encoding($city, 'UTF-8', 'auto');
            $district = mb_convert_encoding($district, 'UTF-8', 'auto');
            $ward = mb_convert_encoding($ward, 'UTF-8', 'auto');
            $level = mb_convert_encoding($level, 'UTF-8', 'auto');
            
            // Execute insert
            $stmt->execute([
                $city,
                $city_code,
                $district,
                $district_code,
                $ward,
                $ward_code,
                $level,
                $english_name
            ]);
            
            $successCount++;
            
            // Show progress every 1000 rows
            if ($rowCount % 1000 == 0) {
                echo "📊 Đã xử lý: $rowCount dòng, thành công: $successCount\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Lỗi tại dòng $rowCount: " . $e->getMessage() . "\n";
            echo "Dữ liệu: " . implode(' | ', $row) . "\n";
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    fclose($handle);
    
    echo "\n🎉 Hoàn thành import!\n";
    echo "📊 Tổng số dòng xử lý: $rowCount\n";
    echo "✅ Số dòng import thành công: $successCount\n";
    
    // Verify data
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM locations");
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "🔍 Kiểm tra database: $total dòng trong bảng locations\n";
    
    // Show sample Vietnamese data
    echo "\n📋 Dữ liệu mẫu (tiếng Việt):\n";
    $sampleStmt = $pdo->query("SELECT city, district, ward FROM locations WHERE city LIKE '%Hà Nội%' LIMIT 5");
    
    while ($sample = $sampleStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$sample['city']} > {$sample['district']} > {$sample['ward']}\n";
    }
    
} catch (PDOException $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n🏁 Script hoàn thành!\n";
?> 