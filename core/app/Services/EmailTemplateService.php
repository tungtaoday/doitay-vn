<?php

namespace App\Services;

use App\Models\GeneralSetting;

class EmailTemplateService
{
    /**
     * Wrap email content with professional template
     */
    public static function wrapWithProfessionalTemplate($emailBody, $subject, $userEmail = null)
    {
        $gs = GeneralSetting::first();
        
        // Build logo URL
        $logoUrl = asset('assets/images/logo_icon/logo.png');
        if (file_exists(public_path('assets/images/logo_icon/logo.png'))) {
            $logoUrl = asset('assets/images/logo_icon/logo.png');
        } else {
            // Fallback to a default logo or company name text
            $logoUrl = '';
        }
        
        // Get site information
        $siteName = $gs->site_name ?? 'Service Platform';
        $siteUrl = url('/');
        $currentYear = date('Y');
        
        // Load the professional wrapper template
        $wrapperTemplate = view('email_templates.professional_wrapper')->render();
        
        // Replace placeholders
        $wrapperTemplate = str_replace('{{subject}}', $subject, $wrapperTemplate);
        $wrapperTemplate = str_replace('{{logo_url}}', $logoUrl, $wrapperTemplate);
        $wrapperTemplate = str_replace('{{site_name}}', $siteName, $wrapperTemplate);
        $wrapperTemplate = str_replace('{{site_url}}', $siteUrl, $wrapperTemplate);
        $wrapperTemplate = str_replace('{{current_year}}', $currentYear, $wrapperTemplate);
        $wrapperTemplate = str_replace('{{user_email}}', $userEmail ?? 'user@example.com', $wrapperTemplate);
        $wrapperTemplate = str_replace('{{unsubscribe_url}}', $siteUrl . '/unsubscribe', $wrapperTemplate);
        $wrapperTemplate = str_replace('{!! $email_body !!}', $emailBody, $wrapperTemplate);
        
        return $wrapperTemplate;
    }
    
    /**
     * Process email template with shortcodes and professional formatting
     */
    public static function processEmailTemplate($template, $shortCodes = [], $userEmail = null)
    {
        // First process shortcodes in the email body
        $emailBody = $template->email_body;
        
        // Replace shortcodes
        foreach ($shortCodes as $code => $value) {
            $emailBody = str_replace('{{'.$code.'}}', $value, $emailBody);
            $emailBody = str_replace('{{'.$code.'}}', $value, $emailBody);
        }
        
        // Process subject with shortcodes
        $subject = $template->subject;
        foreach ($shortCodes as $code => $value) {
            $subject = str_replace('{{'.$code.'}}', $value, $subject);
        }
        
        // Wrap with professional template
        $finalEmailContent = self::wrapWithProfessionalTemplate($emailBody, $subject, $userEmail);
        
        return [
            'subject' => $subject,
            'email_body' => $finalEmailContent,
            'original_body' => $emailBody
        ];
    }
    
    /**
     * Generate appointment-related shortcodes
     */
    public static function generateAppointmentShortcodes($appointment)
    {
        $gs = GeneralSetting::first();
        $siteName = $gs->site_name ?? 'Service Platform';
        $siteUrl = url('/');
        
        return [
            'site_name' => $siteName,
            'site_url' => $siteUrl,
            'user_name' => $appointment->user->fullname ?? $appointment->user->username,
            'user_email' => $appointment->user->email,
            'appointment_id' => $appointment->id,
            'appointment_date' => $appointment->date ? date('F j, Y', strtotime($appointment->date)) : 'TBD',
            'appointment_time' => $appointment->time ?? 'TBD',
            'company_name' => $appointment->company->name ?? 'Service Provider',
            'company_address' => $appointment->company->address ?? 'Address will be provided',
            'company_phone' => $appointment->company->mobile ?? '',
            'appointment_url' => $siteUrl . '/user/appointments/' . $appointment->id,
            'reschedule_url' => $siteUrl . '/user/appointments/' . $appointment->id . '/reschedule',
            'contact_url' => $siteUrl . '/contact',
            'review_url' => $siteUrl . '/user/appointments/' . $appointment->id . '/review',
            'book_again_url' => $siteUrl . '/appointments/book?company=' . ($appointment->company->id ?? ''),
            'browse_services_url' => $siteUrl . '/services',
            'book_new_url' => $siteUrl . '/appointments/book',
            'contact_company_url' => $siteUrl . '/company/' . ($appointment->company->id ?? '') . '/contact',
            'cancellation_reason' => $appointment->admin_feedback ?? 'No reason provided',
            'current_year' => date('Y')
        ];
    }
    
    /**
     * Generate marketing campaign shortcodes
     */
    public static function generateMarketingShortcodes($user = null)
    {
        $gs = GeneralSetting::first();
        $siteName = $gs->site_name ?? 'Service Platform';
        $siteUrl = url('/');
        
        return [
            'site_name' => $siteName,
            'site_url' => $siteUrl,
            'user_name' => $user ? ($user->fullname ?? $user->username) : 'Valued Customer',
            'user_email' => $user ? $user->email : 'user@example.com',
            'profile_url' => $siteUrl . '/user/profile-setting',
            'services_url' => $siteUrl . '/services',
            'book_url' => $siteUrl . '/appointments/book',
            'get_started_url' => $siteUrl . '/services',
            'dashboard_url' => $siteUrl . '/user/dashboard',
            'browse_url' => $siteUrl . '/services',
            'app_download_url' => $siteUrl . '/mobile-app',
            'upgrade_url' => $siteUrl . '/company/premium',
            'demo_url' => $siteUrl . '/premium-demo',
            'consultation_url' => $siteUrl . '/contact',
            'support_phone' => $gs->phone ?? '+1-800-SUPPORT',
            'business_email' => 'business@' . str_replace(['http://', 'https://'], '', $siteUrl),
            'monthly_appointments' => '2,847',
            'new_companies' => '156',
            'new_users' => '1,023',
            'current_year' => date('Y')
        ];
    }
    
    /**
     * Get logo URL for emails
     */
    public static function getLogoUrl()
    {
        // Try different logo locations
        $logoFiles = [
            'assets/images/logo_icon/logo.png',
            'assets/images/logo_icon/logo_white.png',
            'assets/images/logo.png',
            'public/assets/images/logo_icon/logo.png'
        ];
        
        foreach ($logoFiles as $logoFile) {
            if (file_exists(public_path($logoFile))) {
                return asset($logoFile);
            }
        }
        
        // Return empty string if no logo found
        return '';
    }
    
    /**
     * Create a simple text-based logo if image logo is not available
     */
    public static function getLogoHtml($siteName)
    {
        $logoUrl = self::getLogoUrl();
        
        if ($logoUrl) {
            return '<img src="' . $logoUrl . '" alt="' . $siteName . ' Logo" class="logo" />';
        } else {
            // Return stylized text logo
            return '<div class="text-logo" style="font-size: 32px; font-weight: bold; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">' . $siteName . '</div>';
        }
    }
} 