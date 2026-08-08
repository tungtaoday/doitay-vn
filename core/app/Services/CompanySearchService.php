<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * CompanySearchService — single source of truth for the public Company
 * read surface.
 *
 * Both the new Api/V1/Public/CompanyController and the existing Blade
 * controllers (web.php → CompanyController/SiteController) should call
 * this service instead of querying Eloquent directly. Without this
 * extraction the two frontends will silently drift in business rules.
 */
class CompanySearchService
{
    /**
     * @param  array{q?: ?string, category?: ?int, district?: ?string, min_rating?: ?float, sort?: ?string}  $filters
     */
    public function listPublic(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $perPage = max(1, min($perPage, 50));

        $query = Company::query()
            ->approved()
            ->with(['category']);

        if (! empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (! empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }

        if (! empty($filters['min_rating'])) {
            $query->where('avg_rating', '>=', (float) $filters['min_rating']);
        }

        if (! empty($filters['q'])) {
            $tuKhoa = trim((string) $filters['q']);
            $term = '%' . $tuKhoa . '%';

            // Khách gõ NGHỀ ("điện", "điều hòa") chứ không gõ tên thợ — ô tìm ở
            // trang chủ hỏi thẳng "Bạn cần thợ gì hôm nay?". Chỉ khớp name +
            // description thì "điện" trả về 0 kết quả dù có cả một danh mục
            // Thợ Điện. Tìm thêm ở tên nghề và địa bàn.
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhere('city', 'like', $term)
                  ->orWhere('district', 'like', $term)
                  ->orWhereHas('category', fn ($k) => $k->where('name', 'like', $term));
            });
        }

        $query->when(\Illuminate\Support\Facades\Schema::hasColumn('companies', 'is_seeded'),
            fn ($q) => $q->orderBy('is_seeded'));

        match ($filters['sort'] ?? 'newest') {
            'rating'  => $query->orderByDesc('avg_rating')->orderByDesc('id'),
            'newest'  => $query->orderByDesc('id'),
            default   => $query->orderByDesc('id'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Live `companies` table has no slug column — legacy URL is
     * /companies/{id}/{vanity-slug}, with the slug derived from `name`
     * client-side. The API resolves by id; the frontend appends a vanity
     * segment for SEO parity.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function showPublicById(int $id): Company
    {
        return Company::approved()
            ->with(['category', 'portfolios', 'ratings.user', 'ratings.ratingDetails.feature'])
            ->where('id', $id)
            ->firstOrFail();
    }
}
