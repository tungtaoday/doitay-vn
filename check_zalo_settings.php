<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "🔍 Kiểm tra cấu trúc bảng general_settings...\n";
    
    // Kiểm tra cấu trúc bảng
    $columns = DB::select('DESCRIBE general_settings');
    echo "📋 Cấu trúc bảng:\n";
    foreach ($columns as $column) {
        echo "  - {$column->Field}: {$column->Type} {$column->Null} {$column->Key} {$column->Default}\n";
    }
    
    echo "\n🔍 Kiểm tra dữ liệu Zalo hiện tại...\n";
    
    // Kiểm tra các cột Zalo
    $zaloColumns = ['zalo_phone', 'zalo_name', 'zalo_avatar', 'zalo_online', 'zalo_message'];
    
    foreach ($zaloColumns as $column) {
        $value = DB::table('general_settings')->where('setting_key', $column)->value('setting_value');
        echo "  - {$column}: " . ($value ?? 'NULL') . "\n";
    }
    
    // Kiểm tra tất cả dữ liệu
    echo "\n📊 Tất cả dữ liệu trong general_settings:\n";
    $allSettings = DB::table('general_settings')->get();
    foreach ($allSettings as $setting) {
        echo "  - {$setting->setting_key}: {$setting->setting_value}\n";
    }
    
    // Kiểm tra cột global_shortcodes
    echo "\n🔍 Kiểm tra cột global_shortcodes:\n";
    $globalShortcodes = DB::table('general_settings')->where('setting_key', 'global_shortcodes')->first();
    if ($globalShortcodes) {
        $data = json_decode($globalShortcodes->setting_value, true);
        if ($data) {
            echo "  - global_shortcodes (JSON):\n";
            foreach ($data as $key => $value) {
                echo "    * {$key}: " . (is_array($value) ? json_encode($value) : $value) . "\n";
            }
        } else {
            echo "  - global_shortcodes: JSON không hợp lệ\n";
        }
    } else {
        echo "  - global_shortcodes: Không tìm thấy\n";
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}
?> 