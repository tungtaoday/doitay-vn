/**
 * Cụm cam kết / tín hiệu tin cậy — dùng ở trang chủ và trang chi tiết thợ.
 * Chỉ nêu cam kết CÓ THẬT của doitay (không hứa bảo hành/hoàn tiền nếu nền tảng
 * chưa thực sự cung cấp — tránh quảng cáo sai).
 *
 * Mỗi mục có MÀU RIÊNG + hover (icon đổ đầy màu, thẻ nhấc nhẹ) để tránh đơn điệu.
 */
type Commitment = {
  icon: string;
  title: string;
  desc: string;
  /** class literal cho chip icon + trạng thái hover (để Tailwind purge giữ lại) */
  chip: string;
  hover: string;
  strip: string;
};

const COMMITMENTS: Commitment[] = [
  {
    icon: 'verified_user',
    title: 'Thợ đã kiểm duyệt',
    desc: 'Hồ sơ, tay nghề và đánh giá được xác thực.',
    chip: 'bg-emerald-100 text-emerald-600',
    hover: 'group-hover:bg-emerald-500 group-hover:text-white',
    strip: 'text-emerald-600',
  },
  {
    icon: 'payments',
    title: 'Không phí trung gian',
    desc: 'Làm việc và trả tiền trực tiếp với thợ.',
    chip: 'bg-sky-100 text-sky-600',
    hover: 'group-hover:bg-sky-500 group-hover:text-white',
    strip: 'text-sky-600',
  },
  {
    icon: 'reviews',
    title: 'Đánh giá thật',
    desc: 'Phản hồi từ khách đã dùng dịch vụ.',
    chip: 'bg-amber-100 text-amber-600',
    hover: 'group-hover:bg-amber-500 group-hover:text-white',
    strip: 'text-amber-600',
  },
  {
    icon: 'support_agent',
    title: 'Hỗ trợ tận tâm',
    desc: 'Đồng hành khi bạn cần trợ giúp.',
    chip: 'bg-violet-100 text-violet-600',
    hover: 'group-hover:bg-violet-500 group-hover:text-white',
    strip: 'text-violet-600',
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
              className={`material-symbols-outlined text-[1.25rem] ${c.strip}`}
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
          className="group flex flex-col gap-3 rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15 transition-all duration-300 hover:-translate-y-1 hover:ring-outline-variant/30"
        >
          <span
            className={`flex h-12 w-12 items-center justify-center rounded-xl transition-colors duration-300 ${c.chip} ${c.hover}`}
          >
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
