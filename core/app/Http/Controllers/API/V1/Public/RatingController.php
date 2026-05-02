<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Public\FeatureResource;
use App\Http\Resources\V1\Public\RatingResource;
use App\Services\RatingService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RatingController extends Controller
{
    public function __construct(
        protected RatingService $ratingService,
    ) {}

    public function index(int $companyId): AnonymousResourceCollection
    {
        return RatingResource::collection(
            $this->ratingService->listForCompany($companyId),
        );
    }

    public function features(int $categoryId): AnonymousResourceCollection
    {
        return FeatureResource::collection(
            $this->ratingService->featuresForCategory($categoryId),
        );
    }
}
