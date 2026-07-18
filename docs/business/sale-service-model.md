# Sale & Service Model — Hệ thống KPI Cộng tác viên tuyển thợ

> Mô hình vận hành đội CTV đi tìm thợ cho doitay.vn + hệ KPI/hoa hồng + quy trình chăm sóc sau tuyển.
> **Tiền đề tài chính:** nền tảng KHÔNG tạo doanh thu ở giai đoạn này (monetize sau, cách khác) —
> mọi chỉ tiêu tối ưu cho **tăng trưởng cung thợ chất lượng với chi phí tiền mặt thấp nhất**.
> Số liệu neo vào hệ thống đang chạy: hoa hồng 30.000đ + thưởng chia sẻ 10.000đ (`config/sale.php`),
> tặng ví 200.000đ khi duyệt (`marketplace.welcome_credit` — tiền ảo, không tốn tiền mặt),
> công cụ: `/sale/nhap`, `/sale`, `/sale/duyet` (kèm hàng đợi vận hành).
> File tài chính đi kèm: `fm-model-doitay.xlsx` (cùng thư mục).

---

## 1. Phễu CTV — định nghĩa chuẩn (đơn vị đo của toàn hệ thống)

```
TIẾP CẬN → HỒ SƠ NHẬP → HỒ SƠ DUYỆT → THỢ KÍCH HOẠT → THỢ SỐNG
```

| Bậc phễu | Định nghĩa đo được (trong hệ thống) | Ai chịu trách nhiệm |
|---|---|---|
| Tiếp cận | Nói chuyện trực tiếp với 1 thợ (chợ VLXD, cửa hàng điện nước, công trình, nhóm Zalo) | CTV |
| Hồ sơ nhập | 1 submission trên `/sale/nhap` (tên + SĐT + nghề + giá + 3–5 ảnh việc thật) | CTV |
| Hồ sơ duyệt | Quản lý bấm duyệt trên `/sale/duyet` (qua checklist) → thợ lên chợ + ví 200k | Quản lý |
| Thợ kích hoạt | Thợ **xác nhận lịch hẹn đầu tiên** (confirm lead — dùng credit tặng) | CTV chăm + hệ thống notify |
| Thợ sống | Có ≥1 lịch hẹn confirmed trong 30 ngày gần nhất | Vận hành |

**Benchmark mục tiêu ban đầu** (chỉnh sau 4 tuần dữ liệu thật):
Tiếp cận→Nhập **30%** · Nhập→Duyệt **70%** · Duyệt→Kích hoạt **40%** · Kích hoạt→Sống **60%**.

---

## 2. KPI cho CTV

### 2.1 KPI tuần (theo dõi trên `/sale` — CTV tự thấy)

| KPI | Thử việc (tuần 1–2) | Chính thức | Xuất sắc |
|---|---|---|---|
| Hồ sơ nhập/tuần | ≥5 | ≥10 | ≥20 |
| Tỉ lệ duyệt (chất lượng) | ≥50% | ≥70% | ≥85% |
| Hồ sơ bị từ chối vì ảnh giả/SĐT sai | 0 | 0 | 0 |

### 2.2 KPI tháng (đánh giá + xếp bậc)

| KPI | Trọng số | Chỉ tiêu chính thức |
|---|---|---|
| Hồ sơ DUYỆT/tháng | 50% | ≥30 |
| Tỉ lệ kích hoạt thợ mình tuyển | 30% | ≥35% |
| Tỉ lệ duyệt | 20% | ≥70% |

- **Đạt <60% điểm 2 tháng liên tiếp** → dừng hợp tác.
- **Đạt ≥120% điểm 2 tháng liên tiếp** → đề bạt Trưởng nhóm (dẫn 3–5 CTV, hưởng override).

### 2.3 Vì sao đo "kích hoạt" chứ không dừng ở "duyệt"
Trả tiền chỉ theo hồ sơ duyệt sẽ sinh **hồ sơ đẹp nhưng thợ nguội** (gom danh bạ). Kích hoạt
(thợ tự bấm xác nhận lịch đầu tiên) chứng minh thợ THẬT + CÓ NHU CẦU — đây mới là tài sản.

---

## 3. Cơ chế hoa hồng (tiền mặt thật)

### 3.1 Hiện hành (đã chạy trong hệ thống)
| Khoản | Mức | Điều kiện |
|---|---|---|
| Hoa hồng cơ bản | **30.000đ** | Hồ sơ được duyệt (`SALE_COMMISSION_BASE`) |
| Thưởng chia sẻ | **10.000đ** | Thợ đồng ý chia sẻ hồ sơ lên mạng xã hội (`SALE_COMMISSION_SHARE_BONUS`) |

