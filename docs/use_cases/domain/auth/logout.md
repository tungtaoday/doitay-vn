---
id: DUC-AUTH-LOGOUT
name: "Logout User"
version: "1.0"
date: 2026-04-11
status: drafted
finished_at: null
---

# DUC-AUTH-LOGOUT: Logout User

## Brief Description

Revoke PAT hiện tại của user. Frontend Next.js sẽ xoá httpOnly cookie tương ứng.

## Actors
- **Authenticated user** (primary)
- **AuthController**

## Preconditions
1. Request có Bearer token hợp lệ.

## Postconditions
### Success
1. PAT bị xoá khỏi `personal_access_tokens`.
2. 204 No Content.

### Failure
1. 401 nếu chưa auth.

## Main Success Flow

| Step | Component | Action |
|---|---|---|
| 1 | Sanctum | Verify token |
| 2 | AuthController@logout | `$request->user()->currentAccessToken()->delete()` |
| 3 | AuthController | Return 204 |

## Business Rules
| Rule | Description |
|---|---|
| BR-LOGOUT-1 | Chỉ xoá PAT hiện tại, KHÔNG xoá tất cả token của user (user có thể login từ thiết bị khác) |
| BR-LOGOUT-2 | Endpoint phải behind `auth:sanctum` middleware |

## Acceptance Criteria
| AC | Description |
|---|---|
| AC1 | Logout thành công → 204, token bị xoá khỏi DB |
| AC2 | Gọi lại `/auth/me` với token đã logout → 401 |
| AC3 | Token khác của cùng user vẫn hoạt động sau logout |
| AC4 | Không có token → 401 |

## References
- API: `POST /api/v1/auth/logout`
- Controller: `Api\V1\AuthController@logout`
