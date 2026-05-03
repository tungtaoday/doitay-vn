import type { Metadata } from 'next';
import { Be_Vietnam_Pro, Inter } from 'next/font/google';
import './globals.css';
import { SiteHeader } from '@/components/site-header';
import { SiteFooter } from '@/components/site-footer';
import { ZaloWidget } from '@/components/zalo-widget';
import { getSiteSettings } from '@/lib/site-settings';

const inter = Inter({
  subsets: ['latin', 'vietnamese'],
  weight: ['400', '500', '600', '700'],
  variable: '--font-inter',
  display: 'swap',
});

const beVietnamPro = Be_Vietnam_Pro({
  subsets: ['latin', 'vietnamese'],
  weight: ['400', '500', '700', '800'],
  variable: '--font-be-vietnam-pro',
  display: 'swap',
});

export async function generateMetadata(): Promise<Metadata> {
  const settings = await getSiteSettings();
  return {
    metadataBase: new URL('https://doitay.vn'),
    title: {
      default: `${settings.site_name} — Kết nối bàn tay thợ tài hoa`,
      template: `%s | ${settings.site_name}`,
    },
    description:
      'Marketplace kết nối khách hàng với thợ và nhà thầu uy tín tại Việt Nam.',
    icons: {
      icon: [
        { url: settings.site_favicon ?? '/favicon.ico', sizes: 'any' },
        { url: '/icon.png', type: 'image/png', sizes: '32x32' },
      ],
      apple: '/apple-icon.png',
      shortcut: '/favicon.ico',
    },
  };
}

export default async function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const settings = await getSiteSettings();

  return (
    <html lang="vi" className={`${inter.variable} ${beVietnamPro.variable}`}>
      <head>
        {/* Material Symbols Outlined — used as wayfinding icons across pages. */}
        <link
          rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        />
      </head>
      <body className="bg-background text-on-surface antialiased">
        <SiteHeader settings={settings} />
        <main className="pt-20">{children}</main>
        <SiteFooter settings={settings} />
        <ZaloWidget settings={settings.zalo} />
      </body>
    </html>
  );
}
