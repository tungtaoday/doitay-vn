<?php

echo "=== TESTING GMAIL SMTP ===\n\n";

// Test với fsockopen trước
echo "1. Testing basic connection to Gmail SMTP...\n";
$connection = @fsockopen('smtp.gmail.com', 587, $errno, $errstr, 10);
if ($connection) {
    echo "✅ Can connect to smtp.gmail.com:587\n";
    fclose($connection);
} else {
    echo "❌ Cannot connect to smtp.gmail.com:587\n";
    echo "Error: $errstr ($errno)\n";
}

echo "\n2. Checking PHP extensions...\n";
$required_extensions = ['openssl', 'sockets'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext extension loaded\n";
    } else {
        echo "❌ $ext extension NOT loaded\n";
    }
}

echo "\n3. Testing with cURL (alternative check)...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "smtp.gmail.com:587");
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "cURL connection: ❌ $error\n";
} else {
    echo "✅ cURL can reach Gmail SMTP\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Kiểm tra App Password Gmail có đúng không\n";
echo "2. Đảm bảo 2-Step Verification đã bật cho Gmail\n";
echo "3. Tạo App Password mới tại: https://myaccount.google.com/apppasswords\n";
echo "4. Kiểm tra Windows Firewall/Antivirus không block SMTP\n";
echo "5. Thử test gửi email từ Admin Panel trực tiếp\n";

?> 