/**
 * Hiển thị GIÁ DỊCH VỤ của thợ.
 *
 * Thợ nhập giá tự do trong Mini App, phần lớn là KHOẢNG hoặc chữ:
 * "350k - 900k", "200.000 - 500.000", "Thoả thuận", "Tuỳ hiện trạng".
 * Code cũ làm `Number(price).toLocaleString()` nên mọi giá dạng chữ đều ra
 * **"NaN đ"** ngay trên hồ sơ khách đang xem — bắt được khi dựng video giới
 * thiệu, ảnh chụp màn hồ sơ thật hiện "NaN đ" ở cả hai dòng dịch vụ.
 *
 * Quy tắc: là số thì định dạng cho đẹp; không phải số thì in nguyên văn thợ
 * đã gõ; trống thì "Liên hệ". Không bao giờ được ra NaN.
 */
export function hienGia(gia: unknown): string {
  if (gia === null || gia === undefined) return 'Liên hệ';

  const raw = String(gia).trim();
  if (!raw) return 'Liên hệ';

  // Chỉ coi là số khi TOÀN BỘ chuỗi là số (cho phép dấu phẩy/chấm phân cách).
  const thuanSo = /^[\d.,\s]+$/.test(raw);
  if (thuanSo) {
    const so = Number(raw.replace(/[.,\s]/g, ''));
    if (Number.isFinite(so)) {
      // Giá 0 nghĩa là thợ chưa đặt giá, hiện "0" trơ ra thì khách tưởng miễn phí.
      return so > 0 ? `${so.toLocaleString('vi-VN')} đ` : 'Liên hệ';
    }
  }

  return raw;
}
