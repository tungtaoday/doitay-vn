# Backlog / Deferred Items — doitay.vn

Danh sách hạng mục đã quyết định HOÃN, kèm lý do & điều kiện kích hoạt lại (traceability).

---

## DEFERRED-001 — Migrate ThợTốt: Firebase → doitay.vn API

- **Trạng thái:** 🅿️ Hoãn (parked)
- **Ngày ghi nhận:** 2026-07-15
- **Truy vết:**
  - BREQ-SALE-CTV-ONBOARDING → FR-015, FR-016, BR-7
  - Design `architecture/designs/sale_ctv_onboarding_multi_surface.md` → §4.4 Multi-Surface Strategy & ThợTốt Migration
- **Nội dung hoãn:** Chuyển ThợTốt (Zalo Mini App) từ đọc/ghi Firebase sang đọc hồ sơ thợ qua API public của doitay.vn.
- **Lý do hoãn:** ThợTốt đang trong quá trình xin duyệt lại Zalo (đồng nhất brand Doitay + gỡ cụm từ đăng nhập). Đổi tầng dữ liệu ngay bây giờ = build + deploy phiên bản mới = **reset quá trình review đang chạy**.
- **Điều kiện kích hoạt lại:** SAU KHI phiên bản ThợTốt hiện tại **được Zalo DUYỆT**.
- **Khi làm (checklist):**
  - Đổi `Tool_cv/src/services/firebase.ts` → client gọi `GET /api/v1/public/tho/{id}`.
  - Map field ThợTốt ↔ doitay (bảng ở design §4.4).
  - Cập nhật khai báo domain trên Zalo Console: **bỏ** các domain Firebase, **giữ** `doitay.vn`.
  - Cập nhật webhook `zalo/webhook-delete-data` để xoá bản ghi trên doitay thay vì Firebase.
- **Tác động tới duyệt Zalo:** Tích cực (ít domain ngoài hơn → giảm rủi ro app trắng). Nhưng **phải làm SAU khi qua duyệt**, không chen ngang phiên bản đang review.
