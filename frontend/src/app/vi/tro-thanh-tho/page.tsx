import Link from 'next/link';
import type { Route } from 'next';
import { redirect } from 'next/navigation';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { AuthUser } from '@/lib/api-types';

export const metadata = {
  title: 'Trở thành thợ — doitay.vn',
};

export default async function BecomeContractorPage() {
  const token = await getToken();
  if (!token) redirect('/login?error=unauthenticated');

  // If already a contractor, redirect to their profile
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    if (res.data.has_company) redirect('/vi/tho/lich-hen');
  } catch { /* proceed */ }

  const BENEFITS = [
    {
      icon: 'groups',
      title: 'Tiếp cận khách hàng',
      desc: 'Hàng ngàn khách hàng đang tìm kiếm thợ tay nghề cao mỗi ngày.',
    },
    {
      icon: 'payments',
      title: 'Thu nhập ổn định',
      desc: 'Nhận đơn hàng trực tiếp, thanh toán minh bạch qua ví điện tử.',
    },
    {
      icon: 'verified',
      title: 'Huy hiệu Verified',
      desc: 'Được xác minh bởi doitay.vn — tăng uy tín, tăng tỉ lệ đặt hàng.',
    },
    {
      icon: 'star',
      title: 'Xây dựng danh tiếng',
      desc: 'Nhận đánh giá thực tế từ khách hàng, xây dựng portfolio uy tín.',
    },
  ];

  return (
    <div className="mx-auto max-w-3xl px-6 py-16 md:px-8">
      {/* Hero */}
      <div className="mb-16 text-center">
        <div className="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-primary/10">
          <span className="material-symbols-outlined text-5xl text-primary" style={{ fontVariationSettings: "'FILL' 1" }}>
            engineering
          </span>
        </div>
        <h1 className="font-headline text-[2.5rem] font-bold leading-tight text-on-surface">
          Bạn muốn trở thành thợ?
        </h1>
        <p className="mx-auto mt-4 max-w-xl text-[1.125rem] leading-relaxed text-secondary">
          Tham gia cộng đồng thợ chuyên nghiệp của doitay.vn — nơi bàn tay tài hoa
          gặp gỡ khách hàng cần hỗ trợ thực sự.
        </p>
      </div>

      {/* Benefits grid */}
      <div className="mb-16 grid grid-cols-1 gap-6 md:grid-cols-2">
        {BENEFITS.map((b) => (
          <div key={b.icon} className="rounded-xl bg-surface-container-lowest p-6">
            <span className="material-symbols-outlined mb-4 block text-3xl text-primary" style={{ fontVariationSettings: "'FILL' 1" }}>
              {b.icon}
            </span>
            <h3 className="mb-2 font-headline text-[1.125rem] font-bold text-on-surface">{b.title}</h3>
            <p className="text-[0.9375rem] leading-relaxed text-secondary">{b.desc}</p>
          </div>
        ))}
      </div>

      {/* Steps */}
      <div className="mb-16 rounded-xl bg-surface-container-lowest p-8">
        <h2 className="mb-6 font-headline text-[1.375rem] font-bold text-on-surface">Chỉ 3 bước đơn giản</h2>
        <ol className="space-y-5">
          {[
            { step: '1', label: 'Điền thông tin hồ sơ thợ', sub: 'Tên, nghề nghiệp, kinh nghiệm, bảng giá dịch vụ' },
            { step: '2', label: 'Tải ảnh đại diện & ảnh công trình', sub: 'Ảnh thực tế tăng tỷ lệ được đặt lịch lên đến 40%' },
            { step: '3', label: 'Chờ admin xét duyệt', sub: 'Thường trong vòng 24 giờ làm việc' },
          ].map((item) => (
            <li key={item.step} className="flex items-start gap-4">
              <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary text-[1rem] font-bold text-on-primary">
                {item.step}
              </span>
              <div>
                <p className="font-bold text-on-surface">{item.label}</p>
                <p className="text-[0.875rem] text-secondary">{item.sub}</p>
              </div>
            </li>
          ))}
        </ol>
      </div>

      {/* CTA */}
      <div className="text-center">
        <Link
          href={'/vi/tho/dang-ky' as Route}
          className="inline-flex h-16 items-center gap-3 rounded-xl bg-primary px-14 text-[1.25rem] font-bold text-on-primary shadow-ambient transition-all hover:brightness-105 active:scale-[0.98]"
        >
          <span className="material-symbols-outlined">arrow_forward</span>
          Bắt đầu đăng ký ngay
        </Link>
        <p className="mt-4 text-[0.875rem] text-secondary">Miễn phí hoàn toàn. Không mất phí đăng ký.</p>
      </div>
    </div>
  );
}
