'use client';

import Link from 'next/link';
import { useActionState, useEffect, useMemo, useState } from 'react';
import { createAppointmentAction, type CreateAppointmentResult } from './actions';
import type { AuthUser, PublicCompanyDetail, ServiceRequestData } from '@/lib/api-types';

type Step = 'form' | 'confirm' | 'success';

interface Props {
  company: PublicCompanyDetail;
  user: AuthUser | null;
  prefillRequest?: ServiceRequestData | null;
}

function composeAddressFromRequest(req: ServiceRequestData): string {
  return [req.location.address, req.location.ward, req.location.district, req.location.city]
    .filter(Boolean)
    .join(', ');
}

function slotToTime(slot: ServiceRequestData['preferred_time_slot']): string {
  if (slot === 'morning') return '09:00';
  if (slot === 'afternoon') return '14:00';
  if (slot === 'evening') return '17:00';
  return '';
}

function todayISO(): string {
  const d = new Date();
  d.setHours(0, 0, 0, 0);
  return d.toISOString().slice(0, 10);
}

function maxISO(days = 30): string {
  const d = new Date();
  d.setDate(d.getDate() + days);
  return d.toISOString().slice(0, 10);
}

const TIME_SLOTS = [
  '07:00', '08:00', '09:00', '10:00', '11:00',
  '13:00', '14:00', '15:00', '16:00', '17:00', '18:00',
];

