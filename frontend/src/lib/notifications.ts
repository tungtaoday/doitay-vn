import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { NotificationListResponse, UserNotification } from '@/lib/api-types';

/**
 * Server-side helpers for /api/v1/user/notifications.
 * Client-side mutations should go through server actions so the httpOnly
 * token cookie stays on the server.
 */

async function authed() {
  const token = await getToken();
  if (!token) throw new Error('Unauthenticated');
  return token;
}

export interface ListParams {
  page?: number;
  perPage?: number;
  type?: string;
  unreadOnly?: boolean;
}

export async function getMyNotifications(params: ListParams = {}) {
  const token = await authed();
  const qs = new URLSearchParams();
  if (params.page) qs.set('page', String(params.page));
  if (params.perPage) qs.set('per_page', String(params.perPage));
  if (params.type) qs.set('type', params.type);
  if (params.unreadOnly) qs.set('unread_only', '1');
  const suffix = qs.toString() ? `?${qs}` : '';
  return api<NotificationListResponse>(`/user/notifications${suffix}`, { token });
}

export async function getUnreadCount(): Promise<number> {
  const token = await getToken();
  if (!token) return 0;
  try {
    const res = await api<{ count: number }>('/user/notifications/unread-count', { token });
    return res.count;
  } catch {
    return 0;
  }
}

export async function getRecentNotifications(limit = 5) {
  const token = await getToken();
  if (!token) return null;
  try {
    return await api<NotificationListResponse>(
      `/user/notifications?per_page=${limit}`,
      { token },
    );
  } catch {
    return null;
  }
}

export type { UserNotification };
