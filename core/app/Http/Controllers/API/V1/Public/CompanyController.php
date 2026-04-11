<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Public\ListCompaniesRequest;
use App\Http\Resources\V1\Public\CompanyDetailResource;
use App\Http\Resources\V1\Public\CompanyResource;
use App\Services\CompanySearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Public/CompanyController for /api/v1/public/companies/*.
 *
 * Implements DUC-COMPANY-LIST-PUBLIC and DUC-COMPANY-SHOW-PUBLIC.
 * Stays thin — all query logic lives in CompanySearchService so the
 * existing Blade controllers can also call it without duplication.
 */
class CompanyController extends Controller
{
    public function __construct(private readonly CompanySearchService $search)
    {
    }

    public function index(ListCompaniesRequest $request): AnonymousResourceCollection
    {
        $paginator = $this->search->listPublic(
            filters: $request->validated(),
            perPage: (int) $request->integer('per_page', 20),
        );

        return CompanyResource::collection($paginator)
            ->additional(['meta' => ['version' => 'v1']]);
    }

    public function show(int $id): JsonResponse
    {
        $company = $this->search->showPublicById($id); // throws ModelNotFoundException → 404

        return (new CompanyDetailResource($company))
            ->response()
            ->setStatusCode(200);
    }
}
