import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { getSiteSettings } from '@/lib/site-settings';
import { api } from '@/lib/api';

export const metadata: Metadata = {
  title: 'Trở thành thợ trên Doitay — miễn phí, không trung gian',
  description:
    'Tạo hồ sơ nghề chuyên nghiệp miễn phí, nhận khách trong khu vực và xây dựng uy tín cá nhân trên Doitay.vn.',
  alternates: { canonical: '/tuyen-dung-tho' },
};

const HERO_IMG =
  'https://lh3.googleusercontent.com/aida-public/AB6AXuDCML3pSiE7pVqYSC-eJXH1ISSGyaCDDBNLnkMgLl-bNmK4ytr5bUnTkDdcc1KWZHF8cEiq_1xRNMvW3ePeU-kipbq5Rzb-CZcv3dz-6Q0YaYtDQygF9sD4kEKoxfiLbq0E1p3RwUSmwBDiRarVkh3p2nuuJMVA599-89OOGnZDpM5cpxXEUNL0r18uuuvyRJbsTnIFeTF7liuCxhOSp6BvCOk7pORFAwRj1qKeLU4eQeaV1_sEZJIqNGDEbNaR7DGHWeKZQAPOxLJJ';
const WORK_IMG_1 =
  'https://lh3.googleusercontent.com/aida-public/AB6AXuDffQi0SWYQ52keOxy-xkYeuk71H49Uc7mX6czBDzw8zfNZO5D89bNW5ZF2IUfGPbaoyneFLdk7gwDDMiRNi_6FMlg92tEPu92uPh5btC4rcp82d2y7nN3XWnZK2FMR9sFSEwoFOWc_6EZSM1A9LkRfQkESMMCXkiOcd7LNMjRTLZeDJSyamg1Vz9D412nSbffiqukOFAWkYkCgkIox0QC7up7Y6VXwedWdPzOhr7nDZtb2HjBmxkYgZgyY_5-9Kyi6fyTj86FoXnK0';
const WORK_IMG_2 =
  'https://lh3.googleusercontent.com/aida-public/AB6AXuDz1ZtC7_4ZuRrzTLblk7wSU3LMvgzrh_2LX0TwST3uzXAbV-HtVhh96bMk96Hy5snSKKW7VGV-HGIB-zjMnSZzhMwOxVw9aRN95CpafZHWWNCQi7LvGhc-5jTarc7Pbo5qTT_4SNj3HtdUKAwwZ8nVkPH4F2Cme1YluBGhxKzMoJNFlgIHPf6YrtozOO9Z12g-zCR7t6sSJTIaWLbx0_AJuh6Fk-Vrz0xbEdKsjKc22lhK-_3tCBMnMpylyETLNs4B1StMiYt_Eblb';

const BENEFITS = [
  {
    icon: 'badge',
    title: 'Hồ sơ nghề miễn phí',
    desc: 'Trang hồ sơ chuyên nghiệp với ảnh công việc, bảng giá và đánh giá thật — gửi khách bằng một đường link hoặc mã QR, khỏi giải thích tay nghề nhiều lần.',
  },
  {
    icon: 'person_search',
    title: 'Khách tìm đến bạn',
    desc: 'Khách trong khu vực tìm thợ theo nghề và quận huyện — hồ sơ tốt, đánh giá cao sẽ được ưu tiên hiển thị trên nền tảng.',
  },
  {
    icon: 'payments',
    title: 'Không phí trung gian',
    desc: 'Bạn làm việc và nhận tiền trực tiếp từ khách hàng. Doitay không thu bất kỳ phí hoa hồng nào trên công sức lao động của bạn.',
  },
];

async function loadContractorCount(): Promise<number> {
  try {
    const res = await api<{ data: { approved_contractors: number } }>('/public/stats');
    return res.data.approved_contractors ?? 0;
  } catch {
    return 0;
  }
}

