<?php

namespace App\Http\Resources\V1\Public;

use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * Hồ sơ thợ trả về CHO CHÍNH THỢ (Mini App) để dựng lại toàn bộ màn hình sau khi
 * đổi máy / cài lại app. Khác `CompanyDetailResource` (dành cho khách xem) ở chỗ
 * có kèm SĐT của chính thợ và trạng thái duyệt.
 *
 * KHÔNG trả `claim_token` — vé chỉ đi qua link CTV gửi cho thợ.
 */
class ThoProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $live = (int) $this->status === Status::APPROVED;

        return [
            'company_id'    => $this->id,
            'name'          => $this->name,
            'phone'         => $this->phone,
            'nghe'          => $this->whenLoaded('category', fn () => $this->category->name, null),
            'city'          => $this->city ?: null,
            'district'      => $this->district ?: null,
            'experience'    => (int) ($this->experience ?? 0),
            'description'   => $this->description,
            'tags'          => $this->tags ?? [],
            'services'      => $this->services ?? [],
            'avatar'        => $this->image
                ? (str_starts_with($this->image, 'http') ? $this->image : asset('assets/images/company/' . $this->image))
                : null,
            'images'        => $this->whenLoaded('portfolios', fn () => $this->portfolios
                ->filter(fn ($p) => (bool) $p->image)
                ->map(fn ($p) => [
                    'id'    => $p->id,
                    'title' => $p->title,
                    'url'   => str_starts_with((string) $p->image, 'http')
                        ? $p->image
                        : asset('assets/images/portfolio/' . $p->image),
                ])->values()->all(), []),
            'review_status' => $live ? 'live' : 'pending',
            'is_live'       => $live,
            'claimed'       => (bool) $this->zalo_id,
            'profile_url'   => rtrim((string) config('app.frontend_url', config('app.url')), '/')
                . '/tho/' . $this->id . '/' . Str::slug((string) $this->name),
        ];
    }
}
