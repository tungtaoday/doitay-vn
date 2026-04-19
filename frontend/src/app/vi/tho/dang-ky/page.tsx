import { redirect } from 'next/navigation';
import { api } from '@/lib/api';
import { requireUser } from '@/lib/require-user';
import type { LocationItem, PublicCategory } from '@/lib/api-types';
import { CompanyForm } from './company-form';

export const metadata = {
  title: 'Đăng ký làm thợ — doitay.vn',
};

export default async function RegisterExpertPage() {
  const user = await requireUser();

  if (user.has_company) {
    redirect('/vi/tho/lich-hen');
  }

  let categories: PublicCategory[] = [];
  let cities: LocationItem[] = [];
  try {
    const [catRes, citiesRes] = await Promise.all([
      api<{ data: PublicCategory[] }>('/public/categories'),
      api<{ data: LocationItem[] }>('/public/locations/cities'),
    ]);
    categories = catRes.data;
    cities = citiesRes.data;
  } catch {
    // degrade: empty dropdowns
  }

  const defaultCityCode = cities.find((c) => c.name === user.location.city)?.code ?? '';

  return (
    <div className="fixed inset-0 z-30 overflow-auto bg-surface">
      <CompanyForm
        categories={categories}
        cities={cities}
        defaultName={user.name}
        defaultEmail={user.email}
        defaultPhone={user.mobile ?? ''}
        defaultCityCode={defaultCityCode}
        defaultAddress={user.location.address ?? ''}
      />
    </div>
  );
}
