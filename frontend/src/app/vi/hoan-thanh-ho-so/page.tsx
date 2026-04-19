import { redirect } from 'next/navigation';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser, LocationItem } from '@/lib/api-types';
import { ProfileForm } from './profile-form';

export const metadata = {
  title: 'Hoàn thành hồ sơ — doitay.vn',
};

export default async function CompleteProfilePage() {
  const token = await getToken();
  if (!token) redirect('/login');

  let user: AuthUser;
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    user = res.data;
  } catch {
    redirect('/login');
  }

  if (user.profile_complete) {
    if (!user.has_company && (user.pending_role === 'contractor' || user.pending_role === 'both')) {
      redirect('/vi/tho/dang-ky');
    }
    redirect(user.has_company ? '/vi/tho/lich-hen' : '/vi/lich-hen');
  }

  let cities: LocationItem[] = [];
  try {
    const res = await api<{ data: LocationItem[] }>('/public/locations/cities');
    cities = res.data;
  } catch {
    // will show empty dropdown
  }

  return (
    <section className="mx-auto max-w-lg px-6 py-12">
      <div className="mb-8 text-center">
        <h1 className="font-headline text-2xl font-bold text-on-surface">
          Hoàn thành hồ sơ
        </h1>
        <p className="mt-2 text-sm text-on-surface-variant">
          Vui lòng bổ sung thông tin để sử dụng dịch vụ
        </p>
      </div>
      <ProfileForm
        initialCities={cities}
        defaultPhone={user.mobile ?? ''}
        defaultRegisterAsExpert={user.pending_role === 'contractor' || user.pending_role === 'both'}
      />
    </section>
  );
}