### 3.2 Đề xuất bổ sung (cần code thêm — ước lượng nhỏ)
| Khoản | Mức | Điều kiện | Mục đích |
|---|---|---|---|
| **Thưởng kích hoạt** | 20.000đ | Thợ do CTV tuyển xác nhận lịch hẹn ĐẦU TIÊN | Buộc CTV chọn thợ thật, chăm sau tuyển |
| Thưởng mốc tháng | 200.000đ | Đạt 30 hồ sơ duyệt/tháng | Giữ nhịp |
| Override trưởng nhóm | 5.000đ/hồ sơ duyệt của thành viên | Nhóm ≥3 CTV | Scale không cần quản lý thuê |

→ **Chi phí tiền mặt tối đa ≈ 60.000đ/thợ kích hoạt** (30+10+20) + thưởng mốc. Rẻ hơn mọi kênh ads.

### 3.3 Chống gian lận (đã có nền + bổ sung quy tắc)
- SĐT dedup tự động khi nhập (đã có trong `SubmissionService`).
- Chỉ trả khi **duyệt qua checklist** (ảnh thật, gọi thử SĐT — đã in trên `/sale/duyet`).
- Từ chối kèm lý do — CTV 3 lần bị từ chối vì gian dối → dừng vĩnh viễn.
- Thưởng kích hoạt chỉ tính lịch hẹn từ **khách khác SĐT thợ/CTV**.
- Đối soát + chi trả hoa hồng **1 lần/tuần** (chuyển khoản, có bảng kê từ `/sale` meta).

---

## 4. Service model — chăm thợ sau tuyển (vòng đời 30 ngày đầu)

| Ngày | Việc | Ai | Công cụ |
|---|---|---|---|
| D0 (duyệt) | Thợ nhận notify "đã duyệt + tặng 200k" (tự động) | Hệ thống | ✅ đã chạy |
| D0–D1 | CTV gọi/Zalo hướng dẫn thợ: xem hồ sơ, chờ tin khách, cách xác nhận lịch | CTV | Kịch bản trong salekit |
| D2–D7 | Nếu có lead mà thợ chưa confirm >4h → hiện đỏ trên hàng đợi → Quản lý/CTV gọi nhắc | QL + CTV | ✅ panel `/sale/duyet` |
| D7 | Chưa có lead nào → khuyến khích thợ chia sẻ thẻ QR cho khách quen (+10k share bonus cho CTV) | CTV | Mini App/link hồ sơ |
| D30 | Thợ chưa kích hoạt → CTV gọi "cứu" lần cuối; ghi lý do nguội (giá? khu vực? nghề?) | CTV | Form ghi chú |

**Phân vai gọn:** CTV = hunter + chăm 30 ngày đầu thợ mình tuyển · Quản lý = duyệt + đối soát + trực hàng đợi · Nền tảng = notify tự động + matching.

---

## 5. Nhịp vận hành tuần

| Thứ | Việc |
|---|---|
| Hàng ngày | Quản lý mở `/sale/duyet`: duyệt hồ sơ mới <24h; xử lý ô đỏ (lịch kẹt, yêu cầu mở) |
| Thứ 2 | Chốt số tuần trước theo CTV (hồ sơ nhập/duyệt/kích hoạt) — gửi bảng xếp hạng vào nhóm Zalo CTV |
| Thứ 6 | Đối soát + chuyển hoa hồng; công bố top CTV |
| Cuối tháng | Review KPI tháng: lên/xuống bậc; điều chỉnh benchmark phễu theo dữ liệu thật |

---

## 6. Ràng buộc từ mô hình "không doanh thu"

1. **Tặng ví 200k là TIỀN ẢO** — không tốn tiền mặt (phí lead 10k chỉ trừ credit ảo). Chi phí
   thật của tăng trưởng = **hoa hồng CTV + chi vận hành**. FM model chỉ tính tiền mặt.
2. Vì không thu tiền, **kỷ luật duy nhất chống lãng phí là KPI kích hoạt** — không trả thêm cho
   hồ sơ không sống.
3. Khi bắt đầu monetize (sau này), dữ liệu "thợ sống/khu vực/nghề" từ phễu này chính là tài sản
   định giá — vì vậy mọi CTV phải nhập ĐỦ nghề + khu vực chuẩn (đã bắt buộc trong form).

*Cập nhật lần đầu: 2026-07. File FM đi kèm: `fm-model-doitay.xlsx` — chỉnh giả định ở sheet "GiaDinh".*
