<?php

namespace App\Http\Controllers\API\V1\Public;

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
