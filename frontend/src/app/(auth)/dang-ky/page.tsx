import type { Metadata } from 'next';
import Link from 'next/link';
import { SocialButtons } from '@/components/social-buttons';
import { RegisterForm } from './register-form';

export const metadata: Metadata = {
  title: 'Đăng ký | doitay.vn',
};

export default function RegisterPage() {
  return (
    <>
      <header className="mb-12">
        <h1 className="mb-4 font-headline text-[3.5rem] font-bold leading-none text-primary">
          Đăng ký thành viên
        </h1>
        <p className="text-[1.375rem] font-medium text-secondary">
          Bắt đầu hành trình của bạn ngay hôm nay.
        </p>
      </header>

      <RegisterForm />

      <div className="mt-12">
        <div className="mb-8 flex items-center gap-4">
          <div className="h-[2px] flex-1 bg-surface-container-highest" />
          <span className="text-[1.125rem] font-bold text-secondary">
            Hoặc đăng ký bằng
          </span>
          <div className="h-[2px] flex-1 bg-surface-container-highest" />
        </div>

        <SocialButtons />
      </div>

      <footer className="mt-12 text-center">
        <p className="text-[1.125rem] text-secondary">
          Đã có tài khoản?{' '}
          <Link href="/login" className="font-bold text-primary hover:underline">
            Đăng nhập
          </Link>
        </p>
      </footer>
    </>
  );
}
