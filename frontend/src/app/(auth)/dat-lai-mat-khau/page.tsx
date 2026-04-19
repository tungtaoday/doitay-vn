import type { Metadata } from 'next';
import Link from 'next/link';
import { ResetForm } from './reset-form';

export const metadata: Metadata = {
  title: 'Đặt lại mật khẩu | doitay.vn',
};

export default async function ResetPasswordPage({
  searchParams,
}: {
  searchParams: Promise<{ identifier?: string }>;
}) {
  const sp = await searchParams;
  const identifier = sp.identifier ?? '';

  return (
    <div className="space-y-8">
      <header className="space-y-2 text-center">
        <h1 className="font-headline text-4xl font-bold tracking-tight text-on-surface">
          Đặt lại mật khẩu
        </h1>
        <p className="text-sm text-on-surface-variant">
          Nhập mã 6 chữ số đã được gửi tới email/SĐT của bạn
        </p>
      </header>

      <div className="space-y-6 rounded-[2rem] bg-surface p-8 shadow-ambient">
        <ResetForm identifier={identifier} />
      </div>

      <p className="text-center text-sm text-on-surface-variant">
        <Link href="/login" className="font-semibold text-primary hover:underline">
          Quay lại đăng nhập
        </Link>
      </p>
    </div>
  );
}
