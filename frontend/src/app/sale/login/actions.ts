'use server';

import { api, ApiError } from '@/lib/api';
import { setToken } from '@/lib/auth';
import type { LoginResponse } from '@/lib/api-types';

export type SaleLoginResult = { ok: true } | { ok: false; error: string };

/**
 * Đăng nhập cho vai trò Sale/CTV. Dùng CHUNG backend /auth/login (tài khoản do
 * admin tạo), nhưng TÁCH khỏi luồng thợ/khách: không điều hướng theo hồ sơ thợ —
 * form sẽ đưa thẳng về /sale.
 */
export async function saleLoginAction(
  _prev: SaleLoginResult | null,
  formData: FormData,
): Promise<SaleLoginResult> {
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
    return { ok: true };
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
