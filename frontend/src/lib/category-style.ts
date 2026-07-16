// Icon + màu SẮC theo NGÀNH NGHỀ cho danh mục thợ.
//
// Match theo TỪ KHÓA trong tên (giống getPlaceholderImage) để bền với cách đặt
// tên ở DB — không phụ thuộc khớp chính xác. Trước đây map khớp-đúng-tên chỉ có
// 4 mục ('Thợ Điện', 'Thợ Nước', 'Điện lạnh', 'Sửa chữa') nên 8/10 nghề thật
// rơi về icon cờ-lê chung và mọi nghề cùng một màu.
//
// Class Tailwind là literal (không ghép chuỗi động) để purge giữ lại.

export interface CategoryStyle {
  /** Tên icon Material Symbols Outlined */
  icon: string;
  /** Nền chip + màu icon — dùng cho icon đặt trong hình tròn nền nhạt */
  chip: string;
  /** Chỉ màu icon — dùng khi icon đặt trên nền khác (vd thẻ bento lớn) */
  fg: string;
}

// Thứ tự QUAN TRỌNG: luật hẹp trước luật rộng.
// 'điều hòa' / 'điện lạnh' phải đứng TRƯỚC luật 'điện' chung.
const RULES: Array<{ test: RegExp; style: CategoryStyle }> = [
  { test: /điều\s*hòa|điều\s*hoà|máy\s*lạnh|điện\s*lạnh|vrv|vrf/, style: { icon: 'ac_unit',                chip: 'bg-cyan-100 text-cyan-600',       fg: 'text-cyan-600' } },
  { test: /nước|đường\s*ống|thông\s*tắc|bồn|cấp\s*thoát/,          style: { icon: 'water_drop',            chip: 'bg-blue-100 text-blue-600',       fg: 'text-blue-600' } },
  { test: /hàn|cơ\s*khí|sắt\s*thép/,                               style: { icon: 'local_fire_department', chip: 'bg-red-100 text-red-600',         fg: 'text-red-600' } },
  { test: /mộc|đồ\s*gỗ|nội\s*thất/,                                style: { icon: 'chair',                 chip: 'bg-[#EFE1CE] text-[#96602B]',     fg: 'text-[#96602B]' } },
  { test: /ốp\s*lát|lát\s*gạch|ốp\b|gạch/,                         style: { icon: 'grid_view',             chip: 'bg-teal-100 text-teal-600',       fg: 'text-teal-600' } },
  { test: /sơn|bả\s*matit|matit/,                                  style: { icon: 'format_paint',          chip: 'bg-violet-100 text-violet-600',   fg: 'text-violet-600' } },
  { test: /vệ\s*sinh|dọn\s*dẹp|lau\s*dọn/,                         style: { icon: 'cleaning_services',     chip: 'bg-emerald-100 text-emerald-600', fg: 'text-emerald-600' } },
  { test: /xây|thợ\s*hồ|trát/,                                     style: { icon: 'construction',          chip: 'bg-orange-100 text-orange-600',   fg: 'text-orange-600' } },
  // Luật rộng nhất — mọi nghề còn lại có chữ 'điện' (Thợ Điện, điện dân dụng...).
  { test: /điện/,                                                  style: { icon: 'bolt',                  chip: 'bg-amber-100 text-amber-600',     fg: 'text-amber-600' } },
];

// Sửa chữa tổng hợp / không rõ ngành → cờ-lê, tông trung tính.
const FALLBACK: CategoryStyle = { icon: 'handyman', chip: 'bg-slate-200 text-slate-700', fg: 'text-slate-700' };

export function categoryStyle(name?: string | null): CategoryStyle {
  const n = (name ?? '').toLowerCase();
  for (const rule of RULES) {
    if (rule.test.test(n)) return rule.style;
  }
  return FALLBACK;
}
