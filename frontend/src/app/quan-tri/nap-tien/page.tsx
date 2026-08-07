import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { LenhNapItem, type LenhNap } from './lenh-nap';

export const metadata: Metadata = {
  title: 'Lệnh nạp tiền | Quản trị',
  robots: { index: false, follow: false },
};

interface Res {
  data: {
    items: LenhNap[];
    dem: {
      pending: number;
      processing: number;
      completed: number;
      rejected: number;
      cho_duyet_tien: number;
    };
  };
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

const TAB: Array<{ v: string; nhan: string }> = [
  { v: 'pending', nhan: 'Chờ duyệt' },
  { v: 'processing', nhan: 'Đang xử lý' },
  { v: 'completed', nhan: 'Đã cộng ví' },
  { v: '', nhan: 'Tất cả' },
];

interface PageProps {
  searchParams: Promise<{ status?: string }>;
}

/**
 * LỆNH NẠP TIỀN — màn duy nhất của admin cũ được dùng hàng ngày (đọc log 15
 * ngày: 50 lượt, các màn khác gần như 0). Kéo về đây để không phải nhảy sang
 * hệ quản trị thứ hai chỉ vì một việc.
 */
export default async function NapTienPage({ searchParams }: PageProps) {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const { status = 'pending' } = await searchParams;
  const token = await getToken();

  let d: Res['data'] | null = null;
  let denied = false;
  try {
    const qs = status ? `?status=${status}` : '';
    const res = await api<Res>(`/admin/ops/deposits${qs}`, { token: token ?? undefined });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  return (
    <div className="max-w-4xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Lệnh nạp tiền</h1>
        <p className="text-sm text-on-surface-variant">
          Duyệt là cộng tiền thật vào ví thợ — đối chiếu sao kê ngân hàng trước khi bấm.
        </p>
      </div>

      <div className="mb-6 grid grid-cols-2 gap-3 md:grid-cols-4">
        <O nhan="Chờ duyệt" gt={String(d.dem.pending)} nhan_manh={d.dem.pending > 0} />
        <O nhan="Tiền đang chờ" gt={vnd(d.dem.cho_duyet_tien)} nhan_manh={d.dem.cho_duyet_tien > 0} />
        <O nhan="Đã cộng ví" gt={String(d.dem.completed)} />
        <O nhan="Từ chối / huỷ" gt={String(d.dem.rejected)} />
      </div>

      <div className="mb-4 flex flex-wrap gap-2">
        {TAB.map((t) => (
          <Link
            key={t.v || 'all'}
            href={(t.v ? `/quan-tri/nap-tien?status=${t.v}` : '/quan-tri/nap-tien?status=') as Route}
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
          Không có lệnh nào ở trạng thái này.
        </div>
      ) : (
        <ul className="space-y-3">
          {d.items.map((x) => (
            <LenhNapItem key={x.id} d={x} />
          ))}
        </ul>
      )}
    </div>
  );
}

function O({ nhan, gt, nhan_manh }: { nhan: string; gt: string; nhan_manh?: boolean }) {
  return (
    <div className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15">
      <p
        className={`font-headline text-xl font-extrabold ${
          nhan_manh ? 'text-primary' : 'text-on-surface'
        }`}
      >
        {gt}
      </p>
      <p className="mt-1 text-xs text-on-surface-variant">{nhan}</p>
    </div>
  );
}
