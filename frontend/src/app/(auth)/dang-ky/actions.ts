'use server';

import { api, ApiError } from '@/lib/api';
import { setToken } from '@/lib/auth';
import type { RegisterResponse } from '@/lib/api-types';

export type RegisterResult =
  | { ok: true; profileComplete: boolean }
  | { ok: false; error: string };

export async function registerAction(
  _prev: RegisterResult | null,
  formData: FormData,
): Promise<RegisterResult> {
  const firstname = String(formData.get('firstname') ?? '').trim();
  const lastname = String(formData.get('lastname') ?? '').trim();
  const identifier = String(formData.get('identifier') ?? '').trim();
  const password = String(formData.get('password') ?? '');
  const passwordConfirmation = String(formData.get('password_confirmation') ?? '');
  const userRoleRaw = String(formData.get('user_role') ?? 'customer');
  const userRole: 'customer' | 'contractor' | 'both' =
    userRoleRaw === 'contractor' || userRoleRaw === 'both' ? userRoleRaw : 'customer';

  if (!firstname || !lastname || !identifier || !password) {
    return { ok: false, error: 'Vui lòng nhập đầy đủ thông tin' };
  }

  if (password !== passwordConfirmation) {
    return { ok: false, error: 'Mật khẩu xác nhận không khớp' };
  }

  try {
    const res = await api<RegisterResponse>('/auth/register', {
      method: 'POST',
      json: {
        firstname,
        lastname,
        name: `${firstname} ${lastname}`,
        identifier,
        password,
        password_confirmation: passwordConfirmation,
        user_role: userRole,
      },
    });
    await setToken(res.token);
    return { ok: true, profileComplete: res.user.profile_complete };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 422) {
        const body = e.body as { errors?: Record<string, string[]>; message?: string };
        const first = body.errors ? Object.values(body.errors)[0]?.[0] : body.message;
        return { ok: false, error: first ?? 'Dữ liệu không hợp lệ' };
      }
      if (e.status === 429) return { ok: false, error: 'Bạn thử quá nhiều lần, đợi 1 phút' };
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}
