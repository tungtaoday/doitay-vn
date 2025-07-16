<?php

require_once 'core/bootstrap/app.php';

echo "=== DEBUG LEAD #45 CUSTOMER PHONE ISSUE ===\n\n";

// Check Lead #45 details
$lead = App\Models\Lead::with(['customer', 'category', 'purchases.company'])->find(45);

if (!$lead) {
    echo "❌ Lead #45 not found!\n";
    exit;
}

echo "✅ Lead #45 found: {$lead->title}\n\n";

echo "=== LEAD BASIC INFO ===\n";
echo "ID: {$lead->id}\n";
echo "Title: {$lead->title}\n";
echo "Customer ID: {$lead->customer_id}\n";
echo "Status: {$lead->status}\n";
echo "Created: {$lead->created_at}\n\n";

echo "=== CUSTOMER INFO ===\n";
if ($lead->customer) {
    $customer = $lead->customer;
    echo "Customer found: YES\n";
    echo "Customer ID: {$customer->id}\n";
    echo "Name: {$customer->firstname} {$customer->lastname}\n";
    echo "Email: {$customer->email}\n";
    
    // Check different phone field names
    echo "\n--- PHONE FIELDS CHECK ---\n";
    echo "mobile: " . ($customer->mobile ?? 'NULL') . "\n";
    echo "phone: " . ($customer->phone ?? 'NULL') . "\n";
    echo "telephone: " . ($customer->telephone ?? 'NULL') . "\n";
    
    // Check all customer attributes
    echo "\n--- ALL CUSTOMER ATTRIBUTES ---\n";
    $attributes = $customer->getAttributes();
    foreach ($attributes as $key => $value) {
        if (strpos(strtolower($key), 'phone') !== false || strpos(strtolower($key), 'mobile') !== false || strpos(strtolower($key), 'tel') !== false) {
            echo "{$key}: " . ($value ?? 'NULL') . "\n";
        }
    }
    
    echo "\n--- FULL CUSTOMER DATA ---\n";
    foreach ($attributes as $key => $value) {
        $displayValue = $value;
        if (is_null($value)) {
            $displayValue = 'NULL';
        } elseif (is_array($value) || is_object($value)) {
            $displayValue = json_encode($value);
        }
        echo "{$key}: {$displayValue}\n";
    }
    
} else {
    echo "❌ Customer not found!\n";
}

echo "\n=== USER PURCHASE CHECK ===\n";
// Check if current user (if any) has purchased this lead
if (auth()->check()) {
    $userId = auth()->id();
    echo "Current user ID: {$userId}\n";
    
    $userCompanies = App\Models\Company::where('user_id', $userId)->pluck('id');
    echo "User companies: " . $userCompanies->implode(', ') . "\n";
    
    $purchases = $lead->purchases()->whereIn('company_id', $userCompanies)->get();
    echo "User purchases for this lead: " . $purchases->count() . "\n";
    
    if ($purchases->count() > 0) {
        echo "✅ User has purchased this lead - should see customer phone\n";
    } else {
        echo "❌ User has NOT purchased this lead - phone hidden for security\n";
    }
} else {
    echo "❌ No authenticated user\n";
}

echo "\n=== DATABASE SCHEMA CHECK ===\n";
// Check users table schema
try {
    $schema = DB::select("DESCRIBE users");
    echo "Users table columns:\n";
    foreach ($schema as $column) {
        $columnName = $column->Field;
        if (strpos(strtolower($columnName), 'phone') !== false || strpos(strtolower($columnName), 'mobile') !== false || strpos(strtolower($columnName), 'tel') !== false) {
            echo "📱 {$columnName} ({$column->Type})\n";
        } else {
            echo "   {$columnName} ({$column->Type})\n";
        }
    }
} catch (Exception $e) {
    echo "Error checking schema: " . $e->getMessage() . "\n";
}

echo "\n=== VIEW FILE ANALYSIS ===\n";
$viewPath = 'core/resources/views/templates/basic/user/leads/show.blade.php';
if (file_exists($viewPath)) {
    echo "✅ View file exists: {$viewPath}\n";
    
    $content = file_get_contents($viewPath);
    
    // Check phone display logic
    if (strpos($content, '{{ $lead->customer->phone') !== false) {
        echo "✅ Found: \$lead->customer->phone\n";
    }
    if (strpos($content, '{{ $lead->customer->mobile') !== false) {
        echo "✅ Found: \$lead->customer->mobile\n";
    }
    if (strpos($content, '$hasPurchased') !== false) {
        echo "✅ Found: \$hasPurchased condition\n";
    }
    if (strpos($content, 'Only if purchased') !== false) {
        echo "✅ Found: Security condition comment\n";
    }
    
    // Extract the customer info section
    $pattern = '/<!-- Customer Contact Info.*?<\/div>/s';
    if (preg_match($pattern, $content, $matches)) {
        echo "\n--- CUSTOMER INFO SECTION ---\n";
        echo $matches[0] . "\n";
    }
} else {
    echo "❌ View file not found: {$viewPath}\n";
}

echo "\n=== SUMMARY & SOLUTION ===\n";
if ($lead->customer) {
    $hasPhone = !empty($lead->customer->mobile) || !empty($lead->customer->phone);
    
    if (!$hasPhone) {
        echo "❌ ISSUE: Customer has no phone number in database\n";
        echo "📋 SOLUTION: Update customer phone number in database\n";
        echo "   Customer ID: {$lead->customer->id}\n";
        echo "   Customer Email: {$lead->customer->email}\n";
    } else {
        echo "✅ Customer has phone number in database\n";
        echo "📋 POSSIBLE ISSUE: Purchase check or view logic\n";
    }
} else {
    echo "❌ ISSUE: No customer associated with lead\n";
}

echo "\n=== QUICK FIX SUGGESTIONS ===\n";
echo "1. Check if customer phone field is populated in database\n";
echo "2. Verify that user has purchased this lead (for security)\n";
echo "3. Check view template logic for phone display\n";
echo "4. Consider which phone field is being used (mobile vs phone)\n";

?> 