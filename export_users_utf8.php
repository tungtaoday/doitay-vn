<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=t_review_db;charset=utf8mb4', 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối database thành công!\n\n";
    
    // Lấy danh sách users
    $stmt = $pdo->prepare("
        SELECT 
            id,
            username,
            name,
            email,
            mobile,
            firstname,
            lastname,
            district,
            created_at
        FROM users 
        ORDER BY id ASC
    ");
    
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Tìm thấy " . count($users) . " users\n";
    
    if (count($users) == 0) {
        echo "❌ Không có users nào để xuất\n";
        exit;
    }
    
    // Tạo file CSV với BOM UTF-8
    $filename = 'users_list_utf8_' . date('Y-m-d_H-i-s') . '.csv';
    $filepath = __DIR__ . '/' . $filename;
    
    // Mở file để ghi
    $file = fopen($filepath, 'w');
    
    // Ghi BOM UTF-8
    fwrite($file, "\xEF\xBB\xBF");
    
    // Ghi header CSV
    $headers = [
        'ID',
        'Username',
        'Tên đầy đủ',
        'Email',
        'Số điện thoại',
        'Tên',
        'Họ',
        'Quận/Huyện',
        'Ngày tạo',
        'Password'
    ];
    
    fputcsv($file, $headers);
    
    // Ghi dữ liệu users
    $count = 0;
    foreach ($users as $user) {
        $row = [
            $user['id'],
            $user['username'] ?: '',
            $user['name'] ?: '',
            $user['email'] ?: '',
            $user['mobile'] ?: '',
            $user['firstname'] ?: '',
            $user['lastname'] ?: '',
            $user['district'] ?: '',
            $user['created_at'] ?: '',
            'password123' // Password mặc định
        ];
        
        fputcsv($file, $row);
        $count++;
        
        if ($count % 50 == 0) {
            echo "📝 Đã xuất {$count}/" . count($users) . " users...\n";
        }
    }
    
    fclose($file);
    
    echo "\n✅ Hoàn thành xuất CSV!\n";
    echo "📁 File: {$filename}\n";
    echo "📊 Tổng users: {$count}\n";
    echo "📍 Đường dẫn: {$filepath}\n";
    
    // Hiển thị mẫu dữ liệu
    echo "\n📋 Mẫu dữ liệu (10 users đầu tiên):\n";
    echo str_repeat('-', 120) . "\n";
    echo sprintf("%-5s %-15s %-25s %-25s %-15s %-10s\n", 'ID', 'Username', 'Tên đầy đủ', 'Email', 'Số điện thoại', 'Password');
    echo str_repeat('-', 120) . "\n";
    
    for ($i = 0; $i < min(10, count($users)); $i++) {
        $user = $users[$i];
        echo sprintf("%-5s %-15s %-25s %-25s %-15s %-10s\n", 
            $user['id'], 
            mb_substr($user['username'] ?: '', 0, 12) . (mb_strlen($user['username'] ?: '') > 12 ? '...' : ''), 
            mb_substr($user['name'] ?: '', 0, 20) . (mb_strlen($user['name'] ?: '') > 20 ? '...' : ''), 
            mb_substr($user['email'] ?: '', 0, 20) . (mb_strlen($user['email'] ?: '') > 20 ? '...' : ''), 
            $user['mobile'] ?: '', 
            'password123'
        );
    }
    
    echo str_repeat('-', 120) . "\n";
    
    // Thống kê theo quận/huyện
    echo "\n📊 Thống kê theo quận/huyện:\n";
    $districtStats = [];
    foreach ($users as $user) {
        $district = $user['district'] ?: 'Không xác định';
        if (!isset($districtStats[$district])) {
            $districtStats[$district] = 0;
        }
        $districtStats[$district]++;
    }
    
    arsort($districtStats);
    foreach ($districtStats as $district => $count) {
        echo sprintf("%-25s: %d users\n", $district, $count);
    }
    
    // Thống kê theo thời gian
    echo "\n📅 Thống kê theo thời gian:\n";
    $yearStats = [];
    foreach ($users as $user) {
        if ($user['created_at']) {
            $year = date('Y', strtotime($user['created_at']));
            if (!isset($yearStats[$year])) {
                $yearStats[$year] = 0;
            }
            $yearStats[$year]++;
        }
    }
    
    ksort($yearStats);
    foreach ($yearStats as $year => $count) {
        echo sprintf("Năm %s: %d users\n", $year, $count);
    }
    
    // Tạo file Excel đơn giản (HTML table)
    $htmlFilename = 'users_list_html_' . date('Y-m-d_H-i-s') . '.html';
    $htmlFilepath = __DIR__ . '/' . $htmlFilename;
    
    $htmlFile = fopen($htmlFilepath, 'w');
    
    // Ghi HTML header
    fwrite($htmlFile, '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách Users</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .header { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h1>Danh sách Users - Doitay.vn</h1>
    <p>Tổng cộng: ' . $count . ' users</p>
    <table>
        <tr class="header">
            <th>ID</th>
            <th>Username</th>
            <th>Tên đầy đủ</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Tên</th>
            <th>Họ</th>
            <th>Quận/Huyện</th>
            <th>Ngày tạo</th>
            <th>Password</th>
        </tr>');
    
    // Ghi dữ liệu users
    foreach ($users as $user) {
        fwrite($htmlFile, '<tr>
            <td>' . htmlspecialchars($user['id']) . '</td>
            <td>' . htmlspecialchars($user['username'] ?: '') . '</td>
            <td>' . htmlspecialchars($user['name'] ?: '') . '</td>
            <td>' . htmlspecialchars($user['email'] ?: '') . '</td>
            <td>' . htmlspecialchars($user['mobile'] ?: '') . '</td>
            <td>' . htmlspecialchars($user['firstname'] ?: '') . '</td>
            <td>' . htmlspecialchars($user['lastname'] ?: '') . '</td>
            <td>' . htmlspecialchars($user['district'] ?: '') . '</td>
            <td>' . htmlspecialchars($user['created_at'] ?: '') . '</td>
            <td>password123</td>
        </tr>');
    }
    
    fwrite($htmlFile, '</table>
</body>
</html>');
    
    fclose($htmlFile);
    
    echo "\n🌐 Đã tạo file HTML: {$htmlFilename}\n";
    echo "📊 Có thể mở file HTML trong Excel để xem dữ liệu đẹp hơn\n";
    
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 