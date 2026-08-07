'use client';

import { useState, useTransition } from 'react';
import { doiTrangThaiUser } from './actions';

/**
 * Khoá / mở khoá một tài khoản. Khoá buộc ghi lý do vì đây là thứ về sau người
 * khác đọc lại sẽ cần biết, và bản thân người bị khoá cũng có thể hỏi.
 */
export function NutKhoa({ id, dangKhoa, ten }: { id: number; dangKhoa: boolean; ten: string }) {
  const [mo, setMo] = useState(false);
  const [lyDo, setLyDo] = useState('');
  const [xong, setXong] = useState<string | null>(null);
  const [loi, setLoi] = useState<string | null>(null);
  const [dangChay, start] = useTransition();

  if (xong) return <span className="text-xs text-primary">{xong}</span>;

  function chay(status: 0 | 1) {
    setLoi(null);
    start(async () => {
      const r = await doiTrangThaiUser(id, status, lyDo);
      if (r.ok) {
        setXong(r.message);
        setMo(false);
      } else {
        setLoi(r.error);
      }
    });
  }

  if (dangKhoa) {
    return (
      <div>
        <button
          type="button"
          disabled={dangChay}
          onClick={() => chay(1)}
          className="rounded-lg bg-surface-container px-3 py-1.5 text-xs font-semibold text-on-surface disabled:opacity-50"
        >
          {dangChay ? '…' : 'Mở khoá'}
        </button>
        {loi ? <p className="mt-1 text-xs text-red-600">{loi}</p> : null}
      </div>
    );
  }

  return (
    <div>
      {mo ? (
        <div className="w-52">
          <p className="text-xs text-on-surface-variant">Khoá {ten}?</p>
          <input
            value={lyDo}
            onChange={(e) => setLyDo(e.target.value)}
            placeholder="Lý do khoá"
            className="mt-1 w-full rounded-lg bg-surface-container-low px-2 py-1.5 text-xs outline-none ring-2 ring-transparent focus:ring-primary/30"
          />
          <div className="mt-1.5 flex gap-1.5">
            <button
              type="button"
              disabled={dangChay}
              onClick={() => chay(0)}
              className="rounded-lg bg-error-container px-3 py-1.5 text-xs font-semibold text-on-error-container disabled:opacity-50"
            >
              {dangChay ? '…' : 'Khoá'}
            </button>
            <button
              type="button"
              onClick={() => setMo(false)}
              className="rounded-lg bg-surface-container px-3 py-1.5 text-xs font-semibold text-on-surface"
            >
              Thôi
            </button>
          </div>
          {loi ? <p className="mt-1 text-xs text-red-600">{loi}</p> : null}
        </div>
      ) : (
        <button
          type="button"
          onClick={() => setMo(true)}
          className="rounded-lg bg-surface-container px-3 py-1.5 text-xs font-semibold text-on-surface-variant"
        >
          Khoá
        </button>
      )}
    </div>
  );
}
