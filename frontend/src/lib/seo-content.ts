/**
 * Hệ nội dung + KEYWORD cho SEO nghề × khu vực.
 * - NGHE_SEO: nội dung độc nhất theo nghề (chống doorway) + FAQ (chèn {khuvuc}).
 * - keywordsFor: sinh "keyword universe" cho một cặp nghề×khu vực (thay công cụ ngoài
 *   ở giai đoạn đầu — pSEO deterministic). Dùng cho title/H1/meta + tham chiếu.
 */

export interface NgheSeo {
  intro: string;              // 1 đoạn giới thiệu nghề (unique/nghề)
  faq: { q: string; a: string }[]; // {khuvuc} sẽ được thay bằng tên quận
}

/** Cụm ý định gắn vào từ khoá local. */
const INTENT_MODIFIERS = ['', ' giá rẻ', ' uy tín', ' tại nhà', ' gần đây', ' 24/7'];

/**
 * Sinh danh sách từ khoá mục tiêu cho một cặp nghề × khu vực.
 * VD: ("Thợ Điện", "Cầu Giấy") → ["thợ điện cầu giấy", "thợ điện cầu giấy giá rẻ", ...]
 */
export function keywordsFor(categoryName: string, districtLabel: string): string[] {
  const base = `${categoryName} ${districtLabel}`.toLowerCase();
  const kws = INTENT_MODIFIERS.map((m) => base + m);
  // biến thể "tại": "thợ điện tại cầu giấy"
  kws.push(`${categoryName.toLowerCase()} tại ${districtLabel.toLowerCase()}`);
  return Array.from(new Set(kws));
}

const DEFAULT_FAQ = (khuvuc: string): { q: string; a: string }[] => [
  { q: `Thuê thợ tại ${khuvuc} có mất phí trung gian không?`, a: 'Không. Bạn làm việc và thanh toán trực tiếp với thợ, Doitay không thu phí hoa hồng trên công lao động của thợ.' },
  { q: `Thợ trên Doitay có được kiểm duyệt không?`, a: 'Có. Mỗi hồ sơ thợ được kiểm duyệt trước khi lên chợ; bạn xem được ảnh công việc thật và đánh giá của khách trước đó.' },
  { q: `Đặt lịch ở ${khuvuc} nhanh không?`, a: 'Bạn tạo yêu cầu miễn phí, hệ thống gợi ý thợ phù hợp quanh khu vực và thông báo để thợ nhận việc nhanh.' },
];

export const NGHE_SEO: Record<string, NgheSeo> = {
  'tho-dien': {
    intro: 'Thợ điện dân dụng nhận sửa chập cháy, mất điện, lắp đặt đèn – ổ cắm – aptomat, đi dây điện nổi/âm tường và sửa bình nóng lạnh. Đây là hạng mục cần thợ có tay nghề và an toàn, nên chọn thợ đã được kiểm duyệt và có ảnh công việc thật.',
    faq: (k => [
      { q: `Thợ điện ${k} giá bao nhiêu?`, a: 'Giá tuỳ hạng mục: sửa chập điện thường 100.000–300.000đ, lắp đèn/ổ cắm 150.000–250.000đ. Thợ báo giá trước khi làm, minh bạch.' },
      { q: `Thợ điện ${k} có làm buổi tối / cuối tuần không?`, a: 'Nhiều thợ nhận việc ngoài giờ và cuối tuần. Bạn ghi rõ thời gian mong muốn khi tạo yêu cầu để thợ phù hợp nhận.' },
    ])('{khuvuc}'),
  },
  'tho-nuoc': {
    intro: 'Thợ nước nhận sửa rò rỉ ống nước, thông tắc cống và bồn cầu, lắp vòi – chậu rửa – thiết bị vệ sinh, lắp máy bơm và đi đường ống mới. Ưu tiên thợ đến nhanh vì sự cố nước thường khẩn cấp.',
    faq: (k => [
      { q: `Thông tắc cống ${k} giá bao nhiêu?`, a: 'Thường 200.000–500.000đ tuỳ mức độ tắc. Thợ khảo sát và báo giá trước khi làm.' },
      { q: `Thợ nước ${k} có đến trong ngày không?`, a: 'Có, sự cố nước thường được ưu tiên xử lý trong ngày. Ghi rõ mức khẩn cấp khi tạo yêu cầu.' },
    ])('{khuvuc}'),
  },
  'tho-dieu-hoa': {
    intro: 'Thợ điều hòa nhận vệ sinh, bơm gas, lắp đặt mới, tháo – di dời và sửa điều hòa không mát. Nên bảo dưỡng định kỳ trước mùa nóng để máy bền và tiết kiệm điện.',
    faq: (k => [
      { q: `Vệ sinh điều hòa ${k} giá bao nhiêu?`, a: 'Vệ sinh thường 150.000–250.000đ/máy; bơm gas tuỳ loại máy. Thợ báo giá trước.' },
      { q: `Lắp điều hòa ${k} có bảo hành không?`, a: 'Nhiều thợ bảo hành công lắp đặt. Bạn hỏi rõ và xem đánh giá của khách trước đó trên hồ sơ thợ.' },
    ])('{khuvuc}'),
  },
};

export function getNgheSeo(ngheSlug: string, khuvuc: string): NgheSeo {
  const base = NGHE_SEO[ngheSlug];
  if (base) {
    return { intro: base.intro, faq: base.faq.map((f) => ({ q: f.q.replace(/\{khuvuc\}/g, khuvuc), a: f.a })) };
  }
  return {
    intro: 'Thợ được kiểm duyệt trên Doitay, có ảnh công việc thật và đánh giá của khách. Bạn tạo yêu cầu miễn phí, làm việc và thanh toán trực tiếp với thợ, không mất phí trung gian.',
    faq: DEFAULT_FAQ(khuvuc),
  };
}
