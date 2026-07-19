<?php

namespace App\Http\Resources\V1\User;

use App\Models\Company;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Shape của service request + matched companies (optional).
 *
 * Dùng với 2 case:
 * - index/show: chỉ snapshot request
 * - create/match: gắn thêm `matches` collection (bơm qua ->additional() hoặc
 *   instance state)
 */
class ServiceRequestResource extends JsonResource
{
    public ?Collection $matches = null;

    public function withMatches(Collection $matches): self
    {
        $this->matches = $matches;

        return $this;
    }

    public function toArray(Request $request): array
    {
        /** @var ServiceRequest $model */
        $model = $this->resource;

        return [
            'id' => $model->id,
            'status' => $model->status,
            'title' => $model->title,
            'description' => $model->description,
            'category' => $model->category ? [
                'id' => $model->category->id,
                'name' => $model->category->name,
            ] : null,
            'location' => [
                'city' => $model->city,
                'district' => $model->district,
                'ward' => $model->ward,
                'address' => $model->address,
            ],
            'budget' => [
                'min' => $model->budget_min,
                'max' => $model->budget_max,
            ],
            'preferred_date' => $model->preferred_date?->toDateString(),
            'preferred_time_slot' => $model->preferred_time_slot,
            'contact' => [
                'name' => $model->contact_name,
                'phone' => $model->contact_phone,
            ],
            'images' => $model->images ?? [],
            'is_editable' => $model->isEditable(),
            'selected_company_id' => $model->selected_company_id,
            'selected_appointment_id' => $model->selected_appointment_id,
            'expires_at' => $model->expires_at?->toIso8601String(),
            'closed_at' => $model->closed_at?->toIso8601String(),
            'created_at' => $model->created_at?->toIso8601String(),
            'matches' => $this->when(
                $this->matches !== null,
                fn () => $this->matches->map(fn ($m) => $this->shapeMatch($m))->all(),
            ),
        ];
    }

    private function shapeMatch(array $match): array
    {
        /** @var Company $company */
        $company = $match['company'];

        return [
            'company' => [
                'id' => $company->id,
                'vanity_slug' => Str::slug($company->name),
                'name' => $company->name,
                // DB có thể lưu tên file thô — build full URL như CompanyResource,
                // nếu không FE sẽ resolve tương đối thành URL rác (ảnh vỡ).
                'image' => $company->image
                    ? (str_starts_with($company->image, 'http') ? $company->image : asset('assets/images/company/' . $company->image))
                    : null,
                'category' => $company->category ? [
                    'id' => $company->category->id,
                    'name' => $company->category->name,
                ] : null,
                'avg_rating' => (float) ($company->avg_rating ?? 0),
                'city' => $company->city,
                'district' => $company->district,
                'short_description' => Str::limit((string) $company->description, 160),
            ],
            'score' => $match['score'],
            'reasons' => $match['reasons'],
        ];
    }
}
