import type { Metadata } from 'next';
import './globals.css';

export const metadata: Metadata = {
  title: {
    default: 'doitay.vn — Tìm dịch vụ uy tín',
    template: '%s | doitay.vn',
  },
  description: 'Marketplace kết nối khách hàng với nhà thầu, dịch vụ uy tín tại Việt Nam.',
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="vi">
      <body>
        <header className="border-b bg-white">
          <nav className="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="/" className="text-xl font-bold">doitay.vn</a>
            <div className="flex gap-4 text-sm">
              <a href="/cong-ty" className="hover:underline">Công ty</a>
              <a href="/login" className="hover:underline">Đăng nhập</a>
            </div>
          </nav>
        </header>
        <main className="mx-auto max-w-6xl px-4 py-8">{children}</main>
        <footer className="border-t mt-16 py-6 text-center text-sm text-gray-500">
          © doitay.vn — Headless rebuild Phase 1
        </footer>
      </body>
    </html>
  );
}
