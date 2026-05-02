import Link from 'next/link';
import type { Metadata, Route } from 'next';
import { api, ApiError } from '@/lib/api';
import type { Paginated, PublicCompanyListItem } from '@/lib/api-types';

export const metadata: Metadata = {
  title: 'Danh sách thợ chuyên nghiệp',
  description:
    'Tìm thợ tay nghề cao đã được kiểm duyệt — sửa chữa, thi công, dịch vụ tận nơi.',
};

interface PageProps {
  searchParams: Promise<{
    q?: string;
    category?: string;
    sort?: 'newest' | 'rating' | 'price';
    page?: string;
  }>;
}

const SORT_OPTIONS: { value: NonNullable<PageProps['searchParams'] extends Promise<infer R> ? (R extends { sort?: infer S } ? S : never) : never>; label: string }[] = [
  { value: 'newest', label: 'Mới nhất' },
  { value: 'rating', label: 'Đánh giá cao' },
  { value: 'price', label: 'Giá thấp' },
];

const REGION_FILTERS = ['Tất cả khu vực', 'Quận 1, TP. HCM', 'Quận 7, TP. HCM', 'TP. Thủ Đức'];
const CATEGORY_FILTERS = ['Điện lạnh', 'Sửa ống nước', 'Nội thất & Gỗ', 'Sơn & Xây dựng'];

function buildHref(
  current: Awaited<PageProps['searchParams']>,
  patch: Partial<Awaited<PageProps['searchParams']>>,
): Route {
  const next = { ...current, ...patch };
  const qs = new URLSearchParams();
  if (next.q) qs.set('q', next.q);
  if (next.category) qs.set('category', next.category);
  if (next.sort) qs.set('sort', next.sort);
  if (next.page) qs.set('page', next.page);
  const s = qs.toString();
  return (s ? `/tho?${s}` : '/tho') as Route;
}

