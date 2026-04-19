<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Feature;
use App\Models\Rating;
use App\Models\RatingDetail;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RatingService
{
    public function submitRating(User $user, int $appointmentId, array $data): Rating
    {
        $appointment = Appointment::with('company')->findOrFail($appointmentId);

        if ($appointment->user_id !== $user->id) {
            throw new \DomainException('Không có quyền đánh giá lịch hẹn này.');
        }

        if ($appointment->status !== 'completed') {
            throw new \DomainException('Chỉ có thể đánh giá lịch hẹn đã hoàn thành.');
        }

        $existing = Rating::where('user_id', $user->id)
            ->where('appointment_id', $appointment->id)
            ->first();

        if ($existing) {
            throw new \DomainException('Bạn đã đánh giá lịch hẹn này rồi.');
        }

        $rating = Rating::create([
            'user_id' => $user->id,
            'company_id' => $appointment->company_id,
            'appointment_id' => $appointment->id,
            'suggest' => $data['comment'] ?? '',
            'status' => 1,
        ]);

        if (!empty($data['ratings']) && is_array($data['ratings'])) {
            foreach ($data['ratings'] as $featureId => $score) {
                RatingDetail::create([
                    'rating_id' => $rating->id,
                    'feature_id' => (int) $featureId,
                    'rating' => (float) $score,
                ]);
            }
        }

        $avgRating = RatingDetail::where('rating_id', $rating->id)->avg('rating');
        $rating->avg_rating = round($avgRating ?? 0, 2);
        $rating->save();

        $companyAvg = RatingDetail::join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
            ->where('ratings.company_id', $appointment->company_id)
            ->avg('rating_details.rating');

        $company = $appointment->company;
        $company->avg_rating = $companyAvg ? round($companyAvg, 2) : 0;
        $company->save();

        // Notify contractor that a new review has landed.
        $contractorOwner = $company->user ?? null;
        if ($contractorOwner) {
            $stars = number_format((float) $rating->avg_rating, 1);
            $customerName = $user->fullname ?: $user->name;
            NotificationService::sendSystemNotification(
                $contractorOwner,
                'Bạn có đánh giá mới',
                "{$customerName} đã đánh giá {$stars}/5 cho lịch hẹn #{$appointment->id}.",
                'review_received',
                url('/vi/tho/lich-hen/' . $appointment->id),
            );
        }

        return $rating->load(['ratingDetails.feature', 'user:id,name']);
    }

    public function listForCompany(int $companyId, int $perPage = 10): LengthAwarePaginator
    {
        return Rating::where('company_id', $companyId)
            ->where('status', 1)
            ->with(['ratingDetails.feature:id,name', 'user:id,name', 'appointment:id,appointment_date'])
            ->latest()
            ->paginate($perPage);
    }

    public function featuresForCategory(int $categoryId): Collection
    {
        return Feature::where('category_id', $categoryId)
            ->where('status', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'description']);
    }

    public function ratingForAppointment(User $user, int $appointmentId): ?Rating
    {
        return Rating::where('user_id', $user->id)
            ->where('appointment_id', $appointmentId)
            ->with('ratingDetails.feature:id,name')
            ->first();
    }
}
