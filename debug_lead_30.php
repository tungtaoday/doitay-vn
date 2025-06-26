<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== DEBUG LEAD 30 DATA ===";
echo PHP_EOL;

// Get Lead 30 data
$stmt = $pdo->prepare("SELECT * FROM leads WHERE id = 30");
$stmt->execute();
$lead = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lead) {
    echo "❌ Lead 30 not found!" . PHP_EOL;
    exit;
}

echo "Lead 30 Data:" . PHP_EOL;
foreach ($lead as $key => $value) {
    echo "  - {$key}: ";
    if (is_null($value)) {
        echo "NULL";
    } elseif (is_string($value) && strlen($value) > 100) {
        echo substr($value, 0, 100) . "...";
    } else {
        echo $value;
    }
    echo PHP_EOL;
}

echo PHP_EOL;

// Check specific problematic fields
echo "Checking Potential Array Fields:" . PHP_EOL;

$arrayFields = ['address', 'customer_info', 'requirements', 'attachments'];
foreach ($arrayFields as $field) {
    $value = $lead[$field];
    echo "  - {$field}: ";
    
    if (is_null($value)) {
        echo "NULL (OK)" . PHP_EOL;
    } elseif (empty($value)) {
        echo "EMPTY (OK)" . PHP_EOL;  
    } else {
        // Try to decode as JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "JSON ARRAY: " . json_encode($decoded) . PHP_EOL;
        } else {
            echo "STRING: " . $value . PHP_EOL;
        }
    }
}

echo PHP_EOL;
echo "Expected Model Casting:" . PHP_EOL;
echo "  - address => 'array' (Laravel will cast to array)" . PHP_EOL;
echo "  - If DB value is JSON string, Laravel casts to array" . PHP_EOL;
echo "  - If DB value is NULL, Laravel casts to null" . PHP_EOL; 