<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'type',
        'title',
        'message',
        'data',
        'icon',
        'color',
        'action_url',
        'is_read',
        'read_at',
        'priority',
        'is_important',
        'expires_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_important' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId, $userType = 'user')
    {
        return $query->where('user_id', $userId)->where('user_type', $userType);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    // Static methods for creating notifications
    public static function createAppointmentNotification($user, $type, $appointment, $message)
    {
        $icons = [
            'appointment_created' => '📅',
            'appointment_confirmed' => '✅',
            'appointment_completed' => '🎊',
            'appointment_cancelled' => '❌',
            'appointment_reminder' => '⏰'
        ];

        $colors = [
            'appointment_created' => 'blue',
            'appointment_confirmed' => 'green',
            'appointment_completed' => 'purple',
            'appointment_cancelled' => 'red',
            'appointment_reminder' => 'orange'
        ];

        // Determine the correct action URL based on user type
        $isCompany = $user instanceof \App\Models\Company;
        $actionUrl = null;
        
        try {
            if ($isCompany) {
                // For company/service provider - redirect to appointments list
                // They can view all appointments and decide which one to check
                $actionUrl = route('company.appointments.index');
            } else {
                // For regular user - direct to specific appointment details
                $actionUrl = route('appointments.show', $appointment->id);
            }
        } catch (\Exception $e) {
            // Fallback to dashboard if routes don't exist
            $actionUrl = route('user.dashboard');
        }

        return self::create([
            'user_id' => $user->id,
            'user_type' => $isCompany ? 'company' : 'user',
            'type' => 'appointment',
            'title' => self::getAppointmentTitle($type),
            'message' => $message,
            'data' => [
                'appointment_id' => $appointment->id,
                'appointment_type' => $type,
                'company_name' => $appointment->company->company_name ?? 'N/A',
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time
            ],
            'icon' => $icons[$type] ?? '📋',
            'color' => $colors[$type] ?? 'blue',
            'action_url' => $actionUrl,
            'priority' => in_array($type, ['appointment_cancelled', 'appointment_reminder']) ? 'high' : 'normal',
            'is_important' => in_array($type, ['appointment_confirmed', 'appointment_cancelled'])
        ]);
    }

    public static function createCampaignNotification($user, $campaign, $message)
    {
        return self::create([
            'user_id' => $user->id,
            'user_type' => $user instanceof \App\Models\Company ? 'company' : 'user',
            'type' => 'campaign',
            'title' => 'Khuyến mãi mới dành cho bạn',
            'message' => $message,
            'data' => [
                'campaign_id' => $campaign->id ?? null,
                'campaign_type' => $campaign->act ?? 'general'
            ],
            'icon' => '🎁',
            'color' => 'purple',
            'action_url' => url('/'),
            'priority' => 'normal',
            'expires_at' => now()->addDays(30)
        ]);
    }

    public static function createSystemNotification($user, $title, $message, $type = 'system')
    {
        return self::create([
            'user_id' => $user->id,
            'user_type' => $user instanceof \App\Models\Company ? 'company' : 'user',
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => '🔔',
            'color' => 'blue',
            'priority' => 'normal'
        ]);
    }

    private static function getAppointmentTitle($type)
    {
        $titles = [
            'appointment_created' => 'Cuộc hẹn đã được tạo',
            'appointment_confirmed' => 'Cuộc hẹn đã được xác nhận',
            'appointment_completed' => 'Dịch vụ hoàn thành',
            'appointment_cancelled' => 'Cuộc hẹn đã bị hủy',
            'appointment_reminder' => 'Nhắc nhở cuộc hẹn'
        ];

        return $titles[$type] ?? 'Cập nhật cuộc hẹn';
    }

    // Clean up expired notifications
    public static function cleanupExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }
} 