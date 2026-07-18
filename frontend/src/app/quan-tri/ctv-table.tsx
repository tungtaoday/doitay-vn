'use client';

import { useState, useTransition } from 'react';
import { useRouter } from 'next/navigation';
import { markPaidAction } from './actions';

export interface CtvRow {
  ctv_id: number;
  name: string;
  nhap: number;
  duyet: number;
  tu_choi: number;
  ti_le_duyet: number;
  hoa_hong_tong: number;
  hoa_hong_chua_tra: number;
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

export function CtvTable({ rows }: { rows: CtvRow[] }) {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [msg, setMsg] = useState<string | null>(null);

  function pay(ctvId: number, name: string) {
    setMsg(null);
    startTransition(async () => {
      const res = await markPaidAction(ctvId);
      if (!res.ok) { setMsg(res.error); return; }
      setMsg(`Đã đánh dấu trả ${vnd(res.tongTien)} (${res.soKhoan} khoản) cho ${name}.`);
      router.refresh();
    });
  }

  if (rows.length === 0) {
    return (
      <div className="rounded-2xl border border-dashed border-outline-variant/60 bg-surface-container-lowest px-6 py-10 text-center text-sm text-on-surface-variant">
        Chưa có dữ liệu CTV.
      </div>
    );
  }

  return (
    <div className="overflow-x-auto rounded-2xl bg-surface-container-lowest ring-1 ring-outline-variant/15">
      {msg ? (
        <p className="border-b border-outline-variant/15 px-4 py-2 text-xs font-semibold text-primary">{msg}</p>
      ) : null}
      <table className="w-full text-sm">
        <thead>
          <tr className="text-left text-xs uppercase tracking-wide text-on-surface-variant">
            <th className="px-4 py-3">CTV</th>
            <th className="px-3 py-3 text-right">Nhập</th>
            <th className="px-3 py-3 text-right">Duyệt</th>
            <th className="px-3 py-3 text-right">Tỉ lệ</th>
            <th className="px-3 py-3 text-right">Hoa hồng</th>
            <th className="px-3 py-3 text-right">Chưa trả</th>
            <th className="px-4 py-3 text-right">Đối soát</th>
          </tr>
        </thead>
        <tbody className="divide-y divide-outline-variant/10">
          {rows.map((r) => (
            <tr key={r.ctv_id}>
              <td className="px-4 py-3 font-semibold text-on-surface">{r.name}</td>
              <td className="px-3 py-3 text-right">{r.nhap}</td>
              <td className="px-3 py-3 text-right font-bold text-primary">{r.duyet}</td>
              <td className={`px-3 py-3 text-right ${r.ti_le_duyet >= 0.7 ? 'text-emerald-700' : 'text-red-600'}`}>
                {Math.round(r.ti_le_duyet * 100)}%
              </td>
              <td className="px-3 py-3 text-right">{vnd(r.hoa_hong_tong)}</td>
              <td className={`px-3 py-3 text-right font-bold ${r.hoa_hong_chua_tra > 0 ? 'text-red-600' : 'text-on-surface-variant'}`}>
                {vnd(r.hoa_hong_chua_tra)}
              </td>
              <td className="px-4 py-3 text-right">
                {r.hoa_hong_chua_tra > 0 ? (
                  <button
                    onClick={() => pay(r.ctv_id, r.name)}
                    disabled={isPending}
                    className="rounded-lg bg-primary px-3 py-1.5 text-xs font-bold text-on-primary transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-60"
                  >
                    Đã trả
                  </button>
                ) : (
                  <span className="text-xs text-on-surface-variant">✓ sạch nợ</span>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
