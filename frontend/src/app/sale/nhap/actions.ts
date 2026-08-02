'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

export type CreateSubmissionResult =
  | { ok: true; id: number; claimLink?: string | null }
  | { ok: false; error: string; fieldErrors?: Record<string, string>; needsLogin?: boolean };

export type ThoLookup = {
  ton_tai: boolean;
  da_mo: boolean;
  ten_tho: string | null;
  nghe: string | null;
  khu_vuc: string | null;
  da_co_ctv: boolean;
  company_id: number | null;
};

/**
 * Tra SĐT trước khi nộp: thợ đã tự mở hồ sơ trong Mini App chưa, đã có CTV nhận
 * công chưa. Nhờ đó CTV chọn đúng kiểu nộp và khỏi gõ lại thông tin đã có.
 */
export async function lookupThoAction(sdt: string): Promise<ThoLookup | null> {
  const token = await getToken();
  if (!token || !sdt.trim()) return null;

  try {
    const res = await api<{ data: ThoLookup }>(
      `/sale/tho-lookup?sdt=${encodeURIComponent(sdt.trim())}`,
      { token },
    );
    return res.data;
  } catch {
    return null;
  }
}

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
    const res = await api<{ data: { id: number; claim_link?: string | null } }>('/sale/submissions', {
      method: 'POST',
      token,
      body: formData,
    });
    return { ok: true, id: res.data.id, claimLink: res.data.claim_link ?? null };
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
