# Thiết kế build — Trang SEO nghề × khu vực (doitay.vn)

> Bản thiết kế kỹ thuật để triển khai động cơ SEO trong `seo-strategy.md`.
> Đủ chi tiết để "bắt tay build" là chạy. Điều kiện tiên quyết: xử lý seed data (A4).
> Ngăn xếp: Next.js 15 App Router (RSC), Laravel API. Quy ước: `frontend/CLAUDE.md`.

---

## 0. Phạm vi

Sinh trang landing cho từng cặp **nghề × khu vực có cung thợ thật**, tối ưu truy vấn
local ("thợ điện cầu giấy"). 3 loại route + hub + sitemap + structured data + noindex-gate.

**KHÔNG** sinh 10 nghề × 700 khu vực rỗng. Chỉ cặp có ≥ `MIN_THO = 3` thợ approved.

---

## 1. URL & Routing (Next.js App Router)

```
src/app/dich-vu/
├── page.tsx                       → HUB: liệt kê nghề × khu vực có cung (internal link)
├── [nghe]/
│   ├── page.tsx                   → nghề toàn quốc: "Thợ điện tại Việt Nam" (gom khu vực)
│   └── [khuvuc]/
│       └── page.tsx               → ⭐ TRANG CHÍNH: "Thợ điện tại Cầu Giấy"
```

URL kết quả: `/dich-vu`, `/dich-vu/tho-dien`, `/dich-vu/tho-dien/cau-giay`.
Slug không dấu, keyword-rich. `generateStaticParams` prerender các cặp có cung (ISR revalidate 24h).

---

## 2. Hệ slug & phân giải thực thể (chỗ KHÓ nhất)

DB lưu: `category_id` (số) + `category.name` ("Thợ Điện"), `company.district` (chuỗi "Quận Cầu Giấy").
API lọc: `GET /public/companies?category=<id>&district=<chuỗi khớp CHÍNH XÁC>`.

**File `src/lib/seo-slugs.ts`:**
```ts
// slugify tiếng Việt: "Thợ Điện" -> "tho-dien", "Quận Cầu Giấy" -> "cau-giay" (bỏ tiền tố Quận/Huyện)
export function slugifyVi(s: string): string  // bỏ dấu, đ→d, bỏ "Quận/Huyện/Thành phố", nối -
```

**Phân giải (2 chiều), build 1 lần khi render từ dữ liệu THẬT:**
- `nghe slug → {categoryId, categoryName}`: lấy từ `GET /public/categories`, map `slugifyVi(name) → {id,name}`.
- `khuvuc slug → districtDbString`: lấy từ **API service-areas (§4)** — trả về đúng chuỗi district
  như DB lưu, kèm slug đã tính. → tránh đoán "Quận Cầu Giấy" vs "Cầu Giấy" (dùng chuỗi DB thật).

⚠️ **Không tự chế chuỗi district** để query — luôn dùng chuỗi DB thật từ service-areas, vì
`where('district', ...)` khớp chính xác. (Data hiện sạch: "Quận X"/"Huyện Y", nhưng vẫn dùng nguồn thật.)

---

## 3. Backend cần thêm: endpoint liệt kê cặp có cung

**Mới:** `GET /api/v1/public/service-areas`
```
Trả: [{ category_id, category_name, district, count }]  — chỉ count >= MIN_THO
Query: SELECT category_id, district, COUNT(*) c FROM companies
       WHERE status=APPROVED AND district<>'' GROUP BY category_id, district HAVING c>=3
       (join categories lấy name)
Cache 1h. Dùng cho: generateStaticParams, sitemap, hub page, noindex-gate.
```
File: `PublicServiceAreaController@index` + route + (tuỳ chọn) `ServiceAreaResource`.
→ 1 nguồn sự thật cho "cặp nào đủ cung để index".

*(Không có endpoint này thì phải quét toàn bộ companies ở FE — chậm + không gate được sitemap.)*

