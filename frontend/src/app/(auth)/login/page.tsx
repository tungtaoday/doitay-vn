import type { Metadata } from 'next';
import type { Route } from 'next';
import Link from 'next/link';
import { SocialButtons } from '@/components/social-buttons';
import { LoginForm } from './login-form';

export const metadata: Metadata = {
  title: 'Đăng nhập | doitay.vn',
};

export default function LoginPage() {
  return (
    <>
      <header className="mb-12">
        <h2 className="mb-4 font-headline text-[3.5rem] font-bold leading-none tracking-tighter text-on-surface">
          Chào mừng trở lại
        </h2>
        <p className="text-[1.375rem] font-medium text-secondary">
          Nhập thông tin của bạn để tiếp tục
        </p>
      </header>

      <LoginForm />

      <div className="my-12 flex items-center gap-6">
        <div className="h-[2px] flex-grow bg-surface-container-highest" />
        <span className="text-lg font-bold uppercase tracking-widest text-secondary">
          Hoặc
        </span>
        <div className="h-[2px] flex-grow bg-surface-container-highest" />
      </div>

      <SocialButtons />

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
