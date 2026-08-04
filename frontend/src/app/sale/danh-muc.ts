/**
 * Danh mục dùng CHUNG cho cổng CTV — phải khớp với app Hồ Sơ Thợ và web doitay.vn.
 *
 * Trước đây CTV gõ tay nghề và khu vực, còn thợ chọn từ danh sách trong app → hai bên
 * ghi khác nhau ("Thợ điện" / "Thợ Điện" / "điện"), khớp hồ sơ rất khó và chuẩn hoá
 * dữ liệu bị hỏng ngay từ khâu nhập.
 *
 * Nguồn: Tool_cv/src/types/index.ts (JOB_TITLES, HANOI_DISTRICTS, CITIES).
 */

export const NGHE = [
  'Thợ Điện',
  'Thợ Nước',
  'Thợ Xây Dựng',
  'Thợ Sơn',
  'Thợ Mộc',
  'Thợ Điều Hòa',
  'Thợ Ốp Lát',
  'Thợ Hàn',
  'Thợ Vệ Sinh',
  'Thợ Sửa Chữa Tổng Hợp',
] as const;

export const TINH_THANH = [
  'Hà Nội',
  'TP. Hồ Chí Minh',
  'Đà Nẵng',
  'Hải Phòng',
  'Cần Thơ',
  'Bình Dương',
  'Đồng Nai',
  'Khánh Hòa',
  'Quảng Ninh',
] as const;

export const QUAN_HA_NOI = [
  'Ba Đình',
  'Hoàn Kiếm',
  'Hai Bà Trưng',
  'Đống Đa',
  'Cầu Giấy',
  'Thanh Xuân',
  'Hoàng Mai',
  'Long Biên',
  'Tây Hồ',
  'Bắc Từ Liêm',
  'Nam Từ Liêm',
  'Hà Đông',
] as const;

/** "Cầu Giấy" + "Hà Nội" → "Cầu Giấy, Hà Nội" (dạng backend tách quận/tỉnh). */
export function ghepKhuVuc(quan: string, tinh: string): string {
  return [quan.trim(), tinh.trim()].filter(Boolean).join(', ');
}
