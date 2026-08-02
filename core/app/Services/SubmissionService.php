<?php

namespace App\Services;

use App\Models\ThoSubmission;
use App\Models\User;
use App\Services\MiniAppProfileService;
use App\Support\Identifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Sale onboarding — tạo & quản lý hồ sơ thợ do CTV nhập.
 * Ref: DUC-SUBMISSION-CREATE, BR-1 (chống trùng SĐT).
 */
class SubmissionService
{
    /**
     * Chuẩn hoá SĐT về dạng so trùng: bỏ ký tự không phải số, +84/84 → 0.
     * "+84 972 585 990" và "0972585990" → cùng "0972585990".
     */
    public static function normalizePhone(string $phone): string
    {
        // Dùng chung helper với users.mobile để dedup nhất quán.
        return Identifier::normalizePhone($phone);
    }

    /**
     * Tạo submission (pending) + lưu ảnh. Chặn trùng SĐT (còn hiệu lực).
     *
     * @param  \Illuminate\Http\UploadedFile[]  $imageFiles
     *
     * @throws ValidationException khi SĐT đã tồn tại.
     */
    public function create(User $ctv, array $data, array $imageFiles): ThoSubmission
    {
        $normalized = self::normalizePhone($data['sdt_tho']);

        $exists = ThoSubmission::query()
            ->where('sdt_normalized', $normalized)
            ->where('status', '!=', 'rejected')
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'sdt_tho' => 'Số điện thoại này đã tồn tại trong hệ thống.',
            ]);
        }

        return DB::transaction(function () use ($ctv, $data, $normalized, $imageFiles) {
            $submission = ThoSubmission::create([
                'ctv_id'         => $ctv->id,
                'ten_tho'        => $data['ten_tho'],
                'nghe'           => $data['nghe'],
                'khu_vuc'        => $data['khu_vuc'],
                'sdt_tho'        => $data['sdt_tho'],
                'sdt_normalized' => $normalized,
                'nam_kn'         => $data['nam_kn'] ?? null,
                'bang_gia'       => $data['bang_gia'] ?? null,
                'status'         => 'pending',
            ]);

            foreach ($imageFiles as $file) {
                $path = $file->store('submissions', 'public');
                $submission->images()->create([
                    'url'      => Storage::disk('public')->url($path),
                    'approved' => false,
                ]);
            }

            // Dựng luôn hồ sơ (PENDING — chưa lên chợ) + vé để CTV gửi link cho thợ.
            // Thợ bấm link → Mini App nhận hồ sơ về tài khoản Zalo của thợ, khỏi
            // phải nhập lại gì. Chính việc thợ bấm được link cũng là bằng chứng
            // thợ có thật. Lỗi ở bước này KHÔNG được làm hỏng việc nhập của CTV.
            try {
                $result = app(MiniAppProfileService::class)->publish([
                    'name'        => $data['ten_tho'],
                    'phone'       => $data['sdt_tho'],
                    'nghe'        => $data['nghe'],
                    'city'        => $this->cityFromKhuVuc($data['khu_vuc']),
                    'district'    => $this->districtFromKhuVuc($data['khu_vuc']),
                    'experience'  => (int) ($data['nam_kn'] ?? 0),
                    'description' => 'Thợ ' . $data['nghe'] . ' — ' . $data['khu_vuc'],
                ]);
                $company = $result['company'];
                app(MiniAppProfileService::class)->issueClaimToken($company);
                $submission->company_id = $company->id;
                $submission->save();
            } catch (\Throwable $e) {
                report($e);
            }

            return $submission->load('images');
        });
    }

    /** "Cầu Giấy, Hà Nội" → quận = phần đầu, thành phố = phần sau (nếu có). */
    private function districtFromKhuVuc(string $khuVuc): string
    {
        $parts = array_map('trim', explode(',', $khuVuc));

        return $parts[0] ?? '';
    }

    private function cityFromKhuVuc(string $khuVuc): string
    {
        $parts = array_map('trim', explode(',', $khuVuc));

        return count($parts) > 1 ? end($parts) : '';
    }
}
