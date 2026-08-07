import type { Metadata } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { BangCtv, type CtvRow } from './bang-ctv';

export const metadata: Metadata = {
  title: 'Cộng tác viên | Quản trị',
  robots: { index: false, follow: false },
};

interface Res {
  data: {
    items: CtvRow[];
    dem: {
      tong: number;
      hoat_dong: number;
      chua_bat_dau: number;
      nguoi_lanh: number;
      no_hoa_hong: number;
    };
  };
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

/**
 * CỘNG TÁC VIÊN — danh sách chính thức, không phải bảng thống kê.
 *
 * Trước đây CTV chỉ hiện ra sau khi đã nhập hồ sơ, nên người mới tuyển mà chưa
 * bắt đầu thì vô hình — đúng nhóm cần gọi nhất lại không thấy.
 */
export default async function CtvPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let d: Res['data'] | null = null;
  let denied = false;
  try {
    const res = await api<Res>('/admin/ops/ctv', { token: token ?? undefined });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  return (
    <div className="max-w-4xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Cộng tác viên</h1>
        <p className="text-sm text-on-surface-variant">
          Chỉ người trong danh sách này mới nhập được hồ sơ thợ ở doitay.vn/sale.
        </p>
      </div>

      <div className="mb-6 grid grid-cols-2 gap-3 md:grid-cols-4">
        <O nhan="Đang hoạt động" gt={`${d.dem.hoat_dong}/${d.dem.tong}`} />
        <O nhan="Chưa nhập hồ sơ nào" gt={String(d.dem.chua_bat_dau)} manh={d.dem.chua_bat_dau > 0} />
        <O nhan="Nguội >14 ngày" gt={String(d.dem.nguoi_lanh)} manh={d.dem.nguoi_lanh > 0} />
        <O nhan="Hoa hồng chưa trả" gt={vnd(d.dem.no_hoa_hong)} manh={d.dem.no_hoa_hong > 0} />
      </div>

      <BangCtv rows={d.items} />
    </div>
  );
}

function O({ nhan, gt, manh }: { nhan: string; gt: string; manh?: boolean }) {
  return (
    <div className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15">
      <p className={`font-headline text-xl font-extrabold ${manh ? 'text-primary' : 'text-on-surface'}`}>
        {gt}
      </p>
      <p className="mt-1 text-xs text-on-surface-variant">{nhan}</p>
    </div>
  );
}
