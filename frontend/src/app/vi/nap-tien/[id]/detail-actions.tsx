'use client';

import { useRouter } from 'next/navigation';
import { useState, useTransition } from 'react';
import { cancelDeposit, uploadDepositProof } from '../actions';

export function UploadProofForm({ depositId }: { depositId: number }) {
  const router = useRouter();
  const [pending, startTransition] = useTransition();
  const [error, setError] = useState<string | null>(null);

  function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError(null);
    const formData = new FormData(e.currentTarget);
    startTransition(async () => {
      const res = await uploadDepositProof(depositId, formData);
      if (!res.ok) {
        setError(res.error);
        return;
      }
      router.refresh();
    });
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-3">
      <input
        type="file"
        name="payment_proof"
        accept="image/jpeg,image/png,image/jpg"
        required
        className="w-full text-sm"
      />
      {error && <div className="text-xs text-rose-600">{error}</div>}
      <button
        type="submit"
        disabled={pending}
        className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary disabled:opacity-50"
      >
        {pending ? 'Đang tải...' : 'Cập nhật ảnh'}
      </button>
    </form>
  );
}

export function CancelButton({ depositId }: { depositId: number }) {
  const router = useRouter();
  const [pending, startTransition] = useTransition();
  const [error, setError] = useState<string | null>(null);

  function handleClick() {
    if (!confirm('Bạn chắc chắn muốn hủy yêu cầu nạp tiền này?')) return;
    setError(null);
    startTransition(async () => {
      const res = await cancelDeposit(depositId);
      if (!res.ok) {
        setError(res.error);
        return;
      }
      router.refresh();
    });
  }

  return (
    <div>
      <button
        type="button"
        onClick={handleClick}
        disabled={pending}
        className="rounded-xl border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50 disabled:opacity-50"
      >
        {pending ? 'Đang hủy...' : 'Hủy yêu cầu'}
      </button>
      {error && <div className="mt-2 text-xs text-rose-600">{error}</div>}
    </div>
  );
}
