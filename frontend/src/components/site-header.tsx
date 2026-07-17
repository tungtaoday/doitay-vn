import Link from 'next/link';
import type { Route } from 'next';
import type { SiteSettings, AuthUser, UserCompany } from '@/lib/api-types';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import { getRecentNotifications, getUnreadCount } from '@/lib/notifications';
import { NotificationBell } from '@/components/notification-bell';
import { UserDropdown } from '@/components/user-dropdown';
import { MobileNav } from '@/components/mobile-nav';

const NAV_LINKS: { href: Route; label: string }[] = [
  { href: '/' as Route, label: 'Trang chủ' },
  { href: '/tho' as Route, label: 'Tìm thợ' },
  { href: '/yeu-cau' as Route, label: 'Tạo yêu cầu' },
];

const CUSTOMER_LINKS: { href: Route; label: string }[] = [
  { href: '/vi/lich-hen' as Route, label: 'Lịch hẹn' },
];

const PENDING_CONTRACTOR_LINKS: { href: Route; label: string }[] = [
  { href: '/vi/lich-hen' as Route, label: 'Lịch hẹn' },
  { href: '/vi/tho/dang-ky' as Route, label: 'Đăng ký thợ' },
];

async function getCurrentUser(): Promise<AuthUser | null> {
  const token = await getToken();
  if (!token) return null;
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    return res.data;
  } catch {
    return null;
  }
}

async function getOwnCompanyId(token: string): Promise<number | null> {
  try {
    const res = await api<{ data: UserCompany[] }>('/user/companies', { token });
    return res.data[0]?.id ?? null;
  } catch {
    return null;
  }
}

export async function SiteHeader({ settings }: { settings: SiteSettings }) {
  const user = await getCurrentUser();
  const token = user?.has_company ? await getToken() : null;
  const [unreadCount, recent, contractorCompanyId] = user
    ? await Promise.all([
        getUnreadCount(),
        getRecentNotifications(5),
        token ? getOwnCompanyId(token) : Promise.resolve(null),
      ])
    : [0, null, null];

  const contractorLinks: { href: Route; label: string }[] = [
    { href: '/vi/tho/lich-hen' as Route, label: 'Lịch hẹn' },
    ...(contractorCompanyId
      ? [{ href: `/tho/${contractorCompanyId as number}` as Route, label: 'Hồ sơ cá nhân' }]
      : []),
  ];

  const roleLinks = user
    ? (user.has_company
        ? contractorLinks
        : user.pending_role === 'contractor' || user.pending_role === 'both'
          ? PENDING_CONTRACTOR_LINKS
          : CUSTOMER_LINKS)
    : [];
  const mobileLinks = [
    ...NAV_LINKS,
    ...roleLinks,
    ...(user
      ? []
      : [
          { href: '/tuyen-dung-tho' as Route, label: 'Trở thành thợ' },
          // Guest: đưa Đăng nhập vào menu (nút Đăng ký hiện sẵn ở thanh trên).
          { href: '/login' as Route, label: 'Đăng nhập' },
          ...(settings.features.registration
            ? [{ href: '/dang-ky' as Route, label: 'Đăng ký' }]
            : []),
        ]),
  ];

  return (
    <nav className="fixed top-0 left-0 z-50 w-full bg-surface/80 backdrop-blur-md">
      <div className="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 md:px-8">
        <Link
          href="/"
          className="flex items-center gap-2 font-headline text-2xl font-bold tracking-tight text-primary"
        >
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src={settings.site_logo ?? '/logo.png'}
            alt={settings.site_name}
            className="h-9 w-auto shrink-0 object-contain"
          />
        </Link>

        <div className="hidden items-center gap-8 font-headline text-sm font-medium md:flex">
          {NAV_LINKS.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className="text-on-surface-variant transition-colors hover:text-primary"
            >
              {link.label}
            </Link>
          ))}
          {/* Khách chưa đăng nhập: lối vào "Trở thành thợ" ngay trên thanh nav (desktop) */}
          {!user && (
            <Link
              href={'/tuyen-dung-tho' as Route}
              className="text-on-surface-variant transition-colors hover:text-primary"
            >
              Trở thành thợ
            </Link>
          )}
          {user && (user.has_company
            ? contractorLinks
            : !user.has_company && (user.pending_role === 'contractor' || user.pending_role === 'both')
              ? PENDING_CONTRACTOR_LINKS
              : CUSTOMER_LINKS
          ).map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className="text-on-surface-variant transition-colors hover:text-primary"
            >
              {link.label}
            </Link>
          ))}
        </div>

        <div className="flex items-center gap-3">
          {user ? (
            <div className="flex items-center gap-4">
              <NotificationBell
                initialUnreadCount={unreadCount}
                initialRecent={recent}
              />
              <UserDropdown user={user} />
            </div>
          ) : (
            <>
              {/* Đăng nhập: ẩn trên mobile (đã có trong menu hamburger) để nhường chỗ CTA Đăng ký */}
              <Link
                href="/login"
                className="hidden rounded-xl px-5 py-2 text-sm font-semibold text-primary transition-colors hover:bg-surface-container-low sm:block"
              >
                Đăng nhập
              </Link>
              {settings.features.registration ? (
                <Link
                  href={'/dang-ky' as Route}
                  className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95 sm:px-5"
                >
                  Đăng ký
                </Link>
              ) : null}
            </>
          )}
          <MobileNav links={mobileLinks} />
        </div>
      </div>
    </nav>
  );
}
