/**
 * Placeholder trung thực cho ảnh thợ seed/stock — KHÔNG dùng ảnh mặt người AI.
 *
 * Trước đây: 4 ảnh AI theo nghề → cùng 1 khuôn mặt lặp lại hàng chục lần trên
 * trang danh sách, khách nhận ra chợ ảo ngay. Giờ: sinh SVG data-URI (chữ cái
 * đầu tên + gradient theo brand) — mỗi thợ một hình riêng, trung thực, on-brand.
 */

// Cặp màu gradient lấy từ design system (primary/secondary + biến thể tonal).
const PALETTES: Array<[string, string]> = [
  ['#48BBE2', '#2E7DA6'], // sky (primary)
  ['#1E8849', '#0F5E30'], // green
  ['#102F4B', '#2C5578'], // navy (secondary)
  ['#5B8DB8', '#33618C'], // steel blue
  ['#3AA6A0', '#1F6E69'], // teal
];

function hashString(s: string): number {
  let h = 0;
  for (let i = 0; i < s.length; i++) h = (h * 31 + s.charCodeAt(i)) | 0;
  return Math.abs(h);
}

/** True với ảnh seed/stock cần thay bằng placeholder (unsplash, ảnh AI cũ). */
export function isSeedImage(url?: string | null): boolean {
  if (!url) return true;
  return url.includes('unsplash.com') || url.includes('lh3.googleusercontent.com');
}

/**
 * Trả về SVG data-URI: chữ cái đầu của tên thợ trên nền gradient.
 * `seedName` (tên thợ) quyết định màu + chữ cái → mỗi thợ một hình khác nhau.
 */
export function getPlaceholderImage(
  categoryName?: string | null,
  seedName?: string | null,
): string {
  const base = (seedName ?? categoryName ?? 'Thợ').trim();
  const initial = (base.charAt(0) || 'T').toUpperCase();
  const [c1, c2] = PALETTES[hashString(base) % PALETTES.length];

  const svg =
    `<svg xmlns="http://www.w3.org/2000/svg" width="640" height="400" viewBox="0 0 640 400">` +
    `<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">` +
    `<stop offset="0" stop-color="${c1}"/><stop offset="1" stop-color="${c2}"/>` +
    `</linearGradient></defs>` +
    `<rect width="640" height="400" fill="url(#g)"/>` +
    `<circle cx="540" cy="60" r="180" fill="#ffffff" opacity="0.08"/>` +
    `<circle cx="90" cy="360" r="150" fill="#ffffff" opacity="0.06"/>` +
    `<text x="50%" y="53%" font-family="Arial, Helvetica, sans-serif" font-size="180" ` +
    `font-weight="bold" fill="#ffffff" opacity="0.95" text-anchor="middle" ` +
    `dominant-baseline="middle">${initial}</text>` +
    `</svg>`;

  return `data:image/svg+xml;utf8,${encodeURIComponent(svg)}`;
}
