import type { Metadata } from 'next';
import Link from 'next/link';
import { SocialButtons, SOCIAL_LOGIN_ENABLED } from '@/components/social-buttons';
import { RegisterForm } from './register-form';

export const metadata: Metadata = {
  title: 'Đăng ký',
  description: 'Tạo tài khoản doitay.vn miễn phí — đăng yêu cầu và nhận báo giá từ thợ uy tín gần bạn.',
  alternates: { canonical: '/dang-ky' },
};

export default function RegisterPage() {
  return (
    <>
      <header className="mb-12">
        <h1 className="mb-3 font-headline text-4xl font-bold leading-tight tracking-tight text-primary md:text-5xl">
          Đăng ký thành viên
        </h1>
        <p className="text-lg font-medium text-secondary">
          Miễn phí — tạo yêu cầu và nhận phản hồi từ thợ trong khu vực của bạn.
        </p>
      </header>

      <RegisterForm />

      {SOCIAL_LOGIN_ENABLED && (
        <div className="mt-10">
          <div className="mb-8 flex items-center gap-4">
            <div className="h-[2px] flex-1 bg-surface-container-highest" />
            <span className="text-[1.125rem] font-bold text-secondary">
              Hoặc đăng ký bằng
            </span>
            <div className="h-[2px] flex-1 bg-surface-container-highest" />
          </div>
          <SocialButtons />
        </div>
      )}

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
