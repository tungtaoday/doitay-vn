import type { Metadata } from 'next';
import { redirect } from 'next/navigation';
import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { AuthUser } from '@/lib/api-types';
import { SaleLoginForm } from './sale-login-form';

export const metadata: Metadata = {
  title: 'Đăng nhập Sale | Doitay',
  robots: { index: false, follow: false },
};

/**
 * Đăng nhập riêng cho vai trò Sale/CTV — tách khỏi luồng thợ/khách.
 * Tài khoản do quản lý (admin) tạo; không có tự đăng ký ở đây.
 */
export default async function SaleLoginPage() {
  // Nếu đã đăng nhập hợp lệ, vào thẳng /sale (tránh hiện lại form).
  // Lưu ý: redirect() ném NEXT_REDIRECT nên PHẢI gọi ngoài try/catch.
  const token = await getToken();
  let alreadyValid = false;
  if (token) {
    try {
      await api<{ data: AuthUser }>('/auth/me', { token });
      alreadyValid = true;
    } catch {
      // token hỏng/hết hạn → cứ hiện form đăng nhập
    }
  }
  if (alreadyValid) redirect('/sale');

  return (
    <div className="flex min-h-[80vh] items-center justify-center bg-surface px-6 py-16">
      <div className="w-full max-w-md">
        <div className="mb-8 text-center">
          <div className="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">
            <span className="material-symbols-outlined text-[30px] text-primary">badge</span>
          </div>
          <h1 className="font-headline text-3xl font-bold text-on-surface">Cổng Sale / CTV</h1>
          <p className="mt-2 text-sm text-on-surface-variant">
            Đăng nhập bằng tài khoản quản lý cấp để nhập hồ sơ thợ.
          </p>
        </div>

        <div className="rounded-3xl bg-surface-container-lowest p-6 shadow-ambient md:p-8">
          <SaleLoginForm />
        </div>

        <p className="mt-6 text-center text-xs text-on-surface-variant">
          Chưa có tài khoản? Liên hệ quản lý để được cấp — cổng này không tự đăng ký.
        </p>
      </div>
    </div>
  );
}
