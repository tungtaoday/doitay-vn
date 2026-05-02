import { getDepositMethods, getWalletOverview } from '@/lib/wallet';
import { formatVND } from '@/lib/format';
import { DepositCreateForm } from './deposit-create-form';

export const metadata = { title: 'Nạp tiền' };

export default async function DepositCreatePage() {
  let overview;
  let methods;
  try {
    [overview, methods] = await Promise.all([
      getWalletOverview(),
      getDepositMethods(),
    ]);
  } catch {
    return (
      <div className="space-y-6">
        <header>
          <h1 className="font-headline text-3xl font-bold text-on-surface">Nạp tiền vào ví</h1>
        </header>
        <div className="rounded-2xl bg-error-container p-8 text-center text-on-error-container">
          <p className="font-headline text-lg font-bold">Không tải được thông tin nạp tiền</p>
          <p className="mt-2 text-sm">Vui lòng thử lại sau.</p>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <header>
        <h1 className="font-headline text-3xl font-bold text-on-surface">Nạp tiền vào ví</h1>
        <p className="mt-2 text-sm text-on-surface-variant">
          Chọn ví và phương thức thanh toán. Yêu cầu sẽ được xử lý sau khi admin xác nhận.
        </p>
      </header>

      {overview.data.wallets.length === 0 ? (
        <div className="rounded-2xl border border-dashed border-outline-variant px-6 py-16 text-center text-on-surface-variant">
          <span className="material-symbols-outlined mb-3 block text-4xl opacity-30">account_balance_wallet</span>
          <p className="font-headline text-base font-bold">Chưa có ví nào</p>
          <p className="mt-1 text-sm">Tính năng nạp tiền dành cho thợ đã được duyệt. Hồ sơ công ty của bạn cần được admin phê duyệt trước khi có thể nạp tiền vào ví.</p>
        </div>
      ) : methods.length === 0 ? (
        <div className="rounded-2xl border border-dashed border-outline-variant px-6 py-10 text-center text-sm text-on-surface-variant">
          Hiện chưa có phương thức thanh toán nào đang hoạt động.
        </div>
      ) : (
        <DepositCreateForm
          wallets={overview.data.wallets.map((w) => ({
            id: w.id,
            label: `Ví của bạn — số dư ${formatVND(w.balance)}`,
          }))}
          methods={methods}
        />
      )}
    </div>
  );
}
