/**
 * Money + date formatting helpers. Money is VND integer — no decimals.
 */
const vndFormatter = new Intl.NumberFormat('vi-VN', {
  style: 'currency',
  currency: 'VND',
  maximumFractionDigits: 0,
});

export function formatVND(amount: number | string | null | undefined): string {
  if (amount === null || amount === undefined || amount === '') return '—';
  const n = typeof amount === 'string' ? Number(amount) : amount;
  if (Number.isNaN(n)) return '—';
  return vndFormatter.format(n);
}

export function formatSignedVND(amount: number): string {
  const sign = amount >= 0 ? '+' : '−';
  return `${sign}${vndFormatter.format(Math.abs(amount))}`;
}

export function formatDateTime(iso: string | null | undefined): string {
  if (!iso) return '—';
  try {
    return new Date(iso).toLocaleString('vi-VN', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
    });
  } catch {
    return '—';
  }
}

/**
 * Định dạng ô giá dịch vụ khi gõ: số → chèn dấu chấm nghìn ("150000"→"150.000");
 * nhưng NẾU có chữ cái (vd "Liên hệ", "Thỏa thuận") thì GIỮ NGUYÊN để thợ vẫn ghi chữ được.
 */
export function formatPriceInput(v: string): string {
  const raw = v ?? '';
  if (/[^\d.\s]/.test(raw)) return raw; // có chữ → để nguyên
  const digits = raw.replace(/\D/g, '');
  return digits ? Number(digits).toLocaleString('vi-VN') : '';
}
