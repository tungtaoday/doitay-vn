'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { CompleteProfileResponse, LocationItem } from '@/lib/api-types';

export type ProfileResult =
  | { ok: true; registerAsExpert: boolean }
  | { ok: false; error: string; fieldErrors?: Record<string, string> };

export async function completeProfileAction(
  _prev: ProfileResult | null,
  formData: FormData,
): Promise<ProfileResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập đã hết hạn' };

  const username = String(formData.get('username') ?? '').trim();
  const mobile = String(formData.get('mobile') ?? '').trim();
  const cityCode = String(formData.get('city_code') ?? '');
  const districtCode = String(formData.get('district_code') ?? '');
  const wardCode = String(formData.get('ward_code') ?? '');
  const address = String(formData.get('address') ?? '').trim();
  const registerAsExpert = formData.get('register_as_expert') === 'on';

  try {
    const res = await api<CompleteProfileResponse>('/user/complete-profile', {
      method: 'POST',
      token,
      json: {
        username,
        mobile,
        city_code: cityCode,
        district_code: districtCode,
        ward_code: wardCode,
        address,
        register_as_expert: registerAsExpert,
      },
    });
    return { ok: true, registerAsExpert: res.register_as_expert };
  } catch (e) {
    if (e instanceof ApiError && e.status === 422) {
      const body = e.body as { errors?: Record<string, string[]>; message?: string };
      const fieldErrors: Record<string, string> = {};
      if (body.errors) {
        for (const [key, msgs] of Object.entries(body.errors)) {
          fieldErrors[key] = msgs[0];
        }
      }
      const firstError = Object.values(fieldErrors)[0] ?? body.message ?? 'Dữ liệu không hợp lệ';
      return { ok: false, error: firstError, fieldErrors };
    }
    if (e instanceof ApiError && e.status === 429) {
      return { ok: false, error: 'Bạn thử quá nhiều lần, đợi 1 phút' };
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}

export async function fetchCities(): Promise<LocationItem[]> {
  try {
    const res = await api<{ data: LocationItem[] }>('/public/locations/cities');
    return res.data;
  } catch {
    return [];
  }
}

export async function fetchDistricts(cityCode: string): Promise<LocationItem[]> {
  if (!cityCode) return [];
  try {
    const res = await api<{ data: LocationItem[] }>(`/public/locations/districts/${cityCode}`);
    return res.data;
  } catch {
    return [];
  }
}

export async function fetchWards(districtCode: string): Promise<LocationItem[]> {
  if (!districtCode) return [];
  try {
    const res = await api<{ data: LocationItem[] }>(`/public/locations/wards/${districtCode}`);
    return res.data;
  } catch {
    return [];
  }
}
