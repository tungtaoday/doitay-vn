<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== KIỂM TRA GOOGLE ANALYTICS HIỆN TẠI ===\n";

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Kết nối thành công!\n";
    
    // Kiểm tra Google Analytics ID
    echo "\n=== GOOGLE ANALYTICS CONFIGURATION ===\n";
    $stmt = $pdo->prepare("SELECT `key`, value FROM general_settings WHERE `key` LIKE '%google%' OR `key` LIKE '%analytics%'");
    $stmt->execute();
    $googleSettings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($googleSettings) {
        foreach ($googleSettings as $setting) {
            echo "Key: " . $setting['key'] . "\n";
            echo "Value: " . $setting['value'] . "\n";
            echo "---\n";
        }
    } else {
        echo "❌ Không tìm thấy cài đặt Google Analytics\n";
    }
    
    // Kiểm tra extensions
    echo "\n=== EXTENSIONS ===\n";
    $stmt = $pdo->prepare("SELECT * FROM extensions WHERE name LIKE '%google%' OR name LIKE '%analytics%'");
    $stmt->execute();
    $extensions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($extensions) {
        foreach ($extensions as $ext) {
            echo "Extension: " . $ext['name'] . "\n";
            echo "Status: " . ($ext['status'] ? 'Active' : 'Inactive') . "\n";
            echo "Script: " . substr($ext['script'], 0, 100) . "...\n";
            echo "---\n";
        }
    } else {
        echo "❌ Không tìm thấy extension Google Analytics\n";
    }
    
    // Kiểm tra tất cả general settings
    echo "\n=== TẤT CẢ GENERAL SETTINGS ===\n";
    $stmt = $pdo->prepare("SELECT `key`, value FROM general_settings ORDER BY `key`");
    $stmt->execute();
    $allSettings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Tổng cộng: " . count($allSettings) . " settings\n";
    foreach ($allSettings as $setting) {
        if (strpos($setting['key'], 'google') !== false || 
            strpos($setting['key'], 'analytics') !== false ||
            strpos($setting['key'], 'gtag') !== false ||
            strpos($setting['key'], 'ga') !== false) {
            echo "🔍 " . $setting['key'] . ": " . $setting['value'] . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?> 