/**
 * Hệ slug cho trang SEO nghề × khu vực.
 * Nguyên tắc: slug là để hiển thị/URL; khi QUERY thợ luôn dùng chuỗi district
 * THẬT từ /public/service-areas (không tự chế), vì filter khớp chính xác.
 */

/** "Thợ Điện" → "tho-dien"; "Quận Cầu Giấy" → "cau-giay" (bỏ tiền tố Quận/Huyện/TP). */
export function slugifyVi(input: string): string {
  return (input || '')
    .normalize('NFD')
    .replace(/[̀-ͯ]/g, '')
    .replace(/đ/g, 'd')
    .replace(/Đ/g, 'D')
    .toLowerCase()
    .replace(/\b(quan|huyen|thi xa|thanh pho|tp)\b/g, '') // bỏ tiền tố hành chính
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
}

/** Nhãn khu vực gọn để hiển thị: "Quận Cầu Giấy" → "Cầu Giấy". */
export function districtLabel(district: string): string {
  return (district || '').replace(/^(Quận|Huyện|Thị xã|Thành phố)\s+/i, '').trim();
}
