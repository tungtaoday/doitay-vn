<?php

namespace App\Services;

use App\Models\ThoSubmission;
use App\Models\User;
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
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '84')) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
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

            return $submission->load('images');
        });
    }
}
