---
id: DUC-SERVICE-REQUEST-CREATE
name: "Create Service Request"
version: "1.0"
date: 2026-04-13
status: drafted
finished_at: null
---

# DUC-SERVICE-REQUEST-CREATE: Create Service Request (broadcast + match)

## Brief Description

Khách hàng đăng 1 yêu cầu dịch vụ công khai: chọn category, địa điểm, mô tả công việc, thời gian mong muốn, ngân sách. Backend tạo `ServiceRequest` và trả ngay `request_id` + preview top 5 thợ matched để customer xem kết quả. Đây là luồng **broadcast-with-matching-engine** song song với luồng direct booking `/cong-ty/{id}`.

## Actors
- **Authenticated customer** (có `auth:sanctum` token, profile đã complete)
- **ServiceRequestController** (API\V1\User)
- **ServiceRequestService** (domain logic + matching engine)

## Preconditions
1. User đã đăng nhập (`auth:sanctum`).
2. User đã complete profile (middleware `api.profile`).
3. `category_id` tồn tại và active.
4. Location (`city` bắt buộc, `district` optional) hợp lệ.

## Postconditions
### Success
1. Bản ghi `service_requests` được tạo, `status = open`, `expires_at = now + 14 ngày`.
2. Matching engine chạy synchronously, tính score cho mọi company thoả pre-filter, trả top N.
3. Response chứa `request_id` + `matches` (array công ty đã rank).
4. Không trừ tiền ai cả (thợ không phải trả phí lúc matching — chỉ trả khi customer confirm appointment như luồng hiện tại).

### Failure
1. 401 nếu chưa đăng nhập.
2. 403 nếu profile chưa complete.
3. 422 nếu thiếu/sai field.
4. 429 nếu customer spam (throttle `10,1`).

## Main Success Flow

| Step | Component | Action |
|---|---|---|
| 1 | Client | POST `/api/v1/user/service-requests` với payload đầy đủ |
| 2 | CreateServiceRequestRequest | Validate: category_id exists, city exists (text match vietnam_cities), dates, budget range, images count ≤ 5 |
| 3 | Controller | `$service->create($user, $data)` |
| 4 | Service | `DB::transaction` — `ServiceRequest::create([...])` với snapshot input |
| 5 | Service | Gọi `matchCompanies($request)` — query `Company::approved()` pre-filter by category_id + city, score, sort, take top 5 |
| 6 | Service | Return request model + matched collection |
| 7 | Controller | Response 201 với `ServiceRequestResource` (gồm `matches` collection) |

## Exception Flows
- **EF1**: `category_id` không tồn tại → 422
- **EF2**: `budget_max < budget_min` → 422
- **EF3**: `preferred_date` < today → 422
- **EF4**: `images` upload > 5 file hoặc > 3MB mỗi file → 422
- **EF5**: Customer có > 5 request `open` chưa đóng trong 24h → 429 "Bạn đang có quá nhiều yêu cầu mở"

## Business Rules

| Rule | Description | Enforcement |
|---|---|---|
| BR-SR-1 | Chỉ customer đã profile-complete mới được tạo request | Middleware `api.profile` |
| BR-SR-2 | Mỗi request hết hạn sau 14 ngày nếu không được convert thành appointment | Step 4 (`expires_at`) + scheduler job mark `expired` |
| BR-SR-3 | Pre-filter bắt buộc: `category_id` match + company `status=approved` | Service step 5 |
| BR-SR-4 | Score weight: `category_exact=40` + `location_city=20` + `location_district=15` + `avg_rating * 5` (tối đa 25) + `completed_count` normalized tới 20. Tổng max 120. | Service `scoreCompany()` |
| BR-SR-5 | Top N = 5 thợ. Nếu < 5 thợ pass pre-filter → trả hết | Step 5 |
| BR-SR-6 | Thợ KHÔNG bị trừ tiền khi được match. Chỉ trừ khi customer đặt lịch chính thức (giữ nguyên luồng `customer_info_access` fee) | Không trigger WalletService ở đây |
| BR-SR-7 | Service request KHÔNG gắn sẵn với 1 company nào. `selected_company_id` = null cho tới khi customer pick | DB schema nullable |
| BR-SR-8 | Rate limit: 10 POST/phút (throttle:10,1) | Route middleware |

