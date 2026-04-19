'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import { revalidatePath } from 'next/cache';

export async function cancelAppointment(id: number): Promise<{ ok: true } | { ok: false; error: string }> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Chưa đăng nhập.' };

  try {
    await api(`/user/appointments/${id}/cancel`, { method: 'POST', token });
    revalidatePath('/vi/lich-hen');
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      const msg = (e.body as { message?: string })?.message ?? 'Có lỗi xảy ra.';
      return { ok: false, error: msg };
    }
    throw e;
  }
}

export async function submitReview(
  appointmentId: number,
  ratings: Record<number, number>,
  comment: string,
): Promise<{ ok: true } | { ok: false; error: string }> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Chưa đăng nhập.' };

  try {
    await api(`/user/appointments/${appointmentId}/review`, {
      method: 'POST',
      token,
      json: { ratings, comment },
    });
    revalidatePath(`/vi/lich-hen/${appointmentId}`);
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      const msg = (e.body as { message?: string })?.message ?? 'Có lỗi xảy ra.';
      return { ok: false, error: msg };
    }
    throw e;
  }
}
