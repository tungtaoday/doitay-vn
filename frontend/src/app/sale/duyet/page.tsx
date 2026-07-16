import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import type { SaleSubmission, SaleSubmissionListResponse } from '../types';
import { ReviewItem } from './review-item';

export const metadata: Metadata = {
  title: 'Duyệt hồ sơ thợ | Quản lý',
};

// TODO Phase 3: gate bằng admin guard (hiện chỉ auth:sanctum — xem v1_admin.php TODO).
export default async function DuyetPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let items: SaleSubmission[] = [];
  let denied = false;
  try {
    const res = await api<SaleSubmissionListResponse>('/admin/submissions?status=pending', {
      token: token ?? undefined,
    });
    items = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
    // các lỗi khác: giữ danh sách rỗng
  }

  if (denied) {
    return (
      <div className="mx-auto max-w-2xl px-6 py-20 text-center">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Không có quyền</h1>
        <p className="mt-3 text-on-surface-variant">
          Trang này chỉ dành cho Quản lý duyệt hồ sơ.
        </p>
        <Link href={'/sale' as Route} className="mt-6 inline-block text-primary hover:underline">
          ← Về danh sách của tôi
        </Link>
      </div>
    );
  }

  return (
    <div className="mx-auto max-w-2xl px-6 py-10">
      <div className="mb-8 flex items-center justify-between">
        <div>
          <h1 className="font-headline text-2xl font-bold text-on-surface">Duyệt hồ sơ thợ</h1>
          <p className="text-sm text-on-surface-variant">{items.length} hồ sơ chờ duyệt</p>
        </div>
        <Link href={'/sale' as Route} className="text-sm text-primary hover:underline">
          Danh sách của tôi →
        </Link>
      </div>

      {items.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-low p-10 text-center text-on-surface-variant">
          Không có hồ sơ nào đang chờ duyệt.
        </div>
      ) : (
        <ul className="space-y-3">
          {items.map((s) => (
            <ReviewItem key={s.id} submission={s} />
          ))}
        </ul>
      )}
    </div>
  );
}
