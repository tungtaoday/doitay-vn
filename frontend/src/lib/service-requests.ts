import { unstable_cache } from 'next/cache';
import { api } from './api';
import { getToken } from './auth';
import type { PublicCategory, ServiceRequestResponse } from './api-types';

export const getPublicCategories = unstable_cache(
  async (): Promise<PublicCategory[]> => {
    const res = await api<{ data: PublicCategory[] }>('/public/categories');
    return res.data;
  },
  ['public-categories'],
  { revalidate: 300 },
);

export async function getServiceRequest(id: number): Promise<ServiceRequestResponse> {
  const token = await getToken();
  return api<ServiceRequestResponse>(`/user/service-requests/${id}`, { token });
}
