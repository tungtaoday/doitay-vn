<?php
$pdo = new PDO('mysql:host=localhost;dbname=t_review_db', 'root', 'Vuivui@123');

echo "🔍 KIỂM TRA CATEGORIES HIỆN TẠI:\n\n";

$stmt = $pdo->query('SELECT id, name FROM categories ORDER BY id');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} - {$row['name']}\n";
}

echo "\n📋 CATEGORIES CẦN THIẾT CHO FEATURES:\n";
$needed = [1, 2, 3, 4, 6, 7, 8, 9, 10];
$stmt = $pdo->query('SELECT id FROM categories WHERE id IN (' . implode(',', $needed) . ')');
$existing = [];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $existing[] = $row['id'];
}

$missing = array_diff($needed, $existing);
if (empty($missing)) {
    echo "✅ Tất cả categories cần thiết đã tồn tại!\n";
} else {
    echo "❌ Thiếu categories: " . implode(', ', $missing) . "\n";
}
?> 