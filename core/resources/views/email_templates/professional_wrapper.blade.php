<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{subject}}</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Header */
        .email-header {
            background: #102f4b;
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .tagline {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        /* Content */
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #102f4b;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .content-section {
            margin-bottom: 25px;
        }
        
        .content-section h2 {
            color: #34495e;
            font-size: 20px;
            margin-bottom: 15px;
            border-bottom: 2px solid #48bbe2;
            padding-bottom: 5px;
        }
        
        .content-section h3 {
            color: #2980b9;
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .content-section p {
            margin-bottom: 15px;
            line-height: 1.7;
        }
        
        .content-section ul {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        
        .content-section li {
            margin-bottom: 8px;
        }
        
        /* Highlight boxes */
        .highlight-box {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .info-box {
            background-color: #ecf0f1;
            border-left: 4px solid #48bbe2;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .success-box {
            background-color: #d5f4e6;
            border-left: 4px solid #27ae60;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        
        .warning-box {
            background-color: #fef9e7;
            border-left: 4px solid #f39c12;
            padding: 15px 20px;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        
        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #102f4b;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            text-align: center;
            transition: transform 0.2s;
            margin: 10px 5px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #48bbe2 0%, #2980b9 100%);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        
        /* Appointment details */
        .appointment-details {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        
        .appointment-details h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }
        
        .detail-value {
            color: #495057;
            font-weight: 500;
        }
        
        /* Footer */
        .email-footer {
            background-color: #102f4b;
            color: #ecf0f1;
            padding: 30px 20px;
            text-align: center;
        }
        
        .footer-links {
            margin-bottom: 20px;
        }
        
        .footer-links a {
            color: #48bbe2;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
        }
        
        .footer-links a:hover {
            color: #74b9ff;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #bdc3c7;
            font-size: 18px;
            text-decoration: none;
        }
        
        .footer-text {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 20px;
            line-height: 1.5;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }
            
            .email-content {
                padding: 20px 15px;
            }
            
            .email-header {
                padding: 20px 15px;
            }
            
            .company-name {
                font-size: 24px;
            }
            
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                margin-bottom: 5px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .email-content {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="{{logo_url}}" alt="{{site_name}} Logo" class="logo" />
            <div class="company-name">{{site_name}}</div>
            <div class="tagline">Your Trusted Service Platform</div>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            {!! $email_body !!}
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-links">
                <a href="{{site_url}}">Home</a>
                <a href="{{site_url}}/contact">Contact</a>
                <a href="{{site_url}}/about">About</a>
                <a href="{{site_url}}/privacy">Privacy</a>
            </div>
            
            <div class="social-links">
                <a href="#" title="Facebook">📘</a>
                <a href="#" title="Twitter">🐦</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="LinkedIn">💼</a>
            </div>
            
            <div class="footer-text">
                © {{current_year}} {{site_name}}. All rights reserved.<br>
                This email was sent to {{user_email}}. If you no longer wish to receive these emails, 
                <a href="{{unsubscribe_url}}" style="color: #48bbe2;">unsubscribe here</a>.
            </div>
        </div>
    </div>
</body>
</html> 