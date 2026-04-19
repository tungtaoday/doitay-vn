import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type {
  Appointment,
  Paginated,
  ThoAppointmentListResponse,
  ThoAppointment,
  PublicRating,
  RatingFeature,
} from '@/lib/api-types';

async function authed() {
  const token = await getToken();
  if (!token) throw new Error('Unauthenticated');
  return token;
}

export async function getMyAppointments(page = 1) {
  const token = await authed();
  return api<Paginated<Appointment>>(`/user/appointments?page=${page}`, { token });
}

export async function getMyAppointment(id: number) {
  const token = await authed();
  return api<{ data: Appointment }>(`/user/appointments/${id}`, { token });
}

export async function getThoAppointments(page = 1, status?: string) {
  const token = await authed();
  const params = new URLSearchParams({ page: String(page) });
  if (status) params.set('status', status);
  return api<ThoAppointmentListResponse>(`/user/tho/appointments?${params}`, { token });
}

export async function getThoAppointment(id: number) {
  const token = await authed();
  return api<{ data: ThoAppointment }>(`/user/tho/appointments/${id}`, { token });
}

export async function getAppointmentRating(id: number) {
  const token = await authed();
  return api<{ data: unknown }>(`/user/appointments/${id}/rating`, { token });
}

export async function getCompanyRatings(companyId: number, page = 1) {
  return api<Paginated<PublicRating>>(`/public/companies/${companyId}/ratings?page=${page}`);
}

export async function getCategoryFeatures(categoryId: number) {
  return api<{ data: RatingFeature[] }>(`/public/categories/${categoryId}/features`);
}
