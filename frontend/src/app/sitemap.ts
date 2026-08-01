import type { MetadataRoute } from 'next';
import { api } from '@/lib/api';
import type { Paginated, PublicCompanyListItem } from '@/lib/api-types';
import { getServiceAreas } from '@/lib/service-areas';
import { slugifyVi } from '@/lib/seo-slugs';

const BASE = 'https://doitay.vn';

const STATIC_PAGES: MetadataRoute.Sitemap = [
  { url: `${BASE}/`,           priority: 1.0, changeFrequency: 'daily'   },
  { url: `${BASE}/tho`,        priority: 0.9, changeFrequency: 'hourly'  },
  { url: `${BASE}/cong-ty`,    priority: 0.8, changeFrequency: 'hourly'  },
  { url: `${BASE}/gioi-thieu`, priority: 0.5, changeFrequency: 'monthly' },
  { url: `${BASE}/lien-he`,    priority: 0.5, changeFrequency: 'monthly' },
  { url: `${BASE}/faq`,        priority: 0.5, changeFrequency: 'monthly' },
  { url: `${BASE}/dieu-khoan`, priority: 0.3, changeFrequency: 'yearly'  },
  { url: `${BASE}/bao-mat`,    priority: 0.3, changeFrequency: 'yearly'  },
  { url: `${BASE}/doitay/dieu-khoan`, priority: 0.3, changeFrequency: 'yearly' },
  { url: `${BASE}/doitay/bao-mat`,    priority: 0.3, changeFrequency: 'yearly' },
];

async function loadAllCompanies(): Promise<PublicCompanyListItem[]> {
  const results: PublicCompanyListItem[] = [];
  let page = 1;
  try {
    while (true) {
      const res = await api<Paginated<PublicCompanyListItem>>(
        `/public/companies?per_page=50&page=${page}`,
      );
      results.push(...res.data);
      if (page >= res.meta.last_page) break;
      page++;
    }
  } catch {
    // If API unavailable, return what we have
  }
  return results;
}

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  // Chỉ đưa hồ sơ THẬT vào sitemap — seed/test (indexable=false) không được mời Google index.
  const companies = (await loadAllCompanies()).filter(c => c.indexable !== false);

  const companyEntries: MetadataRoute.Sitemap = companies.flatMap(c => [
    {
      url: `${BASE}/tho/${c.id}/${c.vanity_slug}`,
      priority: 0.7,
      changeFrequency: 'weekly' as const,
    },
    {
      url: `${BASE}/cong-ty/${c.id}/${c.vanity_slug}`,
      priority: 0.6,
      changeFrequency: 'weekly' as const,
    },
  ]);

  // Trang SEO nghề × khu vực — CHỈ đưa vào sitemap khi bật index (seed đã xử + có cung).
  let seoEntries: MetadataRoute.Sitemap = [];
  if (process.env.SEO_INDEX_ENABLED === 'true') {
    const areas = await getServiceAreas();
    const nghes = new Set<string>();
    for (const a of areas) nghes.add(slugifyVi(a.category_name));
    seoEntries = [
      { url: `${BASE}/dich-vu`, priority: 0.8, changeFrequency: 'weekly' },
      ...Array.from(nghes).map((n) => ({ url: `${BASE}/dich-vu/${n}`, priority: 0.7, changeFrequency: 'weekly' as const })),
      ...areas.filter((a) => a.count >= 3).map((a) => ({
        url: `${BASE}/dich-vu/${slugifyVi(a.category_name)}/${slugifyVi(a.district)}`,
        priority: 0.7, changeFrequency: 'weekly' as const,
      })),
    ];
  }

  return [...STATIC_PAGES, ...companyEntries, ...seoEntries];
}
