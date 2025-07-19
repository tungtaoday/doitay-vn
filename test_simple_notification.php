<?php
echo "Testing Notification API\n";
echo "========================\n\n";

// Test the notification endpoint
$url = 'http://localhost/user/notifications/header-data';
echo "Testing URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "HTTP Status: $httpCode\n";
echo "cURL Error: " . ($error ?: 'None') . "\n";
echo "Response Length: " . strlen($response) . " bytes\n";
echo "Response Content:\n";
echo "----------------\n";
echo $response . "\n";

// Test if it's a authentication issue
if ($httpCode === 302 || $httpCode === 401) {
    echo "\n⚠️  This looks like an authentication issue.\n";
    echo "The notification API requires a logged-in user.\n";
    echo "Make sure you're logged in when testing from browser.\n";
} elseif ($httpCode === 500) {
    echo "\n❌ Server error detected.\n";
    echo "Check Laravel logs for detailed error information.\n";
} elseif ($httpCode === 200) {
    echo "\n✅ API endpoint is working!\n";
}

echo "\nTest completed.\n";
?> 