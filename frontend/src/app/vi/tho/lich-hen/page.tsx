import Link from 'next/link';
import type { Route } from 'next';
import { getThoAppointments } from '@/lib/appointments';
import { formatVND, formatDateTime } from '@/lib/format';

const STATUS_COLORS: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  canceled: 'bg-red-100 text-red-800',
};

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

      {/* Filter */}
      <div className="mb-6 flex flex-wrap gap-2">
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
            className={`rounded-full px-4 py-1.5 text-sm font-medium transition-colors ${
              (status ?? '') === f.value
                ? 'bg-primary text-on-primary'
                : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'
            }`}
          >
            {f.label}
          </Link>
        ))}
      </div>

      {/* List */}
      {res.data.length === 0 ? (
        <p className="text-on-surface-variant">Không có lịch hẹn nào.</p>
      ) : (
        <div className="space-y-4">
          {res.data.map((apt) => (
            <Link
              key={apt.id}
              href={`/vi/tho/lich-hen/${apt.id}` as Route}
              className="flex items-center justify-between rounded-2xl bg-surface-container-lowest p-6 transition-shadow hover:shadow-ambient"
            >
              <div className="space-y-1">
                <p className="font-headline text-lg font-bold text-on-surface">
                  {apt.customer.name}
                </p>
                <p className="text-sm text-on-surface-variant">
                  {apt.appointment_date} — {apt.appointment_time}
                </p>
                {apt.customer_info_unlocked && (
                  <p className="text-sm text-on-surface-variant">{apt.customer.phone}</p>
                )}
              </div>
              <div className="flex flex-col items-end gap-2">
                <span
                  className={`rounded-full px-3 py-1 text-xs font-bold ${STATUS_COLORS[apt.status] ?? 'bg-gray-100'}`}
                >
                  {apt.status_label}
                </span>
                {apt.can_confirm && (
                  <span className="text-xs text-primary">Xác nhận: {formatVND(50000)}</span>
                )}
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
