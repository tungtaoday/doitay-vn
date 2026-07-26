import Link from 'next/link';
import type { Route } from 'next';
import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { api } from '@/lib/api';
import type { Paginated, PublicCompanyListItem } from '@/lib/api-types';
import { getServiceAreas, resolveArea, nghesInDistrict, areasForNghe } from '@/lib/service-areas';
import { slugifyVi, districtLabel } from '@/lib/seo-slugs';
import { getNgheSeo, keywordsFor } from '@/lib/seo-content';
import { getServiceSuggestions } from '@/lib/service-suggestions';
import { getPlaceholderImage, isSeedImage } from '@/lib/placeholder-images';

const BASE = 'https://doitay.vn';
// Kill-switch: seed data chưa xử lý → KHÔNG index bất kỳ trang SEO nào.
// Bật bằng env SEO_INDEX_ENABLED=true (sau khi xử seed + có cung thật).
const SEO_INDEX = process.env.SEO_INDEX_ENABLED === 'true';

export const revalidate = 86400; // 24h

// Prerender các cặp có cung (nguồn: service-areas). Không sinh tổ hợp rỗng.
export async function generateStaticParams() {
  const areas = await getServiceAreas();
  return areas.map((a) => ({ nghe: slugifyVi(a.category_name), khuvuc: slugifyVi(a.district) }));
}

async function load(nghe: string, khuvuc: string) {
  const area = await resolveArea(nghe, khuvuc);
  if (!area) return null;
  const label = districtLabel(area.district);
  const city = area.city ?? '';
  let companies: PublicCompanyListItem[] = [];
  try {
    const res = await api<Paginated<PublicCompanyListItem>>(
      `/public/companies?category=${area.category_id}&district=${encodeURIComponent(area.district)}&per_page=24`,
    );
    companies = res.data;
  } catch { /* rỗng */ }
  return { area, label, city, companies };
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ nghe: string; khuvuc: string }>;
}): Promise<Metadata> {
  const { nghe, khuvuc } = await params;
  const data = await load(nghe, khuvuc);
  if (!data) return { title: 'Không tìm thấy' };
  const { area, label, city, companies } = data;
  const title = `${area.category_name} tại ${label}${city ? ', ' + districtLabel(city) : ''} — Đặt lịch uy tín | Doitay`;
  const description = `Tìm ${area.category_name.toLowerCase()} uy tín tại ${label}: ${companies.length}+ thợ đã kiểm duyệt, báo giá minh bạch, đặt lịch nhanh trên Doitay.vn.`;
  const indexable = SEO_INDEX && companies.length >= 3;
  return {
    title,
    description,
    keywords: keywordsFor(area.category_name, label),
    alternates: { canonical: `/dich-vu/${nghe}/${khuvuc}` },
    robots: indexable ? undefined : { index: false, follow: true },
    openGraph: { title, description, url: `${BASE}/dich-vu/${nghe}/${khuvuc}`, type: 'website' },
  };
}

