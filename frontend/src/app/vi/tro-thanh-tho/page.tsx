import Link from 'next/link';
import type { Route } from 'next';
import { redirect } from 'next/navigation';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser } from '@/lib/api-types';
import { getSiteSettings } from '@/lib/site-settings';

export const metadata = {
  title: 'Trở thành thợ — doitay.vn',
};

const BENEFITS = [
  {
    icon: 'groups',
    title: 'Khách tìm đến bạn',
    desc: 'Khách trong khu vực tìm thợ theo nghề — hồ sơ tốt được ưu tiên hiển thị.',
  },
  {
    icon: 'payments',
    title: 'Không phí hoa hồng',
    desc: 'Làm việc và nhận tiền trực tiếp từ khách, minh bạch.',
  },
  {
    icon: 'verified',
    title: 'Huy hiệu xác minh',
    desc: 'Được doitay kiểm duyệt — tăng uy tín, tăng tỉ lệ chốt.',
  },
  {
    icon: 'star',
    title: 'Danh tiếng tích lũy',
    desc: 'Mỗi đánh giá 5★ là tài sản theo bạn mãi trên nền tảng.',
  },
];

const STEPS = [
  {
    icon: 'badge',
    title: 'Điền hồ sơ thợ',
    desc: 'Tên, nghề, kinh nghiệm, bảng giá dịch vụ — 5 phút là xong.',
  },
  {
    icon: 'add_a_photo',
    title: 'Tải ảnh việc đã làm',
    desc: 'Ảnh thực tế tăng tỉ lệ được đặt lịch lên đến 40%.',
  },
  {
    icon: 'task_alt',
    title: 'Chờ duyệt trong 24h',
    desc: 'Hồ sơ lên chợ + nhận quà chào mừng vào ví để bắt đầu nhận khách.',
  },
];

