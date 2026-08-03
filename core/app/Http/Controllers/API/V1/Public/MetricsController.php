<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\ProductEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API V1 — Public (guard bằng token). Trả phễu BẮC ĐẨU:
 *   thợ publish → thợ share → khách xem → khách liên hệ.
 * Bảo vệ bằng config('metrics.token') (đặt METRICS_TOKEN trong .env). Token
 * đọc qua config để an toàn với config:cache. Không có token cấu hình → 403.
 */
class MetricsController extends Controller
{
    public function bacDau(Request $request): JsonResponse
    {
        $configured = (string) config('metrics.token', '');
        $given = (string) ($request->query('token') ?? $request->bearerToken() ?? '');
        if ($configured === '' || ! hash_equals($configured, $given)) {
            abort(403, 'Metrics token không hợp lệ.');
        }

        $days  = max(1, min(365, (int) $request->query('days', 30)));
        $since = now()->subDays($days);

        // Đếm distinct company theo từng event trong cửa sổ thời gian.
        $distinctCompanies = function (string $event) use ($since): int {
            return (int) ProductEvent::query()
                ->where('event', $event)
                ->whereNotNull('company_id')
                ->where('created_at', '>=', $since)
                ->distinct()
                ->count('company_id');
        };
        $countEvent = function (string $event) use ($since): int {
            return (int) ProductEvent::query()
                ->where('event', $event)
                ->where('created_at', '>=', $since)
                ->count();
        };

        $published = $distinctCompanies('profile_published');
        $shared    = $distinctCompanies('profile_shared');
        $viewed    = $distinctCompanies('profile_viewed');
        $contacted = $distinctCompanies('contact_clicked');

        // Hồ sơ ĐÃ SHARE mà có ít nhất 1 khách liên hệ (real lead) — lõi Bắc Đẩu.
        $sharedWithContact = (int) DB::table('product_events as s')
            ->join('product_events as c', 's.company_id', '=', 'c.company_id')
            ->where('s.event', 'profile_shared')->where('s.created_at', '>=', $since)
            ->where('c.event', 'contact_clicked')->where('c.created_at', '>=', $since)
            ->distinct()
            ->count('s.company_id');

        $rate = fn (int $num, int $den): float => $den > 0 ? round($num / $den, 4) : 0.0;

        return response()->json([
            'data' => [
                'window_days' => $days,
                // ── Phễu chính ──
                'funnel' => [
                    'tho_published'  => $published,   // thợ đưa hồ sơ lên chợ
                    'tho_shared'     => $shared,      // thợ chia sẻ cho khách
                    'profile_viewed' => $viewed,      // hồ sơ được khách xem
                    'khach_contacted'=> $contacted,   // hồ sơ có khách liên hệ
                ],
                // ── BẮC ĐẨU ──
                'bac_dau' => [
                    'shared_profiles'              => $shared,
                    'shared_profiles_with_contact' => $sharedWithContact,
                    'real_lead_rate'               => $rate($sharedWithContact, $shared), // khách thật / thợ share
                    'share_rate'                   => $rate($shared, $published),         // thợ share / thợ publish
                    'total_contact_clicks'         => $countEvent('contact_clicked'),
                    'total_profile_views'          => $countEvent('profile_viewed'),
                ],
                // ── TÁCH KÊNH: khách đến từ đâu (meta.src do web gắn; miniapp = thẻ ThợTốt) ──
                // src: thotot_card | thotot_app | seo | facebook | zalo | tiktok | ctv | truc_tiep | khac
                'sources' => (function () use ($since) {
                    $rows = DB::table('product_events')
                        ->selectRaw(
                            "COALESCE(CASE WHEN channel = 'miniapp' THEN 'thotot_app' "
                            . "ELSE JSON_UNQUOTE(JSON_EXTRACT(meta, '$.src')) END, 'khac') as src, "
                            . 'event, count(*) as n'
                        )
                        ->whereIn('event', ['profile_viewed', 'contact_clicked', 'booking_confirmed'])
                        ->where('created_at', '>=', $since)
                        ->groupBy('src', 'event')
                        ->get();
                    $out = [];
                    foreach ($rows as $r) {
                        $s = $r->src ?: 'khac';
                        $out[$s] ??= ['viewed' => 0, 'contacted' => 0, 'booked' => 0];
                        if ($r->event === 'profile_viewed') $out[$s]['viewed'] = (int) $r->n;
                        if ($r->event === 'contact_clicked') $out[$s]['contacted'] = (int) $r->n;
                        if ($r->event === 'booking_confirmed') $out[$s]['booked'] = (int) $r->n;
                    }
                    return $out;
                })(),
                // ── Hồ sơ thợ MỚI trong kỳ, gồm cả CHỜ DUYỆT ──
                // API công khai /public/companies chỉ trả hồ sơ đã duyệt, nên bot
                // nhắc việc không thấy thợ vừa tạo. Khối này để chủ hệ (có token)
                // biết ngay có ai vào, và ai đang kẹt ở hàng chờ duyệt.
                'recent_tho' => DB::table('companies')
                    ->leftJoin('categories', 'categories.id', '=', 'companies.category_id')
                    ->where('companies.created_at', '>=', $since)
                    ->where(function ($q) {
                        $q->whereNull('companies.is_seeded')->orWhere('companies.is_seeded', 0);
                    })
                    ->orderByDesc('companies.id')
                    ->limit(20)
                    ->get([
                        'companies.id',
                        'companies.name',
                        'companies.district',
                        'companies.status',
                        'companies.zalo_id',
                        'companies.created_at',
                        'categories.name as nghe',
                    ])
                    ->map(fn ($c) => [
                        'id'         => (int) $c->id,
                        'name'       => $c->name,
                        'nghe'       => $c->nghe,
                        'district'   => $c->district ?: null,
                        // live = đã duyệt, hiện trên chợ · pending = còn ở hàng chờ
                        'status'     => (int) $c->status === Status::APPROVED ? 'live' : 'pending',
                        // true = thợ đã bấm link nhận hồ sơ (hoặc tự tạo trong app)
                        'claimed'    => (bool) $c->zalo_id,
                        'created_at' => (string) $c->created_at,
                    ])
                    ->all(),
                // ── Đếm thô mọi event (debug/dashboard) ──
                'events' => ProductEvent::query()
                    ->where('created_at', '>=', $since)
                    ->select('event', DB::raw('count(*) as n'))
                    ->groupBy('event')
                    ->pluck('n', 'event'),
            ],
        ]);
    }
}
