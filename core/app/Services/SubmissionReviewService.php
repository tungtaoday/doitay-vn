<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Category;
use App\Models\Company;
use App\Models\ThoSubmission;
use App\Models\User;
use App\Support\Identifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Quản lý duyệt hồ sơ thợ: reject + approve (→ tạo thợ công khai + ghi hoa hồng).
 * Ref: DUC-SUBMISSION-APPROVE, DUC-SUBMISSION-REJECT.
 *
 * Lưu ý: tạo thợ ánh xạ nghề→category (LIKE, fallback default) và khu_vuc (text)
 * vì submission chỉ có dữ liệu tự do. Company tạo ở trạng thái APPROVED — vì việc
 * Quản lý duyệt submission (kiểm ảnh + thông tin) CHÍNH LÀ bước vet → thợ lên chợ ngay
 * (FR-006), tránh double-approval.
 */
class SubmissionReviewService
{
    public function __construct(private readonly CommissionService $commissions)
    {
    }

    public function reject(ThoSubmission $submission, string $reason): ThoSubmission
    {
        $this->assertPending($submission);

        $submission->update([
            'status'        => 'rejected',
            'ly_do_tu_choi' => $reason,
        ]);

        return $submission;
    }

    public function approve(ThoSubmission $submission): ThoSubmission
    {
        $this->assertPending($submission);

        return DB::transaction(function () use ($submission) {
            // Duyệt ảnh (BR-2).
            $submission->images()->update(['approved' => true]);

            $user    = $this->findOrCreateThoUser($submission);
            $company = $this->findOrCreateCompany($user, $submission);

            $submission->update([
                'status'     => 'approved',
                'company_id' => $company->id,
            ]);

            // Ghi hoa hồng (idempotent).
            $this->commissions->recordFor($submission);

            return $submission->fresh(['images']);
        });
    }

    private function assertPending(ThoSubmission $submission): void
    {
        if ($submission->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Hồ sơ không ở trạng thái chờ duyệt.',
            ]);
        }
    }

    /** Idempotent theo SĐT chuẩn hoá (BR-SUB-A2). */
    private function findOrCreateThoUser(ThoSubmission $submission): User
    {
        $mobile = Identifier::normalizePhone($submission->sdt_tho);

        $user = User::where('mobile', $mobile)->first();
        if ($user) {
            return $user;
        }

        return User::create([
            'name'             => $submission->ten_tho,
            'firstname'        => $submission->ten_tho,
            'lastname'         => '',
            'password'         => Hash::make(bin2hex(random_bytes(16))),
            'status'           => 1,
            'ev'               => 0,
            'sv'               => 1,
            'profile_complete' => 1,
            'mobile'           => $mobile,
            'dial_code'        => '+84',
            'country_code'     => 'VN',
            'country_name'     => 'Vietnam',
            'email'            => 'tho_' . $mobile . '_' . uniqid() . '@phone.doitay.local',
        ]);
    }

    private function findOrCreateCompany(User $user, ThoSubmission $submission): Company
    {
        $existing = $user->companies()->first();
        if ($existing) {
            return $existing;
        }

        [$district, $city] = $this->splitKhuVuc($submission->khu_vuc);

        $company = new Company();
        $company->forceFill([
            'user_id'        => $user->id,
            'category_id'    => $this->resolveCategoryId($submission->nghe),
            'name'           => $submission->ten_tho,
            'email'          => $user->email,
            'phone'          => $submission->sdt_tho,
            'address'        => $submission->khu_vuc,
            'city'           => $city,
            'district'       => $district,
            'ward'           => null,
            'state'          => '',
            'zip'            => '',
            'country'        => 'Vietnam',
            'description'    => 'Thợ ' . $submission->nghe . ' — khu vực ' . $submission->khu_vuc,
            'experience'     => (int) ($submission->nam_kn ?? 0),
            // Duyệt submission = đã vet → publish thợ lên chợ ngay (FR-006).
            'status'         => Status::APPROVED,
            'tags'           => [],
            'services'       => $submission->bang_gia ?? [],
            'business_hours' => [],
        ])->save();

        // P0.2: tạo ví + tặng tín dụng chào mừng (config marketplace.welcome_credit).
        // createForCompany dùng firstOrCreate + wasRecentlyCreated → idempotent,
        // không thể tặng 2 lần cho cùng company.
        \App\Models\CompanyWallet::createForCompany($company);

        return $company;
    }

    private function resolveCategoryId(string $nghe): int
    {
        $cat = Category::where('name', 'like', '%' . $nghe . '%')->first()
            ?? Category::query()->first();

        return (int) ($cat?->id ?? config('sale.default_category_id', 1));
    }

    /** "Cầu Giấy, Hà Nội" → [district, city]. */
    private function splitKhuVuc(string $khuVuc): array
    {
        $parts = array_values(array_filter(array_map('trim', explode(',', $khuVuc))));

        if (count($parts) >= 2) {
            $city = array_pop($parts);
            return [implode(', ', $parts), $city];
        }

        return [$khuVuc, $khuVuc];
    }
}
