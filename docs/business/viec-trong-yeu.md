# Việc trọng yếu — doitay (cập nhật 2026-07-24)

> Rút từ bản đánh giá toàn luồng kinh doanh. Nguyên tắc: **chưa giải xong nhóm A thì
> chưa được tiêu tiền CTV / bơm cầu.** Chi tiết phân tích: `chien-luoc-giu-tho-monetize.md`.
>
> Trạng thái chuỗi: GIEO CUNG 🟢 → **KÍCH HOẠT 🔴 đứt** → **NHẬN LEAD 🔴 đứt** → GIAO DỊCH 🟡 → GIỮ CHÂN ⚪ → MONETIZE ⚪
> Hai mắt xích đứt cùng chờ một cửa: **Zalo** → phải có đường vòng, không để Zalo là điều kiện sống.

---

## A. NÚT THẮT SỐNG CÒN (làm trước tiên)

> ✅ 2026-07-25: Xong TẦNG 1 (Mini App xuất bản hồ sơ lên doitay) — endpoint
> `POST /public/tho-profiles` + Mini App publish khi đồng ý + QR/share/nút dùng URL
> thật. Sửa 3 điểm gãy hành trình. Còn: khai domain Zalo Console (xem Tool_cv/ZALO_CONSOLE_CONFIG.md).

| # | Việc | Ai | Trạng thái | Ghi chú |
|---|------|----|-----------|---------|
| A1 | **Gỡ duyệt Zalo Mini App** — phản hồi kiểm duyệt: "doanh nghiệp PHẦN MỀM (ĐKKD phần mềm + bán hàng hoá), không phải tiệm sửa chữa; sửa chữa dân dụng KHÔNG phải ngành có điều kiện → không tồn tại giấy phép con; đề nghị đổi danh mục về Công nghệ/Phần mềm" | **Chủ** | ☐ | Nút thắt số 1 của toàn mô hình (kích hoạt thợ + ZNS) |
| A2 | **Mua OTP** (chọn SMS brandname hoặc ZNS) + đưa credentials | **Chủ** | ☐ | Là ĐƯỜNG VÒNG khi Zalo chậm |
| A3 | **Đăng nhập bằng SĐT + OTP trên web** (sau A2) | Claude | ☐ | Thợ CTV nhập vào được tài khoản không cần Zalo → mở khoá mắt xích KÍCH HOẠT |
| A4 | **Xử lý seed data** — ẩn/xoá 105 thợ seed (rating giả) khỏi chợ public | Claude (chờ chủ chốt ẩn hay xoá) | ☐ | BẮT BUỘC trước khi có khách thật — khách đặt trúng thợ giả = mất niềm tin lần chạm đầu |
| A5 | **Nộp đơn nhãn hiệu "doitay"** — online Cục SHTT, ~1,5tr, dấu nhận đơn trong ngày | **Chủ** | ☐ | Bảo hiểm rẻ nhất cho thương hiệu định monetize; dùng luôn cho OA/Zalo sau này |
| A6 | **OA Zalo**: chờ duyệt bộ giấy tờ; nếu từ chối → đổi danh mục khớp ĐKKD, nộp GCN ĐKKD (ngành phần mềm) | **Chủ** | ☐ đang chờ | OA = hạ tầng ZNS báo lead |

## B. HẠ TẦNG & AN TOÀN (tuần này, rẻ)

| # | Việc | Ai | Trạng thái | Ghi chú |
|---|------|----|-----------|---------|
| B1 | **Dọn rotation backup server** — giữ 7 ngày gần nhất | Claude | ☐ | Đĩa server 82%, còn 1.8GB; backup ~150MB/ngày → ~10 ngày nữa đầy |
| B2 | Dọn đĩa máy dev (ổ C còn ~100MB) | **Chủ** | ☐ | Đầy đĩa làm build/deploy/Windows trục trặc |
| B3 | Backup DB+files hàng ngày 2h sáng → volume riêng | — | ✅ đã verify 24/07 | `t-review-backup.sh`, bản mới nhất cùng ngày |

## C. ĐỘNG CƠ CẦU DÀI HẠN (bắt đầu ngay vì độ trễ 3-6 tháng)

| # | Việc | Ai | Trạng thái | Ghi chú |
|---|------|----|-----------|---------|
| C1 | **Khung SEO nghề × quận** — trang landing "thợ điện Cầu Giấy"... sinh từ categories × districts | Claude | ☐ | Kênh cầu CAC≈0 đã chọn; mỗi tuần chưa làm là lùi lịch có cầu organic |

## D. SAU KHI A XONG — atomic network (30 ngày)

| # | Việc | Điều kiện mở khoá |
|---|------|-------------------|
| D1 | Chọn ô nguyên tử: **1 quận × 2-3 nghề** (điện, nước, điều hoà) | — |
| D2 | Pilot **1-2 CTV** đúng quận đó (chưa scale) — quy trình: nhập hồ sơ + **kích hoạt tại chỗ** + follow OA | Cần A3 hoặc Mini App duyệt |
| D3 | Bơm cầu nhỏ hyperlocal (nhóm cư dân, tờ rơi) — kiểm chứng thanh khoản trên `/quan-tri` | Cần A4 xong (chợ sạch) |
| D4 | Ngưỡng ô sống: ≥75% yêu cầu có thợ nhận ≤60p · ≥50% thành lịch · thợ có việc đều · cầu organic/referral tăng | — |
| D5 | Chưa đạt D4 → KHÔNG mở quận mới, KHÔNG thêm nghề | Kỷ luật tập trung |

## Quy tắc chặn (guardrails)

1. **Chưa có đường kích hoạt thợ (A3 hoặc Mini App) → không scale CTV** — chỉ pilot thử quy trình.
2. **Chợ chưa sạch seed (A4) → không bơm cầu.**
3. **Zalo là tối ưu hoá, không phải điều kiện sống** — mọi mắt xích phải có đường vòng (OTP, gọi tay, SMS).
4. Trợ giá (200k, hoa hồng) là mồi lửa — theo dõi trên `/quan-tri`, tắt được khi ô sống.

---

*Cập nhật trạng thái vào file này khi xong từng việc. Đánh giá rủi ro đầy đủ: xem lịch sử trao đổi 24/07 — R1 phụ thuộc Zalo · R2 seed giả · R3 CTV chạy khi kích hoạt đứt · R4 SEO trễ · R5 nhãn hiệu · R6 bus factor 1 · R7 đĩa đầy · R8 khách rò rỉ (đã chống bằng che SĐT + monetize phía thợ).*
