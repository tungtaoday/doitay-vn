'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { UserCompany } from '@/lib/api-types';

export type UpdateCompanyResult =
  | { ok: true; company: UserCompany }
  | { ok: false; error: string; fieldErrors?: Record<string, string> };

export async function updateCompanyAction(
  _prev: UpdateCompanyResult | null,
  formData: FormData,
): Promise<UpdateCompanyResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập đã hết hạn' };

  const companyId = Number(formData.get('company_id'));
  if (!companyId) return { ok: false, error: 'Không tìm thấy hồ sơ' };

  const tagsRaw = String(formData.get('tags') ?? '').trim();
  const tags = tagsRaw ? tagsRaw.split(',').map((t) => t.trim()).filter(Boolean) : [];

  const servicesJson = String(formData.get('services_json') ?? '[]');
  let services: Array<{ name: string; description?: string; price?: string }> = [];
  try {
    const parsed = JSON.parse(servicesJson);
    if (Array.isArray(parsed)) services = parsed;
  } catch { /* ignore */ }

  try {
    const res = await api<{ data: UserCompany }>(`/user/companies/${companyId}`, {
      method: 'PUT',
      token,
      json: {
        name:          String(formData.get('name') ?? '').trim(),
        email:         String(formData.get('email') ?? '').trim(),
        phone:         String(formData.get('phone') ?? '').trim() || null,
        category_id:   Number(formData.get('category_id')),
        description:   String(formData.get('description') ?? '').trim(),
        experience:    Number(formData.get('experience')),
        city_code:     String(formData.get('city_code') ?? ''),
        district_code: String(formData.get('district_code') ?? ''),
        ward_code:     String(formData.get('ward_code') ?? '') || null,
        address:       String(formData.get('address') ?? '').trim(),
        tags,
        services,
      },
    });
    return { ok: true, company: res.data };
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
      if (e.status === 404) return { ok: false, error: 'Không tìm thấy hồ sơ' };
      if (e.status === 429) return { ok: false, error: 'Thử quá nhiều lần, đợi 1 phút' };
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}
