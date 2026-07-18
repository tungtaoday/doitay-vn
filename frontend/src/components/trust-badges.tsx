/**
 * Cụm cam kết / tín hiệu tin cậy — dùng ở trang chủ và trang chi tiết thợ.
 * Chỉ nêu cam kết CÓ THẬT của doitay (không hứa bảo hành/hoàn tiền nếu nền tảng
 * chưa thực sự cung cấp — tránh quảng cáo sai).
 *
 * Đồng bộ 1 tông TEAL của thương hiệu (không dùng nhiều màu để tránh loạn màu).
 * Sự sinh động đến từ hiệu ứng hover (icon đổ đầy màu, thẻ nhấc nhẹ).
 */
const COMMITMENTS = [
  {
    icon: 'verified_user',
    title: 'Thợ đã kiểm duyệt',
    desc: 'Hồ sơ, tay nghề và đánh giá được xác thực.',
  },
  {
    icon: 'payments',
    title: 'Không phí trung gian',
    desc: 'Làm việc và trả tiền trực tiếp với thợ.',
  },
  {
    icon: 'reviews',
    title: 'Đánh giá thật',
    desc: 'Phản hồi từ khách đã dùng dịch vụ.',
  },
  {
    icon: 'support_agent',
    title: 'Hỗ trợ tận tâm',
    desc: 'Đồng hành khi bạn cần trợ giúp.',
  },
];

export function TrustBadges({
  variant = 'card',
  className = '',
}: {
  /** 'card' = lưới thẻ đầy đủ; 'strip' = dải gọn 1 hàng (chip) */
  variant?: 'card' | 'strip';
  className?: string;
}) {
  if (variant === 'strip') {
    return (
      <div className={`flex flex-wrap items-center gap-x-7 gap-y-3 ${className}`}>
        {COMMITMENTS.map((c) => (
          <span key={c.title} className="flex items-center gap-2 text-sm font-semibold text-on-surface">
            <span
              className="material-symbols-outlined text-[1.25rem] text-primary"
              style={{ fontVariationSettings: "'FILL' 1" }}
            >
              {c.icon}
            </span>
            {c.title}
          </span>
        ))}
      </div>
    );
  }

  return (
    <div className={`grid grid-cols-2 gap-4 md:grid-cols-4 ${className}`}>
      {COMMITMENTS.map((c) => (
        <div
          key={c.title}
          className="group flex flex-col gap-3 rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15 transition-all duration-300 hover:-translate-y-1 hover:ring-primary/25"
        >
          <span className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary transition-colors duration-300 group-hover:bg-primary group-hover:text-white">
            <span
              className="material-symbols-outlined text-[1.5rem]"
              style={{ fontVariationSettings: "'FILL' 1" }}
            >
              {c.icon}
            </span>
          </span>
          <p className="font-headline text-[0.9375rem] font-bold leading-tight text-on-surface">
            {c.title}
          </p>
          <p className="text-xs leading-snug text-on-surface-variant">{c.desc}</p>
        </div>
      ))}
    </div>
  );
}
