<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateServiceRequestRequest;
use App\Http\Resources\V1\User\ServiceRequestResource;
use App\Services\ServiceRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API V1 — Service Request broadcast + matching (Thumbtack-style).
 *
 * Customer tạo request → service chạy matching engine → trả top N thợ rank
 * → customer pick 1 thợ → convert sang appointment qua luồng hiện tại.
 */
class ServiceRequestController extends Controller
{
    public function __construct(private readonly ServiceRequestService $service)
    {
    }

    public function store(CreateServiceRequestRequest $request): JsonResponse
    {
        try {
            $serviceRequest = $this->service->create($request->user(), $request->validated());
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 429);
        }

        $matches = $this->service->matchCompanies($serviceRequest);

        $resource = (new ServiceRequestResource($serviceRequest->load('category')))
            ->withMatches($matches);

        return $resource->response()->setStatusCode(201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $serviceRequest = $this->service->findForUser($request->user(), $id);
        $matches = $this->service->matchCompanies($serviceRequest);

        return (new ServiceRequestResource($serviceRequest))
            ->withMatches($matches)
            ->response();
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min(50, (int) $request->integer('per_page', 20)));

        $page = \App\Models\ServiceRequest::where('user_id', $request->user()->id)
            ->with('category:id,name')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'data' => ServiceRequestResource::collection($page->items()),
            'meta' => [
                'current_page' => $page->currentPage(),
                'last_page' => $page->lastPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
            ],
        ]);
    }
}
