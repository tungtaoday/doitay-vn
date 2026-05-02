<?php

namespace App\Http\Resources\V1\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'name' => $this->user?->name,
            ],
            'avg_rating' => (float) $this->avg_rating,
            'comment' => $this->suggest,
            'details' => $this->whenLoaded('ratingDetails', fn () =>
                $this->ratingDetails->map(fn ($d) => [
                    'feature' => $d->feature?->name,
                    'rating' => (float) $d->rating,
                ])
            ),
            'appointment_date' => $this->appointment?->appointment_date,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
