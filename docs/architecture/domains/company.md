# Domain: Company

> Map từ `app/Models/Company.php` sang góc nhìn API rebuild.

## Aggregate Root: Company

**Source**: `core/app/Models/Company.php`. Schema bị split qua nhiều migration (xem `core/database/migrations/*compan*`).

### Fields chính

| Field | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `user_id` | FK → users | owner |
| `category_id` | FK → categories | |
| `name` | string | |
| `slug` | string unique | URL public |
| `email` | string nullable | |
| `phone` | string nullable | |
| `image` | string url | logo |
| `url` | string url | website |
| `description` | text | markdown |
| `experience` | int default 0 | số năm hoạt động |
| `district`, `ward`, `state`, `zip`, `country` | mixed | location |
| `tags` | json array | cast `array` |
| `services` | json array | cast `array` |
| `business_hours` | json | cast `array` |
| `status` | int | xem `App\Constants\Status`: 0 pending, 1 approved, 2 rejected |
| `avg_rating` | decimal cached | sync từ ratings |
| `admin_feedback` | text | nội bộ — KHÔNG public |
| `created_at`, `updated_at` | timestamps | |

### Relations

| Relation | Target | Type |
|---|---|---|
| `user()` | User | belongsTo (owner) |
| `category()` | Category | belongsTo |
| `reviews()` | Review | hasMany |
| `ratings()` | Rating | hasMany |
| `portfolios()` | Portfolio | hasMany |
| `certificates()` | Certificate | hasMany |
| `statistics()` | CompanyStatistics | hasOne |
| `wallet()` | CompanyWallet | hasOne |
| `leadPurchases()` | LeadPurchase | hasMany |
| `appointments()` | Appointment | hasMany |

### Scopes có sẵn
`approved()`, `pending()`, `rejected()`, `active()`, `verified()` — tất cả filter theo `status`.

### Invariants

| INV | Rule | Enforcement |
|---|---|---|
| INV-CO-1 | `slug` unique và URL-safe | DB unique + Service generate slug |
| INV-CO-2 | Chỉ company `status = approved` được hiển thị public | Scope `approved()` ở Service |
| INV-CO-3 | `admin_feedback` KHÔNG bao giờ ra public Resource | `Public\CompanyResource` |
| INV-CO-4 | `email`/`phone` chỉ public nếu `show_contact = true` (cần thêm column hoặc dùng setting) | Resource conditional |
| INV-CO-5 | `avg_rating` sync khi có rating mới (job/observer) | Out of scope Phase 1 — đọc trực tiếp |

## Commands

| Command | DUC | Endpoint | Phase |
|---|---|---|---|
| `CreateCompany` | DUC-COMPANY-CREATE | `POST /api/v1/user/companies` | Phase 2 |
| `UpdateMyCompany` | DUC-COMPANY-UPDATE-MINE | `PUT /api/v1/user/companies/{id}` | Phase 2 |
| `ApproveCompany` | DUC-COMPANY-APPROVE | `POST /api/v1/admin/companies/{id}/approve` | Phase 3 |

## Queries (Phase 1 scope)

| Query | DUC | Endpoint |
|---|---|---|
| `ListPublicCompanies` | DUC-COMPANY-LIST-PUBLIC | `GET /api/v1/public/companies` |
| `ShowPublicCompany` | DUC-COMPANY-SHOW-PUBLIC | `GET /api/v1/public/companies/{slug}` |

## Service Layer (mới — extract từ Blade controller)

**File**: `app/Services/CompanySearchService.php`

```php
class CompanySearchService {
    public function listPublic(array $filters, int $perPage = 20): LengthAwarePaginator;
    public function showPublicBySlug(string $slug): Company; // throws ModelNotFound
}
```

Service này sẽ là **single source of truth** — Blade controller hiện tại của trang `/cong-ty/...` cũng phải refactor để gọi vào đây thay vì query trực tiếp. Đây là điều kiện tiên quyết để 2 frontend (Blade legacy + Next.js mới) không drift.

## Resource Layer

| Resource | Audience | Field expose |
|---|---|---|
| `Public\CompanyResource` | List public | id, slug, name, image, category{id,name}, location{district,state}, avg_rating, rating_count, short_description |
| `Public\CompanyDetailResource` | Show public | full company + portfolios + features + ratings_recent + view_count, conditional phone/email |
| `User\MyCompanyResource` | Owner xem company của mình | full + admin_feedback + status + balance | (Phase 2)
| `Admin\AdminCompanyResource` | Admin panel | full + admin_feedback + kyc_status + owner.email + balance + leads | (Phase 3)

## Out of scope Phase 1

- Create/update/delete company (Phase 2 — User namespace)
- Approval workflow (Phase 3 — Admin namespace)
- Lead purchase, wallet operations (Phase 4)
- Rating submit (Phase 2 — separate aggregate)

## References

- Eloquent: `core/app/Models/Company.php`
- Migrations: `core/database/migrations/*compan*` (12 files)
- Status constants: `core/app/Constants/Status.php`
- DUCs: `docs/use_cases/domain/company/*.md`
