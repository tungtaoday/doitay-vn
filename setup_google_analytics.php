<?php
// Script setup Google Analytics tracking
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🔧 Setup Google Analytics Tracking</h2>";
    
    // Google Analytics ID (thay thế bằng ID thật của bạn)
    $gaId = 'G-XXXXXXXXXX'; // Thay thế bằng Google Analytics ID thật
    
    // Kiểm tra xem đã có GA ID chưa
    $stmt = $pdo->prepare("SELECT value FROM general_settings WHERE `key` = 'google_analytics_id'");
    $stmt->execute();
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Cập nhật GA ID
        $stmt = $pdo->prepare("UPDATE general_settings SET value = ? WHERE `key` = 'google_analytics_id'");
        $result = $stmt->execute([$gaId]);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Đã cập nhật Google Analytics ID: $gaId</p>";
        } else {
            echo "<p style='color: red;'>❌ Lỗi khi cập nhật GA ID</p>";
        }
    } else {
        // Tạo mới GA ID
        $stmt = $pdo->prepare("INSERT INTO general_settings (`key`, value) VALUES ('google_analytics_id', ?)");
        $result = $stmt->execute([$gaId]);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Đã tạo Google Analytics ID: $gaId</p>";
        } else {
            echo "<p style='color: red;'>❌ Lỗi khi tạo GA ID</p>";
        }
    }
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>🎯 Google Analytics Tracking đã được setup!</h3>";
    echo "<ul>";
    echo "<li>📊 <strong>Page Views:</strong> Tự động track tất cả page views</li>";
    echo "<li>👥 <strong>User Types:</strong> Track user_type (guest, user, company)</li>";
    echo "<li>📱 <strong>Page Categories:</strong> Track loại trang (home, company, appointment)</li>";
    echo "<li>🎯 <strong>Custom Events:</strong> Track từng điểm chạm cụ thể</li>";
    echo "<li>📈 <strong>Enhanced Ecommerce:</strong> Track appointment như transactions</li>";
    echo "<li>⏱️ <strong>Engagement:</strong> Track scroll depth và time on page</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
    echo "<h4>🎯 Các Events được Track:</h4>";
    echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 15px;'>";
    echo "<div>";
    echo "<h5>📅 Appointment Events:</h5>";
    echo "<ul>";
    echo "<li>appointment_booking_started</li>";
    echo "<li>appointment_booking_submitted</li>";
    echo "<li>appointment_confirmation</li>";
    echo "<li>appointment_cancellation</li>";
    echo "<li>appointment_completion</li>";
    echo "<li>appointment_reschedule</li>";
    echo "</ul>";
    echo "</div>";
    echo "<div>";
    echo "<h5>🏢 Company Events:</h5>";
    echo "<ul>";
    echo "<li>company_card_click</li>";
    echo "<li>company_profile_view</li>";
    echo "<li>company_contact</li>";
    echo "<li>company_rating</li>";
    echo "<li>service_category_click</li>";
    echo "</ul>";
    echo "</div>";
    echo "<div>";
    echo "<h5>👤 User Events:</h5>";
    echo "<ul>";
    echo "<li>user_registration</li>";
    echo "<li>user_login</li>";
    echo "<li>user_logout</li>";
    echo "<li>profile_update</li>";
    echo "<li>password_change</li>";
    echo "</ul>";
    echo "</div>";
    echo "<div>";
    echo "<h5>🔍 Search Events:</h5>";
    echo "<ul>";
    echo "<li>search_submitted</li>";
    echo "<li>search_filter_changed</li>";
    echo "<li>scroll_depth</li>";
    echo "<li>time_on_page</li>";
    echo "<li>form_submit</li>";
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>🔧 Hướng dẫn sử dụng:</h4>";
    echo "<ol>";
    echo "<li><strong>Thay đổi GA ID:</strong> Cập nhật biến \$gaId trong script này</li>";
    echo "<li><strong>Test Tracking:</strong> Mở website và thực hiện các hành động</li>";
    echo "<li><strong>Kiểm tra GA:</strong> Vào Google Analytics > Real-time > Events</li>";
    echo "<li><strong>Custom Tracking:</strong> Sử dụng data attributes trong HTML</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>📝 Data Attributes để sử dụng:</h4>";
    echo "<pre>";
    echo "<!-- Form tracking -->\n";
    echo "&lt;form data-track-type=\"appointment\"&gt;\n\n";
    echo "<!-- Button tracking -->\n";
    echo "&lt;button data-track-appointment-action=\"cancel\" data-appointment-id=\"123\"&gt;\n\n";
    echo "<!-- Company tracking -->\n";
    echo "&lt;div data-track-company-card data-company-id=\"456\" data-company-name=\"ABC Company\"&gt;\n\n";
    echo "<!-- User tracking -->\n";
    echo "&lt;form data-track-type=\"registration\" data-user-type=\"company\"&gt;\n\n";
    echo "<!-- Search tracking -->\n";
    echo "&lt;form data-track-type=\"search\"&gt;\n";
    echo "</pre>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
} 
 