import Link from 'next/link';
import type { ReactNode } from 'react';
import { getSiteSettings } from '@/lib/site-settings';

export default async function AuthLayout({ children }: { children: ReactNode }) {
  const settings = await getSiteSettings();

  return (
    <div className="flex min-h-screen flex-col bg-surface md:flex-row">
      <section className="relative hidden flex-col items-start justify-between overflow-hidden bg-primary p-16 md:flex md:w-1/2">
        <div className="z-10">
          <Link
            href="/"
            className="mb-24 flex items-center gap-2 font-headline text-3xl font-bold tracking-tight text-on-primary"
          >
            {settings.site_logo_dark ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img src={settings.site_logo_dark} alt={settings.site_name} className="h-10 w-auto" />
            ) : settings.site_logo ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img src={settings.site_logo} alt={settings.site_name} className="h-10 w-auto brightness-0 invert" />
            ) : (
              <span>{settings.site_name}</span>
            )}
          </Link>
          <h1 className="mb-8 font-headline text-[3.5rem] font-bold leading-tight tracking-tight text-on-primary">
            {settings.banner?.heading ? (
              <span dangerouslySetInnerHTML={{ __html: settings.banner.heading.replace(/\n/g, '<br/>') }} />
            ) : (
              <>
                Kết nối dịch vụ,<br />vươn tầm cuộc sống.
              </>
            )}
          </h1>
          <p className="max-w-md text-[1.375rem] font-medium text-on-primary opacity-90">
            {settings.banner?.subheading || 'Nền tảng giao dịch dịch vụ thông minh và an toàn hàng đầu dành cho cộng đồng.'}
          </p>
        </div>
        
        {settings.banner?.image ? (
          // eslint-disable-next-line @next/next/no-img-element
          <img 
            src={settings.banner.image} 
            alt="Banner background" 
            className="absolute inset-0 z-0 h-full w-full object-cover opacity-20"
          />
        ) : (
          <div className="absolute bottom-0 right-0 h-64 w-64 translate-x-10 translate-y-10 transform rounded-full bg-white opacity-10"></div>
        )}

        <div className="z-10 mt-auto flex flex-col gap-6">
          <div className="flex items-center gap-4">
            <span
              className="material-symbols-outlined text-4xl text-on-primary"
              style={{ fontVariationSettings: "'FILL' 1" }}
            >
              check_circle
            </span>
            <span className="text-[1.25rem] font-bold text-on-primary">Bảo mật tuyệt đối</span>
          </div>
          <div className="flex items-center gap-4">
            <span
              className="material-symbols-outlined text-4xl text-on-primary"
              style={{ fontVariationSettings: "'FILL' 1" }}
            >
              speed
            </span>
            <span className="text-[1.25rem] font-bold text-on-primary">Tốc độ vượt trội</span>
          </div>
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
