/**
 * Gợi ý dịch vụ THEO NGÀNH NGHỀ cho bảng giá (form đăng ký + sửa hồ sơ thợ).
 * Khớp tên category bằng keyword-regex (cùng cách tiếp cận category-style.ts) —
 * dữ liệu category nằm trong DB nên không map cứng theo id.
 * Thợ vẫn gõ tự do được (ô input text) — chips chỉ là đường tắt.
 */
const RULES: Array<{ test: RegExp; services: string[] }> = [
  {
    test: /điều\s*hòa|điều\s*hoà|máy\s*lạnh|lạnh\s*trung\s*tâm|vrv|vrf/i,
    services: ['Vệ sinh điều hòa', 'Bơm gas điều hòa', 'Lắp đặt điều hòa mới', 'Tháo / di dời điều hòa', 'Sửa điều hòa không mát', 'Bảo dưỡng định kỳ'],
  },
  {
    test: /nước|ống|thông\s*tắc|bồn\s*cầu|vệ\s*sinh\s*(công|cống)/i,
    services: ['Sửa rò rỉ ống nước', 'Thông tắc cống / bồn cầu', 'Lắp vòi, chậu rửa', 'Lắp bồn cầu, thiết bị vệ sinh', 'Lắp máy bơm nước', 'Đi đường ống mới'],
  },
  {
    test: /sơn|bả|matit/i,
    services: ['Sơn tường trong nhà (m²)', 'Sơn ngoại thất (m²)', 'Bả matit (m²)', 'Xử lý chống thấm, nấm mốc'],
  },
  {
    test: /mộc|gỗ|tủ|nội\s*thất/i,
    services: ['Đóng tủ, kệ theo yêu cầu', 'Sửa cửa gỗ, bản lề', 'Lắp sàn gỗ', 'Đánh vecni / sơn PU'],
  },
  {
    test: /ốp|lát|gạch/i,
    services: ['Ốp lát gạch nền (m²)', 'Ốp tường, nhà vệ sinh (m²)', 'Xử lý gạch bong rộp'],
  },
  {
    test: /hàn|sắt|inox|cơ\s*khí/i,
    services: ['Hàn cửa sắt, lan can', 'Làm khung mái tôn', 'Hàn sửa đồ inox', 'Làm khung sắt theo yêu cầu'],
  },
  {
    test: /xây|trát|bê\s*tông|cải\s*tạo/i,
    services: ['Xây, trát tường', 'Sửa chữa cải tạo nhà', 'Chống thấm tường / sân thượng', 'Lát nền'],
  },
  {
    test: /vệ\s*sinh|dọn|giặt/i,
    services: ['Tổng vệ sinh nhà (gói)', 'Vệ sinh sofa, nệm, rèm', 'Vệ sinh kính, mặt tiền'],
  },
  {
    test: /khóa|khoá/i,
    services: ['Mở khóa cửa', 'Thay ổ khóa', 'Lắp khóa điện tử'],
  },
  {
    // 'điện' đặt SAU 'điện lạnh/điều hòa' — regex rộng nhất để cuối
    test: /điện/i,
    services: ['Sửa chập / mất điện', 'Lắp đèn, ổ cắm, công tắc', 'Lắp quạt trần / quạt hút', 'Đi dây điện nổi / âm tường', 'Lắp tủ điện, aptomat', 'Sửa bình nóng lạnh'],
  },
];

const FALLBACK = ['Sửa chữa vặt trong nhà', 'Lắp đặt thiết bị gia dụng', 'Khoan tường, treo kệ / tranh', 'Bảo trì định kỳ'];

export function getServiceSuggestions(categoryName?: string | null): string[] {
  const name = (categoryName ?? '').trim();
  if (!name) return FALLBACK;
  for (const rule of RULES) {
    if (rule.test.test(name)) return rule.services;
  }
  return FALLBACK;
}
