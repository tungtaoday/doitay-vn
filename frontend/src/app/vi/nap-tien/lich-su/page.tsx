import Link from 'next/link';
import type { Route } from 'next';
import { getDepositList } from '@/lib/wallet';
import { formatDateTime, formatVND } from '@/lib/format';
import type { DepositStatus } from '@/lib/api-types';

export const metadata = { title: 'Lịch sử nạp tiền' };

const STATUS_STYLES: Record<DepositStatus, string> = {
  pending: 'bg-amber-100 text-amber-800',
  processing: 'bg-blue-100 text-blue-800',
  completed: 'bg-emerald-100 text-emerald-800',
  rejected: 'bg-rose-100 text-rose-800',
  cancelled: 'bg-slate-100 text-slate-700',
};

export default async function DepositHistoryPage({
  searchParams,
}: {
  searchParams: Promise<{ page?: string }>;
}) {
  const sp = await searchParams;
  const page = Math.max(1, parseInt(sp.page ?? '1', 10) || 1);
  const res = await getDepositList(page);

  return (
    <div className="space-y-6">
      <header className="flex items-end justify-between">
        <div>
          <h1 className="font-headline text-3xl font-bold text-on-surface">Lịch sử nạp tiền</h1>
          <p className="mt-2 text-sm text-on-surface-variant">
            Tổng {res.stats.total_requests} yêu cầu · {res.stats.pending_requests} chờ xử lý ·{' '}
            {formatVND(res.stats.total_deposited)} đã nạp thành công.
          </p>
        </div>
        <Link
          href={'/vi/nap-tien' as Route}
          className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary shadow-ambient"
        >
          + Nạp mới
        </Link>
      </header>

      {res.data.length === 0 ? (
        <div className="rounded-2xl border border-dashed border-outline-variant px-6 py-10 text-center text-sm text-on-surface-variant">
          Chưa có yêu cầu nạp tiền nào.
        </div>
      ) : (
        <ul className="divide-y divide-outline-variant rounded-2xl bg-surface ring-1 ring-outline-variant">
          {res.data.map((d) => (
            <li key={d.id} className="flex items-center justify-between px-5 py-4">
              <div className="min-w-0">
                <Link
                  href={`/vi/nap-tien/${d.id}` as Route}
                  className="font-headline text-base font-semibold text-on-surface hover:text-primary"
                >
                  {d.deposit_code}
                </Link>
                <div className="mt-1 text-xs text-on-surface-variant">
                  {d.payment_method_label} · {d.wallet.company_name ?? '—'} ·{' '}
                  {formatDateTime(d.created_at)}
                </div>
              </div>
              <div className="flex items-center gap-4">
                <div className="text-right font-headline text-base font-semibold text-on-surface">
                  {formatVND(d.amount)}
                </div>
                <span
                  className={`rounded-full px-3 py-1 text-xs font-medium ${STATUS_STYLES[d.status]}`}
                >
                  {d.status_label}
                </span>
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
