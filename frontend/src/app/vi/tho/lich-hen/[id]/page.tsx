import Link from 'next/link';
import type { Route } from 'next';
import { notFound } from 'next/navigation';
import { getThoAppointment } from '@/lib/appointments';
import { formatVND, formatDateTime } from '@/lib/format';
import { ApiError } from '@/lib/api';
import { AppointmentStatusBadge } from '@/components/appointment-status';
import { ThoActions } from './tho-actions';

export const metadata = { title: 'Chi tiết lịch hẹn — Thợ' };

function InfoRow({ icon, label, children }: { icon: string; label: string; children: React.ReactNode }) {
  return (
    <div className="flex items-start gap-3.5">
      <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10">
        <span className="material-symbols-outlined text-[1.25rem] text-primary">{icon}</span>
      </span>
      <div className="min-w-0">
        <p className="text-xs font-medium uppercase tracking-wide text-on-surface-variant">{label}</p>
        <div className="mt-0.5 font-medium text-on-surface">{children}</div>
      </div>
    </div>
  );
}

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
      <Link
        href={'/vi/tho/lich-hen' as Route}
        className="mb-6 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:underline"
      >
        <span className="material-symbols-outlined text-[1.125rem]">arrow_back</span>
        Quản lý lịch hẹn
      </Link>

      <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div className="flex items-center gap-4">
          <span className="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-on-primary shadow-ambient">
            <span className="material-symbols-outlined text-[1.75rem]" style={{ fontVariationSettings: "'FILL' 1" }}>
              calendar_month
            </span>
          </span>
          <div>
            <h1 className="font-headline text-2xl font-bold text-on-surface">
              Lịch hẹn #{appointment.id}
            </h1>
            <p className="text-sm text-on-surface-variant">
              Tạo lúc {formatDateTime(appointment.created_at)}
            </p>
          </div>
        </div>
        <AppointmentStatusBadge status={appointment.status} label={appointment.status_label} size="md" />
      </div>

      <div className="mb-8 rounded-3xl bg-surface-container-lowest p-6 shadow-soft ring-1 ring-outline-variant/10 md:p-8">
        <div className="grid gap-6 md:grid-cols-2">
          <InfoRow icon="person" label="Khách hàng">
            <span className="font-bold">{appointment.customer.name}</span>
          </InfoRow>
          <InfoRow icon="event" label="Thời gian hẹn">
            {appointment.appointment_date} — {appointment.appointment_time}
          </InfoRow>
          <InfoRow icon="call" label="Điện thoại">{appointment.customer.phone}</InfoRow>
          <InfoRow icon="storefront" label="Hồ sơ nhận việc">{appointment.company.name}</InfoRow>
          <div className="md:col-span-2">
            <InfoRow icon="location_on" label="Địa chỉ">{appointment.customer.address}</InfoRow>
          </div>
          {appointment.notes ? (
            <div className="md:col-span-2">
              <InfoRow icon="sticky_note_2" label="Ghi chú của khách">{appointment.notes}</InfoRow>
            </div>
          ) : null}
        </div>

        {!appointment.customer_info_unlocked && appointment.status === 'pending' && (
          <div className="mt-6 flex items-start gap-3 rounded-xl bg-secondary-container px-5 py-4 text-sm text-on-secondary-container">
            <span className="material-symbols-outlined text-[1.25rem]" style={{ fontVariationSettings: "'FILL' 1" }}>
              lock
            </span>
            <p>
              SĐT &amp; địa chỉ đầy đủ của khách sẽ hiển thị sau khi bạn xác nhận
              {appointment.confirm_fee ? <> (phí {formatVND(appointment.confirm_fee)} trừ từ ví)</> : null}.
            </p>
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
