<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\CreateAppointmentRequest;
use App\Http\Requests\V1\User\SubmitRatingRequest;
use App\Http\Resources\V1\User\AppointmentResource;
use App\Services\AppointmentService;
use App\Services\RatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $appointmentService,
        protected RatingService $ratingService,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $appointments = $this->appointmentService->listForUser(
            $request->user(),
            (int) $request->input('per_page', 20),
        );

        return AppointmentResource::collection($appointments);
    }

    public function store(CreateAppointmentRequest $request): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->createForUser(
                $request->user(),
                $request->validated(),
            );

            return (new AppointmentResource($appointment))
                ->response()
                ->setStatusCode(201);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(Request $request, int $id): AppointmentResource
    {
        $appointment = $this->appointmentService->findForUser($request->user(), $id);

        return new AppointmentResource($appointment);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $this->appointmentService->cancelByUser($request->user(), $id);

            return response()->json(['message' => 'Đã huỷ lịch hẹn.']);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function submitReview(SubmitRatingRequest $request, int $id): JsonResponse
    {
        try {
            $this->ratingService->submitRating(
                $request->user(),
                $id,
                $request->validated(),
            );

            return response()->json(['message' => 'Cảm ơn bạn đã đánh giá!']);
        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function rating(Request $request, int $id): JsonResponse
    {
        $rating = $this->ratingService->ratingForAppointment($request->user(), $id);

        if (!$rating) {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => [
                'id' => $rating->id,
                'avg_rating' => (float) $rating->avg_rating,
                'comment' => $rating->suggest,
                'details' => $rating->ratingDetails->map(fn ($d) => [
                    'feature_id' => $d->feature_id,
                    'feature_name' => $d->feature?->name,
                    'rating' => (float) $d->rating,
                ]),
            ],
        ]);
    }
}
