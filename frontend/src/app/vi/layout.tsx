import { redirect } from 'next/navigation';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser } from '@/lib/api-types';
import { DashboardNav } from '@/components/dashboard-nav';

export default async function ViLayout({ children }: { children: React.ReactNode }) {
  const token = await getToken();
  if (!token) redirect('/login?error=unauthenticated');

  let user: AuthUser | null = null;
  try {
    user = (await api<{ data: AuthUser }>('/auth/me', { token })).data;
  } catch {
    // giữ null — vẫn render dashboard, nav ẩn nếu không lấy được user
  }

  const isContractor =
    !!user &&
    (user.has_company || user.pending_role === 'contractor' || user.pending_role === 'both');
  const hasCompany = !!user?.has_company;

  return (
    <>
      {user ? <DashboardNav isContractor={isContractor} hasCompany={hasCompany} /> : null}
      <div className="mx-auto max-w-6xl px-6 py-10 md:px-8">{children}</div>
    </>
  );
}
