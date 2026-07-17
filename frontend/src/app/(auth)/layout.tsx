import Link from 'next/link';
import type { ReactNode } from 'react';
import { getSiteSettings } from '@/lib/site-settings';

export default async function AuthLayout({ children }: { children: ReactNode }) {
  const settings = await getSiteSettings();

  return (
    <div className="flex min-h-screen flex-col bg-surface md:flex-row">
      <section className="relative hidden flex-col justify-center overflow-hidden bg-primary p-14 md:flex md:w-1/2 lg:p-16">
        {/* Nền trang trí nhẹ — thay khoảng trống mênh mông trước đây */}
        <div className="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/[0.06]" />
        <div className="pointer-events-none absolute -bottom-32 -left-16 h-80 w-80 rounded-full bg-white/[0.06]" />

        <div className="relative z-10 max-w-md">
          <Link href="/" className="mb-14 inline-flex items-center gap-2">
            {settings.site_logo ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img src={settings.site_logo} alt={settings.site_name} className="h-10 w-auto brightness-0 invert" />
            ) : (
              <span className="font-headline text-2xl font-bold text-on-primary">{settings.site_name}</span>
            )}
          </Link>

          {/* Câu marketing trang trí — KHÔNG dùng <h1> (h1 là của form bên phải). */}
          <p className="mb-4 font-headline text-4xl font-bold leading-tight text-on-primary lg:text-5xl">
            Kết nối bàn tay
            <br />
            thợ tài hoa.
          </p>
          <p className="mb-12 max-w-sm text-lg font-medium leading-relaxed text-on-primary/80">
            Đặt lịch, theo dõi yêu cầu và làm việc trực tiếp với thợ uy tín — không qua trung gian.
          </p>

          <ul className="space-y-5">
            {[
              ['verified_user', 'Thợ đã kiểm duyệt', 'Hồ sơ, tay nghề và đánh giá thực tế.'],
              ['bolt', 'Kết nối nhanh', 'Nhận phản hồi từ thợ trong khu vực.'],
              ['payments', 'Không phí ẩn', 'Làm việc trực tiếp, minh bạch chi phí.'],
            ].map(([icon, title, desc]) => (
              <li key={title} className="flex items-start gap-4">
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10">
                  <span
                    className="material-symbols-outlined text-on-primary"
                    style={{ fontVariationSettings: "'FILL' 1" }}
                  >
                    {icon}
                  </span>
                </span>
                <div>
                  <p className="font-bold text-on-primary">{title}</p>
                  <p className="text-sm text-on-primary/80">{desc}</p>
                </div>
              </li>
            ))}
          </ul>
        </div>
      </section>

      <section className="flex w-full items-center justify-center bg-surface-container-lowest p-8 md:w-1/2 md:p-16">
        <div className="w-full max-w-[480px]">
          <Link
            href="/"
            className="mb-12 flex items-center gap-2 font-headline text-2xl font-bold tracking-tight text-primary md:hidden"
          >
            {settings.site_logo ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img src={settings.site_logo} alt={settings.site_name} className="h-9 w-auto" />
            ) : (
              <span>{settings.site_name}</span>
            )}
          </Link>

          {children}

          <footer className="mt-12 text-center text-[1rem] font-medium text-on-surface-variant">
            © {new Date().getFullYear()} {settings.site_name} — Mọi quyền được bảo lưu
          </footer>
        </div>
      </section>
    </div>
  );
}
