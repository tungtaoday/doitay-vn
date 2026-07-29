'use client';

import { useEffect } from 'react';
import { recordEvent } from '@/lib/track';

/**
 * Đo phễu BẮC ĐẨU ở web (trang hồ sơ thợ):
 *  - mount  → profile_viewed
 *  - click phần tử có [data-track-contact] → contact_clicked (kèm 'kind')
 * Không render gì. Dùng delegated listener để không phải đổi các nút RSC.
 */
export function ProfileAnalytics({ companyId }: { companyId: number }) {
  useEffect(() => {
    recordEvent('profile_viewed', { companyId, surface: 'khach', channel: 'web' });

    const onClick = (e: MouseEvent) => {
      const el = (e.target as HTMLElement | null)?.closest('[data-track-contact]');
      if (el) {
        recordEvent('contact_clicked', {
          companyId,
          surface: 'khach',
          channel: 'web',
          meta: { kind: el.getAttribute('data-track-contact') || 'contact' },
        });
      }
    };
    document.addEventListener('click', onClick, true);
    return () => document.removeEventListener('click', onClick, true);
  }, [companyId]);

  return null;
}
