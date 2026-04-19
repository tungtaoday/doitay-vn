'use client';

import Link from 'next/link';
import { useState, useTransition } from 'react';
import type { PaginationMeta, UserNotification } from '@/lib/api-types';
import {
  deleteNotificationAction,
  markAllReadAction,
  markNotificationReadAction,
} from './actions';

interface Props {
  initialData: UserNotification[];
  unreadCount: number;
  meta: PaginationMeta;
  page: number;
  type?: string;
  unreadOnly: boolean;
}

export function NotificationsList({
  initialData,
  unreadCount: initialUnread,
  meta,
  page,
  type,
  unreadOnly,
}: Props) {
  const [items, setItems] = useState(initialData);
  const [unreadCount, setUnreadCount] = useState(initialUnread);
  const [isPending, startTransition] = useTransition();
  const [error, setError] = useState<string | null>(null);

  const onMarkRead = (id: number) => {
    startTransition(async () => {
      const res = await markNotificationReadAction(id);
      if (res.ok) {
        setItems((prev) =>
          prev.map((x) => (x.id === id ? { ...x, is_read: true } : x)),
        );
        setUnreadCount(res.unreadCount);
      } else {
        setError(res.error);
      }
    });
  };

  const onDelete = (id: number) => {
    startTransition(async () => {
      const res = await deleteNotificationAction(id);
      if (res.ok) {
        setItems((prev) => prev.filter((x) => x.id !== id));
        setUnreadCount(res.unreadCount);
      } else {
        setError(res.error);
      }
    });
  };

  const onMarkAll = () => {
    startTransition(async () => {
      const res = await markAllReadAction();
      if (res.ok) {
        setItems((prev) => prev.map((x) => ({ ...x, is_read: true })));
        setUnreadCount(0);
      } else {
        setError(res.error);
      }
    });
  };

  const buildHref = (p: number) => {
    const qs = new URLSearchParams();
    qs.set('page', String(p));
    if (type) qs.set('type', type);
    if (unreadOnly) qs.set('unread', '1');
    return `/vi/thong-bao?${qs}` as never;
  };

  if (items.length === 0) {
    return (
      <div className="rounded-3xl bg-surface-container-lowest p-12 text-center">
        <span className="material-symbols-outlined mb-3 block text-5xl text-on-surface-variant/40">
          notifications_off
        </span>
        <p className="text-sm text-on-surface-variant">
          {unreadOnly ? 'Không có thông báo chưa đọc' : 'Chưa có thông báo nào'}
        </p>
      </div>
    );
  }

  return (
    <div className="space-y-3">
      {error && (
        <div className="rounded-xl bg-error-container/60 px-4 py-2 text-sm text-on-error-container">
          {error}
        </div>
      )}

      {unreadCount > 0 && (
        <div className="flex justify-end">
          <button
            type="button"
            onClick={onMarkAll}
            disabled={isPending}
            className="text-xs font-semibold text-primary hover:underline disabled:opacity-60"
          >
            Đánh dấu tất cả đã đọc
          </button>
        </div>
      )}

      <ul className="space-y-2">
        {items.map((n) => (
          <li
            key={n.id}
            className={`flex gap-4 rounded-2xl p-4 transition-colors ${
              n.is_read
                ? 'bg-surface-container-lowest'
                : 'bg-primary-container/15'
            }`}
          >
            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-surface-container-low text-xl">
              {n.icon ?? '🔔'}
            </div>
            <div className="min-w-0 flex-1">
              <div className="flex items-start justify-between gap-2">
                <h3 className="font-semibold text-on-surface">{n.title}</h3>
                {!n.is_read && (
                  <span className="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-primary" />
                )}
              </div>
              <p className="mt-1 text-sm text-on-surface-variant">{n.message}</p>
              <div className="mt-3 flex items-center gap-4 text-xs">
                {n.created_at && (
                  <span className="text-on-surface-variant/70">
                    {new Date(n.created_at).toLocaleString('vi-VN')}
                  </span>
                )}
                {n.action_url && (
                  <Link
                    href={n.action_url as never}
                    onClick={() => !n.is_read && onMarkRead(n.id)}
                    className="font-semibold text-primary hover:underline"
                  >
                    Xem chi tiết →
                  </Link>
                )}
                {!n.is_read && (
                  <button
                    type="button"
                    onClick={() => onMarkRead(n.id)}
                    disabled={isPending}
                    className="font-semibold text-on-surface-variant hover:text-primary disabled:opacity-60"
                  >
                    Đánh dấu đã đọc
                  </button>
                )}
                <button
                  type="button"
                  onClick={() => onDelete(n.id)}
                  disabled={isPending}
                  className="ml-auto font-semibold text-error hover:underline disabled:opacity-60"
                >
                  Xoá
                </button>
              </div>
            </div>
          </li>
        ))}
      </ul>

      {meta.last_page > 1 && (
        <nav className="mt-6 flex items-center justify-center gap-2">
          {page > 1 && (
            <Link
              href={buildHref(page - 1)}
              className="rounded-xl bg-surface-container-low px-4 py-2 text-sm font-semibold text-on-surface hover:bg-surface-container"
            >
              ← Trước
            </Link>
          )}
          <span className="px-4 text-sm text-on-surface-variant">
            Trang {page} / {meta.last_page}
          </span>
          {page < meta.last_page && (
            <Link
              href={buildHref(page + 1)}
              className="rounded-xl bg-surface-container-low px-4 py-2 text-sm font-semibold text-on-surface hover:bg-surface-container"
            >
              Sau →
            </Link>
          )}
        </nav>
      )}
    </div>
  );
}
