<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use App\Support\Identifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Xuất bản hồ sơ thợ từ Zalo Mini App lên chợ doitay.
 *
 * SERVER LÀ NGUỒN SỰ THẬT: Mini App chỉ là client, localStorage chỉ để cache.
 * Thợ đổi máy / cài lại app → khôi phục bằng `findByZaloId()`.
 *
 * - Idempotent theo SĐT chuẩn hoá: cùng số → cùng company (không tạo trùng).
 * - Hồ sơ đã có: CẬP NHẬT khi người gọi chứng minh sở hữu (`zalo_id` khớp) hoặc
 *   khi hồ sơ chưa có chủ. Người lạ gửi trùng SĐT → trả hồ sơ cũ, không sửa gì
 *   (chống chiếm hồ sơ).
 * - Mặc định tạo PENDING → vào hàng đợi duyệt (/sale/duyet, /quan-tri) để chống
 *   hồ sơ ảo. Bật `marketplace.miniapp_autopublish=true` để lên chợ ngay.
 */
class MiniAppProfileService
{
    /** Vé nhận hồ sơ (CTV dựng hộ) sống bao lâu. */
    public const CLAIM_TTL_DAYS = 30;

    /**
     * @return array{company: Company, created: bool, live: bool, updated: bool}
     */
    public function publish(array $data): array
    {
        $mobile = Identifier::normalizePhone($data['phone']);
        $zaloId = trim((string) ($data['zalo_id'] ?? ''));

        return DB::transaction(function () use ($data, $mobile, $zaloId) {
            $user = $this->findOrCreateThoUser($data, $mobile);

            // Dedup theo SĐT: cùng số → cùng company.
            $existing = $user->companies()->first();
            if ($existing) {
                $owner = trim((string) ($existing->zalo_id ?? ''));
                // Chủ sở hữu khớp, hoặc hồ sơ chưa có chủ → cho phép cập nhật.
                $mayEdit = $zaloId !== '' && ($owner === '' || $owner === $zaloId);
                if ($mayEdit) {
                    $this->applyProfileFields($existing, $data);
                    if ($owner === '') {
                        $existing->zalo_id = $zaloId;
                    }
                    $existing->save();
                }

                return [
                    'company' => $existing->refresh(),
                    'created' => false,
                    'updated' => $mayEdit,
                    'live'    => (int) $existing->status === Status::APPROVED,
                ];
            }

            $autopublish = (bool) config('marketplace.miniapp_autopublish', false);
            $status = $autopublish ? Status::APPROVED : Status::PENDING;

            $company = new Company();
            $company->forceFill([
                'user_id'        => $user->id,
                'category_id'    => $this->resolveCategoryId($data['nghe']),
                'name'           => $data['name'],
                'email'          => $user->email,
                'phone'          => $data['phone'],
                'zalo_id'        => $zaloId !== '' ? $zaloId : null,
                'address'        => trim(implode(', ', array_filter([$data['district'] ?? null, $data['city'] ?? null]))),
                'city'           => $data['city'] ?? '',
                'district'       => $data['district'] ?? '',
                'ward'           => null,
                'state'          => '',
                'zip'            => '',
                'country'        => 'Vietnam',
                'description'    => $data['description'] ?? ('Thợ ' . $data['nghe']),
                'experience'     => (int) ($data['experience'] ?? 0),
                'status'         => $status,
                'tags'           => array_values(array_filter($data['tags'] ?? [])),
                'services'       => $this->normalizeServices($data['services'] ?? []),
                'business_hours' => [],
            ])->save();

            // Autopublish → tạo ví + tín dụng chào mừng ngay (như đường duyệt).
            if ($status === Status::APPROVED) {
                \App\Models\CompanyWallet::createForCompany($company);
            }

            return ['company' => $company, 'created' => true, 'updated' => false, 'live' => $status === Status::APPROVED];
        });
    }

    /** Ghi các trường hồ sơ do thợ nhập (dùng chung cho tạo mới & cập nhật). */
    private function applyProfileFields(Company $company, array $data): void
    {
        $company->name        = $data['name'];
        $company->phone       = $data['phone'];
        $company->category_id = $this->resolveCategoryId($data['nghe']);
        $company->city        = $data['city'] ?? '';
        $company->district    = $data['district'] ?? '';
        $company->address     = trim(implode(', ', array_filter([$data['district'] ?? null, $data['city'] ?? null])));
        $company->experience  = (int) ($data['experience'] ?? 0);
        if (! empty($data['description'])) {
            $company->description = $data['description'];
        }
        if (! empty($data['tags'])) {
            $company->tags = array_values(array_filter($data['tags']));
        }
        if (! empty($data['services'])) {
            $company->services = $this->normalizeServices($data['services']);
        }
    }

