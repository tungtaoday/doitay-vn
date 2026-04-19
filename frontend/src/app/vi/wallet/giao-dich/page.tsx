import { getWalletTransactions } from '@/lib/wallet';
import { formatDateTime, formatSignedVND, formatVND } from '@/lib/format';

export const metadata = { title: 'Lịch sử giao dịch' };

const TYPE_OPTIONS = [
  { value: '', label: 'Tất cả loại' },
  { value: 'welcome_bonus', label: 'Thưởng đăng ký' },
  { value: 'referral_bonus', label: 'Thưởng giới thiệu' },
  { value: 'lead_purchase', label: 'Mua leads' },
  { value: 'customer_info_access', label: 'Phí truy cập' },
  { value: 'admin_adjustment', label: 'Điều chỉnh' },
  { value: 'refund', label: 'Hoàn tiền' },
];

export default async function TransactionsPage({
  searchParams,
}: {
  searchParams: Promise<{ transaction_type?: string; type?: string }>;
}) {
  const sp = await searchParams;
  const res = await getWalletTransactions({
    transaction_type: sp.transaction_type,
    type: sp.type,
  });

  return (
    <div className="space-y-6">
      <header>
        <h1 className="font-headline text-3xl font-bold text-on-surface">Lịch sử giao dịch</h1>
        <p className="mt-2 text-sm text-on-surface-variant">
          Tổng {res.meta.total} giao dịch trên tất cả ví của bạn.
        </p>
      </header>

      <form method="get" className="flex flex-wrap gap-3 rounded-2xl bg-surface p-4 ring-1 ring-outline-variant">
        <select
          name="transaction_type"
          defaultValue={sp.transaction_type ?? ''}
          className="rounded-xl border border-outline-variant bg-background px-3 py-2 text-sm"
        >
          {TYPE_OPTIONS.map((o) => (
            <option key={o.value} value={o.value}>{o.label}</option>
          ))}
        </select>
        <select
          name="type"
          defaultValue={sp.type ?? ''}
          className="rounded-xl border border-outline-variant bg-background px-3 py-2 text-sm"
        >
          <option value="">Tiền vào / ra</option>
          <option value="credit">Tiền vào</option>
          <option value="debit">Tiền ra</option>
        </select>
        <button
          type="submit"
          className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary"
        >
          Lọc
        </button>
      </form>

      {res.data.length === 0 ? (
        <div className="rounded-2xl border border-dashed border-outline-variant px-6 py-10 text-center text-sm text-on-surface-variant">
          Không có giao dịch phù hợp bộ lọc.
        </div>
      ) : (
        <div className="overflow-x-auto rounded-2xl bg-surface ring-1 ring-outline-variant">
          <table className="min-w-full text-sm">
            <thead className="border-b border-outline-variant bg-surface-container-low text-left text-xs uppercase tracking-wider text-on-surface-variant">
              <tr>
                <th className="px-5 py-3">Thời gian</th>
                <th className="px-5 py-3">Loại</th>
                <th className="px-5 py-3">Mô tả</th>
                <th className="px-5 py-3 text-right">Số tiền</th>
                <th className="px-5 py-3 text-right">Số dư sau</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-outline-variant">
              {res.data.map((t) => (
                <tr key={t.id}>
                  <td className="px-5 py-3 text-on-surface-variant">{formatDateTime(t.created_at)}</td>
                  <td className="px-5 py-3">{t.transaction_type_label || '—'}</td>
                  <td className="px-5 py-3 text-on-surface-variant">
                    <div>{t.description}</div>
                    <div className="text-xs opacity-75">{t.company_name}</div>
                  </td>
                  <td
                    className={
                      t.type === 'credit'
                        ? 'px-5 py-3 text-right font-semibold text-emerald-600'
                        : 'px-5 py-3 text-right font-semibold text-rose-600'
                    }
                  >
                    {formatSignedVND(t.signed_amount)}
                  </td>
                  <td className="px-5 py-3 text-right text-on-surface">
                    {formatVND(t.balance_after)}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
