import type { ReactNode } from 'react';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser } from '@/lib/api-types';
import { SalePortalShell } from '@/components/sale-portal-shell';

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
  return <SalePortalShell userName={userName}>{children}</SalePortalShell>;
}
