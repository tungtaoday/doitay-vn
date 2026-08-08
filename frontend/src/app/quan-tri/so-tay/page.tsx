import type { Metadata } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';

export const metadata: Metadata = {
  title: 'Sổ tay vận hành | Quản trị',
  robots: { index: false, follow: false },
};

/**
 * SỔ TAY VẬN HÀNH — 12 quyển + phụ lục, bản dựng sẵn.
 *
 * Trước đây sách chỉ mở được ở máy anh (localhost:3939/sach.html), phải bật app
 * agent-system mới xem — nên trên đường hoặc trên điện thoại là chịu. Nay nằm
 * trong trung tâm quản trị, sau cùng một cổng đăng nhập.
 *
 * Nội dung nạp qua iframe tới route `so-tay/noi-dung` (có kiểm quyền riêng) để
 * giữ nguyên CSS/JS của bản dựng, khỏi phải chuyển sang React.
 */
export default async function SoTayPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  try {
    await api('/admin/ops/cai-dat', { token: token ?? undefined });
  } catch (e) {
    if (e instanceof ApiError) return <KhongCoQuyen />;
    throw e;
  }

  return (
    <div className="max-w-none">
      <div className="mb-4 flex flex-wrap items-baseline justify-between gap-2">
        <div>
          <h1 className="font-headline text-2xl font-bold text-on-surface">Sổ tay vận hành</h1>
          <p className="text-sm text-on-surface-variant">
            12 quyển + phụ lục. Việc từng ngày trích từ đây nằm ở{' '}
            <span className="font-semibold">Việc hôm nay</span>.
          </p>
        </div>
        <a
          href="/quan-tri/so-tay/noi-dung"
          target="_blank"
          rel="noreferrer"
          className="rounded-xl bg-surface-container px-4 py-2 text-sm font-semibold text-on-surface"
        >
          Mở tab mới ↗
        </a>
      </div>

      <iframe
        src="/quan-tri/so-tay/noi-dung"
        title="Sổ tay vận hành"
        className="h-[calc(100vh-13rem)] w-full rounded-2xl bg-white ring-1 ring-outline-variant/15"
      />

      <p className="mt-3 text-xs text-outline">
        Nội dung nội bộ — không lập chỉ mục, chỉ Quản lý đăng nhập mới đọc được. Sửa sổ tay ở
        <code className="mx-1">agent-system/so-tay-van-hanh/</code>rồi dựng lại và chép đè
        <code className="mx-1">frontend/content/so-tay.html</code>.
      </p>
    </div>
  );
}
