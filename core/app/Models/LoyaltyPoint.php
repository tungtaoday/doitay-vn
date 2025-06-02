<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'transaction_type',
        'source',
        'description',
        'related_id',
        'related_type',
        'amount',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeEarned($query)
    {
        return $query->where('transaction_type', 'earned');
    }

    public function scopeRedeemed($query)
    {
        return $query->where('transaction_type', 'redeemed');
    }

    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now());
    }

    // Static methods for earning points
    public static function earnForAppointmentCompleted($userId, $appointmentId)
    {
        return self::create([
            'user_id' => $userId,
            'points' => 100, // 100 points for completed appointment
            'transaction_type' => 'earned',
            'source' => 'appointment_completed',
            'description' => 'Điểm thưởng hoàn thành lịch hẹn',
            'related_id' => $appointmentId,
            'related_type' => 'App\Models\Appointment',
            'amount' => 10000, // 100 points = 10,000 VND value
            'expires_at' => now()->addYear()
        ]);
    }

    public static function earnForReview($userId, $reviewId)
    {
        return self::create([
            'user_id' => $userId,
            'points' => 50, // 50 points for writing review
            'transaction_type' => 'earned',
            'source' => 'review_written',
            'description' => 'Điểm thưởng viết đánh giá',
            'related_id' => $reviewId,
            'related_type' => 'App\Models\Rating',
            'amount' => 5000, // 50 points = 5,000 VND value
            'expires_at' => now()->addYear()
        ]);
    }

    public static function earnForReferral($userId, $referralUserId)
    {
        return self::create([
            'user_id' => $userId,
            'points' => 200, // 200 points for successful referral
            'transaction_type' => 'earned',
            'source' => 'referral_signup',
            'description' => 'Điểm thưởng giới thiệu thành công',
            'related_id' => $referralUserId,
            'related_type' => 'App\Models\User',
            'amount' => 20000, // 200 points = 20,000 VND value
            'expires_at' => now()->addYear()
        ]);
    }
}
