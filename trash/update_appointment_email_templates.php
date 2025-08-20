<?php
// Script cập nhật email templates với logo và styling đẹp
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🎨 Cập nhật Email Templates với Logo & Styling Đẹp</h2>";
    
    // Template email tiếng Việt với logo và styling chuyên nghiệp
    $emailTemplates = [
        'APPOINTMENT_CANCELED' => [
            'subject' => 'Doitay.vn - Lịch hẹn đã bị hủy',
            'email_body' => '
                <div class="greeting">Xin chào <strong>{{user_name}}</strong>,</div>
                
                <div class="warning-box">
                    <h3>⚠️ Lịch hẹn của bạn đã bị hủy</h3>
                    <p>Chúng tôi rất tiếc phải thông báo rằng lịch hẹn của bạn đã bị hủy.</p>
                </div>
                
                <div class="appointment-details">
                    <h3>📅 Chi tiết lịch hẹn đã hủy</h3>
                    <div class="detail-row">
                        <span class="detail-label">Mã lịch hẹn:</span>
                        <span class="detail-value">#{{appointment_id}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ngày hẹn:</span>
                        <span class="detail-value">{{appointment_date}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Giờ hẹn:</span>
                        <span class="detail-value">{{appointment_time}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nhà thầu:</span>
                        <span class="detail-value">{{company_name}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Lý do hủy:</span>
                        <span class="detail-value">{{cancellation_reason}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Trạng thái:</span>
                        <span class="detail-value" style="color: #e74c3c; font-weight: bold;">❌ Đã hủy</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <h3>💡 Bạn có thể làm gì bây giờ:</h3>
                    <ul>
                        <li>Đặt lịch hẹn mới với cùng hoặc nhà thầu khác</li>
                        <li>Liên hệ trực tiếp với {{company_name}} để sắp xếp lại</li>
                        <li>Xem danh sách các nhà thầu khác trên hệ thống</li>
                        <li>Liên hệ đội ngũ hỗ trợ nếu cần trợ giúp</li>
                    </ul>
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{book_new_url}}" class="btn btn-primary">Đặt lịch hẹn mới</a>
                    <a href="{{contact_company_url}}" class="btn">Liên hệ nhà thầu</a>
                </div>
                
                <p>Chúng tôi xin lỗi vì sự bất tiện này. Đội ngũ của chúng tôi luôn sẵn sàng hỗ trợ bạn tìm giải pháp thay thế.</p>
                
                <p style="margin-top: 30px;">
                    <strong>Trân trọng,</strong><br>
                    Đội ngũ {{site_name}}
                </p>
            '
        ],
        'NEW_APPOINTMENT' => [
            'subject' => 'Doitay.vn - Đặt lịch hẹn thành công',
            'email_body' => '
                <div class="greeting">Xin chào <strong>{{user_name}}</strong>,</div>
                
                <div class="success-box">
                    <h3>🎉 Lịch hẹn của bạn đã được tạo thành công!</h3>
                    <p>Chúng tôi đã nhận được yêu cầu đặt lịch hẹn của bạn và rất vui mừng được phục vụ bạn.</p>
                </div>
                
                <div class="appointment-details">
                    <h3>📅 Chi tiết lịch hẹn</h3>
                    <div class="detail-row">
                        <span class="detail-label">Mã lịch hẹn:</span>
                        <span class="detail-value">#{{appointment_id}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ngày hẹn:</span>
                        <span class="detail-value">{{appointment_date}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Giờ hẹn:</span>
                        <span class="detail-value">{{appointment_time}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nhà thầu:</span>
                        <span class="detail-value">{{company_name}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Địa chỉ:</span>
                        <span class="detail-value">{{appointment_address}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Trạng thái:</span>
                        <span class="detail-value" style="color: #f39c12; font-weight: bold;">⏳ Chờ xác nhận</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <h3>📝 Lưu ý quan trọng</h3>
                    <ul>
                        <li>Nhà thầu sẽ xem xét và xác nhận lịch hẹn trong vòng 2-4 giờ</li>
                        <li>Bạn sẽ nhận được thông báo khi lịch hẹn được xác nhận</li>
                        <li>Nhà thầu sẽ liên hệ trực tiếp với bạn để thống nhất chi tiết</li>
                        <li>Vui lòng kiểm tra tình trạng giao thông trước khi di chuyển</li>
                    </ul>
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{appointment_url}}" class="btn btn-success">Xem chi tiết lịch hẹn</a>
                    <a href="{{reschedule_url}}" class="btn">Đổi lịch hẹn</a>
                </div>
                
                <p>Chúng tôi rất mong được phục vụ bạn! Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi ngay lập tức.</p>
                
                <p style="margin-top: 30px;">
                    <strong>Trân trọng,</strong><br>
                    Đội ngũ {{site_name}}
                </p>
            '
        ],
        'APPOINTMENT_CONFIRMED' => [
            'subject' => 'Doitay.vn - Lịch hẹn đã được xác nhận',
            'email_body' => '
                <div class="greeting">Xin chào <strong>{{user_name}}</strong>,</div>
                
                <div class="success-box">
                    <h3>✅ Lịch hẹn của bạn đã được xác nhận!</h3>
                    <p>Tin vui! Lịch hẹn của bạn với {{company_name}} đã được xác nhận.</p>
                </div>
                
                <div class="appointment-details">
                    <h3>📅 Chi tiết lịch hẹn đã xác nhận</h3>
                    <div class="detail-row">
                        <span class="detail-label">Mã lịch hẹn:</span>
                        <span class="detail-value">#{{appointment_id}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ngày hẹn:</span>
                        <span class="detail-value">{{appointment_date}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Giờ hẹn:</span>
                        <span class="detail-value">{{appointment_time}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nhà thầu:</span>
                        <span class="detail-value">{{company_name}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Địa chỉ:</span>
                        <span class="detail-value">{{appointment_address}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Trạng thái:</span>
                        <span class="detail-value" style="color: #27ae60; font-weight: bold;">✅ Đã xác nhận</span>
                    </div>
                </div>
                
                <div class="info-box">
                    <h3>📝 Lưu ý quan trọng</h3>
                    <ul>
                        <li>Vui lòng đến sớm 10-15 phút</li>
                        <li>Mang theo giấy tờ tùy thân và các tài liệu cần thiết</li>
                        <li>Liên hệ nhà thầu nếu cần đổi lịch hẹn</li>
                        <li>Kiểm tra tình trạng giao thông trước khi di chuyển</li>
                    </ul>
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{appointment_url}}" class="btn btn-success">Xem chi tiết lịch hẹn</a>
                    <a href="{{reschedule_url}}" class="btn">Đổi lịch hẹn</a>
                </div>
                
                <p>Chúng tôi rất mong được phục vụ bạn! Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi ngay lập tức.</p>
                
                <p style="margin-top: 30px;">
                    <strong>Trân trọng,</strong><br>
                    Đội ngũ {{site_name}}
                </p>
            '
        ],
        'APPOINTMENT_COMPLETED' => [
            'subject' => 'Doitay.vn - Lịch hẹn đã hoàn thành',
            'email_body' => '
                <div class="greeting">Xin chào <strong>{{user_name}}</strong>,</div>
                
                <div class="success-box">
                    <h3>🎊 Cảm ơn bạn đã chọn {{site_name}}!</h3>
                    <p>Lịch hẹn của bạn với {{company_name}} đã được hoàn thành thành công.</p>
                </div>
                
                <div class="appointment-details">
                    <h3>📋 Tóm tắt lịch hẹn đã hoàn thành</h3>
                    <div class="detail-row">
                        <span class="detail-label">Mã lịch hẹn:</span>
                        <span class="detail-value">#{{appointment_id}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ngày hoàn thành:</span>
                        <span class="detail-value">{{appointment_date}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nhà thầu:</span>
                        <span class="detail-value">{{company_name}}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Trạng thái:</span>
                        <span class="detail-value" style="color: #27ae60; font-weight: bold;">✅ Đã hoàn thành</span>
                    </div>
                </div>
                
                <div class="highlight-box">
                    <h3>⭐ Trải nghiệm của bạn thế nào?</h3>
                    <p>Phản hồi của bạn giúp chúng tôi cải thiện dịch vụ và giúp người dùng khác đưa ra quyết định sáng suốt.</p>
                    <a href="{{review_url}}" class="btn" style="background: white; color: #333; margin-top: 15px;">Để lại đánh giá</a>
                </div>
                
                <div class="info-box">
                    <h3>🚀 Bước tiếp theo:</h3>
                    <ul>
                        <li>Đánh giá trải nghiệm với {{company_name}}</li>
                        <li>Chia sẻ phản hồi để giúp người dùng khác</li>
                        <li>Đặt lịch hẹn tiếp theo nếu cần</li>
                        <li>Giới thiệu bạn bè và gia đình đến {{site_name}}</li>
                    </ul>
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{book_again_url}}" class="btn btn-primary">Đặt lại</a>
                    <a href="{{browse_services_url}}" class="btn">Xem dịch vụ</a>
                </div>
                
                <p>Cảm ơn bạn đã tin tưởng {{site_name}} với nhu cầu dịch vụ của mình. Chúng tôi hy vọng sẽ được phục vụ bạn sớm!</p>
                
                <p style="margin-top: 30px;">
                    <strong>Với lòng biết ơn,</strong><br>
                    Đội ngũ {{site_name}}
                </p>
            '
        ]
    ];
    
    $updated = 0;
    
    foreach ($emailTemplates as $act => $template) {
        echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
        echo "<h3>🔄 Cập nhật template: {$act}</h3>";
        
        // Kiểm tra template có tồn tại không
        $stmt = $pdo->prepare("SELECT id FROM notification_templates WHERE act = ?");
        $stmt->execute([$act]);
        $existingTemplate = $stmt->fetch();
        
        if ($existingTemplate) {
            // Cập nhật template hiện có
            $stmt = $pdo->prepare("
                UPDATE notification_templates 
                SET subject = ?, email_body = ?, updated_at = NOW()
                WHERE act = ?
            ");
            
            $result = $stmt->execute([
                $template['subject'],
                $template['email_body'],
                $act
            ]);
            
            if ($result) {
                echo "<p style='color: green;'>✅ Đã cập nhật template: {$act}</p>";
                echo "<p><strong>Subject:</strong> {$template['subject']}</p>";
                $updated++;
            } else {
                echo "<p style='color: red;'>❌ Lỗi khi cập nhật template: {$act}</p>";
            }
        } else {
            echo "<p style='color: orange;'>⚠️ Template {$act} không tồn tại, bỏ qua</p>";
        }
        
        echo "</div>";
    }
    
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>🎉 Hoàn thành cập nhật Email Templates!</h3>";
    echo "<ul>";
    echo "<li>📧 <strong>Templates đã cập nhật:</strong> {$updated}</li>";
    echo "<li>🇻🇳 <strong>Nội dung tiếng Việt:</strong> Tất cả templates đã được Việt hóa</li>";
    echo "<li>🎨 <strong>Styling chuyên nghiệp:</strong> Sử dụng CSS classes đẹp</li>";
    echo "<li>🖼️ <strong>Logo tích hợp:</strong> Sẽ hiển thị logo Doitay.vn</li>";
    echo "<li>📱 <strong>Responsive:</strong> Email hiển thị đẹp trên mọi thiết bị</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
    echo "<h4>🎯 Các templates đã được cập nhật:</h4>";
    echo "<ul>";
    foreach (array_keys($emailTemplates) as $template) {
        echo "<li><strong>{$template}:</strong> " . $emailTemplates[$template]['subject'] . "</li>";
    }
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>🔧 Lưu ý kỹ thuật:</h4>";
    echo "<ul>";
    echo "<li>Email templates sẽ được wrap với professional wrapper có logo</li>";
    echo "<li>Shortcodes sẽ được replace tự động: {{user_name}}, {{appointment_id}}, etc.</li>";
    echo "<li>Logo sẽ hiển thị từ: {{site_url}}/assets/images/logo_icon/logo.png</li>";
    echo "<li>Styling responsive cho mobile và desktop</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
} 
 