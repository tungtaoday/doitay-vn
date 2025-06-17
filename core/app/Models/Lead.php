<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'category_id',
        'title',
        'description',
        'location',
        'district',
        'ward',
        'address',
        'budget_min',
        'budget_max',
        'lead_price',
        'needed_by',
        'urgency',
        'status',
        'max_contractors',
        'purchased_count',
        'customer_info',
        'requirements',
        'attachments',
        'is_premium',
        'expires_at'
    ];

    protected $casts = [
        'address' => 'array',
        'customer_info' => 'array',
        'requirements' => 'array',
        'attachments' => 'array',
        'needed_by' => 'datetime',
        'expires_at' => 'datetime',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'lead_price' => 'decimal:2',
        'is_premium' => 'boolean'
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchases()
    {
        return $this->hasMany(LeadPurchase::class);
    }

    public function activePurchases()
    {
        return $this->hasMany(LeadPurchase::class)->where('status', '!=', 'lost');
    }

    public function visibilities()
    {
        return $this->hasMany(LeadVisibility::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->whereColumn('purchased_count', '<', 'max_contractors');
    }

    public function scopeByLocation($query, $district = null, $ward = null)
    {
        if ($district) {
            $query->where('district', $district);
        }
        if ($ward) {
            $query->where('ward', $ward);
        }
        return $query;
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBudget($query, $minBudget = null, $maxBudget = null)
    {
        if ($minBudget) {
            $query->where('budget_max', '>=', $minBudget);
        }
        if ($maxBudget) {
            $query->where('budget_min', '<=', $maxBudget);
        }
        return $query;
    }

    // Methods
    public function isAvailable()
    {
        return $this->status === 'active' && 
               $this->purchased_count < $this->max_contractors &&
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function canBePurchasedBy($companyId)
    {
        if (!$this->isAvailable()) {
            return false;
        }

        // Check if company already purchased this lead
        return !$this->purchases()->where('company_id', $companyId)->exists();
    }

    public function getRemainingSlots()
    {
        return $this->max_contractors - $this->purchased_count;
    }

    public function markAsPurchased()
    {
        $this->increment('purchased_count');
        
        if ($this->purchased_count >= $this->max_contractors) {
            $this->update(['status' => 'closed']);
        }
    }

    public function getUrgencyBadge()
    {
        $badges = [
            'low' => '<span class="badge badge--info">Không khẩn</span>',
            'medium' => '<span class="badge badge--warning">Bình thường</span>',
            'high' => '<span class="badge badge--danger">Khẩn cấp</span>'
        ];

        return $badges[$this->urgency] ?? $badges['medium'];
    }

    public function getStatusBadge()
    {
        $badges = [
            'draft' => '<span class="badge badge--secondary">Nháp</span>',
            'active' => '<span class="badge badge--success">Đang mở</span>',
            'closed' => '<span class="badge badge--primary">Đã đóng</span>',
            'expired' => '<span class="badge badge--danger">Hết hạn</span>'
        ];

        return $badges[$this->status] ?? $badges['active'];
    }

    public function getBudgetRange()
    {
        if ($this->budget_min && $this->budget_max) {
            return number_format($this->budget_min) . '₫ - ' . number_format($this->budget_max) . '₫';
        } elseif ($this->budget_min) {
            return 'Từ ' . number_format($this->budget_min) . '₫';
        } elseif ($this->budget_max) {
            return 'Tối đa ' . number_format($this->budget_max) . '₫';
        } else {
            return 'Thỏa thuận';
        }
    }

    public function getTimeLeft()
    {
        if (!$this->expires_at) {
            return null;
        }

        $now = Carbon::now();
        if ($this->expires_at->isPast()) {
            return 'Đã hết hạn';
        }

        return $this->expires_at->diffForHumans($now);
    }
}
