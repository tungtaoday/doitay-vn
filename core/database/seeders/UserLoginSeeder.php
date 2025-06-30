<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class UserLoginSeeder extends Seeder
{
    private $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0',
        'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
    ];
    
    private $cities = [
        'Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 'Biên Hòa', 'Nha Trang', 'Huế'
    ];
    
    private $ipRanges = [
        '14.160.', '14.161.', '14.162.', '14.163.', '14.164.', '14.165.', '14.166.', '14.167.',
        '27.64.', '27.65.', '27.66.', '27.67.', '27.68.', '27.69.', '27.70.', '27.71.',
        '113.160.', '113.161.', '113.162.', '113.163.', '113.164.', '113.165.', '113.166.',
        '171.224.', '171.225.', '171.226.', '171.227.', '171.228.', '171.229.', '171.230.',
    ];
    
    public function run()
    {
        echo "🔐 Tạo lịch sử đăng nhập...\n";
        
        $users = User::all();
        $totalLogins = 0;
        
        foreach ($users as $user) {
            // Số lần đăng nhập cho mỗi user (từ 5-50 lần trong năm qua)
            $loginCount = rand(5, 50);
            
            // Thời gian đăng ký của user
            $userCreatedAt = Carbon::parse($user->created_at);
            
            for ($i = 0; $i < $loginCount; $i++) {
                // Thời gian đăng nhập random từ lúc tạo tài khoản đến hiện tại
                $loginTime = $userCreatedAt->copy()->addDays(rand(0, $userCreatedAt->diffInDays(Carbon::now())));
                
                // Thêm random giờ trong ngày (80% trong giờ hành chính, 20% ngoài giờ)
                if (rand(1, 100) <= 80) {
                    // Giờ hành chính (7:00 - 18:00)
                    $loginTime->setHour(rand(7, 18))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
                } else {
                    // Ngoài giờ hành chính
                    $hour = rand(1, 100) <= 50 ? rand(19, 23) : rand(0, 6);
                    $loginTime->setHour($hour)->setMinute(rand(0, 59))->setSecond(rand(0, 59));
                }
                
                // Tạo IP address Vietnam
                $ipPrefix = $this->ipRanges[array_rand($this->ipRanges)];
                $ipAddress = $ipPrefix . rand(1, 254) . '.' . rand(1, 254);
                
                // Location info
                $city = $this->cities[array_rand($this->cities)];
                $latitude = $this->getLatitude($city);
                $longitude = $this->getLongitude($city);
                
                // User agent
                $userAgent = $this->userAgents[array_rand($this->userAgents)];
                
                // OS và Browser từ user agent
                $osInfo = $this->parseUserAgent($userAgent);
                
                DB::table('user_logins')->insert([
                    'user_id' => $user->id,
                    'user_ip' => $ipAddress,
                    'city' => $city,
                    'country' => 'Vietnam',
                    'country_code' => 'VN',
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'browser' => $osInfo['browser'],
                    'os' => $osInfo['os'],
                    'user_agent' => $userAgent,
                    'created_at' => $loginTime,
                    'updated_at' => $loginTime,
                ]);
                
                $totalLogins++;
            }
            
            if ($totalLogins % 500 == 0) {
                echo "   Đã tạo {$totalLogins} login records...\n";
            }
        }
        
        echo "✅ Đã tạo {$totalLogins} login records\n";
    }
    
    private function getLatitude($city)
    {
        $coordinates = [
            'Hà Nội' => 21.0285,
            'Hồ Chí Minh' => 10.8231,
            'Đà Nẵng' => 16.0544,
            'Hải Phòng' => 20.8449,
            'Cần Thơ' => 10.0452,
            'Biên Hòa' => 10.9500,
            'Nha Trang' => 12.2388,
            'Huế' => 16.4637,
        ];
        
        return $coordinates[$city] ?? 21.0285;
    }
    
    private function getLongitude($city)
    {
        $coordinates = [
            'Hà Nội' => 105.8542,
            'Hồ Chí Minh' => 106.6297,
            'Đà Nẵng' => 108.2022,
            'Hải Phòng' => 106.6881,
            'Cần Thơ' => 105.7469,
            'Biên Hòa' => 106.8200,
            'Nha Trang' => 109.1967,
            'Huế' => 107.5909,
        ];
        
        return $coordinates[$city] ?? 105.8542;
    }
    
    private function parseUserAgent($userAgent)
    {
        $browser = 'Unknown';
        $os = 'Unknown';
        
        // Detect browser
        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $browser = 'Edge';
        }
        
        // Detect OS
        if (strpos($userAgent, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (strpos($userAgent, 'Mac OS X') !== false) {
            $os = 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $os = 'Linux';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            $os = 'iOS';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $os = 'Android';
        } elseif (strpos($userAgent, 'iPad') !== false) {
            $os = 'iPadOS';
        }
        
        return [
            'browser' => $browser,
            'os' => $os
        ];
    }
} 