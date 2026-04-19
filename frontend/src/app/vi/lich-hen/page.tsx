import Link from 'next/link';
import type { Route } from 'next';
import { getMyAppointments } from '@/lib/appointments';
import { formatDateTime } from '@/lib/format';

const STATUS_COLORS: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  canceled: 'bg-red-100 text-red-800',
};

export const metadata = { title: 'Lịch hẹn của tôi' };

export default async function MyAppointmentsPage({
  searchParams,
}: {
  searchParams: Promise<{ page?: string }>;
}) {
  const { page } = await searchParams;
  const res = await getMyAppointments(Number(page) || 1);

  return (
    <>
      <h1 className="mb-8 font-headline text-3xl font-bold text-on-surface">
        Lịch hẹn của tôi
      </h1>

      {res.data.length === 0 ? (
        <p className="text-on-surface-variant">Bạn chưa có lịch hẹn nào.</p>
      ) : (
        <div className="space-y-4">
          {res.data.map((apt) => (
            <Link
              key={apt.id}
              href={`/vi/lich-hen/${apt.id}` as Route}
              className="flex items-center justify-between rounded-2xl bg-surface-container-lowest p-6 transition-shadow hover:shadow-ambient"
            >
              <div className="space-y-1">
                <p className="font-headline text-lg font-bold text-on-surface">
                  {apt.company.name ?? 'Thợ'}
                </p>
                <p className="text-sm text-on-surface-variant">
                  {apt.appointment_date} — {apt.appointment_time}
                </p>
                <p className="text-sm text-on-surface-variant">
                  {apt.recipient_address}
                </p>
              </div>
              <div className="flex flex-col items-end gap-2">
                <span
                  className={`rounded-full px-3 py-1 text-xs font-bold ${STATUS_COLORS[apt.status] ?? 'bg-gray-100 text-gray-800'}`}
                >
                  {apt.status_label}
                </span>
                <span className="text-xs text-outline">
                  {formatDateTime(apt.created_at)}
                </span>
              </div>
            </Link>
          ))}

          {res.meta.last_page > 1 && (
            <div className="flex justify-center gap-2 pt-4">
              {Array.from({ length: res.meta.last_page }, (_, i) => (
                <Link
                  key={i + 1}
                  href={`/vi/lich-hen?page=${i + 1}` as Route}
                  className={`rounded-lg px-3 py-1 text-sm font-medium ${
                    res.meta.current_page === i + 1
                      ? 'bg-primary text-on-primary'
                      : 'text-on-surface-variant hover:bg-surface-container'
                  }`}
                >
                  {i + 1}
                </Link>
              ))}
            </div>
          )}
        </div>
      )}
    </>
  );
}
