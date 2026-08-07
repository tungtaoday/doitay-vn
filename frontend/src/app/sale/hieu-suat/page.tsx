import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { DieuHuongQuanTri } from '../../quan-tri/dieu-huong';
import { api } from '@/lib/api';
import { TINH_TRANG_INFO, type HieuSuatResponse, type ThoRow } from './types';

export const metadata: Metadata = { title: 'Hiệu suất thợ | Sale' };

interface PageProps {
  searchParams: Promise<{ days?: string; loc?: string }>;
}

/**
 * BẢNG HIỆU SUẤT THỢ — dành cho người quản lý.
 *
 * Không phải bảng số để ngắm: mỗi thợ được chẩn đoán sẵn đang kẹt ở đâu và kèm
 * việc phải làm. Thứ tự cột đi theo chuỗi ra tiền của Quyển 6:
 * nhận hồ sơ → gửi thẻ → khách xem → khách liên hệ.
 */
export default async function HieuSuatPage({ searchParams }: PageProps) {
  await requireUser();
  const { days = '30', loc = '' } = await searchParams;

  let payload: HieuSuatResponse['data'] | null = null;
  let loi: string | null = null;
  try {
    const res = await api<HieuSuatResponse>(
      `/public/metrics/tho-performance?token=${process.env.METRICS_TOKEN ?? ''}&days=${days}`,
    );
    payload = res.data;
  } catch {
    loi = 'Chưa đọc được số liệu — kiểm tra METRICS_TOKEN trong env của frontend.';
  }

  const tq = payload?.tong_quan;
  const rows: ThoRow[] = payload?.tho ?? [];
  const loc_rows = loc ? rows.filter((r) => r.tinh_trang === loc) : rows;

  const dem = (t: string) => rows.filter((r) => r.tinh_trang === t).length;

  return (
    <div className="mx-auto max-w-6xl px-4 py-6 md:px-6">
      <DieuHuongQuanTri dang_o="/sale/hieu-suat" />
      <div className="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 className="font-headline text-2xl font-bold text-on-surface">Hiệu suất thợ</h1>
          <p className="mt-1 text-sm text-on-surface-variant">
            {days} ngày gần nhất · nhìn theo chuỗi ra tiền: nhận hồ sơ → gửi thẻ → khách xem → khách gọi
          </p>
        </div>
        <div className="flex gap-2">
          {['7', '30', '90'].map((d) => (
            <Link
              key={d}
              href={`/sale/hieu-suat?days=${d}${loc ? `&loc=${loc}` : ''}` as Route}
              className={`rounded-xl px-3 py-2 text-sm font-semibold ${
                days === d ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface'
              }`}
            >
              {d} ngày
            </Link>
          ))}
        </div>
      </div>

      {loi ? (
        <div className="rounded-2xl bg-error-container px-4 py-3 text-sm text-on-error-container">{loi}</div>
      ) : null}

      {tq ? (
        <div className="mb-6 grid grid-cols-2 gap-3 md:grid-cols-4">
          {[
            ['Tổng thợ', tq.tong_tho, ''],
            ['Đã nhận hồ sơ', tq.da_nhan_ho_so, `${tq.tong_tho ? Math.round((tq.da_nhan_ho_so * 100) / tq.tong_tho) : 0}%`],
            ['Đã gửi thẻ', tq.da_share, `share_rate ${tq.share_rate}%`],
            ['Có khách liên hệ', tq.co_khach_lien_he, `real_lead ${tq.real_lead_rate}%`],
          ].map(([nhan, so, phu]) => (
            <div key={String(nhan)} className="rounded-2xl bg-surface-container-lowest p-4 shadow-soft">
              <p className="text-xs font-semibold uppercase tracking-wide text-outline">{nhan}</p>
              <p className="mt-1 font-headline text-3xl font-bold text-on-surface">{String(so)}</p>
              {phu ? <p className="mt-0.5 text-xs text-on-surface-variant">{phu}</p> : null}
            </div>
          ))}
        </div>
      ) : null}

      {/* Lọc theo tình trạng — bấm vào là ra đúng nhóm cần xử lý */}
      <div className="mb-4 flex flex-wrap gap-2">
        <Link
          href={`/sale/hieu-suat?days=${days}` as Route}
          className={`rounded-full px-3 py-1.5 text-sm font-semibold ${
            !loc ? 'bg-on-surface text-surface' : 'bg-surface-container text-on-surface'
          }`}
        >
          Tất cả ({rows.length})
        </Link>
        {Object.entries(TINH_TRANG_INFO).map(([key, info]) => (
          <Link
            key={key}
            href={`/sale/hieu-suat?days=${days}&loc=${key}` as Route}
            className={`rounded-full px-3 py-1.5 text-sm font-semibold ${
              loc === key ? 'bg-on-surface text-surface' : info.mau
            }`}
          >
            {info.nhan} ({dem(key)})
          </Link>
        ))}
      </div>

      {loc ? (
        <div className="mb-4 rounded-2xl bg-surface-container-low p-4">
          <p className="text-sm font-bold text-on-surface">Việc cần làm với nhóm này</p>
          <p className="mt-1 text-sm text-on-surface-variant">
            {TINH_TRANG_INFO[loc as keyof typeof TINH_TRANG_INFO]?.viec}
          </p>
        </div>
      ) : null}

      {loc_rows.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-lowest p-8 text-center text-on-surface-variant">
          Chưa có thợ nào trong nhóm này.
        </div>
      ) : (
        <div className="overflow-x-auto rounded-2xl bg-surface-container-lowest shadow-soft">
          <table className="w-full min-w-[820px] border-collapse text-left text-sm">
            <thead>
              <tr className="bg-surface-container-low text-xs uppercase tracking-wide text-outline">
                <th className="p-3">Thợ</th>
                <th className="p-3">Tình trạng</th>
                <th className="p-3 text-right">Gửi thẻ</th>
                <th className="p-3 text-right">Khách xem</th>
                <th className="p-3 text-right">Khách gọi</th>
                <th className="p-3 text-right">Tỉ lệ gọi</th>
                <th className="p-3">Hoạt động cuối</th>
              </tr>
            </thead>
            <tbody>
              {loc_rows.map((r) => {
                const info = TINH_TRANG_INFO[r.tinh_trang];
                return (
                  <tr key={r.id} className="border-t border-outline-variant/10 align-top">
                    <td className="p-3">
                      <p className="font-bold text-on-surface">{r.name}</p>
                      <p className="text-xs text-on-surface-variant">
                        {[r.nghe, r.khu_vuc].filter(Boolean).join(' · ')}
                      </p>
                      <p className="mt-0.5 text-xs text-outline">
                        {r.phone}
                        {r.status === 'pending' ? ' · chờ duyệt' : ''}
                      </p>
                    </td>
                    <td className="p-3">
                      <span className={`inline-block rounded-full px-2.5 py-1 text-xs font-bold ${info.mau}`}>
                        {info.nhan}
                      </span>
                    </td>
                    <td className="p-3 text-right font-semibold">{r.shared}</td>
                    <td className="p-3 text-right font-semibold">{r.viewed}</td>
                    <td className="p-3 text-right font-semibold">{r.contacted}</td>
                    <td className="p-3 text-right font-semibold">
                      {r.contact_rate === null ? '—' : `${r.contact_rate}%`}
                    </td>
                    <td className="p-3 text-xs text-on-surface-variant">
                      {r.last_at ? r.last_at.slice(0, 16).replace('T', ' ') : 'chưa có'}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      )}

      <p className="mt-4 text-xs text-outline">
        Ngưỡng tham chiếu (Quyển 12 §6): thẻ thợ ≥30% khách xem bấm gọi · share_rate nên tăng theo tuần.
      </p>
    </div>
  );
}
