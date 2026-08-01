/**
 * Client-side product event tracking (đo phễu BẮC ĐẨU) + TÁCH KÊNH.
 * Mỗi event tự gắn `src` (nguồn khách): thotot_card / seo / facebook / zalo /
 * tiktok / ctv / truc_tiep / khac — từ UTM (ưu tiên, nhớ trong session) hoặc referrer.
 * Fire-and-forget tới POST /public/events. Không PII, nuốt mọi lỗi.
 */
type TrackEvent =
  | 'profile_viewed'
  | 'contact_clicked'
  | 'booking_started'
  | 'booking_confirmed';

interface TrackOpts {
  companyId?: number;
  surface?: 'tho' | 'khach';
  channel?: 'miniapp' | 'web';
  meta?: Record<string, unknown>;
}

/** Chuẩn hoá utm_source về bộ nhãn kênh thống nhất (Q12). */
function normSrc(u: string): string {
  const s = u.toLowerCase();
  if (s.includes('miniapp') || s.includes('thotot')) return 'thotot_card';
  if (s.includes('facebook') || s === 'fb') return 'facebook';
  if (s.includes('zalo') || s === 'zl') return 'zalo';
  if (s.includes('tiktok') || s === 'tt') return 'tiktok';
  if (s === 'ctv') return 'ctv';
  if (s.includes('google') || s === 'seo') return 'seo';
  return s.slice(0, 24) || 'khac';
}

/** Xác định nguồn phiên: UTM thắng referrer; nhớ suốt session (first-touch). */
function detectSrc(): { src: string; camp?: string } {
  try {
    const qs = new URLSearchParams(window.location.search);
    const utm = qs.get('utm_source');
    const camp = qs.get('utm_campaign') ?? undefined;
    if (utm) {
      const src = normSrc(utm);
      sessionStorage.setItem('dt_src', src);
      if (camp) sessionStorage.setItem('dt_camp', camp.slice(0, 40));
      return { src, camp };
    }
    const saved = sessionStorage.getItem('dt_src');
    if (saved) return { src: saved, camp: sessionStorage.getItem('dt_camp') ?? undefined };

    const ref = document.referrer;
    let src = 'truc_tiep';
    if (ref) {
      if (/google\.|bing\.|coccoc|search\.yahoo|duckduckgo/.test(ref)) src = 'seo';
      else if (/facebook\.|fb\.com|l\.messenger/.test(ref)) src = 'facebook';
      else if (/zalo/.test(ref)) src = 'zalo';
      else if (/tiktok/.test(ref)) src = 'tiktok';
      else if (!ref.includes(window.location.hostname)) src = 'khac';
    }
    sessionStorage.setItem('dt_src', src);
    return { src };
  } catch {
    return { src: 'khac' };
  }
}

export function recordEvent(event: TrackEvent, opts: TrackOpts = {}): void {
  if (typeof window === 'undefined') return;
  const base = process.env.NEXT_PUBLIC_API_URL;
  if (!base) return;
  try {
    const { src, camp } = detectSrc();
    void fetch(`${base}/public/events`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      keepalive: true,
      body: JSON.stringify({
        event,
        surface: opts.surface ?? 'khach',
        channel: opts.channel ?? 'web',
        company_id: opts.companyId,
        meta: { src, ...(camp ? { camp } : {}), ...opts.meta },
      }),
    }).catch(() => {});
  } catch {
    /* ignore */
  }
}
