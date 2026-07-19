import Link from 'next/link';
import type { Route } from 'next';
import { getMyAppointments } from '@/lib/appointments';
import { formatDateTime } from '@/lib/format';
import { AppointmentStatusBadge } from '@/components/appointment-status';

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
        <div className="flex flex-col items-center rounded-3xl border border-dashed border-outline-variant/60 bg-surface-container-lowest px-6 py-16 text-center">
          <div className="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10">
            <span className="material-symbols-outlined text-3xl text-primary">event_available</span>
          </div>
          <h2 className="font-headline text-xl font-bold text-on-surface">Bạn chưa có lịch hẹn nào</h2>
          <p className="mt-2 max-w-sm text-on-surface-variant">
            Tạo yêu cầu để được thợ báo giá, hoặc tìm thợ trong khu vực và đặt lịch trực tiếp.
          </p>
          <div className="mt-6 flex flex-col gap-3 sm:flex-row">
            <Link
              href={'/yeu-cau' as Route}
              className="rounded-xl bg-primary px-6 py-3 font-headline font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95"
            >
              Tạo yêu cầu
            </Link>
            <Link
              href="/tho"
              className="rounded-xl border border-outline-variant/40 px-6 py-3 font-headline font-bold text-on-surface transition-colors hover:bg-surface-container-low"
            >
              Tìm thợ
            </Link>
          </div>
        </div>
      ) : (
        <div className="space-y-4">
          {res.data.map((apt) => (
            <Link
              key={apt.id}
              href={`/vi/lich-hen/${apt.id}` as Route}
              className="flex items-center gap-4 rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/10 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-ambient md:p-6"
            >
              <span className="hidden h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 sm:flex">
                <span className="material-symbols-outlined text-primary">calendar_month</span>
              </span>
              <div className="min-w-0 flex-1 space-y-1">
                <p className="truncate font-headline text-lg font-bold text-on-surface">
                  {apt.company.name ?? 'Thợ'}
                </p>
                <p className="flex items-center gap-1.5 text-sm text-on-surface-variant">
                  <span className="material-symbols-outlined text-[1rem]">event</span>
                  {apt.appointment_date} — {apt.appointment_time}
                </p>
                <p className="flex items-center gap-1.5 truncate text-sm text-on-surface-variant">
                  <span className="material-symbols-outlined text-[1rem]">location_on</span>
                  {apt.recipient_address}
                </p>
              </div>
              <div className="flex shrink-0 flex-col items-end gap-2">
                <AppointmentStatusBadge status={apt.status} label={apt.status_label} />
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
