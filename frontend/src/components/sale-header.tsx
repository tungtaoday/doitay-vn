'use client';

import Link from 'next/link';
import type { Route } from 'next';
import { usePathname } from 'next/navigation';
import { useState, useTransition } from 'react';
import { saleLogoutAction } from '@/app/sale/logout-action';

const NAV: { href: Route; label: string; icon: string; exact?: boolean }[] = [
  { href: '/sale' as Route, label: 'Danh sách của tôi', icon: 'list_alt', exact: true },
  { href: '/sale/nhap' as Route, label: 'Nhập hồ sơ', icon: 'note_add' },
  { href: '/sale/duyet' as Route, label: 'Vận hành & Duyệt', icon: 'fact_check' },
  { href: '/quan-tri' as Route, label: 'Điều hành', icon: 'insights' },
];

/**
 * Header RIÊNG của cổng Sale/CTV — tách hẳn khỏi giao diện doitay dành cho
 * khách/thợ. Logo về /sale (KHÔNG về trang chủ doitay), có menu sale + đăng xuất.
 */
export function SaleHeader({ userName }: { userName: string | null }) {
  const pathname = usePathname() || '';
  const [open, setOpen] = useState(false);
  const [isPending, startTransition] = useTransition();

  const isActive = (href: string, exact?: boolean) =>
    exact ? pathname === href : pathname === href || pathname.startsWith(href + '/');

  function logout() {
    startTransition(() => saleLogoutAction());
  }

  return (
    <header className="sticky top-0 z-50 border-b border-outline-variant/10 bg-on-surface">
      <div className="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-6">
        {/* Brand → /sale (cổng sale, không phải trang chủ doitay) */}
        <Link href={'/sale' as Route} className="flex shrink-0 items-center gap-2">
          <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-on-primary">
            <span className="material-symbols-outlined text-[1.25rem]" style={{ fontVariationSettings: "'FILL' 1" }}>
              badge
            </span>
          </span>
          <span className="flex flex-col leading-none">
            <span className="font-headline text-base font-bold text-white">Cổng CTV</span>
            <span className="text-[0.65rem] font-medium uppercase tracking-wider text-white/50">Doitay Sale</span>
          </span>
        </Link>

        {/* Nav desktop */}
        <nav className="hidden items-center gap-1 md:flex">
          {NAV.map((n) => (
            <Link
              key={n.href}
              href={n.href}
              className={`flex items-center gap-1.5 rounded-full px-3.5 py-2 text-sm transition-colors ${
                isActive(n.href, n.exact)
                  ? 'bg-white/15 font-bold text-white'
                  : 'font-medium text-white/70 hover:bg-white/10 hover:text-white'
              }`}
            >
              <span className="material-symbols-outlined text-[1.125rem]">{n.icon}</span>
              {n.label}
            </Link>
          ))}
        </nav>

        {/* User + logout */}
        <div className="flex shrink-0 items-center gap-3">
          {userName ? (
            <span className="hidden text-sm font-medium text-white/80 sm:block">{userName}</span>
          ) : null}
          <button
            onClick={logout}
            disabled={isPending}
            className="hidden items-center gap-1.5 rounded-lg bg-white/10 px-3 py-2 text-sm font-semibold text-white transition-colors hover:bg-white/20 disabled:opacity-60 sm:flex"
          >
            <span className="material-symbols-outlined text-[1.125rem]">logout</span>
            Đăng xuất
          </button>
          {/* Mobile menu toggle */}
          <button
            onClick={() => setOpen((v) => !v)}
            className="flex h-10 w-10 items-center justify-center rounded-lg text-white md:hidden"
            aria-label="Mở menu"
          >
            <span className="material-symbols-outlined">{open ? 'close' : 'menu'}</span>
          </button>
        </div>
      </div>

      {/* Nav mobile */}
      {open ? (
        <nav className="border-t border-white/10 bg-on-surface px-4 pb-4 pt-2 md:hidden">
          {NAV.map((n) => (
            <Link
              key={n.href}
              href={n.href}
              onClick={() => setOpen(false)}
              className={`flex items-center gap-2.5 rounded-lg px-3 py-3 text-sm ${
                isActive(n.href, n.exact) ? 'bg-white/15 font-bold text-white' : 'font-medium text-white/70'
              }`}
            >
              <span className="material-symbols-outlined text-[1.25rem]">{n.icon}</span>
              {n.label}
            </Link>
          ))}
          <button
            onClick={logout}
            disabled={isPending}
            className="mt-2 flex w-full items-center gap-2.5 rounded-lg bg-white/10 px-3 py-3 text-sm font-semibold text-white"
          >
            <span className="material-symbols-outlined text-[1.25rem]">logout</span>
            Đăng xuất{userName ? ` (${userName})` : ''}
          </button>
        </nav>
      ) : null}
    </header>
  );
}
