<?php

echo "=== FIXING APPOINTMENT SHORTCODE MAPPING ===\n\n";

try {
    // Include Laravel to access the system
    require_once 'core/bootstrap/app.php';
    
    echo "1. Analyzing current shortcode mapping issues...\n";
    
    echo "   Current AppointmentController shortcodes:\n";
    echo "   - customer_name → should be: user_name\n";
    echo "   - customer_phone → should be: company_phone (from company)\n";
    echo "   - customer_address → should be: appointment_address\n";
    echo "   - Missing: appointment_id, appointment_url, current_year\n\n";
    
    echo "2. Email template expects these shortcodes:\n";
    $expectedShortcodes = [
        'user_name' => 'User full name',
        'user_email' => 'User email',
        'appointment_id' => 'Appointment ID',
        'appointment_date' => 'Formatted appointment date',
        'appointment_time' => 'Appointment time',
        'company_name' => 'Company name',
        'company_phone' => 'Company phone number',
        'appointment_address' => 'Appointment location',
        'site_url' => 'Website URL',
        'current_year' => 'Current year'
    ];
    
    foreach ($expectedShortcodes as $code => $desc) {
        echo "   - {{$code}} : $desc\n";
    }
    
    echo "\n3. Generating corrected shortcode function...\n";
    
    $fixedFunction = '
    /**
     * Generate proper appointment shortcodes
     */
    public static function generateAppointmentShortcodes($appointment, $user = null)
    {
        $user = $user ?: $appointment->user;
        $company = $appointment->company;
        
        return [
            \'site_name\' => gs(\'site_name\') ?: \'Doitay.vn\',
            \'site_url\' => url(\'/\'),
            \'user_name\' => $user->fullname ?: ($user->firstname . \' \' . $user->lastname) ?: $user->username ?: $user->name,
            \'user_email\' => $user->email,
            \'appointment_id\' => $appointment->id,
            \'appointment_date\' => $appointment->appointment_date ? date(\'d/m/Y\', strtotime($appointment->appointment_date)) : \'TBD\',
            \'appointment_time\' => $appointment->appointment_time ?: \'TBD\',
            \'company_name\' => $company->name ?: \'Service Provider\',
            \'company_phone\' => $company->mobile ?: $company->phone ?: \'Sẽ cập nhật sau\',
            \'appointment_address\' => $appointment->recipient_address ?: \'Địa chỉ sẽ được cung cấp\',
            \'appointment_url\' => route(\'user.appointments.show\', $appointment->id),
            \'contact_url\' => route(\'contact\'),
            \'current_year\' => date(\'Y\'),
            
            // Legacy support
            \'customer_name\' => $appointment->recipient_name,
            \'customer_phone\' => $appointment->recipient_phone,
            \'customer_address\' => $appointment->recipient_address,
            \'notes\' => $appointment->notes ?: \'Không có ghi chú đặc biệt\'
        ];
    }';
    
    echo "✅ Function generated\n\n";
    
    echo "4. Testing shortcode generation with sample data...\n";
    
    // Test with database
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get a sample appointment
    $stmt = $pdo->query("
        SELECT a.*, c.name as company_name, c.mobile as company_phone, 
               u.firstname, u.lastname, u.username, u.email as user_email
        FROM appointments a 
        LEFT JOIN companies c ON a.company_id = c.id 
        LEFT JOIN users u ON a.user_id = u.id 
        ORDER BY a.id DESC 
        LIMIT 1
    ");
    
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($appointment) {
        echo "   Sample appointment found: ID {$appointment['id']}\n";
        
        // Generate proper shortcodes
        $properShortcodes = [
            'site_name' => 'Doitay.vn',
            'site_url' => 'http://localhost',
            'user_name' => trim(($appointment['firstname'] ?: '') . ' ' . ($appointment['lastname'] ?: '')) ?: $appointment['username'] ?: 'Khách hàng',
            'user_email' => $appointment['user_email'],
            'appointment_id' => $appointment['id'],
            'appointment_date' => $appointment['appointment_date'] ? date('d/m/Y', strtotime($appointment['appointment_date'])) : 'TBD',
            'appointment_time' => $appointment['appointment_time'] ?: 'TBD',
            'company_name' => $appointment['company_name'] ?: 'Service Provider',
            'company_phone' => $appointment['company_phone'] ?: 'Sẽ cập nhật sau',
            'appointment_address' => $appointment['recipient_address'] ?: 'Địa chỉ sẽ được cung cấp',
            'current_year' => date('Y')
        ];
        
        echo "   Generated shortcodes:\n";
        foreach ($properShortcodes as $key => $value) {
            echo "     {{$key}} = '$value'\n";
        }
    } else {
        echo "   No appointments found in database\n";
    }
    
    echo "\n5. Next steps:\n";
    echo "   A. Update AppointmentController to use proper shortcodes\n";
    echo "   B. Update CompanyAppointmentController similarly\n";
    echo "   C. Test email sending with new shortcodes\n";
    echo "   D. Verify email template renders correctly\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} 