'use server';

import { revalidatePath } from 'next/cache';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

export type KhoaResult = { ok: true; message: string } | { ok: false; error: string };

/** Khoá (status=0) hoặc mở khoá (status=1) một tài khoản. */
export async function doiTrangThaiUser(
  id: number,
  status: 0 | 1,
  lyDo?: string,
): Promise<KhoaResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Vui lòng đăng nhập lại.' };

  try {
    const res = await api<{ data: { message: string } }>(`/admin/ops/users/${id}/status`, {
      method: 'POST',
      token,
      json: { status, ban_reason: status === 0 ? lyDo?.trim() || null : null },
    });
    revalidatePath('/quan-tri/nguoi-dung');
    return { ok: true, message: res.data.message };
  } catch (e) {
    if (e instanceof ApiError) {
      const body = e.body as { message?: string };
      return { ok: false, error: body.message ?? `Lỗi ${e.status}` };
    }
    return { ok: false, error: 'Không đổi được trạng thái.' };
  }
}
