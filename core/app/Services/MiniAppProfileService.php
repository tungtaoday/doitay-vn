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
 * - Idempotent theo SĐT chuẩn hoá: cùng số → cùng company (không tạo trùng).
 * - Mặc định tạo PENDING → vào hàng đợi duyệt (/sale/duyet, /quan-tri) để chống
 *   hồ sơ ảo. Bật `marketplace.miniapp_autopublish=true` để lên chợ ngay.
 */
class MiniAppProfileService
{
    /**
     * @return array{company: Company, created: bool, live: bool}
     */
    public function publish(array $data): array
    {
        $mobile = Identifier::normalizePhone($data['phone']);

        return DB::transaction(function () use ($data, $mobile) {
            $user = $this->findOrCreateThoUser($data, $mobile);

            // Dedup: SĐT này đã có company → cập nhật nhẹ, không tạo trùng.
            $existing = $user->companies()->first();
            if ($existing) {
                return ['company' => $existing, 'created' => false, 'live' => (int) $existing->status === Status::APPROVED];
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

            return ['company' => $company, 'created' => true, 'live' => $status === Status::APPROVED];
        });
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
