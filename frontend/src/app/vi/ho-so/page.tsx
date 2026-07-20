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

  // Container do layout /vi cung cấp (max-w-6xl px-6 py-10) — chỉ giới hạn bề
  // ngang cho dễ đọc, KHÔNG bọc thêm padding (trước đây bị padding kép).
  return (
    <div className="mx-auto max-w-4xl">
      {/* Page header — cùng ngôn ngữ với trang lịch hẹn: tile + tiêu đề + phụ đề */}
      <div className="mb-8 flex items-center gap-4">
        <div className="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-primary text-on-primary shadow-ambient">
          {user.avatar ? (
            // eslint-disable-next-line @next/next/no-img-element
            <img src={user.avatar} alt={user.name} className="h-full w-full object-cover" />
          ) : (
            <span className="material-symbols-outlined text-[2rem]" style={{ fontVariationSettings: "'FILL' 1" }}>
              account_circle
            </span>
          )}
        </div>
        <div className="min-w-0">
          <h1 className="truncate font-headline text-2xl font-bold text-on-surface md:text-3xl">
            {user.name}
          </h1>
          <p className="mt-0.5 flex flex-wrap items-center gap-x-3 text-sm text-on-surface-variant">
            <span>@{user.username ?? '—'}</span>
            {user.has_company && (
              <span className="inline-flex items-center gap-1 rounded-full bg-tertiary-container px-2.5 py-0.5 text-xs font-bold text-on-tertiary-container">
                <span className="material-symbols-outlined text-[0.875rem]" style={{ fontVariationSettings: "'FILL' 1" }}>verified</span>
                Đã xác minh
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
