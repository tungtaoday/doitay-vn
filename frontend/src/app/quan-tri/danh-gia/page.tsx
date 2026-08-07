import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { NutHanhDong } from '../nut-hanh-dong';
import { xoaDanhGia } from '../ops-actions';
import { gio } from '../insight-types';

export const metadata: Metadata = {
  title: 'Đánh giá | Quản trị',
  robots: { index: false, follow: false },
};

interface Row {
  id: number;
  rating: number;
  review: string | null;
  created_at: string | null;
  company_id: number | null;
  khach: string | null;
  tho: string | null;
}

interface Res {
  data: {
    items: Row[];
    dem: { tong: number; thap: number; trung_binh: number };
  };
}

interface PageProps {
  searchParams: Promise<{ max?: string; q?: string }>;
}

/**
 * ĐÁNH GIÁ — chủ yếu để soi điểm thấp và gỡ nội dung bậy.
 *
 * Mặc định lọc ≤2 sao: đánh giá tốt không cần ai duyệt, còn đánh giá xấu thì
 * phải gọi cho thợ hỏi chuyện gì đã xảy ra trước khi khách khác đọc được.
 */
export default async function DanhGiaPage({ searchParams }: PageProps) {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const { max = '2', q = '' } = await searchParams;
  const token = await getToken();

  let d: Res['data'] | null = null;
  let denied = false;
  try {
    const qs = new URLSearchParams();
    if (max) qs.set('max', max);
    if (q) qs.set('q', q);
    const res = await api<Res>(`/admin/ops/danh-gia?${qs.toString()}`, { token: token ?? undefined });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  return (
    <div className="max-w-4xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Đánh giá</h1>
        <p className="text-sm text-on-surface-variant">
          {d.dem.tong} đánh giá · trung bình {d.dem.trung_binh} sao · {d.dem.thap} cái ≤2 sao
        </p>
      </div>

      <form action="/quan-tri/danh-gia" className="mb-4 flex gap-2">
        <input
          name="q"
          defaultValue={q}
          placeholder="Tìm trong nội dung hoặc tên thợ…"
          className="min-w-0 flex-1 rounded-xl bg-surface-container-low px-4 py-2.5 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
        />
        <input type="hidden" name="max" value={max} />
        <button
          type="submit"
          className="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary"
        >
          Tìm
        </button>
      </form>

      <div className="mb-4 flex flex-wrap gap-2">
        {[
          { v: '2', nhan: '≤2 sao (cần xử lý)' },
          { v: '3', nhan: '≤3 sao' },
          { v: '', nhan: 'Tất cả' },
        ].map((x) => (
          <Link
            key={x.v || 'all'}
            href={
              `/quan-tri/danh-gia?max=${x.v}${q ? `&q=${encodeURIComponent(q)}` : ''}` as Route
            }
            className={`rounded-full px-4 py-2 text-sm font-semibold ${
              max === x.v
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
          Không có đánh giá nào trong nhóm này.
        </div>
      ) : (
        <ul className="space-y-2">
          {d.items.map((r) => (
            <li
              key={r.id}
              className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15"
            >
              <div className="flex flex-wrap items-start justify-between gap-3">
                <div className="min-w-0">
                  <p className="font-semibold text-on-surface">
                    <span className={r.rating <= 2 ? 'text-red-600' : 'text-on-surface'}>
                      {'★'.repeat(Math.max(0, Math.min(5, r.rating)))}
                      <span className="text-outline">
                        {'★'.repeat(Math.max(0, 5 - r.rating))}
                      </span>
                    </span>{' '}
                    {r.tho ? (
                      <Link
                        href={`/tho/${r.company_id}` as Route}
                        className="text-primary hover:underline"
                      >
                        {r.tho}
                      </Link>
                    ) : null}
                  </p>
                  {r.review ? (
                    <p className="mt-1 text-sm text-on-surface-variant">{r.review}</p>
                  ) : (
                    <p className="mt-1 text-sm italic text-outline">Không có nội dung</p>
                  )}
                  <p className="mt-1 text-xs text-outline">
                    {r.khach ?? 'Khách ẩn danh'} · {gio(r.created_at)}
                  </p>
                </div>
                <NutHanhDong
                  chay={xoaDanhGia.bind(null, r.id)}
                  nhan="Xoá"
                  kieu="nguy_hiem"
                  hoi="Xoá hẳn đánh giá này, không khôi phục được."
                />
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
