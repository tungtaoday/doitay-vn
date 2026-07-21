import type { ReactNode } from 'react';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser } from '@/lib/api-types';
import { SalePortalShell } from '@/components/sale-portal-shell';

/** Cổng Sale/CTV — layout riêng, tách khỏi giao diện doitay khách/thợ. */
export default async function SaleLayout({ children }: { children: ReactNode }) {
  const token = await getToken();
  let userName: string | null = null;
  if (token) {
    try {
      const res = await api<{ data: AuthUser }>('/auth/me', { token });
      userName = res.data.name;
    } catch {
      /* chưa đăng nhập (vd trang /sale/login) */
    }
  }
  return <SalePortalShell userName={userName}>{children}</SalePortalShell>;
}
