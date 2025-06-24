<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Models\User;
use App\Models\Company;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * Display all user notifications
     */
    public function index(Request $request)
    {
        $pageTitle = 'User Notifications Management';
        
        $query = UserNotification::with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->type && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        if ($request->status && $request->status != 'all') {
            if ($request->status == 'read') {
                $query->where('is_read', true);
            } elseif ($request->status == 'unread') {
                $query->where('is_read', false);
            }
        }

        if ($request->user_type && $request->user_type != 'all') {
            $query->where('user_type', $request->user_type);
        }

        if ($request->priority && $request->priority != 'all') {
            $query->where('priority', $request->priority);
        }

        $notifications = $query->paginate(20);

        // Get statistics
        $stats = NotificationService::getAdminStatistics();

        return view('admin.user_notifications.index', compact('pageTitle', 'notifications', 'stats'));
    }

    /**
     * Show notification statistics
     */
    public function statistics()
    {
        $pageTitle = 'Notification Statistics';
        $stats = NotificationService::getAdminStatistics();

        // Additional detailed stats
        $recentStats = [
            'today' => UserNotification::whereDate('created_at', today())->count(),
            'yesterday' => UserNotification::whereDate('created_at', today()->subDay())->count(),
            'this_week' => UserNotification::where('created_at', '>=', now()->startOfWeek())->count(),
            'last_week' => UserNotification::whereBetween('created_at', [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek()
            ])->count(),
            'this_month' => UserNotification::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        // Top active users (most notifications)
        $topUsers = UserNotification::selectRaw('user_id, user_type, count(*) as notification_count')
            ->groupBy('user_id', 'user_type')
            ->orderBy('notification_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.user_notifications.statistics', compact('pageTitle', 'stats', 'recentStats', 'topUsers'));
    }

    /**
     * Create and send custom notification
     */
    public function create()
    {
        $pageTitle = 'Send Custom Notification';
        
        // Get users and companies for recipient selection
        $users = User::where('status', 1)->select('id', 'username', 'email')->get();
        $companies = Company::where('status', 1)->select('id', 'company_name', 'email')->get();

        return view('admin.user_notifications.create', compact('pageTitle', 'users', 'companies'));
    }

    /**
     * Store and send custom notification
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array|min:1',
            'recipient_type' => 'required|in:specific,all_users,all_companies',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string',
            'icon' => 'nullable|string',
            'color' => 'required|in:blue,green,red,yellow,purple,orange',
            'priority' => 'required|in:low,normal,high',
            'is_important' => 'boolean',
            'action_url' => 'nullable|url',
            'expires_at' => 'nullable|date'
        ]);

        $recipients = [];

        // Get recipients based on type
        if ($request->recipient_type === 'all_users') {
            $recipients = User::where('status', 1)->get();
        } elseif ($request->recipient_type === 'all_companies') {
            $recipients = Company::where('status', 1)->get();
        } else {
            // Specific recipients
            foreach ($request->recipients as $recipientData) {
                $id = $recipientData['id'];
                $type = $recipientData['type'];
                
                if ($type === 'user') {
                    $user = User::find($id);
                    if ($user) $recipients[] = $user;
                } elseif ($type === 'company') {
                    $company = Company::find($id);
                    if ($company) $recipients[] = $company;
                }
            }
        }

        // Send notifications
        $sentCount = 0;
        foreach ($recipients as $recipient) {
            try {
                NotificationService::createCustomNotification($recipient, [
                    'type' => $request->type,
                    'title' => $request->title,
                    'message' => $request->message,
                    'icon' => $request->icon ?: '📢',
                    'color' => $request->color,
                    'priority' => $request->priority,
                    'is_important' => $request->is_important ?? false,
                    'action_url' => $request->action_url,
                    'expires_at' => $request->expires_at ? \Carbon\Carbon::parse($request->expires_at) : null
                ]);
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send notification to ' . get_class($recipient) . ' ' . $recipient->id . ': ' . $e->getMessage());
            }
        }

        $notify[] = ['success', "Custom notification sent to {$sentCount} recipients"];
        return redirect()->route('admin.user.notifications.index')->withNotify($notify);
    }

    /**
     * Send bulk notification to all users
     */
    public function sendBulkNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_type' => 'required|in:user,company,all',
            'type' => 'required|string',
            'priority' => 'required|in:low,normal,high'
        ]);

        try {
            if ($request->user_type === 'all') {
                // Send to both users and companies
                $userNotifications = NotificationService::sendToAllUsers(
                    $request->title, 
                    $request->message, 
                    'user'
                );
                $companyNotifications = NotificationService::sendToAllUsers(
                    $request->title, 
                    $request->message, 
                    'company'
                );
                $totalSent = count($userNotifications) + count($companyNotifications);
            } else {
                $notifications = NotificationService::sendToAllUsers(
                    $request->title, 
                    $request->message, 
                    $request->user_type
                );
                $totalSent = count($notifications);
            }

            $notify[] = ['success', "Bulk notification sent to {$totalSent} recipients"];
        } catch (\Exception $e) {
            $notify[] = ['error', 'Failed to send bulk notification: ' . $e->getMessage()];
        }

        return back()->withNotify($notify);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = UserNotification::findOrFail($id);
        $notification->delete();

        $notify[] = ['success', 'Notification deleted successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Bulk delete notifications
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array|min:1',
            'notification_ids.*' => 'exists:user_notifications,id'
        ]);

        $deletedCount = UserNotification::whereIn('id', $request->notification_ids)->delete();

        $notify[] = ['success', "Deleted {$deletedCount} notifications"];
        return back()->withNotify($notify);
    }

    /**
     * Clean up old notifications
     */
    public function cleanup(Request $request)
    {
        $days = $request->input('days', 30);
        
        try {
            $result = NotificationService::cleanupOldNotifications($days);
            
            $notify[] = ['success', 
                "Cleanup completed: Deleted {$result['deleted_old']} old notifications and {$result['deleted_expired']} expired notifications"
            ];
        } catch (\Exception $e) {
            $notify[] = ['error', 'Cleanup failed: ' . $e->getMessage()];
        }

        return back()->withNotify($notify);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:user_notifications,id'
        ]);

        try {
            $notification = UserNotification::findOrFail($request->notification_id);
            
            if (!$notification->is_read) {
                $notification->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Notification marked as read']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to mark as read'], 500);
        }
    }
} 