<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Company;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Marketplace matching engine cho service request.
 *
 * Luồng broadcast + matching: customer post yêu cầu → service tạo bản ghi +
 * gọi matchCompanies() để rank top N thợ → customer pick 1 thợ → luồng đặt
 * lịch hiện tại (POST /api/v1/user/appointments) tiếp quản.
 *
 * Không trừ tiền ai lúc matching — phí chỉ thu ở bước company confirm lead
 * (như hiện tại trong AppointmentService::confirmByCompany).
 */
class ServiceRequestService
{
    public const MAX_MATCHES = 5;
    public const MAX_OPEN_PER_USER_24H = 5;
    public const EXPIRY_DAYS = 14;

    // Score weights — giữ đơn giản cho MVP, tunable sau
    private const W_CATEGORY = 40;
    private const W_CITY = 20;
    private const W_DISTRICT = 15;
    private const W_RATING_MAX = 25;
    private const W_ACTIVITY_MAX = 20;

    public function create(User $user, array $data): ServiceRequest
    {
        $this->assertNotSpamming($user);

        return DB::transaction(function () use ($user, $data) {
            return ServiceRequest::create([
                'user_id' => $user->id,
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'city' => $data['city'],
                'district' => $data['district'] ?? null,
                'ward' => $data['ward'] ?? null,
                'address' => $data['address'] ?? null,
                'budget_min' => $data['budget_min'] ?? null,
                'budget_max' => $data['budget_max'] ?? null,
                'preferred_date' => $data['preferred_date'] ?? null,
                'preferred_time_slot' => $data['preferred_time_slot'] ?? null,
                'contact_name' => $data['contact_name'],
                'contact_phone' => $data['contact_phone'],
                'images' => $data['images'] ?? null,
                'status' => 'open',
                'expires_at' => now()->addDays(self::EXPIRY_DAYS),
            ]);
        });
    }

    /**
     * Match thợ cho request. Sync, không cache — re-run được để phản ánh thợ
     * mới. Trả collection với shape: [{company, score, reasons[]}].
     */
    public function matchCompanies(ServiceRequest $request): Collection
    {
        $candidates = Company::query()
            ->where('status', \App\Constants\Status::APPROVED)
            ->where('category_id', $request->category_id)
            ->with('category:id,name')
            ->get();

        if ($candidates->isEmpty()) {
            return collect();
        }

        // Completed count per company (activity signal) — 1 query để tránh N+1
        $completedCounts = Appointment::query()
            ->whereIn('company_id', $candidates->pluck('id'))
            ->where('status', 'completed')
            ->selectRaw('company_id, COUNT(*) as cnt')
            ->groupBy('company_id')
            ->pluck('cnt', 'company_id');

        $maxActivity = max(1, $completedCounts->max() ?? 1);

        $scored = $candidates->map(function (Company $company) use ($request, $completedCounts, $maxActivity) {
            [$score, $reasons] = $this->scoreCompany(
                $company,
                $request,
                (int) ($completedCounts[$company->id] ?? 0),
                $maxActivity,
            );

            return [
                'company' => $company,
                'score' => $score,
                'reasons' => $reasons,
            ];
        });

        return $scored
            ->sortByDesc('score')
            ->take(self::MAX_MATCHES)
            ->values();
    }

    /**
     * @return array{0: int, 1: array<string>}
     */
    private function scoreCompany(Company $company, ServiceRequest $request, int $completed, int $maxActivity): array
    {
        $score = 0;
        $reasons = [];

        // Category: đã pre-filter nên luôn match
        $score += self::W_CATEGORY;
        $reasons[] = 'category_match';

        // Location: city exact, district bonus
        if ($company->city && $this->normalizeLocation($company->city) === $this->normalizeLocation($request->city)) {
            $score += self::W_CITY;
            $reasons[] = 'same_city';

            if ($request->district && $company->district
                && $this->normalizeLocation($company->district) === $this->normalizeLocation($request->district)) {
                $score += self::W_DISTRICT;
                $reasons[] = 'same_district';
            }
        }

        // Rating: tuyến tính 0..5 → 0..25
        $rating = (float) ($company->avg_rating ?? 0);
        if ($rating > 0) {
            $score += (int) round(($rating / 5) * self::W_RATING_MAX);
            $reasons[] = 'rating_' . number_format($rating, 1);
        }

        // Activity: completed count normalized
        if ($completed > 0) {
            $score += (int) round(($completed / $maxActivity) * self::W_ACTIVITY_MAX);
            $reasons[] = "completed_{$completed}";
        }

        return [$score, $reasons];
    }

    private function normalizeLocation(?string $value): string
    {
        return mb_strtolower(trim((string) $value));
    }

    private function assertNotSpamming(User $user): void
    {
        $count = ServiceRequest::where('user_id', $user->id)
            ->where('status', 'open')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($count >= self::MAX_OPEN_PER_USER_24H) {
            throw new \DomainException('Bạn đang có quá nhiều yêu cầu mở. Hãy đóng bớt hoặc chờ xử lý trước khi tạo mới.');
        }
    }

    public function findForUser(User $user, int $id): ServiceRequest
    {
        return ServiceRequest::where('user_id', $user->id)
            ->with('category:id,name')
            ->findOrFail($id);
    }

    /**
     * P0.1 — Báo cho các thợ vừa được match biết có khách cần họ.
     * Chỉ gọi MỘT LẦN khi request vừa tạo (từ controller store), không gọi khi
     * xem lại kết quả. In-app notification; email/Zalo bổ sung sau.
     */
    public function notifyMatches(ServiceRequest $request, \Illuminate\Support\Collection $matches): void
    {
        $area = trim(implode(', ', array_filter([$request->district, $request->city])));
        $categoryName = $request->category->name ?? 'dịch vụ';

        foreach ($matches as $match) {
            $company = $match['company'] ?? $match;
            $owner = $company->user ?? null;
            if (!$owner) {
                continue;
            }

            try {
                \App\Services\NotificationService::sendSystemNotification(
                    $owner,
                    'Có khách đang cần ' . $categoryName,
                    'Khách tại ' . ($area ?: 'khu vực của bạn') . ' vừa đăng yêu cầu: "'
                        . mb_substr($request->title, 0, 80)
                        . '". Hồ sơ của bạn được gợi ý cho khách — hãy giữ máy, khách có thể đặt lịch.',
                    'lead_match',
                    url('/tho/' . $company->id),
                );
            } catch (\Throwable) {
                // Notify lỗi không được làm hỏng luồng tạo yêu cầu.
            }
        }
    }

    /**
     * Đánh dấu request đã được convert thành appointment. Gọi từ
     * AppointmentService::createForUser khi payload có service_request_id.
     */
    public function linkAppointment(ServiceRequest $request, int $appointmentId, int $companyId): void
    {
        $request->update([
            'status' => 'matched',
            'selected_company_id' => $companyId,
            'selected_appointment_id' => $appointmentId,
            'closed_at' => now(),
        ]);
    }
}
