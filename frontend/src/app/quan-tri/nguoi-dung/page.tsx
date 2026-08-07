import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { NutKhoa } from './nut-khoa';

export const metadata: Metadata = {
  title: 'Người dùng | Quản trị',
  robots: { index: false, follow: false },
};

interface UserRow {
  id: number;
  name: string | null;
  username: string | null;
  email: string | null;
  mobile: string | null;
  city: string | null;
  status: number;
  ev: number | null;
  sv: number | null;
  created_at: string | null;
  is_seeded: number | null;
  company_id: number | null;
  company_name: string | null;
}

interface Res {
  data: {
    items: UserRow[];
    dem: { tong: number; tho: number; khoa: number; seed: number };
  };
}

const LOC: Array<{ v: string; nhan: string }> = [
  { v: '', nhan: 'Tất cả' },
  { v: 'tho', nhan: 'Là thợ' },
  { v: 'khach', nhan: 'Khách' },
  { v: 'khoa', nhan: 'Đang khoá' },
  { v: 'seed', nhan: 'Dữ liệu mồi' },
];

interface PageProps {
  searchParams: Promise<{ q?: string; loc?: string }>;
}

/**
 * NGƯỜI DÙNG — tra cứu và khoá/mở tài khoản.
 *
 * Cố ý KHÔNG kéo về: sửa hồ sơ người dùng, đăng nhập hộ, gửi thông báo hàng
 * loạt. Mấy thứ đó vừa hiếm dùng vừa dễ gây hại; cần thì vào admin cũ.
 */
export default async function NguoiDungPage({ searchParams }: PageProps) {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const { q = '', loc = '' } = await searchParams;
  const token = await getToken();

  let d: Res['data'] | null = null;
  let denied = false;
  try {
    const qs = new URLSearchParams();
    if (q) qs.set('q', q);
    if (loc) qs.set('loc', loc);
    const res = await api<Res>(`/admin/ops/users?${qs.toString()}`, { token: token ?? undefined });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  return (
    <div className="max-w-5xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Người dùng</h1>
        <p className="text-sm text-on-surface-variant">
          {d.dem.tong} tài khoản · {d.dem.tho} là thợ · {d.dem.khoa} đang khoá · {d.dem.seed} là dữ
          liệu mồi
        </p>
      </div>

      <form action="/quan-tri/nguoi-dung" className="mb-4 flex gap-2">
        <input
          name="q"
          defaultValue={q}
          placeholder="Tìm tên, số điện thoại, email…"
          className="min-w-0 flex-1 rounded-xl bg-surface-container-low px-4 py-2.5 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
        />
        {loc ? <input type="hidden" name="loc" value={loc} /> : null}
        <button
          type="submit"
          className="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary"
        >
          Tìm
        </button>
      </form>

      <div className="mb-4 flex flex-wrap gap-2">
        {LOC.map((x) => (
          <Link
            key={x.v || 'all'}
            href={
              (x.v
                ? `/quan-tri/nguoi-dung?loc=${x.v}${q ? `&q=${encodeURIComponent(q)}` : ''}`
                : `/quan-tri/nguoi-dung${q ? `?q=${encodeURIComponent(q)}` : ''}`) as Route
            }
            className={`rounded-full px-3 py-1.5 text-sm font-semibold ${
              loc === x.v
                ? 'bg-on-surface text-surface'
                : 'bg-surface-container text-on-surface hover:bg-surface-container-high'
            }`}
          >
            {x.nhan}
          </Link>
        ))}
      </div>

      {d.items.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-low p-8 text-center text-on-surface-variant">
          Không tìm thấy tài khoản nào.
        </div>
      ) : (
        <div className="overflow-x-auto rounded-2xl bg-surface-container-lowest shadow-soft">
          <table className="w-full min-w-[760px] text-left text-sm">
            <thead>
              <tr className="bg-surface-container-low text-xs uppercase tracking-wide text-outline">
                <th className="p-3">Tài khoản</th>
                <th className="p-3">Liên hệ</th>
                <th className="p-3">Vai trò</th>
                <th className="p-3">Trạng thái</th>
                <th className="p-3" />
              </tr>
            </thead>
            <tbody>
              {d.items.map((u) => {
                const khoa = Number(u.status) === 0;
                return (
                  <tr key={u.id} className="border-t border-outline-variant/10 align-top">
                    <td className="p-3">
                      <p className="font-semibold text-on-surface">{u.name ?? `#${u.id}`}</p>
                      <p className="text-xs text-outline">
                        #{u.id}
                        {u.username ? ` · ${u.username}` : ''}
                        {u.is_seeded ? ' · mồi' : ''}
                      </p>
                    </td>
                    <td className="p-3 text-xs">
                      {u.mobile ? (
                        <a href={`tel:${u.mobile}`} className="block text-primary">
                          {u.mobile}
                        </a>
                      ) : null}
                      <span className="block text-on-surface-variant">{u.email}</span>
                      <span className="block text-outline">{u.city}</span>
                    </td>
                    <td className="p-3 text-xs">
                      {u.company_id ? (
                        <Link
                          href={`/tho/${u.company_id}` as Route}
                          className="font-semibold text-primary hover:underline"
                        >
                          Thợ: {u.company_name}
                        </Link>
                      ) : (
                        <span className="text-on-surface-variant">Khách</span>
                      )}
                    </td>
                    <td className="p-3">
                      <span
                        className={`rounded-full px-2.5 py-1 text-xs font-bold ${
                          khoa
                            ? 'bg-error-container text-on-error-container'
                            : 'bg-primary-container text-on-primary-container'
                        }`}
                      >
                        {khoa ? 'Đang khoá' : 'Hoạt động'}
                      </span>
                    </td>
                    <td className="p-3">
                      <NutKhoa id={u.id} dangKhoa={khoa} ten={u.name ?? `#${u.id}`} />
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      )}

      {d.items.length >= 200 ? (
        <p className="mt-3 text-xs text-outline">
          Đang hiện 200 dòng đầu — dùng ô tìm kiếm để thu hẹp.
        </p>
      ) : null}
    </div>
  );
}
