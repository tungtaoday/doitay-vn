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
      confirm: confirmFee
        ? `Xác nhận lịch hẹn? Phí ${formatVND(confirmFee)} sẽ được trừ từ ví.`
        : 'Xác nhận lịch hẹn này?',
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
            className="inline-flex min-h-[48px] items-center gap-2 rounded-xl bg-primary px-7 font-headline font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-50"
          >
            <span className="material-symbols-outlined text-[1.25rem]">event_available</span>
            {isPending ? 'Đang xử lý…' : confirmFee ? `Xác nhận (${formatVND(confirmFee)})` : 'Xác nhận'}
          </button>
        )}

        {canComplete && (
          <button
            type="button"
            onClick={() => handle('complete')}
            disabled={isPending}
            className="inline-flex min-h-[48px] items-center gap-2 rounded-xl bg-primary px-7 font-headline font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-50"
          >
            <span className="material-symbols-outlined text-[1.25rem]">task_alt</span>
            {isPending ? 'Đang xử lý…' : 'Hoàn thành'}
          </button>
        )}

        {canCancel && (
          <button
            type="button"
            onClick={() => handle('cancel')}
            disabled={isPending}
            className="inline-flex min-h-[48px] items-center gap-2 rounded-xl border border-error/30 px-6 font-headline font-bold text-error transition-colors hover:bg-error-container/40 disabled:opacity-50"
          >
            <span className="material-symbols-outlined text-[1.25rem]">event_busy</span>
            {isPending ? 'Đang xử lý…' : 'Huỷ'}
          </button>
        )}
      </div>
    </div>
  );
}
