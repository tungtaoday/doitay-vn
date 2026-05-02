import Link from 'next/link';
import { unstable_cache } from 'next/cache';
import type { Metadata, Route } from 'next';
import { api, ApiError } from '@/lib/api';
import type { Paginated, PublicCategory, PublicCompanyListItem } from '@/lib/api-types';
import { getPlaceholderImage, isSeedImage } from '@/lib/placeholder-images';

export const metadata: Metadata = {
  title: 'Danh sách thợ chuyên nghiệp',
  description:
    'Tìm thợ tay nghề cao đã được kiểm duyệt — sửa chữa, thi công, dịch vụ tận nơi.',
};

interface SearchParams {
  q?: string;
  category?: string;    // category ID (int as string)
  district?: string;    // district name e.g. "Quận Thanh Xuân"
  min_rating?: string;  // '3' | '4'
  sort?: 'newest' | 'rating' | 'price';
  page?: string;
}

interface PageProps {
  searchParams: Promise<SearchParams>;
}

const SORT_OPTIONS = [
  { value: 'newest' as const, label: 'Mới nhất' },
  { value: 'rating' as const, label: 'Đánh giá cao' },
  { value: 'price'  as const, label: 'Giá thấp' },
];

const RATING_OPTIONS = [
  { value: '', label: 'Tất cả' },
  { value: '4', label: '4★ trở lên' },
  { value: '3', label: '3★ trở lên' },
];

const loadCategories = unstable_cache(
  () => api<{ data: PublicCategory[] }>('/public/categories').then(r => r.data),
  ['public-categories'],
  { revalidate: 3600 },
);

interface DistrictItem { code: number; name: string }

const loadHanoiDistricts = unstable_cache(
  () =>
    api<{ data: DistrictItem[] }>('/public/locations/districts/1')
      .then(r => r.data.filter(d => d.name.startsWith('Quận'))),
  ['hanoi-quan'],
  { revalidate: 86400 },
);

function buildHref(current: SearchParams, patch: Partial<SearchParams>): Route {
  const next = { ...current, ...patch };
  const qs = new URLSearchParams();
  if (next.q)          qs.set('q',          next.q);
  if (next.category)   qs.set('category',   next.category);
  if (next.district)   qs.set('district',   next.district);
  if (next.min_rating) qs.set('min_rating', next.min_rating);
  if (next.sort)       qs.set('sort',       next.sort);
  if (next.page)       qs.set('page',       next.page);
  const s = qs.toString();
  return (s ? `/cong-ty?${s}` : '/cong-ty') as Route;
}

