<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Company;
use App\Models\User;
use App\Models\VietnamDistrict;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Creates a Company for a user. Mirrors the Blade CompanyController::store
 * logic, stripped to the fields the headless Next.js form actually sends.
 *
 * Business rules:
 *   - A user MAY own multiple companies (no 1:1 enforcement in DB), but for
 *     Phase 1 we only allow one. Reject if a company already exists.
 *   - New companies start as status = PENDING (2). Admin approval required.
 *   - Location code lookup happens here (not in controller) so the same
 *     resolution logic can be reused by future admin tools.
 */
class CompanyCreationService
{
    /**
     * @param  array{
     *   name:string, email:string, phone:?string, category_id:int,
     *   description:string, experience:int,
     *   city_code:string, district_code:string, ward_code:?string, address:string,
     *   tags:?array<int,string>, services:?array<int,array<string,mixed>>
     * }  $data
     */
    public function create(User $user, array $data): Company
    {
        if ($user->companies()->exists()) {
            throw new InvalidArgumentException('company_already_exists');
        }

        $city = VietnamDistrict::where('city_code', $data['city_code'])
            ->orWhere('City_code', $data['city_code'])
            ->first();
        if (! $city) {
            throw new InvalidArgumentException('city_not_found');
        }

        $district = VietnamDistrict::where('district_code', $data['district_code'])
            ->orWhere('District_code', $data['district_code'])
            ->first();
        if (! $district) {
            throw new InvalidArgumentException('district_not_found');
        }

        $wardName = null;
        if (! empty($data['ward_code'])) {
            $ward = VietnamDistrict::where('ward_code', $data['ward_code'])
                ->orWhere('Ward_code', $data['ward_code'])
                ->first();
            if (! $ward) {
                throw new InvalidArgumentException('ward_not_found');
            }
            $wardName = $ward->ward;
        }

        return DB::transaction(function () use ($user, $data, $city, $district, $wardName) {
            $company = new Company();
            $company->forceFill([
                'user_id'      => $user->id,
                'category_id'  => $data['category_id'],
                'name'         => $data['name'],
                'email'        => strtolower($data['email']),
                'phone'        => $data['phone'] ?? null,
                'address'      => $data['address'],
                'city'         => $city->city,
                'district'     => $district->district,
                'ward'         => $wardName,
                'state'        => '',
                'zip'          => '',
                'country'      => 'Vietnam',
                'description'  => $data['description'],
                'experience'   => (int) $data['experience'],
                'status'       => Status::PENDING,
                'tags'         => $data['tags'] ?? [],
                'services'     => $data['services'] ?? [],
                'business_hours' => [],
            ])->save();

            return $company;
        });
    }
}
