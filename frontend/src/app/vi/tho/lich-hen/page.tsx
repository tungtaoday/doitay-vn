import Link from 'next/link';
import type { Route } from 'next';
import { getThoAppointments } from '@/lib/appointments';
import { formatVND, formatDateTime } from '@/lib/format';
import { AppointmentStatusBadge } from '@/components/appointment-status';

export const metadata = { title: 'Quản lý lịch hẹn — Thợ' };

export default async function ThoAppointmentsPage({
  searchParams,
}: {
  searchParams: Promise<{ page?: string; status?: string }>;
}) {
  const { page, status } = await searchParams;
  const res = await getThoAppointments(Number(page) || 1, status);

  return (
    <>
      <h1 className="mb-4 font-headline text-3xl font-bold text-on-surface">
        Quản lý lịch hẹn
      </h1>

      {/* Stats */}
      <div className="mb-8 grid grid-cols-2 gap-4 md:grid-cols-4">
        <StatCard label="Số dư ví" value={formatVND(res.stats.total_balance)} />
        <StatCard label="Đang chờ" value={String(res.stats.pending_count)} sub={`Chi phí: ${formatVND(res.stats.pending_cost)}`} />
        <StatCard label="Đã xác nhận (tháng)" value={String(res.stats.confirmed_this_month)} />
        <StatCard label="Hoàn thành (tháng)" value={String(res.stats.completed_this_month)} />
      </div>

      {!res.stats.can_afford_all && res.stats.pending_count > 0 && (
        <div className="mb-6 rounded-xl bg-error-container p-4 text-sm text-error">
          Số dư ví không đủ để xác nhận tất cả lịch hẹn đang chờ.{' '}
          <Link href={'/vi/nap-tien' as Route} className="font-bold underline">Nạp tiền ngay</Link>
        </div>
      )}

      {/* Filter — segmented pills đồng bộ với DashboardNav */}
      <div className="mb-6 flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
        {[
          { value: '', label: 'Tất cả' },
          { value: 'pending', label: 'Chờ xác nhận' },
          { value: 'confirmed', label: 'Đã xác nhận' },
          { value: 'completed', label: 'Hoàn thành' },
          { value: 'canceled', label: 'Đã huỷ' },
        ].map((f) => (
          <Link
            key={f.value}
            href={`/vi/tho/lich-hen${f.value ? `?status=${f.value}` : ''}` as Route}
            className={`flex min-h-[40px] shrink-0 items-center whitespace-nowrap rounded-full px-4 text-sm transition-all ${
              (status ?? '') === f.value
                ? 'bg-primary font-bold text-on-primary shadow-ambient'
                : 'bg-surface-container-lowest font-medium text-on-surface-variant ring-1 ring-outline-variant/15 hover:bg-surface-container-low hover:text-on-surface'
            }`}
          >
            {f.label}
          </Link>
        ))}
      </div>

      {/* List */}
      {res.data.length === 0 ? (
        <div className="flex flex-col items-center rounded-3xl border border-dashed border-outline-variant/60 bg-surface-container-lowest px-6 py-14 text-center">
          <span className="material-symbols-outlined mb-3 text-4xl text-outline">event_upcoming</span>
          <p className="font-headline font-bold text-on-surface">Chưa có lịch hẹn nào</p>
          <p className="mt-1 max-w-sm text-sm text-on-surface-variant">
            Khi khách đặt lịch với bạn, lịch hẹn sẽ hiện ở đây kèm thông báo.
          </p>
        </div>
      ) : (
        <div className="space-y-4">
          {res.data.map((apt) => (
            <Link
              key={apt.id}
              href={`/vi/tho/lich-hen/${apt.id}` as Route}
              className="flex items-center gap-4 rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/10 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-ambient md:p-6"
            >
              <span className="hidden h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 sm:flex">
                <span className="material-symbols-outlined text-primary">person</span>
              </span>
              <div className="min-w-0 flex-1 space-y-1">
                <p className="truncate font-headline text-lg font-bold text-on-surface">
                  {apt.customer.name}
                </p>
                <p className="flex items-center gap-1.5 text-sm text-on-surface-variant">
                  <span className="material-symbols-outlined text-[1rem]">event</span>
                  {apt.appointment_date} — {apt.appointment_time}
                </p>
                {apt.customer_info_unlocked && (
                  <p className="flex items-center gap-1.5 text-sm text-on-surface-variant">
                    <span className="material-symbols-outlined text-[1rem]">call</span>
                    {apt.customer.phone}
                  </p>
                )}
              </div>
              <div className="flex shrink-0 flex-col items-end gap-2">
                <AppointmentStatusBadge status={apt.status} label={apt.status_label} />
                {apt.can_confirm && res.stats.lead_fee ? (
                  <span className="text-xs font-semibold text-primary">
                    Xác nhận: {formatVND(res.stats.lead_fee)}
                  </span>
                ) : null}
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
                  href={`/vi/tho/lich-hen?page=${i + 1}${status ? `&status=${status}` : ''}` as Route}
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

function StatCard({ label, value, sub }: { label: string; value: string; sub?: string }) {
  return (
    <div className="rounded-2xl bg-surface-container-lowest p-5">
      <p className="text-xs font-medium uppercase text-on-surface-variant">{label}</p>
      <p className="mt-1 font-headline text-2xl font-bold text-on-surface">{value}</p>
      {sub && <p className="mt-0.5 text-xs text-outline">{sub}</p>}
    </div>
  );
}
