---
id: DUC-COMPANY-LIST-PUBLIC
name: "List Public Companies"
version: "1.0"
date: 2026-04-11
status: drafted
finished_at: null
---

# DUC-COMPANY-LIST-PUBLIC: List Public Companies

## Brief Description

Trả về danh sách company đã `approved` cho khách public, có pagination, filter theo category, location, search keyword. Đây là endpoint trung tâm cho homepage và search results.

## Actors
- **Public visitor** (no auth)
- **Public/CompanyController**, **CompanySearchService** (shared)

## Preconditions
1. Không cần auth.
2. Query params hợp lệ (page, per_page ≤ 50).

## Postconditions
### Success
1. Trả paginated list company với fields công khai.
2. Không expose email/phone owner trừ khi company chọn public.

### Failure
1. 422 nếu query params sai format.

## Main Success Flow

| Step | Component | Action |
|---|---|---|
| 1 | Public/CompanyController | `GET /api/v1/public/companies?category=...&location=...&q=...&page=1&per_page=20` |
| 2 | ListCompaniesRequest | Validate query params |
| 3 | CompanySearchService | Build query: `Company::approved()->with(['category','location'])` |
| 4 | CompanySearchService | Apply filters: category_id, district_id, search keyword (LIKE name OR description) |
| 5 | CompanySearchService | `->orderByDesc('rating_avg')->paginate($per_page)` |
| 6 | Controller | Return `Public\CompanyResource::collection($companies)` |

## Exception Flows
- **EF1**: per_page > 50 → 422
- **EF2**: page < 1 → 422
- **EF3**: category_id không tồn tại → trả empty list (không error)

## Business Rules
| Rule | Description | Enforcement |
|---|---|---|
| BR-CL-1 | Chỉ company `status = approved` được trả | Step 3 (scope `approved()`) |
| BR-CL-2 | Public Resource KHÔNG expose `owner_email`, `internal_notes`, `kyc_status` | Public/CompanyResource |
| BR-CL-3 | per_page mặc định 20, max 50 | Step 2 |
| BR-CL-4 | Sort mặc định theo `rating_avg DESC, id DESC` | Step 5 |
| BR-CL-5 | Cache kết quả 5 phút theo cache key `companies:list:{md5(query)}` | Service layer |
| BR-CL-6 | Rate limit `throttle:60,1` | Middleware |

## Data Requirements

### Input (query)
| Param | Type | Required | Validation |
|---|---|---|---|
| q | string | No | max:100 |
| category | int | No | exists:categories,id |
| location | int | No | exists:vietnam_districts,id |
| page | int | No | min:1, default:1 |
| per_page | int | No | min:1, max:50, default:20 |

### Output (200)
```json
{
  "data": [
    {
      "id": 1, "vanity_slug": "abc-construction",
      "name": "ABC Construction", "image": "https://...",
      "category": { "id": 5, "name": "Xây dựng" },
      "location": { "id": 1, "name": "Hà Nội" },
      "rating_avg": 4.7, "rating_count": 23,
      "short_description": "..."
    }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": { "current_page": 1, "from": 1, "to": 20, "total": 145, "per_page": 20, "version": "v1" }
}
```

## Acceptance Criteria
| AC | Description |
|---|---|
| AC1 | GET không params → 200, paginated 20 item |
| AC2 | Filter theo category → chỉ trả company của category đó |
| AC3 | Filter theo location → đúng |
| AC4 | Search `q=abc` → company có name/description match |
| AC5 | per_page=51 → 422 |
| AC6 | Response KHÔNG chứa `owner_email`, `internal_notes` |
| AC7 | Chỉ company `approved` xuất hiện (test với pending company) |
| AC8 | Cùng query gọi 2 lần → lần 2 phải hit cache (verify qua log hoặc timing) |

## Related Domain UCs
- [DUC-COMPANY-SHOW-PUBLIC](./show-public.md)

## References
- API: `GET /api/v1/public/companies`
- Controller: `Api\V1\Public\CompanyController@index`
- Resource: `Resources\V1\Public\CompanyResource`
- Service: `App\Services\CompanySearchService` (mới, extract từ logic Blade hiện tại)
