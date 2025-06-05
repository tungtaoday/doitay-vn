<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với {{ $website_name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }
        .login-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .login-box h3 {
            margin-top: 0;
            color: #667eea;
        }
        .login-info {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            background-color: #fff;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin: 10px 0;
        }
        .login-info strong {
            color: #333;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ffeaa7;
            margin: 15px 0;
        }
        .security-note {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Chào mừng đến với {{ $website_name }}!</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px;">Tài khoản của bạn đã được tạo thành công</p>
        </div>
        
        <div class="content">
            <p class="welcome-message">
                Xin chào <strong>{{ $user->firstname }} {{ $user->lastname }}</strong>,
            </p>
            
            <p>Cảm ơn bạn đã sử dụng dịch vụ của {{ $website_name }}! Tài khoản của bạn đã được tạo thành công khi bạn tạo lead.</p>
            
            <div class="login-box">
                <h3>🔑 Thông tin đăng nhập của bạn:</h3>
                
                <div class="login-info">
                    <strong>Tài khoản:</strong> {{ $user->mobile }}
                </div>
                
                <div class="login-info">
                    <strong>Mật khẩu:</strong> {{ $password }}
                </div>
                
                <div class="login-info">
                    <strong>Email:</strong> {{ $user->email }}
                </div>
            </div>
            
            <div class="security-note">
                <strong>🔒 Lưu ý bảo mật:</strong>
                <br>Vui lòng đổi mật khẩu sau lần đăng nhập đầu tiên để bảo mật tài khoản của bạn.
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $login_url }}" class="button">
                    🚀 Đăng nhập ngay
                </a>
            </div>
            
            <div class="highlight">
                <strong>📱 Bạn có thể sử dụng tài khoản này để:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Quản lý các lead đã tạo</li>
                    <li>Theo dõi tình trạng báo giá từ thợ</li>
                    <li>Đánh giá và phản hồi về dịch vụ</li>
                    <li>Tạo thêm lead mới dễ dàng hơn</li>
                    <li>Cập nhật thông tin cá nhân</li>
                </ul>
            </div>
            
            <p style="margin-top: 30px;">
                Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua:
            </p>
            
            <ul style="color: #666; line-height: 1.6;">
                <li>📧 Email: support@doitay.vn</li>
                <li>📞 Hotline: 1900-XXXX</li>
                <li>💬 Live Chat trên website</li>
            </ul>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">
                © {{ date('Y') }} {{ $website_name }}. Tất cả quyền được bảo lưu.
            </p>
            <p style="margin: 5px 0 0 0;">
                Email này được gửi tự động, vui lòng không trả lời.
            </p>
        </div>
    </div>
</body>
</html> 