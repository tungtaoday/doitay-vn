<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Helper method to determine user type consistently
     */
    private function getUserType($user)
    {
        // Use same logic as in UserNotification::createLeadNotification
        $isCompany = $user && $user->companies()->exists();
        return $isCompany ? 'company' : 'user';
    }

    /**
     * Get notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // FIX: Query by user_id only, ignore user_type to catch all notifications
        $query = UserNotification::where('user_id', $user->id)
            ->active()
            ->orderBy('is_important', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by type if specified
        if ($request->has('type') && $request->type != 'all') {
            $query->byType($request->type);
        }

        // Filter by read status
        if ($request->has('status')) {
            if ($request->status == 'unread') {
                $query->unread();
            } elseif ($request->status == 'read') {
                $query->read();
            }
        }

        $notifications = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'total' => $notifications->total()
                ]
            ]);
        }

        $pageTitle = "Notifications";
        return view('user.notifications.index', compact('notifications', 'pageTitle'));
    }

    /**
     * Get unread notifications count and recent notifications for header
     */
    public function headerData()
    {
        try {
            $user = Auth::user();
            
            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Not authenticated',
                    'unread_count' => 0,
                    'notifications' => []
                ], 401);
            }
            
            $userType = $this->getUserType($user);
            
            // Test if UserNotification model exists and database is accessible
            if (!class_exists('App\Models\UserNotification')) {
                return response()->json([
                    'success' => false,
                    'error' => 'UserNotification model not found',
                    'unread_count' => 0,
                    'notifications' => []
                ], 500);
            }
            
            try {
                // FIX: Query by user_id only, ignore user_type to catch all notifications
                $unreadCount = UserNotification::where('user_id', $user->id)
                    ->unread()
                    ->active()
                    ->count();
            } catch (\Exception $e) {
                // If UserNotification table doesn't exist, return empty data
                return response()->json([
                    'success' => true,
                    'unread_count' => 0,
                    'notifications' => [],
                    'debug' => 'Database error: ' . $e->getMessage()
                ]);
            }

            try {
                // FIX: Query by user_id only, ignore user_type to catch all notifications
                $recentNotifications = UserNotification::where('user_id', $user->id)
                    ->active()
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                $recentNotifications = collect(); // Empty collection
            }

            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount,
                'notifications' => $recentNotifications->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'icon' => $notification->icon,
                        'color' => $notification->color,
                        'is_read' => $notification->is_read,
                        'is_important' => $notification->is_important,
                        'time_ago' => $notification->time_ago ?? $notification->created_at->diffForHumans(),
                        'action_url' => $notification->action_url
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Notification headerData error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return safe response
            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'unread_count' => 0,
                'notifications' => [],
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Not authenticated'
            ], 401);
        }
        
        $userType = $this->getUserType($user);
        
        $notification = UserNotification::where('user_id', $user->id)
            ->findOrFail($id);
            
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread($id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $notification = UserNotification::where('user_id', $user->id)
            ->findOrFail($id);
            
        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        UserNotification::where('user_id', $user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $notification = UserNotification::where('user_id', $user->id)
            ->findOrFail($id);
            
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $deletedCount = UserNotification::where('user_id', $user->id)
            ->read()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Deleted {$deletedCount} read notifications"
        ]);
    }

    /**
     * Get notification statistics
     */
    public function statistics()
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $stats = [
            'total' => UserNotification::where('user_id', $user->id)->active()->count(),
            'unread' => UserNotification::where('user_id', $user->id)->unread()->active()->count(),
            'important' => UserNotification::where('user_id', $user->id)->important()->active()->count(),
            'today' => UserNotification::where('user_id', $user->id)->whereDate('created_at', today())->count(),
            'this_week' => UserNotification::where('user_id', $user->id)->where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        // By type statistics
        $typeStats = UserNotification::where('user_id', $user->id)
            ->active()
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'type_stats' => $typeStats
        ]);
    }

    /**
     * Handle notification click (mark as read and redirect)
     */
    public function click(Request $request, $id)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        $notification = UserNotification::where('user_id', $user->id)
            ->findOrFail($id);
            
        // Mark as read if not already read
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        // Redirect to action URL if exists
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        // Default redirect to notifications page
        return redirect()->route('user.notifications.index');
    }
} 