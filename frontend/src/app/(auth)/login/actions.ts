'use server';

import { api, ApiError } from '@/lib/api';
import { setToken } from '@/lib/auth';
import type { LoginResponse } from '@/lib/api-types';

export type LoginResult =
  | { ok: true; profileComplete: boolean; hasCompany: boolean }
  | { ok: false; error: string };

export async function loginAction(
  _prev: LoginResult | null,
  formData: FormData,
): Promise<LoginResult> {
  const identifier = String(formData.get('identifier') ?? '').trim();
  const password = String(formData.get('password') ?? '');

  if (!identifier || !password) {
    return { ok: false, error: 'Vui lòng nhập đầy đủ thông tin' };
  }

  try {
    const res = await api<LoginResponse>('/auth/login', {
      method: 'POST',
      json: { identifier, password },
    });
    await setToken(res.token);
    const user = res.user;
    return { ok: true, profileComplete: user.profile_complete, hasCompany: user.has_company };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 401) return { ok: false, error: 'Email/SĐT hoặc mật khẩu không đúng' };
      if (e.status === 403) return { ok: false, error: 'Tài khoản chưa được kích hoạt' };
      if (e.status === 422) return { ok: false, error: 'Dữ liệu không hợp lệ' };
      if (e.status === 429) return { ok: false, error: 'Bạn thử quá nhiều lần, đợi 1 phút' };
    }
    return { ok: false, error: 'Lỗi kết nối server' };
  }
}

export async function loginWithGoogle(idToken: string): Promise<LoginResult> {
  try {
    const res = await api<LoginResponse>('/auth/social/google', {
      method: 'POST',
      json: { id_token: idToken },
    });
    await setToken(res.token);
    const user = res.user;
    return { ok: true, profileComplete: user.profile_complete, hasCompany: user.has_company };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 401) return { ok: false, error: 'Token Google không hợp lệ' };
      if (e.status === 403) return { ok: false, error: 'Tài khoản chưa được kích hoạt' };
    }
    return { ok: false, error: 'Lỗi đăng nhập Google' };
  }
}

export async function loginWithFacebook(accessToken: string): Promise<LoginResult> {
  try {
    const res = await api<LoginResponse>('/auth/social/facebook', {
      method: 'POST',
      json: { access_token: accessToken },
    });
    await setToken(res.token);
    const user = res.user;
    return { ok: true, profileComplete: user.profile_complete, hasCompany: user.has_company };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 401) return { ok: false, error: 'Token Facebook không hợp lệ' };
      if (e.status === 403) return { ok: false, error: 'Tài khoản chưa được kích hoạt' };
    }
    return { ok: false, error: 'Lỗi đăng nhập Facebook' };
  }
}
