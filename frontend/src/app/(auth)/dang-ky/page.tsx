import type { Metadata } from 'next';
import Link from 'next/link';
import { SocialButtons } from '@/components/social-buttons';
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

      <SocialButtons label="Hoặc đăng ký bằng" />

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
