<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LeadPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'company_id',
        'user_id',
        'price_paid',
        'status',
        'notes',
        'contacted_at',
        'quoted_at',
        'quote_amount',
        'outcome',
        'outcome_notes'
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'quote_amount' => 'decimal:2',
        'contacted_at' => 'datetime',
        'quoted_at' => 'datetime'
    ];

    // Relationships
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeContacted($query)
    {
        return $query->whereNotNull('contacted_at');
    }

    public function scopeQuoted($query)
    {
        return $query->whereNotNull('quoted_at');
    }

    public function scopeWon($query)
    {
        return $query->where('outcome', 'won');
    }

    public function scopeLost($query)
    {
        return $query->where('outcome', 'lost');
    }

    // Methods
    public function markAsContacted($notes = null)
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
            'notes' => $notes
        ]);
    }

    public function markAsQuoted($amount, $notes = null)
    {
        $this->update([
            'status' => 'quoted',
            'quoted_at' => now(),
            'quote_amount' => $amount,
            'notes' => $notes
        ]);
    }

    public function markAsWon($notes = null)
    {
        $this->update([
            'outcome' => 'won',
            'outcome_notes' => $notes
        ]);
    }

    public function markAsLost($notes = null)
    {
        $this->update([
            'outcome' => 'lost',
            'outcome_notes' => $notes
        ]);
    }

    public function getStatusBadge()
    {
        $badges = [
            'active' => '<span class="badge badge--info">Mới mua</span>',
            'contacted' => '<span class="badge badge--warning">Đã liên hệ</span>',
            'quoted' => '<span class="badge badge--primary">Đã báo giá</span>',
            'won' => '<span class="badge badge--success">Thành công</span>',
            'lost' => '<span class="badge badge--danger">Thất bại</span>'
        ];

        return $badges[$this->status] ?? $badges['active'];
    }

    public function getOutcomeBadge()
    {
        $badges = [
            'pending' => '<span class="badge badge--secondary">Chờ kết quả</span>',
            'won' => '<span class="badge badge--success">Trúng thầu</span>',
            'lost' => '<span class="badge badge--danger">Trượt thầu</span>',
            'no_response' => '<span class="badge badge--warning">Không phản hồi</span>'
        ];

        return $badges[$this->outcome] ?? $badges['pending'];
    }

    public function getDaysOld()
    {
        return $this->created_at->diffInDays(now());
    }

    public function getDaysSinceContact()
    {
        if (!$this->contacted_at) {
            return null;
        }

        return $this->contacted_at->diffInDays(now());
    }

    public function isStale()
    {
        // Lead cũ hơn 7 ngày và chưa liên hệ
        return $this->getDaysOld() > 7 && !$this->contacted_at;
    }

    public function getConversionRate()
    {
        // Tính tỷ lệ chuyển đổi dựa trên trạng thái
        if ($this->outcome === 'won') {
            return 100;
        } elseif ($this->outcome === 'lost') {
            return 0;
        } elseif ($this->quoted_at) {
            return 50; // Đã báo giá nhưng chưa có kết quả
        } elseif ($this->contacted_at) {
            return 25; // Đã liên hệ nhưng chưa báo giá
        }
        return 10; // Mới mua, chưa liên hệ
    }
}
