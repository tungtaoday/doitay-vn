'use server';

import { redirect } from 'next/navigation';
import { api } from '@/lib/api';
import { clearToken, getToken } from '@/lib/auth';

export async function logoutAction(): Promise<void> {
  const token = await getToken();
  if (token) {
    try {
      await api('/auth/logout', { method: 'POST', token });
    } catch {
      // Best-effort — clear cookie regardless
    }
  }
  await clearToken();
  redirect('/');
}
