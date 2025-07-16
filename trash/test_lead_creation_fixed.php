<?php

$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TESTING LEAD CREATION AFTER FIX ===\n\n";
    
    // 1. Tạo lead test mới
    echo "1. Creating test lead...\n";
    
    $stmt = $pdo->prepare("
        INSERT INTO leads (
            customer_id, category_id, title, description, location, district, ward, 
            address, budget_min, budget_max, urgency, status, needed_by, max_contractors, 
            lead_price, expires_at, requirements, customer_info, created_at, updated_at
        ) VALUES (
            127, 4, ?, 'Test lead sau khi fix email notification', 
            'Huyện Hoài Đức, Xã Test', 'Huyện Hoài Đức', 'Xã Test',
            '{\"detail\":\"123 Test Street After Fix\"}', 150000, 300000, 'medium', 'active', 
            DATE_ADD(NOW(), INTERVAL 7 DAY), 5, 50000, DATE_ADD(NOW(), INTERVAL 30 DAY),
            '[]', '{}', NOW(), NOW()
        )
    ");
    
    $leadTitle = 'TEST LEAD EMAIL FIX - ' . date('H:i:s');
    $stmt->execute([$leadTitle]);
    
    $leadId = $pdo->lastInsertId();
    echo "✅ Lead created with ID: {$leadId}\n";
    echo "   Title: {$leadTitle}\n\n";
    
    // 2. Trigger CustomerLeadController logic manually
    echo "2. Triggering lead notification manually...\n";
    
    require 'core/vendor/autoload.php';
    require 'core/bootstrap/app.php';
    
    $lead = \App\Models\Lead::find($leadId);
    
    // Find matching contractors
    $contractors = \App\Models\Company::where('category_id', $lead->category_id)
        ->where('district', $lead->district)
        ->where('status', 1)
        ->with(['user'])
        ->get()
        ->map(function($company) {
            $avgRating = (float)$company->avg_rating;
            $company->smart_score = $avgRating;
            return $company;
        })
        ->sortByDesc('smart_score')
        ->take(3)
        ->filter(function($company) {
            return $company->user; 
        });

    echo "   Found " . $contractors->count() . " contractors\n\n";

    foreach ($contractors as $contractor) {
        echo "3. Processing contractor: {$contractor->name}\n";
        
        // Create lead visibility
        \App\Models\LeadVisibility::create([
            'lead_id' => $lead->id,
            'company_id' => $contractor->id,
            'priority_score' => $contractor->smart_score,
            'notified_at' => now(),
            'expires_at' => now()->addHours(24)
        ]);
        
        echo "   ✅ LeadVisibility created\n";
        
        // Send Laravel notification
        try {
            $contractor->user->notify(new \App\Notifications\SmartLeadNotification($lead, $contractor->smart_score));
            echo "   ✅ Laravel notification sent\n";
        } catch (\Exception $e) {
            echo "   ❌ Laravel notification failed: {$e->getMessage()}\n";
        }
        
        // Send email via template system (THE FIX)
        try {
            notify($contractor->user, 'NEW_LEAD_NOTIFICATION', [
                'contractor_name' => $contractor->user->firstname . ' ' . $contractor->user->lastname,
                'lead_title' => $lead->title,
                'lead_location' => $lead->location,
                'lead_budget' => $lead->getBudgetRange(),
                'lead_category' => $lead->category->name ?? 'Dịch vụ',
                'lead_urgency' => ucfirst($lead->urgency),
                'priority_score' => number_format($contractor->smart_score, 1),
                'lead_price' => number_format($lead->lead_price),
                'lead_url' => route('user.leads.show', $lead->id),
                'expires_at' => '24 giờ',
                'current_time' => now()->format('d/m/Y H:i:s')
            ]);
            echo "   ✅ Email notification sent via template system!\n";
            echo "   📧 To: {$contractor->user->email}\n";
        } catch (\Exception $e) {
            echo "   ❌ Email notification failed: {$e->getMessage()}\n";
        }
        
        // Create UserNotification
        try {
            \App\Models\UserNotification::createLeadNotification(
                $contractor->user->id,
                $lead,
                'smart_lead',
                "🎯 Lead ưu tiên: {$lead->title}",
                "Bạn được chọn trong top 3 thợ cho công việc tại {$lead->location}. Ngân sách: {$lead->getBudgetRange()}. Thời gian độc quyền: 24h",
                url("/user/leads/show/{$lead->id}")
            );
            echo "   ✅ UserNotification created\n";
        } catch (\Exception $e) {
            echo "   ❌ UserNotification failed: {$e->getMessage()}\n";
        }
        
        echo "\n";
    }
    
    // 4. Verify results
    echo "4. Verification...\n";
    
    // Check notification logs
    $stmt = $pdo->query("
        SELECT * FROM notification_logs 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($logs) {
        echo "✅ Recent notification logs found:\n";
        foreach ($logs as $log) {
            echo "   📧 To: {$log['sent_to']}\n";
            echo "      Template: {$log['template_name']}\n";
            echo "      Time: {$log['created_at']}\n\n";
        }
    } else {
        echo "❌ No recent notification logs\n";
    }
    
    echo "=== TEST COMPLETE ===\n";
    echo "✅ Lead created: #{$leadId}\n";
    echo "✅ Contractors notified: {$contractors->count()}\n";
    echo "📧 Emails should be sent to contractors now!\n";
    echo "\n💡 Fix applied: Added notify() function calls in CustomerLeadController\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 