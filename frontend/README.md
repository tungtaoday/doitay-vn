# doitay.vn — Frontend (Next.js 15)

Headless frontend cho doitay.vn. Tiêu thụ Laravel API ở `core/` qua `/api/v1/*`.

## Stack
- Next.js 15 (App Router) + React 19
- TypeScript strict
- Tailwind CSS 3
- TanStack Query (mutations) + RSC (reads)
- Zod + react-hook-form
- Playwright (e2e)

## Setup
```bash
npm install
cp .env.example .env.local
npm run dev
```

→ http://localhost:3000

Backend Laravel phải chạy ở `http://localhost:8000`. Xem `../docs/local-testing.md`.

## Cấu trúc
```
src/
├── app/
│   ├── layout.tsx
│   ├── page.tsx                  # /
│   ├── globals.css
│   ├── (auth)/
│   │   ├── login/page.tsx        # /login
│   │   └── login/actions.ts
│   └── cong-ty/
│       ├── page.tsx              # /cong-ty (list)
│       └── [slug]/page.tsx       # /cong-ty/[slug] (detail)
├── lib/
│   ├── api.ts                    # single backend entry point — DON'T fetch directly elsewhere
│   ├── auth.ts                   # cookie helpers
│   └── api-types.ts              # mirrors Laravel JsonResource shapes
└── middleware.ts                 # gates protected routes
```

## Convention
- **Mọi request backend đi qua `src/lib/api.ts`** — không bao giờ `fetch` trực tiếp trong component.
- **Auth token KHÔNG bao giờ ở localStorage** — luôn ở httpOnly cookie set bởi server action.
- Mọi form submit có Zod schema mirror với Laravel `FormRequest`.
- URL public phải khớp byte-for-byte với Blade legacy (`/cong-ty/[slug]`, không phải `/companies/[slug]`).
