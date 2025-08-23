<?php
// Test Carbon locale tiếng Việt
require_once 'core/bootstrap/app.php';

use Carbon\Carbon;

echo "🔍 Test Carbon locale tiếng Việt...\n\n";

// Test với locale mặc định
$date = Carbon::now()->subDays(5);
echo "Mặc định: " . $date->diffForHumans() . "\n";

// Test với locale vi
echo "Tiếng Việt: " . $date->locale('vi')->diffForHumans() . "\n";

// Test các khoảng thời gian khác
$dates = [
    Carbon::now()->subMinutes(30),
    Carbon::now()->subHours(2),
    Carbon::now()->subDays(1),
    Carbon::now()->subDays(7),
    Carbon::now()->subMonths(1),
    Carbon::now()->subYears(1),
];

echo "\n📅 Test các khoảng thời gian:\n";
foreach ($dates as $date) {
    echo "- " . $date->locale('vi')->diffForHumans() . "\n";
}

// Kiểm tra locale có sẵn
echo "\n🌐 Locales có sẵn:\n";
$locales = ['en', 'vi', 'vi_VN'];
foreach ($locales as $locale) {
    try {
        $test = Carbon::now()->locale($locale)->diffForHumans();
        echo "- $locale: ✅ $test\n";
    } catch (Exception $e) {
        echo "- $locale: ❌ " . $e->getMessage() . "\n";
    }
}
?> 