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

  const items: NavItem[] = [
    ...COMMON,
    ...(isContractor ? CONTRACTOR_ITEMS : [CUSTOMER_BECOME]),
    ...FINANCE,
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
    <nav className="sticky top-20 z-40 overflow-x-auto border-b border-on-surface/5 bg-white">
      <div className="flex items-center gap-1 px-6 md:px-8">
        {visibleItems.map((item) => {
          const active = isActive(item.href);
          return (
            <Link
              key={item.href}
              href={item.href}
              className={`flex items-center gap-2 whitespace-nowrap px-5 py-4 text-[0.8125rem] font-medium transition-all ${
                active
                  ? 'border-b-[3px] border-primary bg-primary/5 font-bold text-primary'
                  : 'border-b-[3px] border-transparent text-secondary hover:bg-surface-container-low hover:text-on-surface'
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
    </nav>
  );
}