export default async function ContractorListPage({ searchParams }: PageProps) {
  const params = await searchParams;
  const qs = new URLSearchParams();
  if (params.q) qs.set('q', params.q);
  if (params.category) qs.set('category', params.category);
  if (params.sort) qs.set('sort', params.sort);
  if (params.page) qs.set('page', params.page);
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

  const activeSort = params.sort ?? 'newest';

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
          <form action="/tho" method="get" className="space-y-10">
            {/* Preserve current filters */}
            {params.category ? (
              <input type="hidden" name="category" value={params.category} />
            ) : null}
            {params.sort ? (
              <input type="hidden" name="sort" value={params.sort} />
            ) : null}

            {/* Search box */}
            <div>
              <label className="mb-3 block text-xs font-bold uppercase tracking-widest text-outline">
                Tìm thợ
              </label>
              <div className="flex items-center rounded-xl bg-surface-container-low px-4 py-3 transition-all focus-within:ring-2 focus-within:ring-primary/20">
                <span className="material-symbols-outlined mr-3 text-primary-fixed-dim">
                  search
                </span>
                <input
                  type="text"
                  name="q"
                  defaultValue={params.q ?? ''}
                  placeholder="Tìm tên thợ, dịch vụ..."
                  className="w-full border-none bg-transparent text-sm placeholder:text-outline focus:outline-none focus:ring-0"
                />
              </div>
            </div>

            {/* Region filter (UI only — backend chưa có endpoint) */}
            <div>
              <label className="mb-4 block text-xs font-bold uppercase tracking-widest text-outline">
                Khu vực
              </label>
              <div className="space-y-3">
                {REGION_FILTERS.map((region, i) => (
                  <label
                    key={region}
                    className="group flex cursor-pointer items-center gap-3"
                  >
                    <input
                      type="checkbox"
                      defaultChecked={i === 0}
                      className="h-5 w-5 rounded border-outline-variant text-primary transition-all focus:ring-primary/20"
                    />
                    <span className="text-sm text-on-surface-variant group-hover:text-primary">
                      {region}
                    </span>
                  </label>
                ))}
              </div>
            </div>

            <button
              type="submit"
              className="w-full rounded-xl bg-primary px-6 py-3 font-headline text-sm font-bold text-on-primary shadow-ambient transition-all hover:brightness-105 active:scale-95"
            >
              Áp dụng bộ lọc
            </button>
          </form>

          {/* Specialist filter — chips link để giữ RSC */}
          <div>
            <label className="mb-4 block text-xs font-bold uppercase tracking-widest text-outline">
              Loại thợ
            </label>
            <div className="grid grid-cols-1 gap-2">
              <Link
                href={buildHref(params, { category: undefined, page: undefined })}
                className={
                  !params.category
                    ? 'flex items-center justify-between rounded-xl bg-primary px-4 py-3 text-sm font-medium text-on-primary transition-all'
                    : 'flex items-center justify-between rounded-xl bg-surface-container-low px-4 py-3 text-left text-sm text-on-surface-variant transition-all hover:bg-surface-container hover:text-on-surface'
                }
              >
                Tất cả loại thợ
                {!params.category && (
                  <span className="material-symbols-outlined fill text-sm">
                    check_circle
                  </span>
                )}
              </Link>
              {CATEGORY_FILTERS.map((cat) => {
                const active = params.category === cat;
                return (
                  <Link
                    key={cat}
                    href={buildHref(params, { category: cat, page: undefined })}
                    className={
                      active
                        ? 'flex items-center justify-between rounded-xl bg-primary px-4 py-3 text-sm font-medium text-on-primary transition-all'
                        : 'flex items-center justify-between rounded-xl bg-surface-container-low px-4 py-3 text-left text-sm text-on-surface-variant transition-all hover:bg-surface-container hover:text-on-surface'
                    }
                  >
                    {cat}
                    {active && (
                      <span className="material-symbols-outlined fill text-sm">
                        check_circle
                      </span>
                    )}
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
                  <span className="font-bold text-on-surface">
                    {payload.meta.total}
                  </span>{' '}
                  thợ
                  {params.category ? ` "${params.category}"` : ''}
                </>
              ) : (
                'Đang tải danh sách…'
              )}
            </p>
            <div className="flex items-center gap-1 rounded-xl bg-surface-container-low p-1.5">
              {SORT_OPTIONS.map((opt) => {
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
                {payload.data.map((c) => (
                  <ContractorCard key={c.id} c={c} />
                ))}
              </div>

              {payload.meta.last_page > 1 && (
                <Pagination
                  current={payload.meta.current_page}
                  last={payload.meta.last_page}
                  buildPageHref={(p) =>
                    buildHref(params, { page: String(p) })
                  }
                />
              )}
            </>
          )}
        </section>
      </div>
    </div>
  );
}

function ContractorCard({ c }: { c: PublicCompanyListItem }) {
  return (
    <Link
      href={`/tho/${c.id}/${c.vanity_slug}`}
      className="group block overflow-hidden rounded-4xl bg-surface-container-lowest transition-all duration-300 hover:-translate-y-2 hover:shadow-ambient"
    >
      <div className="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-surface-container-low to-surface-container">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img
          src={c.image ?? ''}
          alt={c.name}
          className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
        />
        <div className="absolute left-4 top-4">
          <span className="flex items-center gap-1 rounded-full bg-tertiary-container px-3 py-1.5 text-xs font-bold text-on-tertiary-container shadow-sm">
            <span className="material-symbols-outlined fill text-sm">
              verified
            </span>
            CHUYÊN GIA
          </span>
        </div>
      </div>
      <div className="p-8">
        <div className="mb-3 flex items-start justify-between">
          <h3 className="font-headline text-xl font-bold text-on-surface">
            {c.name}
          </h3>
          <div className="flex items-center gap-1">
            <span className="material-symbols-outlined fill text-lg text-tertiary">
              star
            </span>
            <span className="text-sm font-bold text-on-surface">
              {c.rating_avg.toFixed(1)}
            </span>
          </div>
        </div>
        <p className="mb-6 flex items-center gap-2 text-sm font-medium text-primary">
          <span className="material-symbols-outlined text-sm">
            home_repair_service
          </span>
          {c.category?.name ?? 'Dịch vụ chuyên nghiệp'}
          {c.experience > 0 ? ` • ${c.experience} năm kinh nghiệm` : ''}
        </p>
        <div className="mb-8 grid grid-cols-2 gap-4">
          <div className="rounded-2xl bg-surface-container-low p-3">
            <span className="mb-1 block text-[10px] font-bold uppercase text-outline">
              Đánh giá
            </span>
            <span className="text-sm font-bold text-on-surface">
              {c.rating_count}+ phản hồi
            </span>
          </div>
          <div className="rounded-2xl bg-surface-container-low p-3">
            <span className="mb-1 block text-[10px] font-bold uppercase text-outline">
              Khu vực
            </span>
            <span className="text-sm font-bold text-on-surface">
              {[c.location.district, c.location.city]
                .filter(Boolean)
                .join(', ') || 'Toàn quốc'}
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
