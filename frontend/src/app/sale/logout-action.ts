'use server';

import { redirect } from 'next/navigation';
import { api } from '@/lib/api';
import { clearToken, getToken } from '@/lib/auth';

/** Đăng xuất khỏi cổng Sale → về TRANG ĐĂNG NHẬP SALE (không về trang chủ doitay). */
export async function saleLogoutAction(): Promise<void> {
  const token = await getToken();
  if (token) {
    try {
      await api('/auth/logout', { method: 'POST', token });
    } catch {
      // best-effort
    }
  }
  await clearToken();
  redirect('/sale/login');
}
