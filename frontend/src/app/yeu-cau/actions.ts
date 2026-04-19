'use server';

import { redirect } from 'next/navigation';
import type { Route } from 'next';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { ServiceRequestResponse } from '@/lib/api-types';

export type CreateServiceRequestInput = {
  category_id: number;
  title: string;
  description: string;
  city: string;
  district?: string;
  ward?: string;
  address?: string;
  contact_name: string;
  contact_phone: string;
  preferred_date?: string;
  preferred_time_slot?: 'morning' | 'afternoon' | 'evening' | 'flexible';
  budget_min?: number;
  budget_max?: number;
};

export type CreateServiceRequestResult =
  | { ok: true; id: number }
  | { ok: false; error: string; fieldErrors?: Record<string, string>; needsLogin?: boolean };

export async function createServiceRequestAction(
  input: CreateServiceRequestInput,
): Promise<CreateServiceRequestResult> {
  const token = await getToken();
  if (!token) {
    return { ok: false, error: 'Vui lòng đăng nhập để tạo yêu cầu', needsLogin: true };
  }

  try {
    const res = await api<ServiceRequestResponse>('/user/service-requests', {
      method: 'POST',
      token,
      json: input,
    });
    return { ok: true, id: res.data.id };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 401) {
        return { ok: false, error: 'Phiên đăng nhập đã hết hạn', needsLogin: true };
      }
      if (e.status === 403) {
        return { ok: false, error: 'Vui lòng hoàn thành hồ sơ trước khi tạo yêu cầu' };
      }
      if (e.status === 422) {
        const body = e.body as { message?: string; errors?: Record<string, string[]> };
        const fieldErrors: Record<string, string> = {};
        for (const [k, v] of Object.entries(body.errors ?? {})) {
          if (Array.isArray(v) && v.length) fieldErrors[k] = v[0] as string;
        }
        return {
          ok: false,
          error: body.message ?? 'Dữ liệu không hợp lệ',
          fieldErrors,
        };
      }
      if (e.status === 429) {
        const body = e.body as { message?: string };
        return { ok: false, error: body.message ?? 'Bạn đang thao tác quá nhanh, vui lòng thử lại sau.' };
      }
    }
    return { ok: false, error: 'Không thể gửi yêu cầu. Vui lòng thử lại.' };
  }
}

/**
 * Legacy export — giữ để request-wizard.tsx cũ không vỡ nếu còn import.
 * Wizard mới nên gọi trực tiếp createServiceRequestAction và tự redirect.
 */
export async function createRequest(formData: FormData): Promise<void> {
  const input: CreateServiceRequestInput = {
    category_id: Number(formData.get('category_id') ?? 0),
    title: String(formData.get('title') ?? '').trim(),
    description: String(formData.get('description') ?? '').trim(),
    city: String(formData.get('city') ?? '').trim(),
    district: String(formData.get('district') ?? '').trim() || undefined,
    ward: String(formData.get('ward') ?? '').trim() || undefined,
    address: String(formData.get('address') ?? '').trim() || undefined,
    contact_name: String(formData.get('contact_name') ?? '').trim(),
    contact_phone: String(formData.get('contact_phone') ?? '').trim(),
  };
  const result = await createServiceRequestAction(input);
  if (!result.ok) {
    throw new Error(result.error);
  }
  redirect(`/yeu-cau/ket-qua/${result.id}` as Route);
}
