import type { Metadata } from 'next';
import Link from 'next/link';
import { ForgotForm } from './forgot-form';

export const metadata: Metadata = {
  title: 'Quên mật khẩu | doitay.vn',
};

export default function ForgotPasswordPage() {
  return (
    <div className="space-y-8">
      <header className="space-y-2 text-center">
        <h1 className="font-headline text-4xl font-bold tracking-tight text-on-surface">
          Quên mật khẩu?
        </h1>
        <p className="text-sm text-on-surface-variant">
          Nhập email hoặc số điện thoại đã đăng ký, chúng tôi sẽ gửi mã xác nhận
        </p>
      </header>

      <div className="space-y-6 rounded-[2rem] bg-surface p-8 shadow-ambient">
        <ForgotForm />
      </div>

      <p className="text-center text-sm text-on-surface-variant">
        Nhớ mật khẩu rồi?{' '}
        <Link href="/login" className="font-semibold text-primary hover:underline">
          Đăng nhập
        </Link>
      </p>
    </div>
  );
}
