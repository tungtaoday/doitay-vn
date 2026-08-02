import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import { formatVnd } from './types';
import type { SaleSubmission, SaleSubmissionListResponse } from './types';
import { ClaimLinkBox } from './claim-link-box';

export const metadata: Metadata = {
  title: 'Hồ sơ thợ đã nhập | Sale',
};

const STATUS_LABEL: Record<SaleSubmission['status'], { text: string; cls: string }> = {
  pending: { text: 'Chờ duyệt', cls: 'bg-tertiary-container text-on-tertiary-container' },
  approved: { text: 'Hợp lệ', cls: 'bg-primary-container text-on-primary-container' },
  rejected: { text: 'Từ chối', cls: 'bg-error-container text-on-error-container' },
};

const TABS: Array<{ value: string; label: string }> = [
  { value: '', label: 'Tất cả' },
  { value: 'pending', label: 'Chờ duyệt' },
  { value: 'approved', label: 'Hợp lệ' },
  { value: 'rejected', label: 'Từ chối' },
];

interface PageProps {
  searchParams: Promise<{ status?: string }>;
}

export default async function SalePage({ searchParams }: PageProps) {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const { status = '' } = await searchParams;
  const token = await getToken();

  let items: SaleSubmission[] = [];
  let meta: SaleSubmissionListResponse['meta'] | null = null;
  try {
    const qs = status ? `?status=${status}` : '';
    const res = await api<SaleSubmissionListResponse>(`/sale/submissions${qs}`, {
      token: token ?? undefined,
    });
    items = res.data;
    meta = res.meta;
  } catch {
    // giữ rỗng nếu lỗi
  }

  const counts = meta?.counts;
  const hoaHong = meta?.tong_hoa_hong ?? 0;

  return (
    <div className="mx-auto max-w-2xl px-4 py-8 md:px-6 md:py-10">
      {/* Header + CTA */}
      <div className="mb-6 flex items-center justify-between gap-3">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Hồ sơ thợ</h1>
        <Link
          href={'/sale/nhap' as Route}
          className="shrink-0 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary shadow-ambient transition-all active:scale-95"
        >
          + Nhập thợ
        </Link>
      </div>

      {/* Stats — hoa hồng là động lực chính của CTV, đưa lên đầu */}
      <div className="mb-6 grid grid-cols-2 gap-3">
        <div className="col-span-2 flex items-center justify-between rounded-2xl bg-gradient-to-r from-primary to-primary-container p-5 text-on-primary shadow-ambient">
          <div>
            <p className="text-xs font-semibold uppercase tracking-wider opacity-80">Hoa hồng của bạn</p>
            <p className="mt-1 font-headline text-3xl font-extrabold">{formatVnd(hoaHong)}</p>
          </div>
          <span className="material-symbols-outlined text-4xl opacity-80">payments</span>
        </div>
        <StatTile label="Chờ duyệt" value={counts?.pending ?? 0} />
        <StatTile label="Hợp lệ" value={counts?.approved ?? 0} />
      </div>

      {/* Filter tabs */}
      <div className="mb-5 flex gap-2 overflow-x-auto pb-1">
        {TABS.map((t) => {
          const active = status === t.value;
          const href = t.value ? `/sale?status=${t.value}` : '/sale';
          return (
            <Link
              key={t.value}
              href={href as Route}
              className={`shrink-0 rounded-full px-4 py-2 text-sm font-semibold transition-colors ${
                active
                  ? 'bg-secondary text-white'
                  : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'
              }`}
            >
              {t.label}
            </Link>
          );
        })}
      </div>

      {items.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-low p-10 text-center text-on-surface-variant">
          {status ? 'Không có hồ sơ ở trạng thái này.' : (
            <>Chưa có hồ sơ nào. Bấm <span className="font-semibold text-primary">+ Nhập thợ</span> để bắt đầu.</>
          )}
        </div>
      ) : (
        <ul className="space-y-3">
          {items.map((s) => {
            const st = STATUS_LABEL[s.status];
            return (
              <li key={s.id} className="rounded-2xl bg-surface-container-lowest p-4 shadow-soft md:p-5">
                <div className="flex items-start justify-between gap-3">
                  <div className="min-w-0">
                    <p className="truncate font-headline font-bold text-on-surface">{s.ten_tho}</p>
                    <p className="truncate text-sm text-on-surface-variant">
                      {s.nghe} • {s.khu_vuc}
                    </p>
                    <p className="mt-1 text-xs text-outline">{s.sdt_tho}</p>
                  </div>
                  <span className={`shrink-0 rounded-full px-3 py-1 text-xs font-bold ${st.cls}`}>
                    {st.text}
                  </span>
                </div>

                {/* Thumbnail ảnh công việc */}
                {s.anh && s.anh.length > 0 ? (
                  <div className="mt-3 flex gap-2 overflow-x-auto">
                    {s.anh.slice(0, 5).map((img) => (
                      // eslint-disable-next-line @next/next/no-img-element
                      <img
                        key={img.id}
                        src={img.url}
                        alt=""
                        loading="lazy"
                        className="h-16 w-16 shrink-0 rounded-lg object-cover"
                      />
                    ))}
                  </div>
                ) : null}

                {s.status === 'rejected' && s.ly_do_tu_choi ? (
                  <p className="mt-3 rounded-xl bg-error-container/60 px-3 py-2 text-xs text-on-error-container">
                    Lý do từ chối: {s.ly_do_tu_choi} — sửa lại thông tin/ảnh và nhập lại nhé.
                  </p>
                ) : null}

                {/* Vé còn hạn = thợ chưa bấm link → cho CTV chép lại để gửi Zalo */}
                {s.claim_link && s.status !== 'rejected' ? (
                  <ClaimLinkBox link={s.claim_link} tenTho={s.ten_tho} />
                ) : null}

                {s.status === 'approved' && s.company_id ? (
                  <a
                    href={`https://doitay.vn/tho/${s.company_id}`}
                    target="_blank"
                    rel="noreferrer"
                    className="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-primary hover:underline"
                  >
                    <span className="material-symbols-outlined text-base">open_in_new</span>
                    Xem hồ sơ trên chợ — gửi link này cho thợ
                  </a>
                ) : null}
              </li>
            );
          })}
        </ul>
      )}
    </div>
  );
}

function StatTile({ label, value }: { label: string; value: number }) {
  return (
    <div className="rounded-2xl bg-surface-container-low p-4">
      <p className="text-xs font-semibold uppercase tracking-wider text-outline">{label}</p>
      <p className="mt-1 font-headline text-2xl font-bold text-on-surface">{value}</p>
    </div>
  );
}
