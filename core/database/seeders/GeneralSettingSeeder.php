<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingSeeder extends Seeder
{
    public function run()
    {
        echo "⚙️ Tạo general settings...\n";
        
        $settings = [
            // Site Information
            ['key' => 'site_name', 'value' => 'DoiTay.vn'],
            ['key' => 'site_title', 'value' => 'DoiTay.vn - Kết nối thợ chuyên nghiệp'],
            ['key' => 'site_description', 'value' => 'Nền tảng kết nối khách hàng với thợ chuyên nghiệp tại Việt Nam. Tìm thợ điện, thợ nước, thợ xây dựng, thợ sơn, thợ mộc uy tín.'],
            ['key' => 'site_keywords', 'value' => 'thợ điện, thợ nước, thợ xây dựng, thợ sơn, thợ mộc, sửa chữa nhà cửa, dịch vụ thợ'],
            ['key' => 'site_author', 'value' => 'DoiTay.vn Team'],
            
            // Contact Information
            ['key' => 'contact_email', 'value' => 'info@doitay.vn'],
            ['key' => 'support_email', 'value' => 'support@doitay.vn'],
            ['key' => 'contact_phone', 'value' => '1900-xxxx'],
            ['key' => 'contact_address', 'value' => 'Tầng 10, Tòa nhà ABC, Số 123 Đường XYZ, Quận 1, Hà Nội'],
            ['key' => 'business_hours', 'value' => 'Thứ 2 - Chủ nhật: 8:00 - 22:00'],
            
            // Social Media
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/doitay.vn'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/@doitayvn'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/doitay.vn'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/company/doitay-vn'],
            
            // System Settings
            ['key' => 'active_template', 'value' => 'basic'],
            ['key' => 'system_info', 'value' => json_encode([
                'version' => '2.0.0',
                'last_updated' => date('Y-m-d H:i:s'),
                'environment' => 'production'
            ])],
            ['key' => 'timezone', 'value' => 'Asia/Ho_Chi_Minh'],
            ['key' => 'locale', 'value' => 'vi'],
            ['key' => 'currency', 'value' => 'VND'],
            ['key' => 'currency_symbol', 'value' => '₫'],
            
            // Email Configuration
            ['key' => 'email_from', 'value' => 'noreply@doitay.vn'],
            ['key' => 'email_from_name', 'value' => 'DoiTay.vn'],
            ['key' => 'email_template', 'value' => 'default'],
            
            // Registration & Login
            ['key' => 'registration_enabled', 'value' => '1'],
            ['key' => 'email_verification', 'value' => '1'],
            ['key' => 'sms_verification', 'value' => '1'],
            ['key' => 'social_login_enabled', 'value' => '1'],
            
            // Lead System
            ['key' => 'lead_price_min', 'value' => '50000'],
            ['key' => 'lead_price_max', 'value' => '500000'],
            ['key' => 'lead_expiry_days', 'value' => '30'],
            ['key' => 'max_leads_per_contractor', 'value' => '10'],
            ['key' => 'lead_auto_approval', 'value' => '0'],
            
            // Payment Settings
            ['key' => 'payment_methods', 'value' => json_encode(['bank_transfer', 'momo', 'zalopay', 'vnpay'])],
            ['key' => 'commission_rate', 'value' => '10'], // 10%
            ['key' => 'minimum_withdrawal', 'value' => '100000'], // 100k VND
            ['key' => 'withdrawal_fee', 'value' => '5000'], // 5k VND
            
            // SEO Settings
            ['key' => 'seo_title', 'value' => 'DoiTay.vn - Tìm thợ chuyên nghiệp nhanh chóng'],
            ['key' => 'seo_description', 'value' => 'Kết nối với hàng nghìn thợ chuyên nghiệp tại Việt Nam. Dịch vụ thợ điện, nước, xây dựng, sơn, mộc uy tín, giá cả hợp lý.'],
            ['key' => 'seo_image', 'value' => '/assets/images/seo-image.jpg'],
            ['key' => 'google_analytics_id', 'value' => 'GA-XXXXXXXXX'],
            ['key' => 'facebook_pixel_id', 'value' => ''],
            
            // Maintenance
            ['key' => 'maintenance_mode', 'value' => '0'],
            ['key' => 'maintenance_message', 'value' => 'Hệ thống đang được bảo trì. Vui lòng quay lại sau.'],
            
            // Terms & Privacy
            ['key' => 'terms_of_service', 'value' => 'Điều khoản dịch vụ của DoiTay.vn...'],
            ['key' => 'privacy_policy', 'value' => 'Chính sách bảo mật của DoiTay.vn...'],
            ['key' => 'cookie_policy', 'value' => 'Chính sách cookie của DoiTay.vn...'],
            
            // Statistics (Demo data)
            ['key' => 'total_contractors', 'value' => '100'],
            ['key' => 'total_customers', 'value' => '300'],
            ['key' => 'total_leads', 'value' => '500'],
            ['key' => 'total_completed_jobs', 'value' => '350'],
            ['key' => 'average_rating', 'value' => '4.2'],
            
            // App Settings
            ['key' => 'app_debug', 'value' => '0'],
            ['key' => 'app_url', 'value' => 'https://doitay.vn'],
            ['key' => 'app_logo', 'value' => '/assets/images/logo.png'],
            ['key' => 'app_favicon', 'value' => '/assets/images/favicon.png'],
            
            // Notification Settings
            ['key' => 'email_notifications', 'value' => '1'],
            ['key' => 'sms_notifications', 'value' => '1'],
            ['key' => 'push_notifications', 'value' => '1'],
            
            // Content Settings
            ['key' => 'about_us', 'value' => 'DoiTay.vn là nền tảng kết nối hàng đầu Việt Nam, giúp khách hàng tìm kiếm thợ chuyên nghiệp một cách nhanh chóng và tin cậy.'],
            ['key' => 'how_it_works', 'value' => json_encode([
                'step1' => 'Đăng yêu cầu dịch vụ',
                'step2' => 'Nhận báo giá từ thợ',
                'step3' => 'Chọn thợ phù hợp',
                'step4' => 'Hoàn thành công việc'
            ])],
            
            // API Settings
            ['key' => 'api_enabled', 'value' => '1'],
            ['key' => 'api_rate_limit', 'value' => '100'], // requests per minute
            
            // Cache Settings
            ['key' => 'cache_enabled', 'value' => '1'],
            ['key' => 'cache_duration', 'value' => '3600'], // 1 hour
        ];
        
        foreach ($settings as $setting) {
            DB::table('general_settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
        
        echo "✅ Đã tạo " . count($settings) . " general settings\n";
    }
} 