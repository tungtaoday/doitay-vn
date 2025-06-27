<?php

// Simple database connection
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== LEAD #45 CUSTOMER PHONE DEBUG ===\n\n";
    
    // Get lead #45 info
    $stmt = $pdo->prepare("SELECT * FROM leads WHERE id = ?");
    $stmt->execute([45]);
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lead) {
        echo "❌ Lead #45 not found!\n";
        exit;
    }
    
    echo "✅ Lead #45: {$lead['title']}\n";
    echo "Customer ID: {$lead['customer_id']}\n";
    echo "Status: {$lead['status']}\n\n";
    
    // Get customer info
    if ($lead['customer_id']) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$lead['customer_id']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($customer) {
            echo "=== CUSTOMER INFO ===\n";
            echo "ID: {$customer['id']}\n";
            echo "Name: {$customer['firstname']} {$customer['lastname']}\n";
            echo "Email: {$customer['email']}\n";
            
            // Check phone fields
            echo "\n--- PHONE FIELDS ---\n";
            foreach ($customer as $key => $value) {
                if (stripos($key, 'phone') !== false || stripos($key, 'mobile') !== false || stripos($key, 'tel') !== false) {
                    $displayValue = $value ?: 'NULL/EMPTY';
                    echo "📱 {$key}: {$displayValue}\n";
                }
            }
            
            // Show relevant fields
            echo "\n--- KEY FIELDS ---\n";
            $keyFields = ['mobile', 'phone', 'telephone', 'firstname', 'lastname', 'email', 'username'];
            foreach ($keyFields as $field) {
                if (isset($customer[$field])) {
                    $value = $customer[$field] ?: 'NULL/EMPTY';
                    echo "{$field}: {$value}\n";
                }
            }
            
        } else {
            echo "❌ Customer not found in database!\n";
        }
    } else {
        echo "❌ No customer_id in lead!\n";
    }
    
    // Check purchases for this lead
    echo "\n=== LEAD PURCHASES ===\n";
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as company_name, c.user_id 
        FROM lead_purchases p 
        JOIN companies c ON p.company_id = c.id 
        WHERE p.lead_id = ?
    ");
    $stmt->execute([45]);
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total purchases: " . count($purchases) . "\n";
    foreach ($purchases as $purchase) {
        echo "- Company: {$purchase['company_name']} (User ID: {$purchase['user_id']})\n";
        echo "  Purchase ID: {$purchase['id']}, Date: {$purchase['created_at']}\n";
    }
    
    echo "\n=== VIEW TEMPLATE CHECK ===\n";
    $viewFile = 'core/resources/views/templates/basic/user/leads/show.blade.php';
    if (file_exists($viewFile)) {
        $content = file_get_contents($viewFile);
        
        // Look for customer phone display logic
        if (preg_match('/Customer Contact Info.*?<\/div>/s', $content, $matches)) {
            echo "Found customer contact section:\n";
            echo "---\n";
            // Show just the phone line
            if (preg_match('/Điện thoại.*?<br>/s', $matches[0], $phoneMatch)) {
                echo $phoneMatch[0] . "\n";
            }
            echo "---\n";
        }
        
        // Check what phone field is being used
        if (strpos($content, '$lead->customer->phone') !== false) {
            echo "✅ Uses: \$lead->customer->phone\n";
        }
        if (strpos($content, '$lead->customer->mobile') !== false) {
            echo "✅ Uses: \$lead->customer->mobile\n";
        }
        
        // Check purchase condition
        if (strpos($content, '$hasPurchased') !== false) {
            echo "✅ Has purchase security check\n";
        }
        
    } else {
        echo "❌ View file not found: {$viewFile}\n";
    }
    
    echo "\n=== DIAGNOSIS ===\n";
    if ($customer) {
        $phoneFields = ['mobile', 'phone', 'telephone'];
        $hasAnyPhone = false;
        
        foreach ($phoneFields as $field) {
            if (!empty($customer[$field])) {
                echo "✅ Customer has {$field}: {$customer[$field]}\n";
                $hasAnyPhone = true;
            }
        }
        
        if (!$hasAnyPhone) {
            echo "❌ PROBLEM: Customer has NO phone number in database\n";
            echo "💡 SOLUTION: Need to add phone number to customer record\n";
            echo "   Customer ID: {$customer['id']}\n";
            echo "   Email: {$customer['email']}\n";
        } else {
            echo "✅ Customer has phone number\n";
            echo "💡 CHECK: View template might be looking for different field\n";
            echo "💡 CHECK: User might not have purchased this lead\n";
        }
    }
    
    echo "\n=== QUICK FIXES ===\n";
    echo "1. UPDATE customer phone: UPDATE users SET mobile='0123456789' WHERE id={$lead['customer_id']}\n";
    echo "2. Check which field template uses: phone vs mobile\n";
    echo "3. Verify user has purchased lead for security\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

?> 