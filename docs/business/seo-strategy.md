# Chiến thuật SEO doitay.vn (Google)

> Mục tiêu: biến Google Search thành **kênh cầu CAC ≈ 0** (mục C1 việc-trọng-yếu).
> Nguyên tắc: SEO của doitay là **local + transactional** — thắng ở đúng khoảnh khắc
> khách cần thợ. Gắn chặt với chiến lược tần suất thấp (`chien-luoc-giu-tho-monetize.md`)
> và atomic network (bão hoà cục bộ từng quận).

---

## 1. Bản chất SEO của doitay

Khách **không lướt** doitay hằng ngày (tần suất thấp). Họ **tìm Google lúc phát sinh nhu cầu**:
"thợ điện Cầu Giấy", "sửa điều hòa Hà Đông giá rẻ", "thợ nước gần đây". Đây là truy vấn
**địa phương + ý định giao dịch cao** → thắng những truy vấn này = có khách CAC ≈ 0.

→ **SEO là kênh cầu QUAN TRỌNG NHẤT** của doitay (hơn cả ads, vì ads không hoàn vốn ở tần suất thấp).

## 2. Bản đồ từ khoá (4 nhóm intent)

| Nhóm | Ví dụ | Trang đích |
|---|---|---|
| **Local giao dịch** (chính) | `thợ điện cầu giấy`, `sửa điều hòa thanh xuân`, `thông tắc cống đống đa` | Trang **nghề × khu vực** |
| **Near-me / mobile** | `thợ điện gần đây`, `thợ nước gần nhất` | nghề × khu vực (theo vị trí) |
| **Thợ cụ thể (long-tail)** | tên thợ + nghề | Trang hồ sơ thợ `/tho/<id>` |
| **Thông tin (top-funnel)** | `giá sửa điều hòa 2026`, `cách chọn thợ điện uy tín` | Blog/cẩm nang |

Từ khoá thương hiệu (`doitay`, `doitay.vn`) → tự nhiên có khi thương hiệu lớn.

## 3. Kiến trúc site — 4 loại trang

```
1. Trang chủ /                         → thương hiệu + điều hướng
2. ⭐ Trang NGHỀ × KHU VỰC             → /dich-vu/<nghe>/<khu-vuc>  (ĐỘNG CƠ SEO CHÍNH)
   vd: /dich-vu/tho-dien/cau-giay
3. Trang hồ sơ thợ /tho/<id>/<slug>    → long-tail + topical authority (đã có)
4. Blog/cẩm nang /cam-nang/<bai>       → informational funnel (chưa có)
```

**URL nghề × khu vực:** `/dich-vu/{nghe-slug}/{khuvuc-slug}` — không dấu, keyword-rich,
phân cấp rõ. Vd `/dich-vu/tho-dien/cau-giay`, `/dich-vu/sua-dieu-hoa/ha-dong`.

## 4. ⭐ Động cơ chính: trang NGHỀ × KHU VỰC (programmatic SEO)

Đây là mảnh thiếu lớn nhất hiện tại. Mỗi trang nhắm 1 truy vấn local, gồm:

- **H1 + title** đúng truy vấn: "Thợ Điện tại Cầu Giấy, Hà Nội — Đặt lịch uy tín | Doitay"
- **Danh sách thợ THẬT** của nghề đó ở khu vực đó (API đã lọc được `category` + `district`)
- **Bảng giá tham khảo** dịch vụ phổ biến của nghề
- **Nội dung địa phương độc nhất** (2-3 đoạn: vì sao chọn qua doitay, cam kết) — tránh doorway
- **FAQ** (schema FAQPage): "Thợ điện Cầu Giấy giá bao nhiêu?", "Có làm cuối tuần không?"
- **Structured data**: BreadcrumbList + ItemList (danh sách thợ) + Service
- **Internal links**: sang nghề khác cùng quận + cùng nghề quận lân cận

**Quy mô có kiểm soát:** 10 nghề × (quận có thợ). KHÔNG sinh 10×700 trang rỗng (xem §9).

## 5. Technical SEO — đã có gì / thêm gì

| Hạng mục | Trạng thái |
|---|---|
| SSR nhanh (Next.js) + mobile-first | ✅ |
| robots.txt + sitemap.xml (222 URL) | ✅ có, cần **thêm trang nghề×khuvực vào sitemap** |
| Canonical (URL parity /tho/id/slug) | ✅ |
| Structured data Organization+WebSite (home), Company (hồ sơ) | ✅ một phần, **thêm LocalBusiness/Service + Breadcrumb + FAQ** |
| Title/description tối ưu local | ⚠️ cần cho trang nghề×khuvực |
| Trang nghề × khu vực | ❌ **CHƯA CÓ — việc chính** |
| Sơ đồ internal link | ⚠️ yếu, cần mạng liên kết nghề↔khu vực |
| Core Web Vitals | ✅ tốt (đã tối ưu ảnh, SSR) |

