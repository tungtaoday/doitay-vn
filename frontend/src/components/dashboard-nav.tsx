'use client';

import Link from 'next/link';
import type { Route } from 'next';
import { usePathname } from 'next/navigation';

type NavItem = { href: Route; label: string; icon: string };

const COMMON: NavItem[] = [
  { href: '/vi/lich-hen', label: 'Lịch hẹn', icon: 'calendar_month' },
];

const CONTRACTOR_ITEMS: NavItem[] = [
  { href: '/vi/tho/lich-hen', label: 'Quản lý thợ', icon: 'engineering' },
  { href: '/vi/tho/sua-ho-so', label: 'Hồ sơ của tôi', icon: 'person' },
];

const CUSTOMER_BECOME: NavItem = {
  href: '/vi/tro-thanh-tho', label: 'Trở thành thợ', icon: 'handyman',
};

const FINANCE: NavItem[] = [
  { href: '/vi/wallet', label: 'Ví của tôi', icon: 'account_balance_wallet' },
  { href: '/vi/wallet/giao-dich', label: 'Giao dịch', icon: 'receipt_long' },
  { href: '/vi/nap-tien', label: 'Nạp tiền', icon: 'add_card' },
  { href: '/vi/nap-tien/lich-su', label: 'Lịch sử nạp', icon: 'history' },
];

export function DashboardNav({
  isContractor,
  hasCompany,
}: {
  isContractor: boolean;
  hasCompany: boolean;
}) {
  const pathname = usePathname();

  // Ví/Nạp tiền là tính năng theo CÔNG TY (của thợ) — không hiện cho khách hàng
  // để tránh lệch persona ("Tạo công ty để bắt đầu").
  const items: NavItem[] = [
    ...COMMON,
    ...(isContractor ? [...CONTRACTOR_ITEMS, ...FINANCE] : [CUSTOMER_BECOME]),
  ];

  // Only show "Hồ sơ của tôi" if actually has a company
  const visibleItems = items.filter(
    (item) => item.href !== '/vi/tho/sua-ho-so' || hasCompany,
  );

  function isActive(href: string) {
    if (href === '/vi/lich-hen') return pathname === href || pathname.startsWith('/vi/lich-hen/');
    if (href === '/vi/tho/lich-hen') return pathname === href || pathname.startsWith('/vi/tho/lich-hen/');
    return pathname.startsWith(href);
  }

  return (
    <nav
      aria-label="Điều hướng bảng điều khiển"
      className="sticky top-20 z-40 bg-surface-container-lowest shadow-soft"
    >
      {/* Container căn ĐÚNG lề với logo header (max-w-7xl + px như site-header) */}
      <div className="mx-auto max-w-7xl overflow-x-auto px-6 md:px-8 hide-scrollbar">
        <div className="flex items-center gap-2 py-2.5">
          {visibleItems.map((item) => {
            const active = isActive(item.href);
            return (
              <Link
                key={item.href}
                href={item.href}
                aria-current={active ? 'page' : undefined}
                className={`flex min-h-[44px] shrink-0 items-center gap-2 whitespace-nowrap rounded-full px-4 text-sm transition-all ${
                  active
                    ? 'bg-primary font-bold text-on-primary shadow-ambient'
                    : 'font-medium text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'
                }`}
              >
                <span
                  className="material-symbols-outlined text-[1.125rem]"
                  style={active ? { fontVariationSettings: "'FILL' 1" } : undefined}
                >
                  {item.icon}
                </span>
                {item.label}
              </Link>
            );
          })}
        </div>
      </div>
    </nav>
  );
}
