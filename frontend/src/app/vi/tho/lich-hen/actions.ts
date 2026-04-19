'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import { revalidatePath } from 'next/cache';

type Result = { ok: true } | { ok: false; error: string };

async function thoAction(id: number, action: string): Promise<Result> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Chưa đăng nhập.' };

  try {
    await api(`/user/tho/appointments/${id}/${action}`, { method: 'POST', token });
    revalidatePath('/vi/tho/lich-hen');
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      const msg = (e.body as { message?: string })?.message ?? 'Có lỗi xảy ra.';
      return { ok: false, error: msg };
    }
    throw e;
  }
}

export async function confirmAppointment(id: number): Promise<Result> {
  return thoAction(id, 'confirm');
}

export async function completeAppointment(id: number): Promise<Result> {
  return thoAction(id, 'complete');
}

export async function cancelThoAppointment(id: number): Promise<Result> {
  return thoAction(id, 'cancel');
}
