<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use App\Traits\UserNotify;

class Company extends Model
{
    use UserNotify, Notifiable;

    protected $casts = [];

    protected function tags(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    return is_array($decoded) ? $decoded : [];
                }
                return is_array($value) ? $value : [];
            },
            set: function ($value) {
                if (is_array($value)) {
                    return json_encode($value);
                }
                return $value;
            }
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::APPROVED);
    }

    public function scopeVerified($query) 
    {
        return $query->where('status', Status::APPROVED);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::APPROVED);
    }
    
    public function scopePending($query)
    {
        return $query->where('status', Status::PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::REJECTED);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
    

    public function badgeData()
    {
        $html = '';

        if($this->status == Status::APPROVED){
            $html = '<span class="badge badge--success">'.trans("Approved").'</span>';
        }
        elseif($this->status == Status::PENDING){
            $html = '<span class="badge badge--warning">'.trans("Pending").'</span>';
        }
        elseif($this->status == Status::REJECTED){
            $html = '<span class="badge badge--danger">'.trans("Rejected").'</span>';
        }
        return $html;
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            get:fn () => $this->badgeData(),
        );
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class, 'company_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'company_id');
    }

    public function statistics()
    {
        return $this->hasOne(CompanyStatistics::class);
    }

    public function wallet()
    {
        return $this->hasOne(CompanyWallet::class);
    }

    public function leadPurchases()
    {
        return $this->hasMany(LeadPurchase::class);
    }

    public function leadVisibilities()
    {
        return $this->hasMany(LeadVisibility::class);
    }

    // Methods
    public function hasActiveWallet()
    {
        return $this->wallet && $this->wallet->balance > 0;
    }

    public function getAverageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function getTotalReviews()
    {
        return $this->ratings()->count();
    }

    public function getSmartScore()
    {
        $avgRating = $this->getAverageRating();
        $reviewCount = $this->getTotalReviews();
        
        // Weighted score: rating + review count bonus (max 0.5 bonus)
        return $avgRating + min($reviewCount * 0.1, 0.5);
    }
}
