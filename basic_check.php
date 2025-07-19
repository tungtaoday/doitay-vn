<?php
echo "🔍 Basic Database Check\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    echo "❌ Kết nối database thất bại: " . mysqli_connect_error() . "\n";
    exit;
}

echo "✅ Kết nối database thành công\n";

// Kiểm tra bảng general_settings
$result = mysqli_query($conn, "SHOW TABLES LIKE 'general_settings'");
if (mysqli_num_rows($result) > 0) {
    echo "✅ Bảng general_settings tồn tại\n";
    
    // Kiểm tra cấu trúc
    $result = mysqli_query($conn, "DESCRIBE general_settings");
    echo "📊 Cấu trúc bảng:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "  - {$row['Field']} ({$row['Type']})\n";
    }
    
    // Kiểm tra dữ liệu
    $result = mysqli_query($conn, "SELECT * FROM general_settings LIMIT 1");
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        echo "📋 Dữ liệu mẫu:\n";
        foreach ($data as $key => $value) {
            echo "  $key: $value\n";
        }
    }
    
} else {
    echo "❌ Bảng general_settings không tồn tại\n";
}

mysqli_close($conn); 