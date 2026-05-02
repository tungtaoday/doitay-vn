'use client';

import { useState, useRef, useEffect, useTransition } from 'react';
import Link from 'next/link';
import type { Route } from 'next';
import { usePathname } from 'next/navigation';
import type { AuthUser, Appointment, WalletTransaction } from '@/lib/api-types';
import { logoutAction } from '@/app/(auth)/logout/actions';
import { fetchDashboardDataAction } from '@/lib/dashboard-actions';
import { formatVND, formatSignedVND } from '@/lib/format';
import { UserAvatar } from '@/components/user-avatar';

// ── Greeting ──────────────────────────────────────────────────────────────────
function getGreeting(): string {
  const h = new Date().getHours();
  if (h >= 5 && h < 12) return 'Chào buổi sáng';
  if (h >= 12 && h < 18) return 'Chào buổi chiều';
  return 'Chào buổi tối';
}

// ── Role label ────────────────────────────────────────────────────────────────
function getRoleLabel(user: AuthUser): string {
  if (user.has_company) return 'Thợ lành nghề';
  if (user.pending_role === 'contractor' || user.pending_role === 'both') return 'Đang đăng ký thợ';
  return 'Khách hàng';
}

// ── Nav items ─────────────────────────────────────────────────────────────────
type NavItem = { href: Route; label: string; icon: string; contractorOnly?: boolean; hideWhenNoCompany?: boolean; customerOnly?: boolean };

const NAV_ITEMS: NavItem[] = [
  { href: '/vi/lich-hen',          label: 'Lịch hẹn',       icon: 'calendar_month' },
  { href: '/vi/tho/lich-hen',      label: 'Quản lý thợ',    icon: 'engineering',    contractorOnly: true },
  { href: '/vi/tho/sua-ho-so',     label: 'Hồ sơ của tôi',  icon: 'person',         contractorOnly: true, hideWhenNoCompany: true },
  { href: '/vi/tro-thanh-tho',     label: 'Trở thành thợ',  icon: 'handyman',       customerOnly: true },
  { href: '/vi/wallet',            label: 'Ví của tôi',     icon: 'account_balance_wallet', contractorOnly: true },
  { href: '/vi/wallet/giao-dich',  label: 'Giao dịch',      icon: 'receipt_long',           contractorOnly: true },
  { href: '/vi/nap-tien',          label: 'Nạp tiền',       icon: 'add_card',               contractorOnly: true },
  { href: '/vi/nap-tien/lich-su',  label: 'Lịch sử nạp',   icon: 'history',                contractorOnly: true },
];

// ── Onboarding steps ──────────────────────────────────────────────────────────
function getOnboardingSteps(user: AuthUser, isContractor: boolean) {
  const steps = [
    { label: 'Hoàn thành hồ sơ cá nhân', done: user.profile_complete },
    { label: 'Thêm ảnh đại diện', done: !!user.avatar },
    { label: 'Xác minh số điện thoại', done: user.sv },
  ];
  if (isContractor) {
    steps.push({ label: 'Đăng ký trở thành thợ', done: true });
    steps.push({ label: 'Hồ sơ thợ được duyệt', done: user.has_company });
  } else {
    steps.push({ label: 'Đặt lịch hẹn đầu tiên', done: false });
  }
  return steps;
}

// ── Appointment status ────────────────────────────────────────────────────────
const APT_STATUS: Record<string, { label: string; cls: string }> = {
  pending:   { label: 'Chờ xác nhận', cls: 'bg-yellow-100 text-yellow-700' },
  confirmed: { label: 'Đã xác nhận',  cls: 'bg-blue-100 text-blue-700' },
  completed: { label: 'Hoàn thành',   cls: 'bg-green-100 text-green-700' },
  canceled:  { label: 'Đã huỷ',       cls: 'bg-red-100 text-red-700' },
};

// ── Tips ──────────────────────────────────────────────────────────────────────
const TIPS = [
  'Trả lời khách hàng trong vòng 30 phút để tăng tỉ lệ chốt hợp đồng lên 60%.',
  'Thêm ảnh portfolio thực tế giúp hồ sơ được click nhiều hơn 3 lần.',
  'Cập nhật lịch làm việc đều đặn để tránh bị đánh giá thấp vì bỏ lỡ lịch.',
  '5 đánh giá tốt đầu tiên rất quan trọng — hãy chủ động xin feedback.',
];