export function AppointmentBookingForm({ company, user, prefillRequest }: Props) {
  const [step, setStep] = useState<Step>('form');
  const [state, formAction, isPending] = useActionState<CreateAppointmentResult | null, FormData>(
    createAppointmentAction,
    null,
  );

  const [name, setName] = useState(prefillRequest?.contact.name ?? user?.name ?? '');
  const [phone, setPhone] = useState(prefillRequest?.contact.phone ?? user?.mobile ?? '');
  const [address, setAddress] = useState(
    prefillRequest
      ? composeAddressFromRequest(prefillRequest)
      : user?.location?.address
        ? [user.location.address, user.location.ward, user.location.district, user.location.city]
            .filter(Boolean)
            .join(', ')
        : '',
  );
  const [date, setDate] = useState(prefillRequest?.preferred_date ?? '');
  const [time, setTime] = useState(slotToTime(prefillRequest?.preferred_time_slot ?? null));
  const [notes, setNotes] = useState(
    prefillRequest ? `[Từ yêu cầu #${prefillRequest.id}] ${prefillRequest.description}`.slice(0, 1000) : '',
  );
  const [clientError, setClientError] = useState<string | null>(null);

  const minDate = useMemo(() => todayISO(), []);
  const maxDate = useMemo(() => maxISO(30), []);

  useEffect(() => {
    if (state?.ok) setStep('success');
  }, [state]);

  const inputCls =
    'w-full rounded-xl border border-outline-variant/40 bg-surface-container-low px-4 py-3 text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/30';

  // Đặt lịch KHÔNG cần đăng nhập: khách vãng lai vẫn thấy form đầy đủ. Nếu chưa
  // đăng nhập, server action đặt qua luồng guest (tự tạo tài khoản theo SĐT/email).

  // --- Success state ---
  if (step === 'success' && state?.ok) {
    return (
      <div className="space-y-4 rounded-2xl border border-emerald-200 bg-emerald-50/60 p-6 text-center">
        <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100">
          <span className="material-symbols-outlined text-3xl text-emerald-600">check_circle</span>
        </div>
        <div>
          <h4 className="font-headline text-lg font-bold text-on-surface">
            Đã gửi yêu cầu đặt lịch
          </h4>
          <p className="mt-1 text-sm text-on-surface-variant">
            {company.name} sẽ liên hệ với bạn trong thời gian sớm nhất
          </p>
        </div>
        {state.guest ? (
          <p className="text-sm text-on-surface-variant">
            Thợ sẽ gọi số{' '}
            <span className="font-semibold text-on-surface">{phone}</span>{' '}
            để xác nhận. Anh/chị không cần đăng nhập.
          </p>
        ) : (
          <div className="flex gap-2">
            <Link
              href={`/vi/lich-hen/${state.id}`}
              className="flex-1 rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-on-primary"
            >
              Xem chi tiết
            </Link>
            <Link
              href="/vi/lich-hen"
              className="flex-1 rounded-xl border border-outline-variant/40 px-4 py-3 text-sm font-semibold text-on-surface"
            >
              Danh sách lịch hẹn
            </Link>
          </div>
        )}
      </div>
    );
  }

  function validate(): string | null {
    if (!name.trim()) return 'Vui lòng nhập tên người nhận';
    if (!/^[0-9+\-\s]{8,15}$/.test(phone.trim()))
      return 'Số điện thoại không hợp lệ (8-15 chữ số)';
    if (!address.trim()) return 'Vui lòng nhập địa chỉ';
    if (!date) return 'Vui lòng chọn ngày hẹn';
    if (!time) return 'Vui lòng chọn giờ hẹn';

    const selected = new Date(`${date}T${time}:00`);
    const minAllowed = new Date(Date.now() + 2 * 60 * 60 * 1000);
    if (selected < minAllowed) {
      return 'Lịch hẹn phải cách hiện tại ít nhất 2 giờ';
    }
    return null;
  }

  function handleContinue(e: React.FormEvent) {
    e.preventDefault();
    const err = validate();
    if (err) {
      setClientError(err);
      return;
    }
    setClientError(null);
    setStep('confirm');
  }

  const fieldErrors = state && !state.ok ? state.fieldErrors : undefined;
  const topError = clientError ?? (state && !state.ok ? state.error : null);

  const formatDateVi = (d: string) => {
    if (!d) return '';
    const [y, m, day] = d.split('-');
    return `${day}/${m}/${y}`;
  };

  // --- Confirm step ---
  if (step === 'confirm') {
    return (
      <form action={formAction} className="space-y-4">
        <input type="hidden" name="company_id" value={company.id} />
        {prefillRequest && (
          <input type="hidden" name="service_request_id" value={prefillRequest.id} />
        )}
        <input type="hidden" name="recipient_name" value={name.trim()} />
        <input type="hidden" name="recipient_phone" value={phone.trim()} />
        <input type="hidden" name="recipient_address" value={address.trim()} />
        <input type="hidden" name="appointment_date" value={date} />
        <input type="hidden" name="appointment_time" value={time} />
        <input type="hidden" name="notes" value={notes.trim()} />

        <div className="rounded-2xl border border-outline-variant/30 bg-surface-container-low/50 p-5">
          <p className="mb-4 text-xs font-semibold uppercase tracking-wider text-on-surface-variant">
            Xác nhận thông tin
          </p>
          <dl className="space-y-3 text-sm">
            <Row label="Thợ" value={company.name} />
            <Row label="Ngày" value={formatDateVi(date)} />
            <Row label="Giờ" value={time} />
            <Row label="Người nhận" value={name} />
            <Row label="Điện thoại" value={phone} />
            <Row label="Địa chỉ" value={address} />
            {notes && <Row label="Ghi chú" value={notes} />}
          </dl>
        </div>

        {topError && (
          <div className="rounded-xl bg-error-container/60 px-4 py-3 text-sm text-on-error-container">
            {topError}
          </div>
        )}

        <div className="flex gap-2">
          <button
            type="button"
            onClick={() => setStep('form')}
            disabled={isPending}
            className="flex-1 rounded-xl border border-outline-variant/40 px-4 py-3 text-sm font-semibold text-on-surface disabled:opacity-60"
          >
            Quay lại
          </button>
          <button
            type="submit"
            disabled={isPending}
            className="flex-[2] rounded-xl bg-primary px-4 py-3 text-sm font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-[0.98] disabled:opacity-60"
          >
            {isPending ? 'Đang gửi...' : 'Xác nhận đặt lịch'}
          </button>
        </div>
      </form>
    );
  }

  // --- Form step ---
  return (
    <form onSubmit={handleContinue} className="space-y-4">
      {topError && (
        <div className="rounded-xl bg-error-container/60 px-4 py-3 text-sm text-on-error-container">
          {topError}
        </div>
      )}

      <Field label="Người nhận" error={fieldErrors?.recipient_name}>
        <input
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
          placeholder="Họ và tên"
          maxLength={255}
          className={inputCls}
          required
        />
      </Field>

      <Field label="Số điện thoại" error={fieldErrors?.recipient_phone}>
        <input
          type="tel"
          value={phone}
          onChange={(e) => setPhone(e.target.value)}
          placeholder="090x xxx xxx"
          maxLength={15}
          className={inputCls}
          required
        />
      </Field>

      <Field label="Địa chỉ" error={fieldErrors?.recipient_address}>
        <input
          type="text"
          value={address}
          onChange={(e) => setAddress(e.target.value)}
          placeholder="Số nhà, đường, phường, quận, thành phố"
          maxLength={500}
          className={inputCls}
          required
        />
      </Field>

      <div className="grid grid-cols-2 gap-3">
        <Field label="Ngày hẹn" error={fieldErrors?.appointment_date}>
          <input
            type="date"
            value={date}
            min={minDate}
            max={maxDate}
            onChange={(e) => setDate(e.target.value)}
            className={inputCls}
            required
          />
        </Field>

        <Field label="Giờ hẹn" error={fieldErrors?.appointment_time}>
          <select
            value={time}
            onChange={(e) => setTime(e.target.value)}
            className={inputCls}
            required
          >
            <option value="">— Chọn —</option>
            {TIME_SLOTS.map((t) => (
              <option key={t} value={t}>
                {t}
              </option>
            ))}
          </select>
        </Field>
      </div>

      <Field label="Mô tả yêu cầu (tuỳ chọn)">
        <textarea
          value={notes}
          onChange={(e) => setNotes(e.target.value)}
          rows={3}
          maxLength={1000}
          placeholder="Tình trạng hiện tại, vật liệu cần..."
          className={inputCls}
        />
      </Field>

      <button
        type="submit"
        data-track-contact="booking"
        className="flex w-full items-center justify-center gap-2 rounded-xl bg-primary py-4 font-headline font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-[0.98]"
      >
        <span className="material-symbols-outlined text-base">calendar_month</span>
        Tiếp tục
      </button>

      <p className="text-center text-xs text-on-surface-variant">
        Thợ sẽ xác nhận trong 15 phút • Miễn phí đặt lịch
      </p>
    </form>
  );
}

function Field({
  label,
  error,
  children,
}: {
  label: string;
  error?: string;
  children: React.ReactNode;
}) {
  return (
    <label className="block">
      <span className="mb-1.5 block text-sm font-semibold text-on-surface">{label}</span>
      {children}
      {error && <span className="mt-1 block text-xs text-error">{error}</span>}
    </label>
  );
}

function Row({ label, value }: { label: string; value: string }) {
  return (
    <div className="flex justify-between gap-4">
      <dt className="text-on-surface-variant">{label}</dt>
      <dd className="text-right font-medium text-on-surface">{value}</dd>
    </div>
  );
}
