'use server';

import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { Appointment } from '@/lib/api-types';

export type CreateAppointmentResult =
  | { ok: true; id: number }
  | { ok: false; error: string; fieldErrors?: Record<string, string>; needsLogin?: boolean };

export async function createAppointmentAction(
  _prev: CreateAppointmentResult | null,
  formData: FormData,
): Promise<CreateAppointmentResult> {
  const token = await getToken();
  if (!token) {
    return { ok: false, error: 'Vui lòng đăng nhập để đặt lịch', needsLogin: true };
  }

  const companyId = Number(formData.get('company_id'));
  if (!Number.isFinite(companyId) || companyId <= 0) {
    return { ok: false, error: 'Thiếu thông tin thợ' };
  }

  const serviceRequestIdRaw = formData.get('service_request_id');
  const serviceRequestId = serviceRequestIdRaw ? Number(serviceRequestIdRaw) : null;

  try {
    const res = await api<{ data: Appointment }>('/user/appointments', {
      method: 'POST',
      token,
      json: {
        company_id: companyId,
        recipient_name: String(formData.get('recipient_name') ?? '').trim(),
        recipient_phone: String(formData.get('recipient_phone') ?? '').trim(),
        recipient_address: String(formData.get('recipient_address') ?? '').trim(),
        appointment_date: String(formData.get('appointment_date') ?? ''),
        appointment_time: String(formData.get('appointment_time') ?? ''),
        notes: String(formData.get('notes') ?? '').trim() || null,
        service_request_id: serviceRequestId && Number.isFinite(serviceRequestId) ? serviceRequestId : null,
      },
    });
    return { ok: true, id: res.data.id };
  } catch (e) {
    if (e instanceof ApiError) {
      if (e.status === 401) {
        return { ok: false, error: 'Phiên đăng nhập đã hết hạn', needsLogin: true };
      }
      if (e.status === 409) {
        const body = e.body as { message?: string };
        return { ok: false, error: body.message ?? 'Bạn cần hoàn thành hồ sơ trước' };
      }
      if (e.status === 422) {
        const body = e.body as { message?: string; errors?: Record<string, string[]> };
        const fieldErrors: Record<string, string> = {};
        if (body.errors) {
          for (const [k, v] of Object.entries(body.errors)) {
            fieldErrors[k] = v[0];
          }
        }
        const firstError =
          Object.values(fieldErrors)[0] ?? body.message ?? 'Dữ liệu không hợp lệ';
        return { ok: false, error: firstError, fieldErrors };
      }
      if (e.status === 429) {
        return { ok: false, error: 'Bạn đặt quá nhanh, đợi 1 phút rồi thử lại' };
      }
    }
    return { ok: false, error: 'Lỗi kết nối máy chủ, thử lại sau' };
  }
}
