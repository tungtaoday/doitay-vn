import type { Metadata } from 'next';
import { notFound, redirect } from 'next/navigation';
import { api, ApiError } from '@/lib/api';
import type { DetailEnvelope, PublicCompanyDetail } from '@/lib/api-types';

interface PageProps {
  params: Promise<{ id: string; rest?: string[] }>;
}

async function loadCompany(id: string): Promise<PublicCompanyDetail | null> {
  if (!/^\d+$/.test(id)) return null;
  try {
    const res = await api<DetailEnvelope<PublicCompanyDetail>>(`/public/companies/${id}`);
    return res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) return null;
    throw e;
  }
}

export async function generateMetadata({ params }: PageProps): Promise<Metadata> {
  const { id } = await params;
  const company = await loadCompany(id);
  if (!company) return { title: 'Không tìm thấy công ty' };

  return {
    title: company.name,
    description: company.description?.slice(0, 160) ?? undefined,
    openGraph: {
      title: company.name,
      description: company.description?.slice(0, 160) ?? undefined,
      images: company.image ? [company.image] : undefined,
    },
    alternates: {
      canonical: `/cong-ty/${company.id}/${company.vanity_slug}`,
    },
  };
}

export default async function CompanyDetailPage({ params }: PageProps) {
  const { id, rest } = await params;
  const company = await loadCompany(id);
  if (!company) notFound();

  // Canonicalise the vanity slug — if the visitor hit /cong-ty/{id} or
  // /cong-ty/{id}/wrong-slug, redirect to the canonical URL for SEO parity
  // with the legacy /companies/{id}/{slug} pattern.
  const currentSlug = rest?.[0];
  if (currentSlug !== company.vanity_slug) {
    redirect(`/cong-ty/${company.id}/${company.vanity_slug}`);
  }

  return (
    <article className="space-y-6">
      <header>
        <h1 className="text-3xl font-bold">{company.name}</h1>
        {company.category && (
          <p className="text-gray-600">{company.category.name}</p>
        )}
        <div className="mt-2 flex items-center gap-3 text-sm">
          <span className="font-medium">★ {company.rating_avg.toFixed(1)}</span>
          <span className="text-gray-500">({company.rating_count} đánh giá)</span>
          {company.experience > 0 && (
            <span className="text-gray-500">{company.experience} năm kinh nghiệm</span>
          )}
        </div>
      </header>

      <section className="prose max-w-none">
        <p>{company.description}</p>
      </section>

      {company.show_contact && (
        <section className="rounded-lg border bg-gray-50 p-4">
          <h2 className="font-semibold">Thông tin liên hệ</h2>
          <ul className="mt-2 space-y-1 text-sm">
            {company.phone && <li>SĐT: {company.phone}</li>}
            {company.email && <li>Email: {company.email}</li>}
            {company.website && (
              <li>
                Website:{' '}
                <a href={company.website} className="text-blue-600 underline">
                  {company.website}
                </a>
              </li>
            )}
          </ul>
        </section>
      )}

      {company.ratings_recent && company.ratings_recent.length > 0 && (
        <section>
          <h2 className="text-xl font-semibold">Đánh giá gần đây</h2>
          <ul className="mt-3 space-y-3">
            {company.ratings_recent.map((r) => (
              <li key={r.id} className="rounded-lg border p-3">
                <div className="flex items-center justify-between text-sm">
                  <span className="font-medium">{r.user?.name ?? 'Ẩn danh'}</span>
                  <span>★ {r.score.toFixed(1)}</span>
                </div>
                {r.comment && <p className="mt-1 text-sm text-gray-700">{r.comment}</p>}
              </li>
            ))}
          </ul>
        </section>
      )}
    </article>
  );
}
