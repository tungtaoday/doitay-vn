import { redirect } from 'next/navigation';
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
 */
export async function requireUser(
  options: { requireProfile?: boolean } = {},
): Promise<AuthUser> {
  const { requireProfile = true } = options;

  const token = await getToken();
  if (!token) redirect('/login');

  let user: AuthUser;
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    user = res.data;
  } catch (e) {
    if (e instanceof ApiError && (e.status === 401 || e.status === 403)) {
      await clearToken();
      redirect('/login');
    }
    throw e;
  }

  if (user.status !== 1) {
    await clearToken();
    redirect('/login');
  }

  if (requireProfile && !user.profile_complete) {
    redirect('/vi/hoan-thanh-ho-so');
  }

  return user;
}
