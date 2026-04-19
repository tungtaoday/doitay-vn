# CLAUDE.md — doitay.vn rebuild

> Hướng dẫn cho Claude Code khi làm việc trong repo này. File này được Claude Code tự động load vào context khi mở thư mục `doitay-vn/`.

## 1. Bối cảnh dự án

doitay.vn là marketplace nhà thầu/dịch vụ Việt Nam (rating, lead matching, ví, subscription). Dự án này là **rebuild headless**: giữ nguyên backend Laravel, viết lại frontend bằng Next.js, cutover từng page một — KHÔNG big-bang.

- **Branch hiện tại**: `rebuild/headless-nextjs` (local-only, chưa có remote)
- **Nguồn gốc**: clone từ `github.com/tungtaoday/doitay.vn`, sau đó detach origin để push lên repo mới khi sẵn sàng
- **Workflow tham chiếu**: `C:\cypher_ai\claude` — luôn dùng các skill có sẵn (`analyze-requirements`, `breakdown-requirements`, `design-domain`, `implement-tests`, `implement-code`, `review`). KHÔNG tự bịa skill mới.

## 2. Layout repo (NON-STANDARD — đọc kỹ)

```
doitay-vn/
├── index.php              ← Laravel front controller (KHÔNG phải core/public/index.php!)
├── core/                  ← Laravel 11 app (PHP 8.3)
│   ├── app/
│   │   ├── Http/Controllers/API/V1/   ← API mới (uppercase API!)
│   │   ├── Http/Requests/V1/          ← FormRequest mới
│   │   ├── Http/Resources/V1/         ← JsonResource mới
│   │   ├── Services/                  ← Business logic shared (Blade + API)
│   │   ├── Exceptions/Api/            ← Domain exceptions
│   │   └── Models/                    ← 47 Eloquent models có sẵn
│   ├── routes/
│   │   ├── api.php                    ← Versioned /api/v1/* + legacy
│   │   ├── api/v1_public.php          ← Public endpoints
│   │   ├── api/v1_user.php            ← auth:sanctum
│   │   └── api/v1_admin.php           ← auth:sanctum + admin
│   ├── tests/Feature/Api/V1/          ← PHPUnit feature tests
│   ├── database/factories/            ← UserFactory, CategoryFactory, CompanyFactory
│   └── config/cors.php                ← FRONTEND_URL env, supports_credentials=true
├── frontend/              ← Next.js 15 + TS strict + Tailwind 3
│   ├── src/
│   │   ├── lib/api.ts                 ← ApiError class, fetch wrapper
│   │   ├── lib/auth.ts                ← httpOnly cookie token (cookies() từ next/headers)
│   │   ├── lib/api-types.ts           ← Hand-typed mirror các Resource shapes
│   │   └── app/
│   │       ├── (auth)/login/          ← Server action login
│   │       ├── cong-ty/page.tsx       ← RSC list
│   │       └── cong-ty/[id]/[[...rest]]/page.tsx  ← RSC detail + canonical redirect
│   └── tests/e2e/                     ← Playwright smoke
└── docs/                  ← BREQ + DUC + Domain models (DDD)
    ├── business/requirements/headless-migration.md   ← BREQ
    ├── use_cases/domain/{auth,company}/              ← DUCs
    ├── architecture/domains/                         ← Aggregate docs
    └── local-testing.md                              ← Status table + run commands
```

## 3. Chạy local (CHÍNH XÁC)

### Backend — XAMPP Apache (khuyến nghị)

Backend chạy qua XAMPP Apache VirtualHost tại **port 8080**, đã cấu hình sẵn:
- VirtualHost: `C:/xampp/apache/conf/extra/httpd-vhosts.conf`
- DocumentRoot: `C:/doitay_all_in_one/Strategy/doitay-vn` (nơi có `index.php` thật)
- Listen port: 8080 (thêm trong `C:/xampp/apache/conf/httpd.conf`)

```bash
# Bật Apache qua XAMPP Control Panel (nút Start), hoặc:
"C:/xampp/apache/bin/httpd.exe"
```
→ Backend: http://localhost:8080/api/v1/...

