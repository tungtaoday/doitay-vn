'use client';

import { useRouter } from 'next/navigation';
import { useState, useTransition } from 'react';
import { confirmAppointment, completeAppointment, cancelThoAppointment } from '../actions';
import { formatVND } from '@/lib/format';

export function ThoActions({
  appointmentId,
  status,
  canConfirm,
  canComplete,
  canCancel,
  confirmFee,
}: {
  appointmentId: number;
  status: string;
  canConfirm: boolean;
  canComplete: boolean;
  canCancel: boolean;
  confirmFee: number | null;
}) {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [error, setError] = useState('');

  function handle(action: 'confirm' | 'complete' | 'cancel') {
    const labels = {
      confirm: `Xác nhận lịch hẹn? Phí ${formatVND(confirmFee ?? 50000)} sẽ được trừ từ ví.`,
      complete: 'Đánh dấu lịch hẹn đã hoàn thành?',
      cancel: 'Bạn chắc chắn muốn huỷ lịch hẹn này?',
    };
    if (!window.confirm(labels[action])) return;

    const fns = { confirm: confirmAppointment, complete: completeAppointment, cancel: cancelThoAppointment };
    startTransition(async () => {
      const res = await fns[action](appointmentId);
      if (!res.ok) setError(res.error);
      else router.refresh();
    });
  }

  return (
    <div className="space-y-4">
      {error && (
        <p className="rounded-xl bg-error-container p-4 text-error">{error}</p>
      )}

      <div className="flex flex-wrap gap-3">
        {canConfirm && (
          <button
            type="button"
            onClick={() => handle('confirm')}
            disabled={isPending}
            className="rounded-xl bg-primary px-6 py-3 font-bold text-on-primary transition-all hover:opacity-90 disabled:opacity-50"
          >
            {isPending ? 'Đang xử lý...' : `Xác nhận (${formatVND(confirmFee ?? 50000)})`}
          </button>
        )}

        {canComplete && (
          <button
            type="button"
            onClick={() => handle('complete')}
            disabled={isPending}
            className="rounded-xl bg-green-600 px-6 py-3 font-bold text-white transition-all hover:opacity-90 disabled:opacity-50"
          >
            {isPending ? 'Đang xử lý...' : 'Hoàn thành'}
          </button>
        )}

        {canCancel && (
          <button
            type="button"
            onClick={() => handle('cancel')}
            disabled={isPending}
            className="rounded-xl bg-error px-6 py-3 font-bold text-on-error transition-all hover:opacity-90 disabled:opacity-50"
          >
            {isPending ? 'Đang xử lý...' : 'Huỷ'}
          </button>
        )}
      </div>
    </div>
  );
}