    /** Khôi phục hồ sơ theo định danh Zalo (thợ đổi máy / cài lại app). */
    public function findByZaloId(string $zaloId): ?Company
    {
        $zaloId = trim($zaloId);
        if ($zaloId === '') {
            return null;
        }

        return Company::with('portfolios')->where('zalo_id', $zaloId)->first();
    }

    /**
     * Sinh vé nhận hồ sơ để CTV gửi link cho thợ. Ghi đè vé cũ (mỗi hồ sơ 1 vé sống).
     *
     * @return array{token: string, expires_at: \Illuminate\Support\Carbon}
     */
    public function issueClaimToken(Company $company): array
    {
        $token = Str::lower(Str::random(40));
        $expires = now()->addDays(self::CLAIM_TTL_DAYS);

        $company->forceFill([
            'claim_token'      => $token,
            'claim_expires_at' => $expires,
        ])->save();

        return ['token' => $token, 'expires_at' => $expires];
    }

    /**
     * Thợ bấm link CTV gửi → đổi vé lấy quyền sở hữu hồ sơ.
     *
     * @return array{ok: bool, company?: Company, error?: string}
     */
    public function claim(Company $company, string $token, string $zaloId): array
    {
        $token = trim($token);
        $zaloId = trim($zaloId);

        if ($zaloId === '') {
            return ['ok' => false, 'error' => 'missing_zalo_id'];
        }
        // Đã là chủ rồi → coi như thành công (bấm lại link cũ không bị lỗi).
        if (trim((string) $company->zalo_id) === $zaloId) {
            return ['ok' => true, 'company' => $company];
        }
        if (! $company->claim_token || ! hash_equals($company->claim_token, $token)) {
            return ['ok' => false, 'error' => 'invalid_token'];
        }
        if ($company->claim_expires_at && $company->claim_expires_at->isPast()) {
            return ['ok' => false, 'error' => 'expired_token'];
        }
        if ($company->zalo_id) {
            return ['ok' => false, 'error' => 'already_claimed'];
        }

        $company->forceFill([
            'zalo_id'          => $zaloId,
            'claim_token'      => null,
            'claim_expires_at' => null,
            'claimed_at'       => now(),
        ])->save();

        return ['ok' => true, 'company' => $company->refresh()];
    }

    /** Người gọi có quyền sửa hồ sơ này không (chủ sở hữu, hoặc vé còn hạn). */
    public function mayEdit(Company $company, string $zaloId, ?string $claimToken = null): bool
    {
        $zaloId = trim($zaloId);
        $owner = trim((string) $company->zalo_id);

        if ($zaloId !== '' && $owner !== '' && $owner === $zaloId) {
            return true;
        }
        if ($owner === '' && $claimToken && $company->claim_token
            && hash_equals($company->claim_token, trim($claimToken))
            && (! $company->claim_expires_at || ! $company->claim_expires_at->isPast())) {
            return true;
        }

        return false;
    }

    private function findOrCreateThoUser(array $data, string $mobile): User
    {
        $user = User::where('mobile', $mobile)->first();
        if ($user) {
            return $user;
        }

        return User::create([
            'name'             => $data['name'],
            'firstname'        => $data['name'],
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

    private function resolveCategoryId(string $nghe): int
    {
        $cat = Category::where('name', 'like', '%' . $nghe . '%')->first()
            ?? Category::query()->first();

        return (int) ($cat?->id ?? config('sale.default_category_id', 1));
    }

    /** [{name, unit, price}] → chuẩn hoá, bỏ dòng trống. */
    private function normalizeServices(array $services): array
    {
        return array_values(array_filter(array_map(function ($s) {
            $name = trim((string) ($s['name'] ?? ''));
            if ($name === '') {
                return null;
            }
            return [
                'name'  => $name,
                'unit'  => trim((string) ($s['unit'] ?? '')),
                'price' => trim((string) ($s['price'] ?? '')),
            ];
        }, $services)));
    }

    /** URL công khai của hồ sơ (chỉ hoạt động khi company đã APPROVED). */
    public function profileUrl(Company $company): string
    {
        $slug = Str::slug((string) $company->name);
        return rtrim((string) config('app.frontend_url', 'https://doitay.vn'), '/') . '/tho/' . $company->id . ($slug ? '/' . $slug : '');
    }
}
