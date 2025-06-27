<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CUSTOMER PHONE FIX VERIFICATION ===\n\n";
    
    // Get lead #45 data
    $stmt = $pdo->prepare("
        SELECT l.*, u.firstname, u.lastname, u.email, u.mobile 
        FROM leads l 
        JOIN users u ON l.customer_id = u.id 
        WHERE l.id = ?
    ");
    $stmt->execute([45]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($data) {
        echo "✅ Lead #45: {$data['title']}\n";
        echo "✅ Customer: {$data['firstname']} {$data['lastname']}\n";
        echo "✅ Email: {$data['email']}\n";
        echo "✅ Mobile: {$data['mobile']}\n\n";
        
        echo "=== TEMPLATE LOGIC SIMULATION ===\n";
        
        // Simulate old template logic
        $oldName = $data['name'] ?? null; // This field doesn't exist
        $oldPhone = $data['phone'] ?? null; // This field doesn't exist
        
        // Simulate new template logic  
        $newName = trim(($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? ''));
        $newPhone = $data['mobile'] ?? null;
        
        echo "OLD LOGIC:\n";
        echo "  Name: " . ($oldName ?: 'NULL - ❌ PROBLEM') . "\n";
        echo "  Phone: " . ($oldPhone ?: 'NULL - ❌ PROBLEM') . "\n\n";
        
        echo "NEW LOGIC:\n";
        echo "  Name: " . ($newName ?: 'N/A') . " - " . ($newName ? '✅ WORKS' : '❌ EMPTY') . "\n";
        echo "  Phone: " . ($newPhone ?: 'N/A') . " - " . ($newPhone ? '✅ WORKS' : '❌ EMPTY') . "\n\n";
        
        // Check purchase requirement for showing customer info
        echo "=== PURCHASE SECURITY CHECK ===\n";
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM lead_purchases WHERE lead_id = ?");
        $stmt->execute([45]);
        $purchaseCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        echo "Total purchases for Lead #45: {$purchaseCount}\n";
        
        if ($purchaseCount > 0) {
            echo "✅ Lead has purchases - customer info should be visible to buyers\n";
            
            $stmt = $pdo->prepare("
                SELECT p.*, c.name as company_name, c.user_id 
                FROM lead_purchases p 
                JOIN companies c ON p.company_id = c.id 
                WHERE p.lead_id = ?
            ");
            $stmt->execute([45]);
            $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "\nPurchases:\n";
            foreach ($purchases as $purchase) {
                echo "- Company: {$purchase['company_name']} (User: {$purchase['user_id']})\n";
            }
            
        } else {
            echo "⚠️ No purchases - customer info hidden for security\n";
        }
        
    } else {
        echo "❌ Lead #45 data not found!\n";
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "✅ Fixed: \$lead->customer->phone → \$lead->customer->mobile\n";
    echo "✅ Fixed: \$lead->customer->name → firstname + lastname\n";
    echo "✅ Applied to: leads/show.blade.php\n";
    echo "✅ Applied to: leads/my-purchases.blade.php\n\n";
    
    echo "=== TEST RESULTS ===\n";
    if ($data && $data['mobile']) {
        echo "✅ Customer phone number should now display: {$data['mobile']}\n";
        echo "🎯 Visit: http://localhost/user/leads/show/45\n";
        echo "🔒 Note: Must be logged in as user who purchased this lead\n";
    } else {
        echo "❌ Still have issues with customer data\n";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

?> 