'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

export type PayResult =
  | { ok: true; soKhoan: number; tongTien: number }
  | { ok: false; error: string };

/** Đối soát: đánh dấu đã trả toàn bộ hoa hồng đang nợ của 1 CTV. */
export async function markPaidAction(ctvId: number): Promise<PayResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Vui lòng đăng nhập' };
  try {
    const res = await api<{ data: { so_khoan: number; tong_tien: number } }>(
      '/admin/commissions/mark-paid',
      { method: 'PATCH', token, json: { ctv_id: ctvId } },
    );
    return { ok: true, soKhoan: res.data.so_khoan, tongTien: res.data.tong_tien };
  } catch (e) {
    if (e instanceof ApiError) {
      const body = e.body as { message?: string };
      return { ok: false, error: body.message ?? 'Không thể đánh dấu đã trả.' };
    }
    return { ok: false, error: 'Không thể đánh dấu đã trả.' };
  }
}
