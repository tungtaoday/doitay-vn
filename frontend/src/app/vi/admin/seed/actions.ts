'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { AuthUser, SeedStats } from '@/lib/api-types';

async function requireAdmin(): Promise<string> {
  const token = await getToken();
  if (!token) throw new Error('Unauthenticated');
  const res = await api<{ data: AuthUser }>('/auth/me', { token });
  if (res.data.role !== 'admin') throw new Error('Forbidden');
  return token;
}

export type RunResult =
  | { ok: true; message: string; log?: string }
  | { ok: false; error: string };

export async function runSeedAction(
  _prev: RunResult | null,
  formData: FormData,
): Promise<RunResult> {
  try {
    const token = await requireAdmin();
    const contractors  = Number(formData.get('contractors')  ?? 3);
    const customers    = Number(formData.get('customers')    ?? 5);
    const appointments = Number(formData.get('appointments') ?? 8);

    const res = await api<{ ok: boolean; message: string; log?: string }>(
      '/admin/seed/run',
      {
        method: 'POST',
        token,
        json: { contractors, customers, appointments },
      },
    );
    return { ok: true, message: res.message, log: res.log };
  } catch (e) {
    if (e instanceof ApiError) return { ok: false, error: `Lỗi ${e.status}: ${JSON.stringify(e.body)}` };
    if (e instanceof Error)    return { ok: false, error: e.message };
    return { ok: false, error: 'Lỗi không xác định' };
  }
}

export async function loadSeedStats(): Promise<SeedStats | null> {
  try {
    const token = await requireAdmin();
    const res = await api<{ data: SeedStats }>('/admin/seed/stats', { token });
    return res.data;
  } catch {
    return null;
  }
}
