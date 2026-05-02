import Link from 'next/link';
import type { Route } from 'next';
import type { SiteSettings } from '@/lib/api-types';

const QUICK_LINKS: { href: Route; label: string }[] = [
  { href: '/' as Route, label: 'Trang chủ' },
  { href: '/tho' as Route, label: 'Tìm thợ' },
  { href: '/yeu-cau' as Route, label: 'Tạo yêu cầu' },
  { href: '/gioi-thieu' as Route, label: 'Giới thiệu' },
  { href: '/lien-he' as Route, label: 'Liên hệ' },
];

const POLICY_LINKS: { href: Route; label: string }[] = [
  { href: '/dieu-khoan' as Route, label: 'Điều khoản dịch vụ' },
  { href: '/bao-mat' as Route, label: 'Chính sách bảo mật' },
  { href: '/faq' as Route, label: 'Câu hỏi thường gặp' },
  { href: '/tuyen-dung-tho' as Route, label: 'Tuyển dụng thợ' },
];

export function SiteFooter({ settings }: { settings: SiteSettings }) {
  return (
    <footer className="w-full bg-surface-container-low">
      <div className="mx-auto grid max-w-7xl grid-cols-1 gap-12 px-6 py-16 md:grid-cols-4 md:px-8">
        <div>
          <span className="mb-4 block font-headline text-xl font-bold text-primary">
            {settings.site_name}
          </span>
          <p className="text-sm leading-relaxed text-on-surface-variant">
            Sứ mệnh của chúng tôi là tôn vinh những đôi bàn tay Việt thông qua
            nền tảng kết nối dịch vụ thợ chuyên nghiệp hàng đầu.
          </p>
        </div>

        <div>
          <h4 className="mb-6 font-headline font-bold text-on-surface">
            Liên kết nhanh
          </h4>
          <ul className="space-y-4 text-sm">
            {QUICK_LINKS.map((l) => (
              <li key={l.href}>
                <Link
                  href={l.href}
                  className="text-on-surface-variant transition-colors hover:text-primary"
                >
                  {l.label}
                </Link>
              </li>
            ))}
          </ul>
        </div>

        <div>
          <h4 className="mb-6 font-headline font-bold text-on-surface">
            Chính sách
          </h4>
          <ul className="space-y-4 text-sm">
            {POLICY_LINKS.map((l) => (
              <li key={l.href}>
                <Link
                  href={l.href}
                  className="text-on-surface-variant transition-colors hover:text-primary"
                >
                  {l.label}
                </Link>
              </li>
            ))}
          </ul>
        </div>

        <div>
          <h4 className="mb-6 font-headline font-bold text-on-surface">
            Liên hệ
          </h4>
          <ul className="space-y-4 text-sm text-on-surface-variant">
            {settings.contact.phone ? (
              <li className="flex gap-3">
                <span className="material-symbols-outlined text-base text-primary">
                  call
                </span>
                <a href={`tel:${settings.contact.phone}`}>{settings.contact.phone}</a>
              </li>
            ) : null}
            {settings.contact.support_email ? (
              <li className="flex gap-3">
                <span className="material-symbols-outlined text-base text-primary">
                  mail
                </span>
                <a href={`mailto:${settings.contact.support_email}`}>
                  {settings.contact.support_email}
                </a>
              </li>
            ) : null}
          </ul>
        </div>
      </div>

      <div className="mx-auto max-w-7xl px-6 py-8 text-center text-xs text-on-surface-variant md:px-8">
        © {new Date().getFullYear()} {settings.site_name} — The Digital Craftsman.
      </div>
    </footer>
  );
}
