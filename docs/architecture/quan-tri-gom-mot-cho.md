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
| Hằng ngày | **Lịch hẹn** (chốt xong việc · huỷ lịch chờ) | kéo từ admin Blade |
| Theo dõi | Điều hành · Khách hàng · Hiệu suất thợ · Điểm chạm | mới |
| Tiền | **Ví thợ & giao dịch** | kéo từ admin Blade |
| Tiền | **Cộng tác viên** (thêm · ngưng · đối soát hoa hồng) | mới |
| Dữ liệu | **Người dùng** (tìm · khoá · mở) | kéo từ admin Blade |
| Dữ liệu | **Đánh giá** (soi điểm thấp · xoá nội dung bậy) | kéo từ admin Blade |
| Dữ liệu | **Danh mục nghề** (thêm · sửa · ẩn hiện) | kéo từ admin Blade |
| Dữ liệu | Hồ sơ CTV nhập | đã có |
| Hệ thống | **Cài đặt** (tên site · nút Zalo · bảo trì) | kéo từ admin Blade |

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
4. **Lịch hẹn: admin không "xác nhận hộ thợ".** `AppointmentService::confirmByCompany`
   trừ phí lead trong ví thợ và có thể kích thưởng kích hoạt cho CTV — phải là hành
   động của chính thợ. Admin chỉ chốt hoàn thành / huỷ lịch đang chờ, và gọi lại
   đúng service với tài khoản chủ sở hữu để giữ nguyên thông báo hai đầu.
5. **Cài đặt chỉ mở whitelist** (`AdminOpsController::CAI_DAT_CHO_PHEP`).
   `general_settings` còn chứa `mail_config`, `sms_config`, `socialite_credentials`,
   `system_info` — bí mật hệ thống, API không bao giờ trả ra.
6. **Cột `confirmed_at` của `appointments` chỉ có trên DB production**, DB dev cũ chưa
   có. Đừng select nó trong truy vấn dùng chung.
7. **CTV là thực thể thật từ bảng `ctvs`**, không suy ra từ `tho_submissions` nữa.
   `SubmissionService::assertLaCtv` chặn người ngoài danh sách nộp hồ sơ; danh sách
   RỖNG thì bỏ qua kiểm tra để hệ đang chạy không gãy. Migration đã backfill mọi
   người từng nộp hồ sơ nên không ai đang làm bị mất quyền.
8. **Không tạo tài khoản hộ CTV** — mật khẩu phải do chính họ đặt. Thêm CTV là tìm
   theo SĐT của tài khoản đã có; chưa có thì bảo đăng ký trước.

## Chưa kéo (còn ở admin Blade)

Nội dung trang (Frontend) · mẫu email/thông báo · ticket hỗ trợ · ngôn ngữ ·
extension · quảng cáo · báo cáo giao dịch & lịch sử đăng nhập · cấu hình mail/SMS
(cố ý không kéo vì chứa khoá bí mật).

Kéo tiếp theo thứ tự có người dùng thật, không kéo cho đủ bộ.
