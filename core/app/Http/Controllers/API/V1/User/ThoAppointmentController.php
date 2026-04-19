<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\ThoAppointmentResource;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ThoAppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $appointmentService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $appointments = $this->appointmentService->listForCompany(
            $request->user(),
            $request->only(['status']),
            (int) $request->input('per_page', 20),
        );

        $stats = $this->appointmentService->companyStats($request->user());

        return response()->json([
            'data' => ThoAppointmentResource::collection($appointments)->resolve(),
            'stats' => $stats,
            'meta' => [
                'current_page' => $appointments->currentPage(),
                'last_page' => $appointments->lastPage(),
                'per_page' => $appointments->perPage(),
                'total' => $appointments->total(),
            ],
        ]);
    }

    public function show(Request $request, int $id): ThoAppointmentResource
    {
        $appointment = $this->appointmentService->findForCompany($request->user(), $id);

        return new ThoAppointmentResource($appointment);
    }

    public function confirm(Request $request, int $id): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->confirmByCompany($request->user(), $id);

            return response()->json([
                'message' => 'Xác nhận thành công! Thông tin khách hàng đã được mở khoá.',
                'data' => (new ThoAppointmentResource($appointment))->resolve(),
            ]);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => 'Số dư ví không đủ. Vui lòng nạp thêm tiền.'], 422);
        }
    }

    public function complete(Request $request, int $id): JsonResponse
    {
        try {
            $this->appointmentService->completeByCompany($request->user(), $id);

            return response()->json(['message' => 'Đã đánh dấu hoàn thành.']);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $this->appointmentService->cancelByCompany($request->user(), $id);

            return response()->json(['message' => 'Đã huỷ lịch hẹn.']);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
