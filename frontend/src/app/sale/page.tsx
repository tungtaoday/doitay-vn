import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { SaleSubmission, SaleSubmissionListResponse } from './types';

export const metadata: Metadata = {
  title: 'Hồ sơ thợ đã nhập | Sale',
};

const STATUS_LABEL: Record<SaleSubmission['status'], { text: string; cls: string }> = {
  pending: { text: 'Chờ duyệt', cls: 'bg-tertiary-container text-on-tertiary-container' },
  approved: { text: 'Hợp lệ', cls: 'bg-primary-container text-on-primary-container' },
  rejected: { text: 'Từ chối', cls: 'bg-error-container text-on-error-container' },
};

export default async function SalePage() {
  await requireUser({ requireProfile: false });
  const token = await getToken();

  let items: SaleSubmission[] = [];
  let total = 0;
  try {
    const res = await api<SaleSubmissionListResponse>('/sale/submissions', { token: token ?? undefined });
    items = res.data;
    total = res.meta.total;
  } catch {
    // giữ danh sách rỗng nếu lỗi
  }

  return (
    <div className="mx-auto max-w-2xl px-6 py-10">
      <div className="mb-8 flex items-center justify-between">
        <div>
          <h1 className="font-headline text-2xl font-bold text-on-surface">Hồ sơ thợ đã nhập</h1>
          <p className="text-sm text-on-surface-variant">Tổng: {total} hồ sơ</p>
        </div>
        <Link
          href={'/sale/nhap' as Route}
          className="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary shadow-ambient active:scale-95"
        >
          + Nhập thợ
        </Link>
      </div>

      {items.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-low p-10 text-center text-on-surface-variant">
          Chưa có hồ sơ nào. Bấm <span className="font-semibold text-primary">+ Nhập thợ</span> để bắt đầu.
        </div>
      ) : (
        <ul className="space-y-3">
          {items.map((s) => {
            const st = STATUS_LABEL[s.status];
            return (
              <li key={s.id} className="rounded-2xl bg-surface-container-lowest p-5 shadow-soft">
                <div className="flex items-start justify-between gap-3">
                  <div>
                    <p className="font-headline font-bold text-on-surface">{s.ten_tho}</p>
                    <p className="text-sm text-on-surface-variant">
                      {s.nghe} • {s.khu_vuc}
                    </p>
                    <p className="mt-1 text-xs text-outline">
                      {s.sdt_tho} • {s.so_anh} ảnh
                    </p>
                    {s.ly_do_tu_choi ? (
                      <p className="mt-1 text-xs text-error">Lý do: {s.ly_do_tu_choi}</p>
                    ) : null}
                  </div>
                  <span className={`shrink-0 rounded-full px-3 py-1 text-xs font-bold ${st.cls}`}>
                    {st.text}
                  </span>
                </div>
              </li>
            );
          })}
        </ul>
      )}
    </div>
  );
}
