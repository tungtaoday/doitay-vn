import type { Metadata } from 'next';
import Script from 'next/script';
import { Be_Vietnam_Pro, Inter } from 'next/font/google';
import './globals.css';
import { SiteHeader } from '@/components/site-header';
import { SiteFooter } from '@/components/site-footer';
import { SiteChrome } from '@/components/site-chrome';
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
  const defaultTitle = `${settings.site_name} — Kết nối bàn tay thợ tài hoa`;
  const description = 'Marketplace kết nối khách hàng với thợ và nhà thầu uy tín tại Việt Nam.';
  return {
    metadataBase: new URL('https://doitay.vn'),
    title: {
      default: defaultTitle,
      template: `%s | ${settings.site_name}`,
    },
    description,
    openGraph: {
      type: 'website',
      locale: 'vi_VN',
      url: 'https://doitay.vn',
      siteName: settings.site_name,
      title: defaultTitle,
      description,
    },
    icons: {
      icon: [
        { url: settings.site_favicon ?? '/favicon.ico', sizes: 'any' },
        { url: '/icon.png', type: 'image/png', sizes: '32x32' },
      ],
      apple: '/apple-icon.png',
      shortcut: '/favicon.ico',
    },
    // Google Search Console — đặt NEXT_PUBLIC_GSC_VERIFICATION trong .env để xác minh domain.
    ...(process.env.NEXT_PUBLIC_GSC_VERIFICATION
      ? { verification: { google: process.env.NEXT_PUBLIC_GSC_VERIFICATION } }
      : {}),
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
        <SiteChrome
          header={<SiteHeader settings={settings} />}
          footer={<SiteFooter settings={settings} />}
        >
          {children}
        </SiteChrome>
        <ZaloWidget settings={settings.zalo} />
        {/* Google Analytics 4 — chỉ chạy khi đặt NEXT_PUBLIC_GA_ID (G-XXXXXXX) trong .env.
            Đo SEO/hành vi web phía KHÁCH; anonymize IP. Nguồn sự thật phễu vẫn là product_events. */}
        {process.env.NEXT_PUBLIC_GA_ID && (
          <>
            <Script
              src={`https://www.googletagmanager.com/gtag/js?id=${process.env.NEXT_PUBLIC_GA_ID}`}
              strategy="afterInteractive"
            />
            <Script id="ga4" strategy="afterInteractive">
              {`window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '${process.env.NEXT_PUBLIC_GA_ID}', { anonymize_ip: true });`}
            </Script>
          </>
        )}
      </body>
    </html>
  );
}
