<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public const STATUS_LABELS = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'completed' => 'Hoàn thành',
        'canceled' => 'Đã huỷ',
    ];

    public function toArray(Request $request): array
    {
        // SĐT thợ chỉ lộ cho khách SAU KHI thợ đã xác nhận (hoặc đã hoàn thành) lịch —
        // trước đó ẩn để bảo vệ mô hình lead. Đây là chiều khách↔thợ; ẩn trên hồ sơ công khai.
        $revealPhone = in_array($this->status, ['confirmed', 'completed'], true);
        $companyPhone = $this->company?->phone;

        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company?->id,
                'name' => $this->company?->name,
                'image' => $this->company?->image,
                'phone' => $revealPhone ? $companyPhone : null,
                'contact_unlocked' => $revealPhone,
            ],
            'recipient_name' => $this->recipient_name,
            'recipient_phone' => $this->recipient_phone,
            'recipient_address' => $this->recipient_address,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            'notes' => $this->notes,
            'status' => $this->status,
            'status_label' => self::STATUS_LABELS[$this->status] ?? $this->status,
            'can_cancel' => $this->status === 'pending',
            'can_review' => $this->status === 'completed' && !$this->relationLoaded('rating'),
            'has_rating' => $this->whenLoaded('rating', fn () => $this->rating !== null, false),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
