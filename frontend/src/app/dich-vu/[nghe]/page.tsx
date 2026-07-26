import Link from 'next/link';
import type { Route } from 'next';
import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { getServiceAreas, areasForNghe } from '@/lib/service-areas';
import { slugifyVi, districtLabel } from '@/lib/seo-slugs';
import { getNgheSeo } from '@/lib/seo-content';

const BASE = 'https://doitay.vn';
const SEO_INDEX = process.env.SEO_INDEX_ENABLED === 'true';
export const revalidate = 86400;

export async function generateStaticParams() {
  const areas = await getServiceAreas();
  return Array.from(new Set(areas.map((a) => slugifyVi(a.category_name)))).map((nghe) => ({ nghe }));
}

export async function generateMetadata({ params }: { params: Promise<{ nghe: string }> }): Promise<Metadata> {
  const { nghe } = await params;
  const areas = await areasForNghe(nghe);
  if (areas.length === 0) return { title: 'Không tìm thấy' };
  const name = areas[0].category_name;
  const title = `${name} theo khu vực — Đặt lịch thợ uy tín | Doitay`;
  const description = `Tìm ${name.toLowerCase()} uy tín theo khu vực trên Doitay.vn: đã kiểm duyệt, ảnh việc thật, báo giá minh bạch.`;
  return {
    title, description,
    alternates: { canonical: `/dich-vu/${nghe}` },
    robots: SEO_INDEX ? undefined : { index: false, follow: true },
    openGraph: { title, description, url: `${BASE}/dich-vu/${nghe}`, type: 'website' },
  };
}

export default async function NghePage({ params }: { params: Promise<{ nghe: string }> }) {
  const { nghe } = await params;
  const areas = await areasForNghe(nghe);
  if (areas.length === 0) notFound();
  const name = areas[0].category_name;
  const seo = getNgheSeo(nghe, 'khu vực của bạn');

  return (
    <div className="mx-auto max-w-5xl px-6 py-10 md:px-8">
      <nav className="mb-6 flex items-center gap-1.5 text-sm text-on-surface-variant">
        <Link href={'/' as Route} className="hover:text-primary">Trang chủ</Link>
        <span className="material-symbols-outlined text-base">chevron_right</span>
        <Link href={'/dich-vu' as Route} className="hover:text-primary">Dịch vụ</Link>
        <span className="material-symbols-outlined text-base">chevron_right</span>
        <span className="font-medium text-on-surface">{name}</span>
      </nav>

      <h1 className="font-headline text-3xl font-extrabold text-on-surface md:text-4xl">{name} theo khu vực</h1>
      <p className="mt-4 max-w-3xl text-lg leading-relaxed text-on-surface-variant">{seo.intro}</p>

      <h2 className="mb-4 mt-10 font-headline text-2xl font-bold text-on-surface">Chọn khu vực</h2>
      <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
        {areas.map((a) => (
          <Link
            key={a.district}
            href={`/dich-vu/${nghe}/${slugifyVi(a.district)}` as Route}
            className="flex items-center justify-between rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/10 transition-all hover:-translate-y-0.5 hover:shadow-ambient"
          >
            <span className="font-semibold text-on-surface">{districtLabel(a.district)}</span>
            <span className="text-sm text-outline">{a.count} thợ</span>
          </Link>
        ))}
      </div>
    </div>
  );
}
