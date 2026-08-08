'use client';

import { useState } from 'react';

export interface NhatLenh {
  ngay: string;
  so_ngay: number | null;
  tieu_de: string | null;
  noi_dung: {
    viec_uu_tien?: string[];
    viec?: string[];
    chi_tiet?: Array<{ nguon: string; noi_dung: string }>;
    so_lieu?: {
      bac_dau?: { real_lead_rate?: number; share_rate?: number };
      funnel?: Record<string, number>;
      sources?: Record<string, { viewed?: number; contacted?: number }>;
      recent_tho?: Array<{
        id: number;
        name: string;
        nghe?: string | null;
        district?: string | null;
        status?: string;
        claimed?: boolean;
      }>;
    };
  };
}

/**
 * NHẬT LỆNH — việc theo blueprint 30 ngày, do bot đẩy lên mỗi sáng.
 *
 * Trước đây nội dung này là 3 tin Telegram dài: đọc xong là trôi, không tra lại
 * được ngày cũ, và đoạn trích sách rất khó đọc trên điện thoại. Ở đây trích sách
 * gập lại mặc định — mở ra khi cần, không đẩy phần việc xuống dưới màn hình.
 */
export function NhatLenhBox({ d }: { d: NhatLenh }) {
  const [moChiTiet, setMoChiTiet] = useState<number | null>(null);
  const nd = d.noi_dung ?? {};
  const viec = nd.viec ?? [];
  const uuTien = nd.viec_uu_tien ?? [];
  const chiTiet = nd.chi_tiet ?? [];
  const bd = nd.so_lieu?.bac_dau;
  const tho = nd.so_lieu?.recent_tho ?? [];

  const cuNgay = d.ngay !== new Date().toISOString().slice(0, 10);

  return (
    <section className="mb-8 rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15">
      <div className="mb-3 flex flex-wrap items-baseline justify-between gap-2">
        <h2 className="font-headline text-lg font-bold text-on-surface">
          {d.so_ngay ? `Ngày ${d.so_ngay}/30` : 'Nhật lệnh'}
          {d.tieu_de ? <span className="font-normal text-on-surface-variant"> — {d.tieu_de}</span> : null}
        </h2>
        <span className={`text-xs ${cuNgay ? 'font-semibold text-amber-700' : 'text-outline'}`}>
          {cuNgay ? `Bản ngày ${d.ngay} — bot chưa đẩy bản hôm nay` : `Cập nhật ${d.ngay}`}
        </span>
      </div>

      {uuTien.length > 0 ? (
        <div className="mb-4 rounded-xl bg-amber-50 p-3 ring-1 ring-amber-200/60">
          <p className="text-xs font-bold uppercase tracking-wide text-amber-900">
            Việc ưu tiên — làm trước
          </p>
          <ul className="mt-1.5 space-y-1">
            {uuTien.map((v, i) => (
              <li key={i} className="text-sm text-amber-900">
                • {v}
              </li>
            ))}
          </ul>
        </div>
      ) : null}

      {viec.length > 0 ? (
        <ol className="space-y-2">
          {viec.map((v, i) => (
            <li key={i} className="flex gap-3 text-sm text-on-surface">
              <span className="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-surface-container text-xs font-bold text-on-surface-variant">
                {i + 1}
              </span>
              <span>{v}</span>
            </li>
          ))}
        </ol>
      ) : (
        <p className="text-sm text-on-surface-variant">Chưa có việc nào cho hôm nay.</p>
      )}

      {chiTiet.length > 0 ? (
        <div className="mt-4">
          <p className="mb-2 text-xs font-semibold uppercase tracking-wide text-outline">
            Trích sổ tay — khỏi mở sách
          </p>
          <div className="space-y-1.5">
            {chiTiet.map((c, i) => (
              <div key={i} className="rounded-xl bg-surface-container-low">
                <button
                  type="button"
                  onClick={() => setMoChiTiet(moChiTiet === i ? null : i)}
                  className="flex w-full items-center justify-between px-3 py-2 text-left text-sm font-semibold text-on-surface"
                >
                  {c.nguon}
                  <span className="text-xs text-outline">{moChiTiet === i ? 'thu gọn' : 'mở'}</span>
                </button>
                {moChiTiet === i ? (
                  <p className="whitespace-pre-wrap px-3 pb-3 text-sm leading-relaxed text-on-surface-variant">
                    {c.noi_dung}
                  </p>
                ) : null}
              </div>
            ))}
          </div>
        </div>
      ) : null}

      {bd ? (
        <p className="mt-4 border-t border-outline-variant/10 pt-3 text-xs text-on-surface-variant">
          Bắc Đẩu 7 ngày — real_lead_rate <b className="text-on-surface">{bd.real_lead_rate ?? 0}%</b>{' '}
          · share_rate <b className="text-on-surface">{bd.share_rate ?? 0}%</b>
          {tho.length > 0 ? ` · ${tho.length} hồ sơ thợ mới` : ''}
        </p>
      ) : null}

      {tho.length > 0 ? (
        <ul className="mt-2 space-y-1">
          {tho.slice(0, 6).map((t) => (
            <li key={t.id} className="text-xs text-on-surface-variant">
              {t.status === 'live' ? '✅' : '⏳'} {t.name}
              {[t.nghe, t.district].filter(Boolean).length
                ? ` — ${[t.nghe, t.district].filter(Boolean).join(' · ')}`
                : ''}
              {t.claimed === false ? (
                <span className="font-semibold text-red-600"> · chưa bấm link nhận hồ sơ</span>
              ) : null}
            </li>
          ))}
        </ul>
      ) : null}
    </section>
  );
}
