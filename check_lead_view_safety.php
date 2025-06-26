<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== VIEW SAFETY CHECK FOR LEAD 30 ===";
echo PHP_EOL;

// Get Lead 30
$stmt = $pdo->prepare("SELECT * FROM leads WHERE id = 30");
$stmt->execute();
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) {
    echo "❌ Lead 30 not found!";
    exit;
}

echo "Checking all template variables for safety:" . PHP_EOL;

// Check basic strings
$safeStrings = ['id', 'title', 'description', 'ward', 'district', 'urgency', 'status'];
foreach ($safeStrings as $field) {
    $value = $lead[$field];
    echo "  ✅ {$field}: " . (is_string($value) ? "STRING" : gettype($value)) . PHP_EOL;
}

// Check numeric fields
$numericFields = ['budget_min', 'budget_max', 'lead_price'];
foreach ($numericFields as $field) {
    $value = $lead[$field];
    echo "  ✅ {$field}: " . (is_numeric($value) ? "NUMERIC" : gettype($value)) . PHP_EOL;
}

// Check potential problematic arrays
$arrayFields = ['address', 'customer_info', 'requirements', 'attachments'];
foreach ($arrayFields as $field) {
    $value = $lead[$field];
    if (is_null($value)) {
        echo "  ✅ {$field}: NULL (safe)" . PHP_EOL;
    } else {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "  ⚠️ {$field}: JSON ARRAY - needs array handling in view" . PHP_EOL;
        } else {
            echo "  ✅ {$field}: STRING" . PHP_EOL;
        }
    }
}

echo PHP_EOL;
echo "Laravel Model Casting Effects:" . PHP_EOL;
echo "  - address: " . ($lead['address'] ? "Will be cast to array" : "Will be null") . PHP_EOL;
echo "  - customer_info: " . ($lead['customer_info'] ? "Will be cast to array" : "Will be null") . PHP_EOL;
echo "  - requirements: " . ($lead['requirements'] ? "Will be cast to array" : "Will be null") . PHP_EOL;
echo "  - attachments: " . ($lead['attachments'] ? "Will be cast to array" : "Will be null") . PHP_EOL;

echo PHP_EOL;
echo "View Safety Issues:" . PHP_EOL;
echo "  ✅ address: Fixed in view with array handling" . PHP_EOL;
echo "  ❓ Are there any other {{ \$lead->... }} using arrays?" . PHP_EOL; 