export default async function ServiceAreaPage({
  params,
}: {
  params: Promise<{ nghe: string; khuvuc: string }>;
}) {
  const { nghe, khuvuc } = await params;
  const data = await load(nghe, khuvuc);
  if (!data) notFound();
  const { area, label, city, companies } = data;
  const seo = getNgheSeo(nghe, label);
  const priceHints = getServiceSuggestions(area.category_name).slice(0, 6);
  const [otherNghe, otherAreas] = await Promise.all([
    nghesInDistrict(area.district, area.category_id),
    areasForNghe(nghe),
  ]);

  const jsonld = {
    '@context': 'https://schema.org',
    '@graph': [
      {
        '@type': 'BreadcrumbList',
        itemListElement: [
          { '@type': 'ListItem', position: 1, name: 'Trang chủ', item: BASE },
          { '@type': 'ListItem', position: 2, name: 'Dịch vụ', item: `${BASE}/dich-vu` },
          { '@type': 'ListItem', position: 3, name: area.category_name, item: `${BASE}/dich-vu/${nghe}` },
          { '@type': 'ListItem', position: 4, name: label },
        ],
      },
      {
        '@type': 'ItemList',
        itemListElement: companies.slice(0, 20).map((c, i) => ({
          '@type': 'ListItem',
          position: i + 1,
          url: `${BASE}/tho/${c.id}/${c.vanity_slug}`,
          name: c.name,
        })),
      },
      {
        '@type': 'FAQPage',
        mainEntity: seo.faq.map((f) => ({
          '@type': 'Question',
          name: f.q,
          acceptedAnswer: { '@type': 'Answer', text: f.a },
        })),
      },
    ],
  };

  return (
    <div className="mx-auto max-w-6xl px-6 py-8 md:px-8">
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonld) }} />

      {/* Breadcrumb */}
      <nav className="mb-6 flex flex-wrap items-center gap-1.5 text-sm text-on-surface-variant">
        <Link href={'/' as Route} className="hover:text-primary">Trang chủ</Link>
        <span className="material-symbols-outlined text-base">chevron_right</span>
        <Link href={'/dich-vu' as Route} className="hover:text-primary">Dịch vụ</Link>
        <span className="material-symbols-outlined text-base">chevron_right</span>
        <Link href={`/dich-vu/${nghe}` as Route} className="hover:text-primary">{area.category_name}</Link>
        <span className="material-symbols-outlined text-base">chevron_right</span>
        <span className="font-medium text-on-surface">{label}</span>
      </nav>

      <h1 className="font-headline text-3xl font-extrabold text-on-surface md:text-4xl">
        {area.category_name} tại {label}{city ? `, ${districtLabel(city)}` : ''}
      </h1>
      <p className="mt-4 max-w-3xl text-lg leading-relaxed text-on-surface-variant">{seo.intro}</p>

      {/* Danh sách thợ */}
      <section className="mt-10">
        <h2 className="mb-4 font-headline text-2xl font-bold text-on-surface">
          {companies.length} thợ tại {label}
        </h2>
        {companies.length === 0 ? (
          <div className="rounded-2xl bg-surface-container-low p-8 text-center text-on-surface-variant">
            Chưa có thợ hiển thị ở khu vực này. <Link href={'/yeu-cau' as Route} className="font-bold text-primary">Tạo yêu cầu</Link> để hệ thống tìm thợ giúp bạn.
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {companies.map((c) => {
              const img = isSeedImage(c.image) ? getPlaceholderImage(c.category?.name, c.name) : c.image!;
              return (
                <Link
                  key={c.id}
                  href={`/tho/${c.id}/${c.vanity_slug}` as Route}
                  className="flex gap-4 rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/10 transition-all hover:-translate-y-0.5 hover:shadow-ambient"
                >
                  {/* eslint-disable-next-line @next/next/no-img-element */}
                  <img src={img} alt={c.name} className="h-16 w-16 shrink-0 rounded-xl object-cover" />
                  <div className="min-w-0">
                    <p className="truncate font-headline font-bold text-on-surface">{c.name}</p>
                    <p className="truncate text-sm text-on-surface-variant">{c.category?.name}</p>
                    <p className="mt-0.5 flex items-center gap-1 text-sm">
                      <span className="material-symbols-outlined text-[1rem] text-tertiary" style={{ fontVariationSettings: "'FILL' 1" }}>star</span>
                      <span className="font-bold text-on-surface">{c.rating_avg > 0 ? c.rating_avg.toFixed(1) : 'Mới'}</span>
                      {c.rating_count > 0 ? <span className="text-outline">({c.rating_count})</span> : null}
                    </p>
                  </div>
                </Link>
              );
            })}
          </div>
        )}
      </section>

      {/* Bảng giá tham khảo */}
      {priceHints.length > 0 && (
        <section className="mt-10">
          <h2 className="mb-4 font-headline text-2xl font-bold text-on-surface">Dịch vụ {area.category_name.toLowerCase()} phổ biến tại {label}</h2>
          <div className="flex flex-wrap gap-2">
            {priceHints.map((p) => (
              <span key={p} className="rounded-full bg-surface-container-high px-4 py-2 text-sm font-medium text-on-surface">{p}</span>
            ))}
          </div>
          <p className="mt-3 text-sm text-outline">Giá tham khảo — thợ báo giá chính xác theo hạng mục cụ thể.</p>
        </section>
      )}

      {/* Internal links */}
      {(otherNghe.length > 0 || otherAreas.length > 1) && (
        <section className="mt-10 grid gap-6 md:grid-cols-2">
          {otherNghe.length > 0 && (
            <div>
              <h3 className="mb-2 font-headline font-bold text-on-surface">Nghề khác tại {label}</h3>
              <div className="flex flex-wrap gap-2">
                {otherNghe.map((a) => (
                  <Link key={a.category_id} href={`/dich-vu/${slugifyVi(a.category_name)}/${khuvuc}` as Route} className="text-sm text-primary hover:underline">{a.category_name}</Link>
                ))}
              </div>
            </div>
          )}
          {otherAreas.length > 1 && (
            <div>
              <h3 className="mb-2 font-headline font-bold text-on-surface">{area.category_name} khu vực khác</h3>
              <div className="flex flex-wrap gap-2">
                {otherAreas.filter((a) => a.district !== area.district).map((a) => (
                  <Link key={a.district} href={`/dich-vu/${nghe}/${slugifyVi(a.district)}` as Route} className="text-sm text-primary hover:underline">{districtLabel(a.district)}</Link>
                ))}
              </div>
            </div>
          )}
        </section>
      )}

      {/* FAQ */}
      <section className="mt-10">
        <h2 className="mb-4 font-headline text-2xl font-bold text-on-surface">Câu hỏi thường gặp</h2>
        <div className="space-y-3">
          {seo.faq.map((f) => (
            <details key={f.q} className="rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/10">
              <summary className="cursor-pointer font-bold text-on-surface">{f.q}</summary>
              <p className="mt-2 text-on-surface-variant">{f.a}</p>
            </details>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section className="mt-10 rounded-3xl bg-primary p-8 text-center md:p-12">
        <h2 className="font-headline text-2xl font-extrabold text-on-primary md:text-3xl">Cần {area.category_name.toLowerCase()} tại {label}?</h2>
        <p className="mx-auto mt-2 max-w-xl text-on-primary/85">Tạo yêu cầu miễn phí — hệ thống gợi ý thợ phù hợp quanh khu vực bạn.</p>
        <Link href={'/yeu-cau' as Route} className="mt-6 inline-flex h-14 items-center gap-2 rounded-xl bg-surface-container-lowest px-8 font-headline text-lg font-bold text-primary shadow-ambient transition-all hover:bg-surface-container-low active:scale-95">
          Tạo yêu cầu ngay
          <span className="material-symbols-outlined">arrow_forward</span>
        </Link>
      </section>
    </div>
  );
}
