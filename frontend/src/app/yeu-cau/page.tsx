import type { Metadata } from 'next';
import { RequestWizard } from './request-wizard';
import { getPublicCategories } from '@/lib/service-requests';
import { requireUser } from '@/lib/require-user';

export const metadata: Metadata = {
  title: 'Tạo yêu cầu dịch vụ',
  description:
    'Mô tả công việc bạn cần — chúng tôi sẽ kết nối với thợ phù hợp trong thời gian ngắn nhất.',
};

export default async function CreateRequestPage() {
  const [user, categories] = await Promise.all([
    requireUser(),
    getPublicCategories(),
  ]);

  return <RequestWizard categories={categories} user={user} />;
}
