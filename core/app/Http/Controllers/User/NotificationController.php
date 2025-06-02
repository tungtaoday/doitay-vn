<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $pageTitle = 'Thông báo';
        
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(20);

        // Mark as read when viewed
        Auth::user()->unreadNotifications->markAsRead();

        return view('Template::user.notifications.index', compact('pageTitle', 'notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json([
            'count' => $count,
            'notifications' => Auth::user()->unreadNotifications->take(5)->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Thông báo mới',
                    'message' => $notification->data['message'] ?? '',
                    'time' => $notification->created_at->diffForHumans(),
                    'type' => $this->getNotificationType($notification->type),
                    'action_url' => $this->getActionUrl($notification)
                ];
            })
        ]);
    }

    private function getNotificationType($type)
    {
        $typeMap = [
            'App\Notifications\NewLeadNotification' => 'lead',
            'App\Notifications\NewAppointmentNotification' => 'appointment',
            'App\Notifications\AppointmentConfirmedNotification' => 'appointment',
            'App\Notifications\AppointmentCanceledNotification' => 'appointment',
            'App\Notifications\AppointmentCompletedNotification' => 'appointment',
        ];

        return $typeMap[$type] ?? 'general';
    }

    private function getActionUrl($notification)
    {
        $data = $notification->data;
        
        if (isset($data['lead_id'])) {
            return route('user.leads.show', $data['lead_id']);
        }
        
        if (isset($data['appointment_id'])) {
            return route('appointments.show', $data['appointment_id']);
        }

        return route('user.notifications.index');
    }

    public function delete($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }
} 