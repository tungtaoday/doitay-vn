# CLAUDE.md — frontend (Next.js)

> File này focused vào Next.js frontend. Đọc `../CLAUDE.md` (root) để có context tổng thể của project.

## Stack

- Next.js 15 App Router (RSC mặc định)
- React 19
- TypeScript strict + `typedRoutes: true`
- Tailwind CSS 3
- Playwright (e2e, chromium)
- Server Actions cho mutation, RSC cho read

> ⚠️ React 19 → `npm install` phải có `--legacy-peer-deps` (một số dep còn peer-pin React 18).

## Lệnh thường dùng

| Command | Mục đích |
|---|---|
| `npm run dev` | Dev server :3000 |
| `npm run build` | Production build (cũng chạy type check + lint) |
| `npm run typecheck` | `tsc --noEmit` only |
| `npm run lint` | ESLint (next/core-web-vitals) |
| `npm run test:e2e` | Playwright (cần backend chạy ở :8000) |

## Design System (BẮT BUỘC)

Mọi thay đổi frontend **phải tuân thủ** design system tại `docs/design-system.md`. Tóm tắt các nguyên tắc cốt lõi:

- **Colors:** Primary `#48BBE2` (sky blue), Secondary `#102F4B` (navy), Tertiary `#FFD700` (gold, dùng rất ít — chỉ cho verified/expert badges)
- **No-Line Rule:** KHÔNG dùng `border` 1px để phân vùng. Dùng background shift giữa surface levels thay thế. Ghost border chỉ dùng ở `outline-variant` opacity 15%
- **Typography:** Headlines = `font-headline` (Be Vietnam Pro, 500/700), Body = `font-sans` (Inter). Giữ contrast ratio lớn giữa headline và body
- **Elevation:** KHÔNG dùng `shadow-md/lg/xl`. Depth qua surface level contrast. Floating elements (CTA buttons) dùng ambient shadow tinted navy ở 6% opacity (`shadow-ambient`)
- **Text:** KHÔNG dùng `text-black`. Luôn dùng `text-on-surface` (navy-based) cho softer premium contrast
- **Cards:** KHÔNG dùng dividers. Spacing làm ranh giới. Hover = background shift + minor translate-y, không phải shadow
- **Buttons:** Primary = solid `bg-primary` rounded-xl. Secondary = transparent + ghost border. Hover = tonal shift
- **Inputs:** Label *trên* field (không placeholder-as-label). Focus = 2px primary glow (`ring-primary/30`)
- **Corners:** Moderate roundedness (`rounded-xl` / `rounded-2xl`), không sharp corners
- **Spacing:** Generous whitespace. Section gaps lớn cho editorial feel

> Đọc đầy đủ: `docs/design-system.md`

## Quy ước

### Fetching
- **Luôn** fetch qua `src/lib/api.ts` — không gọi `fetch()` trực tiếp trong page.
- `api<T>(path, opts)` tự prepend `INTERNAL_API_URL`, parse JSON, throw `ApiError` với `status` và `body`.
- Catch `ApiError` để map status → UX (401 → re-login, 404 → notFound(), 422 → form errors, 429 → rate-limit message).

```typescript
import { api, ApiError } from '@/lib/api';

try {
  const data = await api<MeResponse>('/auth/me');
} catch (e) {
  if (e instanceof ApiError && e.status === 401) {
    // ...
  }
  throw e;
}
```

### Auth
- Token Sanctum lưu trong **httpOnly cookie** (`doitay_token`) qua `cookies()` từ `next/headers`.
- `src/lib/auth.ts` expose `getToken()`, `setToken(token)`, `clearToken()`.
- **TUYỆT ĐỐI KHÔNG** dùng `localStorage` / `document.cookie` cho token.
- Login flow: server action (`src/app/(auth)/login/actions.ts`) → call `api('/auth/login', ...)` → `setToken(res.token)` → redirect.

### Pages
- **Read = RSC**. Không thêm `'use client'` trừ khi cần state/event handler.
- **Mutation = Server Action**. Trả `{ ok: true } | { ok: false, error: string }` rồi parent xử lý redirect.
- `params` và `searchParams` là `Promise` trong Next 15 — phải `await`.

### Routes hiện có

| Route | File | Notes |
|---|---|---|
| `/` | `src/app/page.tsx` | Homepage tối giản |
| `/login` | `src/app/(auth)/login/page.tsx` | Server action login |
| `/cong-ty` | `src/app/cong-ty/page.tsx` | Public list, có search |
| `/cong-ty/[id]/[[...rest]]` | Dynamic + optional catch-all | Detail; canonical-redirect vanity slug để parity với legacy `/companies/{id}/{vanity-slug}` |

### URL parity (quan trọng)

Legacy doitay.vn dùng URL `/companies/{id}/{vanity-slug}` (id-based, slug = SEO vanity derived từ name). Live `companies` table KHÔNG có cột `slug`.

→ Frontend route detail là `cong-ty/[id]/[[...rest]]` với optional catch-all. Trong page:
1. Load company theo `id`
2. So sánh `rest?.[0]` với `company.vanity_slug`
3. Nếu khác → `redirect()` sang URL canonical `/cong-ty/{id}/{vanity_slug}` (307)

Đừng đổi cấu trúc route này — break SEO continuity.

## Env

- `.env.example` → copy sang `.env.local` (đã trong `.gitignore`)
- `NEXT_PUBLIC_API_URL` — public, dùng cho client-side fetch. Dev: `http://localhost:8080/api/v1` (XAMPP Apache)
- `INTERNAL_API_URL` — server-side, dùng trong RSC + server action. Dev = same as public; prod sẽ trỏ internal load balancer
- ⚠️ Backend dev hiện tại chạy trên **XAMPP Apache port 8080** (không phải `php -S :8000` nữa). Xem `../CLAUDE.md` §3 để biết chi tiết setup.

## Type safety với backend

`src/lib/api-types.ts` là **hand-maintained mirror** của Laravel JsonResource. Khi backend thay shape của Resource → update type ở đây cùng PR. Phase 2 sẽ thay bằng OpenAPI gen (dedoc/scramble + openapi-typescript).

Bao giờ thêm field vào Resource Laravel → check ngay file này có cần update không.

## Playwright

`playwright.config.ts` auto-bật `npm run dev` trước khi test (`webServer` block). **Backend Laravel phải đang chạy** trước khi chạy `npm run test:e2e` — hiện tại dùng XAMPP Apache :8080 (xem `../CLAUDE.md` §3).

```bash
# Bật Apache qua XAMPP Control Panel (nếu chưa chạy)
# Rồi:
cd C:\doitay_all_in_one\Strategy\doitay-vn\frontend
npm run test:e2e
```

Smoke specs trong `tests/e2e/smoke.spec.ts`. Khi thêm slice mới → thêm spec vào đây, đừng tạo file rời.

## Đừng làm

- ❌ `localStorage.setItem('token', ...)` — vi phạm rule auth
- ❌ `'use client'` ở page level chỉ để gọi 1 fetch — dùng RSC
- ❌ Custom fetch wrapper khác `src/lib/api.ts`
- ❌ Hardcode URL `http://localhost:8000` — dùng env
- ❌ `any`, `as unknown as X`, `@ts-expect-error` không lý do
- ❌ Đổi route `cong-ty/[id]/[[...rest]]` sang slug-based — break SEO + không có cột slug
