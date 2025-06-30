<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    private $notificationTemplates = [
        'lead_new' => [
            'title' => 'Có lead mới phù hợp với bạn',
            'message' => 'Có một yêu cầu dịch vụ mới trong khu vực của bạn. Hãy xem ngay!',
            'icon' => 'bell',
            'color' => 'primary',
            'user_type' => 'contractor'
        ],
        'lead_purchased' => [
            'title' => 'Lead của bạn đã được mua',
            'message' => 'Thông tin liên hệ khách hàng đã được gửi qua email.',
            'icon' => 'check-circle',
            'color' => 'success',
            'user_type' => 'contractor'
        ],
        'review_received' => [
            'title' => 'Bạn nhận được đánh giá mới',
            'message' => 'Khách hàng vừa để lại đánh giá cho dịch vụ của bạn.',
            'icon' => 'star',
            'color' => 'warning',
            'user_type' => 'contractor'
        ],
        'lead_accepted' => [
            'title' => 'Yêu cầu của bạn đã được chấp nhận',
            'message' => 'Một thợ đã chấp nhận yêu cầu dịch vụ của bạn và sẽ liên hệ sớm.',
            'icon' => 'user-check',
            'color' => 'success',
            'user_type' => 'customer'
        ],
        'profile_incomplete' => [
            'title' => 'Hoàn thiện hồ sơ để nhận thêm lead',
            'message' => 'Hồ sơ chưa đầy đủ có thể ảnh hưởng đến cơ hội nhận việc.',
            'icon' => 'user',
            'color' => 'info',
            'user_type' => 'contractor'
        ],
        'payment_success' => [
            'title' => 'Thanh toán thành công',
            'message' => 'Giao dịch mua lead đã được xử lý thành công.',
            'icon' => 'credit-card',
            'color' => 'success',
            'user_type' => 'contractor'
        ],
        'system_maintenance' => [
            'title' => 'Thông báo bảo trì hệ thống',
            'message' => 'Hệ thống sẽ được bảo trì vào 2:00 AM ngày mai. Thời gian dự kiến: 2 giờ.',
            'icon' => 'settings',
            'color' => 'warning',
            'user_type' => 'all'
        ],
        'welcome' => [
            'title' => 'Chào mừng bạn đến với DoiTay.vn',
            'message' => 'Cảm ơn bạn đã đăng ký. Hãy hoàn thiện hồ sơ để bắt đầu nhận việc!',
            'icon' => 'heart',
            'color' => 'primary',
            'user_type' => 'all'
        ]
    ];
    
    public function run()
    {
        echo "🔔 Tạo notifications...\n";
        
        $users = User::all();
        $contractors = User::whereHas('companies')->get();
        $customers = User::whereDoesntHave('companies')->get();
        
        $totalNotifications = 0;
        
        foreach ($users as $user) {
            $isContractor = $contractors->contains($user);
            
            // Số lượng notification cho mỗi user (5-20)
            $notificationCount = rand(5, 20);
            
            for ($i = 0; $i < $notificationCount; $i++) {
                // Chọn template phù hợp với user type
                $availableTemplates = [];
                foreach ($this->notificationTemplates as $key => $template) {
                    if ($template['user_type'] === 'all' || 
                        ($template['user_type'] === 'contractor' && $isContractor) ||
                        ($template['user_type'] === 'customer' && !$isContractor)) {
                        $availableTemplates[$key] = $template;
                    }
                }
                
                $templateKey = array_rand($availableTemplates);
                $template = $availableTemplates[$templateKey];
                
                // Thời gian tạo notification (từ khi user đăng ký đến hiện tại)
                $userCreatedAt = Carbon::parse($user->created_at);
                $notificationTime = $userCreatedAt->copy()->addDays(rand(0, $userCreatedAt->diffInDays(Carbon::now())));
                
                // Thêm random giờ trong ngày
                $notificationTime->setHour(rand(0, 23))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
                
                // Trạng thái đọc (70% đã đọc, 30% chưa đọc)
                $isRead = rand(1, 100) <= 70 ? 1 : 0;
                
                // Priority (80% normal, 15% high, 5% low)
                $priorityRand = rand(1, 100);
                if ($priorityRand <= 80) {
                    $priority = 'normal';
                } elseif ($priorityRand <= 95) {
                    $priority = 'high';
                } else {
                    $priority = 'low';
                }
                
                // Action URL tùy theo loại notification
                $actionUrl = $this->getActionUrl($templateKey, $user);
                
                // Expiry (một số notification có thời hạn)
                $expiresAt = null;
                if (in_array($templateKey, ['system_maintenance', 'profile_incomplete'])) {
                    $expiresAt = $notificationTime->copy()->addDays(rand(7, 30));
                }
                
                DB::table('user_notifications')->insert([
                    'user_id' => $user->id,
                    'user_type' => $isContractor ? 'contractor' : 'customer',
                    'title' => $template['title'],
                    'message' => $template['message'],
                    'icon' => $template['icon'],
                    'color' => $template['color'],
                    'action_url' => $actionUrl,
                    'is_read' => $isRead,
                    'priority' => $priority,
                    'is_important' => rand(1, 100) <= 20 ? 1 : 0, // 20% important
                    'expires_at' => $expiresAt,
                    'created_at' => $notificationTime,
                    'updated_at' => $notificationTime,
                ]);
                
                $totalNotifications++;
            }
            
            if ($totalNotifications % 200 == 0) {
                echo "   Đã tạo {$totalNotifications} notifications...\n";
            }
        }
        
        echo "✅ Đã tạo {$totalNotifications} notifications\n";
    }
    
    private function getActionUrl($templateKey, $user)
    {
        $baseUrls = [
            'lead_new' => '/contractor/leads',
            'lead_purchased' => '/contractor/purchased-leads',
            'review_received' => '/contractor/reviews',
            'lead_accepted' => '/customer/my-requests',
            'profile_incomplete' => '/profile/edit',
            'payment_success' => '/contractor/transactions',
            'system_maintenance' => '/notifications',
            'welcome' => '/dashboard'
        ];
        
        return $baseUrls[$templateKey] ?? '/dashboard';
    }
} 