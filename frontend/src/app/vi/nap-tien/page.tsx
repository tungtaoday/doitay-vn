import { getDepositMethods, getWalletOverview } from '@/lib/wallet';
import { formatVND } from '@/lib/format';
import { DepositCreateForm } from './deposit-create-form';

export const metadata = { title: 'Nạp tiền' };

export default async function DepositCreatePage() {
  const [overview, methods] = await Promise.all([
    getWalletOverview(),
    getDepositMethods(),
  ]);

  return (
    <div className="space-y-6">
      <header>
        <h1 className="font-headline text-3xl font-bold text-on-surface">Nạp tiền vào ví</h1>
        <p className="mt-2 text-sm text-on-surface-variant">
          Chọn ví và phương thức thanh toán. Yêu cầu sẽ được xử lý sau khi admin xác nhận.
        </p>
      </header>

      {overview.data.wallets.length === 0 ? (
        <div className="rounded-2xl border border-dashed border-outline-variant px-6 py-10 text-center text-sm text-on-surface-variant">
          Bạn chưa có ví nào. Hãy tạo công ty trước.
        </div>
      ) : methods.length === 0 ? (
        <div className="rounded-2xl border border-dashed border-outline-variant px-6 py-10 text-center text-sm text-on-surface-variant">
          Hiện chưa có phương thức thanh toán nào đang hoạt động.
        </div>
      ) : (
        <DepositCreateForm
          wallets={overview.data.wallets.map((w) => ({
            id: w.id,
            label: `${w.company.name ?? 'Ví'} — số dư ${formatVND(w.balance)}`,
          }))}
          methods={methods}
        />
      )}
    </div>
  );
}
