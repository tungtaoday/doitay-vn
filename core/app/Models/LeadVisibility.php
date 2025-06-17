<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadVisibility extends Model
{
    use HasFactory;

    protected $table = 'lead_visibility';

    protected $fillable = [
        'lead_id',
        'company_id',
        'priority_score',
        'notified_at',
        'viewed_at',
        'expires_at',
        'is_purchased'
    ];

    protected $casts = [
        'priority_score' => 'decimal:2',
        'notified_at' => 'datetime',
        'viewed_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_purchased' => 'boolean'
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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now())
                    ->where('is_purchased', false);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeHighPriority($query)
    {
        return $query->orderByDesc('priority_score');
    }

    // Methods
    public function isActive()
    {
        return $this->expires_at && $this->expires_at->isFuture() && !$this->is_purchased;
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function markAsViewed()
    {
        $this->update(['viewed_at' => now()]);
    }

    public function markAsPurchased()
    {
        $this->update(['is_purchased' => true]);
    }

    public function getTimeRemaining()
    {
        if (!$this->expires_at || $this->isExpired()) {
            return null;
        }

        return $this->expires_at->diffForHumans();
    }

    public function getPriorityBadge()
    {
        if ($this->priority_score >= 4.5) {
            return '<span class="badge bg-success">Ưu tiên cao</span>';
        } elseif ($this->priority_score >= 4.0) {
            return '<span class="badge bg-primary">Ưu tiên</span>';
        } else {
            return '<span class="badge bg-secondary">Thường</span>';
        }
    }
}
