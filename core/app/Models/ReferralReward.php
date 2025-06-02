<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referee_id', 
        'reward_type',
        'reward_amount',
        'status',
        'description',
        'conditions',
        'paid_at',
        'expires_at'
    ];

    protected $casts = [
        'conditions' => 'array',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'reward_amount' => 'decimal:2'
    ];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referee()
    {
        return $this->belongsTo(User::class, 'referee_id');
    }

    public function getStatusBadge()
    {
        $badges = [
            'pending' => '<span class="badge badge--warning">Chờ xử lý</span>',
            'paid' => '<span class="badge badge--success">Đã thanh toán</span>',
            'expired' => '<span class="badge badge--danger">Hết hạn</span>',
            'cancelled' => '<span class="badge badge--secondary">Đã hủy</span>'
        ];

        return $badges[$this->status] ?? '';
    }

    public function getFormattedAmount()
    {
        return number_format($this->reward_amount, 0, '.', ',') . ' VNĐ';
    }
}
