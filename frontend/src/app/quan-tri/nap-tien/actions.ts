'use server';

import { revalidatePath } from 'next/cache';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

export type XuLyResult = { ok: true; message: string } | { ok: false; error: string };

/**
 * Duyệt / từ chối / chuyển đang xử lý một lệnh nạp tiền.
 *
 * Backend gọi thẳng DepositRequest::approve()/reject() — nơi đã có transaction
 * cộng ví và ghi sổ. Ở đây không tính toán tiền bạc gì thêm.
 */
export async function xuLyLenhNap(
  id: number,
  action: 'approve' | 'reject' | 'processing',
  ghiChu?: string,
  lyDoTuChoi?: string,
): Promise<XuLyResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Vui lòng đăng nhập lại.' };

  if (action === 'reject' && !lyDoTuChoi?.trim()) {
    return { ok: false, error: 'Phải ghi lý do từ chối để thợ biết vì sao.' };
  }

  try {
    const res = await api<{ data: { message: string } }>(`/admin/ops/deposits/${id}/process`, {
      method: 'POST',
      token,
      json: {
        action,
        admin_notes: ghiChu?.trim() || null,
        rejection_reason: action === 'reject' ? lyDoTuChoi?.trim() : null,
      },
    });
    revalidatePath('/quan-tri/nap-tien');
    revalidatePath('/quan-tri/vi-tho');
    return { ok: true, message: res.data.message };
  } catch (e) {
    if (e instanceof ApiError) {
      const body = e.body as { message?: string };
      return { ok: false, error: body.message ?? `Lỗi ${e.status}` };
    }
    return { ok: false, error: 'Không xử lý được lệnh nạp.' };
  }
}
