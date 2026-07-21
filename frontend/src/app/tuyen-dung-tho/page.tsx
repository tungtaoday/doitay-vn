import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { redirect } from 'next/navigation';
import { getSiteSettings } from '@/lib/site-settings';
import { getPublicCategories } from '@/lib/service-requests';
import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { AuthUser } from '@/lib/api-types';
import { HeroPhoneForm } from './hero-phone-form';

export const metadata: Metadata = {
  title: 'Trở thành thợ trên Doitay — miễn phí, không trung gian',
  description:
    'Tạo hồ sơ nghề chuyên nghiệp miễn phí, nhận khách trong khu vực và xây dựng uy tín cá nhân trên Doitay.vn.',
  alternates: { canonical: '/tuyen-dung-tho' },
};

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

// Số liệu MINH HỌA tiềm năng (không phải cam kết) — dùng để trực quan hoá lợi ích.
const INCOME = [6, 9, 12, 16, 20, 25]; // triệu đồng/tháng
const PROOF = [
  { value: '15–25tr*', label: 'Thu nhập tiềm năng mỗi tháng' },
  { value: '4.9★', label: 'Điểm đánh giá trung bình' },
  { value: '340+', label: 'Lượt khách xem một hồ sơ tốt*' },
  { value: '80%', label: 'Khách tin tưởng hơn khi hồ sơ đủ ảnh' },
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
  // Đã đăng nhập: nếu đã là thợ có công ty thì vào thẳng khu quản lý; còn lại
  // vẫn xem landing nhưng CTA sẽ đi tới form tạo hồ sơ thợ (không bắt đăng ký lại).
  const token = await getToken();
  let isAuthenticated = false;
  if (token) {
    try {
      const me = await api<{ data: AuthUser }>('/auth/me', { token });
      if (me.data.has_company) redirect('/vi/tho/lich-hen');
      isAuthenticated = true;
    } catch { /* token hỏng → coi như khách */ }
  }

  const [settings, contractorCount, categories] = await Promise.all([
    getSiteSettings(),
    loadContractorCount(),
    getPublicCategories().catch(() => []),
  ]);
  const categoryCount = categories.length;
  const joinLine =
    contractorCount > 0
      ? `Gia nhập ${contractorCount.toLocaleString('vi-VN')}+ thợ chuyên nghiệp đã đăng ký trên hệ thống Doitay.vn`
      : 'Gia nhập cộng đồng thợ chuyên nghiệp đang xây dựng uy tín trên Doitay.vn';

  // Dải số liệu — dùng dữ liệu THẬT khi có; con số minh họa được ghi rõ "minh họa".
  const STATS = [
    { value: contractorCount > 0 ? `${contractorCount}+` : 'Đang mở', label: 'Thợ đang hoạt động' },
    { value: categoryCount > 0 ? `${categoryCount}` : '10+', label: 'Nhóm nghề' },
    { value: '0đ', label: 'Phí hoa hồng' },
    { value: '24/7', label: 'Hỗ trợ CSKH' },
  ];

  return (
    <div className="bg-surface text-on-surface">
      {/* ─── Hero — ảnh đội thợ thật ──────────────────────────────────── */}
      <section className="relative overflow-hidden bg-surface">
        <div className="absolute inset-0 z-0">
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src="/hero-tho.jpg"
            alt="Đội ngũ thợ chuyên nghiệp trên Doitay"
            className="h-full w-full object-cover object-right"
          />
          {/* Trái phủ sáng để chữ tối đọc rõ, phải trong dần để lộ ảnh thợ */}
          <div className="absolute inset-0 bg-gradient-to-r from-surface via-surface/90 to-surface/20 md:to-transparent" />
          <div className="absolute inset-0 bg-surface/45 md:bg-transparent" />
        </div>

        <div className="relative z-10 mx-auto max-w-7xl px-6 py-20 md:px-8 md:py-28 lg:py-36">
          <div className="max-w-xl space-y-6">
            <span className="inline-block rounded-full bg-tertiary-container px-4 py-1.5 text-sm font-bold tracking-wider text-on-tertiary-container">
              DÀNH CHO THỢ &amp; ĐỘI NHÓM
            </span>
            <h1 className="font-headline text-4xl font-extrabold leading-[1.1] tracking-tight text-on-surface sm:text-5xl lg:text-6xl">
              Tay nghề của bạn
              <br />
              <span className="text-primary">xứng đáng</span> được nhiều khách biết đến.
            </h1>
            <p className="max-w-md text-lg leading-relaxed text-on-surface-variant">
              Tạo hồ sơ nghề chuyên nghiệp hoàn toàn miễn phí — khách hàng tìm thợ uy tín theo khu
              vực sẽ thấy bạn.
            </p>

            <HeroPhoneForm isAuthenticated={isAuthenticated} />

            <div className="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-on-surface-variant">
              <span className="flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-primary">check_circle</span>
                Miễn phí 100%
              </span>
              <span className="flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-primary">check_circle</span>
                Không phí hoa hồng
              </span>
              {contractorCount > 0 ? (
                <span className="flex items-center gap-1.5">
                  <span className="material-symbols-outlined text-base text-primary">groups</span>
                  {contractorCount}+ thợ đã tham gia
                </span>
              ) : null}
            </div>
          </div>
        </div>
      </section>

      {/* ─── Dải số liệu chứng minh ───────────────────────────────────── */}
      <section className="bg-surface-container-low py-12 md:py-16">
        <div className="mx-auto grid max-w-5xl grid-cols-2 gap-6 px-6 md:grid-cols-4 md:px-8">
          {STATS.map((s) => (
            <div key={s.label} className="text-center">
              <p className="font-headline text-3xl font-extrabold text-primary md:text-4xl">{s.value}</p>
              <p className="mt-1 text-sm font-medium text-on-surface-variant">{s.label}</p>
            </div>
          ))}
        </div>
      </section>

      {/* ─── Thu nhập & uy tín — biểu đồ + số liệu ấn tượng ────────────── */}
      <section className="bg-surface py-20 md:py-24">
        <div className="mx-auto max-w-6xl px-6 md:px-8">
          <div className="mb-12 max-w-2xl">
            <h2 className="font-headline text-3xl font-extrabold tracking-tight text-on-surface md:text-4xl">
              Hồ sơ tốt = nhiều việc hơn, thu nhập đều hơn
            </h2>
            <p className="mt-3 text-lg text-on-surface-variant">
              Thợ có hồ sơ đầy đủ — ảnh công việc, đánh giá thật, bảng giá rõ ràng — được khách ưu
              tiên chọn, nên lượng việc tăng dần theo từng tháng.
            </p>
          </div>

          <div className="grid items-stretch gap-6 lg:grid-cols-5">
            {/* Biểu đồ thu nhập */}
            <div className="rounded-3xl bg-surface-container-low p-6 md:p-8 lg:col-span-3">
              <div className="mb-5 flex items-center justify-between">
                <p className="font-headline text-lg font-bold text-on-surface">
                  Thu nhập theo tháng (triệu đồng)
                </p>
                <span className="rounded-full bg-surface-container-highest px-2.5 py-1 text-[11px] font-semibold text-on-surface-variant">
                  minh họa
                </span>
              </div>
              <svg viewBox="0 0 320 150" className="w-full" role="img" aria-label="Biểu đồ thu nhập tăng dần qua 6 tháng">
                {INCOME.map((v, i) => {
                  const h = (v / 25) * 100;
                  const x = 8 + i * 50;
                  const y = 120 - h;
                  return (
                    <g key={i}>
                      <rect
                        x={x}
                        y={y}
                        width="34"
                        height={h}
                        rx="6"
                        className={i === INCOME.length - 1 ? 'fill-primary' : 'fill-primary-container'}
                      />
                      <text x={x + 17} y={y - 6} textAnchor="middle" className="fill-on-surface text-[11px] font-bold">
                        {v}
                      </text>
                      <text x={x + 17} y="142" textAnchor="middle" className="fill-on-surface-variant text-[10px]">
                        T{i + 1}
                      </text>
                    </g>
                  );
                })}
              </svg>
            </div>

            {/* Số liệu ấn tượng */}
            <div className="grid grid-cols-2 gap-4 lg:col-span-2">
              {PROOF.map((p) => (
                <div
                  key={p.label}
                  className="flex flex-col justify-center rounded-2xl bg-surface-container-low p-5"
                >
                  <p className="font-headline text-3xl font-extrabold text-primary">{p.value}</p>
                  <p className="mt-1 text-sm leading-snug text-on-surface-variant">{p.label}</p>
                </div>
              ))}
            </div>
          </div>

          <p className="mt-6 text-xs text-outline">
            * Số liệu minh họa tiềm năng dựa trên thợ có hồ sơ đầy đủ — không phải cam kết thu nhập.
          </p>
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
              <span aria-hidden="true" className="pointer-events-none absolute -right-4 -top-6 font-headline text-9xl font-black text-on-surface/5">
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
                    <div className="flex h-10 w-full items-center justify-between rounded-lg border border-outline-variant/30 bg-surface px-3 text-sm font-semibold text-on-surface">
                      <span>0912 345 678</span>
                      <span
                        className="material-symbols-outlined text-lg text-primary"
                        style={{ fontVariationSettings: "'FILL' 1" }}
                      >
                        check_circle
                      </span>
                    </div>
                    <div className="flex h-10 w-full items-center justify-center rounded-lg bg-primary-container text-sm font-bold text-on-primary-container">
                      Gửi mã xác thực
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Bước 2 */}
            <div className="group relative flex flex-col items-center gap-8 overflow-hidden rounded-3xl bg-on-surface p-8 md:p-10 lg:col-span-8 lg:flex-row">
              <span aria-hidden="true" className="pointer-events-none absolute right-0 top-0 font-headline text-9xl font-black text-surface/5">
                2
              </span>
              <div className="relative z-10 flex-1 space-y-3">
                <h3 className="font-headline text-2xl font-bold text-primary-fixed-dim md:text-3xl">
                  Tạo hồ sơ nghề
                </h3>
                <p className="max-w-md text-lg leading-relaxed text-inverse-on-surface">
                  Thêm ảnh công việc thật, kỹ năng chuyên môn và bảng giá minh bạch. Một hồ sơ đầy đủ
                  giúp khách hàng tin tưởng bạn hơn 80%.
                </p>
              </div>
              <div
                className="relative z-10 flex w-full flex-1 gap-4 overflow-x-auto py-2 hide-scrollbar"
                tabIndex={0}
                role="group"
                aria-label="Ảnh công việc mẫu — cuộn ngang để xem thêm"
              >
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
          <p className="mx-auto max-w-2xl text-lg italic leading-relaxed text-inverse-on-surface md:text-xl">
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
