import { cookies } from 'next/headers';

const COOKIE_NAME = 'doitay_token';

/** Read the auth token from the httpOnly cookie (server-side only). */
export async function getToken(): Promise<string | undefined> {
  return (await cookies()).get(COOKIE_NAME)?.value;
}

/** Set the auth token cookie. Call from server actions only. */
export async function setToken(token: string): Promise<void> {
  (await cookies()).set(COOKIE_NAME, token, {
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    path: '/',
    // 30 days; tune later when refresh strategy is decided.
    maxAge: 60 * 60 * 24 * 30,
  });
}

/** Remove the auth token cookie. */
export async function clearToken(): Promise<void> {
  (await cookies()).delete(COOKIE_NAME);
}
