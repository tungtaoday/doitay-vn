<?php
/**
 * Script để cập nhật gradient sang màu xanh đậm chuyên nghiệp
 * Từ: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)
 * Thành: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)
 */

$oldGradient = 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
$newGradient = 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)';

// Danh sách các file cần cập nhật
$files = [
    'core/resources/views/templates/basic/company/companies.blade.php',
    'core/resources/views/templates/basic/company/enhanced_companies.blade.php',
    'core/resources/views/templates/basic/company/index.blade.php',
    'core/resources/views/templates/basic/company/enhanced_index.blade.php',
    'core/resources/views/templates/basic/company/details.blade.php',
    'core/resources/views/templates/basic/user/company/form.blade.php',
    'core/resources/views/templates/basic/user/company/index.blade.php',
    'core/resources/views/templates/basic/user/leads/my-purchases.blade.php',
    'core/resources/views/templates/basic/user/customer/leads/index.blade.php',
    'core/resources/views/templates/basic/home.blade.php',
    'core/resources/views/templates/basic/contractors/search_improved.blade.php',
    'core/resources/views/user/partials/notification_bell.blade.php',
    'core/resources/views/email_templates/professional_wrapper.blade.php',
    'core/resources/views/emails/welcome_guest.blade.php'
];

$totalFiles = count($files);
$updatedFiles = 0;
$totalReplacements = 0;

echo "🎨 Bắt đầu cập nhật gradient sang màu xanh đậm chuyên nghiệp...\n";
echo "Từ: $oldGradient\n";
echo "Thành: $newGradient\n\n";

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "❌ File không tồn tại: $file\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // Thay thế gradient
    $newContent = str_replace($oldGradient, $newGradient, $content);
    
    // Đếm số lần thay thế
    $replacements = substr_count($content, $oldGradient);
    
    if ($replacements > 0) {
        // Ghi file
        if (file_put_contents($file, $newContent)) {
            echo "✅ $file: Thay thế $replacements lần\n";
            $updatedFiles++;
            $totalReplacements += $replacements;
        } else {
            echo "❌ $file: Không thể ghi file\n";
        }
    } else {
        echo "ℹ️  $file: Không có gradient cần thay thế\n";
    }
}

echo "\n🎉 Hoàn thành!\n";
echo "📊 Tổng kết:\n";
echo "- Files đã cập nhật: $updatedFiles/$totalFiles\n";
echo "- Tổng số lần thay thế: $totalReplacements\n";

// Cập nhật các màu liên quan
echo "\n🔧 Cập nhật các màu liên quan...\n";

$colorUpdates = [
    '#4facfe' => '#1e3a8a',
    '#00f2fe' => '#3b82f6',
    'rgba(79, 172, 254,' => 'rgba(30, 58, 138,'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    $originalContent = $content;
    
    foreach ($colorUpdates as $oldColor => $newColor) {
        $content = str_replace($oldColor, $newColor, $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "✅ $file: Cập nhật màu liên quan\n";
    }
}

echo "\n✨ Tất cả gradient đã được cập nhật thành công!\n";
echo "🎨 Gradient mới: $newGradient\n";
echo "💙 Màu xanh đậm chuyên nghiệp, hài hòa với tông màu website\n";
echo "🎯 Phù hợp với --primary-dark: #102f4b\n";
?> 