export default async function BecomeContractorPage() {
  const token = await getToken();
  if (!token) redirect('/login?error=unauthenticated');

  // Nếu đã là thợ có công ty → đi thẳng trang quản lý
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    if (res.data.has_company) redirect('/vi/tho/lich-hen');
  } catch {
    /* proceed */
  }

  const settings = await getSiteSettings();
  const credit = settings.incentives?.welcome_credit ?? 0;
  const creditText = credit > 0 ? `${credit.toLocaleString('vi-VN')}đ` : null;

  return (
    <div className="-mx-6 -my-10 md:-mx-8">
      {/* ─── Hero ───────────────────────────────────────────────────── */}
      <section className="bg-surface px-6 pb-16 pt-10 md:px-8">
        <div className="mx-auto grid max-w-6xl items-center gap-10 lg:grid-cols-2">
          <div className="space-y-6">
            <span className="inline-flex items-center gap-2 rounded-full bg-tertiary-container px-4 py-1.5 text-sm font-bold tracking-wide text-on-tertiary-container">
              <span className="material-symbols-outlined text-base">construction</span>
              DÀNH CHO NGƯỜI CÓ TAY NGHỀ
            </span>
            <h1 className="font-headline text-4xl font-extrabold leading-[1.1] tracking-tight text-on-surface md:text-5xl">
              Biến tay nghề
              <br />
              thành <span className="text-primary">thu nhập đều</span>.
            </h1>
            <p className="max-w-md text-lg leading-relaxed text-on-surface-variant">
              Tạo hồ sơ thợ miễn phí — khách quanh khu vực thấy bạn, đặt lịch với bạn,
              trả tiền trực tiếp cho bạn.
            </p>

            <div className="flex flex-col gap-3 pt-1 sm:flex-row sm:items-center">
              <Link
                href={'/vi/tho/dang-ky' as Route}
                className="inline-flex h-14 items-center justify-center gap-2 rounded-xl bg-primary px-8 font-headline text-lg font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95"
              >
                Bắt đầu đăng ký ngay
                <span className="material-symbols-outlined">arrow_forward</span>
              </Link>
              <span className="text-sm font-medium text-on-surface-variant">
                Miễn phí hoàn toàn · duyệt trong 24h
              </span>
            </div>

            <div className="flex flex-wrap gap-x-6 gap-y-2 text-sm font-semibold text-on-surface">
              {creditText ? (
                <span className="flex items-center gap-1.5">
                  <span className="material-symbols-outlined text-base text-tertiary" style={{ fontVariationSettings: "'FILL' 1" }}>
                    redeem
                  </span>
                  Tặng {creditText} vào ví khi được duyệt
                </span>
              ) : null}
              <span className="flex items-center gap-1.5">
                <span className="material-symbols-outlined text-base text-primary">check_circle</span>
                Không phí hoa hồng
              </span>
            </div>
          </div>

          {/* Ảnh hero + badge nổi */}
          <div className="relative">
            <div className="overflow-hidden rounded-[2rem] shadow-ambient">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src="/tro-tho-hero.jpg"
                alt="Thợ hoàn thành công việc, khách hàng hài lòng"
                className="h-full w-full object-cover"
              />
            </div>
            {creditText ? (
              <div className="absolute -bottom-5 left-6 flex items-center gap-3 rounded-2xl bg-on-surface px-5 py-3.5 shadow-ambient">
                <span className="material-symbols-outlined text-tertiary" style={{ fontVariationSettings: "'FILL' 1" }}>
                  redeem
                </span>
                <div>
                  <p className="font-headline text-sm font-bold text-surface-bright">Tặng {creditText} vào ví</p>
                  <p className="text-[11px] text-surface-bright/70">ngay khi hồ sơ được duyệt</p>
                </div>
              </div>
            ) : null}
          </div>
        </div>
      </section>

      {/* ─── Lợi ích ─────────────────────────────────────────────────── */}
      <section className="bg-surface-container-low px-6 py-16 md:px-8">
        <div className="mx-auto grid max-w-6xl grid-cols-2 gap-4 md:grid-cols-4">
          {BENEFITS.map((b) => (
            <div
              key={b.title}
              className="group flex flex-col gap-3 rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15 transition-all duration-300 hover:-translate-y-1 hover:ring-primary/25"
            >
              <span className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary transition-colors duration-300 group-hover:bg-primary group-hover:text-white">
                <span className="material-symbols-outlined text-[1.5rem]" style={{ fontVariationSettings: "'FILL' 1" }}>
                  {b.icon}
                </span>
              </span>
              <p className="font-headline text-[0.9375rem] font-bold leading-tight text-on-surface">{b.title}</p>
              <p className="text-xs leading-snug text-on-surface-variant">{b.desc}</p>
            </div>
          ))}
        </div>
      </section>

      {/* ─── 3 bước + ảnh nhận việc ──────────────────────────────────── */}
      <section className="bg-surface px-6 py-16 md:px-8">
        <div className="mx-auto grid max-w-6xl items-center gap-10 lg:grid-cols-2">
          <div>
            <h2 className="mb-2 font-headline text-3xl font-bold text-on-surface">Chỉ 3 bước đơn giản</h2>
            <p className="mb-8 text-on-surface-variant">Từ đăng ký đến nhận khách đầu tiên.</p>
            <div className="space-y-4">
              {STEPS.map((s, i) => (
                <div
                  key={s.title}
                  className="group relative flex gap-4 overflow-hidden rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15 transition-all duration-300 hover:-translate-y-0.5"
                >
                  <span className="pointer-events-none absolute -right-1 -top-4 font-headline text-6xl font-black text-primary/[0.07]">
                    0{i + 1}
                  </span>
                  <span className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary text-on-primary">
                    <span className="material-symbols-outlined" style={{ fontVariationSettings: "'FILL' 1" }}>
                      {s.icon}
                    </span>
                  </span>
                  <div className="relative">
                    <p className="text-xs font-bold uppercase tracking-wide text-primary">Bước {i + 1}</p>
                    <h3 className="font-headline font-bold text-on-surface">{s.title}</h3>
                    <p className="mt-0.5 text-sm text-on-surface-variant">{s.desc}</p>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="relative mx-auto w-full max-w-md">
            <div className="overflow-hidden rounded-[2rem] shadow-ambient">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src="/tro-tho-phone.jpg"
                alt="Thợ nhận thông báo có khách đặt lịch trên điện thoại"
                className="h-full w-full object-cover"
              />
            </div>
            <div className="absolute -bottom-5 right-6 flex items-center gap-2.5 rounded-2xl bg-surface-container-lowest px-4 py-3 shadow-ambient ring-1 ring-outline-variant/15">
              <span className="material-symbols-outlined text-primary" style={{ fontVariationSettings: "'FILL' 1" }}>
                notifications_active
              </span>
              <p className="font-headline text-sm font-bold text-on-surface">
                Có khách là báo ngay
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* ─── CTA cuối ───────────────────────────────────────────────── */}
      <section className="bg-surface-container-low px-6 pb-20 pt-4 md:px-8">
        <div className="mx-auto max-w-6xl overflow-hidden rounded-[2rem] bg-primary p-10 text-center md:p-14">
          <h2 className="font-headline text-3xl font-extrabold text-on-primary md:text-4xl">
            Sẵn sàng nhận khách đầu tiên?
          </h2>
          <p className="mx-auto mt-3 max-w-xl text-on-primary/85">
            {creditText
              ? `Đăng ký miễn phí — được duyệt là có ngay ${creditText} trong ví để bắt đầu.`
              : 'Đăng ký miễn phí — hồ sơ được duyệt trong 24h làm việc.'}
          </p>
          <Link
            href={'/vi/tho/dang-ky' as Route}
            className="mt-8 inline-flex h-14 items-center justify-center gap-2 rounded-xl bg-surface-container-lowest px-10 font-headline text-lg font-bold text-primary shadow-ambient transition-all hover:bg-surface-container-low active:scale-95"
          >
            Bắt đầu đăng ký ngay
            <span className="material-symbols-outlined">arrow_forward</span>
          </Link>
        </div>
      </section>
    </div>
  );
}
