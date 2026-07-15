'use client';

import { useState, useTransition } from 'react';
import { useRouter } from 'next/navigation';
import type { SaleSubmission } from '../types';
import { approveAction, rejectAction } from './review-actions';

/**
 * Thẻ duyệt 1 hồ sơ. Điểm cốt lõi: Quản lý phải NHÌN ĐƯỢC ẢNH công việc
 * (BR-2 — kiểm ảnh thật) — bấm ảnh mở bản đầy đủ ở tab mới.
 */
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
    <li className="rounded-2xl bg-surface-container-lowest p-4 shadow-soft md:p-5">
      {/* Thông tin thợ */}
      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0">
          <p className="font-headline text-lg font-bold text-on-surface">{submission.ten_tho}</p>
          <p className="text-sm text-on-surface-variant">
            {submission.nghe}
            {submission.nam_kn ? ` • ${submission.nam_kn} năm KN` : ''} • {submission.khu_vuc}
          </p>
          <a
            href={`tel:${submission.sdt_tho}`}
            className="mt-1 inline-flex items-center gap-1 text-sm font-semibold text-primary"
          >
            <span className="material-symbols-outlined text-base">call</span>
            {submission.sdt_tho}
            <span className="ml-1 text-xs font-normal text-outline">(bấm gọi xác minh)</span>
          </a>
        </div>
        <span className="shrink-0 rounded-full bg-tertiary-container px-3 py-1 text-xs font-bold text-on-tertiary-container">
          {submission.so_anh} ảnh
        </span>
      </div>

      {/* ẢNH CÔNG VIỆC — trọng tâm của nghiệm thu */}
      {submission.anh && submission.anh.length > 0 ? (
        <div className="mt-4 grid grid-cols-3 gap-2">
          {submission.anh.map((img) => (
            <a key={img.id} href={img.url} target="_blank" rel="noreferrer" className="group relative block">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img
                src={img.url}
                alt="Ảnh công việc"
                loading="lazy"
                className="aspect-square w-full rounded-xl object-cover transition-transform group-hover:scale-[1.02]"
              />
              <span className="absolute bottom-1.5 right-1.5 rounded-md bg-secondary/70 px-1.5 py-0.5 text-[10px] font-semibold text-white">
                Phóng to
              </span>
            </a>
          ))}
        </div>
      ) : (
        <p className="mt-4 rounded-xl bg-error-container/60 px-3 py-2 text-xs text-on-error-container">
          ⚠️ Không tải được ảnh — kiểm tra lại trước khi duyệt.
        </p>
      )}

      {/* Bảng giá (nếu có) */}
      {submission.bang_gia && submission.bang_gia.length > 0 ? (
        <div className="mt-4 rounded-xl bg-surface-container-low p-3">
          <p className="mb-2 text-xs font-bold uppercase tracking-wider text-outline">Bảng giá</p>
          <ul className="space-y-1 text-sm text-on-surface">
            {submission.bang_gia.map((d, i) => (
              <li key={i} className="flex justify-between gap-3">
                <span className="truncate">{d.ten}</span>
                <span className="shrink-0 font-semibold">{d.gia}</span>
              </li>
            ))}
          </ul>
        </div>
      ) : null}

      {error ? <p className="mt-3 text-xs text-error">{error}</p> : null}

      {/* Hành động */}
      {rejecting ? (
        <div className="mt-4 space-y-2">
          <input
            value={reason}
            onChange={(e) => setReason(e.target.value)}
            placeholder="Lý do từ chối (CTV sẽ thấy để sửa)..."
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
            {pending ? 'Đang xử lý...' : '✓ Duyệt — thợ lên chợ ngay'}
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
