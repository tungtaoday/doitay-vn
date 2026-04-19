'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { UserCompany } from '@/lib/api-types';

export type CreateCompanyResult =
  | { ok: true; id: number }
  | { ok: false; error: string; fieldErrors?: Record<string, string> };

export type UploadResult =
  | { ok: true }
  | { ok: false; error: string };

export async function createCompanyAction(
  _prev: CreateCompanyResult | null,
  formData: FormData,
): Promise<CreateCompanyResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập đã hết hạn' };

  const tagsRaw = String(formData.get('tags') ?? '').trim();
  const tags = tagsRaw ? tagsRaw.split(',').map((t) => t.trim()).filter(Boolean) : [];

  const servicesJson = String(formData.get('services_json') ?? '[]');
  let services: Array<{ name: string; description?: string; price?: string }> = [];
  try {
    const parsed = JSON.parse(servicesJson);
    if (Array.isArray(parsed)) services = parsed;
  } catch {
    // ignore
  }

  try {
    const res = await api<{ data: UserCompany }>('/user/companies', {
      method: 'POST',
      token,
      json: {
        name: String(formData.get('name') ?? '').trim(),
        email: String(formData.get('email') ?? '').trim(),
        phone: String(formData.get('phone') ?? '').trim() || null,
        category_id: Number(formData.get('category_id')),
        description: String(formData.get('description') ?? '').trim(),
        experience: Number(formData.get('experience')),
        city_code: String(formData.get('city_code') ?? ''),
        district_code: String(formData.get('district_code') ?? ''),
        ward_code: String(formData.get('ward_code') ?? '') || null,
        address: String(formData.get('address') ?? '').trim(),
        tags,
        services,
      },
    });
    return { ok: true, id: res.data.id };
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
        const firstError =
          Object.values(fieldErrors)[0] ?? body.message ?? 'Dữ liệu không hợp lệ';
        return { ok: false, error: firstError, fieldErrors };
      }
      if (e.status === 409) {
        const body = e.body as { message?: string };
        return { ok: false, error: body.message ?? 'Bạn đã có hồ sơ công ty rồi' };
      }
      if (e.status === 429) return { ok: false, error: 'Bạn thử quá nhiều lần, đợi 1 phút' };
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}

export async function uploadCompanyImage(companyId: number, file: File): Promise<UploadResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập đã hết hạn' };

  const body = new FormData();
  body.append('image', file);

  try {
    await api(`/user/companies/${companyId}/image`, {
      method: 'POST',
      token,
      body,
    });
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError && e.status === 422) {
      return { ok: false, error: 'Ảnh không hợp lệ (JPEG/PNG, tối đa 2MB)' };
    }
    return { ok: false, error: 'Lỗi upload ảnh đại diện' };
  }
}

export async function uploadPortfolioImage(
  companyId: number,
  file: File,
  title: string,
): Promise<UploadResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập đã hết hạn' };

  const body = new FormData();
  body.append('image', file);
  body.append('title', title);

  try {
    await api(`/user/companies/${companyId}/portfolio`, {
      method: 'POST',
      token,
      body,
    });
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError && e.status === 422) {
      return { ok: false, error: 'Ảnh không hợp lệ (JPEG/PNG/WebP, tối đa 2MB)' };
    }
    return { ok: false, error: 'Lỗi upload ảnh dự án' };
  }
}
