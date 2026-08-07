import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { NutHanhDong } from '../nut-hanh-dong';
import { xuLyLichHen } from '../ops-actions';
import { gio } from '../insight-types';

export const metadata: Metadata = {
  title: 'Lịch hẹn | Quản trị',
  robots: { index: false, follow: false },
};

interface Row {
  id: number;
  status: string | null;
  appointment_date: string | null;
  appointment_time: string | null;
  created_at: string | null;
  recipient_name: string | null;
  recipient_phone: string | null;
  recipient_address: string | null;
  notes: string | null;
  company_id: number | null;
  tho: string | null;
  tho_phone: string | null;
}

interface Res {
  data: {
    items: Row[];
    dem: { pending: number; confirmed: number; completed: number; canceled: number };
  };
}

const NHAN: Record<string, { text: string; cls: string }> = {
  pending: { text: 'Chờ thợ nhận', cls: 'bg-amber-100 text-amber-900' },
  confirmed: { text: 'Đã nhận', cls: 'bg-blue-100 text-blue-900' },
  completed: { text: 'Xong việc', cls: 'bg-primary-container text-on-primary-container' },
  canceled: { text: 'Đã huỷ', cls: 'bg-surface-container text-on-surface-variant' },
};

const TAB = [
  { v: 'pending', nhan: 'Chờ thợ nhận' },
  { v: 'confirmed', nhan: 'Đã nhận' },
  { v: 'completed', nhan: 'Xong việc' },
  { v: 'canceled', nhan: 'Đã huỷ' },
  { v: '', nhan: 'Tất cả' },
];

interface PageProps {
  searchParams: Promise<{ status?: string; q?: string }>;
}

/**
 * LỊCH HẸN — nơi chốt kết quả từng việc.
 *
 * Admin cố ý KHÔNG có nút "xác nhận hộ thợ": xác nhận sẽ trừ phí lead trong ví
 * thợ và có thể kích thưởng CTV, đó phải là hành động của chính thợ.
 */
export default async function LichHenPage({ searchParams }: PageProps) {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const { status = 'confirmed', q = '' } = await searchParams;
  const token = await getToken();

  let d: Res['data'] | null = null;
  let denied = false;
  try {
    const qs = new URLSearchParams();
    if (status) qs.set('status', status);
    if (q) qs.set('q', q);
    const res = await api<Res>(`/admin/ops/lich-hen?${qs.toString()}`, { token: token ?? undefined });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  const homNay = new Date().toISOString().slice(0, 10);

  return (
    <div className="max-w-5xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Lịch hẹn</h1>
        <p className="text-sm text-on-surface-variant">
          {d.dem.pending} chờ thợ nhận · {d.dem.confirmed} đã nhận · {d.dem.completed} xong việc
        </p>
      </div>

      <form action="/quan-tri/lich-hen" className="mb-4 flex gap-2">
        <input
          name="q"
          defaultValue={q}
          placeholder="Tìm tên khách, số điện thoại, tên thợ…"
          className="min-w-0 flex-1 rounded-xl bg-surface-container-low px-4 py-2.5 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
        />
        <input type="hidden" name="status" value={status} />
        <button
          type="submit"
          className="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary"
        >
          Tìm
        </button>
      </form>

      <div className="mb-4 flex flex-wrap gap-2">
        {TAB.map((t) => (
          <Link
            key={t.v || 'all'}
            href={`/quan-tri/lich-hen?status=${t.v}${q ? `&q=${encodeURIComponent(q)}` : ''}` as Route}
            className={`rounded-full px-4 py-2 text-sm font-semibold ${
              status === t.v
                ? 'bg-on-surface text-surface'
                : 'bg-surface-container text-on-surface hover:bg-surface-container-high'
            }`}
          >
            {t.nhan}
          </Link>
        ))}
      </div>

      {d.items.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-low p-8 text-center text-on-surface-variant">
          Không có lịch nào ở nhóm này.
        </div>
      ) : (
        <ul className="space-y-2">
          {d.items.map((a) => {
            const nhan = NHAN[a.status ?? ''] ?? {
              text: a.status ?? '—',
              cls: 'bg-surface-container text-on-surface',
            };
            const quaNgay =
              a.status === 'confirmed' && a.appointment_date && a.appointment_date < homNay;

            return (
              <li
                key={a.id}
                className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15"
              >
                <div className="flex flex-wrap items-start justify-between gap-3">
                  <div className="min-w-0">
                    <p className="font-semibold text-on-surface">
                      {a.recipient_name ?? `Lịch #${a.id}`}
                      {a.recipient_phone ? (
                        <>
                          {' · '}
                          <a href={`tel:${a.recipient_phone}`} className="text-primary">
                            {a.recipient_phone}
                          </a>
                        </>
                      ) : null}
                    </p>
                    <p className="text-xs text-on-surface-variant">
                      Thợ: {a.tho ?? '—'}
                      {a.tho_phone ? (
                        <>
                          {' · '}
                          <a href={`tel:${a.tho_phone}`} className="text-primary">
                            {a.tho_phone}
                          </a>
                        </>
                      ) : null}
                    </p>
                    <p className="mt-0.5 text-xs text-outline">
                      Hẹn {a.appointment_date ?? '—'} {a.appointment_time ?? ''} · đặt{' '}
                      {gio(a.created_at)}
                    </p>
                    {a.recipient_address ? (
                      <p className="mt-0.5 truncate text-xs text-outline">{a.recipient_address}</p>
                    ) : null}
                  </div>
                  <div className="flex shrink-0 flex-col items-end gap-2">
                    <span className={`rounded-full px-3 py-1 text-xs font-bold ${nhan.cls}`}>
                      {quaNgay ? 'Quá ngày chưa chốt' : nhan.text}
                    </span>
                    <span className="flex gap-1.5">
                      {a.status === 'confirmed' ? (
                        <NutHanhDong
                          chay={xuLyLichHen.bind(null, a.id, 'hoan_thanh')}
                          nhan="Chốt xong việc"
                          kieu="chinh"
                          hoi="Đánh dấu việc đã làm xong — thợ và khách đều nhận thông báo."
                        />
                      ) : null}
                      {a.status === 'pending' ? (
                        <NutHanhDong
                          chay={xuLyLichHen.bind(null, a.id, 'huy')}
                          nhan="Huỷ lịch"
                          kieu="nguy_hiem"
                          hoi="Huỷ lịch thợ chưa nhận. Khách sẽ nhận thông báo."
                        />
                      ) : null}
                    </span>
                  </div>
                </div>
              </li>
            );
          })}
        </ul>
      )}
    </div>
  );
}
