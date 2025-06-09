<?php
// Kiểm tra tiếng Việt trong bảng companies
header('Content-Type: text/html; charset=UTF-8');
echo "<meta charset='UTF-8'>";

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "t_review_db";

// Set UTF-8
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
    
    echo "<h2>✅ Database Connection UTF-8 Status</h2>";
    echo "<p>Connection charset: " . $conn->character_set_name() . "</p>";
    
    // Kiểm tra character set database
    $result = $conn->query("SHOW VARIABLES LIKE 'character_set%'");
    echo "<h3>📋 MySQL Character Sets:</h3>";
    while($row = $result->fetch_assoc()) {
        echo $row['Variable_name'] . ": " . $row['Value'] . "<br>";
    }
    
    echo "<hr>";
    
    // Kiểm tra collation database
    $result = $conn->query("SHOW VARIABLES LIKE 'collation%'");
    echo "<h3>📋 MySQL Collations:</h3>";
    while($row = $result->fetch_assoc()) {
        echo $row['Variable_name'] . ": " . $row['Value'] . "<br>";
    }
    
    echo "<hr>";
    
    // Kiểm tra bảng companies
    $result = $conn->query("SHOW TABLE STATUS LIKE 'companies'");
    if($row = $result->fetch_assoc()) {
        echo "<h3>📋 Companies Table Status:</h3>";
        echo "Charset: " . $row['Collation'] . "<br>";
    }
    
    echo "<hr>";
    
    // Lấy dữ liệu tiếng Việt từ bảng companies
    $result = $conn->query("SELECT id, name, details FROM companies LIMIT 5");
    
    echo "<h2>🇻🇳 Dữ liệu tiếng Việt trong bảng Companies:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Tên</th><th>Chi tiết</th></tr>";
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["details"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>❌ Không có dữ liệu</td></tr>";
    }
    echo "</table>";
    
    // Test tạo dữ liệu tiếng Việt mới
    echo "<hr>";
    echo "<h3>🧪 Test Insert Vietnamese Text:</h3>";
    $test_name = "Thợ Điện Văn Thành Test";
    $test_details = "Chuyên sửa chữa điện dân dụng và công nghiệp tại TP.HCM";
    
    $stmt = $conn->prepare("INSERT INTO companies (user_id, category_id, name, email, mobile, address, city, state, zip, country, details, total_rating, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
    $user_id = 1;
    $category_id = 1;
    $email = "test@vietnam.com";
    $mobile = "0909123456";
    $address = "123 Nguyễn Văn Cừ";
    $city = "TP Hồ Chí Minh";
    $state = "TP.HCM";
    $zip = "70000";
    $country = "Vietnam";
    $total_rating = 0;
    $status = 1;
    
    $stmt->bind_param("iissssssssiii", $user_id, $category_id, $test_name, $email, $mobile, $address, $city, $state, $zip, $country, $test_details, $total_rating, $status);
    
    if ($stmt->execute()) {
        echo "✅ Đã thêm công ty test thành công!<br>";
        $new_id = $conn->insert_id;
        
        // Lấy lại để xem
        $check = $conn->query("SELECT name, details FROM companies WHERE id = $new_id");
        if($check_row = $check->fetch_assoc()) {
            echo "<strong>Tên đã lưu:</strong> " . htmlspecialchars($check_row['name'], ENT_QUOTES, 'UTF-8') . "<br>";
            echo "<strong>Chi tiết đã lưu:</strong> " . htmlspecialchars($check_row['details'], ENT_QUOTES, 'UTF-8') . "<br>";
        }
        
        // Xóa record test
        $conn->query("DELETE FROM companies WHERE id = $new_id");
        echo "🗑️ Đã xóa record test<br>";
    } else {
        echo "❌ Lỗi khi thêm: " . $stmt->error;
    }
    
} catch(Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}

$conn->close();
?> 