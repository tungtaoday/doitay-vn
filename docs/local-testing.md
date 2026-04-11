# Local Testing Guide — doitay.vn rebuild

> Cách chạy thử backend Laravel + frontend Next.js trên máy local trước khi push lên repo mới.

## ✅ Status snapshot — Phase 1 local validation (2026-04-11, green)

| Check | Status | Notes |
|---|---|---|
| `composer install` | ✅ pass | Vendor cài đầy đủ |
| PSR-4 autoload (new files) | ✅ pass | `App\Http\Controllers\API\V1\…` (uppercase `API` để khớp legacy dir) |
| `php -l` (controllers/services/tests) | ✅ pass | No syntax errors |
| `php artisan route:list --path=api/v1` | ✅ pass | 6 routes register: auth × 4, public/companies × 2 |
| HTTP kernel smoke (`/api/v1/public/companies`) against **live prod DB** (read-only) | ✅ pass | 200 + paginated envelope, returns dữ liệu thật |
| HTTP kernel smoke `/api/v1/public/companies/{id}` | ✅ pass | 200 với id hợp lệ, 404 với id không tồn tại |
| `Resource→array()` against live row | ✅ pass | `id, vanity_slug, name, image, category, location, rating_*, experience` |
| `npm install --legacy-peer-deps` | ✅ pass | 362 packages, ~1m. React 19 cần `--legacy-peer-deps`. |
| `npx tsc --noEmit` (TypeScript strict) | ✅ pass | 0 errors |
| `npx next build` | ✅ pass | 5 routes prerender ok (`/`, `/cong-ty`, `/cong-ty/[id]/[[...rest]]`, `/login`) |
| `npx playwright test` (chromium, 3 specs) | ✅ pass | 3/3 trong 26s — homepage, public company list từ live API, login reject sai password |
| PHPUnit feature tests (RefreshDatabase) | ⏸️ deferred | Cần MySQL test DB riêng `doitay_test` (60 migration, có FULLTEXT/ENUM MySQL-only). Code & factories đã sẵn. |

### Local server layout gotcha

doitay.vn dùng layout **non-standard**: front controller là `doitay-vn/index.php` (root level), KHÔNG phải `core/public/index.php`. `core/public/` chỉ chứa assets/css/js.

→ `php artisan serve` từ `core/` sẽ FAIL vì nó tìm `core/public/index.php`. Phải dùng:

```bash
cd C:\doitay_all_in_one\Strategy\doitay-vn
php -S 127.0.0.1:8000 -t . index.php
```

`index.php` ở root tự `require core/vendor/autoload.php` rồi `core/bootstrap/app.php`.

### Critical schema discovery

Live `companies` table (production XAMPP DB `t_review_db`) **KHÔNG có cột `slug`**. Legacy URL parity là `/companies/{id}/{vanity-slug}` (id-based, slug chỉ là vanity SEO).

Đã sửa toàn bộ slice để dùng id-based routing:
- `CompanySearchService::showPublicById($id)` (đổi từ `showPublicBySlug`)
- Route `/api/v1/public/companies/{id}` (whereNumber)
- `CompanyResource` / `CompanyDetailResource` trả `vanity_slug` derived từ `Str::slug($name)` thay vì `slug` column
- Frontend route `/cong-ty/[id]/[[...rest]]/page.tsx` — canonicalise vanity tail bằng redirect 307
- DUC-COMPANY-SHOW-PUBLIC v1.1 ghi nhận quyết định này

`show_contact` column cũng không có → expose contact mặc định cho parity với Blade hôm nay; flag opt-in được ghi nhận là Phase 2 trong DUC.

### Để unblock frontend

1. Dọn đĩa C: (cần ít nhất ~2 GB trống cho `node_modules` + Playwright browsers).
2. `cd doitay-vn/frontend && npm install --legacy-peer-deps` (React 19 + một số deps cần `--legacy-peer-deps`).
3. `npm run typecheck && npm run build` để verify TS strict + RSC build pass.
4. Boot backend trên `:8000`, frontend trên `:3000`, rồi `npm run test:e2e`.

## 1. Backend Laravel (`core/`)

### 1.1 Cài đặt lần đầu

```bash
cd doitay-vn/core
composer install
cp .env.example .env             # nếu chưa có .env, chép từ env.production.example
php artisan key:generate
```

Sửa `.env` cho local:
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doitay_local
DB_USERNAME=root
DB_PASSWORD=

# Frontend dev
FRONTEND_URL=http://localhost:3000,http://127.0.0.1:3000

SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000
SESSION_DOMAIN=null
```

Tạo DB và migrate:
```bash
mysql -uroot -e "CREATE DATABASE doitay_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
```

> ⚠️ Vì XAMPP đã có sẵn DB production ở local, **đừng dùng cùng DB**. Tạo `doitay_local` riêng.

### 1.2 Chạy dev server

> ⚠️ doitay.vn có layout non-standard: front controller là `doitay-vn/index.php` ở root, không phải `core/public/index.php`. KHÔNG dùng `php artisan serve` (sẽ báo "Failed opening required core/public/index.php"). Dùng PHP built-in server từ root:

```bash
cd C:\doitay_all_in_one\Strategy\doitay-vn
php -S 127.0.0.1:8000 -t . index.php
```

Test endpoint sống không:
```bash
curl http://localhost:8000/api/v1/public/companies
```

### 1.3 Chạy PHPUnit feature test

```bash
cd doitay-vn/core
php artisan test --filter=Api/V1
# hoặc cụ thể:
vendor/bin/phpunit tests/Feature/Api/V1/AuthTest.php
vendor/bin/phpunit tests/Feature/Api/V1/Public/CompanyTest.php
```

> **Cảnh báo**: Tests dùng `RefreshDatabase` → sẽ migrate toàn bộ ~50 migration mỗi lần. Lần đầu sẽ chậm. Để tăng tốc, dùng SQLite in-memory:
>
> Bỏ comment trong `phpunit.xml`:
> ```xml
> <env name="DB_CONNECTION" value="sqlite"/>
> <env name="DB_DATABASE" value=":memory:"/>
> ```
>
> Lưu ý: một số migration của doitay.vn có thể dùng cú pháp MySQL-only (FULLTEXT, ENUM cũ). Nếu SQLite fail, fall back sang MySQL test DB:
> ```bash
> mysql -uroot -e "CREATE DATABASE doitay_test;"
> ```
> Và set `DB_DATABASE=doitay_test` trong `phpunit.xml`.

### 1.4 Smoke test thủ công

```bash
# Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@local.dev","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d '{"email":"test@local.dev","password":"password123"}'

# Me (thay TOKEN)
curl http://localhost:8000/api/v1/auth/me \
  -H "Accept: application/json" -H "Authorization: Bearer 1|TOKEN_HERE"

# Public list
curl "http://localhost:8000/api/v1/public/companies?per_page=5" \
  -H "Accept: application/json"
```

---

## 2. Frontend Next.js (`frontend/`)

### 2.1 Cài đặt

```bash
cd doitay-vn/frontend
npm install
cp .env.example .env.local
```

`.env.local`:
```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
INTERNAL_API_URL=http://localhost:8000/api/v1
```

### 2.2 Chạy dev

```bash
npm run dev
# → http://localhost:3000
```

### 2.3 Chạy Playwright e2e

Yêu cầu cả backend (port 8000) và frontend (port 3000) đang chạy.

```bash
cd doitay-vn/frontend
npx playwright install --with-deps  # lần đầu
npm run test:e2e
```

---

## 3. Chạy cả 2 stack đồng thời (Windows)

Mở 2 terminal:

```bash
# Terminal 1
cd C:\doitay_all_in_one\Strategy\doitay-vn\core
php artisan serve --port=8000

# Terminal 2
cd C:\doitay_all_in_one\Strategy\doitay-vn\frontend
npm run dev
```

→ Truy cập `http://localhost:3000`, login với user vừa tạo qua curl.

---

## 4. Trước khi push lên repo mới

```bash
cd C:\doitay_all_in_one\Strategy\doitay-vn

# 1. Verify branch & remote
git status
git branch --show-current   # phải là rebuild/headless-nextjs
git remote -v                # phải TRỐNG (không có origin)

# 2. Chạy lại tests
cd core && php artisan test --filter=Api/V1 && cd ..
cd frontend && npm run test:e2e && cd ..

# 3. Commit
git add docs core/app/Http/Controllers/Api core/app/Http/Requests/V1 \
        core/app/Http/Resources/V1 core/app/Services core/app/Exceptions/Api \
        core/database/factories/CategoryFactory.php core/database/factories/CompanyFactory.php \
        core/routes/api.php core/routes/api core/config/cors.php \
        core/tests/Feature/Api frontend
git commit -m "[feat] Phase 1 — headless API + Next.js scaffold"

# 4. Tạo repo mới trên GitHub (web UI hoặc gh)
gh repo create tungtaoday/doitay-headless --private --source=. --remote=newrepo --push
```

> KHÔNG `git push origin` vì origin đã bị remove. Chỉ push lên `newrepo`.
