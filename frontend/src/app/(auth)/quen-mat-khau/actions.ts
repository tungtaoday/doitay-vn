'use server';

import { api, ApiError } from '@/lib/api';
import { redirect } from 'next/navigation';
import type { Route } from 'next';

export type ForgotResult = { ok: true; identifier: string } | { ok: false; error: string };

export async function sendResetCodeAction(
  _prev: ForgotResult | null,
  formData: FormData,
): Promise<ForgotResult> {
  const identifier = String(formData.get('identifier') ?? '').trim();

  if (!identifier) {
    return { ok: false, error: 'Vui lòng nhập email hoặc số điện thoại' };
  }

  try {
    await api<{ message: string }>('/auth/password/forgot', {
      method: 'POST',
      json: { identifier },
    });
    redirect(`/dat-lai-mat-khau?identifier=${encodeURIComponent(identifier)}` as Route);
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 422) return { ok: false, error: 'Email/SĐT không hợp lệ' };
      if (e.status === 429) return { ok: false, error: 'Bạn thử quá nhiều lần, đợi 1 phút' };
    }
    throw e;
  }
}

export async function resetPassword(
  identifier: string,
  code: string,
  password: string,
  passwordConfirmation: string,
): Promise<{ ok: true } | { ok: false; error: string }> {
  if (password !== passwordConfirmation) {
    return { ok: false, error: 'Mật khẩu xác nhận không khớp' };
  }
  try {
    await api<{ message: string }>('/auth/password/reset', {
      method: 'POST',
      json: {
        identifier,
        code,
        password,
        password_confirmation: passwordConfirmation,
      },
    });
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 422) {
        const body = e.body as { errors?: Record<string, string[]>; message?: string };
        const first = body.errors ? Object.values(body.errors)[0]?.[0] : body.message;
        return { ok: false, error: first ?? 'Mã xác nhận sai hoặc đã hết hạn' };
      }
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}
