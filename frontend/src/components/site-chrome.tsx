'use client';

import { usePathname } from 'next/navigation';
import type { ReactNode } from 'react';

// Trang xác thực dùng layout riêng (split-panel) — ẩn header/footer marketing
// để tập trung, tránh "double chrome".
const BARE_PREFIXES = [
  '/login',
  '/dang-ky',
  '/quen-mat-khau',
  '/dat-lai-mat-khau',
  '/sale/login',
];

// Cổng Sale/CTV — có header RIÊNG (SaleHeader qua layout của /sale và /quan-tri).
// Ẩn hoàn toàn header/footer doitay để không lẫn với giao diện khách/thợ.
const PORTAL_PREFIXES = ['/sale', '/quan-tri'];

export function SiteChrome({
  header,
  footer,
  children,
}: {
  header: ReactNode;
  footer: ReactNode;
  children: ReactNode;
}) {
  const pathname = usePathname() || '';
  const bare = BARE_PREFIXES.some((p) => pathname === p || pathname.startsWith(p + '/'));
  const portal = PORTAL_PREFIXES.some((p) => pathname === p || pathname.startsWith(p + '/'));

  if (bare) {
    // Không header/footer, không padding-top (auth layout tự chiếm toàn màn hình).
    return <main className="min-h-screen">{children}</main>;
  }

  if (portal) {
    // Cổng Sale tự cung cấp header/nền qua layout riêng — không bọc chrome doitay.
    return <>{children}</>;
  }

  return (
    <>
      {header}
      <main className="pt-20">{children}</main>
      {footer}
    </>
  );
}
