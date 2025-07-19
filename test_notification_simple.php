<?php
// Simple test for notification API
echo "<h1>Testing Notification API</h1>";

// Test 1: Direct database check
echo "<h2>1. Database Test</h2>";
require_once 'core/bootstrap/app.php';

try {
    $count = \App\Models\UserNotification::count();
    echo "<p>✅ UserNotification table exists. Total records: $count</p>";
    
    // Create a test notification for user ID 1 if exists
    $user = \App\Models\User::first();
    if ($user) {
        $testNotification = \App\Models\UserNotification::create([
            'user_id' => $user->id,
            'user_type' => 'user',
            'type' => 'test',
            'title' => 'Test Notification',
            'message' => 'This is a test notification to verify the system works',
            'icon' => '🧪',
            'color' => 'blue',
            'priority' => 'normal',
            'is_important' => false
        ]);
        echo "<p>✅ Test notification created with ID: {$testNotification->id}</p>";
    } else {
        echo "<p>⚠️ No users found in database</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 2: API endpoint test
echo "<h2>2. API Endpoint Test</h2>";

$url = 'http://localhost/user/notifications/header-data';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>URL:</strong> $url</p>";
echo "<p><strong>HTTP Code:</strong> $httpCode</p>";
echo "<p><strong>cURL Error:</strong> " . ($error ?: 'None') . "</p>";

if ($httpCode === 200) {
    echo "<p>✅ API endpoint is working!</p>";
    echo "<h3>Response:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
} else {
    echo "<p>❌ API endpoint failed</p>";
    echo "<h3>Response:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

// Test 3: Controller method direct call
echo "<h2>3. Controller Method Test</h2>";
try {
    $controller = new \App\Http\Controllers\User\NotificationController();
    
    // Mock request and auth
    \Illuminate\Support\Facades\Auth::shouldReceive('user')
        ->andReturn($user ?? new \App\Models\User(['id' => 1]));
    
    $response = $controller->headerData();
    $responseData = $response->getData();
    
    echo "<p>✅ Controller method called successfully</p>";
    echo "<p><strong>Success:</strong> " . ($responseData->success ? 'true' : 'false') . "</p>";
    echo "<p><strong>Unread Count:</strong> " . ($responseData->unread_count ?? 'N/A') . "</p>";
    echo "<p><strong>Notifications:</strong> " . count($responseData->notifications ?? []) . "</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Controller error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><em>Test completed at " . date('Y-m-d H:i:s') . "</em></p>";
?> 