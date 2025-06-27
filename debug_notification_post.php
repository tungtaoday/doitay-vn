<?php

echo "=== DEBUG NOTIFICATION POST REQUEST ===\n\n";

// Test actual POST request to mark as read endpoint
$baseUrl = 'http://localhost';
$testNotificationId = 1; // We'll test with ID 1

echo "Testing POST request to mark notification as read...\n";
echo "URL: {$baseUrl}/user/notifications/{$testNotificationId}/read\n";
echo "Method: POST\n\n";

// Initialize curl
$ch = curl_init();

// Set curl options
curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/user/notifications/{$testNotificationId}/read");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects
curl_setopt($ch, CURLOPT_HEADER, true); // Include headers in output

// Add headers that JavaScript would send
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Requested-With: XMLHttpRequest', // Indicates AJAX request
    'Accept: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);

curl_close($ch);

echo "=== RESPONSE ANALYSIS ===\n\n";
echo "HTTP Status Code: {$httpCode}\n";

if ($redirectUrl) {
    echo "Redirect URL: {$redirectUrl}\n";
}

echo "\nFull Response:\n";
echo "----------------------------------------\n";
echo $response;
echo "\n----------------------------------------\n\n";

// Analyze the response
echo "=== ANALYSIS ===\n\n";

if ($httpCode == 302) {
    echo "❌ PROBLEM FOUND: Server returning 302 redirect\n";
    echo "   This means the route is redirecting instead of returning JSON\n";
    echo "   Common causes:\n";
    echo "   - Route requires authentication but user not logged in\n";
    echo "   - Middleware issue\n";
    echo "   - CSRF token missing or invalid\n\n";
} elseif ($httpCode == 404) {
    echo "❌ PROBLEM FOUND: Route not found (404)\n";
    echo "   The route /user/notifications/{id}/read doesn't exist\n\n";
} elseif ($httpCode == 405) {
    echo "❌ PROBLEM FOUND: Method not allowed (405)\n";
    echo "   The route exists but doesn't accept POST method\n\n";
} elseif ($httpCode == 419) {
    echo "❌ PROBLEM FOUND: CSRF token mismatch (419)\n";
    echo "   Missing or invalid CSRF token\n\n";
} elseif ($httpCode == 500) {
    echo "❌ PROBLEM FOUND: Server error (500)\n";
    echo "   Check Laravel logs for detailed error\n\n";
}

// Check if response contains HTML
if (strpos($response, '<html') !== false || strpos($response, '<!DOCTYPE') !== false) {
    echo "❌ RESPONSE IS HTML (not JSON)\n";
    echo "   This explains the 'Unexpected token <' error\n";
    echo "   JavaScript expects JSON but receives HTML\n\n";
}

// Check if response contains JSON
if (strpos($response, '{"') !== false || strpos($response, '"success"') !== false) {
    echo "✅ RESPONSE CONTAINS JSON\n";
    echo "   Response format looks correct\n\n";
}

echo "=== SOLUTIONS ===\n\n";

echo "1. Check Route Registration:\n";
echo "   Verify route exists in core/routes/user.php\n";
echo "   Route pattern: Route::post('{id}/read', 'markAsRead')->name('read');\n\n";

echo "2. Check Middleware:\n";
echo "   Ensure auth middleware allows API requests\n";
echo "   Check if middleware redirects API calls\n\n";

echo "3. Check CSRF Protection:\n";
echo "   API routes might need CSRF token\n";
echo "   Or exclude from CSRF protection\n\n";

echo "4. Test with Browser:\n";
echo "   Open F12 → Network tab\n";
echo "   Click notification\n";
echo "   Check actual request/response\n\n";

echo "5. Check Laravel Logs:\n";
echo "   Look in storage/logs/laravel.log\n";
echo "   Check for errors during notification requests\n\n";

// Test header-data endpoint too
echo "=== TESTING HEADER DATA ENDPOINT ===\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/user/notifications/header-data");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$headerResponse = curl_exec($ch);
$headerHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Header-data endpoint status: {$headerHttpCode}\n";
if ($headerHttpCode == 302) {
    echo "❌ Header-data also redirecting - authentication issue\n";
} else {
    echo "✅ Header-data returns: {$headerHttpCode}\n";
}

echo "\nFirst 200 chars of header-data response:\n";
echo substr($headerResponse, 0, 200) . "...\n";

?> 