---
id: DUC-AUTH-ME
name: "Get Current User"
version: "1.0"
date: 2026-04-11
status: drafted
finished_at: null
---

# DUC-AUTH-ME: Get Current User

## Brief Description

Trả về thông tin user đang authenticated qua Sanctum PAT. Endpoint này được Next.js gọi mỗi page load có RSC để xác định trạng thái auth.

## Actors
- **Authenticated user** (primary)
- **Sanctum middleware**

## Preconditions
1. Request có header `Authorization: Bearer {token}`.
2. Token chưa bị revoke và user còn `active`.

## Postconditions
### Success
1. Trả về user (JsonResource) — không thay đổi state.

### Failure
1. 401 nếu thiếu/sai token.

## Main Success Flow

| Step | Component | Action |
|---|---|---|
| 1 | Sanctum middleware | Verify token, attach `Auth::user()` |
| 2 | AuthController@me | Return `new UserResource($request->user())` |

## Exception Flows
- **EF1**: Không có Bearer token → 401 (Sanctum tự handle)
- **EF2**: Token revoke → 401
- **EF3**: User bị soft-delete giữa các request → 401

## Business Rules
| Rule | Description |
|---|---|
| BR-ME-1 | UserResource KHÔNG bao giờ trả `password`, `remember_token`, `api_token` cũ |
| BR-ME-2 | Trả thêm `permissions` và `role` để Next.js render UI conditional |

## Data Requirements

### Output (200)
```json
{
  "data": {
    "id": 123,
    "name": "Nguyễn Văn A",
    "email": "a@example.com",
    "avatar": "https://...",
    "role": "user",
    "permissions": ["company.create", "rating.write"]
  }
}
```

## Acceptance Criteria
| AC | Description |
|---|---|
| AC1 | Token hợp lệ → 200 + user data |
| AC2 | Không có token → 401 |
| AC3 | Token sai/expired → 401 |
| AC4 | Response KHÔNG chứa password hay remember_token |

## References
- API: `GET /api/v1/auth/me`
- Controller: `Api\V1\AuthController@me`
- Resource: `Resources\V1\User\UserResource`
