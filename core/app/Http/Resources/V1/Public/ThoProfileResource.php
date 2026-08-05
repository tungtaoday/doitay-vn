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
    /** Đếm sự kiện 30 ngày của chính hồ sơ này. Lỗi thì trả 0, không chặn hồ sơ. */
    private function thongKe(): array
    {
        try {
            $rows = \Illuminate\Support\Facades\DB::table('product_events')
                ->selectRaw('event, count(*) as n')
                ->where('company_id', $this->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('event')
                ->pluck('n', 'event');

            $viewed = (int) ($rows['profile_viewed'] ?? 0);
            $contacted = (int) ($rows['contact_clicked'] ?? 0);

            return [
                'da_gui_the'  => (int) ($rows['profile_shared'] ?? 0),
                'khach_xem'   => $viewed,
                'khach_lien_he' => $contacted,
                'ty_le_lien_he' => $viewed > 0 ? round($contacted * 100 / $viewed) : 0,
            ];
        } catch (\Throwable) {
            return ['da_gui_the' => 0, 'khach_xem' => 0, 'khach_lien_he' => 0, 'ty_le_lien_he' => 0];
        }
    }

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
            // Hiệu suất hồ sơ 30 ngày — để thợ tự thấy thẻ của mình có ai xem không.
            // Đây là thứ khiến thợ chịu gửi thẻ tiếp: nhìn thấy con số nhúc nhích.
            'thong_ke'      => $this->thongKe(),
            'profile_url'   => rtrim((string) config('app.frontend_url', config('app.url')), '/')
                . '/tho/' . $this->id . '/' . Str::slug((string) $this->name),
        ];
    }
}
