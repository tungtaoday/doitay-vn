'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

export type CreateSubmissionResult =
  | { ok: true; id: number }
  | { ok: false; error: string; fieldErrors?: Record<string, string>; needsLogin?: boolean };

/**
 * Nhận FormData (đã kèm images[]) từ client, chuyển tiếp lên API sale.
 * Ref: DUC-SUBMISSION-CREATE.
 */
export async function createSubmissionAction(formData: FormData): Promise<CreateSubmissionResult> {
  const token = await getToken();
  if (!token) {
    return { ok: false, error: 'Vui lòng đăng nhập', needsLogin: true };
  }

  try {
    const res = await api<{ data: { id: number } }>('/sale/submissions', {
      method: 'POST',
      token,
      body: formData,
    });
    return { ok: true, id: res.data.id };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 401) {
        return { ok: false, error: 'Phiên đăng nhập đã hết hạn', needsLogin: true };
      }
      if (e.status === 422) {
        const body = e.body as { message?: string; errors?: Record<string, string[]> };
        const fieldErrors: Record<string, string> = {};
        for (const [k, v] of Object.entries(body.errors ?? {})) {
          if (Array.isArray(v) && v.length) fieldErrors[k] = v[0] as string;
        }
        return { ok: false, error: body.message ?? 'Dữ liệu không hợp lệ', fieldErrors };
      }
    }
    return { ok: false, error: 'Không thể gửi hồ sơ. Vui lòng thử lại.' };
  }
}
