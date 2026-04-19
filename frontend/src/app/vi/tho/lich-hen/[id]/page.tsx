import { notFound } from 'next/navigation';
import { getThoAppointment } from '@/lib/appointments';
import { formatVND, formatDateTime } from '@/lib/format';
import { ApiError } from '@/lib/api';
import { ThoActions } from './tho-actions';

const STATUS_COLORS: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  canceled: 'bg-red-100 text-red-800',
};

export const metadata = { title: 'Chi tiết lịch hẹn — Thợ' };

export default async function ThoAppointmentDetailPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;

  let appointment;
  try {
    const res = await getThoAppointment(Number(id));
    appointment = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) notFound();
    throw e;
  }

  return (
    <>
      <h1 className="mb-8 font-headline text-3xl font-bold text-on-surface">
        Lịch hẹn #{appointment.id}
      </h1>

      <div className="mb-8 rounded-2xl bg-surface-container-lowest p-8">
        <div className="mb-6 flex items-center gap-4">
          <span
            className={`rounded-full px-4 py-1 text-sm font-bold ${STATUS_COLORS[appointment.status] ?? 'bg-gray-100'}`}
          >
            {appointment.status_label}
          </span>
          <span className="text-sm text-outline">{formatDateTime(appointment.created_at)}</span>
        </div>

        <dl className="grid gap-6 md:grid-cols-2">
          <div>
            <dt className="text-sm font-medium text-on-surface-variant">Khách hàng</dt>
            <dd className="mt-1 text-lg font-bold text-on-surface">{appointment.customer.name}</dd>
          </div>
          <div>
            <dt className="text-sm font-medium text-on-surface-variant">Điện thoại</dt>
            <dd className="mt-1 text-on-surface">{appointment.customer.phone}</dd>
          </div>
          <div className="md:col-span-2">
            <dt className="text-sm font-medium text-on-surface-variant">Địa chỉ</dt>
            <dd className="mt-1 text-on-surface">{appointment.customer.address}</dd>
          </div>
          <div>
            <dt className="text-sm font-medium text-on-surface-variant">Ngày hẹn</dt>
            <dd className="mt-1 text-lg text-on-surface">
              {appointment.appointment_date} — {appointment.appointment_time}
            </dd>
          </div>
          <div>
            <dt className="text-sm font-medium text-on-surface-variant">Công ty</dt>
            <dd className="mt-1 text-on-surface">{appointment.company.name}</dd>
          </div>
          {appointment.notes && (
            <div className="md:col-span-2">
              <dt className="text-sm font-medium text-on-surface-variant">Ghi chú</dt>
              <dd className="mt-1 text-on-surface">{appointment.notes}</dd>
            </div>
          )}
        </dl>

        {!appointment.customer_info_unlocked && appointment.status === 'pending' && (
          <div className="mt-6 rounded-xl bg-primary-container p-4 text-sm text-on-primary-container">
            Thông tin khách hàng sẽ được hiển thị sau khi xác nhận (phí: {formatVND(50000)}).
          </div>
        )}
      </div>

      <ThoActions
        appointmentId={appointment.id}
        status={appointment.status}
        canConfirm={appointment.can_confirm}
        canComplete={appointment.can_complete}
        canCancel={appointment.can_cancel}
        confirmFee={appointment.confirm_fee}
      />
    </>
  );
}
