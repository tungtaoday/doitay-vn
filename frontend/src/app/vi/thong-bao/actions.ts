'use server';

import { revalidatePath } from 'next/cache';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { NotificationListResponse } from '@/lib/api-types';

export type NotificationMutationResult =
  | { ok: true; unreadCount: number }
  | { ok: false; error: string };

async function withToken<T>(fn: (token: string) => Promise<T>): Promise<T | null> {
  const token = await getToken();
  if (!token) return null;
  return fn(token);
}

export async function markNotificationReadAction(
  id: number,
): Promise<NotificationMutationResult> {
  const result = await withToken(async (token) => {
    try {
      const res = await api<{ unread_count: number }>(
        `/user/notifications/${id}/read`,
        { method: 'POST', token },
      );
      return { ok: true as const, unreadCount: res.unread_count };
    } catch (e) {
      if (e instanceof ApiError && e.status === 404) {
        return { ok: false as const, error: 'Thông báo không tồn tại' };
      }
      return { ok: false as const, error: 'Không thể đánh dấu đã đọc' };
    }
  });
  if (!result) return { ok: false, error: 'Chưa đăng nhập' };
  if (result.ok) {
    revalidatePath('/vi/thong-bao');
  }
  return result;
}

export async function markAllReadAction(): Promise<NotificationMutationResult> {
  const result = await withToken(async (token) => {
    try {
      await api('/user/notifications/read-all', { method: 'POST', token });
      return { ok: true as const, unreadCount: 0 };
    } catch {
      return { ok: false as const, error: 'Không thể đánh dấu tất cả' };
    }
  });
  if (!result) return { ok: false, error: 'Chưa đăng nhập' };
  if (result.ok) {
    revalidatePath('/vi/thong-bao');
  }
  return result;
}

export async function deleteNotificationAction(
  id: number,
): Promise<NotificationMutationResult> {
  const result = await withToken(async (token) => {
    try {
      const res = await api<{ unread_count: number }>(
        `/user/notifications/${id}`,
        { method: 'DELETE', token },
      );
      return { ok: true as const, unreadCount: res.unread_count };
    } catch {
      return { ok: false as const, error: 'Không thể xoá' };
    }
  });
  if (!result) return { ok: false, error: 'Chưa đăng nhập' };
  if (result.ok) {
    revalidatePath('/vi/thong-bao');
  }
  return result;
}

/**
 * Client-callable server action for polling unread count from the bell.
 * Keeps the token cookie on the server side.
 */
export async function fetchUnreadCountAction(): Promise<number> {
  const token = await getToken();
  if (!token) return 0;
  try {
    const res = await api<{ count: number }>('/user/notifications/unread-count', {
      token,
    });
    return res.count;
  } catch {
    return 0;
  }
}

export async function fetchRecentNotificationsAction(
  limit = 5,
): Promise<NotificationListResponse | null> {
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
