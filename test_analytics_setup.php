<?php
// Script test Google Analytics setup
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔍 Test Google Analytics Setup</h2>";
    
    // Kiểm tra GA ID
    $stmt = $pdo->prepare("SELECT value FROM general_settings WHERE `key` = 'google_analytics_id'");
    $stmt->execute();
    $gaId = $stmt->fetchColumn();
    
    echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>📊 Google Analytics Configuration</h3>";
    
    if ($gaId && $gaId != 'GA-XXXXXXXXX') {
        echo "<p style='color: green;'>✅ Google Analytics ID: $gaId</p>";
    } else {
        echo "<p style='color: red;'>❌ Google Analytics ID chưa được cấu hình</p>";
    }
    
    // Kiểm tra các settings khác
    $settings = [
        'analytics_enabled' => 'Analytics Enabled',
        'track_appointments' => 'Track Appointments',
        'track_appointment_status' => 'Track Appointment Status',
        'track_company_views' => 'Track Company Views',
        'track_company_contacts' => 'Track Company Contacts',
        'track_user_registration' => 'Track User Registration',
        'track_user_login' => 'Track User Login',
        'track_search' => 'Track Search',
        'track_scroll_depth' => 'Track Scroll Depth',
        'enhanced_ecommerce' => 'Enhanced Ecommerce',
        'custom_dimensions' => 'Custom Dimensions',
        'analytics_debug' => 'Analytics Debug',
        'gdpr_compliance' => 'GDPR Compliance'
    ];
    
    echo "<h4>Settings Status:</h4>";
    foreach ($settings as $key => $label) {
        $stmt = $pdo->prepare("SELECT value FROM general_settings WHERE `key` = ?");
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        
        $status = $value ? 'Enabled' : 'Disabled';
        $color = $value ? 'green' : 'orange';
        
        echo "<p style='color: $color;'>• $label: $status</p>";
    }
    
    echo "</div>";
    
    // Test tracking script
    echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>🧪 Test Tracking Script</h3>";
    
    if ($gaId && $gaId != 'GA-XXXXXXXXX') {
        echo "<p>✅ Tracking script sẽ được load với GA ID: $gaId</p>";
        echo "<p>📝 Để test tracking:</p>";
        echo "<ol>";
        echo "<li>Mở website trong browser</li>";
        echo "<li>Mở Developer Tools (F12)</li>";
        echo "<li>Vào tab Console</li>";
        echo "<li>Thực hiện các hành động (click, scroll, form submit)</li>";
        echo "<li>Kiểm tra console để xem tracking events</li>";
        echo "</ol>";
        
        echo "<h4>Test Commands:</h4>";
        echo "<pre style='background: #fff; padding: 10px; border-radius: 5px;'>";
        echo "// Test tracking function\n";
        echo "trackEvent('test_event', {category: 'test', label: 'debug'});\n\n";
        echo "// Check GA object\n";
        echo "console.log(window.gtag);\n\n";
        echo "// Check dataLayer\n";
        echo "console.log(window.dataLayer);\n";
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Tracking script sẽ không được load do chưa có GA ID</p>";
    }
    
    echo "</div>";
    
    // Instructions
    echo "<div style='background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
    echo "<h3>📋 Hướng dẫn Setup</h3>";
    echo "<ol>";
    echo "<li><strong>Lấy Google Analytics ID:</strong></li>";
    echo "<ul>";
    echo "<li>Truy cập <a href='https://analytics.google.com' target='_blank'>Google Analytics</a></li>";
    echo "<li>Vào Admin → Data Streams → Web Stream</li>";
    echo "<li>Copy Measurement ID (G-XXXXXXXXXX)</li>";
    echo "</ul>";
    echo "<li><strong>Cấu hình trong Admin:</strong></li>";
    echo "<ul>";
    echo "<li>Vào Admin Panel → System Setting → Analytics Settings</li>";
    echo "<li>Nhập Google Analytics ID</li>";
    echo "<li>Bật các tracking options cần thiết</li>";
    echo "<li>Lưu settings</li>";
    echo "</ul>";
    echo "<li><strong>Test Tracking:</strong></li>";
    echo "<ul>";
    echo "<li>Mở website và thực hiện các hành động</li>";
    echo "<li>Vào Google Analytics → Real-time → Events</li>";
    echo "<li>Kiểm tra các events được gửi</li>";
    echo "</ul>";
    echo "</ol>";
    echo "</div>";
    
    // Troubleshooting
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #dc3545;'>";
    echo "<h3>🔧 Troubleshooting</h3>";
    echo "<h4>Common Issues:</h4>";
    echo "<ul>";
    echo "<li><strong>Events không hiển thị:</strong> Kiểm tra GA ID và network connection</li>";
    echo "<li><strong>Data attributes không work:</strong> Kiểm tra JavaScript console cho errors</li>";
    echo "<li><strong>Duplicate events:</strong> Kiểm tra event listeners có bị duplicate không</li>";
    echo "<li><strong>GDPR compliance:</strong> Đảm bảo user đã accept cookies</li>";
    echo "</ul>";
    echo "<h4>Debug Commands:</h4>";
    echo "<pre style='background: #fff; padding: 10px; border-radius: 5px;'>";
    echo "// Check if GA is loaded\n";
    echo "typeof gtag !== 'undefined'\n\n";
    echo "// Check dataLayer\n";
    echo "window.dataLayer\n\n";
    echo "// Test custom event\n";
    echo "gtag('event', 'test', {category: 'test'})\n";
    echo "</pre>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
} 