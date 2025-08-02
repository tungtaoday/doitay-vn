<?php
// Kiểm tra toàn diện hệ thống notification email
echo "=== 🔍 CHECK NOTIFICATION EMAIL SYSTEM ===\n\n";

require_once 'core/vendor/autoload.php';

// Bootstrap Laravel
try {
    $app = require_once 'core/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n\n";
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n\n";
}

// 1. Kiểm tra database tables
echo "1. 🗄️ KIỂM TRA DATABASE TABLES:\n";
echo "================================\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection: SUCCESS\n";
    
    // Check tables
    $tables = ['notifications', 'device_tokens', 'appointments', 'users', 'companies'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        echo ($exists ? "✅" : "❌") . " Table $table: " . ($exists ? "EXISTS" : "NOT FOUND") . "\n";
        
        if ($exists) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "   - Records: " . $result['count'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// 2. Kiểm tra email configuration
echo "\n2. 📧 KIỂM TRA EMAIL CONFIGURATION:\n";
echo "===================================\n";

try {
    // Check database mail config
    $stmt = $pdo->query("SELECT mail_config, email_from FROM general_settings WHERE id = 1");
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($config) {
        echo "✅ Database mail config:\n";
        echo "   - email_from: " . $config['email_from'] . "\n";
        
        $mailConfig = json_decode($config['mail_config'], true);
        if ($mailConfig) {
            echo "   - host: " . ($mailConfig['host'] ?? 'not set') . "\n";
            echo "   - port: " . ($mailConfig['port'] ?? 'not set') . "\n";
            echo "   - username: " . ($mailConfig['username'] ?? 'not set') . "\n";
            echo "   - encryption: " . ($mailConfig['enc'] ?? 'not set') . "\n";
        }
    } else {
        echo "❌ No mail config in database\n";
    }
    
    // Check .env file
    if (file_exists('core/.env')) {
        $envContent = file_get_contents('core/.env');
        echo "\n✅ .env file exists\n";
        
        $envVars = ['MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_FROM_ADDRESS'];
        foreach ($envVars as $var) {
            if (preg_match("/^$var=(.*)$/m", $envContent, $matches)) {
                $value = trim($matches[1], '"\'');
                echo "   - $var: " . ($value ?: 'not set') . "\n";
            } else {
                echo "   - $var: not found\n";
            }
        }
    } else {
        echo "❌ .env file not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Config check error: " . $e->getMessage() . "\n";
}

// 3. Test email sending
echo "\n3. 📤 TEST EMAIL SENDING:\n";
echo "=========================\n";

try {
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    
    $mail = new PHPMailer(true);
    
    // ElasticEmail configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.elasticemail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'hotro@doitay.vn';
    $mail->Password = 'ED430DB8FF4A17CCE00E2B4D10D45161E9FD';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;
    
    $mail->setFrom('hotro@doitay.vn', 'DoiTay Support');
    $mail->addAddress('nguyentung0910@gmail.com', 'Tung Test');
    
    $mail->isHTML(true);
    $mail->Subject = '🧪 Notification Test - ' . date('H:i:s d/m/Y');
    $mail->Body = '<h2>🧪 Notification Email Test</h2><p>Testing notification email system.</p>';
    
    $result = $mail->send();
    
    if ($result) {
        echo "✅ Email sending: SUCCESS\n";
    } else {
        echo "❌ Email sending: FAILED\n";
    }
    
} catch (Exception $e) {
    echo "❌ Email error: " . $e->getMessage() . "\n";
}

// 4. Kiểm tra Notification classes
echo "\n4. 📨 KIỂM TRA NOTIFICATION CLASSES:\n";
echo "====================================\n";

$notificationPaths = [
    'core/app/Notifications/',
    'core/app/Mail/',
    'core/app/Http/Controllers/'
];

foreach ($notificationPaths as $path) {
    if (is_dir($path)) {
        echo "✅ Directory $path exists\n";
        $files = scandir($path);
        $phpFiles = array_filter($files, function($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });
        echo "   - PHP files: " . count($phpFiles) . "\n";
        foreach ($phpFiles as $file) {
            echo "   - $file\n";
        }
    } else {
        echo "❌ Directory $path not found\n";
    }
}

// 5. Test Laravel notification
echo "\n5. 🧪 TEST LARAVEL NOTIFICATION:\n";
echo "=================================\n";

try {
    // Test if User model exists and has notification trait
    if (class_exists('App\Models\User')) {
        echo "✅ User model exists\n";
        
        $user = App\Models\User::first();
        if ($user) {
            echo "✅ User found: " . $user->email . "\n";
            
            // Check if user has notifiable trait
            if (method_exists($user, 'notify')) {
                echo "✅ User has notifiable trait\n";
                
                // Test simple notification
                try {
                    // Create a simple notification
                    $notification = new Illuminate\Notifications\Messages\MailMessage();
                    $notification->subject('Test Notification')
                               ->line('This is a test notification.')
                               ->action('View Details', url('/'))
                               ->line('Thank you for testing!');
                    
                    echo "✅ Notification created\n";
                    
                    // Try to send notification
                    $user->notify($notification);
                    echo "✅ Notification sent successfully\n";
                    
                } catch (Exception $e) {
                    echo "❌ Notification send error: " . $e->getMessage() . "\n";
                }
                
            } else {
                echo "❌ User missing notifiable trait\n";
            }
        } else {
            echo "❌ No users found\n";
        }
    } else {
        echo "❌ User model not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel notification error: " . $e->getMessage() . "\n";
}

// 6. Kiểm tra appointment creation trigger
echo "\n6. 🗓️ KIỂM TRA APPOINTMENT TRIGGER:\n";
echo "===================================\n";

try {
    // Check appointment controller
    $controllerPaths = [
        'core/app/Http/Controllers/AppointmentController.php',
        'core/app/Http/Controllers/AppointmentsController.php'
    ];
    
    foreach ($controllerPaths as $controllerPath) {
        if (file_exists($controllerPath)) {
            echo "✅ Controller found: $controllerPath\n";
            
            $content = file_get_contents($controllerPath);
            
            // Check for notification in controller
            if (strpos($content, 'notify') !== false || strpos($content, 'Notification') !== false) {
                echo "✅ Controller contains notification code\n";
            } else {
                echo "❌ Controller missing notification code\n";
            }
            
            // Check for email sending
            if (strpos($content, 'mail') !== false || strpos($content, 'Mail') !== false) {
                echo "✅ Controller contains email code\n";
            } else {
                echo "❌ Controller missing email code\n";
            }
            
        } else {
            echo "❌ Controller not found: $controllerPath\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Controller check error: " . $e->getMessage() . "\n";
}

// 7. Create fixed appointment endpoint với notification
echo "\n7. 🔧 CREATE FIXED APPOINTMENT ENDPOINT:\n";
echo "========================================\n";

$fixedEndpoint = '<?php
// Fixed appointment endpoint với email notification
header("Content-Type: application/json");

try {
    require_once "core/vendor/autoload.php";
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    
    // Bootstrap Laravel
    $app = require_once "core/bootstrap/app.php";
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", "root", "Vuivui@123");
    
    // Get POST data
    $postData = json_decode(file_get_contents("php://input"), true);
    
    // Required fields
    $requiredFields = [
        "user_id", "company_id", "recipient_name", 
        "recipient_phone", "recipient_address", 
        "appointment_date", "appointment_time"
    ];
    
    // Check required fields
    foreach ($requiredFields as $field) {
        if (!isset($postData[$field]) || empty($postData[$field])) {
            echo json_encode([
                "success" => false,
                "message" => "Missing required field: $field"
            ]);
            exit;
        }
    }
    
    // Prepare appointment data
    $appointmentData = [
        "user_id" => $postData["user_id"],
        "company_id" => $postData["company_id"],
        "recipient_name" => $postData["recipient_name"],
        "recipient_phone" => $postData["recipient_phone"],
        "recipient_address" => $postData["recipient_address"],
        "appointment_date" => $postData["appointment_date"],
        "appointment_time" => $postData["appointment_time"],
        "status" => isset($postData["status"]) ? $postData["status"] : "pending",
        "notes" => isset($postData["notes"]) ? $postData["notes"] : ""
    ];
    
    // Insert appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id, company_id, recipient_name, recipient_phone, recipient_address, appointment_date, appointment_time, status, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute([
        $appointmentData["user_id"],
        $appointmentData["company_id"],
        $appointmentData["recipient_name"],
        $appointmentData["recipient_phone"],
        $appointmentData["recipient_address"],
        $appointmentData["appointment_date"],
        $appointmentData["appointment_time"],
        $appointmentData["status"],
        $appointmentData["notes"]
    ]);
    
    if ($result) {
        $appointmentId = $pdo->lastInsertId();
        
        // Get user and company info
        $userStmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
        $userStmt->execute([$appointmentData["user_id"]]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);
        
        $companyStmt = $pdo->prepare("SELECT name, email FROM companies WHERE id = ?");
        $companyStmt->execute([$appointmentData["company_id"]]);
        $company = $companyStmt->fetch(PDO::FETCH_ASSOC);
        
        // Send email notifications
        $emailResults = [];
        
        // 1. Email to user
        if ($user && $user["email"]) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = "smtp.elasticemail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "hotro@doitay.vn";
                $mail->Password = "ED430DB8FF4A17CCE00E2B4D10D45161E9FD";
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 2525;
                
                $mail->setFrom("hotro@doitay.vn", "DoiTay Support");
                $mail->addAddress($user["email"], $user["name"]);
                
                $mail->isHTML(true);
                $mail->Subject = "🗓️ Appointment Confirmed - " . $appointmentData["appointment_date"];
                $mail->Body = "
                <h2>🗓️ Your Appointment Has Been Created</h2>
                <p>Dear {$user["name"]},</p>
                <p>Your appointment has been successfully created.</p>
                <hr>
                <h3>📋 Appointment Details:</h3>
                <p><strong>Appointment ID:</strong> $appointmentId</p>
                <p><strong>Date:</strong> {$appointmentData["appointment_date"]}</p>
                <p><strong>Time:</strong> {$appointmentData["appointment_time"]}</p>
                <p><strong>Company:</strong> {$company["name"]}</p>
                <p><strong>Recipient:</strong> {$appointmentData["recipient_name"]}</p>
                <p><strong>Phone:</strong> {$appointmentData["recipient_phone"]}</p>
                <p><strong>Address:</strong> {$appointmentData["recipient_address"]}</p>
                <p><strong>Status:</strong> {$appointmentData["status"]}</p>
                <hr>
                <p>Thank you for using DoiTay.vn!</p>
                ";
                
                $mail->send();
                $emailResults["user"] = "sent";
            } catch (Exception $e) {
                $emailResults["user"] = "failed: " . $e->getMessage();
            }
        }
        
        // 2. Email to company
        if ($company && $company["email"]) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = "smtp.elasticemail.com";
                $mail->SMTPAuth = true;
                $mail->Username = "hotro@doitay.vn";
                $mail->Password = "ED430DB8FF4A17CCE00E2B4D10D45161E9FD";
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 2525;
                
                $mail->setFrom("hotro@doitay.vn", "DoiTay Support");
                $mail->addAddress($company["email"], $company["name"]);
                
                $mail->isHTML(true);
                $mail->Subject = "📞 New Appointment Request - " . $appointmentData["appointment_date"];
                $mail->Body = "
                <h2>📞 New Appointment Request</h2>
                <p>Dear {$company["name"]},</p>
                <p>You have received a new appointment request.</p>
                <hr>
                <h3>📋 Appointment Details:</h3>
                <p><strong>Appointment ID:</strong> $appointmentId</p>
                <p><strong>Date:</strong> {$appointmentData["appointment_date"]}</p>
                <p><strong>Time:</strong> {$appointmentData["appointment_time"]}</p>
                <p><strong>Customer:</strong> {$user["name"]} ({$user["email"]})</p>
                <p><strong>Recipient:</strong> {$appointmentData["recipient_name"]}</p>
                <p><strong>Phone:</strong> {$appointmentData["recipient_phone"]}</p>
                <p><strong>Address:</strong> {$appointmentData["recipient_address"]}</p>
                <p><strong>Status:</strong> {$appointmentData["status"]}</p>
                <p><strong>Notes:</strong> {$appointmentData["notes"]}</p>
                <hr>
                <p>Please contact the customer to confirm the appointment.</p>
                ";
                
                $mail->send();
                $emailResults["company"] = "sent";
            } catch (Exception $e) {
                $emailResults["company"] = "failed: " . $e->getMessage();
            }
        }
        
        echo json_encode([
            "success" => true,
            "message" => "Appointment created successfully",
            "appointment_id" => $appointmentId,
            "data" => $appointmentData,
            "email_notifications" => $emailResults
        ]);
        
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to create appointment",
            "error" => $stmt->errorInfo()
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage(),
        "trace" => $e->getTraceAsString()
    ]);
}
?>';

file_put_contents('appointment_with_notification.php', $fixedEndpoint);
echo "✅ Fixed appointment endpoint created: appointment_with_notification.php\n";

echo "\n=== 🚀 NOTIFICATION CHECK COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Test appointment_with_notification.php\n";
echo "2. Check email notifications are sent\n";
echo "3. Update frontend to use new endpoint\n";
echo "4. Deploy to production\n";
echo "5. Monitor email delivery\n";
?> 
 