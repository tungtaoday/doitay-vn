/**
 * Badge trạng thái lịch hẹn — dùng chung cho khách + thợ (list + detail).
 * Màu = token design system (không dùng palette raw yellow-100/blue-100...),
 * kèm ICON để trạng thái không chỉ truyền đạt bằng màu (WCAG 1.4.1).
 */
const STATUS_STYLE: Record<string, { cls: string; icon: string }> = {
  pending:   { cls: 'bg-tertiary-container text-on-tertiary-container', icon: 'schedule' },
  confirmed: { cls: 'bg-secondary-container text-on-secondary-container', icon: 'event_available' },
  completed: { cls: 'bg-primary-container text-on-primary-container', icon: 'task_alt' },
  canceled:  { cls: 'bg-error-container text-on-error-container', icon: 'cancel' },
};

const FALLBACK = { cls: 'bg-surface-container-highest text-on-surface', icon: 'help' };

// Nhãn dự phòng khi API trả status_label rỗng (dữ liệu cũ trước khi fix enum).
const LABEL_FALLBACK: Record<string, string> = {
  pending: 'Chờ xác nhận',
  confirmed: 'Đã xác nhận',
  completed: 'Hoàn thành',
  canceled: 'Đã huỷ',
  cancelled: 'Đã huỷ',
};

export function AppointmentStatusBadge({
  status,
  label,
  size = 'sm',
}: {
  status: string;
  label: string;
  size?: 'sm' | 'md';
}) {
  const s = STATUS_STYLE[status] ?? STATUS_STYLE[status === 'cancelled' ? 'canceled' : ''] ?? FALLBACK;
  const text = label || LABEL_FALLBACK[status] || 'Không rõ';
  return (
    <span
      className={`inline-flex items-center gap-1.5 rounded-full font-bold ${s.cls} ${
        size === 'md' ? 'px-4 py-1.5 text-sm' : 'px-3 py-1 text-xs'
      }`}
    >
      <span
        className={`material-symbols-outlined ${size === 'md' ? 'text-[1.125rem]' : 'text-[1rem]'}`}
        style={{ fontVariationSettings: "'FILL' 1" }}
      >
        {s.icon}
      </span>
      {text}
    </span>
  );
}
