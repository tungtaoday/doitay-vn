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
        'selected_company_id',
        'customer_info',
        'requirements',
        'attachments',
        'is_premium',
        'expires_at',
        'completed_at'
    ];

    protected $casts = [
        'address' => 'array',
        'customer_info' => 'array',
        'requirements' => 'array',
        'attachments' => 'array',
        'needed_by' => 'datetime',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function selectedCompany()
    {
        return $this->belongsTo(Company::class, 'selected_company_id');
    }

    // NEW RELATIONSHIPS FOR CONTRACTOR SELF-REPORT FLOW
    public function reportedPurchases()
    {
        return $this->hasMany(LeadPurchase::class)->where('contractor_reported', true);
    }

    public function confirmedPurchases()
    {
        return $this->hasMany(LeadPurchase::class)->where('customer_confirmed', true);
    }

    public function pendingConfirmationPurchases()
    {
        return $this->hasMany(LeadPurchase::class)
            ->where('contractor_reported', true)
            ->where('customer_confirmed', false);
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

    public function getTimeline()
    {
        $timeline = [];

        // Lead created
        $timeline[] = [
            'title' => 'Lead được tạo',
            'description' => "Lead được tạo bởi {$this->customer->firstname} {$this->customer->lastname}",
            'timestamp' => $this->created_at,
            'icon' => 'las la-plus-circle',
            'color' => 'primary'
        ];

        // Notifications sent
        foreach ($this->visibilities as $visibility) {
            $timeline[] = [
                'title' => 'Gửi thông báo',
                'description' => "Thông báo gửi đến {$visibility->company->name} (Điểm ưu tiên: {$visibility->priority_score})",
                'timestamp' => $visibility->notified_at,
                'icon' => 'las la-bell',
                'color' => 'info'
            ];
        }

        // Lead purchases
        foreach ($this->purchases as $purchase) {
            $timeline[] = [
                'title' => 'Lead được mua',
                'description' => "{$purchase->company->name} đã mua lead với giá " . number_format($purchase->price_paid) . '₫',
                'timestamp' => $purchase->created_at,
                'icon' => 'las la-shopping-cart',
                'color' => 'success'
            ];

            if ($purchase->contacted_at) {
                $timeline[] = [
                    'title' => 'Đã liên hệ khách hàng',
                    'description' => "{$purchase->company->name} đã liên hệ với khách hàng",
                    'timestamp' => $purchase->contacted_at,
                    'icon' => 'las la-phone',
                    'color' => 'warning'
                ];
            }

            if ($purchase->quoted_at) {
                $timeline[] = [
                    'title' => 'Báo giá',
                    'description' => "{$purchase->company->name} đã báo giá " . number_format($purchase->quote_amount) . '₫',
                    'timestamp' => $purchase->quoted_at,
                    'icon' => 'las la-dollar-sign',
                    'color' => 'info'
                ];
            }

            if ($purchase->outcome === 'won') {
                $timeline[] = [
                    'title' => 'Thắng thầu',
                    'description' => "{$purchase->company->name} đã được chọn bởi khách hàng",
                    'timestamp' => $purchase->updated_at,
                    'icon' => 'las la-trophy',
                    'color' => 'warning'
                ];
            } elseif ($purchase->outcome === 'lost') {
                $timeline[] = [
                    'title' => 'Thua thầu',
                    'description' => "{$purchase->company->name} không được chọn",
                    'timestamp' => $purchase->updated_at,
                    'icon' => 'las la-times-circle',
                    'color' => 'danger'
                ];
            }
        }

        // Status changes
        if ($this->status === 'closed') {
            $timeline[] = [
                'title' => 'Lead đã đóng',
                'description' => 'Lead đã được đóng và không còn nhận thêm contractor',
                'timestamp' => $this->updated_at,
                'icon' => 'las la-lock',
                'color' => 'secondary'
            ];
        } elseif ($this->status === 'expired') {
            $timeline[] = [
                'title' => 'Lead hết hạn',
                'description' => 'Lead đã hết hạn và không còn hiệu lực',
                'timestamp' => $this->expires_at ?? $this->updated_at,
                'icon' => 'las la-hourglass-end',
                'color' => 'danger'
            ];
        }

        // Sort by timestamp descending (newest first)
        return collect($timeline)->sortByDesc('timestamp')->values()->all();
    }

    // NEW METHODS FOR CONTRACTOR SELF-REPORT FLOW
    
    /**
     * Complete lead with selected contractor
     */
    public function completeWithContractor($companyId, $notes = null)
    {
        $this->update([
            'status' => 'completed',
            'selected_company_id' => $companyId,
            'completed_at' => now()
        ]);

        // Mark other contractors as lost
        $this->purchases()
            ->where('company_id', '!=', $companyId)
            ->update(['outcome' => 'lost']);
    }

    /**
     * Get contractors who reported being selected
     */
    public function getReportedContractors()
    {
        return $this->reportedPurchases()->with('company')->get();
    }

    /**
     * Check if lead has any pending confirmations
     */
    public function hasPendingConfirmations()
    {
        return $this->pendingConfirmationPurchases()->exists();
    }

    /**
     * Check if lead is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed' && $this->selected_company_id;
    }

    /**
     * Get new status badge for contractor self-report flow
     */
    public function getNewStatusBadge()
    {
        if ($this->isCompleted()) {
            return '<span class="badge badge--success">✅ Hoàn thành</span>';
        } elseif ($this->hasPendingConfirmations()) {
            return '<span class="badge badge--warning">⏳ Chờ xác nhận</span>';
        } elseif ($this->purchases()->exists()) {
            return '<span class="badge badge--info">👥 Có thợ quan tâm</span>';
        } else {
            return '<span class="badge badge--primary">🆕 Đang mở</span>';
        }
    }
}
