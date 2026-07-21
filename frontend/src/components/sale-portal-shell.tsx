'use client';

import { usePathname } from 'next/navigation';
import type { ReactNode } from 'react';
import { SaleHeader } from './sale-header';

/**
 * Khung cổng Sale/CTV: header sale riêng + nền. Trang /sale/login tự chiếm toàn
 * màn hình nên KHÔNG bọc header (giống trang đăng nhập doitay).
 */
export function SalePortalShell({
  userName,
  children,
}: {
  userName: string | null;
  children: ReactNode;
}) {
  const pathname = usePathname() || '';

  if (pathname === '/sale/login') {
    return <main className="min-h-screen bg-surface">{children}</main>;
  }

  return (
    <>
      <SaleHeader userName={userName} />
      <main className="min-h-screen bg-surface">{children}</main>
    </>
  );
}
