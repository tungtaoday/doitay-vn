import Link from 'next/link';
import type { Route } from 'next';
import { notFound } from 'next/navigation';
import { getMyAppointment, getAppointmentRating, getCategoryFeatures } from '@/lib/appointments';
import { formatDateTime } from '@/lib/format';
import { api, ApiError } from '@/lib/api';
import type { DetailEnvelope, PublicCompanyDetail, RatingFeature } from '@/lib/api-types';
import { AppointmentStatusBadge } from '@/components/appointment-status';
import { AppointmentActions } from './appointment-actions';

export const metadata = { title: 'Chi tiết lịch hẹn' };

/** Hàng thông tin có icon — ngôn ngữ chung của design system. */
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
  let features: RatingFeature[] = [];
  if (appointment.status === 'completed') {
    try {
      const rRes = await getAppointmentRating(numId);
      existingRating = rRes.data;
    } catch {
      // chưa có đánh giá
    }
    // Tải tiêu chí đánh giá theo nghề Ở SERVER (đúng quy ước: mọi fetch qua lib/api).
    if (!existingRating && appointment.company.id) {
      try {
        const co = await api<DetailEnvelope<PublicCompanyDetail>>(
          `/public/companies/${appointment.company.id}`,
        );
        if (co.data.category?.id) {
          const f = await getCategoryFeatures(co.data.category.id);
          features = f.data;
        }
      } catch {
        // không có tiêu chí → form chỉ còn nhận xét
      }
    }
  }

  return (
    <>
      {/* Breadcrumb quay lại */}
      <Link
        href={'/vi/lich-hen' as Route}
        className="mb-6 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:underline"
      >
        <span className="material-symbols-outlined text-[1.125rem]">arrow_back</span>
        Lịch hẹn của tôi
      </Link>

      {/* Header */}
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

      {/* Thông tin */}
      <div className="mb-8 rounded-3xl bg-surface-container-lowest p-6 shadow-soft ring-1 ring-outline-variant/10 md:p-8">
        <div className="grid gap-6 md:grid-cols-2">
          <InfoRow icon="engineering" label="Thợ">
            {appointment.company.id ? (
              <Link href={`/tho/${appointment.company.id}` as Route} className="font-bold text-primary hover:underline">
                {appointment.company.name}
              </Link>
            ) : (
              appointment.company.name
            )}
          </InfoRow>
          <InfoRow icon="event" label="Thời gian hẹn">
            {appointment.appointment_date} — {appointment.appointment_time}
          </InfoRow>
          <InfoRow icon="person" label="Người nhận">{appointment.recipient_name}</InfoRow>
          <InfoRow icon="call" label="Điện thoại">{appointment.recipient_phone}</InfoRow>
          <div className="md:col-span-2">
            <InfoRow icon="location_on" label="Địa chỉ">{appointment.recipient_address}</InfoRow>
          </div>
          {appointment.notes ? (
            <div className="md:col-span-2">
              <InfoRow icon="sticky_note_2" label="Ghi chú">{appointment.notes}</InfoRow>
            </div>
          ) : null}
        </div>
      </div>

      <AppointmentActions
        appointmentId={appointment.id}
        status={appointment.status}
        hasRating={!!existingRating}
        features={features}
      />
    </>
  );
}
