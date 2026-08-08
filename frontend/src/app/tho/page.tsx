import Link from 'next/link';
import { unstable_cache } from 'next/cache';
import type { Metadata, Route } from 'next';
import { api, ApiError } from '@/lib/api';
import { SearchAnalytics } from '@/components/search-analytics';
import type { Paginated, PublicCategory, PublicCompanyListItem } from '@/lib/api-types';
import { getPlaceholderImage, isSeedImage } from '@/lib/placeholder-images';

export const metadata: Metadata = {
  title: 'Danh sách thợ chuyên nghiệp',
  description:
    'Tìm thợ tay nghề cao đã được kiểm duyệt — sửa chữa, thi công, dịch vụ tận nơi.',
  alternates: { canonical: '/tho' },
};

interface SearchParams {
  q?: string;
  category?: string;
  district?: string;
  min_rating?: string;
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
  return (s ? `/tho?${s}` : '/tho') as Route;
}

export default async function ContractorListPage({ searchParams }: PageProps) {
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

  const activeCatName = categories.find(c => String(c.id) === activeCat)?.name;

  // Khoá lọc: bỏ `page` để lật trang không tính là một lượt tìm mới.
  const khoaLoc = [params.q, activeCat, activeDist, activeRating, params.sort]
    .filter(Boolean)
    .join('|');

  return (
    <div className="mx-auto max-w-7xl px-4 pb-32 pt-6 md:px-8 md:pt-8">
      <SearchAnalytics khoa={khoaLoc} soKetQua={payload?.data.length ?? 0} />
      <nav className="mb-6 lg:mb-12">
        <div className="mb-3 flex items-center gap-2 text-sm text-outline">
          <Link href="/" className="transition-colors hover:text-primary">Trang chủ</Link>
          <span className="material-symbols-outlined text-xs">chevron_right</span>
          <span className="font-medium text-on-surface">Danh sách thợ</span>
        </div>
        <h1 className="max-w-2xl font-headline text-2xl font-bold tracking-tight text-on-surface md:text-5xl">
          Tìm kiếm thợ tay nghề cao{' '}
          <span className="italic text-primary">cho ngôi nhà của bạn.</span>
        </h1>
      </nav>

      <div className="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-12">
        {/* ─── Filter sidebar (mobile: chip cuộn ngang, desktop: dọc) ── */}
        <aside className="space-y-5 lg:col-span-3 lg:space-y-10">
          <form action="/tho" method="get" className="space-y-3 lg:space-y-8">
            {activeCat    ? <input type="hidden" name="category"   value={activeCat}    /> : null}
            {activeDist   ? <input type="hidden" name="district"   value={activeDist}   /> : null}
            {activeRating ? <input type="hidden" name="min_rating" value={activeRating} /> : null}
            {params.sort  ? <input type="hidden" name="sort"       value={params.sort}  /> : null}

            <div>
              <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-outline lg:mb-3">
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
              className="w-full rounded-xl bg-primary px-6 py-3 font-headline text-sm font-bold text-on-primary transition-all hover:bg-primary-hover active:scale-95"
            >
              Tìm kiếm
            </button>
          </form>

          {/* Khu vực — Quận Hà Nội */}
          <div>
            <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-outline lg:mb-4">
              Khu vực — Hà Nội
            </label>
            <div className="flex gap-2 overflow-x-auto pb-2 lg:grid lg:grid-cols-1 lg:overflow-visible lg:pb-0">
              <Link href={buildHref(params, { district: undefined, page: undefined })} className={chip(!activeDist)}>
                Tất cả khu vực
                {!activeDist && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
              </Link>
              {districts.map(d => {
                const active = activeDist === d.name;
                return (
                  <Link key={d.code} href={buildHref(params, { district: d.name, page: undefined })} className={chip(active)}>
                    {d.name.replace('Quận ', '')}
                    {active && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
                  </Link>
                );
              })}
            </div>
          </div>

          {/* Ngành nghề */}
          <div>
            <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-outline lg:mb-4">
              Ngành nghề
            </label>
            <div className="flex gap-2 overflow-x-auto pb-2 lg:grid lg:grid-cols-1 lg:overflow-visible lg:pb-0">
              <Link href={buildHref(params, { category: undefined, page: undefined })} className={chip(!activeCat)}>
                Tất cả ngành nghề
                {!activeCat && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
              </Link>
              {categories.map(cat => {
                const active = activeCat === String(cat.id);
                return (
                  <Link key={cat.id} href={buildHref(params, { category: String(cat.id), page: undefined })} className={chip(active)}>
                    {cat.name}
                    {active && <span className="material-symbols-outlined fill text-sm">check_circle</span>}
                  </Link>
                );
              })}
            </div>
          </div>

          {/* Rating */}
          <div>
            <label className="mb-2 block text-xs font-bold uppercase tracking-widest text-outline lg:mb-4">
              Đánh giá
            </label>
            <div className="flex gap-2 overflow-x-auto pb-2 lg:grid lg:grid-cols-1 lg:overflow-visible lg:pb-0">
              {RATING_OPTIONS.map(opt => {
                const active = activeRating === opt.value;
                return (
                  <Link key={opt.value} href={buildHref(params, { min_rating: opt.value || undefined, page: undefined })} className={chip(active)}>
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
              ) : 'Đang tải danh sách…'}
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
            <div className="rounded-2xl bg-error-container p-6 text-on-error-container">{error}</div>
          )}

          {payload && payload.data.length === 0 && (
            /* Ngõ cụt cũ: chỉ báo "không tìm thấy" rồi hết. Khách đã nói rõ họ
               cần gì mà lại bị chặn ở đây — đưa thẳng sang gửi yêu cầu, vì đó
               mới là đường ra việc khi chưa có thợ khớp sẵn. */
            <div className="rounded-3xl bg-surface-container-low p-8 text-center md:p-12">
              <p className="font-headline text-xl font-bold text-on-surface">
                {params.q ? <>Chưa có thợ nào khớp &ldquo;{params.q}&rdquo;</> : 'Chưa có thợ nào khớp bộ lọc này'}
              </p>
              <p className="mx-auto mt-2 max-w-md text-sm text-on-surface-variant">
                Mô tả việc bạn cần, chúng tôi tìm thợ phù hợp và gọi lại cho bạn. Không mất phí.
              </p>

              <div className="mt-5 flex flex-wrap items-center justify-center gap-3">
                <Link
                  href={'/yeu-cau' as Route}
                  className="rounded-full bg-primary px-8 py-3.5 font-headline font-bold text-on-primary shadow-ambient transition-all active:scale-95"
                >
                  Gửi yêu cầu — có thợ gọi lại
                </Link>
                <Link
                  href={'/tho' as Route}
                  className="rounded-full bg-surface-container px-6 py-3.5 text-sm font-semibold text-on-surface transition-colors hover:bg-surface-container-high"
                >
                  Xem tất cả thợ
                </Link>
              </div>

              {categories.length > 0 && (
                <div className="mt-7">
                  <p className="text-xs font-semibold uppercase tracking-wide text-outline">
                    Hoặc chọn nghề
                  </p>
                  <div className="mt-2 flex flex-wrap justify-center gap-2">
                    {categories.slice(0, 10).map(k => (
                      <Link
                        key={k.id}
                        href={`/tho?category=${k.id}` as Route}
                        className="rounded-full bg-surface-container-lowest px-4 py-2 text-sm font-medium text-on-surface transition-colors hover:bg-surface-container"
                      >
                        {k.name}
                      </Link>
                    ))}
                  </div>
                </div>
              )}
            </div>
          )}

          {payload && payload.data.length > 0 && (
            <>
              <div className="grid grid-cols-1 gap-8 md:grid-cols-2">
                {payload.data.map(c => <ContractorCard key={c.id} c={c} />)}
              </div>
              {payload.meta.last_page > 1 && (
                <Pagination
                  current={payload.meta.current_page}
                  last={payload.meta.last_page}
                  buildPageHref={p => buildHref(params, { page: String(p) })}
                />
              )}

              {/* Có kết quả nhưng chưa ưng ai vẫn là ngõ cụt nếu không có lối ra. */}
              <div className="mt-10 flex flex-wrap items-center justify-between gap-4 rounded-3xl bg-surface-container-low p-6">
                <p className="text-sm text-on-surface-variant">
                  Chưa ưng ai? Mô tả việc cần làm, chúng tôi tìm thợ phù hợp và gọi lại.
                </p>
                <Link
                  href={'/yeu-cau' as Route}
                  className="shrink-0 rounded-full bg-primary px-6 py-3 text-sm font-bold text-on-primary transition-all active:scale-95"
                >
                  Gửi yêu cầu
                </Link>
              </div>
            </>
          )}
        </section>
      </div>
    </div>
  );
}

function chip(active: boolean) {
  // shrink-0 + whitespace-nowrap: để chip nằm gọn khi cuộn ngang trên mobile.
  return active
    ? 'flex shrink-0 items-center justify-between gap-1.5 whitespace-nowrap rounded-xl bg-primary px-4 py-2 text-sm font-medium text-on-primary transition-all lg:py-3'
    : 'flex shrink-0 items-center justify-between gap-1.5 whitespace-nowrap rounded-xl bg-surface-container-low px-4 py-2 text-left text-sm text-on-surface-variant transition-all hover:bg-surface-container hover:text-on-surface lg:py-3';
}

function ContractorCard({ c }: { c: PublicCompanyListItem }) {
  // Mobile: card ngang gọn (ảnh 96px) — trước đây ảnh 16/10 full-width khiến
  // mỗi card cao ~600px, chỉ thấy hơn 1 thợ mỗi màn hình. Desktop (md+): giữ nguyên.
  return (
    <Link
      href={`/tho/${c.id}/${c.vanity_slug}`}
      className="group flex gap-4 overflow-hidden rounded-3xl bg-surface-container-lowest p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-ambient md:block md:rounded-4xl md:p-0 md:hover:-translate-y-2"
    >
      <div className="relative h-24 w-24 shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-surface-container-low to-surface-container md:aspect-[16/10] md:h-auto md:w-full md:rounded-none">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img
          src={isSeedImage(c.image) ? getPlaceholderImage(c.category?.name, c.name) : c.image!}
          alt={c.name}
          className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
        />
        {/* Badge chỉ dành cho thợ thật sự nổi bật — gắn đại trà làm badge mất nghĩa */}
        {c.rating_avg >= 4.5 && c.rating_count >= 3 ? (
          <div className="absolute left-1 top-1 md:left-4 md:top-4">
            <span className="flex items-center gap-1 rounded-full bg-tertiary-container px-1.5 py-0.5 text-[9px] font-bold text-on-tertiary-container shadow-sm md:px-3 md:py-1.5 md:text-xs">
              <span className="material-symbols-outlined fill hidden text-sm md:inline">verified</span>
              UY TÍN
            </span>
          </div>
        ) : null}
      </div>
      <div className="min-w-0 flex-1 md:p-8">
        <div className="mb-1 flex items-start justify-between gap-2 md:mb-3">
          <h3 className="truncate font-headline text-base font-bold text-on-surface md:text-xl">{c.name}</h3>
          <div className="flex shrink-0 items-center gap-1">
            <span className="material-symbols-outlined fill text-base text-tertiary md:text-lg">star</span>
            <span className="text-sm font-bold text-on-surface">{c.rating_avg.toFixed(1)}</span>
          </div>
        </div>
        <p className="mb-1 flex items-center gap-1.5 truncate text-xs font-medium text-primary md:mb-6 md:gap-2 md:text-sm">
          <span className="material-symbols-outlined hidden text-sm md:inline">home_repair_service</span>
          {c.category?.name ?? 'Dịch vụ chuyên nghiệp'}
          {c.experience > 0 ? ` • ${c.experience} năm KN` : ''}
        </p>
        {/* Mobile: gói khu vực + đánh giá vào 1 dòng text thay vì 2 ô to */}
        <p className="truncate text-xs text-on-surface-variant md:hidden">
          {[c.location.district, c.location.city].filter(Boolean).join(', ') || 'Toàn quốc'}
          {' • '}{c.rating_count}+ phản hồi
        </p>
        <div className="mb-8 hidden grid-cols-2 gap-4 md:grid">
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
        <div className="hidden items-center justify-between md:flex">
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
        <Link href={buildPageHref(current - 1)} className="flex h-12 w-12 items-center justify-center rounded-2xl bg-surface-container-low text-on-surface-variant transition-all hover:bg-primary hover:text-on-primary">
          <span className="material-symbols-outlined">chevron_left</span>
        </Link>
      )}
      {pages.map((p, idx) => (
        <span key={p} className="flex items-center">
          {idx > 0 && pages[idx - 1] !== p - 1 && <span className="mx-2 text-outline">…</span>}
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
        <Link href={buildPageHref(current + 1)} className="flex h-12 w-12 items-center justify-center rounded-2xl bg-surface-container-low text-on-surface-variant transition-all hover:bg-primary hover:text-on-primary">
          <span className="material-symbols-outlined">chevron_right</span>
        </Link>
      )}
    </div>
  );
}
