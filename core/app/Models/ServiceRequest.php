<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'city',
        'district',
        'ward',
        'address',
        'budget_min',
        'budget_max',
        'preferred_date',
        'preferred_time_slot',
        'contact_name',
        'contact_phone',
        'images',
        'status',
        'selected_company_id',
        'selected_appointment_id',
        'expires_at',
        'closed_at',
    ];

    protected $casts = [
        'images' => 'array',
        'preferred_date' => 'date',
        'expires_at' => 'datetime',
        'closed_at' => 'datetime',
        'budget_min' => 'integer',
        'budget_max' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function selectedCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'selected_company_id');
    }

    public function selectedAppointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'selected_appointment_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isEditable(): bool
    {
        return $this->status === 'open' && (! $this->expires_at || $this->expires_at->isFuture());
    }
}
