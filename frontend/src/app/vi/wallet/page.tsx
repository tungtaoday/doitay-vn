import Link from 'next/link';
import type { Route } from 'next';
import { getWalletOverview, getWalletTransactions } from '@/lib/wallet';
import { formatDateTime, formatSignedVND, formatVND } from '@/lib/format';

export const metadata = { title: 'Ví của tôi' };

export default async function WalletOverviewPage() {
  const [overview, recent] = await Promise.all([
    getWalletOverview(),
    getWalletTransactions({}),
  ]);
  const { wallets, totals } = overview.data;
  const latest = recent.data.slice(0, 5);

  return (
    <div className="space-y-8">
      <header>
        <h1 className="font-headline text-3xl font-bold text-on-surface">Ví của tôi</h1>
        <p className="mt-2 text-sm text-on-surface-variant">
          Tổng quan số dư và hoạt động gần đây trên tất cả ví của bạn.
        </p>
      </header>

      <section className="grid gap-4 md:grid-cols-4">
        <StatCard label="Tổng số dư" value={formatVND(totals.balance)} highlight />
        <StatCard label="Đã chi tiêu" value={formatVND(totals.spent_total)} />
        <StatCard label="Chi tháng này" value={formatVND(totals.spent_this_month)} />
        <StatCard label="Thưởng đã nhận" value={formatVND(totals.bonuses_total)} />
      </section>

      <section>
        <div className="mb-4 flex items-center justify-between">
          <h2 className="font-headline text-xl font-semibold text-on-surface">
            Ví theo công ty ({wallets.length})
          </h2>
          <Link
            href={'/vi/nap-tien' as Route}
            className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary shadow-ambient transition-all active:scale-95"
          >
            Nạp tiền
          </Link>
        </div>
        {wallets.length === 0 ? (
          <EmptyState message="Bạn chưa có ví nào. Tạo công ty để bắt đầu." />
        ) : (
          <ul className="grid gap-4 md:grid-cols-2">
            {wallets.map((w) => (
              <li
                key={w.id}
                className="rounded-2xl bg-surface p-5 shadow-ambient ring-1 ring-outline-variant"
              >
                <div className="text-sm text-on-surface-variant">{w.company.name ?? '—'}</div>
                <div className="mt-1 font-headline text-2xl font-bold text-primary">
                  {formatVND(w.balance)}
                </div>
                <div className="mt-3 text-xs text-on-surface-variant">
                  Mở ngày {formatDateTime(w.created_at)}
                </div>
              </li>
            ))}
          </ul>
        )}
      </section>

      <section>
        <div className="mb-4 flex items-center justify-between">
          <h2 className="font-headline text-xl font-semibold text-on-surface">Giao dịch gần đây</h2>
          <Link href={'/vi/wallet/giao-dich' as Route} className="text-sm font-medium text-primary hover:underline">
            Xem tất cả →
          </Link>
        </div>
        {latest.length === 0 ? (
          <EmptyState message="Chưa có giao dịch nào." />
        ) : (
          <ul className="divide-y divide-outline-variant rounded-2xl bg-surface ring-1 ring-outline-variant">
            {latest.map((t) => (
              <li key={t.id} className="flex items-center justify-between px-5 py-4">
                <div>
                  <div className="text-sm font-medium text-on-surface">
                    {t.transaction_type_label || t.description}
                  </div>
                  <div className="text-xs text-on-surface-variant">
                    {t.company_name} · {formatDateTime(t.created_at)}
                  </div>
                </div>
                <div
                  className={
                    t.type === 'credit'
                      ? 'font-semibold text-emerald-600'
                      : 'font-semibold text-rose-600'
                  }
                >
                  {formatSignedVND(t.signed_amount)}
                </div>
              </li>
            ))}
          </ul>
        )}
      </section>
    </div>
  );
}

function StatCard({
  label,
  value,
  highlight,
}: {
  label: string;
  value: string;
  highlight?: boolean;
}) {
  return (
    <div
      className={
        highlight
          ? 'rounded-2xl bg-gradient-to-br from-primary to-primary-container p-5 text-on-primary shadow-ambient'
          : 'rounded-2xl bg-surface p-5 ring-1 ring-outline-variant'
      }
    >
      <div className={highlight ? 'text-xs opacity-90' : 'text-xs text-on-surface-variant'}>
        {label}
      </div>
      <div className="mt-2 font-headline text-2xl font-bold">{value}</div>
    </div>
  );
}

function EmptyState({ message }: { message: string }) {
  return (
    <div className="rounded-2xl border border-dashed border-outline-variant px-6 py-10 text-center text-sm text-on-surface-variant">
      {message}
    </div>
  );
}
