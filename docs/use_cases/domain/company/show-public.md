---
id: DUC-COMPANY-SHOW-PUBLIC
name: "Show Public Company Detail"
version: "1.1"
date: 2026-04-11
status: drafted
finished_at: null
---

# DUC-COMPANY-SHOW-PUBLIC: Show Public Company Detail

## Brief Description

Trả về chi tiết 1 company public theo **id**, kèm portfolio, ratings (paginated nested), category, location. Đây là page detail SEO quan trọng nhất.

> **URL parity note**: live `companies` table KHÔNG có cột `slug`. Legacy Blade route hiện tại là `/companies/{id}/{vanity-slug}` (id-based, slug chỉ là vanity SEO segment). API v1 cũng resolve theo `id`; resource trả thêm `vanity_slug` (derived từ `name` qua `Str::slug`) để frontend build URL `/cong-ty/{id}/{vanity-slug}` cho SEO.

## Actors
- **Public visitor** (no auth)
- **Public/CompanyController**

## Preconditions
1. Id tồn tại trong DB.
2. Company có `status = approved`.

## Postconditions
### Success
1. Trả company với eager-loaded relations.
2. (Future) Tăng counter `views` async qua queue, không block response.

### Failure
1. 404 nếu id không tồn tại hoặc company không approved.

## Main Success Flow

| Step | Component | Action |
|---|---|---|
| 1 | Controller | `GET /api/v1/public/companies/{id}` |
| 2 | Service    | `CompanySearchService::showPublicById($id)` → `Company::approved()->with(['category','portfolios','ratings.user'])->where('id', $id)->firstOrFail()` |
| 3 | Controller | (Phase 2) Dispatch `IncrementCompanyViewJob($company->id)` |
| 4 | Controller | Return `new Public\CompanyDetailResource($company)` |

## Exception Flows
- **EF1**: Id không tồn tại → 404
- **EF2**: Company `pending`/`rejected` → 404 (cùng response shape với EF1, tránh leak existence)

## Business Rules
| Rule | Description |
|---|---|
| BR-CS-1 | Chỉ trả company `approved` |
| BR-CS-2 | Ratings nested chỉ trả 5 cái mới nhất + tổng count (full list qua endpoint riêng) |
| BR-CS-3 | (Phase 2) View count tăng async, không block response |
| BR-CS-4 | Khi schema có cột `show_contact`: email/phone owner chỉ trả nếu `show_contact = true`. Hiện tại schema chưa có flag này → expose mặc định (parity với Blade hôm nay). |
| BR-CS-5 | (Phase 2) Cache 10 phút theo key `company:public:{id}` |

## Data Requirements

### Output (200)
```json
{
  "data": {
    "id": 54,
    "vanity_slug": "tho-nuoc-chuyen-nghiep",
    "name": "Thợ Nước Chuyên Nghiệp",
    "description": "...long html...",
    "image": "https://.../assets/images/company/x.jpg",
    "category": { "id": 2, "name": "Thợ Nước" },
    "location": { "district": "Quận 3", "ward": null, "state": null, "country": "Vietnam" },
    "tags": [],
    "services": [],
    "business_hours": null,
    "experience": 5,
    "rating_avg": 5.0,
    "rating_count": 3,
    "portfolios": [{ "id": 1, "title": "...", "image": "..." }],
    "ratings_recent": [
      { "id": 100, "score": 5, "comment": "...", "user": { "name": "A", "avatar": null }, "created_at": "..." }
    ],
    "show_contact": true,
    "phone": "0901234567",
    "email": "contact@x.com",
    "website": "https://x.com",
    "created_at": "..."
  }
}
```

## Acceptance Criteria
| AC | Description |
|---|---|
| AC1 | GET với id hợp lệ → 200 + full detail |
| AC2 | Id không tồn tại → 404 |
| AC3 | Company pending → 404 |
| AC4 | (Phase 2, when `show_contact` exists) `show_contact=false` → response KHÔNG chứa phone/email |
| AC5 | (Phase 2) View count tăng sau request (async, verify bằng `Bus::fake()`) |
| AC6 | Ratings nested ≤ 5 |
| AC7 | Response chứa category, portfolios eager-loaded (không N+1) |
| AC8 | Response chứa `vanity_slug` derived từ `name` để FE build SEO URL |

## References
- API: `GET /api/v1/public/companies/{id}`
- Controller: `App\Http\Controllers\API\V1\Public\CompanyController@show`
- Service: `App\Services\CompanySearchService@showPublicById`
- Resource: `App\Http\Resources\V1\Public\CompanyDetailResource`
