import Link from 'next/link';
import type { Route } from 'next';
import type { Metadata } from 'next';
import { getServiceAreas } from '@/lib/service-areas';
import { slugifyVi, districtLabel } from '@/lib/seo-slugs';

const BASE = 'https://doitay.vn';
const SEO_INDEX = process.env.SEO_INDEX_ENABLED === 'true';
export const revalidate = 86400;

export const metadata: Metadata = {
  title: 'Dịch vụ thợ theo khu vực — Điện, Nước, Điều hòa... | Doitay',
  description: 'Tìm thợ uy tín theo nghề và khu vực trên Doitay.vn: thợ điện, thợ nước, thợ điều hòa... đã kiểm duyệt, báo giá minh bạch.',
  alternates: { canonical: '/dich-vu' },
  robots: SEO_INDEX ? undefined : { index: false, follow: true },
};

export default async function DichVuHub() {
  const areas = await getServiceAreas();
  // gom theo nghề
  const byNghe = new Map<string, { name: string; areas: typeof areas }>();
  for (const a of areas) {
    const slug = slugifyVi(a.category_name);
    if (!byNghe.has(slug)) byNghe.set(slug, { name: a.category_name, areas: [] });
    byNghe.get(slug)!.areas.push(a);
  }

  return (
    <div className="mx-auto max-w-6xl px-6 py-10 md:px-8">
      <h1 className="font-headline text-3xl font-extrabold text-on-surface md:text-4xl">Dịch vụ thợ theo khu vực</h1>
      <p className="mt-3 max-w-2xl text-lg text-on-surface-variant">Chọn nghề và khu vực để xem thợ uy tín đã kiểm duyệt gần bạn.</p>

      {byNghe.size === 0 ? (
        <p className="mt-8 text-on-surface-variant">Đang cập nhật khu vực có thợ.</p>
      ) : (
        <div className="mt-8 space-y-8">
          {Array.from(byNghe.entries()).map(([slug, g]) => (
            <section key={slug}>
              <h2 className="mb-3 font-headline text-xl font-bold text-on-surface">
                <Link href={`/dich-vu/${slug}` as Route} className="hover:text-primary">{g.name}</Link>
              </h2>
              <div className="flex flex-wrap gap-2">
                {g.areas.map((a) => (
                  <Link
                    key={a.district}
                    href={`/dich-vu/${slug}/${slugifyVi(a.district)}` as Route}
                    className="rounded-full bg-surface-container-low px-4 py-2 text-sm font-medium text-on-surface transition-colors hover:bg-surface-container"
                  >
                    {districtLabel(a.district)} <span className="text-outline">({a.count})</span>
                  </Link>
                ))}
              </div>
            </section>
          ))}
        </div>
      )}
    </div>
  );
}