> **Tại sao không dùng `php -S`?** Apache multi-process xử lý concurrent requests (RSC gọi 2-3 API song song). `php -S` single-thread → chậm 3-5x. Xem benchmark ở §10.

> **Tại sao không dùng `php artisan serve`?** Layout non-standard: `core/public/` chỉ chứa assets, KHÔNG có `index.php`. Front controller thật là `doitay-vn/index.php` ở root, nó `require core/vendor/autoload.php` rồi `core/bootstrap/app.php`.

### Backend — php -S (fallback)

Nếu XAMPP không khả dụng, có thể dùng PHP built-in server (chậm hơn, single-thread):
```bash
cd C:\doitay_all_in_one\Strategy\doitay-vn
php -S 127.0.0.1:8000 -t . index.php
```
⚠️ Nếu dùng cách này, cần đổi `frontend/.env.local` về port 8000.

### Frontend — Terminal
```bash
cd C:\doitay_all_in_one\Strategy\doitay-vn\frontend
npm run dev
```
→ http://localhost:3000

### Laravel cache (đã bật, cần rebuild khi đổi config)

```bash
cd core
php artisan config:cache   # Rebuild sau khi đổi .env hoặc config/*.php
php artisan view:cache      # Rebuild sau khi đổi Blade views
# php artisan route:cache   # ❌ KHÔNG dùng — 103 duplicate route names trong legacy admin routes
```

> ⚠️ **Sau khi sửa `.env` hoặc `config/*.php`** phải chạy `php artisan config:cache` lại, vì config đang cached.

### PHP OPcache (đã bật)

OPcache đã enabled trong `C:/xampp/php83/php.ini` (256MB, 20k files, validate mỗi 2s). Nếu session mới thấy PHP chậm, kiểm tra `zend_extension=opcache` chưa bị comment lại.

### Test commands

| Command | Where | Purpose |
|---|---|---|
| `php -l <file>` | `core/` | Syntax lint trước khi commit |
| `php artisan route:list --path=api/v1` | `core/` | Verify routes register |
| `php artisan config:cache` | `core/` | Sau khi đổi `.env` hoặc config (⚠️ config đang cached!) |
| `npm run typecheck` | `frontend/` | `tsc --noEmit` strict |
| `npm run build` | `frontend/` | Verify production build pass |
| `npm run test:e2e` | `frontend/` | Playwright (cần backend chạy) |
| `php artisan test --filter=Api/V1` | `core/` | PHPUnit (⚠️ cần MySQL test DB riêng — xem §6) |

## 4. Quy ước code

### Backend (Laravel)

- **Namespace casing**: `App\Http\Controllers\API\V1\…` — uppercase `API` để khớp legacy `core/app/Http/Controllers/API/`. Mixed-case `Api` sẽ làm composer PSR-4 strict skip class → autoload fail.
- **Cấu trúc API namespace**: 3 sub-namespace song song với layout Controllers cũ:
  - `API\V1\Public\*` — không auth, public read surface (browseable, indexable)
  - `API\V1\User\*` — `auth:sanctum`, mutation của chính user
  - `API\V1\Admin\*` — `auth:sanctum + admin` middleware
- **Controller mỏng**: validate trong FormRequest, business logic trong Service, shape trong JsonResource. Controller chỉ là wiring.
- **Service layer là single source of truth**: cả Blade controller cũ và API controller mới phải call cùng service (`AuthService`, `CompanySearchService`, …) — nếu không, 2 frontend sẽ silently drift.
- **Resource KHÔNG BAO GIỜ expose**: `admin_feedback`, `password`, `remember_token`, `ver_code`, `kyc_*`, balance, internal notes. Test bằng `assertStringNotContainsString` trong feature test.
- **Exception → HTTP code**: `InvalidCredentialsException` → 401, `AccountInactiveException` → 403, `ModelNotFoundException` → 404 (auto). Đừng leak existence: pending/rejected company trả 404 giống không tồn tại.