// ── Component ─────────────────────────────────────────────────────────────────
interface UserDropdownProps {
  user: AuthUser;
}

export function UserDropdown({ user }: UserDropdownProps) {
  const [open, setOpen] = useState(false);
  const [dashboardBalance, setDashboardBalance] = useState<number | null>(null);
  const [recentAppointments, setRecentAppointments] = useState<Appointment[]>([]);
  const [recentTransactions, setRecentTransactions] = useState<WalletTransaction[]>([]);
  const [dataLoaded, setDataLoaded] = useState(false);
  const [, startTransition] = useTransition();
  const ref = useRef<HTMLDivElement>(null);
  const pathname = usePathname();

  const isContractor = user.has_company ||
    user.pending_role === 'contractor' ||
    user.pending_role === 'both';

  // Lazy-fetch dashboard data on first open to avoid blocking page load
  function handleOpen() {
    const next = !open;
    setOpen(next);
    if (next && !dataLoaded) {
      startTransition(async () => {
        const data = await fetchDashboardDataAction();
        setDashboardBalance(data.balance);
        setRecentAppointments(data.recentAppointments);
        setRecentTransactions(data.recentTransactions);
        setDataLoaded(true);
      });
    }
  }

  const visibleItems = NAV_ITEMS.filter((item) => {
    if (item.contractorOnly && !isContractor) return false;
    if (item.hideWhenNoCompany && !user.has_company) return false;
    if (item.customerOnly && isContractor) return false;
    return true;
  });

  const onboardingSteps = getOnboardingSteps(user, isContractor);
  const onboardingDone = onboardingSteps.filter(s => s.done).length;
  const onboardingPct = Math.round((onboardingDone / onboardingSteps.length) * 100);

  const tip = TIPS[new Date().getDay() % TIPS.length];

  useEffect(() => {
    function handleClick(e: MouseEvent) {
      if (ref.current && !ref.current.contains(e.target as Node)) setOpen(false);
    }
    document.addEventListener('mousedown', handleClick);
    return () => document.removeEventListener('mousedown', handleClick);
  }, []);

  useEffect(() => { setOpen(false); }, [pathname]);

  function isActive(href: string) {
    if (href === '/vi/lich-hen') return pathname === href || pathname.startsWith('/vi/lich-hen/');
    if (href === '/vi/tho/lich-hen') return pathname === href || pathname.startsWith('/vi/tho/lich-hen/');
    return pathname.startsWith(href);
  }

  return (
    <div ref={ref} className="relative">
      {/* ── Trigger ── */}
      <button
        onClick={handleOpen}
        className="flex items-center gap-3 border-l border-on-surface/10 pl-6 transition-opacity hover:opacity-80"
        aria-expanded={open}
      >
        <div className="text-right">
          <p className="text-sm font-bold text-primary">{user.name}</p>
          <p className="text-[10px] uppercase tracking-wider text-secondary">{getRoleLabel(user)}</p>
        </div>
        <div className="overflow-hidden rounded-lg border-2 border-primary/30">
          <UserAvatar
            name={user.name}
            userId={user.id}
            avatarUrl={user.avatar}
            sizeClass="h-10 w-10"
            roundedClass="rounded-lg"
            fontClass="text-xs"
          />
        </div>
        <span
          className="material-symbols-outlined text-[1.25rem] text-primary transition-transform duration-200"
          style={{ transform: open ? 'rotate(180deg)' : 'rotate(0deg)' }}
        >
          expand_more
        </span>
      </button>

      {/* ── Backdrop ── */}
      {open && (
        <div className="fixed inset-0 top-20 z-40" onClick={() => setOpen(false)} />
      )}

      {/* ── Panel ── */}
      {open && (
        <div className="fixed left-0 right-0 top-20 z-50 max-h-[calc(100vh-5rem)] overflow-y-auto border-b border-outline-variant/15 bg-white shadow-ambient">
          <div className="mx-auto max-w-7xl px-6 md:px-8">

            {/* ── Section 1: Nav + Account ── */}
            <div className="grid grid-cols-1 gap-8 py-6 md:grid-cols-3">
              {/* Col 1+2: Nav items */}
              <div className="md:col-span-2">
                <p className="mb-3 text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">
                  Điều hướng
                </p>
                <div className="grid grid-cols-2 gap-1 sm:grid-cols-3 md:grid-cols-4">
                  {visibleItems.map((item) => {
                    const active = isActive(item.href);
                    return (
                      <Link
                        key={item.href}
                        href={item.href}
                        onClick={() => setOpen(false)}
                        className={`flex items-center gap-2 rounded-lg px-4 py-3 text-[0.8125rem] font-medium transition-all ${
                          active
                            ? 'bg-primary/10 font-bold text-primary'
                            : 'text-on-surface hover:bg-surface-container-low hover:text-primary'
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

              {/* Col 3: User info + actions */}
              <div className="border-t border-outline-variant/10 pt-4 md:border-l md:border-t-0 md:pl-8 md:pt-0">
                <p className="mb-3 text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">
                  Tài khoản
                </p>
                <div className="mb-4 flex items-center gap-3">
                  <div className="overflow-hidden rounded-xl border-2 border-primary/20">
                    <UserAvatar
                      name={user.name}
                      userId={user.id}
                      avatarUrl={user.avatar}
                      sizeClass="h-12 w-12"
                      roundedClass="rounded-xl"
                      fontClass="text-sm"
                    />
                  </div>
                  <div className="min-w-0">
                    <p className="truncate font-bold text-on-surface">{user.name}</p>
                    <p className="truncate text-[0.75rem] text-secondary">{user.email}</p>
                    {user.has_company && (
                      <span className="mt-1 inline-flex items-center gap-1 text-[0.6875rem] font-bold text-tertiary">
                        <span className="material-symbols-outlined text-[0.875rem]"
                          style={{ fontVariationSettings: "'FILL' 1" }}>verified</span>
                        Verified
                      </span>
                    )}
                  </div>
                </div>

                <div className="space-y-1">
                  <Link
                    href={('/vi/ho-so') as Route}
                    onClick={() => setOpen(false)}
                    className="flex items-center gap-2 rounded-lg px-3 py-2.5 text-[0.875rem] font-medium text-on-surface transition-colors hover:bg-surface-container-low hover:text-primary"
                  >
                    <span className="material-symbols-outlined text-[1.125rem] text-primary">manage_accounts</span>
                    Cài đặt tài khoản
                  </Link>

                  <form action={logoutAction}>
                    <button
                      type="submit"
                      className="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-[0.875rem] font-medium text-error transition-colors hover:bg-error/5"
                    >
                      <span className="material-symbols-outlined text-[1.125rem]">logout</span>
                      Đăng xuất
                    </button>
                  </form>
                </div>
              </div>
            </div>

            {/* ── Divider ── */}
            <div className="border-t border-outline-variant/10" />

            {/* ── Section 2: Dashboard body ── */}
            <div className="grid grid-cols-1 gap-6 py-6 md:grid-cols-3">

              {/* ── Col 1+2: Left content ── */}
              <div className="space-y-4 md:col-span-2">

                {/* Greeting hero card */}
                <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary to-[#1a7fa8] px-6 py-5 text-white">
                  {/* decorative circles */}
                  <div className="pointer-events-none absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10" />
                  <div className="pointer-events-none absolute -bottom-8 right-16 h-24 w-24 rounded-full bg-white/5" />

                  <p className="relative text-[0.75rem] font-medium uppercase tracking-widest text-white/70">
                    {getGreeting()}
                  </p>
                  <h2 className="relative mt-0.5 font-headline text-[1.375rem] font-bold leading-tight">
                    {user.name}
                  </h2>
                  <p className="relative mt-2 max-w-xs text-[0.8125rem] text-white/80">
                    {isContractor
                      ? 'Quản lý lịch hẹn, xem số dư và cập nhật hồ sơ thợ của bạn.'
                      : 'Đặt lịch dịch vụ, theo dõi lịch hẹn và quản lý tài khoản của bạn.'}
                  </p>
                  <div className="relative mt-4 flex gap-3">
                    <Link
                      href={(isContractor ? '/vi/tho/lich-hen' : '/vi/lich-hen') as Route}
                      onClick={() => setOpen(false)}
                      className="rounded-xl bg-white px-4 py-2 text-[0.8125rem] font-bold text-primary transition-opacity hover:opacity-90"
                    >
                      Xem lịch hẹn
                    </Link>
                    {isContractor ? (
                      <Link
                        href={'/vi/wallet' as Route}
                        onClick={() => setOpen(false)}
                        className="rounded-xl bg-white/20 px-4 py-2 text-[0.8125rem] font-bold text-white transition-opacity hover:bg-white/30"
                      >
                        Xem ví
                      </Link>
                    ) : (
                      <Link
                        href={'/yeu-cau' as Route}
                        onClick={() => setOpen(false)}
                        className="rounded-xl bg-white/20 px-4 py-2 text-[0.8125rem] font-bold text-white transition-opacity hover:bg-white/30"
                      >
                        Đặt lịch liền
                      </Link>
                    )}
                  </div>
                </div>

                {/* Stats row */}
                <div className="grid grid-cols-2 gap-3">
                  {isContractor ? (
                    <div className="rounded-xl bg-surface-container-lowest px-5 py-4">
                      <p className="text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">Số dư</p>
                      <p className="mt-1 font-headline text-[1.25rem] font-bold text-on-surface">
                        {dashboardBalance !== null ? formatVND(dashboardBalance) : '—'}
                      </p>
                      <Link
                        href={'/vi/wallet' as Route}
                        onClick={() => setOpen(false)}
                        className="mt-2 inline-flex items-center gap-1 text-[0.75rem] font-medium text-primary hover:underline"
                      >
                        <span className="material-symbols-outlined text-[0.875rem]">add_card</span>
                        Nạp tiền
                      </Link>
                    </div>
                  ) : (
                    <div className="rounded-xl bg-surface-container-lowest px-5 py-4">
                      <p className="text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">Lịch hẹn</p>
                      <p className="mt-1 font-headline text-[1.25rem] font-bold text-on-surface">
                        {recentAppointments.length > 0 ? recentAppointments.length : '0'}
                      </p>
                      <Link
                        href={'/vi/lich-hen' as Route}
                        onClick={() => setOpen(false)}
                        className="mt-2 inline-flex items-center gap-1 text-[0.75rem] font-medium text-primary hover:underline"
                      >
                        <span className="material-symbols-outlined text-[0.875rem]">calendar_month</span>
                        Xem tất cả
                      </Link>
                    </div>
                  )}

                  <div className="rounded-xl bg-surface-container-lowest px-5 py-4">
                    <p className="text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">Hoàn thành hồ sơ</p>
                    <p className="mt-1 font-headline text-[1.25rem] font-bold text-on-surface">
                      {onboardingPct}%
                    </p>
                    <div className="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-surface-container">
                      <div
                        className="h-full rounded-full bg-primary transition-all"
                        style={{ width: `${onboardingPct}%` }}
                      />
                    </div>
                  </div>
                </div>

                {/* Recent appointments */}
                <div>
                  <div className="mb-3 flex items-center justify-between">
                    <p className="text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">
                      Lịch hẹn gần đây
                    </p>
                    <Link
                      href={(isContractor ? '/vi/tho/lich-hen' : '/vi/lich-hen') as Route}
                      onClick={() => setOpen(false)}
                      className="text-[0.75rem] font-medium text-primary hover:underline"
                    >
                      Tất cả lịch hẹn →
                    </Link>
                  </div>

                  {recentAppointments.length === 0 ? (
                    <div className="flex items-center gap-3 rounded-xl bg-surface-container-lowest px-5 py-4">
                      <span className="material-symbols-outlined text-[1.5rem] text-secondary/50">calendar_month</span>
                      <div>
                        <p className="text-[0.875rem] font-medium text-on-surface">Chưa có lịch hẹn nào</p>
                        <p className="text-[0.75rem] text-secondary">
                          {isContractor ? 'Chờ khách hàng đặt lịch.' : 'Hãy đặt lịch dịch vụ đầu tiên.'}
                        </p>
                      </div>
                    </div>
                  ) : (
                    <div className="space-y-2">
                      {recentAppointments.map((apt) => {
                        const st = APT_STATUS[apt.status] ?? { label: apt.status_label, cls: 'bg-gray-100 text-gray-700' };
                        return (
                          <Link
                            key={apt.id}
                            href={`/vi/lich-hen/${apt.id}` as Route}
                            onClick={() => setOpen(false)}
                            className="flex items-center gap-3 rounded-xl bg-surface-container-lowest px-4 py-3 transition-colors hover:bg-surface-container"
                          >
                            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                              <span className="material-symbols-outlined text-[1.125rem] text-primary"
                                style={{ fontVariationSettings: "'FILL' 1" }}>handyman</span>
                            </div>
                            <div className="min-w-0 flex-1">
                              <p className="truncate text-[0.8125rem] font-bold text-on-surface">
                                {apt.company.name ?? 'Thợ'}
                              </p>
                              <p className="text-[0.75rem] text-secondary">
                                {apt.appointment_date} — {apt.appointment_time}
                              </p>
                            </div>
                            <span className={`shrink-0 rounded-full px-2.5 py-0.5 text-[0.6875rem] font-bold ${st.cls}`}>
                              {st.label}
                            </span>
                          </Link>
                        );
                      })}
                    </div>
                  )}
                </div>
              </div>

              {/* ── Col 3: Right panel ── */}
              <div className="space-y-4 border-t border-outline-variant/10 pt-4 md:border-l md:border-t-0 md:pl-6 md:pt-0">

                {/* Pro Onboarding */}
                <div>
                  <div className="mb-3 flex items-center justify-between">
                    <p className="text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">
                      Pro Onboarding
                    </p>
                    <span className="text-[0.75rem] font-bold text-primary">{onboardingDone}/{onboardingSteps.length}</span>
                  </div>
                  <div className="space-y-2">
                    {onboardingSteps.map((step, i) => (
                      <div key={i} className="flex items-center gap-2.5">
                        <span className={`flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[0.75rem] font-bold ${
                          step.done ? 'bg-primary text-on-primary' : 'bg-surface-container text-secondary'
                        }`}>
                          {step.done
                            ? <span className="material-symbols-outlined text-[0.875rem]" style={{ fontVariationSettings: "'FILL' 1" }}>check</span>
                            : <span className="h-1.5 w-1.5 rounded-full bg-secondary/40" />}
                        </span>
                        <span className={`text-[0.8125rem] ${step.done ? 'text-on-surface line-through opacity-50' : 'font-medium text-on-surface'}`}>
                          {step.label}
                        </span>
                      </div>
                    ))}
                  </div>
                  {onboardingPct < 100 && (
                    <div className="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-surface-container">
                      <div className="h-full rounded-full bg-primary transition-all" style={{ width: `${onboardingPct}%` }} />
                    </div>
                  )}
                </div>

                {/* Recent transactions */}
                {recentTransactions.length > 0 && (
                  <div>
                    <div className="mb-3 flex items-center justify-between">
                      <p className="text-[0.6875rem] font-bold uppercase tracking-widest text-secondary">
                        Giao dịch gần đây
                      </p>
                      <Link
                        href={'/vi/wallet/giao-dich' as Route}
                        onClick={() => setOpen(false)}
                        className="text-[0.75rem] font-medium text-primary hover:underline"
                      >
                        Xem tất cả →
                      </Link>
                    </div>
                    <div className="space-y-2">
                      {recentTransactions.map((tx) => (
                        <div key={tx.id} className="flex items-center justify-between gap-2">
                          <div className="min-w-0 flex-1">
                            <p className="truncate text-[0.8125rem] font-medium text-on-surface">
                              {tx.transaction_type_label}
                            </p>
                            {tx.company_name && (
                              <p className="truncate text-[0.75rem] text-secondary">{tx.company_name}</p>
                            )}
                          </div>
                          <span className={`shrink-0 text-[0.8125rem] font-bold tabular-nums ${
                            tx.type === 'credit' ? 'text-green-600' : 'text-red-500'
                          }`}>
                            {formatSignedVND(tx.signed_amount)}
                          </span>
                        </div>
                      ))}
                    </div>
                  </div>
                )}

                {/* Daily tip */}
                <div className="rounded-xl bg-secondary/5 px-4 py-4">
                  <div className="mb-2 flex items-center gap-2">
                    <span className="material-symbols-outlined text-[1rem] text-tertiary"
                      style={{ fontVariationSettings: "'FILL' 1" }}>lightbulb</span>
                    <p className="text-[0.6875rem] font-bold uppercase tracking-widest text-tertiary">Mẹo vặt</p>
                  </div>
                  <p className="text-[0.8125rem] leading-relaxed text-on-surface">{tip}</p>
                </div>

              </div>
            </div>

          </div>
        </div>
      )}
    </div>
  );
}
