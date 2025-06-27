<?php

echo "=== CHECKING CUSTOMER NOTIFICATION TEMPLATES ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Tìm templates có thể dành cho customer
    echo "1. Looking for customer-related templates:\n";
    $patterns = [
        '%CUSTOMER%',
        '%LEAD_CREATED%', 
        '%LEAD_CONFIRMATION%',
        '%THANK%',
        '%CONFIRMATION%',
        '%RECEIPT%'
    ];
    
    $found_templates = [];
    
    foreach ($patterns as $pattern) {
        $stmt = $pdo->prepare("SELECT act, name, email_status FROM notification_templates WHERE act LIKE ? OR name LIKE ?");
        $stmt->execute([$pattern, $pattern]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($results)) {
            echo "\nTemplates matching '$pattern':\n";
            foreach ($results as $template) {
                echo "   - {$template['act']}: {$template['name']} (Email: " . ($template['email_status'] ? 'ON' : 'OFF') . ")\n";
                $found_templates[] = $template['act'];
            }
        }
    }
    
    if (empty($found_templates)) {
        echo "❌ No customer notification templates found!\n";
    }
    
    // 2. Kiểm tra trong code xem có gọi notification cho customer không
    echo "\n2. Checking for customer notifications in code:\n";
    
    $controllerFile = 'core/app/Http/Controllers/User/CustomerLeadController.php';
    if (file_exists($controllerFile)) {
        $content = file_get_contents($controllerFile);
        
        // Tìm các notify() calls cho customer/user
        $lines = explode("\n", $content);
        $customer_notifications = [];
        
        foreach ($lines as $lineNum => $line) {
            if (strpos($line, 'notify(') !== false && 
                (strpos($line, '$user') !== false || 
                 strpos($line, '$customer') !== false || 
                 strpos($line, 'auth()->user()') !== false)) {
                $customer_notifications[] = ($lineNum + 1) . ": " . trim($line);
            }
        }
        
        if (!empty($customer_notifications)) {
            echo "✅ Found customer notification calls:\n";
            foreach ($customer_notifications as $notification) {
                echo "   - Line $notification\n";
            }
        } else {
            echo "❌ No customer notification calls found in CustomerLeadController\n";
        }
    }
    
    // 3. Kiểm tra lead creation process
    echo "\n3. Checking lead creation methods:\n";
    
    if (file_exists($controllerFile)) {
        $content = file_get_contents($controllerFile);
        
        // Tìm store method
        if (strpos($content, 'public function store') !== false) {
            echo "✅ Found store() method for lead creation\n";
            
            // Kiểm tra xem có notify customer sau khi tạo lead không
            $storeMethodStart = strpos($content, 'public function store');
            $nextMethodStart = strpos($content, 'public function', $storeMethodStart + 1);
            
            if ($nextMethodStart === false) {
                $storeMethod = substr($content, $storeMethodStart);
            } else {
                $storeMethod = substr($content, $storeMethodStart, $nextMethodStart - $storeMethodStart);
            }
            
            if (strpos($storeMethod, 'notify(') !== false) {
                echo "✅ Store method contains notify() calls\n";
                
                // Count notifications in store method
                $notifyCount = substr_count($storeMethod, 'notify(');
                echo "   - Number of notify() calls: $notifyCount\n";
                
                // Check if any is for the user creating the lead
                if (strpos($storeMethod, 'notify($user') !== false || 
                    strpos($storeMethod, 'notify(auth()->user()') !== false) {
                    echo "✅ Found notification for lead creator\n";
                } else {
                    echo "❌ No notification found for lead creator\n";
                }
            } else {
                echo "❌ Store method does NOT contain customer notifications\n";
            }
        }
    }
    
    // 4. Suggestions
    echo "\n4. Recommendations:\n";
    if (empty($found_templates)) {
        echo "   - Create a LEAD_CREATED template for customers\n";
        echo "   - Template should confirm lead submission\n";
        echo "   - Include lead details and next steps\n";
    }
    
    echo "   - Add notify() call for lead creator in store() method\n";
    echo "   - Send confirmation email to customer after lead creation\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== CHECK COMPLETED ===\n"; 