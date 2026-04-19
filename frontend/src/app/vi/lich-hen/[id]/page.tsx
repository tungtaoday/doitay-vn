import { notFound } from 'next/navigation';
import { getMyAppointment, getAppointmentRating } from '@/lib/appointments';
import { formatDateTime } from '@/lib/format';
import { ApiError } from '@/lib/api';
import { AppointmentActions } from './appointment-actions';

const STATUS_COLORS: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  confirmed: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  canceled: 'bg-red-100 text-red-800',
};

export const metadata = { title: 'Chi tiết lịch hẹn' };

export default async function AppointmentDetailPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  const numId = Number(id);

  let appointment;
  try {
    const res = await getMyAppointment(numId);
    appointment = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) notFound();
    throw e;
  }

  let existingRating = null;
  if (appointment.status === 'completed') {
    try {
      const rRes = await getAppointmentRating(numId);
      existingRating = rRes.data;
    } catch {
      // ignore
    }
  }

  return (
    <>
      <h1 className="mb-8 font-headline text-3xl font-bold text-on-surface">
        Chi tiết lịch hẹn #{appointment.id}
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
            <dt className="text-sm font-medium text-on-surface-variant">Thợ</dt>
            <dd className="mt-1 text-lg font-bold text-on-surface">{appointment.company.name}</dd>
          </div>
          <div>
            <dt className="text-sm font-medium text-on-surface-variant">Ngày hẹn</dt>
            <dd className="mt-1 text-lg text-on-surface">
              {appointment.appointment_date} — {appointment.appointment_time}
            </dd>
          </div>
          <div>
            <dt className="text-sm font-medium text-on-surface-variant">Người nhận</dt>
            <dd className="mt-1 text-on-surface">{appointment.recipient_name}</dd>
          </div>
          <div>
            <dt className="text-sm font-medium text-on-surface-variant">Điện thoại</dt>
            <dd className="mt-1 text-on-surface">{appointment.recipient_phone}</dd>
          </div>
          <div className="md:col-span-2">
            <dt className="text-sm font-medium text-on-surface-variant">Địa chỉ</dt>
            <dd className="mt-1 text-on-surface">{appointment.recipient_address}</dd>
          </div>
          {appointment.notes && (
            <div className="md:col-span-2">
              <dt className="text-sm font-medium text-on-surface-variant">Ghi chú</dt>
              <dd className="mt-1 text-on-surface">{appointment.notes}</dd>
            </div>
          )}
        </dl>
      </div>

      <AppointmentActions
        appointmentId={appointment.id}
        companyId={appointment.company.id ?? 0}
        status={appointment.status}
        hasRating={!!existingRating}
      />
    </>
  );
}
