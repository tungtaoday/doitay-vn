'use server';

import { api, ApiError } from '@/lib/api';

export type ResetResult = { ok: true } | { ok: false; error: string };

export async function resetPasswordAction(
  _prev: ResetResult | null,
  formData: FormData,
): Promise<ResetResult> {
  const identifier = String(formData.get('identifier') ?? '').trim();
  const code = String(formData.get('code') ?? '').trim();
  const password = String(formData.get('password') ?? '');
  const passwordConfirmation = String(formData.get('password_confirmation') ?? '');

  if (!identifier || !code || !password) {
    return { ok: false, error: 'Vui lòng nhập đầy đủ thông tin' };
  }

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
