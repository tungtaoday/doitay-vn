<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== KIỂM TRA LEAD #46 ===\n\n";
    
    // 1. Thông tin Lead #46
    echo "1. THÔNG TIN LEAD #46:\n";
    $stmt = $pdo->query("
        SELECT l.id, l.title, l.status, l.customer_id, l.district, 
               l.budget_min, l.budget_max, l.created_at,
               u.firstname, u.lastname, u.email as customer_email
        FROM leads l
        LEFT JOIN users u ON l.customer_id = u.id
        WHERE l.id = 46
    ");
    $lead = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($lead) {
        echo "   ✅ Lead found:\n";
        echo "      ID: {$lead['id']}\n";
        echo "      Title: {$lead['title']}\n";
        echo "      Status: {$lead['status']}\n";
        echo "      District: {$lead['district']}\n";
        echo "      Budget: " . number_format($lead['budget_min']) . "₫ - " . number_format($lead['budget_max']) . "₫\n";
        echo "      Customer: {$lead['firstname']} {$lead['lastname']} ({$lead['customer_email']})\n";
        echo "      Created: {$lead['created_at']}\n\n";
    } else {
        echo "   ❌ Lead #46 not found!\n\n";
        exit;
    }
    
    // 2. Kiểm tra purchases của Lead #46
    echo "2. PURCHASES CỦA LEAD #46:\n";
    $stmt = $pdo->query("
        SELECT lp.id as purchase_id, lp.company_id, c.name as company_name,
               lp.status, lp.created_at, lp.price_paid,
               lp.contractor_reported, lp.reported_at, lp.report_notes,
               lp.customer_confirmed, lp.confirmed_at, lp.confirmation_notes,
               u.username as contractor_username, u.firstname, u.lastname
        FROM lead_purchases lp
        JOIN companies c ON lp.company_id = c.id
        JOIN users u ON c.user_id = u.id
        WHERE lp.lead_id = 46
        ORDER BY lp.created_at DESC
    ");
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($purchases) {
        echo "   ✅ Found " . count($purchases) . " purchases:\n";
        foreach ($purchases as $p) {
            echo "      📦 Purchase #{$p['purchase_id']}\n";
            echo "         Company: {$p['company_name']} (ID: {$p['company_id']})\n";
            echo "         Contractor: {$p['firstname']} {$p['lastname']} ({$p['contractor_username']})\n";
            echo "         Status: {$p['status']}\n";
            echo "         Price paid: " . number_format($p['price_paid']) . "₫\n";
            echo "         Purchased: {$p['created_at']}\n";
            echo "         Contractor reported: " . ($p['contractor_reported'] ? '✅ YES' : '❌ NO') . "\n";
            if ($p['contractor_reported']) {
                echo "         - Reported at: {$p['reported_at']}\n";
                echo "         - Report notes: " . ($p['report_notes'] ?: 'None') . "\n";
            }
            echo "         Customer confirmed: " . ($p['customer_confirmed'] ? '✅ YES' : '❌ NO') . "\n";
            if ($p['customer_confirmed']) {
                echo "         - Confirmed at: {$p['confirmed_at']}\n";
                echo "         - Confirmation notes: " . ($p['confirmation_notes'] ?: 'None') . "\n";
            }
            echo "\n";
        }
    } else {
        echo "   ❌ No purchases found for Lead #46\n\n";
    }
    
    // 3. Kiểm tra Company 58 có mua Lead #46 không
    echo "3. COMPANY 58 VÀ LEAD #46:\n";
    $stmt = $pdo->query("
        SELECT lp.*, c.name as company_name, u.username, u.firstname, u.lastname
        FROM lead_purchases lp
        JOIN companies c ON lp.company_id = c.id
        JOIN users u ON c.user_id = u.id
        WHERE lp.lead_id = 46 AND lp.company_id = 58
    ");
    $company58Purchase = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company58Purchase) {
        echo "   ✅ Company 58 HAS purchased Lead #46!\n";
        echo "      Purchase ID: {$company58Purchase['id']}\n";
        echo "      Company: {$company58Purchase['company_name']}\n";
        echo "      User: {$company58Purchase['firstname']} {$company58Purchase['lastname']} ({$company58Purchase['username']})\n";
        echo "      Status: {$company58Purchase['status']}\n";
        echo "      Price paid: " . number_format($company58Purchase['price_paid']) . "₫\n";
        echo "      Contractor reported: " . ($company58Purchase['contractor_reported'] ? '✅ YES' : '❌ NO') . "\n";
        echo "      Customer confirmed: " . ($company58Purchase['customer_confirmed'] ? '✅ YES' : '❌ NO') . "\n\n";
        
        echo "   🎯 BUTTON STATUS FOR http://localhost/leads/show/46:\n";
        if ($company58Purchase['customer_confirmed']) {
            echo "      ✅ Should show: 'Khách hàng đã xác nhận chọn bạn!'\n";
        } elseif ($company58Purchase['contractor_reported']) {
            echo "      ⏳ Should show: 'Đang chờ khách hàng xác nhận'\n";
        } else {
            echo "      🆕 Should show: 'Khách đã chọn tôi' button\n";
        }
        
    } else {
        echo "   ❌ Company 58 has NOT purchased Lead #46\n";
        echo "   💡 That's why you don't see the 'Khách đã chọn tôi' button!\n";
        echo "   🔧 Company 58 needs to buy Lead #46 first.\n\n";
        
        // Check if Company 58 can buy this lead
        echo "   🛒 CAN COMPANY 58 BUY LEAD #46?\n";
        if ($lead['status'] === 'active') {
            echo "      ✅ Lead is active - can be purchased\n";
            
            // Check wallet balance
            $stmt = $pdo->query("
                SELECT balance FROM company_wallets WHERE company_id = 58
            ");
            $wallet = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($wallet) {
                $leadPrice = 50000; // Default lead price
                echo "      💰 Company 58 wallet balance: " . number_format($wallet['balance']) . "₫\n";
                echo "      💳 Lead price: " . number_format($leadPrice) . "₫\n";
                
                if ($wallet['balance'] >= $leadPrice) {
                    echo "      ✅ Sufficient balance - can purchase!\n";
                } else {
                    echo "      ❌ Insufficient balance - need to top up wallet\n";
                }
            } else {
                echo "      ❌ Company 58 wallet not found\n";
            }
        } else {
            echo "      ❌ Lead is not active - cannot be purchased\n";
        }
    }
    
    echo "\n=== KẾT LUẬN ===\n";
    if ($company58Purchase) {
        echo "✅ Company 58 đã mua Lead #46\n";
        echo "✅ Trang http://localhost/leads/show/46 SẼ HIỂN THỊ button 'Khách đã chọn tôi'\n";
        echo "✅ Hoặc hiển thị trạng thái tương ứng nếu đã báo cáo/xác nhận\n";
    } else {
        echo "❌ Company 58 CHƯA mua Lead #46\n";
        echo "❌ Đó là lý do trang http://localhost/leads/show/46 không có button\n";
        echo "🔧 Cần mua Lead #46 trước khi có thể báo cáo 'Khách đã chọn tôi'\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 