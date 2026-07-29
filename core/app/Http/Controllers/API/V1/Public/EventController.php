<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Public\CreateEventRequest;
use App\Models\ProductEvent;
use Illuminate\Http\JsonResponse;

/**
 * API V1 — Public. Ghi product event từ Mini App (thợ) + web (khách) để đo phễu
 * Bắc Đẩu. Fire-and-forget: luôn trả 202, không bao giờ chặn client.
 */
class EventController extends Controller
{
    public function store(CreateEventRequest $request): JsonResponse
    {
        ProductEvent::log($request->validated());

        return response()->json(['data' => ['ok' => true]], 202);
    }
}
