import Link from 'next/link';
import type { Route } from 'next';
import { api, ApiError } from '@/lib/api';
import type { Paginated, PublicCompanyListItem } from '@/lib/api-types';
import { getPlaceholderImage, isSeedImage } from '@/lib/placeholder-images';

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

const SMALL_CATS = [
  { icon: 'water_drop', title: 'Thợ Nước', desc: 'Sửa đường ống, vòi sen, lavabo và máy bơm.' },
  { icon: 'construction', title: 'Thợ Xây', desc: 'Cải tạo nhà, lát gạch và sơn bả chuyên nghiệp.' },
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
  const featured = await loadFeaturedCompanies();

  return (
    <>
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
              action="/cong-ty"
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
                  className="rounded-full bg-gradient-to-r from-primary to-primary-container px-10 py-4 font-headline text-lg font-bold text-on-primary transition-all active:scale-95"
                >
                  Tìm ngay
                </button>
              </div>
            </form>

            <div className="flex gap-10 pt-4">
              <div className="flex flex-col">
                <span className="font-headline text-3xl font-bold text-primary">
                  5,000+
                </span>
                <span className="text-sm font-medium text-outline">
                  Thợ xác thực
                </span>
              </div>
              <div className="flex flex-col">
                <span className="font-headline text-3xl font-bold text-primary">
                  12k+
                </span>
                <span className="text-sm font-medium text-outline">
                  Dự án hoàn thành
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

      {/* ─── 3 bước ───────────────────────────────────────────────────── */}
      <section className="bg-surface py-32">
        <div className="mx-auto max-w-7xl px-6 md:px-8">
          <div className="mb-20 space-y-4 text-center">
            <span className="text-sm font-bold uppercase tracking-[0.2em] text-primary">
              Quy trình chuyên nghiệp
            </span>
            <h2 className="font-headline text-4xl font-bold text-on-surface">
              Chỉ 3 bước đơn giản
            </h2>
          </div>
          <div className="relative grid grid-cols-1 gap-16 md:grid-cols-3">
            {STEPS.map((step, i) => (
              <div
                key={step.title}
                className="flex flex-col items-center space-y-6 text-center"
              >
                <div className="relative flex h-20 w-20 items-center justify-center rounded-full border-4 border-surface bg-surface-container-lowest text-primary shadow-ambient">
                  <span className="material-symbols-outlined text-4xl">
                    {step.icon}
                  </span>
                  <span className="absolute -right-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-on-primary">
                    {i + 1}
                  </span>
                </div>
                <div className="space-y-2">
                  <h3 className="font-headline text-xl font-bold">
                    {step.title}
                  </h3>
                  <p className="px-4 leading-relaxed text-on-surface-variant">
                    {step.desc}
                  </p>
                </div>
              </div>
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
              href="/cong-ty"
              className="hidden items-center gap-2 font-bold text-primary hover:underline md:flex"
            >
              Tất cả dịch vụ
              <span className="material-symbols-outlined">arrow_forward</span>
            </Link>
          </div>

          <div className="grid h-auto grid-cols-2 gap-6 md:h-[600px] md:grid-cols-4">
            {/* Hero card — Thợ Điện */}
            <div className="group relative col-span-2 row-span-2 flex flex-col justify-between overflow-hidden rounded-5xl bg-primary-fixed p-10">
              <div className="relative z-10">
                <h3 className="mb-4 font-headline text-3xl font-extrabold leading-tight text-on-primary-fixed">
                  Thợ Điện &amp;
                  <br />
                  Hệ Thống Mạng
                </h3>
                <p className="max-w-[200px] font-medium text-on-primary-fixed-variant opacity-80">
                  Xử lý mọi sự cố điện dân dụng và công nghiệp.
                </p>
              </div>
              <div className="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl bg-surface-container-lowest/40 backdrop-blur">
                <span className="material-symbols-outlined text-3xl text-primary">
                  bolt
                </span>
              </div>
            </div>

            {SMALL_CATS.map((cat) => (
              <div
                key={cat.title}
                className="group col-span-2 flex flex-col justify-between rounded-5xl bg-surface-container-highest p-8 transition-colors duration-500 hover:bg-secondary-container md:col-span-1"
              >
                <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10">
                  <span className="material-symbols-outlined text-primary">
                    {cat.icon}
                  </span>
                </div>
                <div>
                  <h3 className="mb-2 font-headline text-xl font-bold">
                    {cat.title}
                  </h3>
                  <p className="text-sm text-on-surface-variant">{cat.desc}</p>
                </div>
              </div>
            ))}

            {/* Wide card — Thợ Mộc */}
            <div className="col-span-2 flex items-center justify-between rounded-5xl bg-tertiary-fixed p-10 md:col-span-2">
              <div className="max-w-[60%]">
                <h3 className="mb-3 font-headline text-2xl font-bold text-on-tertiary-fixed">
                  Thợ Mộc &amp; Nội Thất
                </h3>
                <p className="text-on-tertiary-fixed-variant">
                  Đóng mới và sửa chữa nội thất gỗ gia đình.
                </p>
              </div>
              <div className="flex h-24 w-24 items-center justify-center rounded-full bg-surface-container-lowest/50">
                <span className="material-symbols-outlined text-4xl text-on-tertiary-fixed-variant">
                  chair
                </span>
              </div>
            </div>
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
              {featured.map((c) => (
                <Link
                  key={c.id}
                  href={`/cong-ty/${c.id}/${c.vanity_slug}`}
                  className="group rounded-4xl bg-surface-container-lowest p-8 shadow-soft transition-all duration-500 hover:-translate-y-1 hover:shadow-ambient"
                >
                  <div className="mb-8 flex items-start justify-between">
                    <div className="relative">
                      <div className="flex h-24 w-24 items-center justify-center overflow-hidden rounded-3xl bg-surface-container-low text-on-surface-variant">
                        {/* eslint-disable-next-line @next/next/no-img-element */}
                        <img
                          src={isSeedImage(c.image) ? getPlaceholderImage(c.category?.name) : c.image!}
                          alt={c.name}
                          className="h-full w-full object-cover grayscale transition-all duration-500 group-hover:grayscale-0"
                        />
                      </div>
                      <div className="absolute -bottom-2 -right-2 rounded-full bg-tertiary-container px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-on-tertiary-container shadow-md">
                        Verified
                      </div>
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
                <span className="font-headline text-4xl font-black">98%</span>
                <span className="font-bold">Hài lòng</span>
              </div>
            </div>
            <div className="space-y-4">
              <div className="flex h-48 flex-col justify-end rounded-4xl bg-tertiary-container p-8 text-on-tertiary-container">
                <span className="font-headline text-4xl font-black">15+</span>
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
                href={'/dang-ky-tho' as Route}
                className="rounded-full bg-surface-container-lowest px-10 py-5 font-headline text-lg font-bold text-primary shadow-ambient transition-all hover:bg-surface-container-low"
              >
                Đăng ký thợ ngay
              </Link>
              <Link
                href="/cong-ty"
                className="rounded-full border-2 border-on-primary/30 px-10 py-5 font-headline text-lg font-bold text-on-primary transition-all hover:bg-on-primary/10"
              >
                Tìm hiểu thêm
              </Link>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
