<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Public\CreateGuestAppointmentRequest;
use App\Models\ProductEvent;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;

/**
 * API V1 — Public. Đặt lịch KHÔNG cần đăng nhập. Hệ thống tự tạo tài khoản khách
 * theo SĐT/email rồi tạo lịch. Route có rate-limit (xem v1_public.php).
 */
class GuestAppointmentController extends Controller
{
    public function __construct(private readonly AppointmentService $service)
    {
    }

    public function store(CreateGuestAppointmentRequest $request): JsonResponse
    {
        try {
            $appointment = $this->service->createForGuest($request->validated());
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        // Đo phễu Bắc Đẩu: khách hoàn tất đặt lịch (an toàn — không chặn nếu lỗi).
        ProductEvent::log([
            'event'      => 'booking_confirmed',
            'surface'    => 'khach',
            'channel'    => 'web',
            'company_id' => $appointment->company_id,
            'meta'       => ['guest' => true, 'appointment_id' => $appointment->id],
        ]);

        return response()->json([
            'data' => [
                'id'         => $appointment->id,
                'company_id' => $appointment->company_id,
                'status'     => $appointment->status,
            ],
        ], 201);
    }
}
