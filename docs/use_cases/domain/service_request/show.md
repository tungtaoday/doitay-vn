---
id: DUC-SERVICE-REQUEST-SHOW
name: "Show Service Request + Matches"
version: "1.0"
date: 2026-04-13
status: drafted
finished_at: null
---

# DUC-SERVICE-REQUEST-SHOW

## Brief

Customer xem lại 1 service request họ đã tạo + danh sách thợ matched. Dùng để render page `/yeu-cau/ket-qua/{id}`. Matching engine có thể chạy lại khi request (stale results nếu thợ mới đăng ký sau khi create).

## Actors
- Authenticated customer (owner của request)
- ServiceRequestController

## Preconditions
1. `auth:sanctum` + profile complete.
2. Request thuộc về user hiện tại.
3. Request chưa expired HOẶC đã expired nhưng < 30 ngày.

## Main Flow

| Step | Component | Action |
|---|---|---|
| 1 | Client | GET `/api/v1/user/service-requests/{id}` |
| 2 | Controller | `ServiceRequest::where('user_id', auth)->findOrFail($id)` |
| 3 | Service | `matchCompanies($request)` — re-run scoring (không cache) |
| 4 | Controller | Return `ServiceRequestResource` + `matches` |

## Business Rules
- BR-SS-1: Owner-only — không user khác xem được.
- BR-SS-2: Rematch luôn live (không lưu snapshot) để phản ánh thợ mới.
- BR-SS-3: Nếu `status != open` → vẫn trả matches nhưng flag `is_editable=false` ở FE.

## Acceptance Criteria
| AC | Description |
|---|---|
| AC1 | Owner GET → 200 với data + matches |
| AC2 | User khác GET → 404 (không 403 để không leak existence) |
| AC3 | Request expired → vẫn 200 nhưng `status=expired` |
| AC4 | Không đăng nhập → 401 |

## References
- API: `GET /api/v1/user/service-requests/{id}`
- Controller: `ServiceRequestController@show`
