<?php

echo "=== UPDATING ALL APPOINTMENT SHORTCODES ===\n\n";

$files = [
    'core/app/Http/Controllers/User/AppointmentController.php',
    'core/app/Http/Controllers/User/CompanyAppointmentController.php'
];

$properShortcodesTemplate = '
                \'user_name\' => ${{USER_VAR}}->fullname ?: (${{USER_VAR}}->firstname . \' \' . ${{USER_VAR}}->lastname) ?: ${{USER_VAR}}->username ?: ${{USER_VAR}}->name,
                \'user_email\' => ${{USER_VAR}}->email,
                \'appointment_id\' => $appointment->id,
                \'appointment_date\' => date(\'d/m/Y\', strtotime($appointment->appointment_date)),
                \'appointment_time\' => $appointment->appointment_time,
                \'company_name\' => $appointment->company->name ?? \'Service Provider\',
                \'company_phone\' => $appointment->company->mobile ?? $appointment->company->phone ?? \'Sẽ cập nhật sau\',
                \'appointment_address\' => $appointment->recipient_address,
                \'site_url\' => url(\'/\'),
                \'current_year\' => date(\'Y\'),
                \'notes\' => $appointment->notes ?? \'Không có ghi chú đặc biệt\'';

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "❌ File not found: $file\n";
        continue;
    }
    
    echo "📝 Processing: $file\n";
    
    $content = file_get_contents($file);
    $originalContent = $content;
    
    // Replace old shortcode patterns
    $oldPatterns = [
        // Pattern 1: customer notifications
        '/notify\(\$user, \'([A-Z_]+)\', \[\s*\'customer_name\' => \$appointment->recipient_name,\s*\'customer_phone\' => \$appointment->recipient_phone,\s*\'customer_address\' => \$appointment->recipient_address,\s*\'appointment_date\' => \$appointment->appointment_date,\s*\'appointment_time\' => \$appointment->appointment_time,\s*\'notes\' => \$appointment->notes \?\? \'N\/A\',\s*\'company_name\' => \$appointment->company->name \?\? \'Unknown Company\'\s*\]\);/s',
        
        // Pattern 2: company owner notifications  
        '/notify\(\$companyOwner, \'([A-Z_]+)\', \[\s*\'customer_name\' => \$appointment->recipient_name,\s*\'customer_phone\' => \$appointment->recipient_phone,\s*\'customer_address\' => \$appointment->recipient_address,\s*\'appointment_date\' => \$appointment->appointment_date,\s*\'appointment_time\' => \$appointment->appointment_time,\s*\'notes\' => \$appointment->notes \?\? \'N\/A\',\s*\'company_name\' => \$appointment->company->name \?\? \'Unknown Company\'\s*\]\);/s',
        
        // Pattern 3: customer notifications (alternative)
        '/notify\(\$customer, \'([A-Z_]+)\', \[\s*\'customer_name\' => \$appointment->recipient_name,\s*\'customer_phone\' => \$appointment->recipient_phone,\s*\'customer_address\' => \$appointment->recipient_address,\s*\'appointment_date\' => \$appointment->appointment_date,\s*\'appointment_time\' => \$appointment->appointment_time,\s*\'notes\' => \$appointment->notes \?\? \'N\/A\',\s*\'company_name\' => \$appointment->company->name \?\? \'Unknown Company\'\s*\]\);/s'
    ];
    
    $replacements = [
        // Pattern 1: $user notifications  
        str_replace('{{USER_VAR}}', 'user', 'notify($user, \'$1\', [' . $properShortcodesTemplate . '
            ]);'),
        
        // Pattern 2: $companyOwner notifications
        str_replace('{{USER_VAR}}', 'companyOwner', 'notify($companyOwner, \'$1\', [' . $properShortcodesTemplate . '
            ]);'),
        
        // Pattern 3: $customer notifications
        str_replace('{{USER_VAR}}', 'customer', 'notify($customer, \'$1\', [' . $properShortcodesTemplate . '
            ]);')
    ];
    
    // Apply replacements
    $content = preg_replace($oldPatterns, $replacements, $content);
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "   ✅ Updated successfully\n";
    } else {
        echo "   ℹ️ No changes needed\n";
    }
}

echo "\n🎯 Creating test script to verify email template...\n";

$testScript = '<?php

echo "=== TESTING EMAIL TEMPLATE WITH PROPER SHORTCODES ===\\n\\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", "root", "Vuivui@123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get email template
    $stmt = $pdo->query("SELECT * FROM notification_templates WHERE act LIKE \"%appointment%\" OR name LIKE \"%appointment%" LIMIT 1");
    $template = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$template) {
        echo "❌ No appointment template found\\n";
        exit;
    }
    
    echo "📧 Template found: {$template[\'name\']} (ID: {$template[\'id\']})\\n\\n";
    
    // Test shortcodes
    $testShortcodes = [
        \'user_name\' => \'Nguyễn Văn A\',
        \'user_email\' => \'nguyenvana@example.com\',
        \'appointment_id\' => \'123\',
        \'appointment_date\' => \'17/07/2025\',
        \'appointment_time\' => \'16:00\',
        \'company_name\' => \'Nguyễn Hoàng Tùng\',
        \'company_phone\' => \'0123456789\',
        \'appointment_address\' => \'123 Đường ABC, Quận XYZ\',
        \'site_url\' => \'http://localhost\',
        \'current_year\' => date(\'Y\')
    ];
    
    // Replace shortcodes in email body
    $emailBody = $template[\'email_body\'];
    $subject = $template[\'subject\'];
    
    foreach ($testShortcodes as $key => $value) {
        $emailBody = str_replace(\'{{\'.$key.\'}}\\', $value, $emailBody);
        $subject = str_replace(\'{{\'.$key.\'}}\\', $value, $subject);
    }
    
    echo "📝 Subject: $subject\\n\\n";
    echo "📧 Email preview:\\n";
    echo "═══════════════════════════════════════\\n";
    echo $emailBody;
    echo "\\n═══════════════════════════════════════\\n\\n";
    
    // Check for remaining unreplaced shortcodes
    preg_match_all(\'/{{([^}]+)}}/\', $emailBody, $matches);
    if (!empty($matches[1])) {
        echo "⚠️ Unreplaced shortcodes found:\\n";
        foreach (array_unique($matches[1]) as $shortcode) {
            echo "   - {{$shortcode}}\\n";
        }
    } else {
        echo "✅ All shortcodes replaced successfully!\\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\\n";
}
?>';

file_put_contents('test_email_template.php', $testScript);

echo "📧 Test script created: test_email_template.php\n";
echo "\n🎉 SHORTCODE UPDATE COMPLETE!\n\n";

echo "📋 Next steps:\n";
echo "1. Run: php test_email_template.php\n";
echo "2. Test appointment creation\n";
echo "3. Check email received\n";
echo "4. Verify all shortcodes are replaced\n";

?> 