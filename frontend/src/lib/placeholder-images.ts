// Profession-specific placeholder images (Google AI generated, from design stitch).
// Theo yêu cầu chủ dự án: dùng ảnh đại diện THEO NGÀNH NGHỀ cho thợ seed/chưa có ảnh
// (không dùng avatar chữ cái).
// Ảnh nội bộ (sinh bằng Gemini) — thợ Việt đúng nghề, đồng phục navy-sky, đồ nghề.
const PLACEHOLDER_IMAGES: Record<string, string> = {
  electric: '/professions/electric.jpg',
  plumbing: '/professions/plumbing.jpg',
  cooling:  '/professions/cooling.jpg',
  general:  '/professions/general.jpg',
};

/** Returns true for seed/stock images that should be replaced with profession-specific placeholders */
export function isSeedImage(url?: string | null): boolean {
  if (!url) return true;
  return url.includes('unsplash.com');
}

/**
 * Trả ảnh đại diện theo ngành nghề. `seedName` giữ trong chữ ký để tương thích
 * với các nơi gọi (hiện không dùng — ảnh chọn theo category).
 */
export function getPlaceholderImage(
  categoryName?: string | null,
  seedName?: string | null, // eslint-disable-line @typescript-eslint/no-unused-vars
): string {
  const name = (categoryName ?? '').toLowerCase();
  if (/nước|ống|thông|bồn/.test(name)) return PLACEHOLDER_IMAGES.plumbing;
  if (/máy lạnh|điều hòa|điều hoà|lạnh trung tâm|vrv|vrf/.test(name)) return PLACEHOLDER_IMAGES.cooling;
  if (/điện/.test(name)) return PLACEHOLDER_IMAGES.electric;
  return PLACEHOLDER_IMAGES.general;
}
