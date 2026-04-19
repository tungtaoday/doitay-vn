'use client';

import Link from 'next/link';
import { useCallback, useEffect, useRef, useState, useTransition } from 'react';
import type { NotificationListResponse, UserNotification } from '@/lib/api-types';
import {
  fetchRecentNotificationsAction,
  fetchUnreadCountAction,
  markAllReadAction,
  markNotificationReadAction,
} from '@/app/vi/thong-bao/actions';

const POLL_INTERVAL_MS = 30_000;

interface Props {
  initialUnreadCount: number;
  initialRecent: NotificationListResponse | null;
}

export function NotificationBell({ initialUnreadCount, initialRecent }: Props) {
  const [unreadCount, setUnreadCount] = useState(initialUnreadCount);
  const [recent, setRecent] = useState<UserNotification[]>(
    initialRecent?.data ?? [],
  );
  const [open, setOpen] = useState(false);
  const [, startTransition] = useTransition();
  const panelRef = useRef<HTMLDivElement>(null);

  const refresh = useCallback(async () => {
    const count = await fetchUnreadCountAction();
    setUnreadCount(count);
  }, []);

  const refreshList = useCallback(async () => {
    const data = await fetchRecentNotificationsAction(5);
    if (data) {
      setRecent(data.data);
      setUnreadCount(data.unread_count);
    }
  }, []);

  // Poll for badge count
  useEffect(() => {
    const iv = setInterval(refresh, POLL_INTERVAL_MS);
    const onFocus = () => refresh();
    window.addEventListener('focus', onFocus);
    return () => {
      clearInterval(iv);
      window.removeEventListener('focus', onFocus);
    };
  }, [refresh]);

  // Close on outside click
  useEffect(() => {
    if (!open) return;
    function onClick(e: MouseEvent) {
      if (panelRef.current && !panelRef.current.contains(e.target as Node)) {
        setOpen(false);
      }
    }
    document.addEventListener('mousedown', onClick);
    return () => document.removeEventListener('mousedown', onClick);
  }, [open]);

  const handleOpen = () => {
    const next = !open;
    setOpen(next);
    if (next) refreshList();
  };

  const handleItemClick = (n: UserNotification) => {
    if (!n.is_read) {
      startTransition(async () => {
        const res = await markNotificationReadAction(n.id);
        if (res.ok) {
          setUnreadCount(res.unreadCount);
          setRecent((prev) =>
            prev.map((x) => (x.id === n.id ? { ...x, is_read: true } : x)),
          );
        }
      });
    }
  };

  const handleMarkAll = () => {
    startTransition(async () => {
      const res = await markAllReadAction();
      if (res.ok) {
        setUnreadCount(0);
        setRecent((prev) => prev.map((x) => ({ ...x, is_read: true })));
      }
    });
  };

  return (
    <div ref={panelRef} className="relative">
      <button
        type="button"
        onClick={handleOpen}
        aria-label={`Thông báo${unreadCount > 0 ? ` (${unreadCount} chưa đọc)` : ''}`}
        className="relative flex h-10 w-10 items-center justify-center rounded-xl text-on-surface-variant transition-colors hover:bg-surface-container-low hover:text-primary"
      >
        <span className="material-symbols-outlined">notifications</span>
        {unreadCount > 0 && (
          <span className="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-error px-1 text-[10px] font-bold leading-none text-on-error">
            {unreadCount > 99 ? '99+' : unreadCount}
          </span>
        )}
      </button>

      {open && (
        <div className="absolute right-0 top-12 z-50 w-[340px] overflow-hidden rounded-2xl border border-outline-variant/30 bg-surface-container-lowest shadow-ambient">
          <div className="flex items-center justify-between border-b border-outline-variant/30 px-4 py-3">
            <h3 className="font-headline text-sm font-bold text-on-surface">
              Thông báo
            </h3>
            {unreadCount > 0 && (
              <button
                type="button"
                onClick={handleMarkAll}
                className="text-xs font-semibold text-primary hover:underline"
              >
                Đánh dấu tất cả
              </button>
            )}
          </div>

          <div className="max-h-[400px] overflow-y-auto">
            {recent.length === 0 ? (
              <div className="px-4 py-10 text-center text-sm text-on-surface-variant">
                <span className="material-symbols-outlined mb-2 block text-3xl opacity-40">
                  notifications_off
                </span>
                Chưa có thông báo nào
              </div>
            ) : (
              recent.map((n) => (
                <NotificationRow
                  key={n.id}
                  notification={n}
                  onClick={() => handleItemClick(n)}
                />
              ))
            )}
          </div>

          <Link
            href="/vi/thong-bao"
            onClick={() => setOpen(false)}
            className="block border-t border-outline-variant/30 px-4 py-3 text-center text-sm font-semibold text-primary hover:bg-surface-container-low"
          >
            Xem tất cả thông báo →
          </Link>
        </div>
      )}
    </div>
  );
}

function NotificationRow({
  notification: n,
  onClick,
}: {
  notification: UserNotification;
  onClick: () => void;
}) {
  const body = (
    <div
      onClick={onClick}
      className={`flex gap-3 px-4 py-3 transition-colors hover:bg-surface-container-low ${
        n.is_read ? '' : 'bg-primary-container/10'
      }`}
    >
      <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-surface-container-low text-lg">
        {n.icon ?? '🔔'}
      </div>
      <div className="min-w-0 flex-1">
        <p className="truncate text-sm font-semibold text-on-surface">{n.title}</p>
        <p className="line-clamp-2 text-xs text-on-surface-variant">{n.message}</p>
        {n.created_at && (
          <p className="mt-1 text-[10px] text-on-surface-variant/70">
            {formatRelative(n.created_at)}
          </p>
        )}
      </div>
      {!n.is_read && (
        <div className="mt-2 h-2 w-2 shrink-0 rounded-full bg-primary" aria-hidden />
      )}
    </div>
  );

  if (n.action_url) {
    return (
      <Link href={n.action_url as never} className="block">
        {body}
      </Link>
    );
  }
  return body;
}

function formatRelative(iso: string): string {
  const now = Date.now();
  const then = new Date(iso).getTime();
  const diff = Math.max(0, now - then);
  const mins = Math.floor(diff / 60_000);
  if (mins < 1) return 'vừa xong';
  if (mins < 60) return `${mins} phút trước`;
  const hrs = Math.floor(mins / 60);
  if (hrs < 24) return `${hrs} giờ trước`;
  const days = Math.floor(hrs / 24);
  if (days < 7) return `${days} ngày trước`;
  return new Date(iso).toLocaleDateString('vi-VN');
}
