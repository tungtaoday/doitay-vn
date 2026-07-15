import type { Metadata } from 'next';
import { RequestWizard } from './request-wizard';
import { getPublicCategories } from '@/lib/service-requests';
import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { AuthUser } from '@/lib/api-types';

export const metadata: Metadata = {
  title: 'Tạo yêu cầu dịch vụ',
  description:
    'Mô tả công việc bạn cần — chúng tôi sẽ kết nối với thợ phù hợp trong thời gian ngắn nhất.',
};

/**
 * Khách CHƯA đăng nhập vẫn xem + điền được form (đây là hành động có ý định
 * cao nhất — chặn từ cửa là giết conversion). Gate ở bước GỬI: server action
 * trả needsLogin → wizard đưa sang /login.
 */
export default async function CreateRequestPage() {
  const token = await getToken();
  let user: AuthUser | null = null;
  if (token) {
    try {
      const res = await api<{ data: AuthUser }>('/auth/me', { token });
      user = res.data;
    } catch {
      // token hỏng/hết hạn → coi như khách
    }
  }

  const categories = await getPublicCategories();

  return <RequestWizard categories={categories} user={user} />;
}
