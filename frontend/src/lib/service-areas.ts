import { api } from '@/lib/api';
import { unstable_cache } from 'next/cache';
import { slugifyVi } from '@/lib/seo-slugs';

export interface ServiceArea {
  category_id: number;
  category_name: string;
  city: string | null;
  district: string;
  count: number;
}

/** Cặp nghề × khu vực có đủ cung (>= SEO_MIN_THO). Cache 1h (nguồn cho SEO). */
export const getServiceAreas = unstable_cache(
  async (): Promise<ServiceArea[]> => {
    try {
      const res = await api<{ data: ServiceArea[] }>('/public/service-areas');
      return res.data;
    } catch {
      return [];
    }
  },
  ['service-areas'],
  { revalidate: 3600 },
);

/** Phân giải slug nghề+khuvuc → cặp ServiceArea thật (để query đúng chuỗi district). */
export async function resolveArea(
  ngheSlug: string,
  khuvucSlug: string,
): Promise<ServiceArea | null> {
  const areas = await getServiceAreas();
  return (
    areas.find(
      (a) => slugifyVi(a.category_name) === ngheSlug && slugifyVi(a.district) === khuvucSlug,
    ) ?? null
  );
}

/** Các khu vực có cùng nghề (cho trang /dich-vu/[nghe] + internal link). */
export async function areasForNghe(ngheSlug: string): Promise<ServiceArea[]> {
  const areas = await getServiceAreas();
  return areas.filter((a) => slugifyVi(a.category_name) === ngheSlug);
}

/** Các nghề khác cùng khu vực (internal link). */
export async function nghesInDistrict(district: string, exceptCategoryId: number): Promise<ServiceArea[]> {
  const areas = await getServiceAreas();
  return areas.filter((a) => a.district === district && a.category_id !== exceptCategoryId);
}
