<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\ProductEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * API V1 — Public (guard bằng token). Trả phễu BẮC ĐẨU:
 *   thợ publish → thợ share → khách xem → khách liên hệ.
 * Bảo vệ bằng config('metrics.token') (đặt METRICS_TOKEN trong .env). Token
 * đọc qua config để an toàn với config:cache. Không có token cấu hình → 403.
 */
class MetricsController extends Controller
{
    /** Chặn cửa: mọi endpoint metrics đều cần token đọc. */
    private function guard(Request $request): void
    {
        $configured = (string) config('metrics.token', '');
        $given = (string) ($request->query('token') ?? $request->bearerToken() ?? '');
        if ($configured === '' || ! hash_equals($configured, $given)) {
            abort(403, 'Metrics token không hợp lệ.');
        }
    }

    /**
     * GET /public/metrics/tho-performance?token=&days=30
     *
     * Bảng HIỆU SUẤT TỪNG THỢ cho người quản lý: ai đang sống, ai có hồ sơ mà
     * chưa share, ai share rồi mà không ai gọi. Đây là thứ Quyển 6 gọi là "đo
     * chuỗi ra tiền" — nhìn theo từng thợ chứ không chỉ tổng.
     */
    public function thoPerformance(Request $request): JsonResponse
    {
        $this->guard($request);

        $days  = max(1, min(365, (int) $request->query('days', 30)));
        $since = now()->subDays($days);

        // Gộp sự kiện theo company trong cửa sổ — 1 truy vấn, không N+1.
        $stats = DB::table('product_events')
            ->selectRaw('company_id, event, count(*) as n, max(created_at) as last_at')
            ->whereNotNull('company_id')
            ->where('created_at', '>=', $since)
            ->groupBy('company_id', 'event')
            ->get()
            ->groupBy('company_id');

        $companies = DB::table('companies')
            ->leftJoin('categories', 'categories.id', '=', 'companies.category_id')
            ->where(function ($q) {
                $q->whereNull('companies.is_seeded')->orWhere('companies.is_seeded', 0);
            })
            ->orderByDesc('companies.id')
            ->limit(500)
            ->get([
                'companies.id', 'companies.name', 'companies.district', 'companies.city',
                'companies.status', 'companies.zalo_id', 'companies.phone',
                'companies.created_at', 'categories.name as nghe',
            ]);

        $rows = [];
        foreach ($companies as $c) {
            $ev = $stats->get($c->id, collect());
            $get = fn (string $e) => (int) ($ev->firstWhere('event', $e)->n ?? 0);
            $viewed    = $get('profile_viewed');
            $contacted = $get('contact_clicked');
            $shared    = $get('profile_shared');
            $lastAt    = $ev->max('last_at');

            $rows[] = [
                'id'          => (int) $c->id,
                'name'        => $c->name,
                'nghe'        => $c->nghe,
                'khu_vuc'     => trim(implode(', ', array_filter([$c->district, $c->city]))) ?: null,
                'phone'       => $c->phone,
                'status'      => (int) $c->status === Status::APPROVED ? 'live' : 'pending',
                'claimed'     => (bool) $c->zalo_id,
                'created_at'  => (string) $c->created_at,
                'shared'      => $shared,
                'viewed'      => $viewed,
                'contacted'   => $contacted,
                // Tỉ lệ khách xem rồi bấm liên hệ — chỉ số chất lượng hồ sơ
                'contact_rate' => $viewed > 0 ? round($contacted * 100 / $viewed, 1) : null,
                'last_at'     => $lastAt ? (string) $lastAt : null,
                // Chẩn đoán sẵn để người xem khỏi tự luận
                'tinh_trang'  => $this->chanDoan((bool) $c->zalo_id, $shared, $viewed, $contacted),
            ];
        }

        $tong = count($rows);
        $daShare = count(array_filter($rows, fn ($r) => $r['shared'] > 0));
        $coKhach = count(array_filter($rows, fn ($r) => $r['contacted'] > 0));

        return response()->json(['data' => [
            'window_days' => $days,
            'tong_quan' => [
                'tong_tho'      => $tong,
                'da_nhan_ho_so' => count(array_filter($rows, fn ($r) => $r['claimed'])),
                'da_share'      => $daShare,
                'co_khach_lien_he' => $coKhach,
                'share_rate'    => $tong > 0 ? round($daShare * 100 / $tong, 1) : 0,
                'real_lead_rate' => $daShare > 0 ? round($coKhach * 100 / $daShare, 1) : 0,
            ],
            'tho' => $rows,
        ]]);
    }

    /** Một câu nói thẳng thợ này đang kẹt ở đâu — dùng cho cả bảng quản lý lẫn bot. */
    private function chanDoan(bool $claimed, int $shared, int $viewed, int $contacted): string
    {
        if (! $claimed)               return 'chua_nhan_ho_so';   // CTV dựng hộ, thợ chưa bấm link
        if ($shared === 0)            return 'chua_share';        // có hồ sơ mà chưa gửi khách
        if ($viewed === 0)            return 'share_chua_ai_xem';
        if ($contacted === 0)         return 'xem_nhung_khong_goi'; // hồ sơ yếu: thiếu ảnh/giá
        return 'dang_song';
    }

    public function bacDau(Request $request): JsonResponse
    {
        $this->guard($request);

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
                // ── SỐ VẬN HÀNH cho máy chấm cược tuần ──
                // Sổ cược (agent-system/quan-tri/thi-nghiem.yaml) cần vài con số
                // ngoài phễu event: thợ thật, CTV, kỷ luật báo cáo. Gom vào đây
                // để script bao-cao-tuan chỉ cần MỘT token và MỘT lượt gọi.
                'van_hanh' => [
                    'so_tho_that' => (int) DB::table('companies')
                        ->where('status', Status::APPROVED)
                        ->where(fn ($q) => $q->whereNull('is_seeded')->orWhere('is_seeded', 0))
                        ->count(),
                    'tho_cho_duyet' => (int) DB::table('companies')
                        ->where('status', Status::PENDING)->count(),
                    'so_ctv' => Schema::hasTable('ctvs')
                        ? (int) DB::table('ctvs')->count() : 0,
                    'so_ctv_hoat_dong' => Schema::hasTable('ctvs')
                        ? (int) DB::table('ctvs')->where('trang_thai', 1)->count() : 0,
                    'ho_so_ctv_nhap_ky' => (int) DB::table('tho_submissions')
                        ->where('created_at', '>=', $since)->count(),
                    'yeu_cau_ky' => (int) DB::table('service_requests')
                        ->where('created_at', '>=', $since)->count(),
                    'bao_cao_viec_ky' => Schema::hasTable('bao_cao_viec')
                        ? (int) DB::table('bao_cao_viec')->where('ngay', '>=', $since->toDateString())->count() : 0,
                    'so_ngay_co_bao_cao' => Schema::hasTable('bao_cao_viec')
                        ? (int) DB::table('bao_cao_viec')->where('ngay', '>=', $since->toDateString())
                            ->distinct()->count('ngay') : 0,
                ],
            ],
        ]);
    }
}