## 6. Content / cẩm nang (top-funnel)

Blog nhắm truy vấn thông tin → kéo khách sớm + nuôi internal link về trang dịch vụ:
- "Giá sửa điều hòa mới nhất 2026 (bảng chi tiết)"
- "5 dấu hiệu cần gọi thợ điện gấp"
- "Cách chọn thợ nước uy tín, tránh bị chặt chém"
Mỗi bài link nội bộ về trang nghề×khu vực liên quan. Làm dần, không gấp (ưu tiên §4 trước).

## 7. Off-page (việc của CHỦ — ngoài code)

- **Google Business Profile** cho doitay.vn (tên, địa chỉ Hộ KD, hotline 0972585990, danh mục). Local SEO + hiện trên Maps.
- **Citations**: đăng doitay lên các danh bạ dịch vụ VN (foody-style, trang vàng, nhóm review).
- **Backlink**: nhóm cư dân/FB khu vực, báo địa phương, đối tác VLXD.
- **Nhãn hiệu "doitay"** (việc A5) — giúp Google nhận diện thực thể thương hiệu.

## 8. Rollout GẮN với atomic network (không rải)

SEO đi theo cung thợ, không phủ trước:
```
Quận atomic (vd Cầu Giấy) × {điện, nước, điều hòa} có thợ thật
   → sinh + index 3 trang nghề×khuvực đó
   → cung dày thêm → mở thêm nghề / quận lân cận
```
Lý do: trang có thợ thật = nội dung giá trị, rank được. Trang rỗng = Google phạt (§9).
**SEO khởi động NGAY** (độ trễ 3-6 tháng) nhưng **chỉ index nơi có cung**.

## 9. GUARDRAILS (bắt buộc — nếu sai thì phản tác dụng)

1. **Thin content**: trang nghề×khuvực **< N thợ thật (vd < 3) → `noindex`** (vẫn tồn tại cho
   người dùng, nhưng không đẩy Google index). Chỉ đưa vào sitemap khi đủ thợ. Trang rỗng
   hàng loạt = Google coi là "doorway/thin" → tụt cả site.
2. **Seed data giả (R2/A4)**: 105 thợ seed rating giả — **KHÔNG được để trang SEO liệt kê
   thợ giả**. Phải xử lý seed (ẩn/xoá) TRƯỚC khi index trang nghề×khuvực, nếu không Google +
   khách thấy thợ ma = mất uy tín + rủi ro thuật toán.
3. **Nội dung độc nhất**: mỗi trang có đoạn mô tả + FAQ riêng, không chỉ đổi tên quận trong
   cùng template (doorway page bị phạt).
4. **Thời gian**: SEO ngấm 3-6 tháng. Không kỳ vọng traffic tuần đầu. Kiên nhẫn + đo GSC.

## 10. Đo lường

- **Google Search Console** (cài ngay): impressions, clicks, thứ hạng theo truy vấn nghề×quận.
- **Chỉ số bắc cầu**: số trang được index, thứ hạng "thợ <nghề> <quận>", CTR.
- **Cuối phễu**: % yêu cầu/khách đến từ organic (đo ở `/quan-tri` qua utm/referrer).

## 11. Roadmap 90 ngày

| Giai đoạn | Việc |
|---|---|
| **Tuần 1-2** | Xử lý seed data (A4) · dựng route trang nghề×khuvực + metadata + structured data + FAQ · noindex-gate · thêm vào sitemap · cài Google Search Console + submit sitemap |
| **Tuần 3-4** | Blog 3-5 bài cẩm nang trụ cột · mạng internal link · GBP (chủ) |
| **Tháng 2** | Mở rộng trang theo quận có cung (atomic) · citations/backlink (chủ) · theo dõi GSC |
| **Tháng 3** | Tối ưu theo dữ liệu GSC (trang nào có impression mà CTR thấp → sửa title) · nhân bản sang quận mới |

---

## Việc kỹ thuật tôi làm được ngay (chờ chốt)
1. **Route `/dich-vu/[nghe]/[khu-vuc]`** — SSR, lọc thợ theo category+district, metadata local,
   structured data (Breadcrumb+ItemList+FAQ), noindex khi < 3 thợ thật.
2. **Sitemap** thêm các trang nghề×khuvực đủ cung.
3. **Structured data** LocalBusiness/Service cho trang hồ sơ thợ.
4. Trang chỉ mục `/dich-vu` liệt kê nghề × khu vực (hub internal link).

⚠️ Điều kiện tiên quyết: **xử lý seed data (A4) trước khi index** — nếu không trang SEO
liệt kê thợ giả. Nên làm A4 + route SEO cùng đợt.