export default async function CompanyListPage({ searchParams }: PageProps) {
  const params = await searchParams;

  const [categories, districts]: [PublicCategory[], DistrictItem[]] = await Promise.all([
    loadCategories().catch(() => [] as PublicCategory[]),
    loadHanoiDistricts().catch(() => [] as DistrictItem[]),
  ]);

  const qs = new URLSearchParams();
  if (params.q)          qs.set('q',          params.q);
  if (params.category)   qs.set('category',   params.category);
  if (params.district)   qs.set('district',   params.district);
  if (params.min_rating) qs.set('min_rating', params.min_rating);
  if (params.sort)       qs.set('sort',       params.sort);
  if (params.page)       qs.set('page',       params.page);
  qs.set('per_page', '12');

  let payload: Paginated<PublicCompanyListItem> | null = null;
  let error: string | null = null;
  try {
    payload = await api<Paginated<PublicCompanyListItem>>(
      `/public/companies?${qs.toString()}`,
    );
  } catch (e) {
    error =
      e instanceof ApiError
        ? `Lỗi ${e.status}`
        : e instanceof Error
          ? e.message
          : 'Không tải được danh sách';
  }

  const activeSort   = params.sort       ?? 'newest';
  const activeRating = params.min_rating ?? '';
  const activeCat    = params.category   ?? '';
  const activeDist   = params.district   ?? '';

  // Active category name for summary text
  const activeCatName = categories.find(c => String(c.id) === activeCat)?.name;

  return (
    <div className="mx-auto max-w-7xl px-6 pb-32 pt-8 md:px-8">
      {/* Breadcrumbs + title */}
      <nav className="mb-12">
        <div className="mb-4 flex items-center gap-2 text-sm text-outline">
          <Link href="/" className="transition-colors hover:text-primary">
            Trang chủ
          </Link>
          <span className="material-symbols-outlined text-xs">chevron_right</span>
          <span className="font-medium text-on-surface">Danh sách dịch vụ</span>
        </div>
        <h1 className="max-w-2xl font-headline text-4xl font-bold tracking-tight text-on-surface md:text-5xl">
          Tìm kiếm thợ tay nghề cao{' '}
          <span className="italic text-primary">cho ngôi nhà của bạn.</span>
        </h1>
      </nav>

      <div className="grid grid-cols-1 gap-12 lg:grid-cols-12">
        {/* ─── Filter sidebar ───────────────────────────────────────── */}
        <aside className="space-y-10 lg:col-span-3">
          <form action="/cong-ty" method="get" className="space-y-8">
            {/* Preserve link-based filters in form submissions */}
            {activeCat  ? <input type="hidden" name="category"   value={activeCat}  /> : null}
            {activeDist ? <input type="hidden" name="district"   value={activeDist} /> : null}
            {activeRating ? <input type="hidden" name="min_rating" value={activeRating} /> : null}
            {params.sort  ? <input type="hidden" name="sort"       value={params.sort}  /> : null}

            {/* Search box */}
            <div>
              <label className="mb-3 block text-xs font-bold uppercase tracking-widest text-outline">
                Tìm thợ
              </label>
              <div className="flex items-center rounded-xl bg-surface-container-low px-4 py-3 transition-all focus-within:ring-2 focus-within:ring-primary/20">
                <span className="material-symbols-outlined mr-3 text-primary-fixed-dim">search</span>
                <input
                  type="text"
                  name="q"
                  defaultValue={params.q ?? ''}
                  placeholder="Tìm tên thợ, dịch vụ..."
                  className="w-full border-none bg-transparent text-sm placeholder:text-outline focus:outline-none focus:ring-0"
                />
              </div>
            </div>

            <button
              type="submit"
              className="w-full rounded-xl bg-gradient-to-r from-primary to-primary-container px-6 py-3 font-headline text-sm font-bold text-on-primary transition-all active:scale-95"
            >
              Tìm kiếm
            </button>
          </form>

          {/* ── Khu vực (Quận Hà Nội) ── */}
          <div>
            <label className="mb-4 block text-xs font-bold uppercase tracking-widest text-outline">
              Khu vực — Hà Nội
            </label>
            <div className="grid grid-cols-1 gap-2">
              <Link
                href={buildHref(params, { district: undefined, page: undefined })}
                className={chip(!activeDist)}
              >
                Tất cả khu vực
                {!activeDist && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
              </Link>
              {districts.map(d => {
                const active = activeDist === d.name;
                return (
                  <Link
                    key={d.code}
                    href={buildHref(params, { district: d.name, page: undefined })}
                    className={chip(active)}
                  >
                    {d.name.replace('Quận ', '')}
                    {active && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
                  </Link>
                );
              })}
            </div>
          </div>

          {/* ── Loại thợ (từ DB) ── */}
          <div>
            <label className="mb-4 block text-xs font-bold uppercase tracking-widest text-outline">
              Ngành nghề
            </label>
            <div className="grid grid-cols-1 gap-2">
              <Link
                href={buildHref(params, { category: undefined, page: undefined })}
                className={chip(!activeCat)}
              >
                Tất cả ngành nghề
                {!activeCat && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
              </Link>
              {categories.map(cat => {
                const active = activeCat === String(cat.id);
                return (
                  <Link
                    key={cat.id}
                    href={buildHref(params, { category: String(cat.id), page: undefined })}
                    className={chip(active)}
                  >
                    {cat.name}
                    {active && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
                  </Link>
                );
              })}
            </div>
          </div>

          {/* ── Rating ── */}
          <div>
            <label className="mb-4 block text-xs font-bold uppercase tracking-widest text-outline">
              Đánh giá
            </label>
            <div className="grid grid-cols-1 gap-2">
              {RATING_OPTIONS.map(opt => {
                const active = activeRating === opt.value;
                return (
                  <Link
                    key={opt.value}
                    href={buildHref(params, { min_rating: opt.value || undefined, page: undefined })}
                    className={chip(active)}
                  >
                    {opt.label}
                    {active && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
                  </Link>
                );
              })}
            </div>
          </div>
        </aside>

        {/* ─── Listing content ──────────────────────────────────────── */}
        <section className="lg:col-span-9">
          {/* Sort + summary */}
          <div className="mb-10 flex flex-col justify-between gap-6 md:flex-row md:items-center">
            <p className="text-on-surface-variant">
              {payload ? (
                <>
                  Tìm thấy{' '}
                  <span className="font-bold text-on-surface">{payload.meta.total}</span>{' '}
                  thợ
                  {activeCatName ? ` "${activeCatName}"` : ''}
                  {activeDist ? ` tại ${activeDist}` : ''}
                </>
              ) : (
                'Đang tải danh sách…'
              )}
            </p>
            <div className="flex items-center gap-1 rounded-xl bg-surface-container-low p-1.5">
              {SORT_OPTIONS.map(opt => {
                const active = activeSort === opt.value;
                return (
                  <Link
                    key={opt.value}
                    href={buildHref(params, { sort: opt.value, page: undefined })}
                    className={
                      active
                        ? 'rounded-lg bg-surface-container-lowest px-4 py-2 text-sm font-medium text-on-surface shadow-sm'
                        : 'rounded-lg px-4 py-2 text-sm font-medium text-outline hover:text-on-surface'
                    }
                  >
                    {opt.label}
                  </Link>
                );
              })}
            </div>
          </div>

          {error && (
            <div className="rounded-2xl bg-error-container p-6 text-on-error-container">
              {error}
            </div>
          )}

          {payload && payload.data.length === 0 && (
            <div className="rounded-3xl bg-surface-container-low p-12 text-center text-on-surface-variant">
              Không tìm thấy thợ nào phù hợp.
            </div>
          )}

          {payload && payload.data.length > 0 && (
            <>
              <div className="grid grid-cols-1 gap-8 md:grid-cols-2">
                {payload.data.map(c => (
                  <CompanyCard key={c.id} c={c} />
                ))}
              </div>

              {payload.meta.last_page > 1 && (
                <Pagination
                  current={payload.meta.current_page}
                  last={payload.meta.last_page}
                  buildPageHref={p => buildHref(params, { page: String(p) })}
                />
              )}
            </>
          )}
        </section>
      </div>
    </div>
  );
}

function chip(active: boolean) {
  return active
    ? 'flex items-center justify-between rounded-xl bg-primary px-4 py-3 text-sm font-medium text-on-primary transition-all'
    : 'flex items-center justify-between rounded-xl bg-surface-container-low px-4 py-3 text-left text-sm text-on-surface-variant transition-all hover:bg-surface-container hover:text-on-surface';
}

function CompanyCard({ c }: { c: PublicCompanyListItem }) {
  return (
    <Link
      href={`/cong-ty/${c.id}/${c.vanity_slug}`}
      className="group block overflow-hidden rounded-4xl bg-surface-container-lowest transition-all duration-300 hover:-translate-y-2 hover:shadow-ambient"
    >
      <div className="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-surface-container-low to-surface-container">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img
          src={isSeedImage(c.image) ? getPlaceholderImage(c.category?.name) : c.image!}
          alt={c.name}
          className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
        />
        <div className="absolute left-4 top-4">
          <span className="flex items-center gap-1 rounded-full bg-tertiary-container px-3 py-1.5 text-xs font-bold text-on-tertiary-container shadow-sm">
            <span className="material-symbols-outlined fill text-sm">verified</span>
            CHUYÊN GIA
          </span>
        </div>
      </div>
      <div className="p-8">
        <div className="mb-3 flex items-start justify-between">
          <h3 className="font-headline text-xl font-bold text-on-surface">{c.name}</h3>
          <div className="flex items-center gap-1">
            <span className="material-symbols-outlined fill text-lg text-tertiary">star</span>
            <span className="text-sm font-bold text-on-surface">{c.rating_avg.toFixed(1)}</span>
          </div>
        </div>
        <p className="mb-6 flex items-center gap-2 text-sm font-medium text-primary">
          <span className="material-symbols-outlined text-sm">home_repair_service</span>
          {c.category?.name ?? 'Dịch vụ chuyên nghiệp'}
          {c.experience > 0 ? ` • ${c.experience} năm kinh nghiệm` : ''}
        </p>
        <div className="mb-8 grid grid-cols-2 gap-4">
          <div className="rounded-2xl bg-surface-container-low p-3">
            <span className="mb-1 block text-[10px] font-bold uppercase text-outline">Đánh giá</span>
            <span className="text-sm font-bold text-on-surface">{c.rating_count}+ phản hồi</span>
          </div>
          <div className="rounded-2xl bg-surface-container-low p-3">
            <span className="mb-1 block text-[10px] font-bold uppercase text-outline">Khu vực</span>
            <span className="text-sm font-bold text-on-surface">
              {[c.location.district, c.location.city].filter(Boolean).join(', ') || 'Toàn quốc'}
            </span>
          </div>
        </div>
        <div className="flex items-center justify-between">
          <p className="line-clamp-1 max-w-[60%] text-sm text-on-surface-variant">
            {c.short_description ?? 'Liên hệ để được tư vấn'}
          </p>
          <span className="rounded-xl bg-surface-container-highest px-6 py-3 text-sm font-bold text-primary transition-all group-hover:bg-primary group-hover:text-on-primary">
            Xem chi tiết
          </span>
        </div>
      </div>
    </Link>
  );
}

function Pagination({
  current,
  last,
  buildPageHref,
}: {
  current: number;
  last: number;
  buildPageHref: (page: number) => Route;
}) {
  const pages: number[] = [];
  for (let p = 1; p <= Math.min(3, last); p++) pages.push(p);
  if (last > 3) pages.push(last);

  return (
    <div className="mt-20 flex items-center justify-center gap-2">
      {current > 1 && (
        <Link
          href={buildPageHref(current - 1)}
          className="flex h-12 w-12 items-center justify-center rounded-2xl bg-surface-container-low text-on-surface-variant transition-all hover:bg-primary hover:text-on-primary"
        >
          <span className="material-symbols-outlined">chevron_left</span>
        </Link>
      )}
      {pages.map((p, idx) => (
        <span key={p} className="flex items-center">
          {idx > 0 && pages[idx - 1] !== p - 1 && (
            <span className="mx-2 text-outline">…</span>
          )}
          <Link
            href={buildPageHref(p)}
            className={
              p === current
                ? 'flex h-12 w-12 items-center justify-center rounded-2xl bg-primary font-bold text-on-primary shadow-ambient'
                : 'flex h-12 w-12 items-center justify-center rounded-2xl bg-surface-container-low font-bold text-on-surface-variant transition-all hover:bg-primary-container/20 hover:text-primary'
            }
          >
            {p}
          </Link>
        </span>
      ))}
      {current < last && (
        <Link
          href={buildPageHref(current + 1)}
          className="flex h-12 w-12 items-center justify-center rounded-2xl bg-surface-container-low text-on-surface-variant transition-all hover:bg-primary hover:text-on-primary"
        >
          <span className="material-symbols-outlined">chevron_right</span>
        </Link>
      )}
    </div>
  );
}
