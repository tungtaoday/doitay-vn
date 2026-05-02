<?php

namespace App\Services;

use App\Models\UserNotification;
use App\Models\User;
use App\Models\Company;

class NotificationService
{
    /**
     * Send in-app notification for appointment events
     */
    public static function sendAppointmentNotification($user, $appointment, $type)
    {
        $messages = [
            'appointment_created' => "Lịch hẹn của bạn đã được tạo và đang chờ xác nhận từ {$appointment->company->company_name}.",
            'appointment_confirmed' => "Tin tuyệt vời! Lịch hẹn của bạn với {$appointment->company->company_name} đã được xác nhận vào ngày {$appointment->appointment_date} lúc {$appointment->appointment_time}.",
            'appointment_completed' => "Dịch vụ của bạn với {$appointment->company->company_name} đã hoàn thành. Vui lòng xem xét để lại đánh giá!",
            'appointment_cancelled' => "Rất tiếc, lịch hẹn của bạn với {$appointment->company->company_name} đã bị hủy. Vui lòng liên hệ với họ để biết thêm thông tin.",
            'appointment_reminder' => "Nhắc nhở: Bạn có lịch hẹn với {$appointment->company->company_name} được lên lịch vào ngày {$appointment->appointment_date} lúc {$appointment->appointment_time}."
        ];

        $message = isset($messages[$type]) ? $messages[$type] : "Trạng thái lịch hẹn của bạn đã được cập nhật.";

        // Create notification for the user
        UserNotification::createAppointmentNotification($user, $type, $appointment, $message);

        // Notify company owner — but only when the caller is the customer,
        // not when the caller IS already the company owner (avoids duplicate).
        if ($appointment->company && $appointment->company->user &&
            $appointment->company->user->id !== $user->id) {
            $userName = $user->fullname ? $user->fullname : $user->username;
            
            $companyMessages = [
                'appointment_created' => "Yêu cầu lịch hẹn mới từ {$userName} vào ngày {$appointment->appointment_date} lúc {$appointment->appointment_time}.",
                'appointment_confirmed' => "Bạn đã xác nhận lịch hẹn với {$userName} vào ngày {$appointment->appointment_date} lúc {$appointment->appointment_time}.",
                'appointment_completed' => "Lịch hẹn với {$userName} đã được đánh dấu là hoàn thành. Dịch vụ được cung cấp vào ngày {$appointment->appointment_date}.",
                'appointment_cancelled' => "Lịch hẹn với {$userName} vào ngày {$appointment->appointment_date} đã bị hủy.",
                'appointment_reminder' => "Nhắc nhở: Bạn có lịch hẹn với {$userName} được lên lịch vào ngày {$appointment->appointment_date} lúc {$appointment->appointment_time}."
            ];
            
            $companyMessage = isset($companyMessages[$type]) ? $companyMessages[$type] : "Trạng thái lịch hẹn đã được cập nhật cho {$userName}.";
            // FIX: Pass company->user instead of company object
            UserNotification::createAppointmentNotification($appointment->company->user, $type, $appointment, $companyMessage);
        }
    }

    /**
     * Send marketing campaign notification
     */
    public static function sendCampaignNotification($user, $campaign, $customMessage = null)
    {
        $messages = [
            'WELCOME_CAMPAIGN' => "Chào mừng bạn đến với nền tảng của chúng tôi! Khám phá các dịch vụ tuyệt vời và được giảm 10% cho lần đặt dịch vụ đầu tiên.",
            'MONTHLY_NEWSLETTER' => "Khám phá những gì mới trong tháng này! Các tính năng mới, câu chuyện thành công và ưu đãi đặc biệt đang chờ bạn.",
            'COMPANY_PROMOTION' => "Phát triển doanh nghiệp của bạn với các tính năng Premium! Được giảm 40% cho 3 tháng đầu tiên."
        ];

                 $message = $customMessage ? $customMessage : (isset($messages[$campaign->act]) ? $messages[$campaign->act] : "Chúng tôi có tin tức thú vị và ưu đãi dành cho bạn!");

        UserNotification::createCampaignNotification($user, $campaign, $message);
    }

    /**
     * Send system notification
     */
    public static function sendSystemNotification($user, $title, $message, $type = 'system', $actionUrl = null)
    {
        $notification = UserNotification::createSystemNotification($user, $title, $message, $type);
        
        if ($actionUrl) {
            $notification->update(['action_url' => $actionUrl]);
        }

        return $notification;
    }

    /**
     * Send bulk notifications to multiple users
     */
    public static function sendBulkNotifications($users, $title, $message, $type = 'system', $actionUrl = null)
    {
        $notifications = [];
        
        foreach ($users as $user) {
            $notifications[] = self::sendSystemNotification($user, $title, $message, $type, $actionUrl);
        }

        return $notifications;
    }

    /**
     * Send notification to all users of a specific type
     */
    public static function sendToAllUsers($title, $message, $userType = 'user', $actionUrl = null)
    {
        if ($userType === 'user') {
            $users = User::where('status', 1)->get();
        } else {
            $users = Company::where('status', 1)->get();
        }

        return self::sendBulkNotifications($users, $title, $message, 'announcement', $actionUrl);
    }

    /**
     * Send appointment reminder notifications
     */
    public static function sendAppointmentReminders()
    {
        // Get appointments that are scheduled for tomorrow
        $appointments = \App\Models\Appointment::where('appointment_date', now()->addDay()->format('Y-m-d'))
            ->where('status', 'confirmed')
            ->with(['user', 'company'])
            ->get();

        foreach ($appointments as $appointment) {
            self::sendAppointmentNotification($appointment->user, $appointment, 'appointment_reminder');
        }

        return $appointments->count();
    }

    /**
     * Clean up old notifications
     */
    public static function cleanupOldNotifications($days = 30)
    {
        // Delete read notifications older than specified days
        $deletedCount = UserNotification::where('is_read', true)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        // Delete expired notifications
        $expiredCount = UserNotification::cleanupExpired();

        return [
            'deleted_old' => $deletedCount,
            'deleted_expired' => $expiredCount
        ];
    }

    /**
     * Get notification statistics for admin
     */
    public static function getAdminStatistics()
    {
        return [
            'total_notifications' => UserNotification::count(),
            'unread_notifications' => UserNotification::unread()->count(),
            'today_notifications' => UserNotification::whereDate('created_at', today())->count(),
            'this_week_notifications' => UserNotification::where('created_at', '>=', now()->startOfWeek())->count(),
            'by_type' => UserNotification::selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'by_priority' => UserNotification::selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority')
                ->toArray()
        ];
    }

    /**
     * Create custom notification with full options
     */
    public static function createCustomNotification($user, $options)
    {
        $defaultOptions = [
            'type' => 'system',
            'title' => 'Thông báo',
            'message' => '',
            'icon' => '🔔',
            'color' => 'blue',
            'priority' => 'normal',
            'is_important' => false,
            'action_url' => null,
            'expires_at' => null,
            'data' => null
        ];

        $options = array_merge($defaultOptions, $options);

        return UserNotification::create([
            'user_id' => $user->id,
            'user_type' => $user instanceof Company ? 'company' : 'user',
            'type' => $options['type'],
            'title' => $options['title'],
            'message' => $options['message'],
            'icon' => $options['icon'],
            'color' => $options['color'],
            'priority' => $options['priority'],
            'is_important' => $options['is_important'],
            'action_url' => $options['action_url'],
            'expires_at' => $options['expires_at'],
            'data' => $options['data']
        ]);
    }
} 