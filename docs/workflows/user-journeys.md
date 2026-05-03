# User Journeys — doitay.vn

> Tài liệu này mô tả các workflow chính của hai vai trò: **Khách hàng (Customer)** và **Thợ (Contractor)**.  
> Cập nhật lần cuối: 2026-04-20. Cần sync khi thêm route hoặc đổi business logic.

---

## Mục lục

1. [Kiến trúc tổng quan](#1-kiến-trúc-tổng-quan)
2. [Workflow: Khách hàng](#2-workflow-khách-hàng)
3. [Workflow: Thợ (Contractor)](#3-workflow-thợ-contractor)
4. [Workflow chung (cả hai role)](#4-workflow-chung)
5. [Điểm giao tiếp giữa hai role](#5-điểm-giao-tiếp-giữa-hai-role)
6. [Trạng thái lịch hẹn](#6-trạng-thái-lịch-hẹn)
7. [Redirect logic & guards](#7-redirect-logic--guards)
8. [Vấn đề đã biết & TODO](#8-vấn-đề-đã-biết--todo)

---

## 1. Kiến trúc tổng quan

```
Public (không cần đăng nhập)       Protected (/vi/*)
────────────────────────────       ─────────────────────────────────────
/                                  /vi/ho-so           ← mọi user
/cong-ty                           /vi/hoan-thanh-ho-so ← onboarding
/cong-ty/[id]/[slug]               /vi/lich-hen         ← customer
/yeu-cau                           /vi/tho/dang-ky      ← pending contractor
/login                             /vi/tho/lich-hen     ← contractor
/dang-ky                           /vi/wallet           ← contractor
/quen-mat-khau                     /vi/nap-tien         ← contractor
/dat-lai-mat-khau                  /vi/thong-bao        ← mọi user
```

**Auth**: token Sanctum lưu trong httpOnly cookie `doitay_token`.  
**Fetch pattern**: Read = RSC (server component), Mutation = Server Action.

---

## 2. Workflow: Khách hàng

### 2.1 Đăng ký & hoàn thiện hồ sơ

```
/dang-ky  →  loginAction()  →  redirect tuỳ profile_complete:
                                ├─ profile_complete=false  →  /vi/hoan-thanh-ho-so
                                └─ profile_complete=true   →  /vi/lich-hen
```

**File**: `(auth)/dang-ky/`, `(auth)/login/actions.ts`

**Guard tại `/vi/ho-so`**: nếu `!profile_complete` → redirect `/vi/hoan-thanh-ho-so`.

**Dữ liệu cần nhập khi onboarding** (`/vi/hoan-thanh-ho-so`):
- Số điện thoại (bắt buộc)
- Tỉnh/thành, quận/huyện, phường/xã
- Địa chỉ cụ thể

Sau khi submit thành công → redirect `/vi/ho-so` (hoặc `/vi/tho/dang-ky` nếu `pending_role === 'contractor'`).

---

### 2.2 Tìm thợ & đặt lịch

```
/cong-ty  →  lọc (q, category, sort, page)
          →  click card  →  /cong-ty/[id]/[slug]  (canonical redirect nếu slug sai)
          →  AppointmentBookingForm:
               ├─ user=null           →  hiện nút "Đăng nhập để đặt lịch"
               ├─ !profile_complete   →  hiện nút "Hoàn thành hồ sơ trước"
               └─ ok                 →  form (step=form → step=confirm → step=success)
                                          └─ createAppointmentAction()
                                               ├─ ok   → hiện success + link /vi/lich-hen/{id}
                                               └─ fail → hiện lỗi, ở lại step=confirm
```

**Prefill từ yêu cầu**: nếu URL có `?from_request=123`, form tự điền thông tin từ ServiceRequest #123.

**Validation phía client** (trước khi qua step confirm):
- Tên người nhận không rỗng
- SĐT: 8–15 ký tự số/khoảng trắng/+/-
- Địa chỉ không rỗng
- Ngày hẹn: hôm nay → +30 ngày
- Giờ hẹn phải cách hiện tại ≥ 2 giờ

---

### 2.3 Quản lý lịch hẹn (Customer)

```
/vi/lich-hen           →  danh sách có phân trang (page query param)
/vi/lich-hen/[id]      →  chi tiết + actions
```

**Actions có thể thực hiện** (dựa vào `can_*` flags từ backend):

| Flag | Action | Điều kiện |
|------|--------|-----------|
| `can_cancel` | Huỷ lịch hẹn | Backend quyết định (thường khi status=pending) |
| `can_review` | Gửi đánh giá | status=completed && !has_rating |

**Cancel flow** (inline confirmation, không dùng `window.confirm`):
```
click "Huỷ lịch hẹn"  →  hiện inline confirm dialog
→ "Xác nhận huỷ"      →  cancelAppointment(id)  →  router.refresh()
→ "Không"             →  đóng dialog
```

**Review flow**:
- Load rating features từ `GET /public/categories/{catId}/features`
- Nếu không có features → chỉ cần nhập comment
- Submit: `submitReview(appointmentId, ratings, comment)`

---

### 2.4 Tạo yêu cầu dịch vụ

```
/yeu-cau  →  request-wizard.tsx (multi-step):
              step 1: chọn danh mục
              step 2: mô tả, ngân sách, ngày mong muốn
              step 3: địa chỉ (location-picker.tsx)
              step 4: thông tin liên hệ
           →  createServiceRequestAction()
           →  ok: redirect /yeu-cau/ket-qua/[id]
              └─ trang kết quả hiện danh sách thợ phù hợp + nút "Đặt lịch"
```

**Không cần đăng nhập** để tạo yêu cầu. Nếu chưa login, action trả `{ needsLogin: true }`.

---

## 3. Workflow: Thợ (Contractor)

### 3.1 Đăng ký làm thợ

```
/vi/tro-thanh-tho   ← landing page giới thiệu lợi ích
→ click CTA         → /vi/tho/dang-ky

Guard: nếu has_company → redirect /vi/tho/lich-hen (đã là thợ rồi)
```

**Form đăng ký thợ** (`/vi/tho/dang-ky`, fullscreen overlay):
- Tên công ty/thợ
- Email, SĐT
- Danh mục nghề (select từ API)
- Mô tả, kinh nghiệm (năm)
- Địa chỉ (city/district/ward)
- Bảng giá dịch vụ (dynamic rows: tên, giá, mô tả)
- Upload ảnh đại diện

Sau submit → backend tạo company với status chờ duyệt. Admin xét duyệt trong 24h.

---

### 3.2 Quản lý lịch hẹn (Contractor)

```
/vi/tho/lich-hen        →  danh sách + stats + filter by status
/vi/tho/lich-hen/[id]   →  chi tiết + actions
```

**Stats hiển thị trên đầu trang**:
- Số dư ví
- Đang chờ (số lượng + tổng phí)
- Đã xác nhận tháng này
- Hoàn thành tháng này

**Warning**: nếu `!can_afford_all && pending_count > 0` → hiện banner đỏ "Số dư không đủ".

**Actions** (inline confirmation, không dùng `window.confirm`):

| Flag | Action | Chi phí | Hiệu ứng |
|------|--------|---------|----------|
| `can_confirm` | Xác nhận | `confirm_fee` (≈50k) trừ ví | customer_info_unlocked → true |
| `can_complete` | Hoàn thành | Miễn phí | status → completed |
| `can_cancel` | Huỷ | Miễn phí | status → canceled |

**Thông tin khách hàng**:
- `customer_info_unlocked = false` → phone và address hiển thị "Chưa mở khoá"
- `customer_info_unlocked = true` → hiển thị đầy đủ
- Tên khách hàng luôn hiển thị (không phụ thuộc unlock)

---

### 3.3 Ví & Nạp tiền

```
/vi/wallet              →  tổng quan: số dư, ví theo công ty, giao dịch gần đây
/vi/wallet/giao-dich    →  lịch sử đầy đủ (có phân trang)
/vi/nap-tien            →  tạo yêu cầu nạp tiền
/vi/nap-tien/[id]       →  chi tiết yêu cầu nạp + upload proof
/vi/nap-tien/lich-su    →  lịch sử nạp tiền
```

**Luồng nạp tiền**:
```
/vi/nap-tien  →  chọn ví + phương thức + số tiền
→ createDeposit()  →  tạo DepositRequest (status=pending)
→ redirect /vi/nap-tien/[id]
→ user upload proof: uploadDepositProof(id, formData)
→ admin xác nhận → status=completed → số dư ví tăng
→ user có thể cancel nếu chưa processed: cancelDeposit(id)
```

**Guard**: nếu `wallets.length === 0` → hiện "Tạo công ty trước".  
**Guard**: nếu `methods.length === 0` → hiện "Chưa có phương thức thanh toán".

---

### 3.4 Sửa hồ sơ thợ

```
/vi/tho/sua-ho-so  →  company-edit-form.tsx
                   →  update info, ảnh, portfolio, bảng giá
```

Link "Sửa hồ sơ" cũng xuất hiện trên trang public `/cong-ty/[id]/[slug]` khi `isOwner === true`.

---

## 4. Workflow chung

### 4.1 Đăng nhập

```
/login  →  loginAction(email, password)
        →  ok: setToken → redirect tuỳ trạng thái:
             ├─ !profile_complete          → /vi/hoan-thanh-ho-so
             ├─ has_company                → /vi/tho/lich-hen
             └─ else                       → /vi/lich-hen
        →  fail: hiện error message
```

Hỗ trợ: Google OAuth, Facebook OAuth (qua `loginWithGoogle`, `loginWithFacebook`).  
Hỗ trợ `?next=` param để redirect sau khi login.

---

### 4.2 Hồ sơ cá nhân

```
/vi/ho-so  →  xem + sửa thông tin (form 2 phần tách biệt):
              ① Avatar form (standalone)  → uploadAvatarAction() → POST /user/avatar
              ② Profile form             → updateProfileAction() → PUT /user/profile
```

Hai form là **siblings** (không lồng nhau) để tránh lỗi HTML `<form>` trong `<form>`.

---

### 4.3 Thông báo

```
/vi/thong-bao   →  danh sách notifications (có mark as read)
Header bell     →  dropdown 5 thông báo gần nhất + unread count (polling 30s nếu tab active)
```

---

## 5. Điểm giao tiếp giữa hai role

### Đặt lịch (Customer → Contractor)

```
Customer đặt lịch  →  Appointment(status=pending) tạo trong DB
                   →  Contractor thấy trong /vi/tho/lich-hen
                   →  Contractor xác nhận → trừ confirm_fee từ ví
                                          → customer_info_unlocked=true
                                          → Customer thấy status="Đã xác nhận"
                   →  Contractor hoàn thành → status=completed
                                            → Customer có thể gửi đánh giá
```

### Liên hệ (chính sách ẩn thông tin)

| Thông tin | Customer xem profile thợ | Contractor xem chi tiết lịch hẹn |
|-----------|-------------------------|----------------------------------|
| SĐT thợ | **Ẩn** (chỉ chính chủ thấy) | N/A |
| Email thợ | **Ẩn** (chỉ chính chủ thấy) | N/A |
| Website thợ | Hiển thị | N/A |
| SĐT khách | N/A | **Ẩn** nếu chưa unlock |
| Địa chỉ khách | N/A | **Ẩn** nếu chưa unlock |
| Tên khách | N/A | Luôn hiển thị |

---

## 6. Trạng thái lịch hẹn

```
pending  →  confirmed  →  completed
   ↓             ↓
canceled      canceled
```

| Status | Customer thấy | Contractor thấy | Actions |
|--------|--------------|-----------------|---------|
| `pending` | "Chờ xác nhận" | "Chờ xác nhận" | C: huỷ (nếu can_cancel); T: xác nhận/huỷ |
| `confirmed` | "Đã xác nhận" | "Đã xác nhận" | T: hoàn thành/huỷ |
| `completed` | "Hoàn thành" | "Hoàn thành" | C: đánh giá (nếu chưa có) |
| `canceled` | "Đã huỷ" | "Đã huỷ" | — |

**Màu badge** (hiện tại dùng Tailwind raw colors — P2 chuyển sang design system tokens):
- pending → `bg-yellow-100 text-yellow-800`
- confirmed → `bg-blue-100 text-blue-800`
- completed → `bg-green-100 text-green-800`
- canceled → `bg-red-100 text-red-800`

---

## 7. Redirect logic & Guards

### Auth guard (`/vi/*`)
`vi/layout.tsx`: nếu không có token → redirect `/login?error=unauthenticated`.

### Profile guard
`vi/ho-so/page.tsx`: nếu `!profile_complete` → redirect `/vi/hoan-thanh-ho-so`.

### Contractor guard
`vi/tho/dang-ky/page.tsx`: nếu `has_company` → redirect `/vi/tho/lich-hen`.  
`vi/tro-thanh-tho/page.tsx`: nếu `has_company` → redirect `/vi/tho/lich-hen`.

### Onboarding redirect chain (sau login/register)
```
profile_complete=false                  → /vi/hoan-thanh-ho-so
profile_complete=true + pending=contractor/both → /vi/tho/dang-ky
profile_complete=true + has_company     → /vi/tho/lich-hen
profile_complete=true + else            → /vi/lich-hen
```

### URL canonicalisation (SEO)
`/cong-ty/[id]/[[...rest]]`: nếu `rest[0] !== company.vanity_slug` → redirect 307 sang URL chuẩn.

---

## 8. Vấn đề đã biết & TODO

### Đã fix (2026-04-20)

| # | Bug | File | Fix |
|---|-----|------|-----|
| B1 | Nút huỷ lịch hẹn (customer) check `status==='pending'` thay vì `can_cancel` | `vi/lich-hen/[id]/appointment-actions.tsx` | Thêm prop `canCancel`, dùng flag từ backend |
| B2 | `API_BASE` fallback hardcode port 8000 (backend chạy 8080) | `appointment-actions.tsx` | Sửa fallback → 8080 |
| B3 | Phone/address khách hàng hiện vô điều kiện dù chưa unlock | `vi/tho/lich-hen/[id]/page.tsx` | Wrap bằng `customer_info_unlocked` |
| B4 | `vi/wallet/page.tsx` throw uncaught nếu API fail | `vi/wallet/page.tsx` | Thêm try/catch, hiện error state |
| B5 | `vi/nap-tien/page.tsx` tương tự | `vi/nap-tien/page.tsx` | Tương tự |
| B6 | Avatar header `/vi/ho-so` dùng icon cũ, không nhất quán | `vi/ho-so/page.tsx` | Dùng `UserAvatar` component |
| B7 | `window.confirm()` trong ThoActions (block UI, không mobile-friendly) | `vi/tho/lich-hen/[id]/tho-actions.tsx` | Chuyển sang inline confirmation dialog |
| B8 | Nút cancel AppointmentActions dùng `window.confirm()` | `vi/lich-hen/[id]/appointment-actions.tsx` | Inline confirmation |
| B9 | Phone/email thợ hiện cho tất cả user (không phân biệt owner) | `cong-ty/[id]/page.tsx` | Chỉ hiện khi `isOwner === true` |

### P1 — Cần fix sớm

| # | Vấn đề | File |
|---|--------|------|
| P1-1 | Badge status màu dùng raw Tailwind (`bg-yellow-100`...) thay vì design system tokens | `vi/lich-hen/page.tsx`, `vi/tho/lich-hen/page.tsx` |
| P1-2 | Thiếu `loading.tsx` tại các sub-routes: `vi/ho-so`, `vi/lich-hen`, `vi/tho/lich-hen`, `vi/wallet`, `vi/nap-tien` — không có skeleton khi navigate | Tất cả sub-routes trên |
| P1-3 | Login redirect mất vanity slug: `?next=/cong-ty/${id}` thay vì `/cong-ty/${id}/${slug}` | `appointment-form.tsx:93` |

### P2 — Backlog

| # | Vấn đề | Ghi chú |
|---|--------|---------|
| P2-1 | Phân trang lịch sử giao dịch (`/vi/wallet/giao-dich`) dùng button số thuần, thiếu prev/next | |
| P2-2 | Không có empty state đẹp cho `/vi/thong-bao` khi không có thông báo | |
| P2-3 | `/yeu-cau/ket-qua/[id]` chưa có nút "Xem lại" hay edit request | |
| P2-4 | `/vi/tho/sua-ho-so` chưa kiểm tra redirect nếu company status bị suspended/rejected | |
| P2-5 | Review form load features bằng `fetch()` thẳng, không qua `api()` wrapper | `appointment-actions.tsx:81` |
| P2-6 | `revalidatePath` sau cancel/action chỉ revalidate list, không revalidate detail page | `vi/lich-hen/actions.ts`, `vi/tho/lich-hen/actions.ts` |
