<?php
// Script kiểm tra dữ liệu company từ database
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== KIỂM TRA DỮ LIỆU COMPANY ID 61 ===\n\n";
    
    // Lấy thông tin company ID 61
    $stmt = $pdo->prepare("SELECT * FROM companies WHERE id = 61");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "✅ Tìm thấy company ID 61:\n";
        echo "Tên: " . $company['name'] . "\n";
        echo "Email: " . $company['email'] . "\n";
        echo "Address: " . $company['address'] . "\n";
        echo "City: " . $company['city'] . "\n";
        echo "District: " . $company['district'] . "\n";
        echo "Ward: " . $company['ward'] . "\n";
        echo "Description: " . substr($company['description'], 0, 100) . "...\n";
        echo "Experience: " . $company['experience'] . "\n";
        echo "Status: " . $company['status'] . "\n";
        echo "Avg Rating: " . $company['avg_rating'] . "\n";
        echo "Tags: " . $company['tags'] . "\n";
        echo "Services: " . $company['services'] . "\n";
        echo "Business Hours: " . $company['business_hours'] . "\n";
        echo "Service Areas: " . $company['service_areas'] . "\n";
        echo "Created: " . $company['created_at'] . "\n";
        echo "Updated: " . $company['updated_at'] . "\n\n";
        
        // Kiểm tra kiểu dữ liệu
        echo "=== KIỂM TRA KIỂU DỮ LIỆU ===\n";
        foreach ($company as $key => $value) {
            $type = gettype($value);
            $length = is_string($value) ? strlen($value) : 'N/A';
            echo "$key: $type (Length: $length) = ";
            
            if (is_null($value)) {
                echo "NULL\n";
            } elseif (is_string($value)) {
                echo "'$value'\n";
            } elseif (is_array($value)) {
                echo "Array: " . json_encode($value) . "\n";
            } else {
                echo $value . "\n";
            }
        }
        
        // Kiểm tra JSON fields
        echo "\n=== KIỂM TRA JSON FIELDS ===\n";
        $jsonFields = ['tags', 'services', 'business_hours'];
        foreach ($jsonFields as $field) {
            if (isset($company[$field]) && !is_null($company[$field])) {
                $decoded = json_decode($company[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    echo "$field: JSON hợp lệ\n";
                    echo "  Decoded: " . json_encode($decoded, JSON_PRETTY_PRINT) . "\n";
                } else {
                    echo "$field: JSON không hợp lệ - " . json_last_error_msg() . "\n";
                    echo "  Raw value: " . $company[$field] . "\n";
                }
            } else {
                echo "$field: NULL hoặc không tồn tại\n";
            }
        }
        
        // Kiểm tra category
        echo "\n=== KIỂM TRA CATEGORY ===\n";
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$company['category_id']]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            echo "Category ID: " . $category['id'] . "\n";
            echo "Category Name: " . $category['name'] . "\n";
            echo "Category Status: " . $category['status'] . "\n";
        } else {
            echo "❌ Không tìm thấy category ID: " . $company['category_id'] . "\n";
        }
        
        // Kiểm tra user
        echo "\n=== KIỂM TRA USER ===\n";
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$company['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "User ID: " . $user['id'] . "\n";
            echo "Username: " . $user['username'] . "\n";
            echo "Fullname: " . $user['fullname'] . "\n";
            echo "Email: " . $user['email'] . "\n";
        } else {
            echo "❌ Không tìm thấy user ID: " . $company['user_id'] . "\n";
        }
        
    } else {
        echo "❌ Không tìm thấy company ID 61\n";
    }
    
    // Kiểm tra cấu trúc bảng
    echo "\n=== KIỂM TRA CẤU TRÚC BẢNG COMPANIES ===\n";
    $stmt = $pdo->query("DESCRIBE companies");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . " - " . $column['Null'] . " - " . $column['Key'] . " - " . $column['Default'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi kết nối database: " . $e->getMessage() . "\n";
}
?> 