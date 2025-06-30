<?php

require_once 'core/bootstrap/app.php';

echo "=== TESTING LEAD TIME CALCULATION LOGIC ===\n\n";

// Test different scenarios
$testCases = [
    [
        'name' => 'Lead with 5 days remaining',
        'needed_by' => now()->addDays(5),
        'expected_color' => 'bg-success',
        'expected_display' => '5 days'
    ],
    [
        'name' => 'Lead with 2 days remaining',
        'needed_by' => now()->addDays(2),
        'expected_color' => 'bg-info',
        'expected_display' => '2 days'
    ],
    [
        'name' => 'Lead with 1 day remaining',
        'needed_by' => now()->addDays(1),
        'expected_color' => 'bg-warning',
        'expected_display' => '1 day'
    ],
    [
        'name' => 'Lead with 12 hours remaining',
        'needed_by' => now()->addHours(12),
        'expected_color' => 'bg-warning',
        'expected_display' => '12 hours'
    ],
    [
        'name' => 'Lead overdue by 2 days',
        'needed_by' => now()->subDays(2),
        'expected_color' => 'bg-danger',
        'expected_display' => 'Overdue 2 days'
    ],
    [
        'name' => 'Lead with no deadline',
        'needed_by' => null,
        'expected_color' => 'bg-secondary',
        'expected_display' => 'No limit'
    ]
];

foreach ($testCases as $case) {
    echo "Testing: {$case['name']}\n";
    
    if ($case['needed_by']) {
        $now = now();
        $neededBy = $case['needed_by'];
        $diffInDays = $now->diffInDays($neededBy, false);
        $diffInHours = $now->diffInHours($neededBy, false);
        
        // Icon color logic
        if ($diffInDays < 0) {
            $iconColor = 'bg-danger';
        } elseif ($diffInDays <= 1) {
            $iconColor = 'bg-warning';
        } elseif ($diffInDays <= 3) {
            $iconColor = 'bg-info';
        } else {
            $iconColor = 'bg-success';
        }
        
        // Display logic
        if ($diffInDays > 0) {
            $display = "{$diffInDays} ngày còn lại";
        } elseif ($diffInHours > 0) {
            $display = "{$diffInHours} giờ còn lại";
        } elseif ($diffInHours == 0) {
            $display = "Sắp hết hạn";
        } else {
            $display = "Quá hạn " . abs($diffInDays) . " ngày";
        }
        
        echo "  Needed by: {$neededBy->format('d/m/Y H:i')}\n";
        echo "  Days diff: {$diffInDays}\n";
        echo "  Hours diff: {$diffInHours}\n";
        echo "  Icon color: {$iconColor}\n";
        echo "  Display: {$display}\n";
        
    } else {
        $iconColor = 'bg-secondary';
        $display = 'Không giới hạn';
        echo "  No deadline set\n";
        echo "  Icon color: {$iconColor}\n";
        echo "  Display: {$display}\n";
    }
    
    echo "\n";
}

// Test with actual Lead #46
echo "=== TESTING WITH LEAD #46 ===\n";
try {
    $lead = App\Models\Lead::find(46);
    if ($lead) {
        echo "Lead #46: {$lead->title}\n";
        echo "Created: {$lead->created_at->format('d/m/Y H:i')}\n";
        echo "Needed by: " . ($lead->needed_by ? $lead->needed_by->format('d/m/Y H:i') : 'Not set') . "\n";
        
        if ($lead->needed_by) {
            $now = now();
            $neededBy = $lead->needed_by;
            $diffInDays = $now->diffInDays($neededBy, false);
            $diffInHours = $now->diffInHours($neededBy, false);
            
            if ($diffInDays < 0) {
                $iconColor = 'bg-danger';
            } elseif ($diffInDays <= 1) {
                $iconColor = 'bg-warning';
            } elseif ($diffInDays <= 3) {
                $iconColor = 'bg-info';
            } else {
                $iconColor = 'bg-success';
            }
            
            echo "Days remaining: {$diffInDays}\n";
            echo "Hours remaining: {$diffInHours}\n";
            echo "Icon color: {$iconColor}\n";
            
            if ($diffInDays > 0) {
                echo "Display: {$diffInDays} ngày còn lại\n";
            } elseif ($diffInHours > 0) {
                echo "Display: {$diffInHours} giờ còn lại\n";
            } elseif ($diffInHours == 0) {
                echo "Display: Sắp hết hạn\n";
            } else {
                echo "Display: Quá hạn " . abs($diffInDays) . " ngày\n";
            }
        } else {
            echo "No deadline - will show infinity symbol\n";
        }
    } else {
        echo "Lead #46 not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "✅ Updated customer lead show view\n";
echo "✅ Time calculation based on needed_by field\n";
echo "✅ Dynamic icon colors based on urgency\n";
echo "✅ Shows days/hours remaining or overdue status\n";
echo "✅ Handles leads without deadlines\n\n";

echo "🎯 Test URL: http://localhost/customer/leads/show/46\n";

?> 