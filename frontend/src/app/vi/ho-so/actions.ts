'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { AuthUser } from '@/lib/api-types';

export type UpdateProfileResult =
  | { ok: true; user: AuthUser }
  | { ok: false; error: string; fieldErrors?: Record<string, string> };

export type UploadAvatarResult =
  | { ok: true; user: AuthUser }
  | { ok: false; error: string };

export async function uploadAvatarAction(
  _prev: UploadAvatarResult | null,
  formData: FormData,
): Promise<UploadAvatarResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập đã hết hạn' };

  try {
    const res = await api<{ data: AuthUser }>('/user/avatar', {
      method: 'POST',
      token,
      body: formData,
    });
    return { ok: true, user: res.data };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 422) {
        const body = e.body as { message?: string };
        return { ok: false, error: body.message ?? 'File không hợp lệ' };
      }
    }
    return { ok: false, error: 'Lỗi upload ảnh' };
  }
}

export async function updateProfileAction(
  _prev: UpdateProfileResult | null,
  formData: FormData,
): Promise<UpdateProfileResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập đã hết hạn' };

  const payload: Record<string, unknown> = {
    name:          String(formData.get('name') ?? '').trim(),
    username:      String(formData.get('username') ?? '').trim(),
    mobile:        String(formData.get('mobile') ?? '').trim(),
    about:         String(formData.get('about') ?? '').trim() || null,
    city_code:     String(formData.get('city_code') ?? ''),
    district_code: String(formData.get('district_code') ?? ''),
    ward_code:     String(formData.get('ward_code') ?? '') || null,
    address:       String(formData.get('address') ?? '').trim(),
  };

  try {
    const res = await api<{ data: AuthUser }>('/user/profile', {
      method: 'PUT',
      token,
      json: payload,
    });
    return { ok: true, user: res.data };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 422) {
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
      if (e.status === 429) return { ok: false, error: 'Thử quá nhiều lần, đợi 1 phút' };
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}
