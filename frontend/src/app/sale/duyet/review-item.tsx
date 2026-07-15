'use client';

import { useState, useTransition } from 'react';
import { useRouter } from 'next/navigation';
import type { SaleSubmission } from '../types';
import { approveAction, rejectAction } from './review-actions';

export function ReviewItem({ submission }: { submission: SaleSubmission }) {
  const router = useRouter();
  const [pending, startTransition] = useTransition();
  const [error, setError] = useState<string | null>(null);
  const [rejecting, setRejecting] = useState(false);
  const [reason, setReason] = useState('');

  function onApprove() {
    setError(null);
    startTransition(async () => {
      const res = await approveAction(submission.id);
      if (res.ok) router.refresh();
      else setError(res.error);
    });
  }

  function onReject() {
    if (reason.trim().length < 3) {
      setError('Nhập lý do từ chối (tối thiểu 3 ký tự).');
      return;
    }
    setError(null);
    startTransition(async () => {
      const res = await rejectAction(submission.id, reason.trim());
      if (res.ok) router.refresh();
      else setError(res.error);
    });
  }

  return (
    <li className="rounded-2xl bg-surface-container-lowest p-5 shadow-soft">
      <div className="flex items-start justify-between gap-3">
        <div>
          <p className="font-headline font-bold text-on-surface">{submission.ten_tho}</p>
          <p className="text-sm text-on-surface-variant">
            {submission.nghe} • {submission.khu_vuc}
          </p>
          <p className="mt-1 text-xs text-outline">
            {submission.sdt_tho} • {submission.so_anh} ảnh
          </p>
        </div>
      </div>

      {error ? <p className="mt-3 text-xs text-error">{error}</p> : null}

      {rejecting ? (
        <div className="mt-4 space-y-2">
          <input
            value={reason}
            onChange={(e) => setReason(e.target.value)}
            placeholder="Lý do từ chối..."
            className="h-11 w-full rounded-xl border-none bg-surface-container-low px-4 text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary/30"
          />
          <div className="flex gap-2">
            <button
              onClick={onReject}
              disabled={pending}
              className="flex-1 rounded-xl bg-error px-4 py-2.5 text-sm font-semibold text-on-error disabled:opacity-50"
            >
              Xác nhận từ chối
            </button>
            <button
              onClick={() => setRejecting(false)}
              disabled={pending}
              className="rounded-xl bg-surface-container px-4 py-2.5 text-sm font-medium text-on-surface"
            >
              Huỷ
            </button>
          </div>
        </div>
      ) : (
        <div className="mt-4 flex gap-2">
          <button
            onClick={onApprove}
            disabled={pending}
            className="flex-1 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-on-primary shadow-ambient active:scale-95 disabled:opacity-50"
          >
            {pending ? 'Đang xử lý...' : 'Duyệt hợp lệ'}
          </button>
          <button
            onClick={() => setRejecting(true)}
            disabled={pending}
            className="rounded-xl bg-surface-container px-4 py-2.5 text-sm font-medium text-on-surface"
          >
            Từ chối
          </button>
        </div>
      )}
    </li>
  );
}
