import type { Metadata, Viewport } from 'next';
import type { ReactNode } from 'react';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser } from '@/lib/api-types';
import { SalePortalShell } from '@/components/sale-portal-shell';
import { ThanhBen } from './thanh-ben';

// PWA "app quản trị trên điện thoại": manifest riêng cho nhánh /quan-tri —
// KHÔNG đặt ở root layout vì manifest site-wide phải dành cho phía khách;
// người lạ thêm doitay.vn vào màn hình chính không được rơi vào cổng admin.
export const metadata: Metadata = {
  manifest: '/quan-tri.webmanifest',
  appleWebApp: { capable: true, title: 'Doitay QT', statusBarStyle: 'default' },
};

export const viewport: Viewport = {
  themeColor: '#102F4B',
};

/** Trung tâm điều hành thuộc cổng Sale — dùng chung header sale, tách khỏi doitay. */
export default async function QuanTriLayout({ children }: { children: ReactNode }) {
  const token = await getToken();
  let userName: string | null = null;
  if (token) {
    try {
      const res = await api<{ data: AuthUser }>('/auth/me', { token });
      userName = res.data.name;
    } catch {
      /* ignore */
    }
  }
  // Thanh bên nằm TRONG cổng Sale: mọi màn quản trị dùng chung một khung,
  // không còn cảnh mỗi trang tự vẽ một thanh điều hướng riêng.
  return (
    <SalePortalShell userName={userName}>
      <div className="mx-auto flex max-w-[1400px] flex-col gap-6 px-4 py-6 md:flex-row md:px-6 md:py-8">
        <ThanhBen />
        <div className="min-w-0 flex-1">{children}</div>
      </div>
    </SalePortalShell>
  );
}
