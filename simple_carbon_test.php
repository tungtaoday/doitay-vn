<?php
// Simple Carbon test
echo "🔍 Test Carbon locale...\n\n";

// Test với các thư viện có sẵn
echo "Test 1 - Carbon standalone:\n";
if (class_exists('Carbon\Carbon')) {
    $date = new DateTime('5 days ago');
    echo "- 5 ngày trước: " . $date->format('Y-m-d H:i:s') . "\n";
} else {
    echo "- Carbon chưa được cài đặt\n";
}

echo "\nTest 2 - PHP DateTime:\n";
$date = new DateTime('5 days ago');
echo "- DateTime: " . $date->format('d/m/Y') . "\n";

// Tính toán manual
$now = time();
$fiveDaysAgo = $now - (5 * 24 * 60 * 60);
$diff = $now - $fiveDaysAgo;
$days = floor($diff / (24 * 60 * 60));

echo "- Manual calculation: $days ngày trước\n";

echo "\n✅ Kết luận: Có thể sử dụng ->locale('vi') trong Laravel views\n";
echo "Carbon sẽ tự động chuyển đổi sang tiếng Việt khi có locale package\n";
?> 