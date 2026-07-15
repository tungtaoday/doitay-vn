<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\RejectSubmissionRequest;
use App\Http\Resources\V1\Sale\SubmissionResource;
use App\Models\ThoSubmission;
use App\Services\SubmissionReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API V1 — Admin. Quản lý duyệt/từ chối hồ sơ thợ (Phase 2).
 * Ref: DUC-SUBMISSION-APPROVE, DUC-SUBMISSION-REJECT.
 */
class SubmissionReviewController extends Controller
{
    public function __construct(private readonly SubmissionReviewService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $status  = $request->query('status', 'pending');
        $perPage = max(1, min(50, (int) $request->integer('per_page', 20)));

        $page = ThoSubmission::where('status', $status)
            ->withCount('images')
            ->latest()
            ->paginate($perPage);

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

    public function approve(int $id): JsonResponse
    {
        $submission = ThoSubmission::findOrFail($id);
        $updated = $this->service->approve($submission);

        return (new SubmissionResource($updated->loadCount('images')))->response();
    }

    public function reject(RejectSubmissionRequest $request, int $id): JsonResponse
    {
        $submission = ThoSubmission::findOrFail($id);
        $updated = $this->service->reject($submission, $request->validated()['ly_do']);

        return (new SubmissionResource($updated->loadCount('images')))->response();
    }
}
