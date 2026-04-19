'use client';

import { useState, useTransition } from 'react';
import type { DepositMethod } from '@/lib/api-types';
import { formatVND } from '@/lib/format';
import { createDeposit } from './actions';

interface WalletOption {
  id: number;
  label: string;
}

export function DepositCreateForm({
  wallets,
  methods,
}: {
  wallets: WalletOption[];
  methods: DepositMethod[];
}) {
  const [pending, startTransition] = useTransition();
  const [error, setError] = useState<string | null>(null);
  const [walletId, setWalletId] = useState<number>(wallets[0]?.id ?? 0);
  const [methodId, setMethodId] = useState<number>(methods[0]?.id ?? 0);
  const [amount, setAmount] = useState<string>('');

  const selectedMethod = methods.find((m) => m.id === methodId);

  function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError(null);
    const formData = new FormData(e.currentTarget);
    startTransition(async () => {
      const res = await createDeposit(formData);
      if (res && !res.ok) setError(res.error);
      // on success, the server action redirects — nothing to do here
    });
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="rounded-2xl bg-surface p-6 ring-1 ring-outline-variant">
        <label className="mb-2 block text-sm font-semibold text-on-surface">Chọn ví</label>
        <select
          name="wallet_id"
          value={walletId}
          onChange={(e) => setWalletId(Number(e.target.value))}
          className="w-full rounded-xl border border-outline-variant bg-background px-4 py-3 text-sm"
          required
        >
          {wallets.map((w) => (
            <option key={w.id} value={w.id}>
              {w.label}
            </option>
          ))}
        </select>
      </div>

      <div className="rounded-2xl bg-surface p-6 ring-1 ring-outline-variant">
        <label className="mb-3 block text-sm font-semibold text-on-surface">
          Phương thức thanh toán
        </label>
        <div className="grid gap-3 md:grid-cols-2">
          {methods.map((m) => (
            <label
              key={m.id}
              className={`flex cursor-pointer items-start gap-3 rounded-xl border p-4 transition-colors ${
                methodId === m.id
                  ? 'border-primary bg-primary/5'
                  : 'border-outline-variant hover:bg-surface-container-low'
              }`}
            >
              <input
                type="radio"
                name="payment_method_id"
                value={m.id}
                checked={methodId === m.id}
                onChange={() => setMethodId(m.id)}
                className="mt-1"
              />
              <div className="min-w-0 flex-1">
                <div className="text-sm font-medium text-on-surface">{m.name}</div>
                <div className="mt-1 text-xs text-on-surface-variant">
                  {m.bank.name ?? m.wallet.name ?? m.payment_method}
                </div>
                <div className="mt-1 text-xs text-on-surface-variant">
                  Từ {formatVND(m.min_amount)}
                  {m.max_amount ? ` đến ${formatVND(m.max_amount)}` : ''}
                </div>
              </div>
            </label>
          ))}
        </div>

        {selectedMethod && (
          <div className="mt-4 space-y-2 rounded-xl bg-surface-container-low p-4 text-sm text-on-surface-variant">
            {selectedMethod.bank.account_number && (
              <div>
                <strong className="text-on-surface">STK:</strong>{' '}
                {selectedMethod.bank.account_number} — {selectedMethod.bank.account_name}
                {selectedMethod.bank.name ? ` (${selectedMethod.bank.name})` : ''}
              </div>
            )}
            {selectedMethod.wallet.phone && (
              <div>
                <strong className="text-on-surface">SĐT ví:</strong>{' '}
                {selectedMethod.wallet.phone} — {selectedMethod.wallet.name}
              </div>
            )}
            {selectedMethod.instructions && (
              <pre className="whitespace-pre-wrap font-sans text-xs">
                {selectedMethod.instructions}
              </pre>
            )}
            {selectedMethod.qr_code_url && (
              // eslint-disable-next-line @next/next/no-img-element
              <img
                src={selectedMethod.qr_code_url}
                alt="QR code"
                className="mt-2 max-h-48 rounded-lg ring-1 ring-outline-variant"
              />
            )}
          </div>
        )}
      </div>

      <div className="rounded-2xl bg-surface p-6 ring-1 ring-outline-variant">
        <label className="mb-2 block text-sm font-semibold text-on-surface">Số tiền (VND)</label>
        <input
          type="number"
          name="amount"
          value={amount}
          onChange={(e) => setAmount(e.target.value)}
          min={10000}
          step={1000}
          required
          placeholder="100000"
          className="w-full rounded-xl border border-outline-variant bg-background px-4 py-3 text-sm"
        />
        <div className="mt-2 flex flex-wrap gap-2">
          {[100000, 200000, 500000, 1000000].map((v) => (
            <button
              key={v}
              type="button"
              onClick={() => setAmount(String(v))}
              className="rounded-full bg-surface-container-low px-3 py-1 text-xs hover:bg-primary/10"
            >
              {formatVND(v)}
            </button>
          ))}
        </div>

        <label className="mt-4 mb-2 block text-sm font-semibold text-on-surface">
          Ghi chú (tuỳ chọn)
        </label>
        <textarea
          name="user_notes"
          rows={2}
          maxLength={1000}
          className="w-full rounded-xl border border-outline-variant bg-background px-4 py-2 text-sm"
        />

        <label className="mt-4 mb-2 block text-sm font-semibold text-on-surface">
          Ảnh chứng minh (tuỳ chọn — có thể upload sau)
        </label>
        <input
          type="file"
          name="payment_proof"
          accept="image/jpeg,image/png,image/jpg"
          className="w-full text-sm"
        />
      </div>

      {error && (
        <div className="rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700">{error}</div>
      )}

      <button
        type="submit"
        disabled={pending}
        className="w-full rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-on-primary shadow-ambient transition-all active:scale-95 disabled:opacity-50"
      >
        {pending ? 'Đang gửi...' : 'Gửi yêu cầu nạp tiền'}
      </button>
    </form>
  );
}
