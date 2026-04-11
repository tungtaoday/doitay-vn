'use server';

import { api, ApiError } from '@/lib/api';
import { setToken } from '@/lib/auth';
import type { LoginResponse } from '@/lib/api-types';

export type LoginResult = { ok: true } | { ok: false; error: string };

export async function login(email: string, password: string): Promise<LoginResult> {
  try {
    const res = await api<LoginResponse>('/auth/login', {
      method: 'POST',
      json: { email, password },
    });
    await setToken(res.token);
    return { ok: true };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 401) return { ok: false, error: 'Email hoặc mật khẩu không đúng' };
      if (e.status === 403) return { ok: false, error: 'Tài khoản chưa được kích hoạt' };
      if (e.status === 422) return { ok: false, error: 'Dữ liệu không hợp lệ' };
      if (e.status === 429) return { ok: false, error: 'Bạn thử quá nhiều lần, đợi 1 phút' };
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}
