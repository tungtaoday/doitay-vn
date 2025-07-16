<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== CREATING TEST LEAD ===";
echo PHP_EOL;

// Create a safe lead with minimal data
$stmt = $pdo->prepare("
    INSERT INTO leads (
        customer_id, category_id, title, description, 
        district, ward, budget_min, budget_max, lead_price,
        urgency, status, max_contractors, needed_by,
        address, customer_info, requirements, attachments,
        created_at, updated_at
    ) VALUES (
        127, 4, 'Test Lead Safe', 'Test description safe', 
        'Huyện Hoài Đức', 'Xã Đông La', 100000, 300000, 50000,
        'medium', 'active', 5, '2025-06-27 00:00:00',
        NULL, NULL, NULL, NULL,
        NOW(), NOW()
    )
");

$result = $stmt->execute();

if ($result) {
    $leadId = $pdo->lastInsertId();
    echo "✅ Created test lead ID: {$leadId}" . PHP_EOL;
    echo "   - Title: Test Lead Safe" . PHP_EOL;
    echo "   - All array fields: NULL (safe)" . PHP_EOL;
    echo "   - URL to test: http://localhost/user/leads/show/{$leadId}" . PHP_EOL;
} else {
    echo "❌ Failed to create test lead" . PHP_EOL;
    print_r($stmt->errorInfo());
}

echo PHP_EOL;
echo "Test this URL first to see if basic view works!" . PHP_EOL; 