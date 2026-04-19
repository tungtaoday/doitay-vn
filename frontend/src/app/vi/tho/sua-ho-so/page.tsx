import { redirect } from 'next/navigation';
import Link from 'next/link';
import type { Route } from 'next';
import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { LocationItem, PublicCategory, UserCompany } from '@/lib/api-types';
import { CompanyEditForm } from './company-edit-form';

export const metadata = {
  title: 'Sửa hồ sơ thợ — doitay.vn',
};

export default async function EditCompanyPage() {
  const token = await getToken();
  if (!token) redirect('/login?error=unauthenticated');

  // Load company, categories, cities in parallel
  let companies: UserCompany[] = [];
  let categories: PublicCategory[] = [];
  let cities: LocationItem[] = [];

  try {
    const [companiesRes, catRes, citiesRes] = await Promise.all([
      api<{ data: UserCompany[] }>('/user/companies', { token }),
      api<{ data: PublicCategory[] }>('/public/categories'),
      api<{ data: LocationItem[] }>('/public/locations/cities'),
    ]);
    companies = companiesRes.data;
    categories = catRes.data;
    cities = citiesRes.data;
  } catch {
    redirect('/vi/tho/lich-hen');
  }

  const company = companies[0];
  if (!company) redirect('/vi/tho/dang-ky');

  // Resolve city code from display name
  const cityItem = cities.find((c) => c.name === company.location.city);
  const defaultCityCode = cityItem?.code ?? '';

  // Pre-load districts
  let initialDistricts: LocationItem[] = [];
  let defaultDistrictCode = '';
  if (defaultCityCode) {
    try {
      const res = await api<{ data: LocationItem[] }>(
        `/public/locations/districts?city_code=${defaultCityCode}`,
      );
      initialDistricts = res.data;
      const districtItem = initialDistricts.find((d) => d.name === company.location.district);
      defaultDistrictCode = districtItem?.code ?? '';
    } catch { /* empty */ }
  }

  // Pre-load wards
  let initialWards: LocationItem[] = [];
  if (defaultDistrictCode) {
    try {
      const res = await api<{ data: LocationItem[] }>(
        `/public/locations/wards?district_code=${defaultDistrictCode}`,
      );
      initialWards = res.data;
    } catch { /* empty */ }
  }

  // Status 1 = active (đã duyệt) — chỉ khi đó trang công khai mới truy cập được
  const isActive = company.status === 1;

  return (
    <div className="mx-auto max-w-4xl px-6 py-10 md:px-8">
      {/* Header */}
      <div className="mb-6 flex items-start justify-between gap-4">
        <div>
          <p className="text-[0.875rem] font-bold uppercase tracking-widest text-primary">Hồ sơ thợ</p>
          <h1 className="mt-1 font-headline text-[2rem] font-bold text-on-surface">{company.name}</h1>
          <span className={`mt-2 inline-flex items-center gap-1 rounded-full px-3 py-0.5 text-[0.75rem] font-bold ${
            isActive
              ? 'bg-primary/10 text-primary'
              : company.status === 2
                ? 'bg-tertiary/10 text-tertiary'
                : 'bg-error/10 text-error'
          }`}>
            {company.status_label}
          </span>
        </div>
        {isActive ? (
          <Link href={`/cong-ty/${company.id}` as Route}
            className="flex shrink-0 items-center gap-2 rounded-xl border border-outline-variant/30 bg-surface-container-low px-5 py-2.5 text-[0.875rem] font-bold text-on-surface transition-colors hover:bg-surface-container">
            <span className="material-symbols-outlined text-[1.125rem]">open_in_new</span>
            Xem trang công khai
          </Link>
        ) : (
          <div className="rounded-xl border border-outline-variant/20 bg-surface-container-low px-5 py-2.5 text-[0.875rem] text-secondary">
            <span className="material-symbols-outlined mr-1 align-middle text-[1rem]">schedule</span>
            Đang chờ admin duyệt
          </div>
        )}
      </div>

      {/* Banner pending */}
      {!isActive && company.status === 2 && (
        <div className="mb-8 flex items-start gap-3 rounded-xl bg-tertiary/10 px-6 py-4">
          <span className="material-symbols-outlined text-tertiary" style={{ fontVariationSettings: "'FILL' 1" }}>info</span>
          <div>
            <p className="font-bold text-on-surface">Hồ sơ đang chờ xét duyệt</p>
            <p className="mt-1 text-[0.875rem] text-secondary">
              Admin sẽ duyệt hồ sơ của bạn trong vòng 24 giờ. Bạn vẫn có thể chỉnh sửa thông tin trong lúc chờ.
            </p>
          </div>
        </div>
      )}

      <CompanyEditForm
        company={company}
        categories={categories}
        cities={cities}
        defaultCityCode={defaultCityCode}
        defaultDistrictCode={defaultDistrictCode}
        initialDistricts={initialDistricts}
        initialWards={initialWards}
      />
    </div>
  );
}
