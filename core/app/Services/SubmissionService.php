<?php

namespace App\Services;

use App\Models\Company;
use App\Models\ThoSubmission;
use App\Models\User;
use App\Services\MiniAppProfileService;
use App\Services\ThoPortfolioService;
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
    public function create(User $ctv, array $data, array $imageFiles, $anhChanDung = null): ThoSubmission
    {
        $normalized = self::normalizePhone($data['sdt_tho']);
        $loai = ($data['loai'] ?? 'lam_ho') === 'da_mo' ? 'da_mo' : 'lam_ho';

        $exists = ThoSubmission::query()
            ->where('sdt_normalized', $normalized)
            ->where('status', '!=', 'rejected')
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'sdt_tho' => 'Số điện thoại này đã tồn tại trong hệ thống.',
            ]);
        }

        // Kiểu "thợ đã tự mở": hồ sơ phải CÓ THẬT và đã có chủ (thợ đã bấm vào app).
        // Lấy luôn tên/nghề/khu vực từ hồ sơ để CTV khỏi gõ lại.
        $daMoCompany = null;
        if ($loai === 'da_mo') {
            $daMoCompany = $this->findClaimedCompanyByPhone($normalized);
            if (! $daMoCompany) {
                throw ValidationException::withMessages([
                    'sdt_tho' => 'Chưa thấy hồ sơ nào của số này trong app. Nhờ thợ mở Zalo tạo hồ sơ trước, '
                        . 'hoặc chuyển sang mục "Làm hộ tại chỗ".',
                ]);
            }
            // CTV không phải gõ lại — lấy thẳng từ hồ sơ thợ đã tạo.
            $data['ten_tho'] = ($data['ten_tho'] ?? null) ?: $daMoCompany->name;
            $data['nghe'] = ($data['nghe'] ?? null) ?: (string) ($daMoCompany->category?->name ?? 'Thợ');
            $data['khu_vuc'] = ($data['khu_vuc'] ?? null)
                ?: (trim(implode(', ', array_filter([$daMoCompany->district, $daMoCompany->city]))) ?: 'Chưa rõ');
        }

        return DB::transaction(function () use ($ctv, $data, $normalized, $imageFiles, $loai, $daMoCompany, $anhChanDung) {
            $submission = ThoSubmission::create([
                'ctv_id'         => $ctv->id,
                'loai'           => $loai,
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

            // Thợ đã tự mở → hồ sơ có sẵn, chỉ gắn để đối chiếu khi duyệt. Không
            // sinh vé (hồ sơ đã có chủ, không ai được nhận nữa).
            if ($daMoCompany) {
                $submission->company_id = $daMoCompany->id;
                $submission->save();

                return $submission->load('images');
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
                // Ảnh chân dung → companies.image, hiện ngay trên hồ sơ khách xem
                if ($anhChanDung) {
                    app(ThoPortfolioService::class)->setAvatar($company, $anhChanDung);
                }
                app(MiniAppProfileService::class)->issueClaimToken($company);
                $submission->company_id = $company->id;
                $submission->save();
            } catch (\Throwable $e) {
                report($e);
            }

            return $submission->load('images');
        });
    }

    /**
     * Tìm hồ sơ THỢ ĐÃ TỰ MỞ theo SĐT (đã có chủ = thợ đã bấm vào app).
     * Dùng cho kiểu `da_mo` — và cũng là API tra cứu cho form CTV.
     */
    public function findClaimedCompanyByPhone(string $normalizedPhone): ?Company
    {
        $user = User::where('mobile', $normalizedPhone)->first();
        if (! $user) {
            return null;
        }

        return $user->companies()
            ->whereNotNull('zalo_id')
            ->with('category')
            ->first();
    }

    /**
     * Tra cứu cho form CTV: số này đã có hồ sơ chưa, ai đã nhận công chưa.
     *
     * @return array{ton_tai: bool, da_mo: bool, ten_tho: ?string, nghe: ?string,
     *               khu_vuc: ?string, da_co_ctv: bool, company_id: ?int}
     */
    public function lookupByPhone(string $phone): array
    {
        $normalized = self::normalizePhone($phone);
        $company = $this->findClaimedCompanyByPhone($normalized);
        $daCoCtv = ThoSubmission::where('sdt_normalized', $normalized)
            ->where('status', '!=', 'rejected')
            ->exists();

        return [
            'ton_tai'    => (bool) $company,
            'da_mo'      => (bool) $company,
            'ten_tho'    => $company?->name,
            'nghe'       => $company?->category?->name,
            'khu_vuc'    => $company ? trim(implode(', ', array_filter([$company->district, $company->city]))) : null,
            'da_co_ctv'  => $daCoCtv,
            'company_id' => $company?->id,
        ];
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
