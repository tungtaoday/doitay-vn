import { redirect } from 'next/navigation';
import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { AuthUser, LocationItem } from '@/lib/api-types';
import { ProfileEditForm } from './profile-edit-form';

export const metadata = {
  title: 'Hồ sơ của tôi — doitay.vn',
};

export default async function ProfilePage() {
  const token = await getToken();
  if (!token) redirect('/login?error=unauthenticated');

  let user: AuthUser;
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    user = res.data;
  } catch {
    redirect('/login');
  }

  if (!user.profile_complete) redirect('/vi/hoan-thanh-ho-so');

  // Load cities + pre-resolve district list from user's city
  let cities: LocationItem[] = [];
  let initialDistricts: LocationItem[] = [];
  let initialWards: LocationItem[] = [];

  try {
    const citiesRes = await api<{ data: LocationItem[] }>('/public/locations/cities');
    cities = citiesRes.data;
  } catch { /* empty dropdowns */ }

  const userCity = cities.find((c) => c.name === user.location.city);
  if (userCity) {
    try {
      const districtsRes = await api<{ data: LocationItem[] }>(
        `/public/locations/districts?city_code=${userCity.code}`,
      );
      initialDistricts = districtsRes.data;
    } catch { /* empty */ }
  }

  return (
    <div className="mx-auto max-w-4xl px-6 py-10 md:px-8">
      {/* Page header */}
      <div className="mb-10 flex items-center gap-5">
        <div className="flex h-20 w-20 items-center justify-center overflow-hidden rounded-2xl bg-primary/10">
          {user.avatar ? (
            // eslint-disable-next-line @next/next/no-img-element
            <img src={user.avatar} alt={user.name} className="h-full w-full object-cover" />
          ) : (
            <span className="material-symbols-outlined text-4xl text-primary" style={{ fontVariationSettings: "'FILL' 1" }}>
              account_circle
            </span>
          )}
        </div>
        <div>
          <h1 className="font-headline text-[2rem] font-bold text-on-surface">{user.name}</h1>
          <p className="mt-1 text-[1rem] text-secondary">
            @{user.username ?? '—'}
            {user.has_company && (
              <span className="ml-3 inline-flex items-center gap-1 text-tertiary">
                <span className="material-symbols-outlined text-[1rem]" style={{ fontVariationSettings: "'FILL' 1" }}>verified</span>
                Verified
              </span>
            )}
          </p>
        </div>
      </div>

      <ProfileEditForm
        user={user}
        cities={cities}
        initialDistricts={initialDistricts}
        initialWards={initialWards}
      />
    </div>
  );
}