### Frontend (Next.js 15)

- **Luôn fetch qua `src/lib/api.ts`** — không gọi `fetch()` trực tiếp trong page. `api()` xử lý baseURL, JSON parsing, throw `ApiError` với status code.
- **Token auth**: lưu trong **httpOnly cookie** qua `cookies()` của next/headers (`src/lib/auth.ts`). KHÔNG dùng localStorage. Cookie name: `doitay_token`, secure ở prod, sameSite lax.
- **Read = RSC, Mutation = Server Action**. Tránh client-side fetch trừ khi thực sự cần interactivity. Server action trả `{ ok: true } | { ok: false, error }` và parent component xử lý redirect.
- **Type safety**: api-types.ts hand-maintained mirror các Laravel Resource. Khi thêm/sửa Resource → update type cùng PR. Phase 2 sẽ chuyển sang OpenAPI generation (dedoc/scramble + openapi-typescript).
- **TypeScript strict**: typedRoutes ON. Không dùng `any`, không `@ts-expect-error` trừ khi có lý do được comment.

## 5. Schema gotchas (đã verify trên live DB)

Live DB `t_review_db` có những điểm khác với phỏng đoán từ URL/PHPDoc:

| Bảng | Cột thiếu | Hệ quả |
|---|---|---|
| `companies` | `slug` | URL legacy là `/companies/{id}/{vanity-slug}` (id-based, slug = SEO vanity). API resolve theo `id`, Resource trả `vanity_slug` derived từ `Str::slug($name)`. Frontend route `cong-ty/[id]/[[...rest]]` canonical-redirect vanity tail. |
| `companies` | `show_contact` | Hiện expose phone/email mặc định để parity với Blade. Flag opt-in deferred sang Phase 2. |

**Quy tắc bắt buộc**: trước khi viết code dựa trên giả định schema, **verify cột tồn tại**:
```bash
php -r "require 'core/vendor/autoload.php'; \$app = require 'core/bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); print_r(Schema::getColumnListing('table_name'));"
```
Đừng tin URL pattern, model PHPDoc, hay migration cũ.

## 6. PHPUnit — caveats

Tests có sẵn ở `core/tests/Feature/Api/V1/` dùng `RefreshDatabase`. **Chưa chạy được trên môi trường hiện tại** vì:

1. 60 migration trong `core/database/migrations/`, một số dùng MySQL-only features (FULLTEXT, ENUM) → sqlite path không an toàn
2. Không nên migrate vào DB prod `t_review_db`

**Cách chạy đúng** (khi cần):
```bash
mysql -uroot -e "CREATE DATABASE doitay_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
# Trong core/phpunit.xml thêm:
#   <env name="DB_DATABASE" value="doitay_test"/>
cd core
php artisan test --filter=Api/V1
```

## 7. Migration strategy (nguyên tắc bất di bất dịch)

- **KHÔNG xoá** routes Blade (`web.php`, `admin.php`, `user.php`) hay views/Blade controller cũ trong giai đoạn migration.
- **KHÔNG big-bang**. Cutover từng page theo traffic: bắt đầu từ public read (homepage, list, detail), rồi auth, rồi user dashboard, admin panel có thể giữ Blade vĩnh viễn.
- **URL parity**: giữ cấu trúc URL tiếng Việt (`/cong-ty/{id}/{slug}`) cho SEO continuity.
- Mỗi slice mới phải có DUC trong `docs/use_cases/`, test trong `core/tests/Feature/Api/V1/`, và update `docs/local-testing.md`.

## 8. Git rules cho repo này

- **Branch chính cho rebuild**: `rebuild/headless-nextjs`
- **KHÔNG có remote `origin`** — đã detach. KHÔNG `git remote add origin` về repo gốc `tungtaoday/doitay.vn.git`.
- Khi user nói "push", luôn xác nhận remote nào trước khi `git push`.
- Stage explicit (`git add core/path frontend/path docs`) — KHÔNG `git add -A` vì repo có nhiều file untracked legacy ở `core/` (`clean_and_recreate_data.php`, `production-cleanup.bat`, …) không thuộc Phase 1.
- Commit message theo style hiện tại của repo: `[feat] ...`, `[fix] ...`, mô tả nhiều dòng OK.
- Bao giờ cũng tạo commit MỚI, không amend.

