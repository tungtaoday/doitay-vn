import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { getSiteSettings } from '@/lib/site-settings';

export const metadata: Metadata = {
  title: 'Trở thành thợ trên Doitay — miễn phí, không trung gian',
  description:
    'Tạo hồ sơ nghề chuyên nghiệp miễn phí, nhận khách trong khu vực và xây dựng uy tín cá nhân trên Doitay.vn.',
  alternates: { canonical: '/tuyen-dung-tho' },
};

const BENEFITS = [
  {
    icon: 'badge',
    title: 'Hồ sơ nghề miễn phí',
    desc: 'Trang hồ sơ chuyên nghiệp với ảnh công việc, bảng giá và đánh giá thật — gửi khách bằng một đường link hoặc mã QR, khỏi giải thích tay nghề nhiều lần.',
  },
  {
    icon: 'person_search',
    title: 'Khách tìm đến bạn',
    desc: 'Khách trong khu vực tìm thợ theo nghề và quận huyện — hồ sơ tốt, đánh giá cao sẽ được ưu tiên hiển thị.',
  },
  {
    icon: 'payments',
    title: 'Không phí trung gian',
    desc: 'Bạn làm việc và nhận tiền trực tiếp từ khách. Doitay không thu phí hoa hồng trên công sức của bạn.',
  },
];

const STEPS = [
  { n: '1', title: 'Đăng ký tài khoản', desc: 'Chỉ cần số điện thoại — chưa đến 2 phút.' },
  { n: '2', title: 'Tạo hồ sơ nghề', desc: 'Thêm ảnh công việc thật, kỹ năng, bảng giá.' },
  { n: '3', title: 'Chia sẻ & nhận việc', desc: 'Gửi hồ sơ cho khách quen, nhận yêu cầu mới từ khu vực.' },
];

export default async function TuyenDungThoPage() {
  const settings = await getSiteSettings();
  const phone = settings.contact.phone;

  return (
    <div className="bg-surface">
      {/* Hero */}
      <section className="relative overflow-hidden px-6 pb-20 pt-16 md:px-8">
        <div className="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-primary-fixed/40 blur-3xl" />
        <div className="relative mx-auto max-w-4xl text-center">
          <span className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
            <span className="material-symbols-outlined text-sm">construction</span>
            Dành cho thợ và đội nhóm
          </span>
          <h1 className="mt-6 font-headline text-4xl font-extrabold leading-tight text-on-surface md:text-6xl">
            Tay nghề của bạn
            <br />
            <span className="text-primary">xứng đáng được nhiều khách biết đến.</span>
          </h1>
          <p className="mx-auto mt-6 max-w-2xl text-lg text-on-surface-variant">
            Tạo hồ sơ nghề chuyên nghiệp hoàn toàn miễn phí trên Doitay — nơi khách
            hàng tìm thợ uy tín theo khu vực.
          </p>
          <div className="mt-10 flex flex-col justify-center gap-4 md:flex-row">
            <Link
              href={'/dang-ky' as Route}
              className="rounded-full bg-primary px-10 py-5 font-headline text-lg font-bold text-on-primary shadow-ambient transition-all hover:brightness-105 active:scale-95"
            >
              Tạo hồ sơ miễn phí
            </Link>
            {phone ? (
              <a
                href={`tel:${phone}`}
                className="rounded-full bg-surface-container-low px-10 py-5 font-headline text-lg font-bold text-primary transition-colors hover:bg-surface-container"
              >
                Gọi tư vấn: {phone}
              </a>
            ) : null}
          </div>
        </div>
      </section>

      {/* Benefits */}
      <section className="bg-surface-container-low py-20">
        <div className="mx-auto grid max-w-6xl grid-cols-1 gap-8 px-6 md:grid-cols-3 md:px-8">
          {BENEFITS.map((b) => (
            <div key={b.title} className="rounded-4xl bg-surface-container-lowest p-8 shadow-soft">
              <div className="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">
                <span className="material-symbols-outlined text-3xl text-primary">{b.icon}</span>
              </div>
              <h3 className="mb-3 font-headline text-xl font-bold text-on-surface">{b.title}</h3>
              <p className="text-sm leading-relaxed text-on-surface-variant">{b.desc}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Steps */}
      <section className="py-20">
        <div className="mx-auto max-w-4xl px-6 md:px-8">
          <h2 className="mb-14 text-center font-headline text-3xl font-bold text-on-surface">
            Bắt đầu trong 3 bước
          </h2>
          <div className="grid grid-cols-1 gap-10 md:grid-cols-3">
            {STEPS.map((s) => (
              <div key={s.n} className="text-center">
                <div className="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary font-headline text-xl font-bold text-on-primary">
                  {s.n}
                </div>
                <h3 className="mb-2 font-headline text-lg font-bold text-on-surface">{s.title}</h3>
                <p className="text-sm text-on-surface-variant">{s.desc}</p>
              </div>
            ))}
          </div>
          <div className="mt-16 text-center">
            <Link
              href={'/dang-ky' as Route}
              className="inline-block rounded-full bg-primary px-10 py-5 font-headline text-lg font-bold text-on-primary shadow-ambient transition-all hover:brightness-105 active:scale-95"
            >
              Đăng ký ngay — miễn phí
            </Link>
            <p className="mt-4 text-sm text-on-surface-variant">
              Đã có tài khoản?{' '}
              <Link href="/login" className="font-semibold text-primary hover:underline">
                Đăng nhập
              </Link>{' '}
              rồi vào mục &ldquo;Đăng ký thợ&rdquo;.
            </p>
          </div>
        </div>
      </section>
    </div>
  );
}
