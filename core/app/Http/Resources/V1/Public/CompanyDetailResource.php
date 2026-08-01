<?php

namespace App\Http\Resources\V1\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * Public detail shape for a single Company.
 *
 * Conditional contact fields: only included when the company opted-in
 * to public contact (column `show_contact` if present, otherwise default false).
 */
class CompanyDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // P1.1: che SĐT/email thợ trên trang public để khách đi qua luồng đặt lịch
        // (bảo vệ phí lead). Bật lại bằng env SHOW_CONTACT_PUBLIC=true nếu đổi chiến lược.
        $showContact = (bool) config('marketplace.show_contact', false);

        return [
            'id'              => $this->id,
            'vanity_slug'     => Str::slug((string) $this->name),
            'name'            => $this->name,
            'description'     => $this->description,
            'image'           => $this->image
                ? (str_starts_with($this->image, 'http') ? $this->image : asset('assets/images/company/' . $this->image))
                : null,
            'category'        => $this->whenLoaded('category', fn () => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
            ]),
            'location'        => [
                'district' => $this->district,
                'ward'     => $this->ward ?? null,
                'state'    => $this->state ?? null,
                'country'  => $this->country ?? 'Vietnam',
            ],
            'tags'            => $this->tags ?? [],
            'services'        => $this->services ?? [],
            'business_hours'  => $this->business_hours ?? null,
            'experience'      => (int) ($this->experience ?? 0),
            'rating_avg'      => (float) ($this->avg_rating ?? 0),
            'rating_count'    => (int) ($this->rating_count ?? $this->ratings()->count()),
            'portfolios'      => $this->whenLoaded('portfolios', fn () => $this->portfolios->map(fn ($p) => [
                'id'    => $p->id,
                'title' => $p->title ?? null,
                'image' => $p->image ? asset('assets/images/portfolio/' . $p->image) : null,
            ])),
            'ratings_recent'  => $this->whenLoaded('ratings', fn () => $this->ratings->take(5)->map(fn ($r) => [
                'id'         => $r->id,
                'score'      => (float) ($r->avg_rating ?? 0),
                'comment'    => $r->suggest ?? null,
                'created_at' => optional($r->created_at)->toIso8601String(),
                'user'       => $r->relationLoaded('user') && $r->user ? [
                    'name'   => $r->user->name ?? null,
                    'avatar' => $r->user->image ? asset('assets/images/user/profile/' . $r->user->image) : null,
                ] : null,
                'features'   => $r->relationLoaded('ratingDetails')
                    ? $r->ratingDetails->filter(fn ($d) => $d->feature)->map(fn ($d) => [
                        'name'   => $d->feature->name,
                        'rating' => (float) $d->rating,
                    ])->values()->all()
                    : [],
            ])),
            'show_contact'    => $showContact,
            'phone'           => $showContact ? $this->phone : null,
            'email'           => $showContact ? $this->email : null,
            'website'         => $this->url ?? null,
            'created_at'      => optional($this->created_at)->toIso8601String(),
            // Hồ sơ seed không được index (frontend gắn meta robots noindex).
            'indexable'       => ! (bool) ($this->is_seeded ?? false),
        ];
    }
}
