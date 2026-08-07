import type { Metadata } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { BangNghe, type NgheRow } from './bang-nghe';

export const metadata: Metadata = {
  title: 'Danh mục nghề | Quản trị',
  robots: { index: false, follow: false },
};

export default async function DanhMucPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let rows: NgheRow[] | null = null;
  let denied = false;
  try {
    const res = await api<{ data: { items: NgheRow[] } }>('/admin/ops/danh-muc', {
      token: token ?? undefined,
    });
    rows = res.data.items;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !rows) return <KhongCoQuyen />;

  const dangHien = rows.filter((r) => r.status === 1).length;
  const rongKhach = rows.filter((r) => r.status === 1 && r.so_tho === 0).length;

  return (
    <div className="max-w-3xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Danh mục nghề</h1>
        <p className="text-sm text-on-surface-variant">
          {rows.length} nghề · {dangHien} đang hiện
          {rongKhach > 0 ? ` · ${rongKhach} nghề đang hiện mà chưa có thợ nào` : ''}
        </p>
      </div>

      <BangNghe rows={rows} />
    </div>
  );
}
