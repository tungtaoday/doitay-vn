import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { HANH_DONG_NHAN, KENH_NHAN, gio, type DiemChamResponse } from '../insight-types';

export const metadata: Metadata = {
  title: 'Điểm chạm & hành động | Quản trị',
  robots: { index: false, follow: false },
};

interface PageProps {
  searchParams: Promise<{ days?: string }>;
}

/**
 * ĐIỂM CHẠM — mọi hành động đo được của cả khách lẫn thợ, trên web và Mini App.
 *
 * Có thêm khối "chưa gắn đo": không có nó thì một con số 0 dễ bị đọc nhầm là
 * "không ai làm", trong khi thật ra là "chưa cắm cảm biến chỗ đó".
 */
export default async function DiemChamPage({ searchParams }: PageProps) {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const { days = '30' } = await searchParams;
  const token = await getToken();

  let d: DiemChamResponse['data'] | null = null;
  let denied = false;
  try {
    const res = await api<DiemChamResponse>(`/admin/insight/diem-cham?days=${days}`, {
      token: token ?? undefined,
    });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  const p = d.pheu;
  const buoc = [
    { nhan: 'Thợ gửi thẻ đi', n: p.profile_shared ?? 0 },
    { nhan: 'Khách xem hồ sơ', n: p.profile_viewed ?? 0 },
    { nhan: 'Khách bấm gọi', n: p.contact_clicked ?? 0 },
    { nhan: 'Thành lịch', n: p.booking_confirmed ?? 0 },
  ];
  const dinh = Math.max(...buoc.map((b) => b.n), 1);

  return (
    <div className="max-w-6xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Điểm chạm &amp; hành động</h1>
        <p className="text-sm text-on-surface-variant">
          {d.days} ngày · mọi hành động ghi được từ web doitay.vn và Mini App ThợTốt
        </p>
      </div>

      <div className="mb-6 flex gap-2">
        {['7', '30', '90'].map((x) => (
          <Link
            key={x}
            href={`/quan-tri/diem-cham?days=${x}` as Route}
            className={`rounded-xl px-3 py-2 text-sm font-semibold ${
              days === x ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface'
            }`}
          >
            {x} ngày
          </Link>
        ))}
      </div>

      {/* Phễu chính — chuỗi ra tiền của Bắc Đẩu */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Phễu chính</h2>
      <div className="mb-8 space-y-2">
        {buoc.map((b, i) => {
          const truoc = i > 0 ? buoc[i - 1].n : 0;
          const tyLe = i > 0 && truoc > 0 ? Math.round((b.n * 100) / truoc) : null;
          return (
            <div key={b.nhan} className="rounded-2xl bg-surface-container-lowest p-3 ring-1 ring-outline-variant/15">
              <div className="mb-1.5 flex items-baseline justify-between gap-2">
                <span className="text-sm font-semibold text-on-surface">{b.nhan}</span>
                <span className="font-headline text-lg font-extrabold text-on-surface">
                  {b.n}
                  {tyLe !== null ? (
                    <span className="ml-2 text-xs font-semibold text-on-surface-variant">
                      {tyLe}% bậc trên
                    </span>
                  ) : null}
                </span>
              </div>
              <div className="h-2 overflow-hidden rounded-full bg-surface-container">
                <div
                  className="h-full rounded-full bg-primary"
                  style={{ width: `${Math.round((b.n * 100) / dinh)}%` }}
                />
              </div>
            </div>
          );
        })}
      </div>

      {/* Kênh nào mang khách tới */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Khách đến từ kênh nào</h2>
      {d.kenh.length === 0 ? (
        <p className="mb-8 rounded-2xl bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
          Chưa có lượt nào trong kỳ.
        </p>
      ) : (
        <div className="mb-8 overflow-x-auto rounded-2xl bg-surface-container-lowest shadow-soft">
          <table className="w-full min-w-[620px] text-left text-sm">
            <thead>
              <tr className="bg-surface-container-low text-xs uppercase tracking-wide text-outline">
                <th className="p-3">Kênh</th>
                <th className="p-3 text-right">Xem hồ sơ</th>
                <th className="p-3 text-right">Bấm gọi</th>
                <th className="p-3 text-right">Tỉ lệ gọi</th>
                <th className="p-3 text-right">Thành lịch</th>
                <th className="p-3 text-right">Tổng lượt</th>
              </tr>
            </thead>
            <tbody>
              {d.kenh.map((k) => (
                <tr key={k.src} className="border-t border-outline-variant/10">
                  <td className="p-3 font-semibold text-on-surface">{KENH_NHAN[k.src] ?? k.src}</td>
                  <td className="p-3 text-right">{k.viewed}</td>
                  <td className="p-3 text-right">{k.contacted}</td>
                  <td className="p-3 text-right font-semibold">
                    {k.ty_le_goi === null ? '—' : `${k.ty_le_goi}%`}
                  </td>
                  <td className="p-3 text-right">{k.booked}</td>
                  <td className="p-3 text-right text-on-surface-variant">{k.tong}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Bảng hành động */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Từng hành động</h2>
      {d.hanh_dong.length === 0 ? (
        <p className="mb-8 rounded-2xl bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
          Chưa ghi được hành động nào trong kỳ.
        </p>
      ) : (
        <div className="mb-8 overflow-x-auto rounded-2xl bg-surface-container-lowest shadow-soft">
          <table className="w-full min-w-[620px] text-left text-sm">
            <thead>
              <tr className="bg-surface-container-low text-xs uppercase tracking-wide text-outline">
                <th className="p-3">Hành động</th>
                <th className="p-3">Ai · ở đâu</th>
                <th className="p-3 text-right">Lượt</th>
                <th className="p-3 text-right">Người</th>
                <th className="p-3">Lần cuối</th>
              </tr>
            </thead>
            <tbody>
              {d.hanh_dong.map((h, i) => (
                <tr key={`${h.event}-${h.surface}-${h.channel}-${i}`} className="border-t border-outline-variant/10">
                  <td className="p-3">
                    <p className="font-semibold text-on-surface">
                      {HANH_DONG_NHAN[h.event] ?? h.event}
                    </p>
                    <p className="text-xs text-outline">{h.event}</p>
                  </td>
                  <td className="p-3 text-xs text-on-surface-variant">
                    {h.surface === 'tho' ? 'Thợ' : 'Khách'} ·{' '}
                    {h.channel === 'miniapp' ? 'Mini App' : 'Web'}
                  </td>
                  <td className="p-3 text-right font-semibold">{h.luot}</td>
                  <td className="p-3 text-right">{h.nguoi}</td>
                  <td className="p-3 text-xs text-on-surface-variant">{gio(h.last_at)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Trung thực về độ phủ đo lường */}
      {d.chua_do.length > 0 ? (
        <div className="mb-8 rounded-2xl bg-amber-50 p-4 ring-1 ring-amber-200/60">
          <p className="font-headline text-sm font-bold text-amber-900">
            Điểm chạm chưa gắn đo ({d.chua_do.length})
          </p>
          <p className="mt-1 text-xs text-amber-900/80">
            Những chỗ này chưa bắn sự kiện nên bảng trên không phản ánh được. Số 0 ở đây nghĩa là
            &quot;chưa đo&quot;, không phải &quot;không ai làm&quot;.
          </p>
          <ul className="mt-2 flex flex-wrap gap-2">
            {d.chua_do.map((c) => (
              <li key={c.event} className="rounded-full bg-white/70 px-3 py-1 text-xs text-amber-900">
                {c.mo_ta}
              </li>
            ))}
          </ul>
        </div>
      ) : null}

      {/* Dòng sự kiện gần đây */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Gần đây</h2>
      {d.gan_day.length === 0 ? (
        <p className="rounded-2xl bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
          Chưa có sự kiện nào.
        </p>
      ) : (
        <ul className="space-y-1.5">
          {d.gan_day.map((s, i) => (
            <li
              key={i}
              className="flex flex-wrap items-baseline justify-between gap-2 rounded-xl bg-surface-container-lowest px-4 py-2.5 text-sm"
            >
              <span className="font-semibold text-on-surface">
                {HANH_DONG_NHAN[s.event] ?? s.event}
                {s.tho ? <span className="font-normal text-on-surface-variant"> — {s.tho}</span> : null}
              </span>
              <span className="text-xs text-outline">
                {KENH_NHAN[s.src ?? 'khac'] ?? s.src} · {gio(s.created_at)}
              </span>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
