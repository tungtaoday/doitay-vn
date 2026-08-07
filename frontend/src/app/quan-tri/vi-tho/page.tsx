import type { Metadata } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';

export const metadata: Metadata = {
  title: 'Ví thợ & giao dịch | Quản trị',
  robots: { index: false, follow: false },
};

interface ViRow {
  id: number;
  tho: string | null;
  tho_phone: string | null;
  balance: number;
  is_active: boolean;
}

interface GiaoDichRow {
  id: number;
  type: string;
  amount: number;
  transaction_type: string | null;
  description: string | null;
  created_at: string | null;
  tho: string | null;
}

interface Res {
  data: { vi: ViRow[]; tong_du: number; giao_dich: GiaoDichRow[] };
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

/**
 * VÍ THỢ — số dư là tiền ẢO (credit dùng để mở thông tin khách), không phải
 * tiền mặt phải trả. Đặt cạnh sổ giao dịch để khi thợ thắc mắc "sao trừ tiền"
 * là tra được ngay, không phải mở admin cũ.
 */
export default async function ViThoPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let d: Res['data'] | null = null;
  let denied = false;
  try {
    const res = await api<Res>('/admin/ops/wallets', { token: token ?? undefined });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  const coTien = d.vi.filter((v) => v.balance > 0).length;

  return (
    <div className="max-w-5xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Ví thợ &amp; giao dịch</h1>
        <p className="text-sm text-on-surface-variant">
          Số dư là credit (tiền ảo) thợ dùng để mở thông tin khách — không phải tiền mặt phải trả.
        </p>
      </div>

      <div className="mb-6 grid grid-cols-3 gap-3">
        <O nhan="Tổng số dư đang nợ" gt={vnd(d.tong_du)} />
        <O nhan="Ví có số dư" gt={`${coTien}/${d.vi.length}`} />
        <O nhan="Giao dịch gần đây" gt={String(d.giao_dich.length)} />
      </div>

      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Số dư từng thợ</h2>
      {d.vi.length === 0 ? (
        <p className="mb-8 rounded-2xl bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
          Chưa có ví nào.
        </p>
      ) : (
        <div className="mb-8 overflow-x-auto rounded-2xl bg-surface-container-lowest shadow-soft">
          <table className="w-full min-w-[520px] text-left text-sm">
            <thead>
              <tr className="bg-surface-container-low text-xs uppercase tracking-wide text-outline">
                <th className="p-3">Thợ</th>
                <th className="p-3 text-right">Số dư</th>
                <th className="p-3">Trạng thái</th>
              </tr>
            </thead>
            <tbody>
              {d.vi.map((v) => (
                <tr key={v.id} className="border-t border-outline-variant/10">
                  <td className="p-3">
                    <p className="font-semibold text-on-surface">{v.tho ?? `Ví #${v.id}`}</p>
                    {v.tho_phone ? (
                      <a href={`tel:${v.tho_phone}`} className="text-xs text-primary">
                        {v.tho_phone}
                      </a>
                    ) : null}
                  </td>
                  <td className="p-3 text-right font-headline font-bold text-on-surface">
                    {vnd(v.balance)}
                  </td>
                  <td className="p-3">
                    <span
                      className={`rounded-full px-2.5 py-1 text-xs font-bold ${
                        v.is_active
                          ? 'bg-primary-container text-on-primary-container'
                          : 'bg-surface-container text-on-surface-variant'
                      }`}
                    >
                      {v.is_active ? 'Hoạt động' : 'Tạm khoá'}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Sổ giao dịch gần đây</h2>
      {d.giao_dich.length === 0 ? (
        <p className="rounded-2xl bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
          Chưa có giao dịch nào.
        </p>
      ) : (
        <ul className="space-y-1.5">
          {d.giao_dich.map((g) => {
            const cong = g.type === 'credit';
            return (
              <li
                key={g.id}
                className="flex flex-wrap items-baseline justify-between gap-2 rounded-xl bg-surface-container-lowest px-4 py-2.5 text-sm"
              >
                <span className="min-w-0">
                  <span className="font-semibold text-on-surface">{g.tho ?? '—'}</span>
                  <span className="text-on-surface-variant"> · {g.description ?? g.transaction_type}</span>
                </span>
                <span className="flex shrink-0 items-baseline gap-3">
                  <span className={`font-headline font-bold ${cong ? 'text-emerald-700' : 'text-red-600'}`}>
                    {cong ? '+' : '−'}
                    {vnd(Math.abs(g.amount))}
                  </span>
                  <span className="text-xs text-outline">
                    {(g.created_at ?? '').slice(0, 16).replace('T', ' ')}
                  </span>
                </span>
              </li>
            );
          })}
        </ul>
      )}
    </div>
  );
}

function O({ nhan, gt }: { nhan: string; gt: string }) {
  return (
    <div className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15">
      <p className="font-headline text-xl font-extrabold text-on-surface">{gt}</p>
      <p className="mt-1 text-xs text-on-surface-variant">{nhan}</p>
    </div>
  );
}
