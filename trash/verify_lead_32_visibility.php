<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFY LEAD #32 VISIBILITY ===\n\n";
    
    // Check lead_visibility records
    echo "LeadVisibility records for Lead #32:\n";
    $stmt = $pdo->query("
        SELECT lv.*, c.name 
        FROM lead_visibility lv
        JOIN companies c ON lv.company_id = c.id
        WHERE lv.lead_id = 32
    ");
    $visibilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($visibilities as $vis) {
        echo "- Company ID {$vis['company_id']}: {$vis['name']}\n";
        echo "  Priority: {$vis['priority_score']}\n";
        echo "  Notified: {$vis['notified_at']}\n";
        echo "  Expires: {$vis['expires_at']}\n";
        echo "  Purchased: " . ($vis['is_purchased'] ? 'YES' : 'NO') . "\n\n";
    }
    
    // Check if Company 58 can see Lead #32
    echo "Can Company 58 buy Lead #32? ";
    $stmt = $pdo->prepare("
        SELECT l.*, lv.id as visibility_id
        FROM leads l
        JOIN lead_visibility lv ON l.id = lv.lead_id
        WHERE l.id = 32 AND lv.company_id = 58 AND lv.expires_at > NOW()
    ");
    $stmt->execute();
    $visible = $stmt->fetch();
    
    if ($visible) {
        echo "✅ YES - Lead is visible and available\n";
        echo "Lead title: {$visible['title']}\n";
        echo "Status: {$visible['status']}\n";
    } else {
        echo "❌ NO - Lead not visible\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 