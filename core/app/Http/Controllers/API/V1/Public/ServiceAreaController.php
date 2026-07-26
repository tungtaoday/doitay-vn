<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * API V1 — Public. Liệt kê các cặp NGHỀ × KHU VỰC có đủ cung thợ thật.
 * Nguồn sự thật cho SEO: generateStaticParams, sitemap, noindex-gate, hub.
 * Chỉ trả cặp có >= MIN thợ (chống thin content). Cache 1h.
 */
class ServiceAreaController extends Controller
{
    public function index(): JsonResponse
    {
        $min = (int) config('marketplace.seo_min_tho', 3);

        $rows = Cache::remember("public.service-areas.min{$min}", 3600, function () use ($min) {
            return DB::table('companies as c')
                ->join('categories as cat', 'cat.id', '=', 'c.category_id')
                ->where('c.status', Status::APPROVED)
                ->whereNotNull('c.district')
                ->where('c.district', '!=', '')
                ->groupBy('c.category_id', 'cat.name', 'c.city', 'c.district')
                ->havingRaw('COUNT(*) >= ?', [$min])
                ->orderByDesc(DB::raw('COUNT(*)'))
                ->get([
                    'c.category_id',
                    'cat.name as category_name',
                    'c.city',
                    'c.district',
                    DB::raw('COUNT(*) as count'),
                ]);
        });

        return response()->json([
            'data' => $rows->map(fn ($r) => [
                'category_id'   => (int) $r->category_id,
                'category_name' => $r->category_name,
                'city'          => $r->city,
                'district'      => $r->district,
                'count'         => (int) $r->count,
            ])->all(),
        ]);
    }
}
