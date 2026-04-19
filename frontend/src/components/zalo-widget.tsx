'use client';

import { useState } from 'react';
import type { SiteSettings } from '@/lib/api-types';

const SIZE_PX = { small: 48, medium: 56, large: 64 } as const;
const POSITION = {
  'bottom-right': 'bottom-6 right-6',
  'bottom-left': 'bottom-6 left-6',
  'top-right': 'top-24 right-6',
  'top-left': 'top-24 left-6',
} as const;

type ZaloPosition = keyof typeof POSITION;
type ZaloButtonSize = keyof typeof SIZE_PX;

export function ZaloWidget({ settings }: { settings: SiteSettings['zalo'] }) {
  const [open, setOpen] = useState(false);

  if (!settings.enabled || !settings.phone) return null;

  const positionClass =
    POSITION[(settings.position as ZaloPosition) ?? 'bottom-right'] ?? POSITION['bottom-right'];
  const size = SIZE_PX[(settings.button_size as ZaloButtonSize) ?? 'medium'] ?? SIZE_PX.medium;
  const zaloHref = `https://zalo.me/${settings.phone.replace(/\D/g, '')}`;

  return (
    <div className={`fixed ${positionClass} z-40 hidden md:block`}>
      {open ? (
        <div className="mb-3 w-72 rounded-2xl bg-surface p-4 shadow-ambient">
          <div className="mb-3 flex items-center gap-3">
            {settings.avatar ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img
                src={settings.avatar}
                alt={settings.name ?? 'Zalo'}
                className="h-10 w-10 rounded-full object-cover"
              />
            ) : null}
            <div>
              <div className="text-sm font-semibold text-on-surface">
                {settings.name ?? 'Hỗ trợ'}
              </div>
              <div className="text-xs text-emerald-600">● Đang trực tuyến</div>
            </div>
            <button
              type="button"
              onClick={() => setOpen(false)}
              className="ml-auto text-on-surface-variant hover:text-on-surface"
              aria-label="Đóng"
            >
              ✕
            </button>
          </div>
          {settings.message ? (
            <p className="mb-3 rounded-2xl bg-surface-container-low px-3 py-2 text-sm text-on-surface-variant">
              {settings.message}
            </p>
          ) : null}
          <a
            href={zaloHref}
            target="_blank"
            rel="noopener noreferrer"
            className="block w-full rounded-2xl bg-[#0068ff] px-4 py-2.5 text-center text-sm font-semibold text-white"
          >
            Chat ngay qua Zalo
          </a>
        </div>
      ) : null}
      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        style={{ width: size, height: size }}
        className="flex items-center justify-center rounded-full bg-[#0068ff] text-white shadow-ambient transition-transform hover:scale-105"
        aria-label="Mở Zalo"
      >
        <svg viewBox="0 0 24 24" fill="currentColor" className="h-1/2 w-1/2">
          <path d="M12 2C6.48 2 2 6.04 2 11.02c0 2.85 1.49 5.4 3.83 7.07L5 22l3.86-2.04c.99.27 2.04.41 3.14.41 5.52 0 10-4.04 10-9.02S17.52 2 12 2z" />
        </svg>
      </button>
    </div>
  );
}
