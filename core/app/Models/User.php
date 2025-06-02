<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\UserNotify;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, UserNotify, Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','ver_code'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];

    protected $fillable = [
        'username', 'country_name', 'image', 'status','district','ward',
        'address', 'email', 'mobile', 'password', 'referral_code', 'referred_by',
        'referral_count', 'total_referral_earnings'
    ];

    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    // Quan hệ với bảng a_notifications (trạng thái chi tiết)
    public function aNotifications()
    {
        return $this->morphMany(\App\Models\ANotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    public function routeNotificationFor($driver, $notification = null)
    {
        if ($driver === 'mail') {
            return $this->email; // Trả về email cho kênh mail
        }

        if ($driver === 'database') {
            return $this->notifications(); // Trả về mối quan hệ cho kênh database
        }

        return null; // Mặc định nếu không khớp kênh nào
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn () => $this->dial_code . $this->mobile,
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev',Status::VERIFIED)->where('sv',Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    public function VietnamDistrict()
    {
        return $this->belongsTo(VietnamDistrict::class);
    }

    // Referral relationships
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralRewards()
    {
        return $this->hasMany(ReferralReward::class, 'referrer_id');
    }

    public function receivedRewards()
    {
        return $this->hasMany(ReferralReward::class, 'referee_id');
    }

    // Generate unique referral code
    public static function generateReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    // Get referral URL
    public function getReferralUrl()
    {
        return route('user.register') . '?ref=' . $this->referral_code;
    }
}