## 9. Khi thêm endpoint API mới — checklist

1. Viết DUC trong `docs/use_cases/domain/<aggregate>/<verb>.md` (id, version, AC, BR, JSON sample)
2. Viết PHPUnit feature test trong `core/tests/Feature/Api/V1/<Namespace>/<Aggregate>Test.php` (RED)
3. Tạo FormRequest trong `core/app/Http/Requests/V1/<Namespace>/`
4. Tạo/cập nhật Service trong `core/app/Services/` (single source of truth)
5. Tạo JsonResource trong `core/app/Http/Resources/V1/<Namespace>/`
6. Tạo Controller mỏng trong `core/app/Http/Controllers/API/V1/<Namespace>/`
7. Đăng ký route trong `core/routes/api/v1_<namespace>.php`
8. Verify: `php artisan route:list --path=api/v1` + smoke `curl`
9. Update FE type ở `frontend/src/lib/api-types.ts` cùng PR
10. Update `docs/local-testing.md` status table nếu thêm test

## 10. Dev performance — đã optimize, known remaining issues

### Đã fix (2026-04-16)

| Optimization | Chi tiết | Kết quả |
|---|---|---|
| XAMPP Apache thay `php -S` | VirtualHost :8080, multi-process | Concurrent 3.5s → 0.4s (~8x) |
| OPcache ON | `php.ini`: 256MB, 20k files, revalidate 2s | Single req ~550ms → ~300ms |
| `config:cache` + `view:cache` | Bỏ parse config/view mỗi request | ~50ms saved/req |
| `APP_DEBUG=false` | Xóa duplicate `APP_DEBUG=true` trong `.env` | Bỏ debug overhead |
| `DB_HOST=127.0.0.1` | Tránh DNS resolve `localhost` trên Windows | ~50ms saved/req |
| `unstable_cache` cho public data | `site-settings` + `categories` revalidate 5 min | Bỏ 1-2 API calls/navigation |
| `loading.tsx` skeletons | `/cong-ty`, `/vi`, `/yeu-cau` | Perceived instant feedback |

### Còn lại (P2)

1. **`next dev` compile on-demand** — lần đầu visit route luôn chậm. Verify bằng `npm run build && npm start`, nếu nhanh hẳn thì đúng là do dev mode.
2. **Route cache bất khả thi** — 103 duplicate route names trong legacy admin/user routes (GET + POST cùng name). `php artisan route:cache` fail. Fix tất cả quá rủi ro cho Blade views. Chấp nhận ~50ms overhead mỗi request.
3. **Thêm `loading.tsx`** ở sub-routes: `vi/lich-hen/`, `vi/tho/`, `cong-ty/[id]/`.
4. **`unstable_cache` cho cities/locations** — endpoint được gọi ở `location-picker.tsx` (client-side fetch, không dùng `unstable_cache` được) và `tho/dang-ky/page.tsx` (inline `api()` call).

## 11. Tài liệu tham chiếu

- `docs/design-system.md` — **Design System "The Digital Craftsman"** — BẮT BUỘC tuân thủ khi chỉnh sửa bất kỳ frontend nào. Xem tóm tắt ở `frontend/CLAUDE.md` §Design System
- `docs/business/requirements/headless-migration.md` — BREQ chính
- `docs/use_cases/domain/` — tất cả DUC hiện tại
- `docs/architecture/domains/` — Aggregate docs (Auth, Company)
- `docs/local-testing.md` — status table + run commands chi tiết
- `C:\cypher_ai\claude\skills\` — workflow skills bắt buộc dùng

---

**Khi không chắc**: hỏi user trước khi tạo file mới, tránh đoán schema, đừng touch code trong `core/` mà không liên quan tới slice đang làm.
