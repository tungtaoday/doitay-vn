---
id: DUC-AUTH-REGISTER
name: "Register User"
version: "1.0"
date: 2026-04-11
status: drafted
finished_at: null
---

# DUC-AUTH-REGISTER: Register User

## Brief Description

Tạo tài khoản end-user mới (không phải admin, không phải company owner trực tiếp). Sau khi tạo, cấp PAT để auto-login.

## Actors
- **Public visitor** (primary)
- **AuthController**, **AuthService**

## Preconditions
1. Email chưa tồn tại trong `users`.
2. Request `Content-Type: application/json`.

## Postconditions
### Success
1. User row mới với `status = active` (hoặc `pending` nếu cần verify email — xem BR).
2. PAT được tạo.
3. (Optional) Email welcome được queue.

### Failure
1. Không tạo user.
2. Trả `422` validation hoặc `409` nếu email trùng.

## Main Success Flow

| Step | Component | Action |
|---|---|---|
| 1 | AuthController | `POST /api/v1/auth/register` với `{name, email, password, password_confirmation}` |
| 2 | RegisterRequest | Validate: name required, email unique, password min:8 + confirmed |
| 3 | AuthService | `User::create(['name','email','password'=>Hash::make(...), 'status'=>'active'])` |
| 4 | AuthService | `$user->createToken('frontend',['*'])` |
| 5 | AuthService | Dispatch `Welcome` mail (queued, không block) |
| 6 | AuthController | Return `{token, user}` |

## Exception Flows
- **EF1**: Email trùng → 422 validation (`email.unique:users`)
- **EF2**: Password không match confirmation → 422
- **EF3**: Rate limit `throttle:3,1` → 429

## Business Rules
| Rule | Description | Enforcement |
|---|---|---|
| BR-REG-1 | Password phải hash bằng bcrypt | Step 3 |
| BR-REG-2 | Default `role = user`, không phải `company_owner` (company tạo riêng qua flow khác) | Step 3 |
| BR-REG-3 | Email phải unique case-insensitive | Step 2 |

## Data Requirements

### Input
| Field | Type | Required | Validation |
|---|---|---|---|
| name | string | Yes | min:2, max:100 |
| email | string | Yes | email, unique:users |
| password | string | Yes | min:8, confirmed |
| password_confirmation | string | Yes | — |

### Output (201)
```json
{ "token": "1|...", "user": { "id": 123, "name": "...", "email": "...", "role": "user" } }
```

## Acceptance Criteria
| AC | Description |
|---|---|
| AC1 | Register thành công → 201, user trong DB, token dùng được |
| AC2 | Email trùng → 422 với errors.email |
| AC3 | Password yếu (<8) → 422 |
| AC4 | Password không match confirmation → 422 |
| AC5 | Welcome email được queue (kiểm tra qua `Mail::fake()`) |

## References
- API: `POST /api/v1/auth/register`
- Controller: `Api\V1\AuthController@register`
- Request: `Requests\V1\Auth\RegisterRequest`
