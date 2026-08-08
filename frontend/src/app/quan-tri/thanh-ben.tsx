'use client';

import Link from 'next/link';
import type { Route } from 'next';
import { usePathname } from 'next/navigation';

/**
 * Thanh bên của trung tâm quản trị — MỘT chỗ để đi tới mọi việc.
 *
 * Thứ tự nhóm đi theo nhịp làm việc thật: sáng mở "Hằng ngày", tuần xem
 * "Theo dõi", đụng tiền thì vào "Tiền", còn "Dữ liệu" là tra cứu khi cần.
 * Mảng nào của admin Blade cũ chưa kéo về thì để nguyên link ở cuối, không
 * giấu đi — giấu là lúc cần lại không biết tìm ở đâu.
 */
const NHOM: { ten: string; muc: { href: string; nhan: string; mo_ta?: string }[] }[] = [
  {
    ten: 'Hằng ngày',
    muc: [
      { href: '/quan-tri/hom-nay', nhan: 'Việc hôm nay', mo_ta: 'Gọi ai, việc nào quá hạn' },
      { href: '/sale/duyet', nhan: 'Hàng chờ duyệt', mo_ta: 'Nghiệm thu hồ sơ thợ' },
      { href: '/quan-tri/nap-tien', nhan: 'Lệnh nạp tiền', mo_ta: 'Duyệt tiền vào ví thợ' },
      { href: '/quan-tri/lich-hen', nhan: 'Lịch hẹn', mo_ta: 'Chốt kết quả từng việc' },
    ],
  },
  {
    ten: 'Theo dõi',
    muc: [
      { href: '/quan-tri', nhan: 'Điều hành', mo_ta: 'Phễu · chi phí · CTV' },
      { href: '/quan-tri/khach-hang', nhan: 'Khách hàng', mo_ta: 'Phía cầu' },
      { href: '/sale/hieu-suat', nhan: 'Hiệu suất thợ', mo_ta: 'Phía cung' },
      { href: '/quan-tri/diem-cham', nhan: 'Điểm chạm', mo_ta: 'Hành động · kênh nguồn' },
    ],
  },
  {
    ten: 'Tiền',
    muc: [
      { href: '/quan-tri/vi-tho', nhan: 'Ví thợ & giao dịch' },
      { href: '/quan-tri/ctv', nhan: 'Cộng tác viên', mo_ta: 'Thêm · ngưng · trả hoa hồng' },
    ],
  },
  {
    ten: 'Dữ liệu',
    muc: [
      { href: '/quan-tri/nguoi-dung', nhan: 'Người dùng', mo_ta: 'Tìm · khoá · mở' },
      { href: '/quan-tri/danh-gia', nhan: 'Đánh giá', mo_ta: 'Soi điểm thấp · gỡ bậy' },
      { href: '/quan-tri/danh-muc', nhan: 'Danh mục nghề', mo_ta: 'Thêm · sửa · ẩn hiện' },
      { href: '/sale', nhan: 'Hồ sơ CTV nhập' },
    ],
  },
  {
    ten: 'Hệ thống',
    muc: [
      { href: '/quan-tri/so-tay', nhan: 'Sổ tay vận hành', mo_ta: '12 quyển + phụ lục' },
      { href: '/quan-tri/cai-dat', nhan: 'Cài đặt', mo_ta: 'Tên site · nút Zalo · bảo trì' },
    ],
  },
];

export function ThanhBen() {
  const path = usePathname() || '';

  return (
    <nav className="w-full shrink-0 md:w-56">
      <div className="rounded-2xl bg-surface-container-low p-3 md:sticky md:top-4">
        {NHOM.map((n) => (
          <div key={n.ten} className="mb-3 last:mb-0">
            <p className="px-2 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-outline">
              {n.ten}
            </p>
            <ul className="space-y-0.5">
              {n.muc.map((m) => {
                const active = path === m.href;
                return (
                  <li key={`${n.ten}-${m.href}-${m.nhan}`}>
                    <Link
                      href={m.href as Route}
                      className={`block rounded-xl px-3 py-2 transition-colors ${
                        active
                          ? 'bg-on-surface text-surface'
                          : 'text-on-surface hover:bg-surface-container'
                      }`}
                    >
                      <span className="block text-sm font-semibold">{m.nhan}</span>
                      {m.mo_ta ? (
                        <span
                          className={`block text-[11px] ${active ? 'text-surface/70' : 'text-outline'}`}
                        >
                          {m.mo_ta}
                        </span>
                      ) : null}
                    </Link>
                  </li>
                );
              })}
            </ul>
          </div>
        ))}

        <a
          href="https://doitay.vn/admin"
          target="_blank"
          rel="noreferrer"
          className="mt-2 block rounded-xl bg-surface-container px-3 py-2 text-on-surface-variant transition-colors hover:bg-surface-container-high"
        >
          <span className="block text-sm font-semibold">Admin cũ ↗</span>
          <span className="block text-[11px] text-outline">Mảng chưa kéo về</span>
        </a>
      </div>
    </nav>
  );
}