---

## 4. Giải phẫu trang chính `/dich-vu/[nghe]/[khuvuc]`

RSC, `async` fetch. Các khối (trên → dưới):

| Khối | Nội dung | SEO |
|---|---|---|
| Breadcrumb | Trang chủ › Dịch vụ › Thợ điện › Cầu Giấy | JSON-LD BreadcrumbList |
| **H1** | "Thợ Điện tại Cầu Giấy, Hà Nội" | 1 H1 duy nhất, chứa nghề+khu vực |
| Intro độc nhất | 2 đoạn: giới thiệu nghề + cam kết doitay (từ `seo-content.ts`) | chống doorway |
| **Danh sách thợ THẬT** | Card thợ (tái dùng component /tho) — `?category=<id>&district=<str>` | JSON-LD ItemList |
| Bảng giá tham khảo | dịch vụ phổ biến của nghề (reuse `service-suggestions.ts`) + khoảng giá | nội dung giá trị |
| Khu vực lân cận | link sang quận kề cùng nghề | internal link |
| Nghề khác cùng quận | link sang nghề khác cùng khu vực | internal link |
| **FAQ** | 3-4 câu theo nghề (từ `seo-content.ts`) | JSON-LD FAQPage |
| CTA | "Tạo yêu cầu" / "Xem tất cả thợ" | chuyển đổi |

Nếu `count < MIN_THO` → vẫn render (cho người dùng) nhưng **metadata `robots: noindex`** + KHÔNG vào sitemap.

---

## 5. Metadata (generateMetadata)

```ts
export async function generateMetadata({ params }): Promise<Metadata> {
  const { nghe, khuvuc, count, cityName } = await resolve(params);
  const title = `${categoryName} tại ${districtLabel}, ${cityName} — Đặt lịch uy tín | Doitay`;
  const description = `Tìm ${categoryName.toLowerCase()} uy tín tại ${districtLabel}: ${count}+ thợ đã kiểm duyệt, báo giá minh bạch, đặt lịch nhanh trên Doitay.vn.`;
  return {
    title, description,
    alternates: { canonical: `/dich-vu/${nghe}/${khuvuc}` },
    robots: count >= MIN_THO ? undefined : { index: false, follow: true },
    openGraph: { title, description, url, type: 'website' },
  };
}
```
Title ≤ 60 ký tự lý tưởng; nếu dài, rút gọn `| Doitay`.

---

## 6. Structured data (JSON-LD) — 3 khối trên trang chính

1. **BreadcrumbList** — chuỗi điều hướng.
2. **ItemList** — danh sách thợ (mỗi item: position, url `/tho/<id>`, name).
3. **FAQPage** — câu hỏi/đáp theo nghề.
(Trang hồ sơ thợ bổ sung **LocalBusiness/Service** riêng — xem §9.)

Render qua `<script type="application/ld+json" dangerouslySetInnerHTML>` như `page.tsx` hiện tại.

---

## 7. Nội dung độc nhất (chống doorway) — `src/lib/seo-content.ts`

```ts
// Theo NGHỀ (không theo từng quận — quận lấy tính địa phương từ danh sách thợ thật)
export const NGHE_SEO: Record<string, {
  intro: string;          // 1 đoạn mô tả nghề (unique/nghề)
  commitments: string[];   // cam kết
  faq: { q: string; a: string }[];  // 3-4 câu, chèn biến {khuvuc}
}> = {
  'tho-dien': { intro: '...', faq: [{q:'Thợ điện {khuvuc} giá bao nhiêu?', a:'...'}, ...] },
  ...
};
```
Mỗi trang = intro-nghề (khác nhau theo nghề) + **danh sách thợ thật khác nhau theo quận** +
FAQ chèn tên quận. → đủ khác biệt để không bị coi là trang cửa (doorway).

---

