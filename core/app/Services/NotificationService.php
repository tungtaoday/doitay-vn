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
            'appointment_created' => "Your appointment has been created and is waiting for confirmation from {$appointment->company->company_name}.",
            'appointment_confirmed' => "Great news! Your appointment with {$appointment->company->company_name} has been confirmed for {$appointment->appointment_date} at {$appointment->appointment_time}.",
            'appointment_completed' => "Your service with {$appointment->company->company_name} has been completed. Please consider leaving a review!",
            'appointment_cancelled' => "Unfortunately, your appointment with {$appointment->company->company_name} has been cancelled. Please contact them for more information.",
            'appointment_reminder' => "Reminder: You have an appointment with {$appointment->company->company_name} scheduled for {$appointment->appointment_date} at {$appointment->appointment_time}."
        ];

        $message = isset($messages[$type]) ? $messages[$type] : "Your appointment status has been updated.";

        // Create notification for the user
        UserNotification::createAppointmentNotification($user, $type, $appointment, $message);

        // Always notify the company for all appointment events
        if ($appointment->company && $appointment->company->user) {
            $userName = $user->fullname ? $user->fullname : $user->username;
            
            $companyMessages = [
                'appointment_created' => "New appointment request from {$userName} for {$appointment->appointment_date} at {$appointment->appointment_time}.",
                'appointment_confirmed' => "You have confirmed the appointment with {$userName} for {$appointment->appointment_date} at {$appointment->appointment_time}.",
                'appointment_completed' => "Appointment with {$userName} has been marked as completed. Service provided on {$appointment->appointment_date}.",
                'appointment_cancelled' => "Appointment with {$userName} for {$appointment->appointment_date} has been cancelled.",
                'appointment_reminder' => "Reminder: You have an appointment with {$userName} scheduled for {$appointment->appointment_date} at {$appointment->appointment_time}."
            ];
            
            $companyMessage = isset($companyMessages[$type]) ? $companyMessages[$type] : "Appointment status updated for {$userName}.";
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
            'WELCOME_CAMPAIGN' => "Welcome to our platform! Discover amazing services and get 10% off your first booking.",
            'MONTHLY_NEWSLETTER' => "Check out what's new this month! New features, success stories, and special offers await you.",
            'COMPANY_PROMOTION' => "Boost your business with our Premium features! Get 40% off for the first 3 months."
        ];

                 $message = $customMessage ? $customMessage : (isset($messages[$campaign->act]) ? $messages[$campaign->act] : "We have exciting news and offers for you!");

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
            'title' => 'Notification',
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