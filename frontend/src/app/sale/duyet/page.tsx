import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import type { SaleSubmission, SaleSubmissionListResponse } from '../types';
import { ReviewItem } from './review-item';
import { OpsQueuesPanel, type OpsQueues } from './ops-queues';

export const metadata: Metadata = {
  title: 'Duyệt hồ sơ thợ | Quản lý',
};

// TODO Phase 3: gate bằng admin guard (hiện chỉ auth:sanctum — xem v1_admin.php TODO).
export default async function DuyetPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let items: SaleSubmission[] = [];
  let denied = false;
  let queues: OpsQueues | null = null;
  try {
    const res = await api<SaleSubmissionListResponse>('/admin/submissions?status=pending', {
      token: token ?? undefined,
    });
    items = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
    // các lỗi khác: giữ danh sách rỗng
  }

  // P0.3: hàng đợi vận hành — lỗi thì ẩn panel, không làm hỏng trang duyệt.
  if (!denied) {
    try {
      queues = await api<OpsQueues>('/admin/ops/queues', { token: token ?? undefined });
    } catch {
      queues = null;
    }
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
    <div className="mx-auto max-w-3xl px-6 py-10">
      <div className="mb-8 flex items-center justify-between">
        <div>
          <h1 className="font-headline text-2xl font-bold text-on-surface">Vận hành &amp; duyệt hồ sơ</h1>
          <p className="text-sm text-on-surface-variant">{items.length} hồ sơ CTV chờ duyệt</p>
        </div>
        <Link href={'/sale' as Route} className="text-sm text-primary hover:underline">
          Danh sách của tôi →
        </Link>
      </div>

      {queues ? <OpsQueuesPanel queues={queues} /> : null}

      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Hồ sơ CTV nhập chờ duyệt</h2>
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
