# Gom quản trị về một chỗ

> Quyết định và trạng thái. Cập nhật khi kéo thêm module.

## Vì sao không port cả 209 route

Admin Blade (`doitay.vn/admin`) có **209 route / 22 module** — phần lớn là hàng đi kèm
của script marketplace mua sẵn (ngôn ngữ, extension, email flow, quảng cáo, ticket,
mẫu thông báo…). Viết lại toàn bộ tốn hàng tháng và đổi lại gần như không có gì.

Đọc log Apache 15 ngày (`/var/log/apache2/doitay*access*.log*`, lọc GET /admin trả
200/302) thì thực tế dùng như sau:

| Màn | Lượt |
|---|---|
| `/admin/deposits/requests` | 50 |
| `/admin` (trang chủ admin) | 12 |
| `/admin/dashboard` · `/admin/company-wallets` · `/admin/category` | 1 mỗi cái |
| Toàn bộ phần còn lại | 0 |

⇒ Kéo về **đúng những thứ có người dùng**, phần còn lại để nguyên và vẫn giữ link
sang admin cũ. Không giấu đi — lúc cần mà không biết tìm ở đâu thì tệ hơn.

## Đã kéo về (`/quan-tri`, thanh bên trái)

| Nhóm | Màn | Nguồn |
|---|---|---|
| Hằng ngày | Việc hôm nay | mới |
| Hằng ngày | Hàng chờ duyệt (`/sale/duyet`) | đã có |
| Hằng ngày | **Lệnh nạp tiền** | kéo từ admin Blade |
| Theo dõi | Điều hành · Khách hàng · Hiệu suất thợ · Điểm chạm | mới |
| Tiền | **Ví thợ & giao dịch** | kéo từ admin Blade |
| Tiền | Hoa hồng CTV | đã có |
| Dữ liệu | **Người dùng** (tìm · khoá · mở) | kéo từ admin Blade |
| Dữ liệu | Hồ sơ CTV nhập | đã có |

API: `API\V1\Admin\AdminOpsController` (`/api/v1/admin/ops/*`), gate bằng
`config('sale.manager_user_ids')`.

## Nguyên tắc khi kéo tiếp

1. **Không viết lại logic tiền.** Duyệt lệnh nạp gọi thẳng
   `DepositRequest::approve()/reject()` — nơi đã có transaction, ghi
   `wallet_transactions` và bắn thông báo. Controller mới chỉ chặn xử lý lại
   (`status` phải là `pending|processing`) để không cộng ví hai lần.
2. **`processed_by` là khoá ngoại sang bảng `admins`**, mà người đăng nhập ở cổng
   mới là user thường ⇒ để `null`, ghi tên người thao tác vào `admin_notes`.
3. **Không kéo những thứ dễ gây hại mà hiếm dùng**: sửa hồ sơ người dùng, đăng nhập
   hộ, gửi thông báo hàng loạt. Cần thì vào admin cũ.

## Chưa kéo (còn ở admin Blade)

Danh mục nghề · đánh giá · lịch hẹn (đã thấy gián tiếp ở Việc hôm nay) · nội dung
trang · mẫu email/thông báo · ticket hỗ trợ · ngôn ngữ · extension · quảng cáo ·
cài đặt chung · báo cáo giao dịch/đăng nhập.

Kéo tiếp theo thứ tự có người dùng thật, không kéo cho đủ bộ.