## Data Requirements

### Input (JSON body)
| Field | Type | Required | Validation |
|---|---|---|---|
| category_id | int | Yes | exists:categories,id |
| title | string | Yes | min:5, max:120 |
| description | string | Yes | min:20, max:2000 |
| city | string | Yes | max:100, present in `vietnam_cities` |
| district | string | No | max:100 |
| ward | string | No | max:100 |
| address | string | No | max:255 |
| budget_min | int | No | min:0 |
| budget_max | int | No | gte:budget_min |
| preferred_date | date | No | after_or_equal:today, before_or_equal:+60 days |
| preferred_time_slot | string | No | in: `morning,afternoon,evening,flexible` |
| contact_name | string | Yes | max:100 (prefill từ user) |
| contact_phone | string | Yes | regex phone VN |
| images | array | No | max:5 |
| images.* | file | No | image, max:3072 (KB) |

### Output (201)
```json
{
  "data": {
    "id": 42,
    "status": "open",
    "title": "Sửa điều hoà nhà 3 phòng",
    "description": "...",
    "category": { "id": 5, "name": "Điện lạnh" },
    "location": { "city": "Hà Nội", "district": "Cầu Giấy", "ward": null },
    "budget_min": 500000, "budget_max": 1500000,
    "preferred_date": "2026-04-20",
    "preferred_time_slot": "morning",
    "expires_at": "2026-04-27T14:00:00+07:00",
    "created_at": "2026-04-13T14:00:00+07:00",
    "matches": [
      {
        "company": {
          "id": 12, "vanity_slug": "dien-lanh-abc",
          "name": "Điện Lạnh ABC", "image": "...",
          "category": { "id": 5, "name": "Điện lạnh" },
          "avg_rating": 4.8, "total_completed": 87,
          "city": "Hà Nội", "district": "Cầu Giấy"
        },
        "score": 108,
        "reasons": ["category_match", "same_district", "rating_4_8"]
      }
    ]
  }
}
```

## Acceptance Criteria

| AC | Description |
|---|---|
| AC1 | POST với payload hợp lệ → 201, response có `data.id`, `status=open`, `matches` array |
| AC2 | Không đăng nhập → 401 |
| AC3 | Profile chưa complete → 403 |
| AC4 | `category_id` sai → 422 với field error |
| AC5 | `budget_max < budget_min` → 422 |
| AC6 | `matches` không rỗng nếu có thợ pass category + approved |
| AC7 | Công ty có district trùng xếp trên công ty khác district nhưng cùng city |
| AC8 | Công ty không match category KHÔNG xuất hiện |
| AC9 | Công ty `status != approved` KHÔNG xuất hiện |
| AC10 | `matches[].score` và `matches[].reasons` được trả về |
| AC11 | DB check: bản ghi `service_requests` được tạo đúng với `user_id = auth user` |
| AC12 | Spam > 10 POST/phút → 429 |

## Related Domain UCs
- [DUC-SERVICE-REQUEST-SHOW](./show.md) — xem lại request + rematch
- [DUC-APPOINTMENT-CREATE](../appointment/create.md) — convert khi customer pick thợ

## References
- API: `POST /api/v1/user/service-requests`
- Controller: `App\Http\Controllers\API\V1\User\ServiceRequestController@store`
- FormRequest: `App\Http\Requests\V1\User\CreateServiceRequestRequest`
- Resource: `App\Http\Resources\V1\User\ServiceRequestResource` (+ `MatchedCompanyResource`)
- Service: `App\Services\ServiceRequestService`
- Model: `App\Models\ServiceRequest`
- Migration: `database/migrations/2026_04_13_000000_create_service_requests_table.php`

## Non-goals (Phase 2)
- Thợ chủ động gửi quote cho request (sẽ là `ServiceQuote` overlay sau)
- Thông báo push cho thợ khi có request mới phù hợp
- Async matching via queue (MVP chạy sync trong request)
- Re-matching tự động khi thợ mới đăng ký
- Attachment storage trên S3 (MVP dùng public disk)
