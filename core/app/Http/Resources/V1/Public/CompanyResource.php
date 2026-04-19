<?php

namespace App\Http\Resources\V1\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * Public listing shape for Company. Used in /api/v1/public/companies index.
 *
 * NEVER expose admin_feedback, owner email, internal kyc data, balances.
 */
class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            // Live `companies` table has no slug column — derive a vanity
            // segment from name so the frontend can build SEO URLs of the
            // form /cong-ty/{id}/{vanity-slug} (matches legacy parity).
            'vanity_slug'       => Str::slug((string) $this->name),
            'name'              => $this->name,
            'image'             => $this->image
                ? asset('assets/images/company/' . $this->image)
                : asset('assets/images/placeholders/worker-' . (($this->id % 4) + 1) . '.jpg'),
            'short_description' => $this->description
                ? mb_substr(strip_tags($this->description), 0, 160)
                : null,
            'category'          => $this->whenLoaded('category', fn () => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),
            'location'          => [
                'district' => $this->district,
                'city'     => $this->city ?? null,
                'state'    => $this->state ?? null,
            ],
            'rating_avg'        => (float) ($this->avg_rating ?? 0),
            'rating_count'      => (int) ($this->rating_count ?? $this->ratings()->count()),
            'experience'        => (int) ($this->experience ?? 0),
        ];
    }
}
