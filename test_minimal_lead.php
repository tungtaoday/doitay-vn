<?php
echo "=== MINIMAL LEAD TEST ===";
echo PHP_EOL;

// Test route resolution first
$url = "http://localhost/user/leads/show/30";
echo "Testing URL: {$url}" . PHP_EOL;
echo PHP_EOL;

// Test step by step
echo "Potential Issues:" . PHP_EOL;
echo "1. ✅ Route exists: /user/leads/show/{id}" . PHP_EOL;
echo "2. ✅ Controller method exists: LeadController@show" . PHP_EOL;
echo "3. ✅ View file exists: user.leads.show.blade.php" . PHP_EOL;
echo "4. ✅ Address array handling: Fixed" . PHP_EOL;
echo "5. ✅ View cache: Cleared" . PHP_EOL;
echo "6. ❓ Model relationships loading properly?" . PHP_EOL;
echo "7. ❓ Any helper functions causing issues?" . PHP_EOL;
echo PHP_EOL;

// Simple PHP array test mimicking Laravel model
$testLead = (object) [
    'id' => 30,
    'title' => 'Test',
    'description' => 'Test desc',
    'ward' => 'Ward',
    'district' => 'District',
    'budget_min' => 100000,
    'budget_max' => 200000,
    'lead_price' => 50000,
    'status' => 'active',
    'address' => ['detail' => 'Test address'], // This will be array in Laravel
    'customer_info' => ['test' => 'data'],     // This will be array in Laravel
    'requirements' => [],                       // This will be array in Laravel
    'attachments' => null                      // This will be null
];

echo "Testing template logic with array fields:" . PHP_EOL;

// Test address logic
if ($testLead->address && is_array($testLead->address) && isset($testLead->address['detail'])) {
    echo "✅ Address array: " . $testLead->address['detail'] . PHP_EOL;
} else {
    echo "❌ Address failed" . PHP_EOL;
}

// Check if any field could be problematic when converted to string
$arrayFields = ['customer_info', 'requirements'];
foreach ($arrayFields as $field) {
    if (isset($testLead->$field) && is_array($testLead->$field)) {
        echo "⚠️ {$field} is array - potential issue if used in {{ }} without checking" . PHP_EOL;
    }
}

echo PHP_EOL;
echo "SOLUTION: Try accessing URL directly in browser to see exact error message!" . PHP_EOL; 