export default async function TuyenDungThoPage() {
  const [settings, contractorCount] = await Promise.all([getSiteSettings(), loadContractorCount()]);
  const phone = settings.contact.phone;
  const joinLine =
    contractorCount > 0
      ? `Gia nhập ${contractorCount.toLocaleString('vi-VN')}+ thợ chuyên nghiệp đã đăng ký trên hệ thống Doitay.vn`
      : 'Gia nhập cộng đồng thợ chuyên nghiệp đang xây dựng uy tín trên Doitay.vn';

  return (
    <div className="bg-surface text-on-surface">
      {/* ─── Hero ─────────────────────────────────────────────────────── */}
      <section className="relative flex min-h-[600px] items-center overflow-hidden bg-on-surface md:min-h-[720px]">
        <div className="absolute inset-0 z-0">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src={HERO_IMG}
            alt="Đội ngũ thợ chuyên nghiệp Việt Nam"
            className="h-full w-full object-cover opacity-40 mix-blend-overlay"
          />
          <div className="absolute inset-0 bg-gradient-to-r from-on-surface via-on-surface/85 to-on-surface/40" />
        </div>

        <div className="relative z-10 mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-6 py-20 md:px-8 lg:grid-cols-2">
          <div className="space-y-7">
            <span className="inline-block rounded-full bg-tertiary-container px-4 py-1.5 text-sm font-bold tracking-wider text-on-tertiary-container">
              DÀNH CHO THỢ &amp; ĐỘI NHÓM
            </span>
            <h1 className="font-headline text-4xl font-extrabold leading-[1.1] tracking-tight text-surface-bright sm:text-5xl lg:text-6xl">
              Tay nghề của bạn
              <br />
              <span className="text-primary-fixed-dim">xứng đáng</span> được nhiều khách biết đến.
            </h1>
            <p className="max-w-lg text-lg leading-relaxed text-surface-variant md:text-xl">
              Tạo hồ sơ nghề chuyên nghiệp hoàn toàn miễn phí trên Doitay — nơi khách hàng tìm thợ
              uy tín theo khu vực.
            </p>
            <div className="flex flex-col gap-4 pt-2 sm:flex-row">
              <Link
                href={'/dang-ky' as Route}
                className="inline-flex items-center justify-center rounded-xl bg-primary-container px-8 py-4 text-lg font-bold text-on-primary-container transition-transform hover:scale-[1.02] active:scale-95"
              >
                Tạo hồ sơ miễn phí
              </Link>
              {phone ? (
                <a
                  href={`tel:${phone}`}
                  className="inline-flex items-center justify-center gap-2 rounded-xl border border-surface-variant/30 bg-white/10 px-8 py-4 text-lg font-semibold text-surface-bright backdrop-blur-md transition-colors hover:bg-white/20"
                >
                  <span className="material-symbols-outlined">call</span>
                  {phone}
                </a>
              ) : null}
            </div>
          </div>
        </div>
      </section>

      {/* ─── Lợi ích ──────────────────────────────────────────────────── */}
      <section className="bg-surface py-20 md:py-24">
        <div className="mx-auto max-w-7xl px-6 md:px-8">
          <div className="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-10">
            {BENEFITS.map((b) => (
              <div
                key={b.title}
                className="group space-y-5 rounded-3xl p-6 transition-colors duration-300 hover:bg-surface-container-low md:p-8"
              >
                <div className="flex h-16 w-16 items-center justify-center rounded-2xl bg-secondary-container text-primary transition-transform group-hover:rotate-6">
                  <span
                    className="material-symbols-outlined text-4xl"
                    style={{ fontVariationSettings: "'FILL' 1" }}
                  >
                    {b.icon}
                  </span>
                </div>
                <h3 className="font-headline text-2xl font-bold text-on-surface">{b.title}</h3>
                <p className="text-lg leading-relaxed text-on-surface-variant">{b.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ─── 3 bước — bento ───────────────────────────────────────────── */}
      <section className="overflow-hidden bg-surface-container-low py-20 md:py-24">
        <div className="mx-auto max-w-7xl px-6 md:px-8">
          <div className="mb-14 space-y-4 text-center">
            <h2 className="font-headline text-3xl font-extrabold tracking-tight text-on-surface md:text-5xl">
              Bắt đầu trong 3 bước
            </h2>
            <div className="mx-auto h-1 w-24 rounded-full bg-primary" />
          </div>

          <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
            {/* Bước 1 */}
            <div className="group relative flex flex-col justify-between overflow-hidden rounded-3xl bg-surface-container-highest p-8 md:p-10 lg:col-span-4">
              <span className="pointer-events-none absolute -right-4 -top-6 font-headline text-9xl font-black text-on-surface/5">
                1
              </span>
              <div className="relative z-10 space-y-3">
                <h3 className="font-headline text-2xl font-bold text-primary md:text-3xl">
                  Đăng ký tài khoản
                </h3>
                <p className="text-lg leading-relaxed text-on-surface-variant">
                  Chỉ cần số điện thoại — chưa đến 2 phút để bắt đầu hành trình chuyên nghiệp của bạn.
                </p>
              </div>
              {/* Mock UI xác thực */}
              <div className="relative z-10 mt-8 transition-transform duration-500 group-hover:-translate-y-2">
                <div className="rounded-2xl border border-outline-variant/30 bg-surface p-5">
                  <div className="mb-4 flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-secondary-container">
                      <span className="material-symbols-outlined text-primary">phone_android</span>
                    </div>
                    <div className="h-2 w-28 rounded-full bg-outline-variant" />
                  </div>
                  <div className="space-y-3">
                    <div className="h-10 w-full rounded-lg border border-outline-variant/20 bg-surface-container" />
                    <div className="flex h-10 w-full items-center justify-center rounded-lg bg-primary-container text-sm font-bold text-on-primary-container">
                      Gửi mã xác thực
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Bước 2 */}
            <div className="group relative flex flex-col items-center gap-8 overflow-hidden rounded-3xl bg-on-surface p-8 md:p-10 lg:col-span-8 lg:flex-row">
              <span className="pointer-events-none absolute right-0 top-0 font-headline text-9xl font-black text-surface/5">
                2
              </span>
              <div className="relative z-10 flex-1 space-y-3">
                <h3 className="font-headline text-2xl font-bold text-primary-fixed-dim md:text-3xl">
                  Tạo hồ sơ nghề
                </h3>
                <p className="max-w-md text-lg leading-relaxed text-surface-variant">
                  Thêm ảnh công việc thật, kỹ năng chuyên môn và bảng giá minh bạch. Một hồ sơ đầy đủ
                  giúp khách hàng tin tưởng bạn hơn 80%.
                </p>
              </div>
              <div className="relative z-10 flex w-full flex-1 gap-4 overflow-x-auto py-2 hide-scrollbar">
                {[WORK_IMG_1, WORK_IMG_2].map((src, i) => (
                  <div
                    key={i}
                    className="h-72 w-56 shrink-0 overflow-hidden rounded-2xl bg-secondary-container md:h-80 md:w-64"
                  >
                    {/* eslint-disable-next-line @next/next/no-img-element */}
                    <img
                      src={src}
                      alt="Ảnh công việc mẫu"
                      className="h-full w-full object-cover grayscale transition-all duration-500 hover:grayscale-0"
                    />
                  </div>
                ))}
              </div>
            </div>

            {/* Bước 3 */}
            <div className="flex flex-col items-center justify-between gap-8 rounded-3xl border border-primary-container/30 bg-primary-container/20 p-8 md:flex-row md:p-10 lg:col-span-12">
              <div className="space-y-3">
                <div className="flex items-center gap-4">
                  <div className="flex h-12 w-12 items-center justify-center rounded-full bg-primary font-headline text-xl font-bold text-on-primary">
                    3
                  </div>
                  <h3 className="font-headline text-2xl font-bold text-on-surface md:text-3xl">
                    Chia sẻ &amp; Nhận việc
                  </h3>
                </div>
                <p className="max-w-xl text-lg leading-relaxed text-on-surface-variant">
                  Gửi hồ sơ cho khách quen qua Zalo/Facebook, nhận yêu cầu công việc mới từ khách hàng
                  trong khu vực lân cận của bạn.
                </p>
              </div>
              <div className="flex gap-4">
                {['share', 'notifications_active', 'chat'].map((icon) => (
                  <div
                    key={icon}
                    className="flex h-16 w-16 items-center justify-center rounded-2xl bg-surface text-primary shadow-soft"
                  >
                    <span className="material-symbols-outlined text-4xl">{icon}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ─── Trích dẫn sứ mệnh ────────────────────────────────────────── */}
      <section className="bg-on-surface py-20 text-center md:py-24">
        <div className="mx-auto max-w-4xl space-y-7 px-6">
          <span className="material-symbols-outlined text-6xl text-primary-fixed-dim">
            format_quote
          </span>
          <h2 className="font-headline text-3xl font-bold leading-tight text-surface-bright md:text-5xl">
            &ldquo;Tôn vinh những đôi bàn tay Việt&rdquo;
          </h2>
          <p className="mx-auto max-w-2xl text-lg italic leading-relaxed text-surface-variant md:text-xl">
            Sứ mệnh của chúng tôi là xây dựng một cộng đồng thợ chuyên nghiệp, nơi kỹ năng thực sự
            được ghi nhận và giá trị lao động được trân trọng.
          </p>
        </div>
      </section>

      {/* ─── CTA cuối ─────────────────────────────────────────────────── */}
      <section className="flex flex-col items-center bg-surface py-24 text-center md:py-32">
        <div className="max-w-3xl space-y-8 px-6">
          <h2 className="font-headline text-4xl font-extrabold tracking-tight text-on-surface md:text-6xl">
            Bạn đã sẵn sàng để <span className="text-primary">nâng tầm</span> nghề nghiệp?
          </h2>
          <p className="text-lg text-on-surface-variant md:text-xl">{joinLine}</p>
          <div className="flex flex-col items-center gap-6">
            <Link
              href={'/dang-ky' as Route}
              className="rounded-2xl bg-primary px-10 py-5 font-headline text-xl font-bold text-on-primary shadow-ambient transition-transform hover:scale-105 active:scale-95 md:px-12"
            >
              Đăng ký ngay — Miễn phí
            </Link>
            <p className="flex flex-wrap items-center justify-center gap-1.5 font-medium text-on-surface-variant">
              Đã có tài khoản?{' '}
              <Link href="/login" className="font-bold text-primary underline">
                Đăng nhập
              </Link>{' '}
              rồi vào mục &ldquo;Đăng ký thợ&rdquo;.
            </p>
          </div>
        </div>
      </section>
    </div>
  );
}
