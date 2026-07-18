import Link from 'next/link';
import type { Route } from 'next';
import { unstable_cache } from 'next/cache';
import { api, ApiError } from '@/lib/api';
import type { Paginated, PublicCompanyListItem } from '@/lib/api-types';
import { getPlaceholderImage, isSeedImage } from '@/lib/placeholder-images';
import type { Metadata } from 'next';
import { getPublicCategories } from '@/lib/service-requests';
import { categoryStyle } from '@/lib/category-style';
import { Reveal } from '@/components/reveal';
import { CountUp } from '@/components/count-up';
import { TrustBadges } from '@/components/trust-badges';

export const metadata: Metadata = {
  alternates: { canonical: '/' },
};

// Structured data (SEO) — Organization + WebSite (ô tìm kiếm rich result).
const HOME_JSONLD = {
  '@context': 'https://schema.org',
  '@graph': [
    {
      '@type': 'Organization',
      name: 'Doitay.vn',
      url: 'https://doitay.vn',
      logo: 'https://doitay.vn/icon.png',
    },
    {
      '@type': 'WebSite',
      name: 'Doitay.vn',
      url: 'https://doitay.vn',
      potentialAction: {
        '@type': 'SearchAction',
        target: 'https://doitay.vn/tho?q={search_term_string}',
        'query-input': 'required name=search_term_string',
      },
    },
  ],
};

/**
 * Homepage — rebuild from frontend/stitch/trang_ch_m_i.
 *
 * Sections (top → bottom):
 *   1. Hero with primary search + verified-trust badge
 *   2. "3 bước" how-it-works
 *   3. Bento-grid danh mục thợ
 *   4. Thợ tiêu biểu (fetched from /api/v1/public/companies)
 *   5. Cam kết dịch vụ (dark band with stat tiles)
 *   6. CTA cho thợ
 *
 * Design system: azure_professional ("The Digital Craftsman") — see
 * frontend/stitch/azure_professional/DESIGN.md.
 */

const STEPS = [
  {
    icon: 'post_add',
    title: 'Tạo yêu cầu',
    desc: 'Mô tả chi tiết công việc bạn cần thực hiện và đặt lịch hẹn.',
  },
  {
    icon: 'how_to_reg',
    title: 'Chọn thợ',
    desc: 'Xem hồ sơ năng lực và đánh giá từ cộng đồng.',
  },
  {
    icon: 'task_alt',
    title: 'Hoàn thành',
    desc: 'Thanh toán trực tiếp cho thợ không qua bất cứ bên nào.',
  },
] as const;

const COMMITMENTS = [
  {
    icon: 'verified_user',
    title: 'Bảo vệ quyền lợi khách hàng',
    desc: 'Đúng thợ khách hàng chọn với hồ sơ chính xác nhất.',
  },
  {
    icon: 'payments',
    title: 'Minh bạch không qua trung gian',
    desc: 'Khách hàng làm việc trực tiếp với thợ, không sợ chi phí ẩn của nền tảng.',
  },
  {
    icon: 'support_agent',
    title: 'Hỗ trợ 24/7',
    desc: 'Đội ngũ CSKH luôn sẵn sàng giải quyết mọi thắc mắc.',
  },
] as const;

interface PlatformStats {
  approved_contractors: number;
  completed_appointments: number;
  satisfied_customers: number;
}

const loadStats = unstable_cache(
  async (): Promise<PlatformStats> => {
    try {
      const res = await api<{ data: PlatformStats }>('/public/stats');
      return res.data;
    } catch {
      return { approved_contractors: 0, completed_appointments: 0, satisfied_customers: 0 };
    }
  },
  ['platform-stats'],
  { revalidate: 300 },
);


async function loadFeaturedCompanies(): Promise<PublicCompanyListItem[]> {
  try {
    const res = await api<Paginated<PublicCompanyListItem>>(
      '/public/companies?per_page=3&sort=rating',
    );
    return res.data;
  } catch (e) {
    if (e instanceof ApiError) {
      // Backend may be down during local dev — render homepage with no
      // featured cards rather than crash the whole page.
      return [];
    }
    throw e;
  }
}

