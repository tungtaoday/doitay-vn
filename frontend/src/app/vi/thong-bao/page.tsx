import Link from 'next/link';
import { requireUser } from '@/lib/require-user';
import { getMyNotifications } from '@/lib/notifications';
import { NotificationsList } from './notifications-list';

export const metadata = {
  title: 'Thông báo — doitay.vn',
};

interface PageProps {
  searchParams: Promise<{ page?: string; type?: string; unread?: string }>;
}

const TYPE_FILTERS: { value: string; label: string }[] = [
  { value: '', label: 'Tất cả' },
  { value: 'appointment', label: 'Lịch hẹn' },
  { value: 'lead', label: 'Yêu cầu' },
  { value: 'system', label: 'Hệ thống' },
  { value: 'campaign', label: 'Khuyến mãi' },
];

export default async function NotificationsPage({ searchParams }: PageProps) {
  await requireUser();
  const sp = await searchParams;
  const page = Number(sp.page ?? '1') || 1;
  const type = sp.type || undefined;
  const unreadOnly = sp.unread === '1';

  const res = await getMyNotifications({ page, perPage: 20, type, unreadOnly });

  return (
    <section className="mx-auto max-w-3xl px-6 py-12">
      <header className="mb-6 flex items-center justify-between">
        <div>
          <h1 className="font-headline text-3xl font-bold text-on-surface">
            Thông báo
          </h1>
          <p className="mt-1 text-sm text-on-surface-variant">
            {res.unread_count > 0
              ? `Bạn có ${res.unread_count} thông báo chưa đọc`
              : 'Bạn đã đọc hết thông báo'}
          </p>
        </div>
      </header>

      <div className="mb-5 flex flex-wrap items-center gap-2">
        {TYPE_FILTERS.map((f) => {
          const active = (type ?? '') === f.value;
          const qs = new URLSearchParams();
          if (f.value) qs.set('type', f.value);
          if (unreadOnly) qs.set('unread', '1');
          const href = (`/vi/thong-bao${qs.toString() ? `?${qs}` : ''}`) as never;
          return (
            <Link
              key={f.value || 'all'}
              href={href}
              className={`rounded-full px-4 py-1.5 text-xs font-semibold transition-colors ${
                active
                  ? 'bg-primary text-on-primary'
                  : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'
              }`}
            >
              {f.label}
            </Link>
          );
        })}
        <Link
          href={
            (unreadOnly
              ? `/vi/thong-bao${type ? `?type=${type}` : ''}`
              : `/vi/thong-bao?unread=1${type ? `&type=${type}` : ''}`) as never
          }
          className={`ml-auto rounded-full px-4 py-1.5 text-xs font-semibold transition-colors ${
            unreadOnly
              ? 'bg-primary-container text-on-primary-container'
              : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'
          }`}
        >
          {unreadOnly ? '✓ Chưa đọc' : 'Chỉ chưa đọc'}
        </Link>
      </div>

      <NotificationsList
        initialData={res.data}
        unreadCount={res.unread_count}
        meta={res.meta}
        page={page}
        type={type}
        unreadOnly={unreadOnly}
      />
    </section>
  );
}
