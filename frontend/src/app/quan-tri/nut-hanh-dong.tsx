'use client';

import { useState, useTransition } from 'react';
import type { OpsResult } from './ops-actions';

/**
 * Nút gọi một server action, có bước xác nhận tuỳ chọn.
 *
 * Dùng chung cho chốt lịch / huỷ lịch / xoá đánh giá — mấy việc chỉ khác nhau ở
 * câu hỏi xác nhận, không đáng viết ba component gần giống nhau.
 */
export function NutHanhDong({
  chay,
  nhan,
  hoi,
  kieu = 'thuong',
}: {
  chay: () => Promise<OpsResult>;
  nhan: string;
  hoi?: string;
  kieu?: 'thuong' | 'chinh' | 'nguy_hiem';
}) {
  const [choXacNhan, setChoXacNhan] = useState(false);
  const [xong, setXong] = useState<string | null>(null);
  const [loi, setLoi] = useState<string | null>(null);
  const [dangChay, start] = useTransition();

  if (xong) return <span className="text-xs font-semibold text-primary">{xong}</span>;

  const mau =
    kieu === 'chinh'
      ? 'bg-primary text-on-primary'
      : kieu === 'nguy_hiem'
        ? 'bg-error-container text-on-error-container'
        : 'bg-surface-container text-on-surface';

  function bam() {
    if (hoi && !choXacNhan) {
      setChoXacNhan(true);
      return;
    }
    setLoi(null);
    start(async () => {
      const r = await chay();
      if (r.ok) setXong(r.message);
      else {
        setLoi(r.error);
        setChoXacNhan(false);
      }
    });
  }

  return (
    <span className="inline-flex flex-col gap-1">
      <span className="inline-flex items-center gap-1.5">
        <button
          type="button"
          disabled={dangChay}
          onClick={bam}
          className={`rounded-lg px-3 py-1.5 text-xs font-semibold disabled:opacity-50 ${mau}`}
        >
          {dangChay ? '…' : choXacNhan ? 'Chắc chắn?' : nhan}
        </button>
        {choXacNhan && !dangChay ? (
          <button
            type="button"
            onClick={() => setChoXacNhan(false)}
            className="text-xs text-on-surface-variant"
          >
            thôi
          </button>
        ) : null}
      </span>
      {choXacNhan && hoi ? <span className="text-[11px] text-on-surface-variant">{hoi}</span> : null}
      {loi ? <span className="text-[11px] text-red-600">{loi}</span> : null}
    </span>
  );
}
