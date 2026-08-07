import Link from 'next/link';
import type { Route } from 'next';

/**
 * Thanh điều hướng dùng chung cho MỌI mặt quản trị.
 *
 * Trước đây các trang nằm rời: /quan-tri (số tháng, CTV), /sale (hồ sơ CTV nhập),
 * /sale/duyet (hàng chờ), /sale/hieu-suat (hiệu suất thợ) — muốn đi từ chỗ này sang
 * chỗ kia phải gõ tay địa chỉ. Đặt cùng một thanh ở đầu mọi trang để đi lại được.
 */
const MUC: { href: string; nhan: string; mo_ta: string }[] = [
  { href: '/quan-tri', nhan: 'Điều hành', mo_ta: 'Số tháng · chi phí · CTV' },
  { href: '/sale/hieu-suat', nhan: 'Hiệu suất thợ', mo_ta: 'Ai kẹt ở đâu' },
  { href: '/sale/duyet', nhan: 'Hàng chờ duyệt', mo_ta: 'Nghiệm thu hồ sơ' },
  { href: '/sale', nhan: 'Hồ sơ CTV nhập', mo_ta: 'Danh sách + link gửi thợ' },
];

export function DieuHuongQuanTri({ dang_o }: { dang_o: string }) {
  return (
    <nav className="mb-6 flex flex-wrap items-stretch gap-2">
      {MUC.map((m) => {
        const active = m.href === dang_o;
        return (
          <Link
            key={m.href}
            href={m.href as Route}
            className={`rounded-xl px-4 py-2.5 transition-colors ${
              active
                ? 'bg-on-surface text-surface'
                : 'bg-surface-container text-on-surface hover:bg-surface-container-high'
            }`}
          >
            <span className="block text-sm font-bold">{m.nhan}</span>
            <span className={`block text-[11px] ${active ? 'text-surface/70' : 'text-outline'}`}>
              {m.mo_ta}
            </span>
          </Link>
        );
      })}

      {/* Mảng hồ sơ gốc (user, công ty, danh mục, cài đặt, ví, giao dịch) vẫn nằm
          ở admin Laravel — không bê sang đây, chỉ mở đường sang cho khỏi gõ tay. */}
      <a
        href="https://doitay.vn/admin"
        target="_blank"
        rel="noreferrer"
        className="rounded-xl bg-surface-container-low px-4 py-2.5 text-on-surface-variant transition-colors hover:bg-surface-container"
      >
        <span className="block text-sm font-bold">Admin hệ thống ↗</span>
        <span className="block text-[11px] text-outline">User · công ty · ví · cài đặt</span>
      </a>
    </nav>
  );
}
