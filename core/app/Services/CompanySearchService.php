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
     * @param  array{q?: ?string, category?: ?int, location?: ?int}  $filters
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

        if (! empty($filters['location'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('district', $filters['location'])
                  ->orWhere('city', $filters['location']);
            });
        }

        if (! empty($filters['q'])) {
            $term = '%' . trim((string) $filters['q']) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('description', 'like', $term);
            });
        }

        return $query
            ->orderBy('is_seeded')          // real companies (0) always before seeded (1)
            ->orderByDesc('avg_rating')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
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
