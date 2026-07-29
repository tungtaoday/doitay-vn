/**
 * Client-side product event tracking (đo phễu BẮC ĐẨU).
 * Fire-and-forget tới POST /public/events. Không PII, nuốt mọi lỗi.
 * Chỉ chạy ở client (component 'use client').
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

export function recordEvent(event: TrackEvent, opts: TrackOpts = {}): void {
  if (typeof window === 'undefined') return;
  const base = process.env.NEXT_PUBLIC_API_URL;
  if (!base) return;
  try {
    void fetch(`${base}/public/events`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      keepalive: true,
      body: JSON.stringify({
        event,
        surface: opts.surface ?? 'khach',
        channel: opts.channel ?? 'web',
        company_id: opts.companyId,
        meta: opts.meta,
      }),
    }).catch(() => {});
  } catch {
    /* ignore */
  }
}
