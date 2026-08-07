import type { Metadata } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { FormCaiDat, type CaiDat } from './form-cai-dat';

export const metadata: Metadata = {
  title: 'Cài đặt | Quản trị',
  robots: { index: false, follow: false },
};

export default async function CaiDatPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let cd: CaiDat | null = null;
  let denied = false;
  try {
    const res = await api<{ data: { cai_dat: CaiDat } }>('/admin/ops/cai-dat', {
      token: token ?? undefined,
    });
    cd = res.data.cai_dat;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !cd) return <KhongCoQuyen />;

  return (
    <div className="max-w-3xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Cài đặt</h1>
        <p className="text-sm text-on-surface-variant">
          Thông tin website và nút Zalo hỗ trợ. Tham số kinh doanh (phí lead, hoa hồng, tặng ví) nằm
          ở <code>.env</code> trên server — xem khối cấu hình ở trang Điều hành.
        </p>
      </div>

      <FormCaiDat ban_dau={cd} />

      <p className="mt-4 text-xs text-outline">
        Cấu hình mail/SMS, đăng nhập mạng xã hội và thông tin hệ thống cố ý không mở ở đây vì chứa
        khoá bí mật. Cần sửa thì vào admin cũ.
      </p>
    </div>
  );
}
