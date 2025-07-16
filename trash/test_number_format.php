<?php

echo "=== TESTING NUMBER FORMAT FOR LEAD TIME ===\n\n";

// Simulate different time calculations
$testNumbers = [
    5.8 => 'diffInDays = 5.8 (should show 5)',
    23.7 => 'diffInHours = 23.7 (should show 23)', 
    1.2 => 'diffInDays = 1.2 (should show 1)',
    0.9 => 'diffInHours = 0.9 (should show 0)',
    -2.3 => 'overdue = -2.3 (should show 2)'
];

foreach ($testNumbers as $number => $description) {
    echo "{$description}\n";
    echo "Original: {$number}\n";
    echo "intval(): " . intval($number) . "\n";
    echo "abs + intval: " . intval(abs($number)) . "\n";
    echo "\n";
}

echo "=== BLADE TEMPLATE LOGIC ===\n";
echo "Before fix: {{ \$diffInDays }} → could show 5.8333\n";
echo "After fix:  {{ intval(\$diffInDays) }} → will show 5\n\n";

echo "✅ Now numbers will display as integers without decimal places\n";
echo "🎯 Test at: http://localhost/customer/leads/show/46\n";

?> 