## 8. Sitemap (`src/app/sitemap.ts` — mở rộng)

Thêm: fetch `/public/service-areas` → mỗi cặp `count>=MIN_THO` thành 1 entry
`/dich-vu/<nghe>/<khuvuc>` (priority 0.7, changeFrequency weekly) + entry `/dich-vu/<nghe>` +
`/dich-vu`. **Chỉ cặp đủ cung** (đồng bộ với noindex-gate — không submit trang noindex).

---

## 9. Việc phụ đi kèm

- **Trang hub `/dich-vu`**: liệt kê nghề × khu vực có cung (từ service-areas) → mạng internal link + để Google crawl khám phá.
- **LocalBusiness/Service schema** cho `/tho/[id]` (bổ sung JSON-LD: type Service/LocalBusiness, areaServed, provider).
- **Trang nghề toàn quốc `/dich-vu/[nghe]`**: gom các khu vực có nghề đó (link xuống từng quận).

---

## 10. Danh sách file (manifest)

**Frontend (mới):**
```
src/app/dich-vu/page.tsx                       # hub
src/app/dich-vu/[nghe]/page.tsx                # nghề toàn quốc
src/app/dich-vu/[nghe]/[khuvuc]/page.tsx       # ⭐ trang chính
src/lib/seo-slugs.ts                           # slugify + phân giải
src/lib/seo-content.ts                         # intro/FAQ theo nghề
src/lib/service-areas.ts                       # fetch + cache /public/service-areas
```
**Frontend (sửa):** `src/app/sitemap.ts` (thêm cặp nghề×khuvuc), tái dùng component card thợ.

**Backend (mới):**
```
core/app/Http/Controllers/API/V1/Public/ServiceAreaController.php
core/routes/api/v1_public.php   (thêm route service-areas)
```

---

## 11. Trình tự build (khi bắt tay)

1. **[A4] Xử lý seed data** — ẩn/xoá thợ giả (điều kiện tiên quyết).
2. Backend: `ServiceAreaController` + route → verify curl trả cặp có cung.
3. FE: `seo-slugs.ts` + `service-areas.ts` + `seo-content.ts` (nội dung 3 nghề đầu: điện/nước/điều hòa).
4. FE: route `[nghe]/[khuvuc]/page.tsx` (fetch thợ, render khối, metadata, JSON-LD, noindex-gate).
5. FE: hub `/dich-vu` + `[nghe]` + mở rộng sitemap.
6. LocalBusiness schema cho `/tho/[id]`.
7. Deploy → submit sitemap trong Google Search Console → theo dõi index.
8. Mở rộng nội dung `seo-content.ts` cho các nghề còn lại theo cung.

---

## 12. Rủi ro & biên

- **District không khớp chuỗi DB** → luôn query bằng chuỗi từ service-areas (không tự chế).
- **Trùng lặp nội dung** → intro/FAQ theo nghề + danh sách thợ thật + FAQ chèn quận.
- **Thin content** → noindex + loại khỏi sitemap khi < MIN_THO.
- **Seed giả lọt trang** → phải làm A4 trước (guardrail §9 strategy).
- **generateStaticParams quá nhiều** → chỉ params từ service-areas (đã gate cung) — không sinh tổ hợp rỗng.
- **Slug đụng nhau** (2 quận khác tỉnh cùng tên) → khoá theo cặp tỉnh trong slug nếu cần (`cau-giay-ha-noi`); hiện chưa cần (1 tỉnh).

## 13. Definition of Done
- `/dich-vu/tho-dien/<quận có cung>` render: H1 đúng, ≥3 thợ thật, intro+FAQ, 3 JSON-LD hợp lệ (test Rich Results).
- Cặp < 3 thợ → noindex + không trong sitemap.
- Sitemap chứa các cặp đủ cung; GSC nhận + bắt đầu index.
- Không trang nào liệt kê thợ seed giả (sau A4).
