<?php
// Fix Vietnamese encoding in database
$conn = new mysqli("localhost", "root", "", "t_review_db");

// Set connection to UTF-8
$conn->set_charset("utf8mb4");

echo "🔧 Fixing Vietnamese character encoding...\n\n";

// Step 1: Fix database charset
echo "Step 1: Setting database charset...\n";
$conn->query("ALTER DATABASE t_review_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// Step 2: Fix table charsets
echo "Step 2: Converting table charsets...\n";
$tables = ['companies', 'users', 'categories', 'ratings', 'leads', 'notifications'];
foreach($tables as $table) {
    $conn->query("ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "  ✅ Fixed $table table\n";
}

// Step 3: Fix corrupted Vietnamese data in companies
echo "\nStep 3: Restoring Vietnamese company data...\n";

// Delete corrupted records
$conn->query("DELETE FROM companies WHERE id IN (11, 12, 17, 21, 22)");

// Insert correct Vietnamese data
$companies = [
    [11, 24, 1, 'Điện Lạnh Thỏ Lan', 'tho1@doitay.vn', '0987654321', '123 Trần Hưng Đạo, Q1', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Chuyên sửa chữa điện lạnh, máy lạnh, tủ lạnh', 5, 1],
    [12, 25, 2, 'Thợ Nước Thành Đạt', 'tho2@doitay.vn', '0987654322', '456 Lê Lợi, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sửa chữa hệ thống nước, ống nước, vòi nước', 3, 1],
    [17, 30, 7, 'Thợ Cơ Khí Văn Tú', 'tho7@doitay.vn', '0987654327', '147 Điện Biên Phủ, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sửa chữa máy móc, thiết bị cơ khí', 8, 1],
    [21, 34, 1, 'Điện Lạnh Văn An', 'tho11@doitay.vn', '0987654331', '852 Lê Văn Sỹ, Q3', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Sửa chữa điều hòa, tủ lạnh chuyên nghiệp', 6, 1],
    [22, 35, 2, 'Thợ Nước Thị Bình', 'tho12@doitay.vn', '0987654332', '963 Cộng Hòa, Q10', 'Hồ Chí Minh', 'TP.HCM', '70000', 'Vietnam', 'Thông tắc cống, sửa ống nước', 4, 1]
];

$stmt = $conn->prepare("INSERT INTO companies (id, user_id, category_id, name, email, mobile, address, city, state, zip, country, details, total_rating, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

foreach($companies as $company) {
    $stmt->bind_param("iiiissssssssii", ...$company);
    $stmt->execute();
    echo "  ✅ Restored: " . $company[3] . "\n";
}

// Step 4: Fix Vietnamese user names
echo "\nStep 4: Fixing Vietnamese user names...\n";
$users = [
    [24, 'Thỏ', 'Lan'],
    [25, 'Thành', 'Đạt'], 
    [30, 'Văn', 'Tú'],
    [34, 'Văn', 'An'],
    [35, 'Thị', 'Bình']
];

$stmt2 = $conn->prepare("UPDATE users SET firstname = ?, lastname = ? WHERE id = ?");
foreach($users as $user) {
    $stmt2->bind_param("ssi", $user[1], $user[2], $user[0]);
    $stmt2->execute();
    echo "  ✅ Fixed user: " . $user[1] . " " . $user[2] . "\n";
}

// Step 5: Verify the fix
echo "\n🔍 Verification:\n";
$result = $conn->query("SELECT id, name, details FROM companies WHERE id IN (11, 12, 17, 21, 22)");
while($row = $result->fetch_assoc()) {
    echo "  ID " . $row['id'] . ": " . $row['name'] . " - " . substr($row['details'], 0, 30) . "...\n";
}

$result2 = $conn->query("SELECT id, firstname, lastname FROM users WHERE id IN (24, 25, 30, 34, 35)");
echo "\nUsers:\n";
while($row = $result2->fetch_assoc()) {
    echo "  ID " . $row['id'] . ": " . $row['firstname'] . " " . $row['lastname'] . "\n";
}

echo "\n✅ Vietnamese encoding fix completed!\n";
$conn->close();
?> 