/**
 * Cụm cam kết / tín hiệu tin cậy — dùng ở trang chủ và trang chi tiết thợ.
 * Chỉ nêu cam kết CÓ THẬT của doitay (không hứa bảo hành/hoàn tiền nếu nền tảng
 * chưa thực sự cung cấp — tránh quảng cáo sai).
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
      <div className={`flex flex-wrap items-center gap-x-6 gap-y-3 ${className}`}>
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
          className="flex flex-col gap-2 rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15"
        >
          <span className="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10">
            <span
              className="material-symbols-outlined text-[1.375rem] text-primary"
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
