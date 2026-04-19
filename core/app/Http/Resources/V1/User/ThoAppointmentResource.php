<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThoAppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $unlocked = (bool) $this->customer_info_unlocked;

        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company?->id,
                'name' => $this->company?->name,
            ],
            'customer' => $unlocked ? [
                'name' => $this->recipient_name,
                'phone' => $this->recipient_phone,
                'address' => $this->recipient_address,
            ] : [
                'name' => mb_substr($this->recipient_name, 0, 1) . '***',
                'phone' => '***',
                'address' => '***',
            ],
            'customer_info_unlocked' => $unlocked,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            'notes' => $this->notes,
            'status' => $this->status,
            'status_label' => AppointmentResource::STATUS_LABELS[$this->status] ?? $this->status,
            'can_confirm' => $this->status === 'pending',
            'can_complete' => $this->status === 'confirmed',
            'can_cancel' => $this->status === 'pending',
            'confirm_fee' => $this->status === 'pending' ? 50000 : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
