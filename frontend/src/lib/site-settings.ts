import { cache } from 'react';
import { unstable_cache } from 'next/cache';
import { api } from '@/lib/api';
import type { DetailEnvelope, SiteSettings } from '@/lib/api-types';

/**
 * Fetches admin-controlled site settings from `/api/v1/public/site-settings`.
 *
 * Two layers of caching:
 *   1. `unstable_cache` — Next.js Data Cache, revalidates every 5 min.
 *      Avoids hitting the backend on every single navigation.
 *   2. React `cache()` — dedupes within one RSC render tree so header +
 *      footer + page share the same resolved Promise.
 *
 * Falls back to a hard-coded default if the backend is unreachable so the
 * frontend never crashes during local dev or backend hiccups.
 */
const fetchSettings = unstable_cache(
  async (): Promise<SiteSettings> => {
    try {
      const res = await api<DetailEnvelope<SiteSettings>>('/public/site-settings');
      return res.data;
    } catch {
      return DEFAULT_SETTINGS;
    }
  },
  ['site-settings'],
  { revalidate: 300 },
);

export const getSiteSettings = cache(fetchSettings);

const DEFAULT_SETTINGS: SiteSettings = {
  site_name: 'doitay.vn',
  site_logo: null,
  site_logo_dark: null,
  site_favicon: null,
  colors: { primary: null, secondary: null },
  currency: { text: 'VND', symbol: '₫' },
  features: {
    registration: true,
    maintenance_mode: false,
    email_verify: false,
    sms_verify: false,
    kyc: false,
    multi_language: false,
    force_ssl: false,
  },
  contact: { support_email: null, phone: null },
  social: { google_enabled: false, facebook_enabled: false },
  zalo: {
    enabled: false,
    phone: null,
    name: null,
    avatar: null,
    message: null,
    position: 'bottom-right',
    button_size: 'medium',
    show_mobile: false,
  },
  banner: { heading: null, subheading: null, image: null },
};
