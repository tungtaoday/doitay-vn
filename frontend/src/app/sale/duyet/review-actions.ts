'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

export type ReviewResult = { ok: true } | { ok: false; error: string };

/** Quản lý duyệt hồ sơ hợp lệ. Ref: DUC-SUBMISSION-APPROVE. */
export async function approveAction(id: number): Promise<ReviewResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Vui lòng đăng nhập' };
  try {
    await api(`/admin/submissions/${id}/approve`, { method: 'PATCH', token });
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      const body = e.body as { message?: string };
      return { ok: false, error: body.message ?? 'Không thể duyệt hồ sơ.' };
    }
    return { ok: false, error: 'Không thể duyệt hồ sơ.' };
  }
}

/** P1.3 — Duyệt thợ TỰ ĐĂNG KÝ (company PENDING) ngay trên màn này. */
export async function approveCompanyAction(id: number): Promise<ReviewResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Vui lòng đăng nhập' };
  try {
    await api(`/admin/companies/${id}/approve`, { method: 'PATCH', token });
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      const body = e.body as { message?: string };
      return { ok: false, error: body.message ?? 'Không thể duyệt hồ sơ thợ.' };
    }
    return { ok: false, error: 'Không thể duyệt hồ sơ thợ.' };
  }
}

/** Quản lý từ chối kèm lý do. Ref: DUC-SUBMISSION-REJECT. */
export async function rejectAction(id: number, lyDo: string): Promise<ReviewResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Vui lòng đăng nhập' };
  try {
    await api(`/admin/submissions/${id}/reject`, { method: 'PATCH', token, json: { ly_do: lyDo } });
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      const body = e.body as { message?: string };
      return { ok: false, error: body.message ?? 'Không thể từ chối hồ sơ.' };
    }
    return { ok: false, error: 'Không thể từ chối hồ sơ.' };
  }
}
