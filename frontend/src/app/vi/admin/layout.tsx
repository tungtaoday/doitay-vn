import { redirect, notFound } from 'next/navigation';
import Link from 'next/link';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser } from '@/lib/api-types';

async function getAdminUser(): Promise<AuthUser | null> {
  const token = await getToken();
  if (!token) return null;
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    return res.data.role === 'admin' ? res.data : null;
  } catch { return null; }
}

const NAV_ITEMS = [
  { href: '/vi/admin/seed', icon: 'database', label: 'Seed Data' },
] as const;

export default async function AdminLayout({ children }: { children: React.ReactNode }) {
  const user = await getAdminUser();
  if (!user) notFound();

  return (
    <div className="flex min-h-screen gap-8">
      {/* Sidebar */}
      <aside className="w-56 shrink-0">
        <div className="sticky top-8">
          <div className="mb-6">
            <span className="text-xs font-bold uppercase tracking-[0.2em] text-outline">
              Admin Panel
            </span>
            <p className="mt-1 text-sm text-on-surface-variant">{user.name}</p>
          </div>
          <nav className="space-y-1">
            {NAV_ITEMS.map(({ href, icon, label }) => (
              <Link
                key={href}
                href={href}
                className="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-on-surface-variant transition-colors hover:bg-surface-container hover:text-on-surface"
              >
                <span className="material-symbols-outlined text-base text-primary">{icon}</span>
                {label}
              </Link>
            ))}
          </nav>
        </div>
      </aside>

      {/* Content */}
      <main className="min-w-0 flex-1">{children}</main>
    </div>
  );
}
