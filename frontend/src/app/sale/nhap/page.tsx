import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { SubmissionForm } from './submission-form';

export const metadata: Metadata = {
  title: 'Nhập hồ sơ thợ | Sale',
};

export default async function NhapPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });

  return (
    <div className="mx-auto max-w-2xl px-6 py-10">
      <div className="mb-6">
        <Link href={'/sale' as Route} className="text-sm text-primary hover:underline">
          ← Danh sách hồ sơ
        </Link>
        <h1 className="mt-2 font-headline text-2xl font-bold text-on-surface">Nhập hồ sơ thợ</h1>
        <p className="text-sm text-on-surface-variant">Điền thông tin thợ và thêm 3–5 ảnh công việc.</p>
      </div>

      <SubmissionForm />
    </div>
  );
}
