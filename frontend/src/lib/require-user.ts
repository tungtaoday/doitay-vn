import { redirect } from 'next/navigation';
import type { Route } from 'next';
import { api, ApiError } from './api';
import { clearToken, getToken } from './auth';
import type { AuthUser } from './api-types';

/**
 * Authentication guard for RSC pages. Centralizes the "what happens when
 * the token is missing / invalid / user banned / profile incomplete" logic
 * so individual pages don't each reimplement it.
 *
 * Options:
 *   - requireProfile: if true (default), redirect to /vi/hoan-thanh-ho-so
 *     when profile_complete is false.
 *   - loginPath: nơi điều hướng khi chưa/không đăng nhập. Mặc định '/login'
 *     (luồng thợ/khách). Khu vực Sale truyền '/sale/login' để tách luồng.
 */
export async function requireUser(
  options: { requireProfile?: boolean; loginPath?: Route } = {},
): Promise<AuthUser> {
  const { requireProfile = true, loginPath = '/login' as Route } = options;

  const token = await getToken();
  if (!token) redirect(loginPath);

  let user: AuthUser;
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    user = res.data;
  } catch (e) {
    if (e instanceof ApiError && (e.status === 401 || e.status === 403)) {
      await clearToken();
      redirect(loginPath);
    }
    throw e;
  }

  if (user.status !== 1) {
    await clearToken();
    redirect(loginPath);
  }

  if (requireProfile && !user.profile_complete) {
    redirect('/vi/hoan-thanh-ho-so');
  }

  return user;
}
