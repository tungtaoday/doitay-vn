<?php

namespace App\Http\Controllers\API\V1\Sale;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Sale\CreateSubmissionRequest;
use App\Http\Resources\V1\Sale\SubmissionResource;
use App\Models\ThoSubmission;
use App\Services\SubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API V1 — Sale namespace. CTV nhập & xem hồ sơ thợ (Phase 1).
 * Ref: DUC-SUBMISSION-CREATE, DUC-SUBMISSION-LIST.
 */
class SubmissionController extends Controller
{
    public function __construct(private readonly SubmissionService $service)
    {
    }

    public function store(CreateSubmissionRequest $request): JsonResponse
    {
        $submission = $this->service->create(
            $request->user(),
            $request->validated(),
            $request->file('images', []),
        );

        return (new SubmissionResource($submission))
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = max(1, min(50, (int) $request->integer('per_page', 20)));

        $query = ThoSubmission::where('ctv_id', $request->user()->id)
            ->withCount('images')
            ->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $page = $query->paginate($perPage);

        return response()->json([
            'data' => SubmissionResource::collection($page->items()),
            'meta' => [
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
            ],
        ]);
    }
}
