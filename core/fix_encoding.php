<?php
$conn = new mysqli("localhost", "root", "", "t_review_db");
$conn->set_charset("utf8mb4");

echo "Fixing Vietnamese text...\n";

// Delete corrupted records
$conn->query("DELETE FROM companies WHERE id = 11");

// Insert correct data
$conn->query("INSERT INTO companies (id, user_id, category_id, name, email, mobile, address, city, state, zip, country, details, total_rating, status, created_at, updated_at) VALUES (11, 24, 1, 'Điện Lạnh Thỏ Lan', 'tho1@doitay.vn', '0987654321', '123 Trần Hưng Đạo, Q1', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Chuyên sửa chữa điện lạnh, máy lạnh, tủ lạnh', 5, 1, NOW(), NOW())");

// Check result
$result = $conn->query("SELECT name FROM companies WHERE id = 11");
$row = $result->fetch_assoc();
echo "Result: " . $row['name'] . "\n";

$conn->close();
?> 