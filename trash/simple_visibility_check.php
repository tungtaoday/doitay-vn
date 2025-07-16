<?php
$pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');

echo "=== SIMPLE VISIBILITY CHECK ===" . PHP_EOL;

// Check if lead 28 has visibility records
$stmt = $pdo->query("SELECT COUNT(*) as count FROM lead_visibility WHERE lead_id = 28");
$result = $stmt->fetch();
echo "Lead 28 visibility records: " . $result['count'] . PHP_EOL;

// Check latest visibility records
$stmt = $pdo->query("SELECT lead_id, company_id, created_at FROM lead_visibility ORDER BY created_at DESC LIMIT 5");
$records = $stmt->fetchAll();
echo "Latest visibility records:" . PHP_EOL;
foreach ($records as $record) {
    echo "  Lead {$record['lead_id']} → Company {$record['company_id']} at {$record['created_at']}" . PHP_EOL;
}

// Check if company 58 has any visibility records
$stmt = $pdo->query("SELECT COUNT(*) as count FROM lead_visibility WHERE company_id = 58");
$result = $stmt->fetch();
echo "Company 58 total visibility records: " . $result['count'] . PHP_EOL; 