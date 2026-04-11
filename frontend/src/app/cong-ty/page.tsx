import Link from 'next/link';
import type { Metadata } from 'next';
import { api } from '@/lib/api';
import type { Paginated, PublicCompanyListItem } from '@/lib/api-types';

export const metadata: Metadata = {
  title: 'Danh sách công ty',
  description: 'Tìm kiếm các công ty, nhà thầu uy tín đã được kiểm duyệt.',
};

interface PageProps {
  searchParams: Promise<{ q?: string; category?: string; page?: string }>;
}

export default async function CompanyListPage({ searchParams }: PageProps) {
  const params = await searchParams;
  const qs = new URLSearchParams();
  if (params.q) qs.set('q', params.q);
  if (params.category) qs.set('category', params.category);
  if (params.page) qs.set('page', params.page);
  qs.set('per_page', '20');

  let payload: Paginated<PublicCompanyListItem> | null = null;
  let error: string | null = null;
  try {
    payload = await api<Paginated<PublicCompanyListItem>>(`/public/companies?${qs.toString()}`);
  } catch (e) {
    error = e instanceof Error ? e.message : 'Không tải được danh sách';
  }

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold">Danh sách công ty</h1>

      <form className="flex gap-2" action="/cong-ty" method="get">
        <input
          name="q"
          defaultValue={params.q ?? ''}
          placeholder="Tìm theo tên, mô tả..."
          className="flex-1 rounded-lg border px-4 py-2"
        />
        <button className="rounded-lg bg-blue-600 px-4 py-2 text-white">Tìm</button>
      </form>

      {error && (
        <div className="rounded-lg border border-red-300 bg-red-50 p-4 text-red-700">
          Lỗi: {error}
        </div>
      )}

      {payload && payload.data.length === 0 && (
        <p className="text-gray-500">Không tìm thấy công ty nào.</p>
      )}

      {payload && payload.data.length > 0 && (
        <>
          <ul className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {payload.data.map((c) => (
              <li key={c.id} className="rounded-lg border p-4 hover:shadow-md">
                <Link href={`/cong-ty/${c.id}/${c.vanity_slug}`} className="block">
                  <h2 className="text-lg font-semibold">{c.name}</h2>
                  {c.category && (
                    <p className="text-sm text-gray-500">{c.category.name}</p>
                  )}
                  <p className="mt-2 line-clamp-2 text-sm text-gray-600">
                    {c.short_description}
                  </p>
                  <div className="mt-3 flex items-center gap-2 text-sm">
                    <span className="font-medium">★ {c.rating_avg.toFixed(1)}</span>
                    <span className="text-gray-400">({c.rating_count})</span>
                  </div>
                </Link>
              </li>
            ))}
          </ul>

          <div className="flex items-center justify-between text-sm text-gray-600">
            <span>
              Trang {payload.meta.current_page} / {payload.meta.last_page} —
              tổng {payload.meta.total} công ty
            </span>
          </div>
        </>
      )}
    </div>
  );
}
