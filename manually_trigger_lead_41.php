<?php

require 'core/vendor/autoload.php';
require 'core/bootstrap/app.php';

use App\Models\Lead;
use App\Models\Company;
use App\Models\User;

echo "=== MANUALLY TRIGGERING EMAIL FOR LEAD #41 ===\n\n";

try {
    $leadId = 41;
    
    // 1. Get lead 41
    echo "1. Getting Lead #41...\n";
    $lead = Lead::with('category')->find($leadId);
    
    if (!$lead) {
        throw new Exception("Lead #41 not found!");
    }
    
    echo "✅ Lead found:\n";
    echo "   Title: {$lead->title}\n";
    echo "   Category: {$lead->category->name} (ID: {$lead->category_id})\n";
    echo "   District: {$lead->district}\n";
    echo "   Status: {$lead->status}\n\n";
    
    // 2. Find contractors who should receive notifications
    echo "2. Finding contractors matching criteria...\n";
    $contractors = Company::where('category_id', $lead->category_id)
        ->where('district', $lead->district)
        ->where('status', 1)
        ->with('user')
        ->get();
        
    echo "✅ Found " . $contractors->count() . " matching contractors:\n";
    foreach ($contractors as $contractor) {
        if ($contractor->user) {
            echo "   🏢 {$contractor->name} - {$contractor->user->email}\n";
        }
    }
    echo "\n";
    
    // 3. Send emails manually
    echo "3. Sending emails manually...\n";
    
    $emailsSent = 0;
    foreach ($contractors as $contractor) {
        if (!$contractor->user) {
            echo "   ⚠️ Skipping {$contractor->name} - no user assigned\n";
            continue;
        }
        
        echo "   📧 Sending email to {$contractor->user->email}...\n";
        
        try {
            // Send email via template system (same as in CustomerLeadController)
            notify($contractor->user, 'NEW_LEAD_NOTIFICATION', [
                'contractor_name' => $contractor->user->firstname . ' ' . $contractor->user->lastname,
                'lead_title' => $lead->title,
                'lead_location' => $lead->location,
                'lead_budget' => $lead->getBudgetRange(),
                'lead_category' => $lead->category->name ?? 'Dịch vụ',
                'lead_urgency' => ucfirst($lead->urgency),
                'priority_score' => '5.0',
                'lead_price' => number_format($lead->lead_price),
                'lead_url' => route('user.leads.show', $lead->id),
                'expires_at' => '24 giờ',
                'current_time' => now()->format('d/m/Y H:i:s')
            ]);
            
            echo "      ✅ Email sent successfully!\n";
            $emailsSent++;
            
        } catch (\Exception $e) {
            echo "      ❌ Email failed: {$e->getMessage()}\n";
            \Log::error('Manual email trigger failed for Lead #41:', [
                'contractor_email' => $contractor->user->email,
                'error' => $e->getMessage()
            ]);
        }
        
        echo "\n";
    }
    
    echo "=== SUMMARY ===\n";
    echo "✅ Manual email trigger completed!\n";
    echo "📧 Emails sent: {$emailsSent}/{$contractors->count()}\n";
    echo "🎯 Lead: #{$lead->id} - {$lead->title}\n";
    echo "📧 Recipients should check their inbox for NEW_LEAD_NOTIFICATION emails\n\n";
    
    if ($emailsSent > 0) {
        echo "💡 SUCCESS: If this manual trigger works, it means:\n";
        echo "   - notify() function works correctly\n";
        echo "   - Email templates are configured properly\n";
        echo "   - SMTP is working\n";
        echo "   - Issue was with the original lead creation process\n\n";
        
        echo "🔧 NEXT STEPS:\n";
        echo "1. Check why UserNotification wasn't created for Lead #41\n";
        echo "2. Create a new test lead to verify the fix works for new leads\n";
        echo "3. Monitor future lead creations for email notifications\n";
    } else {
        echo "❌ ISSUE: Manual trigger also failed\n";
        echo "   → Check notify() function implementation\n";
        echo "   → Check email template configuration\n";
        echo "   → Check SMTP settings\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
} 