export default async function HomePage() {
  const [featured, stats, categories] = await Promise.all([
    loadFeaturedCompanies(),
    loadStats(),
    getPublicCategories().catch(() => []),
  ]);

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(HOME_JSONLD) }}
      />
      {/* ─── Hero ─────────────────────────────────────────────────────── */}
      <section className="relative flex min-h-[760px] items-center overflow-hidden px-6 pb-32 pt-16 md:px-8">
        <div className="absolute inset-0 z-0">
          <div className="absolute inset-0 bg-gradient-to-tr from-surface via-surface to-surface-container-low" />
          <div className="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-primary-fixed/40 blur-3xl" />
          <div className="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-primary-container/20 blur-3xl" />
        </div>

        <div className="relative z-10 mx-auto grid w-full max-w-7xl grid-cols-1 items-center gap-16 lg:grid-cols-2">
          <div className="space-y-8">
            <h1 className="font-headline text-5xl font-extrabold leading-[1.1] tracking-tight text-on-surface md:text-7xl">
              Kết nối bàn tay
              <br />
              <span className="text-primary">Thợ tài hoa.</span>
            </h1>
            <p className="max-w-lg text-lg leading-relaxed text-on-surface-variant md:text-xl">
              Tìm kiếm và kết nối trực tiếp với những thợ chuyên nghiệp hàng đầu
              Việt Nam cho mọi nhu cầu sửa chữa và sáng tạo của bạn.
            </p>

            <form
              action="/tho"
              className="flex max-w-2xl flex-col gap-2 rounded-[2rem] bg-surface-container-lowest p-2 shadow-ambient"
            >
              <div className="flex flex-col gap-2 md:flex-row">
                <div className="flex flex-1 items-center gap-3 rounded-full bg-surface-container-low px-6 py-4">
                  <span className="material-symbols-outlined text-primary">
                    search
                  </span>
                  <input
                    name="q"
                    type="text"
                    placeholder="Bạn cần thợ gì hôm nay?"
                    className="w-full border-none bg-transparent text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-0"
                  />
                </div>
                <button
                  type="submit"
                  className="rounded-full bg-primary px-10 py-4 font-headline text-lg font-bold text-on-primary transition-all hover:bg-primary-hover active:scale-95"
                >
                  Tìm ngay
                </button>
              </div>
            </form>

            <div className="flex gap-10 pt-4">
              <div className="flex flex-col">
                <CountUp
                  value={stats.approved_contractors}
                  suffix="+"
                  className="font-headline text-3xl font-bold text-primary"
                />
                <span className="text-sm font-medium text-outline">
                  Thợ xác thực
                </span>
              </div>
              <div className="flex flex-col">
                <CountUp
                  value={stats.completed_appointments}
                  suffix="+"
                  className="font-headline text-3xl font-bold text-primary"
                />
                <span className="text-sm font-medium text-outline">
                  Dự án hoàn thành
                </span>
              </div>
              <div className="flex flex-col">
                <CountUp
                  value={stats.satisfied_customers}
                  suffix="+"
                  className="font-headline text-3xl font-bold text-primary"
                />
                <span className="text-sm font-medium text-outline">
                  Khách hài lòng
                </span>
              </div>
            </div>
          </div>

          {/* Right — banner photo */}
          <div className="relative">
            <div className="relative z-10 h-[260px] overflow-hidden rounded-[2rem] shadow-ambient transition-transform duration-700 sm:h-[340px] lg:h-[600px] lg:rotate-2 lg:rounded-[3rem] lg:hover:rotate-0">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src="/banner-worker.jpg"
                alt="Thợ chuyên nghiệp doitay.vn"
                className="h-full w-full object-cover"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-secondary/60 via-transparent to-transparent" />
              <div className="absolute bottom-6 left-0 right-0 p-6 lg:bottom-16 lg:p-10">
                <p className="font-headline text-lg font-bold text-white lg:text-2xl">
                  Thợ chuyên nghiệp
                </p>
                <p className="mt-1 text-sm text-white/80 lg:text-base">
                  Sẵn sàng phục vụ tận nơi — toàn quốc.
                </p>
              </div>
            </div>
            <div className="absolute -bottom-8 -left-8 z-20 hidden items-center gap-4 rounded-3xl bg-surface-container-lowest p-6 shadow-ambient lg:flex">
              <div className="flex h-12 w-12 items-center justify-center rounded-full bg-tertiary-fixed text-on-tertiary-fixed">
                <span className="material-symbols-outlined" style={{ fontVariationSettings: "'FILL' 1" }}>verified</span>
              </div>
              <div>
                <p className="font-headline font-bold text-on-surface">
                  Đã kiểm duyệt
                </p>
                <p className="text-xs font-medium text-outline">
                  100% thợ có hồ sơ thật
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ─── Cam kết tin cậy (đưa tín hiệu an tâm lên sớm) ─────────────── */}
      <section className="bg-surface-container-low py-14">
        <div className="mx-auto max-w-7xl px-6 md:px-8">
          <Reveal>
            <TrustBadges />
          </Reveal>
        </div>
      </section>

      {/* ─── 3 bước — luồng quy trình ────────────────────────────────── */}
      <section className="bg-surface py-24 md:py-32">
        <div className="mx-auto max-w-6xl px-6 md:px-8">
          <div className="mb-14 space-y-3 text-center">
            <span className="text-sm font-bold uppercase tracking-[0.2em] text-primary">
              Quy trình chuyên nghiệp
            </span>
            <h2 className="font-headline text-4xl font-bold text-on-surface md:text-5xl">
              Chỉ 3 bước đơn giản
            </h2>
            <p className="mx-auto max-w-xl text-lg text-on-surface-variant">
              Từ lúc cần đến khi xong việc — nhanh, rõ ràng, không qua trung gian.
            </p>
          </div>
          <div className="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-8">
            {STEPS.map((step, i) => (
              <Reveal key={step.title} delay={i * 120} className="relative h-full">
                <div className="group relative h-full overflow-hidden rounded-3xl bg-surface-container-lowest p-8 shadow-soft ring-1 ring-outline-variant/10 transition-all duration-300 hover:-translate-y-1 hover:shadow-ambient">
                  {/* Số bước lớn — watermark tông brand */}
                  <span className="pointer-events-none absolute -right-1 -top-5 font-headline text-8xl font-black text-primary/[0.08]">
                    0{i + 1}
                  </span>
                  {/* Icon tile — tông teal thương hiệu */}
                  <div className="relative flex h-16 w-16 items-center justify-center rounded-2xl bg-primary text-on-primary shadow-ambient transition-transform duration-300 group-hover:scale-105">
                    <span
                      className="material-symbols-outlined text-[2rem]"
                      style={{ fontVariationSettings: "'FILL' 1" }}
                    >
                      {step.icon}
                    </span>
                  </div>
                  <p className="relative mt-6 text-sm font-bold uppercase tracking-wide text-primary">
                    Bước {i + 1}
                  </p>
                  <h3 className="relative mt-1 font-headline text-xl font-bold text-on-surface">
                    {step.title}
                  </h3>
                  <p className="relative mt-2 leading-relaxed text-on-surface-variant">
                    {step.desc}
                  </p>
                </div>
                {/* Mũi tên nối bước (desktop) */}
                {i < STEPS.length - 1 ? (
                  <span className="material-symbols-outlined absolute right-0 top-1/2 z-10 hidden -translate-y-1/2 translate-x-1/2 rounded-full bg-surface text-3xl text-primary shadow-ambient md:block">
                    chevron_right
                  </span>
                ) : null}
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* ─── Bento grid danh mục ─────────────────────────────────────── */}
      <section className="bg-surface-container-low py-32">
        <div className="mx-auto max-w-7xl px-6 md:px-8">
          <div className="mb-16 flex items-end justify-between">
            <div className="space-y-4">
              <span className="text-sm font-bold uppercase tracking-[0.2em] text-primary">
                Khám phá
              </span>
              <h2 className="font-headline text-4xl font-bold text-on-surface">
                Danh mục thợ chuyên môn
              </h2>
            </div>
            <Link
              href="/tho"
              className="hidden items-center gap-2 font-bold text-primary hover:underline md:flex"
            >
              Tất cả dịch vụ
              <span className="material-symbols-outlined">arrow_forward</span>
            </Link>
          </div>

          {/* Data-driven từ /public/categories — icon + màu khớp đúng từng ngành nghề
              (trước đây hardcode 4 nghề với icon/màu cố định, không khớp danh mục thật). */}
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 lg:grid-cols-5">
            {categories.map((cat, i) => {
              const style = categoryStyle(cat.name);
              return (
                <Reveal key={cat.id} delay={(i % 5) * 60}>
                  <Link
                    href={`/tho?category=${cat.id}` as Route}
                    className={`group flex h-full flex-col gap-5 rounded-4xl bg-surface-container-lowest p-6 transition-all duration-300 hover:-translate-y-1 ${style.soft} md:p-7`}
                  >
                    <div className={`flex h-14 w-14 items-center justify-center rounded-2xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 ${style.chip}`}>
                      <span className="material-symbols-outlined text-[28px]">{style.icon}</span>
                    </div>
                    <div className="flex items-center justify-between gap-2">
                      <h3 className="font-headline text-lg font-bold leading-tight text-on-surface">
                        {cat.name}
                      </h3>
                      <span className="material-symbols-outlined text-on-surface-variant opacity-0 transition-all group-hover:translate-x-1 group-hover:opacity-100">
                        arrow_forward
                      </span>
                    </div>
                  </Link>
                </Reveal>
              );
            })}
          </div>
        </div>
      </section>

      {/* ─── Thợ tiêu biểu ───────────────────────────────────────────── */}
      <section className="bg-surface py-32">
        <div className="mx-auto max-w-7xl px-6 md:px-8">
          <div className="mb-20 space-y-4 text-center">
            <span className="text-sm font-bold uppercase tracking-[0.2em] text-primary">
              Đội ngũ tinh hoa
            </span>
            <h2 className="font-headline text-4xl font-bold text-on-surface">
              Thợ giỏi tiêu biểu
            </h2>
          </div>

          {featured.length === 0 ? (
            <p className="text-center text-on-surface-variant">
              Chưa có dữ liệu thợ tiêu biểu — kiểm tra backend tại{' '}
              <code>:8000</code>.
            </p>
          ) : (
            <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
              {featured.map((c, i) => (
                <Reveal key={c.id} delay={i * 100} className="h-full">
                <Link
                  href={`/tho/${c.id}/${c.vanity_slug}`}
                  className="group block h-full rounded-4xl bg-surface-container-lowest p-8 shadow-soft transition-all duration-500 hover:-translate-y-1 hover:shadow-ambient"
                >
                  <div className="mb-8 flex items-start justify-between">
                    <div className="relative">
                      <div className="flex h-24 w-24 items-center justify-center overflow-hidden rounded-3xl bg-surface-container-low text-on-surface-variant">
                        {/* eslint-disable-next-line @next/next/no-img-element */}
                        <img
                          src={isSeedImage(c.image) ? getPlaceholderImage(c.category?.name, c.name) : c.image!}
                          alt={c.name}
                          className="h-full w-full object-cover grayscale transition-all duration-500 group-hover:grayscale-0"
                        />
                      </div>
                      {c.rating_avg >= 4.5 && c.rating_count >= 3 ? (
                        <div className="absolute -bottom-2 -right-2 rounded-full bg-tertiary-container px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-on-tertiary-container shadow-md">
                          Uy tín
                        </div>
                      ) : null}
                    </div>
                    <div className="text-right">
                      <div className="mb-1 flex items-center gap-1 font-bold text-tertiary">
                        <span className="material-symbols-outlined fill text-lg">
                          star
                        </span>
                        <span>{c.rating_avg.toFixed(1)}</span>
                      </div>
                      <p className="text-xs font-medium text-outline">
                        {c.rating_count} đánh giá
                      </p>
                    </div>
                  </div>
                  <div className="space-y-4">
                    <div>
                      <h3 className="font-headline text-xl font-bold transition-colors group-hover:text-primary">
                        {c.name}
                      </h3>
                      <p className="text-sm font-medium text-outline">
                        {c.category?.name ?? 'Dịch vụ chuyên nghiệp'}
                        {c.experience > 0 ? ` • ${c.experience} năm exp` : ''}
                      </p>
                    </div>
                    {c.location.city || c.location.district ? (
                      <div className="flex items-center gap-2 text-sm text-on-surface-variant">
                        <span className="material-symbols-outlined text-base text-primary">
                          location_on
                        </span>
                        <span>
                          {[c.location.district, c.location.city]
                            .filter(Boolean)
                            .join(', ')}
                        </span>
                      </div>
                    ) : null}
                    <div className="flex items-center justify-between pt-4">
                      <div className="text-sm">
                        <span className="text-outline">Hồ sơ:</span>
                        <span className="ml-1 font-bold text-on-surface">
                          Xem chi tiết
                        </span>
                      </div>
                      <span className="material-symbols-outlined text-primary">
                        arrow_forward
                      </span>
                    </div>
                  </div>
                </Link>
                </Reveal>
              ))}
            </div>
          )}
        </div>
      </section>

      {/* ─── Cam kết dịch vụ (dark band) ─────────────────────────────── */}
      <section className="relative overflow-hidden bg-on-background py-32 text-on-primary">
        <div className="pointer-events-none absolute right-0 top-0 h-full w-1/2 opacity-10">
          <div className="h-full w-full -translate-y-1/2 translate-x-1/2 rounded-full border-[100px] border-on-primary/20" />
        </div>
        <div className="relative mx-auto grid max-w-7xl grid-cols-1 items-center gap-24 px-6 md:px-8 lg:grid-cols-2">
          <div className="space-y-8">
            <span className="text-sm font-bold uppercase tracking-[0.2em] text-primary-fixed">
              Cam kết dịch vụ
            </span>
            <h2 className="font-headline text-5xl font-extrabold leading-tight tracking-tight">
              Chất lượng tạo nên
              <br />
              <span className="text-primary-container">
                Sự tin tưởng tuyệt đối.
              </span>
            </h2>
            <p className="text-lg leading-relaxed text-inverse-on-surface/70">
              Tại doitay.vn, chúng tôi đặt sự an toàn và hài lòng của bạn lên
              hàng đầu với quy trình kiểm soát đánh giá nghiêm ngặt.
            </p>
            <div className="space-y-6">
              {COMMITMENTS.map((c) => (
                <div key={c.title} className="flex items-start gap-4">
                  <div className="rounded-lg bg-primary/20 p-2">
                    <span className="material-symbols-outlined text-primary-fixed">
                      {c.icon}
                    </span>
                  </div>
                  <div>
                    <h4 className="font-headline text-lg font-bold">
                      {c.title}
                    </h4>
                    <p className="text-sm text-inverse-on-surface/60">
                      {c.desc}
                    </p>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div className="mt-12 space-y-4">
              <div className="h-64 rounded-4xl bg-gradient-to-br from-primary-container to-primary" />
              <div className="flex h-48 flex-col justify-end rounded-4xl bg-primary-container p-8 text-on-primary-container">
                <CountUp value={98} suffix="%" className="font-headline text-4xl font-black" />
                <span className="font-bold">Hài lòng</span>
              </div>
            </div>
            <div className="space-y-4">
              <div className="flex h-48 flex-col justify-end rounded-4xl bg-tertiary-container p-8 text-on-tertiary-container">
                <CountUp value={15} suffix="+" className="font-headline text-4xl font-black" />
                <span className="font-bold">Khu vực</span>
              </div>
              <div className="h-64 rounded-4xl bg-gradient-to-br from-tertiary-fixed to-tertiary-container" />
            </div>
          </div>
        </div>
      </section>

      {/* ─── CTA cho thợ ─────────────────────────────────────────────── */}
      <section className="bg-surface py-32">
        <div className="relative mx-auto max-w-5xl overflow-hidden rounded-[4rem] bg-primary p-16 text-center">
          <div className="absolute inset-0 bg-gradient-to-br from-primary to-primary-container opacity-50" />
          <div className="relative z-10 space-y-8">
            <h2 className="font-headline text-4xl font-extrabold text-on-primary md:text-5xl">
              Bạn là thợ lành nghề?
            </h2>
            <p className="mx-auto max-w-2xl text-xl text-primary-fixed/80">
              Gia nhập cộng đồng doitay.vn để tiếp cận hàng ngàn khách hàng mới
              mỗi ngày và xây dựng uy tín cá nhân chuyên nghiệp.
            </p>
            <div className="flex flex-col justify-center gap-4 md:flex-row">
              <Link
                href={'/tuyen-dung-tho' as Route}
                className="rounded-full bg-surface-container-lowest px-10 py-5 font-headline text-lg font-bold text-primary shadow-ambient transition-all hover:bg-surface-container-low"
              >
                Trở thành thợ
              </Link>
              <Link
                href={'/dang-ky' as Route}
                className="rounded-full border-2 border-on-primary/30 px-10 py-5 font-headline text-lg font-bold text-on-primary transition-all hover:bg-on-primary/10"
              >
                Đăng ký ngay
              </Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
