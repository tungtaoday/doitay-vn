import type { Metadata } from 'next';
import type { Route } from 'next';
import Link from 'next/link';
import { SocialButtons } from '@/components/social-buttons';
import { LoginForm } from './login-form';

export const metadata: Metadata = {
  title: 'Đăng nhập',
  description: 'Đăng nhập tài khoản doitay.vn để đặt lịch thợ, theo dõi yêu cầu và quản lý hồ sơ.',
  alternates: { canonical: '/login' },
};

export default function LoginPage() {
  return (
    <>
      <header className="mb-12">
        <h1 className="mb-3 font-headline text-4xl font-bold leading-tight tracking-tight text-on-surface md:text-5xl">
          Chào mừng trở lại
        </h1>
        <p className="text-lg font-medium text-secondary">
          Nhập thông tin của bạn để tiếp tục
        </p>
      </header>

      <LoginForm />

      <SocialButtons label="Hoặc" />

      <footer className="mt-12 text-center">
        <p className="text-[1.125rem] font-medium text-secondary">
          Chưa có tài khoản?{' '}
          <Link
            href={'/dang-ky' as Route}
            className="font-bold text-primary hover:underline"
          >
            Đăng ký ngay
          </Link>
        </p>
      </footer>
    </>
  );
}
