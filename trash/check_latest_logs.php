<?php

echo "=== CHECKING LATEST LARAVEL LOGS ===\n\n";

$logFile = 'core/storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "❌ Laravel log file not found at: {$logFile}\n";
    exit;
}

// Get last 100 lines of log file
$lines = file($logFile);
$totalLines = count($lines);
$startLine = max(0, $totalLines - 100);
$recentLines = array_slice($lines, $startLine);

echo "📝 Showing last 100 lines from Laravel logs:\n";
echo "   Total lines in log: {$totalLines}\n";
echo "   Showing from line: " . ($startLine + 1) . " to {$totalLines}\n\n";

// Filter for relevant entries
$relevantLines = [];
foreach ($recentLines as $index => $line) {
    $lineNumber = $startLine + $index + 1;
    
    // Look for notification-related logs
    if (strpos($line, 'Attempting to send email notification') !== false ||
        strpos($line, 'Email notification sent successfully') !== false ||
        strpos($line, 'Failed to send email notification') !== false ||
        strpos($line, 'UserNotification created successfully') !== false ||
        strpos($line, 'Failed to create UserNotification') !== false ||
        strpos($line, 'Smart lead distribution completed') !== false ||
        strpos($line, 'Lead creation request data') !== false ||
        strpos($line, 'notify()') !== false ||
        strpos($line, 'NEW_LEAD_NOTIFICATION') !== false) {
        
        $relevantLines[] = "Line {$lineNumber}: " . trim($line);
    }
}

if ($relevantLines) {
    echo "🎯 RELEVANT LOG ENTRIES:\n";
    foreach ($relevantLines as $line) {
        echo "   {$line}\n";
    }
} else {
    echo "❌ No relevant log entries found in recent logs\n";
    echo "   Looking for: email notification, UserNotification, Smart lead distribution\n\n";
    
    echo "📋 RECENT LOG ENTRIES (last 10 lines):\n";
    $last10 = array_slice($recentLines, -10);
    foreach ($last10 as $index => $line) {
        $lineNumber = $totalLines - 10 + $index + 1;
        echo "   Line {$lineNumber}: " . trim($line) . "\n";
    }
}

echo "\n=== INSTRUCTIONS ===\n";
echo "1. If you see 'Attempting to send email notification' but no 'Email notification sent successfully'\n";
echo "   → There's an error in the notify() function\n\n";
echo "2. If you see 'Failed to send email notification' with error details\n";
echo "   → Check the specific error message\n\n";
echo "3. If you don't see any notification attempts\n";
echo "   → The notifyMatchingContractors() method may not be called\n\n";
echo "4. Create a new lead and run this script again to see fresh logs\n"; 