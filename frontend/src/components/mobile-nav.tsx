'use client';

import { useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';

export interface MobileNavLink {
  href: string;
  label: string;
}

/**
 * Menu điều hướng mobile (hamburger). Trước đây nav bị `hidden md:flex` —
 * người dùng điện thoại KHÔNG có đường đến Tìm thợ / Tạo yêu cầu.
 */
export function MobileNav({ links }: { links: MobileNavLink[] }) {
  const [open, setOpen] = useState(false);
  const pathname = usePathname();

  return (
    <div className="md:hidden">
      <button
        type="button"
        aria-label={open ? 'Đóng menu' : 'Mở menu'}
        aria-expanded={open}
        onClick={() => setOpen((v) => !v)}
        className="flex h-10 w-10 items-center justify-center rounded-xl text-on-surface transition-colors hover:bg-surface-container-low"
      >
        <span className="material-symbols-outlined">{open ? 'close' : 'menu'}</span>
      </button>

      {open ? (
        <>
          {/* Backdrop */}
          <button
            type="button"
            aria-label="Đóng menu"
            onClick={() => setOpen(false)}
            className="fixed inset-0 top-20 z-40 bg-secondary/20 backdrop-blur-sm"
          />
          {/* Panel */}
          <div className="fixed left-0 right-0 top-20 z-50 bg-surface shadow-ambient">
            <nav className="mx-auto max-w-7xl space-y-1 px-6 py-4">
              {links.map((l) => {
                const active = pathname === l.href;
                return (
                  <Link
                    key={l.href}
                    // eslint-disable-next-line @typescript-eslint/no-explicit-any
                    href={l.href as any}
                    onClick={() => setOpen(false)}
                    className={`block rounded-xl px-4 py-3 font-headline text-base font-semibold transition-colors ${
                      active
                        ? 'bg-primary/10 text-primary'
                        : 'text-on-surface hover:bg-surface-container-low'
                    }`}
                  >
                    {l.label}
                  </Link>
                );
              })}
            </nav>
          </div>
        </>
      ) : null}
    </div>
  );
}
